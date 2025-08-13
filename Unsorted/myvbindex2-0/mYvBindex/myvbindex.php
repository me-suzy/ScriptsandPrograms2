<?php
error_reporting(7);

// -------------------------
// mYvBindex v2.0 release by PlurPlanet (Brian Gunter)
// -------------------------

function getrowcolor() {
  global $bgcounter;
  if ($bgcounter++%2 == '0') {
    return '{secondaltcolor}';
  } else {
    return '{firstaltcolor}';
  }
}

$templatesused = 'index,index_threadbit,index_newsbits,index_news_adminopts,index_news_comments,index_news_commentnull,index_news_readmore,index_header,index_footer,headinclude,forumhome_pmloggedin,index_welcometext,index_logincode,index_buddy,index_buddylist,index_buddypmlink,forumhome_loggedinuser,index_online,index_search,index_welcometext_avatar,index_calendar,index_weather,index_custom1,index_custom2,index_pollresult,index_polloption_multiple,index_polloption,index_polloptions,index_pollresults_voted,index_pollresults_closed,index_pollreview,index_pollresults,index_polldiscuss,index_polledit';
$loadmaxusers=1;

// Enter The Full Path To Your Forums Here
chdir('/home/path/to/your/forum');
// No Further Editing Necessary

require('./global.php');

$index_header = '';
$index_footer = '';

// CSS, Header & Footer
eval("\$headinclude = \"".gettemplate('headinclude')."\";");
eval("\$index_header .= \"".gettemplate('index_header')."\";");
eval("\$index_footer .= \"".gettemplate('index_footer')."\";");


// Check usergroup of user to see if they can use PMs
if ($showpm) {
if ($enablepms==1 and $permissions['canusepm'] and $bbuserinfo['receivepm']) {
  $ignoreusers="";
  if (trim($bbuserinfo['ignorelist'])!="") {
    $ignoreusers='AND fromuserid<>'.implode(' AND fromuserid<>',explode(' ', trim($bbuserinfo['ignorelist'])));
  }
  $unreadpm=$DB_site->query_first("SELECT COUNT(*) AS messages FROM privatemessage WHERE userid=$bbuserinfo[userid] AND messageread=0 AND folderid=0 $ignoreusers");
  if ($newpm['messages']==0) {
    $lightbulb='off';
  } else {
    $lightbulb='on';
  }
  eval("\$pminfo = \"".gettemplate('forumhome_pmloggedin')."\";");
} else {
  $pminfo='';
}
}


// Show Avatar
$avatarimage='';
if ($showavatar) {
if ($bbuserinfo[userid]!=0) {
  $avatarurl=getavatarurl($bbuserinfo[userid]);
  if ($avatarurl=='') {
    $avatarurl='images/noavatar.gif';
  } 
  eval("\$avatarimage = \"".gettemplate("index_welcometext_avatar")."\";");
}
}


// Start Buddy List
if ($showbuddylist) {
if ($bbuserinfo['userid']!=0) {
if ($permissions[maxbuddypm]) {
  eval("\$buddypmlink = \"".gettemplate("index_buddypmlink")."\";");
} else {
  $buddypmlink = '';
}
$datecut = time() - $cookietimeout;
$buddyuserssql=str_replace(" ","' OR user.userid='",$bbuserinfo[buddylist]);
$sql="SELECT userid,username,invisible,lastactivity,lastvisit
      FROM user
      WHERE (user.userid='$buddyuserssql')
      ORDER BY username";
$buddys=$DB_site->query($sql);
$onlineusers="";
$doneuser = array();
while ($buddy=$DB_site->fetch_array($buddys)) {
if ($doneuser[$buddy[userid]]) {
		continue;
	}
	$doneuser[$buddy[userid]]=1;

  if ($buddy['lastactivity'] > $datecut and (!$buddy['invisible'] or $bbuserinfo['usergroupid'] == 6) and $buddy['lastvisit'] != $buddy['lastactivity']) {
    $onoff="on";
  } else {
    $onoff="off";
  }
  eval("\$var = \"".gettemplate("index_buddy")."\";");
  if ($onoff=="on") {
    $onlineusers.=$var;
}
}
}
}


// Welcome Text & Buddy List or Logincode
  if ($bbuserinfo['userid']!=0) {
  $username=$bbuserinfo['username'];
  $getbgrow=getrowcolor();
  eval("\$welcometext = \"".gettemplate('index_welcometext')."\";");
if ($showbuddylist) {
  $getbgrow=getrowcolor();
  eval("\$buddylist = \"".gettemplate("index_buddylist")."\";");
}
} else {
  $getbgrow=getrowcolor();
  eval("\$welcometext = \"".gettemplate('index_logincode')."\";");
  $buddylist = "";
}


// Online Users
if ($showonline) {

// **********
// PLACE YOUR ORIGINAL VBULLETIN CODE HERE
// **********

  $getbgrow=getrowcolor();
  eval("\$loggedinusers = \"".gettemplate('index_online')."\";");
}
}


// Forum Permissions
$forumperms=$DB_site->query("SELECT forumid,canview,canpostnew FROM forumpermission WHERE usergroupid='$bbuserinfo[usergroupid]'");
while ($forumperm=$DB_site->fetch_array($forumperms)) {
  $ipermcache["$forumperm[forumid]"] = $forumperm;}
$DB_site->free_result($forumperms);
unset($forumperm);


//Forum Info
$forums=$DB_site->query('SELECT * FROM forum WHERE displayorder<>0 AND active=1 ORDER BY parentid,displayorder');
while ($forum=$DB_site->fetch_array($forums)) {
  $iforumcache["$forum[parentid]"]["$forum[displayorder]"]["$forum[forumid]"] = $forum;
  if ($ipermcache["$forum[forumid]"]["canview"]==1 || !isset($ipermcache["$forum[forumid]"]["canview"])) {
    $iforumperms[] = $forum["forumid"];}
}
$DB_site->free_result($forums);
unset($forum);

if (!empty($iforumperms)) {
  $iforumperms = 'AND forumid=' . implode(' OR forumid=', $iforumperms);
}


// Current Poll
$currentpoll = '';
if ($showpoll){
  $pollinfo=$DB_site->query_first("SELECT poll.*,thread.* FROM poll LEFT JOIN thread ON (thread.pollid = poll.pollid) WHERE thread.forumid='$pollsforum' ORDER BY poll.dateline DESC LIMIT 1");{
  $allowsmilies=$showpollsmilies;
  $pollinfo[question]=bbcodeparse($pollinfo[question],$forum[forumid],$showpollsmilies);

  $splitoptions=explode("|||", $pollinfo[options]);
  $splitvotes=explode("|||",$pollinfo[votes]);

  $showresults = 0;
  $uservoted = 0;

  if (!$pollinfo[active] or !$pollinfo[open] or ($pollinfo[dateline]+($pollinfo[timeout]*86400)<time() and $pollinfo[timeout]!=0)){
    //thread/poll is closed, ie show results no matter what
    $showresults=1;
  } else if (get_bbarraycookie('pollvoted', $pollid) or ($bbuserinfo['userid'] and $uservote=$DB_site->query_first("SELECT pollvoteid FROM pollvote WHERE userid='$bbuserinfo[userid]' AND pollid=$pollinfo[pollid]"))) {
      $uservoted = 1;
    }
  

  $counter=0;
  while ($counter++ < $pollinfo[numberoptions]) {
    $pollinfo[numbervotes] += $splitvotes[$counter-1];
  }

  $counter=0;
  $pollbits="";
  $option = array();

  while ($counter++<$pollinfo[numberoptions]) {
    $option[question] = bbcodeparse($splitoptions[$counter-1],$forum[forumid],$showpollsmilies);
    $option[votes] = $splitvotes[$counter-1];  //get the vote count for the option
    $option[number] = $counter;  //number of the option

    //Now we check if the user has voted or not
    if ($showresults or $uservoted) { // user did vote or poll is closed
$pluralize = '';
  if ($option[votes]<>1) {
	  $pluralize = 's';
}
      if ($option[votes] == 0){
        $option[percent] = 0;
      } else{
        $option[percent] = number_format($option[votes]/$pollinfo[numbervotes]*100,2);
      }

      $option[graphicnumber]=$option[number]%6 + 1;
      $option[barnumber] = round($option[percent])*1.3;
      if ($showresults) {
        eval("\$pollstatus = \"".gettemplate('index_pollresults_closed')."\";");
      } elseif ($uservoted) {
        eval("\$pollstatus = \"".gettemplate('index_pollresults_voted')."\";");
        } 
        eval("\$pollbits .= \"".gettemplate('index_pollresult')."\";");

      }
      elseif ($pollinfo['multiple'])
      {
        eval("\$pollbits .= \"".gettemplate('index_polloption_multiple')."\";");
      }
      else
      {
        eval("\$pollbits .= \"".gettemplate('index_polloption')."\";");
      }
    }

$editpoll = '';
      if (in_array($bbuserinfo['usergroupid'], array(5, 6)))
      {
eval("\$editpoll .= \"".gettemplate('index_polledit')."\";");
      }
    if ($pollinfo['multiple'])
    {
      $pollinfo['numbervotes'] = $pollinfo['voters'];
    }

$discusspoll='';
if ($showpolldiscuss) {
    eval("\$discusspoll = \"".gettemplate('index_polldiscuss')."\";");
}

    if ($showresults or $uservoted) {
    eval("\$currentpoll = \"".gettemplate('index_pollresults')."\";");
    } else {
    eval("\$currentpoll = \"".gettemplate('index_polloptions')."\";");
  }
}
} 


// Latest XX Threads on Forumhome Page - by TECK, modified by PlurPlanet
$threads=$DB_site->query("SELECT * FROM thread WHERE open='1' AND open<>10 $iforumperms ORDER BY lastpost DESC LIMIT $maxlatethreads");
while ($thread=$DB_site->fetch_array($threads)) {
  $title = unhtmlspecialchars($thread['title']);
  if (strlen($thread['title']) > $maxthreadchars and $maxthreadchars!=0) {
    $title = substr($thread['title'], 0, $maxthreadchars - 3) . '...';
}
  if ($showthreaddate) {
  	$thread['time'] = vbdate($timeformat, $thread['dateline']);
  	$thread['date'] = date("m-d-y", $thread['dateline']);
}


// Thread Icon
if ($showthreadicon) {
  if ($thread['iconid'] == 0) {
    $thread['icon'] = '<img src="{imagesfolder}/icons/icon1.gif" border="0" align="absmiddle" alt="">';
} else {
    $thread['icon'] = '<img src="{imagesfolder}/icons/icon' . $thread['iconid'] . '.gif" border="0" align="absmiddle" alt="">';
  }
}


    $getbgrow=getrowcolor();
  eval("\$threadbits .= \"".gettemplate('index_threadbit')."\";");}
  
$DB_site->free_result($threads);
unset($thread);


// News 
$getnews = $DB_site->query("SELECT thread.threadid,thread.title,thread.replycount,thread.postusername,thread.postuserid,thread.dateline as dateline,post.pagetext,thread.iconid,post.postid FROM thread LEFT JOIN post USING (threadid) LEFT JOIN user ON (user.userid=thread.postuserid) WHERE thread.forumid=$newsforum AND isnews='Y' GROUP BY thread.threadid ORDER BY thread.threadid DESC LIMIT $newslimit");

while ($news=$DB_site->fetch_array($getnews)) {
	$dateline=$news['dateline'];
	$dateposted = date("M.d, Y - g:i A",$dateline);
  	$icon=$getnews[iconid];
	$allowsmilies=$shownewssmilies;
  
  $news[message] = bbcodeparse($news[pagetext],$newsforum,$shownewssmilies);
  if (strlen($news[message]) > $maxnewschars and $maxnewschars!=0)  {
    eval("\$newsreadmore = \"".gettemplate('index_news_readmore')."\";");
    $news[message] = substr($news[message], 0, $maxnewschars) . $newsreadmore;
  }


// News Comments
if ($showcomments) {
	$pluralize = '';
  if ($news[replycount]<>1) {
	  $pluralize = 's';}
	  
  if ($news[replycount]>0) {
	eval("\$newscomments = \"".gettemplate('index_news_comments')."\";");
} else {
    eval("\$newscomments = \"".gettemplate('index_news_commentnull')."\";");
  }
}


// News Icon
$newsicon='';
if ($shownewsicon) {
if ($news['iconid'] == 0) {
  $newsicon = '<img src="{imagesfolder}/icons/icon1.gif" border="0" align="absmiddle" alt="">';
} else {
  $newsicon = '<img src="{imagesfolder}/icons/icon' . $news['iconid'] . '.gif" border="0" align="absmiddle" alt="">';
  }
}
  
// News Admin Options
$adminopts = '';
if (in_array($bbuserinfo['usergroupid'], array(5, 6))){
eval("\$adminopts .= \"".gettemplate('index_news_adminopts')."\";");
}


	eval("\$newsbits .= \"".gettemplate('index_newsbits')."\";");
}

$DB_site->free_result($getnews);
unset($news);


// Search Box
$search='';
if ($showsearch) {
  $getbgrow=getrowcolor();
  eval("\$search .= \"".gettemplate('index_search')."\";");
}


// vbPortal mini calendar - by wajones
if ($showcalendar) {
$year=date("Y");
$doublemonth=vbdate("m",time());
$month=date("n");
$day=1;
$today=vbdate("m-d",time());
$events=$DB_site->query("SELECT eventdate,subject,eventid FROM calendar_events WHERE eventdate LIKE '$year-$doublemonth-%' AND ((userid='$bbuserinfo[userid]' AND public=0) OR (public=1))");
while ($event=$DB_site->fetch_array($events)) {
  if ($event[eventdate]==vbdate("Y-m-d",time())) {
    $eventsubject=htmlspecialchars($event[subject]);
    $todaysevents.="
		<li><smallfont><b><a href=\"$bburl/calendar.php?s=$session[sessionhash]&action=getinfo&eventid=$event[eventid]\">$eventsubject</a></b></smallfont></li>";
  }
}
if (!$bbuserinfo[startofweek]) {
  $bbuserinfo[startofweek]=1;
}
$dayname_s="<td width=\"*\" bgcolor=\"{firstaltcolor}\"><smallfont>S</smallfont></td>";
$dayname_m="<td width=\"*\" bgcolor=\"{firstaltcolor}\"><smallfont>M</smallfont></td>";
$dayname_t="<td width=\"*\" bgcolor=\"{firstaltcolor}\"><smallfont>T</smallfont></td>";
$dayname_w="<td width=\"*\" bgcolor=\"{firstaltcolor}\"><smallfont>W</smallfont></td>";
$dayname_f="<td width=\"*\" bgcolor=\"{firstaltcolor}\"><smallfont>F</smallfont></td>";
if ($bbuserinfo[startofweek]==1) {
  $calendar_daynames="$dayname_s$dayname_m$dayname_t$dayname_w$dayname_t$dayname_f$dayname_s";
} else if ($bbuserinfo[startofweek]==2) {
  $calendar_daynames="$dayname_m$dayname_t$dayname_w$dayname_t$dayname_f$dayname_s$dayname_s";
} else if ($bbuserinfo[startofweek]==3) {
  $calendar_daynames="$dayname_t$dayname_w$dayname_t$dayname_f$dayname_s$dayname_s$dayname_m";
} else if ($bbuserinfo[startofweek]==4) {
  $calendar_daynames="$dayname_w$dayname_t$dayname_f$dayname_s$dayname_s$dayname_m$dayname_t";
} else if ($bbuserinfo[startofweek]==5) {
  $calendar_daynames="$dayname_t$dayname_f$dayname_s$dayname_s$dayname_m$dayname_t$dayname_w";
} else if ($bbuserinfo[startofweek]==6) {
  $calendar_daynames="$dayname_f$dayname_s$dayname_s$dayname_m$dayname_t$dayname_w$dayname_t";
} else if ($bbuserinfo[startofweek]==7) {
  $calendar_daynames="$dayname_s$dayname_s$dayname_m$dayname_t$dayname_w$dayname_t$dayname_f";
}
$numdays=1;
while (checkdate($month,$numdays,$year)) {
  $numdays++;
}
while ($day<$numdays) {
  $eventtoday=0;
  if ($DB_site->num_rows($events)>0) {
    $DB_site->data_seek(0,$events);
    while ($event=$DB_site->fetch_array($events)) {
      $eventdatebits=explode("-",$event[eventdate]);
      if ($eventdatebits[2]==$day) {
        $eventtoday=1;
      }
    }
  }
  if ($eventtoday==1) {
    $daylink="<a href=\"$bburl/calendar.php?s=$session[sessionhash]&action=getday&day=$year-$month-$day\">$day</a>";
  } else {
    $daylink=$day;
  }
  if ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Sunday') {
      $off=2-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Monday') {
      $off=3-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Tuesday') {
      $off=4-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Wednesday') {
      $off=5-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Thursday')  {
      $off=6-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Friday') {
      $off=7-$bbuserinfo[startofweek];
  } elseif ($day==1 and date('l',mktime(0,0,0,$month,$day,$year))=='Saturday')  {
      $off=8-$bbuserinfo[startofweek];
  }
  if ($off<0) {
   $off=$off+7;
  }
  $counter=0;
  while (($day==1)&&($counter<$off-1)) {
    $calendarbits.="<td>&nbsp;</td>";
    $counter++;
  }
  if (date("j",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+($bbuserinfo[timezoneoffset]-$timeoffset)*3600)==$day) {
    	$calendarbits.="<td bgcolor=\"{firstaltcolor}\"><smallfont color=\"{caldaycolor}\">$daylink</font></td>";
    
  } else {
	  $calendarbits.="<td bgcolor=\"{secondaltcolor}\"><smallfont color=\"{caldaycolor}\">$daylink</font></td>";
	
  }
  $day++;
  $off++;
  if (($off>7)||($day==$numdays)) {
    if ($day!=$numdays) {
      $calendarbits.="
		</tr><tr>";
      $off=1;
    } else {
      $counter=0;
      while ($counter<8-$off) {
        $calendarbits.="<td bgcolor=\"{secondaltcolor}\">&nbsp;</td>";
        $counter++;
      }
    }
  }
}

$cal_date=strftime('%B %e');
    eval("\$calendar .= \"".gettemplate('index_calendar')."\";");
}


// Weather - by JJR512
  if ($showweather) {
if ($weatherpage!=1 or !isset($weatherpage)) {

  $usersettings = $DB_site->query_first("SELECT * FROM weather_usersettings WHERE userid=$bbuserinfo[userid]");
  if (!isset($usersettings[userid])) {
    $usersettings[accid] = "USNY0996";
    $usersettings[tpc] = "1";
    $usersettings[tps] = "1";
    $DB_site->query("INSERT INTO weather_usersettings (userid,accid,tpc,tps) VALUES ('$bbuserinfo[userid]','$usersettings[accid]','$usersettings[tpc]','$usersettings[tps]')");
  }

  $userdata = $DB_site->query_first("SELECT * FROM weather_userdata WHERE userid=$bbuserinfo[userid]");
  $datecut = $userdata[time];
  if ((time()-7200)>$datecut or $forceupdate=="yes") {
    $rawdata = fsockopen("www.msnbc.com",80,$num_error,$str_error,30);
    if(!$rawdata) {
      $weather[error_num] = $num_error;
      $weather[error_str] = $str_error;
    } else {
      fputs($rawdata,"GET /m/chnk/d/weather_d_src.asp?acid=$usersettings[accid] HTTP/1.0\n\n");

      while (!feof($rawdata)) {
        $getbit = fgets($rawdata,4096);
        $getbit = trim($getbit)."\n";
        if (substr($getbit,7,4) == "City") {
          $weather[city] = substr($getbit,15,40);
          $weather[city] = substr($weather[city],0,strlen($weather[city])-3);
        }
        if (substr($getbit,7,6) == "SubDiv") {
          $weather[subdiv] = substr($getbit,17,20);
          $weather[subdiv] = substr($weather[subdiv],0,strlen($weather[subdiv])-3);
        }
        if (substr($getbit,7,7) == "Country") {
          $weather[country] = substr($getbit,18,20);
          $weather[country] = substr($weather[country],0,strlen($weather[country])-3);
        }
        if (substr($getbit,7,5) == "Temp ") {
          $weather[temp] = substr($getbit,15,20);
          $weather[temp] = substr($weather[temp],0,strlen($weather[temp])-3);
        }
        if (substr($getbit,7,5) == "CIcon") {
          $weather[cicon] = substr($getbit,16,20);
          $weather[cicon] = substr($weather[cicon],0,strlen($weather[cicon])-3);
        }
        if (substr($getbit,7,5) == "WindS") {
          $weather[wind_spd] = substr($getbit,16,20);
          $weather[wind_spd] = substr($weather[wind_spd],0,strlen($weather[wind_spd])-3);
        }
        if (substr($getbit,7,5) == "WindD") {
          $weather[wind_dir] = substr($getbit,16,20);
          $weather[wind_dir] = substr($weather[wind_dir],0,strlen($weather[wind_dir])-3);
        }
        if (substr($getbit,7,4) == "Baro") {
          $weather[barometer] = substr($getbit,15,20);
          $weather[barometer] = substr($weather[barometer],0,strlen($weather[barometer])-3);
        }
        if (substr($getbit,7,5) == "Humid") {
          $weather[humidity] = substr($getbit,16,20);
          $weather[humidity] = substr($weather[humidity],0,strlen($weather[humidity])-3);
        }
        if (substr($getbit,7,4) == "Real") {
          $weather[realfeel] = substr($getbit,15,20);
          $weather[realfeel] = substr($weather[realfeel],0,strlen($weather[realfeel])-3);
        }
        if (substr($getbit,7,6) == "LastUp") {
          $weather[lastup] = substr($getbit,17,25);
          $weather[lastup] = substr($weather[lastup],0,strlen($weather[lastup])-3);
        }
        if (substr($getbit,7,7) == "ConText") {
          $weather[context] = substr($getbit,18,25);
          $weather[context] = substr($weather[context],0,strlen($weather[context])-3);
        }
      }

      // Location Info
      $weatherdata[city] = $weather[city];
      $weatherdata[subdiv] = $weather[subdiv];
      $weatherdata[country] = $weather[country];

      // Current Conditions
      $weatherdata[temp] = convert_temp($weather[temp],$usersettings[tpc]);
      $weatherdata[cicon] = $weather[cicon];
      $weatherdata[wind_dir] = $weather[wind_dir];
      $weatherdata[wind_spd] = convert_speed($weather[wind_spd],$usersettings[tps]);
      $weatherdata[barometer] = convert_press($weather[barometer],$usersettings[tps]);
      $weatherdata[humidity] = $weather[humidity];
      $weatherdata[realfeel] = convert_temp($weather[realfeel],$usersettings[tpc]);
      $weatherdata[lastup] = $weather[lastup];
      $weatherdata[context] = $weather[context];

      fclose($rawdata);
    }
  } else {
    $weatherdata = $DB_site->query_first("SELECT city,subdiv,country,temp,cicon,wind_dir,wind_spd,barometer,humidity,realfeel,lastup,context FROM weather_userdata WHERE userid=$bbuserinfo[userid]");
  }

  if ($weatherdata[subdiv]) {
    $weatherdata[showsubdiv] = "$weatherdata[subdiv] ";
  } else {
    $weatherdata[showsubdiv] = "";
  }

  $time_lastup = strtotime($weatherdata[lastup]);
  $weather[updatedate] = vbdate($dateformat,$time_lastup);
  $weather[updatetime] = vbdate($timeformat,$time_lastup);

  eval("\$currentweather = \"".gettemplate("index_weather")."\";");
}
}


// Custom Boxes
$custom1='';
$custom2='';
if ($showcustom1) {
  $getbgrow=getrowcolor();
  eval("\$custom1 .= \"".gettemplate('index_custom1')."\";");
}
if ($showcustom2) {
  $getbgrow=getrowcolor();
  eval("\$custom2 .= \"".gettemplate('index_custom2')."\";");
}



$getbgrow=getrowcolor();
eval("dooutput(\"".gettemplate('index')."\");");

?>