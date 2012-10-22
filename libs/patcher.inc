<?php
function patcher ( $project ) {
   
    // execute for every patch child
    foreach ( $project->patch as $patch ) {
        
        // declare variables 
        $patchPath = (string) $patch['path'];
        $patchTarget = (string) '/' . $project['path'];
        $patchName = basename($patchPath);
        
        // make directory for downloads 
        @mkdir( getcwd() . '/.depo', 0777, TRUE );
        
        // download patch
        exec( 'wget -q ' . $patchPath . ' -O' . getcwd() . '/.depo/' . $patchName, $output, $returnWget );
        
        // if download failes exit
        if ( $returnWget != 0 ) {
            die( "\033[31m    Patch download failed.\033[0m".PHP_EOL );
        }
        
        // apply patch with git am
        exec( 'cd ' . getcwd() . $patchTarget . ' && 
            git am -q < ' . getcwd() .  '/.depo/' . $patchName, $output, $returnGit );
        
        // output message to indicate success or failure
        if ( $returnGit != 0 ) {
            die( "\033[31m     Applying ". $patchName . ' to ' . $project['name'] 
               . " failed.\033[0m".PHP_EOL );
        } 
        else {
             echo "\033[32m    Applying ". $patchName . ' to ' . $project['name'] 
               . " successful.\033[0m".PHP_EOL;
        }
    }

}
?>
 
