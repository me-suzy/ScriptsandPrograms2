<?php 
ob_start();
require_once('class.browser.php');
require_once('function.css.php');
require_once('function.header_setexpires.php');

$setting['user_agent'] = $_SERVER["HTTP_USER_AGENT"];
$browser = &new browser($setting);

$BROWSER_AGENT = $browser->getBrowser();
$BROWSER_VER = $browser->getVersion();
$BROWSER_PLATFORM = $browser->getPlatForm();

$setting = array();
//$setting['font_size'] = ;
//$setting['font_smaller'] = ;
//$setting['font_smallest'] = ;
$setting['site_font'] = 'palatino,  georgia, times new roman, serif';


if($BROWSER_AGENT == 'ie'){

    $setting['font_size'] = 'x-small';
		$setting['font_smaller'] = 'xx-small';
		$setting['font_smallest'] = '7pt';
    //$setting['site_font'] = ;
		
}elseif($BROWSER_AGENT == 'mozilla'){

    if($BROWSER_PLATFORM == 'windows'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
		}elseif($BROWSER_PLATFORM == 'linux'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'unix'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}else{
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}

}elseif($BROWSER_AGENT == 'opera'){

    if($BROWSER_PLATFORM == 'windows'){
		    $setting['font_size'] = 'x-small';
		    $setting['font_smaller'] = 'xx-small';
		    $setting['font_smallest'] = '7pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'linux'){
		    $setting['font_size'] = 'x-small';
		    $setting['font_smaller'] = 'xx-small';
		    $setting['font_smallest'] = '7pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'unix'){
		    $setting['font_size'] = 'x-small';
		    $setting['font_smaller'] = 'xx-small';
		    $setting['font_smallest'] = '7pt';
        //$setting['site_font'] = ;
			
		}else{
		    $setting['font_size'] = 'x-small';
		    $setting['font_smaller'] = 'xx-small';
		    $setting['font_smallest'] = '7pt';
        //$setting['site_font'] = ;
			
		}

}elseif($BROWSER_AGENT == 'netscape'){

    if($BROWSER_PLATFORM == 'windows'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'linux'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'unix'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}else{
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}
		
}else{ 

    if($BROWSER_PLATFORM == 'windows'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'linux'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}elseif($BROWSER_PLATFORM == 'unix'){
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}else{
		    $setting['font_size'] = 'small';
		    $setting['font_smaller'] = 'x-small';
		    $setting['font_smallest'] = '9pt';
        //$setting['site_font'] = ;
			
		}
}

header_setExpires(1*60*60);
header("Content-Type: text/css");
echo '<!-- Browser = '.$BROWSER_AGENT." -->\n";
echo '<!-- Browser Version = '.$BROWSER_VER." -->\n";
echo '<!-- Browser Platform = '.$BROWSER_PLATFORM." -->\n\n\n";
css($setting);
ob_end_flush();
?>
