<?php
/*

    .: EasyNews by Pierino :.
|===============================|
| http://www.code4fun.org       |
| mail01: info@code4fun.org     |
| mail02: sanculamo@hotmail.com |
|===============================|


*/

/************************ SETTING UP SOME VARIABLES ***************************/

// include configuration variables
   include 'config.php';

// images path
   $imgpath=$enPath.'images/';

// rectify $enPath
   validatePath($enPath);

// emoticons path
   $emoPath=$enPath.'emoticons/';
   
// check if this file is called for preview
   if ($easynewsPreview) { $imgpath='images/'; $emoPath='emoticons/'; }

// include some useful functions...
   include 'includes/functions.php';

/*********************** ESTABILISH DBMS CONNECTION ***************************/

// connection
$conn = @mysql_connect($db_host, $db_user, $db_pw) or die ('Error during DBMS connection:<br />' . mysql_error() );

// db selection
@mysql_select_db($db_name,$conn) or die ('Error during db selection:<br /> ' . mysql_error() );

/******************************* READ MORE ************************************/

if (isset($_GET['id'])) {

	$id = (int)$_GET['id'];
	$query = 'SELECT * FROM `'.$table_name.'` WHERE newstime='.$id.'';
	// send query and get affected num rows...
	$result = @mysql_query($query,$conn) or die ('Error reading news from database, check config.php file and run setup.php first:<br />' . mysql_error() );

	if ($num_rows = @mysql_num_rows($result)) $flagId=true;
	else $flagId=false;

}

/**************************** SHOW NEWS LIST **********************************/
// se $_GET['id'] non è settato o il $num_rows precedente è nullo (id hack)

if ( !isset($_GET['id']) || !$num_rows) {

	// check page variable
	if ( !isset($_GET['page']) || $_GET['page']<1 ) $page = 1;
	else $page = $_GET['page'];

	// get all news
	$query = 'SELECT * FROM `'.$table_name.'`';
	// send query and get affected num rows...
	$result = @mysql_query($query,$conn) or die ('Error reading news from database, check config.php file and run setup.php first:<br />' . mysql_error() );
	$num_rows = @mysql_num_rows($result);
	
		// calculate how many pages
		$pages = intval($num_rows/$newscount);
		if ($num_rows%$newscount) $pages++;

		// check if someone insert manually a wrong page
		if ($page > $pages) $page = 1;

		// select query
		$recordStart=($page*$newscount)-$newscount;
		$query = 'SELECT * FROM `'.$table_name.'` ORDER BY newstime DESC LIMIT '.$recordStart.','.$newscount;
		$result = @mysql_query($query,$conn) or die ('Error reading news from database, check config.php file and run setup.php first:<br />' . mysql_error() );
}

/********************* NEWS PRESENT IN DB: LET's SHOW *************************/

         if ($num_rows) {

            while (list($time, $text, $imageName, $align, $link, $title) = mysql_fetch_row($result)) {

               // define full-story link
               $fullStoryLink = '<a style="text-decoration: none;" href="'.$_SERVER['SCRIPT_NAME'].'?id='.$time.'&amp;page='.$page.'">';

               /****************************************************************
			   /*  DATE,TITLE AND TEXT HANDLING
			   ****************************************************************/

		       // date
		       $date='<span style="font-family: Verdana,Arial; font-size: '.$dateSize.'px; color: '.$dateColor.'; font-weight: '.$dateBoldness.'; padding: 2px;">['.date('d M y' , $time).']</span> :';
                       
               // if title exist push back to the date
		       if ($title!='') $date.=': <span style="font-family: Verdana,Arial; font-size: '.$titleSize.'px; color: '.$titleColor.'; font-weight: '.$titleBoldness.';">'.$title.'</span>';
		       
		       // cut the text
		       if (!$flagId && $charMax>0) $text = stringCutter($text, $charMax, $fullStoryLink);
		       // convert smiles to emoticons
               $text = doReplace($text , $emoticons['char'], $emoticons['icon'] );

               /****************************************************************
			   /*  IMAGE HANDLING
			   ****************************************************************/

               if ($imageName!='') {

                  // user submitted link
                  if ($link!='') $image = '<a href="'.$link.'">';

                  // image link to it self
                  else $image = '<a href="'.$imgpath.$imageName.'">';
                  
                  // image alignment
                  if ($align=='left') $padding = '2px 5px 0px 0px';
                  else $padding = '2px 0px 0px 5px';
                  $image.='<img style="float: '.$align.'; padding: '.$padding.'; border: 0;" src="'.$imgpath.$imageName.'" alt="news image" '.imgSize($imgpath.$imageName , ($imgRatio/100)).' /></a>';

			   }
			   else $image=''; // azzero la variabile per il prossimo ciclo

               /****************************************************************
			   /*  PRINT SINGLE NEWS
			   ****************************************************************/
			   // table 
        	   echo '<table style="width: '.$tableWidth.'px; border: 0; padding: 0; margin-bottom: '.$newsSpacer.'px;">

					 <tr><td style="border-width: '.$tableBorder.'px '.$tableBorder.'px 0 '.$tableBorder.'px; border-style: solid; border-color: '.$borderColor.'; text-align: '.$textAlign.'; padding: 0 2px 2px 2px; background-color: '.$dateBgColor.';">'.$date.'</td></tr>

					 <tr><td style="border-width: 0 '.$tableBorder.'px '.$tableBorder.'px '.$tableBorder.'px; border-style: solid; border-color: '.$borderColor.'; text-align: left; padding: '.$contentPadding.'px; background-color: '.$textBgColor.'; font-family: Arial,Verdana; font-size: '.$textSize.'px; color: '.$textColor.'; font-weight: '.$textBoldness.';">'.$image.$text.'</td></tr>';

               if ($flagId) {

				 	echo '<tr><td style="font-family: Verdana,Arial; font-size: '.$dateSize.'px; color: '.$dateColor.'; font-weight: '.$dateBoldness.'; padding: 2px; border-width: '.$tableBorder.'px; border-style: solid; border-color: '.$borderColor.'; background-color: '.$dateBgColor.';">
		 						<span style="float:left;"><em>posted on '.date('d M y' , $time).' @ '.date('H:i' , $time).'</em></span>
		 						<span style="float:right;"><a style="text-decoration: none;" href="'.$_SERVER['SCRIPT_NAME'].'?page='.$page.'">back to news</a></span>
					 	  </td></tr>';
			   }
			   print '</table>'."\n";

            } // end while loop

			if (!$flagId) {

				// html table footer and paginator
	         	echo '<table style="width: '.$tableWidth.'px; border: 0; padding: 0; margin: 0;">
				 	  <tr><td style="border: '.$tableBorder.'px solid '.$borderColor.'; text-align: center; font-family: Verdana; font-size: '.$textSize.'px; color: '.$textColor.'; background-color: '.$textBgColor.'; padding: 4px;">';
  			 	if ($page>1) print '<a style="text-decoration: none;" href="'.$_SERVER['SCRIPT_NAME'].'?page='.($page-1).'">&lt;</a> ';
	    	    print ' Page ['.$page.'/'.$pages.']';
		 		if ($page<$pages) print ' <a style="text-decoration: none;" href="'.$_SERVER['SCRIPT_NAME'].'?page='.($page+1).'">&gt;</a>';
		 		print '</td></tr><tr><td style="font-size: 10px; font-family: Arial; text-align: right;">powered by <a style="text-decoration: none;" href="http://www.code4fun.org/easynews">EasyNews</a></td></tr></table>';
		 	}

		 } // end if ($num_row)

         // nessuna news è presente nel db
         else print '<div style="padding: 4px; text-align: center; font-family: Arial,Verdana; font-size: 12px; color: #000000;">No News In Database</div>';

// close connection
@mysql_close($conn);


/*******************************************************************************
**             Img Size According $maxWidth and $margin                       **
*******************************************************************************/
function imgSize($img, $ratio) {

		 global $tableWidth,$contentPadding;
		 if ($ratio>100) $ratio=100;

		 $maxWidth = round(($tableWidth - 2*$contentPadding)*$ratio);
         // dati dell'immagine reale
         if (!$size = @getimagesize($img)) {
			$newWidth = $maxWidth;
			return 'width="'.$newWidth.'"';
	 	 }
		 $width=$size[0];
		 $height=$size[1];
         // rapporto lunghezza/altezza immagine reale
         $imgRatio = $height/$width;
         // calcolo dei nuovi valori lunghezza e altezza in accordo col parametro
         // $maxWidth e $margin
         if ($width>$maxWidth) {
                 $newWidth = $maxWidth;
                 $newHeight= round($newWidth*$imgRatio);
         }
         else { $newWidth=$width; $newHeight=$height; }
         return 'width="'.$newWidth.'" height="'.$newHeight.'"';
}

/*******************************************************************************
**  		"rettifica" la path $enPath inserita in config.php                **
*******************************************************************************/
function validatePath(&$path) {

	if (substr($path,0,4)=='www.') $path='http://'.$path;
	// remove front "/"
	if ($path{0}=="/") $path=substr($path, 1);
	// pushback "/"
	if ($path{strlen($path)-1}!="/" && $path!="") $path.="/";

}

?>
