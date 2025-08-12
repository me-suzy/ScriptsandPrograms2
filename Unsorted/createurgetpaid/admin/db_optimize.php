<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	$GLOBALS["adminpage"] = "yes";
	
	include "../lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", "Optimize Database");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Optimize Database","You can not access this page."));
	
	$text	= "<B>Optimizing database \"" ._DB_NAME. "\".</B><BR><BR>\n"
			 ."<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
			 ."<TR BGCOLOR=\"#D3D3D3\"><TD><B>Table</B></TD><TD><B>Size</B></TD>"
			 ."<TD><B>Status</B></TD><TD><B>Space Saved</B></TD></TR>";

	$tot_data		= 0;
	$tot_idx		= 0;
	$tot_all		= 0;
	
	$db->Query("SHOW TABLE STATUS FROM " . _DB_NAME);
	
	while($row = $db->NextRow())
	{
		$tot_data		= $row["Data_length"];
		$tot_idx		= $row["Index_length"];
		$total			= $tot_data + $tot_idx;
		$total			= $total / 1024 ;
		$total			= round($total, 3);
		$gain			= $row["Data_free"];
		$gain			= $gain / 1024 ;
		$total_gain		+= $gain;
		$gain			= round($gain,3);
		
		$db->Query("OPTIMIZE TABLE " . $row[0], 2);
		
		if($gain == 0)
 			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD>" . $row[0] . "</TD>"."<TD>" . $total . " Kb"."</TD>"."<TD>Already optimized</TD><TD>0 Kb</TD></TR>\n";
		else
	 		$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD><B>" . $row[0] . "</B></TD>"."<TD><B>" . $total . " Kb"."</B></TD>"."<TD><B>Optimized!</B></TD><TD><B>" . $gain . " Kb</B></TD></TR>\n";
	}
	
	$text	.= "</TABLE><BR><TABLE WIDTH=\"50%\" STYLE=\"border: 1 solid #468ECA;\">\n"
			  ."<TR BGCOLOR=\"#D3D3D3\"><TD><B>Optimization Results</B></TD></TR>\n"
			  ."<TR BGCOLOR=\"#EAEAEA\"><TD>";
	
	$total_gain = round($total_gain, 3);
	
	$text	.= "Total Space Saved: " . $total_gain . " Kb<BR>\n";
	
	$db->Query("INSERT INTO optimize_gain (gain) VALUES ('$total_gain')");
	
	$db->Query("SELECT * FROM optimize_gain");
	
	while($row = $db->NextRow())
	{
		$histo	+= $row[0];
		$cpt	+= 1;
	}
	
	$text	.= "You have executed this script: $cpt times<BR>";
	$text	.= "$histo Kb saved since its first execution!</TD></TR></TABLE>";
	
	$main->printText($text);

?>