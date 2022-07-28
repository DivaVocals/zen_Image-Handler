<?php
/**
 * mod Image Handler 4.3.3
 * bmz_io_conf.php
 * filesystem access configuration
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: bmz_io_conf.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Last modified by DerManoMann 2010-05-31 23:46:50 
 */
 
$bmzConf = array();
$bmzConf['umask']       = 0111;              //set the umask for new files
$bmzConf['dmask']       = 0000;              //directory mask accordingly
//$bmzConf['cachetime']   = 60*60*24;         //maximum age for cachefile in seconds (defaults to a day)
$bmzConf['cachetime']   = 0;         //maximum age for cachefile in seconds (defaults to a day)
$bmzConf['cachedir']    = DIR_FS_CATALOG . 'bmz_cache';
$bmzConf['lockdir']     = DIR_FS_CATALOG . 'bmz_lock';
