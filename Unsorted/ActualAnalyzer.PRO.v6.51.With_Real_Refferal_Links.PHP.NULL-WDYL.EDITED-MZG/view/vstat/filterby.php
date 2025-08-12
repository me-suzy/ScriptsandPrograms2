<?php

  $flt='';
  if(isset($GLOBALS['filter_sort'])) $flt=$GLOBALS['filter_sort'];
  elseif(isset($HTTP_POST_VARS['filter_sort'])) $flt=$HTTP_POST_VARS['filter_sort'];

  //default sort by time
  $sort='f_time';

  //clear filter fields
  $fclr='';
  if(isset($GLOBALS['f_clear_x'])) $fclr=$GLOBALS['f_clear_x'];
  elseif(isset($HTTP_POST_VARS['f_clear_x'])) $fclr=$HTTP_POST_VARS['f_clear_x'];
  if(!empty($fclr)&&($fclr!=0)) return;

  //if filter panel exists
  if(!empty($flt)) {
    $sort=$flt;

    //host
    $tmp='';
    if(isset($GLOBALS['f_host'])) $tmp=$GLOBALS['f_host'];
    elseif(isset($HTTP_POST_VARS['f_host'])) $tmp=$HTTP_POST_VARS['f_host'];
    $tmp_cl='';
    if(isset($GLOBALS['f_host_cl'])) $tmp_cl=$GLOBALS['f_host_cl'];
    elseif(isset($HTTP_POST_VARS['f_host_cl'])) $tmp_cl=$HTTP_POST_VARS['f_host_cl'];

    if(!empty($tmp)) {
      $filter['f_host']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_host']=$tmp_cl;
      else $filter_cl['f_host']=1;
    }
    //proxy
    $tmp='';
    if(isset($GLOBALS['f_proxy'])) $tmp=$GLOBALS['f_proxy'];
    elseif(isset($HTTP_POST_VARS['f_proxy'])) $tmp=$HTTP_POST_VARS['f_proxy'];
    $tmp_cl='';
    if(isset($GLOBALS['f_proxy_cl'])) $tmp_cl=$GLOBALS['f_proxy_cl'];
    elseif(isset($HTTP_POST_VARS['f_proxy_cl'])) $tmp_cl=$HTTP_POST_VARS['f_proxy_cl'];

    if(!empty($tmp)) {
      $filter['f_proxy']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_proxy']=$tmp_cl;
      else $filter_cl['f_proxy']=1;
    }
    //provider
    $tmp='';
    if(isset($GLOBALS['f_provider'])) $tmp=$GLOBALS['f_provider'];
    elseif(isset($HTTP_POST_VARS['f_provider'])) $tmp=$HTTP_POST_VARS['f_provider'];
    $tmp_cl='';
    if(isset($GLOBALS['f_provider_cl'])) $tmp_cl=$GLOBALS['f_provider_cl'];
    elseif(isset($HTTP_POST_VARS['f_provider_cl'])) $tmp_cl=$HTTP_POST_VARS['f_provider_cl'];

    if(!empty($tmp)) {
      $filter['f_provider']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_provider']=$tmp_cl;
      else $filter_cl['f_provider']=1;
    }
    //country
    $tmp='';
    if(isset($GLOBALS['f_country'])) $tmp=$GLOBALS['f_country'];
    elseif(isset($HTTP_POST_VARS['f_country'])) $tmp=$HTTP_POST_VARS['f_country'];
    $tmp_cl='';
    if(isset($GLOBALS['f_country_cl'])) $tmp_cl=$GLOBALS['f_country_cl'];
    elseif(isset($HTTP_POST_VARS['f_country_cl'])) $tmp_cl=$HTTP_POST_VARS['f_country_cl'];

    if(!empty($tmp)) {
      $filter['f_country']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_country']=$tmp_cl;
      else $filter_cl['f_country']=1;
    }
    //time zone
    $tmp='';
    if(isset($GLOBALS['f_tzone'])) $tmp=$GLOBALS['f_tzone'];
    elseif(isset($HTTP_POST_VARS['f_tzone'])) $tmp=$HTTP_POST_VARS['f_tzone'];
    $tmp_cl='';
    if(isset($GLOBALS['f_tzone_cl'])) $tmp_cl=$GLOBALS['f_tzone_cl'];
    elseif(isset($HTTP_POST_VARS['f_tzone_cl'])) $tmp_cl=$HTTP_POST_VARS['f_tzone_cl'];

    if(!empty($tmp)) {
      $filter['f_tzone']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_tzone']=$tmp_cl;
      else $filter_cl['f_tzone']=1;
    }
    //language
    $tmp='';
    if(isset($GLOBALS['f_lang'])) $tmp=$GLOBALS['f_lang'];
    elseif(isset($HTTP_POST_VARS['f_lang'])) $tmp=$HTTP_POST_VARS['f_lang'];
    $tmp_cl='';
    if(isset($GLOBALS['f_lang_cl'])) $tmp_cl=$GLOBALS['f_lang_cl'];
    elseif(isset($HTTP_POST_VARS['f_lang_cl'])) $tmp_cl=$HTTP_POST_VARS['f_lang_cl'];

    if(!empty($tmp)) {
      $filter['f_lang']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_lang']=$tmp_cl;
      else $filter_cl['f_lang']=1;
    }
    //operating system
    $tmp='';
    if(isset($GLOBALS['f_os'])) $tmp=$GLOBALS['f_os'];
    elseif(isset($HTTP_POST_VARS['f_os'])) $tmp=$HTTP_POST_VARS['f_os'];
    $tmp_cl='';
    if(isset($GLOBALS['f_os_cl'])) $tmp_cl=$GLOBALS['f_os_cl'];
    elseif(isset($HTTP_POST_VARS['f_os_cl'])) $tmp_cl=$HTTP_POST_VARS['f_os_cl'];

    if(!empty($tmp)) {
      $filter['f_os']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_os']=$tmp_cl;
      else $filter_cl['f_os']=1;
    }
    //browser
    $tmp='';
    if(isset($GLOBALS['f_browser'])) $tmp=$GLOBALS['f_browser'];
    elseif(isset($HTTP_POST_VARS['f_browser'])) $tmp=$HTTP_POST_VARS['f_browser'];
    $tmp_cl='';
    if(isset($GLOBALS['f_browser_cl'])) $tmp_cl=$GLOBALS['f_browser_cl'];
    elseif(isset($HTTP_POST_VARS['f_browser_cl'])) $tmp_cl=$HTTP_POST_VARS['f_browser_cl'];

    if(!empty($tmp)) {
      $filter['f_browser']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_browser']=$tmp_cl;
      else $filter_cl['f_browser']=1;
    }
    //referring page
    $tmp='';
    if(isset($GLOBALS['f_refpg'])) $tmp=$GLOBALS['f_refpg'];
    elseif(isset($HTTP_POST_VARS['f_refpg'])) $tmp=$HTTP_POST_VARS['f_refpg'];
    $tmp_cl='';
    if(isset($GLOBALS['f_refpg_cl'])) $tmp_cl=$GLOBALS['f_refpg_cl'];
    elseif(isset($HTTP_POST_VARS['f_refpg_cl'])) $tmp_cl=$HTTP_POST_VARS['f_refpg_cl'];

    if(!empty($tmp)) {
      $filter['f_refpg']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_refpg']=$tmp_cl;
      else $filter_cl['f_refpg']=1;
    }
    //frame address
    $tmp='';
    if(isset($GLOBALS['f_frame'])) $tmp=$GLOBALS['f_frame'];
    elseif(isset($HTTP_POST_VARS['f_frame'])) $tmp=$HTTP_POST_VARS['f_frame'];
    $tmp_cl='';
    if(isset($GLOBALS['f_frame_cl'])) $tmp_cl=$GLOBALS['f_frame_cl'];
    elseif(isset($HTTP_POST_VARS['f_frame_cl'])) $tmp_cl=$HTTP_POST_VARS['f_frame_cl'];

    if(!empty($tmp)) {
      $filter['f_frame']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_frame']=$tmp_cl;
      else $filter_cl['f_frame']=1;
    }
    //search engine
    $tmp='';
    if(isset($GLOBALS['f_engine'])) $tmp=$GLOBALS['f_engine'];
    elseif(isset($HTTP_POST_VARS['f_engine'])) $tmp=$HTTP_POST_VARS['f_engine'];
    $tmp_cl='';
    if(isset($GLOBALS['f_engine_cl'])) $tmp_cl=$GLOBALS['f_engine_cl'];
    elseif(isset($HTTP_POST_VARS['f_engine_cl'])) $tmp_cl=$HTTP_POST_VARS['f_engine_cl'];

    if(!empty($tmp)) {
      $filter['f_engine']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_engine']=$tmp_cl;
      else $filter_cl['f_engine']=1;
    }
    //search phrase
    $tmp='';
    if(isset($GLOBALS['f_phrase'])) $tmp=$GLOBALS['f_phrase'];
    elseif(isset($HTTP_POST_VARS['f_phrase'])) $tmp=$HTTP_POST_VARS['f_phrase'];
    $tmp_cl='';
    if(isset($GLOBALS['f_phrase_cl'])) $tmp_cl=$GLOBALS['f_phrase_cl'];
    elseif(isset($HTTP_POST_VARS['f_phrase_cl'])) $tmp_cl=$HTTP_POST_VARS['f_phrase_cl'];

    if(!empty($tmp)) {
      $filter['f_phrase']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_phrase']=$tmp_cl;
      else $filter_cl['f_phrase']=1;
    }
    //visitor id
    $tmp='';
    if(isset($GLOBALS['f_visitor'])) $tmp=$GLOBALS['f_visitor'];
    elseif(isset($HTTP_POST_VARS['f_visitor'])) $tmp=$HTTP_POST_VARS['f_visitor'];
    $tmp_cl='';
    if(isset($GLOBALS['f_visitor_cl'])) $tmp_cl=$GLOBALS['f_visitor_cl'];
    elseif(isset($HTTP_POST_VARS['f_visitor_cl'])) $tmp_cl=$HTTP_POST_VARS['f_visitor_cl'];

    if(!empty($tmp)) {
      if(preg_match("/\s*(\d+)/",$tmp,$match)) $tmp=$match[1];
      else $tmp=0;
      $filter['f_visitor']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_visitor']=$tmp_cl;
      else $filter_cl['f_visitor']=1;
    }
    //depth of viewing
    $tmp='';
    if(isset($GLOBALS['f_dview'])) $tmp=$GLOBALS['f_dview'];
    elseif(isset($HTTP_POST_VARS['f_dview'])) $tmp=$HTTP_POST_VARS['f_dview'];
    $tmp_cl='';
    if(isset($GLOBALS['f_dview_cl'])) $tmp_cl=$GLOBALS['f_dview_cl'];
    elseif(isset($HTTP_POST_VARS['f_dview_cl'])) $tmp_cl=$HTTP_POST_VARS['f_dview_cl'];

    if(!empty($tmp)) {
      if(preg_match("/\s*(\d+)/",$tmp,$match)) $tmp=$match[1];
      else $tmp=0;
      $filter['f_dview']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_dview']=$tmp_cl;
      else $filter_cl['f_dview']=1;
    }
    //hits
    $tmp='';
    if(isset($GLOBALS['f_hits'])) $tmp=$GLOBALS['f_hits'];
    elseif(isset($HTTP_POST_VARS['f_hits'])) $tmp=$HTTP_POST_VARS['f_hits'];
    $tmp_cl='';
    if(isset($GLOBALS['f_hits_cl'])) $tmp_cl=$GLOBALS['f_hits_cl'];
    elseif(isset($HTTP_POST_VARS['f_hits_cl'])) $tmp_cl=$HTTP_POST_VARS['f_hits_cl'];

    if(!empty($tmp)) {
      if(preg_match("/\s*(\d+)/",$tmp,$match)) $tmp=$match[1];
      else $tmp=0;
      $filter['f_hits']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_hits']=$tmp_cl;
      else $filter_cl['f_hits']=1;
    }
    //cookie
    $tmp='';
    if(isset($GLOBALS['f_cookie'])) $tmp=$GLOBALS['f_cookie'];
    elseif(isset($HTTP_POST_VARS['f_cookie'])) $tmp=$HTTP_POST_VARS['f_cookie'];
    $tmp_cl='';
    if(isset($GLOBALS['f_cookie_cl'])) $tmp_cl=$GLOBALS['f_cookie_cl'];
    elseif(isset($HTTP_POST_VARS['f_cookie_cl'])) $tmp_cl=$HTTP_POST_VARS['f_cookie_cl'];

    if(!empty($tmp)) {
      $filter['f_cookie']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_cookie']=$tmp_cl;
      else $filter_cl['f_cookie']=1;
    }
    //java
    $tmp='';
    if(isset($GLOBALS['f_java'])) $tmp=$GLOBALS['f_java'];
    elseif(isset($HTTP_POST_VARS['f_java'])) $tmp=$HTTP_POST_VARS['f_java'];
    $tmp_cl='';
    if(isset($GLOBALS['f_java_cl'])) $tmp_cl=$GLOBALS['f_java_cl'];
    elseif(isset($HTTP_POST_VARS['f_java_cl'])) $tmp_cl=$HTTP_POST_VARS['f_java_cl'];

    if(!empty($tmp)) {
      $filter['f_java']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_java']=$tmp_cl;
      else $filter_cl['f_java']=1;
    }
    //javascript
    $tmp='';
    if(isset($GLOBALS['f_jscript'])) $tmp=$GLOBALS['f_jscript'];
    elseif(isset($HTTP_POST_VARS['f_jscript'])) $tmp=$HTTP_POST_VARS['f_jscript'];
    $tmp_cl='';
    if(isset($GLOBALS['f_jscript_cl'])) $tmp_cl=$GLOBALS['f_jscript_cl'];
    elseif(isset($HTTP_POST_VARS['f_jscript_cl'])) $tmp_cl=$HTTP_POST_VARS['f_jscript_cl'];

    if(!empty($tmp)) {
      $filter['f_jscript']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_jscript']=$tmp_cl;
      else $filter_cl['f_jscript']=1;
    }
    //screen resolution
    $tmp='';
    if(isset($GLOBALS['f_screen'])) $tmp=$GLOBALS['f_screen'];
    elseif(isset($HTTP_POST_VARS['f_screen'])) $tmp=$HTTP_POST_VARS['f_screen'];
    $tmp_cl='';
    if(isset($GLOBALS['f_screen_cl'])) $tmp_cl=$GLOBALS['f_screen_cl'];
    elseif(isset($HTTP_POST_VARS['f_screen_cl'])) $tmp_cl=$HTTP_POST_VARS['f_screen_cl'];

    if(!empty($tmp)) {
      $filter['f_screen']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_screen']=$tmp_cl;
      else $filter_cl['f_screen']=1;
    }
    //color depth
    $tmp='';
    if(isset($GLOBALS['f_cdepth'])) $tmp=$GLOBALS['f_cdepth'];
    elseif(isset($HTTP_POST_VARS['f_cdepth'])) $tmp=$HTTP_POST_VARS['f_cdepth'];
    $tmp_cl='';
    if(isset($GLOBALS['f_cdepth_cl'])) $tmp_cl=$GLOBALS['f_cdepth_cl'];
    elseif(isset($HTTP_POST_VARS['f_cdepth_cl'])) $tmp_cl=$HTTP_POST_VARS['f_cdepth_cl'];

    if(!empty($tmp)) {
      if(!strcmp($tmp,_NONE)) $tmp=0;
      if(!strcmp($tmp,_UNDEFINED)) $tmp=1000;
      if(preg_match("/\s*(\d+)/",$tmp,$match)) $tmp=$match[1];
      else $tmp=0;
      $filter['f_cdepth']=$tmp;
      if(!empty($tmp_cl)) $filter_cl['f_cdepth']=$tmp_cl;
      else $filter_cl['f_cdepth']=1;
    }

    //add value to filter
    $fltprm='';
    if(isset($GLOBALS['filter_prm'])) $fltprm=$GLOBALS['filter_prm'];
    elseif(isset($HTTP_POST_VARS['filter_prm'])) $fltprm=$HTTP_POST_VARS['filter_prm'];

    if(!empty($fltprm)) {
      $tarr=preg_split("/=/",$fltprm);
      $vname=$tarr[0];
      unset($tarr[0]);
      $vval=join('=',$tarr);
      $vcon=1;
      if((!strcmp($vname,'f_cookie'))||(!strcmp($vname,'f_java'))) {
        if(!strcasecmp($vval,_ENABLED)) $vval=1;
        elseif(!strcasecmp($vval,_DISABLED)) $vval=2;
        elseif(!strcasecmp($vval,_UNDEFINED)) $vval=3;
      }
      elseif(!strcmp($vname,'f_tzone')) {
        require './data/bases/tzones.php';

        reset($tzones);
        while ($k=key($tzones)) {
          if(!strcmp($vval,$tzones[$k])) {$vval=$k;break;}
          next($tzones);
        }
        if(!strcasecmp($vval,_NONE)) $vval=0;
      }
      elseif((!strcmp($vname,'f_dview'))||(!strcmp($vname,'f_hits'))) {
        $vcon=2;
      }
      elseif(!strcmp($vname,'f_cdepth')) {
        if(!strcasecmp($vval,_NONE)) $vval=0;
        if(!strcasecmp($vval,_UNDEFINED)) $vval=1000;
        $vcon=2;
      }
      elseif(!strcmp($vname,'f_phrase')) {
        $vval=urldecode($vval);
        $vcon=1;
      }

      $filter[$vname]=$vval;
      $filter_cl[$vname]=$vcon;
    }
  }

?>
