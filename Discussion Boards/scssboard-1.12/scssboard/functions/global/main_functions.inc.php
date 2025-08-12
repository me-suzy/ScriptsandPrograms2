<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php
function redirect($location, $time=0) {
	$location = str_replace("&amp;","&",$location);
	if($_MAIN[redir_method] == "meta") {
		$return_html = "<meta http-equiv='refresh' content='$time; url=$location'>";
	} else {
		$return_html = "<script language=\"JavaScript\">";
		$return_html .= "window.location = '$location' ";
		$return_html .= "</script>";
	}
	$return_html .= "<span class='main_button'><a href='$location'>Continue...</a></span>";
	return $return_html;
}


function br2nl($data) {
	//Function that reverses nl2br
	return preg_replace('!<br.*>!iU', "\n", $data);
}


function get_date($date,$date_format,$use_rel) {
	
	if ((time() - $date > 172800) or (time() == $date)) {
		return date($date_format, $date);
	} else {
		if ($use_rel == "yes") {
			return doRelativeDate(date("YmdHis",$date));
		} else {
			return date($date_format, $date);
		}
	}

}


function doRelativeDate($posted_date) {

	$unformatted_curr_time = date("YmdHis");
	$curr_time = strtotime(substr($unformatted_curr_time,0,8).' '.
                  substr($unformatted_curr_time,8,2).':'.
                  substr($unformatted_curr_time,10,2).':'.
                  substr($unformatted_curr_time,12,2));
    $in_seconds = strtotime(substr($posted_date,0,8).' '.
                  substr($posted_date,8,2).':'.
                  substr($posted_date,10,2).':'.
                  substr($posted_date,12,2));
    $diff = $curr_time-$in_seconds;
    $months = floor($diff/2419200);
    $diff -= $months*2419200;
    $weeks = floor($diff/604800);
    $diff -= $weeks*604800;
    $days = floor($diff/86400);
    $diff -= $days*86400;
    $hours = floor($diff/3600);
    $diff -= $hours*3600;
    $minutes = floor($diff/60);
    $diff -= $minutes*60;
    $seconds = $diff;

    if ($days>2 or $weeks>0 or $months>0 or $curr_time == $in_seconds) {
		// over 2 days old, just show date n time regularly
		return "out of range";
    } else {
        if ($weeks>0) {
            // weeks and days
            $relative_date .= ($relative_date?', ':'').$weeks.' week'.($weeks>1?'s':'');
            $relative_date .= $days>0?($relative_date?', ':'').$days.' day'.($days>1?'s':''):'';
        } elseif ($days>0) {
            // days and hours
            $relative_date .= ($relative_date?', ':'').$days.' day'.($days>1?'s':'');
            $relative_date .= $hours>0?($relative_date?', ':'').$hours.' hour'.($hours>1?'s':''):'';
        } elseif ($hours>0) {
            // hours and minutes
            $relative_date .= ($relative_date?', ':'').$hours.' hour'.($hours>1?'s':'');
            $relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':''):'';
        } elseif ($minutes>0) {
            // minutes only
            $relative_date .= ($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':'');
        } else {
            // seconds only
            $relative_date .= ($relative_date?', ':'').$seconds.' second'.($seconds>1?'s':'');
        }
    }
    // show relative date and add proper verbiage
    return $relative_date.' ago';
}

function BBCodeParser($message) {

$message = strip_tags($message, '<br>');
//regexp doesn't want to work with newlines, so this is a quick hack
//to make sure quotes are parsed correctly
$message = str_replace("\n","%newline%",$message);

$bbreplace = array (	'/(\[[Bb]\])(.+?)(\[\/[Bb]\])/',
						'/(\[[Ii]\])(.+?)(\[\/[Ii]\])/',
						'/(\[[Uu]\])(.+?)(\[\/[Uu]\])/',
						'/(\[img\])(.+?)(\[\/img\])/',
						'/(\[url=http:\/\/)(.+?)(\])(.+?)(\[\/url\])/',
						'/(\[quote\])(.+?)(\[\/quote\])/',
						'/(\[quote=)(.+?)(\])(.+)(\[\/quote\])/'
);

$bbreplacements = array (	'<strong>\\2</strong>', //not using <b> to comply with accessibility web standards
							'<em>\\2</em>', //not using <i> to comply with accessibility web standards
							'<u>\\2</u>',
							'<img src="\\2" alt="Posted Image">',
							'<a href="http://\\2" target="_blank">\\4</a>',
							'<div class="quote_hd">Quote:</div><div class="quote_box">\\2</div>',
							'<span class="quote_hd">Quote (\\2):</span><div class="quote_box">\\4</div>'
	);

$parsed_message = preg_replace($bbreplace, $bbreplacements, $message);

$quotes_parsed = 0;

//The $quotes_parsed makes sure we don't go into an infinite loop if someone
//really messes up their tags by running this only up to 20 times.
//Why 20? Because it's my lucky number. No, actually, it's because you'd have 
//to be crazy to nest 21 quotes. Pfft.

while((strpos($parsed_message,"[quote")) and (strpos($parsed_message,"[/quote]")) and ($quotes_parsed <= 20)) {
	$parsed_message = preg_replace($bbreplace, $bbreplacements, $parsed_message);
	$quotes_parsed += 1;
}

//now we reverse the regexp hack
$parsed_message = str_replace("%newline%","\n",$parsed_message);

return $parsed_message;

}

?>