<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Lost Password Retrieval
// >>
// >> LOST . PHP File - Password Retrieval
// >> Started : November 20, 2003
// >> Edited  : 25 November, 2003
// << -------------------------------------------------------------------- >>

ob_start();

$EX_FILE  =  1;

// INCLUDE COMMON FILE
include_once ( 'common.php' );

// Check to see if a cookie exists
if( $NO_AUTH == 1 )
{
	if(ACT == '')
	{
		if(SUBM == "")
		{
			_parse($tpl_dir.'lost_pass.tpl');
			echo substr($class->read,0, strpos($class->read, '[#'));
		}
		else
		{
			$sendto = $_POST['user'];
			
			$_Q = $db->query("SELECT * FROM phpdesk_members WHERE username='".$sendto."'");
			$_Q1 = $db->query("SELECT * FROM phpdesk_staff WHERE username='".$sendto."'");
			$_Q2 = $db->query("SELECT * FROM phpdesk_admin WHERE name='".$sendto."'");		
			$where = ($db->num($_Q)) ? "phpdesk_members" : (($db->num($_Q1)) ? "phpdesk_staff" : (($db->num($_Q2)) ? "phpdesk_admin" : ""));
			$sql = ($where == "phpdesk_admin") ? "SELECT * FROM ".$where." WHERE name = '".$sendto."'" : "SELECT * FROM ".$where." WHERE username = '".$sendto."'";
			
			$Q3 = $db->query("SELECT * FROM phpdesk_lostpass WHERE user = '".$sendto."'");
			
			$Q = $db->query($sql);
			$F = $db->fetch($Q);
			
			if(!$sendto)
			{
				echo $error['fields'];
			}
			elseif($db->num($Q3))
			{
				echo $error['passm_ago'];
			}
			elseif(!$db->num($_Q) && !$db->num($_Q1) && !$db->num($_Q2))
			{
				echo $error['no_user'];
			}
			else
			{
				$key = md5(time());
				$sql = "INSERT INTO phpdesk_lostpass (`key`,`user`,`date`,`type`)
				VALUES('".$key."','".$sendto."','".time()."','".$where."')";
				
				if($db->query($sql))
				{
					echo mail_it('lostpass', $F['email'], $general['lostpass'], $key, $sendto);
					header("Location: ".SELF."?action=validate");
				}
			}
		}
	}
	elseif(ACT == 'validate')
	{
		if(isset($_GET['key']) && isset($_GET['user']) && isset($_GET['pass']))
		{
			$sendto = $_GET['user'];
			$pass = $_GET['pass'];
			
			$Q = $db->query("SELECT * FROM phpdesk_lostpass WHERE `user` = '".$_GET['user']."' AND `key` = '".$_GET['key']."'");

			if(!$db->num($Q))
			{
				echo $error['wrong_key'];
			}
			else
			{
				$F = $db->fetch($Q);
				$where = $F['type'];
				if($where == "phpdesk_admin")
				{
					$name = "name";
					$_pass = "pass";
					$file = "admin.php";
				}
				else
				{
					$name = "username";
					$_pass = "password";
					$file = ($where == "phpdesk_members") ? "member.php" : "staff.php";
				}
				
				$name = ($where == "phpdesk_admin") ? "name" : "username";
				$_pass = ($where == "phpdesk_admin") ? "pass" : "password";

				$sql = "UPDATE ".$where." SET `".$_pass."`='".md5($pass)."' WHERE `".$name."`='".$sendto."'";
				if($db->query($sql))
				{
					echo str_replace('^file^', $file, $success['pass_reset']);
					$db->query("DELETE FROM phpdesk_lostpass WHERE `user`='".$sendto."' AND `key`='".$_GET['key']."'");
				}
			}
		}
		else
		{
			_parse($tpl_dir.'lost_pass.tpl');
			$out = substr($class->read, strpos($class->read, '[#')+2);
			$out = substr($out, 0, strpos($out, '/#]'));
			echo $out;
		
		}
	}
}
else
{
	header("Location: index.php");
}

// INCLUDE FOOTER FILE
include_once ( 'footer.php' );

// Flush all the headers
ob_end_flush();

?>