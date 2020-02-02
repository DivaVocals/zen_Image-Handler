<?php
/**
 * image_handler.php
 * Image Handler Admin interface
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: image_handler.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * DerManoMann 2010-05-31 23:46:50 
 * Nigelt74 2012-02-18
 * torvista 2012-04-14  
 */
require 'includes/application_top.php';

require DIR_WS_CLASSES . 'currencies.php';

// -----
// Load, and create an instance of, the "helper" class for the Image Handler.  This class
// consolidates the various functions previously present in this module.
//
// Note: The $ihConf array is loaded as part of /admin/includes/functions/extra_functions/functions_bmz_image_handler.php.
//
require DIR_WS_CLASSES . 'ImageHandlerAdmin.php';
$ih_admin = new ImageHandlerAdmin();

define('HEADING_TITLE', IH_HEADING_TITLE);
define('HEADING_TITLE_PRODUCT_SELECT', IH_HEADING_TITLE_PRODUCT_SELECT);

$ih_page = isset($_GET['ih_page']) ? $_GET['ih_page'] : 'manager';

$action = (isset($_POST['action'])) ? $_POST['action'] : ((isset($_GET['action'])) ? $_GET['action'] : '');

$products_filter = (isset($_GET['products_filter']) ? ((int)$_GET['products_filter']) : '');
$current_category_id = (isset($_GET['current_category_id'])) ? ((int)$_GET['current_category_id']) : (isset($current_category_id) ? $current_category_id : '');
$currencies = new currencies();
$import_info = null;

// -----
// If the admin has chosen a product from the drop-down list provided by the
// products_previous_next_display module, redirect back to identify that product
// for follow-on processing.
//
if ($action == 'set_products_filter') {  
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager&amp;products_filter=' . (int)$_POST['products_filter']));
}

// -----
// Make sure that the 'products_filter', if set, is associated with a defined product; if not
// redirect back to the main entry page without message.
//
if ($products_filter != '') {
    $product = $db->Execute(
        "SELECT p.products_id, p.products_model, p.products_image, 
                p.product_is_free, p.product_is_call, p.products_quantity_mixed, p.products_priced_by_attribute, p.products_status,
                p.products_discount_type, p.products_discount_type_from, p.products_price_sorter,
                pd.products_name, p.master_categories_id
           FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
          WHERE p.products_id = $products_filter
            AND p.products_id = pd.products_id
            AND pd.language_id = " . (int)$_SESSION['languages_id'] . "
          LIMIT 1"
    );
    if ($product->EOF) {
        $ih_admin->debugLog("Products filter ($products_filter) not found.");
        zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER));
    }
    
    if ($product->fields['products_image'] != '') {
        $image_info = pathinfo($product->fields['products_image']);
        $products_image_directory = $image_info['dirname'];
        if ($products_image_directory != '.') {
            $products_image_directory .= '/';
        } else {
            $products_image_directory = '';
        }
        $products_image_base = $image_info['filename'];
        $products_image_extension = '.' . $image_info['extension'];
    }
}

// -----
// For the 'manager' sub-page, all action-processing is handled by a separate module.
//
if ($ih_page == 'manager') {
    require DIR_WS_INCLUDES . 'ih_manager.php';
}

if ($action == 'ih_clear_cache') {
    $error = bmz_clear_cache();
    if (!$error) {
        zen_record_admin_activity(IH_CACHE_CLEARED, 'info');
        $messageStack->add(IH_CACHE_CLEARED, 'success');
    }
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - '. ICON_IMAGE_HANDLER; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<style type="text/css">
<!--
h1, h2, h3, h4, h5 {
 color: #000000;
 font-weight: bold;
 letter-spacing: 0.1em;
 word-spacing: 0.2em;
 margin: 0 0 0 0;
 padding: 0 0 0 0;
 clear: left
}

.logo h1 {margin: 0; padding: 11px 0 0 0; font-size: 30px; color:#CCCCCC}
h1 {font-size: 180%}
h2 {font-size: 160%}
h3 {font-size: 140%}
h4 {font-size: 120%}
h5 {font-size: 100%}
h1 a, h2 a, h3 a, h4 a, h5 a { font-weight: bold;  letter-spacing: 0.1em;  word-spacing: 0.2em;}

input[type="text"], input[type="submit"], input[type="file"], select {border: 1px solid #CCCCCC;}

.managerbox .dataTableRow:hover { background-color: #dcdcdc; }

#ih-head { float:left; padding: 8px 5px; }
#ih-search { float: right; padding: 5px; }
#ih-admin { background-color: #F5F5F5; border: solid #CCCCCC; border-width: 1px 0px; }

#ih-p-buttons { padding-left: 5px; }
#ih-p-buttons a img { margin-top: 5px; }

#ih-p-info { border-collapse: collapse; margin: 5px; }
#ih-p-info td { padding: 5px; border: 1px solid #444; }
#ih-p-info td:first-child { font-weight: bold; }

.ih-center { text-align: center; }
.ih-right { text-align: right; }
.ih-vtop { vertical-align: top; }
.ih-vbot { vertical-align: bottom; }

div.adminbox {padding: 10px;}
div.aboutbox {width: 95%;}

.page-links {display:inline; padding:2px 5px;}
.page-current {background:#CCCCCC;}

.aboutbox p {text-align: justify;}
fieldset {background: #f6f6f8; padding: 0.5em 0.5em 0.5em 0.5em; margin: 0 0 1em 0; border: 1px solid #ccc;}
legend {font-weight: bold; font-size: 1.4em; color: #1240b0;}

div.managerbox {clear: both;}

.preview-bb {border-bottom: 1px solid #CCCCCC;}
.preview-br {border-right: 1px solid #CCCCCC;}
.preview-check {border: 1px solid #000000; background:url(images/checkpattern.gif);}
-->
</style>
<script src="includes/menu.js"></script>
<script src="includes/general.js"></script>
<script>
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }

   function popupWindow(url) {
       window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=600,height=460,screenX=150,screenY=150,top=150,left=150')
   }

  // -->
</script>
</head>
<body onload="init();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<div>
    <div id="ih-head">
        <h1><?php echo HEADING_TITLE; ?></h1>
<?php
if (defined('IH_VERSION')) {
    echo IH_VERSION_VERSION . ':&nbsp;' . IH_VERSION . '<br />';
} else {
    echo IH_VERSION_NOT_FOUND . '<br />';
}
?>
    </div>
<?php
if ($ih_page == 'manager') {
    // SEARCH DIALOG BOX
    //-----
    // The category/product listing page changed in zc156, detect the current Zen Cart
    // version to determine the page to which the search results are destined.
    //
    $zen_cart_version = PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR;
    $search_target_page = ($zen_cart_version > '1.5.6') ? FILENAME_CATEGORY_PRODUCT_LISTING : FILENAME_CATEGORIES;
    echo '<div id="ih-search">' . zen_draw_form('search', $search_target_page, '', 'get');
    echo HEADING_TITLE_SEARCH_DETAIL . ' ' . zen_draw_input_field('search');
    echo '</form></div>';
}
?>
</div>

<div class="clearBoth"></div>

<ul id="ih-admin">
    <li class="page-links <?php echo ($ih_page == 'manager') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager') ?>"><?php echo IH_MENU_MANAGER; ?></a>
    </li>
    <li class="page-links <?php echo ($ih_page == 'admin') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=admin') ?>"><?php echo IH_MENU_ADMIN; ?></a>
    </li>
    <li class="page-links <?php echo ($ih_page == 'preview') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=preview') ?>"><?php echo IH_MENU_PREVIEW; ?></a>
    </li>
    <li class="page-links <?php echo ($ih_page == 'about') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=about') ?>"><?php echo IH_MENU_ABOUT; ?></a>
    </li>
</ul>

<div class="adminbox">
<?php
/** ----------------------------------------------------------
 * ADMIN TABPAGE INITIALIZATION
 */
$ih_admin_actions = array();
if ($ih_page == 'admin') {
    $ih_admin_actions['ih_uninstall'] = IH_REMOVE;
    $ih_admin_actions['ih_view_config'] = IH_VIEW_CONFIGURATION;
    $ih_admin_actions['ih_clear_cache'] = IH_CLEAR_CACHE;
}

if (count($ih_admin_actions) > 0) {
    echo '<ul>';
    foreach ($ih_admin_actions as $action_name => $link_name) {
        if ($action_name == 'ih_uninstall') {
            // -----
            // Include the "uninstall" page in the menu only if the admin is currently authorized.
            //
            if (zen_is_superuser() || check_page(FILENAME_IMAGE_HANDLER_UNINSTALL, '')) {
                echo '<li><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER_UNINSTALL) . '">' . $link_name . '</a></li>';
            }
        } elseif ($action_name == 'ih_view_config') {
            // -----
            // Include the "View Configuration" page in the menu only if the admin is currently authorized.
            //
            if (zen_is_superuser() || check_page(FILENAME_IMAGE_HANDLER_VIEW_CONFIG, '')) {
                echo '<li><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER_VIEW_CONFIG) . '">' . $link_name . '</a></li>';
            }            
        } else {
            echo '<li><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=admin&amp;action=' . $action_name) . '">' . $link_name . '</a></li>';
        }
    }
    echo '</ul>';
}

/** -----------------------------------------------------
 * MANAGER TABPAGE
 */
if ($ih_page == 'manager') {
    // -----
    // Set the current page, used by the previous/next display module.
    //
    $curr_page = FILENAME_IMAGE_HANDLER;
?>
    <table class="table" summary="Products Previous Next Display"><?php require DIR_WS_MODULES . FILENAME_PREV_NEXT_DISPLAY; ?></table>
<?php
    echo zen_draw_form('set_products_filter_id', FILENAME_IMAGE_HANDLER, 'action=set_products_filter', 'post');
    echo zen_draw_hidden_field('products_filter', $products_filter); 
?> 
    <table summary="Manager Table" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td class="main ih-vtop" width="200" align="left">&nbsp;</td>
            <td colspan="2" class="main"><?php if (isset($_POST['products_filter'])) echo TEXT_PRODUCT_TO_VIEW; ?></td>
        </tr>
        
        <tr>
            <td class="main ih-center ih-vtop" width="200">
<?php   
    //----- Nigel - Another ugly hack - probably need to clean up the attributes section - not really sure why the attributes section matters to IH - ask Diva
    if (isset($_POST['products_filter'])) { 
        $products_filter = $_GET['products_filter'] = (int)$_POST['products_filter'];
    } 
    //------  Nigel --End ugly hack
// FIX HERE
    if ($products_filter != '') {
        $display_priced_by_attributes = zen_get_products_price_is_priced_by_attributes($products_filter);
        echo ($display_priced_by_attributes ? '<span class="alert">' . TEXT_PRICED_BY_ATTRIBUTES . '</span>' . '<br />' : '');
        echo zen_get_products_display_price($products_filter) . '<br /><br />';
        echo zen_get_products_quantity_min_units_display($products_filter, $include_break = true);
    }
?>
            </td>
<?php
    if ($products_filter != '') { //prevent creation of empty Select 
?>
            <td class="ih-center"><?php echo zen_draw_products_pull_down('products_filter', 'size="5"', '', true, $products_filter, true, true); ?></td>
            <td id="ih-p-buttons" class="ih-center ih-vtop">
<?php 
        echo '<input type="submit" class="btn btn-primary" value="'. IMAGE_DISPLAY .'" />&nbsp;';
	
        $edit_product_link = zen_href_link(FILENAME_PRODUCT, "action=new_product&amp;cPath=$current_category_id&amp;pID=$products_filter&amp;product_type=" . zen_get_products_type($products_filter));
        echo '<a href="' . $edit_product_link . '" class="btn btn-info">' . IMAGE_EDIT_PRODUCT . '</a>&nbsp;';
        
        $attribute_controller_link = zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, "products_filter=$products_filter&amp;current_category_id=$current_category_id");
        echo '<a href="' . $attribute_controller_link . '" class="btn btn-warning">' . IMAGE_EDIT_ATTRIBUTES . '</a>'
?>
            </td>
<?php
    } else {
?>   
            <td colspan="2">&nbsp;</td>
<?php
    } 
?>
        </tr>
    </table></form>

    <div class="managerbox">
<!-- Start Photo Display -->
<?php
    if (empty($products_filter) || !isset($product)) {
?>
        <h2><?php echo IH_HEADING_TITLE_PRODUCT_SELECT; ?></h2>
<?php 
    } else {
        $pInfo = new objectInfo($product->fields);

        // -----
        // Gather the images associated with this product (if the product currently has an image
        // identified!).  The ImageHandlerAdmin class returns a sorted list of the images, updating the
        // products_image_match_array with its findings; the first entry in the array is the main product-image.
        //
        $no_images = true;
        $products_image_match_array = array();
        if ($pInfo->products_image != '') {
            $ih_admin->findAdditionalImages($products_image_match_array, $products_image_directory, $products_image_base);
        }
?>
    <table id="ih-p-info">
        <tr>
            <td><?php echo TEXT_PRODUCT_INFO; ?></td>
            <td><?php echo '#' . $pInfo->products_id . ' &mdash; ' . $pInfo->products_name; ?></td>
            
        </tr>
<?php 
        if ($pInfo->products_model != '') {
?>
        <tr>
            <td><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td><?php echo $pInfo->products_model; ?></td>
        </tr>
<?php
        }
        if ($pInfo->products_image != '') {
            $image_info = pathinfo($pInfo->products_image);
            $dirname = ($image_info['dirname'] == '.') ? '' : $image_info['dirname'];
?>
        <tr>
            <td><?php echo TEXT_IMAGE_BASE_DIR; ?></td>
            <td><?php echo DIR_WS_IMAGES . $dirname; ?></td>
        </tr>
<?php
        }
?>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <caption class="ih-center"><?php echo TEXT_TABLE_CAPTION_INSTRUCTIONS; ?></caption>
        <tr>
            <td class="ih-vtop"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                    <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PHOTO_NAME; ?></th>
                    <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILETYPE; ?></th><?php //added nigel ?>
                    <th class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_BASE_SIZE; ?></th>
                    <th class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_SMALL_SIZE; ?></th>
                    <th class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_MEDIUM_SIZE; ?></th>
                    <th class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_LARGE_SIZE; ?></th>
                    <th class="dataTableHeadingContent ih-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>
<?php
        $count = count($products_image_match_array);
        $no_images = ($count == 0);
        if ($no_images) {
?>
                <tr>
                     <td colspan="6" class="dataTableContent ih-center"><?php echo TEXT_NO_PRODUCT_IMAGES; ?></td>
                </tr>
<?php 
        } elseif ($action == '') {
            $action = 'layout_info';
        }

        $selected_image_name = '';
        $selected_image_extension = '';
        $selected_image_file = '';
        $selected_image_suffix = '';
        for ($i = 0, $main_image = true; $i < $count; $i++, $main_image = false) {
            // there are some pictures, show them!
            $current_image = $products_image_match_array[$i];
            $image_info = pathinfo($current_image);
            $tmp_image_name = $image_info['filename'];
            $tmp_image_extension = '.' . $image_info['extension'];
            
            // -----
            // Create the additional variables to accompany the various actions.
            //
            $tmp_image_suffix = str_replace($products_image_base, '', $tmp_image_name);
            
            $parms = "&amp;imgSuffix=$tmp_image_suffix&amp;imgExtension=$tmp_image_extension";
            $info_page = "layout_info$parms";
            $delete_page = "layout_delete$parms";
 
            
            $image_file = DIR_WS_IMAGES . $products_image_directory . $tmp_image_name . $tmp_image_extension;
            $image_file_medium = DIR_WS_IMAGES . 'medium/' . $products_image_directory . $tmp_image_name . IMAGE_SUFFIX_MEDIUM . $tmp_image_extension;
            $image_file_large  = DIR_WS_IMAGES . 'large/' . $products_image_directory . $tmp_image_name . IMAGE_SUFFIX_LARGE .  $tmp_image_extension;

            $image_file_full = DIR_FS_CATALOG . $image_file;
            $image_file_medium_full = DIR_FS_CATALOG . $image_file_medium;
            $image_file_large_full = DIR_FS_CATALOG . $image_file_large;

            $tmp_image = new ih_image($image_file, $ihConf['small']['width'], $ihConf['small']['height']);
            $tmp_image_file = $tmp_image->get_local();
            $tmp_image_file_full = DIR_FS_CATALOG . $tmp_image_file;
            $tmp_image_preview = new ih_image($image_file, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT);
            $text_base_size = $ih_admin->getImageDetailsString(DIR_FS_CATALOG . $image_file);
            $text_default_size = $ih_admin->getImageDetailsString($tmp_image_file_full);
          
            if ($main_image) {
                $tmp_image_medium = new ih_image($image_file_medium, $ihConf['medium']['width'], $ihConf['medium']['height']);
                $tmp_image_file_medium = $tmp_image_medium->get_local();
                $tmp_image_file_medium_full = DIR_FS_CATALOG . $tmp_image_file_medium;
                $tmp_image_medium_preview = new ih_image($image_file_medium, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT);
                $text_medium_size = $ih_admin->getImageDetailsString($tmp_image_file_medium_full);
            }
            
            $tmp_image_large = new ih_image($image_file_large, $ihConf['large']['width'], $ihConf['large']['height']);
            $tmp_image_file_large = $tmp_image_large->get_local();
            $tmp_image_file_large_full = DIR_FS_CATALOG . $tmp_image_file_large;
            $tmp_image_large_preview = new ih_image($image_file_large, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT);
            $text_large_size = $ih_admin->getImageDetailsString($tmp_image_file_large_full);

            if ($main_image) {
                $tmp_image_link = zen_catalog_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $pInfo->products_id);
            } else {
                $tmp_image_link = zen_catalog_href_link(FILENAME_POPUP_IMAGE_ADDITIONAL, 'pID=' . $pInfo->products_id . '&amp;pic=' . ($i) . "&amp;products_image_large_additional=$tmp_image_file_large");
            }

            // -----
            // If this is the selected image, highlight it and save its name for use in the sidebar form handling.
            //
            if ((isset($_GET['imgName']) && $_GET['imgName'] == $tmp_image_name) || (!isset($_GET['imgName']) && $main_image)) {
?>
                <tr id="defaultSelected" class="dataTableRowSelected" onclick="document.location.href='<?php echo $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_edit', $parms); ?>'">
<?php
                // set some details for later usage
                $selected_image_file = DIR_WS_CATALOG . $tmp_image_file;
                $selected_image_file_large = DIR_WS_CATALOG . $tmp_image_file_large;
                $selected_image_link = $tmp_image_link;
                $selected_image_name = $tmp_image_name;
                $selected_image_suffix = str_replace($products_image_base, '', $tmp_image_name);
                $selected_image_extension = $tmp_image_extension;
                $selected_is_main = $main_image;
                $selected_parms = "&amp;imgSuffix=$selected_image_suffix&amp;imgExtension=$selected_image_extension";
            } else {
?>
                <tr class="dataTableRow" onclick="document.location.href='<?php echo $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_info', $parms); ?>'">
<?php
            }
?>
                    <td class="dataTableContent"><?php echo $tmp_image_name; ?></td>
                    <td class="dataTableContent"<?php echo ($products_image_extension != $tmp_image_extension) ? ' style="color:red;"' : ''; ?>><?php echo $tmp_image_extension; ?></td>
                    <td class="dataTableContent ih-center"><?php echo $text_base_size; ?></td>
<?php
            $preview_image = $tmp_image_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
            list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
            $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
            $height = min($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
?>
                    <td class="dataTableContent ih-center ih-vtop"><?php echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />' . $text_default_size; ?></td>
<?php
            if (!$main_image) {
?>
                    <td class="dataTableContent ih-center"><?php echo TEXT_NOT_NEEDED; ?></td>
<?php
            } else {
                $preview_image = $tmp_image_medium_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                $the_image = zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height);
                $delete_link = '';
                if (is_file($image_file_medium_full)) {
                    $delete_link = '<br /><a href="' . $ih_admin->imageHandlerHrefLink($image_file_medium, $products_filter, 'quick_delete') . '" class="btn btn-danger">' .  IMAGE_DELETE . '</a>';
                }
?>
                    <td class="dataTableContent ih-center ih-vtop"><?php echo $the_image . '<br />' . $text_medium_size . $delete_link; ?></td>
<?php
            }
            
            $preview_image = $tmp_image_large_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
            list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
            $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
            $height = min ($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
            $the_image = zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height);
            $delete_link = '';
            if (is_file($image_file_large_full)) {
                $delete_link = '<br />';
                $delete_link .= zen_draw_form("quick_del_$i", FILENAME_IMAGE_HANDLER, zen_get_all_get_params(array('action')) . '&amp;action=quick_delete');
                $delete_link .= zen_draw_hidden_field('qdFile', $image_file_large);
                $delete_link .= '<input type="submit" class="btn btn-danger" value ="' . IMAGE_DELETE . '" />';
                $delete_link .= '</form>';
            }
?>
                    <td class="dataTableContent ih-center ih-vtop"><?php echo $the_image . '<br />' . $text_large_size . $delete_link; ?></td>
                    <td class="dataTableContent ih-right">
<?php 
            if ((isset($_GET['imgName']) && $_GET['imgName'] == $tmp_image_name) || (!isset($_GET['imgName']) && $main_image)) { 
                echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
            } else {
                echo ' <a href="' . $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_info', $parms) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
            } 
?>
                    </td>
                </tr>
<?php
        } // for each photo loop
        
        $new_link = $ih_admin->imageHandlerHrefLink('', $products_filter, 'layout_new');
?>
                <tr class="dataTableRow">
                    <td colspan="7" class="ih-right"><a href="<?php echo $new_link; ?>" class="btn btn-info"><?php echo IH_IMAGE_NEW_FILE; ?></a></td>
                </tr>
            </table></td>
<!-- END Photo list table -->

<!-- Start Data Edit Pane -->
<?php
        $heading = array();
        $contents = array();
        $imgNameStr = '';
        $form_parameters = zen_get_all_get_params(array('action'));
        switch ($action) {
            // -----
            // Sidebar contents when viewing an image's defined layout.
            //
            case 'layout_info':
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $selected_image_file);
                $heading[] = array(
                    'text' => '<strong>' . TEXT_INFO_IMAGE_INFO . '</strong>'
                );
                $contents = array(
                    'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, "$form_parameters&amp;action=save", 'post', 'enctype="multipart/form-data"')
                );
                $contents[] = array(
                    'text' => '<strong>' . TEXT_INFO_NAME. ': </strong>' . $selected_image_name . '<br />'
                );
                $contents[] = array(
                    'text' => '<strong>' . TEXT_INFO_FILE_TYPE . ': </strong>' . $selected_image_extension . '<br />'
                );
                $contents[] = array(
                    'text' => 
                        '<script type="text/javascript"><!--
                            document.write(\'<a href="javascript:popupWindow(\\\'' . $selected_image_link . '\\\')">' 
                            . zen_image($selected_image_file, addslashes($pInfo->products_name), $width, $height) 
                            . '<br />' . TEXT_CLICK_TO_ENLARGE . '<\/a>\');'
                            . '//-->'
                        . '</script>
                        <noscript>'
                        . '<a href="' . zen_href_link($selected_image_file_large) . '" target="_blank">' 
                            . zen_image($selected_image_file, $pInfo->products_name, $width, $height) 
                            . TEXT_CLICK_TO_ENLARGE . '</a>'
                        . '</noscript>' 
                );

                // -----
                // Different buttons shown for different conditions:
                //
                // 1) Current image is the main-product image, show Edit/Delete.
                // 2) Current image is an additional image with the same extension as the main, show Edit/Delete.
                // 3) Current image is an image with a **different** extension as the main, show Delete only
                //
                $edit_button = '';
                $delete_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_delete', $selected_parms);
                $delete_button = '<a href="' . $delete_link . '" class="btn btn-danger">' . IMAGE_DELETE . '</a> &nbsp;';
                
                if ($products_image_extension == $selected_image_extension) {
                    $edit_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_edit', $selected_parms);
                    $edit_button = '<a href="' . $edit_link . '" class="btn btn-warning">' . IH_IMAGE_EDIT . '</a> &nbsp; ';
                }
                $contents[] = array(
                    'align' => 'center', 
                    'text' => "<br />$edit_button$delete_button"
                );
                break;
                
            // -----
            // Sidebar content when either editing an existing image's information or when creating
            // a new image.
            //
            case 'layout_edit':
                $editing = true;
                $hidden_vars = zen_draw_hidden_field('saveType', 'edit') . zen_draw_hidden_field('imgSuffix', $selected_image_suffix);
                $heading[] = array(
                    'text' => '<strong>' . (($selected_is_main) ? TEXT_INFO_EDIT_PHOTO : TEXT_INFO_EDIT_ADDL_PHOTO) . '</strong>'
                );

            case 'layout_new':
                if (empty($editing)) {
                    $editing = false;
                    $hidden_vars = zen_draw_hidden_field('saveType', ($no_images) ? 'new_main' : 'new_addl');
                    $heading[] = array(
                        'text' => '<strong>' . (($no_images) ? TEXT_INFO_NEW_PHOTO : TEXT_INFO_NEW_ADDL_PHOTO) . '</strong>'
                    );
                }

                $contents = array(
                    'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, "$form_parameters&amp;action=save", 'post', 'enctype="multipart/form-data"')
                );

                // check if this is a master image or if no images exist
                if ($no_images) {
                    $contents[] = array(
                        'text' => '<strong>' . TEXT_INFO_IMAGE_BASE_NAME . '</strong><br />' 
                    );
                    $contents[] = array(
                        'text' => zen_draw_input_field('imgBase', '', 'size="30"')
                    );
                  
                    $no_show_dirs = array(
                        '.',
                        '..',
                        'original',
                        'medium',
                        'large'
                    );
                    $dir = @dir(DIR_FS_CATALOG_IMAGES);
                    $dir_info[] = array('id' => '', 'text' => TEXT_INFO_MAIN_DIR);
                    while ($file = $dir->read()) {
                        if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) != 'CVS' && !in_array($file, $no_show_dirs)) {
                            $dir_info[] = array('id' => $file . '/', 'text' => $file);
                        }
                    }
                    $contents[] = array(
                        'text' => '<br /><strong>' . TEXT_INFO_BASE_DIR . '</strong><br />' . TEXT_INFO_NEW_DIR
                    );
                    $contents[] = array(
                        'text' => '<strong>' . TEXT_INFO_IMAGE_DIR . '</strong>' . zen_draw_pull_down_menu('imgBaseDir', $dir_info, "")
                    );
                    $contents[] = array(
                        'text' => TEXT_INFO_OR . ' ' . zen_draw_input_field('imgNewBaseDir', '', 'size="20"') 
                    );
                } elseif (!$editing) {
                    $contents[] = array(
                        'text' => '<strong>' . TEXT_INFO_IMAGE_SUFFIX . '</strong><br />' . TEXT_INFO_USE_AUTO_SUFFIX . '<br />' 
                    );
                    $contents[] = array(
                        'text' => zen_draw_input_field('imgSuffix', $selected_image_suffix, 'size="10"') 
                    );
                }

                // -----
                // Set up the "acceptable" file types for the form, depending on whether or not the active product
                // currently has an image defined.
                //
                if ($no_images) {
                    $accept = 'image/jpeg,image/jpg,image/gif,image/png';
                } else {
                    switch (strtolower($products_image_extension)) {
                        case '.gif':
                            $accept = 'image/gif';
                            break;
                        case '.png':
                            $accept = 'image/png';
                            break;
                        case '.jpg':        //-Fall-through ...
                        case '.jpeg':
                            $accept = 'image/jpeg,image/jpg';
                            break;
                        default:
                            $accept = 'image/jpeg,/image/jpg,image/gif,image/png';
                            break;
                    }
                }
                $file_parms = 'accept="' . $accept . '"';
                
                // Image fields
                $base_image_note = ($action == 'layout_new') ? '&nbsp;&nbsp;<strong class="errorText">(required)</strong>' : '';
                $contents[] = array(
                    'text' => '<br /><strong>' . TEXT_INFO_DEFAULT_IMAGE . '</strong>' . $base_image_note . '<br />'
                        . TEXT_INFO_DEFAULT_IMAGE_HELP . '<br />'
                        . zen_draw_input_field('default_image', '', 'size="20" ' . $file_parms, false, 'file') . '<br />' . $selected_image_name . $selected_image_extension
                );

                if ($editing) {
                    if ($selected_is_main) {
                        $contents[] = array(
                            'text' => zen_draw_radio_field('imgNaming', 'new_discard', false) . IH_NEW_NAME_DISCARD_IMAGES . '<br />'
                                . zen_draw_radio_field('imgNaming', 'keep_name', true) . IH_KEEP_NAME
                        );
                    }
                }

                if (($editing && $selected_image_suffix == '') || (!$editing && $no_images)) {
                    $contents[] = array(
                        'text' => '<br /><strong>' . TEXT_MEDIUM_FILE_IMAGE . '</strong><br />' . zen_draw_input_field('medium_image', '', 'size="20" ' . $file_parms, false, 'file') . '<br />'
                    );
                }
                
                $contents[] = array(
                    'text' => '<br /><strong>' . TEXT_LARGE_FILE_IMAGE . '</strong><br />' . zen_draw_input_field('large_image', '', 'size="20" ' . $file_parms, false, 'file') . '<br />'
                );
                
                
                if (!$editing) {
                    $cancel_button_link = $ih_admin->imageHandlerHrefLink('', $products_filter, '', '&amp;ih_page=manager');
                } else {
                    $cancel_button_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_info');
                }
                $cancel_button = '<a href="' . $cancel_button_link . '" class="btn btn-warning">' . IMAGE_CANCEL . '</a>';
                $contents[] = array(
                    'align' => 'center', 
                    'text' => '<br />' . $cancel_button . '&nbsp;' . '<input type="submit" class="btn btn-primary" value="' . IMAGE_SAVE . '" />' . $hidden_vars
                );
                break;
                
            // -----
            // Sidebar content when an image-delete is requested.
            //
            case 'layout_delete':

                $imgStr = "&amp;imgSuffix=$selected_image_suffix&amp;imgExtension=$selected_image_extension";
              
                // show new button      
                $heading[] = array(
                    'text' => '<strong>' . sprintf(TEXT_INFO_CONFIRM_DELETE, (($selected_is_main) ? TEXT_MAIN : TEXT_ADDITIONAL)) . '</strong>'
                );
                $hidden_vars = zen_draw_hidden_field('imgSuffix', $selected_image_suffix);
                $hidden_vars .= zen_draw_hidden_field('imgExtension', $selected_image_extension);
                $hidden_vars .= zen_draw_hidden_field('imgName', $selected_image_name);
                $page_parameters = zen_get_all_get_params(array('action', 'imgName', 'imgSuffix', 'imgExtension')) . 'action=delete';
                $contents = array(
                    'form' => zen_draw_form('image_delete', FILENAME_IMAGE_HANDLER, $page_parameters) . $hidden_vars
                );
                $contents[] = array(
                    'text' => '<br />' . $products_image_directory . $products_image_base . $selected_image_suffix . $selected_image_extension
                );
                $contents[] = array(
                    'text' => '<br />' . TEXT_INFO_CONFIRM_DELETE_SURE
                );
                if ($selected_image_suffix == '') {
                    $contents[] = array(
                        'text' => zen_draw_checkbox_field('delete_from_db_only', 'Y', false) . IH_DELETE_FROM_DB_ONLY
                    );
                }

                $cancel_button_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_info');
                $cancel_button = '<a href="' . $cancel_button_link . '" class="btn btn-warning">' . IMAGE_CANCEL . '</a>';
                $contents[] = array(
                    'align' => 'center', 
                    'text' => '<br />' . $cancel_button . '&nbsp;' . '<input type="submit" class="btn btn-danger" value ="' . IMAGE_DELETE . '" />'
                );
                break;
            
            // -----
            // Default content, used on initial (no parameters) page display.
            //
            default:
                // show new button      
                $heading[] = array(
                    'text' => '<strong>' . TEXT_INFO_SELECT_ACTION . '</strong>'
                );
                $contents[] = array(
                    'text' => '<br />' . (($no_images) ? TEXT_INFO_CLICK_TO_ADD_MAIN : TEXT_INFO_CLICK_TO_ADD_ADDL)
                );
                break;
        }

        if (zen_not_null($heading) && zen_not_null($contents)) {
            $box = new box;
?>
            <td width="25%" class="ih-vtop"><?php echo $box->infoBox($heading, $contents); ?></td>
<?php
        }
?>  
        </tr></table>
<?php
    } // if products_filter
?>
    </div>
<?php
} // if $ih_page == 'manager'

/** ------------------------------------
 * PREVIEW TABPAGE
 */
if ($ih_page == 'preview') {
      $images = array();
      $pngimage = new ih_image(basename($ihConf['dir']['admin']) . "/" . 'images/ih-test.png', intval($ihConf['small']['width']), intval($ihConf['small']['height']));
      $images['pngsource'] = $pngimage->get_resized_image(intval($ihConf['small']['width']), intval($ihConf['small']['height']), 'orig');
      $images['pngsmall'] = $pngimage->get_resized_image($ihConf['small']['width'], $ihConf['small']['height'], 'small');
      $images['pngmedium'] = $pngimage->get_resized_image($ihConf['medium']['width'], $ihConf['medium']['height'], 'medium');
      $images['pnglarge'] = $pngimage->get_resized_image($ihConf['large']['width'], $ihConf['large']['height'], 'large');
      
      $jpgimage = new ih_image(basename($ihConf['dir']['admin']) . "/" . 'images/ih-test.jpg', intval($ihConf['small']['width']), intval($ihConf['small']['height'])); 
      $images['jpgsource'] = $jpgimage->get_resized_image(intval($ihConf['small']['width']), intval($ihConf['small']['height']), 'orig');
      $images['jpgsmall'] = $jpgimage->get_resized_image($ihConf['small']['width'], $ihConf['small']['height'], 'small');
      $images['jpgmedium'] = $jpgimage->get_resized_image($ihConf['medium']['width'], $ihConf['medium']['height'], 'medium');
      $images['jpglarge'] = $jpgimage->get_resized_image($ihConf['large']['width'], $ihConf['large']['height'], 'large');
      
      $gifimage = new ih_image(basename($ihConf['dir']['admin']) . "/" . 'images/ih-test.gif', intval($ihConf['small']['width']), intval($ihConf['small']['height'])); 
      $images['gifsource'] = $gifimage->get_resized_image(intval($ihConf['small']['width']), intval($ihConf['small']['height']), 'orig');
      $images['gifsmall'] = $gifimage->get_resized_image($ihConf['small']['width'], $ihConf['small']['height'], 'small');
      $images['gifmedium'] = $gifimage->get_resized_image($ihConf['medium']['width'], $ihConf['medium']['height'], 'medium');
      $images['giflarge'] = $gifimage->get_resized_image($ihConf['large']['width'], $ihConf['large']['height'], 'large');
  
?>
    <table summary="Preview Images" style="background-color:#F5F5F5" cellspacing="0" cellpadding="5" border="0">
        <tr>
            <th class="preview-bb preview-br"><?php echo IH_SOURCE_TYPE; ?></th>
            <th class="preview-bb"><?php echo IH_SOURCE_IMAGE; ?></th>
            <th class="preview-bb"><?php echo IH_SMALL_IMAGE; ?></th>
            <th class="preview-bb"><?php echo IH_MEDIUM_IMAGE; ?></th>
        </tr>
<!-- source png row -->
        <tr>
            <td class="preview-br"><strong>png</strong></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['pngsource']?>" alt="png source" title="png source" /></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['pngsmall']?>" alt="png small" title="png small" /></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['pngmedium']?>" alt="png medium" title="png medium" /></td>
        </tr>
<!-- source jpg row -->
        <tr>
            <td class="preview-br"><strong>jpg</strong></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['jpgsource']?>" alt="jpg source" title="jpg source" /></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['jpgsmall']?>" alt="jpg small" title="jpg small" /></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['jpgmedium']?>" alt="jpg medium" title="jpg medium" /></td>
        </tr>
<!-- source gif row -->
        <tr class="preview-br">
            <td style="border-right: 1px solid #CCCCCC"><strong>gif</strong></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['gifsource']?>" alt="gif source" title="gif source" /></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['gifsmall']?>" alt="gif small" title="gif small" /></td>
            <td><img class="preview-check" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['gifmedium']?>" alt="gif medium" title="gif medium" /></td>
        </tr>
    </table>
<?php
}

/** -------------------------------------
 * ABOUT TABPAGE
 */

if ($ih_page == 'about') {
    if (file_exists(DIR_WS_LANGUAGES . $_SESSION['language'] . '/image_handler_about.php')) {
        include DIR_WS_LANGUAGES . $_SESSION['language'] . '/image_handler_about.php';
    } elseif (file_exists(DIR_WS_LANGUAGES . 'english/image_handler_about.php')) {
        include DIR_WS_LANGUAGES . 'english/image_handler_about.php';
    } else {
?>
    <div style="font-size: x-large;"><b>Missing <?php echo DIR_WS_LANGUAGES . $_SESSION['language'] . '/image_handler_about.php'; ?>!</b></div>
<?php
    }
}
?>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php
require DIR_WS_INCLUDES . 'footer.php';
?>
<!-- footer_eof //-->
</body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
