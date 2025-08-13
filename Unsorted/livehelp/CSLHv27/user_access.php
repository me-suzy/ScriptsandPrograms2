<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/

/*  -----------------------------------------------------------------------  */ 
function RandomPassword( $passwordLength ) {
    $password = "";
    for ($index = 1; $index <= $passwordLength; $index++) {
         // Pick random number between 1 and 62
         $randomNumber = rand(1, 62);

       // Select random character based on mapping.
         if ($randomNumber < 11)
              $password .= Chr($randomNumber + 48 - 1); // [ 1,10] => [0,9]
         else if ($randomNumber < 37)
              $password .= Chr($randomNumber + 65 - 10); // [11,36] => [A,Z]
         else
              $password .= Chr($randomNumber + 97 - 36); // [37,62] => [a,z]
    }
   return $password;
}

/*  -----------------------------------------------------------------------  */ 
 function checkuser(){
   global $mydatabase,$mypassword,$username,$random;  

      if(!isset($username)) {   
        authenticate(3);
        exit;
      } else { 
        validate();
      }    
 }

/*  -----------------------------------------------------------------------  */
 function logout(){
  global $mydatabase,$rememberme,$username,$random;
   
  // change online status to offline.
    $query = "UPDATE livehelp_users set isonline='N',status='offline' WHERE username='$username' ";
    $mydatabase->sql_query($query);
       
  // log out of system.
  if($rememberme == ""){
    // generate random string for later authentication. 
    $random = RandomPassword(10);
    $query = "UPDATE livehelp_users set identity='$random',isonline='N',status='offline' WHERE username='$username' ";
    $mydatabase->sql_query($query);
    setcookie("username","", time() - 3600);
    setcookie("random","", time() - 3600);
  }

  Header("Location: login.php?err=3");
  exit;

}

/*  -----------------------------------------------------------------------  */
function authenticate($err){
    
  Header("Location: login.php?err=$err");
  exit;
}

/*  -----------------------------------------------------------------------  */
function validate(){
  global $mydatabase,$mypassword,$username,$random; 

  // does the user exist?
  $query = "SELECT * FROM livehelp_users WHERE username='$username' and isoperator='Y'";
  $data = $mydatabase->select($query);
  if (count($data) < 1){
    authenticate(1);
    exit;
  }

  // check password :
  $myrow = $data[0];
  $passwordcheck = $myrow["password"];  
  $randomcheck = $myrow["identity"]; 
  if ( ($passwordcheck != $mypassword) && ($randomcheck != $random) ){
    authenticate(2);
    exit;
  }   
}
?>
