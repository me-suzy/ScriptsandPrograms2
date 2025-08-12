<?php 

function css_site() {

if(browser_is_windows($font = array())){

	if(browser_is_ie()){
		//ie needs smaller fonts
		$font_size='x-small';
		$font_smaller='xx-small';
		$font_smallest='7pt';
	}elseif(browser_is_opera()){
		//ie needs smaller fonts
		$font_size='x-small';
		$font_smaller='xx-small';
		$font_smallest='7pt';
	}else{
		//linux and other users
		$font_size='small';
		$font_smaller='x-small';
		$font_smallest='x-small';
	}

}elseif(browser_is_mac()){

    //mac users need bigger fonts
		$font_size='medium';
		$font_smaller='small';
		$font_smallest='x-small';

}else{

    //linux and other users
		$font_size='small';
		$font_smaller='x-small';
		$font_smallest='x-small';

}

	$site_fonts='verdana, arial, helvetica, sans-serif';
	
	header("Content-type: text/css"); 

global $BROWSER_AGENT,$BROWSER_VER,$BROWSER_PLATFORM;
	
echo '<!-- Browser = '.$BROWSER_AGENT." -->\n";
echo '<!-- Browser Version = '.$BROWSER_VER." -->\n";
echo '<!-- Browser Platform = '.$BROWSER_PLATFORM." -->\n\n\n";	

?>
<STYLE TYPE="text/css">	
	BODY{
	background: #cccccc;
	color: #000000;		
	leftmargin: 0;
	topmargin: 0;
	marginwidth: 0;
	marginheight: 0;
	text-align: center;
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: <?php echo $font_size; ?>;
	}
	
	TH { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; }
	TD { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; }
	OL { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; }
	UL { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; }
	LI { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; }
	
  H1 { font-size: 175%; font-family: <?php echo $site_fonts; ?>; }
  H2 { font-size: 150%; font-family: <?php echo $site_fonts; ?>; } 
  H3 { font-size: 125%; font-family: <?php echo $site_fonts; ?>; }
  H4 { font-size: 100%; font-family: <?php echo $site_fonts; ?>; } 
  H5 { font-size: 75%; font-family: <?php echo $site_fonts; ?>; }
  H6 { font-size: 50%; font-family: <?php echo $site_fonts; ?>; }
	PRE, TT, CODE { font-family: courier, sans-serif; font-size: <?php echo $font_size; ?>; }
	A:hover { text-decoration: none; color: #FF6666; font-size: <?php echo $font_size; ?>; }
	A.menus { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smaller; ?>; }
	A.menus:visited { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smaller; ?>; }
	A.menus:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menussel { color: #FF6666; text-decoration: none; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menussel:visited { color: #FF6666; text-decoration: none; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menussel:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menusxxs { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smallest; ?>; }
	A.menusxxs:visited { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smallest; ?>; }
	A.menusxxs:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: <?php echo $font_smallest; ?>; }
	
.top
{
    color: #ffffff;
    background-color: #ffffff;
    padding: 1px;
    padding-left: 3px;
    text-align: center;
}
	

.mainmenu{
  width: 10em;
	border-bottom: 5px solid #cc3366;
	padding: 5px 5px 5px 5px;
	margin-bottom: 5px;
	font-family: 'Trebuchet MS', 'Lucida Grande',
	Verdana, Lucida, Geneva, Helvetica,
	Arial, sans-serif;
	background-color: #ffffff;
	color: #333;
	text-align: left;
	width: auto;
	}	

</STYLE>

<?php
}
?>
