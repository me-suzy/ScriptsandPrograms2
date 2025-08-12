<?php
/*! \class ChartRow
 *  \brief Row chart implementaion of the ChartCommon class

    Creates a Row chart from the data in the supplied Poll object
*/
class ChartRow extends ChartCommon{
	
	var $poll 	= null;
	var $titles = array();
	var $votes 	= array();
	
	function ChartRow($poll){
		if(!is_a($poll, 'Poll')){
			$this->isError = new Error("Not a poll object");
			return;
		}
		$this->poll = &$poll;
		
		// extract data
		$questions = $this->poll->getQuestions();
		while($question = each($questions)){
			$this->titles[] = $question['value']->getText();
			$this->votes[] 	= $question['value']->getVotes();
		}
	}
	
	function getImageData($sizeX, $sizeY){
		// find amount of questions
		$amount = count($this->votes);
		if($amount > 15){
			// we can handle a maximum of 15 questions
			$this->isError = new Error("To many questions");
			return false;
		}
		
		// convert votes to percent -- and find highest votes
		$voteAmount = array_sum($this->votes);
		$voteHigh = 0; // higheste votecount in percent
		for($i = 0; $i < count($this->votes); $i++){
			$this->votes[$i] = 100/$voteAmount*$this->votes[$i];
			if($this->votes[$i] > $voteHigh)
				$voteHigh = $this->votes[$i];
		}
		
		// create image
		$image = @imagecreatefrompng(dirname(__FILE__)."/img/row_graph.png");
		if (!$image) { /* See if it failed */
       		$image  = imagecreate(200, 200); /* Create a blank image */
       		$white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF); // white -- background
       		imagefilledrectangle($image, 0, 0, 200, 200, $white);
		}
		ChartCommon::getColor(true); // reset the colors
		// allocate the colors
		for($i = 0; $i < $amount*2; $i++){
			$n = ChartCommon::getColor();
			$color[] = imagecolorallocate($image, $n[0], $n[1], $n[2]); // color 
		}
		
		// calc widths
		$questionSpace = floor($sizeX/$amount);
		// we need a padding of 5px on each side of the bar
		if($questionSpace < 10){
			// problem FIXME
		}else{
			$rowWidth = $questionSpace - 5;
		}
		$rowHeight = $sizeY - 20;
		
		// create bars
		
		// ajust votes
		for($i = 0; $i < count($this->votes); $i++){
			if($this->votes[$i] == 0)
				continue;
			$this->votes[$i] = $this->votes[$i]/$voteHigh*$rowHeight;
		}
		$n = 0;
		$startY = 10;
		foreach($this->votes as $data){
			$voteHeight = ceil($data);
			// draw bar
			imagefilledrectangle($image, 10, $startY, 10+$voteHeight, $rowWidth+$startY, $color[$n]);
			
			//draw bar shadow 
			$values = array(
           		10,  $startY,  // Point 1 (x, y)
           		12,  $startY-2, // Point 2 (x, y)
           		10+$voteHeight+2, $startY-2,  // Point 3 (x, y)
           		10+$voteHeight, $startY  // Point 4 (x, y)
           	);
			imagefilledpolygon($image, $values, 4, $color[$n+1]);
			$values = array(
				10+$voteHeight, $startY,
				10+$voteHeight+2, $startY-2,
				10+$voteHeight+2, $rowWidth+$startY-2,
				10+$voteHeight, $rowWidth+$startY,
           		
           	);
			imagefilledpolygon($image, $values, 4, $color[$n+1]);
			
			#echo "10, ".$startY." - ".$voteHeight.", ".($rowWidth+$startY)."<br/>";
			$startY += $questionSpace;
			$n += 2;
		}
		
		// flush image
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image); 
	}
}
?>