<?
class Misc
{
	/*function _nl2br($message){
		return str_replace("\n", "<br>", $message);
	}*/

	function _nl2br($message){
		$message = str_replace("\n", "<br>", "$message");
		$message = str_replace("\r", "", "$message");
		return $message;
	}
	
	function _br2nl($message){
	    return preg_replace("=<br(>|([\s/][^>]*)>)\r?\n?=i", "\n", $message);
	}

	function _parse_hrefs($_message){
		$dst = $_message;
		//$dst = Misc::_check_http($dst);
		//$dst = Misc::_check_at($dst);
		return  $dst;
	}
	
	function _check_at($_message){
		$words = split(" |[[:cntrl:]]",$_message);
		$result_mailto= array();
		for($i=0;$i<sizeof($words);$i++){
			if(strstr($words[$i],"@")){
				if(isset($words[$i-1]) && !strstr($words[$i-1],"mailto:"))
				$result_mailto[$i] = "<a href=\"mailto:".$words[$i]."\">".$words[$i]."</a>";
			}
				else
				$result_mailto[$i]=$words[$i];
		}
		
		$result="";
		for($i=0;$i<sizeof($result_mailto);$i++){
			$result.=$result_mailto[$i]." ";
		}
		
		return $result;

	}

	function _check_http($_message){
		$words = split(" |[[:cntrl:]]",$_message);
		$result_= array();
		for($i=0;$i<sizeof($words);$i++){
			if(strstr($words[$i],"http:")){
				if(isset($words[$i-1]) && !strstr($words[$i-1],"http:"))
				$result_[$i] = "<a href=\"".$words[$i]."\">".$words[$i]."</a>";
			}
				else
				$result_[$i]=$words[$i];
		}
		
		$result="";
		for($i=0;$i<sizeof($result_);$i++){
			$result.=$result_[$i]." ";
		}
		
		return $result;

	}

	function _generateRandom($length=6)
	{
		$_rand_src[0][0]=48;
		$_rand_src[0][1]=57;
		$_rand_src[1][0]=65;
		$_rand_src[1][1]=90;
		$_rand_src[2][0]=97;
		$_rand_src[2][1]=122;
		srand ((double) microtime() * 1000000);
		for($i=0;$i<$length;$i++){
			$i1=rand(0,2);
			$pass.=chr(rand($_rand_src[$i1][0],$_rand_src[$i1][1]));
		}
		return $pass;
	}

}

?>