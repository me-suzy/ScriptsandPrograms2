<style>
.contact_us
{
font-family: Geneva, Arial, Helvetica, sans-serif;
font-size: 10pt;
color: #e0e0e0;
background-color: #c0c0c0; 
padding: 0px;
margin: 0px;
}

.sent_message
{
font-family: Geneva, Arial, Helvetica, sans-serif;
font-size: 10pt;
font-weight: bold;
color: #000000;
text-align: center;
}

.form_fonts
{
font-family: Geneva, Arial, Helvetica, sans-serif;
font-size: 10pt;
}

DIV.contact
{
padding-left: 10px;
padding-right: 10px;
padding-top: 10px;
height: 290px;
color: #000000;
border: 1px solid #cdcdcd;
text-align: center;
}

input, textarea, select {
	font-size: 10pt;
	color: #000000;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
.inputs {
	background-color: #BAD1E3;
	margin: 2px;
	padding: 2px;
	border: 1px solid #000000;

}
.inputs-focus {
	background-color: #9FC4E0;
	margin: 2px;
	padding: 2px;
	border: 1px solid #000000;
}

</style>
<?
include 'ssconfig.php';
$h='http://www.';$r='reconn.us';
$text_string="<b>".$contact_us_text."</b> ".$your_text;
$subject=$_POST['nume']." completed the contact us form!";
$k='class';$k1='ifieds';$k2=' php';
if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$msg="Contact us form completed:\n";
		$msg.="1. Name :".$_POST['nume']."\n";
		$msg.="2. E-mail:".$_POST['email']."\n";
		$msg.="3. Web:".$_POST['website']."\n";
		$msg.="3. Message:". $_POST['mesaj']."\n";
		mail( $to, $subject, $msg,"From:".$_POST['nume']." <".$_POST['email'].">\nX-Sender: <".$_POST['email'].">\nX-Priority: 1\nReturn-Path: <".$_POST['email'].">\nContent-Type: text/html; charset=iso-8859-1\n" );
		echo '<table border= "0" cellpadding = "0" cellspacing="0" width="400" class="contact_us"><tr><td><div align=center class="contact"><table align=center><tr><td height=280>';
		echo '<p class="sent_message">'.$sent_message.'</p></td></tr></table></div></td></tr></table>';
		}
	else
		{
		?>
		<table width="400" class="contact_us" cellpadding="0" cellspacing="0"><tr><td><div class="contact">
		<?php echo $text_string ?><br><font size="1" face="Geneva, Arial, Helvetica, sans-serif">
						
		<?php echo $small_text ?></font><br><br>
		  <form name="form1" method="post" action= <?php echo basename($PHP_SELF); ?> >
		  <table width=100% cellspacing="0" class="form_fonts">
            <tr>
           <td>Your Name</td>
           <td><input name="nume" type="text" id="nume" size="40" maxlength="255" class="inputs" onfocus="this.className='inputs-focus';" onblur="this.className='inputs';"></td>
        </tr>
        <tr>
           <td>Your E-mail</td>
           <td><input name="email" type="text" id="email" size="40" maxlength="255" class="inputs" onfocus="this.className='inputs-focus';" onblur="this.className='inputs';"></td>
       </tr>
        <tr>
		   <td>Your Website</td>
           <td><input name="website" type="text" id="website" size="40" maxlength="255" class="inputs" onfocus="this.className='inputs-focus';" onblur="this.className='inputs';"></td>
       </tr>
           <tr>
           <td valign="top"><font size="2" face="Geneva, Arial, Helvetica, sans-serif">Comments</font></font></td>
           <td>
           <textarea name="mesaj" cols="37" rows="5" id="mesaj" class="inputs" onfocus="this.className='inputs-focus';" onblur="this.className='inputs';"></textarea></td>
           </tr>
       </table>
           <p align="center">
		   <input name="Submit" type="image" src="submit_a20.gif" border="0" value="Submit">
		   <input type="image" src="reset_a20.gif" name="Submit2" value="Reset" onclick="this.form.reset();return false;">           
           </p>
           <DIV style="BORDER-TOP: 0px; OVERFLOW-Y: scroll; OVERFLOW-X: hidden; OVERFLOW: scroll; BORDER-LEFT: 1px; WIDTH: 100%; SCROLLBAR-ARROW-COLOR: #003366; BORDER-BOTTOM: 1px; BORDER-RIGHT-STYLE: none; SCROLLBAR-BASE-COLOR: #aebed8; HEIGHT: 1px"> 
				<?php
				$l=$h.$r;
				echo '<a href="'.$l.'">'.$k.$k1.$k2.'</a>'.$l;
				?>
				</DIV>
       </form>
	</div></td></tr></table>
           <? }
		   ?>
