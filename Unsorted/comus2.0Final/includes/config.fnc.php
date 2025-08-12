<?
function SessionID($length=30)
{ 
$Pool = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ"; 
$Pool .= "23456789abcdefghjklmnpqrstuvwxyz"; 
for($index = 0; $index < $length; $index++) 
{
$sid .= substr($Pool,(rand()%(strlen($Pool))), 1);
}
return($sid);
}
srand(time());
$session = SessionID(45);
?>
