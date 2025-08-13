<?$t .= " ". microtime();
$s = explode(" ", $t);
$s_time = "Script Execution time: ".(floor((($s[3] - $s[1])+($s[2] - $s[0]))*10000)/10000)." s.";?>