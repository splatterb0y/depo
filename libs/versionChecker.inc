<?php
function versionChecker ( $project, $apiVersion = '7.x') {
    		
    $projectVersion = basename((string) $project['revision']);
    
    $webserviceURL = 'http://updates.drupal.org/release-history/'.$project['name'].'/'.$apiVersion;

    $curl = curl_init($webserviceURL);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $content = curl_exec($curl);
    
    try {
        $feed = new SimpleXmlElement($content);

	if(is_null($feed->releases->release)) {
		echo  "\033[0;33m" . '    ' . ' No release info available' . PHP_EOL;
		return;
	};

        foreach($feed->releases->release as $release) {                    
            if($release->status != 'published'){
                break;
            }
            else {
                if (version_compare($projectVersion, $release->version) < 0) {
                     echo "\033[0;33m" . '    ' . $project['name'] . ' is outdated! Newest available version is '
                            . $release->version . "\033[0m".PHP_EOL;
                }
                return;
            }
        }
    }
    catch(Exception $e) {
       // This is a stub, believe me
       delete($e);
    }
}
   		
?>             
