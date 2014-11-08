<?php
$buildingCode = "BC15";
$KWhSensorID = "D282ENERGY";
$startDate = "11:Jan:2011";
$endDate   = "17:Sep:2012";

/*
$query_kwh = urlencode("PREFIX Sensor: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                	//"PREFIX Measurement: <http://www.cei.usc.edu/SmartGrid.owl#> "+
                	"PREFIX Place: <http://dbpedia.org/ontology/Place> ".
                        "PREFIX Building: <http://dbpedia.org/ontology/> ".
                        "PREFIX ObservableThing: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                        "PREFIX ObservableFeature: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                        "PREFIX ObserveredValue: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                //"SELECT ?building ?sensor ?feature ?observation ?measDate ?measValue " +
                "SELECT ?measValue  " .
                "WHERE { " .
                	"               ?building Building:hasBuildingCode \"" . $buildingCode . "\" . " .
                        "               ?building ObservableThing:hasAObservableFeature ?feature . " .
                        //"               ?feature ObservableFeature:class "KiloWattLoadObservable" .     " .  //??? How to find if a var belongs to a certain class
                        "               ?sensor Sensor:measures ?feature . " .
                        "               ?sensor Sensor:hasSensorID \"" . $KWhSensorID + "\" . " .
                        "               ?sensor Sensor:hasMeasurementObservation ?observation . " .
                        "               ?observation ObserveredValue:hasMeasuredValue ?measValue . " .
                        "               ?observation ObserveredValue:hasMeasuredTimeStamp ?measDate . " .               //Not needed in this query
                        "               FILTER ( ?measDate >= \"". $startDate ."\" && ?measDate <= \"". $endDate ."\") " .
                        "               }");
*/

$url = 'http://128.125.224.121:8000/sparql/';
$query_kwh = urlencode("PREFIX Sensor: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                	"PREFIX Measurement: <http://www.cei.usc.edu/SmartGrid.owl#> ".
                	"PREFIX Place: <http://dbpedia.org/ontology/Place> ".
                        "PREFIX Building: <http://dbpedia.org/ontology/> ".
                        "PREFIX ObservableThing: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                        "PREFIX ObservableFeature: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
                        "PREFIX ObserveredValue: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> ".
			"PREFIX rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> ".
                //"SELECT ?building ?sensor ?feature ?observation ?measDate ?measValue " +
                "SELECT ?BuildingCode " .
                "WHERE { " .
			"		?contributor rdf:type ?BuildingEntityClass . ".
			"		?contributor Building:hasBuildingNo ?BuildingNum .".
			"		?contributor Building:hasBuildingCode ?Buildingcode . ".
                	"               ?building Building:hasBuildingCode ?BuildingCode . " .
                        "               ?building ObservableThing:hasAObservableFeature ?feature . " .
                        //"               ?feature ObservableFeature:class "KiloWattLoadObservable" .     " .  //??? How to find if a var belongs to a certain class
                        "               ?sensor Sensor:measures ?feature . " .
//                        "               ?sensor Sensor:hasSensorID . " .
                        "               ?sensor Sensor:hasMeasurementObservation ?observation . " .
                        "               ?observation ObserveredValue:hasMeasuredValue ?measValue . " .
                        "               ?observation ObserveredValue:hasMeasuredTimeStamp ?measDate . " .               //Not needed in this query
//                        "               FILTER ( ?measDate >= \"". $startDate ."\" && ?measDate <= \"". $endDate ."\") " .
                        "               }");

//	RETURNS ALL BUILDING CODES
$query_kwh =  urlencode("PREFIX Building: <http://dbpedia.org/ontology/> 
	PREFIX rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> 
	PREFIX Sensor: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
        PREFIX Measurement: <http://www.cei.usc.edu/SmartGrid.owl#>
        PREFIX Place: <http://dbpedia.org/ontology/Place>
        PREFIX ObservableThing: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
        PREFIX ObservableFeature: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
        PREFIX ObserveredValue: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>".

"SELECT ?BuildingCode
WHERE { 
?contributor rdf:type ?BuildingEntityClass .
?contributor Building:hasBuildingNo ?BuildingNum .
?contributor Building:hasBuildingCode ?BuildingCode}");//\" . ".$buildingCode." . \"}"); //?BuildingCode . 
//*/
/*	ATTEMPT MEASURE VALUE
$query_kwh =  urlencode("PREFIX Building: <http://dbpedia.org/ontology/> 
	PREFIX rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> 
	PREFIX ObservableThing: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
	PREFIX ObservableFeature: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
	PREFIX ObserveredValue: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>
	PREFIX Sensor: <http://www.smartgrid.usc.edu/Ontology/2012.owl#>

SELECT ?BuildingCode ?measValue
WHERE { 
?contributor rdf:type ?BuildingEntityClass .
?contributor Building:hasBuildingNo ?BuildingNum .
?observation ObserveredValue:hasMeasuredValue ?measValue .
?contributor Building:hasBuildingCode ?BuildingCode}");//\" . ".$buildingCode." . \"}"); //?BuildingCode . 
*///}");

//	ORIGINAL PHP
$query =  urlencode("PREFIX Building: <http://dbpedia.org/ontology/> 
PREFIX rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> 
SELECT * 
WHERE { 
?contributor rdf:type ?BuildingEntityClass . 
?contributor Building:hasBuildingNo ?BuildingNum . 
?contributor Building:hasBuildingCode ?BuildingCode . 
?contributor Building:address ?Address . 
?contributor Building:elevation ?Elevation . 
?contributor Building:buildingstartdate ?YearBuilt . 
?contributor Building:floorArea ?GrossArea .
?contributor Building:Architect ?architect .
?architect rdf:type ?ArchitectEntityClass . 
?architect Building:birthname ?ArchitectName . 
}");

header('Content-Type: text/xml');

$url .= '?query='.$query_kwh;//."&output=json";// for json format
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);
echo $html;
?>
