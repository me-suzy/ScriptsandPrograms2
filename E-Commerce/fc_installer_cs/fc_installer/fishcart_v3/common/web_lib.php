<?php
// FishCart: an online catalog management / shopping system
// Copyright (C) 1997-2002  FishNet, Inc.

/* Gets attributes for <body> */
function fc_get_body_attr ($zid, $lid)
{

    // get the Web table
    $fc_web=new FC_SQL;
    $fc_web->query("select * from WEBTABLE where webzid=$zid and weblid=$lid");
    $fc_web->next_record();
    $ret = "" ;

    $fc_web->f("webback") && $ret .= " background=\"$fc_web->f(webback)\"" ;
    $fc_web->f("webtext") && $ret .= " text=\"$fc_web->f(webtext)\""  ;
    $fc_web->f("weblink") && $ret .= " link=\"$fc_web->f(weblink)\"" ;
    $fc_web->f("webvlink") && $ret .= " vlink=\"$fc_web->f(webvlink)\"" ;
    $fc_web->f("webalink") && $ret .= " alink=\"$fc_web->f(webalink)\"" ;
    $fc_web->f("webbg") && $ret .= " bgcolor=\"$fc_web->f(webbg)\"" ;

    return $ret ;
}

/* Determines whether or not an entry has a 'contrib' */
function fc_has_contrib ($zid, $lid)
{
    // get the Web table
    $fc_web=new FC_SQL;
    $fc_web->query("select * from WEBTABLE where webzid=$zid and weblid=$lid");
    $fc_web->next_record();
	return $fc_web->f("webcontrib") ;
}

?>
