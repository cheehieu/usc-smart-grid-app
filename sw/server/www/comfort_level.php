<?php
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

	if( isset($_POST['comfort']) && isset($_POST['uid']) ) {
		$uid	= $_POST['uid'];
		$comfort = $_POST['comfort'];
	        $append_attributes = $sdb->put_attributes($domain, $uid, array(
 	       		'comfort'   => $comfort,
	      	), true);
			
       	        // Were the new attributes appended successfully?
               	if ($append_attributes->isOK()) {
			
			if ($comfort < 5) {
				$temp = 'cold';
			} else if ($comfort > 5) {
				$temp = 'hot';
			} else {
				$temp = 'neutral';
			}
			echo json_encode(array('comfort' => $comfort, 'temp' => $temp));
      	 	} else {
			echo "Write Failed!";
		}	
	} else {
		echo "Improper Inputs!";
	}
?>
