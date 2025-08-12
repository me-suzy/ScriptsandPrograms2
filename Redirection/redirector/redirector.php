<?php
$domain = $_SERVER["HTTP_HOST"];
$parts = split ("\.", $domain);
$total = count($parts) - 1;
$redirect = "http://landing.domainsponsor.com/?a_id=778&domainname=".$parts[$total-1].".".$parts[$total];
header("Location: $redirect");
exit;
?>
