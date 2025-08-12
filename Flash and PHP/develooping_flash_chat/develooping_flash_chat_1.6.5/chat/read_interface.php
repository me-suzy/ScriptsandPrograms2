<?php 
/*	read interface for develooping flash chat           */
/*	develooping flash chat   		                    */
/*	juancarlos@develooping.com	                        */
/*	version 1.6.5                                         */

require ('required/config.php');
echo "&url=".urlencode($url);
echo "&pre=".urlencode($before_name)."&post=".urlencode($after_name);
echo "&output=".urlencode($conn);
echo "&you_are=".urlencode($you_are);
echo "&intro_text=".utf8_encode($intro_text);
echo "&private_message_to=".urlencode($private_message_to);
echo "&connected_users=".urlencode($connected_users);
echo "&private_message_text=".urlencode($private_message_text);
echo "&enter_string=".urlencode($enter_string);
echo "&bye_user=".urlencode($bye_user);
?>