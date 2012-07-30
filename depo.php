#!/usr/bin/php
<?php
/**
 * @author Christian Ziegenrücker
 * @author Sebastian Langer
 *  
 * @version 0.2
 *
 * DePo - Drupal Deploy based on Repo
 * 
 * @todo checkout of single/multiple projects but not the whole manifest
 *          repo sync modules/xmlsitemap .. .. 
 * @todo drush befehle ausführen
 * @todo Repo Refs fixen, damit pushen auch möglich ist(?)
 */

if ( !isset($argv[1]) ) {
    echo "\033[31m No parameters given.\033[0m".PHP_EOL;
} 
else {
    echo "\033[1;37m Repo started...\033[0m".PHP_EOL;
    switch ( $argv[1] ) {
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

    if ( empty($argv[2]) ) {
        echo "\033[31m Please provide a manifest file.\033[0m".PHP_EOL;
    } 
    else {

        passthru('repo init -u '.$argv[2], &$return);

        if ($return == 0) {
            echo "\033[32m Repo finished successfully.\033[0m".PHP_EOL;
        } 
        else {
            echo "\033[31m Repo not finished successfully.\033[0m".PHP_EOL;
        }
        
    }
}

function _sync() {
    
    passthru('repo sync');
    
    try {
        $manifest = new SimpleXmlElement('file://' . getcwd() . '/.repo/manifest.xml', NULL, TRUE); 
        
        foreach ( $manifest->project as $project ) {

            echo ":: ".$project['name'].PHP_EOL;

            if ( $project->patch ) {
                require_once(dirname(__FILE__).'/libs/patcher.inc');
                patcher($project);
            }
            
            if ( $project->download ) {
                require_once(dirname(__FILE__).'/libs/downloader.inc');
                downloader($project);
            }

            if ( isset($project['git-version']) && $project['git-version'] == true ) {
                require_once(dirname(__FILE__).'/libs/versioner.inc');
                versioner($project);                        
            }                                        
        }

        unset( $manifest );
    }
    catch ( Exception $ex ) {
        echo "\033[31m Something went wrong while reading the manifest.\033[0m".PHP_EOL;
        echo $ex;
    }
    
}
?>
