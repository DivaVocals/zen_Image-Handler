<?php
// mod Image Handler 4.3.3

// copyright stuff

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
} 


$autoLoadConfig[199][] = array(
    'autoType' => 'init_script',
    'loadFile' => 'init_image_handler.php'
    );  

// set a flag that is used in file overwrites/
$extraXXX = 'IH_'.date('U');

// uncomment the following line to perform a uninstall
// $uninstall = 'uninstall';