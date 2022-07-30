<?php
/**
 * image_handler.php, v5.3.0
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

$products_filter = isset($_GET['products_filter']) ? (int)$_GET['products_filter'] : '';

$current_category_id = (isset($_GET['current_category_id'])) ? ((int)$_GET['current_category_id']) : (isset($_POST['current_category_id']) ? $_POST['current_category_id'] : '');
$currencies = new currencies();
$import_info = null;

// -----
// If the admin has chosen a product from the drop-down list provided by the
// products_previous_next_display module, redirect back to identify that product
// for follow-on processing.
//
if ($action === 'set_products_filter') {
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager' . '&current_category_id=' . $current_category_id . '&products_filter=' . (int)$_POST['products_filter']));
} elseif ($action === 'toggle_config') {
    $new_config_value = (IH_RESIZE === 'no') ? 'yes' : 'no';
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_value = '" . $new_config_value . "',
                last_modified = now()
          WHERE configuration_key = 'IH_RESIZE'
          LIMIT 1"
    );
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, zen_get_all_get_params(['action'])));
}

// -----
// Make sure that the 'products_filter', if set, is associated with a defined product; if not
// redirect back to the main entry page without message.
//
if ($products_filter !== '') {
    $product = $db->Execute(
        "SELECT p.products_id, p.products_model, p.products_image,
                p.product_is_free, p.product_is_call, p.products_quantity_mixed, p.products_priced_by_attribute, p.products_status,
                p.products_discount_type, p.products_discount_type_from, p.products_price_sorter,
                pd.products_name, p.master_categories_id, p.products_status
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

    if ($product->fields['products_image'] !== '') {
        $image_info = pathinfo($product->fields['products_image']);
        $products_image_directory = $image_info['dirname'];
        if ($products_image_directory !== '.') {
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
if ($ih_page === 'manager') {
    require DIR_WS_INCLUDES . 'ih_manager.php';
}

if ($action === 'ih_clear_cache') {
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
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
</head>

<body>
    <?php require DIR_WS_INCLUDES . 'header.php'; ?>

    <h1><?php echo HEADING_TITLE . '&nbsp;&nbsp;' . ((defined('IH_VERSION')) ? IH_VERSION_VERSION . ':&nbsp;' . IH_VERSION : IH_VERSION_NOT_FOUND); ?></h1>

    <div class="row">
        <div class="col-md-6">
            <ul id="ih-menu" class="nav navbar-nav">
                <li>
                    <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager') ?>" class="navbar-btn btn btn-default<?php echo ($ih_page === 'manager') ? ' active' : ''; ?>">
                        <?php echo IH_MENU_MANAGER; ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=admin') ?>" class="navbar-btn btn btn-default<?php echo ($ih_page === 'admin') ? ' active' : ''; ?>">
                        <?php echo IH_MENU_ADMIN; ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=preview') ?>" class="navbar-btn btn btn-default<?php echo ($ih_page === 'preview') ? ' active' : ''; ?>">
                        <?php echo IH_MENU_PREVIEW; ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=about') ?>" class="navbar-btn btn btn-default<?php echo ($ih_page === 'about') ? ' active' : ''; ?>">
                        <?php echo IH_MENU_ABOUT; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
<?php
/** ----------------------------------------------------------
 * ADMIN TABPAGE INITIALIZATION
 */
if ($ih_page === 'admin') {
    $ih_admin_actions = [
        'ih_uninstall' => IH_REMOVE,
        'ih_view_config' => IH_VIEW_CONFIGURATION,
        'ih_clear_cache' => IH_CLEAR_CACHE,
    ];
?>
        <br>
        <ul class="list-group">
<?php
    foreach ($ih_admin_actions as $action_name => $link_name) {
        if ($action_name === 'ih_uninstall') {
            // -----
            // Include the "uninstall" page in the menu only if the admin is currently authorized.
            //
            if (zen_is_superuser() || check_page(FILENAME_IMAGE_HANDLER_UNINSTALL, '')) {
                echo '<li class="list-group-item"><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER_UNINSTALL) . '">' . $link_name . '</a></li>';
            }
        } elseif ($action_name === 'ih_view_config') {
            // -----
            // Include the "View Configuration" page in the menu only if the admin is currently authorized.
            //
            if (zen_is_superuser() || check_page(FILENAME_IMAGE_HANDLER_VIEW_CONFIG, '')) {
                echo '<li class="list-group-item"><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER_VIEW_CONFIG) . '">' . $link_name . '</a></li>';
            }
        } else {
            echo '<li class="list-group-item"><a href="' . zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=admin&action=' . $action_name) . '">' . $link_name . '</a></li>';
        }
    }
?>
        </ul>
<?php
/** -----------------------------------------------------
 * MANAGER TABPAGE
 */
} elseif ($ih_page === 'manager') {
    // -----
    // Set the current page, used by the previous/next display module.
    //
    $curr_page = FILENAME_IMAGE_HANDLER;
?>
        <div id="ih-prev-next" class="row">
            <?php require DIR_WS_MODULES . FILENAME_PREV_NEXT_DISPLAY; ?>
        </div>
<?php
    if (!empty($products_filter)) {
?>
        <div class="row">
<?php
        echo zen_draw_form('set_products_filter_id', FILENAME_IMAGE_HANDLER, 'action=set_products_filter', 'post');
        echo zen_draw_hidden_field('products_filter', (int)$products_filter);
        echo zen_draw_hidden_field('current_category_id', $current_category_id);
?>
            <div class="row"><?php echo TEXT_PRODUCT_TO_VIEW; ?></div>
            <div class="form-group">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-4 text-center">
<?php
        // -----
        // zc158 changes this function's name ...
        //
        if (function_exists('zen_draw_pulldown_products')) {
            echo zen_draw_pulldown_products('products_filter', 'class="form-control" size="5"', '', true, $products_filter, true, true, 'products_name');
        } else {
            echo zen_draw_products_pull_down('products_filter', 'class="form-control" size="5"', '', true, $products_filter, true, true);
        }
?>
                </div>
                <div class="col-xs-4 col-md-6 col-lg-8">
                    <input type="submit" class="btn btn-primary" value="<?php echo IMAGE_DISPLAY; ?>">&nbsp;
<?php
       $edit_product_link = zen_href_link(FILENAME_PRODUCT, "action=new_product&cPath=$current_category_id&pID=$products_filter&product_type=" . zen_get_products_type($products_filter));
       $attribute_controller_link = zen_href_link(FILENAME_ATTRIBUTES_CONTROLLER, "products_filter=$products_filter&current_category_id=$current_category_id");
?>
                    <a href="<?php echo $edit_product_link; ?>" class="btn btn-info"><?php echo IMAGE_EDIT_PRODUCT; ?></a>&nbsp;
                    <a href="<?php echo $attribute_controller_link; ?>" class="btn btn-warning"><?php echo IMAGE_EDIT_ATTRIBUTES; ?></a>
                </div>
            </div>
        <?php echo '</form>';
?>
        </div>
<?php
    }

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
        $products_image_match_array = [];
        if ($pInfo->products_image !== '') {
            $ih_admin->findAdditionalImages($products_image_match_array, $products_image_directory, $products_image_base);
        }
?>
        <div id="ih-prod" class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <td><b><?php echo TEXT_PRODUCT_INFO; ?></b></td>
                        <td><?php echo '#' . $pInfo->products_id . ' &mdash; ' . $pInfo->products_name; ?></td>
                    </tr>
<?php
        if ($pInfo->products_model !== '') {
?>
                    <tr>
                        <td><b><?php echo TEXT_PRODUCTS_MODEL; ?></b></td>
                        <td><?php echo $pInfo->products_model; ?></td>
                    </tr>
<?php
        }
?>
                    <tr>
                        <td><b><?php echo TEXT_PRICE; ?></b></td>
                        <td><?php
                            echo zen_get_products_display_price($products_filter);
                            echo zen_get_products_price_is_priced_by_attributes($products_filter) ? '<br><span>' . TEXT_PRICED_BY_ATTRIBUTES . '</span>' : '';
                            echo zen_get_products_quantity_min_units_display($products_filter);
                        ?></td>
                    </tr>
<?php
        if ($pInfo->products_image !== '') {
            $image_info = pathinfo($pInfo->products_image);
            $dirname = ($image_info['dirname'] === '.') ? '' : $image_info['dirname'];
?>
                    <tr>
                        <td><b><?php echo TEXT_IMAGE_BASE_DIR; ?></b></td>
                        <td><?php echo DIR_WS_IMAGES . $dirname; ?></td>
                    </tr>
<?php
        }
?>
                </table>
            </div>
            <div class="col-md-6 text-center">
<?php
        if (IH_RESIZE === 'no') {
            $image_resizing = false;
            $resizing_heading = sprintf(IH_RESIZE_INSTRUCTIONS_HEADING, IH_RESIZE_NOT);
            $resizing_instructions = sprintf(IH_RESIZE_INSTRUCTIONS, IH_RESIZE_ENABLE);
            $resize_heading_class = 'text-danger';
        } else {
            $image_resizing = true;
            $resizing_heading = sprintf(IH_RESIZE_INSTRUCTIONS_HEADING, '');
            $resizing_instructions = sprintf(IH_RESIZE_INSTRUCTIONS, IH_RESIZE_DISABLE);
            $resize_heading_class = 'text-success';
        }
?>
                <h5 class="<?php echo $resize_heading_class; ?>"><?php echo $resizing_heading; ?></h5>
                <p><?php echo $resizing_instructions; ?></p>
                <?php echo zen_draw_form('config', FILENAME_IMAGE_HANDLER, zen_get_all_get_params(['action']) . '&action=toggle_config'); ?>
                    <button type="submit" class="btn btn-danger btn-sm form-inline"><?php echo IH_BUTTON_RESIZE_TOGGLE; ?></button>
                <?php echo '</form>'; ?>
            </div>
        </div>

        <div class="row">
            <p class="text-center"><?php echo TEXT_TABLE_CAPTION_INSTRUCTIONS; ?></p>
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft">
                <table class="table">
                    <tr class="dataTableHeadingRow">
                        <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_PHOTO_NAME; ?></th>
                        <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILETYPE; ?></th><?php //added nigel ?>
                        <th class="dataTableHeadingContent text-center"><?php echo TABLE_HEADING_BASE_SIZE; ?></th>
                        <th class="dataTableHeadingContent text-center"><?php echo TABLE_HEADING_SMALL_SIZE; ?></th>
                        <th class="dataTableHeadingContent text-center"><?php echo TABLE_HEADING_MEDIUM_SIZE; ?></th>
                        <th class="dataTableHeadingContent text-center"><?php echo TABLE_HEADING_LARGE_SIZE; ?></th>
                        <th class="dataTableHeadingContent text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                    </tr>
<?php
        $count = count($products_image_match_array);
        $no_images = ($count === 0);
        if ($no_images === true) {
?>
                    <tr>
                         <td colspan="7" class="dataTableContent text-center"><?php echo TEXT_NO_PRODUCT_IMAGES; ?></td>
                    </tr>
<?php
        } elseif ($action === '') {
            $action = 'layout_info';
        }

        $selected_image_name = '';
        $selected_image_extension = '';
        $selected_image_file = '';
        $selected_image_suffix = '';
        $main_image = true;
        foreach ($products_image_match_array as $current_image) {
            $image_info = pathinfo($current_image);
            $tmp_image_name = $image_info['filename'];
            $tmp_image_extension = '.' . $image_info['extension'];

            // -----
            // Create the additional variables to accompany the various actions.
            //
            $tmp_image_suffix = str_replace($products_image_base, '', $tmp_image_name);

            $parms = "&imgSuffix=$tmp_image_suffix&imgExtension=$tmp_image_extension";
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

            if ($main_image === true) {
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

            // -----
            // If this is the selected image, highlight it and save its name for use in the sidebar form handling.
            //
            if ((isset($_GET['imgName']) && $_GET['imgName'] === $tmp_image_name) || (!isset($_GET['imgName']) && $main_image === true)) {
?>
                    <tr id="defaultSelected" class="dataTableRowSelected" onclick="document.location.href='<?php echo $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_edit', $parms); ?>'">
<?php
                // set some details for later usage
                $selected_image_file = $tmp_image_file;
                $selected_image_width = ($image_resizing === true) ? '' : SMALL_IMAGE_WIDTH;
                $selected_image_height = ($image_resizing === true) ? '' : SMALL_IMAGE_HEIGHT;
                $selected_image_file_large = DIR_WS_CATALOG . $tmp_image_file_large;
                $selected_image_large_width = ($image_resizing === true) ? '' : LARGE_IMAGE_MAX_WIDTH;
                $selected_image_large_height = ($image_resizing === true) ? '' : LARGE_IMAGE_MAX_HEIGHT;
                $selected_image_name = $tmp_image_name;
                $selected_image_suffix = str_replace($products_image_base, '', $tmp_image_name);
                $selected_image_extension = $tmp_image_extension;
                $selected_is_main = $main_image;
                $selected_parms = "&imgSuffix=$selected_image_suffix&imgExtension=$selected_image_extension";
            } else {
?>
                    <tr class="dataTableRow" onclick="document.location.href='<?php echo $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_info', $parms); ?>'">
<?php
            }
?>
                        <td class="dataTableContent"><?php echo $tmp_image_name; ?></td>
                        <td class="dataTableContent"<?php echo ($products_image_extension !== $tmp_image_extension ? ' style="color:red;"' : ''); ?>><?php echo $tmp_image_extension; ?></td>
                        <td class="dataTableContent text-center"><?php echo $text_base_size; ?></td>
<?php
            $preview_image = $tmp_image_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
            list($width, $height) = getimagesize(DIR_FS_CATALOG . $preview_image);
            $width = min($width, (int)IMAGE_SHOPPING_CART_WIDTH);
            $height = min($height, (int)IMAGE_SHOPPING_CART_HEIGHT);
?>
                        <td class="dataTableContent text-center"><?php echo zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height) . '<br>' . $text_default_size; ?></td>
<?php
            if ($main_image === false) {
?>
                        <td class="dataTableContent text-center"><?php echo TEXT_NOT_NEEDED; ?></td>
<?php
            } else {
                $preview_image = $tmp_image_medium_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
                list($width, $height) = getimagesize(DIR_FS_CATALOG . $preview_image);
                $width = min($width, (int)IMAGE_SHOPPING_CART_WIDTH);
                $height = min($height, (int)IMAGE_SHOPPING_CART_HEIGHT);
                $the_image = zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height);
                $delete_link = '';
                if (is_file($image_file_medium_full)) {
                    $delete_link = '<br>';
                    $delete_link .= zen_draw_form("quick_del_md", FILENAME_IMAGE_HANDLER, zen_get_all_get_params(['action']) . '&action=quick_delete');
                    $delete_link .= zen_draw_hidden_field('qdFile', $image_file_medium);
                    $delete_link .= '<input type="submit" class="btn btn-danger" value ="' . IMAGE_DELETE . '">';
                    $delete_link .= '</form>';
                }
?>
                        <td class="dataTableContent text-center"><?php echo $the_image . '<br>' . $text_medium_size . $delete_link; ?></td>
<?php
            }

            $preview_image = $tmp_image_large_preview->get_resized_image(IMAGE_SHOPPING_CART_WIDTH, IMAGE_SHOPPING_CART_HEIGHT, 'generic');
            list($width, $height) = getimagesize(DIR_FS_CATALOG . $preview_image);
            $width = min($width, (int)IMAGE_SHOPPING_CART_WIDTH);
            $height = min ($height, (int)IMAGE_SHOPPING_CART_HEIGHT);
            $the_image = zen_image(DIR_WS_CATALOG . $preview_image, addslashes($pInfo->products_name), $width, $height);
            $delete_link = '';
            if (is_file($image_file_large_full)) {
                $delete_link = '<br>';
                $delete_link .= zen_draw_form("quick_del_lg", FILENAME_IMAGE_HANDLER, zen_get_all_get_params(['action']) . '&action=quick_delete');
                $delete_link .= zen_draw_hidden_field('qdFile', $image_file_large);
                $delete_link .= '<input type="submit" class="btn btn-danger" value ="' . IMAGE_DELETE . '">';
                $delete_link .= '</form>';
            }
?>
                        <td class="dataTableContent text-center"><?php echo $the_image . '<br>' . $text_large_size . $delete_link; ?></td>
                        <td class="dataTableContent text-right">
<?php
            if ((isset($_GET['imgName']) && $_GET['imgName'] === $tmp_image_name) || (!isset($_GET['imgName']) && $main_image === true)) {
                echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
            } else {
                echo ' <a href="' . $ih_admin->imageHandlerHrefLink($tmp_image_name, $products_filter, 'layout_info', $parms) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
            }
?>
                        </td>
                    </tr>
<?php
            // -----
            // Subsequent passes through the loop are for additional images.
            //
            $main_image = false;

        } // for each photo loop

        $new_link = $ih_admin->imageHandlerHrefLink('', $products_filter, 'layout_new');
?>
                    <tr class="dataTableRow">
                        <td colspan="7" class="text-right"><a href="<?php echo $new_link; ?>" class="btn btn-info"><?php echo IH_IMAGE_NEW_FILE; ?></a></td>
                    </tr>
                </table>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight">
<?php
        $heading = [];
        $contents = [];
        $imgNameStr = '';
        $editing = false;
        $form_parameters = zen_get_all_get_params(['action']);
        switch ($action) {
            // -----
            // Sidebar contents when viewing an image's defined layout.
            //
            case 'layout_info':
                $selected_image_file = DIR_WS_CATALOG . $selected_image_file;
                $heading[] = [
                    'text' => '<strong>' . TEXT_INFO_IMAGE_INFO . '</strong>'
                ];
                $contents = [
                    'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, "$form_parameters&action=save", 'post', 'enctype="multipart/form-data"')
                ];
                $contents[] = [
                    'text' => '<strong>' . TEXT_INFO_NAME. ': </strong>' . $selected_image_name . '<br>'
                ];
                $contents[] = [
                    'text' => '<strong>' . TEXT_INFO_FILE_TYPE . ': </strong>' . $selected_image_extension . '<br>'
                ];
                $contents[] = [
                    'align' => 'center',
                    'text' => zen_image($selected_image_file, addslashes($pInfo->products_name), $selected_image_width, $selected_image_height)
                ];
                $contents[] = [
                    'align' => 'center',
                    'text' => '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#imageModal">' . TEXT_CLICK_TO_ENLARGE . '</button>',
                ];

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

                if ($products_image_extension === $selected_image_extension) {
                    $edit_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_edit', $selected_parms);
                    $edit_button = '<a href="' . $edit_link . '" class="btn btn-warning">' . IH_IMAGE_EDIT . '</a> &nbsp; ';
                }
                $contents[] = [
                    'align' => 'center',
                    'text' => "<br>$edit_button$delete_button"
                ];
                break;

            // -----
            // Sidebar content when either editing an existing image's information or when creating
            // a new image.
            //
            case 'layout_edit':
                $editing = true;
                $hidden_vars = zen_draw_hidden_field('saveType', 'edit') . zen_draw_hidden_field('imgSuffix', $selected_image_suffix);
                $heading[] = [
                    'text' => '<strong>' . (($selected_is_main === true) ? TEXT_INFO_EDIT_PHOTO : TEXT_INFO_EDIT_ADDL_PHOTO) . '</strong>'
                ];

            case 'layout_new':                  //- Fall through from above!
                if ($editing === false) {
                    $hidden_vars = zen_draw_hidden_field('saveType', ($no_images === true) ? 'new_main' : 'new_addl');
                    $heading[] = [
                        'text' => '<strong>' . (($no_images === true) ? TEXT_INFO_NEW_PHOTO : TEXT_INFO_NEW_ADDL_PHOTO) . '</strong>'
                    ];
                }

                $contents = [
                    'form' => zen_draw_form('image_define', FILENAME_IMAGE_HANDLER, "$form_parameters&action=save", 'post', 'enctype="multipart/form-data"')
                ];

                // check if this is a master image or if no images exist
                if ($no_images === true) {
                    $contents[] = [
                        'text' => '<strong>' . TEXT_INFO_IMAGE_BASE_NAME . '</strong><br>'
                    ];
                    $contents[] = [
                        'text' => zen_draw_input_field('imgBase', '', 'class="form-control" size="30"')
                    ];

                    $no_show_dirs = [
                        '.',
                        '..',
                        'original',
                        'medium',
                        'large'
                    ];
                    $dir = dir(DIR_FS_CATALOG_IMAGES);
                    $dir_info[] = ['id' => '', 'text' => TEXT_INFO_MAIN_DIR];
                    while ($file = $dir->read()) {
                        if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) !== 'CVS' && !in_array($file, $no_show_dirs)) {
                            $dir_info[] = ['id' => $file . '/', 'text' => $file];
                        }
                    }
                    $contents[] = [
                        'text' => '<br><strong>' . TEXT_INFO_BASE_DIR . '</strong><br>' . TEXT_INFO_NEW_DIR
                    ];
                    $contents[] = [
                        'text' => '<strong>' . TEXT_INFO_IMAGE_DIR . '</strong>' . zen_draw_pull_down_menu('imgBaseDir', $dir_info, '', 'class="form-control"')
                    ];
                    $contents[] = [
                        'text' => TEXT_INFO_OR . ' ' . zen_draw_input_field('imgNewBaseDir', '', 'class="form-control"')
                    ];
                } elseif ($editing === false) {
                    $contents[] = [
                        'text' => '<strong>' . TEXT_INFO_IMAGE_SUFFIX . '</strong><br>' . TEXT_INFO_USE_AUTO_SUFFIX . '<br>'
                    ];
                    $contents[] = [
                        'text' => zen_draw_input_field('imgSuffix', $selected_image_suffix, 'class="form-control"')
                    ];
                }

                // -----
                // Set up the "acceptable" file types for the form, depending on whether or not the active product
                // currently has an image defined.
                //
                if ($no_images === true) {
                    $accept = 'image/jpeg,image/jpg,image/gif,image/png';
                } else {
                    switch (strtolower($products_image_extension)) {
                        case '.gif':
                            $accept = 'image/gif';
                            break;
                        case '.png':
                            $accept = 'image/png';
                            break;
                        case '.jpg':
                        case '.jpeg':       //- Fall through from above
                            $accept = 'image/jpeg,image/jpg';
                            break;
                        default:
                            $accept = 'image/jpeg,/image/jpg,image/gif,image/png';
                            break;
                    }
                }
                $file_parms = 'accept="' . $accept . '"';

                // Image fields
                $base_image_note = ($action === 'layout_new') ? '&nbsp;&nbsp;<strong class="errorText">(required)</strong>' : '';
                $contents[] = [
                    'text' => '<br><strong>' . TEXT_INFO_DEFAULT_IMAGE . '</strong>' . $base_image_note . '<br>'
                        . TEXT_INFO_DEFAULT_IMAGE_HELP . '<br>'
                        . zen_draw_input_field('default_image', '', 'class="form-control" size="20" ' . $file_parms, false, 'file') . '<br>' . $selected_image_name . $selected_image_extension
                ];

                if ($editing === true) {
                    if ($selected_is_main === true) {
                        $contents[] = [
                            'text' =>
                                '<label class="radio-inline">' . zen_draw_radio_field('imgNaming', 'new_discard', false) . IH_NEW_NAME_DISCARD_IMAGES . '</label>' .
                                '<br><br>' .
                                '<label class="radio-inline">' . zen_draw_radio_field('imgNaming', 'keep_name', true) . IH_KEEP_NAME . '</label>',
                        ];
                    }
                }

                if (($editing === true && $selected_image_suffix === '') || ($editing === false && $no_images === true)) {
                    $contents[] = [
                        'text' => '<br><strong>' . TEXT_MEDIUM_FILE_IMAGE . '</strong><br>' . zen_draw_input_field('medium_image', '', 'class="form-control" size="20" ' . $file_parms, false, 'file') . '<br>'
                    ];
                }

                $contents[] = [
                    'text' => '<br><strong>' . TEXT_LARGE_FILE_IMAGE . '</strong><br>' . zen_draw_input_field('large_image', '', 'class="form-control" size="20" ' . $file_parms, false, 'file') . '<br>'
                ];


                if ($editing === false) {
                    $cancel_button_link = $ih_admin->imageHandlerHrefLink('', $products_filter, '', '&ih_page=manager');
                } else {
                    $cancel_button_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_info');
                }
                $cancel_button = '<a href="' . $cancel_button_link . '" class="btn btn-warning">' . IMAGE_CANCEL . '</a>';
                $contents[] = [
                    'align' => 'center',
                    'text' => '<br>' . $cancel_button . '&nbsp;' . '<input type="submit" class="btn btn-primary" value="' . IMAGE_SAVE . '">' . $hidden_vars
                ];
                break;

            // -----
            // Sidebar content when an image-delete is requested.
            //
            case 'layout_delete':

                $imgStr = "&imgSuffix=$selected_image_suffix&imgExtension=$selected_image_extension";

                // show new button
                $heading[] = [
                    'text' => '<strong>' . sprintf(TEXT_INFO_CONFIRM_DELETE, (($selected_is_main === true) ? TEXT_MAIN : TEXT_ADDITIONAL)) . '</strong>'
                ];
                $hidden_vars = zen_draw_hidden_field('imgSuffix', $selected_image_suffix);
                $hidden_vars .= zen_draw_hidden_field('imgExtension', $selected_image_extension);
                $hidden_vars .= zen_draw_hidden_field('imgName', $selected_image_name);
                $page_parameters = zen_get_all_get_params(['action', 'imgName', 'imgSuffix', 'imgExtension']) . 'action=delete';
                $contents = [
                    'form' => zen_draw_form('image_delete', FILENAME_IMAGE_HANDLER, $page_parameters) . $hidden_vars
                ];
                $contents[] = [
                    'text' => '<br>' . $products_image_directory . $products_image_base . $selected_image_suffix . $selected_image_extension
                ];
                $contents[] = [
                    'text' => '<br>' . TEXT_INFO_CONFIRM_DELETE_SURE
                ];
                if ($selected_image_suffix === '') {
                    $contents[] = [
                        'text' => zen_draw_checkbox_field('delete_from_db_only', 'Y', false) . IH_DELETE_FROM_DB_ONLY
                    ];
                }

                $cancel_button_link = $ih_admin->imageHandlerHrefLink($selected_image_name, $products_filter, 'layout_info');
                $cancel_button = '<a href="' . $cancel_button_link . '" class="btn btn-warning">' . IMAGE_CANCEL . '</a>';
                $contents[] = [
                    'align' => 'center',
                    'text' => '<br>' . $cancel_button . '&nbsp;' . '<input type="submit" class="btn btn-danger" value ="' . IMAGE_DELETE . '">'
                ];
                break;

            // -----
            // Default content, used on initial (no parameters) page display.
            //
            default:
                // show new button
                $heading[] = [
                    'text' => '<strong>' . TEXT_INFO_SELECT_ACTION . '</strong>'
                ];
                $contents[] = [
                    'text' => '<br>' . (($no_images === true) ? TEXT_INFO_CLICK_TO_ADD_MAIN : TEXT_INFO_CLICK_TO_ADD_ADDL)
                ];
                break;
        }

        if (!empty($heading) && !empty($contents)) {
            $box = new box();
            echo $box->infoBox($heading, $contents);
        }
?>
            </div>
        </div>
<?php
    } // if products_filter

/** ------------------------------------
 * PREVIEW TABPAGE
 */
} elseif ($ih_page === 'preview') {
      $images = [];
      $pngimage = new ih_image(basename($ihConf['dir']['admin']) . "/" . 'images/ih-test.png', (int)$ihConf['small']['width'], (int)$ihConf['small']['height']);
      $images['pngsource'] = $pngimage->get_resized_image((int)$ihConf['small']['width'], (int)$ihConf['small']['height'], 'orig');
      $images['pngsmall'] = $pngimage->get_resized_image($ihConf['small']['width'], $ihConf['small']['height'], 'small');
      $images['pngmedium'] = $pngimage->get_resized_image($ihConf['medium']['width'], $ihConf['medium']['height'], 'medium');
      $images['pnglarge'] = $pngimage->get_resized_image($ihConf['large']['width'], $ihConf['large']['height'], 'large');

      $jpgimage = new ih_image(basename($ihConf['dir']['admin']) . "/" . 'images/ih-test.jpg', (int)$ihConf['small']['width'], (int)$ihConf['small']['height']);
      $images['jpgsource'] = $jpgimage->get_resized_image((int)$ihConf['small']['width'], (int)$ihConf['small']['height'], 'orig');
      $images['jpgsmall'] = $jpgimage->get_resized_image($ihConf['small']['width'], $ihConf['small']['height'], 'small');
      $images['jpgmedium'] = $jpgimage->get_resized_image($ihConf['medium']['width'], $ihConf['medium']['height'], 'medium');
      $images['jpglarge'] = $jpgimage->get_resized_image($ihConf['large']['width'], $ihConf['large']['height'], 'large');

      $gifimage = new ih_image(basename($ihConf['dir']['admin']) . "/" . 'images/ih-test.gif', (int)$ihConf['small']['width'], (int)$ihConf['small']['height']);
      $images['gifsource'] = $gifimage->get_resized_image((int)$ihConf['small']['width'], (int)$ihConf['small']['height'], 'orig');
      $images['gifsmall'] = $gifimage->get_resized_image($ihConf['small']['width'], $ihConf['small']['height'], 'small');
      $images['gifmedium'] = $gifimage->get_resized_image($ihConf['medium']['width'], $ihConf['medium']['height'], 'medium');
      $images['giflarge'] = $gifimage->get_resized_image($ihConf['large']['width'], $ihConf['large']['height'], 'large');
?>
        <table class="table">
            <tr>
                <th><?php echo IH_SOURCE_TYPE; ?></th>
                <th><?php echo IH_SOURCE_IMAGE; ?></th>
                <th><?php echo IH_SMALL_IMAGE; ?></th>
                <th><?php echo IH_MEDIUM_IMAGE; ?></th>
            </tr>

            <tr>
                <td><strong>png</strong></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['pngsource']?>" alt="png source" title="png source" /></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['pngsmall']?>" alt="png small" title="png small" /></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['pngmedium']?>" alt="png medium" title="png medium" /></td>
            </tr>

            <tr>
                <td><strong>jpg</strong></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['jpgsource']?>" alt="jpg source" title="jpg source" /></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['jpgsmall']?>" alt="jpg small" title="jpg small" /></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['jpgmedium']?>" alt="jpg medium" title="jpg medium" /></td>
            </tr>

            <tr>
                <td><strong>gif</strong></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['gifsource']?>" alt="gif source" title="gif source" /></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['gifsmall']?>" alt="gif small" title="gif small" /></td>
                <td><img src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . $images['gifmedium']?>" alt="gif medium" title="gif medium" /></td>
            </tr>
        </table>
<?php
/** -------------------------------------
 * ABOUT TABPAGE
 */
} else {
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
<?php
if (!empty($selected_image_file)) {
?>
    <div id="imageModal" class="modal fade">
          <div class="modal-dialog">
               <div class="modal-content">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                         <h4 class="modal-title text-center"><?php echo addslashes($pInfo->products_name); ?></h4>
                    </div>
                    <div class="modal-body text-center">
                        <?php echo zen_image($selected_image_file_large, addslashes($pInfo->products_name), $selected_image_large_width, $selected_image_large_height); ?> 
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
               </div>
          </div>
    </div>
<?php
}
?>
    <?php require DIR_WS_INCLUDES . 'footer.php'; ?>
</body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
