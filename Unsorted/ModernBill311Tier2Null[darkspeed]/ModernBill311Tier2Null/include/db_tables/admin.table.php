<?php
// +----------------------------------------------------------------------+
// | ModernBill [TM] .:. Client Billing System                            |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001-2002 ModernGigabyte, LLC                          |
// +----------------------------------------------------------------------+
// | This source file is subject to the ModernBill End User License       |
// | Agreement (EULA), that is bundled with this package in the file      |
// | LICENSE, and is available at through the world-wide-web at           |
// | http://www.modernbill.com/extranet/LICENSE.txt                       |
// | If you did not receive a copy of the ModernBill license and are      |
// | unable to obtain it through the world-wide-web, please send a note   |
// | to license@modernbill.com so we can email you a copy immediately.    |
// +----------------------------------------------------------------------+
// | Authors: ModernGigabyte, LLC <info@moderngigabyte.com>               |
// | Support: http://www.modernsupport.com/modernbill/                    |
// +----------------------------------------------------------------------+
// | ModernGigabyte and ModernBill are trademarks of ModernGigabyte, LLC. |
// +----------------------------------------------------------------------+

/* ----------------- ADMIN ---------------------*/
      $title = ADMIN;
      $args = array(array("column"         => "admin_id",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),
                    array("column"         => "admin_realname",
                           "required"      => 1,
                           "title"         => REALNAME,
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 50),
                    array("column"         => "admin_email",
                           "required"      => 1,
                           "title"         => EMAIL,
                           "type"          => "TEXT",
                           "size"          => 25,
                           "maxlength"     => 50),
                    array("column"         => "admin_username",
                           "required"      => 1,
                           "title"         => USERNAME,
                           "type"          => "TEXT",
                           "size"          => 15,
                           "maxlength"     => 15),
                    array("column"         => "admin_password",
                           "required"      => 0,
                           "title"         => NEWPW,
                           "type"          => "PASSWORD",
                           "size"          => 15,
                           "maxlength"     => 15),
                    array("column"         => "admin_password_2",
                           "required"      => 0,
                           "title"         => VERIFYPW,
                           "no_details"    => 1,
                           "type"          => "PASSWORD",
                           "size"          => 15,
                           "maxlength"     => 15),
                    array("column"         => "admin_password_3",
                           "required"      => 0,
                           "title"         => YOURPW,
                           "no_details"    => 1,
                           "type"          => "PASSWORD",
                           "size"          => 15,
                           "maxlength"     => 15),
                    array("column"         => "admin_level",
                           "required"      => 1,
                           "title"         => LEVEL,
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 1,
                           "append"        => "<br>
                                                      // 9 = God         [Uber Admin, Do All]<br>
                                                      // 8 = Admin       [View All, Config Changes = Yes, No Delete]<br>
                                                      // 7 = Power User  [View All, Config Changes = No, No Delete]<br>
                                                      // 6 = NOT IN USE<br>
                                                      // 5 = Support User [Support Desk & FAQ]<br>
                                                      // 4 = NOT IN USE<br>
                                                      // 3 = NOT IN USE<br>
                                                      // 2 = NOT IN USE<br>
                                                      // 1 = NOT IN USE
                                               <br><br>"));

     if ($submit&&!$admin_id&&$do=="add")
     {
        if (!is_valid_email(strtolower(trim($admin_email))))     { $oops .= "[".ERROR."] ".EMAILINVALID."<br>"; }
        if (strlen(strval($admin_password))<6) { $oops .= "[".ERROR."] ".NEWPWSHORT."<br>"; }
        if ($admin_password!=$admin_password_2)                  { $oops .= "[".ERROR."] ".NEWPWMATCH."<br>"; }
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($admin_password_3))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops .= "[".ERROR."] ".YOURPWINVALID."!<br>";
      $insert_sql = "INSERT INTO $db_table (admin_id,
                                            admin_realname,
                                            admin_email,
                                            admin_username,
                                            admin_password,
                                            admin_level) VALUES (NULL,
                                                                 '$admin_realname',
                                                                 '$admin_email',
                                                                 '$admin_username',
                                                                 '".md5($admin_password)."',
                                                                 '$admin_level')";
     }
     elseif ($submit&&$do=="edit")
     {
        if (!is_valid_email(strtolower(trim($admin_email))))     { $oops .= "[".ERROR."] ".EMAILINVALID."<br>"; }
        if ($admin_password&&strlen(strval($admin_password))<6)  { $oops .= "[".ERROR."] ".NEWPWSHORT."<br>"; }
        if ($admin_password&&$admin_password!=$admin_password_2) { $oops .= "[".ERROR."] ".NEWPWMATCH."<br>"; }
        $result = mysql_query("SELECT * FROM admin WHERE admin_username='".$this_admin["admin_username"]."' AND admin_password='".md5(strip_tags($admin_password_3))."'",$dbh) or die (mysql_error());
        if (!$result || mysql_num_rows($result) != 1) $oops .= "[".ERROR."] ".YOURPWINVALID."!<br>";
        $admin_password = ($admin_password) ? md5($admin_password) : $this_admin_password ;
      $update_sql = "UPDATE $db_table SET admin_realname='$admin_realname',
                                          admin_email='$admin_email',
                                          admin_username='$admin_username',
                                          admin_password='$admin_password',
                                          admin_level='$admin_level' WHERE admin_id='$admin_id'";
     }
      $select_sql = "SELECT admin_id, admin_realname, admin_email, admin_username, admin_level FROM $db_table ";
      $delete_sql = array("DELETE FROM $db_table WHERE admin_id='$admin_id'");

      if ($this_admin[admin_level]!=9) {
          $update_sql = $delete_sql = NULL;
      }
?>