<?php
//Read in config file
$thisfile = "credits";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<META http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">

<SCRIPT LANGUAGE="JavaScript">var I = 0;

function scrollit() {if (I == 1450) {stop = true}else {self.scroll(1,I);

I = eval(I + 1);setTimeout("scrollit()", 20);}}// End --></SCRIPT>
</head>

<body bgcolor="#FFFFFF" text="#000000" OnLoad="scrollit()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon6-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav8 ?></td>
    <td rowspan="2" width="0"><a href="help/manual.pdf"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
<?php
	if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_license, $la_title_support, $la_title_credits, $cykuh_title_nullification);
	$nav_links_admin[$la_title_license]="license.php$att_sid";
	$nav_links_admin[$la_title_support]="support.php$att_sid";
	$nav_links_admin[$la_title_credits]="credits.php$att_sid";
	$nav_links_admin[$cykuh_title_nullification]="nullification.php$att_sid";
	echo display_admin_nav($la_title_credits, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_credits ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="credits.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
	  ?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2">
<p>&nbsp;</p>
              <p align="center"><?php echo $la_thank_you ?></p>
              <p align="center"><b><?php echo $la_development_team ?></b></p>
              <p align="center">Ajna Cackovic<br>
                <i>- 
                design, interface, dreamweaver template
                </i><br>
                <br>
                David Chen<br>
                <i>- 
                functionality, programming, database
                </i><br>
                <br>
                Peter Droppa <br>
                <i>- 
                interface, programming 
                </i><br>
                <br>
                Pavel Kharitonov<br>
                <i>- 
                database, functionality, programming
                </i><br>
                <br>
                Andrew Kucheriavy<br>
                <i>- 
                idea, interface, functionality, programming
                </i><br>
                <br>
                Stoyan Vlaikov<br>
                <i>- 
                programming, database
                </i><br>
                <br>
                <br>
                <b><?php echo $la_thank_you_beta ?></b></p>
              <p align="center">
                
				<br>John Abela<br>
				<br>Sébastien Barre<br>
				<br>David Campbell<br>
				<br>Ian Conza<br>
				<br>Veerachai Websnow Kanphugdee<br>				
				<br>Tarun Mistry<br>
				<br>Nezihi Ozduzen<br>
				
                <br>
                <br>
		<b>Nullified Edition: "Version 2.2.10n" by CyKuH [WTN]</b><br>
                <br>
                <b><?php echo $la_user_thank_you ?></b></p>
              <p align="center"><br>
                Websnow&nbsp;<br>
                Ian <br>
                Conza<br>
                etones<br>
                jhcashman<br>
                jsternoff&nbsp;<br>
                Maurice&nbsp;<br>
                scriptmaniac&nbsp;s<br>
                criptmaniac2&nbsp;<br>
                phpuser<br>
                AbelaJohnB<br>
                lawman&nbsp;<br>
                Nette&nbsp;<br>
                media&nbsp;<br>
                newbie&nbsp;<br>
                samtha25&nbsp;<br>
                olivier_l&nbsp;<br>
                MTO&nbsp;<br>
                Mike&nbsp;<br>
                Explorer&nbsp;<br>
                linuxbookmarks&nbsp;<br>
                statix&nbsp;<br>
                kailew&nbsp;<br>
                Helene&nbsp;<br>
                LiTLe-LiOn&nbsp;<br>
                Napstou&nbsp;<br>
                Olejka&nbsp;<br>
                MikeT&nbsp;<br>
                izeickl&nbsp;<br>
                josin_c&nbsp;<br>
                pini&nbsp;<br>
                Ripbud&nbsp;<br>
                ralph&nbsp;<br>
                step2000&nbsp;<br>
                ATKOgirl&nbsp;<br>
                toteam&nbsp;<br>
                bereznay&nbsp;<br>
                Darkmix&nbsp;<br>
                MB&nbsp;<br>
                Brett&nbsp;<br>
                earle&nbsp;<br>
                Jompa&nbsp;<br>
                MaCaR&nbsp;<br>
                jazzy&nbsp;<br>
                brfence&nbsp;<br>
                malcomito&nbsp;<br>
                spcover&nbsp;<br>
                justin_ <br>
                -=KobA=-<br>
                &nbsp;jwnetsource&nbsp;<br>
                Bender&nbsp;<br>
                YellowKard&nbsp;<br>
                JohnAz&nbsp;<br>
                TonySayz&nbsp;<br>
                jerkarchives&nbsp;<br>
                Jinn&nbsp;<br>
                Dawn&nbsp;<br>
                Dai&nbsp;<br>
                sjo&nbsp;<br>
                programmer1&nbsp;<br>
                Eden&nbsp;<br>
                GuidoB&nbsp;<br>
                mushan&nbsp;<br>
                2cn&nbsp;<br>
                AC&nbsp;<br>
                huxtable <br>
                jones&nbsp;<br>
                crjensen&nbsp;<br>
                Muddus&nbsp;<br>
                lunamouse&nbsp;<br>
                avalonx<br>
              </p>
              </td>
          </tr>
        </table>
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
