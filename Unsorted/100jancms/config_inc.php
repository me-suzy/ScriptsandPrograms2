<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/

//database connection
include "config_connection.php";

//load config
	//text encoding
	$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='encoding'";
	$result=mysql_query($query);
	$text_encoding=mysql_result($result,0,"config_value");
	
	//error reporting
	$query="SELECT config_value FROM ".$db_table_prefix."config WHERE config_name='error_level'";
	$result=mysql_query($query);
	$error_level=mysql_result($result,0,"config_value");
 	error_reporting($error_level);
	


// global settings for HTML editor
	$settings = '
		var config = new Object();    // create new config object
		
		config.bodyStyle = \'background-color: white; font-family: "Arial"; font-size: x-small;\';
		config.debug = 0;
		
		// NOTE:  You can remove any of these blocks and use the default config!
		
		config.toolbar = [
		    [\'fontname\'],
		    [\'fontsize\'],
		    [\'bold\',\'italic\',\'underline\',\'separator\'],
		    [\'justifyleft\',\'justifycenter\',\'justifyright\',\'justifyfull\',\'justifynone\',\'separator\'],
		    [\'strikethrough\',\'subscript\',\'superscript\'],
		    [\'linebreak\'],			
		    [\'OrderedList\',\'UnOrderedList\',\'Outdent\',\'Indent\',\'separator\'],
		    [\'forecolor\',\'backcolor\',\'separator\'],
		    [\'InsertTable\',\'HorizontalRule\',\'insertlink\',\'unlink\',\'anchor\',\'separator\',\'100janInsertImage\',\'InsertImage\',\'separator\',\'cut\',\'copy\',\'paste\',\'print\',\'separator\',\'popupeditor\',\'separator\'],
		    [\'htmlmode\'],		    
		];
		
		config.fontnames = {
		    "Arial":           "arial, helvetica, sans-serif",
		    "Courier New":     "courier new, courier, mono",
		    "Georgia":         "Georgia, Times New Roman, Times, Serif",
		    "Tahoma":          "Tahoma, Arial, Helvetica, sans-serif",
		    "Times New Roman": "times new roman, times, serif",
		    "Verdana":         "Verdana, Arial, Helvetica, sans-serif",
		    "impact":          "impact",
		    "WingDings":       "WingDings"
		};
		config.fontsizes = {
		    "1 (8 pt)":  "1",
		    "2 (10 pt)": "2",
		    "3 (12 pt)": "3",
		    "4 (14 pt)": "4",
		    "5 (18 pt)": "5",
		    "6 (24 pt)": "6",
		    "7 (36 pt)": "7"
		  };
		
		config.stylesheet = "http://www.domain.com/sample.css";
		  
		config.fontstyles = [   // make sure classNames are defined in the page the content is being display as well in or they won\'t work!
		  { name: "headline",     className: "headline",  classStyle: "font-family: arial black, arial; font-size: 28px; letter-spacing: -2px;" },
		  { name: "arial red",    className: "headline2", classStyle: "font-family: arial black, arial; font-size: 12px; letter-spacing: -2px; color:red" },
		  { name: "verdana blue", className: "headline4", classStyle: "font-family: verdana; font-size: 18px; letter-spacing: -2px; color:blue" }
		
		// leave classStyle blank if it\'s defined in config.stylesheet (above), like this:
		//  { name: "verdana blue", className: "headline4", classStyle: "" }  
		]; ';



?>