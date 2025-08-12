<?
/*
			Author: Matthew Hamilton (matt@brokendestiny.com)
	to add more cuss words to censor, just add them one line at a time.
~ email me if you need a website designed! i do quality work for cheap pay! ~


*/	
	$bad_words = @file("words.txt");
	function censor($text){
	$rop='*'; // change this to whatever you want! it's the masking. change it to a & and all your censored words will come out like f&&&
		for ($i=1;$i<strlen($text);$i++){
			$replace .= $rop;
		}
		$text = substr_replace($text, $replace, 1);
		return $text;
	}
// how to use this in your script!

	for ($i=0; $i<count($bad_words); $i++) {
		$yourVar = eregi_replace($bad_words[$i], $this->censor($bad_words[$i]), $yourVar);
	}
	echo $yourVar;

// $yourVar can be anything you want that might contain the cuss words you want to censor out.

?>