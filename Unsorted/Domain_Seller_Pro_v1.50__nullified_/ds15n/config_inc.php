<?
//////////////////////////////////////////////////////////////////////////////
// Program Name         : Domain Seller Pro                                 //
// Release Version      : 1.5.0                                             //
// Program Author       : Ronald James                                      //
// Supplied by          : Scoons [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                       //
//////////////////////////////////////////////////////////////////////////////
// COPYRIGHT NOTICE                                                         //
// (c) 2002 Ronald James    All Rights Reserved.                            //
// Distributed under the licencing agreement located in wtn_release.nfo     //
//////////////////////////////////////////////////////////////////////////////
// EDIT THE MYSQL DETAILS HERE
$mysqlhost="localhost";     // host, usually dont need changing 

$mysqluser="USER";            // MySQL database user

$mysqlpass="PASS";                  // MySQL user password

$mysqlbase="DBNAME";             // MySQL database name

$parkeddomains=1; // Read parked domains and jump to domain for sale page?
//$homedomain[]="lookupdomain.com";	// ignore lookup for home domain (optional)
# $marketdomain[]="otherdomain.com";	// add other home domains in the same way

$showoffers=1; // show offers to visitors?
$showsold = 1; // keep sold domains viewable in database (until deleted by admin)


// several useful functions, DO NOT EDIT BELOW THIS LINE
function packx($x){ return $x==""?$x:md5($x*31+11); }

srand ((double)microtime()*1000000);

function myconnect(){ global $mysqlhost,$mysqluser,$mysqlpass,$mysqlbase;

     mysql_connect($mysqlhost,$mysqluser,$mysqlpass);    

     mysql_select_db($mysqlbase);

     return mysql_error();}

function mydisconnect(){ mysql_close(); return mysql_error();}   



$countries_list = Array ( "United States", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan,", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island (Australia)", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Cook Islands (New Zealand)", "Costa Rica", "Cote d`Ivoire", "Croatia (Hrvatska)", "Cyprus", "Czech Republic", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mcdonald Islands", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos, People`s Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent Islands", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovak Republic", "Slovenia", "S.	Georgia and S.Sandwich Is.", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "St Helena", "St Pierre and Miquelon", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Taiwan", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Zambia", "Zimbabwe" );

$states_list = Array ("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia", "Guam", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Puerto Rico", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Virgin Islands", "Washington", "West Virginia", "Wisconsin", "Wyoming");


function get_domains($domain){       
      $email = trim($domain);       
      if($email <> ""){
         $a = split ("\n", $domain);
         return $a;
      }
      else{
         return array();
      }
   }


function valid_domain($domain) { 

return true;
}
 
 function import_domains($list){
      global $buynow,$minimum;
	  $added = 0;
	 if ($buynow < 0) $buynow = 0;
	 if ($minimum < 0) $minimum =0;
     while (list ($key, $val) = each ($list)) {  
        if(trim($val) <> "") {
          $domain = trim($val);                 
		}
		$doms = explode(" ", $domain);
		$dom = $doms[0];
          if(!valid_domain($dom)){
             $notice .= "<font color=blue>[Invalid domain name: $dom]</font><BR>";
             continue;
          }
          
          // check if domain exists          
          $query = "SELECT * FROM dsp_domains WHERE name = '$dom'";
          $result=mysql_query($query);

          if(mysql_num_rows($result) > 0){
                              $notice .= "<font color=red>[Domain $dom is already in database]</font><br>";
							             }
             else{ // domain doesn't already exist

			 // add to database as uncategoried
			 $added++;
			 $dom = strtolower($dom);
			 mysql_query("insert into dsp_domains set category='1', name='$dom', minimum='$minimum',buynow='$buynow', status='0'");
			     }     
		

		}
	$notice .= "Imported $added domain names.<br>";
     return $notice;  
   }
  

function read_domain_file($filename,&$rows) {  
     if(!file_exists($filename)){
        return "Error : $filename not found";
        exit;
      }  
      $fp = fopen("$filename", "r");

      while (!feof($fp)) {
         $buffer .= fgets($fp, 4096);
      }
      fclose($fp);  
      
      $rows = split("\n",$buffer);        
      return "";
   }
  
function valid_email($email) { 

return(eregi("^[0-9a-z_\-\.]+@{1}[0-9a-z_\-\.]+\.{1}[a-z]{2,4}$",$email));
 } 

?>