<? 
session_start();
include "_header.inc" 
?>
<b><?=$_REQUEST["msg"]?></b>
<style>li {list-style:circle outside;margin-left:12px;}</style>

<div style="width=100%">
  <!--- AD BOX ------------------>
  <div style="width:150px;height:430px;float:right;border:1px solid grey;text-align:center;margin:5px;">
      <script type="text/javascript"><!--
      google_ad_client = "pub-6448205169656720";
      google_alternate_color = "FFFFFF";
      google_ad_width = 125;
      google_ad_height = 125;
      google_ad_format = "125x125_as";
      google_ad_channel ="";
      google_ad_type = "text";
      google_color_border = "FFFFFF";
      google_color_bg = "FFFFFF";
      google_color_link = "0000FF";
      google_color_url = "EEEEEE";
      google_color_text = "000000";
      //--></script>
      <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
      </script>

      <? if  ($_SESSION["adminmode"]) { ?> 
				<b>For Admin.</b><br>
  			1. <a href=proc_lnkx.php?listall=1>List All Links</a> <br>
  			2. <a href=proc_lnkx.php?chkall=1>Check All Links</a> <br>
   			3. <a href=proc_lnkx.php?logout=1>Logout</a>
      <? } else { ?>
         <form action="proc_lnkx.php" method=post>
         	<input type=password name=adminpass style="width:60px">
         	<input type=submit name=admin value="Admin Login">
         </form>
      <? } //enf if() ?> 

	</div>

  <!--- USER ACTION GUIDE ------------------------------>
  <br>
  <h1 class="header">Free Link Exchange 1-2-3</h1> 
  
  <b>1. Copy and paste the following to your website.</b>
  <br>
  	<div style="border:1px solid #cccccc;margin:10px;width:530px">
  		&lt;b&gt&lt;a href="http://toronto.citypost.ca"&gt;Toronto Free Classifieds&lt;/a&gt;&lt;/b&gt
  		Rent, Auto, Buysell and Open Community Postings.<br>
  		Also provides myshop, avatar, and ad management for free.
  	</div>
  <b>2. Link the website from your home.</b>
  <br>
  <br>
  <b>3. Let's change link now.</b>
    <div style="margin-left:10px">
	  	Please make it sure you did 1. and 2.!!
		</div>

  <!--- URL SUBMIT FORM ------------------------------>
  <form method="POST" action="proc_lnkx.php">
    <table style="width:530px;margin:10px;">
      <tr>
        <td><b>Your name:</b></td>
        <td><input type="text" name="name" maxlength="50"></td>
      </tr>
      <tr>
        <td><b>E-mail:</b></td>
        <td><input type="text" name="email" maxlength="50"></td>
      </tr>
      <tr>
        <td><b>Website title:</b></td>
        <td><input type="text" name="title" maxlength="50"></td>
      </tr>
      <tr>
        <td><b>Website URL:<b></td>
        <td><input type="text" name="url" maxlength="100" value="http://" size="25">
  				 i.e.,http://mysite.com</td>
      </tr>
      <tr>
        <td><b>Your Link Page Back To Us:</b></td>
        <td><input type="text" name="recurl" maxlength="100" value="http://" size="25">
  				i.e.,http://mysite.com/link.htm</td>
      </tr>
  		<tr>
  			<td><b>Website description: Max. 200</b>
  			<td><textarea name="desc" style="height:40px"></textarea>
      </tr>
  		<tr>
  			<td colspan=2>
					<b>I want to join 
						<a href="spreadtheworld.php" target="_blank">link mail exchange program.</a></b>
    			<input type="radio" name="lnkxmail" value="yes" checked>Yes
					<b><a href="spreadtheworld.php" target="_blank">Why?</a>
    			<input type="radio" name="lnkxmail" value="no" >No
				
      </tr>

  		<tr>
  			<td colspan=2>
  			 <input style="width:400px;height:30px;background-color:#cccccc;border:1px outset #666666;"
  				type="submit" value="Add Link" name="add">
  		</tr>
    </table>
  </form>
  
</div>

<b>CityPost Friend Sites</b> 
<hr>
<?
 require_once("lnkx_functions.php");
 displink();
?>
<?include "_footer.inc" ?>