<?
// Written by: Brad Derstine (Brad@BizzarScripts.net)
// The next 2 lines retrieves the IP address and splits it into 4 variables
$host = getenv("REMOTE_ADDR");
list ($octet1, $octet2, $octet3, $octet4) = split ("\.", $host, 4);

// The next lines are the redirections based on octet values.  You can also use ranges with different operators. '< or = or >' You get the idea
if ($octet1 == "204") { $Redirect= "http://www.google.com"; }
elseif ($octet2 == "21") { $Redirect= "http://www.altavista.com"; }
elseif ($octet3 == "90") { $Redirect= "http://www.altavista.com"; }
else { $Redirect= "http://www.bizzarscripts.net"; }

header ("Location: ".$Redirect)
?>