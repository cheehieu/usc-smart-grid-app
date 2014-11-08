<?php

$url = 'http://128.125.224.121:8000/sparql/';
$query = urlencode("PREFIX sgns: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
SELECT ?kwhReading  ?time WHERE {
 	?building  <http://dbpedia.org/ontology/hasBuildingCode> 'TCC'  .
	?building  sgns:KiloWattLoadObservable ?kwhURI .
 	?kwhURI  rdf:type sgns:ElectricalMeasurement .
 	?kwhURI  sgns:hasMeasurementRecordedDate  ?date .
 	?kwhURI  sgns:hasMeasurementRecordedTime  ?time .
  	FILTER ( ?date >= '04/16/2012' && ?date <= '04/16/2012') 
	FILTER ( ?time >= '04:00' && ?time < '06:00')
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
var_dump($json);
$results = array();
foreach($json['head']['vars'] as $value){
	$results[$value] = array();
}

foreach($json['results']['bindings'] as $value){
	foreach($results as $col=>$useless){
		$results[$col][] = $value[$col]['value'];
	}
}

foreach($results as $col=>$values){
	foreach($values as $key=>$value){
		echo "results[".$col."][".$key."] = ".$value."\n";
	}
}

echo $results['kwhReading'][5]."\n";

$time_begin_hr = '7';
$time_end_hr = ($time_begin_hr < 8) ? '0'.($time_begin_hr + 2) : $time_begin_hr + 2;
echo $time_end_hr;
?>
