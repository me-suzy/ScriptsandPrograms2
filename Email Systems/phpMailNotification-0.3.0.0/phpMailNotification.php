<?php

//
//	php Mail Notification
//
//	Version 0.03.0001 (23.07.2003)
//
//	Copyright(c) 2003 by Petio Tonev
//
//	Revision history:
//
//		12.06.2003 -- initial version
//		30.06.2003 -- added send on email with email address for new email's
//		23.07.2003 -- added MNOTIFY_SUBJECT constant,
//						added customize on subject and body message from cfg file
//
//	Internal identifiers start with: __mnotify_
//

define("MNOTIFY_CFG_PATH",		"./");
define("MNOTIFY_CFG_NAME",		"phpMailNotification.cfg");
define("MNOTIFY_SUBJECT",		"%new% new from %total% total email(s) in %account%.");
define("MNOTIFY_MESSAGE",		"%emails%");

//
//	__mnotify_read_file - read content from file
//
function __mnotify_read_file($name)
{
	$contents = false;

	if(file_exists($name))
	{
		if($fd = @fopen($name, "r"))
		{
			$contents = fread($fd, filesize($name));

			fclose($fd);
		}
	}

	return $contents;
}

//
//	__mnotify_write_file - write data to file
//
function __mnotify_write_file($name, $data)
{
	if($fd = @fopen($name, "w"))
	{
		fwrite($fd, $data);
		fclose($fd);

		return true;
	}
	else
	{
		return false;
	}
}

function __mnotify_get_simple_email($str)
{
	$open = strpos($str, "<");
	$close = strpos($str, ">");

	if(($open === false) && ($close === false))
	{
		return $str;
	}
	
	return substr($str, $open + 1, $close - $open - 1);	
}

function __mnotify_get_from_mail($fp, $number, $size)
{
	fputs($fp, "RETR $number\n");
	$line = fgets($fp, 200);

	$line = fread($fp, $size);
	$line = explode("|", str_replace("\n", "|", $line));

	if(is_array($line) && (count($line) > 0))
	{
		foreach($line as $v)
		{
			$pos = strpos($v, "From:");

			if($pos === false)
			{
				//	noop
			}
			else
			{
				return trim(substr($v, 5));
			}
		}
	}

	return false;
}

function __mnotify_read_mails($fp, $list, $msgs)
{
	$count = $list[0];
	unset($list[0]);

	$get = true;

	if($count > $msgs)
	{
		for($i = 1; $i <= $msgs; $i++)
		{
			unset($list[$i]);
		}
	}
	else if($count < $msgs)
	{
	}
	else
	{
		$get = false;
	}

	if(is_array($list) && (count($list) > 0) && $get)
	{
		$mlist = "";

		foreach($list as $v)
		{
			$vv = explode(" ", $v);

			if(is_array($vv) && (count($vv) == 2))
			{
				$sep = (strlen($mlist) > 0)? ", ": "";
				$from = __mnotify_get_from_mail($fp, $vv[0], $vv[1]);
				$from = __mnotify_get_simple_email($from);

				$mlist = $mlist.$sep.$from;
			}
		}

		return $mlist;
	}
	else
	{
		return "";
	}
}

//
//	__mnotify_check_mail - check email account
//
function __mnotify_check_mail($host, $port, $user, $pass, $msgs)
{
	$count = 0;

	$fp = fsockopen($host, $port, $errno, $errstr);

	if(!$fp)
	{
		//	could not open socket connection
		return false;
	}
	else
	{
		$r = fgets($fp, 200);

		if(substr($r, 0, 3) == "+OK")
		{
			fputs($fp, "USER $user\n");
			fgets($fp, 200);

		  	fputs($fp, "PASS $pass\n");
			$r = fgets($fp, 200);

			if(substr($r, 0, 3) == "+OK")
			{
			  	fputs($fp, "STAT\n");
				$r = fgets($fp, 200);
				
				if(substr($r, 0, 3) == "+OK")
				{
					$r = explode(" ", $r);
					$count = $r[1];

			  		fputs($fp, "LIST\n");
					$list = fgets($fp, 200);

					$mlist = "";

					if(substr($list, 0, 3) == "+OK")
					{
						$list = array();
						$list[0] = $count;

						for($i = 0; $i < $count; $i++)
						{
							$list[] = fgets($fp, 200);
						}

						$mlist = __mnotify_read_mails($fp, $list, $msgs);
					}

					fputs($fp, "QUIT\n");
			  		fclose($fp);
			  		
			  		if($mlist != "")
			  		{
						return array($count, $mlist);
			  		}
			  		else
			  		{
						return $count;
			  		}
				}
				else
				{
					//	server said: $r
					return false;
				}
			}
			else
			{
				//	server said: $r
				return false;
			}
		}
		else
		{
			//	bad connection string
			return false;
		}
	}
}

//	read cfg file
$contents = __mnotify_read_file(MNOTIFY_CFG_PATH.MNOTIFY_CFG_NAME);

if($contents === false)
{
	echo "Can't read config file.<b>\n";
}
else
{
	//	parse and explode account
	$accounts = explode("\n", $contents);

	if (count($accounts) > 0)
	{
		foreach($accounts as $v)
		{
			//	skip comment accounts
			if(substr($v, 0, 1) == "#")
			{
				continue;
			}

			//	parse account on tokens
			$tokens = explode(";", $v);

			//	prepare account name
			$account = $tokens[1];

			if(strpos($account, "@") === false)
			{
				$account = $tokens[1]."@".$tokens[0];
			}

			//	prepare account password
			$password = $tokens[2];

			if(substr($password, 0, 1) == "#")
			{
				$password = base64_decode(substr($password, 1));
			}

			//	compose account file name
			$file_name = $tokens[1]."@".$tokens[0].".cfg";

			//	get subject template text
			if(!isset($tokens[4]))
			{
				$tokens[4] = MNOTIFY_SUBJECT;
			}

			//	get body template text
			if(!isset($tokens[5]))
			{
				$tokens[5] = MNOTIFY_MESSAGE;
			}

			//	read old email count
			$msg_old = __mnotify_read_file(MNOTIFY_CFG_PATH.$file_name);

			//	check email
			$new = __mnotify_check_mail($tokens[0], 110, $tokens[1], $password, $msg_old);

			if($new === false)
			{
				echo "Can't check $account account.<b>\n";
			}
			else
			{
				if(is_array($new) && (count($new) == 2))
				{
					$msg_new = $new[0];
					$body = $new[1];
				}
				else
				{
					$msg_new = $new;
					$body = ".";
				}

				if($msg_new > $msg_old)
				{
					$msg_dif = $msg_new - $msg_old;

					$_msg = $tokens[4];
					$_msg = str_replace("%new%", $msg_dif, $_msg);
					$_msg = str_replace("%total%", $msg_new, $_msg);
					$_msg = str_replace("%account%", $account, $_msg);
					$_msg = str_replace("%emails%", $body, $_msg);

					$_body = $tokens[5];
					$_body = str_replace("%new%", $msg_dif, $_body);
					$_body = str_replace("%total%", $msg_new, $_body);
					$_body = str_replace("%account%", $account, $_body);
					$_body = str_replace("%emails%", $body, $_body);

					if(mail($tokens[3], $_msg, $_body, "From: $account"))
					{
						echo "[$_msg], [$_body]<b>\n";
					}
					else
					{
						echo "Can't send email to ".$tokens[3]." successfully.<b>\n";
					}
				}
				__mnotify_write_file(MNOTIFY_CFG_PATH.$file_name, $msg_new);
			}
		}
	}
	else
	{
		echo "Can't extract account from config file.<b>\n";
	}
}

?>