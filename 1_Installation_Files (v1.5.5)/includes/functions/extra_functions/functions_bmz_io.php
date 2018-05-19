<?php
/**
 * mod Image Handler 5.0.1
 * functions_bmz_io.php
 * general filesystem access handling
 *
 * @author  Tim Kroeger (original author)
 * @author Andreas Gohr
 * @copyright Copyright 2005-2006
 * @license http://www.gnu.org/licenses/gpl.txt GNU General Public License V2.0
 * @version $Id: functions_bmz_io.php,v 2.0 Rev 8 2010-05-31 23:46:5 DerManoMann Exp $
 * Modified by DerManoMann 2010-05-31 23:46:50 
 * Modified by lat9: 2017-07-17, applying PSR-2 formatting.
 * Modified by lat9: 2018-05-19, moving getCacheName to the ih_image class
 */

/**
 * Tries to lock a file
 *
 * Locking uses directories inside $bmzConf['lockdir']
 *
 * It waits maximal 3 seconds for the lock, after this time
 * the lock is assumed to be stale and the function goes on 
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Tim Kroeger <tim@breakmyzencart.com>
 */
function io_lock($file)
{
    global $bmzConf;
    // no locking if safemode hack
    //if ($bmzConf['safemodehack']) return;

    $lockDir = $bmzConf['lockdir'] . '/' . md5($file);
    @ignore_user_abort(1);
    
    $timeStart = time();
    do {
        //waited longer than 3 seconds? -> stale lock
        if ((time() - $timeStart) > 3) break;
        $locked = @mkdir($lockDir);
    } while ($locked === false);
}

/**
 * Unlocks a file
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Tim Kroeger <tim@breakmyzencart.com>
 */
function io_unlock($file)
{
    global $bmzConf;

    // no locking if safemode hack
    //if($bmzConf['safemodehack']) return;

    $lockDir = $bmzConf['lockdir'] . '/' . md5($file);
    @rmdir($lockDir);
    @ignore_user_abort(0);
}

//-bof-IH5.0.1-lat9-getCacheName function moved to bmz_image_handler_class.php


/**
 * Create the directory needed for the given file
 *
 * @author  Andreas Gohr <andi@splitbrain.org>
 * @author  Tim Kroeger <tim@breakmyzencart.com>
 */
function io_makeFileDir($file)
{
    global $messageStack, $bmzConf;

    $dir = dirname($file);
    $dmask = $bmzConf['dmask'];
    umask($dmask);
    if (!is_dir($dir)){
        io_mkdir_p($dir) || $messageStack->add("Creating directory $dir failed", "error");
    }
    umask($bmzConf['umask']); 
}

/**
 * Creates a directory hierachy.
 *
 * @link    http://www.php.net/manual/en/function.mkdir.php
 * @author  <saint@corenova.com>
 * @author  Andreas Gohr <andi@splitbrain.org>
 * @author  Tim Kroeger <tim@breakmyzencart.com>
 */
function io_mkdir_p($target)
{
    global $bmzConf;

    if (is_dir($target) || empty($target)) return 1; // best case check first
    if (@file_exists($target) && !is_dir($target)) return 0;
    //recursion
    if (io_mkdir_p(substr($target, 0, strrpos($target, '/')))) {
        return @mkdir($target, 0755); // crawl back up & create dir tree
    }
    return 0;
}
 
