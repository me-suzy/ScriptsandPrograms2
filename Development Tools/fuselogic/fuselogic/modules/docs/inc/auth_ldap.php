<?php

/**
 * This is used to authenticate against an LDAP server
 * 
 * tested with openldap 2.x on Debian only
 */

/**
 * connects to the ldap server and holds the connection
 * in global scope for multiple use
 */
function auth_ldap_connect(){
  global $LDAP_CONNECTION;
  global $conf;
  $cnf = $conf['auth']['ldap'];

  if(!$LDAP_CONNECTION){
    $LDAP_CONNECTION = @ldap_connect($cnf['server']);
    if(!$LDAP_CONNECTION){
      msg("LDAP: couldn't connect to LDAP server",-1);
    }
  }
  return $LDAP_CONNECTION;
}

/**
 * required auth function
 *
 * Checks if the given user exists and the given
 * plaintext password is correct
 *
 * It does so by trying to connect to the LDAP server
 */
function auth_checkPass($user,$pass){
  global $conf;
  $cnf = $conf['auth']['ldap'];

  //connect to LDAP Server
  $conn = auth_ldap_connect();
  if(!$conn) return false;

  //get dn for given user
  $info = auth_getUserData($user);
  $dn   = $info['dn'];
  if(!$dn) return false;

  //try to bind with dn
  if(@ldap_bind($conn,$dn,$pass)){
    return true;
  }
  return false;
}

/**
 * Required auth function
 *
 * Returns info about the given user needs to contain
 * at least these fields:
 *
 * name string  full name of the user
 * mail string  email addres of the user
 * grps array   list of groups the user is in
 *
 * This LDAP specific function returns the following
 * addional fields
 *
 * dn   string  distinguished name (DN)
 * uid  string  Posix User ID
 */
function auth_getUserData($user){
  global $conf;
  $cnf = $conf['auth']['ldap'];

  //connect to LDAP Server
  $conn = auth_ldap_connect();
  if(!$conn) return false;

  //anonymous bind to lookup userdata
  if(!@ldap_bind($conn)){
    msg("LDAP: can not bind anonymously",-1);
    return false;
  }

  //get info for given user
  $filter = str_replace('%u',$user,$cnf['userfilter']);
  $sr     = ldap_search($conn, $cnf['usertree'], $filter);;
  $result = ldap_get_entries($conn, $sr);
  if($result['count'] != 1){
    return false; //user not found
  }

  //general user info
  $info['dn']  = $result[0]['dn'];
  $info['mail']= $result[0]['mail'][0];
  $info['name']= $result[0]['cn'][0];
  $info['uid'] = $result[0]['uid'][0];
  
  //primary group id
  $gid = $result[0]['gidnumber'][0];

  //get groups for given user
  $filter = "(&(objectClass=posixGroup)(|(gidNumber=$gid)(memberUID=".$info['uid'].")))";
  $sr     = ldap_search($conn, $cnf['grouptree'], $filter);;
  $result = ldap_get_entries($conn, $sr);
  foreach($result as $grp){
    if(!empty($grp['cn'][0]))
      $info['grps'][] = $grp['cn'][0];
  }
  return $info;
}

/**
 * Required auth function
 *
 * Not implemented
 */
function auth_createUser($user,$name,$mail){
  msg("Sorry. Creating users is not supported by the LDAP backend",-1);
  return null;
}

?>
