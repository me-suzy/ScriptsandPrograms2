<?php

connect();

function bbIt($string)
{

  $sql = "SELECT * FROM redcms_bb ORDER BY redcms_bb.bb_id";

  $result = mysql_query($sql) or die("ERROR");

  $bb = array();

  $html = array();

  $num = mysql_num_rows($result);

    for($i = 0; $i < $num; $i++) {

      $bbID = mysql_result($result, $i, "redcms_bb.bb_id");
      $bbTag = mysql_result($result, $i, "redcms_bb.bb_tag");
      $bbCode = mysql_result($result, $i, "redcms_bb.bb_code");
      $bbDesc = mysql_result($result, $i, "redcms_bb.bb_desc");

      $bb[] = $bbTag;
      $html[] = $bbCode;

  }

  $string = str_replace($bb, $html, $string);

  $string = nl2br($string);
 
  return $string;
}