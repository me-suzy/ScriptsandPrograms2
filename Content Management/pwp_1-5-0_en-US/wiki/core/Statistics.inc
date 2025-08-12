<?php

/**
 * Provides an ASCII text based pseudo diagram about the PWP usage.
 * Will use N * GRAPH_STEP_X in order to scale the X-axis. This way the
 * diagramm remains comparable until the amout of data has reached a certain
 * size.
 * Summarizes the types of uploaded files as table.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */
define('CLASS_COUNT', 20);    // use m lines in Y-direction
define('GRAPH_STEP_X', 50 ); // take n files as step for the x direction

class Statistics {

	/**
	 * Calculates some base indicators for a given set of files.
	 */
	function _calcDistribution( &$filestats, $column ) {

		$min   = 2100000000; //roughly 2.1 billion as 32 bit int max [1]
		$max   = 0;
		$sum   = 0;
		$current = 0;
		$count = sizeof($filestats);

/*
[1] = In case the smallest value was bigger than 2.1 billion (e.g. seconds or bytes),
      PWP would use a wrong minimum in the overview table without other implications.
*/
		//find min, max, sum up all
		foreach( $filestats as $item ) {
			$current = $item[$column];
			$min  = ($current < $min)?($current):($min);
			$max  = ($current > $max)?($current):($max);
			$sum += $current;
		}

		//calc class width based on CLASS_COUNT and data range
		$classWidth = ceil(($max + 1) / CLASS_COUNT); //TODO verify +1
		$classWidth = ($classWidth == 0)?(1):($classWidth);

		//create and prefill the distribution array
		$distArray  = array();
		for( $i=0; $i<CLASS_COUNT; $i++ ) {
			$distArray[$i] = 0;
		}

		//count in the distribution array, the keys are the class numbers 0..n
		foreach( $filestats as $item ) {
			++$distArray[floor($item[$column]/$classWidth)];
		}

		//find the biggest class (most counts)
		$distMax = 0;
		for( $i=0; $i<CLASS_COUNT; $i++ ) {
			$distMax  = ($distArray[$i] > $distMax)?($distArray[$i]):($distMax);
		}

		$average = ceil($sum/$count);

		return array($count, $min, $max, $average, $sum, $distArray, $distMax, $classWidth);
	}

	/**
	 * Returns a pseudo-graphical representation of the distribution of Wiki pages or uploaded files
	 * over size or age. Sample:
	 *
	 * Statistics::recalculateAge($testArray, 2, 3600);
     * print Statistics::getSizeDistribution($testArray, 1, "size", "KByte", 1024, 1);
     * print Statistics::getSizeDistribution($testArray, 2, "age", "days", 24, 0);
     *
     * Compatible to the array format provided by Data and Upload:
     *
     * $testArray[] = array('A.xls', 12400, 999409600);
     * $testArray[] = array('A.b.xls', 13000, 995209600);
	 */
	function &getSizeDistribution( &$filestats, $column, $dimension, $unit, $factor, $digits ) {
		global $gConfig;
		list($count, $min, $max, $average, $sum, $distArray, $distMax, $classWidth) = Statistics::_calcDistribution( $filestats,  $column );

		$count   = number_format( $count, 0, '.', ',' );
		$min     = number_format( $min / $factor, 0, '.', ',' );
		$max     = number_format( $max / $factor, 0, '.', ',' );
		$average = number_format( $average /  $factor, 0, '.', ',' );
		$sum     = number_format( $sum / $factor, 0, '.', ',' );

		$graphMaxX = ceil($distMax/GRAPH_STEP_X) * GRAPH_STEP_X;
		$charsMax  = ($gConfig->mProp['EditColumns']  - 12);
		$line      = str_pad('', $charsMax, '-', STR_PAD_RIGHT);

		$res = <<<eoh
<table cellspacing='1' cellpadding='2' border='0' width='100%'>
<tr>
	<th>Files total:</th>
	<th>Total $dimension:</th>
	<th>Average $dimension:</th>
	<th>Max $dimension:</th>
	<th>Min $dimension:</th>
</tr><tr>
	<td align='right'>$count</td>
	<td align='right'>$sum $unit</td>
	<td align='right'>$average $unit</td>
	<td align='right'>$max $unit</td>
	<td align='right'>$min $unit</td>
</tr>
</table>
<br>
<pre>
Class     |
limit     | 0 .. $graphMaxX files -&gt;
----------|$line|

eoh;

		for( $i=0; $i<CLASS_COUNT; $i++ ) {
			$res .= str_pad(number_format( $classWidth * ($i+1) / $factor, $digits, '.', ',' ), 10, " ", STR_PAD_LEFT).'|';
			if (empty($distArray[$i])) {
				$res .= "\n";
			} else {
				$res .= str_pad('', floor($distArray[$i]/$graphMaxX * $charsMax), '#', STR_PAD_RIGHT)."\n";
			}
		}

		$res .= <<<eoh
----------|$line|
$unit
</pre>
eoh;
		return $res;
	}

	/**
	 * Transforms the time of the last change (seconds since 1970) into the
	 * time difference to today (now - age), also stored in seconds.
	 */
	function recalculateAge( &$filestats, $column, $factor ) {
		$now   = time();
		foreach( $filestats as $file => $array ) {
			$filestats[$file][$column] = floor( ($now - $array[$column]) / $factor);
		}
	}

	/**
	 * Shows the amout of uploaded files grouped by type (extension).
	 */
	function &getPopularFiles( &$filestats, $limit, $column ) {

		//create an arry extension => count, sort it
		$resArray  = array();
		$pos       = 0;
		$extension = '';
		foreach( $filestats as $file ) {
			 $pos = strrpos( $file[$column], "." );
			 if ($pos !== FALSE) {
			 	$extension = strtolower(substr($file[$column], $pos));
			 	if (isset($resArray[$extension])) {
			 		++$resArray[$extension];
			 	} else {
			 		$resArray[$extension] = 1;
			 	}
			}
		}
		arsort( $resArray );

		//print the table with the result array
		$res = <<<eoh
<table cellspacing='1' cellpadding='2' border='0' width='66%'>
<tr>
	<th>File type</th>
	<th>Number of files</th>
	<th>Percentage</th>
</tr>
eoh;
		$i     = 0;
		$count = sizeof($filestats);
		foreach( $resArray as $file => $value ) {
			$percentage = number_format( $value / $count * 100, 1, '.', ',' );
			$value      = number_format( $value, 0, '.', ',' );
			$res .= <<<eoh
<tr>
	<td>$file</td><td align='right'>$value</td><td align='right'>$percentage %</td>
</tr>
eoh;
			if (++$i == $limit) {
				break;
			}
		}
		$res .= <<<eoh
</table>
<small>
($count files in total. Show up to $limit popular types.
PWP treats extensions case insensitive.
Files without an extension are ommited in the list but influence
the percentage.)</small>
<br>
eoh;
		return $res;
	}
}
?>