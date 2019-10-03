<?php
// -----
// Part of the "Image Handler" plugin, v5.0.0 and later, by Cindy Merkin a.k.a. lat9 (cindy@vinosdefrutastropicales.com)
// Copyright (c) 2018-2019 Vinos de Frutas Tropicales
//
require 'includes/application_top.php';

// -----
// Load, and create an instance of, the "helper" class for the Image Handler.  This class
// consolidates the various functions previously present in this module.
//
// Note: The $ihConf array is loaded as part of /admin/includes/functions/extra_functions/functions_bmz_image_handler.php.
//
require DIR_WS_CLASSES . 'ImageHandlerAdmin.php';
$ih_admin = new ImageHandlerAdmin();
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<style type="text/css">
<!--
th, td { padding: 0.5em; }
#ih-main { float: left; }
#ih-conf::after { clear: both; }
#ih-main tr:nth-child(even), #ih-conf tr:nth-child(even) {background: #ebebeb; }
.ih-group { background-color: #ccc; font-weight: bold; text-align: center; }
.ih-sub { text-align: right; }
tr.ih-error td:last-child { color: red; font-weight: bold; }
tr span { font-size: smaller; }
-->
</style>
<script src="includes/menu.js"></script>
<script src="includes/general.js"></script>
<script>
<!--
function init()
{
    cssjsmenu('navbar');
    if (document.getElementById) {
        var kill = document.getElementById('hoverJS');
        kill.disabled = true;
    }
}
// -->
</script>
</head>
<body onload="init();">
<!-- header //-->
<?php 
require DIR_WS_INCLUDES . 'header.php';

// -----
// Set up arrays to display the information in the table below.
//
define('CHECK_NONE', 0);
define('CHECK_INTEGER', 1);
define('CHECK_QUALITY', 2);
define('CHECK_BACKGROUND', 3);
define('CHECK_BOOLEAN', 4);
define('CHECK_ARRAY', 5);
define('CHECK_FILETYPE', 6);
define('CHECK_DIR', 7);
define('CHECK_SIZE', 8);
$config_values = array(
    'configuration' => array(
        'IH_VERSION' => array('check' => CHECK_NONE),
        'IH_RESIZE' => array('check' => CHECK_NONE),
        'WATERMARK_GRAVITY' => array('check' => CHECK_NONE),
        'IH_CACHE_NAMING' => array('check' => CHECK_NONE),
        'SMALL_IMAGE_WIDTH' => array('check' => CHECK_INTEGER),
        'SMALL_IMAGE_HEIGHT' => array('check' => CHECK_INTEGER),
        'SMALL_IMAGE_FILETYPE' => array('check' => CHECK_FILETYPE),
        'SMALL_IMAGE_BACKGROUND' => array('check' => CHECK_BACKGROUND),
        'SMALL_IMAGE_QUALITY' => array('check' => CHECK_QUALITY),
        'WATERMARK_SMALL_IMAGES' => array('check' => CHECK_NONE),
        'ZOOM_SMALL_IMAGES' => array('check' => CHECK_NONE),
        'ZOOM_IMAGE_SIZE' => array('check' => CHECK_NONE),
        'MEDIUM_IMAGE_WIDTH' => array('check' => CHECK_INTEGER),
        'MEDIUM_IMAGE_HEIGHT' => array('check' => CHECK_INTEGER),
        'IMAGE_SUFFIX_MEDIUM' => array('check' => CHECK_NONE),
        'MEDIUM_IMAGE_FILETYPE' => array('check' => CHECK_FILETYPE),
        'MEDIUM_IMAGE_BACKGROUND' => array('check' => CHECK_BACKGROUND),
        'MEDIUM_IMAGE_QUALITY' => array('check' => CHECK_QUALITY),
        'WATERMARK_MEDIUM_IMAGES' => array('check' => CHECK_NONE),
        'LARGE_IMAGE_MAX_WIDTH' => array('check' => CHECK_INTEGER),
        'LARGE_IMAGE_MAX_HEIGHT' => array('check' => CHECK_INTEGER),
        'IMAGE_SUFFIX_LARGE' => array('check' => CHECK_NONE),
        'LARGE_IMAGE_FILETYPE' => array('check' => CHECK_FILETYPE),
        'LARGE_IMAGE_BACKGROUND' => array('check' => CHECK_BACKGROUND),
        'LARGE_IMAGE_QUALITY' => array('check' => CHECK_QUALITY),
        'WATERMARK_LARGE_IMAGES' => array('check' => CHECK_NONE),
    ),
    'ihConf' => array(
        'noresize_key' => array('check' => CHECK_NONE),
        'noresize_dirs' => array('check' => CHECK_ARRAY),
        'trans_threshold' => array('check' => CHECK_NONE),
        'im_convert' => array('check' => CHECK_NONE),
        'gdlib' => array('check' => CHECK_INTEGER),
        'default' => array(
            'check' => CHECK_ARRAY,
            'fields' => array(
                'bg' => array('check' => CHECK_BACKGROUND),
                'quality' => array('check' => CHECK_QUALITY),
            ),
        ),
        'resize' => array('check' => CHECK_BOOLEAN),
        'dir' => array(
            'check' => CHECK_ARRAY,
            'fields' => array(
                'docroot' => array('check' => CHECK_DIR),
                'images' => array('check' => CHECK_DIR),
                'admin' => array('check' => CHECK_DIR),
            ),
        ),
        'small' => array(
            'check' => CHECK_ARRAY,
            'fields' => array(
                'width' => array('check' => CHECK_INTEGER),
                'height' => array('check' => CHECK_INTEGER),
                'filetype' => array('check' => CHECK_FILETYPE),
                'bg' => array('check' => CHECK_BACKGROUND),
                'quality' => array('check' => CHECK_QUALITY),
                'watermark' => array('check' => CHECK_BOOLEAN),
                'zoom' => array('check' => CHECK_BOOLEAN),
                'size' => array('check' => CHECK_SIZE),
            ),
        ),
        'medium' => array(
            'check' => CHECK_ARRAY,
            'fields' => array(
                'prefix' => array('check' => CHECK_NONE),
                'suffix' => array('check' => CHECK_NONE),
                'width' => array('check' => CHECK_INTEGER),
                'height' => array('check' => CHECK_INTEGER),
                'filetype' => array('check' => CHECK_FILETYPE),
                'bg' => array('check' => CHECK_BACKGROUND),
                'quality' => array('check' => CHECK_QUALITY),
                'watermark' => array('check' => CHECK_BOOLEAN),
            ),
        ),
        'large' => array(
            'check' => CHECK_ARRAY,
            'fields' => array(
                'prefix' => array('check' => CHECK_NONE),
                'suffix' => array('check' => CHECK_NONE),
                'width' => array('check' => CHECK_INTEGER),
                'height' => array('check' => CHECK_INTEGER),
                'filetype' => array('check' => CHECK_FILETYPE),
                'bg' => array('check' => CHECK_BACKGROUND),
                'quality' => array('check' => CHECK_QUALITY),
                'watermark' => array('check' => CHECK_BOOLEAN),
            ),
        ),
        'watermark' => array(
            'check' => CHECK_ARRAY,
            'fields' => array(
                'gravity' => array('check' => CHECK_NONE),
            ),
        ),
    )   
);
?>
<!-- header_eof //-->
<!-- body //-->
<h1><?php echo HEADING_TITLE; ?></h1>
<p><?php echo sprintf(INSTRUCTIONS, DIR_FS_CATALOG . 'includes/extra_configures/bmx_image_handler_conf.php', DIR_FS_CATALOG . 'includes/functions/extra_functions/functions_bmz_image_handler.php'); ?></p>
<table id="ih-main">
    <tr>
        <td colspan="3" class="ih-group"><?php echo sprintf(CONFIG_HEADING, zen_href_link(FILENAME_CONFIGURATION, 'gID=4')); ?></td>
    </tr>
<?php
foreach ($config_values['configuration'] as $config_name => $config_options) {
    $entry_error = false;
    if (!defined($config_name)) {
        $entry_value = 'not defined';
        $entry_error = true;
    } else {
        $entry_value = constant($config_name);
        $info = $db->Execute(
            "SELECT configuration_id, configuration_title
               FROM " . TABLE_CONFIGURATION . "
              WHERE configuration_key = '$config_name'
              LIMIT 1"
        );
        if ($info->EOF) {
            $entry_title = 'not found';
            $entry_error = true;
            $config_link = $config_name;
        } else {
            $entry_title = $info->fields['configuration_title'];
            $config_link = '<a href="' . zen_href_link(FILENAME_CONFIGURATION, 'gID=4&amp;cID=' . $info->fields['configuration_id'] . '&amp;action=edit') . '">' . $config_name . '</a>';
        }
        $entry_message = '&nbsp;';
        switch ($config_options['check']) {
            // -----
            // Check that the value is a positive integer (no decimal points)
            //
            case CHECK_INTEGER:
                $entry_error = $ih_admin->validatePositiveInteger($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_NOT_INTEGER;
                }
                break;
            case CHECK_QUALITY:
                $entry_error = $ih_admin->validateQuality($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_INVALID_QUALITY;
                }
                break;
            case CHECK_BACKGROUND:
                $entry_error = $ih_admin->validateBackground($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_INVALID_BACKGROUND;
                }
                break;
            case CHECK_FILETYPE:
                $entry_error = $ih_admin->validateFiletype($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_INVALID_FILETYPE;
                }
                break;
            default:
                break;
        }
    }
?>
    <tr<?php echo ($entry_error) ? ' class="ih-error"' : ''; ?>>
        <td><?php echo $config_link; ?></td>
        <td><?php echo $entry_title; ?></td>
        <td><?php echo $entry_value; ?> <span><?php echo $entry_message; ?></span></td>
    </tr>
<?php
}
?>
</table>
<table id="ih-conf">
    <tr>
        <td colspan="2" class="ih-group">Values from the $ihConf array</td>
    </tr>
<?php
foreach ($config_values['ihConf'] as $key => $values) {
    $entry_error = false;
    $single_entry = true;
    if (!isset($ihConf[$key])) {
        $entry_error = true;
        $entry_value = 'not set';
        $entry_message = 'Missing key value from $ihConf array.';
    } else {
        $entry_value = $ihConf[$key];
        $entry_message = '&nbsp;';
        switch ($values['check']) {
            // -----
            // Check that the value is a positive integer (no decimal points)
            //
            case CHECK_INTEGER:
                $entry_error = $ih_admin->validatePositiveInteger($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_NOT_INTEGER;
                }
                break;
            case CHECK_QUALITY:
                $entry_error = $ih_admin->validateQuality($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_INVALID_QUALITY;
                }
                break;
            case CHECK_BACKGROUND:
                $entry_error = $ih_admin->validateBackground($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_INVALID_BACKGROUND;
                }
                break;
            case CHECK_BOOLEAN:
                $entry_error = $ih_admin->validateBoolean($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_NOT_BOOLEAN;
                }
                break;
            case CHECK_FILETYPE:
                $entry_error = $ih_admin->validateFiletype($entry_value);
                if ($entry_error) {
                    $entry_message = ERROR_INVALID_FILETYPE;
                }
                break;
            case CHECK_ARRAY:
                if (!is_array($entry_value)) {
                    $entry_error = true;
                    $entry_message = ERROR_NOT_ARRAY;
                    $entry_value = json_encode($entry_value);
                } else {
                    $single_entry = false;
                    $entry_value = (isset($values['fields'])) ? '&nbsp;' : json_encode($entry_value);
                }
                break;
            default:
                break;
        }
    }
    
    $entry_value = ($entry_value === true) ? 'true' : (($entry_value === false) ? 'false' : $entry_value);
    if ($entry_message != '&nbsp;') {
        $entry_message = "($entry_message)";
    }
?>
    <tr<?php echo ($entry_error) ? ' class="ih-error"' : ''; ?>>
        <td><?php echo '$ihConf[' . $key . ']'; ?></td>
        <td><?php echo $entry_value; ?> <span><?php echo $entry_message; ?></span></td>
    </tr>
<?php
    if (!$single_entry && isset($values['fields'])) {
        foreach ($values['fields'] as $subkey => $subvalues) {
            $subkey_value = $ihConf[$key][$subkey];
            $entry_message = '&nbsp;';
            switch ($subvalues['check']) {
                // -----
                // Check that the value is a positive integer (no decimal points)
                //
                case CHECK_INTEGER:
                    $entry_error = $ih_admin->validatePositiveInteger($subkey_value);
                    if ($entry_error) {
                        $entry_message = ERROR_NOT_INTEGER;
                    }
                    break;
                case CHECK_QUALITY:
                    $entry_error = $ih_admin->validateQuality($subkey_value);
                    if ($entry_error) {
                        $entry_message = ERROR_INVALID_QUALITY;
                    }
                    break;
                case CHECK_BACKGROUND:
                    $entry_error = $ih_admin->validateBackground($subkey_value);
                    if ($entry_error) {
                        $entry_message = ERROR_INVALID_BACKGROUND;
                    }
                    break;
                case CHECK_BOOLEAN:
                    $entry_error = $ih_admin->validateBoolean($subkey_value);
                    if ($entry_error) {
                        $entry_message = ERROR_NOT_BOOLEAN;
                    }
                    break;
                case CHECK_FILETYPE:
                    $entry_error = $ih_admin->validateFiletype($subkey_value);
                    if ($entry_error) {
                        $entry_message = ERROR_INVALID_FILETYPE;
                    }
                    break;
                default:
                    break;
            }
            if ($entry_message != '&nbsp;') {
                $entry_message = "($entry_message)";
            }
?>
    <tr<?php echo ($entry_error) ? ' class="ih-error"' : ''; ?>>
        <td class="ih-sub"><?php echo $subkey; ?></td>
        <td><?php echo ($subkey_value === true) ? 'true' : (($subkey_value === false) ? 'false' : $subkey_value); ?> <span><?php echo $entry_message; ?></span></td>
    </tr>
<?php
        }
    }
}
?>
</table>
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
