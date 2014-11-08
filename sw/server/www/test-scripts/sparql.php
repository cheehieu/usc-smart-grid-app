<?php
$url = 'http://128.125.224.121:8000/sparql/';
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

$url .= '?query='.$query;//&output=json for json format
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
