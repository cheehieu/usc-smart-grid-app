<?php
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) &&
                (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                header('Content-type: application/json');
        } else {
                header('Content-type: text/plain');
        }

	//Enable full-blown error reporting
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);

        //Amazon SDK Setup
        require_once 'AWSSDKforPHP/sdk.class.php';
        $sdb = new AmazonSDB();
        $domain = 'mobile_users';
	
	if( isset($_GET['uid']) ) {
		$uid = $_GET['uid'];
		//Get building location and comfort from SDB
		$building = $sdb->getAttributes($domain, $uid, 'building')->body->GetAttributesResult->Attribute->Value;
		$comfort = $sdb->getAttributes($domain, $uid, 'comfort')->body->GetAttributesResult->Attribute->Value;
		echo json_encode(array('building' => $building, 'comfort' => $comfort));
	} else {
		echo "Error: no id specified!\n";
	}
?>
