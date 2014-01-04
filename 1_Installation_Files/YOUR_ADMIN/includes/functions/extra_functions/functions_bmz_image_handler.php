<?php
/**
 * functions_bmz_image_handler.php
 * call to include IH2 functions from catalog
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: functions_bmz_image_handler.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by DerManoMann 2010-05-31 23:46:50 
 */

require_once(DIR_FS_CATALOG . DIR_WS_FUNCTIONS . 'extra_functions/functions_bmz_image_handler.php');

global $ihConf;

$ihConf['dir']['admin'] = preg_replace('/^\/(.*)/', '$1', (($request_type == 'SSL') ? DIR_WS_HTTPS_ADMIN : DIR_WS_ADMIN));