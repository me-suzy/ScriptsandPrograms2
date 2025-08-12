<?php 

function css($setting = array()){

    $font_size = isset($setting['font_size'])?$setting['font_size']:'x-small';
    $font_smaller = isset($setting['font_smaller'])?$setting['font_smaller']:'xx-small';
		$font_smallest = isset($setting['font_smallest'])?$setting['font_smallest']:'7pt';
		$site_fonts = isset($setting['site_font'])?$setting['site_font']:'verdana, arial, helvetica, sans-serif';
		
	  header("Content-type: text/css"); 


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
	font-family: <?php echo $site_fonts; ?>;
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

div.leftnav{
background: white;
color: black;
position: absolute;
width: 15%;
top: 80px;
} 
	
div.rightnav{
background: #ffcccc;
color: black;
position: absolute;
left: 83%;
width: 200px;
top: 80px; /* 80 pixels from the top */
border-color: white; /* Keep the border invisible */
border-style: solid; /* It is a solid invisible line which is fine */
border-bottom-width: 2px; /* These attributes are pretty self-explanatory */
border-top-width: 2px;
border-left-width: 3px;
border-right-width: 4px;
margin-right: 5px;
} 

div.content{
background: white;
color: black;
position: absolute; /* Says which positioning we are using */
left: 17%; /* 17% from the left side of the screen */
width: 800px; /* This is the width */
}
 
	
</STYLE>

<?php
}
?>
