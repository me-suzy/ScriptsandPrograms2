<? 
   include "conf/sys.conf";
   include "tpl/top.ihtml";
   ?> 
<p><b>DEFAULT PAGE</b><br>
  This page opened because at least one of these things happend:<br>
</p>
<ul>
  <li>The code used to reach this page is inaccurate.</li>
  <li>The traffic requests from your ip are too fast.<br>(Protections set at 
    <?php echo $ipfseconds;?>
    seconds minimum time the user has to wait between new page requests .)<br>
  </li>
  <li>You have already visited the paid link from paid email.</li>
  <li>The campaign promoting the site you were about to visit was disabled/deleted.</li>
  <li>The campaign promoting the site you were about to visit ran out of credits.</li>
  <li>The campaign promoting the site you were about to visit has no valid url.</li>
  <li>There is no other valid campaign in the system to exchange traffic with.</li>
</ul>
<p> 
  <?
   include "tpl/bottom.ihtml";
?>
</p>
