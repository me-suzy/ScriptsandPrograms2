<?php
/*! \class ChartPie
 *  \brief Pie chart implementaion of the ChartCommon class

    Creates a Pie chart from the data in the supplied Poll object
*/
class ChartPie extends ChartCommon{
	
	var $poll 	= null;
	var $titles = array();
	var $votes 	= array();
	
	function ChartPie($poll){
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
		
		// convert votes to percent
		$voteAmount = array_sum($this->votes);
		for($i = 0; $i < count($this->votes); $i++){
			$this->votes[$i] = 100/$voteAmount*$this->votes[$i];
		}
		
		// create image
		$image = imagecreate($sizeX, $sizeY);
		$white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF); // white -- background
		
		ChartCommon::getColor(true); // reset the colors
		// allocate the colors
		for($i = 0; $i < $amount*2; $i++){
			$n = ChartCommon::getColor();
			$color[] = imagecolorallocate($image, $n[0], $n[1], $n[2]); // color 
		}
		
		// make the 3D effect
		$start_degree = 0;
		for($i = 110; $i >= 100; $i--){
			// $i controls the placing of the disks, we move it down on pixel at the time to create the 3d effect
		    $n = 1; // control the color, 1,3,5... is the dark color
		    foreach($this->votes as $data2){
		        $degree = 360*$data2/100;
		        imagefilledarc($image, 100, $i, 150, 75, $start_degree, $start_degree+$degree, $color[$n], IMG_ARC_PIE);
		        $start_degree += $degree;
		        $n += 2;
		    }
		}
		
		// make plot
		$start_degree = 0;
		$n = 0;
		foreach($this->votes as $data2){
		    $degree = 360*$data2/100;
		    imagefilledarc($image, 100, 100, 150, 75, $start_degree, $start_degree+$degree, $color[$n], IMG_ARC_PIE);
		    $start_degree += $degree;
		    $n += 2;
		}
		
		// flush image
		header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image); 
	}
}
?>