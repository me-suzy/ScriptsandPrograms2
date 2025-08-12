<?
/***************************************************************************
 *                           SQLiteBackEnd.class.php
 *                            -------------------
 *   begin                : Monday, Mar 21, 2005
 *   copyright            : (C) 2004 Network Rebusnet
 *   contact              : http://rockcontact.rebusnet.biz/contact/
 *
 *   $Id$
 *
 ***************************************************************************/
 
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

require_once("config.inc.php");
require_once("GenUID.class.php");
require_once("counter.inc.php");

class DBRockContact extends SQLiteBackEnd {

  /**
   * Delete in database all visual code invalidate by time.
   *
   */
  function pruneVisualConfimCode(){
    $now = time();
    $minus_hour = $now - VISUAL_VALID_SECONDE;
    $s_minus_hour = sqlite_escape_string($minus_hour);
    $query = "DELETE FROM ". TABLE_VISUAL_CONFIRM ." WHERE since <= $s_minus_hour";
    $result = sqlite_query($this->db_link, $query);
  }

  /**
   * Delete old record in database.
   *
   * @param string $id The id 
   * @return int Number record delete
   * @see USE_AUTO_PRUNE
   * @see AUTO_PRUNE_SECONDE
   * @see COUNTER_PRUNE_RUN
   * @see COUNTER_PRUNE_RECORD
   */
  function pruneOldRecords() {
    $rv = 0;
  
    $DBConfig = new DBConfig();
    $DBConfig->findByID("last_prune");
    $last_prune = $DBConfig->value;

    $now = time();

    if ( ($last_prune + AUTO_PRUNE_SECONDE) > $now ){
      return;
    }

    increment_counter(COUNTER_PRUNE_RUN);

    $older = $now - AUTO_PRUNE_OLDER_SECONDE;
    $s_older = sqlite_escape_string($older);

    $query = "SELECT id FROM ". TABLE_LOG ." WHERE since < $s_older";
    $result = sqlite_query($this->db_link, $query);
    while ( $result_array = sqlite_fetch_array($result) ){
      increment_counter(COUNTER_PRUNE_RECORD);
      $rv++;
      $id = $result_array['id'];
      $s_id = sqlite_escape_string($id);
      $query = "DELETE FROM ". TABLE_CONTACT ." WHERE id='$s_id'";
      sqlite_query($this->db_link, $query);
      $query = "DELETE FROM ". TABLE_SUBMISSION ." WHERE id='$s_id'";
      sqlite_query($this->db_link, $query);
      $query = "DELETE FROM ". TABLE_EMAIL_CONFIRM ." WHERE id='$s_id'";
      sqlite_query($this->db_link, $query);
      $query = "DELETE FROM ". TABLE_LOG ." WHERE id='$s_id'";
      sqlite_query($this->db_link, $query);
    }

    // Update table config
    $DBConfig->value = $now;
    $DBConfig->Save();

    return $rv;
  }

}

class DBSubmission extends SQLiteBackEnd {
  var $id = NULL;         /* VARCHAR(32) PRIMARY KEY */
  var $since = NULL;      /* TIMESTAMP */
  var $subject = NULL;    /* VARCHAR(255) */
  var $message = NULL;    /* TEXT */
  var $send = NULL;       /* Boolean */

  function add($id, $subject, $message) {
    $s_id = sqlite_escape_string($id);
    $s_since = sqlite_escape_string(time());
    $s_subject = sqlite_escape_string($subject);
    $s_message = sqlite_escape_string($message);
    $query = "INSERT INTO ". TABLE_SUBMISSION ." ";
    $query .= "(id,since,subject,message,send) ";
    $query .= "VALUES ('$s_id',$s_since,'$s_subject','$s_message','FALSE') ";
    $result = sqlite_query($this->db_link, $query);
    return $id;
  }

  function findByID($id){ 
    $s_id = sqlite_escape_string($id);
    $query = "SELECT * FROM ". TABLE_SUBMISSION ." WHERE id='$s_id' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->id = $result_array['id'];
    $this->since = $result_array['since'];
    $this->subject = $result_array['subject'];
    $this->message = $result_array['message'];
    $this->send = $result_array['send'];
    return $this->id;
  }

  function save(){
    $s_id = sqlite_escape_string($this->id);
    $s_since = sqlite_escape_string($this->since);
    $s_subject = sqlite_escape_string($this->subject);
    $s_message = sqlite_escape_string($this->message);
    $s_send = sqlite_escape_string($this->send);
    $query = "UPDATE ". TABLE_SUBMISSION ." SET ";
    $query .= "since=$s_since, ";
    $query .= "subject='$s_subject', ";
    $query .= "message='$s_message', ";
    $query .= "send='$s_send' ";
    $query .= "WHERE id = '$s_id' ";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_changes($this->db_link) <= 0 ) {
      return NULL;
    }
    return $this->id;
  }

}

class DBEmailConfirm extends SQLiteBackEnd {
  var $id = NULL;         /* VARCHAR(32) PRIMARY KEY */
  var $since = NULL;      /* TIMESTAMP */
  var $confirmed = NULL;  /* Boolean */
  var $code = NULL;       /* VARCHAR(32) */
  var $nb_confirm = NULL; /* INTEGER */
  var $send = NULL;       /* Boolean */

  function add($id) {
    $s_id = sqlite_escape_string($id);
    $s_since = sqlite_escape_string(time());
    $GenUID = new GenUID();
    $s_code = sqlite_escape_string($GenUID->nextUID());
    $query = "INSERT INTO ". TABLE_EMAIL_CONFIRM ." ";
    $query .= "(id,since,confirmed,code,nb_confirm,send) ";
    $query .= "VALUES ('$s_id',$s_since,'FALSE','$s_code',0,'FALSE') ";
    $result = sqlite_query($this->db_link, $query);
    return $id;
  }

  function findByCode($code){ 
    $s_code = sqlite_escape_string($code);
    $query = "SELECT * FROM ". TABLE_EMAIL_CONFIRM ." WHERE code='$s_code' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->id = $result_array['id'];
    $this->since = $result_array['since'];
    $this->confirmed = $result_array['confirmed'];
    $this->code = $result_array['code'];
    $this->nb_confirm = $result_array['nb_confirm'];
    $this->send = $result_array['send'];
    return $this->id;
  }

  function findByID($id){ 
    $s_id = sqlite_escape_string($id);
    $query = "SELECT * FROM ". TABLE_EMAIL_CONFIRM ." WHERE id='$s_id' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->id = $result_array['id'];
    $this->since = $result_array['since'];
    $this->confirmed = $result_array['confirmed'];
    $this->code = $result_array['code'];
    $this->nb_confirm = $result_array['nb_confirm'];
    $this->send = $result_array['send'];
    return $this->id;
  }

  function save(){
    $s_id = sqlite_escape_string($this->id);
    $s_since = sqlite_escape_string($this->since);
    $s_confirmed = sqlite_escape_string($this->confirmed);
    $s_code = sqlite_escape_string($this->code);
    $s_nb_confirm = sqlite_escape_string($this->nb_confirm);
    $s_send = sqlite_escape_string($this->send);
    $query = "UPDATE ". TABLE_EMAIL_CONFIRM ." SET ";
    $query .= "since=$s_since, ";
    $query .= "confirmed='$s_confirmed', ";
    $query .= "code='$s_code', ";
    $query .= "nb_confirm=$s_nb_confirm, ";
    $query .= "send='$s_send' ";
    $query .= "WHERE id = '$s_id' ";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_changes($this->db_link) <= 0 ) {
      return NULL;
    }
    return $this->id;
  }

}

class DBContact extends SQLiteBackEnd {
  var $id = NULL;         /* VARCHAR(32) PRIMARY KEY */
  var $since = NULL;      /* TIMESTAMP */
  var $first_name = NULL; /* VARCHAR(32) */
  var $last_name = NULL;  /* VARCHAR(32) */
  var $email = NULL;      /* VARCHAR(64) */
  var $pref_lang = NULL;  /* VARCHAR(2) */

  function add($id, $first_name, $last_name, $email, $pref_lang) {
    $s_id = sqlite_escape_string($id);
    $s_since = sqlite_escape_string(time());
    $s_first_name = sqlite_escape_string($first_name);
    $s_last_name = sqlite_escape_string($last_name);
    $s_email = sqlite_escape_string($email);
    $s_pref_lang = sqlite_escape_string($pref_lang);

    $query = "INSERT INTO ". TABLE_CONTACT ." ";
    $query .= "(id,since,first_name,last_name,email,pref_lang) ";
    $query .= "VALUES ('$s_id',$s_since,'$s_first_name','$s_last_name','$s_email','$s_pref_lang') ";
    $result = sqlite_query($this->db_link, $query);
    return $id;
  }

  function findByID($id){ 
    $s_id = sqlite_escape_string($id);
    $query = "SELECT * FROM ". TABLE_CONTACT ." WHERE id='$s_id' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->id = $result_array['id'];
    $this->since = $result_array['since'];
    $this->first_name = $result_array['first_name'];
    $this->last_name = $result_array['last_name'];
    $this->email = $result_array['email'];
    $this->pref_lang = $result_array['pref_lang'];
    return $this->id;
  }

}

class DBConfig extends SQLiteBackEnd {
  var $key = NULL;        /* VARCHAR(32) PRIMARY KEY */
  var $value = NULL;      /* TIMESTAMP */

  function findByID($key){
    $s_key = sqlite_escape_string($key);
    $query = "SELECT * FROM ". TABLE_CONFIG ." WHERE key='$s_key' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->key = $result_array['key'];
    $this->value = $result_array['value'];
    return $this->key;
  }

  function save(){
    $s_key = sqlite_escape_string($this->key);
    $s_value = sqlite_escape_string($this->value);
    $query = "UPDATE ". TABLE_CONFIG ." SET ";
    $query .= "value=$s_value ";
    $query .= "WHERE key = '$s_key' ";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_changes($this->db_link) <= 0 ) {
      return NULL;
    }
    return $this->id;
  }
}

class DBVisualConfirm extends SQLiteBackEnd {
  var $id = NULL;        /* VARCHAR(32) PRIMARY KEY */
  var $since = NULL;     /* TIMESTAMP */
  var $code = NULL;      /* VARCHAR */

  function add() {
    $GenUID = new GenUID();
    $id = $GenUID->nextUID();
    $s_id = sqlite_escape_string($id);
    $s_since = sqlite_escape_string(time());
    $s_code = sqlite_escape_string($GenUID->nextCode(VISUAL_CONFIRM_NB_DIGIT));
    $query = "INSERT INTO ". TABLE_VISUAL_CONFIRM ." ";
    $query .= "(id,since,code) ";
    $query .= "VALUES ('$s_id',$s_since,'$s_code') ";
    $result = sqlite_query($this->db_link, $query);
    return $id;
  }

  function findByID($id){ 
    $s_id = sqlite_escape_string($id);
    $query = "SELECT * FROM ". TABLE_VISUAL_CONFIRM ." WHERE id='$s_id' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->id = $result_array['id'];
    $this->since = $result_array['since'];
    $this->code = $result_array['code'];
    return $this->id;
  }
}

class DBCounter extends SQLiteBackEnd {
  var $key = NULL;       /* VARCHAR(32) PRIMARY KEY */
  var $value = NULL;     /* INTEGER */
  var $last = NULL;      /* TIMESTAMP */

  function findByID($key){ 
    $s_key = sqlite_escape_string($key);
    $query = "SELECT * FROM ". TABLE_COUNTER ." WHERE key='$s_key' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->key = $result_array['key'];
    $this->value = $result_array['value'];
    $this->last = $result_array['last'];
    return $this->key;
  }

  function save(){
    $s_key = sqlite_escape_string($this->key);
    $s_value = sqlite_escape_string($this->value);
    $s_last = sqlite_escape_string($this->last);
    $query = "UPDATE ". TABLE_COUNTER ." SET ";
    $query .= "value=$s_value, ";
    $query .= "last=$s_last ";
    $query .= "WHERE key = '$s_key' ";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_changes($this->db_link) <= 0 ) {
      return NULL;
    }
    return $this->id;
  }

}

class DBLog extends SQLiteBackEnd {
  var $id = NULL;                /* id VARCHAR(32) PRIMARY KEY */
  var $since = NULL;             /* TIMESTAMP */
  var $ip = NULL;                /* VARCHAR(32) */
  var $agent = NULL;             /* VARCHAR(255) */
  var $since_confirm = NULL;     /* TIMESTAMP */
  var $ip_confirm = NULL;        /* VARCHAR(32) */
  var $agent_confirm = NULL;     /* VARCHAR(255) */
  var $referer_confirm = NULL;   /* VARCHAR(255) */

  function add($id, $ip, $agent) {
    $s_id = sqlite_escape_string($id);
    $s_since = sqlite_escape_string(time());
    $s_ip = sqlite_escape_string($ip);
    $s_agent = sqlite_escape_string($agent);
    $query = "INSERT INTO ". TABLE_LOG ." ";
    $query .= "(id,since,ip,agent) ";
    $query .= "VALUES ('$s_id',$s_since,'$s_ip','$s_agent') ";
    $result = sqlite_query($this->db_link, $query);
    return $id;
  }

  function findByID($id){ 
    $s_id = sqlite_escape_string($id);
    $query = "SELECT * FROM ". TABLE_LOG ." WHERE id='$s_id' LIMIT 1";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_num_rows($result) <= 0 ) {
      return NULL;
    }
    $result_array = sqlite_fetch_array($result);
    $this->id = $result_array['id'];
    $this->since = $result_array['since'];
    $this->ip = $result_array['ip'];
    $this->agent = $result_array['agent'];
    $this->since_confirm = $result_array['since_confirm'];
    $this->ip_confirm = $result_array['ip_confirm'];
    $this->agent_confirm = $result_array['agent_confirm'];
    $this->referer_confirm = $result_array['referer_confirm'];
    return $this->id;
  }

  function save(){
    $s_id = sqlite_escape_string($this->id);
    $s_since = sqlite_escape_string($this->since);
    $s_ip = sqlite_escape_string($this->ip);
    $s_agent = sqlite_escape_string($this->agent);
    $s_since_confirm = sqlite_escape_string($this->since_confirm);
    $s_ip_confirm = sqlite_escape_string($this->ip_confirm);
    $s_agent_confirm = sqlite_escape_string($this->agent_confirm);
    $s_referer_confirm = sqlite_escape_string($this->referer_confirm);
    $query = "UPDATE ". TABLE_LOG ." SET ";
    $query .= "since=$s_since, ";
    $query .= "ip='$s_ip', ";
    $query .= "agent='$s_agent', ";
    $query .= "since_confirm=$s_since_confirm, ";
    $query .= "ip_confirm='$s_ip_confirm', ";
    $query .= "agent_confirm='$s_agent_confirm', ";
    $query .= "referer_confirm='$s_referer_confirm' ";
    $query .= "WHERE id = '$s_id' ";
    $result = sqlite_query($this->db_link, $query);
    if ( sqlite_changes($this->db_link) <= 0 ) {
      return NULL;
    }
    return $this->id;
  }
}

/**
 * Base class
 */
class SQLiteBackEnd {
  var $db_link = NULL;

  function SQLiteBackEnd(){
    register_shutdown_function(array( &$this, "destroy" ));
    $this->db_link = sqlite_open(BD_NAME, 0666, $sqliteerror) or die("Error on open DB:". BD_NAME);
  }

  function destroy() {
    sqlite_close($this->db_link);
  }
}

?>
