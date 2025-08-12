<?php
/*! \class ChartFactory
 *  \brief Manages creation of Chart objects

    Manages creation of the chart objects ChartPie ChartRow aso.
*/
class ChartFactory{
	/** Creates the Chart objects -- call statical ie. ChartFactory::Factory('Pie');
	 * @param $type the type of Chart object to create
	 * @param $poll a Poll object, this is the poll that the chart will graf
	 * @return Error object on error, Chart object on sucess
	 */
	function &Factory($type, $poll)
    {
        include_once "Charts/{$type}.php";

        $classname = "Chart${type}";

        if (!class_exists($classname)) {
            return new Error("Unable to include the Charts/{$type}.php");;
        }

        return new $classname($poll);
    }
}

/*! \class ChartCommon
 *  \brief Template for Chart objects

    If you implement a class with this interface and place it in the Charts folder with the samme name as the 
 	file, look at the Pie.php, Row.php and Colum.php to se examples. Then it would be possible for the user to 
 	select the new charttypes.
*/
class ChartCommon{
	
	var $isError = false;
	
	var $colorCount = 0;
	
	/** Constructor -- must accept a Poll object */
	function ChartCommon($poll){
		
	}
	
	/** Generate an image to show the poll, is outputtet when this method is called
	 * @param $sizeX the width of the image to create
	 * @param $sizeY the height of the image to create
	 * 
	 * @return true on success, false on error, on error ChartCommon::isError() will return an Error object
	 */
	function getImageData($sizeX, $sizeY){
		
	}
	
	function isError(){
		return $this->isError;
	}
	
	function getColor($reset = false){
		static $colorCount = 0;
		if($reset){
			$colorCount = 0;
			return true;
		}
		$color[]    = array(0xC0, 0xC0, 0xC0); // grey
		$color[]    = array(0x90, 0x90, 0x90); // darkgrey
		$color[]    = array(0x00, 0x00, 0x80); // navy
		$color[]    = array(0x00, 0x00, 0x50); // darknavy
		$color[]    = array(0xFF, 0x00, 0x00); // red
		$color[]    = array(0x90, 0x00, 0x00); // darkred
		$color[]    = array(0x00, 0xFF, 0x00); // green
		$color[]    = array(0x00, 0xCC, 0x00); // darkgreen 
		$color[]    = array(0xFF, 0xFF, 0x00); // yellow
		$color[]    = array(0xCC, 0xCC, 0x00); // darkyellow
		$color[]    = array(0x00, 0x00, 0xFF); // blue
		$color[]    = array(0x00, 0x00, 0xCC); // darkblue
		$color[]    = array(0xFF, 0xA5, 0x00); // orange
		$color[]    = array(0xCC, 0x82, 0x00); // darkorange
		$color[]    = array(0x80, 0x80, 0x80); // grey
		$color[]    = array(0x50, 0x50, 0x50); // darkgrey
		$color[]    = array(0xA5, 0x2A, 0x2A); // brown
		$color[]    = array(0x82, 0x08, 0x08); // darkbrown
		$color[]    = array(0xFF, 0xC0, 0xCB); // pink
		$color[]    = array(0xCC, 0x90, 0x98); // darkpink
		$color[]    = array(0x00, 0xFF, 0xFF); // aqua
		$color[]    = array(0x00, 0xCC, 0xCC); // darkaqua
		$color[]    = array(0xDC, 0x14, 0x3C); // crimson
		$color[]    = array(0xA9, 0x01, 0x09); // darkcrimson
		$color[]    = array(0x4B, 0x00, 0x82); // indigo
		$color[]    = array(0x18, 0x00, 0x50); // darkindigo
		$color[]    = array(0xFF, 0x00, 0xFF); // magneta
		$color[]    = array(0xCC, 0x00, 0xCC); // darkmagneta
		$color[]    = array(0xEE, 0x82, 0xEE); // violet
		$color[]    = array(0xBB, 0x50, 0xBB); // darkviolet
		
		return $color[$colorCount++];
	}
}
?>