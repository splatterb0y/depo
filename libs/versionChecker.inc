<?php
function versionChecker ( $project, $apiVersion ) {
    		
	        $project = $project['name'];
			//snippet
		$feedURL = "http://updates.drupal.org/release-history/$project/$apiVersion";
                
                
                $curl = curl_init($feedURL);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $content = curl_exec($curl);
                $feed = new SimpleXmlElement($content);
		
               
		foreach($feed->releases->release as $project) {                    
                    if($project->status != 'published'){
                            break;
                    }
                    else {
                            return $project->version;
                    }
		}
}
    	
		

?>