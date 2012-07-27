<?php
function patcher ($project){
    
   // echo ''.getcwd();
       
       $patchname = basename($patchpfad);
       $patchpfad = $project->patch['path'];
       
       
       //echo $check;
    
    shell_exec( 'mkdir ' . getcwd() . '/cache/' . $patchname );
    shell_exec( 'wget ' . $patchpfad . ' -O' . getcwd() . '/' . $patchname );

        
    //passthru('git apply --stat ' . $modulname, &$return);
    
    exec('cd ' . getcwd() . 'git apply --check ' . $modulname, &$return);
    
    if ($return == 0){
        shell_exec( 'git apply --stat ' . $modulname, &$return );
        shell_exec( 'git am < ' . $patchpfad, &$return);   
        
        }
    else {
        echo "\033[31m Something went very very wrong.\033[0m".PHP_EOL;
    }
    
    //passthru('git am < cat ' . $patchpfad, &$return);
    
    
    
    return;
}
?>
