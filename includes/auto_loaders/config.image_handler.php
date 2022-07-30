<?php
// -----
// Part of the "Image Handler" plugin for Zen Cart 1.5.7 and later.
// Copyright (c) 2017-2022 Vinos de Frutas Tropicales
//
// Last updated: IH 5.3.0
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// ----
// Initialize the plugin's observer ...
// 
$autoLoadConfig[200][] = [
    'autoType' => 'class',
    'loadFile' => 'observers/ImageHandlerObserver.php'
];
$autoLoadConfig[200][] = [
    'autoType' => 'classInstantiate',
    'className' => 'ImageHandlerObserver',
    'objectName' => 'imageHandlerObserver'
];
