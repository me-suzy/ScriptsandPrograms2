<?php

//smilies' codes
$sm_search = array(
':)',
':(',
':o',
':D',
';)',
':p',
'[confused]',
'[cool]',
'[mad]',
'[sad]',
'[smirk]',
'[up]',
'[down]');
$sm_replace = array(
'<img src="smilies/grin.gif" border="0">',
'<img src="smilies/sad.gif" border="0">',
'<img src="smilies/shame.gif" border="0">',
'<img src="smilies/biglol.gif" border="0">',
'<img src="smilies/wink.gif" border="0">',
'<img src="smilies/tongue.gif" border="0">',
'<img src="smilies/confused.gif" border="0">',
'<img src="smilies/cool.gif" border="0">',
'<img src="smilies/mad.gif" border="0">',
'<img src="smilies/sad.gif" border="0">',
'<img src="smilies/smirk.gif" border="0">',
'<img src="smilies/up.gif" border="0">',
'<img src="smilies/down.gif" border="0">');

$barsmilies = 13; //show how many smilies on the smilies bar?

function generate_smilies_bar($doc)
{
  global $sm_search, $sm_replace, $lang, $barsmilies;

  $sarr = array();
  for ($i=0; $i<$barsmilies; ++$i)
  {
    array_push($sarr, '<a href="javascript:paste_string(', $doc, ',\'', $sm_search[$i], '\')">', $sm_replace[$i], '</a>');
  }
  if ($barsmilies < count($sm_search))
    array_push($sarr, '&nbsp;&nbsp;<a style="font-family: arial, tahoma, verdana; font-size: 11px;" href="javascript:spopup(\'', $doc, '\', 400, 400, 1)">', $lang['all_smilies'], '</a>');
  return implode('',$sarr); 

}
?>
