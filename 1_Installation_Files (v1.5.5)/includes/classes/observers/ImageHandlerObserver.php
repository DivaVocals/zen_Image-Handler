<?php
// -----
// Part of the "Image Handler" plugin for Zen Cart 1.5.5b and later.
// Copyright (c) 2017 Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

class ImageHandlerObserver extends base 
{
    public function __construct() 
    {
        if (defined('IH_RESIZE') && IH_RESIZE == 'yes') {
            $this->attach(
                $this,
                array(
                    //- From /includes/modules/main_product_image.php
                    'NOTIFY_MODULES_MAIN_PRODUCT_IMAGE_FILENAME',
                    
                    //- From /includes/modules/additional_images.php
                    'NOTIFY_MODULES_ADDITIONAL_IMAGES_GET_LARGE',
                    'NOTIFY_MODULES_ADDITIONAL_IMAGES_THUMB_SLASHES',
                    
                    //- From /includes/pages/popup_image/header_php.php
                    'NOTIFY_HEADER_END_POPUP_IMAGES',
                )
            );
        }
    }
  
    public function update(&$class, $eventID, $p1, &$p2, &$p3, &$p4, &$p5, &$p6) 
    {
        switch ($eventID) {
            // -----
            // This notifier lets an image-handling observer know that it's time to determine the image information,
            // providing the following parameters:
            //
            // $p1 ... (r/o) ... A copy of the $products_image value
            // $p2 ... (r/w) ... A boolean value, set by the observer to true if the image has been handled.
            // $p3 ... (r/w) ... A reference to the $products_image_extension value
            // $p4 ... (r/w) ... A reference to the $products_image_base value
            // $p5 ... (r/w) ... A reference to the medium product-image-name
            // $p6 ... (r/w) ... A reference to the large product-image-name.
            //
            // If the observer has set the $product_image_handled flag to true, it's indicated that any of the
            // other values have been updated for separate handling.
            //
            case 'NOTIFY_MODULES_MAIN_PRODUCT_IMAGE_FILENAME':
                $products_image = $p1;
                $products_image_extension = $p3;
                $p4 = $products_image_base = preg_replace('/' . $products_image_extension . '$/', '', $products_image);
                $p5 = DIR_WS_IMAGES . 'medium/' . $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
                $p6  = DIR_WS_IMAGES . 'large/' . $products_image_base . IMAGE_SUFFIX_LARGE .  $products_image_extension;
                
                $p2 = true;  //-Indicate that the image has been "handled".
                break;
                
            // -----
            // This notifier lets any image-handler know the current image being processed, providing the following parameters:
            //
            // $p1 ... (r/o) ... The current product's name
            // $p2 ... (r/w) ... The (possibly updated) filename (including path) of the current additional image.
            //
            case 'NOTIFY_MODULES_ADDITIONAL_IMAGES_GET_LARGE':
                $products_name = $p1;
                $products_image_large = $p2;
            	if (function_exists('handle_image')) {
                    $newimg = handle_image($products_image_large, addslashes($products_name), LARGE_IMAGE_MAX_WIDTH, LARGE_IMAGE_MAX_HEIGHT, '');
                    list($src, $alt, $width, $height, $parameters) = $newimg;
                    $p2 = zen_output_string($src);
                } 
                break;
                
            // -----
            // This notifier lets any image-handler "massage" the name of the current thumbnail image name with appropriate
            // slashes for javascript/jQuery display:
            //
            // $p1 ... (n/a) ... An empty array, not applicable.
            // $p2 ... (r/w) ... A reference to the "slashed" thumbnail image name.
            //                
            case 'NOTIFY_MODULES_ADDITIONAL_IMAGES_THUMB_SLASHES':
                //  remove additional single quotes from image attributes (important!)
                $thumb_slashes = $p2;
                $p2 = preg_replace("/([^\\\\])'/", '$1\\\'', $thumb_slashes);
                break;
            
            // -----
            // Update the (globally-available) image names for any rendering of the popup_image page.
            //
            case 'NOTIFY_HEADER_END_POPUP_IMAGES':
                $products_image_extension = $GLOBALS['products_image_extension'];
                
                $products_image_base = preg_replace('/' . $products_image_extension . '$/', '', $GLOBALS['products_image']);
                $GLOBALS['products_image_base'] = $products_image_base;
                
                $GLOBALS['products_image_medium'] = DIR_WS_IMAGES . 'medium/' . $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
                $GLOBALS['products_image_large'] = DIR_WS_IMAGES . 'large/' . $products_image_base . IMAGE_SUFFIX_LARGE . $products_image_extension;
                break;
                
            default:
                break;
        }
    }
}