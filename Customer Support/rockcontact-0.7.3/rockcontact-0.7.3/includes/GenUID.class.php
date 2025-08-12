<?
/***************************************************************************
 *                              GenUID.class.php
 *                            -------------------
 *   begin                : Tuesday, Jan 11, 2005
 *   copyright            : (C) 2005 Network Rebusnet
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

class GenUID {

  /**
   * Create new random UID (MD5).
   *
   * @param int $len The len of UID needed
   * @return string New UID
   */
  function nextUID() {
    $uid = md5 (uniqid (mt_rand()));
    return $uid;
  }

  function nextUIDCompress() {
    $uid = $this->nextUID();
    return $this->compressUID($uid);
  }

  function compressUID( $uid ){
    $compress = Base64_encode(pack("H*",$uid));
    return(substr($compress,0,22));
  }

  function expandUID($uid){
    return ( implode(unpack("H*",Base64_decode($uid ."==")), '') );
  }

  /**
   * Create random new visual code.
   *
   * @param int $len The lenght of new code
   * @return string The new code
   */
  function nextCode($len){
    $s = "CEFHJKLMNPQRTWXY389";
    $rv = "";
    while (strlen($rv) < $len) {
      $digit = $s[rand( 0 , strlen($s)-1)];
      if ( strpos( $rv, $digit) === FALSE){
        $rv .= $digit;
      }
    }
    return $rv;
  }

}

?>
