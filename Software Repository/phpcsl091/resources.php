<?
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9
* Date:         2005/02/09 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/
?>

<br>
<h1>Sites worth a visit</h1>
<ul>
<?php
// feel free to add to this list
$res = array(
        "PHP Code Snippet Library" =>
			array("The official website of PHP Code Snippet Library." => "http://www.php-csl.com"),
        "PHP Editors"  =>
			array("PHP Editors and IDE review site with lots of other PHP related information." => "http://www.php-editors.com"),
        "PHP Resources" =>
			array("PHP Resource website with lots of PHP related resources." => "http://www.php-resources.org"),
		"PHP Freelancers"  =>
			array("PHP Freelance Jobs website, free to freelancers and project managers." => "http://www.php-freelancers.com"),
        "Perl Freelancers"  =>
			array("Perl Freelance Jobs website, free to freelancers and project managers." => "http://www.perl-freelancers.com"),
		"ASP Freelancers"  =>
			array("ASP Freelance Jobs website, free to freelancers and project managers." => "http://www.asp-freelancers.com"),
		"C/C++ Freelancers"  =>
			array("C/C++ Freelance Jobs website, free to freelancers and project managers." => "http://www.cpp-freelancers.com"),
		"Java Freelancers"  =>
			array("Java Freelance Jobs website, free to freelancers and project managers." => "http://www.java-freelancers.com"),
		"JavaScript Freelancers"  =>
			array("JavaScript Freelance Jobs website, free to freelancers and project managers." => "http://www.javascript-freelancers.com"),
		"JSP Freelancers"  =>
			array("JSP Freelance Jobs website, free to freelancers and project managers." => "http://www.jsp-freelancers.com"),
		"VB Freelancers"  =>
			array("VB Freelance Jobs website, free to freelancers and project managers." => "http://www.vb-freelancers.com"),
		"ColdFusion Freelancers"  =>
			array("ColdFusion Freelance Jobs website, free to freelancers and project managers." => "http://www.cfm-freelancers.com"),	
		"ASP Editors"  =>
			array("ASP Editors/development tool review site." => "http://asp.editorhub.com"),
		"C# (Csharp) Editors"  =>
			array("C# Editors/development tool review site." => "http://csharp.editorhub.com"),
		"C/C++ Editors"  =>
			array("C/C++ Editors/development tool review site." => "http://cpp.editorhub.com"),
		"CSS Editors"  =>
			array("CSS Editors/development tool review site." => "http://css.editorhub.com"),
		"ColdFusion Editors"  =>
			array("ColdFusion Editors/development tool review site." => "http://coldfusion.editorhub.com"),
		"HTML Editors"  =>
			array("HTML Editors/development tool review site." => "http://html.editorhub.com"),
		"Java Editors"  =>
			array("Java Editors/development tool review site." => "http://java.editorhub.com"),
		"JavaScript Editors"  =>
			array("JavaScript Editors/development tool review site." => "http://javascript.editorhub.com"),
		"JSP Editors"  =>
			array("JSP Editors/development tool review site." => "http://jsp.editorhub.com"),
		"Perl Editors"  =>
			array("Perl Editors/development tool review site." => "http://perl.editorhub.com"),
		"Python Editors"  =>
			array("Python Editors/development tool review site." => "http://python.editorhub.com"),
		"SQL Editors"  =>
			array("SQL Editors/development tool review site." => "http://sql.editorhub.com"),
		"VB Editors"  =>
			array("VB Editors/development tool review site." => "http://vb.editorhub.com"),
		"XML Editors"  =>
			array("XML Editors/development tool review site." => "http://vb.editorhub.com")
		);
		
foreach ($res as $t => $d) {
	foreach($d as $des => $url) {
		echo '<li><a target="_blank" href="'.$url.'">'.$t.'</a> - ('.$des.')</li><br><br>';
	}
}
?>
</ul>