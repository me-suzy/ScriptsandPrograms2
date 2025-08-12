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
include('inc/header.php');



$result = mysql_query("select * from url order by url");

echo "<table>";
while ($row=mysql_fetch_assoc($result)) {

		$url=$row['url'];

		echo "

        <tr><td>
        $url</td>
        <td>- <A HREF='delete.php?url=$url'>Delete URL</A></td>
        </tr>";

}

echo "</table>";

include('inc/footer.php');

mysql_close($con);

?>