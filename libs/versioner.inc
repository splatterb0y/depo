<?php
function versioner ($project) {
    
    //path of the project
    $projectPath = (string)  $project['path'];                                       
    $projectRev =  array_pop(explode('/', $project['revision']));                       
    $infoName = array_pop(explode( '/', $project['name']));                            
    $patchDestination = (string)  $projectPath . '/' . $project['name'];  
    $patchName = basename($patchDestination);                                        

    switch ( $infoName ) {
        case 'jquerycdn':
            $infoName = 'jquery_cdn';
            break;
        
    }
    //write revisionsinformation into modulename.info
    exec('echo "version = '.$projectRev.'" >> ' . getcwd(). '/' . $projectPath . '/' .
            $infoName . '.info', &$output, &$returnEcho);
    
    if ( $returnEcho != 0 ) {
        die( "\033[31m Writing of .info for " . $patchName . " failed.\033[0m".PHP_EOL);
    }
    // commit with git
    exec('cd ' . getcwd() . '/' . $projectPath . ' && git commit -a -m "Version: '
                . $projectRev .'"', &$output, &$returnGit);
    
    if ( $returnGit != 0 ) {
        print_r($project);
        die( "\033[31m Commit of version " . $projectRev . ' to ' . $patchName 
               . " failed.\033[0m".PHP_EOL); 
    }
    else {
       echo "\033[32m Commit of version ".$projectRev. ' to ' . $patchName 
               . " complete.\033[0m".PHP_EOL;
    }
    
    }
?>
