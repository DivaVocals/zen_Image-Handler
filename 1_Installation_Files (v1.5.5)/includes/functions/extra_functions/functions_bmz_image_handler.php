<?php
/**
 * mod Image Handler 4.3.3
 * functions_bmz_image_handler.php
 * html_output hook function and additional image referencing functions for
 * backwards compatibility, parsing of configuration settings
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: functions_bmz_image_handler.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by DerManoMann 2010-05-31 23:46:50 
 */

require_once DIR_FS_CATALOG . DIR_WS_CLASSES . 'bmz_image_handler.class.php';

$ihConf['version']              = 'v4.3.3';

$ihConf['dir']['docroot']       = DIR_FS_CATALOG;
$ihConf['dir']['images']        = DIR_WS_IMAGES;

$ihConf['resize']               = defined('IH_RESIZE') ? (IH_RESIZE == 'yes') : false;

$ihConf['small']['width']       = SMALL_IMAGE_WIDTH;
$ihConf['small']['height']      = SMALL_IMAGE_HEIGHT;
$ihConf['small']['filetype']     = defined('SMALL_IMAGE_FILETYPE') ? SMALL_IMAGE_FILETYPE : 'no_change';
$ihConf['small']['bg']          = defined('SMALL_IMAGE_BACKGROUND') ? SMALL_IMAGE_BACKGROUND : 'transparent 255:255:255';
$ihConf['small']['quality']     = defined('SMALL_IMAGE_QUALITY') ? intval(SMALL_IMAGE_QUALITY) : 85;
$ihConf['small']['watermark']   = defined('WATERMARK_SMALL_IMAGES') ? (WATERMARK_SMALL_IMAGES == 'yes') : false;
$ihConf['small']['zoom']        = defined('ZOOM_SMALL_IMAGES') ? (ZOOM_SMALL_IMAGES == 'yes') : true;
$ihConf['small']['size']        = defined('ZOOM_IMAGE_SIZE') ? ZOOM_IMAGE_SIZE : 'Medium';

$ihConf['medium']['prefix']      = '/medium';
$ihConf['medium']['suffix']      = IMAGE_SUFFIX_MEDIUM;
$ihConf['medium']['width']      = MEDIUM_IMAGE_WIDTH;
$ihConf['medium']['height']     = MEDIUM_IMAGE_HEIGHT;
$ihConf['medium']['filetype']    = defined('MEDIUM_IMAGE_FILETYPE') ? MEDIUM_IMAGE_FILETYPE : 'no_change';
$ihConf['medium']['bg']         = defined('MEDIUM_IMAGE_BACKGROUND') ? MEDIUM_IMAGE_BACKGROUND : 'transparent 255:255:255';
$ihConf['medium']['quality']    = defined('MEDIUM_IMAGE_QUALITY') ? intval(MEDIUM_IMAGE_QUALITY) : 85;
$ihConf['medium']['watermark']  = defined('WATERMARK_MEDIUM_IMAGES') ? (WATERMARK_MEDIUM_IMAGES == 'yes') : false;

$ihConf['large']['prefix']      = '/large';
$ihConf['large']['suffix']       = IMAGE_SUFFIX_LARGE;
$ihConf['large']['width']       = defined('LARGE_IMAGE_MAX_WIDTH') ? LARGE_IMAGE_MAX_WIDTH : '750';
$ihConf['large']['height']      = defined('LARGE_IMAGE_MAX_HEIGHT') ? LARGE_IMAGE_MAX_HEIGHT : '550';
$ihConf['large']['filetype']     = defined('LARGE_IMAGE_FILETYPE') ? LARGE_IMAGE_FILETYPE : 'no_change';
$ihConf['large']['bg']          = defined('LARGE_IMAGE_BACKGROUND') ? LARGE_IMAGE_BACKGROUND : 'transparent 255:255:255';
$ihConf['large']['quality']     = defined('LARGE_IMAGE_QUALITY') ? intval(LARGE_IMAGE_QUALITY) : 85;
$ihConf['large']['watermark']   = defined('WATERMARK_LARGE_IMAGES') ? (WATERMARK_LARGE_IMAGES == 'yes') : false;

$ihConf['watermark']['gravity'] = defined('WATERMARK_GRAVITY') ? WATERMARK_GRAVITY : 'Center';

function handle_image($src, $alt, $width, $height, $parameters) 
{
	global $ihConf;
	
	if ($ihConf['resize']) {
    	$ih_image = new ih_image($src, $width, $height);
        // override image path, get local image from cache
        if ($ih_image) { 
            $src = $ih_image->get_local();
            $parameters = $ih_image->get_additional_parameters($alt, $ih_image->canvas['width'], $ih_image->canvas['height'], $parameters);
        }
    } else {
        // default to standard Zen-Cart fallback behavior for large -> medium -> small images
        $image_ext = substr($src, strrpos($src, '.'));
        $image_base = substr($src, strlen(DIR_WS_IMAGES), -strlen($image_ext));
        if (strrpos($src, IMAGE_SUFFIX_LARGE) && !is_file(DIR_FS_CATALOG . $src)) {
            //large image wanted but not found
            $image_base = $ihConf['medium']['prefix'] . substr($image_base, strlen($ihConf['large']['prefix']), -strlen($ihConf['large']['suffix'])) . $ihConf['medium']['suffix'];
            $src = DIR_WS_IMAGES . $image_base . $image_ext;
        }
        if (strrpos($src, IMAGE_SUFFIX_MEDIUM) && !is_file(DIR_FS_CATALOG . $src)) {
            //medium image wanted but not found
            $image_base = substr($image_base, strlen($ihConf['medium']['prefix']), -strlen($ihConf['medium']['suffix'])); 
            $src = DIR_WS_IMAGES . $image_base . $image_ext;
        }
    }
    return array($src, $alt, intval($width), intval($height), $parameters);
}


/**
 * get_image functions for backwards compatibility with prior image handler releases
 */
 
function zen_get_small_image($image) 
{
    return $image;
}

function zen_get_medium_image($image_base, $image_extension) 
{
    global $ihConf;
    return $ihConf['medium']['prefix'] . $image_base . $ihConf['medium']['suffix'] . $image_extension;
}

function zen_get_large_image($image_base, $image_extension) 
{
    global $ihConf;
    return $ihConf['large']['prefix'] . $image_base . $ihConf['large']['suffix'] . $image_extension;
}