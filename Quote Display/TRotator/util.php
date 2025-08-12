<?php
  $config =array();
  $cfg=$db->get_results("SELECT * FROM stp_t_s", ARRAY_A);
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

  function catlist($name,$sel,$more,$autopost,$all=false)
  {
    global $db;
    $r="<select name=\"$name\"";
    if ($autopost) $r.=" onchange=\"this.form.submit();\"";
    $r.=">";
    if ($more) foreach ($more as $id=>$val)
    {
      $r.="<option value=\"$id\"";
      $r.=$sel==$id?" selected":"";
      $r.=">$val</option>\n";
    }

    if ($general)
      $r.="<option value=\"0\">$general</option>\n";
    if (!$all) $h="HAVING q.active";
    if ($cats=$db->get_results("SELECT c.*,q.active,COUNT(q.id) as nq FROM stp_t_c c LEFT JOIN stp_t_q q ON c.id=q.category GROUP BY c.id $h")) foreach ($cats as $cat)
    {
      $r.="<option value=\"$cat->id\"";
      $r.=$sel==$cat->id?" selected":"";
      $r.=">$cat->name</option>\n";
    }
    return $r."</select>";
  }

  function substitute($tpl,$vars)
  {
    foreach($vars as $name=>$value)
    {
      $template='/(?U)\[\[('.$name.')((\|([^\]]+))?)\]\]/e';
      $tpl=preg_replace($template," \$value ? \$value : '\\4'",$tpl);
    }
    return $tpl;
  }

  function notify ($id)
  {
    global $db, $config;
    $quote=$db->get_row("SELECT q.*, IF(c.name IS NULL,'General',c.name) as category FROM stp_t_q q LEFT JOIN stp_t_c c ON q.category=c.id WHERE q.id=$id",ARRAY_A);
    $quote['edit']="http://".getenv("SERVER_NAME").dirname(getenv("REQUEST_URI"))."/admin.php?m=eq&edit=$id&m2=s";
    $subject=substitute($config['subject'],$quote);
    $body=substitute($config['body'],$quote);
    @mail($config['adminemail'],$subject,$body,"From: ".$config['adminemail']);
  }

  function confirm ($id)
  {
    global $db, $config;
    $quote=$db->get_row("SELECT q.*, IF(c.name IS NULL,'General',c.name) as category FROM stp_t_q q LEFT JOIN stp_t_c c ON q.category=c.id WHERE q.id=$id",ARRAY_A);
    if ($quote->email)
    {
      $subject=substitute($config['subject'],$quote);
      $body=substitute($config['body'],$quote);
      @mail($quote->email,$subject,$body,"From: ".$config['adminemail']);
    }
  }
?>