<?php
#######################################################################
#                            Randex 1.21                              #
#            By Thomas Tsoi <admin@tecaweb.com> 2001-05-12            #
#          Copyright 2001 (c) Teca Web. All rights reserved.          #
#######################################################################
#                                                                     #
# Teca Web:                                                           #
#   http://www.tecaweb.com/                                           #
# Teca Scripts:                                                       #
#   http://www.teca-scripts.com/                                      #
# Support:                                                            #
#   http://www.teca-scripts.com/forum/                                #
#                                                                     #
# ################################################################### #
#                                                                     #
#        You can distribute this script and use it freely as          #
#          long as this header is not edited in the script.           #
#                                                                     #
#######################################################################

############ This is the only thing required
# chmod 644
$rand_file = "randex.txt";

############ Advanced Options ; Ignore the following if you are not interested
# chmod 666
$rand_data = "data.txt";
# chmod 666
$rand_ip   = "iplog.txt";
# number of ip to store in iplog.txt
$number_ip = 20;
########################################################
#
# That's it, setup ends.
#
########################################################

if (is_writeable($rand_data) && is_writeable($rand_ip) && $number_ip > 0) {
	$advanced = 1;
	}

$codes = split("\[\%\%BREAK\%\%\]", join("", file($rand_file)));
srand((double) microtime()*1000000);
$index = rand(0, count($codes)-1);

if (($advanced) && count($codes) > 0) {
	##################
	# Check IP
	$lines = file($rand_ip);

	for ($i=0;$i<count($lines);$i++) {
		$line = $lines[$i];
		list($ip, $id) = split("\|\|", chop($line));
		
		if ($ip == getenv("REMOTE_ADDR")) {
			$lastid = $id;
			do {
				$index = rand(0, count($codes)-1);
				}
			while ($index == $lastid);
			}
		}

	$fh = fopen($rand_ip, "w");
	fwrite($fh, getenv("REMOTE_ADDR")."||$index\n");
	$count = 1;
	for ($i=0;$i<count($lines);$i++) {
		$line = $lines[$i];
		list($ip, $id) = split("\|\|", $line);
		if ($ip != getenv("REMOTE_ADDR") && $count<$number_ip) {
			fwrite($fh, $line);
			$count++;
			}
		}
	fclose($fh);

	##################
	# Count hits
	unset($line);
	$lines = file($rand_data);

	$flag = 0;
	$fh = fopen($rand_data, "w");
	for ($i=0;$i<count($lines);$i++) {
		$line = $lines[$i];
		if (ereg("^html$index\|", $line)) {
			list ($filename, $hits) = split("\|", chop($line));
			$hits++;
			fwrite($fh, "html$index|$hits\n");
			$flag = 1;
			}
		else {
			fwrite($fh, $line);
			}
		}
	if ($flag == 0) {
		fwrite($fh, "html$index|1\n");
		}
	fclose($fh);
	}

print $codes[$index];
?>