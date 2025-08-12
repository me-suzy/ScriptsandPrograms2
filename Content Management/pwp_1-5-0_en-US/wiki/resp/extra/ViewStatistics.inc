<?php
/**
 * This response shows statistical information
 * about the contained Wikin pages and uploaded files.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Statistics.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );

class ViewStatistics extends Response {

	function ViewStatistics( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {

		$args['Heading'] = "Statistics";
		$this->changeState('out/Html');

		$arrayPages =& Data::getIndexList();
		$arrayFiles =& Upload::getIndexList();

		Statistics::recalculateAge($arrayPages, 'time', 3600); //make it based on hours
		Statistics::recalculateAge($arrayFiles, 'time', 3600);

		echo <<<eoh
<h3>Distribution Wiki Pages Over Size</h3>
eoh;
		echo Statistics::getSizeDistribution($arrayPages, 'size', "Size", "KByte", 1024, 1);

		echo <<<eoh
<h3>Distribution Wiki Pages Over Last Modified Time</h3>
eoh;
		echo Statistics::getSizeDistribution($arrayPages, 'time', "Time", "Days", 24, 0);


		echo <<<eoh
<h3>Distribution Uploaded Files Over Size</h3>
eoh;
		echo Statistics::getSizeDistribution($arrayFiles, 'size', "Size", "KByte", 1024, 1);

		echo <<<eoh
<h3>Distribution Uploaded Files Over Last Modified Time</h3>
eoh;
		echo Statistics::getSizeDistribution($arrayFiles, 'time', "Time", "Days", 24, 0);

		echo <<<eoh
<h3>Uploaded Files: File Type Popularity</h3>
eoh;
		echo Statistics::getPopularFiles($arrayFiles, 10, 'name');

	}
}
?>