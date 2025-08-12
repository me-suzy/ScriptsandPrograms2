<html>
<title>eMedia Office CuteFlow Database Update</title>
<body>
<table border="0">
<?php
include("../config/config.inc.php");

            /*---------------------- Connect to Database and Login ------------------------ */

$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);

	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
            echo "Login: <b>CuteFlow DATABASE...</b><br>";
            echo "<b><br>Login successsfull</b><br>";

            // zu erstellende Tabelle
            
				$history = ("cf_circulationhistory");

            $form = ("cf_circulationform");
            $process = ("cf_circulationprocess");
            echo "<br>create table: <b>$history</b><br>";
            
            //copy table cf_circulationform

            $sql_create_copy = "CREATE TABLE $history AS SELECT * FROM $DATABASE_DB.cf_circulationform ";

            if($createResult = mysql_query($sql_create_copy,$nConnection))
            {
           		$sql_set_primary_key = "ALTER TABLE `$history` DROP PRIMARY KEY , ADD PRIMARY KEY ( `nID` ) ";
             		
						if($createResult = mysql_query($sql_set_primary_key,$nConnection))
             		{
	            		echo "<br>PRIMARY KEY erfolgreich gesetzt<br>";
             		}

           		$sql_auto_increment = "ALTER TABLE `$history` CHANGE `nID` `nID` INT( 11 ) DEFAULT '' NOT NULL AUTO_INCREMENT";
           
			    		if($createResult = mysql_query($sql_auto_increment,$nConnection))
             		{
	            		echo "UNIQUE KEY erfolgreich gelöscht<br>";
             		}

            
				//convert the table cf_circulationhistory

            	$sql_del_unique_key = "ALTER TABLE `$history` DROP INDEX `nID`";
            		if($createResult = mysql_query($sql_del_unique_key,$nConnection))
            		{
           		 		echo "UNIQUE KEY erfolgreich gelöscht<br>";
            		}
            
						$sql_create_nRevisionNumber = "ALTER TABLE `$history` ADD `nRevisionNumber` INT( 11 ) DEFAULT '0' NOT NULL AFTER `nID`";
            		if($createResult = mysql_query($sql_create_nRevisionNumber,$nConnection))
            		{
            			echo "Feld: nRevisionNumber erzeugt<br>";
            		}
      	      		else
		   	         {
            			    echo "create_field_ nRevisionNumber: ERROR<br>";
                		}

            	$sql_create_nCirculationFormId = "ALTER TABLE `$history` ADD `nCirculationFormId` INT( 11 ) DEFAULT '0' NOT NULL";
            		if($createResult = mysql_query($sql_create_nCirculationFormId,$nConnection))
            		{
         		   	echo "Feld: nCirculationFormId erzeugt<br>";
      		      }
    			        else
  			          {
  			              echo "create_field_nCirculationFormId: ERROR<br>";
     	             }

       	      $sql_create_nCirculationHistoryId = "ALTER TABLE `$process` ADD `nCirculationHistoryId` INT( 11 ) DEFAULT '0' NOT NULL";
            		if($createResult = mysql_query($sql_create_nCirculationHistoryId,$nConnection))
            		{
            			echo "Feld: nCirculationHistoryId erzeugt<br>";
            		}
            			else
            			{
                			echo "<b>WARNING COLUMN currently exist</b><br>";
                		}

            /*----------------------------- delete unused Columns ------------------------- */

            	$sql_drop_column = "ALTER TABLE $history DROP nSenderId";
             		if($drop_result = mysql_query($sql_drop_column,$nConnection))
				 		{
             			echo "<br>nSenderId erfolgreich gel&ouml;scht";
						}		

            	$sql_drop_column = "ALTER TABLE $history DROP strName";
             		if($drop_result = mysql_query($sql_drop_column,$nConnection))
						{
             			echo "<br>strName erfolgreich gel&ouml;scht";
						}
						
            	$sql_drop_column = "ALTER TABLE $history DROP nMailingListId";
             		if($drop_result = mysql_query($sql_drop_column,$nConnection))
						{
             			echo "<br>nMailingListId erfolgreich gel&ouml;scht";
						}	

            	$sql_drop_column = "ALTER TABLE $history DROP bIsArchived";
             		if($drop_result = mysql_query($sql_drop_column,$nConnection))
						{
             			echo "<br>bIsArchived erfolgreich gel&ouml;scht";
						}	

             	$sql_drop_column = "ALTER TABLE $form DROP dateSending";
             		if($drop_result = mysql_query($sql_drop_column,$nConnection))
						{
             			echo "<br>dateSending from $form erfolgreich gel&ouml;scht";
						}	

            	$sql_drop_column = "ALTER TABLE $form DROP strAdditionalText";
             		if($drop_result = mysql_query($sql_drop_column,$nConnection))
						{
             			echo "<br>strAdditionalText from $form erfolgreich gel&ouml;scht";
						}	

            	$sql_drop_column = "ALTER TABLE $history DROP nEndAction";
            		if($drop_result = mysql_query($sql_drop_column,$nConnection))
						{
             			echo "<br>bnEndAction erfolgreich gel&ouml;scht";
						}	
              
              
            /*------------------------------- Update History ------------------------------ */

		         $Spalte  = "nCirculationFormId";
				   $p_id         = "nID";
               $newConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);

		         $sql_select = "select $p_id,$Spalte from $history";

  				  	$select_result = mysql_query($sql_select,$nConnection);

            	$update_result = mysql_query($sql_update,$nConnection);

            	if($select_result)
            	{
             		while($aktuelle_zeile = mysql_fetch_row($select_result))
             		{
							$spaltenwert = $aktuelle_zeile[1];
		               $spaltenwert_id = $aktuelle_zeile[0];
		               $sql_insert = "Update $history ($Spalte) VALUES($spaltenwert)";
		               $select_id = "select MAX($p_id) as aktuelle_id from $history";
      			         if($select_id_result = mysql_query($select_id,$newConnection))
                        {
               			  $max_id = mysql_fetch_row($select_id_result);
                 			  $update_history = "update $history set $history.nCirculationFormId = $history.nID";
                 					if($result_update_history = mysql_query($update_history,$newConnection))
                 					{
				                  }
             					    else
                   					echo "<br>ERROR Updatestatement $update_history";
			               }
         				    else
                   			echo "<br>ERROR SQLStatement $select_id";
					   }
        
               }
              	 else
	            	echo "<script>alert('ERROR History Update')</script>";

             
             /* --------------------- END Update History ------------------------------- */
             
             
             
             /* --------------------- Update Process ----------------------------------- */

		         $Spalte  = "nCirculationHistoryId";
				   $f_id         = "nCirculationFormId";
            	$newConnection2 = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);

		        	$sql_select = "select $f_id,$Spalte from $process";

					$select_result = mysql_query($sql_select,$newConnection2);

	            $update_result = mysql_query($sql_update,$newConnection2);

 	            if($select_result)
            	{
             		while($aktuelle_zeile = mysql_fetch_row($select_result))
             		{
						   $spaltenwert = $aktuelle_zeile[1];
							$spaltenwert_id = $aktuelle_zeile[0];
		               $sql_insert = "Update $process ($Spalte) VALUES($spaltenwert)";
		               $select_id = "select MAX($f_id) as aktuelle_id from $process";

		               	if($select_id_result = mysql_query($select_id,$newConnection2))
               			{
                 			  $max_id = mysql_fetch_row($select_id_result);
								  $update_process = "update $process set $process.nCirculationHistoryId = $process.$f_id";
                 				   if($result_update_process = mysql_query($update_process,$newConnection2))
                 					{
										}
                 					 else
                   					echo "<br>ERROR UpdateStatement $process_history";

               			}
               			 else
                   			echo "<br>ERROR SQLStatement $select_id";
					    }

            	}
                else
	            	echo "<script>alert('ERROR Update')</script>";

					
				//Circulation complete Update
				$sql_select_id = "	SELECT cf_circulationform.nID 
									FROM `cf_circulationform` inner join cf_circulationprocess 
									on cf_circulationform.nID = cf_circulationprocess.nCirculationFormId 
									where cf_circulationprocess.nDecissionState = 4";
				
				$zaehler_2 = 0;
				if($result_select_form_ids = mysql_query($sql_select_id,$newConnection2))
				{
					$sql_update = "UPDATE cf_circulationform SET bIsArchived=1 WHERE ";
					
					$zaehler = 0;
					while(mysql_fetch_row($result_select_form_ids))
					{
						$formid = mysql_result($result_select_form_ids,$zaehler, "nID");
						if($zaehler == 0)
							$sql_update .= " nID = $formid";
						else
							$sql_update .= " OR nID = $formid";
						
						$zaehler ++;
					}
					$zaehler_2 ++;
				}
				if(!$result_update_status = mysql_query($sql_update,$newConnection2))
					echo "<br>Circulation complete Update fehlerhaft";				
				
             /*--------------------------- END Update Process -------------------------- */
            
            
             echo "<br><br><h3><b>FINISH</h3><h4>!!!database update complete!!!</h4></b>";
            }
        else
        {
          echo "<br><br><h3><b>ERROR</h3><h4>!!!database table currently exist!!!</h4></b>";
        }
      }

	}

  ?>
<tr>
	<td>  
		<a href="javascript:history.back()">zur&uuml;ck</a>  
	</td>
</tr>
</table>
</body>
</html>
