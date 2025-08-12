<?php
include_once("demo.inc");

$login = $_GET['login'];

if ($login == "success") {
   // Redirect to application homepage
   header("Location: ../EventMgr.htm");
   exit;
}
else {
   global $g_BizSystem;
   $viewobj = $g_BizSystem->GetObjectFactory()->GetObject("shared.SignupView");
   $viewobj->Render();
}
?>
