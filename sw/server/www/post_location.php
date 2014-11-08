<?php
//	$startTime = microtime(true);
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) &&
                (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                header('Content-type: application/json');
        } else {
                header('Content-type: text/plain');
        }

        // Enable full-blown error reporting
	ini_set('display_errors', 'On');
        error_reporting(E_ALL);

        // Include the SDK
        require_once 'AWSSDKforPHP/sdk.class.php';
        $sdb = new AmazonSDB();
        
	$domain = 'mobile_users';
	$landmark_domain = 'landmarks';

	if(isset($_POST['uid']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['timestamp'])) {
		$uid = $_POST['uid'];
		$lat = $_POST['latitude'];
		$long = $_POST['longitude'];
		$time = $_POST['timestamp'];

		// Add a batch of item-key-values to domain
        	$add_attributes = $sdb->batch_put_attributes($domain, array($uid => array(
			'latitude' => $lat, 
			'longitude' => $long, 
			'timestamp' => $time
		)), true);

	        // Were the attributes added successfully?
        	if ($add_attributes->isOK())
        	{
			$pointLocation = new pointLocation();
			$point = $lat." ".$long;
			// Define polygon vertices for landmarks (last point matches first point to close the polygon)
			$USC_campus = array('34.025361 -118.291329', '34.021928 -118.280214', '34.018378 -118.282324', '34.018318 -118.291501', '34.025445 -118.287842', '34.025361 -118.291329');
			$EEB = array('34.019898 -118.290271', '34.019685 -118.289716', '34.019485 -118.289834', '34.019714 -118.290373', '34.019898 -118.290271');
			$MHP = array('34.018901 -118.286828', '34.01875 -118.286394', '34.018354 -118.286694', '34.018407 -118.287177', '34.018901 -118.286828');
			$OHE = array('34.021086 -118.28952', '34.020897 -118.289059', '34.020261 -118.289453', '34.020448 -118.289901', '34.021086 -118.28952');
			$RTH = array('34.020307 -118.290003', '34.020058 -118.289453', '34.019801 -118.289614', '34.020038 -118.290161' ,'34.020307 -118.290003');
			$SAL = array('34.019732 -118.289606', '34.019536 -118.289139', '34.019218 -118.28930', '34.019434 -118.289788', '34.019732 -118.289606');
			$TCC = array('34.020768 -118.286238', '34.020457 -118.285444', '34.019634 -118.285927', '34.020021 -118.286726', '34.020768 -118.286238');
			$VHE = array('34.02051 -118.288187', '34.020365 -118.287846', '34.019709 -118.288278', '34.019841 -118.288605', '34.02051 -118.288187');
			$home = array('34.064778 -118.2941', '34.0648 -118.293558', '34.065351 -118.293558', '34.065324 -118.29292', '34.064138 -118.29293', '34.064142 -118.294105', '34.064778 -118.2941');
						
			// Check if user is on campus, and what building
			if ($pointLocation->pointInPolygon($point, $USC_campus) == 'inside') {		
				$campus = 'on';
				if ($pointLocation->pointInPolygon($point, $EEB) == 'inside') {		
					$building = 'EEB';
				} else if ($pointLocation->pointInPolygon($point, $MHP) == 'inside') {		
					$building = 'MHP';
				} else if ($pointLocation->pointInPolygon($point, $OHE) == 'inside') {		
					$building = 'OHE';
				} else if ($pointLocation->pointInPolygon($point, $RTH) == 'inside') {		
					$building = 'RTH';
				} else if ($pointLocation->pointInPolygon($point, $SAL) == 'inside') {		
					$building = 'SAL';
				} else if ($pointLocation->pointInPolygon($point, $TCC) == 'inside') {		
					$building = 'TCC';
				} else if ($pointLocation->pointInPolygon($point, $VHE) == 'inside') {		
					$building = 'VHE';
				} else {
					$building = 'unknown';
				}
			} else {
				$campus = 'off';
				$building = 'unknown';
				if ($pointLocation->pointInPolygon($point, $home) == 'inside') {		
					$building = 'Hampshire';
				}
			}
		        
			$append_attributes = $sdb->put_attributes($domain, $uid, array(
        			'campus'   => $campus,
				'building' => $building
	                ), true);

        	        // Were the new attributes appended successfully?
                	if ($append_attributes->isOK())
	                {
        			echo json_encode(array('building' => $building, 'campus' => $campus));
//				$timeElapsed = microtime(true) - $startTime;
//		                echo $timeElapsed;

               	 	}
        	} else {
			echo "Write Failed!";
		}	
	} else {
		echo "Improper Inputs!";
	}


class pointLocation {
    var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices

    function pointLocation() {
    }
    
        function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
        
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array(); 
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
        
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            //return "vertex";
            return "inside";
        }
        
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
    
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                //return "boundary";
                return "inside";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    //return "boundary";
                    return "inside";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
        // If the number of edges we passed through is even, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }
    
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }
    
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }
}
?>
