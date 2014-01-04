<?php

define('IH_RESIZE_TITLE', 'IH resize images');
define('IH_RESIZE_TEXT', 'Select either -no- which is old Zen-Cart behaviour or -yes- to activate automatic resizing and caching of images. --Note: If you select -no-, all of the Image Handler specific image settings will be unavailable including: image filetype selection, background colors, compression, image hover, and watermarking-- If you want to use ImageMagick you have to specify the location of the <strong>convert</strong> binary in <em>includes/extra_configures/bmz_image_handler_conf.php</em>.');

define('SMALL_IMAGE_FILETYPE_TITLE', 'IH small images filetype');
define('SMALL_IMAGE_FILETYPE_TEXT', 'Select one of -jpg-, -gif- or -png-. Older versions of Internet Explorer -v6.0 and older- will have issues displaying -png- images with transparent areas. You better stick to -gif- for transparency if you MUST support older versions of Internet Explorer. However -png- is a MUCH BETTER format for transparency. Use -jpg- or -png- for larger images. -no_change- is old zen-cart behavior, use the same file extension for small images as uploaded image');

define('SMALL_IMAGE_BACKGROUND_TITLE', 'IH small images background');
define('SMALL_IMAGE_BACKGROUND_TEXT', 'If converted from an uploaded image with transparent areas, these areas become the specified color. Set to -transparent- to keep transparency.');

define('SMALL_IMAGE_QUALITY_TITLE', 'IH small images compression quality');
define('SMALL_IMAGE_QUALITY_TEXT', 'Specify the desired image quality for small jpg images, decimal values ranging from 0 to 100. Higher is better quality and takes more space. Default is 85 which is ok unless you have very specific needs.');

define('WATERMARK_SMALL_IMAGES_TITLE', 'IH small images watermark');
define('WATERMARK_SMALL_IMAGES_TEXT', 'Set to -yes-, if you want to show watermarked small images instead of unmarked small images.');

define('ZOOM_SMALL_IMAGES_TITLE', 'IH small images zoom on hover');
define('ZOOM_SMALL_IMAGES_TEXT', 'Set to -yes-, if you want to enable a nice zoom overlay while hovering the mouse pointer over small images.');

define('ZOOM_IMAGE_SIZE_TITLE', 'IH small images zoom on hover size');
define('ZOOM_IMAGE_SIZE_TEXT', 'Set to -Medium-, if you want to the zoom on hover display to use the medium sized image. Otherwise, to use the large sized image on hover, set to -Large-');

define('MEDIUM_IMAGE_FILETYPE_TITLE', 'IH medium images filetype');
define('MEDIUM_IMAGE_FILETYPE_TEXT', 'Select one of -jpg-, -gif- or -png-. Older versions of Internet Explorer -v6.0 and older- will have issues displaying -png- images with transparent areas. You better stick to -gif- for transparency if you MUST support older versions of Internet Explorer. However -png- is a MUCH BETTER format for transparency. Use -jpg- or -png- for larger images. -no_change- is old zen-cart behavior, use the same file extension for medium images as uploaded image-s.');

define('MEDIUM_IMAGE_BACKGROUND_TITLE', 'IH medium images background');
define('MEDIUM_IMAGE_BACKGROUND_TEXT', 'If converted from an uploaded image with transparent areas, these areas become the specified color. Set to -transparent- to keep transparency.');

define('MEDIUM_IMAGE_QUALITY_TITLE', 'IH medium images compression quality');
define('MEDIUM_IMAGE_QUALITY_TEXT', 'Specify the desired image quality for medium jpg images, decimal values ranging from 0 to 100. Higher is better quality and takes more space. Default is 85 which is ok unless you have very specific needs.');

define('WATERMARK_MEDIUM_IMAGES_TITLE', 'IH medium images watermark');
define('WATERMARK_MEDIUM_IMAGES_TEXT', 'Set to -yes-, if you want to show watermarked medium images instead of unmarked medium images.');

define('LARGE_IMAGE_FILETYPE_TITLE', 'IH large images filetype');
define('LARGE_IMAGE_FILETYPE_TEXT', 'Select one of -jpg-, -gif- or -png-. Older versions of Internet Explorer -v6.0 and older- will have issues displaying -png- images with transparent areas. You better stick to -gif- for transparency if you MUST support older versions of Internet Explorer. However -png- is a MUCH BETTER format for transparency. Use -jpg- or -png- for larger images. -no_change- is old zen-cart behavior, use the same file extension for large images as uploaded image-s.');

define('LARGE_IMAGE_BACKGROUND_TITLE', 'IH large images background');
define('LARGE_IMAGE_BACKGROUND_TEXT', 'If converted from an uploaded image with transparent areas, these areas become the specified color. Set to -transparent- to keep transparency.');

define('LARGE_IMAGE_QUALITY_TITLE', 'IH large images compression quality');
define('LARGE_IMAGE_QUALITY_TEXT', 'Specify the desired image quality for large jpg images, decimal values ranging from 0 to 100. Higher is better quality and takes more space. Default is 85 which is ok unless you have very specific needs.');

define('WATERMARK_LARGE_IMAGES_TITLE', 'IH large images watermark');
define('WATERMARK_LARGE_IMAGES_TEXT', 'Set to -yes-, if you want to show watermarked large images instead of unmarked large images.');

define('LARGE_IMAGE_MAX_WIDTH_TITLE', 'IH large images maximum width');
define('LARGE_IMAGE_MAX_WIDTH_TEXT', 'Specify a maximum width for your large images. If width and height are empty or set to 0, no resizing of large images is done.');

define('LARGE_IMAGE_MAX_HEIGHT_TITLE', 'IH large images maximum height');
define('LARGE_IMAGE_MAX_HEIGHT_TEXT', 'Specify a maximum height for your large images. If width and height are empty or set to 0, no resizing of large images is done.');

define('WATERMARK_GRAVITY_TITLE', 'IH watermark gravity');
define('WATERMARK_GRAVITY_TEXT', 'Select the position for the watermark relative to the image-s canvas. Default is <strong>Center</Strong>.');

define('IH_VERSION_TITLE', 'IH version');
define('IH_VERSION_TEXT', 'IH Version is stored but not shown on configuration menus');