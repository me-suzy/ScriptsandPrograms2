<h2>Sponsors<h2>
<?php
	 				require('cfg.php');
	 						$sql = 'SELECT * 
							FROM ' .$db_prefix. 'spons_cat 
							ORDER BY spons_cat DESC';
					
					$sql = mysql_query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
					 
						while ($r=mysql_fetch_array($sql))
						{
						  $spons_cat = $r["spons_cat"];
						  $spons_type = $r["spons_type"];
						  
						  echo"<h4>$spons_type</h4>\n";
						  $sq = "SELECT id,spons_cat, spons_name 
							  	FROM " .$db_prefix. "spons 
								WHERE spons_cat = '".$spons_cat."'
								ORDER BY id DESC";
					
						  $sq = mysql_query($sq) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
					 	  echo"<ul>\n";
					 	  while ($read=mysql_fetch_array($sq))
						  {
    						  $id = $read["id"];
    						  $spons_cat = $read["spons_cat"];
    						  $spons_name = $read["spons_name"];
    						  
    						  echo"$spons_name <br/>\n";
						  }
						  echo"</ul>\n";
						}
?>