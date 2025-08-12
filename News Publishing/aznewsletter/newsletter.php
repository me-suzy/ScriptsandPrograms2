<?php
//Verbindung zur Datenbank herstellen
include("config.php");
//---------------------------------------------------------------------------------------------
mysql_connect("$dbserver","$dbuser","$dbpass") or die ("Die zur MySQL-Datenbank ist fehlgeschlagen");
mysql_select_db("$db") or die ("Die benötigte Tabelle konnte nicht gefunden werden");
//---------------------------------------------------------------------------------------------
if(isset ($email))
{
		//Checken ob Formular nicht leer (später wünschenswert zu prüfen ob eMail gültig!)
		if (empty($email))
		{
		echo "Variable ist leer";
		}
		else
		{
		//Checken ob eMail-Adresse bereits in DB in Tabelle newsletter ist
		$abfrage= mysql_query("SELECT * FROM $dbtable WHERE MAIL = '$email'");
		$anzahl=mysql_num_rows($abfrage);

			if($anzahl==0)
			{
				//EINTRAGROUTINE
				$eintrag = "INSERT INTO $dbtable (MAIL) VALUES ('$email')";
				$eintragen = mysql_query($eintrag);
				echo $email," wurde in den Newsverteiler aufgenommen";
			}
			else
			{
				//LöSCHROUTINE
				$loesche = "DELETE FROM $dbtable WHERE MAIL = '$email'";
				$loeschen = mysql_query($loesche);
				echo $email," wurde aus dem Newsverteiler entfernt";
			}
		}
}

else
{
//FORMULARANZEIGE
//---------------------------------------------------------------------------------------------
echo "<form action='newsletter.php' method='get'>";
echo "<input name='email' type='text' size='20' maxlength='50'>";
echo "<input name='' type='submit' value='Ein-/Austragen'>";
echo "</form>";
//---------------------------------------------------------------------------------------------
}
?>
