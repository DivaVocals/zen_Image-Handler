<?php
// -----
// An observer-class to enable the "Fual Slimbox" plugin to operate with the notification updates in the
// main_product_image and additional_images processing, provided by "Image Handler" v5.0.0 and later.
//
// Copyright (c) 2017-2019 Vinos de Frutas Tropicales
//
class FualSlimboxObserver extends base 
{
    public function __construct() 
    {
        if (function_exists('zen_lightbox') && ((defined('ZEN_LIGHTBOX_STATUS') && ZEN_LIGHTBOX_STATUS == 'true') || (defined('FUAL_SLIMBOX') && FUAL_SLIMBOX == 'true'))) {
            $this->attach(
                $this,
                array(
                    //- From /includes/modules/additional_images.php
                    'NOTIFY_MODULES_ADDITIONAL_IMAGES_SCRIPT_LINK',
                )
            );
        }
    }

    public function update(&$class, $eventID, $p1, &$p2, &$p3, &$p4, &$p5, &$p6, &$p7, &$p8, &$p9) 
    {
        switch ($eventID) {
            // -----
            // This notifier gives notice that an additional image's script link is requested.  A monitoring observer sets
            // the $p2 value to boolean true if it has provided an alternate form of that link; otherwise, the base code will
            // create that value.
            //
            // $p1 ... (r/o) ... An associative array, containing the 'flag_display_large', 'products_name', 'products_image_large' and 'thumb_slashes' values.
            // $p2 ... (r/w) ... A reference to the $script_link value, set initially to boolean false; if an observer modifies that value, the
            //                     the default module's processing is bypassed.
            //
            case 'NOTIFY_MODULES_ADDITIONAL_IMAGES_SCRIPT_LINK':
                $thumb_slashes = $p1['thumb_slashes'];
                if (!$p1['flag_display_large']) {
                    $large_image_link = $thumb_slashes;
                } else {
                    $rel = (!defined('ZEN_LIGHTBOX_GALLERY_MODE') || ZEN_LIGHTBOX_GALLERY_MODE == 'true') ? 'lightbox[gallery]' : 'lightbox';
                    $products_name = addslashes($p1['products_name']);
                    $products_image_large = $p1['products_image_large'];
                    
                    // -----
                    // The constants LARGE_IMAGE_WIDTH/HEIGHT are supplied by neither Zen Cart nor Image Handler -- they're a
                    // LightBox 'legacy', I believe.  In any case, need to deal with the condition where those constants
                    // haven't been defined previously, using the IH defaults as fall-back values.
                    //
                    if (!defined('LARGE_IMAGE_WIDTH')) {
                        define('LARGE_IMAGE_WIDTH', (defined('LARGE_IMAGE_MAX_WIDTH')) ? LARGE_IMAGE_MAX_WIDTH : 750);
                    }
                    if (!defined('LARGE_IMAGE_HEIGHT')) {
                        define('LARGE_IMAGE_HEIGHT', (defined('LARGE_IMAGE_MAX_HEIGHT')) ? LARGE_IMAGE_MAX_HEIGHT : 550);
                    }
                    
                    $image_link = zen_lightbox($products_image_large, $products_name, LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT);
                    $large_image_link = '<a href="' . $image_link . '" rel="' . $rel . '" title="' . $products_name . '">' . $thumb_slashes . '<br />' . TEXT_CLICK_TO_ENLARGE . '</a>';
                }
                $p2 = '<script type="text/javascript"><!--' . "\n" . 'document.write(\'' . $large_image_link . '\');' . "\n" . '//--></script>';
                break;

            default:
                break;
        }
    }
}
