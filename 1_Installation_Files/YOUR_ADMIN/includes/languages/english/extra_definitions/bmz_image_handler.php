<?php
/**IH4
 * bmz_image_handler.php
 * english language definitions for image handler
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: bmz_image_handler.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by webchills and cjones 2012-03-10 17:46:50 
 */

define('BOX_TOOLS_IMAGE_HANDLER', 'Image Handler<sup>4</sup>');
define('ICON_IMAGE_HANDLER','Image Handler 4.1');
define('IH_VERSION_VERSION', 'Version');
define('IH_VERSION_NOT_FOUND', 'No Image Handler information found.');
define('IH_REMOVE', 'Uninstall Image Handler.  (Please backup your site and database first)');
define('IH_CONFIRM_REMOVE', 'Are you sure? ');
define('IH_REMOVED', 'Image Handler successfully removed.');
define('IH_UPDATE', 'Update Image Handler');
define('IH_UPDATED', 'Image Handler successfully updated.');
define('IH_INSTALL', 'Install Image Handler');
define('IH_INSTALLED', 'Image Handler successfully installed.');
define('IH_SCAN_FOR_ORIGINALS', 'Scan for old IH 0.x and 1.x <em>original</em> images');
define('IH_CONFIRM_IMPORT', 'Do you really want to import the listed images?<br /><strong>Backup your Database and images folder first!</strong>');
define('IH_NO_ORIGINALS', 'No old IH 0.x or 1.x original images found');
define('IH_IMAGES_IMPORTED', 'Successfully imported images.');
define('IH_CLEAR_CACHE', 'Clear image cache');
define('IH_CACHE_CLEARED', 'Image cache cleared.');

define('IH_SOURCE_TYPE', 'Source imagetype');
define('IH_SOURCE_IMAGE', 'Source image');
define('IH_SMALL_IMAGE', 'Default image');
define('IH_MEDIUM_IMAGE', 'Products image');

define('IH_ADD_NEW_IMAGE', 'Add a new image');
define('IH_NEW_NAME_DISCARD_IMAGES', 'Use new name, discard additional images');
define('IH_NEW_NAME_COPY_IMAGES', 'Use new name, copy additional images');
define('IH_KEEP_NAME', 'Keep old name and additional images');
define('IH_DELETE_FROM_DB_ONLY', 'Delete image reference from database only');

define('IH_HEADING_TITLE', 'Image Handler<sup>4</sup>');
define('IH_HEADING_TITLE_PRODUCT_SELECT','Please select a product to manage the images.');

define('TABLE_HEADING_PHOTO_NAME', 'Image name');
define('TABLE_HEADING_DEFAULT_SIZE','Default size');
define('TABLE_HEADING_MEDIUM_SIZE', 'Medium size');
define('TABLE_HEADING_LARGE_SIZE','Large size');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_FILETYPE', 'File Type');

define('TEXT_PRODUCT_INFO', 'Product');
define('TEXT_PRODUCTS_MODEL', 'Model');
define('TEXT_IMAGE_BASE_DIR', 'Base directory');
define('TEXT_NO_PRODUCT_IMAGES', 'There are no images for this product');
define('TEXT_CLICK_TO_ENLARGE', 'Click to enlarge');
define('TEXT_PRICED_BY_ATTRIBUTES', 'Priced by attributes');
 
define('TEXT_INFO_IMAGE_INFO', 'Image information');
define('TEXT_INFO_NAME', 'Name');
define('TEXT_INFO_FILE_TYPE', 'File type');
define('TEXT_INFO_EDIT_PHOTO', 'Edit image');
define('TEXT_INFO_NEW_PHOTO', 'New image');
define('TEXT_INFO_IMAGE_BASE_NAME', 'Image base name (optional)');
define('TEXT_INFO_AUTOMATIC_FROM_DEFAULT', ' Automatic (from default image name)');
define('TEXT_INFO_MAIN_DIR', 'Main directory');
define('TEXT_INFO_BASE_DIR', 'Base image directory');
define('TEXT_INFO_NEW_DIR', 'Select or define a new directory for the images.');
define('TEXT_INFO_IMAGE_DIR', 'Image directory');
define('TEXT_INFO_OR', 'or');
define('TEXT_INFO_AUTOMATIC', 'Automatic');
define('TEXT_INFO_IMAGE_SUFFIX', 'Image suffix (optional)');
define('TEXT_INFO_USE_AUTO_SUFFIX','Enter a specific suffix or leave empty for automatic suffix generation.');
define('TEXT_INFO_DEFAULT_IMAGE', 'Default image file');
define('TEXT_INFO_DEFAULT_IMAGE_HELP', 'A default image must be defined. The default image is assumed to be the smallest when medium or large images are entered.');
define('TEXT_INFO_CONFIRM_DELETE', "Confirm delete");
define('TEXT_INFO_CONFIRM_DELETE_SURE', 'Are you sure you want to delete this image and all its sizes?');
define('TEXT_INFO_SELECT_ACTION', 'Select action');
define('TEXT_INFO_CLICK_TO_ADD', 'Click to add a new image to this product');

define('TEXT_MSG_AUTO_BASE_ERROR', 'Automatic base select without default file.');
define('TEXT_MSG_INVALID_BASE_ERROR', 'Invalid image base name, or unable to find default image.');
define('TEXT_MSG_AUTO_REPLACE',  'Automatically replacing bad characters in base name, new name: ');
define('TEXT_MSG_INVALID_SUFFIX', 'Invalid image suffix.');
define('TEXT_MSG_IMAGE_TYPES_NOT_SAME_ERROR', 'Image types are not the same.');
define('TEXT_MSG_DEFAULT_REQUIRED_FOR_RESIZE', 'A default image is required for automatic resizing.');
define('TEXT_MSG_NO_DEFAULT', 'No default image has been specified.');
define('TEXT_MSG_FILE_EXISTS', 'File exists! Please alter the base name or suffix.');
define('TEXT_MSG_INVALID_SQL', "Unable to complete SQL query.");
define('TEXT_MSG_NOCREATE_IMAGE_DIR', "Unable to create image directory.");
define('TEXT_MSG_NOCREATE_MEDIUM_IMAGE_DIR', "Unable to create medium image directory.");
define('TEXT_MSG_NOCREATE_LARGE_IMAGE_DIR', "Unable to create large image directory.");
define('TEXT_MSG_NOPERMS_IMAGE_DIR', "Unable to set the permissions of the image directory.");
define('TEXT_MSG_NOPERMS_MEDIUM_IMAGE_DIR', "Unable to set the permissions of the medium image directory.");
define('TEXT_MSG_NOPERMS_LARGE_IMAGE_DIR', "Unable to set the permissions of the large image directory.");

define('TEXT_MSG_NOUPLOAD_DEFAULT', "Unable to upload default image file.");
define('TEXT_MSG_NORESIZE', "Unable to resize image");
define('TEXT_MSG_NOCOPY_LARGE', "Unable to copy large image file.");
define('TEXT_MSG_NOCOPY_MEDIUM', "Unable to copy medium image file.");
define('TEXT_MSG_NOCOPY_DEFAULT', "Unable to copy default image file.");
define('TEXT_MSG_NOPERMS_LARGE', "Unable to set permissions of large image file.");
define('TEXT_MSG_NOPERMS_MEDIUM', "Unable to set permissions of medium image file.");
define('TEXT_MSG_NOPERMS_DEFAULT', "Unable to set permissions of default image file.");
define('TEXT_MSG_IMAGE_SAVED', 'Image successfully saved.');
define('TEXT_MSG_LARGE_DELETED', 'Large image deleted.');
define('TEXT_MSG_NO_DELETE_LARGE', 'Unable to delete large image.');
define('TEXT_MSG_MEDIUM_DELETED', 'Medium image deleted.');
define('TEXT_MSG_NO_DELETE_MEDIUM', 'Unable to delete medium image.');
define('TEXT_MSG_DEFAULT_DELETED', 'Default image deleted.');
define('TEXT_MSG_NO_DELETE_DEFAULT', 'Unable to delete default image.');
define('TEXT_MSG_NO_DEFAULT_FILE_FOUND', "No default image found for delete.");

define('TEXT_MSG_IMAGE_DELETED', 'Image successfully deleted.');
define('TEXT_MSG_IMAGE_NOT_FOUND', 'Unable to locate image.');
define('TEXT_MSG_IMAGE_NOT_DELETED', 'Unable to delete image.');

define('TEXT_MSG_IMPORT_SUCCESS', 'Import successful: ');
define('TEXT_MSG_IMPORT_FAILURE', 'Import failure: ');

// image manager
define('IH_IMAGE_NEW_FILE', 'Click to add a new image to this product');
define('IH_IMAGE_EDIT', 'Click to edit this image');
define('TEXT_MEDIUM_FILE_IMAGE', 'Medium image file (optional)');
define('TEXT_LARGE_FILE_IMAGE', 'Large image file (optional)');

// ih menu
define('IH_MENU_MANAGER', 'Image Manager');
define('IH_MENU_ADMIN', 'Admin Tools');
define('IH_MENU_ABOUT', 'About/Help');
define('IH_MENU_PREVIEW', 'Preview');

// message stack messages
define('IH_MS_ALL_EXIST','Image Handler files all exist in correct positions in the directory structure.');
define('IH_MS_ABORTED','********** Installation has been aborted. **********');
define('IH_MS_SOME_FILES_MISSING','Some Image Handler files do not exist. Perhaps you have uploaded them incorrectly? Or the permissions are set incorrectly?');
define('IH_MS_TEMPLATE_NOTFOUND','Image Handler is having some problems finding your current template.');
define('IH_MS_MISSING_OR_UNREADABLE','Missing or unreadable file:');
define('IH_MS_OVERWRITTEN','was overwritten. A back up copy was saved.');
define('IH_MS_NOT_OVERWRITTEN','was NOT overwritten.');
define('IH_MS_CREATED','was created. A back up copy of any overwritten file was saved.');
define('IH_MS_NOT_CREATED','was NOT created.');
define('IH_MS_SUCCESS','Image Handler has been successfully installed');
define('IH_MS_ROLLBACK_OK','was returned to default version.');
define('IH_MS_ROLLBACK_NOT_OK','was NOT rolled back.');
define('IH_MS_UNINSTALL_OK','Image Handler has been uninstalled.');
define('IH_MS_BACKUP_INFO','Image Handler creates back up versions of certain files when it is installed before overwriting them. These files have been left in position for reference. They may be deleted but will not effect the functioning of the shop if you leave them in place.');
define('IH_MS_AUTOLOADER_NOTDELETED','The auto-loader YOURADMIN/includes/auto_loaders/config.image_handler.php has not been deleted. For Image Handler to work you must delete this file manually.');
