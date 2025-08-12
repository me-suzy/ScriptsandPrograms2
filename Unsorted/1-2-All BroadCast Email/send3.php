<?PHP
if ($schedule == "yes"){
?>
<p><b><font size="2" face="Arial, Helvetica, sans-serif">Message is now in the 
  scheduled queue. </font></b></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">Click on &quot;Scheduled Mailings&quot; 
  to the left to view details.</font></p>
<?PHP 
}
else{
?>
<p><b><font size="2" face="Arial, Helvetica, sans-serif">Message is now in the 
  queue. </font></b></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">Click on &quot;Message Queue&quot; 
  to the left to view the status as it is sent.</font></p>
<?PHP
}
?>