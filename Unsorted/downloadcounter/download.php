<?php
/*
                    ###########################                                 
                    #   DownloadCounter! v1.1 #                        
                    #   by Mohammed Jassim    #                     
                    #   email: lazy@mohd.biz  #                       
                    #   http://mohd.biz       #                 
                    #   http://mj-smile.com   #
                    ###########################

    Description:
	  This is a simple and powerful download counter which will log the details of anyone
	  who download the file specify, it will log their IP Address, Remote Address
	  Browser type and Operating system, you can show the number of downloads anywhere 
	  in your website through SSI. You can easily modify and add new downloads
	  through a protected admin panel, MySQL and SSI are required.

    Copyright (C) 2002  by Mohammed Jassim mj-smile.com 
 
    This program is free software; you can redistribute it and/or modify  
    it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or 
    (at your option) any later version. 

    This program is distributed in the hope that it will be useful, 
    but WITHOUT ANY WARRANTY; without even the implied warranty of 
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
    GNU General Public License for more details. 

    You should have received a copy of the GNU General Public License 
    along with this program; if not, write to the Free Software 
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA 
*/  
include"config.php";
$con=mysql_connect($db_host,$db_user,$db_pass);
$result=mysql_select_db($db_name,$con) or die (mysql_error());
if ($action == ""){
$sql="SELECT * FROM download_$title";
$result=mysql_query($sql);
$ref=$_SERVER["HTTP_REFERER"];
$agent=$_SERVER["HTTP_USER_AGENT"];
$IP=$_SERVER["HTTP_X_FORWARDED_FOR"];
$Remote=$_SERVER["REMOTE_ADDR"];
$query = "INSERT INTO download_$title (IP, RemoteAddr, agent, ref) VALUES ('$IP','$Remote','$agent','$ref')";  
$result = mysql_query($query) or die (mysql_error());
$sql="SELECT URL FROM $table WHERE title='$title'";
$result=mysql_query($sql);
$data=mysql_fetch_assoc($result);
$url=$data['URL'];
header("Location:$url"); 
}
if ($action == "show")
{
$query = "SELECT * FROM download_$title";  
$result = mysql_query($query); 
$numrows = mysql_num_rows($result); 
}
if ($type == "SSI")
printf("%d",$numrows);
else
print "document.write($numrows)";

  
?>