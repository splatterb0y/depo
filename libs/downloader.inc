<?php
function downloader($project) {
        
    $patchSource = (string) $project->download['src'];
    $projectPath = (string)  $project['path'];
    $patchDestination = (string)  $projectPath . '/' . $project->download['dest'];
    
    //the filename
    $patchName = basename($patchDestination);
    
    //the patch destination without filename
    $patchDestination =  substr($patchDestination,0,strrpos($patchDestination,"/") + 1 );
        
    //creates the target directory
    @mkdir(getcwd() . '/' . $patchDestination, 0777, TRUE);
    
    //downloads the patch and saves it in the before created directory
    passthru( 'wget -q ' . $patchSource . ' -O' . getcwd() . '/' .$patchDestination . 
           $patchName, &$return );
    
    $target = '/' .$patchDestination . $patchName;
    
    //if anything while downloading or saving happens show message
    if ( $return != 0 ) {
            die( "\033[32m Download of " . $patchName . "to " . $target .
                    " failed.\033[0m".PHP_EOL);
        }
        else{
            echo "\033[32m Download of " . $patchName . " to " . $target .
                    " complete.\033[0m".PHP_EOL;
        }
            
}
?>
