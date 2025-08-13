<?php
	session_start();
	include ("vars.inc.php");
	
	$q=new Cdb;
	
	if (!isset($action)) $action="homepage";
	
	
	switch ($action)
	{
		case "homepage":
			FFileRead("template.homepage.htm",$content);
			$title=$sitename." - Homepage";
			break;
		case "faq":
			FFileRead("template.faq.htm",$content);
			$title=$sitename." - FAQ";
			break;
		case "contact":
			FFileRead("template.contact.htm",$content);
			$title=$sitename." - Contact";
			break;
		case "sign_up":
			FFileRead("template.signup.htm",$content);
			$title=$sitename." - New users & members";
			if (isset($error_sign_up)) 
				$content=str_replace("{error_sign_up}",$error_sign_up,$content);
			else
				$content=str_replace("{error_sign_up}","",$content);
			if (isset($error_sign_in)) 
				$content=str_replace("{error_sign_in}",$error_sign_in,$content);
			else
				$content=str_replace("{error_sign_in}","",$content);
			if (isset($error_forget_password)) 
				$content=str_replace("{error_forget_password}",$error_forget_password,$content);
			else
				$content=str_replace("{error_forget_password}","",$content);
			break;
		case "do_sign_in":
			require("do.sign.in.php");
			break;
		case "do_sign_up":
			require("do.show.pay.php");
			break;
		case "pay_done":
			require("do.pay.done.php");
			break;
		case "pay_message":
			require("pay.message.php");
			break;
		case "pay":
			require("do.show.pay.php");
			$title=$sitename." - Member area - Pay for membership";
			break;
		case "forget_password":
			require("forget.password.php");
			break;
		case "member_area":
			require("member.area.home.php");
			$title=$sitename." - Member area - Home";
			break;
		case "search_domains":
			require("member.area.search.domains.php");
			$title=$sitename." - Member area - Search Domains";
			break;
		case "do_search_domains":
			require("member.area.do.search.domains.php");
			$title=$sitename." - Member area - Search Results";
			break;
		case "do_add_monitor":
			require("member.area.do.add.monitor.php");
			$title="";
			break;
		case "monitor_domains":
			require("member.area.monitor.domains.php");
			$title=$sitename." - Member area - Monitor Domains";
			break;
		case "analysis":
			require("member.area.analysis.php");
			$title=$sitename." - Member area - Yahoo, Google & Dmoz analysis";
			break;
		case "digger":
			require("member.area.digger.php");
			$title=$sitename." - Member area - Domain Digger";
			break;
		case "do_dig":
			require("member.area.do.dig.php");
			$title=$sitename." - Member area - Domain Digger Results";
			break;
		case "do_analyze":
			require("member.area.do.analyze.php");
			$title=$sitename." - Member area - Yahoo, Google & Dmoz analysis";
			break;
		case "do_remove_monitor":
			require("member.area.do.remove.monitor.php");
			$title=$sitename." - Member area - Monitor Domains";
			break;
		case "do_save_account":
			require("member.area.do.save.account.php");
			$title="";
			break;
	}

	FFileRead("template.main.htm",$main);
	$main=str_replace("{content}",$content,$main);
	$main=str_replace("{title}",$title,$main);
	$main=str_replace("{sitename}",$sitename,$main);
	$main=str_replace("{webmasteremail}",$webmasteremail,$main);
	echo $main;
?>