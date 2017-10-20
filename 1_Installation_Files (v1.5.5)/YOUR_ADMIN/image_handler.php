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

$page = isset($_GET['page']) ? $_GET['page'] : ((!defined('IH_VERSION') || (IH_VERSION == 'REMOVED')) ? 'admin' : 'manager');
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
$products_filter = (isset($_GET['products_filter']) ? $_GET['products_filter'] : '');
$current_category_id = (isset($_GET['current_category_id']) ? $_GET['current_category_id'] : $current_category_id);
$currencies = new currencies();
$import_info = null;

if ($action == 'set_products_filter') {  
    $_GET['products_filter'] = $_POST['products_filter']; 
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'page=manager&amp;products_filter=' . $_GET['products_filter']));
}

if ($page == 'manager') {
    // manager actions are handled in a seperate file
    require 'includes/ih_manager.php';
}

  
if ($action == 'ih_import_images') {
    $files = $ih_admin->getImportInfo();
    $previous_image = '';
    $imageroot = $ihConf['dir']['docroot'] . $ihConf['dir']['images'];
    if (count($files) > 0) {
        for ($i = 0; $i < count($files); $i++) {
            // Remove destination file if it's there
            @unlink($imageroot . $files[$i]['target']);
            if (rename($imageroot . $files[$i]['original'], $imageroot . $files[$i]['target'])) {
                // Update database
                if ($files[$i]['target'] != $files[$i]['source']) {
                    $db->Execute(
                        "UPDATE " . TABLE_PRODUCTS . " 
                            SET products_image = '" . $files[$i]['target'] . "' 
                          WHERE products_image = '" . $files[$i]['source'] . "'"
                    );
                }
                @unlink($imageroot . $files[$i]['source']);
                $messageStack->add(TEXT_MSG_IMPORT_SUCCESS . $files[$i]['original'] . ' => ' . $files[$i]['target'], 'success');
            } else {
                $messageStack->add(TEXT_MSG_IMPORT_FAILURE . $files[$i]['original'] . ' => ' . $files[$i]['target'], 'error');
            }
        }
        $messageStack->add(IH_IMAGES_IMPORTED, 'success');
    }
}

if ($action == 'ih_scan_originals') {
    $import_info = $ih_admin->getImportInfo();
    if (count($import_info) <= 0) {
        $messageStack->add(IH_NO_ORIGINALS, 'caution');
    }
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
    <div style="float:left; padding: 8px 5px;">
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
if ($page == 'manager') {
    // SEARCH DIALOG BOX

    echo '<div style="float: right; padding: 5px;">' . zen_draw_form('search', FILENAME_CATEGORIES, '', 'get');
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

<ul style="background-color:#F5F5F5; border: solid #CCCCCC; border-width: 1px 0px;">
    <li class="page-links <?php echo ($page == 'manager') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'page=manager') ?>"><?php echo IH_MENU_MANAGER; ?></a>
    </li>
    <li class="page-links <?php echo ($page == 'admin') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'page=admin') ?>"><?php echo IH_MENU_ADMIN; ?></a>
    </li>
    <li class="page-links <?php echo ($page == 'preview') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'page=preview') ?>"><?php echo IH_MENU_PREVIEW; ?></a>
    </li>
    <li class="page-links <?php echo ($page == 'about') ? 'page-current' : ''; ?>">
        <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'page=about') ?>"><?php echo IH_MENU_ABOUT; ?></a>
    </li>
</ul>

<div class="adminbox">
<?php
/** ----------------------------------------------------------
 * ADMIN TABPAGE INITIALIZATION
 */
$ih_admin_actions = array();
$page = isset($_GET['page']) ? $_GET['page'] : 'manager';
if ($page == 'admin') {
    $ih_admin_actions['ih_uninstall'] = IH_REMOVE;
    $ih_admin_actions['ih_clear_cache'] = IH_CLEAR_CACHE;
    $ih_admin_actions['ih_scan_originals'] = IH_SCAN_FOR_ORIGINALS;
}

if ($action == 'ih_scan_originals') {
    if (count($import_info) > 0) {
        echo zen_draw_form('import_form', FILENAME_IMAGE_HANDLER, '', 'get') . zen_draw_hidden_field('action', 'ih_import_images');
        echo IH_CONFIRM_IMPORT . '<br />';
        echo zen_image_submit('button_confirm.gif', IMAGE_CONFIRM) . '<br /><br />';
        for ($i = 0; $i < count($import_info); $i++) {
            echo "#$i: " . $import_info[$i]['original'] . ' => ' . $import_info[$i]['target'] . '<br /><br />';
        }
        echo '<br /><br />' . IH_CONFIRM_IMPORT . '<br />';
        echo zen_image_submit('button_confirm.gif', IMAGE_CONFIRM) . '<br />'; 
        echo '</form>';
    }
}

if (count($ih_admin_actions) > 0) {
    echo '<ul>';
    foreach ($ih_admin_actions as $action_name => $link_name) {
        if ($action_name == 'ih_uninstall') {
            echo '<li><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER_UNINSTALL) . '">' . $link_name . '</a></li>';
        } else {
            echo '<li><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'page=admin&amp;action=' . $action_name) . '">' . $link_name . '</a></li>';
        }
    }
    echo '</ul>';
}

/** -----------------------------------------------------
 * MANAGER TABPAGE
 */
if ($page == 'manager') {
    $curr_page = FILENAME_IMAGE_HANDLER;
?>
    <table summary="Products Previous Next Display"><?php require DIR_WS_MODULES . FILENAME_PREV_NEXT_DISPLAY; ?></table>
<?php
    echo zen_draw_form('set_products_filter_id', FILENAME_IMAGE_HANDLER, 'action=set_products_filter', 'post');
    echo zen_draw_hidden_field('products_filter', $_GET['products_filter']); 
?> 
    <table summary="Manager Table" border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td class="main" width="200" align="left" valign="top">&nbsp;</td>
            <td colspan="2" class="main"><?php if (isset($_POST['products_filter'])) echo TEXT_PRODUCT_TO_VIEW; ?></td>
        </tr>
        
        <tr>
            <td class="main" width="200" align="center" valign="top">
<?php   
    //----- Nigel - Another ugly hack - probably need to clean up the attributes section - not really sure why the attributes section matters to IH - ask Diva
    if (isset($_POST['products_filter'])) { 
        $_GET['products_filter'] = $_POST['products_filter'];
    } 
    //------  Nigel --End ugly hack
// FIX HERE
    if ($_GET['products_filter'] != '') {//a category with products has been selected
        $display_priced_by_attributes = zen_get_products_price_is_priced_by_attributes($_GET['products_filter']);
        echo ($display_priced_by_attributes ? '<span class="alert">' . TEXT_PRICED_BY_ATTRIBUTES . '</span>' . '<br />' : '');
        echo zen_get_products_display_price($_GET['products_filter']) . '<br /><br />';
        echo zen_get_products_quantity_min_units_display($_GET['products_filter'], $include_break = true);
        $not_for_cart = $db->Execute("select p.products_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCT_TYPES . " pt on p.products_type= pt.type_id where pt.allow_add_to_cart = 'N'");
    } else { //no category with products has been selected or its the first landing on admin page: nothing to show in products drop down
        echo '';
        $not_for_cart = new stdClass();
        $not_for_cart->fields = array();
    }
?>
            </td>
<?php
    if (isset($products_filter)) { //prevent creation of empty Select 
?>
            <td class="attributes-even" align="center"><?php echo zen_draw_products_pull_down('products_filter', 'size="5"', $not_for_cart->fields, true, $_GET['products_filter'], true, true); ?></td>
            <td class="main" align="center" valign="top"><?php echo zen_image_submit('button_display.gif', IMAGE_DISPLAY); ?></td>
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
    if ((isset($products_filter)) && zen_get_product_is_linked($products_filter) == 'true') {
?>
                    <tr>
                        <td class="main" align="center" valign="bottom">
                            <?php echo zen_image(DIR_WS_IMAGES . 'icon_yellow_on.gif', IMAGE_ICON_LINKED) . '&nbsp;&nbsp;' . TEXT_LEGEND_LINKED . ' ' . zen_get_product_is_linked($products_filter, 'true'); ?>
                        </td>
                    </tr>
<?php 
    } 
?>
                    <tr>
                        <td class="main" align="center" valign="bottom">
<?php
    if ($_GET['products_filter'] != '') {
        echo '<a href="' . zen_href_link(FILENAME_CATEGORIES, 'action=new_product' . '&amp;cPath=' . $current_category_id . '&amp;pID=' . $products_filter . '&amp;product_type=' . zen_get_products_type($products_filter)) . '">' . zen_image_button('button_edit_product.gif', IMAGE_EDIT_PRODUCT) . '<br />' . TEXT_PRODUCT_EDIT . '</a>';
        echo '</td><td class="main" align="center" valign="bottom">';
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
              WHERE p.products_id = " . (int)$_GET['products_filter'] . "
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
            $products_image = $pInfo->products_image;
            $products_image_match_array = array();

            // get file extension and base
            $products_image_extension = substr($products_image, strrpos($products_image, '.'));
            $products_image_base = preg_replace("/" . $products_image_extension . "$/", '', $products_image);
            
            // if in a subdirectory
            if (strrpos($products_image_base, '/')) {
                $products_image_base = substr($products_image_base, strrpos($products_image_base, '/')+1);
            }
        
            // sort out directory
            $products_image_directory =  substr($products_image, 0, strrpos($products_image, '/'));
            // add slash to base dir
            if (($products_image_directory != '') && (!preg_match("|\/$|", $products_image_directory))) {
                $products_image_directory .= '/'; 
            }
            $products_image_directory_full = DIR_FS_CATALOG . DIR_WS_IMAGES . $products_image_directory;
        
            // Check that the image exists! (out of date Database)
            if (file_exists( $products_image_directory_full . $products_image_base . $products_image_extension )) {
                // Add base image to array
                $products_image_match_array[] = $products_image_base . $products_image_extension;
                // Check for additional matching images
                $ih_admin->findAdditionalImages($products_image_match_array, $products_image_directory_full, $products_image_extension, $products_image_base);
            }
        } // if products_image

        if ($pInfo->products_id != '') {
?>
    <h4>
<?php 
            echo TEXT_PRODUCT_INFO . ': #' . $pInfo->products_id . '&nbsp;&nbsp;' . $pInfo->products_name;
            if ($pInfo->products_model != '') {
                echo '<br />'.TEXT_PRODUCTS_MODEL . ': ' . $pInfo->products_model; 
            }
            if ($pInfo->products_image != '') {
                if (preg_match("/^([^\/]+)\//", $pInfo->products_image, $matches)) {
                    echo TEXT_IMAGE_BASE_DIR . ': ' . $matches[1];
                }
            }
?>
    </h4>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PHOTO_NAME; ?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILETYPE; ?></td><?php //added nigel ?>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DEFAULT_SIZE; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_MEDIUM_SIZE; ?></td>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LARGE_SIZE; ?></td>
                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
<?php
            $selected_image_suffix = '';
            // no images
            $no_images = (0 == $count = sizeof($products_image_match_array));
            if ($no_images) {
?>
                <tr>
                    <td colspan="6" class="dataTableContent" align="center"><?php echo TEXT_NO_PRODUCT_IMAGES; ?></td>
                </tr>
<?php 
            }

            $default_extension = 'bob';
            $first = 1;
            for ($i=0; $i < $count; $i++) {
                // there are some pictures, show them!
                $splitpos = strrpos($products_image_match_array[$i], '.');
                $tmp_image_name = substr($products_image_match_array[$i], 0, $splitpos);
                $products_image_extension = substr($products_image_match_array[$i], $splitpos);
                if ($default_extension == 'bob') {
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
                $text_default_size = $ih_admin->getImageDetailsString($tmp_image_file_full);
                $text_medium_size = $ih_admin->getImageDetailsString($tmp_image_file_medium_full);
                $text_large_size = $ih_admin->getImageDetailsString($tmp_image_file_large_full);

                if ($first == 1) {
                    $tmp_image_link = zen_catalog_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $pInfo->products_id);
                    $first = 0;
                } else {
                    $tmp_image_link = zen_catalog_href_link(FILENAME_POPUP_IMAGE_ADDITIONAL, 'pID=' . $pInfo->products_id . '&amp;pic=' . ($i) . "&amp;products_image_large_additional=$tmp_image_file_large");
                }

                if ( isset($_GET['imgName']) && $_GET['imgName'] == $tmp_image_name ) {
                    // an image is selected, highlight it
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' 
                        . zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $_GET['products_filter'] 
                        . '&amp;imgName=' .$tmp_image_name . '&amp;action=layout_edit') . '\'">' . "\n";
                    // set some details for later usage
                    $selected_image_file = DIR_WS_CATALOG . $tmp_image_file_medium;
                    $selected_image_file_large = DIR_WS_CATALOG . $tmp_image_file_large;
                    $selected_image_link = $tmp_image_link;
                    $selected_image_name = $tmp_image_name;
                    $selected_image_suffix = preg_replace("/^".$products_image_base."/", '', $tmp_image_name);
                    $selected_image_extension = $products_image_extension;
                } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\''
                        . zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $_GET['products_filter'] 
                        . '&amp;imgName=' . $tmp_image_name . '&amp;action=layout_info') . '\'">' . "\n";
                }
?>
                    <td class="dataTableContent"><?php echo $tmp_image_name; ?></td>
                    <td class="dataTableContent"<?php if ($products_image_extension != $default_extension){echo 'style="color:red;"';} ?>><?php echo $products_image_extension; ?></td>
                    <td class="dataTableContent" align="center" valign="top">
<?php
                $preview_image = $tmp_image_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min ($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />';
                echo $text_default_size; 
?>
                    </td>
                    <td class="dataTableContent" align="center" valign="top">
<?php
                $preview_image = $tmp_image_medium_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min ($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />';
                echo $text_medium_size . '<br />';
                if (is_file($image_file_medium_full)) {
                    echo ' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'imgName=' 
                        . $image_file_medium . '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=quick_delete') . '">' 
                        . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>';
                }
?>
                    </td>
                    <td class="dataTableContent" align="center" valign="top">
<?php
                $preview_image = $tmp_image_large_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = @getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, intval(IMAGE_SHOPPING_CART_WIDTH));
                $height = min ($height, intval(IMAGE_SHOPPING_CART_HEIGHT));
                echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br />';
                echo $text_large_size . '<br />';
                if (is_file($image_file_large_full)) {
                    echo ' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'imgName=' 
                        . $image_file_large . '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=quick_delete') . '">' 
                        . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>';
                }
?>
                    </td>
                    <td class="dataTableContent" align="right">
<?php 
                if ( isset($_GET['imgName']) && $_GET['imgName'] == $tmp_image_name ) { 
                    echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); 
                } else { 
                    echo '<a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $_GET['products_filter'] 
                        . '&amp;imgName=' . $tmp_image_name . '&amp;action=layout_info') 
                        . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
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
                        'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, 'page=' . $_GET['page'] . '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=save', 'post', 'enctype="multipart/form-data"')
                    );
                    $contents[] = array
                        ('text' => '<strong>' . TEXT_INFO_NAME. ': </strong>' . $selected_image_name . '<br />'
                    );
                    $contents[] = array(
                        'text' => '<strong>' . TEXT_INFO_FILE_TYPE . ': </strong>' . $selected_image_extension . '<br />'
                    );
                    $contents[] = array(
                        'text' => 
                            '<script language="javascript" type="text/javascript"><!--
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
                            ' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'imgName=' 
                            . $_GET['imgName'] . '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=layout_edit') . '">' 
                            . zen_image_button('button_edit.gif', IH_IMAGE_EDIT) . '</a> &nbsp; '
                            . ' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'imgName=' 
                            . $_GET['imgName'] . '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=layout_delete') . '">' 
                            . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a> &nbsp;'
                            .' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 
                            '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=layout_new') . '">' 
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
                            '&products_filter=' . $_GET['products_filter'] . $imgNameStr
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

                    // Image fields
                    // Nigels ugly hack to display warning on edit screen that the default file must be filled in
                    if ( $action == 'layout_new' ) {// -this section is the hack
                        //-------------------------
                        $contents[] = array(
                            'text' => '<br /><strong>' . TEXT_INFO_DEFAULT_IMAGE . '</strong>&nbsp;&nbsp;<strong class="errorText">(required)</strong><br />' 
                                . TEXT_INFO_DEFAULT_IMAGE_HELP . '<br />'
                                . zen_draw_input_field('default_image', '', 'size="20" ', false, 'file') . '<br />' . $pInfo->products_image
                        );
                    } else { // this section is the original code
                        $contents[] = array(
                            'text' => '<br /><strong>' . TEXT_INFO_DEFAULT_IMAGE . '</strong><br />' 
                                . TEXT_INFO_DEFAULT_IMAGE_HELP . '<br />'
                                . zen_draw_input_field('default_image', '', 'size="20" ', false, 'file') . '<br />' . $pInfo->products_image
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
                        'text' => '<br /><strong>' . TEXT_MEDIUM_FILE_IMAGE . '</strong><br />' . zen_draw_input_field('medium_image', '', 'size="20" ', false, 'file') . '<br />'
                    );
                    $contents[] = array(
                        'text' => '<br /><strong>' . TEXT_LARGE_FILE_IMAGE . '</strong><br />' . zen_draw_input_field('large_image', '', 'size="20" ', false, 'file') . '<br />'
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
                        .' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=delete' . $imgStr ) . '">' 
                        . zen_image_button( 'button_delete.gif', IMAGE_DELETE ) . '</a>'
                    );
                    break;
                    
                default:
                    // show new button      
                    $heading[] = array(
                        'text' => '<strong>' . TEXT_INFO_SELECT_ACTION . '</strong>'
                    );
                    $contents = array(
                        'form' => zen_draw_form('image_define', FILENAME_PRODUCT_TYPES, 'page=' . $_GET['page'] . '&amp;action=new', 'post', 'enctype="multipart/form-data"')
                    );
                    $contents[] = array(
                        'text' => '<br />' . TEXT_INFO_CLICK_TO_ADD
                    );
                    $contents[] = array(
                        'align' => 'center', 
                        'text' => '<br />'
                            . ' <a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, '&amp;products_filter=' . $_GET['products_filter'] . '&amp;action=layout_new') . '">' 
                            . zen_image_button('button_new_file.gif', IH_IMAGE_NEW_FILE) . '</a>'
                    );
                    break;
            }
          
            if ((zen_not_null($heading)) && (zen_not_null($contents))) {
                $box = new box;
?>
            <td width="25%" valign="top"><?php echo $box->infoBox($heading, $contents); ?></td>
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
} // if $page == 'manager'

/** ------------------------------------
 * PREVIEW TABPAGE
 */
if ($page == 'preview') {
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

if ($page == 'about') {
?>
    <div class="aboutbox">
        <h2>Image Handler<sup>4</sup> v4.0 for v1.5.x</h2>

        <p>Image Handler<sup>4</sup> v4.0 for v1.5.x is based on an original contribution by Tim Kr&#246;ger.<br /></p>
        <fieldset>
        <legend>Purpose &amp; Aim</legend>
        <p>Image Handler<sup>4</sup> at the heart of it's code is really meant to ease the management of product images (particularly the management of additional product images), and to help improve page performance by optimizing the product images.</p>
        <p>Image Handler<sup>4</sup> generates product images (based on your image settings) in the Image Handler<sup>4</sup> CATALOG/bmz_cache folder. It <strong>DOES NOT</strong> replace or modify the original images. So it's PERFECTLY safe to use on an existing store.</p>
        <p> Image Handler<sup>4</sup> enables you to use GD libraries or ImageMagick (if installed on your server) to generate and resize small, medium and large images on-the-fly/on page request. You can simply upload just one large image (that gets resized as required) or you can have different sources for medium and large images.<br />
        Image Handler<sup>4</sup> also enables you to watermark your images on-the-fly (overlay a second specific translucent image onto the original) and have medium or large images pop up when you move your mouse over a small image (fancy hover).</p>
        <p> This contribution includes a powerful admin interface to browse your   products just like you would with the Attribute Manager and upload /   delete / add additional images without having to do this manually via <acronym title="File Transfer Protocol">FTP</acronym>. Image Handler<sup>4</sup> works fine with mass update utilities like EzPopulate. </p>
        </fieldset>
        <hr>
        <fieldset>
        <legend>Features</legend>
        <ul>
          <li>Improves site performance (faster loading, faster display)</li>
          <li>Professional looking images (no stair-effects, smooth edges)</li>
          <li>Choose preferred image-types for each image size</li>
          <li>Uploading one image automatically creates small, medium and large images on page request</li>
          <li>Drops in and out seamlessly. No need to redo your images. All images are kept.</li>
          <li>Easy install. One-click-database-upgrade.</li>
          <li>Works with mass-update/-upload tools like EzPopulate.</li>
          <li>Watermark images to prevent competitors from stealing them. (prove ownership)</li>
          <li>Fancy image hover functionality lets a larger image pop up whenever you move your mouse above a small image (optional).</li>
          <li>Choose an image background color to match your site or select a transparent background for your images.</li>
          <li>Manage multiple images for products easily from one page just like you do with attributes in the Products Attribute Manager.</li>
        </ul>
        <p>Image Handler<sup>4</sup> is meant to ease the work required to setup images for your store.   It works WITH default Zen Cart functionality, it does not replace it. </p>
        <p>It is very strongly recommend you read through the ENTIRE "<strong>Configuration</strong>" &amp; "<strong>Usage</strong>" sections of the Image Handler<sup>4</sup> readme file. There you will find out exactly what <strong>Image Handler<sup>4</sup></strong> can do.</p>
        </fieldset>

        <hr>

        <fieldset>
        <legend>Troubleshooting Basics</legend>
        <p>Make sure your custom template is active. (Admin &gt; Tools &gt; Template Selection)</p>
        <p>Make sure Image Handler<sup>4</sup> is installed. <strong>Admin
        &gt; Tools &gt; Image Handler<sup>4</sup> &gt; Admin</strong>.
        Set permissions in both your <strong>images</strong> and <strong>bmz_cache</strong> folders to 755 (eg: <strong>both </strong>of these folders need
        to have  the same permissions. For some webhosts you may have to set these permissions to 777).</p>
        <p>If Image Handler<sup>4</sup> does not work or gives you errors:</p>
        <ul>
          <li>Make sure all files are in correct location</li>
          <li>Make sure you uploaded ALL the Image Handler<sup>4</sup> files</li>
          <li>Make sure the files are not corrupt from bad FTP transfers</li>
          <li>Make sure your file merge edits are correct</li>
          <li>MAKE SURE YOU RE-READ THE CONFIGURATION AND USAGE SECTIONS!!!</li>
          <li>Make sure that there are no javascript conflicts (this last point has been largely addressed since Rev 7)</li>
          <li>Make sure that your main product image files names DO NOT contain any special characters (<font>non-alphanumeric characters such as / \ :
        ! @ # $ % ^ &lt; &gt; , [ ] { } &amp; * ( ) + = </font>). Always use proper filenaming practices when naming your images - See this document as a reference: <small><a href="http://www.records.ncdcr.gov/erecords/filenaming_20080508_final.pdf" target="_blank">http://www.records.ncdcr.gov/erecords/filenaming_20080508_final.pdf\</a></small></li>
        </ul>
        </fieldset>

        <hr>

        <fieldset>
        <legend>Zen Cart and Image Management</legend>
        <p>Image Handler<sup>4</sup> is meant to ease the work required to setup images for your store..   It works WITH default Zen Cart functionality, it does not replace it..   Here's some additional FAQs which discuss how product images work in Zen   Cart.</p>
        <ul>
          <li><a href="http://tutorials.zen-cart.com/index.php?article=224" target="_blank">Image Preparation - How-to</a></li>
          <li><a href="http://tutorials.zen-cart.com/index.php?article=30" target="_blank">My images are distorted/fuzzy/squished, help?</a><br>
          </li>
        </ul>
        <p>Information on how Zen Cart   identifies/manages additional product images can be found on these Zen Cart FAQs:</p>
        <ul>
          <li><a href="http://tutorials.zen-cart.com/index.php?article=315" target="_blank">Why am I seeing images for other products on my product pages?</a></li>
          <li><a href="http://tutorials.zen-cart.com/index.php?article=58" target="_blank">How do I add multiple images to a product?</a></li>
          <li><a href="http://tutorials.zen-cart.com/index.php?article=202" target="_blank">How   do I add more than one image of a product?  I want to have a main image   and also one or two other images that show more parts of a particular   product. How/where do I add additional images to a product page?    Thanks!</a></li>
        </ul>
        <p>Check out these FAQs and see if they help clarify how Zen Cart works with product images.</p>
        </fieldset>

        <hr>

        <fieldset>
        <legend> Prepare Your Site for Growth</legend>
        <p>Not many users are aware that Image Handler<sup>4</sup> can manage the needs of a very large site as easily as it does a small one. When first building a site, the owner of a small site needs only to load images to the images folder. But when the site gets bigger and images multiply like rabbits, this can cause file naming confusions for Zen Cart and slow down the site. Preparing for your business to grow from the beginning will save you hours of work later on!</p>
        <p>Without Image Handler<sup>4</sup> installed, Zen Cart requires you to create, optimize, and upload three different size images for each image you want to use. You must name these images using naming suffixes, and place them in corresponding folders inside your main image folder. For example: A product called &quot;Widget&quot; requires images/widget.jpg (small image) images/medium/widget_MED.jpg (medium image) and images/large/widget_LRG.jpg. This is such a hassle, especially if many of your products have multiple images. And as your site grows, it becomes an impossible task!</p>
        <p>With Image Handler<sup>4</sup>, you no longer have to make three sizes of the same images and place them in different folders (unless you want to)! Instead, you need upload only one image in one folder and Image Handler<sup>4</sup> will do the rest! Simply upload your largest highest quality image and Image Handler<sup>4</sup> will resize and optimize your image as needed, and serve up small, medium, or large image sizes appropriate to the page loaded - all automatically and all without actually modifying your original image file in any way! Check out the Configuration Tab of this ReadMe for more info about this awesome functionality!</p>
        <p>Prepare your site for growth by simply creating sub-folders in your main images folder. For example, you may want to put all your &quot;widget&quot; images in a folder called &quot;widgets&quot; and all your doodad images in a folder called &quot;doodads&quot; , like this:<br>
        </p>
        <p>Product: Blue Widget with 3 images<br>
          ---------------------------------- <br>
          /images/widgets/blue_widget1.jpg (main product image for a blue widget, i.e. front view)<br>
          /images/widgets/blue_widget2.jpg (additional product image for a blue widget, i.e. side view)<br>
          /images/widgets/blue_widget3.jpg (additional product image for a blue widget, i.e. rear view)</p>
        <p>&nbsp;</p>
        <p>Product: Red Widget with 1 image<br>
          --------------------------------<br>
          /images/widgets/red_widget.jpg (main product image for a red widget)</p>
        <p>&nbsp;</p>
        <p>Product: Gold Doodad with 2 images<br>
          ----------------------------------<br>
          /images/doodads/gold_doodad1.jpg (main product image for a gold doodad, i.e. view from above)<br>
          /images/doodads/gold_doodad2.jpg (additional product image for a gold doodad, i.e. view from side)</p>
        <p>&nbsp;</p>
        <p>Product: Silver Doodad with 3 images<br>
          ------------------------------------<br>
          /images/doodads/silver_doodad1.jpg (main product image for a silver doodad, i.e. product)<br>
          /images/doodads/silver_doodad2.jpg (additional product image for a silver doodad, i.e. product detail)<br>
          /images/doodads/silver_doodad3.jpg (additional product image for a silver doodad, i.e. product's silver stamp)<br>
        </p>
        <p>Using Image Handler<sup>4</sup>, you can easily sort and manage thousands of images without confusion or hassle! When selecting the main image for a product in the Image Handler<sup>4</sup> interface, Image Handler<sup>4</sup> lets you pick the location for this image. This prompt disappears afterwards because Image Handler<sup>4</sup> knows that additional images need to be in the same folder as their main product image and handles that automatically!</p>
        </fieldset>
    </div>
<?php
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
