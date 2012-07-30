<?php
function versioner ($project) {
    print_r($project);
    
    $projectPath = (string)  $project['path'];                                       //path of the project
    $projectRev =  array_pop(explode('/', $project['revision']));                       // extracts the project revision
    $infoName = array_pop(explode('/', $project['name']));                            //name of the info file
    $patchDestination = (string)  $projectPath . '/' . $project['name'];  // target of the patch
    $patchName = basename($patchDestination);                                         // name of the patch

    //write revisionsinformation into modulename.info
    passthru('echo "version = '.$projectRev.'" >> ' . getcwd(). '/' . $projectPath . '/' .
            $infoName . '.info', $return);
    passthru('git commit -a -m "Version: '. $projectRev .'"');
    
        if ( $return != 0 ) {
            die( "\033[32m Writing of .info for " . $patchName . " failed.\033[0m".PHP_EOL);
        }
        else{
            echo "\033[32m Writing of .info for " . $patchName . " complete.\033[0m".PHP_EOL;
        }
    
    }
?>
