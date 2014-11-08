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

	if( isset($_POST['building']) && isset($_POST['campus']) && isset($_POST['uid']) ) {
		$uid	= $_POST['uid'];
		$campus = $_POST['campus'];
		$building = $_POST['building'];
	        $append_attributes = $sdb->put_attributes($domain, $uid, array(
 	       		'campus'   => $campus,
			'building' => $building
	      	), true);
			
       	        // Were the new attributes appended successfully?
               	if ($append_attributes->isOK()) {
			echo json_encode(array('building' => $building, 'campus' => $campus));
//			$timeElapsed = microtime(true) - $startTime;
//			echo $timeElapsed;
      	 	} else {
			echo "Write Failed!";
		}	
	} else {
		echo "Improper Inputs!";
	}
?>
