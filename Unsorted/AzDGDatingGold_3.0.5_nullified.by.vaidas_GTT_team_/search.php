<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";

$t->set_file(
    array(
"error"=>"templates/".$template_name."/error.html",
"search_form"=>"templates/".$template_name."/search_form.html",
"search_results"=>"templates/".$template_name."/search_results.html",
"search_form_city"=>"templates/".$template_name."/search_form_city.html"
)
);


if ($page == search) {

if (!$t_step) {$t_step = 0;}
if (!$from) {$from = 0;}

//$t_step=10;
$fromage = $toage = "";
if ($agefrom != "") $fromage = " AND age >= '".$agefrom."'";
if ($ageto != "") $toage = " AND age <= '".$ageto."'";
if ($photos == "on")
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." AND pic != '' order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." AND pic != ''";
}
else
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage;
}
     $result = mysql_query($sql)  or die(mysql_error());
     if (mysql_fetch_array($result) == 0)
     {
     $t->set_var("ERROR", W_DONT_FIND);
     $t->pparse("error");
     include "templates/footer.php";
     die;
     }
     else
     {
     $result = mysql_query($sql)  or die(mysql_error());
     $tquery = mysql_query($tsql)  or die(mysql_error());
     $trows = mysql_fetch_array($tquery);
     $count = $trows[total];

     $t->set_var("LANGUAGE", $l);
     $t->set_var("W_SEARCH_RESULTS", W_SEARCH_RESULTS);
     $t->set_var("COUNT", $count);
     $t->set_var("W_USERNAME", W_USERNAME);
     $t->set_var("W_CATEGORY", W_CATEGORY);
     $t->set_var("W_COUNTRY", W_COUNTRY);
     $t->set_var("W_CITY", W_CITY);
     $t->set_var("W_AGE", W_AGE);
     $t->set_var("W_PHOTO", W_PHOTO);
     $t->set_var("W_DATE", W_DATE);


     $cc = 0;
     while ($i = mysql_fetch_array($result)) {
     if ($i[pic] == "") $t->set_var("PICAV", W_NONE);
     else $t->set_var("PICAV", "<a href=view.php?l=".$l."&id=".$i[id].">".W_YES."</a>");
     if ($cc == 0) {
     $t->set_var("COLOR", $color1);
     $cc = 1;
     }
     else
     {
     $t->set_var("COLOR", $color2);
     $cc = 0;
     }
     $data=date("d/m/Y", $i[imgtime] + $date_diff*60*60);
     $t->set_var("ID", $i[id]);
     $t->set_var("USER", $i[user]);
     $t->set_var("CATEGORY", $langgender[$i[gender]]." ".$langpurposes[$i[purposes]]);
     $t->set_var("COUNTRY", $i[country]);
     $t->set_var("CITY", $i[city]);
     $t->set_var("AGE", $i[age]);
     $t->set_var("DATA", $data);
     $t->parse("results_cycle");
     }
// Page generating
////////////////////////////////
if ($t_step < $count)
{
$t->set_var("W_PAGE", W_PAGE);
$mesdisp = $t_step;

	$max = $count;
	$from = ($from > $count) ? $count : $from;
	$from = ( floor( $from / $mesdisp ) ) * $mesdisp;

		if (($cpage % 2) == 1)	//1,3,5,...
			$pc = (int)(($cpage - 1) / 2);
		else
			$pc = (int)($cpage / 2);	

		if ($from > $mesdisp * $pc)	
			$str.= "<a href=\"?l=".$l."&page=search&from=0&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">1</a> ";

		if ($from > $mesdisp * ($pc + 1))
			$str.= "<B> . . . </B>";

		for ($nCont=$pc; $nCont >= 1; $nCont--)	// 1 & 2 before
			if ($from >= $mesdisp * $nCont) {
				$tmpStart = $from - $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"?l=".$l."&page=search&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		$tmpPage = $from / $mesdisp + 1;	// page to show
		$str.= " [<B>$tmpPage</B>] ";

		$tmpMaxPages = (int)(($max - 1) / $mesdisp) * $mesdisp;	// 1 & 2 after
		for ($nCont=1; $nCont <= $pc; $nCont++)
			if ($from + $mesdisp * $nCont <= $tmpMaxPages) {
				$tmpStart = $from + $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"?l=".$l."&page=search&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		if ($from + $mesdisp * ($pc + 1) < $tmpMaxPages)	
			$str.= "<B> . . . </B>";

		if ($from + $mesdisp * $pc < $tmpMaxPages)	{ 
			$tmpPage = $tmpMaxPages / $mesdisp + 1;
			$str.= "<a href=\"?l=".$l."&page=search&from=".$tmpMaxPages."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&photos=".$photos."\">".$tmpPage."</a> ";
		}
$t->set_var("LINKS", $str);
$t->set_var("TABLE_END", "</table>");
$t->parse("if_links");
}
else
{
$t->set_var("TABLE_END", "</table>");
}
// end page generating
$t->pparse("search_results");

}


} 
else if ( $page == "csearch")
{

// Search by city
/////////////////////////

if ($action == "csearch")
{
if (!$t_step) {$t_step = 0;}
if (!$from) {$from = 0;}

//$t_step=10;
$fromage = $toage = "";
if ($agefrom != "") $fromage = " AND age >= '".$agefrom."'";
if ($ageto != "") $toage = " AND age <= '".$ageto."'";
if ($photos == "on")
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' and city LIKE '%".$city."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." AND pic != '' order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' and city LIKE '%".$city."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." AND pic != ''";
}
else
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' and city LIKE '%".$city."%'  AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' and city LIKE '%".$city."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage;
}
$result = mysql_query($sql)  or die(mysql_error());
if (mysql_fetch_array($result) == 0)
{
     $t->set_var("ERROR", W_DONT_FIND);
     $t->pparse("error");
     include "templates/footer.php";
     die;
}
else
{
$result = mysql_query($sql)  or die(mysql_error());
$tquery = mysql_query($tsql)  or die(mysql_error());
$trows = mysql_fetch_array($tquery);
$count = $trows[total];


     $t->set_var("LANGUAGE", $l);
     $t->set_var("W_SEARCH_RESULTS", W_SEARCH_RESULTS);
     $t->set_var("COUNT", $count);
     $t->set_var("W_USERNAME", W_USERNAME);
     $t->set_var("W_CATEGORY", W_CATEGORY);
     $t->set_var("W_COUNTRY", W_COUNTRY);
     $t->set_var("W_CITY", W_CITY);
     $t->set_var("W_AGE", W_AGE);
     $t->set_var("W_PHOTO", W_PHOTO);
     $t->set_var("W_DATE", W_DATE);


     $cc = 0;
     while ($i = mysql_fetch_array($result)) {
     if ($i[pic] == "") $t->set_var("PICAV", W_NONE);
     else $t->set_var("PICAV", "<a href=view.php?l=".$l."&id=".$i[id].">".W_YES."</a>");
     if ($cc == 0) {
     $t->set_var("COLOR", $color1);
     $cc = 1;
     }
     else
     {
     $t->set_var("COLOR", $color2);
     $cc = 0;
     }
     $data=date("d/m/Y", $i[imgtime] + $date_diff*60*60);
     $t->set_var("ID", $i[id]);
     $t->set_var("USER", $i[user]);
     $t->set_var("CATEGORY", $langgender[$i[gender]]." ".$langpurposes[$i[purposes]]);
     $t->set_var("COUNTRY", $i[country]);
     $t->set_var("CITY", $i[city]);
     $t->set_var("AGE", $i[age]);
     $t->set_var("DATA", $data);
     $t->parse("results_cycle");
     }

// Page generating
////////////////////////////////
if ($t_step < $count)
{
$t->set_var("W_PAGE", W_PAGE);
$mesdisp = $t_step;

	$max = $count;
	$from = ($from > $count) ? $count : $from;
	$from = ( floor( $from / $mesdisp ) ) * $mesdisp;

		if (($cpage % 2) == 1)	//1,3,5,...
			$pc = (int)(($cpage - 1) / 2);
		else
			$pc = (int)($cpage / 2);	

		if ($from > $mesdisp * $pc)	
			$str.= "<a href=\"?l=".$l."&page=csearch&action=csearch&city=".$city."&from=0&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">1</a> ";

		if ($from > $mesdisp * ($pc + 1))
			$str.= "<B> . . . </B>";

		for ($nCont=$pc; $nCont >= 1; $nCont--)	// 1 & 2 before
			if ($from >= $mesdisp * $nCont) {
				$tmpStart = $from - $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"?l=".$l."&page=csearch&action=csearch&city=".$city."&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		$tmpPage = $from / $mesdisp + 1;	// page to show
		$str.= " [<B>$tmpPage</B>] ";

		$tmpMaxPages = (int)(($max - 1) / $mesdisp) * $mesdisp;	// 1 & 2 after
		for ($nCont=1; $nCont <= $pc; $nCont++)
			if ($from + $mesdisp * $nCont <= $tmpMaxPages) {
				$tmpStart = $from + $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"?l=".$l."&page=csearch&action=csearch&city=".$city."&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		if ($from + $mesdisp * ($pc + 1) < $tmpMaxPages)	
			$str.= "<B> . . . </B>";

		if ($from + $mesdisp * $pc < $tmpMaxPages)	{ 
			$tmpPage = $tmpMaxPages / $mesdisp + 1;
			$str.= "<a href=\"?l=".$l."&page=csearch&action=csearch&city=".$city."&from=".$tmpMaxPages."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">".$tmpPage."</a> ";
		}
$t->set_var("LINKS", $str);
$t->set_var("TABLE_END", "</table>");
$t->parse("if_links");
}
else
{
$t->set_var("TABLE_END", "</table>");
}
// end page generating
$t->pparse("search_results");
}
}
else
{
// country search
if (empty($country) || $country == "") {
     $t->set_var("ERROR", W_BADCOUNTRY);
     $t->pparse("error");
     include "templates/footer.php";
     die;
}
$sql = "SELECT city FROM ".$mysql_table." where country LIKE '".$country."' order by city ASC";
$result = mysql_query($sql) or die(mysql_error());

while ($i = mysql_fetch_array($result)) {
$newcity = $i[city];
if ($oldcity != $newcity)
{
$t->set_var("CITY", $i[city]);
$t->parse("city_cycle");
$oldcity = $newcity;
}
}
$p = 1;
while ($langpurposes[$p]) 
{
$t->set_var("CAT_NUM", $p);
$t->set_var("CATEGORIES", $langpurposes[$p]);
$t->parse("category2_cycle");
$p++;
}
for ($i=$age_s;$i<=$age_b;$i+=$age_between) {
$t->set_var("AGE", $i);
$t->parse("age_cycle2");
$t->parse("age_cycle3");
}

$t->set_var("W_AGE", W_AGE);
$t->set_var("W_FROM", W_FROM);
$t->set_var("W_TO", W_TO);

$t->set_var("LANGUAGE", $l);
$t->set_var("COUNTRY", $country);
$t->set_var("W_SEARCH", W_SEARCH);
$t->set_var("W_CITY", W_CITY);
$t->set_var("W_NO_IMPORTANT", W_NO_IMPORTANT);
$t->set_var("W_GENDER", W_GENDER);

$t->set_var("GENDER1", $langgender[1]);
$t->set_var("GENDER2", $langgender[2]);
$t->set_var("W_CATEGORY", W_CATEGORY);
$t->set_var("W_RESULTS", W_RESULTS);
$t->set_var("W_RESULTS_", W_RESULTS_);
$t->set_var("W_ONLY_", W_ONLY_);
$t->pparse("search_form_city");
}
} else {

$t->set_var("LANGUAGE", $l);
$t->set_var("W_SEARCH", W_SEARCH);
$t->set_var("W_COUNTRY", W_COUNTRY);
$t->set_var("W_NO_IMPORTANT", W_NO_IMPORTANT);
$t->set_var("W_GENDER", W_GENDER);

$t->set_var("GENDER1", $langgender[1]);
$t->set_var("GENDER2", $langgender[2]);
$t->set_var("W_CATEGORY", W_CATEGORY);
$p = 1;
while ($langpurposes[$p]) 
{
$t->set_var("CAT_NUM", $p);
$t->set_var("CATEGORIES", $langpurposes[$p]);
$t->parse("category_cycle");
$p++;
}
for ($i=$age_s;$i<=$age_b;$i+=$age_between) {
$t->set_var("AGE", $i);
$t->parse("age_cycle");
$t->parse("age_cycle1");
}

$t->set_var("W_AGE", W_AGE);
$t->set_var("W_FROM", W_FROM);
$t->set_var("W_TO", W_TO);
$t->set_var("W_RESULTS", W_RESULTS);
$t->set_var("W_RESULTS_", W_RESULTS_);
$t->set_var("W_ONLY_", W_ONLY_);
$t->set_var("W_SEARCH_C", W_SEARCH_C);
$t->set_var("W_SELECT", W_SELECT);
$t->pparse("search_form");
}
include "templates/footer.php";
?>