<?php
// -----
// Part of the "Image Handler" plugin, v5.0.0 and later, by Cindy Merkin a.k.a. lat9 (cindy@vinosdefrutastropicales.com)
// Copyright (c) 2017 Vinos de Frutas Tropicales
//
if (!defined('IH_DEBUG')) {
    define('IH_DEBUG', 'true'); //-Either 'true' or 'false'
}
class ImageHandlerAdmin
{
    public function __construct()
    {
        $this->debug = (IH_DEBUG == 'true');
        $this->debugLogfile = DIR_FS_LOGS . '/ih_debug.log';
    }
    
    public function getImageDetailsString($filename) 
    {
        if (!file_exists($filename)) {
            return "no info";
        }
        // find out some details about the file 
        $image_size = @getimagesize($filename);
        $image_fs_size = filesize($filename);

        $str = $image_size[0] . "x" . $image_size[1];
        $str .= "<br /><strong>" . round($image_fs_size/1024, 2) . "Kb</strong>";

        return $str;
    }
    
    // -----
    // Search the directory specified for matching additional images, presumed to be a sub-directory
    // off the images root directory.
    //
    // Note that Zen Cart's additional-images "matching" depends on whether the images are present
    // in the image-root ($directory is '') or in an actual subdirectory (non-blank $directory).
    //
    // When in the root, **any** matching suffix for the main-image's name results in a matching
    // additional image (e.g. my_image.jpg matches my_images.jpg, my_images-1.jpg) while the
    // matching suffix for sub-directory images **must** have an underscore (_) separating the names,
    // e.g. my_image.jpg matches my_image_01.jpg, my_image_01_02_03.jpg ... but does not match
    // my_images.jpg or my_images-1.jpg).
    //
    // The function returns a simple, sorted array containing the matching filenames (without the
    // directory information).
    //
    public function findAdditionalImages(&$array, $directory, $extension, $base) 
    {
        // -----
        // Set up the to-be-matched image name, depending on whether the search is being
        // performed in the root or a sub-directory. For the root, any matching characters after the 
        // main-image name; for a sub-directory, 0 (for the main-image) or 1 (for any additional)
        // matches on _{something}.
        //
        $image_match = ($directory == '') ? '.*' : '(?:_.*)?';
        
        // -----
        // Determine whether the directory specified is, in fact, an images sub-directory.  If
        // not, no files will be returned.
        //
        $error = false;
        try {
            $image_dir = new DirectoryIterator(DIR_FS_CATALOG . DIR_WS_IMAGES . $directory);
        } catch(Exception $e) {
            $error = true;
            $this->debugLog("findAdditionalImages(array, $directory, $extension, $base), could not iterate directory." . $e->getMessage());
        }
        
        // -----
        // If the requested directory was accessed without error, find those images!
        //
        if (!$error) {
            // -----
            // The quotemeta function properly escapes any "regex" special characters that
            // might be present in either the image's base name or file extension, e.g. '.jpg'
            // will be converted to '\.jpg'.
            //
            $filename_match = quotemeta($base) . $image_match . quotemeta($extension);
            
            // -----
            // Now, do a regex search of the specified directory, looking for matches on the
            // base-image's name with the additional-image modifier.
            //
            $img_files = new RegexIterator($image_dir, '/^' . $filename_match . '$/i', RegexIterator::GET_MATCH);
            foreach ($img_files as $i => $image_array) {
                $array[] = $image_array[0];
            }
            
            // -----
            // If there are elements in the array of images, sort them!
            //
            if (count($array) > 1) {
                sort($array);
            }
        }
        return ($error) ? 0 : 1;
    }
    
    public function getImportInfo() 
    {
        $products = $GLOBALS['db']->Execute(
            "SELECT products_id, products_image 
               FROM " . TABLE_PRODUCTS . " 
              WHERE products_image != '' 
           ORDER BY products_image ASC"
        );
        $info = array();
        $index = 0;
        $previous_image = '';
        while (!$products->EOF){
            $image = $products->fields['products_image'];
            if ($image != $previous_image) {
                $previous_image = $image;
                $original_image = $this->findOriginalImage($image);
                if ($original_image) {
                    $info[$index]['source'] = $image;
                    $info[$index]['original'] = $original_image;
                    $info[$index]['target'] = preg_replace('/^original\//', '', $original_image);
                    $index++;  
                }
            }
            $products->MoveNext();
        }
        return $info;
    }
    
    public function findOriginalImage($src) 
    {
        // try to find file by using different file extensions if initial
        // source doesn't succeed
        $imageroot = $GLOBALS['ihConf']['dir']['docroot'] . $GLOBALS['ihConf']['dir']['images'] . 'original/';
        if (is_file($imageroot . $src)) {
            return 'original/' . $src;
        } else {
            // do a quick search for files with common extensions
            $extensions = array('.png', '.PNG', '.jpg', '.JPG', '.jpeg', '.JPEG', '.gif', '.GIF');
            $base = substr($src, 0, strrpos($src, '.'));
            for ($i=0; $i<count($extensions); $i++) {
                if (is_file($imageroot . $base . $extensions[$i])) {
                    return 'original/' . $base . $extensions[$i];
                }
            }
            // not found? maybe mixed case file extension?
            if ($GLOBALS['ihConf']['allow_mixed_case_ext']) {
                // this can cost some time for every displayed image so default is
                // to not do this search
                $directory = dirname($imageroot . $src);
                $dir = @dir($directory);
                while ($file = $dir->read()) {
                    if (!is_dir($directory . $file)) {
                        if (preg_match("/^" . $imageroot . $base . "/i", $file) == '1') {
                            $file_ext = substr($file, strrpos($file, '.'));
                            if (is_file($imageroot . $base . $file_ext)) {
                                return 'original/' . $base . $file_ext;
                            }
                        }
                    }
                }
            }
        }
        // still here? no file found...
        return false;
    }
    
    public function debugLog($message) {
        if ($this->debug) {
            error_log(PHP_EOL . date('Y-m-d H:i:s: ') . $message . PHP_EOL, 3, $this->debugLogfile);
        }
    }
}
