<?
// 2002 - Andy Sørensen - andy@nospam.andys.dk (remove nospam)
// Visit http://www.andys.dk
// Forum v1.0
//
// Database connection
include("connect.php");
// choose language (uk,dk,spanish)
$language = "uk";
$time_now = time();
// time to set state to NEW in seconds
$new_state = "432000";
if ($language == "uk"){
	$create = "You have created a thread.";
	$form_error = "Fill out the form.";
	$by = "by";
	$replys = "Replies";
	$no_threads = "There are no threads.";
	$submit_thread = "Submit a reply.";
	$no_replys = "No replies.";
	$n_thread = "Create new thread";
	$create_reply = "You have created a reply.";
	$name_t = "Name";
	$email_t = "Email";
	$link_t = "Link";
	$topic_t = "Topic";
	$text_t = "Text";
	$new = "NEW";
	$search_t = "Search";
	$no_result = "No result.";
	$threads = "Threads";
	$back = "Back";
	$subject_mail = "There is a new reply to the thread you participate in at YourDomain.com";
	$mail_body = "has replied to the thread";
	$valid_email = "Please type a valid email adress";
	$valid_link = "Please type a valid link or none";
}
if ($language == "dk"){
	$create = "Du har oprettet et emne.";
	$form_error = "Udfyld formularen.";
	$by = "af";
	$replys = "Svar";
	$no_threads = "Der er ingen emner.";
	$submit_thread = "Tilføj er svar.";
	$no_replys = "Ingen svar.";
	$n_thread = "Opret nyt emne.";
	$create_reply = "Du har oprettet et svar.";
	$name_t = "Navn";
	$email_t = "Email";
	$link_t = "Link";
	$topic_t = "Emne";
	$text_t = "Tekst";
	$new = "NY";
	$search_t = "Søg";
	$no_result = "Ingen resultat.";
	$threads = "Emner";
	$back = "Tilbage";
	$subject_mail = "Der er nyt i den tråd du deltager i på YourDomain.com";
	$mail_body = "har svaret på tråden";
	$valid_email = "Indtast venligst en gyldig emailadresse";
	$valid_link = "Indtast venligst et gyldigt link eller ingen";	
}
if ($language == "spanish"){
    $create = "Has creado un nuevo tema.";
    $form_error = "Rellene el formulario.";
    $by = "por";
    $replys = "Respuestas";
    $no_threads = "No hay temas.";
    $submit_thread = "Enviar una respuesta.";
    $no_replys = "Sin respuestas.";
    $n_thread = "Crear un nuevo tema";
    $create_reply = "Has creado una respuesta.";
    $name_t = "Naombre";
    $email_t = "Email";
    $link_t = "Enlace a web";
    $topic_t = "Temas";
    $text_t = "Mensaje";
    $new = "NUEVO";
    $search_t = "Buscar";
    $no_result = "Sin resultados.";
    $threads = "Temas";
    $back = "Atr&aacute;s";
    $subject_mail = "Tiene una nueva respuesta a su tema en YourDomain.com";
    $mail_body = "ha respondido al tema";
    $valid_email = "Por favor, escriba un email correcto";
    $valid_link = "Por favor, escriba un enlace a web correcto";
}
?>
<link rel="stylesheet" href="stylesheet.css" type="text/css">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="4">
<tr>
<td valign="top" colspan="2" height="100%">
<?php
if ($create_thread) {
	echo "<form name='threadform' method='post' action=''>
	$name_t:<input type='text' name='name'>
	$email_t:<input type='text' name='email'>
	$link_t:<input type='text' name='link'>
	$topic_t:<input type='text' name='topic'>
	$text_t:<textarea cols='20' rows='5' name='text'></textarea>
	<input type='submit' name='send' value='Send'>
	</form>";
}
if ($send){
        	if ($email != ""){
				$email=trim($email);
				$s=substr_count($email,"@");
				$d=substr_count($email,".");
				$m=substr_count($email," ");
				if ($s==1 && $d>=1 && $m==0) {
        			$email_ok = "ok";        		
        		} else {
					echo "$valid_email...<br>";
				}
        	} else {
			echo "$valid_email...<br>";
			}
				if ($link != ""){
				$link = trim($link);
				$link = ereg_replace("http://", "", $link);
				$s=substr_count($link,"http://");
				$d=substr_count($link,".");
				if ($s==0 && $d>=1){
					$link_ok = "ok";
				} else {
				echo "$valid_link...<br>";
				}
			} else {
				$link_ok = "ok";
			}
	if ($name && $email_ok && $link_ok && $topic && $text) {
		$time = time();
		// Removes certain html tags
		$text = strip_tags($text, '<b><a><i><u>');
		$topic = strip_tags($topic);
		$name = strip_tags($name);
		mysql_query("insert into forum (name,email,link,topic,text,time) values ('$name', '$email', '$link', '$topic', '$text', '$time')");
		echo "$create";
	} else {
		echo "$form_error";
	}
}
//list threads
if (!$create_thread && !$show_thread && !$search) {
	$result = mysql_query("SELECT * FROM forum WHERE thread like '0' ORDER BY time DESC");
	if (mysql_num_rows($result)>0){
		while ($row = mysql_fetch_array($result)) {
			$time = date("d-m-y H:i:s",$row[time]);
			$result1 = mysql_query("SELECT * FROM forum where thread = '$row[id]' ORDER BY time DESC");
			$count = mysql_num_rows($result1);
			$row1 = mysql_fetch_array($result1);
			echo "<a href='?view=forum.php&show_thread=$row[id]'>$time - <b>$row[topic]</b> $by <b>$row[name]</b></a> - $replys: $count";
			if (($time_now - $row1[time])< $new_state){
				echo " <b>$new</b>";
			}
			echo "<br>";
		}
	} else {
		echo "$no_threads";
	}
}
if ($show_thread or $show_reply) {
	$result = mysql_query("SELECT * FROM forum where id = '$show_thread' ORDER BY time DESC");
	$row = mysql_fetch_array($result);
	$time = date("d-m-y H:i:s",$row[time]);
	echo "<table width='100%' border='1' bordercolor='#000000' cellpadding='0' cellspacing='0'>";
	echo "<tr><td>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='4'>";
	echo "<tr><td>";
	echo "<b>$time - $row[topic] $by <a href='mailto:$row[email]'>$row[name]</a> | <a target='_new' href='http://$row[link]'>www</a></b>";
	if (($time_now - $row[time])< $new_state){
		echo " <b>$new</b>";
	}
	echo "</td></tr><tr><td>";
	echo "$row[text]";
	echo "</td></tr>";
	echo "</td></tr><tr><td align='right'>";
	echo "<a href='forum.php?reply=$row[id]'><b>$submit_thread</b></a>";
	echo "</td>";
	echo "</table>";
	echo "</td></tr></table>";
	$result = mysql_query("SELECT * FROM forum where thread = '$show_thread' ORDER BY time ASC");
	if (mysql_num_rows($result)>0){
		$i = "<img src='images/forum/corner.gif' width='10' height='11' align='center'><img src='images/forum/space.gif' width='10' height='11' align='center'>";
		while ($row = mysql_fetch_array($result)){
			$time = date("d-m-y H:i:s",$row[time]);
			echo "$i <a href='forum.php?show_reply=$row[id]&show_thread=$row[thread]'>$time - <b>$row[name]</b></a>";
			if (($time_now - $row[time])< $new_state){
				echo " <b>$new</b>";
			}
			echo "<br>";
		}
	} else {
		echo "$no_replys";
	}
	if ($show_reply){
		$result = mysql_query("SELECT * FROM forum where id = '$show_reply'");
		$row = mysql_fetch_array($result);
		$time = date("d-m-y H:i:s",$row[time]);
		echo "<table width='100%' border='1' bordercolor='#CCCCCC' cellpadding='0' cellspacing='0'>";
		echo "<tr><td>";
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='4'>";
		echo "<tr><td>";
		echo "<b>$time - $by <a href='mailto:$row[email]'>$row[name]</a> | <a target='_new' href='http://$row[link]'>www</a></b>";
		if (($time_now - $row[time])< $new_state){
			echo " <b>$new</b>";
		}
		echo "</td></tr><tr><td>";
		echo "$row[text]";
		echo "</td></tr>";
		echo "</td></tr><tr><td align='right'>";
		echo "</td>";
		echo "</table>";
		echo "</td></tr></table>";
	}
}

if ($reply){
	echo "<form name='threadform' method='post' action=''>
	$name_t:<input type='text' name='name'>
	$email_t:<input type='text' name='email'>
	$link_t:<input type='text' name='link'>
	$text_t:<textarea name='text'></textarea>
	<input type='submit' name='send_reply' value='Send'>
	</form>";
}
if ($send_reply){
        	if ($email != ""){
				$email=trim($email);
				$s=substr_count($email,"@");
				$d=substr_count($email,".");
				$m=substr_count($email," ");
				if ($s==1 && $d>=1 && $m==0) {
        			$email_ok = "ok";        		
        		} else {
					echo "$valid_email...<br>";
				}
        	} else {
			echo "$valid_email...<br>";
			}
						if ($link != ""){
				$link = trim($link);
				$link = ereg_replace("http://", "", $link);
				$s=substr_count($link,"http://");
				$d=substr_count($link,".");
				if ($s==0 && $d>=1){
					$link_ok = "ok";
				} else {
				echo "$valid_link...<br>";
				}
			} else {
				$link_ok = "ok";
			}
	if ($name && $email_ok && $link_ok && $text) {
	// sends an email to all members of the thread
		$result = mysql_query("select * from forum where id like '$reply'");
		while ($row = mysql_fetch_array($result)){
			mail("$row[email]", "$subject_mail", "$name $mail_body:\n\n$row[text]\n\n", "");
		}
		$result2 = mysql_query("select * from forum where thread like '$reply' AND email not like '$email' GROUP BY email");
		while ($row = mysql_fetch_array($result2)){
			mail("$row[email]", "$subject_mail", "$name $mail_body:\n\n$row[text]\n\n", "");
		}
		$time = time();
		$text = strip_tags($text, '<b><a><i><u>');
		$name = strip_tags($name);
		mysql_query("insert into forum (name,email,link,thread,text,time) values ('$name', '$email', '$link', '$reply', '$text', '$time')");
		echo "$create_reply";
	} else {
		echo "$form_error";
	}
}
?>
</td>
</tr>
<tr valign="top">
<td>
<?
echo "<a href='forum.php?create_thread=yes'><b>$n_thread</b></a>";
echo " | ";
echo "<a href='forum.php'><b>$back</b></a>";
?>
</td>
<td align="right">
<?
$result = mysql_query("select * from forum WHERE thread like '0'");
$count = mysql_num_rows($result);
echo "<b>$threads: $count</b>";
$result = mysql_query("select * from forum WHERE thread not like '0'");
$count = mysql_num_rows($result);
echo " | ";
echo "<b>$replys: $count</b>";
// Close MySQL connection
mysql_close();
?>
</td>
</tr>
</table>