<?php
 // file: image_information.php
 // desc: retrieves image for specified ID # in database
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

openDatabase ();

 $result = $sql->db_query (DB_NAME,
                           "SELECT * FROM images WHERE id='$id'");
 if ($sql->num_rows($result)<1)
   die ("image_information :: id not retrievable");
 $r = $sql->fetch_array ($result);

 $r_result = $sql->db_query (DB_NAME,
                             "SELECT * FROM repositories
                              WHERE id='$r[repository]'");
 if ((!$r_result) or ($sql->num_rows($r_result)<1))
   die ("image_information :: no repositories");
 $r_r = $sql->fetch_array ($r_result);
 $repository_name = htmlentities(stripslashes(
                    $r_r[rname]." (".$r_r[rdesc].")"
                    ));

 $content = "";
 switch ($r["imagetype"]) {
  case "GIF":            $content = "image/gif";     break;
  case "TIFF":           $content = "image/tiff";    break;
  case "JPEG": default:  $content = "image/jpeg";    break;
 } // end of type switch

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

 $page_name = "Image Information";
 if ($style != "nobar") { include "header.php"; }
  else {
   echo "
    <HTML>
     <TITLE>".PACKAGE_NAME." ".prepare($page_name)."</TITLE>
     <BODY BGCOLOR=\"".BGCOLOR."\" LINK=\"".LINKCOLOR."\" ".
     "VLINK=\"".VLINKCOLOR."\">
   "; }
 echo "
  <TABLE CELLSPACING=0 CELLPADDING=5 VALIGN=MIDDLE ALIGN=CENTER>
   <TR>
    <TD COLSPAN=2 ALIGN=CENTER BGCOLOR=#aaaaaa>
     <CENTER><B>Image Information</B></CENTER>
    </TD>
   </TR>

   <TR>
    <TD VALIGN=TOP ALIGN=CENTER>
     <IMG SRC=\"get_thumbnail.php?id=$id&large=1\" BORDER=0 ALT=\"\"><BR>
     <TT>".hilite(htmlentities($filename),$search)."</TT>
     ".( 
         ((valid_user() > 0) and ($r[imagetype]=="JPEG")) ?
       "<P><FORM ACTION=\"iptc_edit.php\" METHOD=POST>
        <INPUT TYPE=HIDDEN NAME=\"id\" VALUE=\"".prepare($id)."\">
        <INPUT TYPE=HIDDEN NAME=\"repository\"
	 VALUE=\"".prepare($repository)."\">
        <INPUT TYPE=HIDDEN NAME=\"last_action\"
	 VALUE=\"".prepare($last_action)."\">
        <INPUT TYPE=HIDDEN NAME=\"start\" VALUE=\"".prepare($start)."\">
        <INPUT TYPE=HIDDEN NAME=\"search\" VALUE=\"".prepare($search)."\">
        <INPUT TYPE=HIDDEN NAME=\"field\" VALUE=\"".prepare($field)."\">
        <INPUT TYPE=HIDDEN NAME=\"criteria\" VALUE=\"".prepare($criteria)."\">
        <INPUT TYPE=HIDDEN NAME=\"style\" VALUE=\"".prepare($style)."\">
        <INPUT TYPE=HIDDEN NAME=\"type\" VALUE=\"".prepare($type)."\">
	<INPUT TYPE=SUBMIT VALUE=\"  Edit IPTC Tags  \">
       </FORM>" : ""
     )."
    </TD>
    <TD>
     <!-- description fields table -->
     <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>File Name : </B></TD>
       <TD ALIGN=LEFT>".hilite(htmlentities($filename),$search)."</TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Location : </B></TD>
       <TD ALIGN=LEFT>
         <A HREF=\"index.php?".
	 pass_url (
	 	array (
			"action" => "locationsearch",
			"repository" => $r[repository]
		)
	 )."\">".hilite(htmlentities($filepath),$search)."/</A>
       </TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Caption : </B></TD>
       <TD ALIGN=LEFT>".hilite(htmlentities(
                        stripslashes($r[caption])),$search)."</TD>
      </TR>
      ".(
       (!empty($r[caption_writer])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Caption Writer : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[caption_writer]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[headline]))       ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Headline : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[headline]),$search)."</TD>
       </TR>"                       :
       ""
      ).(
       (!empty($r[special_instructions])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Special Instructions : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[special_instructions]),
          $search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[byline])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Byline/Photographer : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[byline]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[byline_title])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Byline Title : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[byline_title]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[credit])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Credit : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[credit]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[source])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Source : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[source]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       ((!empty($r[date_created])) and
         (substr($r[date_created], 0, 4) != "0000")) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Date Created : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[date_created]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[object_name])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Object Name : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[object_name]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[city])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>".CITY_TEXT." : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[city]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[state])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>State : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[state]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[country])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Country : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities($r[country]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[original_transmission_reference])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B
         >Original Transmission Reference : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities(
          $r[original_transmission_reference]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!empty($r[copyright_notice])) ?
       "<TR>
        <TD ALIGN=RIGHT VALIGN=TOP><B>Copyright Notice : </B></TD>
        <TD ALIGN=LEFT>".hilite(htmlentities(
           $r[copyright_notice]),$search)."</TD>
       </TR>
       "                            :
       ""
      ).(
       (!DISABLE_CATEGORIES) ? 
       "<TR>
         <TD ALIGN=RIGHT VALIGN=TOP><B>Categories : </B></TD>
         <TD ALIGN=LEFT>".(
          (!empty($r[categories]))               ?
          hilite(htmlentities($r["categories"])) :
          "NONE"
          )."</TD>
        </TR>
      "                             :
      ""
      )."
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Keywords : </B></TD>
       <TD ALIGN=LEFT>".(
        (!empty($r[keywords]))               ?
        hilite(htmlentities($r["keywords"])) :
        "NONE"
        )."</TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Size : </B></TD>
       <TD ALIGN=LEFT>".htmlentities(
            human_readable_filesize($r[fullfilename]))."</TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Repository : </B></TD>
       <TD ALIGN=LEFT>$repository_name</TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Magic : </B></TD>
       <TD ALIGN=LEFT>".htmlentities(file_magic($r[fullfilename]))."</TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Size : </B></TD>
       <TD ALIGN=LEFT>$image_height x $image_width pixels<BR>
            ".image_at_resolution ($image_height, $image_width, 120)."<BR> 
            ".image_at_resolution ($image_height, $image_width, 200)."<BR> 
            ".image_at_resolution ($image_height, $image_width, 300)." 
       </TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=TOP><B>Transfer Time : </B></TD>
       <TD ALIGN=LEFT>
        ".human_readable_time(transfer_time ($r[fullfilename], 14.4))."
        at 14.4 kbps<BR>
        ".human_readable_time(transfer_time ($r[fullfilename], 28.8))."
        at 28.8 kbps<BR>
        ".human_readable_time(transfer_time ($r[fullfilename], 57.6))."
        at 57.6 kbps<BR>
        ".human_readable_time(transfer_time ($r[fullfilename], 128))."
        at ISDN
       </TD>
      </TR>
      <TR>
       <TD ALIGN=RIGHT VALIGN=MIDDLE><B>File : </B></TD>
       <TD ALIGN=LEFT>
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3>
	<TR>
        <TD ALIGN=CENTER>
	<A HREF=\"get_image.php/id=$r[id]/$filename\"
	><IMG SRC=\"icon_image.gif\" BORDER=0 WIDTH=32
	  HEIGHT=32 ALT=\"\"></A><BR>
	<A HREF=\"get_image.php/id=$r[id]/$filename\"
        >[Image]</A></TD>".
        ( (BINHEX_ENABLED) ?
         "<TD ALIGN=CENTER>
	  <A HREF=\"get_image.php/id=$r[id]/enc=binhex/$filename.hqx\"
	  ><IMG SRC=\"icon_binhex.gif\" BORDER=0 WIDTH=32 HEIGHT=32
	    ALT=\"\"></A><BR>
	  <A HREF=\"get_image.php/id=$r[id]/enc=binhex/$filename.hqx\"
          >[BinHex]</A></TD>" : ""
        ).
        ( (ZIP_ENABLED) ?
         "<TD ALIGN=CENTER>
	 <A HREF=\"get_image.php/id=$r[id]/enc=zip/$filename.zip\"
	 ><IMG SRC=\"icon_zip.gif\" BORDER=0 WIDTH=32 HEIGHT=32
	   ALT=\"\"></A><BR>
	 <A HREF=\"get_image.php/id=$r[id]/enc=zip/$filename.zip\"
          >[Zip]</A></TD>" : ""
       )."
       </TD></TR></TABLE>
       </TD>
      </TR>
     </TABLE>
     <!-- end of description fields table -->
    </TD>
   </TR>
   <TR>
    <TD COLSPAN=2 BGCOLOR=#cccccc ALIGN=CENTER>
     <CENTER>
      <FORM ACTION=\"index.php\" METHOD=POST>
       ".pass_form(
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
