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

	include('PHP_GnuPlot.php');
	
	if( isset($_GET['uid']) && isset($_GET['date_utc']) && isset ($_GET['date']) && isset($_GET['hour'])
                                && isset($_GET['minute']) && isset($_GET['graph']) ) {
                $uid = $_GET['uid'];
                $date_utc = $_GET['date_utc'];
                $date = $_GET['date'];
//      //Energy History
                $time_end_hr = ($_GET['hour'] < 10) ? '0'.$_GET['hour'] : $_GET['hour'];        //append leading zero to the hour
                $time_end_min = $_GET['minute'];
                $time_end = $time_end_hr.':'.$time_end_min;
                $time_begin_hr = ($time_end_hr < 12) ? '0'.($time_end_hr - 2) : $time_end_hr - 2;
                $time_begin_min = $time_end_min;
                $time_begin = $time_begin_hr.':'.$time_begin_min;
/*      //Energy Forecast               
                $time_begin_hr = ($_GET['hour'] < 10) ? '0'.$_GET['hour'] : $_GET['hour'];      //append leading zero to the hour
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



		//Query server for kWh history measurements based on $building and $date; format into array
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
                $kwh_array = $results['kwhReading'];            //holds the 8 kWh values for $building from database
                $time_array = $results['time'];                 //holds the times that the measurements were taken 
		$rate_array = array(0.15, 0.16, 0.24, 0.30, 0.33, 0.31, 0.29, 0.13);

		//return JSON object of arrays
		echo json_encode(array('kwh_array' => $kwh_array, 'time_array' => $time_array, 'rate_array' => $rate_array));
	} else {
		echo "Error: no id specified!\n";
	}
?>
