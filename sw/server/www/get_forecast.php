<?php
//	$startTime = microtime(true);

//Enable full-blown error reporting
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);

        //Amazon SDK Setup
        require_once 'AWSSDKforPHP/sdk.class.php';
        $sdb = new AmazonSDB();
        $domain = 'mobile_users';
	$s3 = new AmazonS3();
	$bucket = 'forecast-plots';

	include('PHP_GnuPlot.php');
	
	
	if( isset($_GET['uid']) && isset($_GET['date_utc']) && isset ($_GET['date']) && isset($_GET['hour']) 
				&& isset($_GET['minute']) && isset($_GET['graph']) ) {
		$uid = $_GET['uid'];
		$date_utc = $_GET['date_utc'];
		$date = $_GET['date'];
//	//Energy History
		$time_end_hr = ($_GET['hour'] < 10) ? '0'.$_GET['hour'] : $_GET['hour'];	//append leading zero to the hour
		$time_end_min = $_GET['minute'];
		$time_end = $time_end_hr.':'.$time_end_min;
		$time_begin_hr = ($time_end_hr < 12) ? '0'.($time_end_hr - 2) : $time_end_hr - 2;
		$time_begin_min = $time_end_min;     
		$time_begin = $time_begin_hr.':'.$time_begin_min;
/*	//Energy Forecast		
		$time_begin_hr = ($_GET['hour'] < 10) ? '0'.$_GET['hour'] : $_GET['hour'];	//append leading zero to the hour
		$time_begin_min = $_GET['minute'];
		$time_begin = $time_begin_hr.':'.$time_begin_min;
		$time_end_hr = ($time_begin_hr < 8) ? '0'.($time_begin_hr + 2) : $time_begin_hr + 2;
		$time_end_min = $time_begin_min;     
		$time_end = $time_end_hr.':'.$time_end_min;i
*/
		$graph = $_GET['graph'];
		if( !isset($_GET['building']) ) {
			//Get building location from SDB if $building is not set
			$building = $sdb->getAttributes($domain, $uid, 'building')->body->GetAttributesResult->Attribute->Value;
		} else {
			$building = $_GET['building'];
		}



		//Query server for kWh history measurements given $building and $date; format into array
		//currently, data is only available for buildings TCC and MHP
		$date = '04/16/2012';
		$url = 'http://128.125.224.121:8000/sparql/';
		$query = urlencode("PREFIX sgns: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
		PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
		PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
		SELECT ?kwhReading  ?time WHERE {
		        ?building  <http://dbpedia.org/ontology/hasBuildingCode> '".$building."'  .
		        ?building  sgns:KiloWattLoadObservable ?kwhURI .
		        ?kwhURI  rdf:type sgns:ElectricalMeasurement .
		        ?kwhURI  sgns:hasMeasurementRecordedDate  ?date .
		        ?kwhURI  sgns:hasMeasurementRecordedTime  ?time .
			FILTER ( ?date >= '".$date."' && ?date <= '".$date."') 
		        FILTER ( ?time >= '".$time_begin."' && ?time < '".$time_end."')
		        ?kwhURI  sgns:hasMeasuredValue  ?kwhReading   .
		      }");
		
		$url .= '?query='.$query.'&output=json';// for json format
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
		curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($html, 1);
		$results = array();
		foreach($json['head']['vars'] as $value){
			$results[$value] = array();
		}
		foreach($json['results']['bindings'] as $value){
		        foreach($results as $col=>$useless){
		                $results[$col][] = $value[$col]['value'];
		        }
		}
		$kwh_array = $results['kwhReading'];		//holds the 8 kWh values for $building from database
		$time_array = $results['time'];			//holds the times that the measurements were taken 
		$rate_array = array(0.15, 0.16, 0.24, 0.30, 0.33, 0.31, 0.29, 0.13);      //cost per kWh retrieved from a dynamic utility company, store in 3rd column
//		var_dump($json);		


		//Use GnuPlot to plot data to .PNG image
		$time_begin = $time_begin_hr.':'.($time_begin_min-'7.5');	//shift by half time interval to display bar plot properly
		$time_end_hr = $time_begin_hr + '2';				//2-hour gap allows 15-minute xtics
		$time_end_min = $time_begin_min;       
		$time_end = $time_end_hr.':'.($time_end_min-'7.5');
	
		$p = new GNUPlot();
		$p->setTitle("Energy Forecast for ".$building." (requested at ".$time_end_hr.":".$time_end_min." on ".$date.")");
		$p->setDimLabel("x", "Time (HH:MM)");
		$p->formatPlot();
		$data = new PGData($building);
	
		if ($graph == "energy") {
		        $filename = $date_utc.'_'.$building.'_plot_energy.png';
		        foreach ($kwh_array as $key => $value) {
		                $data->addDataEntry( array($time_array[$key], $value) );
		        }
		        $p->setDimLabel("y", "Energy (kWh)");
		        $p->setRange('x', $time_begin, $time_end);
		        $p->setRange('y', 0, 175);
		        $p->plotData( $data, 'boxes', '1:2' );
		        $p->export($filename);
		        $p->close();
		} elseif ($graph == "cost") {
		        $filename = $date_utc.'_'.$building.'_plot_cost.png';
		        foreach ($kwh_array as $key => $value) {
		                $data->addDataEntry( array($time_array[$key], $value * $rate_array[$key]) );
				// $p->set2DLabel("$".$rate_array[$key]."/kWh", $time_begin_hr." ".$time_begin_min, $value * $rate_array[$key]);       //display costs
/*		                if ($time_begin_min == '45') {
		                        $time_begin_hr = ( ($time_begin_hr == '23') ? '00' : $time_begin_hr + '01' );
		                        $time_begin_min = '00';
		                } else {
		                        $time_begin_min = $time_begin_min + '15';
		                }
*/
		        }
		        $p->setDimLabel("y", "Cost (\$USD)");
		        $p->set("autoscale");
		        $p->setRange('x', $time_begin, $time_end);
		        $p->plotData( $data, 'boxes', '1:2' );
		        $p->export($filename);
		        $p->close();
		} else {
		        //echo "Invalid graph type!\n";
		}	
	

		//Upload image to Amazon S3, return URL for display in mobile app
		$filepath = $filename;
		$response = $s3->create_object($bucket, $filename, array('fileUpload' => $filepath));
		if ($response->isOK()) {
			echo $s3->get_object_url($bucket, $filename, '5 minutes') . PHP_EOL . PHP_EOL;
//			$timeElapsed = microtime(true) - $startTime;
//	                echo $timeElapsed;

			unlink($filename);	//remove plot from server; it's in the cloud now!
		} else {
			echo "Error uploading plot to S3!\n";
		}

	} else {
		echo "Error: no id specified!\n";
	}
?>
