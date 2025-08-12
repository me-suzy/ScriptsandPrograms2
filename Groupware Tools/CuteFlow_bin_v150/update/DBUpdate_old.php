<html>
<title>eMedia Office CuteFlow Database Update</title>
<body>
<?
include("../config/config.inc.php");

//Connect to Database and Login

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

//            $drop_table = "ALTER TABLE `$process` DROP TABLE IF EXISTS `nCirculationHistoryId";
            $sql_create_nCirculationHistoryId = "ALTER TABLE `$process` ADD `nCirculationHistoryId` INT( 11 ) DEFAULT '0' NOT NULL";
            if($createResult = mysql_query($sql_create_nCirculationHistoryId,$nConnection))
            {
            	echo "Feld: nCirculationHistoryId erzeugt<br>";
            }
            else
            {
                echo "<b>WARNING COLUMN currently exist</b><br>";
                }

            // delete unused Columns

            $sql_drop_column = "ALTER TABLE $history DROP nSenderId";
             if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>nSenderId erfolgreich gel&ouml;scht";

            $sql_drop_column = "ALTER TABLE $history DROP strName";
             if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>strName erfolgreich gel&ouml;scht";

            $sql_drop_column = "ALTER TABLE $history DROP nMailingListId";
             if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>nMailingListId erfolgreich gel&ouml;scht";

            $sql_drop_column = "ALTER TABLE $history DROP bIsArchived";
             if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>bIsArchived erfolgreich gel&ouml;scht";

             $sql_drop_column = "ALTER TABLE $form DROP dateSending";
             if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>dateSending from $form erfolgreich gel&ouml;scht";

            $sql_drop_column = "ALTER TABLE $form DROP strAdditionalText";
             if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>strAdditionalText from $form erfolgreich gel&ouml;scht";

            $sql_drop_column = "ALTER TABLE $history DROP nEndAction";
            if($drop_result = mysql_query($sql_drop_column,$nConnection))
             	echo "<br>bnEndAction erfolgreich gel&ouml;scht";
              
              
            ############### Update History ##############################

		        $Spalte  = "nCirculationFormId";
				    $p_id         = "nID";
            $newConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);


		        $sql_select = "select $p_id,$Spalte from $history";

#                    echo "<script>alert('$sql_select');</script>";

					  $select_result = mysql_query($sql_select,$nConnection);

#                    echo "$select_result";

            $update_result = mysql_query($sql_update,$nConnection);

            if($select_result)
            {
             while($aktuelle_zeile = mysql_fetch_row($select_result))
             {
#              echo "<pre>";
#              print_r($aktuelle_zeile);
#              echo "</pre>";
#              echo "<script>alert('$aktuelle_zeile[0]');</script>";

               #$spaltenwert = mysql_result($select_result,$Spalte);
               $spaltenwert = $aktuelle_zeile[1];

               #$spaltenwert_id = mysql_result($select_result,$p_id);
               $spaltenwert_id = $aktuelle_zeile[0];
              
#              echo "wert=".$spaltenwert_id."<br>";

                #echo "<script>alert('$zaehler');</script>";
                #$zaehler ++;

#              $sql_update = "Update $history ($p_id,$Spalte) VALUES($spaltenwert_id,$spaltenwert)";
               $sql_insert = "Update $history ($Spalte) VALUES($spaltenwert)";
#               if($insert_result = mysql_query($sql_insert,$nConnection))

               $select_id = "select MAX($p_id) as aktuelle_id from $history";
               if($select_id_result = mysql_query($select_id,$newConnection))
                                    {
                 $max_id = mysql_fetch_row($select_id_result);
#                 echo "<br>ID: $max_id[0]";
                 $update_history = "update $history set $history.nCirculationFormId = $history.nID";
                 if($result_update_history = mysql_query($update_history,$newConnection))
                 {
#                   echo "<br>History Update successfull";
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

             
             ########################### END Update History ##############
             
             
             
             ############### Update Process ##############################

		        $Spalte  = "nCirculationHistoryId";
				    $f_id         = "nCirculationFormId";
            $newConnection2 = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);


		        $sql_select = "select $f_id,$Spalte from $process";

#                    echo "<script>alert('$sql_select');</script>";

					  $select_result = mysql_query($sql_select,$newConnection2);

                    echo "$select_result";

            $update_result = mysql_query($sql_update,$newConnection2);

            if($select_result)
            {
             while($aktuelle_zeile = mysql_fetch_row($select_result))
             {

               #$spaltenwert = mysql_result($select_result,$Spalte);
               $spaltenwert = $aktuelle_zeile[1];

               #$spaltenwert_id = mysql_result($select_result,$_id);
               $spaltenwert_id = $aktuelle_zeile[0];

#              echo "wert=".$spaltenwert_id."<br>";

                #echo "<script>alert('$zaehler');</script>";
                #$zaehler ++;

#              $sql_update = "Update $process ($p_id,$Spalte) VALUES($spaltenwert_id,$spaltenwert)";
               $sql_insert = "Update $process ($Spalte) VALUES($spaltenwert)";
#               if($insert_result = mysql_query($sql_insert,$nConnection))

               $select_id = "select MAX($f_id) as aktuelle_id from $process";

               if($select_id_result = mysql_query($select_id,$newConnection2))
               {
                 $max_id = mysql_fetch_row($select_id_result);
#                 echo "<br>ID: $max_id[0]";
                 $update_process = "update $process set $process.nCirculationHistoryId = $process.$f_id";
                 if($result_update_process = mysql_query($update_process,$newConnection2))
                 {
#                   echo "<br>Process Update successfull";
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


             ########################### END Update Process #######################################
            
            
                echo "<br><br><h3><b>FINISH</h3><h4>!!!database update complete!!!</h4></b>";
            }
             else
            {
                echo "<br><br><h3><b>ERROR</h3><h4>!!!database table currently exist!!!</h4></b>";
            }
          }

			}


  ?>
</body>
</html>
