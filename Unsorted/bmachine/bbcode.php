<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

// BB CODE PARSER FOR bMachine


function BBCode($Text) {
	if(!trim($Text)) { return; }

      // Set up the parameters for a URL search string
      $URLSearchString = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
      // Set up the parameters for a MAIL search string
      $MAILSearchString = $URLSearchString . " a-zA-Z0-9\.@";

      // Perform URL Search
      $Text = preg_replace("/\[url\]([$URLSearchString]*)\[\/url\]/", '<a href="$1" target="_blank">$1</a>', $Text);
      $Text = preg_replace("(\[url\=([$URLSearchString]*)\](.+?)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text);

      $Text = preg_replace("/\[a\]([$URLSearchString]*)\[\/a\]/", '<a href="$1" target="_blank">$1</a>', $Text);
      $Text = preg_replace("(\[a\=([$URLSearchString]*)\](.+?)\[/a\])", '<a href="$1" target="_blank">$2</a>', $Text);


      // Perform MAIL Search
      $Text = preg_replace("(\[mail\]([$MAILSearchString]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $Text);
      $Text = preg_replace("/\[mail\=([$MAILSearchString]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $Text);
			
      // Check for bold text
      $Text = str_replace("[b]","<b>",$Text);
      $Text = str_replace("[B]","<b>",$Text);
      $Text = str_replace("[/b]","</b>",$Text);
      $Text = str_replace("[/B]","</b>",$Text);


      // Check for Italics text
      $Text = str_replace("[i]","<i>",$Text);
      $Text = str_replace("[I]","<i>",$Text);
      $Text = str_replace("[/i]","</i>",$Text);
      $Text = str_replace("[/I]","</i>",$Text);

      // Check for Underline text
      $Text = str_replace("[u]","<u>",$Text);
      $Text = str_replace("[U]","<u>",$Text);
      $Text = str_replace("[/u]","</u>",$Text);
      $Text = str_replace("[/U]","</u>",$Text);

      // Check for strike-through text
      $Text = str_replace("[s]","<strike>",$Text);
      $Text = str_replace("[S]","<strike>",$Text);
      $Text = str_replace("[/s]","</strike>",$Text);
      $Text = str_replace("[/S]","</strike>",$Text);

      // Check for colored text
      $Text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<font color=\"$1\">$2</font>",$Text);

      // Check for sized text
      $Text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<font size=\"$1\">$2</font>",$Text);

      // Check for font change text
      $Text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<font face=\"$1\">$2</font>",$Text);

      // Declare the format for [code] layout
      $CodeLayout = '<table width="90%" cellpadding="2" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
      <td class="quotecodeheader"> Code:</td>
        </tr>
        <tr>
      <td class="codebody">$1</td>
        </tr>
   </table>';
      // Check for [code] text
      $Text = preg_replace("/\[code\](.+?)\[\/code\]/is","$CodeLayout", $Text);

      // Declare the format for [quote] layout
      $QuoteLayout = '<table width="90%" cellpadding="2" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
      <td class="quotecodeheader"> Quote:</td>
        </tr>
        <tr>
      <td class="quotebody">$1</td>
        </tr>
   </table>';

      // Check for [code] text
      $Text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","$QuoteLayout", $Text);

      // Images
      // [img]pathtoimage[/img]
      $Text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1">', $Text);

      // [img=width * height]image source[/img]
	  // eg: [img=420*60]http://site.com/a.gif[/img]

      $Text = preg_replace("/\[img\=([0-9]*)\*([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $Text);

			
	  return $Text;
		}

?>