<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //INSTALL FUNCTIONS\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// construct admin header...
function iHeader($title,$onLoad = "",$meta = "") {
	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");
	print("<html>\n");
	print("<head>\n");
	print("<title> ".$title." </title>\n");
	print("<link rel=\"stylesheet\" href=\"./../admin/content_style.css\" type=\"text/css\" />\n");
	print("<style type=\"text/css\"> p, form, table {width: 90%; margin-left: auto; margin-right: auto;} </style>\n");
	print("</head>\n");
	print("<body>\n\n\n");
}

function iFooter() {
	print("\n\n\n<span class=\"footer\"><a href=\"http://www.webtrickscentral.com\" target=\"_top\">Copyright ©, WebTricksCentral.com</a></span>");
	print("\n\n\n</body>");
	print("\n</html>\n\n");
}

function iTitle($text) {
	print("<h1>".$text."</h1>");
}

function iForm($step,$action = "install.php") {
	print("<form method=\"get\" action=\"".$action."\">\n");
	print("\t<input type=\"hidden\" name=\"step\" value=\"".$step."\" />\n");
	print("\t<input type=\"submit\" class=\"button\" value=\"Next\" />\n");
	print("</form>\n");
}

function iTable($class,$form_name,$submit_id,$make_form = 0) {

	if($make_form) {
		print(stripslashes("\n<form method=\"post\" action=\"\" name=\"".$form_name."\" style=\"margin: 0px;\">\n<br />"));
		print("<input type=\"hidden\" name=\"".$form_name."[set_form]\" value=\"1\" />\n\n");
	}

	print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"".$class."\">\n");
}

function iTableEnd($form = 0) {
	print("\n\n</table>\n\n");
	
	if($form) {
		print("\n\n</form>\n\n");
	}
}

function iHeaderT($text,$colspan) {
	print("\t<tr>\n");
	print("\t\t<td class=\"header\" colspan=\"".$colspan."\">".$text."</td>\n");
	print("\t</tr>\n\n");
}

function iFooterT($colspan,$submit_id,$text = "") {
	global $submitbg;

	print("\t<tr>\n");
	print("\t\t<td class=\"footer\" colspan=\"".$colspan."\">".$text."<pre><button type=\"submit\" id=\"".$submit_id."\" ".$submitbg.">Submit</button>  <button type=\"reset\" ".$submitbg.">Reset</button></pre></td>\n");
	print("\t</tr>\n\n");
}

// start the "construct_text"
function iText($counter,$title,$desc,$array,$key,$value = "",$bottom = 0) {
	// get name..
	$name = stripslashes("$array\[$key\]");

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}

	if($counter) {
		$color = 1;
	} else {
		$color = 2;
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<strong>".$title."</strong> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");
	print("\t\t\t<input type=\"text\" class=\"text\" style=\"width: 90%;\" name=\"".$name."\" value=\"".$value."\" ".$check."/>\n");
	print("\t\t</td>\n");

	print("</tr>\n\n");
}

?>