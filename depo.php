#!/usr/bin/php
<?php
/**
 * @author Christian Ziegenrücker
 * @author Sebastian Langer
 *
 * @version 0.5
 *
 * DePo - Drupal Deploy based on Repo
 *
 * @todo drush befehle ausführen
 * @todo Repo Refs fixen, damit pushen auch möglich ist(?)
 * @todo Leere Zeile nach 'drupal' fixen(?)
 *
 */

if ( !isset($argv[1]) ) {
    echo "\033[31m No parameters given.\033[0m".PHP_EOL;
}
else {
    echo "\033[1;37m Repo started...\033[0m";
    switch ( $argv[1] ) {
        case 'init':
                _init();
            break;
        case 'sync':
                _sync();
            break;
        case 'changelog':
                _changelog();
            break;
    }
}

function _init() {

    global $argv;

    if ( empty($argv[2]) ) {
        echo "\033[31m Please provide a manifest file.\033[0m".PHP_EOL;
    }
    else {
	$return = NULL;
        passthru('repo init -u '.$argv[2], $return);

        if ($return == 0) {
            echo "\033[32m and finished successfully.\033[0m".PHP_EOL;
        }
        else {
            echo "\033[31m and a problem occured.\033[0m".PHP_EOL;
        }

    }
}

function _sync() {

    global $argv;

    $argumentString = ' ';

    if(count($argv) > 2) {
       echo "\033[0;33m(only specified repositories will be updated.)\033[0m";

       $count = 0;
       $arguments = array();
       foreach($argv as $argument) {
           if($count > 1) {
               $argumentString .= $argument." ";
               array_push($arguments, $argument);
           }
           $count++;
       }

    }

    passthru('repo sync' . $argumentString, $return);

    if ($return == 0) {

        echo "\033[32m and finished successful.\033[0m".PHP_EOL;

        try {
            $manifest = new SimpleXmlElement('file://' . getcwd() . '/.repo/manifest.xml', NULL, TRUE);

            foreach ( $manifest->project as $project ) {
                if (!empty($arguments) && !in_array($project['name'], $arguments)) {
                        continue;
                }

                echo ':: '.$project['name'].' ('.basename((string) $project['revision']).')'.PHP_EOL;

                // Because d.o is the default remote location
                if (empty($project['remote'])) {
                    require_once(dirname(__FILE__) . '/libs/versionChecker.inc');
                    versionChecker($project);
                }

                if ( $project->patch ) {
                    require_once(dirname(__FILE__) . '/libs/patcher.inc');
                    patcher($project);
                }

                if ( $project->download ){
                    require_once(dirname(__FILE__) . '/libs/downloader.inc');
                    downloader($project);

                }

                if ( isset($project['git-version']) && $project['git-version'] == true ) {
                    require_once(dirname(__FILE__) . '/libs/versioner.inc');
                    versioner($project);
                }

            }

            unset( $manifest );
        }
        catch ( Exception $ex ) {
            echo "\033[31m something went wrong while reading the manifest.\033[0m".PHP_EOL;
            echo $ex;
        }
    }
    else {
        echo "\033[31m there was a error while running repo.\033[0m".PHP_EOL;
    }
}

function _changelog() {
    $dataset = [];
    exec('git --no-pager log --pretty=format:"%cd %an | %s" --date=short', $output);
    foreach($output as $line) {
        $time = substr($line, 0, 10);
        $line = substr($line, 10);       
        $autor = substr($line, 0, strpos($line, '|'));
        $message = substr($line, strpos($line, '|')+1, strlen($line));
        unset($tmp, $line);

        if(!isset($dataset[$time])) {
            $dataset[$time] = [];
        }

        $dataset[$time][] = array(
            'autor' => trim($autor),
            'message' => trim($message)
        );
    }

    $handle = fopen("test.txt", 'w+');
    foreach(array_keys($dataset) as $time) {
        fwrite($handle, $time. "\n");
        foreach($dataset[$time] as $row) {
            fwrite($handle, "\t" . $row['autor'] . " : " . $row['message'] . "\n");
        }
    }
    fclose($handle);

}

?>
