<?php
/*
 
  .: EasyNews 1.7 by Pierino :.
|===============================|
| http://www.code4fun.org       |
| mail01: info@code4fun.org     |
| mail02: sanculamo@hotmail.com |
|===============================|

*/

//----------------------------
/* START OR REPLACE SESSION */
session_start();

//-----------------------------------------
/* INCLUDE AND SETTING UP SOME VARIABLES */
include 'config.php';
// emoticons local path
$emoPath='emoticons/';
// include some useful functions...
include 'includes/functions.php';

// 1:visualizza il form per l'inserimento delle news
$visualizzaFormInserimento=0;
// 1:visualizza il form per editare le news
$visualizzaFormEdit=0;
// 1:visualizza lista news presenti nel db
$visualizzaNewsList=1;
// inizializzazione messaggio "variabile" mostrato nell'header
$msg='';

//------------------------------
/* ADMIN RESTRICTED AREA TEST */
//------------------------------------------------------------------------------
// update session variables with "user name" and "password"
if ( isset($_POST['id']) && isset($_POST['pw']) ) {
     $_SESSION['userid']=$_POST['id']; $_SESSION['password']=$_POST['pw'];
}
// check "user name" and "password"
if ($_SESSION['userid']==$id && $_SESSION['password']==$pw ) {

// Il ciclo while "fantoccio" esterno al "case" è utile per far saltare il case
// stesso nel caso fallisse la connessione o non fosse presente la variabile
// $_POST['action'] o $_GET['action']
do {

//------------------------------------------------------------------------------
/************** CONNESSIONE E OPERAZIONI PRELIMINARI DATABASE *****************/
//------------------------------------------------------------------------------

if (!$conn = connect($db_host,$db_user,$db_pw,$db_name,$table_name)) break;

//-----------------------------------
/* CHECK $_POST and $_GET variable */
//------------------------------------------------------------------------------
if (isset($_POST['action'])) $action = $_POST['action'];
else if (isset($_GET['action'])) $action = $_GET['action'];
else break;

//------------------------------------------------------------------------------
/************************** START CASE STRUCTURE ******************************/
//------------------------------------------------------------------------------

switch ($action) {

/*******************************************************************************
***                         delete single news                               ***
*******************************************************************************/
case 'delete':

    if (isset($_POST['newsid']) && $_POST['newsid']!='' && is_numeric($_POST['newsid'])) {

		$newsid = $_POST['newsid'];
	    $query = 'DELETE FROM `'.$table_name.'` WHERE newstime=\''.$newsid.'\'';
	     if ($result = mysql_query($query,$conn)) {

						           // news deleted from database
           						   $msg='.:. News removed.';
						           // delete image from file system
						           deleteImg($newsid.'.jpg');
	     }
     	 // error during delete query
	     else $msg='.:. Delete failure:<br />'. mysql_error();
	}
break;

/*******************************************************************************
***                           delete all news                                ***
*******************************************************************************/
case 'deleteall':

    if ($_GET['confirm']) {

        $query = 'DELETE FROM `'.$table_name.'` WHERE 1';
        if ($result = mysql_query($query,$conn)) {
            // all news deleted from database
            $msg='.:. All news deleted successfully.';
            // empty images dir
            purge('images/');
        }
        else $msg='.:. Delete fail:<br />'. mysql_error();
    }
    else { $msg='You\'re about to delete all news in database:<a href="'.$_SERVER['SCRIPT_NAME'].'?action=deleteall&amp;confirm=true"> go on</a>.';
           $visualizzaNewsList=0;
    }
break;

/*******************************************************************************
***                            submit news                                   ***
*******************************************************************************/
case 'insert':

           // validate submitted fields
           if ( isset($_POST['text']) && trim($_POST['text']!='')
		   		&& (strlen($text = bb2html(trim($_POST['text']))))<65500 ) {

               //********              RACCOLTA DATI                 ***********

               // news ID (timestamp)
               $newsid = $_POST['time'];
               // title
               $newstitle = trim($_POST['title']);
               $newstitle = nl2br(htmlspecialchars($newstitle));
               // image position
               $align=$_POST['imgposition'];
               //image link
               if (isset($_POST['url']) && $_POST['url']!='http://') $linkImg=$_POST['url']; else $linkImg='';

               //*******       INSERIMENTO DATABASE E UPLOAD FILE      *********

               // insertion query
               if (!insertText($conn, $table_name, $newsid, $text, $newstitle )) break;
               // file upoload routine
               if (!uploadFile($conn, $newsid, $table_name, $linkImg, $align)) break;
           }
           else {
                   $msg = '.:. Submit news, image must be '.$maxSize.'K max jpg file.';
                   // show submitting form
                   $visualizzaFormInserimento=1;
                   // hide news list
                   $visualizzaNewsList=0;
           }
break;

/*******************************************************************************
***                 delete img from db and file system                       ***
*******************************************************************************/
    
case 'delimg':

     if (isset($_POST['newsid'])) {

				 $newsid = $_POST['newsid'];
                 // aggiorna il database cancellando il record dell'immagine
                 updateImg($conn, $newsid, $table_name , 'del' , 'del');
                 // delete image from file system
                 deleteImg($newsid.'.jpg');

     			 $visualizzaFormEdit=1;
			     $visualizzaNewsList=0;
     }
break;

/*******************************************************************************
***                         edit news (update)                               ***
*******************************************************************************/
case 'edit':


    if (isset($_POST['newsid']) && isset($_POST['text']) && $_POST['text']!=''){

        //********                RACCOLTA DATI                 ****************
		//********          INSERIMENTO DATI EDITATI            ****************

        // news id
        $newsid = $_POST['newsid'];
		// title
		if (isset($_POST['title']) && $_POST['title']!='') {
			$newstitle = trim($_POST['title']);
        	$newstitle = nl2br(htmlspecialchars($newstitle));
        }
        // text
        $text = trim($_POST['text']);
        $text = bb2html( $text );
        // image position
        $align=$_POST['imgposition'];
        // image link
        $linkImg=$_POST['url'];

        //*******       INSERIMENTO DATABASE E UPLOAD FILE      ****************
        // update text row
        if (!updateText($conn, $table_name, $newsid, $text, $newstitle )) break;
        // unlink image?
        if ($_POST['delink']=='on') $linkImg='del';
        // upload image
        uploadFile($conn, $newsid, $table_name, $linkImg, $align);
    }
    else {

		//**********              PRINT EDIT FORM                ***************

        if (isset($_POST['newsid']) && $_POST['newsid']!='' && is_numeric($_POST['newsid']) ) {

		    	$newsid = (int)$_POST['newsid'];
            	$msg = 'Edit text and/or upload image [max '.$maxSize.'K, jpg file].';
            	$visualizzaFormEdit=1; $visualizzaNewsList=0;
        }
        else {

		        $msg = 'Fatal error...';
                $visualizzaFormEdit=0; $visualizzaNewsList=1;
        }
    }
break;

/*******************************************************************************
***                               about                                      ***
*******************************************************************************/
case 'about':

    $msg = '<img src="icons/logo.gif" alt="logo" width="140" height="89" style="float: left; padding: 10px;" />
			<div style="padding: 20px 10px 20px 10px;">
			   EasyNews is written by Pierino.<br /><br />
		       Visit mY hoMe paGe: <a href="http://www.code4fun.org">www.code4fun.org</a><br /><br />
        	   ConTact mE : <a href="mailto:info@code4fun.org">info@code4fun.org</a>
   			</div>';

    $visualizzaNewsList=0;

break;


} //-----------------------------< END CASE >-----------------------------------

} while(0); // -------------< END DUMMY WHILE LOOP >----------------------------

//------------------------------------------------------------------------------
/***************************      XHTML OUTPUT       **************************/
//------------------------------------------------------------------------------

// print header xhtml
top();
// print menu
print_header($msg);
// print news submit form
if ($visualizzaFormInserimento) printFormNews();
// print edit form
if ($visualizzaFormEdit) { printFormEdit($conn, $table_name, $newsid); }
// se la connessione al db è riuscita e non ci sono stati errori
// durante le query successive visualizza le news presenti nel db
// e chiudi la connessione
if ($conn && $visualizzaNewsList) {

        // stampa l'elenco delle news se presenti
        print_news($conn, $table_name);

        // chiusura della connessione
        mysql_close($conn);
}

// print footer
foot();

} //-----------------< end if check restricted area >---------------------------

//-------------------------------------------------------------------
/* if check user name and password fail print restricted area form */
else {restrictedArea();}




/**************************  F U N C T I O N S  *******************************/


/*******************************************************************************
**             Visualizza Form per accesso alla Restricted Area               **
*******************************************************************************/
function connect($db_host,$db_user,$db_pw,$db_name,$table_name) {

         global $msg;

// connessione al DBMS
if (!$conn = @mysql_connect($db_host, $db_user, $db_pw)) {

     $msg = 'Error during connection, check connection parameters in "config.php" file: ' . mysql_error();
     return $conn;
}

// crea il db se non esiste
$query = 'CREATE DATABASE IF NOT EXISTS `'.$db_name.'`';
if (!$result = @mysql_query($query,$conn)) {

     $msg = 'Error during database creation: ' . mysql_error();
     $conn=0;
     return $conn;
}

// selezione del db
if (!$result= @mysql_select_db($db_name,$conn)) {

     $msg = 'Error during database selection: ' . mysql_error();
     $conn=0;
     return $conn;
}

// la connessione è riuscita ed il database è stato già inizializzato in questa sessione
// evito di controllare nella stessa sessione se l atabella esiste già:
if (isset($_SESSION['db']) && $_SESSION['db']=='ok' ) return $conn;

// se la tabella non esiste la crea
if(!@mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table_name."'"))==1) {

          // Crea la tabella $table_name
          $query = "CREATE TABLE `$table_name` (
                    `newstime` INT DEFAULT '0' NOT NULL ,
                    `newstext` TEXT NOT NULL,
                    `newsimg` VARCHAR(15) DEFAULT '',
                    `align` ENUM( 'left', 'right' ) DEFAULT 'left' NOT NULL,
                    `link` VARCHAR(150) DEFAULT '',
                    `newstitle` VARCHAR(100) DEFAULT '',
                    PRIMARY KEY (`newstime`)
                    ) TYPE=MyISAM;";

          if (!$result = @mysql_query($query,$conn)) {

               $msg = 'Table creation fail: ' . mysql_error();
               $conn=0;
               return $conn;
          }

}
// il db è stato correttamente inizializzato:
$_SESSION['db']='ok';


return $conn;
}


/*******************************************************************************
**                        STAMPA INTESTAZIONE E MENU                          **
*******************************************************************************/
function print_header($msg) {

                // Nome del file corrente
                $currentFile= $_SERVER['SCRIPT_NAME'];

                echo '
                <div id="header">
                <!-- logo -->
                <div id="logo">
				  <a href="http://www.code4fun.org" target="_blank">EasyNews</a>
                  <div class="mini-text"> [ v.1.7 ] </div>
				</div>

                <!-- Menu -->
                <div id="menu">.: menu:&nbsp;&nbsp;
                    <a href="'.$currentFile.'">list</a>&nbsp;|&nbsp;
                    <a href="'.$currentFile.'?action=insert">add news</a>&nbsp;|&nbsp;
                    <a href="'.$currentFile.'?action=deleteall">delete all</a>&nbsp;|&nbsp;
                    <a href="preview.php" target="_blank">preview</a>&nbsp;|&nbsp;
					<a href="'.$currentFile.'?action=about">?</a>
			    </div>';
                if (isset($msg) && $msg!='') print '<div class="servicemsg">'.$msg.'</div>';
                print '</div>';
}


/*******************************************************************************
**                           VISUALIZZA NEWS                                  **
********************************************************************************
** $conn: handle di connessione al db restituito da una chiamata mysql_connect()
** $table_name: tabella in cui sono memorizzate le news
*******************************************************************************/
function print_news($conn, $table_name) {

		global $emoticons;

        // news per page
		$numNews=5; 

		// check page variable
		if ( !isset($_GET['page']) || $_GET['page']<1 ) $page = 1;
		else $page = $_GET['page'];

		// get news number
		$query = 'SELECT * FROM `'.$table_name.'`';
		$result = @mysql_query($query,$conn) or die ('Error reading news from database, check config.php file and run setup.php first:<br />' . mysql_error() );

		if ($num_rows = @mysql_num_rows($result)) {

			// calculate how many pages
			$pages = intval($num_rows/$numNews);
			if ($num_rows%$numNews) $pages++;
		
			// check if someone insert manually a wrong page
			if ($page > $pages) $page = 1;
		
			// start news
			$recordStart=($page*$numNews)-$numNews;

			// send the query
	        $query = 'SELECT newstime,newstext,newsimg,newstitle FROM `'.$table_name.'` ORDER BY newstime DESC LIMIT '.$recordStart.','.$numNews;
	        $result = @mysql_query($query,$conn) or die(mysql_error());

                  // tabella che contiene le news...
                  echo '<p></p>
                        <table class="tablenews">
                        <tr><td class="title" colspan="5">News list:</td></tr>
                        <tr><td width="80" class="keyc">Date</td>
                            <td class="keyc">Text (and title)</td>
                            <td class="keyc" width="40">Edit</td>
                            <td class="keyc" width="40">Img</td>
                            <td class="keyc" width="50">Delete</td>
                        </tr>';

                  $i=0; // counter used to alternate roe background color
                  // estraggo le news...
				  while ($riga = @mysql_fetch_row($result)) {

                    // check if current news is in full-story mode ore not
                    if (isset($_GET['id']) && $riga[0]==$_GET['id']) $numCh=0;
                    else  {$numCh=160; $fullStoryLink='<a style="text-decoration: none;" href="'.$_SERVER['SCRIPT_NAME'].'?id='.$riga[0].'&amp;page='.$page.'">'; }

	                // le varie righe
	                $data = date('d M y' , $riga[0]);
                    $text = stringCutter ($riga[1], $numCh, $fullStoryLink);
					$text = doReplace($text , $emoticons['char'], $emoticons['icon'] );

					if ($riga[2]==NULL) $imgField='&nbsp;'; else $imgField='<a href="javascript:CaricaFoto(\'images/'.$riga[2].'\')"><img src="icons/preview.gif" border="0" width="25" height="25"></a>';
        	        if (isset($riga[3]) && $riga[3]!='') $title = '<strong>'.$riga[3].'</strong><br />'; else $title='';

	                if ( $i&1 ) { $bgcolor='#F5F5F5'; } // righe dispari
        	        else $bgcolor='#DCE0E3'; // righe pari

                  	echo '<tr><td bgcolor="'.$bgcolor.'" class="icone">'.$data.'</td>
                              <td bgcolor="'.$bgcolor.'" class="testo">'.$title.$text.'</td>
							<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
							<td bgcolor="'.$bgcolor.'" class="icone">
                                <input type="hidden" name="action" value="edit" />
                                <input type="hidden" name="newsid" value="'.$riga[0].'" />
                                <input type="image" src="icons/edit.gif" border="0" width="25" height="25" name="submit" alt="edit" />
							</td>
							</form>
                            <td bgcolor="'.$bgcolor.'" class="icone">'.$imgField.'</td>
                            <form action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
							<td bgcolor="'.$bgcolor.'" class="icone">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="newsid" value="'.$riga[0].'" />
                                <input type="image" src="icons/delete.gif" border="0" width="25" height="25" name="submit" alt="delete" />
							</td>
							</form>
                        </tr>';

				 		$i++;
                  }
                  	// html table footer and paginator
         			print '<tr><td class="keyc" colspan="5">';
		  		 	if ($page>1) print '<a href="'.$_SERVER['SCRIPT_NAME'].'?page='.($page-1).'">&lt;</a> ';
	        		print ' Page ['.$page.'/'.$pages.']';
		 			if ($page<$pages) print ' <a href="'.$_SERVER['SCRIPT_NAME'].'?page='.($page+1).'">&gt;</a>';
		 			print '</td></tr>';
                  	// chiusura tabella
                  	print '</table><br />';
         }
         else echo '<div id="emptymsg">..: News table is empty :..</div>';

}


/*******************************************************************************
**                   VISUALIZZA FORM PER INSERIMENTO NEWS                     **
*******************************************************************************/
function printFormNews() {

         global $emoticons,$maxSize;

         $currentFile= $_SERVER['SCRIPT_NAME'];
         $currenttime=time();
         $data = date('d M y' , $currenttime);

         /* TEXT SECTION ******************************************************/
         echo '<p></p>
               <form enctype="multipart/form-data" name="news" action="'.$currentFile.'" method="post">
               <input type="hidden" name="MAX_FILE_SIZE" value="'.($maxSize*1000).'" />
               <input type="hidden" name="time" value="'.$currenttime.'" />
               <input type="hidden" name="action" value="insert" />

               <table class="tablenews">
               <tr><td class="title" colspan="2">Text setup</td></tr>
               <tr><td class="key" colspan="2">Date : '.$data.'</td></tr>

               <tr><td class="key">
		       Title:<br /><input type="text" name="title" size="59" maxlength="100" value="" /><br /><br />
               Text:<br /><textarea cols="59" rows="6" wrap="ON" name="text"></textarea><br />
         ';

         /* BBCODE SHORTCUT ***************************************************/
         printBBcodeShort();
             echo '</td><td width="50" class="upload">eMo<br /><br />';

         /* EMOTICONS *********************************************************/
         printEmoticons($emoticons);

         /* FILE UPLOAD SECTION ***********************************************/
         printFormUpload(0, $img=null);

         echo '<tr><td colspan="2" class="keyc"><input type="submit" value="&gt;&gt; POST &lt;&lt;" /></td></tr>
               </table></form>';
}


/*******************************************************************************
**                  Visualizza Form per editare una news                      **
*******************************************************************************/
function printFormEdit($conn, $table_name, $id) {

         global $emoticons;

         $query = 'SELECT newstext,newsimg,align,link,newstitle FROM `'.$table_name.'` WHERE newstime='.$id;
         $result = @mysql_query($query,$conn) or die(mysql_error());
         $row = mysql_fetch_row($result);
         $data = date('d M y' , $id);

         // text
         $text = $row[0];
         // text bbcode reverse
         html2bb($text);
         // image
         $currentImage=$row[1];
         // image align
         $align=$row[2];
         // image link
         $hyperlink=$row[3];
         // title
         $title=$row[4];

         echo '<p></p>
               
               <table class="tablenews">
               <form enctype="multipart/form-data" name="news" action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
               <input name="action" type="hidden" value="edit" />
               <input name="newsid" type="hidden" value="'.$id.'" />
               <tr><td class="title" colspan="2">Text setup</td></tr>
               <tr><td class="key" colspan="2">Date : '.$data.'</td></tr>
               <tr><td class="key">
	           Title: <input type="text" name="title" size="58" maxlength="100" value="'.$title.'" /><br /><br />
               Text:<br /><textarea cols="48" rows="6" wrap="ON" name="text">'.$text.'</textarea><br />
         ';

         /* BBCODE SHORTCUT ***************************************************/
         printBBcodeShort();

         echo '</td><td width="60" class="upload">eMo<br /><br />';

         /* EMOTICONS *********************************************************/
         printEmoticons($emoticons);

		 echo '<tr><td colspan="2" class="keyc">
               <input type="submit" value="&gt;&gt; UPDATE &lt;&lt;" />
               </td></tr>';

	     /* FILE UPLOAD SECTION ***********************************************/
         printFormUpload( 1 , $currentImage, $hyperlink, $align );

         echo '</table>';
               
         @mysql_close($conn);
}


/*******************************************************************************
**            VISUALIZZA LA LISTA DELLE EMOTICONS DISPONIBILI                 **
*******************************************************************************/
function printEmoticons(&$matrice) {

         for ($i=0; $i<count($matrice['char']); $i++ ) {

         echo '<a href="javascript:SetEm_o(\''.$matrice['char'][$i].'\')">&nbsp;'.$matrice['icon'][$i].'</a>&nbsp;';
         if ( $i&1 ) print '<br />';
         }

}


/*******************************************************************************
**            VISUALIZZA LA LISTA DELLE SHORTCUT BBCODE                       **
*******************************************************************************/
function printBBcodeShort() {

        echo '<a href="javascript:SetEm_o(\'[b][/b]\')"><img src="icons/bold.gif" alt="bold" width="21" height="20" border="0" /></a>
              <a href="javascript:SetEm_o(\'[i][/i]\')"><img src="icons/italic.gif" alt="italic" width="21" height="20" border="0" /></a>
              <a href="javascript:SetEm_o(\'[u][/u]\')"><img src="icons/underline.gif" alt="underline" width="21" height="20" border="0" /></a>
              <a href="javascript:SetEm_o(\'[url=http://www.code4fun.org]link[/url]\')"><img src="icons/createlink.gif" width="21" height="20" alt="create link" border="0" /></a>
              <a href="javascript:SetEm_o(\'[br]\')"><img src="icons/break.gif" alt="break line" width="21" height="20" border="0" /></a>
             ';
}


/*******************************************************************************
**                     VISUALIZZA FORM UPLOAD IMMAGINE                        **
*******************************************************************************/
function printFormUpload($flag, $img , $hyperlink='' , $align='left') {

		 global $newsid;

         if ($hyperlink=='') $unlinkButton='&nbsp;';
         else $unlinkButton='<input name="delink" type="checkbox" value="on" />Unlink';

         if ($img=='') $img = '&nbsp;No image submitted for this news.';
         else $img = '<img style="padding: 10px; border: 0px;" src="images/'.$img.'" width="100" align="left" /><br />
     						<form action="'.$_SERVER['SCRIPT_NAME'].'" method="post">
                                  <input type="hidden" name="action" value="delimg" />
                                  <input type="hidden" name="newsid" value="'.$newsid.'" />
                                  <input type="image" src="icons/delete.gif" border="0" width="25" height="25" name="submit" alt="delete image" />
                                  Remove image from news.
							 </form>
					  <p><a href="javascript:CaricaFoto(\'images/'.$img.'\')"><img src="icons/preview.gif" width="25" height="25" alt="image preview" border="0" /></a> Image preview (real size).</p>';

         echo '<tr><td class="title2" colspan="2"><br />Image setup</td></tr>';

         echo '<tr><td class="upload" colspan="2">
                    <!-- Name of input element determines name in $_FILES array -->
                    .: image file :&nbsp;<input name="userfile" type="file" size="25" />
                    &nbsp;.: align :&nbsp;<select size="1" name="imgposition">';

         if ($align=='left') echo '<option value="left">Left</option><option value="right">Right</option>';
         else                echo '<option value="right">Right</option><option value="left">Left</option>';

         echo '</select><br />';

         // link immagine
         if ($flag && $hyperlink!='') $size=39;
         else $size=48;

         echo '<br />.: current image link : <input name="url" type="text" size="'.$size.'" value="'.$hyperlink.'" />&nbsp;&nbsp;'.$unlinkButton.'
               </td></tr>';
               
         // chiudo il form solo se siamo nel "submit panel"
         if ($flag) print '</form>';
               
         if ($flag) echo '<tr><td class="key" colspan="2">'.$img.'</td></tr>';
}


/*******************************************************************************
**                             html -> bbcode                                     **
*******************************************************************************/
function html2bb(&$html2bbtxt) {

        // let's remove all the linefeeds, unix
        $html2bbtxt = str_replace(chr(10), '', $html2bbtxt); // "\n"

        // and Mac (windoze uses both)
        $html2bbtxt = str_replace(chr(13), '', $html2bbtxt); // "\r"

        // 'ordinary' transformations
        $html2bbtxt = str_replace('<br />', '[br]', $html2bbtxt);
        $html2bbtxt = str_replace('<strong>', '[b]', $html2bbtxt);
        $html2bbtxt = str_replace('</strong>', '[/b]', $html2bbtxt);
        $html2bbtxt = str_replace('<em>', '[i]', $html2bbtxt);
        $html2bbtxt = str_replace('</em>', '[/i]', $html2bbtxt);
        $html2bbtxt = str_replace('<span style="text-decoration:underline">', '[u]', $html2bbtxt);
        $html2bbtxt = str_replace('</span>', '[/u]', $html2bbtxt);

        // more stuff
        $html2bbtxt = str_replace('<a href="','[url=', $html2bbtxt);
        $html2bbtxt = str_replace('</a>', '[/url]', $html2bbtxt);
        $html2bbtxt = str_replace('">', ']', $html2bbtxt);
        $html2bbtxt = preg_replace("/<\?(.*)\?>/i", "<b>script-kiddie prank: \\1</b>", $html2bbtxt);

        // you know what happens to the inventor of the database on Judgement Day?
        if (get_magic_quotes_gpc()) stripslashes($html2bbtxt);
}


/*******************************************************************************
**                            bbcode -> html                                  **
*******************************************************************************/
function bb2html($string){

$patterns = array(
                                    '`\[b\](.+?)\[/b\]`is',
                                    '`\[i\](.+?)\[/i\]`is',
                                    '`\[u\](.+?)\[/u\]`is',
                                    '`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si'
                  );

$replaces =  array(
                                    '<strong>\\1</strong>',
                                    '<em>\\1</em>',
                                    '<span style="text-decoration:underline">\\1</span>',
                                    '<a href="\1\2">\6</a>'
                   );

$stringMod = nl2br(htmlspecialchars($string));
$stringMod = str_replace('[br]', '<br />', $stringMod);
$stringMod = preg_replace($patterns, $replaces , $stringMod);

return $stringMod;

}


/*******************************************************************************
**                            Upload image file                               **
*******************************************************************************/
function uploadFile($conn,$newsid,$table_name,$linkImg,$align) {

         global $msg,$maxSize;

         if  ( $_FILES['userfile']['size']!=0 && $_FILES['userfile']['size']<($maxSize*1000)
             && ($_FILES['userfile']['type'] == 'image/jpg'
             || $_FILES['userfile']['type'] == 'image/jpeg'
             || $_FILES['userfile']['type'] == 'image/pjpeg') ) {

             $uploaddir = 'images/';
             $uploadfile = $uploaddir . $newsid . '.jpg';

                      if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

                          // upload riuscito
                          $msg.= ' Image successfully uploaded.';
                          // update image file name in database
                          if (updateImg($conn, $newsid, $table_name, $newsid.'.jpg' , $linkImg , $align )) return true;
                          else return false;

                      }
                      else {
                            $msg.= ' Error: possible file upload attack!';
                            return false;
                      }
         }
         else {
                 // image not submitted
                 if ($_FILES['userfile']['size']==0 && $_FILES['userfile']['name']=='') {
                         $msg .= ' Image not submitted.';
                         // update (in case) link and align
                         if (updateImg($conn, $newsid, $table_name, '' , $linkImg , $align )) return true;
                         else return false;
                 }
                 else $msg .= ' Image upload failure, wrong file.';
         }
}


/*******************************************************************************
**               Aggiorna il campo newsimg,link e align                       **
*******************************************************************************/
function updateImg($connId, $newsId, $table_name, $fileName , $linkImg , $align='left' ) {

 global $msg;

 // aggiorno i campi selettivamente
 $query = 'UPDATE `'.$table_name.'` SET align=\''.$align.'\'';

 if ($linkImg=='del') { $query.=', link=\'\''; $msg.=' Link deleted.';}
 else if ($linkImg!='') { validateUrl($linkImg); $query.=', link=\''.$linkImg.'\''; }

 if ($fileName=='del') $query.=', newsimg=\'\'';
 else if ($fileName!='') $query.=', newsimg=\''.$fileName.'\'';

 $query.=' WHERE newstime=\''.$newsId.'\'';

 if ($result = @mysql_query($query,$connId)) return true;
 else { $msg.=' Error during UPDATE: '.mysql_error(); return false; }

}


/*******************************************************************************
**                     Valida URL sprovvisti di http://                       **
*******************************************************************************/
function validateUrl(&$url) {

        $str1='www.';
        if (!strncmp($str1, $url, 4)) $url='http://'.$url;
        else return true;
}


/*******************************************************************************
**                     Inserisce newstime e newstext                          **
*******************************************************************************/
function insertText($connId, $table_name, $newstime, $text, $title) {

        global $msg;

        // insertion query
        $query = 'INSERT INTO `'.$table_name.'` (newstime,newstext,newstitle) VALUES (\''.$newstime.'\',\''.$text.'\',\''.$title.'\')';
        if ($result = mysql_query($query,$connId)) { $msg='.:. News added successfully.'; return true; }
        else { $msg='.:. Insertion failure:<br />'.mysql_error(); return false; }

}


/*******************************************************************************
**                     Aggiorna newstime e newstext                          **
*******************************************************************************/
function updateText($connId, $table_name, $newsId, $text, $title ) {

        global $msg;

        // update text field
        $query = 'UPDATE `'.$table_name.'` SET newstitle=\''.$title.'\' , newstext=\''.$text.'\' WHERE newstime=\''.$newsId.'\'';
        if ($result = mysql_query($query,$connId)) { $msg='.:. News updated.'; return true; }
        else { $msg='.:. News update failure:<br />'.mysql_error(); return false; }
}


/*******************************************************************************
**                        Delete Image from dir                               **
*******************************************************************************/
function deleteImg($fileName) {

         global $msg;

         if (file_exists('images/'.$fileName)) {

            if (unlink('images/'.$fileName)) $msg.=' Image removed.';
            else $msg.=' Image not removed.';

         }
         else $msg.=' Image not present.';
}


/*******************************************************************************
**                        Svuota una directory                                **
*******************************************************************************/
function purge($dir) {

  $handle = opendir($dir);
  while (false !== ($file = readdir($handle))) {

    if ($file != '.' && $file != '..')  {

      if (is_dir($dir.$file)) {

        purge ($dir.$file.'/');
        rmdir($dir.$file);

      }
      else { unlink($dir.$file); }

    }
  }
  closedir($handle);

}


/*******************************************************************************
**             Visualizza Form per accesso alla Restricted Area               **
*******************************************************************************/
function restrictedArea() {

         echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
               <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                 <title>EasyNews Restricted Area</title>
                 <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
                 <style type="text/css">
                 <!--
                 body {
                       background-color: #FFFFFF;
                       margin-top: 30px;
                       margin-right: 0px;
                       margin-bottom: 0px;
                       margin-left: 0px;
                       font-family: Verdana, Arial, Courier, Serif;
                 }

                 .container { margin:0px auto; text-align: center; width: 200px;}

                 .box {

                        background-color: #F0F0F0;
                        width: 200px;
                        text-align: center;
                        border-color: #000000;
                        border-style: solid;
                        border-width: 2px 2px 2px 2px;
                        padding: 10px 4px 4px 4px;
                        margin: 0px 0px 0px 0px;
                        font-family: Verdana, Arial, sans-serif;
                        font-size: 13px;
                 }
                 -->
                 </style>
                </head>
                <body>
                      <div align="center">
                      <div class="container">
                      <div class="box">
                      <p><strong>EasyNews</strong><br />Restricted Area</p>
                      <form name="restricted" action="'.$currentFile.'" method="post">
                      User Name: <input name="id" type="text" value="" /><br />
                      Password: <input name="pw" type="password" value="" />
                      <p><input type="submit" value="submit" /></p>
                      </form>
                      </div>
                      </div>
                      </div>
                </body>
                </html>
         ';
}


/*******************************************************************************
*****                           HEADER XHTML                               *****
*******************************************************************************/
function top() {

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
       <head>
        <title>EasyNews - www.code4fun.org</title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <style type="text/css">
        <!--
        body {margin-top: 30px; font-family: Arial, Verdana; color: #000000; font-size: 12px; padding: 0px;}
        .container { margin:0px auto; width: 600px;}
        A:LINK {text-decoration:none; color: #336699;}
        A:VISITED {text-decoration:none; color: #336699;}
        A:HOVER {text-decoration:none; color: #33CCFF;}
		#header { border: 2px solid #999999; padding: 0px; width: 596px; }
		#emptymsg { width: 576px; border: 2px solid #999999; padding: 10px; text-align: center; font-size: 12px;	font-family: Verdana,Arial; font-weight: bold; margin-top: 20px;}
		#logo { font-size: 26px; font-style: normal; background: #F0F0F0; text-align: center; padding: 10px;}
		#menu {font-size: 12px; font-family: Verdana,Arial; font-weight: normal; text-align:left; padding: 10px; border-top: 2px solid #999999; }
		.tablenews { width: 600px; border: 2px solid #999999; }
        .title  {border-style: solid; border-width: 0px 0px 2px 0px; background-color: #ffffff; font-size: 13px; font-family: Verdana,Arial; font-weight: bold; text-align: left;}
        .title2 {border-style: solid; border-width: 1px 0px 2px 0px; background-color: #ffffff; font-size: 13px; font-family: Verdana,Arial; font-weight: bold; text-align: left;}
        .key {background-color:#F0F0F0; font-size: 12px; font-family: Verdana,Arial; font-weight:normal; text-align: left; padding: 4px 4px 4px 6px; }
        .keyc {background-color:#F0F0F0; font-size: 12px; font-family: Verdana,Arial; font-weight:bold; text-align: center; padding: 4px 0px 4px 0px; }
        .upload {background-color: #E1EAEE; font-size: 12px; font-family: Verdana,Arial; text-align: center;  padding: 10px 8px 10px 8px; }
        .servicemsg { font-size: 12px; font-family: Verdana,Arial; font-weight: bold; text-align: left; padding: 10px; border-top: 1px dotted #999999;}
        .testo { font-size: 12px; font-family: Arial,Verdana; padding: 10px 5px 5px 5px; text-align: left; vertical-align: top;}
        .icone { font-size: 12px; font-family: Arial,Verdana; padding: 10px 0px 10px 0px; text-align: center; vertical-align: top;}
        .mini-text {font-size: 10px; font-family: Arial,Verdana; font-weight: bold; }
        -->
        </style>
        <SCRIPT type="text/javascript">
        <!--
        function SetEm_o(emo){document.news.text.value+=\'\'+emo; document.news.text.focus();}
        function CaricaFoto(img){
                 foto1= new Image();
                 foto1.src=(img);
                 Controlla(img);
        }
        function Controlla(img){
                 if((foto1.width!=0)&&(foto1.height!=0)){
                 viewFoto(img);
                 }
                 else{
                 funzione="Controlla(\'"+img+"\')";
                 intervallo=setTimeout(funzione,20);
                 }
        }
        function viewFoto(img){
                 largh=foto1.width+20;
                 altez=foto1.height+20;
                 stringa="width="+largh+",height="+altez;
                 finestra=window.open(img,"",stringa);
        }
        //-->
        </SCRIPT>
       </head>
        <body>
        <div class="container">
        ';
}


/*******************************************************************************
*****                           FOOTER XHTML                               *****
*******************************************************************************/
function foot() { echo '</div></body></html>'; }

?>
