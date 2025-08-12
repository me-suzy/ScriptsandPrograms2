<?
function states($which = "none")
   {
      $states = array("Alabama" , "Arizona" , "Arkansas" , "California" , "Colorado" , "Connecticut" , "Delaware" , "District of Columbia" , "Florida" , "Georgia" , "Hawaii" , "Idaho" , "Illinois" , "Indiana" , "Iowa" , "Kansas" , "Kentucky" , "Louisiana" , "Maine" , "Massachusetts" , "Maryland" , "Michigan" , "Minnesota" , "Mississippi" , "Missouri" , "Montana" , "Nebraska" , "Nevada" , "New Hampshire" , "New Jersey" , "New Mexico" , "New York" , "North Carolina" , "North Dakota" , "Ohio" , "Oklahoma" , "Oregon" , "Pennsylvania" , "Rhode Island" , "South Carolina" , "South Dakota" , "Tennessee" , "Texas" , "Utah" , "Virginia" , "Vermont" , "Washington" , "Wisconsin" , "West Virginia" , "Wyoming");
?>
<select name=state class=box>
        <option value="">Select a state (US Only)</option>
      <?for ($i=0;$i<count($states);$i++) { ?>
        <option value="<?=$states[$i]?>" <?if ($states[$i] == $which) print "selected"?>> <?=$states[$i]?> </option>
      <? }?>
</select>
<?
   }
function countries($which = "none")
   {
      if ($which == "none" || $which == "") { $which = "United States"; }
      $countries = array("United States" , "Afghanistan" , "Albania" , "Algeria" , "American Samoa" , "Albania" , "Algeria" , "American Samoa" , "Andorra" , "Angola" , "Anguilla" , "Antarctica" , "Antigua & Barbuda" , "Argentina" , "Armenia" , "Aruba" , "Australia" , "Austria" , "Azerbaijan" , "Bahamas" , "Bahrain" , "Bangladesh" , "Barbados" , "Belarus" , "Belgium" , "Belize" , "Benin" , "Bermuda" , "Bhutan" , "Bolivia" , "Bosnia & Herzegovina" , "Botswana" , "Bouvet Island" , "Brazil" , "Brunei Darussalam" , "Bulgaria" , "Burkina Faso" , "Burundi" , "Cambodia" , "Cameroon" , "Canada" , "Cape Verde" , "Cayman Islands" , "Central African Rep" , "Chad" , "Chile" , "China" , "Colombia" , "Comoros" , "Congo" , "" , " Cook Islands" , "Costa Rica" , "Cote D\'Ivoire" , "Croatia" , "Cuba" , "Cyprus" , "Czech Republic" , "Denmark" , "Djibouti" , "Dominica" , "Dominican Republic" , "East Timor" , "Ecuador" , "Egypt" , "El Salvador" , "Equatorial Guinea" , "Eritrea" , "Estonia" , "Ethiopia" , "Falkland Islands" , "Faroe Islands" , "Fiji" , "Finland" , "France" , "Gabon" , "Gambia" , "Georgia" , "Germany" , "Ghana" , "Gibraltar" , "Greece" , "Greenland" , "Grenada" , "Guadeloupe" , "Guam" , "Guatemala" , "Guinea" , "Guinea-Bissau" , "Guyana" , "Haiti" , "Vatican" , "Honduras" , "Hong Kong" , "Hungary" , "Iceland" , "India" , "Indonesia" , "Iran" , "Iraq" , "Ireland" , "Israel" , "Italy" , "Jamaica" , "Japan" , "Jordan" , "Kazakhstan" , "Kenya" , "Kiribati" , "Korea, Dem Rep" , "Korea, Republic Of" , "Kuwait" , "Kyrgyzstan" , "Latvia" , "Lebanon" , "Lesotho" , "Liberia" , "Liechtenstein" , "Lithuania" , "Luxembourg" , "Macau" , "Macedonia" , "Madagascar" , "Malawi" , "Malaysia" , "Maldives" , "Mali" , "Malta" , "Marshall Islands" , "Martinique" , "Mauritania" , "Mauritius" , "Mayotte" , "Mexico" , "Micronesia, Fed States" , "Moldova" , "Monaco" , "Mongolia" , "Montserrat" , "Morocco" , "Mozambique" , "Myanmar" , "Namibia" , "Nauru" , "Nepal" , "Netherlands" , "New Caledonia" , "New Zealand" , "Nicaragua" , "Niger" , "Nigeria" , "Niue" , "Norfolk Island" , "Northern Mariana Isles" , "Norway" , "Oman" , "Pakistan" , "Palau" , "Panama" , "Papua New Guinea" , "Paraguay" , "Peru" , "Philippines" , "Pitcairn" , "Poland" , "Portugal" , "Puerto Rico" , "Qatar" , "Reunion" , "Romania" , "Russia" , "Rwanda" , "Samoa" , "San Marino" , "Saudi Arabia" , "Senegal" , "Seychelles" , "Sierra Leona" , "Singapore" , "Slovakia" , "Slovenia" , "Solomon Islands" , "Somalia" , "South Africa" , "Spain" , "Sri Lanka" , "St. Helena" , "Sudan" , "Suriname" , "Swaziland" , "Sweden" , "Switzerland" , "Syrian Arab Republic" , "Taiwan" , "Tajikistan" , "Tanzania" , "Thailand" , "Togo" , "Tokelau" , "Tonga" , "Trinidad & Tobago" , "Tunisia" , "Turkey" , "Turkmenistan" , "Tuvalu" , "Uganda" , "Ukraine" , "United Arab Emirates" , "United Kingdom" , "United States" , "US Minor Outlying Isles" , "Uruguay" , "Uzbekistan" , "Vanuatu" , "Venezuela" , "Viet Nam" , "Virgin Isles (British)" , "Virgin Isles (U.S.)" , "Western Sahara" , "Yemen" , "Yugoslavia" , "Zambia" , "Zimbabwe");
?>
<select name=country class=box>
         <option value="United States">Select a country</option>
         <?for ($i=0;$i<count($countries);$i++) { ?>
            <option value="<?=$countries[$i]?>" <?if ($countries[$i] == $which) print "selected"?>> <?=$countries[$i]?> </option>
         <? }?>
</select>
<?
   }
function get_param($param_name)
   {
        global $HTTP_POST_VARS, $HTTP_GET_VARS;

        $param_value = "";

        if(isset($HTTP_POST_VARS[$param_name])) $param_value = $HTTP_POST_VARS[$param_name];
        else if(isset($HTTP_GET_VARS[$param_name])) $param_value = $HTTP_GET_VARS[$param_name];

        return $param_value;
   }

function get_session($param_name)
   {
       return $_SESSION[$param_name];
   }

function set_session($param_name, $param_value)
   {
        $_SESSION[$param_name] = $param_value;
   }

function is_number($string_value)
{
  if(is_numeric($string_value) || !strlen($string_value))
    return true;
  else
    return false;
}
function convertMonth($getMonth)
   {
      switch($getMonth)
         {
            case "01":
               $fixedMonth = "January";
               break;
            case "02":
               $fixedMonth = "February";
               break;
            case "03":
               $fixedMonth = "March";
               break;
            case "04":
               $fixedMonth = "April";
               break;
            case "05":
               $fixedMonth = "May";
               break;
            case "06":
               $fixedMonth = "June";
               break;
            case "07":
               $fixedMonth = "July";
               break;
            case "08":
               $fixedMonth = "August";
               break;
            case "09":
               $fixedMonth = "September";
               break;
            case "10":
               $fixedMonth = "October";
               break;
            case "11":
               $fixedMonth = "November";
               break;
            case "12":
               $fixedMonth = "December";
               break;
         }
      return $fixedMonth;
   }

function convertDate($format, $source)
   {
      $getYear  = substr($source, 0, 4);
      $getMonth = substr($source, 4, 2);
      $getDate  = substr($source, 6, 2);
      $getHour  = substr($source, 8, 2);
      $getMin   = substr($source, 10, 2);
      $getSec   = substr($source, 12, 2);

      // Algorithm for ending detection
      $strCount = (string)$getDate;
      $fSplit    = "";
      for ($i=0;$i<strlen($strCount);$i++) // The workaround for the regEx failure
         {
            if ($i == 0)
                $fSplit .= "|".substr($strCount, $i, $i+1);
            else $fSplit .= "|".substr($strCount, $i, $i);
         }
      $test = split("\|",$fSplit);
      $last2Num = $test[count($test)-2];
      $lastNum  = $test[count($test)-1];
      if ($last2Num == "1" && $lastNum == "1")      {$end = "th";}
      else if ($last2Num == "1" && $lastNum == "2") {$end = "th";}
      else if ($last2Num == "1" && $lastNum == "3") {$end = "th";}
      else if ($lastNum == "1") {$end = "st";}
      else if ($lastNum == "2") {$end = "nd";}
      else if ($lastNum == "3") {$end = "rd";}
      else if ($lastNum == "4") {$end = "th";}
      else if ($lastNum == "5") {$end = "th";}
      else if ($lastNum == "6") {$end = "th";}
      else if ($lastNum == "7") {$end = "th";}
      else if ($lastNum == "8") {$end = "th";}
      else if ($lastNum == "9") {$end = "th";}
      else if ($lastNum == "0") {$end = "th";}
      // End of Algorithm

      // Returning Formated Dates
      $desiredFormat = ereg_replace("-y", $getYear, $format);
      $desiredFormat = ereg_replace("-Y", substr($getYear,2,2), $desiredFormat);
      $desiredFormat = ereg_replace("-m", $getMonth, $desiredFormat);
      $desiredFormat = ereg_replace("-M", convertMonth($getMonth), $desiredFormat);
      $desiredFormat = ereg_replace("-d", $getDate, $desiredFormat);
      $desiredFormat = ereg_replace("-D", ((substr($getDate,0,1) == "0")?substr($getDate,1,1):$getDate).$end, $desiredFormat);

      $desiredFormat = ereg_replace("-h", $getHour, $desiredFormat);
      $desiredFormat = ereg_replace("-t", $getMin, $desiredFormat);
      $desiredFormat = ereg_replace("-s", $getSec, $desiredFormat);

      return $desiredFormat;
   }

//----------------- Mail Function ---------------------------------------------//

function headers()
   {
      global $_Config;
      $headers .= "From: ".$_Config["masterRef"]." <".$_Config["masterEmail"].">\n";
      $headers .= "X-Sender: <".$_Config["masterEmail"].">\n";
      $headers .= "X-Mailer: PHP\n"; // mailer
      $headers .= "Return-Path: <".$_Config["errorEmail"].">\n";  // Return path for errors
      $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

      return $headers;
   }

function mailTO($email, $subjecta, $bodyto)
   {
      @mail($email, $subjecta, $bodyto, headers());
   }
function getIP()
   {
     /*
      if (getenv(HTTP_X_FORWARDED_FOR))
         {
            $ip=getenv(HTTP_X_FORWARDED_FOR);
         if (eregi(",", $ip))
            {
               $temp = split(", ", $ip);
               $ip   = $temp[0];
            }
         }
      else
         {
            $ip=getenv(REMOTE_ADDR);
         }
     */
      return $_SERVER["REMOTE_ADDR"];
   }
function getURL()
   {
      global $PHP_SELF, $QUERY_STRING, $HTTP_HOST;
      $real_url = "http://".$HTTP_HOST.$PHP_SELF.(($QUERY_STRING)?("?".$QUERY_STRING):"");
      return $real_url;
   }
function renderButs($what, $placeIt = "index.php", $width = 500)
   {
      global $str, $_Config, $action, $cmd;
      $per_page = $_Config["paginate"];
      $sets = floor($what/$per_page);
      $ends = $what - $sets*$per_page;
      $numbers = "";
      for ($i=0;$i<$sets;$i++)
         {
            if ($i <= 15)
               {
                  $j = $i + 1;
                  if ($str == ($i*$per_page))
                     $numbers .= "<font color=gray><b>$j</b></font> &nbsp; ";
                  else
                     $numbers .= "<a href='$placeIt&str=".($i*$per_page)."'><b>$j</b></a> &nbsp; ".(($i == 15)?"...":"");
               }
         }
      if ($what <= $per_page)
         {
            $back = "<font color=gray><b>&laquo; Back</b></font>";
            $next = "<font color=gray><b>Next &raquo;</b></font>";
         }
      elseif ($what > $per_page && $str < $per_page)
         {
            $back = "<font color=gray><b>&laquo; Back</b></font>";
            $next = "<a href='$placeIt&str=$per_page' class=text><b>Next &raquo;</b></a>";
         }
      elseif ($what > $per_page && $str >= $per_page)
         {
            $setB = $str - $per_page;
            $setN = $str + $per_page;
            if ($what <= $setN)
               {
                  $back = "<a href='$placeIt&str=$setB' class=text><b>&laquo; Back</b></a>";
                  $next = "<font color=gray><b>Next &raquo;</b></font>";
               }
            else
               {
                  $back = "<a href='$placeIt&str=$setB' class=text><b>&laquo; Back</b></a>";
                  $next = "<a href='$placeIt&str=$setN' class=text><b>Next &raquo;</b></a>";
               }
         }
?>
<table width=<?=$width?> cellspacing=3 cellpadding=0 align=center>
<tr>
   <td align=left class=text>&nbsp;<?=$back?></td>
   <td align=center class=text><?=$numbers?></td>
   <td align=right class=text><?=$next?>&nbsp;</td>
</tr>
</table>
<br>
<?
   }
function sendToHost($host, $method, $path, $data, $protocol="1.0", $withheaders=false)
   {
      if (empty($method))
         {
            $method = 'GET';
         }
      $method = strtoupper($method);
      $fp     = fsockopen($host,80);
      if ($method == 'GET')
         {
            $path .= '?'.$data;
         }

      fputs($fp, "$method $path HTTP/".$protocol."\r\n");
      fputs($fp, "Host: $host\r\n");
      fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
      fputs($fp, "Content-length: " . strlen($data) . "\r\n");
      fputs($fp, "Connection: close\r\n\r\n");
      if ($method == 'POST')
         {
            fputs($fp, $data);
         }
      while (!feof($fp))
         {
            $buf .= fgets($fp,1024);
         }
      if ($withheaders)
         {
            $value = $buf;
         }
      else
         {
            $buffer = split("\n", $buf);
            $contra = false;
            for ($i=0;$i<count($buffer);$i++)
               {
                  if (preg_match("/<(.*?)>/i", $buffer[$i]))	$contra = true;
                  if ($contra)								$value .= $buffer[$i];
               }
         }
      fclose($fp);
      return $value;
   }

function loadTrial()
   {
      global $_PHPLIB;
      if ($_PHPLIB["trial"])
         {
            $resource = $_PHPLIB["maindir"]."license.key";
            if (file_exists($resource))
               {
                  include($resource);

                  if ($_SERIAL_CG < time())
                     {
                        print "<div align=center><font face=Verdana size=4 color=darkred><b>Your license has expired!</b></font></div><br>";
                        print "<center><div style='width:700px;font-family:Verdana;font-size:12px'><p align=justify>";
                        print "&nbsp;&nbsp;Dear Client, the license has expired. This means you could no longer use the software unless you update
                             your license with a new key. If this is your last instalment on your payment plan please contact us for a trial license
                             removal key, if you are using the trial version of the software this is the time when you could decide whether it satisfied
                             your needs and you want to purchase it or leave it as it is now.<br><br>
                             &nbsp;&nbsp;Thank you for using Infinity Interactive's software and we hope you enjoy it as much as we did creating it
                             for you<br><br>
                             <b>The Infinity Interactive Staff</b><br>
                             <a href='http://iinteractive.host.sk'>http://iinteractive.host.sk</a>";
                        print "</div></center>";
                        exit;
                     }
               }
            else
               {
                  print "<div align=center><font face=Verdana size=4 color=darkred><b>Your license has been removed!</b></font></div><br>";
                  print "<center><div style='width:700px;font-family:Verdana;font-size:12px'><p align=justify>";
                  print "&nbsp;&nbsp;Dear Client, the license key we have sent you is missing from the main directory of the software. Because this is
                       a trial version of the software you are required to keep the license key (licence.key) in the main directory until
                       your trial version is converted to deluxe one after you have paid the software in full or purchased its full verion.
                       If you have not touched the license file or it is corrupt and you continue to get this message, please contact us at
                       infinity@developer.bg so we could resolve the problem.<br><br>
                       &nbsp;&nbsp;Thank you for using Infinity Interactive's software and we hope you enjoy it as much as we did creating it
                       for you<br><br>
                       <b>The Infinity Interactive Staff</b><br>
                       <a href='http://iinteractive.host.sk'>http://iinteractive.host.sk</a>";
                  print "</div></center>";
                  exit;
               }
         }
   }
function encodeIt($what) // Encryption Key Function
   {
      $add   = "0".strlen($what)."0";
      $chars = "";
      for ($i=1; $i<=strlen($what); $i++)
         {
            $s      = ($i == 0) ? 0 : ($i - 1);
            $chars .= ord(substr($what , $s, $i)).$add;
            $s      = 0;
         }
      return $chars;
   }
?>
