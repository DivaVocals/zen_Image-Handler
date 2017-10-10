<?php
// -----
// Part of the "Image Handler" plugin for Zen Cart 1.5.5 and later.
// Copyright (c) 2017 Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// ----
// Initialize the plugin's observer ...
// 
$autoLoadConfig[200][] = array(
    'autoType' => 'class',
    'loadFile' => 'observers/ImageHandlerObserver.php'
);
$autoLoadConfig[200][] = array(
    'autoType' => 'classInstantiate',
    'className' => 'ImageHandlerObserver',
    'objectName' => 'imageHandlerObserver'
);
