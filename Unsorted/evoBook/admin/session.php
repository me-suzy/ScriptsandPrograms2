<?php
class Session
{
	function Session()
	{
		global $settings,$_SESSION;

		$this->time = time();
		$this->user_ip = $this->get_ip();
		
		//if (isset($_SESSION['expire']) && $this->time > $_SESSION['expire'])
		//{
			//$this->destroy_session();
			//header("location: ".$_SERVER['PHP_SELF']);
		//}

		$this->ses_name = "s";
		$this->get_session();

		//echo $this->ses_id."<br />";

	}

	function get_session()
	{
		global $_SESSION,$_COOKIE,$_GET,$_POST,$_REQUEST;
		
		session_name("s");
		session_start();
		
		$this->ses_id = session_id();
		$this->ses_amp = "&amp;";

	}
	
	function get_surl()
	{
		global $_COOKIE;
		$surl = !isset($_COOKIE['s']) ? $this->ses_amp.session_name($this->ses_name)."=".$this->ses_id:'';

		return $surl;
	}

	function makeurl($url)
	{
		return $url.$this->get_surl();
	}

	function destroy_session()
	{
		global $evoLANG;

		$_SESSION = array();
		session_destroy();

		//return $evoLANG['ses_loggedout'];
	}

	function get_ip()
	{
		global $_SERVER,$_ENV;
		$getip = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR']:$_ENV['REMOTE_ADDR'];
		return $getip;
	}


}
// session thingy
$ses  = new Session;
$sid  = $ses->ses_id;
$amp  = $ses->ses_amp;
$surl = $ses->get_surl();


//echo $sid;

//kalau ade session, get $userinfo array
// kalau tak login lagi, buat login form
// kalau ade login info, check for session cookie, else check $_GET[s]
// check inadmin
// check session expiration

if ($_GET['do'] == "clear")
{
	$_SESSION = array();
	session_destroy();
	header("location: $_SERVER[PHP_SELF]");
}
?>