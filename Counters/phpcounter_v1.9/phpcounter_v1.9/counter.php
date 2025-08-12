<?php
//----------------------------------------//
//     		 Php Counter v1.9			  //
//			 Creator: Tivadar 		      //
//             2005 september			  //
//				    				 	  //
//										  //
// E-mail: info@tivadar.tk				  //
// MSN: msn@tivadar.tk                    //
// Web: www.tivadar.tk					  //
//----------------------------------------//

session_start();

	include 'config.php';
	
	echo "<?xml version=\"1.0\" encoding=\"$kod\"?>\n"
	. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
	. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
	. "<head>\n"
	. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$kod\" />\n\n";

	@mysql_query("UPDATE szamlalo SET szam_l=szam_l+1");


	if($stilus == 1) {

	echo "<style type=\"text/css\">\n\n"
	. "td, body {\n"
	. "\n"
	. "		border-top: 1px;\n"
	. "		border-bottom: 1px;\n"
	. "		border-left: 1px;\n"
	. "		border-right: 1px;\n"
	. "		background-color: #EDF8FA;\n"
	. "		border-style: solid;\n"
	. "		border-collapse: collapse;\n"
	. "		border-color: #DEEDF0\n"
	. "		font-family: verdana, Arial, Helvetica, sans-serif;\n"
	. "		font-size: 12px;\n"
	. "		color: #006699;\n"
	. "\n"
	. "}\n"
	. "\n"
	. ".font {\n"
	. "\n"
	. "		font-family:Verdana, Arial, Helvetica, sans-serif;\n"
	. "		font-size:10;\n"
	. "\n"
	. "}\n\n"
	. "</style>\n";
	
	}else{
	
	}

	echo "</head>\n"
	. "<body>\n\n"
	. "<!-- Php Counter v1.9, Creator: Tivadar [ MSN & Mail: msn@tivadar.tk ]-->\n\n";
 
	if ($_SESSION["tivi"] == 0) { 
	
    @mysql_query("UPDATE szamlalo SET szam=szam+1"); 

	$_SESSION["tivi"] = 1;
 
}

			$eredmeny = mysql_query("SELECT szam, szam_l FROM szamlalo");
			$sor = mysql_fetch_array($eredmeny);
			
			echo "<div style=\"text-align: center;\">\n"
			. " <table  cellpadding=\"0\" cellspacing=\"0\" style=\"margin-left: auto; margin-right: auto; text-align: left;\">\n"
			. "  <tr>\n"
			. "   <td>\n"
			. "    <span class=\"font\">$szoveg: </span>\n"
			. "   </td>\n"
			. "   <td>\n"
			. "     <span class=\"font\"><strong>".$sor["szam"]."</strong></span>\n"
			. "   </td>\n"
			. "  </tr>\n"
			. "	 <tr>\n"
			. "   <td>\n"
			. "    <span class=\"font\">$szoveg2: </span>\n"
			. "   </td>\n"
			. "   <td>\n"
			. "     <span class=\"font\"><strong>".$sor["szam_l"]."</strong></span>\n"
			. "   </td>\n"
			. "  </tr>\n"
			. " </table>\n"
			. "</div>\n"
			. "</body>\n"
			. "</html>\n";

?>