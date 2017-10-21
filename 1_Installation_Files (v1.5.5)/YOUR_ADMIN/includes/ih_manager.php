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
 */

  if ($action == 'new_cat') {
    $current_category_id = (isset($_GET['current_category_id']) ? $_GET['current_category_id'] : $current_category_id);
    $new_product_query = $db->Execute("select ptc.* from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on ptc.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' where ptc.categories_id='" . $current_category_id . "' order by pd.products_name");
    $products_filter = $new_product_query->fields['products_id'];
    zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager&amp;products_filter=' . $products_filter . '&amp;current_category_id=' . $current_category_id));
  }
  
  // set categories and products if not set
  if ($products_filter == '' and $current_category_id != '') {
    $new_product_query = $db->Execute("select ptc.* from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on ptc.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' where ptc.categories_id='" . $current_category_id . "' order by pd.products_name");
    $products_filter = $new_product_query->fields['products_id'];
    if ($products_filter != '') {
      zen_redirect(zen_href_link(FILENAME_IMAGE_HANDLER, 'ih_page=manager&amp;products_filter=' . $products_filter . '&amp;current_category_id=' . $current_category_id));
    }
  } else {
    if ($products_filter == '' and $current_categories_id == '') {
      $reset_categories_id = zen_get_category_tree('', '', '0', '', '', true);
      $current_category_id = $reset_categories_id[0]['id'];
      $new_product_query = $db->Execute("select ptc.* from " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc  left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on ptc.products_id = pd.products_id and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' where ptc.categories_id='" . $current_category_id . "' order by pd.products_name");
      $products_filter = $new_product_query->fields['products_id'];
      $_GET['products_filter'] = $products_filter;
    }
  }
  
  require(DIR_WS_MODULES . FILENAME_PREV_NEXT);

  if ($action == 'save') {
    $check = 0;
    $data = array();

    $data['imgExtension'] = substr( $_FILES['default_image']['name'], 
            strrpos($_FILES['default_image']['name'], '.'));
    /* Nigel fix here 
	This basically converts the extension of the uploaded file to match the main image, PROVIDED they are the same file type
	*/
	
	$sm_imgExtension = strtolower($data['imgExtension']); 
	$df_extension = strtolower($_GET['imgExtension']); // file extension of base original file
	
	//ugly but effective code
	if ($sm_imgExtension == $df_extension) { // this should get rid of any capitilisation issues for files with the same extensions
	$data['imgExtension'] = $_GET['imgExtension']; 
	} // This deals with any jpg that have differing extensions eg jpeg and jpg
	elseif ((($sm_imgExtension == ".jpeg") && ($df_extension == ".jpg")) || (($sm_imgExtension == ".jpg") && ($df_extension == ".jpeg")) )
	{// this is where they don't match eg original image is a gif and additional image is a jpg or png
	$data['imgExtension'] = $_GET['imgExtension']; 
	} else
	{
	//file mismatch really should put a warning in here that the additional image won't show
	// or a call to convert the actual image to a compatible format
	}

    // Check the data
    if ((isset($_GET['newImg']) && $_GET['newImg'] == 1) || (isset($_GET['imgEdit']) && ($_GET['imgEdit'] == 1) && ($_GET['imgSuffix'] ==  '') && ($_POST['imgNaming'] != 'keep_name') && ($_FILES['default_image']['name'] != ''))) {
      // New Image Name and Base Dir
      if ( ($_POST['imgBase'] != '') ) {
        $data['imgBase'] = $_POST['imgBase'];
			       echo "<!--  point 12 - ".$_POST['imgBase']."-->";
      } else {
        // Extract the name from the default file
        if ($_FILES['default_image']['name'] != '') {
          preg_match("/(.+)\.[^\.]+$/", $_FILES['default_image']['name'], $matches);
          $data['imgBase'] = $matches[1];
        } else {
          $messageStack->add(TEXT_MSG_AUTO_BASE_ERROR, 'error');
          $check = 1;
        }
      }
      
      // catch nasty characters
      if (preg_match("|\+|", $data['imgBase'])) {
        $data['imgBase'] = str_replace("\+", "-", $data['imgBase']);
        $messageStack->add( TEXT_MSG_AUTO_REPLACE .$data['imgBase'], 'warning' );
      }
      
      if ( $_POST['imgNewBaseDir'] != '') {
        $data['imgBaseDir'] = $_POST['imgNewBaseDir'];
      } else {
        $data['imgBaseDir'] = $_POST['imgBaseDir'];
      }
      
      $data['imgSuffix'] = "";

      if ($_POST['imgNaming'] == 'new_copy') {
        // need to copy/rename additional images for new default image
        // this will be implemented in a future release
      }    
      
    } else if (isset($_GET['imgEdit']) && $_GET['imgEdit'] == 1) {
      $data['imgBaseDir'] = $_GET['imgBaseDir'];
      $data['imgBase'] = $_GET['imgBase'];
      //$data['imgExtension'] = $_GET['imgExtension'];
      $data['imgSuffix'] = $_GET['imgSuffix'];
    } else {
      // An additional image is being added
      $data['imgBaseDir'] = $_GET['imgBaseDir'];
      $data['imgBase'] = $_GET['imgBase'];
      //$data['imgExtension'] = $_GET['imgExtension'];
            
      // Image Suffix (if set)
      if ($_POST['imgSuffix'] != '') {
        $data['imgSuffix'] = '_'.$_POST['imgSuffix'];
      } else { 
        // get directory list
        $array = array();
        $ih_admin->findAdditionalImages($array, DIR_FS_CATALOG . DIR_WS_IMAGES . $data['imgBaseDir'], 
          $data['imgExtension'], $data['imgBase'] );
        echo "<!--  point 1".$data['imgExtension']."-->";
        $c = sizeof( $array );
        if ($c > 1) {
          sort($array);
        }
        
        // calculate the next suffix
        // (This is lame, unscalable, and inefficient, but effective)
        $suffix = 1;
        $m = 0;
        while ($m != 1) {
          
          if ($suffix < 10) {
            $suffixStr = "0".$suffix;
          } else { 
            $suffixStr = $suffix;
          }
          
          $string = $data['imgBase'] . '_'. $suffixStr . $data['imgExtension'];
		  echo "<!--  point 2".$data['imgExtension']."-->";
          $n = 0;
          for ($i=0; $i < $c; $i++) {
            if ($array[$i] == $string) {
              $n = 1;
            } 
          }
          if ($n == 1) {
            $suffix++;
          } else {
            $data['imgSuffix'] = "_".$suffixStr;
            $m = 1; 
          }
        }
      }
    } // if newImg

    // determine the filenames 
    if ($check != 1) {
      // add slash to base dir
      if (($data['imgBaseDir'] != '') && (!preg_match("|\/$|", $data['imgBaseDir']))) {
        $data['imgBaseDir'] .= '/'; 
      }
      $data['defaultFileName'] = $data['imgBaseDir']
        . $data['imgBase'] 
        . $data['imgSuffix']
        . $data['imgExtension'];

      // Check if the file already exists
      if ( isset($_GET['imgEdit']) && ($_GET['imgEdit'] != 1) && (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . $data['defaultFileName'])) ) {
        $messageStack->add( TEXT_MSG_FILE_EXISTS, 'error' );
        $check = 1;
      }
            
    }


    // Update the database
    if (($check != 1) && 
        ( isset($_GET['newImg']) && ($_GET['newImg'] == 1) || (isset($_GET['imgEdit']) && ($_GET['imgEdit'] == 1) && ($_GET['imgSuffix'] ==  '') && ($_POST['imgNaming'] != 'keep_name') && ($_FILES['default_image']['name'] != '')) )) {
      // update the database
      $sql = "update ". TABLE_PRODUCTS . " set products_image='"
        .$data['defaultFileName']."' where products_id='".$products_filter."'";
      if ( ! $db->Execute($sql) ) {
        $messageStack->add(TEXT_MSG_INVALID_SQL, "error");
        $check = 1;
      }
    }

    if ($check != 1) {
      // check for destination directory and create, if they don't exist!
      // Then move uploaded file to its new destination
      
      // default image
      if ($_FILES['default_image']['name'] != '') {
        io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['defaultFileName']);
        $source_name = $_FILES['default_image']['tmp_name'];
        $destination_name = DIR_FS_CATALOG_IMAGES . $data['defaultFileName'];
        if ( !move_uploaded_file($source_name, $destination_name) ) {
          $messageStack->add( TEXT_MSG_NOUPLOAD_DEFAULT, "error" );
          $check = 1;
        }
      }  // Nigel Hack for special idiots  
		  
	  elseif (($_FILES['default_image']['name'] == '') && !(isset($_GET['imgEdit']))) {
         io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['defaultFileName']);
        $source_name = $_FILES['default_image']['tmp_name'];
        $destination_name = DIR_FS_CATALOG_IMAGES . $data['defaultFileName'];
        if ( !move_uploaded_file($source_name, $destination_name) ) {
          $messageStack->add( 'you must select a default image', "error" );
          $check = 1;
		  $_FILES['medium_image']['name'] = $_FILES['large_image']['name'] = '';
        }
      }  // End special idiots hack
      // medium image
      if ($_FILES['medium_image']['name'] != '') {
        $data['mediumImgExtension'] = substr( $_FILES['medium_image']['name'], 
          strrpos($_FILES['medium_image']['name'], '.'));
        $data['mediumFileName'] ='medium/' . $data['imgBaseDir']
          . $data['imgBase'] 
          . $data['imgSuffix'] . IMAGE_SUFFIX_MEDIUM
          . $data['mediumImgExtension'];
        io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['mediumFileName']);
        $source_name = $_FILES['medium_image']['tmp_name'];
        $destination_name = DIR_FS_CATALOG_IMAGES . $data['mediumFileName'];
        if ( !move_uploaded_file($source_name, $destination_name) ) {
          $messageStack->add( TEXT_MSG_NOUPLOAD_MEDIUM, "error" );
          $check = 1;
        }
      }
      // large image
      if ($_FILES['large_image']['name'] != '') {
        $data['largeImgExtension'] = substr( $_FILES['large_image']['name'], 
          strrpos($_FILES['large_image']['name'], '.'));
        $data['largeFileName'] = 'large/' . $data['imgBaseDir']
          . $data['imgBase'] 
          . $data['imgSuffix'] . IMAGE_SUFFIX_LARGE
          . $data['largeImgExtension'];
        io_makeFileDir(DIR_FS_CATALOG_IMAGES.$data['largeFileName']);
        $source_name = $_FILES['large_image']['tmp_name'];
        $destination_name = DIR_FS_CATALOG_IMAGES . $data['largeFileName'];
        if ( !move_uploaded_file($source_name, $destination_name) ) {
          $messageStack->add( TEXT_MSG_NOUPLOAD_LARGE, "error" );
          $check = 1;
        }
      }  
    }

    if ($check == 1) {
      if ($_GET['imgEdit'] == 1) {
        $action = "layout_edit";
      } else {
        $action = "layout_new";
      }
      $repeat_check = 1;
    } else {
      // Data has been saved
      // show the new image information
            
      $messageStack->add( TEXT_MSG_IMAGE_SAVED, 'success' );
      // we might need to clear the cache if filenames are kept
      if (isset($_GET['imgEdit']) && $_GET['imgEdit'] == 1) {
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
    if (($data['imgBaseDir'] != '') && (!preg_match("|\/$|", $data['imgBaseDir']))) {
      $data['imgBaseDir'] .= '/'; 
    }


    // Determine file names
    $data['defaultFileName'] = DIR_FS_CATALOG.DIR_WS_IMAGES
        . $data['imgBaseDir'] . $data['imgBase'] 
        . $data['imgSuffix'] . $data['imgExtension'];
    $data['mediumFileName'] = DIR_FS_CATALOG.DIR_WS_IMAGES
        . 'medium/'
        . $data['imgBaseDir'] . $data['imgBase'] 
        . $data['imgSuffix'] . IMAGE_SUFFIX_MEDIUM
        . $data['imgExtension'];
    $data['largeFileName'] = DIR_FS_CATALOG.DIR_WS_IMAGES
        . 'large/'
        . $data['imgBaseDir'] . $data['imgBase'] 
        . $data['imgSuffix'] . IMAGE_SUFFIX_LARGE
        . $data['imgExtension'];
    
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
      $sql = "update ". TABLE_PRODUCTS . " set products_image='' where products_id='".$products_filter."'";
      if ( ! $db->Execute($sql) ) {
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
