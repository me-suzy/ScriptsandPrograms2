<?php
/*	Count Users for develooping flash chat.	    */
/*	version 1.6.5 Created by Juan Carlos Pos	    */
/*	juancarlos@develooping.com	                */
        require ('required/config.php');
        $lines = file("required/users.txt");
        $a = count($lines);
        $counter=0;
        $users_counter=0;
        for($i = $a; $i >= 0 ;$i=$i-2){
        $each_user = strval($lines[$i]);//each connected user
        $each_user = str_replace ("\n","", $each_user);
        $each_password = strval($lines[$i+1]);
        $each_password = str_replace ("\n","", $each_password);
        $each_password = trim ($each_password);
        $userisgood=1;
        if (($each_password=="kicked")or($each_password=="banned")){$userisgood=0;}
        if (($each_user!="") and ($userisgood==1)){
        $users_counter++;
        $counter=$users_counter;
        }
        }
        

        echo "&users_counter=";
echo urlencode($users_counter);

?>