#!/usr/bin/php
<?php
/**
 * @author Christian Ziegenrücker
 * @author Sebastian Langer
 * @copyright http://softlevel.de
 *  
 * @version 0.1
 *
 * DePo - Drupal Deploy based on Repo
 * 
 * @todo implementieren patches 
 * @todo implementieren version nachtragen für module
 * @todo drush befehle ausführen
 * @todo dateidownload
 * @todo Repo Refs fixen, damit pushen auch möglich ist
 */

if (!isset($argv[1])) {
    echo "\033[31m No parameters given.\033[0m".PHP_EOL;
} 
else {
    switch ($argv[1]) {
        case 'init':
                _init();
            break;
        case 'sync':
                _sync();
            break;
    }   
}

function _init() {

    global $argv; 

    if (empty($argv[2])) {
        echo "\033[31m Please provide a manifest file.\033[0m".PHP_EOL;
    } 
    else {

        echo "\033[1;37m Repo started...\033[0m".PHP_EOL;
        passthru('repo init -u '.$argv[2], &$return);

        if ($return == 0) {
            echo "\033[32mRepo finished successfully.\033[0m".PHP_EOL;
        } 
        else {
            echo "\033[31mRepo not finished successfully.\033[0m".PHP_EOL;
        }
        
    }
}

function _sync() {
    
    echo "\033[1;37mRepo started...\033[0m".PHP_EOL;
    
    passthru('repo sync');
    
    try {
                $manifest = new SimpleXmlElement('file://'.getcwd().'/.repo/manifest.xml', NULL, TRUE);  
                
                $i = 0;
                foreach ($manifest as $project) {
                    //If patch-parent available start patcher.                
                    if ($project->patch){
                        require_once('./libs/patcher.inc');
                        patcher($project);
                    }
                     
                    if(isset($project['git-version']) && $project['git-version'] == true) {
                        require_once('libs/versioner.inc');
                        versioner($project);                        
                    }
                    $i++;
                    
                }
                
                unset($manifest);
            }
            catch (Exception $ex) {
                echo "\033[31mSomething went wrong while reading the manifest.\033[0m".PHP_EOL;
                echo $ex;
            }
    
}
?>