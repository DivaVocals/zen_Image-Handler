<?php 
// mod Image Handler 4.3.4

// copyright stuff

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

//=======================================
//
// SET INSTALLATION VARIABLES
//
//=======================================

    //$uninstall = 'uninstall';

    // set version
    $version = '4.3.4';

    // flags
    $install_incomplete = false;
    $no_template = false;

    // find current template
    $sql = "SELECT template_dir FROM ".TABLE_TEMPLATE_SELECT." LIMIT 1";
    $obj = $db->Execute($sql);
    $current_template = $obj->fields['template_dir'];

    if($current_template == '' )
    {
        $install_incomplete = true;
        $no_template = true;
    }

    // make  override directories if needed
    @mkdir(DIR_FS_CATALOG.'includes/modules/'.$current_template, 0755);
    @mkdir(DIR_FS_CATALOG.'includes/templates/'.$current_template.'/css', 0755);
    @mkdir(DIR_FS_CATALOG.'includes/templates/'.$current_template.'/jscript', 0755);
    @mkdir(DIR_FS_CATALOG.'includes/templates/'.$current_template.'/popup_image_additional', 0755);

    // new files or non-core files
    // these are deleted on uninstall
     $files = array(
            DIR_FS_CATALOG.'bmz_cache/.htaccess',
            DIR_FS_CATALOG.'images/watermark.png',
            DIR_FS_CATALOG.'images/large/watermark_LRG.png',
            DIR_FS_CATALOG.'images/medium/watermark_MED.png',
            DIR_FS_CATALOG.'includes/classes/bmz_gif_info.class.php',
            DIR_FS_CATALOG.'includes/classes/bmz_image_handler.class.php',
            DIR_FS_CATALOG.'includes/extra_configures/bmz_image_handler_conf.php',
            DIR_FS_CATALOG.'includes/extra_configures/bmz_io_conf.php',
            DIR_FS_CATALOG.'includes/functions/extra_functions/functions_bmz_image_handler.php',
            DIR_FS_CATALOG.'includes/functions/extra_functions/functions_bmz_io.php',
            DIR_FS_ADMIN.'image_handler.php',
            DIR_FS_ADMIN.'includes/ih_manager.php',
            DIR_FS_ADMIN.'includes/init_includes/init_image_handler.php',
            DIR_FS_ADMIN.'includes/auto_loaders/config.image_handler.php',
            DIR_FS_ADMIN.'images/checkpattern.gif',
            DIR_FS_ADMIN.'images/icon_image_handler.gif',
            DIR_FS_ADMIN.'images/ih-test.gif',
            DIR_FS_ADMIN.'images/ih-test.jpg',
            DIR_FS_ADMIN.'images/ih-test.png',
            DIR_FS_ADMIN.'includes/extra_configures/bmz_image_handler_conf.php',
            DIR_FS_ADMIN.'includes/extra_configures/bmz_io_conf.php',
            DIR_FS_ADMIN.'includes/extra_datafiles/image_handler.php',
            DIR_FS_ADMIN.'includes/functions/extra_functions/functions_bmz_image_handler.php',
            DIR_FS_ADMIN.'includes/functions/extra_functions/functions_bmz_io.php',
            DIR_FS_ADMIN.'includes/languages/english/extra_definitions/bmz_image_handler.php',
            DIR_FS_ADMIN.'includes/languages/english/extra_definitions/bmz_language_admin.php',
            DIR_FS_ADMIN.'includes/modules/category_product_listing.DEFAULT.php.OLD.IH4',
            DIR_FS_CATALOG.'includes/modules/pages/popup_image/header_php.DEFAULT.php.OLD.IH4',
            DIR_FS_CATALOG.'includes/modules/pages/popup_image_additional/header_php.DEFAULT.php.OLD.IH4'
             );

    // core files with overwrite
    // these are rolled back to Zen Default on uninstalll - the .OLD.IH4 file is left in place
    // files arranged in array (file_to_replace,file_to_replace_with)
    // file_to_replace will be resaved as file_to_replace.OLD.IH4
    $core_files = array(
            array(DIR_FS_ADMIN.'includes/modules/category_product_listing.php',DIR_FS_ADMIN.'includes/modules/category_product_listing_IH4.php'),
            array(DIR_FS_CATALOG.'includes/modules/pages/popup_image/header_php.php',DIR_FS_CATALOG.'includes/modules/pages/popup_image/header_php_IH4.php'),
            array(DIR_FS_CATALOG.'includes/modules/pages/popup_image_additional/header_php.php',DIR_FS_CATALOG.'includes/modules/pages/popup_image_additional/header_php_IH4.php'),
            );

    // core files for rollback on uninstall 
    // not used on install
    // files arranged in array (file_to_replace,file_to_replace_with)
    // file_to_replace will be resaved as file_to_replace.OLD.IH4
    $rollback_files = array(
            array(DIR_FS_ADMIN.'includes/modules/category_product_listing.php',DIR_FS_ADMIN.'includes/modules/category_product_listing.DEFAULT.php.OLD.IH4'),
            array(DIR_FS_CATALOG.'includes/modules/pages/popup_image/header_php.php',DIR_FS_CATALOG.'includes/modules/pages/popup_image/header_php.DEFAULT.php.OLD.IH4'),
            array(DIR_FS_CATALOG.'includes/modules/pages/popup_image_additional/header_php.php',DIR_FS_CATALOG.'includes/modules/pages/popup_image_additional/header_php.DEFAULT.php.OLD.IH4'),
            );



    // template files
    // these are deleted on uninstall - the .OLD.IH4 file is left in place
    // files arranged in array (file_to_replace,file_to_replace_with)
    // file_to_replace will be resaved as file_to_replace.OLD.IH4
    $template_files = array(
            array(DIR_FS_CATALOG.'includes/modules/'.$current_template.'/additional_images.php',DIR_FS_CATALOG.'includes/modules/IH_INSTALL/additional_images.php'),
            array(DIR_FS_CATALOG.'includes/modules/'.$current_template.'/main_product_image.php',DIR_FS_CATALOG.'includes/modules/IH_INSTALL/main_product_image.php'),
            array(DIR_FS_CATALOG.'includes/templates/'.$current_template.'/css/style_imagehover.css',DIR_FS_CATALOG.'includes/templates/IH_INSTALL/css/style_imagehover.css'),
            array(DIR_FS_CATALOG.'includes/templates/'.$current_template.'/jscript/jscript_imagehover.js',DIR_FS_CATALOG.'includes/templates/IH_INSTALL/jscript/jscript_imagehover.js'),
            array(DIR_FS_CATALOG.'includes/templates/'.$current_template.'/popup_image_additional/tpl_main_page.php',DIR_FS_CATALOG.'includes/templates/IH_INSTALL/popup_image_additional/tpl_main_page.php')
            );


    // Configuration Values to create or preserve
    $menu_items_image = array(
            array('IH_RESIZE','no',1001,array('yes','no')),
            array('SMALL_IMAGE_FILETYPE','no_change',1011,array('gif','jpg','png','no_change')),
            array('SMALL_IMAGE_BACKGROUND','255:255:255',1021,false),
            array('SMALL_IMAGE_QUALITY',85,1031,false),
            array('WATERMARK_SMALL_IMAGES','no',1041,array('no','yes')),
            array('ZOOM_SMALL_IMAGES','yes',1051,array('no','yes')),
            array('ZOOM_IMAGE_SIZE','Medium',1061,array('Medium','Large')),
            array('MEDIUM_IMAGE_FILETYPE','no_change',1071,array('gif','jpg','png','no_change')),
            array('MEDIUM_IMAGE_BACKGROUND','255:255:255',1081,false),
            array('MEDIUM_IMAGE_QUALITY',85,1091,false),
            array('WATERMARK_MEDIUM_IMAGES','no',1101,array('no','yes')),
            array('LARGE_IMAGE_FILETYPE','no_change',1111,array('gif','jpg','png','no_change')),
            array('LARGE_IMAGE_BACKGROUND','255:255:255',1121,false),
            array('LARGE_IMAGE_QUALITY',85,1131,false),
            array('WATERMARK_LARGE_IMAGES','no',1141,array('no','yes')),
            array('LARGE_IMAGE_MAX_WIDTH',750,1151,false),
            array('LARGE_IMAGE_MAX_HEIGHT',550,1161,false),
            array('WATERMARK_GRAVITY','Center',1171,array('Center','NorthWest','North','NorthEast','East','SouthEast','South','SouthWest','West'))
            );


    // Legacy Configuration Values to Delete Completely
    $menu_items_delete = array(
            array('ZOOM_GRAVITY'),
            array('SMALL_IMAGE_HOTZONE'),
            array('ZOOM_MEDIUM_IMAGES'),
            array('MEDIUM_IMAGE_HOTZONE'),
            array('ADDITIONAL_IMAGE_FILETYPE'),
            array('ADDITIONAL_IMAGE_BACKGROUND'),
            array('SHOW_UPLOADED_IMAGES')
            );



//=======================================
// INSTALL CHECK
//=======================================

    // do not run installer on log in page
    if(strpos(__FILE__,'login.php'))
    {
        $install_incomplete=true;
        $_SESSION['IH_install_delayed'] = true;
        $messageStack->add_session('IH_delayed','success');

    }

    if($uninstall != 'uninstall')
    {


    foreach($files as $f)
    {
        if(!is_readable($f))
        {
        $messageStack->add('' . IH_MS_MISSING_OR_UNREADABLE . ''.$f, 'warning');
        $install_incomplete = true;
        }
    }

    foreach($core_files as $f)
    {
        if(!is_readable($f[1]))
        {
        $messageStack->add('' . IH_MS_MISSING_OR_UNREADABLE . ' '.$f[1], 'warning');
        $install_incomplete = true;
        }
    }

    foreach($template_files as $f)
    {
        if(!is_readable($f[1]))
        {
        $messageStack->add('' . IH_MS_MISSING_OR_UNREADABLE . ''.$f[1], 'warning');
        $install_incomplete = true;
        }
    }

    if($install_incomplete and $uninstall != 'uninstall' and $no_template)
    {
        $messageStack->add('' . IH_MS_TEMPLATE_NOTFOUND . '', 'warning');
        $messageStack->add('' . IH_MS_ABORTED . '', 'warning');
    }
    elseif($install_incomplete and $uninstall != 'uninstall')
    {
        $messageStack->add('' . IH_MS_SOME_FILES_MISSING .'', 'warning');
        $messageStack->add('' . IH_MS_ABORTED .'', 'warning');
    }else{
        $messageStack->add('' . IH_MS_ALL_EXIST .'', 'success');
        
    }

    }

//=======================================
// INSTALL
//=======================================

if($uninstall != 'uninstall' and !$install_incomplete)
{

    // ======================================================
    //
    // add items to the images menu 
    //
    // ======================================================

    /* Find Config ID of Images */
    $sql = "SELECT configuration_group_id FROM ".TABLE_CONFIGURATION_GROUP." WHERE configuration_group_title='Images' LIMIT 1";
    $result = $db->Execute($sql);
        $im_configuration_id = $result->fields['configuration_group_id'];
    if($im_configuration_id=='') $im_configuration_id = 4 ;


    foreach($menu_items_image as $menu_item)
    {
    xxxx_create_menu_item($menu_item[0],$menu_item[1],$menu_item[2],$im_configuration_id,$menu_item[3]);
    }

    // ======================================================
    //
    // register the IH page in admin pages for Zen 1.5 
    //
    // ======================================================

    if (defined('TABLE_ADMIN_PAGES')) 
    {

    zen_deregister_admin_pages('configImageHandler4');
    zen_register_admin_page('configImageHandler4',
        'BOX_TOOLS_IMAGE_HANDLER', 'FILENAME_IMAGE_HANDLER',
        '', 'tools', 'Y',
        14);

    }

    // ======================================================
    //
    // version
    //
    // ======================================================

    $over_item = array('IH_VERSION',$version,10000,false);
    xxxx_overwrite_create_menu_item($over_item[0],$over_item[1],$over_item[2],0,$over_item[3]);

    // ======================================================
    //
    // core files overwrite
    //
    // ======================================================

    foreach($core_files as $cf)
    {
        if(xxxx_install_replace($cf[0],$cf[1]))
        {
            $messageStack->add('CORE FILE OVERWRITE : '.$cf[0].' ' . IH_MS_OVERWRITTEN . '', 'success');		
        }else{
            $messageStack->add('CORE FILE OVERWRITE : '.$cf[0].' ' . IH_MS_NOT_OVERWRITTEN . '', 'warning');
        }
    }

    // ======================================================
    //
    // template files create
    //
    // ======================================================

    foreach($template_files as $cf)
    {
        if(xxxx_install_replace($cf[0],$cf[1]))
        {
            $messageStack->add('TEMPLATE FILE CREATE : '.$cf[0].' ' . IH_MS_CREATED . '', 'success');
        }else{
            $messageStack->add('TEMPLATE FILE CREATE : '.$cf[0].'' . IH_MS_NOT_CREATED . '', 'warning');
        }
    }

    // ======================================================
    //
    // delete legacy configuration options
    //
    // ======================================================

    foreach($menu_items_delete as $del)
    {
        $sql ="DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_key = '".$del[0]."'";
        $db->Execute($sql);
    }

    // ======================================================
    //
    // delete the auto-loader
    //
    // ====================================================== 
    if(file_exists(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'auto_loaders/config.image_handler.php'))
    {
        if(!unlink(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'auto_loaders/config.image_handler.php'))
	{
		$messageStack->add('' . IH_MS_AUTOLOADER_NOTDELETED . '','error');
	};
    }

    $messageStack->add('' . IH_MS_SUCCESS . '', 'success');
    $_SESSION['image_handler_errors'] = false;
}
elseif($uninstall == 'uninstall')
{

// ======================================================
//
// Uninstall
//
// ====================================================== 

    // ======================================================
    //
    // remove the menu items from the images menu
    //
    // ====================================================== 

    foreach($menu_items_image as $m)
    {
        $sql ="DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_key = '".$m[0]."'";
        $db->Execute($sql);
    }

    if (defined('TABLE_ADMIN_PAGES')) 
    {
        zen_deregister_admin_pages('configImageHandler4');
    }

    // ======================================================
    //
    // rollback corefiles to default versions
    //
    // ====================================================== 

    foreach($rollback_files as $cf)
    {
        if(xxxx_install_replace($cf[0],$cf[1]))
        {
                if($message_type=='session'){
                    $messageStack->add_session('ROLLBACK  : '.$cf[0].' ' . IH_MS_ROLLBACK_OK . '', 'success');
                }else{
                    $messageStack->add('ROLLBACK  : '.$cf[0].' ' . IH_MS_ROLLBACK_OK . '', 'success');
                }
		@unlink($cf[1]);
		
        }else{
                if($message_type=='session'){
                    $messageStack->add_session('ROLLBACK : '.$cf[0].' ' . IH_MS_ROLLBACK_NOT_OK . ' ', 'warning');
                }else{
                    $messageStack->add('ROLLBACK : '.$cf[0].' ' . IH_MS_ROLLBACK_NOT_OK . ' ', 'warning');
                }
        }
    }


    // delete the non-core files 
    foreach($files as $f)
    {
        if(file_exists($f))
        {
            if(unlink($f))
            {
            //$messageStack->add_session('deleted - '.$f,'success');
            }else{
                if($message_type=='session'){
                    $messageStack->add_session('not deleted - '.$f,'error');
                }else{
                    $messageStack->add('not deleted - '.$f,'error');
                }
            }
        }
    }

    // delete the template files 
    foreach($template_files as $f)
    {
        if(file_exists($f[0]))
        {
            if(unlink($f[0]))
            {
            //$messageStack->add_session('deleted - '.$f[0],'success');
            }else{
                if($message_type=='session'){
                    $messageStack->add_session('not deleted - '.$f[1],'error');
                }else{
                    $messageStack->add('not deleted - '.$f[1],'error');
                }
            }
        }

        if(file_exists($f[1])) // may not need to do this but what the heck.
        {
            if(unlink($f[1]))
            {
            //$messageStack->add_session('deleted - '.$f[1],'success');
            }else{
                if($message_type=='session'){
                    $messageStack->add_session('not deleted - '.$f[1],'error');
                }else{
                    $messageStack->add('not deleted - '.$f[1],'error');
                }
            }
        }

    }
  

	if($message_type=='session'){
		    $messageStack->add_session('' . IH_MS_UNINSTALL_OK . '', 'success');
		    $messageStack->add_session('' . IH_MS_BACKUP_INFO . '', 'warning');
	}else{
		    $messageStack->add('' . IH_MS_UNINSTALL_OK . '', 'success');
		    $messageStack->add('' . IH_MS_BACKUP_INFO . '', 'warning');
	}
}



//=======================================
// niccol standard install/replace function
//=======================================

    function xxxx_install_replace($path,$path_from)
    {
    global $extraXXX,$messageStack;

        // move the original
        if(file_exists($path) and file_exists($path_from))
        {
            $bPath = $path.$extraXXX.'.OLD.IH4';
            if(!rename($path,$bPath)){return false;}
        }
    
        // move the from file into position
        // perhaps we need to try to return the file to its original position??
        if(file_exists($path_from))    
        {
            	if(!copy($path_from,$path)){
			return false;
		}else{
			@unlink($path_from);
		}
        }else{
            return false;
        }    
    return true;
    }

//=======================================
// niccol standard create menu item function
//=======================================

    function xxxx_create_menu_item($c_key,$default,$sort,$config_id,$values)
    {
            global $db;
            $title = $c_key.'_TITLE';


            $text = $c_key.'_TEXT';

            
            $sql = "SELECT configuration_value FROM ".TABLE_CONFIGURATION." WHERE configuration_key = '".$c_key."' LIMIT 1";
            $results = $db->Execute($sql);
            
            $config_value = ($results->fields['configuration_value'] !='')?$results->fields['configuration_value']: $default;

            $sql ="DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_key = '".$c_key."'";
            $db->Execute($sql);

            if($values)
            {
                foreach($values as $v)
                {
                $v_string .= "''".$v."'',";
                }
                $v_arr = substr($v_string,0,-1);
                $sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, '".constant($title)."', '".$c_key."', '".$config_value."', '".constant($text)."', ".$config_id.", ".$sort.", now(), now(), NULL, 'zen_cfg_select_option(array(".$v_arr."),')";
            }else{
                // text input type
                $sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, '".constant($title)."', '".$c_key."', '".$config_value."', '".constant($text)."', ".$config_id.", ".$sort.", now(), now(), NULL, NULL)";
            }
    
            $db->Execute($sql);  



            return true;

    }


//=======================================
// niccol overwrite create menu item function
//=======================================

    function xxxx_overwrite_create_menu_item($c_key,$default,$sort,$config_id,$values)
    {
            global $db;
            $title = $c_key.'_TITLE';

            $text = $c_key.'_TEXT';

            $config_value = $default;

            $sql ="DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_key = '".$c_key."'";
            $db->Execute($sql);

            if($values)
            {
                foreach($values as $v)
                {
                $v_string .= "''".$v."'',";
                }
                $v_arr = substr($v_string,0,-1);
                $sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, '".constant($title)."', '".$c_key."', '".$config_value."', '".constant($text)."', ".$config_id.", ".$sort.", now(), now(), NULL, 'zen_cfg_select_option(array(".$v_arr."),')";
            }else{
                // text input type
                $sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, '".constant($title)."', '".$c_key."', '".$config_value."', '".constant($text)."', ".$config_id.", ".$sort.", now(), now(), NULL, NULL)";
            }
    
            $db->Execute($sql);  



            return true;

    }

