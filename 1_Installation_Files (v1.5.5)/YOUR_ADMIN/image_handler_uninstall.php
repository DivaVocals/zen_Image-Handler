<?php
// -----
// Part of the "Image Handler" plugin, v5.0.0 and later, by Cindy Merkin a.k.a. lat9 (cindy@vinosdefrutastropicales.com)
// Copyright (c) 2017-2019 Vinos de Frutas Tropicales
//
require 'includes/application_top.php';

// -----
// If the admin has confirmed the removal of "Image Handler" ...
//
if (isset($_POST['action']) && $_POST['action'] == 'uninstall') {
    // -----
    // Build up a list of files to be unconditionally removed.
    //
    // Note: The two template-override files are **not** removed.  They do no harm and might
    // be used by the Fual Slimbox or ColorBox plugins!
    //
    $files_to_remove = array(
        'storefront' => array(
            'auto_loaders/config.image_handler.php',
            'classes/bmz_image_handler.class.php',
            'classes/bmz_gif_info.class.php',
            'classes/observers/ImageHandlerObserver.php',
            'extra_configures/bmz_image_handler_conf.php',
            'extra_configures/bmz_io_conf.php',
            'functions/extra_functions/functions_bmz_image_handler.php',
            'functions/extra_functions/functions_bmz_io.php',
        ),
        'template' => array(
            'css/style_imagehover.css',
            'jscript/jscript_imagehover.js',
        ),
       'admin_includes' => array(
            'ih_manager.php',
            'auto_loaders/config.image_handler.php',
            'classes/ImageHandlerAdmin.php',
            'extra_configures/bmz_image_handler_conf.php',
            'extra_configures/bmz_io_conf.php',
            'extra_datafiles/image_handler.php',
            'functions/extra_functions/functions_bmz_image_handler.php',
            'functions/extra_functions/functions_bmz_io.php',
            'init_includes/init_image_handler.php',
            'languages/english/image_handler.php',
            'languages/english/image_handler_about.php',
            'languages/english/image_handler_uninstall.php',
            'languages/english/image_handler_view_config.php',
            'languages/english/extra_definitions/bmz_image_handler.php',
            'languages/english/extra_definitions/bmz_language_admin.php',
            'languages/english/extra_definitions/image_handler_extra_definitions.php'
        ),
        'admin_root' => array(
            'image_handler.php',
            'image_handler_uninstall.php',
            'image_handler_view_config.php'
        ),
    );
    
    // -----
    // Now, see if either of the "large-image display" plugins are installed and, if not,
    // remove the storefront observers loaded on their behalf.
    //
    if (!defined('FUAL_SLIMBOX')) {
        $files_to_remove['storefront'][] = 'auto_loaders/config.fual_slimbox.php';
        $files_to_remove['storefront'][] = 'classes/observers/FualSlimboxObserver.php';
    }
    if (!defined('ZEN_COLORBOX_STATUS')) {
        $files_to_remove['storefront'][] = 'auto_loaders/config.colorbox.php';
        $files_to_remove['storefront'][] = 'classes/observers/ColorBoxObserver.php';
    }
    
    // -----
    // Remove those files ...
    //
    foreach ($files_to_remove as $key => $file_list) {
        switch ($key) {
            case 'storefront':
                $directory = DIR_FS_CATALOG . DIR_WS_INCLUDES;
                break;
            case 'template':
                $check = $db->Execute(
                    "SELECT template_dir
                       FROM " . TABLE_TEMPLATE_SELECT . "
                      WHERE template_language = 0"
                );
                $directory = DIR_FS_CATALOG . DIR_WS_INCLUDES . 'templates/' . $check->fields['template_dir'] . '/';
                break;
            case 'admin_includes':
                $directory = DIR_FS_ADMIN . DIR_WS_INCLUDES;
                break;
            default:
                $directory = DIR_FS_ADMIN;
                break;
        }
        foreach ($file_list as $current_file) {
            if (file_exists($directory . $current_file)) {
                unlink($directory . $current_file);
            }
        }
    }

    // -----
    // Remove the "Image Handler" database elements.
    //
    $db->Execute(
        "DELETE FROM " . TABLE_CONFIGURATION . "
          WHERE configuration_key IN 
              ( 'IH_VERSION', 'IH_RESIZE',  'ZOOM_IMAGE_SIZE', 'ZOOM_SMALL_IMAGES', 
                'SMALL_IMAGE_FILETYPE', 'SMALL_IMAGE_BACKGROUND', 'SMALL_IMAGE_QUALITY', 'WATERMARK_SMALL_IMAGES',
                'MEDIUM_IMAGE_FILETYPE', 'MEDIUM_IMAGE_BACKGROUND', 'MEDIUM_IMAGE_QUALITY', 'WATERMARK_MEDIUM_IMAGES',
                'LARGE_IMAGE_FILETYPE', 'LARGE_IMAGE_BACKGROUND', 'LARGE_IMAGE_QUALITY',  'WATERMARK_LARGE_IMAGES',
                'LARGE_IMAGE_MAX_WIDTH', 'LARGE_IMAGE_MAX_HEIGHT', 'WATERMARK_GRAVITY'
              )"
    );
    $db->Execute(
        "DELETE FROM " . TABLE_CONFIGURATION . "
          WHERE configuration_key LIKE 'IH_%'"
    );
    $db->Execute(
        "DELETE FROM " . TABLE_ADMIN_PAGES . "
          WHERE page_key IN ('configImageHandler4', 'toolsImageHandlerUninstall', 'toolsImageHandlerViewConfig' )"
    );
    
    // -----
    // Set a message notifying the admin of the removal, note the change in the activity
    // log and redirect back to the admin dashboard.
    //
    $messageStack->add_session(TEXT_MESSAGE_IH_REMOVED, 'success');
    zen_record_admin_activity(TEXT_MESSAGE_IH_REMOVED, 'info');
    zen_redirect(zen_href_link(FILENAME_DEFAULT));
}

// -----
// Set up the next-action to be performed on form-submittal and the message to display on the
// current page.  On initial entry, the admin is questioned as to whether to remove IH; on the
// first form-submittal, the admin is asked to confirm their removal request and on the next
// form-submittal, the file/configuration removal is actually performed.
//
if (!isset($_POST['action']) || $_POST['action'] != 'confirm') {
    $next_action = 'confirm';
    $current_message = TEXT_ARE_YOU_SURE;
} else {
    $next_action = 'uninstall';
    $current_message = TEXT_CONFIRMATION;
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
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
  // -->
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
        <tr>
            <td><?php echo zen_draw_form('remove', FILENAME_IMAGE_HANDLER_UNINSTALL) . zen_draw_hidden_field('action', $next_action);?>
                <p><?php echo $current_message; ?></p>
                <p><a href="<?php echo zen_href_link(FILENAME_DEFAULT); ?>" class="btn btn-warning"><?php echo IMAGE_CANCEL; ?></a>&nbsp;&nbsp;<input type="submit" class="btn btn-danger" value="<?php echo IMAGE_GO; ?>" /></p>
            </form></td>
        </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php 
require DIR_WS_INCLUDES . 'footer.php'; 
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php 
require DIR_WS_INCLUDES . 'application_bottom.php';
