<?php
error_reporting(0);
$scriptname="index.php";
$start=$_GET['start'];
$site=$_GET['site'];
$parametru=$_REQUEST["q"];
if (strlen($parametru) >= 1) {
//$newsPage =$consturl+$parametru;
$parametru=urlencode($parametru);
$newsPage = "http://www.google.ro/search?q=$parametru+site:$site&hl=en&lr=&ie=UTF-8&oe=UTF-8&newwindow=1&output=search";
if (strlen($start) > 0) {
	$newsPage = "http://www.google.ro/search?q=$parametru&hl=en&lr=&ie=UTF-8&oe=UTF-8&start=$start&newwindow=1&output=search";
  	}
//verific daca pagina se poate deschide
if(!($open = fopen("$newsPage",r))){
	header("Location: error.php?mesaj=eroare+reveniti+mai+tarziu");
	exit(0);
	}
else{
	//echo "<b> URL FOUND</b>";
	$read = "";
	while (($data = fread ($open, 2097152)) !== "") 
		{
		$read .= $data;
		}
	}
fclose($open);
}
?>
<FORM action="index.php"  >
            <INPUT size="20" name="q" value="<? echo $_GET['q']; ?>">
            <INPUT type=hidden size="20" name="site" value="softpedia.com">
            <INPUT class="button" type="Submit" value="Search Softpedia">
           
          </FORM>
          <FORM action="index.php"  >
            <INPUT size="20" name="q" value="<? echo $_GET['q']; ?>">
             <INPUT type=hidden size="20" name="site" value="tucows.com">
            <INPUT class="button" type="Submit" value="Search Tucows.com">
           
          </FORM>
          <FORM action="index.php"  >
            <INPUT size="20" name="q" value="<? echo $_GET['q']; ?>">
             <INPUT type=hidden size="20" name="site" value="download.com">
            <INPUT class="button" type="Submit" value="Search Download.com">
           
          </FORM>
          
<script type="text/javascript"><!--
google_ad_client = "pub-1493004420728872";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text";
google_ad_channel ="6566472283";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "0000FF";
google_color_url = "0066FF";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>   
          
          
          
          
          
          <?

if (strlen($parametru) >= 1) {
 $valoare="/search/results/";
 $read= str_replace($valoare,$scriptname, $read);
 $valoare1="/search/results.php";
 $read= str_replace($valoare,$scriptname, $read);
 $valoare0="/search";
 $read= str_replace($valoare0,$scriptname, $read);
 $valoare2="Cached";
 $read= str_replace($valoare2,"", $read);
 $valoare3="/images/";
 $read= str_replace($valoare3,"imgmp3/", $read);
 $valoare4=chr(34);
 // $read= str_replace($valoare4,"", $read);
 // $valoare5="=0>";
 // $read= str_replace($valoare5,"=0 >", $read);
  $valoare5="alt=";

  $read= str_replace($valoare7,"", $read);


$val="table";
$matrice= split ($val, $read);
$count=count($matrice);

echo "<table";
if ($count <= 15) {
  for ($i=7; $i<=($count-5); $i++) 
        {
      echo "$matrice[$i]table"; // add appropiate HTML tags here

        }
 } else {
    


for ($i=7; $i<=($count-9); $i++)
    {
       $trimuitval="http:";
       $trimuit= str_replace($trimuitval,"", $matrice[$i]);
         // echo "$matrice[$i]table"; // add appropiate HTML tags here
           if (strlen($trimuit) < strlen($matrice[$i])) {

             $var1="href=\"http://";
             $var2="target=_blank href=\"http://";
              $valfinal=str_replace($var1,$var2, $matrice[$i]);

             





         //echo "$matrice[$i]table"; // add appropiate HTML tags here

          echo "$valfinal";  ?>table<?  // add appropiate HTML tags here
       
           } else {      
           
            echo "$matrice[$i]table";
            } 


    } 

 }
 echo ">";
}

//include("../footer.inc.php");
//include('../contorizare.php');
?>
