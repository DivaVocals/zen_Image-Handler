<?php
/**
 * mod Image Handler
 * bmz_image_handler_conf.php
 * call to include IH4 configures from catalog
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: bmz_image_handler_conf.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by DerManoMann 2010-05-31 23:46:50 
 */
if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    exit('Invalid access');
}

 require DIR_FS_CATALOG . DIR_WS_INCLUDES . 'extra_configures/bmz_image_handler_conf.php';
