<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
class POP3 {
    var $server;    // POP 3 Server Address
    var $port = 110;// POP 3 Server Port
    var $sockfd;    // Socket Descriptor
    var $user;      // POP 3 Mailbox User ID
    var $passwd;    // POP 3 Mailbox User Password
    var $answer;    // POP 3 Server Answer
    var $mailsize;  // Mail size
    var $mailno;    // Mail number
    var $mailboxno; // Number of mails in mailbox
    var $mailboxsize; // How many bytes are you using in you mailbox
    var $debug = true; // Debug Class

    /*
        bool cmdOK(): Return true if the server validates the cmd, else return false;
    */
    function cmdOK() {
        $this->answer = fgets($this->sockfd, 2048);
        if($this->debug) echo "\nS: $this->answer";
        if(ereg("^\+OK", $this->answer))
            return true;
        else
            return false;
    }

    function sendCmd($cmd, $param1 = '', $param2 = '') {
        if($this->debug) echo "\nC:$cmd $param1 $param2";
        fwrite($this->sockfd, trim("$cmd $param1 $param2") . "\r\n");
    }

    function nextAnswer() {
        $line = fgets($this->sockfd, 2048);
        return (ereg("^\.\r\n$", $line)?false:$line);
    }

    /*
        bool pop3_connect(blocking mode): Connects to POP3 server and return true if succesful,
else return false
    */
    function pop3_connect($block = 'true') {
        if($this->sockfd = fsockopen($this->server, $this->port)) {
            if($this->cmdOK()) {
                //set_socket_blocking($this->sockfd, $block);
                return true;
            }
        }
        return false;
    }

    function pop3_disconnect() {
        fwrite($this->sockfd, "QUIT\r\n");
        $this->cmdOK();
        fclose($this->sockfd);
    }

    function pop3_login() {
        $this->sendCmd("USER", $this->user);
        if($this->cmdOK()) {
            $this->sendCmd("PASS", $this->passwd);
            if($this->cmdOK()) {
                return true;
            }
        }
        return false;
    }

    function pop3_stat() {
        $this->sendCmd("STAT");
        $state = $this->cmdOK();
        if(ereg("^\+OK ([0-9]+) ([0-9]+)", $this->answer, $aux)) {
            $this->mailboxno = $aux[1];
            $this->mailboxsize = $aux[2];
        }
        return $state;
    }

    function pop3_list($mailno = '') {
        $this->sendCmd("LIST", $mailno);
        $state = $this->cmdOK();
        if(ereg("^\+OK ([0-9]+) ([0-9]+)", $this->answer, $aux)) {
            $this->mailno = $aux[1];
            $this->mailsize = $aux[2];
        }
        return $state;
    }

    function pop3_top($mailno = 1, $lines = 0) {
        $this->sendCmd("TOP", $mailno, $lines);
        return $this->cmdOK();
    }

    function pop3_retr($mailno = 1) {
        $this->sendCmd("RETR", $mailno);
        return $this->cmdOK();
    }

    function pop3_dele($mailno = 1) {
        $this->sendCmd("DELE", $mailno);
        return $this->cmdOK();
    }

    function pop3_rset() {
        $this->sendCmd("RSET");
        return $this->cmdOK();
    }

    function pop3_noop() {
        $this->sendCmd("NOOP");
        return $this->cmdOK();
    }

    function pop3_uidl($mailno = '') {
        $this->sendCmd("UIDL", $mailno);
        return $this->cmdOK();
    }
}










?>
