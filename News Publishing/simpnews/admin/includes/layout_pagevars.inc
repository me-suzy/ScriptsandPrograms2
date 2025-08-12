<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$destpages=array($l_heading,$l_categories,$l_news,$l_linkcolors,$l_grafiks,$l_announce,
	"$l_attachements / $l_searching",$l_proped,"$l_eventlists / $l_eventcal",
	$l_propose_entries2,$l_layout,$l_userstyles,"$l_emails / $l_subscriptions",
	$l_applets,$l_hotscripts2,$l_js_scripts,$l_newsletter,$l_newsmail,$l_includescripts,
	$l_rss_newsfeed, $l_wap_newsfeed
	);
$commonvars=array("deflayout");
$pagevars=array(
	// page 0
	array("heading","headingbgcolor","headingfontcolor","headingfont","headingfontsize"),
	// page 1
	array("enablecatlist","catframenewslist","clwidth","nfheight","clleftwidth","clrightwidth","clnowrap",
		"clheadingbgcolor","clheadingfontcolor","clheadingfont","clheadingfontsize","clcontentbgcolor",
		"clcontentfontcolor","clcontentfont","clcontentfontsize","clcontenthighlight","clactdontlink",
		"catinfoindent","catinfofontcolor","catinfofont","catinfofontsize","catinfobgcolor"),
	// page 2
	array("nbindent","newsheadingbgcolor","newsheadingfontcolor","newsheadingfont","newsheadingfontsize",
		"newsheadingstyle","displayposter","linkposter","posterbgcolor","posterfontcolor","posterfont",
		"posterfontsize","posterstyle","contentbgcolor","contentfontcolor","contentfontsize","contentfont",
		"timestampbgcolor","timestampfontcolor","timestampfont","timestampfontsize","timestampstyle",
		"dateformat","categorybgcolor","categoryfontcolor","categoryfont","categoryfontsize","categorystyle",
		"copyrightbgcolor","copyrightfontcolor","copyrightfont","copyrightfontsize","copyrightpos",
		"news5startdate","news5enddate","news5headingdateformat","news5dateformat","news5linktarget",
		"news5noglobalprint","news5displayposter","news5displayicons","ev2onlyheadings","ev2_newsheadingfontsize",
		"ev2_newsheadingstyle","ev2_timestampfontsize","ev2_timestampstyle","ev2_contentfontsize",
		"ev2_posterfontsize","ev2_posterstyle","csvexportdateformat","csvexportfields","news5maxchars",
		"news5useddlink","n5_newsheadingfontsize","n5_timestampfontsize","n5_timestampstyle","news5yearbgcolor",
		"news5yearfontcolor","news5yearfont","news5yearfontsize","news5yearfontstyle","news5monthbgcolor",
		"news5monthfontcolor","news5monthfont","news5monthfontsize","news5monthfontstyle","news5monthdisplayyear",
		"news2target","news3fontsize","news3linktarget","news3dateformat","news3maxchars","news3useddlink",
		"news4fontsize","news4linktarget","news4dateformat","news4maxchars","news4useddlink","sn_hideallnewslink",
		"sncatlink", "ratingprelude", "news4showcat", "showproposer", "commentsinline", "icdisplayemail",
		"ic_heading_bgcolor", "ic_heading_fontcolor", "ic_heading_font", "ic_heading_fontsize", "ic_body_bgcolor",
		"ic_body_fontcolor", "ic_body_font", "ic_body_fontsize", "ic_heading_style", "ic_body_style", "newsnodate",
		"n4nodate", "n4tbmargin", "n4leftmargin", "newsnoicons", "snnodate", "news2entries", "news3entries",
		"news4addoptions", "showfuturenews"
	),
	// page 3
	array("linkcolor","vlinkcolor","alinkcolor","morelinkcolor","morevlinkcolor","morealinkcolor","morelinkfontsize",
		"catlinkcolor","catvlinkcolor","catalinkcolor","commentlinkcolor","commentvlinkcolor","commentalinkcolor",
		"attachlinkcolor","attachvlinkcolor","attachalinkcolor","pagenavlinkcolor","pagenavvlinkcolor","pagenavalinkcolor",
		"eventlinkcolor","eventvlinkcolor","eventalinkcolor","eventlinkfontsize",
		"actionlinkcolor","actionvlinkcolor","actionalinkcolor"
	),
	// page 4
	array("searchpic","backpic","helppic","attachpic","newentrypic","pagetoppic","pagepic_back","pagepic_next",
		"pagepic_first","pagepic_last","newssignal_on","newssignal_off","pagebgpic","proposepic",
		"caltabpic","printpic","csvexportpic","asclistpic","morepic","announcepic","gannouncepic",
		"highlightmarker","emailpic","commentspic", "writecommentpic", "printpic_small", "expandpic",
		"collapsepic", "rsspic"
	),
	// page 5
	array("announceoptions","maxevcannounce","anhn6numentries"),
	// page 6
	array("nofileinfo","searchonlyheadings","searchdetailtarget","searchshortchars","sshort_timestampfontsize",
		"sshort_headingfontsize", "attachpos", "searchmaxchars", "searchighlightcolor", "searchhighlight",
		"activcellcolor", "srchnolimit", "srchaddoptions"
	),
	// page 7
	array("or_bgcolor","or_fontcolor","or_font","or_fontsize","bbc_bgcolor","bbc_bordercolor",
		"bbcsel_bgcolor","bbcsel_fontcolor","bbcsel_bordercolor","bbcsel_borderstyle","bbcsel_fontsize",
		"bbcsel_font","bbcsel_borderwidth","bbcsel_fontstyle","bbcsel_fontweight","bbchelp_bgcolor",
		"bbchelp_fontcolor","bbchelp_font","bbchelp_fontsize","bbchelp_fontweight","bbchelp_fontstyle",
		"sb_bgcolor","sb_bordercolor","el_hovercolor","el_font","el_fontsize","el_fontweight","el_fontstyle",
		
	),
	// page 8
	array("evbindent","eventheading","event_dateformat","eventcalshortnews","eventcalshortonlyheadings",
		"displayevnum","eventcalshortnum","eventcalshortlength","weekstart","evshowcalweek","caljumpbox",
		"cjminyear","cjmaxyear","prevpic","fwdpic","event_dateformat2", "ev3_dateformat", "masssuboptions",
		"eventcalonlymarkers", "evmarkcolgeneral"
	),
	// page 9
	array("proposereq","evproposemaxyears"),
	// page 10
	array("tablealign","bordercolor","separatebylang","showcurrtime","noprinticon","nogotopicon","pagenavdetails",
		"TableWidth","TableWidth2","usecustomheader","customheader","headerfilepos","usecustomfooter",
		"customfooter","footerfilepos","printheader","contentcopy","addbodytags","pagebgcolor","stylesheet",
		"ns4style","ns6style","operastyle","geckostyle","konquerorstyle","headerfile","footerfile", "tablebgcolor",
		"entriesperpage", "cheadnobr", "cfootnobr", "chnobrtrans", "cfnobrtrans"
	),
	// page 11
	array("colorscrollbars","sbfacecolor","sbhighlightcolor","sbshadowcolor","sbdarkshadowcolor","sb3dlightcolor",
		"sbarrowcolor","sbtrackcolor","snsel_bgcolor","snsel_fontcolor","snsel_font","snsel_fontsize",
		"snsel_fontstyle","snsel_fontweight","snsel_borderstyle","snsel_borderwidth","snsel_bordercolor",
		"sninput_bgcolor","sninput_fontcolor","sninput_font","sninput_fontsize","sninput_fontstyle",
		"sninput_fontweight","sninput_borderstyle","sninput_borderwidth","sninput_bordercolor","textareanoscroll",
		"snisb_facecolor","snisb_highlightcolor","snisb_shadowcolor","snisb_darkshadowcolor","snisb_3dlightcolor",
		"snisb_arrowcolor","snisb_trackcolor","snbutton_bgcolor","snbutton_fontcolor","snbutton_font",
		"snbutton_fontsize","snbutton_fontstyle","snbutton_fontweight","snbutton_borderstyle","snbutton_borderwidth",
		"snbutton_bordercolor"
	),
	// page 12
	array("defsignature","emailremark","displaysubscriptionbox","subscriptionbgcolor","subscriptionfontcolor",
		"subscriptionfont","subscriptionfontsize"
	),
	// page 13
	array("sep_char","newstickerbgcolor","newstickerfontcolor","newstickerhighlightcolor","newstickerfont",
		"newstickerfontsize","newstickerwidth","newstickerheight","newstickerscrollspeed","newstickerscrolldelay",
		"newstickertarget","newstickermaxdays","newstickermaxentries","newsscrollerheadingsep","newsscrollerheadingsepchar",
		"newsscrollernumsepchars","newsscrollerbgcolor","newsscrollerfontcolor","newsscrollerfont",
		"newsscrollerfontsize","newsscrollerwidth","newsscrollerheight","newsscrollerbgimage","newsscrollerfgimage",
		"newsscrollerscrollspeed","newsscrollerscrolldelay","newsscrollerscrollpause","newsscrollermousestop",
		"newsscrollerdisplaydate","newsscrollerdateformat","newsscrollerwordwrap","newsscrollertype",
		"newsscrollertarget","newsscrollerxoffset","newsscrolleryoffset","newsscrollernolinking",
		"newsscrollermaxdays","newsscrollermaxentries","newsscrollermaxchars","newsscrollermaxlines",
		"newstyperbgcolor","newstyperfontcolor","newstyperfont","newstyperfontsize","newstyperfontstyle",
		"newstyperwidth","newstyperheight","newstyperbgimage","newstyperscroll","newstyperdisplaydate",
		"newstyperdateformat","newstyperxoffset","newstyperyoffset","newstypermaxdays","newstypermaxentries",
		"newstypermaxchars","newstyper2bgcolor","newstyper2fontcolor","newstyper2fontsize","newstyper2linespace",
		"newstyper2indent","newstyper2width","newstyper2height","newstyper2bgimage","newstyper2sound",
		"newstyper2charpause","newstyper2linepause","newstyper2screenpause","newstyper2newscreen",
		"newstyper2waitentry","newstyper2displaydate","newstyper2dateformat","newstyper2maxdays","newstyper2maxentries",
		"newstyper2maxchars","eventscrolleractdate","ss_width","ss_height","ss_font","ss_fontcolor",
		"ss_fontsize","ss_fontstyle","ss_shadow","ss_bgcolor","ss_stars","ss_speed","ss_dir","ss_targetframe",
		"ss_nolinking","ss_maxdays","ss_maxentries", "appletlinkdest", "useappletlinkdest", "appletlinkdestan",
		"appletlinkdestev", "evscrollevcal2", "evscrollcal2dest", "applet_ganmark", "applet_anmark", "subemailtype"
	),
	// page 14
	array("hotscriptsnoheading","hn_newsheadingfontsize","hotevmaxdays","hotevmaxentries","hotevtarget",
		"hotevmaxchars","hotevdisplayposter","hotevnohtmlformatting","hotevicons","hotnewstarget",
		"hotnewsdisplayposter","hotnewsnohtmlformatting","hotnewsicons","hotnews2target","hn6_numentries",
		"hotnews6target","hotnews7useddlink", "hnlinkdest", "usehnlinkdest", "hnlinkdestan", "hnlinkdestev",
		"hn_catlinking", "hn_linklayout", "hotnewsmaxchars", "numhotnews", "newsnotifydays", "hnnolinking",
		"hotnewscommentslink"
	),
	// page 15
	array("jsnf_width","jsnf_height","jsnf_font","jsnf_fontcolor","jsnf_fontsize","jsnf_delay","jsnf_nolinking",
		"jsnf_linktarget","jsnf_displaydate","jsnf_dateformat","jsnf_maxentries","jsnf_maxdays","jsnf_maxchars",
		"jsns_sepheading","jsns_bgcolor","jsns_width","jsns_height","jsns_font","jsns_fontsize","jsns_fontcolor",
		"jsns_direction","jsns_speed","jsns_step","jsns_nolinking","jsns_linktarget","jsns_displaydate",
		"jsns_dateformat","jsns_maxentries","jsns_maxdays","jsns_maxchars", "jslinkdest", "usejslinkdest",
		"jslinkdestan", "jslinkdestev"
	),
	// page 16
	array("newsletterbgcolor","newslettercustomheader","newslettercustomfooter","subredirecturl","newsletteralign",
		"nlsend_heading","nlsend_dateformat","nlsend_bgcolor","nlsend_font","nlsend_fontcolor","nlsend_fontsize",
		"nchnobrtrans", "ncfnobrtrans"
	),
	// page 17
	array("emailbgcolor","emailcustomheader","emailcustomfooter","emailpageremark"),
	// page 18
	array("sns_tablewidth","sns_options","aninc_options","aninc_tablewidth", "evinc_options", "evinc_tablewidth"),
	// page 19
	array("rss_channel_title","rss_channel_description", "rss_channel_link", "rss_auto_title", "rss_auto_short",
		"rss_channel_copyright", "rss_maxentries", "rss_channel_editor", "rss_channel_webmaster"
	),
	// page 20
	array("wap_title","wap_description","wap_copyright","wap_auto_title","wap_auto_short","wap_options","wap_maxentries",
		"wap_ev_maxentries","wap_ev_title","wap_ev_description","wap_ev_maxdays","wap_an_maxentries","wap_an_title",
		"wap_an_description","wap_an_maxdays","wap_evs_maxldays","wap_evs_dayrange", "wap_catlist_epp", "wap_evlist2_epp",
		"wap_cl_title","wap_cl_description", "wap_cl_logo"
	)
);
$layoutvars=array();
for($x=0;$x<count($pagevars);$x++)
	$layoutvars=array_merge($layoutvars,$pagevars[$x]);
