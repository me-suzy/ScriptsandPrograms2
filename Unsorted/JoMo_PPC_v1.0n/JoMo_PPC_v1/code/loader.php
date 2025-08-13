<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
<?PHP
		ob_start();

//        include("error_config.php");

//		define("__CFG_PAYMENT_DEBUG",1);
		define("__CFG_PAYMENT_DEBUG",0);
		
        /** Load core library */
        include(__SMARTY_DIR . "Smarty.class.php");
       	include_once(__CFG_PATH_LIBS . "xx/class.Error.php");
        include(__CFG_PATH_LIBS."xx/class.xxObject.php");
        include(__CFG_PATH_LIBS."xx/class.xxSession.php");
        include(__CFG_PATH_LIBS."xx/class.xxDatabase.php");


        // mail
        include(__CFG_PATH_LIBS."mail/class.html.mime.mail.inc");
        include(__CFG_PATH_LIBS."mail/class.smtp.inc");
        
        // ppc business logics
        include(__CFG_PATH_LIBS."ppc/searchengine.php");
        include(__CFG_PATH_LIBS."ppc/general.php");
        include(__CFG_PATH_LIBS."ppc/links.php");
        include(__CFG_PATH_LIBS."ppc/membersModule.php"); 
        include(__CFG_PATH_LIBS."ppc/affiliatesModule.php"); 
        include(__CFG_PATH_LIBS."ppc/searchboxes.php"); 
        include(__CFG_PATH_LIBS."ppc/stats.php");
        include(__CFG_PATH_LIBS."ppc/mail.php");
        include(__CFG_PATH_LIBS."ppc/notifications.php");

		// search
		include(__CFG_PATH_LIBS."xml/xmlparser.searchfeedlinks.php"); // searchfeed
        include(__CFG_PATH_LIBS."google/googleapi.php");       // google
        include(__CFG_PATH_LIBS."search/av.php");       // google


		/** Create Error object  */
		$Error = new Error(mktime(0,0,0,11,15,2002));
                
        /** Create database object */
        $dbObj=new xxDatabase(__CFG_HOSTNAME, __CFG_USERNAME, __CFG_PASSWORD, __CFG_DATABASE);
        /** Open connect with database */
        $dbObj->open();
        /** Create dataset object */
        $dbSet=new xxDataset($dbObj);

        /** Create session object */
       	$sID=new xxSession();
		
        /** Create template engine */
        $tpl = new Smarty;
        $tpl->compile_check = true;
        $tpl->template_dir = __CFG_PATH_TEMPLATE;
        $tpl->compile_dir = __CFG_PATH_COMPILE;
        $tpl->left_delimiter = "<%";
        $tpl->right_delimiter = "%>";
//        $tpl->debugging = true;

        set_time_limit (3600);

        $tpl->assign("session", $sID->getSessionId());
        $tpl->assign("pathCSS", __CFG_PATH_STYLESHEET);
        $tpl->assign("pathImages", __CFG_PATH_IMAGES);
        $tpl->assign("pathJS", __CFG_PATH_JAVASCRIPT);
        if (isset($SCRIPT_NAME)) {
        	$tpl->assign("script_name", $SCRIPT_NAME);
        }
        
        define ("__SITE_TITLE", getOption("siteTitle"));
        $tpl->assign("pageTitle", __SITE_TITLE);

        /** Set self url */
        $tpl->assign("selfURL", basename($PHP_SELF));
        
        if (!isset($SERVER_NAME)) 
        	$SERVER_NAME = "";
        $tpl->assign("siteURL", $SERVER_NAME . $PHP_SELF);
		
		// payment debug
		$tpl->assign("payment_debug", __CFG_PAYMENT_DEBUG);
		define("__CFG_PAYPAL_ACCOUNT", getOption("paypalAccount"));


         /** Get browser type */
        $browser = "IE";
		if (isset($HTTP_USER_AGENT) && (preg_match("/.*(MSIE).*/", $HTTP_USER_AGENT, $match) || preg_match("/.*(Gecko).*/", $browser, $match))) {
			$browser = "IE";
		}
        $tpl->assign("browserName", $browser);
        unset($browser);
        
        /** options */
        
        // log
        logVisitor();

		$linksPerPage = getOption("LinksPerPage");
	
		$curtimestamp=time(); $curtime=getdate($curtimestamp);
		
		$months = array("all","Jan","Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		$monthIDs=array(0,1,2,3,4,5,6,7,8,9,10,11,12);
		
		$days [0] = "all";
		$dayIDs[0] = 0;
		for ($i=1;$i<=31;$i++) {
			$days[]=$i;
		}
		
		$years [0] = "all";
		$yearIDs[0] = 0;
		for ($i=2001;$i<=$curtime["year"]+1;$i++) {
			$years[] = $i;
		 	$yearIDs[] = $i;
		}

        $delimiters = array (
	        0=> array( "id" => 0, "name" => "Space", "value" => " "),
	        1=> array( "id" => 1, "name" => "Coma", "value" => ","),
	        2=> array( "id" => 2, "name" => "New String", "value" => "\n"),
        );

?>