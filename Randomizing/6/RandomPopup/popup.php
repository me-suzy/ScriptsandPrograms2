<?php
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

include('inc/config.php');


$sql="select * from url order by rand() limit 1";
$query = mysql_query($sql);
$result=mysql_fetch_array($query);

$url=$result["url"];

?>


<script>

function openpopup(){
var popurl="<? echo  "$url"; ?>"
winpops=window.open(popurl,"","<? echo  "$popupwidth"; ?>,<? echo  "$popupheight"; ?>,")
}

function get_cookie(Name) {
  var search = Name + "="
  var returnvalue = "";
  if (document.cookie.length > 0) {
    offset = document.cookie.indexOf(search)
    if (offset != -1) { // if cookie exists
      offset += search.length
      // set index of beginning of value
      end = document.cookie.indexOf(";", offset);
      // set index of end of cookie value
      if (end == -1)
         end = document.cookie.length;
      returnvalue=unescape(document.cookie.substring(offset, end))
      }
   }
  return returnvalue;
}

function loadornot(){
if (get_cookie('poppedup')==''){
openpopup()
document.cookie="poppedup=yes"
}
}

loadornot()
</script>


<? mysql_close($con); ?>