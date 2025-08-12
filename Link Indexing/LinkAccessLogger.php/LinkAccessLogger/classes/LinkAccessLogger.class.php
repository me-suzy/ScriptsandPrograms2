<?php
# LinkAccessLogger.class.php -- Version 23-Sep-2003
# Copyright (c) 2003 Jochen Kupperschmidt <jochen@kupperschmidt.de>
# Released under the terms of the GNU General Public License
#    _                               _
#   | |_ ___ _____ ___ _ _ _ ___ ___| |_
#   |   | . |     | ._| | | | . |  _| . /
#   |_|_|___|_|_|_|___|_____|___|_| |_|_\
#     http://homework.nwsnet.de/

class LinkAccessLogger
{
    function readLog($logfile)
    {
        LinkAccessLogger::checkFile($logfile);
        return implode('', file($logfile));
    }

    function writeLog($logfile)
    {
        LinkAccessLogger::checkFile($logfile);

        # collect date, ip address, hostname and referer
        $ip = $_SERVER['REMOTE_ADDR'];
        $data = date('Y-m-d H:i ') . str_pad($ip, 16) . gethostbyaddr($ip)
              . ' ' . $_SERVER['HTTP_REFERER'] . "\n";

        # store data to file
        $fp = fopen($logfile, 'a');
        fputs($fp, $data);
        fclose($fp);
    }

    function checkFile($logfile)
    {
        # check if file exists
        # fix with 'touch <file>'
        if (!file_exists($logfile)) {
            exit('file ' . $logfile . ' does not exist.');
        }

        # check if file is writable by everyone
        # fix with 'chmod 666 <file>'
        if (!is_writable($logfile)) {
            exit('file ' . $logfile . ' exists but is not writable.');
        }
    }
}
?>
