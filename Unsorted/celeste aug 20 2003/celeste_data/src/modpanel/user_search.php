<?php

//if (!getpermission('setpermission')) mod_exception('Permission denied.');
mod_header("Find User - Moderator Control Panel");

$errormsg = '';

if (isset($_GET['page'])) {
  $_POST =& $_GET;
  $page = $_GET['page'];
}
 else $page = 1;
$_POST['pp'] = (empty($_POST['pp']) ? 25 : $_POST['pp']);

$searchresult = '';

if (!empty($_POST['submit'])) {

  $conditions = 0;
  $query = '';
  $tables = 'celeste_user u';

  if (!empty($_POST['username'])) {
	  $conditions ++;
    if ($_POST['name_op']==0) {
      // equals
      $query .= ' and u.username= \''.slashesencode($_POST['username']).'\' ';
    } elseif ($_POST['name_op']==1) {
      // starts with
      $query .= ' and u.username like \''.slashesencode($_POST['username']).'%\' ';

    } elseif ($_POST['name_op']==2) {
      // ends with
      $query .= ' and u.username like \'%'.slashesencode($_POST['username']).'\' ';

    } elseif ($_POST['name_op']==3) {
      // contains
      $query .= ' and u.username like \'%'.slashesencode($_POST['username']).'%\' ';
    }
  }

  if (!empty($_POST['userid'])) {
     $conditions ++;
    if ($_POST['id_op']==0) {
      // equals
      $query .= ' and u.userid= \''.slashesencode($_POST['userid']).'\' ';
    } elseif ($_POST['id_op']==1) {
      // greater than
      $query .= ' and u.userid >= \''.slashesencode($_POST['userid']).'\' ';

    } elseif ($_POST['id_op']==2) {
      // less than
      $query .= ' and u.userid <= \''.slashesencode($_POST['userid']).'\' ';
    }
  }

  if (!empty($_POST['email'])) {
     $conditions ++;
      // equals
      $query .= ' and u.email= \''.slashesencode($_POST['email']).'\' ';
  }

  if (!empty($_POST['rating'])) {
     $conditions ++;
    if ($_POST['rating_op']==0) {
      // equals
      $query .= ' and u.rating= \''.slashesencode($_POST['rating']).'\' ';
    } elseif ($_POST['rating_op']==1) {
      // greater than
      $query .= ' and u.rating >= \''.slashesencode($_POST['rating']).'\' ';

    } elseif ($_POST['rating_op']==2) {
      // less than
      $query .= ' and u.rating <= \''.slashesencode($_POST['rating']).'\' ';
    }
  }

  if (!empty($_POST['join'])) {
     $conditions ++;
    if ($_POST['join_op']==0) {
      // equals
      $query .= ' and u.joindate= \''.slashesencode($_POST['join']).'\' ';
    } elseif ($_POST['join_op']==1) {
      // after
      $query .= ' and u.joindate >= \''.slashesencode($_POST['join']).'\' ';

    } elseif ($_POST['join_op']==2) {
      // before
      $query .= ' and u.joindate <= \''.slashesencode($_POST['join']).'\' ';
    }
  }

  if (!empty($_POST['posts'])) {
     $conditions ++;
    if ($_POST['posts_op']==0) {
      // equals
      $query .= ' and u.posts= \''.slashesencode($_POST['posts']).'\' ';
    } elseif ($_POST['posts_op']==1) {
      // greater than
      $query .= ' and u.posts >= \''.slashesencode($_POST['posts']).'\' ';

    } elseif ($_POST['posts_op']==2) {
      // less than
      $query .= ' and u.posts <= \''.slashesencode($_POST['posts']).'\' ';
    }
  }


  if (!empty($_POST['ip'])) {
     $conditions ++;
     $tables .= ' left join celeste_useronline o using(userid)';


    if ($_POST['ip_op']==0) {
      // equals
      $query .= ' and o.ipaddress= \''.slashesencode($_POST['ip']).'\' ';
    } elseif ($_POST['ip_op']==1) {
      // starts with
      $query .= ' and o.ipaddress like \''.slashesencode($_POST['ip']).'%\' ';

    } elseif ($_POST['ip_op']==2) {
      // ends with
      $query .= ' and o.ipaddress like \'%'.slashesencode($_POST['ip']).'\' ';

    } elseif ($_POST['ip_op']==3) {
      // contains
      $query .= ' and o.ipaddress like \'%'.slashesencode($_POST['ip']).'%\' ';
    }
  }

  if (!empty($_POST['usergroupid'])) {
     $conditions ++;
     $query .= ' and u.usergroupid= \''.$_POST['usergroupid'].'\' ';
  }
  
  if ($conditions>0) {
    $query = substr($query, 4);
    $max =& ceil($DB->result('select count(*) from '.$tables.' where '.$query) / $_POST['pp']);
    $ids =& $DB->query('select u.userid, u.username, u.email, u.posts from '.$tables.' where '.$query, ($page-1)*$_POST['pp'], $_POST['pp']);

	  $searchresult = '<table width=90% cellspacing=2 cellpadding=4 border=0 align=center><tr><td colspan=5> <b>Search Result </b>:<hr size=1></td></tr>';
    $searchresult .= '<tr><td> ID </td><td> User name </td><td> Email </td><td> Posts </td><td> Action </td></tr>';
	  while($ids->next_record()) $searchresult .= '<tr><td>'.$ids->get('userid').'</td><td><a href="modpanel.php?fid='.$forumid.'&prog=user::view&uid='.$ids->get('userid').'">'.
                                                $ids->get('username').'</a></td><td>'.
                                                $ids->get('email').'</td><td>'.
                                                $ids->get('posts').'</td><td>'.
                                                '<a href="modpanel.php?fid='.$forumid.'&prog=user::view&uid='.$ids->get('userid').'">view</a> / <a href="modpanel.php?fid='.$forumid.'&prog=user::set&uid='.$ids->get('userid').'">set permission</a></td></tr>';

    $ps='';
    foreach ($_POST as $key=>$val) {
      if ($key!='fid' && $key!='prog' && $key!='page') $ps .= '&'.$key.'='.$val;
    }

    $searchresult .= '<tr><td colspan=5 align=right>';
    $searchresult .= getModPages('fid='.$forumid.'&prog=user::search'.$ps, $max);
    $searchresult .= '</td></tr></table><br><br>';
   }


}

?>
<form method=post action='modpanel.php?fid=<?=$forumid?>&prog=user::search'>
<input type=hidden name=action value='option'>
<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td colspan=2>
You are now managing user in :
<br><br><b>   Forum (<?=$forumid?>) - <?=$forum->getProperty('title')?> </b>
<br><br><br>
Find User : <br>
<hr size=1>
</td>
<td>
</td>
</tr>
<tr><td> User Name : </td>
<td>
<select name=name_op>
  <option value=0>equals</option>
  <option value=1 <?=(!empty($_POST['name_op']) && $_POST['name_op']==1 ? 'selected' : '')?>>starts with&nbsp;&nbsp;</option>
  <option value=2 <?=(!empty($_POST['name_op']) && $_POST['name_op']==2 ? 'selected' : '')?>>ends with</option>
  <option value=3 <?=(!empty($_POST['name_op']) && $_POST['name_op']==3 ? 'selected' : '')?>>contains</option></select> &nbsp;
<input type=text name=username size=20 maxlength=30 <?php if (!empty($_POST['username'])) echo 'value="'.$_POST['username'].'"'; ?>></td></tr>

<tr><td> User ID : </td>
<td><select name=id_op>
  <option value=0>equals</option>
  <option value=1 <?=(!empty($_POST['id_op']) && $_POST['id_op']==1 ? 'selected' : '')?>>greater than</option>
  <option value=2 <?=(!empty($_POST['id_op']) && $_POST['id_op']==2 ? 'selected' : '')?>>less than</option>
  </select> &nbsp;
<input type=text name=userid size=10 maxlength=10 <?php if (isset($_POST['userid'])) echo 'value="'.$_POST['userid'].'"'; ?>></td></tr>


<tr><td> Email Address : </td>
<td><input type=text name=email size=20 maxlength=30 <?php if (!empty($_POST['email'])) echo 'value="'.$_POST['email'].'"'; ?>></td></tr>

<tr><td> Join Date : (yyyy-mm-dd)</td>
<td><select name=join_op>
  <option value=0>equals</option>
  <option value=1 <?=(!empty($_POST['join_op']) && $_POST['join_op']==1 ? 'selected' : '')?>>before&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
  <option value=2 <?=(!empty($_POST['join_op']) && $_POST['join_op']==2 ? 'selected' : '')?>>after</option></select> &nbsp;
<input type=text name=join size=20 maxlength=30 <?php if (!empty($_POST['join'])) echo 'value="'.$_POST['join'].'"'; ?>>
</td></tr>

<tr><td> Posts : </td>
<td><select name=posts_op>
  <option value=0>equals</option>
  <option value=1 <?=(!empty($_POST['posts_op']) && $_POST['posts_op']==1 ? 'selected' : '')?>>greater than</option>
  <option value=2 <?=(!empty($_POST['posts_op']) && $_POST['posts_op']==2 ? 'selected' : '')?>>less than</option></select> &nbsp;
<input type=text name=posts size=20 maxlength=30 <?php if (!empty($_POST['posts'])) echo 'value="'.$_POST['posts'].'"'; ?>>
</td></tr>

<tr><td> Rating Credits : </td>
<td><select name=rating_op>
  <option value=0>equals</option>
  <option value=1 <?=(!empty($_POST['rating_op']) && $_POST['rating_op']==1 ? 'selected' : '')?>>greater than</option>
  <option value=2 <?=(!empty($_POST['rating_op']) && $_POST['rating_op']==2 ? 'selected' : '')?>>less than</option></select> &nbsp;
<input type=text name=rating size=20 maxlength=30 <?php if (!empty($_POST['rating'])) echo 'value="'.$_POST['rating'].'"'; ?>>
</td></tr>
<tr><td> Last IP Address : </td>
<td>
<select name=ip_op>
<option value=0>equals</option>
<option value=1 <?=(!empty($_POST['ip_op']) && $_POST['ip_op']==1 ? 'selected' : '')?>>starts with&nbsp;&nbsp;</option>
<option value=2 <?=(!empty($_POST['ip_op']) && $_POST['ip_op']==2 ? 'selected' : '')?>>ends with</option><option value=3>contains</option></select> &nbsp;
<input type=text name=ip size=20 maxlength=30 <?php if (!empty($_POST['ip'])) echo 'value="'.$_POST['ip'].'"'; ?>></td></tr>

<tr><td> User Group :</td>
<td> <select name=usergroupid ><option value=0> any group</option><?=getAllGroups()?></option></td></tr>

<tr><td> Results Per Page :</td>
<td> <input type=text name=pp size=4 maxlength=10 <?php if (!empty($_POST['pp'])) echo 'value="'.$_POST['pp'].'"'; else echo 'value=25'; ?>></td></tr>

</table>

<br>

<table width=90% cellspacing=2 cellpadding=4 border=0 align=center>
<tr><td> Search :
<hr size=1>
</td></tr>
<tr><td><input type=submit name=submit value=" Search "></td>
</table>
</form>

<?
print $searchresult;

mod_footer();

function getTimeStamp ( $date ) {
  list($year, $month, $day) = explode('-', $date);
  return mktime ( 0,0,0, $month, $day, $year);
}