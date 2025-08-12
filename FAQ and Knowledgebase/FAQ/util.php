<?php
  $config =array();
  $cfg=$db->get_results("SELECT * FROM stp_f_s", ARRAY_A);
  foreach ($cfg as $row)
  {
    $config[$row['name']]=$row['value'];
  }
  if (get_magic_quotes_gpc()) 
  {
    noslashes($_POST);
    noslashes($_GET);
    noslashes($_COOKIE);
  }
  
  function noslashes(&$vars)
  {
    foreach ($vars as $k=>$v)
      if (!is_array($v)) $vars[$k] = trim(stripslashes($v));
      else noslashes($vars[$k]);
  }

  function catlist($name,$sel,$autopost)
  {
    global $db;
    $r="<select name=\"$name\"";
    if ($autopost) $r.=" onchange=\"this.form.submit();\"";
    $r.=">";
    if ($cats=$db->get_results("SELECT * FROM stp_f_c  ORDER BY `order`")) foreach ($cats as $cat)
    {
      $r.="<option value=\"$cat->id\"";
      $r.=$sel==$cat->id?" selected":"";
      $r.=">$cat->name</option>\n";
    }
    return $r."</select>";
  }
?>