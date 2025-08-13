<?php

/*
+--------------------------------------------------------------------------
|   > $$HTACCESS.CLASS.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

class htaccess {

  var $WorkDir = "";
  var $HtaccessFile = "";
  var $HtpasswdFile = "";
  var $AuthType = "Basic";
  var $AuthName = "MembersArea";
  var $ProtectRequire = "require valid-user";
  var $DefaultUser_Name = "";
  var $DefaultUser_Pass = "";
  var $UseDefaultUser = "1";

  /*
    Init class:
    @ set path to .htaccess and .htpasswd files [ optional ]
  */
  function htaccess($FHtaccess="",$FHtpasswd="") {
     $this->HtaccessFile = $FHtaccess;
     $this->HtpasswdFile = $FHtpasswd;
  }

  /*
    @ set path to workdir ( for CSV files )
  */
  function setWorkDir($path) {
     $this->WorkDir = $path;
  }

  /*
    @ set path to .htaccess file [ param $FHtaccess required ]
  */
  function setHtaccess($FHtaccess) {
     $this->HtaccessFile = $FHtaccess;
  }

  /*
    @ set path to .htpasswd file [ param $FHtpasswd required ]
  */
  function setHtpasswd($FHtpasswd) {
     $this->HtpasswdFile = $FHtpasswd;
  }

  /*
    @ set AuthType
  */
  function setAuthType($AuthType) {
     $this->AuthType = $AuthType;
  }

  /*
    @ set AuthName
  */
  function setAuthName($AuthName) {
     $this->AuthName = $AuthName;
  }

  /*
    @ set ProtectRequire value
  */
  function setProtectRequire($ProtectRequire) {
     $this->ProtectRequire = $ProtectRequire;
  }

  /*
    @ set use we default user or not
  */
  function setUseDefaultUser($Is) {
	 if ( $Is == '1') {
        $this->UseDefaultUser = '1';
     } else {
        $this->UseDefaultUser = '0';
     }
  }

  /*
    @ set default user name if needed
  */
  function setDefaultUser_Name($username) {
     $this->DefaultUser_Name = $username;
  }

  /*
    @ set default user password if needed
  */
  function setDefaultUser_Pass($password) {
     $this->DefaultUser_Pass = crypt($password);
  }

  /*
    @ create .htaccess and .htpassd files
  */
  function ProtectDir() {
    global $INFO;

     $fh = @fopen($this->HtaccessFile,"w+");
      @fputs($fh, "AuthType {$this->AuthType}\n");
      @fputs($fh, "AuthName {$this->AuthName}\n");
      @fputs($fh, "AuthUserFile {$this->HtpasswdFile}\n");
      @fputs($fh, "{$this->ProtectRequire}");
     @fclose($fh);

     $fh = @fopen($this->HtpasswdFile,"w+");

       /*
         if default user for each new pretected dir is set and we must use it,
         add it to new created .htpasswd file
       */
       if ( $this->UseDefaultUser == '1' ) {

           $this->setDefaultUser_Name( trim($INFO['DEFAULT_HTA_USER']) );
           $this->setDefaultUser_Pass( trim($INFO['DEFAULT_HTA_PASS']) );

	       @fputs($fh,"{$this->DefaultUser_Name}:{$this->DefaultUser_Pass}\x0A");
       }
       @fclose($fh);
  }

  /*
    @ remove directory protection
    @ return TRUE on success or FALSE on failure
  */
  function UnProtectDir() {
    $is_rem_htaccess = @unlink($this->HtaccessFile);
	$is_rem_htpasswd = @unlink($this->HtpasswdFile);
     if ( $is_rem_htaccess && $is_rem_htpasswd ) {
        return TRUE;
     } else {
         return FALSE;
       }
  }

  /*
  	@ return an array of existing users in .htpasswd
  */
  function getUsers() {
    $users = array();
    $i = 0;
    $fh = @fopen($this->HtpasswdFile,"r");
     while ( $line = @fgets($fh) ) {
       $lineArr = explode(":",$line);
       $users[$i][0] = $lineArr[0];
       $users[$i][1] = $lineArr[1];
       $i++;
     }
    @fclose($fh);
    return $users;
  }

  /*
    @ add new user to .htpasswd
    @ return TRUE on succeed of FALSE on failure
  */
  function addUser($username,$password) {
     $fh = @fopen($this->HtPasswdFile,"r");
        $exists = FALSE;
        while ( $line = @fgets($fh) ) {
            $lineArr=explode(":",$line);
            if ( $username == $lineArr[0] ) {
                $exists = TRUE;
             }
        }

        if($exists == FALSE){
            $fh = @fopen($this->HtpasswdFile,"a");

            $password=crypt($password);
            $newLine=$username.":".$password."\n";

            @fputs($fh,$newLine);
            @fclose($fh);
            return TRUE;
        }else{
            return TRUE;
        }
  }

  /*
    @ set/edit user password
    @ return TRUE in succeed or FALSE on failure
  */
  function setUserPass($username,$new_password) {
     $newUserlist="";

        $fh = @fopen($this->HtpasswdFile,"r");
        $ix=0;
        for ($i = 0; $line = @fgets($fh); $i++) {
            $lineArr=explode(":",$line);
            if ( $username != $lineArr[0] && $lineArr[0] != "" && $lineArr[1] != "" ) {
                $newUserlist[$i][0] = $lineArr[0];
                $newUserlist[$i][1] = $lineArr[1];
                $ix++;
            } elseif ( $lineArr[0] != "" && $lineArr[1] != "" ) {
                $newUserlist[$i][0] = $lineArr[0];
                $newUserlist[$i][1] = crypt($new_password)."\x0A";
                $isSet = TRUE;
                $ix++;
            }
        }
        @fclose($fh);
        $line = "";

//        unlink($this->HtpasswdFile);

        $fh = @fopen($this->HtpasswdFile,"w");
        for ( $i = 0; $i<count($newUserlist); $i++ ) {
            $line = $newUserlist[$i][0].":".$newUserlist[$i][1];
            @fputs($fh,$line);
        }
        @fclose($fh);

        if ( $isSet == TRUE ) {
            return TRUE;
        }else{
            return FALSE;
        }
  }

  /*
    @ remove user from .htpasswd
    @ return TRUE on success or FALSE on failur
  */
  function remUser($username) {
     $fh = @fopen($this->HtpasswdFile,"r");
        $i=0;
        while ( $line = @fgets($fh) ) {
            $lineArr = explode(":",chop($line));
            if ( $username != $lineArr[0] ) {
                $newUserlist[$i][0]=$lineArr[0];
                $newUserlist[$i][1]=$lineArr[1];
                $i++;
            } else {
                $isDel = TRUE;
            }
        }
        @fclose($fh);
        $line = "";

//        unlink($this->HtpasswdFile);

        // Writing names back to file
        $fh = @fopen($this->HtpasswdFile,"w");
        for ( $i = 0; $i<count($newUserlist); $i++ ) {
        	$line = $newUserlist[$i][0].":".$newUserlist[$i][1]."\x0A";
            @fputs($fh,$line);
        }
        @fclose($fh);

        /* if we just remove last user, remove directory protection too
        $users = $this->getUsers();
        if ( count($users) == 0 ) {
          $this->UnProtectDir();
          $isDel = TRUE;
        }
       */

        if ( $isDel == TRUE ) {
            return TRUE;
        } else {
            return FALSE;
          }
  }

  /*
  	@ convert .htpasswd to CSV format
  */
  function toCSV($sent=TRUE) {
     $fh = @fopen($this->HtpasswdFile,"r");
     $i = 0;
      while ( $line = @fgets($fh) ) {
         $lineArr = explode(":",chop($line));
	 $userlist[$i][0] = $lineArr[0];
         $userlist[$i][1] = $lineArr[1];
	 $i++;
      }
     @fclose($fh);
     $to_out = "";
     $csvFile = "htpasswd-".time().".csv";
     $topLine = "UserName;PassWord";
     $to_out .= $topLine . "<br>";
     $fh = @fopen("{$this->WorkDir}/{$csvFile}","w");
      @fputs($fh,"{$topLine}\x0A");
     for ($x=0;$x<count($userlist);$x++) {
       if ( preg_match("/,/",$userlist[$x][0]) ) {
          $userlist[$x][0] = "'".$userlist[$x][0]."'";
       }
       if ( preg_match("/,/",$userlist[$x][1]) ) {
          $userlist[$x][1] = "'".$userlist[$x][1]."'";
       }
       $newLine = $userlist[$x][0] . ";" . $userlist[$x][1];
       @fputs($fh,"{$newLine}\x0A");
       $to_out .= $newLine . "<br>";
     }
     @fclose($fh);

     if ( $sent ) {
        header("Content-type: text/csv");
        header("Content-Length: ".filesize("{$this->WorkDir}/{$csvFile}"));
        header("Content-Disposition: attachment; filename=$csvFile");
        readfile("{$this->WorkDir}/{$csvFile}");
	unlink("{$this->WorkDir}/{$csvFile}");
     } else {
         return $to_out;
       }

  }

}  // End of class htaccess


?>