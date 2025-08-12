<?php
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
?>