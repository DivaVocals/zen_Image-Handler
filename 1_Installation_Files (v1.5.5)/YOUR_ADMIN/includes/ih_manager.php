<?php
/**IH4-1
 * mod Image Handler 5.1.0
 * ih_manager.php
 * manager module for IH4 admin interface
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: ih_manager.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by DerManoMann 2010-05-31 23:46:50
 * Re-factored for IH-5 by lat9 2017-12-02
 * Restructuring for IH-5.1.0 and later by lat9, 2018-05-22.
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
              WHERE ptc.categories_id = " . (int)$current_category_id . " 
           ORDER BY pd.products_name"
        );
        $products_filter = ($new_product_query->EOF) ? null : $new_product_query->fields['products_id'];
        $_GET['products_filter'] = $products_filter;
    }
}

require DIR_WS_MODULES . FILENAME_PREV_NEXT;

// -----
// Note:  On entry, if a product has been selected, the main image_handler module has created the $products
// variable, containing the database information associated with the selected product's image.  The 
// information in that array will be used to determine the product's base-name as well as its base
// image directory.
//
// If a product has **not** been selected, simply return from this module back to the main handler,
// since no actions are possible.
//
if (!isset($product)) {
    return;
}

// -----
// Set the images' directory, using the values specified in the $ihConf array.
//
$images_directory = $ihConf['dir']['docroot'] . $ihConf['dir']['images'];

// -----
// The "save" form gathers up to three (3) separate image files:  base, medium and large.  That form is used
// for two different cases:
//
// 1) Uploading a new image, either:
//    a) A main product-image, for a product that does not yet have a products_image defined.
//       - In this case, $_GET['imgNew'] is set to 'main'.
//    b) An additional product-image, when the product has its main image defined.
//       - In this case, $_GET['imgNew'] is set to 'addl'
// 2) Modifying an existing image.
//    - In this case, $_GET['imgNew'] is *not* supplied, but $_GET['imgSuffix'] will identify
//      the "image-suffix" being edited; if the value is empty, it's the main image being edited.
//
// There are a couple of "rules" associated with those uploaded images:
//
// 1) If the associated product DOES NOT currently have an image, the base image is **required**.
// 2) The file-extension of any medium or large image is controlled by their respective Configuration->Images
//    configuration settings.  Those values are one of 'png', 'jpg', 'gif', or 'no_change'.  If the value is
//    set to 'no_change', the medium/large image is the same file-extension as the main-product image.
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
    // If the "saveType" wasn't supplied with the form, redirect back to the main IH
    // manager page without message (since it "shouldn't" happen).
    //
    if (empty($_POST['saveType'])) {
        zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER));
    }
    
    // -----
    // Initialize some internal "handling" variables.
    //
    $data = array();
    $data_ok = true;
    $keep_name = false;
    $editing = false;
    $is_main = false;
    $new_main_image = false;
    $uploaded_default_extension = false;
    $uploaded_medium_extension = false;
    $uploaded_large_extension = false;
        
    // -----
    // Verify that any uploaded images' file-extension is one of those 'allowed'.
    //
    $supported_extensions = $ih_admin->getSupportedFileExtensions();
    
    // -----
    // If a default/base image was uploaded, make sure that its file-extension is 'allowed'.
    //
    if (!empty($_FILES['default_image']['name'])) {
        $uploaded_default_extension = '.' . pathinfo($_FILES['default_image']['name'], PATHINFO_EXTENSION);
        if (!$ih_admin->validateFileExtension($uploaded_default_extension)) {
            $messageStack->add(sprintf(TEXT_MSG_INVALID_EXTENSION, TEXT_BASE, $uploaded_default_extension, $supported_extensions), 'error');
            $data_ok = false;
        }
    }
    
    // -----
    // If a medium or large image was uploaded, make sure that its file-extension is also 'allowed'.
    //
    if (!empty($_FILES['medium_image']['name'])) {
        $uploaded_medium_extension = '.' . pathinfo($_FILES['medium_image']['name'], PATHINFO_EXTENSION);
        if (!$ih_admin->validateFileExtension($uploaded_medium_extension)) {
            $messageStack->add(sprintf(TEXT_MSG_INVALID_EXTENSION, TEXT_MEDIUM, $uploaded_medium_extension, $supported_extensions), 'error');
            $data_ok = false;
        }
    }
    
    if (!empty($_FILES['large_image']['name'])) {
        $uploaded_large_extension = '.' . pathinfo($_FILES['large_image']['name'], PATHINFO_EXTENSION);
        if (!$ih_admin->validateFileExtension($uploaded_large_extension)) {
            $messageStack->add(sprintf(TEXT_MSG_INVALID_EXTENSION, TEXT_LARGE, $uploaded_large_extension, $supported_extensions), 'error');
            $data_ok = false;
        }
    }
    
    // -----
    // If any of the uploaded files' extensions were found to be 'invalid', simply return to the main
    // image_handler processing for display.
    //
    if (!$data_ok) {
        return;
    }
    
    // -----
    // Otherwise, set some processing flags and gather information, based on the type of image being saved.
    //
    switch ($_POST['saveType']) {
        // -----
        // Updating an existing image.
        //
        case 'edit':
            if (!isset($_POST['imgSuffix'])) {
                $data_ok = false;
            } else {
                $editing = true;
                $data['imgSuffix'] = $_POST['imgSuffix'];
                $data['imgBaseDir'] = $products_image_directory;
                $is_main = ($_POST['imgSuffix'] == '');
                
                $keep_name = (isset($_POST['imgNaming']) && $_POST['imgNaming'] == 'keep_name');
                if ($is_main && !$keep_name) {
                    if (empty($_FILES['default_image']['name'])) {
                        $messageStack->add(TEXT_MSG_NO_DEFAULT_ON_NAME_CHANGE, 'error');
                        $data_ok = false;
                    } else {
                        $data['imgBase'] = pathinfo($_FILES['default_image']['name'], PATHINFO_FILENAME);
                        $data['imgExtension'] = $uploaded_default_extension;
                    }
                } else {
                    $data['imgBase'] = $products_image_base;
                    $data['imgExtension'] = $products_image_extension;
                }
            }
            break;
            
        // -----
        // Creating a new, main image.  There are additional variables passed in that version of the
        // data-gathering form.
        //
        // - imgBase ....... The "base" name for the image, overriding the name associated with any uploaded image.
        // - imgBaseDir .... The "base" directory for the image, selected from existing image-directory sub-directories.
        // - imgNewBaseDir . A new sub-directory to be created under the image-directory for this image.
        //
        case 'new_main':
            $is_main = true;
            $new_main_image = true;
            if (!empty($_POST['imgBase'])) {
                $data['imgBase'] = $_POST['imgBase'];
                if (!empty($uploaded_default_extension)) {
                    $data['imgExtension'] = $uploaded_default_extension;
                } else {
                    $messageStack->add(TEXT_MSG_NO_FILE_UPLOADED, 'error');
                    $data_ok = false;
                }
            } else {
                if (empty($_FILES['default_image']['name'])) {
                    $messageStack->add(TEXT_MSG_AUTO_BASE_ERROR, 'error');
                    $data_ok = false;
                } else {
                    $data['imgBase'] = pathinfo($_FILES['default_image']['name'], PATHINFO_FILENAME);
                    $data['imgExtension'] = $uploaded_default_extension;
                }
            }
            if (!empty($_POST['imgNewBaseDir'])) {
                $data['imgBaseDir'] = $_POST['imgNewBaseDir'];
                $base_is_new = true;
            } else {
                $data['imgBaseDir'] = $_POST['imgBaseDir'];
                $base_is_new = false;
            }
            $data['imgSuffix'] = '';
            break;
            
        // -----
        // Creating a new additional image for a product.  The image is created in the same directory
        // with the same name as the default/main image.
        //
        // The 'imgSuffix' variable, if specified, identifies the suffix (e.g. _01) to apply to this
        // additional image.  If the value is not supplied, determine the next available suffix (in the
        // range _01 to _99 to apply to this image.
        //
        case 'new_addl':
            if ($_FILES['default_image']['name'] == '') {
                $messageStack->add(TEXT_MSG_NO_DEFAULT, 'error');
                $data_ok = false;
            } else {
                $data['imgBaseDir'] = $products_image_directory;
                $data['imgBase'] = $products_image_base;
                $data['imgExtension'] = $uploaded_default_extension;
                
                if ($_POST['imgSuffix'] != '') {
                    $data['imgSuffix'] = '_' . $_POST['imgSuffix'];
                } else {
                    // -----
                    // Get additional images' list; the class function takes care of sorting the files
                    //
                    $matching_files = array();
                    $ih_admin->findAdditionalImages($matching_files, $data['imgBaseDir'], $data['imgBase']);
                    
                    // -----
                    // Log the input values on entry, if debug is enabled.
                    //
                    $ih_admin->debugLog(
                        'ih_manager/save, additional images' . PHP_EOL . var_export($matching_files, true) . PHP_EOL . var_export($data, true)
                    );
                    
                    // -----
                    // If no additional images exist, use the _01 suffix.
                    //
                    $file_count = count($matching_files);
                    if ($file_count == 1) {
                        $data['imgSuffix'] = '_01';
                    } else {
                        // -----
                        // Otherwise, find the first unused suffix in the range _01 to _99.  Note that the first
                        // (ignored) element of the find-array "should be" the main image's name!
                        //
                        for ($suffix = 1, $found = false; $suffix < 99; $suffix++) {
                            $suffix_string = sprintf('_%02u', $suffix);
                            if (!in_array($data['imgBase'] . $suffix_string . $data['imgExtension'], $matching_files)) {
                                $found = true;
                                $data['imgSuffix'] = $suffix_string;
                                break;
                            }
                        }
                        if (!$found) {
                            $messageStack->add(TEXT_MSG_NO_SUFFIXES_FOUND, 'error');
                            $data_ok = false;
                        }
                    }
                }
            }
            break;
            
        default:
            $data_ok = false;
            break;
    }
    
    // -----
    // If the data supplied appears OK, perform a couple of pre-processing checks.
    //
    if ($data_ok) {
        // -----
        // Correct some "nasty" characters in the image's name.
        //
        if (strpos($data['imgBase'], '+') !== false) {
            $data['imgBase'] = str_replace('+', '-', $data['imgBase']);
            $messageStack->add(TEXT_MSG_AUTO_REPLACE . $data['imgBase'], 'warning');
        }
        
        // -----
        // If the image's base-directory doesn't currently end in either a / or \, append a / to that value.
        //
        if ($data['imgBaseDir'] != '') {
            if (substr($data['imgBaseDir'], -1) != '/' && substr($data['imgBaseDir'], -1) != '\\') {
                $data['imgBaseDir'] .= '/';
            }
        }

        // -----
        // Create the name of the base image file, less the store's specific images directory.
        //
        $data['defaultFileName'] = $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . $data['imgExtension'];

        // -----
        // If a **main** image is being edited (i.e. its name is being changed) and the new file already exists, disallow
        // the change.
        //
        if ($editing && $is_main && !$keep_name && file_exists($images_directory . $data['defaultFileName'])) {
            $existing_file = $images_directory . $data['defaultFileName'];
            $messageStack->add(sprintf(TEXT_MSG_FILE_EXISTS, $existing_file), 'error' );
            $data_ok = false;
        }
    }

    // -----
    // If no previous errors and we're either (a) creating a new main-image or (b) editing the main-image and a new name
    // is requested ...
    //
    if ($data_ok && ($new_main_image || ($editing && $is_main && !$keep_name))) {
        // -----
        // ... first, check to see that the image's name is going to fit into the database field.
        //
        if (strlen($data['defaultFileName']) > zen_field_length(TABLE_PRODUCTS, 'products_image')) {
            $messageStack->add(sprintf(TEXT_MSG_NAME_TOO_LONG_ERROR, $data['defaultFileName'], zen_field_length(TABLE_PRODUCTS, 'products_image')), 'error');
            $data_ok = false;
        } else {
            $db->Execute(
                "UPDATE " . TABLE_PRODUCTS . " 
                    SET products_image = '" . $db->prepare_input($data['defaultFileName']) . "' 
                  WHERE products_id = " . (int)$products_filter . "
                  LIMIT 1"
            );
        }
    }

    // -----
    // ... finally (!) create the images based on the validated user-input.  For each
    // image-type supplied, create the file in destination directory and move the
    // uploaded file to that destination.
    //
    if ($data_ok) {
        $ih_admin->debugLog("images_directory: $images_directory, data: " . PHP_EOL . var_export($data, true));
        
        // -----
        // The "base" image ...
        //
        if ($_FILES['default_image']['name'] != '') {
            io_makeFileDir($images_directory . $data['defaultFileName']);
            $source_name = $_FILES['default_image']['tmp_name'];
            $destination_name = $images_directory . $data['defaultFileName'];
            if (!move_uploaded_file($source_name, $destination_name)) {
                $messageStack->add(TEXT_MSG_NOUPLOAD_DEFAULT, 'error' );
                $data_ok = false;
            }
        }
        
        // -----
        // The "medium" image ...
        //
        if ($data_ok && $_FILES['medium_image']['name'] != '') {
            $medium_filename = 'medium/' . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . IMAGE_SUFFIX_MEDIUM . $uploaded_medium_extension;
            io_makeFileDir($images_directory . $medium_filename);
            $source_name = $_FILES['medium_image']['tmp_name'];
            $destination_name = $images_directory . $medium_filename;
            if (!move_uploaded_file($source_name, $destination_name)) {
                $messageStack->add(TEXT_MSG_NOUPLOAD_MEDIUM, 'error');
                $data_ok = false;
            }
        }
        
        // -----
        // The "large" image ...
        //
        if ($data_ok && $_FILES['large_image']['name'] != '') {
            $large_filename = 'large/' . $data['imgBaseDir'] . $data['imgBase'] . $data['imgSuffix'] . IMAGE_SUFFIX_LARGE . $uploaded_large_extension;
            io_makeFileDir($images_directory . $large_filename);
            $source_name = $_FILES['large_image']['tmp_name'];
            $destination_name = $images_directory . $large_filename;
            if (!move_uploaded_file($source_name, $destination_name)) {
                $messageStack->add(TEXT_MSG_NOUPLOAD_LARGE, 'error');
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
        $messageStack->add_session(TEXT_MSG_IMAGE_SAVED, 'success');
        $redirect_parms = zen_get_all_get_params(array('action', 'imgName', 'imgSuffix', 'imgExtension'));
        $redirect_parms .= '&amp;imgName=' . $data['imgBase'] . $data['imgSuffix'];
        $redirect_parms .= '&amp;imgSuffix=' . $data['imgSuffix'];
        $redirect_parms .= '&amp;imgExtension=' . $data['imgExtension'];
        
        zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, $redirect_parms . '&amp;action=layout_info'));
    }
}

// -----
// A 'quick_delete' action enables the removal of a medium/large image file that is different from
// the product's base image.
//
if ($action == 'quick_delete') {
    if (!empty($_POST['qdFile'])) {
        $img_name = DIR_FS_CATALOG . $_POST['qdFile'];
        if (is_file($img_name)) {
            if (unlink($img_name)) {
                // file successfully deleted
                $messageStack->add_session(sprintf(TEXT_MSG_IMAGE_DELETED, $img_name), 'success');
            } else {
                // couldn't delete file
                $messageStack->add_session(sprintf(TEXT_MSG_IMAGE_NOT_DELETED, $img_name), 'error');
            }
        } else {
            // could not find file to delete
            $messageStack->add_session(sprintf(TEXT_MSG_IMAGE_NOT_FOUND, $img_name), 'error');
        }
    }
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, "products_filter=$products_filter&amp;current_category_id=$current_category_id"));
}

// -----
// Delete a specified product image.
//
if ($action == 'delete') {
    if (!empty($_POST['imgSuffix']) || empty($_POST['delete_from_db_only'])) {
        $base_name = $products_image_directory . $_POST['imgName'];
        $image_ext = $_POST['imgExtension'];

        $large_file = $images_directory . 'large/' . $base_name . IMAGE_SUFFIX_LARGE . $image_ext;
        if (is_file($large_file)) {
            if (unlink($large_file)) {
                $messageStack->add_session(sprintf(TEXT_MSG_LARGE_DELETED, $large_file), 'success');
            } else {
                $messageStack->add_session(sprintf(TEXT_MSG_NO_DELETE_LARGE, $large_file), 'error');
            }
        }
        
        $medium_file = $images_directory . 'medium/' . $base_name . IMAGE_SUFFIX_MEDIUM . $image_ext;
        if (is_file($medium_file)) {
            if (unlink($medium_file)) {
                $messageStack->add_session(sprintf(TEXT_MSG_MEDIUM_DELETED, $medium_file), 'success');
            } else {
                $messageStack->add_session(sprintf(TEXT_MSG_NO_DELETE_MEDIUM, $medium), 'error');
            }
        }
        
        $base_file = $images_directory . $base_name . $image_ext;
        if (!is_file($base_file)) {
            $messageStack->add_session(sprintf(TEXT_MSG_NO_DEFAULT_FILE_FOUND, $base_file), 'error');
        } else {
            if (unlink($base_file)) {
                $messageStack->add_session(sprintf(TEXT_MSG_DEFAULT_DELETED, $base_file), 'success');
            } else {
                $messageStack->add_session(sprintf(TEXT_MSG_NO_DELETE_DEFAULT, $base_file), 'error');
            }
        }
    }

    // update the database
    if (empty($_POST['imgSuffix'])) {
        $db->Execute(
            "UPDATE " . TABLE_PRODUCTS . " 
                SET products_image = '' 
              WHERE products_id = " . (int)$products_filter . "
              LIMIT 1"
        );
    }
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, "products_filter=$products_filter&amp;current_category_id=$current_category_id"));
}
