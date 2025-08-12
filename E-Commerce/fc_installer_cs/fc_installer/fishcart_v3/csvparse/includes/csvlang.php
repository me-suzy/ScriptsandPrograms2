<?php

$fcz = new FC_SQL;
$fcz->connect();
if(!$fcz->Link_ID){?>
<?php echo fc_text("outofservice"); ?>
<br>
<?php
 exit;
}

$fcg=new FC_SQL;
$fcv=new FC_SQL;
$fm=new FC_SQL;

// essential code from cartid.php

if(empty($zid)){ // get the default zone
 $fm->query("select zoneid from $mastertable");
 $fm->next_record();
 $zid=$fm->f("zoneid");
 $fm->free_result();
}
if(empty($lid)){	// get default language from zone record
 $fm->query("select zonedeflid from $zonetable where zoneid=$zid");
 $fm->next_record();
 $lid=$fm->f("zonedeflid");
 $fm->free_result();
 $lang_iso='';
}
if(empty($lang_iso)){	// get ISO 639/2 code for this language
 $fm->query("select langiso from $langtable where langid=$lid");
 $fm->next_record();
 $lang_iso=$fm->f("langiso");
 $fm->free_result();
}

// from languages.php file

$lang_inc=1;

// include this file after cartid.php, as cartid.php sets $lang_iso
$language_files = array(
	'eng'  =>  'lang_eng.php',
	'ita'  =>  'lang_ita.php',
	'fra'  =>  'lang_fra.php'
);

$language_names = array(
	'eng'  =>  'English',
	'ita'  =>  'Italian',
	'fra'  =>  'French'
);

// function to return the indicated prompt
function fc_text($msg){
	global $fc_prompt;
	return $fc_prompt[$msg];
}

// set the default language if undefined by langtable
if( empty($lang_iso) ){
	$lang_iso='lang_eng.php';
}

if( empty($no_lang_iso) ){
	$lang_file = $droot.'/languages/'.$language_files["$lang_iso"];
	include($lang_file);
}

?>