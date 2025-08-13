<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class mailer
{
/* MAILER CLASS : by Munzir (02/11/2002) */
/* HOW TO USE THE MAILER CLASS?			 */
// example

/* $mailer = new mailer;
$mailer->to = ""; // to who? can be more than 1 (saparate using comma)
//$mailer->cc = "";
//$mailer->bcc = "";
$mailer->subject = "";  // The subject!
$mailer->usehtml = "0"; // Using html? (0 = no)
$mailer->from = "";
$mailer->message = "";
$mailer->sendmail(); // m00t!
*/
/* ----------------------------------*/
var $to = ""; // to who? can be more than 1 (saparate using comma)
var $cc = "";
var $bcc = "";

var $subject = "";  // The subject!
var $usehtml = "0"; // Using html? (0 = no)
var $from = "";
var $message = "";
/* ----------------------------------*/

	function process_to($process="",$returnend=0)
	{
		$process = ($process == "") ? $this->to:$process;
		if ($process != "")
		{
			$process = str_replace("|||","SPLIT",$process);
			/*-- MHR|||amunzir@tm.net.my,Matt|||matt@blah.com -- */
			if (preg_match("/,/",$process))
			{
				$split = explode(",",$process);
				foreach($split as $split2)
				{
					if (trim($split2) != "")
					{
						if (preg_match("/SPLIT/",$split2))
						{
							$split3 = explode("SPLIT",$split2);
							$a .= $split3[0]." <".$split3[1].">";
						} 
						else
						{
							$a .= $split2;
						}
						
						$a .= ($split2 != end($split)) ? ",":"";
					}		
				}
			}
			else
			{
				
				if (preg_match("/SPLIT/",$process))
				{	
					$spliter = explode("SPLIT",$process);
					$a .= $spliter[0]." <".$spliter[1].">";
				} 
				else
				{
					$a = $process;
				}
			}
			
			$end = ($returnend) ? "\n":"";
			return $a.$end;
		}
	}

	function process($text)
	{
		$get = explode("|||",$text);
		return $get[0]." <".$get[1].">";
	}

	function do_header()
	{
		if (trim($this->usehtml) == "1") 
		{
			$a .= "MIME-Version: 1.0\n";
			$a .= "Content-type: text/html; charset=iso-8859-1\n";
		}
		
		if (trim($this->cc) != "") 
		{
			$a .= $this->process_to($this->cc,1);
		}

		if(trim($this->bcc) != "")
		{
			$a .= $this->process_to($this->bcc,1);
		}
		
		

		if (trim($this->from) != "")
		{
			$a .= "From: ".$this->process($this->from)."\n";
		}
		return $a;
	}
	
	function sendmail($debug=0)
	{		
		if ($debug)
		{
			echo "To :".htmlspecialchars($this->process_to())."<br />";
			echo "Subject: ".$this->subject."<br />";
			echo "Message: <br />:".$this->message."<Br /><br />";
			echo "Header:<br /> ".nl2br(htmlspecialchars($this->do_header()))."<br /><br />";
			exit;
		}

		return mail($this->process_to($this->to), $this->subject, $this->message, $this->do_header());
	}
}
?>
