<?php

class vvis {

  var $iwidth;            //image width
  var $iheight;           //image height
  var $img;               //image handler
  var $bgcolor;           //image handler
  var $col;               //top color
  var $tcol;              //front color
  var $bcol;              //right color
  var $gcol;              //grid color
  var $vars;              //variables for templates
  var $gid;               //ID of graph's data
  var $type;              //image type - pie,bar,graph
  var $act;               //action
  var $stat;              //statistics - visitors,hosts,reloads,hits
  var $rf;                //root folder
  var $pref;              //prefix for current module

function vvis($rf) {
  global $err,$conf;

  //default values
  $this->rf=$rf;
  $this->pref='';
  $this->vars=array();
  $this->iwidth=_T_WIDTH;
  $this->iwidth*=0.95;
  $this->iheight=320;
  $this->img=0;
  $this->gid=0;
  $this->act='';
  $this->type='';
  $this->stat='';
}

function show() {
  global $err,$conf,$gdb;

  $this->params();
  if($err->flag) {$err->reason('vvis.php|show|can\'t get parameters of view form');return;}

  if(!empty($this->pref)) {
    if(!strcmp($this->pref,'aat_')) loadmod($this->pref,'./','./modules/tracker/');
  }

  //globals variables
  $this->vars['RF']=$this->rf;
  $this->vars['ID']='';
  $this->vars['P2']='';
  $this->vars['OLDACT']=$this->act;
  $this->vars['LISTCUR']=0;
  $this->vars['LANG']=$conf->lang;
  $this->vars['STYLE']=$conf->style;
  $this->vars['SCRIPT']='view';
  $this->vars['VERSION']=_VERSION;
  $this->vars['VER']=$conf->{$this->pref.'version'};
  $this->vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->{$this->pref.'site'}.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
  $this->vars['FAQ']=_FAQ;
  $this->vars['SUPPORT']=_SUPPORT;
  $this->vars['CHARSET']=_CHARSET;
  if(!empty($this->pref)) {
    $this->vars['SERIES']=$conf->series.' / '.$conf->{$this->pref.'name'};
    $this->vars['TITLE']='ActualAnalyzer '.$conf->series.' / '.$conf->{$this->pref.'name'}.' - '._ADMINAREA;
  }
  else {
    $this->vars['SERIES']=$conf->series;
    $this->vars['TITLE']='ActualAnalyzer '.$conf->series.' - '._VIEWAREA;
  }
  $this->vars['UNAME']=$conf->uname;
  $this->vars['PASSW']=$conf->passw;
  $this->vars['SITE']=$conf->{$this->pref.'site'};
  $this->vars['BACKTT']=_BACKTOTOP;

  //top
  $this->vars['SCROLL']='';
  $this->top($this->vars);

  $vals=array();
  $gdb->values($vals,$this->gid,$this->stat);

  //visualization
  $this->report($vals);

  //bottom
  $this->bottom($this->vars);
}

//Report with image    -------------------------------------------------------//
function report(&$vals) {
  global $err,$conf,$gdb;

  if(!strcmp($this->stat,'summary')) require './style/'.$conf->style.'/template/cgraph.php';
  else require './style/'.$conf->style.'/template/graphics.php';

  //correct
  $tt=preg_split("/\|/",$vals[0]);
  unset($vals[0]);

  //name of extended action
  if(!strcmp($this->stat,'summary')) $this->vars['HEADER']=_SUMMARY;
  elseif(!strcmp($this->stat,'visitors')) $this->vars['HEADER']=_VISITORS;
  elseif(!strcmp($this->stat,'hosts')) $this->vars['HEADER']=_HOSTS;
  elseif(!strcmp($this->stat,'reloads')) $this->vars['HEADER']=_RELOADS;
  elseif(!strcmp($this->stat,'hits')) $this->vars['HEADER']=_HITS;
  else $this->vars['HEADER']='';
  $extact=$this->vars['HEADER'];
  if(!empty($this->vars['HEADER'])) $this->vars['HEADER'].=' / ';

  //name of action and table header
  $tflag=1;         //1-text,2-plain,3-link,4-url,5-image,6-icon
  if($tt[0]==221) {$hname=_GROUP;$tflag=1;}
  else {$hname=_PAGE;$tflag=3;}
  $actname=_UNDEFINED;
  if(!strcmp($this->act,'vis_int')) $actname=_VISINT;
  elseif(!strcmp($this->act,'vis_grpg')) $actname=_VISGRPG;
  elseif(!strcmp($this->act,'refserv')) {$actname=_REFSERVS;$hname=_REFSERV;$tflag=2;}
  elseif(!strcmp($this->act,'allrefpg')) {$actname=_ALLREFPGS;$hname=_REFPG;$tflag=4;}
  elseif(!strcmp($this->act,'intrefpg')) {$actname=_INTREFPGS;$hname=_REFPG;$tflag=4;}
  elseif(!strcmp($this->act,'extrefpg')) {$actname=_EXTREFPGS;$hname=_REFPG;$tflag=4;}
  elseif(!strcmp($this->act,'onlinegrpg')) {$actname=_ONLINEBYPG;$hname=_PAGE;$tflag=3;}
  elseif(!strcmp($this->act,'countries')) {$actname=_COUNTRIES;$hname=_COUNTRY;$tflag=5;$this->vars['CAT']='flags';}
  elseif(!strcmp($this->act,'languages')) {$actname=_LANGUAGES;$hname=_LANGUAGE;$tflag=1;}
  elseif(!strcmp($this->act,'browsers')) {$actname=_BROWSERS;$hname=_BROWSER;$tflag=5;$this->vars['CAT']='browsers';}
  elseif(!strcmp($this->act,'oss')) {$actname=_OSS;$hname=_OS;$tflag=5;$this->vars['CAT']='os';}
  elseif(!strcmp($this->act,'screen')) {$actname=_SRESOLUTIONS;$hname=_SRESOLUTION;$tflag=1;}
  elseif(!strcmp($this->act,'colord')) {$actname=_COLORDEPTH;$hname=_COLORDEPTH;$tflag=1;}
  elseif(!strcmp($this->act,'jscript')) {$actname=_JAVASCRIPT;$hname=_JAVASCRIPT;$tflag=1;}
  elseif(!strcmp($this->act,'java')) {$actname=_JAVA;$hname=_JAVA;$tflag=6;}
  elseif(!strcmp($this->act,'cookie')) {$actname=_COOKIE;$hname=_COOKIE;$tflag=6;}
  elseif(!strcmp($this->act,'entry')) $actname=_ENTRYGRPG;
  elseif(!strcmp($this->act,'exits')) $actname=_EXITGRPG;
  elseif(!strcmp($this->act,'single')) $actname=_SINGLE;
  elseif(!strcmp($this->act,'transto')) {$actname=_TRANSTO;$hname=_TRANSFROM;}
  elseif(!strcmp($this->act,'transfrom')) {$actname=_TRANSFROM;$hname=_TRANSTO;}
  elseif(!strcmp($this->act,'timeonpg')) {$actname=_TIMEONGRPG;$hname=_TIMEINT;$tflag=1;}
  elseif(!strcmp($this->act,'viewd')) {$actname=_DEPTHOFVIEW;$hname=_DEPTH;$tflag=1;}
  elseif(!strcmp($this->act,'rets')) {$actname=_RETBACK;$hname=_TIMEINT;$tflag=1;}
  elseif(!strcmp($this->act,'engines')) {$actname=_SENGINES;$hname=_SENGINE;$tflag=1;}
  elseif(!strcmp($this->act,'swords')) {$actname=_SWORDS;$hname=_SWORD;$tflag=1;}
  elseif(!strcmp($this->act,'sphrases')) {$actname=_SPHRASES;$hname=_SPHRASE;$tflag=1;}
  elseif(!strcmp($this->act,'frames')) {$actname=_PGINFRAMES;$hname=_FRAMEADDR;$tflag=4;}
  elseif(!strcmp($this->act,'tzones')) {$actname=_TZONES;$hname=_TZONE;$tflag=1;}
  elseif(!strcmp($this->act,'providers')) {$actname=_PROVIDERS;$hname=_PROVIDER;$tflag=1;}
  elseif(!strcmp($this->act,'proxy')) {$actname=_PROXYS;$hname=_PROXY;$tflag=1;}
  elseif(!strcmp($this->act,'cls_int')) $actname=_T_CLICKSINT;
  elseif(!strcmp($this->act,'cls_grlink')) $actname=_T_CLICKSGRLINK;
  elseif(!strcmp($this->act,'refpg')) {$actname=_T_REFPGS;$hname=_REFPG;$tflag=4;}
  elseif(!strcmp($this->act,'timeonsite')) {$actname=_T_TIMEONSITE;$hname=_TIMEINT;$tflag=1;}
  elseif(!strcmp($this->act,'timeonpgb')) {$actname=_T_TIMEONPG;$hname=_TIMEINT;$tflag=1;}
  elseif(!strcmp($this->act,'hitsmake')) {$actname=_T_HITSMAKE;$hname=_T_HITSQUANT;$tflag=1;}
  elseif(!strcmp($this->act,'pviewd')) {$actname=_T_PGVIEWED;$hname=_T_PAGESQUANT;$tflag=1;}

  $this->vars['HEADER'].=$actname.' / '.$tt[1];

  //group/page name
  $name='';
  $url='';
  if($tt[0]!=221) {
    $gdb->getnamegrpg($tt[0],$name,$url);
    if($err->flag) {$err->reason('vvis.php|report|can\'t get name of group/page');return;}
  }

  //check values
  $dzero=true;
  if(!strcmp($this->stat,'summary')) $fcheck=true;
  else $fcheck=false;
  reset($vals);
  while($e=each($vals)) {
    $tarr = preg_split("/\|/",$e[1]);
    if($tarr[1]>0) {$dzero=false;break;}
    if($fcheck) {
      if($tarr[4]>0) {$dzero=false;break;}
      if($tarr[7]>0) {$dzero=false;break;}
      if($tarr[10]>0) {$dzero=false;break;}
    }
  }
  $tot=sizeof($vals);
  if($dzero) $tot=0;

  //variables for modules
  if(!strcmp($this->pref,'aat_')) {
    $extact=_T_CLICKS;
    $fstr=_T_FORLINK;
  }
  else $fstr=_FORPG;

  if((!strcmp($this->act,'vis_int'))||(!strcmp($this->act,'cls_int'))) {
    $this->vars['SHOWING']=_SHOWING.' '.$tot.' '._INTERVAL_S.' ';
    if($tt[0]==221) $this->vars['FPG']=_FORALLGRS;
    elseif($tt[0]>200) $this->vars['FPG']=_FORGR." '<b><i>".$name."</i></b>'";
    else {
      $fname=$name;
      if(strlen($fname)>_VS_PGSTITLINT) $sname=substr($fname,0,_VS_PGSTITLINT-3).'...';
      else $sname=$fname;
      $this->vars['FPG']=$fstr.' \'<b><i><a href="'.$url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
    }
  }
  else {
    $this->vars['SHOWING']=_SHOWING.' '.$tot.' '._ITEM_S.' ';
    if($tot!=0) {
      reset($vals);
      $bp=each($vals);
      end($vals);
      $be=each($vals);
      $this->vars['SHOWING'].=$bp[0].' - '.$be[0].' '._OUTOF.' '.$tt[2];
    }
    else $this->vars['SHOWING'].='0 - 0 '._OUTOF.' 0';
    if($tt[0]==221) $this->vars['FPG']=_FORALLGRS;
    elseif($tt[0]>200) $this->vars['FPG']=_FORGR." '<b><i>".$name."</i></b>'";
    else {
      $fname=$name;
      if(strlen($fname)>_VS_PGSTITLITEM) $sname=substr($fname,0,_VS_PGSTITLITEM-3).'...';
      else $sname=$fname;
      $this->vars['FPG']=$fstr.' \'<b><i><a href="'.$url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
    }
  }
  tparse($begin,$this->vars);

  if($dzero) {
    //data is empty
    $this->vars['MESS']=_ZEROVALS;
    tparse($mess,$this->vars);
  }
  if(!$dzero) {
    //picture
    $this->vars['IMG']="<img src=pict.php?gid=".$this->gid."&act=".$this->act."&stat=".$this->stat."&type=".$this->type."&style=".$conf->style."&language=".$conf->lang.">";
    tparse($image,$this->vars);

    if(!strcmp($this->stat,'summary')) {
        //summary table with time
        if((!strcmp($this->act,'vis_int'))||(!strcmp($this->act,'cls_int'))) {
          //table header
          $this->vars['NAME']=_TIMEINT;
          $this->vars['VISITORS']=_VISITORS;
          $this->vars['HOSTS']=_HOSTS;
          $this->vars['RELOADS']=_RELOADS;
          $this->vars['HITS']=_HITS;
          tparse($ttime,$this->vars);

          //rows
          $num=1;
          reset($vals);
          while($e=each($vals)) {
            $tarr = preg_split("/\|/",$e[1]);
            $this->vars['NAME']=$tarr[12];
            $this->vars['VISITORS']=$tarr[1];
            $this->vars['HOSTS']=$tarr[4];
            $this->vars['RELOADS']=$tarr[7];
            $this->vars['HITS']=$tarr[10];
            tparse($ttext,$this->vars);
            $num++;
          }

          //total
          $this->vars['NAME']=$tt[19];
          $this->vars['VISITORS']=$tt[5];
          $this->vars['HOSTS']=$tt[9];
          $this->vars['RELOADS']=$tt[13];
          $this->vars['HITS']=$tt[17];
          tparse($delimiter,$this->vars);
          $this->vars['NAME']=_MINIMUM;
          $this->vars['VISITORS']=$tt[3];
          $this->vars['HOSTS']=$tt[7];
          $this->vars['RELOADS']=$tt[11];
          $this->vars['HITS']=$tt[15];
          tparse($foot,$this->vars);
          $this->vars['NAME']=_AVERAGE;
          $this->vars['VISITORS']=sprintf("%.0f",$tt[5]/$tt[2]);
          $this->vars['HOSTS']=sprintf("%.0f",$tt[9]/$tt[2]);
          $this->vars['RELOADS']=sprintf("%.0f",$tt[13]/$tt[2]);
          $this->vars['HITS']=sprintf("%.0f",$tt[17]/$tt[2]);
          tparse($foot,$this->vars);
          $this->vars['NAME']=_MAXIMUM;
          $this->vars['VISITORS']=$tt[4];
          $this->vars['HOSTS']=$tt[8];
          $this->vars['RELOADS']=$tt[12];
          $this->vars['HITS']=$tt[16];
          tparse($foot,$this->vars);

          //end of table
          tparse($tend,$this->vars);
        }
        //summary table with parameters
        else {
          //table header
          $this->vars['NAME']=$hname;
          $this->vars['VISITORS']=_VISITORS;
          $this->vars['HOSTS']=_HOSTS;
          $this->vars['RELOADS']=_RELOADS;
          $this->vars['HITS']=_HITS;
          tparse($tparam,$this->vars);

          //rows
          $num=1;
          $vsum=0;
          $hssum=0;
          $rsum=0;
          $htsum=0;
          $vmin=-1;
          $hsmin=-1;
          $rmin=-1;
          $htmin=-1;
          $vmax=0;
          $hsmax=0;
          $rmax=0;
          $htmax=0;
          reset($vals);
          while($e=each($vals)) {
            $tarr = preg_split("/\|/",$e[1]);

            //total,min,max values for frame
            $vsum+=$tarr[1];
            if($tarr[1]>$vmax) $vmax=$tarr[1];
            if($vmin==-1) $vmin=$tarr[1];
            elseif($tarr[1]<$vmin) $vmin=$tarr[1];
            $hssum+=$tarr[4];
            if($tarr[4]>$hsmax) $hsmax=$tarr[4];
            if($hsmin==-1) $hsmin=$tarr[4];
            elseif($tarr[4]<$hsmin) $hsmin=$tarr[4];
            $rsum+=$tarr[7];
            if($tarr[7]>$rmax) $rmax=$tarr[7];
            if($rmin==-1) $rmin=$tarr[7];
            elseif($tarr[7]<$rmin) $rmin=$tarr[7];
            $htsum+=$tarr[10];
            if($tarr[10]>$htmax) $htmax=$tarr[10];
            if($htmin==-1) $htmin=$tarr[10];
            elseif($tarr[10]<$htmin) $htmin=$tarr[10];

            if($tflag==1) {
              $this->vars['NUM']=$e[0];
              $this->vars['NAME']=$tarr[12];
              $this->vars['VISITORS']=$tarr[1];
              $this->vars['HOSTS']=$tarr[4];
              $this->vars['RELOADS']=$tarr[7];
              $this->vars['HITS']=$tarr[10];
              tparse($ctext,$this->vars);
            }
            elseif($tflag==2) {
              $this->vars['NUM']=$e[0];
              $this->vars['NAME']=$tarr[12];
              $this->vars['VISITORS']=$tarr[1];
              $this->vars['HOSTS']=$tarr[4];
              $this->vars['RELOADS']=$tarr[7];
              $this->vars['HITS']=$tarr[10];
              tparse($cplain,$this->vars);
            }
            elseif($tflag==3) {
              $this->vars['NUM']=$e[0];
              $this->vars['REFERRER']=$tarr[13];
              $tstr=$tarr[12];
              if(strlen($tstr)>_VS_REFPGS) $tstr=substr($tstr,0,_VS_REFPGS-3).'...';
              $this->vars['REFSHORT']=$tstr;
              $this->vars['VISITORS']=$tarr[1];
              $this->vars['HOSTS']=$tarr[4];
              $this->vars['RELOADS']=$tarr[7];
              $this->vars['HITS']=$tarr[10];
              tparse($curl,$this->vars);
            }
            elseif($tflag==4) {
              $this->vars['NUM']=$e[0];
              $this->vars['VISITORS']=$tarr[1];
              $this->vars['HOSTS']=$tarr[4];
              $this->vars['RELOADS']=$tarr[7];
              $this->vars['HITS']=$tarr[10];
              if(!strcmp($tarr[12],_DIRECT)) {
                $this->vars['NAME']=$tarr[12];
                tparse($cplain,$this->vars);
              }
              else {
                $this->vars['REFERRER']=$tarr[12];
                $tstr=$this->vars['REFERRER'];
                if(strlen($tstr)>_VS_REFPGS) $tstr=substr($tstr,0,_VS_REFPGS-3).'...';
                $this->vars['REFSHORT']=$tstr;
                tparse($curl,$this->vars);
              }
            }
            elseif($tflag==5) {
              $this->vars['NUM']=$e[0];
              $this->vars['IMG']=$tarr[13];
              $this->vars['NAME']=$tarr[12];
              $this->vars['VISITORS']=$tarr[1];
              $this->vars['HOSTS']=$tarr[4];
              $this->vars['RELOADS']=$tarr[7];
              $this->vars['HITS']=$tarr[10];
              tparse($cimage,$this->vars);
            }
            elseif($tflag==6) {
              $this->vars['VISITORS']=$tarr[1];
              $this->vars['HOSTS']=$tarr[4];
              $this->vars['RELOADS']=$tarr[7];
              $this->vars['HITS']=$tarr[10];
              if(!strcmp($tarr[3],_UNDEFINED)) {
                $this->vars['NAME']=$tarr[12];
                tparse($ctext,$this->vars);
              }
              else {
                $this->vars['IMG']=$tarr[13];
                $this->vars['NAME']=$tarr[12];
                tparse($icenter,$this->vars);
              }
            }
            $num++;
          }

          //frame
          if(sizeof($vals)<$tt[2]) {
            reset($vals);
            $bp=each($vals);
            end($vals);
            $be=each($vals);
            $this->vars['NAME']=$bp[0].' - '.$be[0].' '._OUTOF.' '.$tt[2];
            if((!strcmp($this->act,'vis_grpg'))||(!strcmp($this->act,'entry'))||(!strcmp($this->act,'exits'))||(!strcmp($this->act,'single'))) {
              $this->vars['VISITORS']='-';
              $this->vars['HOSTS']='-';
              $this->vars['RELOADS']='-';
              $this->vars['HITS']='-';
            }
            else {
              $this->vars['VISITORS']=$vsum;
              $this->vars['HOSTS']=$hssum;
              $this->vars['RELOADS']=$rsum;
              $this->vars['HITS']=$htsum;
            }
            tparse($delimiter,$this->vars);
            $this->vars['NAME']=_MINIMUM;
            $this->vars['VISITORS']=$vmin;
            $this->vars['HOSTS']=$hsmin;
            $this->vars['RELOADS']=$rmin;
            $this->vars['HITS']=$htmin;
            tparse($foot,$this->vars);
            $this->vars['NAME']=_AVERAGE;
            $this->vars['VISITORS']=sprintf("%.0f",$vsum/sizeof($vals));
            $this->vars['HOSTS']=sprintf("%.0f",$hssum/sizeof($vals));
            $this->vars['RELOADS']=sprintf("%.0f",$rsum/sizeof($vals));
            $this->vars['HITS']=sprintf("%.0f",$htsum/sizeof($vals));
            tparse($foot,$this->vars);
            $this->vars['NAME']=_MAXIMUM;
            $this->vars['VISITORS']=$vmax;
            $this->vars['HOSTS']=$hsmax;
            $this->vars['RELOADS']=$rmax;
            $this->vars['HITS']=$htmax;
            tparse($foot,$this->vars);
          }
          //total
          $this->vars['NAME']=_TOTAL.' (1 - '.$tt[2].')';
          $this->vars['VISITORS']=$tt[5];
          $this->vars['HOSTS']=$tt[9];
          $this->vars['RELOADS']=$tt[13];
          $this->vars['HITS']=$tt[17];
          tparse($delimiter,$this->vars);
          $this->vars['NAME']=_MINIMUM;
          $this->vars['VISITORS']=$tt[3];
          $this->vars['HOSTS']=$tt[7];
          $this->vars['RELOADS']=$tt[11];
          $this->vars['HITS']=$tt[15];
          tparse($foot,$this->vars);
          $this->vars['NAME']=_AVERAGE;
          $this->vars['VISITORS']=sprintf("%.0f",$tt[5]/$tt[2]);
          $this->vars['HOSTS']=sprintf("%.0f",$tt[9]/$tt[2]);
          $this->vars['RELOADS']=sprintf("%.0f",$tt[13]/$tt[2]);
          $this->vars['HITS']=sprintf("%.0f",$tt[17]/$tt[2]);
          tparse($foot,$this->vars);
          $this->vars['NAME']=_MAXIMUM;
          $this->vars['VISITORS']=$tt[4];
          $this->vars['HOSTS']=$tt[8];
          $this->vars['RELOADS']=$tt[12];
          $this->vars['HITS']=$tt[16];
          tparse($foot,$this->vars);

          //end of table
          tparse($tend,$this->vars);
        }

    }
    else {
        //table with time
        if((!strcmp($this->act,'vis_int'))||(!strcmp($this->act,'cls_int'))) {
          //table header
          $this->vars['NAME']=_TIMEINT;
          $this->vars['PRIM']=_INCREASE;
          $this->vars['SEC']=$extact;
          tparse($ttime,$this->vars);

          //rows
          $num=1;
          $isum=0;
          $imin=-1;
          $iflag=false;
          $imax=0;
          reset($vals);
          while($e=each($vals)) {
            $tarr = preg_split("/\|/",$e[1]);

            //summary,max,min values for frame
            $isum+=$tarr[1];
            if($tarr[1]>$imax) $imax=$tarr[1];
            if(!$iflag) {$imin=$tarr[1];$iflag=true;}
            elseif($tarr[1]<$imin) $imin=$tarr[1];

            eval("\$c=_GR_COL$num;");
            $this->vars['COL']=$c;
            eval("\$c=_GR_BCOL$num;");
            $this->vars['BCOL']=$c;
            $this->vars['NAME']=$tarr[3];
            $this->vars['PRIM']=$tarr[0];
            $this->vars['SEC']=$tarr[1];
            if(!strcmp($this->type,'graph')) tparse($ttext,$this->vars);
            else tparse($ttextc,$this->vars);
            $num++;
          }

          //total
          $this->vars['NAME']=$tt[6];
          $this->vars['PRIM']=$tt[7];
          $this->vars['SEC']=$tt[5];
          tparse($delimiter,$this->vars);
          $this->vars['NAME']=_MINIMUM;
          $this->vars['PRIM']=$imin;
          $this->vars['SEC']=$tt[3];
          tparse($foot,$this->vars);
          $this->vars['NAME']=_AVERAGE;
          $this->vars['PRIM']=sprintf("%.0f",$isum/sizeof($vals));
          $this->vars['SEC']=sprintf("%.0f",$tt[5]/$tt[2]);
          tparse($foot,$this->vars);
          $this->vars['NAME']=_MAXIMUM;
          $this->vars['PRIM']=$imax;
          $this->vars['SEC']=$tt[4];
          tparse($foot,$this->vars);

          //end of table
          tparse($tend,$this->vars);
        }
        //table with parameters
        else {
          //table header
          $this->vars['NAME']=$hname;
          $this->vars['PRIM']=$extact;
          tparse($tparam,$this->vars);

          //rows
          $num=1;
          $vsum=0;
          $vmin=-1;
          $vmax=0;
          reset($vals);
          while($e=each($vals)) {
            $tarr = preg_split("/\|/",$e[1]);

            //summary,max,min values for frame
            $vsum+=$tarr[1];
            if($tarr[1]>$vmax) $vmax=$tarr[1];
            if($vmin==-1) $vmin=$tarr[1];
            elseif($tarr[1]<$vmin) $vmin=$tarr[1];

            if($tflag==1) {
              $this->vars['NUM']=$e[0];
              eval("\$c=_GR_COL$num;");
              $this->vars['COL']=$c;
              eval("\$c=_GR_BCOL$num;");
              $this->vars['BCOL']=$c;
              $this->vars['NAME']=$tarr[3];
              $this->vars['PRIM']=$tarr[1];
              $this->vars['SEC']=$tarr[2];
              if(!strcmp($this->type,'graph')) tparse($ctext,$this->vars);
              else tparse($ctextc,$this->vars);
            }
            elseif($tflag==2) {
              $this->vars['NUM']=$e[0];
              eval("\$c=_GR_COL$num;");
              $this->vars['COL']=$c;
              eval("\$c=_GR_BCOL$num;");
              $this->vars['BCOL']=$c;
              $this->vars['NAME']=$tarr[3];
              $this->vars['PRIM']=$tarr[1];
              $this->vars['SEC']=$tarr[2];
              if(!strcmp($this->type,'graph')) tparse($cplain,$this->vars);
              else tparse($cplainc,$this->vars);
            }
            elseif($tflag==3) {
              $this->vars['NUM']=$e[0];
              eval("\$c=_GR_COL$num;");
              $this->vars['COL']=$c;
              eval("\$c=_GR_BCOL$num;");
              $this->vars['BCOL']=$c;
              $this->vars['REFERRER']=$tarr[4];
              $tstr=$tarr[3];
              if(strlen($tstr)>_VS_TGRAPHLEN) $tstr=substr($tstr,0,_VS_TGRAPHLEN-3).'...';
              $this->vars['REFSHORT']=$tstr;
              $this->vars['PRIM']=$tarr[1];
              $this->vars['SEC']=$tarr[2];
              if(!strcmp($this->type,'graph')) tparse($curl,$this->vars);
              else tparse($curlc,$this->vars);
            }
            elseif($tflag==4) {
              $this->vars['NUM']=$e[0];
              eval("\$c=_GR_COL$num;");
              $this->vars['COL']=$c;
              eval("\$c=_GR_BCOL$num;");
              $this->vars['BCOL']=$c;
              if(!strcmp($tarr[3],_DIRECT)) {
                $this->vars['NAME']=$tarr[3];
                $this->vars['PRIM']=$tarr[1];
                $this->vars['SEC']=$tarr[2];
                if(!strcmp($this->type,'graph')) tparse($cplain,$this->vars);
                else tparse($cplainc,$this->vars);
              }
              else {
                $this->vars['REFERRER']=$tarr[3];
                $tstr=$this->vars['REFERRER'];
                if(strlen($tstr)>_VS_TGRAPHLEN) $tstr=substr($tstr,0,_VS_TGRAPHLEN-3).'...';
                $this->vars['REFSHORT']=$tstr;
                $this->vars['PRIM']=$tarr[1];
                $this->vars['SEC']=$tarr[2];
                if(!strcmp($this->type,'graph')) tparse($curl,$this->vars);
                else tparse($curlc,$this->vars);
              }
            }
            elseif($tflag==5) {
              $this->vars['NUM']=$e[0];
              eval("\$c=_GR_COL$num;");
              $this->vars['COL']=$c;
              eval("\$c=_GR_BCOL$num;");
              $this->vars['BCOL']=$c;
              $this->vars['IMG']=$tarr[4];
              $this->vars['NAME']=$tarr[3];
              $this->vars['PRIM']=$tarr[1];
              $this->vars['SEC']=$tarr[2];
              if(!strcmp($this->type,'graph')) tparse($cimage,$this->vars);
              else tparse($cimagec,$this->vars);
            }
            elseif($tflag==6) {
              $this->vars['NUM']=$e[0];
              eval("\$c=_GR_COL$num;");
              $this->vars['COL']=$c;
              eval("\$c=_GR_BCOL$num;");
              $this->vars['BCOL']=$c;
              if(!strcmp($tarr[3],_UNDEFINED)) {
                $this->vars['NAME']=$tarr[3];
                $this->vars['PRIM']=$tarr[1];
                $this->vars['SEC']=$tarr[2];
                if(!strcmp($this->type,'graph')) tparse($ctext,$this->vars);
                else tparse($ctextc,$this->vars);
              }
              else {
                $this->vars['IMG']=$tarr[4];
                $this->vars['NAME']=$tarr[3];
                $this->vars['PRIM']=$tarr[1];
                $this->vars['SEC']=$tarr[2];
                if(!strcmp($this->type,'graph')) tparse($icenter,$this->vars);
                else tparse($icenterc,$this->vars);
              }
            }
            $num++;
          }

          //frame
          if(sizeof($vals)<$tt[2]) {
            reset($vals);
            $bp=each($vals);
            end($vals);
            $be=each($vals);
            $this->vars['NAME']=$bp[0].' - '.$be[0].' '._OUTOF.' '.$tt[2];
            if((!strcmp($this->act,'vis_grpg'))||(!strcmp($this->act,'entry'))||(!strcmp($this->act,'exits'))||(!strcmp($this->act,'single'))) {
              $this->vars['PRIM']='-';
              $this->vars['SEC']='-';
            }
            else {
              $this->vars['PRIM']=$vsum;
              $this->vars['SEC']=sprintf("%.2f",$vsum*100/$tt[5]);
            }
            tparse($delimiter,$this->vars);
            $this->vars['NAME']=_MINIMUM;
            $this->vars['PRIM']=$vmin;
            $this->vars['SEC']=sprintf("%.2f",$vmin*100/$tt[5]);
            tparse($foot,$this->vars);
            $this->vars['NAME']=_AVERAGE;
            $this->vars['PRIM']=sprintf("%.0f",$vsum/sizeof($vals));
            $this->vars['SEC']=sprintf("%.2f",$this->vars['PRIM']*100/$tt[5]);
            tparse($foot,$this->vars);
            $this->vars['NAME']=_MAXIMUM;
            $this->vars['PRIM']=$vmax;
            $this->vars['SEC']=sprintf("%.2f",$vmax*100/$tt[5]);
            tparse($foot,$this->vars);
          }
          //total
          $this->vars['NAME']=_TOTAL.' (1 - '.$tt[2].')';
          $this->vars['PRIM']=$tt[5];
          $this->vars['SEC']='100.00';
          tparse($delimiter,$this->vars);
          $this->vars['NAME']=_MINIMUM;
          $this->vars['PRIM']=$tt[3];
          $this->vars['SEC']=sprintf("%.2f",$tt[3]*100/$tt[5]);
          tparse($foot,$this->vars);
          $this->vars['NAME']=_AVERAGE;
          $this->vars['PRIM']=sprintf("%.0f",$tt[5]/$tt[2]);
          $this->vars['SEC']=sprintf("%.2f",$this->vars['PRIM']*100/$tt[5]);
          tparse($foot,$this->vars);
          $this->vars['NAME']=_MAXIMUM;
          $this->vars['PRIM']=$tt[4];
          $this->vars['SEC']=sprintf("%.2f",$tt[4]*100/$tt[5]);
          tparse($foot,$this->vars);

          //end of table
          tparse($tend,$this->vars);
        }
    }
  }

  //bottom
  tparse($end,$this->vars);
}

//Image    -------------------------------------------------------------------//
function img($gid,$stat,$type,$act) {
  global $err,$conf,$gdb;

  //get data
  $vals=array();
  $gdb->values($vals,$gid,$stat);

  //correct
  unset($vals[0]);

  //out image
  if(!strcmp($type,'pie')) $this->piechart($vals,$act);
  elseif(!strcmp($type,'bar')&&!strcmp($stat,'summary')) $this->cbarchart($vals,$act);
  elseif(!strcmp($type,'bar')) $this->barchart($vals,$act);
  elseif(!strcmp($type,'graph')&&!strcmp($stat,'summary')) $this->cgraph($vals,$act);
  elseif(!strcmp($type,'graph')) $this->graph($vals,$act);
}

//get parameters     ---------------------------------------------------------//
function params() {
  global $err,$conf,$gdb,$HTTP_POST_VARS;

  //get graph's parameters
  if(isset($GLOBALS['graph'])) $graph=$GLOBALS['graph'];
  elseif(isset($HTTP_POST_VARS['graph'])) $graph=$HTTP_POST_VARS['graph'];
  else {$err->reason('vvis.php|params|can\'t get graph\'s parameters');return;}

  $tarr = preg_split("/\=/",$graph);
  $this->gid=$tarr[0];
  $this->act=$tarr[1];
  $this->stat=$tarr[2];
  $this->type=$tarr[3];
  $this->pref=$tarr[4];

}

//top of page   --------------------------------------------------------------//
function top() {
  global $err,$conf;

  require './style/'.$conf->style.'/template/top.php';
  tparse($top,$this->vars);
}

//bottom of page   -----------------------------------------------------------//
function bottom() {
  global $err,$conf;

  require './style/'.$conf->style.'/template/bottom.php';
  tparse($bottom,$this->vars);
}

//image's headers (protect from cashing)    ----------------------------------//
function headers() {
  global $err,$conf;

  Header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
  Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  Header("Pragma: no-cache"); // HTTP/1.0
  if(ImageTypes() & IMG_PNG) Header("Content-type: image/png");
  elseif(ImageTypes() & IMG_GIF) Header("Content-type: image/gif");
  else {$err->reason('vvis.php|headers|can\'t found supported graphic format');return;}
}

//create image blank  --------------------------------------------------------//
function createimg() {
  global $err,$conf;

  if(!function_exists('ImageTypes')) {$err->reason('vvis.php|createimg|graphic formats are not supported');return;}

  $this->headers();
  $this->img = imagecreate($this->iwidth,$this->iheight);
  $bc=_BG_COLOR;
  eval("\$bc=0x$bc;");
  $c3=$bc & 0xFF;
  $bc/=0x100;
  $c2=$bc & 0xFF;
  $bc/=0x100;
  $c1=$bc & 0xFF;
  $this->bgcol = ImageColorAllocate($this->img, $c1, $c2, $c3);
  imagefill($this->img,1,1,$this->bgcol);

  //colors
  for($i=1;$i<=31;$i++) {
    eval("\$c=_GR_COL$i;");
    eval("\$c=0x$c;");
    $c3=$c & 0xFF;
    $c/=0x100;
    $c2=$c & 0xFF;
    $c/=0x100;
    $c1=$c & 0xFF;
    $this->col[$i] = ImageColorAllocate($this->img, $c1, $c2, $c3);
  }
  for($i=1;$i<=31;$i++) {
    eval("\$c=_GR_TCOL$i;");
    eval("\$c=0x$c;");
    $c3=$c & 0xFF;
    $c/=0x100;
    $c2=$c & 0xFF;
    $c/=0x100;
    $c1=$c & 0xFF;
    $this->tcol[$i] = ImageColorAllocate($this->img, $c1, $c2, $c3);
  }
  for($i=1;$i<=31;$i++) {
    eval("\$c=_GR_BCOL$i;");
    eval("\$c=0x$c;");
    $c3=$c & 0xFF;
    $c/=0x100;
    $c2=$c & 0xFF;
    $c/=0x100;
    $c1=$c & 0xFF;
    $this->bcol[$i] = ImageColorAllocate($this->img, $c1, $c2, $c3);
  }

  $this->gcol[1] = ImageColorAllocate($this->img, 247, 247, 247);
  $this->gcol[2] = ImageColorAllocate($this->img, 239, 239, 239);
  $this->gcol[3] = ImageColorAllocate($this->img, 223, 223, 223);
  $this->gcol[4] = ImageColorAllocate($this->img, 207, 207, 207);
  $this->gcol[5] = ImageColorAllocate($this->img, 159, 159, 159);
}

//output image   -------------------------------------------------------------//
function outimg() {
  global $err,$conf;

  if(ImageTypes() & IMG_PNG) ImagePNG($this->img);
  elseif(ImageTypes() & IMG_GIF) ImageGIF($this->img);
  else {$err->reason('vvis.php|outimg|can\'t found supported graphic format');return;}

  ImageDestroy($this->img);
}

//draw pie chart   -----------------------------------------------------------//
function piechart(&$data,$act) {
  global $err,$conf;

  //maximum values
  $total=0;
  $max=0;
  $maxper=0;
  $maxnum=0;
  reset($data);
  while($e=each($data)) {
    $tarr = preg_split("/\|/",$e[1]);
    $total+=$tarr[1];
    if($tarr[1]>$max) {
      $max=$tarr[1];
      $maxper=$tarr[2];
    }
    if($e[0]>$maxnum) $maxnum=$e[0];
  }

  //size of image
  $piewidth=($this->iwidth-(2*6*_IMG_TWIDTH)-50)*0.9;
  $pieheight=$piewidth*0.7;
  $this->iheight+=60;

  //create blank of image
  $this->createimg();

  //create array with angles
  $sectorarr=array();
  $cangle=0;
  $lindex=0;
  reset($data);
  while($e=each($data)) {
    $tarr = preg_split("/\|/",$e[1]);
    $val=$tarr[1]*10000/$total;
    $val = $val/100*3.6;
    $cangle+=$val;
    $sectorarr[$e[0]]=$cangle;
    $lindex=$e[0];
  }
  $sectorarr[$lindex]=360;

  //create pie chart
  $larr=array();
  $rarr=array();
  $this->pie($sectorarr,$larr,$rarr,$this->iwidth/2,$this->iheight/2-$pieheight/40,$piewidth,$pieheight,$pieheight/10);
  if($err->flag) {$err->reason('vvis.php|piechart|can\'t create sector\'s pie chart');return;}

  //create notes
  $this->notes($sectorarr,$data,$larr,$rarr,$this->iwidth/2,$this->iheight/2-$pieheight/40,$piewidth,$pieheight,$pieheight/10,$act);
  if($err->flag) {$err->reason('vvis.php|piechart|can\'t create notes for sector\'s pie chart');return;}

  $this->outimg();
}

//notes
function notes(&$anglearr,&$data,$larr,$rarr,$piecx,$piecy,$piewidth,$pieheight,$piethick,$act) {
  global $err,$conf;

  //parameters
  $piecy-=$piethick/2;
  $pieheight-=$piethick;
  $th=($this->iheight-20)/30;

  //left numbers
  $ntop=0;
  $nbot=29;
  $lps=array();
  $lps2=array();
  asort($larr);
  reset($larr);
  while($e=each($larr)) {
    if($e[1]<$piecy) {
      $lps[$e[0]]=$nbot;
      $lps2[$nbot]=$e[1];
      $nbot--;
    }
  }
  arsort($larr);
  reset($larr);
  while($e=each($larr)) {
    if($e[1]>=$piecy) {
      $lps[$e[0]]=$ntop;
      $lps2[$ntop]=$e[1];
      $ntop++;
    }
  }
  ksort($larr);
  reset($larr);
  while($e=each($larr)) {
    if($e[1]==$piecy) {
      if($anglearr[$e[0]]>=180) {
        $lps[$e[0]]=$ntop;
        $lps2[$ntop]=$e[1];
        $ntop++;
      }
    }
  }
  krsort($larr);
  reset($larr);
  while($e=each($larr)) {
    if($e[1]==$piecy) {
      if($anglearr[$e[0]]<180) {
        $lps[$e[0]]=$nbot;
        $lps2[$nbot]=$e[1];
        $nbot--;
      }
    }
  }

  asort($lps);
  $clps=$lps;
  reset($clps);
  while($e=each($clps)) {
    if($lps2[$e[1]]<=$piecy) {
      $ty=($this->iheight-15-$lps2[$e[1]])/$th;
      $ty=floor($ty)+1;
      for($g=$ty;$g<$e[1];$g++) {
        if(!isset($lps2[$g])) {
          $lps[$e[0]]=$g;
          $lps2[$g]=$lps2[$e[1]];
          unset($lps2[$e[1]]);
          break;
        }
      }
    }
  }

  arsort($lps);
  $clps=$lps;
  reset($clps);
  while($e=each($clps)) {
    if($lps2[$e[1]]>=$piecy) {
      $ty=($this->iheight-15-$lps2[$e[1]])/$th;
      $ty=floor($ty);
      for($g=$ty;$g>$e[1];$g--) {
        if(!isset($lps2[$g])) {
          $lps[$e[0]]=$g;
          $lps2[$g]=$lps2[$e[1]];
          unset($lps2[$e[1]]);
          break;
        }
      }
    }
  }

  //right numbers
  $ntop=0;
  $nbot=29;
  $rps=array();
  $rps2=array();
  asort($rarr);
  reset($rarr);
  while($e=each($rarr)) {
    if($e[1]<$piecy) {
      $rps[$e[0]]=$nbot;
      $rps2[$nbot]=$e[1];
      $nbot--;
    }
  }
  arsort($rarr);
  reset($rarr);
  while($e=each($rarr)) {
    if($e[1]>$piecy) {
      $rps[$e[0]]=$ntop;
      $rps2[$ntop]=$e[1];
      $ntop++;
    }
  }
  ksort($rarr);
  reset($rarr);
  while($e=each($rarr)) {
    if($e[1]==$piecy) {
      if($anglearr[$e[0]]>=180) {
        $rps[$e[0]]=$ntop;
        $rps2[$ntop]=$e[1];
        $ntop++;
      }
    }
  }
  krsort($rarr);
  reset($rarr);
  while($e=each($rarr)) {
    if($e[1]==$piecy) {
      if($anglearr[$e[0]]<180) {
        $rps[$e[0]]=$nbot;
        $rps2[$nbot]=$e[1];
        $nbot--;
      }
    }
  }

  asort($rps);
  $crps=$rps;
  reset($crps);
  while($e=each($crps)) {
    if($rps2[$e[1]]<=$piecy) {
      $ty=($this->iheight-15-$rps2[$e[1]])/$th;
      $ty=floor($ty)+1;
      for($g=$ty;$g<$e[1];$g++) {
        if(!isset($rps2[$g])) {
          $rps[$e[0]]=$g;
          $rps2[$g]=$rps2[$e[1]];
          unset($rps2[$e[1]]);
          break;
        }
      }
    }
  }

  arsort($rps);
  $crps=$rps;
  reset($crps);
  while($e=each($crps)) {
    if($rps2[$e[1]]>=$piecy) {
      $ty=($this->iheight-15-$rps2[$e[1]])/$th;
      $ty=floor($ty);
      for($g=$ty;$g>$e[1];$g--) {
        if(!isset($rps2[$g])) {
          $rps[$e[0]]=$g;
          $rps2[$g]=$rps2[$e[1]];
          unset($rps2[$e[1]]);
          break;
        }
      }
    }
  }

  //create sectors
  $pangle=0;
  $num=1;
  reset($anglearr);
  while($e=each($anglearr)) {
    if($e[1]<0) $e[1]=0;
    if($e[1]>360) $e[1]=360;

    //note's point on sector
    $avangl=($e[1]+$pangle)/2;
    $val=deg2rad($avangl);
    $a=tan($val);
    $this->xy_pos($a,$x,$y,$avangl,$piecx,$piecy,$piewidth*9/10,$pieheight*9/10);

    //notes
    $txt=$this->gettext($act,$e[0],$data[$e[0]],3);

    if($x>$piecx) {
      $nx=$piecx+$piewidth/2+12;
      $ny=$this->iheight-15-$rps[$e[0]]*$th;
      imageline($this->img,$nx,$ny,$nx+10,$ny,$this->gcol[4]);

      //color rectangle
      $pts=array();
      $pts[]=$nx+15;
      $pts[]=$ny+4;
      $pts[]=$nx+15;
      $pts[]=$ny-4;
      $pts[]=$nx+23;
      $pts[]=$ny-4;
      $pts[]=$nx+23;
      $pts[]=$ny+4;
      imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
      imagepolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);

      //text
      imagestring($this->img,2,$nx+28,$ny-7,$txt,$this->gcol[5]);

    }
    else {
      $nx=$piecx-$piewidth/2-12;
      $ny=$this->iheight-15-$lps[$e[0]]*$th;
      imageline($this->img,$nx,$ny,$nx-10,$ny,$this->gcol[4]);

      //color rectangle
      $pts=array();
      $pts[]=$nx-23;
      $pts[]=$ny+4;
      $pts[]=$nx-23;
      $pts[]=$ny-4;
      $pts[]=$nx-15;
      $pts[]=$ny-4;
      $pts[]=$nx-15;
      $pts[]=$ny+4;
      imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
      imagepolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);

      $dk=strlen($txt);
      imagestring($this->img,2,$nx-26-(6*$dk),$ny-7,$txt,$this->gcol[5]);
    }
    //line
    imageline($this->img,$x,$y,$nx,$ny,$this->gcol[4]);

    $pangle=$e[1];
    $num++;
  }

}

//pie
function pie(&$anglearr,&$larr,&$rarr,$piecx,$piecy,$piewidth,$pieheight,$piethick) {
  global $err,$conf;

  //parameters
  $piecy-=$piethick/2;
  $pieheight-=$piethick;

  //create sectors
  $pangle=0;
  $cx=$piecx+$piewidth/2;
  $cy=$piecy;
  $num=1;
  reset($anglearr);
  while($e=each($anglearr)) {
    if($e[1]<0) $e[1]=0;
    if($e[1]>360) $e[1]=360;

    //line
    $pts=array();
    $pts[]=$piecx;
    $pts[]=$piecy;
    $pts[]=$cx;
    $pts[]=$cy;

    //real
    if($e[1]!=360) {
      $val=deg2rad($e[1]);
      $a=tan($val);
      $ang=$e[1];
    }
    else {
      $a=0;
      $ang=360;
    }
    $this->xy_pos($a,$x,$y,$ang,$piecx,$piecy,$piewidth,$pieheight);

    //safe
    if($e[1]!=360) {
      $dfr=360-$e[1];
      if($dfr>10) $dfr=1;
      $val=deg2rad($e[1]+$dfr);
      $a2=tan($val);
      $ang2=$e[1]+$dfr;
      $this->xy_pos($a2,$x2,$y2,$ang2,$piecx,$piecy,$piewidth,$pieheight);
    }
    else {
      $x2=$x;
      $y2=$y;
      $ang2=$ang;
    }

    //note's point on sector
    $avangl=($e[1]+$pangle)/2;
    $val=deg2rad($avangl);
    $a=tan($val);
    $this->xy_pos($a,$nx,$ny,$avangl,$piecx,$piecy,$piewidth*9/10,$pieheight*9/10);
    if($nx>$piecx) $rarr[$e[0]]=$ny;
    else $larr[$e[0]]=$ny;

    //arc
    $this->arc($piecy,$cx,$pangle,$x2,$ang2,$pts,$piecx,$piewidth,$pieheight);
    $pts[]=$x2;
    $pts[]=$y2;

    //bottom of segment
    if($e[1]>180) {
      $bpts=array();
      if($pangle<180) {
        //arc
        $bpts[]=$piecx-$piewidth/2;
        $bpts[]=$piecy;
        $bpts[]=$piecx-$piewidth/2;
        $bpts[]=$piecy+$piethick;
        $this->arc($piecy+$piethick,$piecx-$piewidth/2,180,$x,$e[1],$bpts,$piecx,$piewidth,$pieheight);
        //thickness line
        $tmp=$bpts[sizeof($bpts)-1];
        $bpts[]=$x;
        $bpts[]=$tmp-$piethick;
        //paint
        imagefilledpolygon($this->img,$bpts,sizeof($bpts)/2,$this->tcol[$num]);
      }
      else {
        //arc
        $bpts[]=$cx;
        $bpts[]=$cy;
        $bpts[]=$cx;
        $bpts[]=$cy+$piethick;
        $this->arc($piecy+$piethick,$cx,$pangle,$x,$e[1],$bpts,$piecx,$piewidth,$pieheight);
        //thickness line
        $tmp=$bpts[sizeof($bpts)-1];
        $bpts[]=$x;
        $bpts[]=$tmp-$piethick;
        //paint
        imagefilledpolygon($this->img,$bpts,sizeof($bpts)/2,$this->tcol[$num]);
      }
    }

    //paint
    imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);

    //next
    $pangle=$e[1];
    $cx=$x;
    $cy=$y;
    $num++;
  }
}

//point on ellipse
function xy_pos($a,&$x,&$y,$angle,$piecx,$piecy,$piewidth,$pieheight) {
  global $err,$conf;

  // angle == 0
  if($angle==0) {
     $x=$piecx+$piewidth/2;
     $y=$piecy;
  }
  // 0 < angle < 90
  elseif($angle>0 && $angle<90) {
     $x=$piewidth*$piewidth*$pieheight*$pieheight/16;
     $x/=$piewidth*$piewidth*$a*$a/4+$pieheight*$pieheight/4;
     $x=sqrt(abs($x));
     $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
     $y=sqrt(abs($y));
     //correction
     $x=round($x);
     $y=round($y);
     $x=$piecx+$x;
     $y=$piecy-$y;
  }
  // angle == 90
  elseif($angle==90) {
     $x=$piecx;
     $y=$piecy-$pieheight/2;
  }
  // 90 < angle < 180
  elseif($angle>90 && $angle<180) {
     $x=$piewidth*$piewidth*$pieheight*$pieheight/16;
     $x/=$piewidth*$piewidth*$a*$a/4+$pieheight*$pieheight/4;
     $x=sqrt(abs($x));
     $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
     $y=sqrt(abs($y));
     //correction
     $x=round($x);
     $y=round($y);
     $x=$piecx-$x;
     $y=$piecy-$y;
  }
  // angle == 180
  elseif($angle==180) {
     $x=$piecx-$piewidth/2;
     $y=$piecy;
  }
  // 180 < angle < 270
  elseif($angle>180 && $angle<270) {
     $x=$piewidth*$piewidth*$pieheight*$pieheight/16;
     $x/=$piewidth*$piewidth*$a*$a/4+$pieheight*$pieheight/4;
     $x=sqrt(abs($x));
     $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
     $y=sqrt(abs($y));
     //correction
     $x=round($x);
     $y=round($y);
     $x=$piecx-$x;
     $y=$piecy+$y;
  }
  // angle == 270
  elseif($angle==270) {
     $x=$piecx;
     $y=$piecy+$pieheight/2;
  }
  // 270 < angle < 360
  elseif($angle>270 && $angle<360) {
     $x=$piewidth*$piewidth*$pieheight*$pieheight/16;
     $x/=$piewidth*$piewidth*$a*$a/4+$pieheight*$pieheight/4;
     $x=sqrt(abs($x));
     $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
     $y=sqrt(abs($y));
     //correction
     $x=round($x);
     $y=round($y);
     $x=$piecx+$x;
     $y=$piecy+$y;
  }
  // angle == 360
  elseif($angle==360) {
     $x=$piecx+$piewidth/2;
     $y=$piecy;
  }
}

//arc for sector
function arc($piecy,$bx,$bangle,$ex,$eangle,&$points,$piecx,$piewidth,$pieheight) {
  global $err,$conf;

  if($bangle<180) {
    if($eangle<=180) $tx=$ex;
    else $tx=$piecx-$piewidth/2;

    for($i=$bx;$i>=$tx;$i--) {
      $x=$i-$piecx;
      $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
      $y=sqrt(abs($y));
      $points[]=round($i);
      $points[]=$piecy-$y;
    }
    if($eangle>180) {
      for($i=$tx;$i<=$ex;$i++) {
        $x=$i-$piecx;
        $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
        $y=sqrt(abs($y));
        $points[]=round($i);
        $points[]=round($piecy+$y);
      }
    }
  }
  else {
    for($i=$bx;$i<=$ex;$i++) {
      $x=$i-$piecx;
      $y=($pieheight*$pieheight/4)-($pieheight*$pieheight*$x*$x)/($piewidth*$piewidth);
      $y=sqrt(abs($y));
      $points[]=round($i);
      $points[]=round($piecy+$y);
    }
  }
}

//draw bar chart   -----------------------------------------------------------//
function barchart(&$data,$act) {
  global $err,$conf;

  //maximum values
  $max=0;
  $maxper=0;
  $maxnum=0;
  reset($data);
  while($e=each($data)) {
    $tarr = preg_split("/\|/",$e[1]);
    if($tarr[1]>$max) {
      $max=$tarr[1];
      $maxper=$tarr[2];
    }
    $txt=$this->gettext($act,$e[0],$data[$e[0]],3);
    if(strlen($txt)>$maxnum) $maxnum=strlen($txt);
  }

  //size of image
  $barwidth=($this->iwidth-8*6-8*6-20)*0.95;
  $barheight=$this->iheight*0.8;

  //grid's angle
  $angle=20;
  $barthick=$barheight*0.2;
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));

  $w=($barwidth-round($x))/sizeof($data);
  if(($maxnum*6+12)>=$w) $this->iheight+=6*$maxnum;

  //create blank of image
  $this->createimg();

  //create grid
  $this->bargrid($max,$maxper,$maxnum,$data,($this->iwidth-$barwidth)/2+10,$barheight+$barheight*0.1,$barwidth,$barheight,$barthick,$angle,$act);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create grid for sector\'s bar chart');return;}

  //create bars
  $this->bar($max,$data,($this->iwidth-$barwidth)/2+10,$barheight+$barheight*0.1,$barwidth,$barheight,$barthick,$angle);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create sector\'s bar chart');return;}

  $this->outimg();
}

//grid
function bargrid(&$max,$maxper,$maxnum,$data,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle,$act) {
  global $err,$conf;

  //grid's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  //grid values
  $num=sizeof($data);
  if($max!=0) $tper=$maxper/$max;
  else $tper=0;
  $tmax=$max;
  $koeff=0;
  while($tmax>9) {
    $tmax=floor($tmax/10);
    $koeff++;
  }
  $t=$tmax;
  for($i=0;$i<$koeff;$i++) $t*=10;
  if($max>$t) {
    $tmax++;
    $t=$tmax;
    for($i=0;$i<$koeff;$i++) $t*=10;
  }
  if($max==$t && $tmax==1 && $koeff>0) {$tmax=10;$koeff--;}
  $max=$t;
  $maxper=$max*$tper;

  //points
  $px1=$barcx;
  $px2=round($x)+$barcx;
  $px3=$barcx+$barwidth;
  $px4=$barcx+$barwidth+round($x);
  $py1=$barcy-$barheight-round($y);
  $py2=$barcy-$barheight;
  $py3=$barcy-round($y);
  $py4=$barcy;

  //left
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px1;
  $pts[]=$py2;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px2;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[3]);

  //rear
  $pts=array();
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[2]);

  //bottom
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px4;
  $pts[]=$py3;
  $pts[]=$px3;
  $pts[]=$py4;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[1]);

  //horizontal grid lines
  imageline($this->img,$px1,$py4,$px3,$py4,$this->gcol[4]);
  if($tmax!=0) $h=$barheight/$tmax;
  else $h=0;
  for($i=0;$i<=$tmax;$i++) {
    imageline($this->img,$px1-3,$py4-($i*$h),$px1,$py4-($i*$h),$this->gcol[4]);
    imageline($this->img,$px1,$py4-($i*$h),$px2,$py3-($i*$h),$this->gcol[4]);
    imageline($this->img,$px2,$py3-($i*$h),$px4+3,$py3-($i*$h),$this->gcol[4]);

    //left values
    if($i>9) $ts=$koeff+1;
    elseif($i==0) $ts=0;
    else $ts=$koeff;
    $td=$i;
    for($d=0;$d<$koeff;$d++) $td*=10;
    imagestring($this->img,2,$px1-12-(6*$ts),$py4-($i*$h)-6,$td,$this->gcol[5]);

    //right percents
    if($tmax!=0) $mp=$maxper/$tmax;
    else $mp=0;
    $ds=sprintf("%.2f",$mp*$i);
    imagestring($this->img,2,$px4+8,$py3-($i*$h)-6,$ds." %",$this->gcol[5]);
  }

  //vertical grid lines
  imageline($this->img,$px1,$py4,$px1,$py2,$this->gcol[4]);
  $w=$barwidth/$num;
  reset($data);
  for($i=0;$i<=$num;$i++) {
    imageline($this->img,$px2+($i*$w),$py3,$px2+($i*$w),$py1,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px2+($i*$w),$py3,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px1+($i*$w),$py4+3,$this->gcol[4]);

    //bottom numbers
    if($i!=0) {
      $e=each($data);
      $tarr = preg_split("/\|/",$e[1]);

      $txt=$this->gettext($act,$e[0],$data[$e[0]],3);
      $dk=strlen($txt);

      if(($maxnum*6+12)<$w) {
        $stxh=round(($w-$dk*6)/2);
        imagestring($this->img,2,$px1+$stxh+($i-1)*$w,$py4+6,$txt,$this->gcol[5]);
      }
      else {
        $stxv=round(($w-12)/2);
        imagestringup($this->img,2,$px1+$stxv+($i-1)*$w,$py4+12+6*($dk),$txt,$this->gcol[5]);
      }
    }
  }
}

//bars
function bar($max,&$percarr,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle) {
  global $err,$conf;

  //bar's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  //create bars
  $xoffs=$barwidth/sizeof($percarr)-($barwidth/sizeof($percarr))/4;
  $cx=$barcx+($barwidth/sizeof($percarr))/8;
  reset($percarr);
  $num=1;
  while($e=each($percarr)) {
    $tarr = preg_split("/\|/",$e[1]);

    //points
    $px1=$cx;
    $px2=round($x)+$cx;
    $px3=$cx+$xoffs;
    $px4=round($x)+$cx+$xoffs;
    if($max!=0) {
      $py1=$barcy-$barheight*$tarr[1]/$max-round($y);
      $py2=$barcy-$barheight*$tarr[1]/$max;
    }
    else {
      $py1=$barcy-round($y);
      $py2=$barcy;
    }
    $py3=$barcy-round($y);
    $py4=$barcy;

    //front
    $pts=array();
    $pts[]=$px1;
    $pts[]=$py4;
    $pts[]=$px1;
    $pts[]=$py2;
    $pts[]=$px3;
    $pts[]=$py2;
    $pts[]=$px3;
    $pts[]=$py4;
    //paint
    imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->tcol[$num]);

    //top
    $pts=array();
    $pts[]=$px1;
    $pts[]=$py2;
    $pts[]=$px2;
    $pts[]=$py1;
    $pts[]=$px4;
    $pts[]=$py1;
    $pts[]=$px3;
    $pts[]=$py2;
    //paint
    imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);

    //right
    $pts=array();
    $pts[]=$px3;
    $pts[]=$py4;
    $pts[]=$px3;
    $pts[]=$py2;
    $pts[]=$px4;
    $pts[]=$py1;
    $pts[]=$px4;
    $pts[]=$py3;
    //paint
    imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);

   //next
   $cx+=$barwidth/sizeof($percarr);
   $num++;
  }
}

//draw graph   ---------------------------------------------------------------//
function graph(&$data,$act) {
  global $err,$conf;

  //maximum values
  $max=0;
  $maxper=0;
  $maxnum=0;
  reset($data);
  while($e=each($data)) {
    $tarr = preg_split("/\|/",$e[1]);
    if($tarr[1]>$max) {
      $max=$tarr[1];
      $maxper=$tarr[2];
    }
    $txt=$this->gettext($act,$e[0],$data[$e[0]],3);
    if(strlen($txt)>$maxnum) $maxnum=strlen($txt);
  }

  //size of image
  $graphwidth=($this->iwidth-8*6-8*6-20)*0.95;
  $graphheight=$this->iheight*0.8;

  //grid's angle
  $angle=20;
  $graphthick=$graphheight*0.2;
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$graphthick*$graphthick/(1+$a*$a);
  $x=sqrt(abs($x));

  $w=($graphwidth-round($x))/sizeof($data);
  if(($maxnum*6+12)>=$w) $this->iheight+=6*$maxnum;

  //create blank of image
  $this->createimg();

  //create grid
  $this->graphgrid($max,$maxper,$maxnum,$data,($this->iwidth-$graphwidth)/2+10,$graphheight+$graphheight*0.1,$graphwidth,$graphheight,$graphthick,$angle,$act);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create grid for sector\'s bar chart');return;}

  //create graph
  $this->drawgraph($max,$data,($this->iwidth-$graphwidth)/2+10,$graphheight+$graphheight*0.1,$graphwidth,$graphheight,$graphthick,$angle);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create sector\'s bar chart');return;}

  $this->outimg();
}

//grid
function graphgrid(&$max,$maxper,$maxnum,$data,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle,$act) {
  global $err,$conf;

  //grid's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  //grid values
  $num=sizeof($data);
  if($max!=0) $tper=$maxper/$max;
  else $tper=0;
  $tmax=$max;
  $koeff=0;
  while($tmax>9) {
    $tmax=floor($tmax/10);
    $koeff++;
  }
  $t=$tmax;
  for($i=0;$i<$koeff;$i++) $t*=10;
  if($max>$t) {
    $tmax++;
    $t=$tmax;
    for($i=0;$i<$koeff;$i++) $t*=10;
  }
  if($max==$t && $tmax==1 && $koeff>0) {$tmax=10;$koeff--;}
  $max=$t;
  $maxper=$max*$tper;

  //points
  $px1=$barcx;
  $px2=round($x)+$barcx;
  $px3=$barcx+$barwidth;
  $px4=$barcx+$barwidth+round($x);
  $py1=$barcy-$barheight-round($y);
  $py2=$barcy-$barheight;
  $py3=$barcy-round($y);
  $py4=$barcy;

  //left
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px1;
  $pts[]=$py2;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px2;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[3]);

  //rear
  $pts=array();
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[2]);

  //bottom
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px4;
  $pts[]=$py3;
  $pts[]=$px3;
  $pts[]=$py4;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[1]);

  //horizontal grid lines
  imageline($this->img,$px1,$py4,$px3,$py4,$this->gcol[4]);
  if($tmax!=0) $h=$barheight/$tmax;
  else $h=0;
  for($i=0;$i<=$tmax;$i++) {
    imageline($this->img,$px1-3,$py4-($i*$h),$px1,$py4-($i*$h),$this->gcol[4]);
    imageline($this->img,$px1,$py4-($i*$h),$px2,$py3-($i*$h),$this->gcol[4]);
    imageline($this->img,$px2,$py3-($i*$h),$px4+3,$py3-($i*$h),$this->gcol[4]);

    //left values
    if($i>9) $ts=$koeff+1;
    elseif($i==0) $ts=0;
    else $ts=$koeff;
    $td=$i;
    for($d=0;$d<$koeff;$d++) $td*=10;
    imagestring($this->img,2,$px1-12-(6*$ts),$py4-($i*$h)-6,$td,$this->gcol[5]);

    //right percents
    if($tmax!=0) $mp=$maxper/$tmax;
    else $mp=0;
    $ds=sprintf("%.2f",$mp*$i);
    imagestring($this->img,2,$px4+8,$py3-($i*$h)-6,$ds." %",$this->gcol[5]);
  }

  //vertical grid lines
  imageline($this->img,$px1,$py4,$px1,$py2,$this->gcol[4]);
  $w=$barwidth/$num;
  reset($data);
  for($i=0;$i<=$num;$i++) {
    imageline($this->img,$px2+($i*$w),$py3,$px2+($i*$w),$py1,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px2+($i*$w),$py3,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px1+($i*$w),$py4+3,$this->gcol[4]);

    //bottom numbers
    if($i!=0) {
      $e=each($data);
      $tarr = preg_split("/\|/",$e[1]);

      $txt=$this->gettext($act,$e[0],$data[$e[0]],3);
      $dk=strlen($txt);

      if(($maxnum*6+12)<$w) {
        $stxh=round(($w-$dk*6)/2);
        imagestring($this->img,2,$px1+$stxh+($i-1)*$w,$py4+6,$txt,$this->gcol[5]);
      }
      else {
        $stxv=round(($w-12)/2);
        imagestringup($this->img,2,$px1+$stxv+($i-1)*$w,$py4+12+6*($dk),$txt,$this->gcol[5]);
      }
    }
  }
}

//graphs
function drawgraph($max,&$percarr,$graphcx,$graphcy,$graphwidth,$graphheight,$graphthick,$angle) {
  global $err,$conf;

  //graph's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$graphthick*$graphthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $graphwidth-=round($x);
  $graphheight-=round($y);

  //create graphs
  $xoffs=$graphwidth/sizeof($percarr);
  $cx=$graphcx;
  $cy=$graphcy;
  reset($percarr);
  $num=1;
  while($e=each($percarr)) {
    $tarr = preg_split("/\|/",$e[1]);

    //points
    $px1=$cx;
    $px2=round($x)+$cx;
    $px3=$cx+$xoffs;
    $px4=round($x)+$cx+$xoffs;

    if($max!=0) {
      $py1=$graphcy-$graphheight*$tarr[1]/$max-round($y);
      $py2=$graphcy-$graphheight*$tarr[1]/$max;
    }
    else {
      $py1=$graphcy-round($y);
      $py2=$graphcy;
    }
    $py3=$cy-round($y);
    $py4=$cy;

    //front
    $pts=array();

    $pts[]=$px1;
    $pts[]=$py4;
    $pts[]=$px2;
    $pts[]=$py3;
    $pts[]=$px4;
    $pts[]=$py1;
    $pts[]=$px3;
    $pts[]=$py2;

    //paint
    $ta=($py4-$py2)/($px2-$px1);
    if($ta>$a) imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);
    elseif($ta<=0) imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
    else imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
    imageline($this->img,$px1,$py4,$px2,$py3,$this->gcol[4]);

    //next
    $cx+=$graphwidth/sizeof($percarr);
    $cy=$py2;
  }
}

//draw complex bar chart   /--------------------------------------------------//
function cbarchart(&$data,$act) {
  global $err,$conf;

  //maximum values
  $max=0;
  $maxper=0;
  $maxnum=0;
  reset($data);
  while($e=each($data)) {
    $tarr = preg_split("/\|/",$e[1]);
    if($tarr[10]>$max) {
      $max=$tarr[10];
      $maxper=$tarr[2];
    }
    $txt=$this->gettext($act,$e[0],$data[$e[0]],12);
    if(strlen($txt)>$maxnum) $maxnum=strlen($txt);
  }

  //size of image
  $barwidth=($this->iwidth-8*6-8*6-20)*0.95;
  $barheight=$this->iheight*0.8;

  //grid's angle
  $angle=50;
  $barthick=$barheight*0.4;
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));

  $w=($barwidth-round($x))/sizeof($data);
  if(($maxnum*6+12)>=$w) $this->iheight+=6*$maxnum;

  //create blank of image
  $this->createimg();

  //create grid
  $this->cbargrid($max,$maxper,$maxnum,$data,($this->iwidth-$barwidth)/2+10,$barheight+$barheight*0.1,$barwidth,$barheight,$barthick,$angle,$act);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create grid for sector\'s bar chart');return;}

  //create complex bars
  $this->cbar($max,$data,($this->iwidth-$barwidth)/2+10,$barheight+$barheight*0.1,$barwidth,$barheight,$barthick,$angle);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create sector\'s bar chart');return;}

  $this->outimg();
}

//complex bars
function cbar($max,&$percarr,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle) {
  global $err,$conf;

  //bar's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  for($cpr=4;$cpr>0;$cpr--) {

    //color
    if($cpr==4) $num=1;
    elseif($cpr==3) $num=3;
    elseif($cpr==2) $num=12;
    else $num=4;

    //top bar's angle
    $cor=($cpr*16-4)/64;
    $val=deg2rad($angle);
    $a=tan($val);
    $x=($barthick*$cor)*($barthick*$cor)/(1+$a*$a);
    $x=sqrt(abs($x));
    $y=$a*$x;

    //bottom bar's angle
    $cor=(($cpr-1)*16+4)/64;
    $val=deg2rad($angle);
    $a=tan($val);
    $tx=($barthick*$cor)*($barthick*$cor)/(1+$a*$a);
    $tx=sqrt(abs($tx));
    $ty=$a*$tx;

    //create bars
    $xoffs=$barwidth/sizeof($percarr)-($barwidth/sizeof($percarr))/3;
    $cx=$barcx+($barwidth/sizeof($percarr))/8;
    reset($percarr);
    while($e=each($percarr)) {
      $tarr = preg_split("/\|/",$e[1]);

      //points
      $px1=$cx+round($tx);
      $px2=$cx+round($x);
      $px3=$cx+round($tx)+$xoffs;
      $px4=$cx+round($x)+$xoffs;
      if($max!=0) {
        $py1=$barcy-$barheight*$tarr[$cpr*3-2]/$max-round($y);
        $py2=$barcy-round($ty)-$barheight*$tarr[$cpr*3-2]/$max;
      }
      else {
        $py1=$barcy-round($y);
        $py2=$barcy-round($ty);
      }
      $py3=$barcy-round($y);
      $py4=$barcy-round($ty);

      //front
      $pts=array();
      $pts[]=$px1;
      $pts[]=$py4;
      $pts[]=$px1;
      $pts[]=$py2;
      $pts[]=$px3;
      $pts[]=$py2;
      $pts[]=$px3;
      $pts[]=$py4;
      //paint
      imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->tcol[$num]);

      //top
      $pts=array();
      $pts[]=$px1;
      $pts[]=$py2;
      $pts[]=$px2;
      $pts[]=$py1;
      $pts[]=$px4;
      $pts[]=$py1;
      $pts[]=$px3;
      $pts[]=$py2;
      //paint
      imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);

      //right
      $pts=array();
      $pts[]=$px3;
      $pts[]=$py4;
      $pts[]=$px3;
      $pts[]=$py2;
      $pts[]=$px4;
      $pts[]=$py1;
      $pts[]=$px4;
      $pts[]=$py3;
      //paint
      imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);

      //next
      $cx+=$barwidth/sizeof($percarr);
    }
  }
}

//complex grid
function cbargrid(&$max,$maxper,$maxnum,$data,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle,$act) {
  global $err,$conf;

  //grid's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  //grid values
  $num=sizeof($data);
  if($max!=0) $tper=$maxper/$max;
  else $tper=0;
  $tmax=$max;
  $koeff=0;
  while($tmax>9) {
    $tmax=floor($tmax/10);
    $koeff++;
  }
  $t=$tmax;
  for($i=0;$i<$koeff;$i++) $t*=10;
  if($max>$t) {
    $tmax++;
    $t=$tmax;
    for($i=0;$i<$koeff;$i++) $t*=10;
  }
  if($max==$t && $tmax==1 && $koeff>0) {$tmax=10;$koeff--;}
  $max=$t;
  $maxper=$max*$tper;

  //points
  $px1=$barcx;
  $px2=round($x)+$barcx;
  $px3=$barcx+$barwidth;
  $px4=$barcx+$barwidth+round($x);
  $py1=$barcy-$barheight-round($y);
  $py2=$barcy-$barheight;
  $py3=$barcy-round($y);
  $py4=$barcy;

  //left
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px1;
  $pts[]=$py2;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px2;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[3]);

  //rear
  $pts=array();
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[2]);

  //bottom
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px4;
  $pts[]=$py3;
  $pts[]=$px3;
  $pts[]=$py4;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[1]);

  //horizontal grid lines
  imageline($this->img,$px1,$py4,$px3,$py4,$this->gcol[4]);
  if($tmax!=0) $h=$barheight/$tmax;
  else $h=0;
  for($i=0;$i<=$tmax;$i++) {
    imageline($this->img,$px1-3,$py4-($i*$h),$px1,$py4-($i*$h),$this->gcol[4]);
    imageline($this->img,$px1,$py4-($i*$h),$px2,$py3-($i*$h),$this->gcol[4]);
    imageline($this->img,$px2,$py3-($i*$h),$px4+3,$py3-($i*$h),$this->gcol[4]);

    //left values
    if($i>9) $ts=$koeff+1;
    elseif($i==0) $ts=0;
    else $ts=$koeff;
    $td=$i;
    for($d=0;$d<$koeff;$d++) $td*=10;
    imagestring($this->img,2,$px1-12-(6*$ts),$py4-($i*$h)-6,$td,$this->gcol[5]);
  }

  //vertical grid lines
  imageline($this->img,$px1,$py4,$px1,$py2,$this->gcol[4]);
  $w=$barwidth/$num;
  reset($data);
  for($i=0;$i<=$num;$i++) {
    imageline($this->img,$px2+($i*$w),$py3,$px2+($i*$w),$py1,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px2+($i*$w),$py3,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px1+($i*$w),$py4+3,$this->gcol[4]);

    //bottom numbers
    if($i!=0) {
      $e=each($data);
      $tarr = preg_split("/\|/",$e[1]);

      $txt=$this->gettext($act,$e[0],$data[$e[0]],12);
      $dk=strlen($txt);

      if(($maxnum*6+12)<$w) {
        $stxh=round(($w-$dk*6)/2);
        imagestring($this->img,2,$px1+$stxh+($i-1)*$w,$py4+6,$txt,$this->gcol[5]);
      }
      else {
        $stxv=round(($w-12)/2);
        imagestringup($this->img,2,$px1+$stxv+($i-1)*$w,$py4+12+6*($dk),$txt,$this->gcol[5]);
      }
    }
  }

  //points
  $px1=$barcx;
  $px2=round($x)+$barcx;
  $px3=$barcx+$barwidth;
  $px4=$barcx+$barwidth+round($x);
  $py1=$barcy-$barheight-round($y);
  $py2=$barcy-$barheight;
  $py3=$barcy-round($y);
  $py4=$barcy;

  //grid for parameters
  for($i=0;$i<4;$i++) {
    $val=deg2rad($angle);
    $a=tan($val);
    $x=($barthick*$i/4)*($barthick*$i/4)/(1+$a*$a);
    $x=sqrt(abs($x));
    $y=$a*$x;
    $x=round($x);
    $y=round($y);
    imageline($this->img,$px1+$x,$py4-$y,$px1+$x,$py2-$y,$this->gcol[4]);
    imageline($this->img,$px1+$x,$py4-$y,$px3+$x+5,$py4-$y,$this->gcol[4]);

    //color
    if($i==3) $num=1;
    elseif($i==2) $num=3;
    elseif($i==1) $num=12;
    else $num=4;

    //color rectangle
    $pts=array();
    $pts[]=$px3+$x+18;
    $pts[]=$py4-$y-12;
    $pts[]=$px3+$x+26;
    $pts[]=$py4-$y-12;
    $pts[]=$px3+$x+26;
    $pts[]=$py4-$y-4;
    $pts[]=$px3+$x+18;
    $pts[]=$py4-$y-4;
    imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
    imagepolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);

    //parameter names
    if($i==0) $ds=_VISITORS;
    elseif($i==1) $ds=_HOSTS;
    elseif($i==2) $ds=_RELOADS;
    else $ds=_HITS;

    imagestring($this->img,2,$px3+$x+30,$py4-$y-14,$ds,$this->gcol[5]);
  }
}

//draw copmplex graph   /-----------------------------------------------------//
function cgraph(&$data,$act) {
  global $err,$conf;

  //maximum values
  $max=0;
  $maxper=0;
  $maxnum=0;
  reset($data);
  while($e=each($data)) {
    $tarr = preg_split("/\|/",$e[1]);
    if($tarr[10]>$max) {
      $max=$tarr[10];
      $maxper=$tarr[2];
    }
    $txt=$this->gettext($act,$e[0],$data[$e[0]],12);
    if(strlen($txt)>$maxnum) $maxnum=strlen($txt);
  }

  //size of image
  $graphwidth=($this->iwidth-8*6-8*6-20)*0.95;
  $graphheight=$this->iheight*0.8;

  //grid's angle
  $angle=50;
  $graphthick=$graphheight*0.4;
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$graphthick*$graphthick/(1+$a*$a);
  $x=sqrt(abs($x));

  $w=($graphwidth-round($x))/sizeof($data);
  if(($maxnum*6+12)>=$w) $this->iheight+=6*$maxnum;

  //create blank of image
  $this->createimg();

  //create grid
  $this->cgraphgrid($max,$maxper,$maxnum,$data,($this->iwidth-$graphwidth)/2+10,$graphheight+$graphheight*0.1,$graphwidth,$graphheight,$graphthick,$angle,$act);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create grid for sector\'s bar chart');return;}

  //create graph
  $this->cdrawgraph($max,$data,($this->iwidth-$graphwidth)/2+10,$graphheight+$graphheight*0.1,$graphwidth,$graphheight,$graphthick,$angle);
  if($err->flag) {$err->reason('vvis.php|barchart|can\'t create sector\'s bar chart');return;}

  $this->outimg();
}

//complex bars
function cdrawgraph($max,&$percarr,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle) {
  global $err,$conf;

  //bar's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  for($cpr=4;$cpr>0;$cpr--) {

    //color
    if($cpr==4) $num=1;
    elseif($cpr==3) $num=3;
    elseif($cpr==2) $num=12;
    else $num=4;

    //top bar's angle
    $cor=($cpr*16-2)/64;
    $val=deg2rad($angle);
    $a=tan($val);
    $x=($barthick*$cor)*($barthick*$cor)/(1+$a*$a);
    $x=sqrt(abs($x));
    $y=$a*$x;

    //bottom bar's angle
    $cor=(($cpr-1)*16+2)/64;
    $val=deg2rad($angle);
    $a=tan($val);
    $tx=($barthick*$cor)*($barthick*$cor)/(1+$a*$a);
    $tx=sqrt(abs($tx));
    $ty=$a*$tx;

    //create bars
    $xoffs=$barwidth/sizeof($percarr);
    $cx=$barcx;
    $cy=$barcy;
    reset($percarr);
    while($e=each($percarr)) {
      $tarr = preg_split("/\|/",$e[1]);

      //points
      $px1=$cx+round($tx);
      $px2=$cx+round($x);
      $px3=$cx+round($tx)+$xoffs;
      $px4=$cx+round($x)+$xoffs;
      if($max!=0) {
        $py1=$barcy-$barheight*$tarr[$cpr*3-2]/$max-round($y);
        $py2=$barcy-round($ty)-$barheight*$tarr[$cpr*3-2]/$max;
      }
      else {
        $py1=$barcy-round($y);
        $py2=$barcy-round($ty);
      }
      $py3=$cy-round($y);
      $py4=$cy-round($ty);


    //front
    $pts=array();

    $pts[]=$px1;
    $pts[]=$py4;
    $pts[]=$px2;
    $pts[]=$py3;
    $pts[]=$px4;
    $pts[]=$py1;
    $pts[]=$px3;
    $pts[]=$py2;

    //paint
    $ta=($py4-$py2)/($px2-$px1);
    if($ta>$a) imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);
    elseif($ta<=0) imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
    else imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
    imageline($this->img,$px1,$py4,$px2,$py3,$this->gcol[4]);

    //next
    $cx+=$barwidth/sizeof($percarr);
    $cy=$py2+round($ty);
    }
  }
}

//complex grid for graph
function cgraphgrid(&$max,$maxper,$maxnum,$data,$barcx,$barcy,$barwidth,$barheight,$barthick,$angle,$act) {
  global $err,$conf;

  //grid's angle
  $val=deg2rad($angle);
  $a=tan($val);
  $x=$barthick*$barthick/(1+$a*$a);
  $x=sqrt(abs($x));
  $y=$a*$x;
  $barwidth-=round($x);
  $barheight-=round($y);

  //grid values
  $num=sizeof($data);
  if($max!=0) $tper=$maxper/$max;
  else $tper=0;
  $tmax=$max;
  $koeff=0;
  while($tmax>9) {
    $tmax=floor($tmax/10);
    $koeff++;
  }
  $t=$tmax;
  for($i=0;$i<$koeff;$i++) $t*=10;
  if($max>$t) {
    $tmax++;
    $t=$tmax;
    for($i=0;$i<$koeff;$i++) $t*=10;
  }
  if($max==$t && $tmax==1 && $koeff>0) {$tmax=10;$koeff--;}
  $max=$t;
  $maxper=$max*$tper;

  //points
  $px1=$barcx;
  $px2=round($x)+$barcx;
  $px3=$barcx+$barwidth;
  $px4=$barcx+$barwidth+round($x);
  $py1=$barcy-$barheight-round($y);
  $py2=$barcy-$barheight;
  $py3=$barcy-round($y);
  $py4=$barcy;

  //left
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px1;
  $pts[]=$py2;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px2;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[3]);

  //rear
  $pts=array();
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px2;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py1;
  $pts[]=$px4;
  $pts[]=$py3;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[2]);

  //bottom
  $pts=array();
  $pts[]=$px1;
  $pts[]=$py4;
  $pts[]=$px2;
  $pts[]=$py3;
  $pts[]=$px4;
  $pts[]=$py3;
  $pts[]=$px3;
  $pts[]=$py4;
  //paint
  imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->gcol[1]);

  //horizontal grid lines
  imageline($this->img,$px1,$py4,$px3,$py4,$this->gcol[4]);
  if($tmax!=0) $h=$barheight/$tmax;
  else $h=0;
  for($i=0;$i<=$tmax;$i++) {
    imageline($this->img,$px1-3,$py4-($i*$h),$px1,$py4-($i*$h),$this->gcol[4]);
    imageline($this->img,$px1,$py4-($i*$h),$px2,$py3-($i*$h),$this->gcol[4]);
    imageline($this->img,$px2,$py3-($i*$h),$px4+3,$py3-($i*$h),$this->gcol[4]);

    //left values
    if($i>9) $ts=$koeff+1;
    elseif($i==0) $ts=0;
    else $ts=$koeff;
    $td=$i;
    for($d=0;$d<$koeff;$d++) $td*=10;
    imagestring($this->img,2,$px1-12-(6*$ts),$py4-($i*$h)-6,$td,$this->gcol[5]);
  }

  //vertical grid lines
  imageline($this->img,$px1,$py4,$px1,$py2,$this->gcol[4]);
  $w=$barwidth/$num;
  reset($data);
  for($i=0;$i<=$num;$i++) {
    imageline($this->img,$px2+($i*$w),$py3,$px2+($i*$w),$py1,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px2+($i*$w),$py3,$this->gcol[4]);
    imageline($this->img,$px1+($i*$w),$py4,$px1+($i*$w),$py4+3,$this->gcol[4]);

    //bottom numbers
    if($i!=0) {
      $e=each($data);
      $tarr = preg_split("/\|/",$e[1]);

      $txt=$this->gettext($act,$e[0],$data[$e[0]],12);
      $dk=strlen($txt);
      if(($maxnum*6+12)<$w) {
        $stxh=round(($w-$dk*6)/2);
        imagestring($this->img,2,$px1+$stxh+($i-1)*$w,$py4+6,$txt,$this->gcol[5]);
      }
      else {
        $stxv=round(($w-12)/2);
        imagestringup($this->img,2,$px1+$stxv+($i-1)*$w,$py4+12+6*($dk),$txt,$this->gcol[5]);
      }
    }
  }

  //points
  $px1=$barcx;
  $px2=round($x)+$barcx;
  $px3=$barcx+$barwidth;
  $px4=$barcx+$barwidth+round($x);
  $py1=$barcy-$barheight-round($y);
  $py2=$barcy-$barheight;
  $py3=$barcy-round($y);
  $py4=$barcy;

  //grid for parameters
  for($i=0;$i<4;$i++) {
    $val=deg2rad($angle);
    $a=tan($val);
    $x=($barthick*$i/4)*($barthick*$i/4)/(1+$a*$a);
    $x=sqrt(abs($x));
    $y=$a*$x;
    $x=round($x);
    $y=round($y);
    imageline($this->img,$px1+$x,$py4-$y,$px1+$x,$py2-$y,$this->gcol[4]);
    imageline($this->img,$px1+$x,$py4-$y,$px3+$x+5,$py4-$y,$this->gcol[4]);

    //color
    if($i==3) $num=1;
    elseif($i==2) $num=3;
    elseif($i==1) $num=12;
    else $num=4;

    //color rectangle
    $pts=array();
    $pts[]=$px3+$x+18;
    $pts[]=$py4-$y-12;
    $pts[]=$px3+$x+26;
    $pts[]=$py4-$y-12;
    $pts[]=$px3+$x+26;
    $pts[]=$py4-$y-4;
    $pts[]=$px3+$x+18;
    $pts[]=$py4-$y-4;
    imagefilledpolygon($this->img,$pts,sizeof($pts)/2,$this->col[$num]);
    imagepolygon($this->img,$pts,sizeof($pts)/2,$this->bcol[$num]);

    //parameter names
    if($i==0) $ds=_VISITORS;
    elseif($i==1) $ds=_HOSTS;
    elseif($i==2) $ds=_RELOADS;
    else $ds=_HITS;

    imagestring($this->img,2,$px3+$x+30,$py4-$y-14,$ds,$this->gcol[5]);
  }
}

//notes for picture
function gettext($act,$prim,$sec,$num) {
  global $err,$conf;

    $txt=$prim;
    $tarr = preg_split("/\|/",$sec);
    if(!strcmp($act,'vis_int')) $txt=$tarr[$num];
    elseif(!strcmp($act,'cls_int')) $txt=$tarr[$num];
    else $txt='['.$prim.'] '.$tarr[$num];

    if(strlen($txt)>_IMG_TWIDTH) $txt=substr($txt,0,_IMG_TWIDTH-3).'...';

    return $txt;
}

}

?>
