<?php
/*********************************************************************************
 *       Filename: Login.php
  *       PHP & Templates build 04/20/2003
 *********************************************************************************/

include ("./common.php");
include ("./Header.php");
include ("./Footer.php");

session_start();

$filename = "Login.php";




$tpl = new Template($app_path);
$tpl->load_file("Login.html", "main");
$tpl->load_file($header_filename, "Header");
$tpl->load_file($footer_filename, "Footer");

$tpl->set_var("FileName", $filename);


$sLoginErr = "";

$sAction = get_param("FormAction");
$sForm = get_param("FormName");
switch ($sForm) {
  case "Login":
    Login_action($sAction);
  break;
}Header_show();
Footer_show();
Login_show();

$tpl->parse("Header", false);
$tpl->parse("Footer", false);

$tpl->pparse("main", false);

//********************************************************************************


function Login_action($sAction)
{
  global $db;
  global $tpl;
  global $sLoginErr;

  switch(strtolower($sAction))
  {
    case "login":
      // Login action
      
      $sLogin = get_param("Login");
      $sPassword = get_param("Password");
      $db->query("SELECT member_id,security_level_id FROM members WHERE member_login =" . tosql($sLogin, "Text") . " AND member_password=" . tosql($sPassword, "Text"));
      
      if($db->next_record())
      {
        // Login and password passed
        set_session("UserID", $db->f("member_id"));
        set_session("UserRights", $db->f("security_level_id"));
        $sPage = get_param("ret_page");
        if (strlen($sPage))
          header("Location: " . $sPage);
        else
          header("Location: Account.php");
      }
      else
      {
        $sLoginErr = "Login or Password is incorrect.";
      }

      $tpl->parse("FormLogin", false);
      
    break;
    case "logout":
      // Logout action
      
      session_unregister("UserID");
      session_unregister("UserRights");
      $tpl->parse("FormLogin", false);
      
    break;
  }
}

function Login_show()
{
  
  global $tpl;
  global $sLoginErr;
  global $db;

  $tpl->set_var("sLoginErr", $sLoginErr);
  $tpl->set_var("querystring", get_param("querystring"));
  $tpl->set_var("ret_page", get_param("ret_page"));
  
  if(get_session("UserID") == "") 
  {
    // User did not login
    $tpl->set_var("LogoutAct", "");
    $tpl->set_var("UserInd", "");
    $tpl->set_var("Login", strip(tohtml(get_param("Login"))));
    if($sLoginErr == "")
      $tpl->set_var("LoginError", "");
    else
    {
      $tpl->set_var("sLoginErr", $sLoginErr);
      $tpl->parse("LoginError", false);
    }
    $tpl->parse("LoginAct", false);
  }
  else
  {
    // User logged in
    $db->query("SELECT member_login FROM members WHERE member_id=". get_session("UserID"));
    $db->next_record();
    $tpl->set_var("LoginError", "");
    $tpl->set_var("LoginAct", "");
    $tpl->set_var("UserID", $db->f("member_login"));
    $tpl->parse("UserInd", false);
  }
  $tpl->parse("FormLogin", false);
  

}

?>