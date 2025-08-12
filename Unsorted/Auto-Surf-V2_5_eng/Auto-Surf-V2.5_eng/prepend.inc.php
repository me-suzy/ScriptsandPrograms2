<?php
include("header.inc.php");

$mysql_host='host';
$mysql_username='User';
$mysql_password='Pass';
$mysql_db='Datenbank';

//////////////////////////////////////////////////////////////////////////////////////////
// ab hier nichts mehr ändern wenn du nicht genau weißt was du tust //////////////////////
//////////////////////////////////////////////////////////////////////////////////////////


$result77 = mysql_query("SELECT startseite FROM `demo_a_texte`");
$myrow77 = mysql_fetch_row($result77);
$starttext = $myrow77[0];

$result12 = mysql_query("SELECT aa, ab, ba, bb, ca, cb, da, db FROM `demo_a_grosse`");
$myrow12 = mysql_fetch_row($result12);

$einsa = $myrow12[0];
$einsb = $myrow12[1];
$zweia = $myrow12[2];
$zweib = $myrow12[3];
$dreia = $myrow12[4];
$dreib = $myrow12[5];
$viera = $myrow12[6];
$vierb = $myrow12[7];

$result11 = mysql_query("SELECT code FROM `demo_a_seitenbanner`");
$myrow = mysql_fetch_row($result11);
$banner = $myrow[0];
$result10 = mysql_query("SELECT logoa FROM `demo_a_logo`");
$myrow = mysql_fetch_row($result10);
$logo = $myrow[0];

$result5 = mysql_query("SELECT faq FROM `demo_a_faq`");
$myrow5 = mysql_fetch_row($result5);

$faq = $myrow5[0];

$result6 = mysql_query("SELECT regeln FROM `demo_a_regeln`");
$myrow6 = mysql_fetch_row($result6);

$regeln = $myrow6[0];

$result7 = mysql_query("SELECT besuchera, besucherb, besucherc, besucherd, besuchere, besucherf, bannera, bannerb, bannerc FROM `demo_a_werbpreis`");
$myrow = mysql_fetch_row($result7);

$besa = $myrow[0];
$besb = $myrow[1];
$besc = $myrow[2];
$besd = $myrow[3];
$bese = $myrow[4];
$besf = $myrow[5];
$baa = $myrow[6];
$bab = $myrow[7];
$bac = $myrow[8];

$result8 = mysql_query("SELECT meinebannera, meinebannerurla, meinebannerb, meinebannerurlb, meinebannerc, meinebannerurlc, meinebannerd, meinebannerurld FROM `demo_a_werbebanner`");
$myrow1 = mysql_fetch_row($result8);

$banna = $myrow1[0];
$bannaurl = $myrow1[1];
$bannb = $myrow1[2];
$bannburl = $myrow1[3];
$bannc = $myrow1[4];
$banncurl = $myrow1[5];
$bannd = $myrow1[6];
$banndurl = $myrow1[7];

$result20 = mysql_query("SELECT za, zb, zc, zd  FROM `demo_a_zahl`");
$myrow20 = mysql_fetch_row($result20);

$zufa = $myrow20[0];
$zufb = $myrow20[1];
$zufc = $myrow20[2];
$zufd = $myrow20[3];

$result9 = mysql_query("SELECT url, seitenname, email, loginpoints, reportpoints, bannerklick, startcredits, jackport, refjackport, ratio, time, defaultbanner, defaultbannerurl, defaulturl, emailmodi, logeout, registriert, referview, frequency, starten, tausch  FROM `demo_a_admin`");
$myrow2 = mysql_fetch_row($result9);

$points_view = $myrow2[9];

$viewpointsb = bcmul($myrow2[9],100,1);
$viewpoints = $viewpointsb / 10;
$endratio = bcmul($viewpoints,10,0);

$points_referer_view = $myrow2[17];

$refpoints = bcmul($myrow2[17],100,1);
$refpointsb = $refpoints / 10;
$refpointsc = bcmul($refpointsb,10,0);

$points_referer_jackpot_views = $myrow2[7];
$points_referer_jackpot_points = $myrow2[8];
$points_hit=1;
$points_register = $myrow2[6];
$points_banner_click = rand($zufc,$zufd);
$points_report = $myrow2[4];
$points_login = rand($zufa,$zufb);
$seitenname = $myrow2[1];
$emailadresse = $myrow2[2];
$seitenstart = $myrow2[19];
$tausch = $myrow2[20];

$url_index = $myrow2[0];
$url_logout_succesfull = $myrow2[15];
$url_register_succesfull = $myrow2[16];
$url_email_modified = $myrow2[14];
$url_default = $myrow2[13];

$banner_default_source = $myrow2[11];
$banner_default_target = $myrow2[12];
$banner_default_alt ='';

$showup_frequency = $myrow2[18];;
$showup_time = $myrow2[10];

$result99 = mysql_query("SELECT gampoints, gamchance, gampointszu FROM `demo_a_gambleadmin`");
$myrow99 = mysql_fetch_row($result99);

$gamble_points = $myrow99[0];
$gamble_chance = $myrow99[1];
$gamble_points_add = $myrow99[2];

$email_header="From: $seitenname < $emailadresse >";
$email_welcome_title="Welcome at $seitenname";
$email_welcome="Welcome at $seitenname!\nPlease click the following link to complete your membership (aol-users must copy it in their browsers)\n\n $url_index/member/?sid=$sid&&validatemail=1";
$email_modified_title='Welcome';
$email_modified="Welcome at $seitenname!\n\n $url_index/member/?sid=$newsid&&validatemail=1";
$email_notifynewmember=" $emailadresse ";
$email_notifynewmember_title='New user';
$email_notifynewmember_msg="A new user has registered at $seitenname.";
$email_deleted_title='Account deleted';
$email_deleted="Your account at $seitenname has been deleted according to cheating.";
$email_showup_title="Membership completed";
$email_showup="Your membership at $seitenname was completed. You may now use the surfbar. Your account has been credited $points_register sign up bonus.";

$email_notifyreported=" $emailadresse ";
$email_notifyreported_title="Website submitted";
$email_notifyreported_msg="Website id no. $id was submitted with the following text:\n";

$email_report_confirmed='Your website was deleted.';
$email_confirmed_title='Website deleted';

$banner_db_clean=60*60*10;

$conn=mysql_connect($mysql_host,$mysql_username,$mysql_password) or die(mysql_error());

/* Nothing to modify below */
@mysql_connect($mysql_host, $mysql_username, $mysql_password) or
        die("An error has occured while processing");
@mysql_select_db($mysql_db) or
        die("An error has occured while processing");

if(!get_magic_quotes_gpc())
{
        while(list($k, $v) = each($HTTP_GET_VARS))
        {
                $HTTP_GET_VARS[$k]=addslashes($v);
        }
        while(list($k, $v) = each($HTTP_POST_VARS))
        {
                $HTTP_POST_VARS[$k]=addslashes($v);
        }
}


function s_verify()
{
        global $sid, $url_logout_succesfull;
        if(!$sid)
        {
                header("Location: $url_logout_succesfull");
                exit;
        }

        $query="SELECT id FROM demo_a_accounts WHERE sessionid='$sid';";
        $result=mysql_query($query);
        if(!$result)
        {
                echo $result;
                header("Location: $url_logout_succesfull");
                exit;
        }
        $userid=@mysql_result($result, 0);
        if(!$userid)
        {
                header("Location: $url_logout_succesfull");
                exit;
        }

        return $userid;
}

function account_add($name, $prename, $password, $email, $url, $showup, $points, $sid, $referer="")
{
        $query="SELECT count(*) FROM demo_a_accounts WHERE email='$email';";
        $result=mysql_query($query);
        if(mysql_result($result, 0)==0)
        {
                $query="INSERT INTO demo_a_accounts (name, prename, password, email, url, showup, points, sessionid, refererid) VALUES ('$name', '$prename', '$password', '$email', '$url', '$showup', '$points', '$sid', '$referer');";
                mysql_query($query);
                return true;
        }
        return false;
}
function account_delete($id)
{
        global $email_deleted_title, $email_deleted, $email_header;
        $result=mysql_query("SELECT email FROM demo_a_accounts WHERE id='$id'");
        $email=mysql_result($result, 0);
        mail($email, $email_deleted_title, $email_deleted, $email_header);
        mysql_query("DELETE FROM demo_a_accounts WHERE id='$id'");
}
function viewpage()
{
        global $userid, $showup_frequency, $points_hit, $points_view, $showup_time, $url_default, $points_referer_view, $points_referer_jackpot_views, $points_referer_jackpot_points;
        if($userid)
        {
                $query="SELECT recently, refererid, views FROM demo_a_accounts WHERE id='$userid';";
                $result=mysql_query($query);
                if($result)
                {
                        $result = @mysql_fetch_array($result);
                        $recently=split(' ', $result[recently]);
                        $refererid=$result[refererid];
                        $views=$result[views];
                        if($points_referer_jackpot_views && $points_referer_jackpot_points && $views==$points_referer_jackpot_views){
                                echo $views;
                                $query="UPDATE demo_a_accounts SET points=points+$points_referer_jackpot_points WHERE id='$refererid';";
                                mysql_query($query);
                        }
                }
        }

        $query = "SELECT id, url FROM demo_a_accounts WHERE  showup='1' AND id!='$userid' AND points>=$points_hit AND savepoints='0'";

        for($i=0; $i<count($recently) && $i<$showup_frequency; $i++)
        {
                $query .= " AND id!='$recently[$i]'";
                $recentlies[]=$recently[$i];
        }
        $query .= ' ORDER BY points DESC';
        $result=mysql_query($query);
        $result=@mysql_fetch_array($result);
        if($result[id] && $result[url])
        {
                $query="UPDATE demo_a_accounts SET hits=hits+1, points=points-$points_hit WHERE id='".$result[id]."';";
                mysql_query($query);
                $recently=implode(" ", $recentlies);
                $recently=$result[id]." ".$recently;
                $query="UPDATE demo_a_accounts SET views=views+1, points=points+$points_view, recently='$recently', lastview='".time()."' WHERE id='$userid' AND lastview<='".time().-$showup_time."';";
                mysql_query($query);
                if($refererid)
                {
                        $query="UPDATE demo_a_accounts SET points=points+$points_referer_view, refpoints=refpoints+$points_referer_view WHERE id='$refererid';";
                        mysql_query($query);
                }
        }else{
                $result[url]=$url_default;
                $query="UPDATE demo_a_accounts SET views=views+1, points=points+$points_view, lastview='".time()."' WHERE id='$userid' AND lastview<='".time().-$showup_time."';";
                mysql_query($query);
                if($refererid)
                {
                        $query="UPDATE demo_a_accounts SET points=points+$points_referer_view, refpoints=refpoints+$points_referer_view WHERE id='$refererid';";
                        mysql_query($query);
                }
        }
        return array(
                url=>$result[url],
                id=>$result[id]
        );
}

function loginb()
{
        global $email, $password;
        $query="SELECT count(*) FROM demo_a_accounts WHERE email='$email' AND password='$password' AND showup!='2' AND showup!='3';";
        $result=mysql_query($query);
        if(mysql_result($result, 0)==1)
        {
                $sid=mt_srand((double)microtime()*1000000);
                $sid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));
                $query="UPDATE demo_a_accounts SET sessionid='$sid', points=points WHERE email='$email' AND password='$password' AND showup!='2' AND showup!='3';";
                $result=mysql_query($query);
                header("Location: ./member/?sid=$sid");
}
}
function login()
{
        global $email, $password, $points_login;
        $query="SELECT count(*) FROM demo_a_accounts WHERE email='$email' AND password='$password' AND showup!='2' AND showup!='3';";
        $result=mysql_query($query);
        if(mysql_result($result, 0)==1)
        {
                $sid=mt_srand((double)microtime()*1000000);
                $sid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));
                $query="UPDATE demo_a_accounts SET sessionid='$sid', points=points+$points_login WHERE email='$email' AND password='$password' AND showup!='2' AND showup!='3';";
                $result=mysql_query($query);
                header("Location: ./member/?sid=$sid");
}
}


function logout()
{
        global $url_logout_succesfull, $sid;
        $query="UPDATE demo_a_accounts SET sessionid='1' WHERE sessionid='".$sid."';";
        mysql_query($query);
        header("Location: $url_logout_succesfull");
        exit;
}


function getstats()
{
        global $userid;
        $query = "SELECT points, hits, views FROM demo_a_accounts WHERE id=$userid";
        $result = mysql_query($query);
        $stats = mysql_fetch_array($result);
        return $stats;
}

function getnoshowups()
{
        $result = mysql_query("SELECT name, prename, id, url, email FROM demo_a_accounts WHERE showup='0';");
        for($i=0; $row=mysql_fetch_array($result); $i++)
        {
                $list[$i]=$row;
        }
        return $list;
}
function validatemail()
{
        global $sid;
        $query="UPDATE demo_a_accounts SET showup='0' WHERE showup='2' and sessionid='$sid'";
        mysql_query($query);
        $query="UPDATE demo_a_accounts SET showup='1' WHERE showup='3' and sessionid='$sid'";
        mysql_query($query);
}
function showup($id)
{
        global $email_showup_title, $email_showup, $email_header;
        $result=mysql_query("SELECT email FROM demo_a_accounts WHERE id='$id'");
        $email=mysql_result($result, 0);
        mail($email, $email_showup_title, $email_showup, $email_header);
        $query="UPDATE demo_a_accounts SET showup='1' WHERE showup='0' AND id='$id';";
        mysql_query($query);
}
function modifyemail($email)
{
        global $userid, $email_header, $email_modified_title, $email_modified, $url_email_modified, $newsid;
        $query="UPDATE demo_a_accounts SET email='$email', showup='3', sessionid='$newsid' WHERE id='$userid';";
        mysql_query($query);
        mail($email,$email_modified_title, $email_modified, $email_header);
}
function modifyurl($url)
{
        global $userid;
        $query="UPDATE demo_a_accounts SET url='$url', showup='0' WHERE id='$userid';";
        mysql_query($query);
}
function getconfig()
{
        global $userid;
        $query="SELECT url, email, savepoints FROM demo_a_accounts WHERE id='$userid';";
        $result=mysql_query($query);
        $result=mysql_fetch_array($result);
        return $result;
}
function savepoints($savepoints)
{
        global $userid;
        $query="UPDATE demo_a_accounts SET savepoints='$savepoints' WHERE id='$userid';";
        mysql_query($query);
}
function banner_add()
{
        global $name, $email, $source, $target, $views, $clicks, $alt ,$anzahl;
        $query="INSERT INTO demo_a_banners (name, email, source, target, views, clicks, alt, anzahl) VALUES ('$name', '$email', '$source', '$target', '$views', '$clicks', '$alt', '$views');";
        mysql_query($query);
}

function banner_cleandb()
{
        global $banner_db_clean;
        $query='DELETE FROM demo_a_banners WHERE clicks<1 AND views<1';
        mysql_query($query);
}
function banner_view()
{
        global $id, $banner_default_source, $userid;
        $query="SELECT id, source, alt FROM demo_a_banners WHERE clicks>0 OR views>0 ORDER BY lastview ASC LIMIT 1";
        $result=mysql_query($query);
        $banner=@mysql_fetch_array($result);
        $query="UPDATE demo_a_banners SET views=views-1, lastview='".time()."' WHERE id='".$banner[id]."';";
        mysql_query($query);
        if(!$banner[source])
                $banner[source]=$banner_default_source;
        if(!$banner[alt])
                $banner[alt]=$banner_default_alt;
        echo '<a href="./banner_click.php?bannerid='.$banner[id].'&&userid='.$userid.'" target="_blank"><img src="'.$banner[source].'" border="0" alt="'.$banner[alt].'"></a>';
}
function banner_click()
{
        global $bannerid, $userid, $banner_default_target, $points_banner_click;
        if($bannerid)
        {
                $query="UPDATE demo_a_banners SET clicks=clicks-1 WHERE id='$bannerid';";
                mysql_query($query);
                $query="UPDATE demo_a_accounts SET points=points+$points_banner_click WHERE id='$userid';";
                mysql_query($query);
                $query="SELECT target FROM demo_a_banners WHERE id='$bannerid'";
                $result=mysql_query($query);
                if($result)
                        $target=@mysql_result($result, 0);

        }
        if(!$target)
                $target=$banner_default_target;
        header("Location: $target");
}

function banner_clickb()
{
        global $bannerid, $userid, $banner_default_target;
        if($bannerid)
        {
                $query="UPDATE demo_a_banners SET clicks=clicks-1 WHERE id='$bannerid';";
                mysql_query($query);
                $query="UPDATE demo_a_accounts SET points=points WHERE id='$userid';";
                mysql_query($query);
                $query="SELECT target FROM demo_a_banners WHERE id='$bannerid'";
                $result=mysql_query($query);
                if($result)
                        $target=@mysql_result($result, 0);

        }
        if(!$target)
                $target=$banner_default_target;
        header("Location: $target");
}

function report($id, $text, $userid)
{
        global $email_notifyreported, $email_notifyreported_title, $email_notifyreported_msg, $email_header;
        $query="UPDATE demo_a_accounts SET showup='4', reportedby='$userid' WHERE id='$id' AND showup='1'";
        mysql_query($query);
        $result=mysql_query("SELECT url FROM demo_a_accounts WHERE id='$id'");
        $url=mysql_result($result, 0);
        mail($email_notifyreported, $email_notifyreported_title, $email_notifyreported_msg."\n\n$text\n\n$url", $email_header);
}

function report_delete($id)
{
        $query="UPDATE demo_a_accounts SET showup='1',reportedby='0' WHERE id='$id' AND showup='4'";
        mysql_query($query);
}

function report_confirm($id)
{
        global $email_report_confirmed, $email_confirmed_title, $email_header;
        $result=mysql_query("SELECT email FROM demo_a_accounts WHERE id='$id'");
        $email=mysql_result($result, 0);
        $result=mysql_query("SELECT reportedby FROM demo_a_accounts WHERE id='$id'");
        $reportedb=mysql_result($result, 0);
        $query="UPDATE demo_a_accounts SET showup='5', reportedby='0' WHERE id='$id' AND showup='4'";
        mysql_query($query);
        $query="UPDATE demo_a_accounts SET points=points+$points_report WHERE id='$reportedby'";
        mysql_query($query);
        mail($email, $email_confirmed_title, $email_report_confirmed, $email_header);
}

function getreports()
{
        $result = mysql_query("SELECT points, prename, name, reportedby, id, url, email FROM demo_a_accounts WHERE showup='4';");
        for($i=0; $row=mysql_fetch_array($result); $i++)
        {
                $list[$i]=$row;
        }
        return $list;
}

function transferpoints($source, $target, $points)
{
        $query="SELECT points FROM demo_a_accounts WHERE id='$source' AND points>=10";
        $query=mysql_query($query);
        $pointsa=@mysql_result($query, 0);
        if($pointsa>=$points && $pointsa>=10){
                $query="UPDATE demo_a_accounts SET points=points-$points WHERE id='$source'";
                mysql_query($query);
                $query="UPDATE demo_a_accounts SET points=points+$points WHERE email='$target'";
                mysql_query($query);
        }

function report_all()
{
        $result = mysql_query("SELECT points, id, url, email FROM demo_a_accounts");
        for($i=0; $row=mysql_fetch_array($result); $i++)
        {
                $list[$i]=$row;
        }
        return $list;
}

}

function gettopviews()
{
        $query="SELECT prename, name, email, url, views FROM demo_a_accounts WHERE views!=0 ORDER BY views DESC LIMIT 10";
        $result=mysql_query($query);
        for($i=0; $row=mysql_fetch_array($result); $i++)
        {
                $list[$i]=$row;
        }
        return $list;
}

function gettoppoints()
{
        $query="SELECT prename, name, email, url, points FROM demo_a_accounts WHERE points!=0 ORDER BY points DESC LIMIT 10";
        $result=mysql_query($query);
        for($i=0; $row=mysql_fetch_array($result); $i++)
        {
                $list[$i]=$row;
        }
        return $list;
}


function autogamble()
{
        global $gamble_points, $gamble_points_add, $gamble_chance, $showup_time;
        if($gamble_points && $gamble_chance)
        {
                srand((double)microtime()*1000000);
                $randval=rand(1, $gamble_chance);
        }
        if($randval==1)
        {
                $dif=time()-$showup_time;
                $query="SELECT id, sessionid, time, points FROM demo_a_gamble WHERE time<$dif LIMIT 1";
                $result=@mysql_query($query);
                $data=@mysql_fetch_array($result);
                if($data[id])
                {
                        $query="UPDATE demo_a_gamble SET points=points+$gamble_points_add WHERE id=".$data[id];
                        mysql_query($query);
                        $sessionid=$data[sessionid];
                }else{
                        mt_srand((double)microtime()*1000000);
                        $sessionid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));
                        $query="INSERT INTO demo_a_gamble (sessionid, time, points) VALUES ('$sessionid', '".time()."', '$gamble_points')";
                        mysql_query($query);
                }
                return $sessionid;
        }
}

function autogamble_click()
{
        global $sid, $userid;
        $query="SELECT points FROM demo_a_gamble WHERE sessionid='$sid'";
        $result=mysql_query($query);
        $points=@mysql_result($result, 0);
        if(!$points)
        {
                return false;
        }else{
                $query="DELETE FROM demo_a_gamble WHERE sessionid='$sid'";
                mysql_query($query);
                $query="UPDATE demo_a_accounts SET points=points+$points WHERE id='$userid';";
                mysql_query($query);
                return $points;
        }
}

function givecredit($data, $points)
{
        $query="SELECT id FROM demo_a_accounts WHERE id='$data' OR email='$data' or url='$data'";
        $result=mysql_query($query);
        $result=@mysql_result($result, 0);
        if($result)
        {
                $query="UPDATE demo_a_accounts SET points=points+$points WHERE id='$data' OR email='$data' or url='$data'";
                mysql_query($query);
                return $result;
        }
}

?>