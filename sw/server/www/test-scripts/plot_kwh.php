<?php
include('PHP_GnuPlot.php');

$building = "EEB";		//get from App in POST request
$timestamp = "1234567890000";	//get from App in POST request (ms from 1970)
$graph = "cost";		//"cost";	//get from App in POST request
$time_begin_hr = '14';		//get from App
$time_begin_min = '30';		//round up

//check isset()

//Pull 8 datapoints from database, and kwh rate
$kwh_array = array(1.5, 2.5, 3.5, 5.5, 8, 4.5, 2.5, 5);		//stores the 8 kWh values for $building from database 
$kwh_rate = array(0.15, 0.16, 0.24, 0.30, 0.33, 0.31, 0.29, 0.13);	//cost per kWh retrieved from dynamic utility look-up table
$time_begin = $time_begin_hr." ".($time_begin_min-'7.5');
$time_end_hr = $time_begin_hr + '2';
$time_end_min = $time_begin_min;	//2-hour gap makes 15-minute xtics
$time_end = $time_end_hr." ".($time_end_min-'7.5');

$p = new GNUPlot();

$p->setTitle("Energy Forecast for ".$building." (requested at t=".$timestamp.")");
$p->setDimLabel("x", "Time (HH:MM)");
$p->formatPlot();
$data = new PGData($building);

if ($graph == "energy") {
	$filename = $timestamp.'_plot_energy.png';
	foreach ($kwh_array as $value) {
		$data->addDataEntry( array($time_begin_hr." ".$time_begin_min, $value) );
		if ($time_begin_min + '15' == '60') {
			$time_begin_hr = ( ($time_begin_hr + '1' == '25') ? '0' : $time_begin_hr + '1' );
			$time_begin_min = '00';
		} else {
			$time_begin_min = $time_begin_min + '15';
		}
	}
	$p->setDimLabel("y", "Energy (kWh)");
	$p->set("autoscale");
	$p->setRange('x', $time_begin, $time_end);
	$p->plotData( $data, 'boxes', '1:3' );
	$p->export($filename);
	$p->close();
} elseif ($graph == "cost") {
	$filename = $timestamp.'_plot_cost.png';
	foreach ($kwh_array as $key => $value) {
		$data->addDataEntry( array($time_begin_hr." ".$time_begin_min, $value * $kwh_rate[$key]) );
		echo $time_begin_hr.":".$time_begin_min."\n".$value."\n";
//		$p->set2DLabel("$".$kwh_rate[$key]."/kWh", $time_begin_hr." ".$time_begin_min, $value * $kwh_rate[$key]);	//display costs
		if ($time_begin_min + '15' == '60') {
			$time_begin_hr = ( ($time_begin_hr + '1' == '25') ? '0' : $time_begin_hr + '1' );
			$time_begin_min = '00';
		} else {
			$time_begin_min = $time_begin_min + '15';
		}
	}
	$p->setDimLabel("y", "Cost (\$USD)");
	$p->set("autoscale");
	$p->setRange('x', $time_begin, $time_end);
	$p->plotData( $data, 'boxes', '1:3' );
	$p->export($filename);
	$p->close();
} else {
	echo "Invalid graph type!\n";
}


?>


