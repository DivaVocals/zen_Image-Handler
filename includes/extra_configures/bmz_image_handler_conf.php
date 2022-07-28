<?php
/**
 * mod Image Handler 5.0.1
 * bmz_image_handler_conf.php
 * additional configuration entries for image handler
 *
 * @author  Tim Kroeger (original author)
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: bmz_image_handler_conf.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Modified by DerManoMann 2010-05-31 23:46:50 
 * Modified by lat9 2018-05-20, Remove 'allow_mixed_case_ext' (see GitHub #89)
 */
$ihConf = array();
$ihConf['noresize_key']         = 'noresize';         //files which contain this string will not be resized
$ihConf['noresize_dirs']        = array('noresize', 'banners'); //images in directories with these names within the images directory will not be resized.
$ihConf['trans_threshold']      = '90%';              //this is where semitransparent pixels blend to transparent when rendering gifs with ImageMagick
$ihConf['im_convert']           = '';                 //if you want to use ImageMagick, you must specify the convert binary here (e.g. '/usr/bin/convert')
$ihConf['gdlib']                = 2;                  //the GDlib version (0, 1 or 2) 2 tries to autodetect
$ihConf['default']['bg']        = 'transparent 255:255:255';
$ihConf['default']['quality']   = 85;