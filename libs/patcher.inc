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
        exec( 'wget -q ' . $patchPath . ' -O' . getcwd() . '/.depo/' . $patchName );
        
        //Patch anwenden.
        passthru( 'cd ' . getcwd() . $patchTarget . ' &&
            git am < ' . getcwd() .  '/.depo/' . $patchName, &$return );
        
        // On error exit and show notification.
        if ( $return != 0 ) {
            die( "\033[31m Something went very very wrong.\033[0m".PHP_EOL );
        }
    }
       
    return;
}
?>
 
