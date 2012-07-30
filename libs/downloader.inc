<?php
/**
 * @tode download foreach?
 */
function downloader( $project ) {
        
    // declare variables 
    $patchSource = (string) $project->download['src'];
    $projectPath = (string) $project['path'];
    $patchDestination = (string) $projectPath . '/' . $project->download['dest'];
    
    // find filename
    $patchName = basename($patchDestination);
    
    // the patch destination without filename
    $patchDestination = substr($patchDestination, 0, strrpos($patchDestination, "/") + 1);
        
    // creates the target directory
    @mkdir(getcwd() . '/' . $patchDestination, 0777, TRUE);
    
    // downloads the file
    exec( 'wget -q ' . $patchSource . ' -O' . getcwd() . '/' . $patchDestination . 
           $patchName, &$output, &$return );
    
    $target = '/' .$patchDestination . $patchName;
    
    // output message to indicate success or failure  
    if ( $return != 0 ) {
        die( "\033[31m    Download of " . $patchName . " to " . $target .
                " failed.\033[0m".PHP_EOL);
    }
    else {
        echo "\033[32m    Download of " . $patchName . " to " . $target .
                " complete.\033[0m".PHP_EOL;
    }
            
}
?>
