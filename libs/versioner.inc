<?php
function versioner ( $project ) {
    
    // declare variables 
    $projectPath = (string)  $project['path'];                                       
    $projectRev =  array_pop(explode('/', $project['revision']));                       
    $infoName = array_pop(explode( '/', $project['name']));                            
    $patchDestination = (string)  $projectPath . '/' . $project['name'];  
    $patchName = basename($patchDestination); 
    
    // process all *.info in a modules directory
    foreach(rGlob( getcwd(). '/' . $projectPath . "/*.info") as $files) {
         exec('echo "" >> '. $files);
         exec('echo "; Added by depo" >> '. $files);
         exec('echo "version = '.$projectRev.'" >> '. $files, $output, $returnEcho1);
         exec('echo "project = '.$infoName.'" >> ' .$files, $output, $returnEcho2);
    }
    
    // write revision information into modulename.info 
    if ( $returnEcho1 != 0 || $returnEcho2 != 0) {
        die( "\033[31m    Writing of .info for " . $patchName . " failed.\033[0m".PHP_EOL);
    }
    
    // commit changes to make them revertable by repo
    exec('cd ' . getcwd() . '/' . $projectPath . ' && git commit -a -m "Version: '
                . $projectRev .'"', $output, $returnGit);
    
    // output message to indicate success or failure    
    if ( $returnGit != 0 ) {
        die( "\033[31m    Writing version " . $projectRev . ' to ' . $patchName 
               . " failed.\033[0m".PHP_EOL); 
    }
    else {
        echo "\033[32m    Writing version ".$projectRev. ' to ' . $patchName 
               . " complete.\033[0m".PHP_EOL;
    }
    
}

/**
 * @url http://bit.ly/NEvV5r
 */
function rGlob($pattern = '*', $flags = 0, $path = false)
{
    if (!$path)
    {
        $path = dirname($pattern).DIRECTORY_SEPARATOR;
    }
 
    $pattern    = basename($pattern);
    $paths      = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files      = glob($path.$pattern, $flags);
    foreach ($paths as $path)
    {
        $files = array_merge($files, rGlob($pattern, $flags, $path));
    }
    return $files;

    
}
?>
