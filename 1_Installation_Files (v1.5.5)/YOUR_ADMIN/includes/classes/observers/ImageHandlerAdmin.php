<?php
// -----
// Part of the "Image Handler" plugin, v5.0.0 and later, by Cindy Merkin a.k.a. lat9 (cindy@vinosdefrutastropicales.com)
// Copyright (c) 2017 Vinos de Frutas Tropicales
//
class ImageHandlerAdmin
{
    public function getImageDetailsString($filename) 
    {
        if (!file_exists($filename)) {
            return "no info";
        }
        // find out some details about the file 
        $image_size = @getimagesize($filename);
        $image_fs_size = filesize($filename);

        $str .= $image_size[0] . "x" . $image_size[1];
        $str .= "<br /><strong>" . round($image_fs_size/1024, 2) . "Kb</strong>";

        return $str;
    }
    
    //
    // Search the base directory and find additional images
    //
    public function findAdditionalImages(&$array, $directory, $extension, $base ) 
    {
        $image = $base . $extension;

        // Check for additional matching images
        if ($dir = @dir($directory)) {
            while ($file = $dir->read()) {
                if (!is_dir($directory . $file)) {
                    if (preg_match("/^" . $base . "/i", $file) == '1') {
                         //error_log( "BASE: ".$base.' FILE: '.$file.PHP_EOL);
                        if (substr($file, 0, strrpos($file, '.')) != substr($image, 0, strrpos($image, '.'))) {
                            if ($base . preg_replace("/^$base/", '', $file) == $file) {
                                $array[] = $file;
                                 //error_log( "\tI AM A MATCH " . $directory . '/'.$file . $extension .PHP_EOL);
                            } else {
                                 //error_log( "\tI AM NOT A MATCH" . $file . PHP_EOL);
                            } 
                        }
                    }
                }
            }
            if (count($array) > 1) {
                sort($array);
            }
            $dir->close();
          
            return 1;
        }
        return 0;
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
    
    public function ihLog ($message, $message_type = 'general') {

    }

}
