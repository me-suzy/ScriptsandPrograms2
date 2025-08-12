<?php
class oup {
    var $sec = 180;
    function view() {
    $this->update();                                                                               
    return "Users online: ".$this->TotalUserNum.", Hits number : ".$this->TotalHits;   
                }
    function update() {
        global $mysql_base,$mysql_online;
        mysql_query("DELETE FROM ".$mysql_online." WHERE (time < DATE_SUB(NOW(), INTERVAL $this->sec SECOND) AND NOW() > $this->sec) or time > NOW()")  or die("Delete Error<br>".mysql_error());
		mysql_query("INSERT INTO ".$mysql_online." VALUES (NOW(),INET_ATON('".ip()."'))") or die("Write Error<br>".mysql_error());       
        
        $result = mysql_query("SELECT count(DISTINCT ip) as total FROM ".$mysql_online) or die("Read Error<br>".mysql_error());
        $trows = mysql_fetch_array($result);
        $this->TotalUserNum = $trows[total]; 
        
        $result = mysql_query("SELECT count(ip) as total FROM ".$mysql_online) or die("Read Error<br>".mysql_error());
        $trows = mysql_fetch_array($result);
        $this->TotalHits = $trows[total];
        } 
}
?>