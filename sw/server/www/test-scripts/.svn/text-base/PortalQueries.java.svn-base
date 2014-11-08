
									/* Portal Queries */

////////////////////////////////////// New Ontology //////////////////////////////////////

//////////// Query 1 ///////////////
Query 1: For given lat, long boundary, give list of building codes and their center lat, long

p2:Building   ----->   p2:Place		[ Building Class has all the attributes we are interested in, buildingNo,code etc... ]
	        subClassof
p2:Place    ----->   Location
	        hasALocation
Location    ----->  GMLSurfaceProperty U Address U GMLPointProperty
			locationISA				
				
<owl:Class rdf:about="http://dbpedia.org/ontology/Building">
    <rdfs:subClassOf rdf:resource="http://dbpedia.org/ontology/Place"/>
    <rdfs:label xml:lang="en">Building</rdfs:label>
</owl:Class>	

<owl:Class rdf:about="http://dbpedia.org/ontology/Place">
    <rdfs:label xml:lang="en">Place</rdfs:label>
</owl:Class>  

//Input top left corner and bottom right corner of the rectangle in which we are to find the buildings
String lLat = 33.1;			//Lower lat
String lLong = 102.5;		//Lower Long
String hLat = 33.1;			//Higher lat
String hLong = 102.5;		//Higher Long
String query1 = "PREFIX Building: <http://dbpedia.org/ontology/>" +
				"PREFIX Location: <http://www.smartgrid.usc.edu/Ontology/2012.owl#Location>" +
				"PREFIX GML: <http://www.smartgrid.usc.edu/Ontology/2012.owl#GMLPointProperty> " +   //???
				"SELECT ?buildCode ?lat ?long" +                                                                
				"WHERE { " +                                                
				"		?building Building:hasLocation ?location . " +
				" 		?location Location:locationISA ?gmlPoint . " +
				"		?gmlPoint GML:hasLat ?lat . " +
				"		?gmlPoint GML:hasLong ?long . " +
				"		FILTER(?lat >= \""+ lLat +"\" && ?lat <= \""+ Hlat +""\) " +
				"		FILTER(?long >= \""+ lLong +"\" && ?long <= \""+ Hlong +""\) " +
				"      }";

//////////// Query 2 ///////////////
Query 2:				
 //Q2] For a given building code, return building info as name value pairs
        
//Input:		
String buildingCode = "RGL"; 
String query2 = "PREFIX Building: <http://dbpedia.org/ontology/> "+
                "SELECT ?BuildingNum ?BuildingCode ?Address ?BuildingFloors ?built ?floorA " +                                                                
                "WHERE { " +                                                
    			"        ?contributor Building:hasBuildingNo ?BuildingNum . " +
    			"        ?contributor Building:hasBuildingCode ?BuildingCode . " +   
    			"        ?contributor Building:address ?Address . " +
				"        ?contributor Building:elevation ?BuildingFloors . " +
    			"        ?contributor Building:buildingstartdate ?built . "+
    			"        ?contributor Building:floorArea ?floorA . "+
				"		 FILTER (?BuildingCode == \""+ buildingCode +"\" )" +
    		    "      }";
				
				//Architect ???

Note: 
	1] Did not find the building entity in the ontology. Looked for it under p2:place also.

//////////// Query 3 ///////////////
Query 3: For a  given building code, time period and time granularity (15min/1day), return historical KWh time series

p2:Building   ----->   p2:Place				[ Building Class has all the attributes we are interested in, buildingNo,code etc... ]
	            subClassof
ObservableThing ----->   ElectricalSink U Weather U p2:Place
	             observableISA				 
ObservableThing ----->   ObservableFeature
	          hasAObservableFeature
Sensor        ----->  ObservableFeature
	            measures
Sensor  ----->   Unit			[ CelciusUnit,PPMUnit etc are Subclasses of Unit ]
	   hasUnits
Sensor   ----> <value>
		hasSensorID
Sensor    ---->   ObservedValue
		hasMeasurementObservation
		
//Input:
String 	buildingCode = "RGL";
String KWhSensorID = "D282ENERGY"; 
String startDate = "11:Jan:2011";
String endDate 	 = "17:Sep:2012"; 			

String query3 = "PREFIX Sensor: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "+
                //"PREFIX Measurement: <http://www.cei.usc.edu/SmartGrid.owl#> "+
                "PREFIX Place: <http://dbpedia.org/ontology/Place> "+
				"PREFIX Building: <http://dbpedia.org/ontology/> "+
				"PREFIX ObservableThing: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "
				"PREFIX ObservableFeature: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "
				"PREFIX ObserveredValue: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "
                //"SELECT ?building ?sensor ?feature ?observation ?measDate ?measValue " +
                "SELECT ?measValue  " +
                "WHERE { " +                                                  
                "		?building Building:hasBuildingCode \"" + buildingCode + "\" . " +
				"		?building ObservableThing:hasAObservableFeature ?feature . " +
				"		?feature ObservableFeature:class "KiloWattLoadObservable" .	" +  //??? How to find if a var belongs to a certain class
				"		?sensor Sensor:measures ?feature . " +
				"		?sensor Sensor:hasSensorID \"" + KWhSensorID + "\" . " +
				"		?sensor Sensor:hasMeasurementObservation ?observation . " +
				"		?observation ObserveredValue:hasMeasuredValue ?measValue . " +
				"		?observation ObserveredValue:hasMeasuredTimeStamp ?measDate . " +		//Not needed in this query
				"		FILTER ( ?measDate >= \""+ startDate +"\" && ?measDate <= \""+ endDate +"\") " +
				"		}";

Note:
	1] Need to add the filter for minute granularity. ObserveredValue hasMeasuredTimeStamp. See what all this time stamp contains.
				
				
//////////// Query 4 ///////////////
Query 4: For a  given building code, time period and time granularity (15min/1day), return forecast KWh time series (OpenPlanet)
Not relevant yet.

//////////// Query 5 ///////////////
Query 5: For a  given building code and time granularity (15min/1day), return average historical KWh for 1-3 years
    	//    Build KWh sensor codes
		//    	EEB			EEBBuildSensor
		//    	RTH			RTHBuildSensor
		//    	DMT			DMTBuildSensor
		//    	RGL			D282Energy
		
//Input:
String 	buildingCode = "RGL";
String KWhSensorID = "D282ENERGY"; 
String startDate = "11:Jan:2011";
String endDate 	 = "17:Sep:2012"; 			

String query5 = "PREFIX Sensor: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "+
                "PREFIX Place: <http://dbpedia.org/ontology/Place> "+
				"PREFIX Building: <http://dbpedia.org/ontology/> "+
				"PREFIX ObservableThing: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "
				"PREFIX ObservableFeature: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "
				"PREFIX ObserveredValue: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "
                //"SELECT ?building ?sensor ?feature ?observation ?measDate ?measValue " +
                //"SELECT ?measValue  " +
				"SELECT (AVG(xsd:double(?measValue)) AS ?KWh_AVG) " +
                "WHERE { " +                                                  
                "		?building Building:hasBuildingCode \"" + buildingCode + "\" . " +
				"		?building ObservableThing:hasAObservableFeature ?feature . " +
				"		?feature ObservableFeature:class "KiloWattLoadObservable" .	" +  //??? How to find if a var belongs to a certain class
				"		?sensor Sensor:measures ?feature . " +
				"		?sensor Sensor:hasSensorID \"" + KWhSensorID + "\" . " +
				"		?sensor Sensor:hasMeasurementObservation ?observation . " +
				"		?observation ObserveredValue:hasMeasuredValue ?measValue . " +
				"		?observation ObserveredValue:hasMeasuredTimeStamp ?measDate . " +
				"		FILTER ( ?measDate >= \""+ startDate +"\" && ?measDate <= \""+ endDate +"\") " +
				"		}";
				
				
//////////// Query 6 ///////////////
Query 6: For campus code, time period and time granularity (15min/1day), return weather time series (min/max temp, humid, etc)

Assumed relationship: 
Campus    ----->   Place
        campusISA

Existing relationships:		
p2:Place ----->   Location
	   hasALocation
Weather	  ----->    Location
		isObservedAt		  
Weather  -----> atmo:MeterologicalPhenomenon
		weatheriSA

Location  -----> GMLSurfaceProperty U Address U GMLPointProperty
		locationISA		

//Input:
String campusCode = "UPC";
String startDate = "11:Jan:2011";
String endDate 	 = "17:Sep:2012";
String query6 = "PREFIX Campus: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> "+
				"PREFIX Place: <http://dbpedia.org/ontology/Place> "+
				"PREFIX Weather: <http://www.smartgrid.usc.edu/Ontology/2012.owl#> " +
				"SELECT * {" +
				"			?campus Campus:hasCampusCode \"" + campusCode + "\" . " +
				"			?campus Campus:hasPlace	?place . " +
				"			?place Place:hasLocation ?location . " +
				"			?weather Weather:isObservedAt ?location . " +
				"			?weather Weather:hasMinTemp ?minTemp . " +
				"			?weather Weather:hasMaxTemp ?maxTemp . " +
				"			?weather Weather:hasHumidity ?humidity . " +
				"			?weather Weather:hasMeasuredDate ?measDate . " +
				"			FILTER ( ?measDate >= \""+ startDate +"\" && ?measDate <= \""+ endDate +"\") " +
				"		  }";

Note:
	1] No properties are present in Weather entity currently
	2] Need to add properties minTemp, maxtemp, humidity, etc. to it.
	3] Another approach would be to use Weather -> isA -> 'observableThing' feature approach. And then a sensor would measure 
		a 'observableFeature' in this 'observableThing'. So we will have a list of observable features that are reported by a 
		sensor that is capable of reading all the values for weather.
	4] Need to decide between 2 or 3

	
//////////// Query 7 ///////////////
Query 7: For a  given building code, return total occupancy capacity

Inputs:
String buildingCode = "EEB";
String query7 = "PREFIX Building: <http://dbpedia.org/ontology/> "+
                "SELECT ?capacity " +                                                                
                "WHERE { " +                                                
    			"       ?building Building:hasBuildingCode \"" + buildingCode + "\" . " +
				"		?building Building:hasOccupancyCapacity ?capacity . "
				//"		?building Building:hasBuildingNo ?BuildingNum . " +
				"      }";

Note:
	1] Building Entity doesn't have hasOccupancyCapacity property ... need to add it
				
				
//////////// Query 8 ///////////////
Query 8:  For a  given building code and time period, return list of scheduled calendar events (classroom and USC calendar)

Existing:
Activity 	   -----> p1:Schedule
				hasSchedule
Location       -----> GMLSurfaceProperty U Address U GMLPointProperty
				locationISA				
p2:Building   ----->   p2:Place				[ Building Class has all the attributes we are interested in, buildingNo,code etc... ]
	            subClassof
p2:Place      ----->   Location
	            hasALocation
				
New ones required:
p1:Schedule   ------>  Location
			isAtLocation				

Schedule does not have date, start time, end time, duration, etc. properties as literal values
Activity does not have name, type, etc properties. need to add that to these properties for query8
			
//Input:
String campusCode = "UPC";
String startDate = "11:Jan:2011";
String endDate 	 = "17:Sep:2012";
String query8 = "PREFIX Building: <http://dbpedia.org/ontology/> " +
				"PREFIX Schedule: http://www.w3.org/2002/12/cal/icaltzd# " +
				"PREFIX Activity: http://www.smartgrid.usc.edu/Ontology/2012.owl#"
				"PREFIX " +
				"SELECT ?activityName " +                                                                
                "WHERE { " +                                                
    			"       ?building Building:hasBuildingCode \"" + buildingCode + "\" . " +
				"		?building Building:hasALocation ?location . " +
				"		?schedule Schedule:isAtLocation ?location . " +		//??? To be added to Ontology
				"		?schedule Schedule:hasDate ?date . " +				//??? To be added to Ontology
				"		?schedule Schedule:hasStartTime ?startTime . " +	//??? To be added to Ontology
				"		?schedule Schedule:hasEndTime ?endTime . " +		//??? To be added to Ontology
				"		FILTER ( ?date >= \""+ startDate +"\" && ?date <= \""+ endDate +"\") " +
				"		?activity Activity:hasSchedule ?schedule . " +
				"		?activity Activity:hasName ?activityName . " +		//??? To be added to Ontology
				"	   }";

//////////// Query 9 ///////////////
Query 9: Push async action alerts to browser from CEP (Sceptre)

Not relevant yet. 


//////////// Query 10 ///////////////
Query 10:  For a  given building code, return current occupancy (!!!) 				

Not relevant yet.
