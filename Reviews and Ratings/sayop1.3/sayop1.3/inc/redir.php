<?php
function smsg($msg) {
ob_start();
echo "
<!DOCTYPE html
PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
   <head>
      <title>Redirecting...</title>
      <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
   </head>
   <body>

      <div align='center' style='font-family: Verdana, Arial, sans-serif; font-size: 11px; background: #CCFF99; border: solid 1px; padding: 15px; margin-left: 100px; margin-right: 100px;'>   
      $msg
      </div>

   </body>
</html> 
";
}
?>