<?php
function patcher ( $project ) {
   
    // Execute for every patch child
    foreach ( $project->patch as $patch ) {
        
        $patchPath = (string) $patch['path'];
        $patchTarget = (string) '/' . $project['path'];
        $patchName = basename($patchPath);
        
        // Make directory for download.
        @mkdir( getcwd() . '/.depo', 0777, TRUE );
        
        // Load patch.
        exec( 'wget -q ' . $patchPath . ' -O' . getcwd() . '/.depo/' . $patchName, &$output, &$returnWget );
        
        if ( $returnWget != 0 ) {
            die( "\033[31m Patch download failed.\033[0m".PHP_EOL );
        }
        
        //Patch anwenden.
        passthru( 'cd ' . getcwd() . $patchTarget . ' &&
            git am -q < ' . getcwd() .  '/.depo/' . $patchName, &$returnGit );
        
        // On error exit and show notification.
        if ( $returnGit != 0 ) {
            die( "\033[31m Applying ". $patchName . ' to ' . $project['name'] 
               . " failed.\033[0m".PHP_EOL );
        } else {
             echo "\033[32m Applying ". $patchName . ' to ' . $project['name'] 
               . " successful.\033[0m".PHP_EOL;
        }
    }
       
    return;
}
?>
 
