<?
#----------------------------------#
# Contains a library of functions  #
#----------------------------------#


#- Make sure a user is logged in
function lib_auth()
{
	global $user, $P;
	
	if(!$user[id])
	{
		lib_redirect("You must be logged in first!","$P[url]/index.php?c=login",1);
	}
	
	return(1);
}


#- Show new stuff in the right column
function lib_newstuff()
{
	$new = lib_getsql("SELECT b.* FROM listings a, items b 
						WHERE a.itemid = b.id AND a.status=1  
						ORDER BY a.date DESC LIMIT 3");
    if(count($new) >= 1)
	{
		$out  = "<table width=100% border=0 cellspacing=0 cellpadding=2>";
		$out .= "<tr><td class=contentmedium align=center><b>New Arrivals</b></td></tr>";
		$out .= "<td><td class=contentsmall>&nbsp;</td></tr>";
		
		foreach($new as $rec)
		{
			$t = urldecode(stripslashes($rec[title]));
			$url  = "<a href=index.php?c=show&f[itemid]=$rec[id]>$t</a>";
			$pic  = "<a href=index.php?c=show&f[itemid]=$rec[id]><img src=$rec[img] width=100 border=0></a>";
			$out .= "<tr><td class=contentsmall align=center>$pic<br>$url<br>&nbsp;</td></tr>\n";
		}
		$out .= "</table>";
	}

	return($out);						
}


#----------------------------------#
# Get class name
#----------------------------------#
function lib_class($classid=0,$fld="sname")
{
	$cl = lib_getsql("SELECT * FROM classes WHERE id=$classid");
	return($cl[0][$fld]);
}


#----------------------------------#
# Get City/State combo from zipcode
# database
#----------------------------------#
function lib_zipcode($zip="")
{
	if(!$zip)
		return("");
		
	$z = lib_getsql("SELECT city,state FROM zipcodes WHERE zipcode='$zip'");
	return(ucwords(strtolower($z[0][city])).", ".$z[0][state]);
}


#----------------------------------#
# Meta-Refresh/Redirect a document #
#----------------------------------#
function lib_redirect($message="",$url="",$timeout=3)
{
        global $T, $P, $refresh;

	$out = "<span class=contentmedium>$message</span><br>&nbsp;<br>
		<span class=content>If you are not redirected in $timeout seconds, 
		click <a href=$url><b>here</b></a> to continue.</span>";

        $refresh = "<meta http-equiv=\"refresh\" content=\"$timeout;URL=$url\">";
        lib_main($out,"");
	exit;
}


#-------------------------------#
# Build a tree					#
#-------------------------------#
function lib_tree($treeid=0)
  {
	  if(!$treeid)
	    return("");
	  
	  $rem = $treeid;
	  
	  #- Extract the nodes
	  $save = 0;
	  while(1)
	    {
		  $leaf = lib_getsql("SELECT * FROM categories WHERE id=$treeid");
		  
		  #- We have reached the top
		  if($leaf[0][parent] == 0)
		    {
			  $tree[] = $leaf[0];
			  break;
		    }
			
		  #- This is just another branch
		  if($leaf[0][parent])
		    {
			  $tree[] = $leaf[0];
			  $treeid = $leaf[0][parent];
		    }
	    }

	  #- Build the HTML
	  $html = array();
	  $out = "";
	  reset($tree);
	  $num = count($tree) - 1;
	  #if(count($tree) > 1)
	  #{
		for($x=$num; $x >=0; $x--)
	    	{
		  		$id = $tree[$x][id];
				$klass = $tree[$x]['class'];
		  		$name = stripslashes($tree[$x][name]);
				if($tree[$x][parent] > 1)
		  			$html[] = "<A href=index.php?browse=$klass&f[sub]=$id class=c>$name</a>";
				else
					$html[] = "<A href=index.php?browse=$klass class=c>$name</a>";
			}
	  	$out .= join("<span class=content> > </span>", $html);
	  #}

	 return($out);
  }




#----------------------------------#
# Display a message				   #
#----------------------------------#
function lib_message($title,$message,$url="")
{
	global $P;
	
	if(!$url)
		$url = $P[url];
	
	$out =  "$message<br>
			<br>
			<a href=$url>Click here to continue</a>
			";	
	lib_main($out,$title);
	return(0);		
}


#----------------------------------#
# Create a hierarchial ht_list	   #
#----------------------------------#
function lib_hier_list($table="",$keyfield="",$dispfield="",$default="", $start=1, $parent="parent")
{
    global $HIERMENU, $sql, $errormsg;

    $HIERMENU=array();

    $top = lib_getsql("SELECT $keyfield, $dispfield FROM $table WHERE id=$start");
    $HIERMENU[] = array(level=>0, id=>$top[0][id],name=>$top[0][name]);
	
    lib_hiermenu($start, 0, $table, $keyfield, $dispfield, $parent);
    
	if(count($HIERMENU))
	{
		reset($HIERMENU);
		$out = "";
		
		foreach($HIERMENU as $rec)
		{
        	if($rec[level] > 0)
				$indent = str_repeat("-",$rec[level]);
        
        	if($default != $rec[id])  
          		$out .= '<OPTION VALUE="'.$rec[id].'">'.$indent.$rec[name].'</OPTION>'."\n";
			else
				$out .= '<OPTION VALUE="'.$rec[id].'" SELECTED>'.$indent.$rec[name].'</OPTION>'."\n";  
		}
	}
	
	return($out);    	
}


#----------------------------------#
# Retrieve a hierarchial menu	   #
# starting at $node                #
#----------------------------------#
function lib_hiermenu($n=0, $level=0, $table, $keyfield, $dispfield, $parent)
{
	global $HIERMENU, $user, $sql, $errormsg, $DBLIB_DB;
    
	if($n == 0)
		return(0);
    
	#- Retrieve the child nodes
	$c = lib_getsql("SELECT $keyfield,$dispfield FROM $table WHERE $parent=$n ORDER BY $dispfield");
    
	if(!$c[0][id])
		return(0);
    
	$level++;
	foreach($c as $rec)
	{
		$name = $rec[name];
		$id = $rec[id];
		$HIERMENU[] = array(level=>$level, id=>$id, name=>$name);

		lib_hiermenu($rec[id], $level, $table, $keyfield, $dispfield, $parent);
	}  
	return(0);
}




#----------------------------------#
# Save a user's last 30 keywords   #
#----------------------------------#
function lib_savekey($word="")
  {
    global $sess;
    if(!$sess[history])
      $sess[history] = array();
      
    #- Is this in the array?
    if(!in_array($word,$sess[history]))
      $sess[history][] = $word;
      
    return(0);  
  }


#----------------------------------#
# Get a word's ID		   #
# add if it does not exist	   #
#----------------------------------#
function lib_getword($word="")
  {
    global $sql, $errormsg;
    
    $keyword = trim(strtolower(urldecode($word)));
    #$keyword = lib_filter($keyword,1);
    
    $kinfo = lib_getsql("SELECT id FROM keywords WHERE keyword='$keyword'");
    
    if(!$kinfo[0][id])
      {
        lib_getsql("INSERT INTO keywords (keyword) VALUES('$keyword')");
        $kinfo = lib_getsql("SELECT id FROM keywords WHERE keyword='$keyword'");    
      }    
    
    #- update the clickcount
    lib_getsql("UPDATE keywords SET clicks=clicks+1 WHERE id=".$kinfo[0][id]);
    return($kinfo[0][id]);  
  }



#----------------------------------#
# Show the main page 		   #
#----------------------------------#
function lib_main($body="",$title="",$menu="")
  {
    global $f, $T, $P, $user, $SEARCH, $S, $refresh;  
    
	 #- Default Title
	 if($title)
	 	$title = "Adrevenue :: $title";
	 else
	 	$title = "Adrevenue";
	
	 $tpl = new XTemplate("main.html");
	 
	 if(!$user[id])
	 {
		$menu = "
		<a href=ad.php?c=login><font color=FFFF00><b>Login</b></font></a>&nbsp;|
		<a href=ad.php?c=register><font color=FFFF00><b>Register</b></font></a>";
		$tpl->assign("MENU", $menu);
	 }
	 else
	 {
		$menu = "
		<a href=ad.php?c=logout><font color=EEEEEE><b>Logout</b></font></a>&nbsp;|
		<a href=ad.php?c=ads><font color=EEEEEE><b>My Ads</b></font></a>&nbsp;|
		<a href=ad.php?c=funds><font color=EEEEEE><b>Add Money</b></font></a>&nbsp;";
		if($user[email] == "admin")
		{
			$wtitle = iif($S[adtype] == "page", "Categories", "Keywords");
			$menu .= "|&nbsp;<a href=ad.php?c=keywords><font color=yellow><b>$wtitle</b></font></a>&nbsp;";
			$menu .= "|&nbsp;<a href=ad.php?c=admin><font color=yellow><b>Clients</b></font></a>&nbsp;";
		}
		$tpl->assign("MENU",$menu );
	 }

	 $tpl->assign("TITLE",$title);
	 $tpl->assign("BODY",$body);
	 $tpl->assign("REFRESH", $refresh);
	 $tpl->assign("HOME", $P[url]."/ad.php");
	 $tpl->parse("main");
	 $out = $tpl->text("main");
	 
	$URL = $P[url];
	$out = str_replace('"/index.php',"\"$URL/index.php",$out);
	$out = str_replace('"index.php',"\"$URL/index.php",$out);
	$out = str_replace("=index.php","=$URL/index.php",$out);
	$out = str_replace("=/index.php","=$URL/index.php",$out);     
     
	echo $out;
	$refresh = "";
	
	return("");      
  }#end lib_main


#----------------------------------#
# Filter a phrase for stopwords    #
#----------------------------------#
function lib_filter($string, $stem = 0)
  {
    if(!$string)
      return($string);
    
    $string = strip_tags($string);
    $string = str_replace('"','',$string);
    $string = str_replace("'","",$string);
    $string = str_replace("+","",$string);
    $string = str_replace("-"," ",$string);
    $string = str_replace("  "," ",$string);
    $string = str_replace("  "," ",$string);
    $string = str_replace("  "," ",$string);
    $string = str_replace("  "," ",$string);            
    #$string = str_replace("."," ",$string);
    $string = str_replace(",","",$string);
    $string = str_replace(":","",$string);    
     
    #- Stopwords  
    $words = "a,about,above,according,across,after,afterwards,again,against,albeit,all,almost,alone,along,already,also,although,always,among,amongst,am,an,and,another,any,anybody,anyhow,anyone,anything,anyway,anywhere,apart,are,around,as,at,av,be,became,because,become,becomes,becoming,been,before,beforehand,behind,being,below,beside,besides,between,beyond,both,but,by,can,cannot,canst,certain,cf,choose,contrariwise,cos,could,cu,day,do,does,doesn,doing,dost,doth,double,down,dual,during,each,either,else,elsewhere,enough,et,etc,even,ever,every,everybody,everyone,everything,everywhere,except,
    	      excepted,excepting,exception,exclude,excluding,exclusive,far,farther,farthest,few,ff,first,for,formerly,forth,forward,from,front,further,furthermore,furthest,get,go,had,halves,hardly,has,hast,hath,have,he,hence,henceforth,her,here,hereabouts,hereafter,hereby,herein,hereto,hereupon,hers,herself,him,himself,hindmost,his,hither,hitherto,how,however,howsoever,i,ie,if,in,inasmuch,inc,include,included,including,indeed,indoors,inside,insomuch,instead,into,inward,inwards,is,it,its,itself,just,kind,kg,km,last,latter,latterly,less,lest,let,like,little,ltd,many,may,maybe,me,meantime,meanwhile,
    	      might,moreover,most,mostly,more,mr,mrs,ms,much,must,my,myself,namely,need,neither,never,nevertheless,next,no,nobody,none,nonetheless,noone,nope,nor,not,nothing,notwithstanding,now,nowadays,nowhere,of,off,often,ok,on,once,one,only,onto,or,other,others,otherwise,ought,our,ours,ourselves,out,outside,over,own,per,perhaps,plenty,provide,quite,rather,really,round,said,sake,same,sang,save,saw,see,seeing,seem,seemed,seeming,seems,seen,seldom,selves,sent,several,shalt,she,should,shown,sideways,since,slept,slew,slung,slunk,smote,so,some,somebody,somehow,someone,something,sometime,sometimes,somewhat,
    	      somewhere,spake,spat,spoke,spoken,sprang,sprung,stave,staves,still,such,supposing,than,that,the,thee,their,them,themselves,then,thence,thenceforth,there,thereabout,thereabouts,thereafter,thereby,therefore,therein,thereof,thereon,thereto,thereupon,these,they,this,those,thou,though,thrice,through,throughout,thru,thus,thy,thyself,till,to,together,too,toward,towards,ugh,unable,under,underneath,unless,unlike,until,up,upon,upward,upwards,us,use,used,using,very,via,vs,want,was,we,week,well,were,what,whatever,whatsoever,when,whence,whenever,whensoever,where,whereabouts,whereafter,whereas,whereat,whereby,
    	      wherefore,wherefrom,wherein,whereinto,whereof,whereon,wheresoever,whereto,whereunto,whereupon,wherever,wherewith,whether,whew,which,whichever,whichsoever,while,whilst,whither,who,whoa,whoever,whole,whom,whomever,whomsoever,whose,whosoever,why,will,wilt,with,within,without,worse,worst,would,wow,ye,yet,year,yippee,you,your,yours,yourself,yourselves,a,ii,about,above,according,across,39,actually,ad,adj,ae,af,after,afterwards,ag,again,against,ai,al,all,almost,alone,along,already,also,although,always,am,among,amongst,an,and,another,any,anyhow,anyone,anything,anywhere,ao,aq,ar,are,aren,aren't,around,arpa,as,
    	      at,au,aw,az,b,ba,bb,bd,be,became,because,become,becomes,becoming,been,before,beforehand,begin,beginning,behind,being,below,beside,besides,between,beyond,bf,bg,bh,bi,billion,bj,bm,bn,bo,both,br,bs,bt,but,buy,bv,bw,by,bz,c,ca,can,can't,cannot,caption,cc,cd,cf,cg,ch,ci,ck,cl,click,cm,cn,co,co.,com,copy,could,couldn,couldn't,cr,cs,cu,cv,cx,cy,cz,d,de,did,didn,didn't,dj,dk,dm,do,does,doesn,doesn't,don,don't,down,during,dz,e,each,ec,edu,ee,eg,eh,eight,eighty,either,else,elsewhere,end,ending,enough,er,es,et,etc,even,ever,every,everyone,everything,everywhere,except,f,few,fi,fifty,find,first,five,fj,fk,fm,fo,for,
    	      former,formerly,forty,found,four,fr,free,from,further,fx,g,ga,gb,gd,ge,get,gf,gg,gh,gi,gl,gm,gmt,gn,go,gov,gp,gq,gr,gs,gt,gu,gw,gy,h,had,has,hasn,hasn't,have,haven,haven't,he,he'd,he'll,he's,help,hence,her,here,here's,hereafter,hereby,herein,hereupon,hers,herself,him,himself,his,hk,hm,hn,home,homepage,how,however,hr,ht,htm,html,http,hu,hundred,i,i'd,i'll,i'm,i've,i.e.,id,ie,if,il,im,in,inc,inc.,indeed,information,instead,int,into,io,iq,ir,is,isn,isn't,it,it's,its,
    	      itself,j,je,jm,jo,join,jp,k,ke,kg,kh,ki,km,kn,kp,kr,kw,ky,kz,l,la,last,later,latter,lb,lc,least,less,let,let's,li,like,likely,lk,ll,lr,ls,lt,ltd,lu,lv,ly,m,ma,made,make,makes,many,maybe,mc,md,me,meantime,meanwhile,mg,mh,might,mil,million,miss,mk,ml,mm,mn,mo,more,moreover,most,mostly,mp,mq,mr,mrs,ms,msie,mt,mu,much,must,mv,mw,mx,my,myself,mz,n,na,namely,nc,ne,neither,net,netscape,never,nevertheless,new,next,nf,ng,ni,nine,ninety,nl,no,nobody,none,nonetheless,noone,nor,not,nothing,now,nowhere,np,nr,nu,nz,o,of,off,often,om,on,once,one,one's,only,onto,or,org,other,others,otherwise,our,ours,ourselves,
    	      out,over,overall,own,p,pa,page,pe,per,perhaps,pf,pg,ph,pk,pl,pm,pn,pr,pt,pw,py,q,qa,r,rather,re,recent,recently,reserved,ring,ro,ru,rw,s,sa,same,sb,sc,sd,se,seem,seemed,seeming,seems,seven,seventy,several,sg,sh,she,she'd,she'll,she's,should,shouldn,shouldn't,si,since,site,six,sixty,sj,sk,sl,sm,sn,so,some,somehow,someone,something,sometime,sometimes,somewhere,sr,st,still,stop,su,such,sv,sy,sz,t,taking,tc,td,ten,text,tf,tg,test,th,than,that,that'll,that's,the,their,them,themselves,then,thence,there,there'll,there's,thereafter,thereby,therefore,therein,thereupon,these,they,they'd,they'll,they're,they've,thirty,
    	      this,those,though,thousand,three,through,throughout,thru,thus,tj,tk,tm,tn,to,together,too,toward,towards,tp,tr,trillion,tt,tv,tw,twenty,two,tz,u,ua,ug,uk,um,under,unless,unlike,unlikely,until,up,upon,us,use,used,using,uy,uz,v,va,vc,ve,very,vg,vi,via,vn,vu,w,was,wasn,wasn't,we,we'd,we'll,we're,we've,web,welcome,well,were,weren,weren't,wf,what,what'll,what's,whatever,when,whence,whenever,where,whereafter,whereas,whereby,wherein,whereupon,wherever,whether,which,while,whither,who,who'd,who'll,who's,whoever,NULL,whole,whom,whomever,whose,why,will,with,within,without,won,won't,would,wouldn,wouldn't,
    	      ws,www,x,y,ye,yes,yet,you,you'd,you'll,you're,you've,your,yours,yourself,yourselves,yt,yu,z,za,zm,zr,10,z";
    
    $w = split(" ", $string);
    
    #- Remove duplicate words
    foreach($w as $rec)
      $x[strtolower($rec)]=1;
    unset($w);
    $w = array_keys($x);  
    
    #- check against the stopword list
    foreach($w as $rec) 
      {
        $r = strtolower(trim($rec));
        
        #- try to stem word as well (simple, basic stemming)
        if($stem)
         {
           $r = eregi_replace("sses$","ss",$r);
           $r = eregi_replace("ies$","i",$r);
           $r = eregi_replace("ss$","ss",$r);
           $r = eregi_replace("s$","",$r);
         } 
        
        if(!@ereg($r,$words))
           $ret .= "$r ";
      }
    
    return(trim($ret));
    
  }#end filter



#-----------------------------#
# Authenticate a user         #
# Using HT authenticate       #
#-----------------------------#
function lib_authenticate()
  {
   Header("WWW-Authenticate: Basic realm=\"Restricted Area\"");
   Header("HTTP/1.0 401 Unauthorized");
   echo "You are not authorized to enter this section of the site!\n";
   exit;
  }



#-----------------------------------------#
# iif function                            #
# Why is not this in PHP beats me!        #
#-----------------------------------------#
function iif($condition,$value_true,$value_false)
  {
     if($condition)
       return $value_true;
     else
       return $value_false;
  }


#-------------------------------------------#
# Given an array, parse a file with vars    #
# Using our humble Xtemplate library        #
#-------------------------------------------#
function lib_xparse($template,$vals)
  {
    if(!is_array($vals))
      return "Param is Not array";

    if(!$template)
      return "No Template Parameter";

    #- Open the template
    $tpl = new XTemplate($template);

    #- get the key and value for each hash element
    while(list($k, $v) = each($vals))
      {
        $k = strtoupper($k);
	$tpl->assign("$k",$v);
      }#wend

    #- Output the stuff now
    $tpl->parse("main");
    $out = $tpl->text("main");

    return($out);

  }#end xparse


#- Return an HT List, given an array of items
#- and a default value. Does not return the name or anything
#- returns something like: <option>Item</option>
function lib_htlist($items="", $default="")
  {
    $retval = "";
    for($x=0;$x<count($items);$x++)
      {
        $i = $items[$x];
        if($default == $items[$x])
          $retval .= "\t<option value=\"$i\" iselected>$items[$x]</option>\n";
        else
          $retval .= "\t<option value=\"$i\">$items[$x]</option>\n";
      }
    return($retval);
  }#end lib_htlist


#- Returns an HT list given an associative array of items
# and a default value
# List should be named with the indexes being the keys
function lib_htlist_array($items="", $default="")
  {

    $retval = "";
    while(list($k, $v) = each($items))
      {
      	if($default == $k)
      	  $retval .= "\t<option value=\"$k\" selected>$v</option>\n";
      	else
      	  $retval .= "\t<option value=\"$k\">$v</option>\n";
      }

    return($retval);
  }#end lib_htlist


#----------------------------------------------------#
# Return a HT List, from any table, given two fields #
#----------------------------------------------------#
function lib_db_htlist($table="",$keyfield="",$valuefield="",$default="", $other="", $user="", $pass="", $host="", $dbase="")
   {
     global $sql, $errormsg;

     $sql = "SELECT $keyfield,$valuefield FROM $table $other ORDER BY $valuefield";
     
     $result = lib_getsql($sql);
     if($result)     
       foreach($result as $rec)
        {
          $name = $rec[$valuefield];
	  $id   = $rec[$keyfield];
	  
	  if($default == $id)
	    $clist .= "\t\t<option value=\"$id\" selected>$name</option>\n";
	  else
	    $clist .= "\t\t<option value=\"$id\">$name</option>\n";
        }

      return $clist;
   }#- End lib_categories



#-------------------------------#
# Retrieve records from a table #
#-------------------------------#
function lib_getsql($query,$user="",$pass="",$host="",$dbase="")
  {
    global $DBLIB, $errormsg;
    
    if($DBLIB[engine] == "mysql")
      return(lib_my_getsql($query,$user,$pass,$host,$dbase));
    if($DBLIB[engine] == "pgsql")
      return(lib_pg_getsql($query,$user,$pass,$host,$dbase));
    
    $errormsg = "No database driver selected.";
    return(0);    
  }



#-------------------------------#
# Retrieve records from a table #
# based on SQL passed in        #
# Postgresql Version		#
#-------------------------------#
function lib_pg_getsql($query, $user="", $pass="", $host="", $dbase="")
      {
        global $sql, $errormsg, $DBLIB;
	
	$sql = $query;
	$retval = array();
	$cstring = "host=".$DBLIB[host]." port=".$DBLIB[port]." dbname=".$DBLIB[database]." user=".$DBLIB[user]." password=".$DBLIB[password];
	
	if($DBLIB[persistent])
	  $db = @pg_pconnect($cstring);
	else
	  $db = @pg_connect($cstring);	  
	  
	$result = @pg_exec($sql);
	
	if($result)
  	  {
	    $num = @pg_numrows($result); 
	    for($i=0; $i<$num; $i++) 
  	       $retval[] = @pg_fetch_array($result,$i,PGSQL_ASSOC);
  	  }     
	else
	  {
	    $errormsg = @pg_errormessage($db);
	  }
	

        return $retval;
      }#fi get_records


#-------------------------------#
# Retrieve records from a table #
# based on SQL passed in        #
# Mysql Version			#
#-------------------------------#
function lib_my_getsql($query, $user="", $pass="", $host="", $dbase="")
      {
        global $sql, $errormsg, $DBLIB;

	$sql = $query;
	$retval = array();
	
	if($DBLIB[persistent])
	  $db = @mysql_pconnect($DBLIB[host],$DBLIB[user],$DBLIB[password]);
	else
	  $db = @mysql_connect($DBLIB[host],$DBLIB[user],$DBLIB[password]);
	  	  
	@mysql_select_db($DBLIB[database]);
	
	$result = @mysql_query($sql);
	
	if($result)
  	  {
	    while($rec = @mysql_fetch_assoc($result))
	      {
	        if($rec)
	          $retval[] = $rec;
	      }	    
  	  }     
	else
	  {
	    $errormsg = @mysql_error($db);
		print "<hr>";
		print "<b>Database ERROR:</b><br><code>$errormsg</code><br>$sql";
		print "<hr>";
		exit(0);
	  }
	
        return $retval;
      }#fi get_records



#-------------------------------#
# Retrieve records from a table #
#-------------------------------#
function lib_getrecords($table="",$key="",$value="",$more="", $user="", $pass="", $host="", $dbase="")
      {
        global $sql, $errormsg;
        $sql = "SELECT * FROM $table WHERE \"$key\"=\"$value\" $more";
	return(lib_getsql($sql));
      }#fi get_records


#-------------------------------#
# Insert records in a table     #
#-------------------------------#
function lib_insert($table="", $arr="")
  {
    global $sql, $errormsg, $DBLIB;

    if(!is_array($arr))
      return(0);


     while(list($k, $v) = each($arr))
       {
         $cols[] = $k;
         $vals[] = addslashes($v);
       }#-wend

	 if($DBLIB[engine] == "pgsql")  
     	$names = '"'.join('","',$cols).'"';
	 else
	 	$names = join(',',$cols);
		
     $vals  = "'" . join("','",$vals) . "'";

     $retval = 0;
     $sql = "INSERT INTO $table ($names) VALUES($vals)";
     lib_getsql($sql);
     
	 #- Get the return ID for MySQL
	 if($DBLIB[engine] = "mysql")
	 {
	 	$j = lib_getsql("SELECT LAST_INSERT_ID() as num");
	 	$retval = $j[0][num]; 
	 }
	 
     return($retval);
  }#-end lib_insert



#-------------------------------#
# Do a simple db update         #
#-------------------------------#
function lib_update($table="", $key_col="", $key_val="", $arr="", $user="", $pass="", $host="", $dbase="" )
  {
    global $sql, $errormsg;


    if(!$table || !$key_col || !$key_val || !$arr)
      {
        return(0);
      }#fi

     while(list($k, $v) = each($arr))
	 {
		 $v = addslashes($v);
         $pairs[] = "$k='$v'";
	 }

     $cols = join(",", $pairs);
     $retval=0;

     $sql = "UPDATE $table SET $cols WHERE $key_col ='$key_val'";
     lib_getsql($sql);

    return(0);
  }#end lib_update


#-------------------------------#
# delete some record(s)         #
#-------------------------------#
function lib_delete($table="", $key_col="", $key_val="", $user="", $pass="", $host="", $dbase="" )
  {
    global $sql, $errormsg;

    if(!$table || !$key_col)
        return(0);

    $sql = "DELETE FROM $table WHERE \"$key_col\"='$key_val'";
    lib_getsql($sql);

    return(0);
  }#end lib_delete


#  Convert to UNIX epoch date
function lib_toepoch($m,$d,$y)
  {
    $date = mktime(0, 0, 0, $m, $d, $y);
	return($date);
  }


#- Return an array from a mysql date y-m-d
function lib_sqldate($mysqldate)
  {
    return(list($year, $month, $date) = split('[/.-]', $mysqldate));
  }

#- convert a SQL date into a UNIX date
function lib_sqltoepoch($mysqldate)
  {
    $d = lib_sqldate($mysqldate);
    return(lib_toepoch($d[1],$d[2],$d[0]));
  }

# Convert to standard date
function lib_frepoch($epoch)
  {
    $date = getdate($epoch);
    $date = $date['mon'] . "-" . $date['mday'] . "-" . $date['year'];
    return($date);
  }


#- Put together some input fields with dates
#- will make 3 fields:
#  $f[fieldname_month], $f[fieldname_day], $f[fieldname_year]
#  Will make all into drop down lists
#  USEAGE:
#  No date - will return today's date
#  A MYSQL date will return that date
#  An epoch date - will return that date
function lib_dateinput($name="date",$date="")
   {
     if(!$date)
	   {
	     $date = time();
	     $curr_day = date("j",$date);
	     $curr_year = date("Y",$date);
	     $curr_month = date("n",$date);
	   }
	 else
	   {
	     #- Check if this thing is in the MYSQL format
		 if(preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$date))
		   {
		     $curr_day = substr($date,8,2);
		     $curr_year = substr($date,0,4);
		     $curr_month = substr($date,5,2);
		   }
		 if(preg_match("/[0-9]{8}/",$date))
		   {
 		     $curr_day = date("j",$date);
		     $curr_year = date("Y",$date);
		     $curr_month = date("n",$date);
		   }
	   }


         $max_days=31;

	 #- Account for years long past
	 $start = $curr_year - 100;
	 $end   = $curr_year + 100;
	 for($x=$start;$x<=$end;$x++)
	   $year[]=$x;

	 #- Setup months
	 $month[1]="January";
	 $month[2]="February";
	 $month[3]="March";
	 $month[4]="April";
	 $month[5]="May";
	 $month[6]="June";
	 $month[7]="July";
	 $month[8]="August";
	 $month[9]="September";
	 $month[10]="October";
	 $month[11]="November";
	 $month[12]="December";

	 #- Prepare monthpart
	 $monthpart = "\t<select name=f[$name" . "_month]" . ">\n";
	 for($x=1; $x<13 ;$x++)
	   {
	     if($curr_month == $x)
		    $monthpart .= "\t\t<option value=$x selected>$month[$x]</option>\n";
		 else
		    $monthpart .= "\t\t<option value=$x>$month[$x]</option>\n";
	   }#-rof
	 $monthpart .= "\t</select>\n";

	 #- Prepare datepart
	 $datepart = "\t<select name=f[$name" . "_day]" . ">\n";
	 $x=0;
	 for($x=1; $x<=$max_days; $x++)
	   {
	     if($curr_day == $x)
		   $datepart .= "\t\t<option value=$x selected>$x</option>\n";
		 else
		   $datepart .= "\t\t<option value=$x>$x</option>\n";
	   }
	 $datepart .= "\t</select>\n";

	 #- Prepare yearpart
	 $yearpart = "\t<select name=f[$name" . "_year]" . ">\n";
	 for($x=$year[0]; $x <= $year[count($year)-1]; $x++)
	    {
		  if($curr_year == $x)
		     $yearpart .= "\t\t<option value=$x selected>$x</option>\n";
		  else
		     $yearpart .= "\t\t<option value=$x>$x</option>\n";
		}#-rof
	 $yearpart .= "\t</select>";

	 $parts = $monthpart . $datepart . $yearpart;
	 return($parts);
   }#- end function


#- Check an e-mail address
function lib_checkemail($email) {
if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$", $email, $check)) {
if ( getmxrr(substr(strstr($check[0], '@'), 1), $validate_email_temp) ) {
return TRUE;
}
// THIS WILL CATCH DNSs THAT ARE NOT MX.
if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY")){
return TRUE;
}
}
return FALSE;
}#-end




// Created By: Joe Stump
// Created On: 2000-12-14
// Notes: This is the infamous "Porter Stemming Algorithm" - lots
// of serach engines use something similar to relate words. An example
// would be to turn "running" into "run" but "kissing" into "kiss".
// 
// Returns: string PorterStem(string word)
  function stemmer($word)
  {
    /**
     This program is free software; you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation; either version 2 of the License, or
     (at your option) any later version.

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with this program; if not, write to the Free Software
     Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
     INSTRUCTIONS FOR USE:
     **/

    $vowels = array('a','e','i','o','u','y');
    $o      = array('w','x','y');

    $test_m = eregi_replace('^[a-z]','',$word);
    $test_m = eregi_replace('[a-z]$','',$test_m); 
    for($i = 0 ; $i < strlen($test_m) ; ++$i)
    {
      if(eregi('[b-df-hj-np-tw-z]',$test_m[$i]) && eregi('[aeiou]',$test_m[($i - 1)]))
      {
        ++$total;
      }
    }

    if($total)
    {
      $m = $total;
    }
    else
    {
      $m = 0;
    }

    // Step 1a
    $replaces = array(
		  '/sses$/' => 'ss',
                  '/ies$/' => 'i',
                  '/ss$/' => 'ss',
                  '/s$/' => '');

    while(list($search,$replace) = each($replaces))
    {
      #$word = eregi_replace($search,$replace,$word);
      $word = preg_replace($search,$replace,trim($word));
      #print $word;
    }

    // Step 1b
    if($m > 0)
    {
      $word = eregi_replace('eed$','ee',$word);
    }

    $do_more = 0;
    if(eregi('[aeiou]',eregi_replace('ed$','',$word)))
    {
      $word = eregi_replace('ed$','',$word);
      $do_more = 1;
    }
   
    if(eregi('[aeiou]',eregi_replace('ing$','',$word)))
    {
      $word = eregi_replace('ing$','',$word);
      $do_more = 1;
    }

    if($do_more)
    {
      $more_replace = array(
                        'at$' => 'ate',
                        'bl$' => 'ble',
                        'iz$' => 'ize');
      while(list($s,$r) = each($more_replace))
      {
        $word = eregi_replace($s,$r,$word);
      }

      $length = strlen($word);
      $last_letter = substr($word,($length - 1),$length);
      $next_last_letter = substr($word,(strlen($word) - 2),-1);
      if(($last_letter == $next_last_letter) && !in_array($last_letter,array('l','s','z')))
      {
        $word = substr($word,0,(strlen($word) - 1));
      }

      if($m == 1 && eregi('[b-df-hj-np-tv-z][aeiou][b-df-hj-np-tv-z]$',$word) && !in_array($last_letter,$o))
      {
        $word .= 'e';
      }
    }

  
    // Step 1c
    if(eregi('[aeiou]',$word) && eregi('y$',$word) && $m == 1 && !eregi('ly$',$word))
    {
      $word = eregi_replace('y$','i',$word);
    }

    // Step 2
    $replaces = array(
                  'ational$' => 'ate',
                  'tional$' => 'tion',
                  'enci$' => 'ence',
                  'anci$' => 'ance',
                  'izer$' => 'ize',
                  'abli$' => 'able',
                  'alli$' => 'al',
                  'entli$' => 'ent',
                  'eli$' => 'e',
                  'ousli$' => 'ous',
                  'ization$' => 'ize',
                  'ation$' => 'ate',
                  'ator$' => 'ate',
                  'alism$' => 'al',
                  'iveness$' => 'ive',
                  'fulness$' => 'ful',
                  'ousness$' => 'ous',
                  'aliti$' => 'al',
                  'iviti$' => 'ive',
                  'biliti$' => 'ble');

    while(list($search,$replace) = each($replaces))
    {
      if($m > 0)
      {
        $word = eregi_replace($search,$replace,$word);
      }
    }

    // Step 3
    $replaces = array(
                  'icate$' => 'ic',
                  'ative$' => '',
                  'alize$' => 'al',
                  'iciti$' => 'ic',
                  'ical$' => 'ic',
                  'ful$' => '',
                  'ness$' => '');

    while(list($search,$replace) = each($replaces))
    {
      if($m > 0)
      {
        $word = eregi_replace($search,$replace,$word);
      }
    }

    // Step 4a
    $replaces = array(
                  'al$' => '',
                  'ance$' => '',
                  'ence$' => '',
                  'er$' => '',
                  'ic$' => '',
                  'able$' => '',
                  'ible$' => '',
                  'ant$' => '',
                  'ement$' => '',
                  'ment$' => '',
                  'ent$' => '',
                  'tion$' => 't',
                  'sion$' => 's',
                  'ou$' => '',
                  'ism$' => '',
                  'ate$' => '',
                  'iti$' => '',
                  'ous$' => '',
                  'ive$' => '',
                  'ize$' => '');

    while(list($search,$replace) = each($replaces))
    {
      if($m > 1)
      {
        $word = eregi_replace($search,$replace,$word);
      }
    }

    // Step 5a
    if($m > 1 && ereg('e$',$word))
    {
      $word = eregi_replace('e$','',$word);
    }

    if($m == 1 && !(eregi('[b-df-hj-np-tv-z][aeiou][b-df-hj-np-tv-z]$',$word) && !in_array($last_letter,$o)))
    {
      $word = eregi_replace('e$','',$word);
    }

    // Step 5b
    if($m > 1)
    {
      $word = eregi_replace('ll$','l',$word); 
    }

    // This is NOT part of the original Porter Stemming Algorithm
    if($m > 0)
    {
      $word = eregi_replace('ly$','',$word);
    }
    return $word;
  }

?>
