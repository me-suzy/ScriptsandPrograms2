<?php
//Title:                RandMail
//Filename:             randmail.php
//Function:             Creates a nasty spammer honeypot
//Date last modified:   8 Nov 04
//Coder:                Leif Gregory
//Website:              http://www.PCWize.com
//E-mail:               phpcoder@pcwize.com

//Copyright (C) 2004  Leif Gregory

//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.

//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

//You should have received a copy of the GNU General Public License
//along with this program; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.


$generateNumber = "50"; #number of addresses to generate
$userNameMin    = "4"; #Everything before the @ symbol
$userNameMax    = "12"; #Everything before the @ symbol
$hostNameMin  = "4"; #Everything after the @ symbol but before the .com, .net etc.
$hostNameMax  = "12"; #Everything after the @ symbol but before the .com, .net etc.

echo "<HTML><BODY>";

for ($i=1; $i<50; $i++)
{
  $userLength = rand($userNameMin,$userNameMax);
  $hostLength = rand($hostNameMin,$hostNameMax);

  for ($j=0; $j<$userLength; $j++)
  {
    $upperLowerNumber = rand(1,10);
  
    if ($upperLowerNumber > 9)          #Uppercase letters if 10
      $gimmeLetter = chr(rand(65,90));
    elseif ($upperLowerNumber < 8)      #Lowercase letters if 0 - 7
      $gimmeLetter = chr(rand(97,122));
    else 
    {
      //Making sure a number doesn't become the first character of the username
      if ($userName != "") 
        {
          $gimmeLetter = rand(0,9);     #Numbers if an 8 or 9
        }
      else
        $i--;
    }
    
    //Building the username one character at a time
    $userName .= $gimmeLetter; 
    $gimmeLetter="";
  } 
  
  for ($j=0; $j<$hostLength; $j++)
  {
    $upperLowerNumber = rand(1,10);
  
    if ($upperLowerNumber > 9)          #Uppercase letters if 10
      $gimmeLetter = chr(rand(65,90));
    elseif ($upperLowerNumber < 8)      #Lowercase letters if 0 - 7
      $gimmeLetter = chr(rand(97,122));
    else 
    {
      //Making sure a number doesn't become the first character of the hostname
      if ($hostName != "") 
        {
          $gimmeLetter = rand(0,9);
        }
      else
        $i--;
    }    
    
    //Building the hostname one character at a time
    $hostName .= $gimmeLetter; 
    $gimmeLetter="";
  } 
  
  //Based on the last random numeber between 1 - 10 we're picking a domain
  switch($upperLowerNumber)
  {
    case "1":
      $domainName = ".com";
      break;
    case "2":
      $domainName = ".net";
      break;
    case "3":
      $domainName = ".org";
      break;
    case "4":
      $domainName = ".ru";
      break;
    case "5":
      $domainName = ".tw";
      break;
    case "6":
      $domainName = ".edu";
      break;
    case "7":
      $domainName = ".gov";
      break;
    case "8":
      $domainName = ".mil";
      break;
    case "9":
      $domainName = ".us";
      break;
    case "10":
      $domainName = ".uk";
      break;
  }  

  //Let's hook them spammers up!
  //We're using str_shuffle() to mix up the username and hostname so they don't 
  //match the mailto: address. Then we're using the shuffled names to look like 
  //first and last names.
  //You'll end up with something like this: rI3o yqjflfiqv e-mail
  //and where is says e-mail you'll have a hyperlink: mailto:I3or@vjqqyffil.net
  echo str_shuffle($userName) . " " . str_shuffle($hostName) . 
       " <a href=mailto:" . $userName . "@" . $hostName . $domainName . 
       ">e-mail</a><br>";
  
  $userName="";
  $hostName="";
}
echo "</BODY></HTML>";
echo '<a href="' . $_SERVER['PHP_SELF'] . '">Some more?</a>';
?>