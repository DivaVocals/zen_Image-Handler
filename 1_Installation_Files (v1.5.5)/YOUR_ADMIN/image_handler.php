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

$ih_page = isset($_GET['ih_page']) ? $_GET['ih_page'] : ((!defined('IH_VERSION') || (IH_VERSION == 'REMOVED')) ? 'admin' : 'manager');
//$action = (isset($_GET['action']) ? $_GET['action'] : '');
//------- Nigel 
$action = '';
if (isset($_POST['action'])) { 
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
}
//-------End Nigel
//  $action = (isset($_GET['action']) ? $_GET['action'] : '');
$products_filter = (isset($_GET['products_filter']) ? ((int)$_GET['products_filter']) : '');
$current_category_id = (isset($_GET['current_category_id'])) ? ((int)$_GET['current_category_id']) : (isset($current_category_id) ? $current_category_id : '');
$currencies = new currencies();
$import_info = null;

if ($action == 'set_products_filter') {  
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager&amp;products_filter=' . (int)$_POST['products_filter']));
}

if ($ih_page == 'manager') {
    // manager actions are handled in a separate file
    require 'includes/ih_manager.php';
}

if ($action == 'ih_clear_cache') {
    $error = bmz_clear_cache();
    if (!$error) {
        $messageStack->add(IH_CACHE_CLEARED, 'success');
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!--doctype changed to stop quirks mode -->
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
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

input[type="text"], input[type="submit"], input[type="file"], select {border: 1px solid #CCCCCC; background-color: #FFFFFF;}

#ih-head { float:left; padding: 8px 5px; }
#ih-search { float: right; padding: 5px; }
#ih-admin { background-color: #F5F5F5; border: solid #CCCCCC; border-width: 1px 0px; }

#ih-p-info { border-collapse: collapse; }
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

div.donationbox {display: none;}
.donationbox label { display: none;}
.donationbox input[type=text], .donationbox input[type=submit], .donationbox select {display: none;}
.donationbox input[type=submit] {margin-bottom: 5px; cursor: pointer}
.donationbox h2 {font-size: 100%;}

.preview-bb {border-bottom: 1px solid #CCCCCC;}
.preview-br {border-right: 1px solid #CCCCCC;}
.preview-check {border: 1px solid #000000; background:url(images/checkpattern.gif);}

a.wikilink1:link { color:#009900; text-decoration: none; }
a.wikilink1:visited { color:#009900; text-decoration: none; }
a.wikilink1:hover { color:#009900; text-decoration: underline; }
-->
</style>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script type="text/javascript">
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
<body onLoad="init();">
<div id="spiffycalendar" class="text"></div>
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

    echo '<div id="ih-search">' . zen_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    // show reset search
    if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
        echo '<a href="' . zen_href_link(FILENAME_CATEGORIES) . '">' . zen_image_button('button_reset.gif', IMAGE_RESET) . '</a>&nbsp;&nbsp;';
    }
    echo HEADING_TITLE_SEARCH_DETAIL . ' ' . zen_draw_input_field('search');
    if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
        $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
        echo '<br/ >' . TEXT_INFO_SEARCH_DETAIL_FILTER . $keywords;
    }
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
$ih_page = isset($_GET['ih_page']) ? $_GET['ih_page'] : 'manager';
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
    $curr_page = FILENAME_IMAGE_HANDLER;
?>
    <table summary="Products Previous Next Display"><?php require DIR_WS_MODULES . FILENAME_PREV_NEXT_DISPLAY; ?></table>
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
    if ($products_filter != '') {//a category with products has been selected
        $display_priced_by_attributes = zen_get_products_price_is_priced_by_attributes($products_filter);
        echo ($display_priced_by_attributes ? '<span class="alert">' . TEXT_PRICED_BY_ATTRIBUTES . '</span>' . '<br />' : '');
        echo zen_get_products_display_price($products_filter) . '<br /><br />';
        echo zen_get_products_quantity_min_units_display($products_filter, $include_break = true);
        $not_for_cart = $db->Execute(
            "SELECT p.products_id 
               FROM " . TABLE_PRODUCTS . " p 
                    LEFT JOIN " . TABLE_PRODUCT_TYPES . " pt 
                        ON p.products_type= pt.type_id 
              WHERE pt.allow_add_to_cart = 'N'"
        );
    } else { //no category with products has been selected or its the first landing on admin page: nothing to show in products drop down
        echo '';
        $not_for_cart = new stdClass();
        $not_for_cart->fields = array();
    }
?>
            </td>
<?php
    if ($products_filter != '') { //prevent creation of empty Select 
?>
            <td class="attributes-even ih-center"><?php echo zen_draw_products_pull_down('products_filter', 'size="5"', $not_for_cart->fields, true, $products_filter, true, true); ?></td>
            <td class="main ih-center ih-vtop"><?php echo zen_image_submit('button_display.gif', IMAGE_DISPLAY); ?></td>
<?php
    } else {
?>   
            <td>&nbsp;</td>
            <td>&nbsp;</td>
<?php
    } 
?>
        </tr>

        <tr>
            <td colspan="3">
                <table summary="Product List">
<?php
    // show when product is linked
    if ($products_filter != '' && zen_get_product_is_linked($products_filter) == 'true') {
?>
                    <tr>
                        <td class="main ih-center ih-vbot">
                            <?php echo zen_image(DIR_WS_IMAGES . 'icon_yellow_on.gif', IMAGE_ICON_LINKED) . '&nbsp;&nbsp;' . TEXT_LEGEND_LINKED . ' ' . zen_get_product_is_linked($products_filter, 'true'); ?>
                        </td>
                    </tr>
<?php 
    } 
?>
                    <tr>
                        <td class="main ih-center ih-vbot">
<?php
    if ($products_filter != '') {
        echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'action=new_product' . '&amp;cPath=' . $current_category_id . '&amp;pID=' . $products_filter . '&amp;product_type=' . zen_get_products_type($products_filter)) . '">' . zen_image_button('button_edit_product.gif', IMAGE_EDIT_PRODUCT) . '<br />' . TEXT_PRODUCT_EDIT . '</a>';
        echo '</td><td class="main ih-center ih-vbot">';
        echo '<a href="' . zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, 'products_filter=' . $products_filter . '&amp;current_category_id=' . $current_category_id, 'NONSSL') . '">' . zen_image_button('button_edit_attribs.gif', IMAGE_EDIT_ATTRIBUTES) . '<br />' . TEXT_ATTRIBUTE_EDIT . '</a>' . '&nbsp;&nbsp;&nbsp;';
    }
?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table></form>


    <div class="managerbox">
<!-- Start Photo Display -->
<?php
    // start of attributes display
    if ($products_filter == '') {
?>
        <h2><?php echo IH_HEADING_TITLE_PRODUCT_SELECT; ?></h2>
<?php 
    } else {
        // Get the details for the product
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
        if ($product->RecordCount() > 0) {
            $pInfo = new objectInfo($product->fields);
        }

        // Determine if there are any images and work out the file names
        // (based on code from modules/pages/product_info/main_template_vars_images(& _additional) (copying is evil!))
        if ($pInfo->products_image != '') {
            $image_info = pathinfo($pInfo->products_image);
            $products_image_directory = $image_info['dirname'];
            if ($products_image_directory != '.') {
                $products_image_directory .= '/';
            } else {
                $products_image_directory = '';
            }
            $products_image_base = $image_info['filename'];
            $products_image_extension = '.' . $image_info['extension'];
            
            $products_image_match_array = array();
            $ih_admin->findAdditionalImages($products_image_match_array, $products_image_directory, $products_image_extension, $products_image_base);
        }

        if ($pInfo->products_id != '') {
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
        <tr>
            <td class="ih-vtop"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PHOTO_NAME; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILETYPE; ?></td><?php //added nigel ?>
                    <td class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_BASE_SIZE; ?></td>
                    <td class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_SMALL_SIZE; ?></td>
                    <td class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_MEDIUM_SIZE; ?></td>
                    <td class="dataTableHeadingContent ih-center"><?php echo TABLE_HEADING_LARGE_SIZE; ?></td>
                    <td class="dataTableHeadingContent ih-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
<?php
            $selected_image_suffix = '';
            // no images
            $count = count($products_image_match_array);
            $no_images = ($count == 0);
            if ($no_images) {
?>
                <tr>
                     <td colspan="6" class="dataTableContent ih-center"><?php echo TEXT_NO_PRODUCT_IMAGES; ?></td>
                </tr>
<?php 
            }

            $default_extension = false;
            $first = 1;
            for ($i=0; $i < $count; $i++) {
                // there are some pictures, show them!
                $current_image = $products_image_match_array[$i];
                $image_info = pathinfo($current_image);
                $tmp_image_name = $image_info['filename'];
                $products_image_extension = '.' . $image_info['extension'];
                if ($default_extension === false) {
                    $default_extension = $products_image_extension;
                }//added nigel
                $image_file = DIR_WS_IMAGES . $products_image_directory . $tmp_image_name . $products_image_extension;
                $image_file_medium = DIR_WS_IMAGES . 'medium/' . $products_image_directory . $tmp_image_name . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
                $image_file_large  = DIR_WS_IMAGES . 'large/' . $products_image_directory . $tmp_image_name . IMAGE_SUFFIX_LARGE .  $products_image_extension;

                $image_file_full = DIR_FS_CATALOG . $image_file;
                $image_file_medium_full = DIR_FS_CATALOG . $image_file_medium;
                $image_file_large_full = DIR_FS_CATALOG . $image_file_large;

                $tmp_image = new ih_image($image_file, $ihConf['small']['width'], $ihConf['small']['height']);
                $tmp_image_file = $tmp_image->get_local();
                $tmp_image_file_full = DIR_FS_CATALOG . $tmp_image_file;
                $tmp_image_preview = new ih_image($tmp_image_file, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT);
              
                $tmp_image_medium = new ih_image($image_file_medium, $ihConf['medium']['width'], $ihConf['medium']['height']);
                $tmp_image_file_medium = $tmp_image_medium->get_local();
                $tmp_image_file_medium_full = DIR_FS_CATALOG . $tmp_image_file_medium;
                $tmp_image_medium_preview = new ih_image($tmp_image_file_medium, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT);
                
                $tmp_image_large = new ih_image($image_file_large, $ihConf['large']['width'], $ihConf['large']['height']);
                $tmp_image_file_large = $tmp_image_large->get_local();
                $tmp_image_file_large_full = DIR_FS_CATALOG . $tmp_image_file_large;
                $tmp_image_large_preview = new ih_image($tmp_image_file_large, IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT);

                // Get file details
                $text_base_size = $ih_admin->getImageDetailsString(DIR_FS_CATALOG . $image_file);
                $text_default_size = $ih_admin->getImageDetailsString($tmp_image_file_full);
                $text_medium_size = $ih_admin->getImageDetailsString($tmp_image_file_medium_full);
                $text_large_size = $ih_admin->getImageDetailsString($tmp_image_file_large_full);

                if ($first == 1) {
                    $tmp_image_link = zen_catalog_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $pInfo->products_id);
                    $first = 0;
                } else {
                    $tmp_image_link = zen_catalog_href_link(FILENAME_POPUP_IMAGE_ADDITIONAL, 'pID=' . $pInfo->products_id . '&amp;pic=' . ($i) . "&amp;products_image_large_additional=$tmp_image_file_large");
                }

                if (isset($_GET['imgName']) && $_GET['imgName'] == $tmp_image_name ) {
                    // an image is selected, highlight it
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' 
                        . $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_edit') . '\'">' . "\n";
                    // set some details for later usage
                    $selected_image_file = DIR_WS_CATALOG . $tmp_image_file_medium;
                    $selected_image_file_large = DIR_WS_CATALOG . $tmp_image_file_large;
                    $selected_image_link = $tmp_image_link;
                    $selected_image_name = $tmp_image_name;
                    $selected_image_suffix = preg_replace("/^".$products_image_base."/", '', $tmp_image_name);
                    $selected_image_extension = $products_image_extension;
                } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\''
                        . $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_info') . '\'">' . "\n";
                }
?>
                    <td class="dataTableContent"><?php echo $tmp_image_name; ?></td>
                    <td class="dataTableContent"<?php if ($products_image_extension != $default_extension){echo 'style="color:red;"';} ?>><?php echo $products_image_extension; ?></td>
                    <td class="dataTableContent ih-center"><?php echo $text_base_size; ?></td>
                    <td class="dataTableContent ih-center ih-vtop">
<?php
                $preview_image = $tmp_image_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min ($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />';
                echo $text_default_size; 
?>
                    </td>
                    <td class="dataTableContent ih-center ih-vtop">
<?php
                $preview_image = $tmp_image_medium_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />';
                echo $text_medium_size . '<br />';
                if (is_file($image_file_medium_full)) {
                    echo ' <a href="' . $ih_admin->imageHandlerHrefLink($image_file_medium, $products_filter, 'quick_delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>';
                }
?>
                    </td>
                    <td class="dataTableContent ih-center ih-vtop">
<?php
                $preview_image = $tmp_image_large_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min ($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />';
                echo $text_large_size . '<br />';
                if (is_file($image_file_large_full)) {
                    echo ' <a href="' . $ih_admin->imageHandlerHrefLink($image_file_large, $products_filter, 'quick_delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>';
                }
?>
                    </td>
                    <td class="dataTableContent ih-right">
<?php 
                if ( isset($_GET['imgName']) && $_GET['imgName'] == $tmp_image_name ) { 
                    echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
                } else {
                    echo ' <a href="' . $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_info') . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                } 
?>
                    &nbsp;</td>
                </tr>
<?php   
            } // for each photo loop
?>
            </table></td>
<!-- END Photo list table -->

<!-- Start Data Edit Pane -->
<?php
            $heading = array();
            $contents = array();
            $imgNameStr = '';
            switch ($action) {
                case 'layout_info':
                    // edit
                    list($width, $height) = @getimagesize(DIR_FS_CATALOG . $selected_image_file);
                    $heading[] = array(
                        'text' => '<strong>' . TEXT_INFO_IMAGE_INFO . '</strong>'
                    );
                    $contents = array(
                        'align' => 'center', 
                        'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, 'ih_page=' . $ih_page . '&amp;products_filter=' . $products_filter . '&amp;action=save', 'post', 'enctype="multipart/form-data"')
                    );
                    $contents[] = array
                        ('text' => '<strong>' . TEXT_INFO_NAME. ': </strong>' . $selected_image_name . '<br />'
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
                    // show new, delete, and edit buttons
                    $contents[] = array(
                        'align' => 'center', 
                        'text' => '<br />' .
                            ' <a href="' . $ih_admin->imageHandlerHrefLink($_GET['imgName'], $products_filter, 'layout_edit') . '">'
                            . zen_image_button('button_edit.gif', IH_IMAGE_EDIT) . '</a> &nbsp; '
                            . ' <a href="' . $ih_admin->imageHandlerHrefLink($_GET['imgName'], $products_filter, 'layout_delete') . '">'
                            . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a> &nbsp;'
                            . ' <a href="' . $ih_admin->imageHandlerHrefLink('', $products_filter, 'layout_new') . '">'
                            . zen_image_button('button_new_file.gif', IH_IMAGE_NEW_FILE) . '</a>'
                    );
                    break;
                    
                case 'layout_edit':
                    // Edit specific details 
                    $imgNameStr = '&amp;imgEdit=1' .'&amp;imgBase=' . $products_image_base
                        . "&amp;imgSuffix=" . $selected_image_suffix
                        . "&amp;imgBaseDir=" . $products_image_directory 
                        . "&amp;imgExtension=" . $selected_image_extension;
                    $heading[] = array(
                        'text' => '<strong>' . TEXT_INFO_EDIT_PHOTO . '</strong>'
                    );

                case 'layout_new':  
                    if ( $action != 'layout_edit' ) {
                        $imgNameStr .= ( $no_images ) ? "&amp;newImg=1" : '&amp;imgBase='.$products_image_base
                            . "&amp;imgBaseDir=" . $products_image_directory 
                            . "&amp;imgExtension=" . $default_extension;
                        $heading[] = array(
                            'text' => '<strong>' . TEXT_INFO_NEW_PHOTO . '</strong>'
                        );
                    }
              
                    $contents = array(
                        'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, 
                            '&products_filter=' . $products_filter . $imgNameStr
                            . '&amp;action=save', 'post', 'enctype="multipart/form-data"')
                    ); //steve check this &products_filter=

                    // check if this is a master image or if no images exist
                    if ($no_images) {
                        $contents[] = array(
                            'text' => '<strong>' . TEXT_INFO_IMAGE_BASE_NAME . '</strong><br />' 
                        );
                        $contents[] = array(
                            'text' => zen_draw_input_field('imgBase', '', 'size="30"')
                        );
                      
                        $dir = @dir(DIR_FS_CATALOG_IMAGES);
                        $dir_info[] = array('id' => '', 'text' => TEXT_INFO_MAIN_DIR);
                        while ($file = $dir->read()) {
                            if (is_dir(DIR_FS_CATALOG_IMAGES . $file) 
                                && strtoupper($file) != 'CVS' 
                                && $file != "." 
                                && $file != ".." 
                                && $file != 'original' 
                                && $file != 'medium'
                                && $file != 'large') {
                                $dir_info[] = array('id' => $file . '/', 'text' => $file);
                            }
                        }
                        $contents[] = array('
                            text' => '<br /><strong>' . TEXT_INFO_BASE_DIR . '</strong><br />' . TEXT_INFO_NEW_DIR
                        );
                        $contents[] = array(
                            'text' => TEXT_INFO_IMAGE_DIR . zen_draw_pull_down_menu('imgBaseDir', $dir_info, "")
                        );
                        $contents[] = array(
                            'text' => TEXT_INFO_OR.' ' . zen_draw_input_field('imgNewBaseDir', '', 'size="20"') 
                        );
                    } elseif ($action != 'layout_edit') {
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
                    // Nigels ugly hack to display warning on edit screen that the default file must be filled in
                    if ( $action == 'layout_new' ) {// -this section is the hack
                        //-------------------------
                        $contents[] = array(
                            'text' => '<br /><strong>' . TEXT_INFO_DEFAULT_IMAGE . '</strong>&nbsp;&nbsp;<strong class="errorText">(required)</strong><br />' 
                                . TEXT_INFO_DEFAULT_IMAGE_HELP . '<br />'
                                . zen_draw_input_field('default_image', '', 'size="20" ' . $file_parms, false, 'file') . '<br />' . $pInfo->products_image
                        );
                    } else { // this section is the original code
                        $contents[] = array(
                            'text' => '<br /><strong>' . TEXT_INFO_DEFAULT_IMAGE . '</strong><br />' 
                                . TEXT_INFO_DEFAULT_IMAGE_HELP . '<br />'
                                . zen_draw_input_field('default_image', '', 'size="20" '. $file_parms, false, 'file') . '<br />' . $pInfo->products_image
                        );
                    }

                    if ($action == 'layout_edit') {
                        if ($selected_image_name == $products_image_match_array[0]) {
                            $contents[] = array(
                                'text' => zen_draw_radio_field('imgNaming', 'new_discard', true)
                                    . IH_NEW_NAME_DISCARD_IMAGES . '<br />'
        //  new_copy functionality scheduled for future release                
        //            . zen_draw_radio_field('imgNaming', 'new_copy', false)
        //                . IH_NEW_NAME_COPY_IMAGES . '<br />'
                                    . zen_draw_radio_field('imgNaming', 'keep_name', false)
                                    . IH_KEEP_NAME
                            );
                        }
                    }

                    $contents[] = array(
                        'text' => '<br /><strong>' . TEXT_MEDIUM_FILE_IMAGE . '</strong><br />' . zen_draw_input_field('medium_image', '', 'size="20" ' . $file_parms, false, 'file') . '<br />'
                    );
                    $contents[] = array(
                        'text' => '<br /><strong>' . TEXT_LARGE_FILE_IMAGE . '</strong><br />' . zen_draw_input_field('large_image', '', 'size="20" ' . $file_parms, false, 'file') . '<br />'
                    );
                    $contents[] = array(
                        'align' => 'center', 
                        'text' => '<br />' . zen_image_submit('button_save.gif', IMAGE_SAVE) 
                    );
                    break;
                    
                case 'layout_delete':
                    $imgStr = "&amp;imgBase=" . $products_image_base
                        . "&amp;imgSuffix=" . $selected_image_suffix
                        . "&amp;imgBaseDir=" . $products_image_directory 
                        . "&amp;imgExtension=" . $selected_image_extension;
                  
                    // show new button      
                    $heading[] = array(
                        'text' => '<strong>' . TEXT_INFO_CONFIRM_DELETE . '</strong>'
                    );
              
                    $contents[] = array(
                        'text' => '<br />' . $products_image_directory . $products_image_base . $selected_image_suffix . $selected_image_extension
                    );
                    $contents[] = array(
                        'text' => '<br />' . TEXT_INFO_CONFIRM_DELETE_SURE
                    );
                    if ($selected_image_suffix == '') {
                        $contents[] = array(
                            'text' => zen_draw_checkbox_field('delete_from_database_only', 'Y', false) . IH_DELETE_FROM_DB_ONLY
                        );
                    }

                    $contents[] = array(
                        'align' => 'center', 
                        'text' => '<br />'
                        .' <a href="' . $ih_admin->imageHandlerHrefLink('', $products_filter, 'delete', $imgStr) . '">' 
                        . zen_image_button( 'button_delete.gif', IMAGE_DELETE ) . '</a>'
                    );
                    break;
                    
                default:
                    // show new button      
                    $heading[] = array(
                        'text' => '<strong>' . TEXT_INFO_SELECT_ACTION . '</strong>'
                    );
                    $contents = array(
                        'form' => zen_draw_form('image_define', FILENAME_PRODUCT_TYPES, 'ih_page=' . $_GET['ih_page'] . '&amp;action=new', 'post', 'enctype="multipart/form-data"')
                    );
                    $contents[] = array(
                        'text' => '<br />' . (($no_images) ? TEXT_INFO_CLICK_TO_ADD_MAIN : TEXT_INFO_CLICK_TO_ADD_ADDL)
                    );
                    $contents[] = array(
                        'align' => 'center', 
                        'text' => '<br />'
                            . ' <a href="' . $ih_admin->imageHandlerHrefLink('', $products_filter, 'layout_new') . '">' 
                            . zen_image_button('button_new_file.gif', IH_IMAGE_NEW_FILE) . '</a>'
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
  
        } // if products_id

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
