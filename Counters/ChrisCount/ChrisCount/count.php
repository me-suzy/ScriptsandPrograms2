<BR><BR><BR><BR><CENTER>YOU ARE VISITOR NUMBER<B>	
<?php
$fp=fopen("acc.txt","r");
$count=fgets($fp,1024);
fclose($fp);
$fw=fopen("acc.txt","w");
$cnew=$count+1;
$countnew=fputs($fw,$count+1);
echo "$cnew";
fclose($fw); 
?> </B>		TO MY WEB PAGE