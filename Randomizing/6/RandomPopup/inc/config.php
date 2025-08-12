<?
#########################################################
# Random Popup                                          #
#########################################################
#                                                       #
# Author: Doni Ronquillo                                #
#                                                       #
# This script and all included functions, images,       #
# and documentation are copyright 2003                  #
# free-php.net (http://free-php.net) unless             #
# otherwise stated in the module.                       #
#                                                       #
# Any copying, distribution, modification with          #
# intent to distribute as new code will result          #
# in immediate loss of your rights to use this          #
# program as well as possible legal action.             #
#                                                       #
#########################################################

$scriptname="Random Popup";
$usernam="";	// MySQL username
$pass="";		// MySQL Password
$db="";			// MySQL database

$con = mysql_connect("localhost", "$usernam", "$pass") or die("Invalid server or user."); 	// Connects to MySQL Database
mysql_select_db("$db", $con);

?>