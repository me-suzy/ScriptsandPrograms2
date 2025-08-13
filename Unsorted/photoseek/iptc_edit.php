<?php
 // file: iptc_edit.php
 // desc: allows modification of IPTC tags of an image from the database
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

openDatabase();

 // retrieve current image information
 $result = $sql->db_query (DB_NAME,
                           "SELECT * FROM images WHERE id='$id'");
 if ($sql->num_rows($result)<1)
   die ("image_information :: id not retrievable");
 $r = $sql->fetch_array ($result);
 extract($r);

 // grab basic repository information
 $r_result = $sql->db_query (DB_NAME,
                             "SELECT * FROM repositories
                              WHERE id='$r[repository]'");
 if ((!$r_result) or ($sql->num_rows($r_result)<1))
   die ("image_information :: no repositories");
 $r_r = $sql->fetch_array ($r_result);
 $repository_name = prepare( $r_r[rname]." (".$r_r[rdesc].")");

 $image_size       = getimagesize ($r["fullfilename"]);
 $image_width      = $image_size[0];
 $image_height     = $image_size[1];

 // get filename
 $this_fileparts   = explode ("/", $r[fullfilename]);
 $filename         = $this_fileparts [(count ($this_fileparts) - 1)];
 unset             ($this_fileparts [(count ($this_fileparts) - 1)]);
 $filepath         = join ("/", $this_fileparts);
 $stripped_slash   = substr ($r_r[rpath], 0, (strlen ($r_r[rpath])) - 1);
 $filepath         = str_replace ($stripped_slash, "", $filepath);

 // create the date
 if (!empty ($r[date_created])) {
   list ($date_created_y, $date_created_m, $date_created_d) =
     explode ("-", stripslashes($r[date_created]) );
   if ($date_created_y==0)
     list ($date_created_y, $date_created_m, $date_created_d) =
       explode ("-", date ("Y-m-d") );
 } else { // if nothing presented, resort to current
   list ($date_created_y, $date_created_m, $date_created_d) =
     explode ("-", date ("Y-m-d") );
 } // end checking if date created

 if ($action=="modify") {
   $page_name = "Modify IPTC Fields";
   if ($style != "nobar") { include "header.php"; }
    else {
    echo "
     <HTML>
     <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
     <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
     "VLINK=\"".VLINKCOLOR."\">
    "; }

   // create blank array
   unset ($iptc_array);

   // pull from combo widgets
   $caption_writer = ( !empty($caption_writer_) ?
                       $caption_writer_ : $caption_writer_text );
   $byline         = ( !empty($byline_)          ?
                       $byline_         : $byline_text         );
   $date_created   = join ("-", array ($date_created_y,
                                       $date_created_m,
				       $date_created_d)        );

   // check to see for each one if we have to ...
   $check_array = array (
     "caption",
     "caption_writer",
     "headline",
     "special_instructions",
     "byline",
     "byline_title",
     "credit",
     "source",
     "object_name",
     "date_created",
     "city",
     "state",
     "country",
     "original_transmission_reference",
     "copyright_notice",
     "keyword",
     "category"
   );
   reset ($check_array);
   while (list ($key, $value) = each ($check_array)) {
     //echo "$$value != $r[$value] ... ";
     $iptc_array[$value] = stripslashes($$value);
   } // end of while looping

   // now build the iptc block
   $iptc_block = build_iptc_block ($iptc_array);

   echo "
     <CENTER>
     <B>Modify IPTC fields</B>
     </CENTER>
     <P>

   ";
  
   // embed the block in the file in question
   $buffer = iptcembed ($iptc_block, $r[fullfilename], 0);

   // write to the file
   $fp = fopen ($r[fullfilename], "w+") or
     DIE ("iptc_edit.php :: could not open file for writing");
   fwrite ($fp, $buffer) or
     DIE ("iptc_edit.php :: could not write to file");
   fclose ($fp) or
     DIE ("iptc_edit.php :: could not close file");

   // debug crap...
   if (VERBOSE) {
     echo "length = ".strlen($iptc_block)."<BR>\n";
     for ($i=0;$i<strlen($iptc_block);$i++) {
       echo "pos = $i, chr = ".ord($iptc_block[$i])." ($iptc_block[$i]) <BR>\n";
     }
   }
   
   echo "<BR><CENTER>Updating the image in the database ... \n";
   update_image_in_database ($id);

   echo "
     </CENTER>
     <P>
     <CENTER>
      <FORM ACTION=\"index.php\" METHOD=POST>
      ".pass_form (
      		array (
			"action" => $last_action
		)
	)."
       <INPUT TYPE=SUBMIT VALUE=\"  Return to Search  \">
      </FORM>
     </CENTER>
   ";
   if ($style != "nobar") include "footer.php";
   die("");
 } // end action = modify

 $page_name = "IPTC Fields Edit";
 if ($style != "nobar") { include "header.php"; }
  else {
   echo "
    <HTML>
    <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
    <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
    "VLINK=\"".VLINKCOLOR."\">
  "; }

 echo "
  <FORM ACTION=\"iptc_edit.php\" METHOD=POST>
  ".pass_form (
  	array (
		"action" => "modify"
	)
  )."
 
  <TABLE CELLSPACING=0 CELLPADDING=5 VALIGN=MIDDLE ALIGN=CENTER>
   <TR>
    <TD COLSPAN=2 ALIGN=CENTER BGCOLOR=#aaaaaa>
     <CENTER><B>".prepare($page_name)."</B></CENTER>
    </TD>
   </TR>

   <TR>
    <TD VALIGN=TOP ALIGN=CENTER>
     <A HREF=\"index.php?".
     pass_url (
     	array (
		"action" => "info"
	)
     )."\"
     ><IMG SRC=\"get_thumbnail.php?id=$id&large=1\" BORDER=0 ALT=\"\"></A><BR>
     <TT>".hilite(prepare($filename),$search)."</TT>
    </TD>
    <TD>
     <!-- description fields table -->
     <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>File Name : </B></TD>
       <TD ALIGN=LEFT>".hilite(prepare($filename),$search)."</TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Caption : </B></TD>
       <TD ALIGN=LEFT>
        <TEXTAREA ROWS=4 COLS=40 WRAP=VIRTUAL NAME=\"caption\"
	>".prepare($caption)."</TEXTAREA></TD>
      </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Caption Writer : </B></TD>
        <TD ALIGN=LEFT>
	 <SELECT NAME=\"caption_writer_\">
	  <OPTION VALUE=\"\">[Fill-in]
    ";
    $cw = $sql->db_query (DB_NAME,
      "SELECT DISTINCT caption_writer FROM images
       WHERE (LENGTH(caption_writer) > 3)
       ORDER BY caption_writer");
    if ($cw) {
     while ($cw_r = $sql->fetch_array ($cw)) {
      echo "  <OPTION VALUE=\"".prepare($cw_r[caption_writer])."\" ".
	       ( ($cw_r[caption_writer] == $r[caption_writer]) ?
                 "SELECTED" : ""
	       ).">".
	      prepare($cw_r[caption_writer])."\n"; 
     } // end of while
    } // check if (cw)
    echo "
         </SELECT>
	 <INPUT TYPE=TEXT NAME=\"caption_writer_text\" SIZE=25 MAXLENGHTH=255
	  VALUE=\"\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Headline : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"headline\" SIZE=25 MAXLENGHTH=255
	  VALUE=\"".prepare($headline)."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Special Instructions : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"special_instructions\" SIZE=25 MAXLENGHTH=255
	  VALUE=\"".prepare($special_instructions)."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Byline/Photographer : </B></TD>
        <TD ALIGN=LEFT>
	 <SELECT NAME=\"byline_\">
	  <OPTION VALUE=\"\">[Fill-in]
    ";
    $cw = $sql->db_query (DB_NAME,
      "SELECT DISTINCT byline FROM images
       WHERE (LENGTH(byline) > 3)
       ORDER BY byline");
    if ($cw) {
     while ($cw_r = $sql->fetch_array ($cw)) {
      echo "  <OPTION VALUE=\"".prepare($byline)."\" ".
	       ( ($cw_r[byline] == $r[byline]) ?
                 "SELECTED" : ""
	       ).">".
	      prepare($cw_r[byline])."\n"; 
     } // end of while
    } // check if (cw)
    echo "
         </SELECT>
	 <INPUT TYPE=TEXT NAME=\"byline_text\" SIZE=25 MAXLENGTH=255
	  VALUE=\"\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Byline Title : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"byline_title\" SIZE=25 MAXLENGTH=255
	  VALUE=\"".prepare($byline_title)."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Credit : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"credit\" SIZE=25 MAXLENGTH=255
	 VALUE=\"".prepare($credit)."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Source : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"source\" SIZE=25 MAXLENGTH=255
	  VALUE=\"".prepare($image_source)."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Date Created : </B></TD>
        <TD ALIGN=LEFT>
	 <SELECT NAME=\"date_created_m\">
     ";
     for ($m=1;$m<=12;$m++) {
       echo "  <OPTION VALUE=\"$m\" ".
            ( ($m==$date_created_m) ? "SELECTED" : "" ).
	    ">".prepare($lang_months[$m])."\n";
     } // end m loop
     echo "
	 </SELECT>
	 <SELECT NAME=\"date_created_d\">
     ";
     for ($d=1;$d<=31;$d++) {
       echo "  <OPTION VALUE=\"$d\" ".
            ( ($d==$date_created_d) ? "SELECTED" : "" ).
	    ">$d\n";
     } // end m loop
     echo "
	 </SELECT>
	 <SELECT NAME=\"date_created_y\">
     ";
     for ($y=EPOCH;$y<=(date("Y")+2);$y++) {
       echo "  <OPTION VALUE=\"$y\" ".
            ( ($y==$date_created_y) ? "SELECTED" : "" ).
	    ">$y\n";
     } // end m loop
     echo "
	 </SELECT>
	</TD> 
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Object Name : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"object_name\" SIZE=25
	  VALUE=\"".prepare($object_name)."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>".CITY_TEXT." : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"city\" SIZE=25
	  VALUE=\"".prepare($r[city])."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>State : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"state\" SIZE=25
	  VALUE=\"".prepare($r[state])."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Country : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"country\" SIZE=25
	  VALUE=\"".prepare($r[country])."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B
         >Original Transmission Reference : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"original_transmission_reference\" SIZE=25
	  VALUE=\"".prepare($r[original_transmission_reference])."\"></TD>
       </TR>
       <TR>
        <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Copyright Notice : </B></TD>
        <TD ALIGN=LEFT>
	 <INPUT TYPE=TEXT NAME=\"copyright_notice\" SIZE=25
	  VALUE=\"".prepare($r[copyright_notice])."\"></TD>
       </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Categories : </B></TD>
       <TD ALIGN=LEFT>
        <INPUT TYPE=TEXT NAME=\"category\" SIZE=25 MAXLENGTH=500
	 VALUE=\"".prepare($r[categories])."\"></TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Keywords : </B></TD>
       <TD ALIGN=LEFT>
        <INPUT TYPE=TEXT NAME=\"keyword\" SIZE=25 MAXLENGTH=500
	 VALUE=\"".prepare($r[keywords])."\"></TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=MIDDLE><B>Repository : </B></TD>
       <TD ALIGN=LEFT>$repository_name</TD>
      </TR>
      <TR BGCOLOR=\"#cccccc\">
       <TD ALIGN=CENTER VALIGN=MIDDLE COLSPAN=2>
        <INPUT TYPE=SUBMIT VALUE=\"Modify IPTC Fields\">
	<INPUT TYPE=RESET  VALUE=\"Reset to Original Values\">
       </TD>
      </TR>
     </TABLE>

     </FORM>
     <!-- end of description fields table -->
    </TD>
   </TR>
   <TR>
    <TD COLSPAN=2 BGCOLOR=\"#cccccc\" ALIGN=CENTER>
     <CENTER>
      <FORM ACTION=\"index.php\" METHOD=POST>
      ".pass_form (
      		array (
			"action" => $last_action
		)
	)."
       <INPUT TYPE=SUBMIT VALUE=\"  Return to Search  \">
      </FORM>
     </CENTER>
    </TD>
   </TR>
  </TABLE> 
 ";
 if ($style != "nobar") include "footer.php";

 closeDatabase ();
?>
