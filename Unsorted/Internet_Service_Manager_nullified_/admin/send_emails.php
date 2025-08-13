<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include_once "../conf.php";
include_once "auth.php";
//this script should be set as a cron task, or have the variable for it in conf.php set to include on each page! otherwise emails WILL NOT BE SENT OR RECIEVED!

if($email_execution_method=="include"){
 echo '<HTML><HEAD><TITLE>Checking Email</title></head><body>';
}

//first well do all the sending...


//grab all the emails to send..
$tosend=mysql_query("SELECT * FROM emails WHERE handled='0' && type='1'");

while($ts=mysql_fetch_array($tosend)){
$message="";
 if($ts[attatchments]){
  //attatchements!
    $uid = strtoupper(md5(uniqid(time())));
    $header = "From: ".$ts[from_name]."<".$ts[from_email].">\nReply-To: From: ".$ts[from_name]."<".$ts[from_email].">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: multipart/mixed; boundary=$uid\n";
    $header .= "--$uid\n";
    $header .= "Content-Type: text/plain\n";
    $header .= "Content-Transfer-Encoding: 8bit\n\n";
    $header .= $ts[message]."\n";

$att=explode(";", $ts[attatchments]);
foreach($att as $at){
    $file=$upload_dir.'/'.$at;
    $content = fread(fopen($file,"r"),filesize($file));
    $content = chunk_split(base64_encode($content));
    $name = basename($file);
    $header .= "--$uid\n";
    $header .= "Content-Type: $type; name=\"$name\"\n";
    $header .= "Content-Transfer-Encoding: base64\n";
    $header .= "Content-Disposition: attachment; filename=\"$name\"\n\n";
    $header .= "$content\n";
    $header .= "--$uid--";
 }
  //end attatchments..
 }else{
 //no attatchements just a simple send routine..
   $header="From: ".$ts[from_name]."<".$ts[from_email].">\nReply-To: ".$ts[from_name]."<".$ts[from_email].">\n";
   $message=$ts[message];;
 //end no attatchments.
 }
  mail($ts[to_email], $ts[subject], $message, $header);
mysql_query("UPDATE emails SET handled='1' WHERE id='".$ts[id]."'");
}



//now well do the receiving...
include "checkpop.php";
function getMessageType($data){
        $pre = explode("Content-Type: ", $data);
        $subject = explode("\r\n", $pre[1]);
        $sub = strtolower($subject[0]);
        return $sub;
}
function getSubject($data){
        $pre = explode("Subject: ", $data);
        $subject = explode("\r\n", $pre[1]);
        $sub = strtolower($subject[0]);
        return $sub;
}
function getBoundary($data){
        $pre = explode('boundary="', $data);
        $subject = explode('"', $pre[1]);
        $sub = $subject[0];
        return $sub;
}
function getPriority($data){
        $pre = explode('Priority:', $data);
        $subject = explode("\n", $pre[1]);
        $sub = $subject[0];
        return $sub;
}
function getEncodingType($data){
        $pre = explode('Content-Transfer-Encoding:', $data);
        $subject = explode("\n", $pre[1]);
        $sub = $subject[0];
        return $sub;
}
function getFileName($data){
        $pre = explode('filename="', $data);
        $subject = explode('"', $pre[1]);
        $sub = $subject[0];
        return $sub;
}
function getFrom($data, $splitter){
        $pre = explode("\n".$splitter, $data);
        list($from,) = explode("\n", $pre[1]);
                  list($from_name, $from_email)=explode("<", $from);
                  $from_email=str_replace(">", "", $from_email);
                 $from_name=str_replace('"', "", $from_name);
                   if(!$from_email){$from_email=$from_name;}
                   if(!$from_name){$from_name=$from_email;}

        return $from_name.'///////////'.$from_email;
}

function getPlainTextMessage($data)
{
     $bits=explode("\r\n\r\n", $data);
       $num=count($bits);
       for($i=1; $i<=$num; $i++){
       $msg.=$bits[$i];
       }
       return $msg;
}

function decodeattatchent($type, $data)
{
    if(trim($type)=="quoted-printable"){
      //type of encoding is quoted-printable..
      return quoted_printable_decode ($data);
    }elseif(trim($type)=="base64"){
      return base64_decode($data);
    }else{
         return $data;
    }
}

function SupportParent($sub, $email)
{
    list(,$parent)=explode(":", $sub);
    $parent=trim($parent);
    //check if this parent ticket is from the same email or contact!
    if(mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE id='$parent' && email LIKE '$email'"))){
    return $parent;
    }
}



//first do all the admins email accounts or just this admins account if its include method..!
if($email_execution_method=="include"){
    $admins=mysql_query("SELECT * FROM admins WHERE id='$admin_id' && last_check<'".(time()-$email_check_interval)."'");
    mysql_query("UPDATE admins SET last_check='".time()."' WHERE id='$admin_id'");
}else{
    $admins=mysql_query("SELECT * FROM admins ORDER BY rand()");
}


while($ad=mysql_fetch_array($admins)){
if($ad[mail_server]){
//create the mail retrieval class object
$pop3 = new POP3;
$pop3->server = $ad[mail_server];
$pop3->user   = $ad[mail_username];
$pop3->passwd = $ad[mail_password];
$pop3->debug = false;
if($pop3->pop3_connect()) {
    $pop3->pop3_login();
    $pop3->pop3_stat();
    if($pop3->pop3_list())
        while($line = $pop3->nextAnswer()){
            list($id)=explode(" ",$line);
            $toget[$id]=1;
        }

    if($toget){foreach($toget as $id=>$sd){
        if($pop3->pop3_retr($id))
        while($line = $pop3->nextAnswer()){
            $emails[$id].=$line;
        }
    }}
}


if($emails){foreach($emails as $emailid=>$email){$newemail=1;
    $type=getMessageType($email);
    list($type,)=explode(";", $type);
    $type.=";";
    if(!$type || $type=="text/plain;" || $type=="text/html;"){
        if($type=="text/html;"){$mtype="html";}
        //just a plain text email so its easy!
    $sub=getSubject($email);
    $from=getFrom($email, "From: ");
    $to=getFrom($email, "To: ");
    list($to_name, $to_email)=explode("///////////", $to);
    list($from_name, $from_email)=explode("///////////", $from);
    $message=getPlainTextMessage($email);
    $priority=getPriority($email);
    list($priority,)=explode("(", $priority);
       mysql_query("INSERT INTO emails SET to_id='".$ad[id]."', message_type='$mtype', type='0', to_email='$to_email', from_email='$from_email', to_name='$to_name', from_name='$from_name', priority='$priority', subject='$sub', message='$message', date='".time()."'");
       //delete the email from the server..
       $pop3->pop3_dele($emailid);
    }elseif($type=="multipart/alternative;" || $type=="multipart/mixed;"){
     //must be multipart or something..
         $allats="";
         $boundary=getBoundary($email);
         $parts=explode("--$boundary", $email);
         foreach($parts as $id=>$part){
                        if($id>0){
                            $type=getMessageType($part);
                            //now check if its a file or a message..
                            $filename=getFileName($part);
                            if($filename){
                             //yeah its a file
                               $contents=getPlainTextMessage($part);
                               $enctype=getEncodingType($part);
                               $contents=decodeattatchent($enctype, $contents);
                               $contents=addslashes($contents);
                               mysql_query("INSERT INTO attatchments SET filename='$filename', type='$type', data='$contents'");
                               $allats.=mysql_insert_id().";";
                            }else{
                             //nah its just part of the email..
                             $emailtype[$type]=getPlainTextMessage($part);
                            }
                        }
         }
         if(isset($emailtype["text/html;"])){$message_type="html";$message=$emailtype["text/html;"];}else{$message=$emailtype["text/plain;"];}
             $sub=getSubject($parts[0]);
    $from=getFrom($parts[0], "From: ");
    $to=getFrom($parts[0], "To: ");
    list($to_name, $to_email)=explode("///////////", $to);
    list($from_name, $from_email)=explode("///////////", $from);
    $priority=getPriority($parts[0]);
    list($priority,)=explode("(", $priority);
       mysql_query("INSERT INTO emails SET to_id='".$ad[id]."', attatchments='$allats', message_type='$message_type', type='0', to_email='$to_email', from_email='$from_email', to_name='$to_name', from_name='$from_name', priority='$priority', subject='$sub', message='$message', date='".time()."'");
       //delete the email from the server..
       $pop3->pop3_dele($emailid);
    }else{
     //dont know this type!
    }
}}
$pop3->pop3_disconnect();
}}
//end admin email accounts!


//now the support addresses!
if($email_execution_method=="include"){
//first check if this admin is a support technician!..
if(mysql_num_rows(mysql_query("SELECT * FROM admins WHERE id='$admin_id' && privelages LIKE '%support%'"))){
$admins=mysql_query("SELECT * FROM support_email_addresses");
}
}else{
$admins=mysql_query("SELECT * FROM support_email_addresses");
}

while($ad=mysql_fetch_array($admins)){
if($ad[mail_server]){
//create the mail retrieval class object
$pop3 = new POP3;
$pop3->server = $ad[mail_server];
$pop3->user   = $ad[mail_username];
$pop3->passwd = $ad[mail_password];
$pop3->debug = false;
if($pop3->pop3_connect()) {
    $pop3->pop3_login();
    $pop3->pop3_stat();
    if($pop3->pop3_list())
        while($line = $pop3->nextAnswer()){
            list($id)=explode(" ",$line);
            $toget[$id]=1;
        }

    if($toget){foreach($toget as $id=>$sd){
        if($pop3->pop3_retr($id))
        while($line = $pop3->nextAnswer()){
            $emails[$id].=$line;
        }
    }}
}


if($emails){foreach($emails as $emailid=>$email){
    $type=getMessageType($email);
    list($type,)=explode(";", $type);
    $type.=";";
    if(!$type || $type=="text/plain;" || $type=="text/html;"){
        if($type=="text/html;"){$mtype="html";}
        //just a plain text email so its easy!
    $sub=getSubject($email);
    $from=getFrom($email, "From: ");
    $to=getFrom($email, "To: ");
    list($to_name, $to_email)=explode("///////////", $to);
    list($from_name, $from_email)=explode("///////////", $from);
    $parent=SupportParent($sub, $from_email);
    $contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE email LIKE '$from_email'"));
    $message=strip_tags(getPlainTextMessage($email));
    $priority=getPriority($email);
    if($priority==3){$priority=0;}
    list($priority,)=explode("(", $priority);
       mysql_query("INSERT INTO support_tickets SET parent='$parent', priority='$priority', department='".$ad[id]."', email='$from_email', contact_id='".$contact[id]."', name='$from_name', subject='$sub', details='$message', date='".time()."', was_email='1', attatchments='$allats'");
       //delete the email from the server..
       $pop3->pop3_dele($emailid);
    }elseif($type=="multipart/alternative;" || $type=="multipart/mixed;"){
     //must be multipart or something..
         $allats="";
         $boundary=getBoundary($email);
         $parts=explode("--$boundary", $email);
         foreach($parts as $id=>$part){
                        if($id>0){
                            $type=getMessageType($part);
                            //now check if its a file or a message..
                            $filename=getFileName($part);
                            if($filename){
                             //yeah its a file
                               $contents=strip_tags(getPlainTextMessage($part));
                               $enctype=getEncodingType($part);
                               $contents=decodeattatchent($enctype, $contents);
                               $contents=addslashes($contents);
                               mysql_query("INSERT INTO attatchments SET filename='$filename', type='$type', data='$contents'");
                               $allats.=mysql_insert_id().";";
                            }else{
                             //nah its just part of the email..
                             $emailtype[$type]=strip_tags(getPlainTextMessage($part));
                            }
                        }
         }
              if(isset($emailtype["text/html;"])){$message_type="html";$message=$emailtype["text/html;"];}else{$message=$emailtype["text/plain;"];}

             $sub=getSubject($parts[0]);
    $from=getFrom($parts[0], "From: ");
    list($from_name, $from_email)=explode("///////////", $from);
                 $parent=SupportParent($sub, $from_email);
				 if($parent){
				 //see who the parent request was allocated to!
				 $parent=mysql_fetch_array(mysql_query("SELECT * FROM support_tickets WHERE id='$parent'"));
				 }
    $priority=getPriority($parts[0]);
    if($priority==3){$priority=0;}
    list($priority,)=explode("(", $priority);
       mysql_query("INSERT INTO support_tickets SET allocated='".$parent["allocated"]."', parent='$parent', priority='$priority', department='".$ad[id]."', email='$from_email', contact_id='".$contact[id]."', name='$from_name', subject='$sub', details='$message', date='".time()."', was_email='1', attatchments='$allats'");
       //delete the email from the server..
       $pop3->pop3_dele($emailid);
    }else{
     //dont know this type!
    }
}}
$pop3->pop3_disconnect();
}}




if($email_execution_method=="include"){
if($newemail){
    echo '<font face="'.$admin_font.'" size=2><B>';
echo '<script language="javascript">
  document.write("New Email!")
  window.focus()
</script>
<BR><font size=1><a href="javascript: window.close()">Close Me</a>
</body></html>';
}else{
echo '<script language="javascript">
  window.close()
</script></body></html>';
}}
?>
