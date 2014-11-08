<?php
// SETUP

	// Enable full-blown error reporting. http://twitter.com/rasmus/status/7448448829
	error_reporting(-1);

	// Include the SDK
	require_once 'AWSSDKforPHP/sdk.class.php';

	// Instantiate the AmazonSDB class
	$sdb = new AmazonSDB();

	// Store the name of the domain
	$domain = 'landmarks';

	// Create the domain
	$new_domain = $sdb->create_domain($domain);

	// Was the domain created successfully?
	if ($new_domain->isOK())
	{
		// Add a batch of item-key-values to your domain
		//E->W decreases longitude (more negative)
		//N->S decreases latitude
		$add_attributes = $sdb->batch_put_attributes($domain, array(
			'USC_campus' => array(
				'lats'       => array('34.025361', '34.021928', '34.018378', '34.018318'),
				'longs'      => array('-118.291329', '-118.280214', '-118.282324', '-118.291501')
			),
			'EEB' => array(
				'lats'       => array('34.019898', '34.019685', '34.019485', '34.019714'),
				'longs'      => array('-118.290271', '-118.289716', '-118.289834', '-118.290373')
			),
			'RTH' => array(
				'lats'       => array('34.020307', '34.020058', '34.019801', '34.020038'),
				'longs'      => array('-118.290003', '-118.289453', '-118.289614', '-118.290161')
			),
			'SAL' => array(
				'lats'       => array('34.019732', '34.019536', '34.019218', '34.019434'),
				'longs'      => array('-118.289606', '-118.289139', '-118.289305', '-118.289788')
			),
			'OHE' => array(
				'lats'       => array('34.021086', '34.020897', '34.020261', '34.020448'),
				'longs'      => array('-118.28952', '-118.289059', '-118.289453', '-118.289901')
			),
			'VHE' => array(
				'lats'       => array('34.02051', '34.020365', '34.019709', '34.019841'),
				'longs'      => array('-118.288187', '-118.287846', '-118.288278', '-118.288605')
			),
			'Hampshire' => array(
				'lats'       => array('34.06476', '34.064778', '34.064151', '34.064151'),
				'longs'      => array('-118.2941', '-118.292952', '-118.292947', '-118.294095')
			),
		), true);

		// Were the attributes added successfully?
		if ($add_attributes->isOK())
		{
		 	// Use a SELECT expression to query the data.
			$results = $sdb->select("SELECT * FROM `{$domain}` WHERE 'lats' > '34.02'");

			// Get all of the <Item> nodes in the response
			$items = $results->body->Item();

			$response = $sdb->select("SELECT * FROM `{$domain}`");
			print_r($response);
		}
	}
?>
