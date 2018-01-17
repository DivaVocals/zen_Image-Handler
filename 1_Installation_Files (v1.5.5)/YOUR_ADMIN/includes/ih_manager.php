<?php
/**IH4-1
 * mod Image Handler 4.3.3
 * ih_manager.php
 * manager module for IH4 admin interface
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: ih_manager.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by DerManoMann 2010-05-31 23:46:50
 * Re-factored for IH-5 by lat9 2017-12-02
 */
if ($action == 'new_cat') {
    $current_category_id = (isset($_GET['current_category_id']) ? $_GET['current_category_id'] : $current_category_id);
    $new_product_query = $db->Execute(
        "SELECT ptc.* FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
            LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                ON ptc.products_id = pd.products_id 
               AND pd.language_id = " . (int)$_SESSION['languages_id'] . " 
          WHERE ptc.categories_id = " . (int)$current_category_id . " 
       ORDER BY pd.products_name"
    );
    $products_filter = ($new_product_query->EOF) ? null : $new_product_query->fields['products_id'];
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager&amp;products_filter=' . $products_filter . '&amp;current_category_id=' . $current_category_id));
}

// set categories and products if not set
if ($products_filter == '' && $current_category_id != '') {
    $new_product_query = $db->Execute(
        "SELECT ptc.* FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc
            LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                ON ptc.products_id = pd.products_id 
               AND pd.language_id = " . (int)$_SESSION['languages_id'] . " 
          WHERE ptc.categories_id = " . (int)$current_category_id . " 
       ORDER BY pd.products_name"
    );
    $products_filter = $new_product_query->fields['products_id'];
    if ($products_filter != '') {
        zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager&amp;products_filter=' . $products_filter . '&amp;current_category_id=' . $current_category_id));
    }
} else {
    if ($products_filter == '' && $current_category_id == '') {
        $reset_categories_id = zen_get_category_tree('', '', '0', '', '', true);
        $current_category_id = $reset_categories_id[0]['id'];
        $new_product_query = $db->Execute(
            "SELECT ptc.* from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc 
                LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                    ON ptc.products_id = pd.products_id 
                   AND pd.language_id = " . (int)$_SESSION['languages_id'] . " 
              WHERE ptc.categories_id = " . $current_category_id . " 
           ORDER BY pd.products_name"
        );
        $products_filter = ($new_product_query->EOF) ? null : $new_product_query->fields['products_id'];
        $_GET['products_filter'] = $products_filter;
    }
}

require DIR_WS_MODULES . FILENAME_PREV_NEXT;

// -----
// The "save" form gathers up to three (3) separate image files:  default (aka small), medium and large.  That form is used
// for two different cases:
//
// 1) Uploading a new image, either:
//    a) A main product-image, for a product that does not yet have a products_image defined.
//    b) An additional product-image, when the product has its main image defined.
// 2) Modifying an existing image.  In this case, the variable $_GET['imgEdit'] is set to the value of 1.
//
// There are a couple of "rules" associated with those uploaded images:
//
// 1) If the associated product DOES NOT currently have an image, the default image is **required**.
// 2) The file-extension of any medium or large image MUST MATCH that of the associated default.
//    a) If a new default image is being uploaded, the medium/large extension must match that of the uploaded default.
//    b) If the existing default image is being used, the medium/large extension must match that of the current default.
//
if ($action == 'save') {
    // -----
    // Log the input values on entry, if debug is enabled.
    //
    $ih_admin->debugLog(
        'ih_manager/save, on entry.' . PHP_EOL . 
        '$_GET:' . PHP_EOL . var_export($_GET, true) . PHP_EOL . 
        '$_POST:' . PHP_EOL . var_export($_POST, true) . PHP_EOL . 
        '$_FILES:' . PHP_EOL . var_export($_FILES, true)
    );
    
    // -----
    // Set some processing flags, based on the type of upload being performed.
    //
    $editing = (isset($_GET['imgEdit']) && $_GET['imgEdit'] == '1');
    $new_image = (isset($_GET['newImg']) && $_GET['newImg'] == '1');
    $main_image = (!isset($_GET['imgSuffix']) || $_GET['imgSuffix'] == '');
    $keep_name = (isset($_POST['imgNaming']) && $_POST['imgNaming'] == 'keep_name');
    
    $data = array();
    $data_ok = true;
    
    // -----
    // Determine the extension required for any uploaded images.
    //
    // 1) A new main-image (and any medium/large) use the extension from the (required) default image suppied.
    // 2) A new additional image's files use the extension from the pre-existing main-image.
    // 3) Editing an image uses the pre-existing file extension.
    //
    if ($new_image) {
        if ($_FILES['default_image']['name'] == '') {
            $messageStack->add(TEXT_MSG_NO_DEFAULT, 'error');
            $data_ok = false;
        } else {
            $data['imgExtension'] = '.' . pathinfo($_FILES['default_image']['name'], PATHINFO_EXTENSION);
        }
    } else {
        $data['imgExtension'] = $_GET['imgExtension'];
    }

    // -----
    // If the file-upload is in support of a new main image or the main image is being edited ...
    //
    if ($new_image || ($editing && $main_image && !$keep_name && $_FILES['default_image']['name'] != '')) {
        // New Image Name and Base Dir
        if (isset($_POST['imgBase']) && $_POST['imgBase'] != '') {
            $data['imgBase'] = $_POST['imgBase'];
        } else {
            // Extract the name from the default file
            if ($_FILES['default_image']['name'] != '') {
                $data['imgBase'] = pathinfo($_FILES['default_image']['name'], PATHINFO_FILENAME);
            } else {
                $messageStack->add(TEXT_MSG_AUTO_BASE_ERROR, 'error');
                $data_ok = false;
            }
        }
  
        // catch nasty characters
        if (strpos($data['imgBase'], '+') !== false) {
            $data['imgBase'] = str_replace('+', '-', $data['imgBase']);
            $messageStack->add(TEXT_MSG_AUTO_REPLACE . $data['imgBase'], 'warning');
        }
  
        if (isset($_POST['imgNewBaseDir']) && $_POST['imgNewBaseDir'] != '') {
            $data['imgBaseDir'] = $_POST['imgNewBaseDir'];
        } elseif (isset($_POST['imgBaseDir'])) {
            $data['imgBaseDir'] = $_POST['imgBaseDir'];
        } else {
            $data['imgBaseDir'] = $_GET['imgBaseDir'];
        }
  
        $data['imgSuffix'] = '';

/*
        if ($_POST['imgNaming'] == 'new_copy') {
            // need to copy/rename additional images for new default image
            // this will be implemented in a future release
        }
*/
    // -----
    // Otherwise, if we're editing an additional product image ...
    //
    } elseif ($editing) {
        $data['imgBaseDir'] = $_GET['imgBaseDir'];
        $data['imgBase'] = $_GET['imgBase'];
        $data['imgSuffix'] = $_GET['imgSuffix'];
    // -----
    // Otherwise, we're adding an additional product image ...
    //
    } else {
        // An additional image is being added
        $data['imgBaseDir'] = $_GET['imgBaseDir'];
        $data['imgBase'] = $_GET['imgBase'];
        
        // Image Suffix (if set)
        if ($_POST['imgSuffix'] != '') {
            $data['imgSuffix'] = '_' . $_POST['imgSuffix'];
        } else {
            // -----
            // Get additional images' list; the class function takes care of sorting the files
            //
            $matching_files = array();
            $ih_admin->findAdditionalImages($matching_files, $data['imgBaseDir'], $data['imgExtension'], $data['imgBase']);
            
            // -----
            // If no additional images exist, use the _01 suffix.
            //
            if ($file_count == 1) {
                $data['imgSuffix'] = '_01';
            } else {
                // -----
                // Otherwise, find the first unused suffix in the range _01 to _99.  Note that the first
                // (ignored) element of the find-array "should be" the main image's name!
                //
                for ($suffix = 1, $found = false; $suffix < 99; $suffix++) {
                    $suffix_string = sprintf('_%02u', $suffix);
                    if (!in_array($data['imgBase'] . $suffix_string . $data['imgExtension'])) {
                        $found = true;
                        $data['imgSuffix'] = $suffix_string;
                        break;
                    }
                }
                if (!$found) {
                    $messageStack->add('Could not find an unused additional-image suffix in the range _01 to _99.', 'error');
                    $data_ok = false;
                }
            }
        }
    }
    
    // determine the filenames 
    if ($data_ok) {
        // add slash to base dir
        if ($data['imgBaseDir'] != '') {
            if (substr($data['imgBaseDir'], -1) != '/' && substr($data['imgBaseDir'], -1) != '\\') {
                $data['imgBaseDir'] .= '/';
            }
        }
        $data['defaultFileName'] = $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . $data['imgExtension'];

        // Check if the file already exists
        if ($editing && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $data['defaultFileName'])) {
            $messageStack->add(TEXT_MSG_FILE_EXISTS, 'error' );
            $data_ok = false;
        }
    }

    // -----
    // If no previous errors and we're either (a) creating a new main-image or (b) editing the main-image and a new name
    // is requested ...
    //
    if ($data_ok && $new_image || ($editing && $main_image && !$keep_name && $_FILES['default_image']['name'] != '')) {
        // -----
        // ... first, check to see that the image's name is going to fit into the database field.
        //
        if (strlen($data['defaultFileName']) > zen_field_length(TABLE_PRODUCTS, 'products_image')) {
            $messageStack->add(sprintf(TEXT_MSG_NAME_TOO_LONG_ERROR, $data['defaultFileName'], zen_field_length(TABLE_PRODUCTS, 'products_image')), 'error');
            $data_ok = false;
        } else {
            // update the database
            $sql = 
                "UPDATE " . TABLE_PRODUCTS . " 
                    SET products_image = '" . $data['defaultFileName'] . "' 
                  WHERE products_id = " . (int)$products_filter . "
                  LIMIT 1";
            if (!$db->Execute($sql)) {
                $messageStack->add(TEXT_MSG_INVALID_SQL, "error");
                $data_ok = false;
            }
        }
    }

    if ($data_ok) {
        // check for destination directory and create, if they don't exist!
        // Then move uploaded file to its new destination

        // default image
        if ($_FILES['default_image']['name'] != '') {
            io_makeFileDir(DIR_FS_CATALOG_IMAGES . $data['defaultFileName']);
            $source_name = $_FILES['default_image']['tmp_name'];
            $destination_name = DIR_FS_CATALOG_IMAGES . $data['defaultFileName'];
            if (!move_uploaded_file($source_name, $destination_name)) {
                $messageStack->add(TEXT_MSG_NOUPLOAD_DEFAULT, "error" );
                $data_ok = false;
            }
        } elseif ($_FILES['default_image']['name'] == '' && !$editing) {
            // Nigel Hack for special idiots  
            io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['defaultFileName']);
            $source_name = $_FILES['default_image']['tmp_name'];
            $destination_name = DIR_FS_CATALOG_IMAGES . $data['defaultFileName'];
            if (!move_uploaded_file($source_name, $destination_name) ) {
                $messageStack->add( 'you must select a default image', "error" );
                $data_ok = false;
                $_FILES['medium_image']['name'] = $_FILES['large_image']['name'] = '';
            }
        }  // End special idiots hack
        // medium image
        if ($_FILES['medium_image']['name'] != '') {
            $data['mediumImgExtension'] = substr( $_FILES['medium_image']['name'], strrpos($_FILES['medium_image']['name'], '.'));
            $data['mediumFileName'] ='medium/' . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . IMAGE_SUFFIX_MEDIUM . $data['mediumImgExtension'];
            io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['mediumFileName']);
            $source_name = $_FILES['medium_image']['tmp_name'];
            $destination_name = DIR_FS_CATALOG_IMAGES . $data['mediumFileName'];
            if (!move_uploaded_file($source_name, $destination_name)) {
                $messageStack->add( TEXT_MSG_NOUPLOAD_MEDIUM, "error" );
                $data_ok = false;
            }
        }
        // large image
        if ($_FILES['large_image']['name'] != '') {
            $data['largeImgExtension'] = substr( $_FILES['large_image']['name'], strrpos($_FILES['large_image']['name'], '.'));
            $data['largeFileName'] = 'large/' . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . IMAGE_SUFFIX_LARGE . $data['largeImgExtension'];
            io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['largeFileName']);
            $source_name = $_FILES['large_image']['tmp_name'];
            $destination_name = DIR_FS_CATALOG_IMAGES . $data['largeFileName'];
            if (!move_uploaded_file($source_name, $destination_name)) {
                $messageStack->add( TEXT_MSG_NOUPLOAD_LARGE, "error" );
                $data_ok = false;
            }
        }  
    }

    if (!$data_ok) {
        if ($editing) {
            $action = "layout_edit";
        } else {
            $action = "layout_new";
        }
    } else {
        // Data has been saved
        // show the new image information
        $messageStack->add(TEXT_MSG_IMAGE_SAVED, 'success');
        // we might need to clear the cache if filenames are kept
        if ($editing) {
            $error = bmz_clear_cache();
            if (!$error) {
                $messageStack->add(IH_CACHE_CLEARED, 'success');
            }
        }
        $_GET['imgName'] = $data['imgBase'] . $data['imgSuffix'];
        $action = "layout_info";
    }
}

if ($action == 'quick_delete') {
    $img_name = $_GET['imgName'];
    $img_name_full = DIR_FS_CATALOG . $img_name;
    if (is_file($img_name_full)) {
        if (unlink($img_name_full)) {
            // file successfully deleted
            $messageStack->add_session(TEXT_MSG_IMAGE_DELETED, 'success');
        } else {
            // couldn't delete file
            $messageStack->add_session(TEXT_MSG_IMAGE_NOT_DELETED, 'error');
        }
    } else {
        // could not find file to delete
        $messageStack->add_session(TEXT_MSG_IMAGE_NOT_FOUND, 'error');
    }
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $_GET['products_filter'] . '&amp;current_category_id=' . $current_category_id));
}

if ($action == 'delete') {
    $data['imgBaseDir'] = $_GET['imgBaseDir'];
    $data['imgBase'] = $_GET['imgBase'];
    $data['imgExtension'] = $_GET['imgExtension'];
    $data['imgSuffix'] = $_GET['imgSuffix'];

    // add slash to base dir
    if (($data['imgBaseDir'] != '') && !preg_match("|\/$|", $data['imgBaseDir'])) {
        $data['imgBaseDir'] .= '/'; 
    }

    // Determine file names
    $data['defaultFileName'] = DIR_FS_CATALOG . DIR_WS_IMAGES . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . $data['imgExtension'];
    $data['mediumFileName'] = DIR_FS_CATALOG . DIR_WS_IMAGES . 'medium/' . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . IMAGE_SUFFIX_MEDIUM . $data['imgExtension'];
    $data['largeFileName'] = DIR_FS_CATALOG . DIR_WS_IMAGES . 'large/' . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . IMAGE_SUFFIX_LARGE . $data['imgExtension'];

    if ($_POST['delete_from_db_only'] != "Y") {
        // check for each file, and delete it!
        if (is_file($data['largeFileName'])) {
            if (unlink($data['largeFileName'])) {
                $messageStack->add(TEXT_MSG_LARGE_DELETED, "success");
            } else {
                $messageStack->add(TEXT_MSG_NO_DELETE_LARGE, "error");
            }
        }
        if (is_file($data['mediumFileName'])) {
            if (unlink($data['mediumFileName'])) {
                $messageStack->add(TEXT_MSG_MEDIUM_DELETED, "success");
            } else {
                $messageStack->add(TEXT_MSG_NO_DELETE_MEDIUM, "error");
            }
        }
        if (is_file($data['defaultFileName'])) {
            if (unlink($data['defaultFileName'])) {
                $messageStack->add(TEXT_MSG_DEFAULT_DELETED, "success");
            } else {
                $messageStack->add(TEXT_MSG_NO_DELETE_DEFAULT, "error");
            }
        } else {
            $messageStack->add(TEXT_MSG_NO_DEFAULT_FILE_FOUND.': '.$data['defaultFileName'], "error");
        }
    }

    // update the database
    if ($data['imgSuffix'] == '') {
        $sql = 
            "UPDATE " . TABLE_PRODUCTS . " 
                SET products_image = '' 
              WHERE products_id = " . (int)$products_filter . "
              LIMIT 1";
        if (!$db->Execute($sql)) {
            $messageStack->add(TEXT_MSG_INVALID_SQL, "error");
        }
    }
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $_GET['products_filter'] . '&amp;current_category_id=' . $current_category_id));
}

if ($action == 'cancel') {
    // set edit message
    $messageStack->add_session(PRODUCT_WARNING_UPDATE_CANCEL, 'warning');
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'products_filter=' . $_GET['products_filter'] . '&amp;current_category_id=' . $current_category_id));
}