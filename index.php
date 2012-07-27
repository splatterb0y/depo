#!/usr/bin/php
<?php
/**
 * @author Christian Ziegenrücker
 * @author Sebastian Langer
 * @copyright http://softlevel.de
 *  
 * @version 0.1
 *
 * DePo - Drupal Deploy based on Repo
 * 
 * @todo implementieren patches 
 * @todo implementieren version nachtragen für module
 * @todo drush befehle ausführen
 * @todo dateidownload
 * @todo Repo Refs fixen, damit pushen auch möglich ist
 */

/**
 *  
 * $object->@attributes->name;
 * 
 * repo init -u ssh://git@softlevelweb.de/opt/git-repo/manifest
 * 
 * repo sync
 * 
 * ./repo/manifest.xml
 * 
 */

if (!isset($argv[1])) {
    echo "\033[31m No parameters given.\033[0m".PHP_EOL;
} 
else {
    switch ($argv[1]) {
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

    if (empty($argv[2])) {
        echo "\033[31m Please provide a manifest file.\033[0m".PHP_EOL;
    } 
    else {

        passthru('repo init -u '.$argv[2], &$return);

        if ($return == 0) {
            echo "\033[32mRepo finished successfully.\033[0m".PHP_EOL;
        } 
        else {
            echo "\033[31mRepo not finished successfully.\033[0m".PHP_EOL;
        }
        
    }
}

function _sync() {
    
    
    try {
                $manifest = new SimpleXmlElement('file://'.getcwd().'/.repo/manifest.xml', NULL, TRUE);  
                                
                //print_r($manifest);  
                                  
                //print_r($manifest->project[43]->attributes()->name);
                
                $i = 0;
                foreach ($manifest as $project) {
                                        
//                    echo "\n\n" . 'Datensatz: ' . $i . "\n";
                    $modulname = (string) $project['name'];  
//                    echo "\n";
//                    $modulpfad = (string) $project['path'];
//                    echo "\n";
//                    print_r((string) $project['revision']); 
                                     
                    if ($project->patch){
                        patcher($project);
                    }
//                    else{
//                        echo 'err';
//                    }
                        
                    
                    // hat das nen Patch cild,
                    // 
                    // wenn ( hat patch ) dann
                    //    patcher();
                    //    
                    // hat das nen download child
                    
                    /*
                    
                    
                   
                    echo "\n";                                        
                    $patchpfad = (string) $manifest->project->patch['path'];
                    
                    
                    $patchpfad = NULL;
                    patcher($patchpfad,$modulpfad,$modulname);*/
         
                    $i++;
                    
                }
                
                

                unset($manifest);
            }
            catch (Exception $ex) {
                echo "\033[31mSomething went wrong while reading the manifest.\033[0m".PHP_EOL;
                echo $ex;
            }
    
    
    // Repo sync aufrufen, wenn erfolgreich alle oben angegebene Todos ausführen
    // Im besten Falle in sinnvolle Funktionen unterteilen. :D
}

/*
* 
 * try {
                $manifest = new SimpleXmlElement('file://'.getcwd().'/.repo/manifest.xml', NULL, TRUE);  

                print_r($manifest);

                unset($manifest);
            }
            catch (Exception $ex) {
                echo "\033[31mSomething went wrong while reading the manifest.\033[0m".PHP_EOL;

            }
*/

/* Wie Patch funktionieren soll: 
 * 
   <project name="drupal" path="buchhandelsweb" revision="refs/tags/7.14">
        <patch path="http://google.de/ichbineinpatch.diff" />
    </project>
 * 
 * 1. Patch herunterladen (wget oder curl) in einen Pfad .depo
 *    * .depo/<projectname>/<patchname> - Cache anlegen
 * 2. Git-Patch Dreisatz
 *    * git apply --stat $patchpfad
 *    * git apply --check $patchpfad
 *    * git am < cat $patchpfad
 */


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