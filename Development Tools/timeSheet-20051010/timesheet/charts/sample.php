<?php

//include charts.php to access the SendChartData function
include "charts.php";
$chart['chart_type'] = '3d column';
$chart['chart_data'] = array(
						array('','2001','2002','2003','2004'),
						array('Region A',5,10,30,64),
						array('Region B',5,10,30,64),
						array('Region C',5,10,30,64),
					);

SendChartData ($chart);

?>
