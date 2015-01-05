<?php
/**mod Image Handler 4.3.2
 * main_product_image module
 *
 * @package templateSystem
 * @copyright Copyright 2005-2006 Tim Kroeger
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: main_product_image.php 4663 2006-10-02 04:08:32Z drbyte $
 * Last modified by DerManoMann 2010-05-31 23:41:53
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$products_image_extension = substr($products_image, strrpos($products_image, '.'));
//Begin Image Handler changes 1 of 2
//the next three lines are commented out for Image Handler 4
//$products_image_base = str_replace($products_image_extension, '', $products_image);
//$products_image_medium = $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
//$products_image_large = $products_image_base . IMAGE_SUFFIX_LARGE . $products_image_extension;
$products_image_base = preg_replace('/'.$products_image_extension . '$/', '', $products_image);
$products_image_medium = DIR_WS_IMAGES . 'medium/' . $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
$products_image_large  = DIR_WS_IMAGES . 'large/' . $products_image_base . IMAGE_SUFFIX_LARGE .  $products_image_extension;
//End Image Handler changes 1 of 2

//Begin Image Handler changes 2 of 2 (this entire section is commented out for Image Handler 4)
// check for a medium image else use small
//if (!file_exists(DIR_WS_IMAGES . 'medium/' . $products_image_medium)) {
//  $products_image_medium = DIR_WS_IMAGES . $products_image;
//} else {
//  $products_image_medium = DIR_WS_IMAGES . 'medium/' . $products_image_medium;
//}
// check for a large image else use medium else use small
//if (!file_exists(DIR_WS_IMAGES . 'large/' . $products_image_large)) {
//  if (!file_exists(DIR_WS_IMAGES . 'medium/' . $products_image_medium)) {
//    $products_image_large = DIR_WS_IMAGES . $products_image;
//  } else {
//    $products_image_large = DIR_WS_IMAGES . 'medium/' . $products_image_medium;
//  }
//} else {
//  $products_image_large = DIR_WS_IMAGES . 'large/' . $products_image_large;
//}
//End Image Handler changes 2 of 2 (this entire section is commented out for Image Handler 4)
  /*
echo
'Base ' . $products_image_base . ' - ' . $products_image_extension . '<br>' .
'Medium ' . $products_image_medium . '<br><br>' .
'Large ' . $products_image_large . '<br><br>';
*/
// to be built into a single variable string