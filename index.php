#!/usr/bin/php
<?php
/**
 * @author Christian Ziegenrücker
 * @version 0.1
 * 
 * Druper - Drupal Repo Builder
 */

switch ($argv[1]) {
    case 'init':
            _init();
        break;
    case 'sync':
            _sync();
        break;
    case 'rebuild':
            _rebuild();
        break;
}

function _init() {
    
    global $argv;
    
    if (empty($argv[2])) {
        echo "\033[31mPlease provide a manifest file.\033[0m".PHP_EOL;
    } 
    else {
        
        passthru('repo init -u '.$argv[2], &$return);
        
        if ($return == 0) {
            try {
                $manifest = new SimpleXmlElement('file://'.getcwd().'/.repo/manifest.xml', NULL, TRUE);  
                
                print_r($manifest);
                
                unset($manifest);
            }
            catch (Exception $ex) {
                echo "\033[31mSomething went wrong while reading the manifest.\033[0m".PHP_EOL;
                
            }
        } 
        else {
           echo "\033[31mRepo not finished successfully.\033[0m".PHP_EOL;
        }
        
       
    }
    
}

?>
