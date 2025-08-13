<?php
// file: prepress.php
// desc: resizes an image for download
// code: adam b (grendel@strangelove.dyndns.org)
// lic : GPLv2

include "config.inc";

if (empty($id)) $error_no_image=true;
else $id=(int)$id;

$page_name="Prepress Module";

// useful function -- for instance, HideInForm("id")
function HideInForm($var)
{
  global $$var;
  return ("<INPUT TYPE=HIDDEN NAME=\"".htmlentities(
    $var)."\" VALUE=\"".htmlentities($$var)."\">");
}

function Inches($pixels)
{
  global $dpi;
  $inches = $pixels/$dpi;
  $inches = bcadd ($inches, 0, 2);
  return $inches;
}

include "header.php";
echo "
  <TABLE CELLSPACING=0 CELLPADDING=5 VALIGN=MIDDLE ALIGN=CENTER>
   <TR>
    <TD ALIGN=CENTER BGCOLOR=\"#aaaaaa\">
     <B>Prepress Module</B>
    </TD>
   </TR>";
if ($error_no_image) {
  echo "
   <TR>
    <TD ALIGN=CENTER>
     You must select an image.
    </TD>
   </TR></TABLE>";
  include "footer.php";
  DIE();
} // if no $id passed

// we fetch the ID's name, h/w info, etc.
$result = $sql->db_query(DB_NAME,
            "SELECT * FROM images WHERE id='$id'");
$r = $sql->fetch_array($result);
$orig_size = getimagesize($r["fullfilename"]);
$orig_w = $orig_size[0]; 
if (empty($curr_w)) $curr_w = $orig_w;
$orig_h = $orig_size[1];
if (empty($curr_h)) $curr_h = $orig_h;
$name_parts = explode('/',$r["fullfilename"]);
$filename = $name_parts[count($name_parts)-1];

if ($r["imagetype"]=="TIFF" || $r["imagetype"]=="EPS") {
  echo "
   <TR>
    <TD ALIGN=CENTER>
     Sorry, TIFFs and EPS files are not supported right now.
    </TD>
   </TR></TABLE>";
  include "footer.php";
  DIE();
} // if image is a TIFF

if (empty($dpi)) $dpi=300;
$ratio = $curr_h / $orig_h;

// change image size to new w/h/whatever
// $ratio = current pixels / original pixels
if ($action = "resize") {
  if ($units == "inches") {
    if (!empty($new_h)) $ratio = ($new_h*$dpi) / $orig_h;
    else if (!empty($new_w)) $ratio = ($new_w*$dpi) / $orig_w;
  }
  if ($units == "pixels") {
    if (!empty($new_h)) $ratio = $new_h / $orig_h;
    else if (!empty($new_w)) $ratio = $new_w / $orig_w;
  }
}

$curr_h = (int)($orig_h * $ratio);
$curr_w = (int)($orig_w * $ratio);

echo "
   <TR>
    <TD>
     <TABLE CELLSPACING=0 CELLPADDING=2>
      <TR>
       <TD ALIGN=CENTER COLSPAN=2>
        <B>Filename :</B> 
        <B>$filename</B>
       </TD>
      </TR>
      <TR>
       <TD ALIGN=CENTER>
        <TABLE CELLSPACING=0 CELLPADDING=10 BORDER=0 BGCOLOR=\"#bbbbbb\">
         <TR>
          <TD ALIGN=RIGHT>
           Current Width : 
          </TD>
          <TD ALIGN=LEFT>
           ".(($units=="inches") ? Inches($curr_w)." inches" : "$curr_w pixels")."
          </TD>
         </TR>
         <TR>
          <TD ALIGN=RIGHT>
           Current Height : 
          </TD>
          <TD ALIGN=LEFT>
           ".(($units=="inches") ? Inches($curr_h)." inches" : "$curr_h pixels")."
          </TD>
         <TR>
          <TD ALIGN=RIGHT>
           Current DPI : 
          </TD>
          <TD ALIGN=LEFT>
           $dpi
          </TD>
	 </TR>
	 <TR>
	  <TD ALIGN=CENTER COLSPAN=2>
	   <FORM METHOD=POST ACTION=\"NULL\">
	    <INPUT TYPE=SUBMIT VALUE=\"Get This Image\">
	   </FORM>
	  </TD>
	 </TR>
	</TABLE>
       </TD>
       <TD ALIGN=CENTER>
        <TABLE BGCOLOR=\"#cccccc\">
	 <TR>
          <TD ALIGN=RIGHT>
           New Width :
          </TD>
          <TD ALIGN=LEFT>
           <FORM METHOD=POST ACTION=\"prepress.php?action=resize\">
   	    ".HideInForm("id")."
	    ".HideInForm("dpi")."
	    ".HideInForm("curr_h")."
	    <INPUT TYPE=TEXT MAXLENGTH=5 SIZE=5 NAME=\"new_w\">
	    <SELECT NAME=\"units\">
	     <OPTION VALUE=\"inches\" ".($units=="inches"?"SELECTED":"").">inches
	     <OPTION VALUE=\"pixels\" ".($units=="pixels"?"SELECTED":"").">pixels
	    </SELECT>
	   </FORM>
          </TD>
         </TR>
         <TR>
          <TD ALIGN=RIGHT>
           New Height :
          </TD>
          <TD ALIGN=LEFT>
           <FORM METHOD=POST ACTION=\"prepress.php?action=resize\">
	    ".HideInForm("id")."
	    ".HideInForm("dpi")."
	    ".HideInForm("curr_h")."
	    <INPUT TYPE=TEXT MAXLENGTH=5 SIZE=5 NAME=\"new_h\">
	    <SELECT NAME=\"units\">
	     <OPTION VALUE=\"inches\" ".($units=="inches"?"SELECTED":"").">inches
	     <OPTION VALUE=\"pixels\" ".($units=="pixels"?"SELECTED":"").">pixels
	    </SELECT>
           </FORM>
          </TD>
         </TR>
          <TD ALIGN=RIGHT>
           New DPI :
          </TD>
          <TD ALIGN=LEFT>
           <FORM METHOD=POST ACTION=\"prepress.php?action=resize\">
	    ".HideInForm("id")."
	    ".HideInForm("units")."
	    ".HideInForm("curr_h")."
	    <INPUT TYPE=TEXT MAXLENGTH=5 SIZE=5 NAME=\"dpi\">
	   </FORM>
          </TD>
         </TR>
	</TABLE>
       </TD>
      </TR>
     </TABLE>
    </TD>
   </TR>
  ";
echo "
  </TABLE>";

include "footer.php";
?>
