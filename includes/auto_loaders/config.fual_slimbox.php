<?php
// -----
// Part of the "Image Handler" plugin for Zen Cart 1.5.5 and later.
// Copyright (c) 2017 Vinos de Frutas Tropicales
//
// This module (and the accompanying observer class) provide the "Fual Slimbox"
// display of products' images (both main and additional) using the Zen Cart
// notifiers added by v5.0.0 and later of the "Image Handler" plugin.
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// ----
// Initialize the plugin's observer ...
// 
$autoLoadConfig[200][] = array(
    'autoType' => 'class',
    'loadFile' => 'observers/FualSlimboxObserver.php'
);
$autoLoadConfig[200][] = array(
    'autoType' => 'classInstantiate',
    'className' => 'FualSlimboxObserver',
    'objectName' => 'fualSlimboxObserver'
);
