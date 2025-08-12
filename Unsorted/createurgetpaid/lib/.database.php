<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©	   \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any		 \\
	// responsibility CreateYourGetPaid© has towards the	  \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the		  \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\

	class Database
	{
		var $queries		= 0;
		
		var $connection		= NULL;
		
		var	$queryHandle	= Array();
		var $t_delta		= Array();
		var $t_start		= Array();
		var $t_stop			= Array();
		
		function Database()
		{
			if(!$this->IsConnected())
				$this->TryConnection();
		}
		
		function IsConnected()
		{
			return (isset($this->connection));
		}
		
		function TryConnection()
		{
			$this->Connect(_DB_SERVER, _DB_USER, _DB_PASS, _DB_NAME);
		}

		function Connect($server, $user, $pass, $name)
		{
			$this->connection	= @mysql_connect($server, $user, $pass) or $GLOBALS["error"]->Fatal(__FILE__, "Cannot connect to the database ('$server' with user '$user'), MySQL said: " . mysql_error());
			@mysql_select_db($name) or $GLOBALS["error"]->Fatal(__FILE__, "Cannot select the database ($name), MySQL said: " . mysql_error());
		}
		
		function Query($SQL, $l = 1, $show_error = 1)
		{
			if($_GET["debug"] == "on")
				$this->_Start($SQL);
			
			$this->queryHandle[$l]	= @mysql_query($SQL) or $GLOBALS["error"]->Fatal(__FILE__, "Error in query ($SQL), MySQL said: " . mysql_error(), $show_error, $SQL, mysql_error());
			
			if($_GET["debug"] == "on")
				$this->_Stop($SQL);
			
			$this->queries++;
			
			if(!preg_match("/^INSERT INTO|^REPLACE|^UPDATE|^DELETE/s", trim($SQL)))
			{
				return $this->NumRows($l);
			}
			else
				return true;
		}
		
		function LastInsertID()
		{
			return @mysql_insert_id();
		}
		
		function Fetch($SQL, $l = 1)
		{
			$num	= $this->Query($SQL, $l);
			
			if($num == 1)
				$data	= $this->NextRow($l);
			
			if(count($data) == 2)
			{
				return $data[0];
			}
			else
				return $data;
		}
		
		function NextRow($l = 1)
		{
			$data	= @mysql_fetch_array($this->queryHandle[$l]);
			
			return $data;
		}
		
		function NumRows($l = 1)
		{
			return @mysql_num_rows($this->queryHandle[$l]);
		}
		
		function _Timer_Poll()
		{
			list($usec, $sec) = explode(" ", microtime());
			
			return ((float) $usec + (float) $sec);
		} 
		
		function _Start($what)
		{
			$this->t_start[$what][] = $this->_Timer_Poll();
		}
		
		function _Stop($what)
		{
			$i = count($this->t_start[$what]) - 1;
			$this->t_stop[$what][$i] = $this->_Timer_Poll();
			$this->t_delta[$what][$i] = $this->t_stop[$what][$i] - $this->t_start[$what][$i];
		}
		
		function Debug()
		{
			$this->_Stop("base");
			
			$total		= 0;
			
			echo "<BR><CENTER><TABLE bgcolor=\"#F2F2F2\">";
			echo "<TR BGCOLOR=\"DF2A2A\"><TD STYLE=\"color: #FFFFFF\"><B>Query</B></TD><TD STYLE=\"color: #FFFFFF\"><B>Seconds</B></TD></TR>";

			foreach($this->t_delta AS $what => $delta)
			{
				$whatShort		= substr($what, 0, 80);
				
				if($whatShort != $what)
					$whatShort		.= "..";
				
				$d			= "";
				
				foreach($delta AS $i)
				{
					$d			+= $i;
					$total		+= $i;
				}
				
				$d	= number_format($d, 5);
				
				if($whatShort == "base")
				{
					$whatShort	= "<B>Total (" . $whatShort . ")</B>";
					
	 				echo "<TR BGCOLOR=\"DF2A2A\"><TD valign=top STYLE=\"color: #FFFFFF\"><FONT title=\"$what\">" . $this->SQLHighlight($whatShort) . "</FONT></TD><TD STYLE=\"color: #FFFFFF\">$d</TD></TR>\n";
					echo "<TR BGCOLOR=\"DF2A2A\"><TD valign=top STYLE=\"color: #FFFFFF\"><B>Unknown</B></TD><TD STYLE=\"color: #FFFFFF\">" . number_format($total-$d, 5) . "</TD></TR>\n";
				}
				else
				{
	 				echo "<TR><TD valign=top><FONT title=\"$what\">" . $this->SQLHighlight($whatShort) . "</FONT></TD><TD>$d</TD></TR>\n";
	 			}
 			}
			
			echo "</TABLE></CENTER><BR>";
		}
		
		function get_def($table)
		{
			$def	= "";
			$def	.= "DROP TABLE IF EXISTS $table;#%%\r\n";
			$def	.= "CREATE TABLE $table (\r\n";
			
			$this->Query("SHOW FIELDS FROM $table");
			
			while($row = $this->NextRow())
			{
				$def	.= "	$row[Field] $row[Type]";
				if ($row["Default"] != "") $def .= " DEFAULT '$row[Default]'";
				if ($row["Null"] != "YES") $def .= " NOT NULL";
				if ($row[Extra] != "") $def .= " $row[Extra]";
					$def .= ",\r\n";
			}
			
			$def	= ereg_replace(",\r\n$","", $def);
			
			$this->Query("SHOW KEYS FROM $table");
			
			while($row = $this->NextRow())
			{
				$kname	= $row[Key_name];
				if(($kname != "PRIMARY") && ($row[Non_unique] == 0)) $kname="UNIQUE|$kname";
				if(!isset($index[$kname])) $index[$kname] = array();
				$index[$kname][] = $row[Column_name];
			}
			
			while(list($x, $columns) = @each($index))
			{
				$def	.= ",\r\n";
				if($x == "PRIMARY") $def .= "   PRIMARY KEY (".implode($columns, ", ").")";
				else if (substr($x,0,6) == "UNIQUE") $def .= "   UNIQUE ".substr($x,7)." (".implode($columns, ", ").")";
				else $def .= "   KEY $x (".implode($columns, ", ").")";
			}
			
			$def	.= "\r\n);#%%";
			
			return (stripslashes($def));
		}
		
		function get_content($table)
		{
			$content	= "";
			
			$this->Query("SELECT * FROM $table");
			
			while($row = $this->NextRow())
			{
				$insert	= "INSERT INTO $table VALUES (";
				
				for($j = 0;$j < mysql_num_fields($this->queryHandle[1]);$j++)
				{
					if(!isset($row[$j]))
						$insert	.= "NULL,";
					elseif($row[$j] != "")
						$insert	.= "'" . addslashes($row[$j]) . "',";
					else
						$insert	.= "'',";
				}
				
				$insert		= substr($insert, 0, -1);
				$insert		.= ");#%%\r\n";
				$content	.= $insert;
			}
			
			return $content;
		}
		
		function SQLHighlight($st, $upper_sintax = false, $case_insensitive = false, $stringtext="#222299", $sintaxcolor="#C00000", $number="#00C000", $background="#FFFFFF")
		{
			$l_ar		= "ADD|ALL|ALTER|ANALYZE|AND|AS|ASC|AUTO_INCREMENT|BDB|BERKELEYDB|BETWEEN|BIGINT|BINARY|BLOB|BOTH|BY|CASCADE|CASE|CHANGE|CHAR|CHARACTER|COLUMN|COLUMNS|CONSTRAINT|COUNT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|DATABASE|DATABASES|DAY_HOUR|DAY_MINUTE|DAY_SECOND|DEC|DECIMAL|DEFAULT|DELAYED|DELETE|DESC|DESCRIBE|DISTINCT|DISTINCTROW|DOUBLE|DROP|ELSE|ENCLOSED|ESCAPED|EXISTS|EXPLAIN|FIELDS|FLOAT|FOR|FOREIGN|FROM|FULLTEXT|FUNCTION|GRANT|GROUP|HAVING|HIGH_PRIORITY|HOUR_MINUTE|HOUR_SECOND|IF|IGNORE|IN|INDEX|INFILE|INNER|INNODB|INSERT|INT|INTEGER|INTERVAL|INTO|IS|JOIN|KEY|KEYS|KILL|LEADING|LEFT|LIKE|LIMIT|LINES|LOAD|LOCK|LONG|LONGBLOB|LONGTEXT|LOW_PRIORITY|MASTER_SERVER_ID|MATCH|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MIDDLEINT|MINUTE_SECOND|MRG_MYISAM|NATURAL|NOT|NULL|NUMERIC|ON|OPTIMIZE|OPTION|OPTIONALLY|OR|ORDER|OUTER|OUTFILE|PARTIAL|PRECISION|PRIMARY|PRIVILEGES|PROCEDURE|PURGE|READ|REAL|REFERENCES|REGEXP|RENAME|REPLACE|REQUIRE|RESTRICT|RETURNS|REVOKE|RIGHT|RLIKE|SELECT|SET|SHOW|SMALLINT|SONAME|SQL_BIG_RESULT|SQL_CALC_FOUND_ROWS|SQL_SMALL_RESULT|SSL|STARTING|STRAIGHT_JOIN|STRIPED|TABLE|TABLES|TERMINATED|THEN|TINYBLOB|TINYINT|TINYTEXT|TO|TRAILING|UNION|UNIQUE|UNLOCK|UNSIGNED|UPDATE|USAGE|USE|USER_RESOURCES|USING|VALUES|VARBINARY|VARCHAR|VARYING|WHEN|WHERE|WITH|WRITE|XOR|YEAR_MONTH|ZEROFILL";
			$sql_arr	= explode("|", $l_ar);
			
			$tag2		= md5(microtime());
			$st			= str_replace("\\\"", $tag2, $st);
			$ver		= explode(".", phpversion());
			$tag3		= md5(microtime());
			$st			= str_replace("\'", $tag3, $st);
			$m			= Array();
			$m			= preg_split ("/(\"){1}([^\"])+(\"){1}|('){1}([^'])+('){1}/", $st, -1, PREG_SPLIT_DELIM_CAPTURE);
			$total_out	= "";
			$ricorda	= 0;
			$m[0]		= " " . $m[0];
			
			$m[count($m)-1]	.= " ";
			
			$num_regger	= "([^a-z0-9\_]){1}([0-9]+)(\)| |\r|\n|,){1}";
			
			for($a = 0; $a < count($m); $a++)
			{
				if($m[$a] == "\"" || $m[$a] == "'")
				{
					$now_char	= 0;
					
					for($i = $ricorda; $i < strlen($st); $i++)
					{
						$now_char++;
						
						if($m[$a] == substr($st, $i, 1))
						{
							$newst	= $m[$a] . substr($st, $ricorda, $now_char);
							break;
						}
					}
					
					$ricorda	+= strlen($newst);
					$a			+= 2;
					
					$total_out	.= "<span style=\"color: {$stringtext};background-color: {$background};\">" . htmlspecialchars($newst) . "</span>";
				}
				else
				{
					if($m[$a] != '')
					{
						$ricorda	+= strlen($m[$a]);
						
						if($upper_sintax != false)
						{
							$m[$a]	= explode(" ", $m[$a]);
							
							for($b = 0; $b < count($m[$a]); $b++)
							{
								for($i = 0; $i < count($sql_arr); $i++)
								{
									if(strtoupper($m[$a][$b]) == $sql_arr[$i])
									{
										$m[$a][$b]	= strtoupper($m[$a][$b]);
										break;
									}
								}
							}
							
							$m[$a]	= implode(" ", $m[$a]);
						}
						
						$finitiinumeri	= false;
						
						for($i = 0; $i < count($sql_arr); $i++)
						{
							if(!eregi($num_regger, $m[$a]) && $finitiinumeri == false)
							{
								$finitiinumeri	= true;
							}
							if($finitiinumeri == false)
							{
								$m[$a]	= eregi_replace($num_regger, "\\1<span style=\"color: {$number};background-color: {$background};\">\\2</span>\\3", $m[$a]);
							}
							
							$myregexp		= "([^a-z0-9\_]){1}(" . $sql_arr[$i] . "){1}( |\r|\n|\(){1}";
							$txttoreplace	= "\\1<span style=\"color: {$sintaxcolor};background-color: {$background};text-transform: uppercase; font-weight: bold;\">\\2</span>\\3";
							
							if($case_insensitive != false)
							{
								if($ver[0] > 4)
								{
									if(stripos($m[$a], $sql_arr[$i])!== false)
									{
										$m[$a]	= eregi_replace($myregexp, $txttoreplace, $m[$a]);
									}
								}
								else
								{
									if(strpos(strtoupper($m[$a]), $sql_arr[$i])!== false)
									{
										$m[$a]	= eregi_replace($myregexp, $txttoreplace, $m[$a]);
									}
								}
							}
							else
							{
								if($upper_sintax != false)
								{
									if(strpos($m[$a], $sql_arr[$i])!== false)
									{
										$m[$a]	= eregi_replace($myregexp, $txttoreplace, $m[$a]);
									}
								}
								else
								{
									if((strpos($m[$a], $sql_arr[$i])!== false) || (strpos($m[$a], strtolower($sql_arr[$i]))!== false))
									{
										$m[$a]	= eregi_replace($myregexp, $txttoreplace, $m[$a]);
									}
								}
							}
						}
						
						$total_out	.= $m[$a];
					}
				}
			}
			$total_out	= str_replace($tag2, "\\\"", $total_out);
			$total_out	= str_replace($tag3, "\\'", $total_out);
			
			return trim($total_out);
		}
		
		function GetRandomRecord($query)
		{
			$data	= Array();
			
			$this->Query($query);
			
			while($row = $this->NextRow())
			{
  				$data[] = $row;
			}
			
			srand((double) microtime() * 524287);
			shuffle($data);
			
			return $data[0]["id"];
		}
		
		function LoopRandomData($query)
		{
			$this->Query($query);
			
			while($row = $this->NextRow())
			{
				$data[]	= $row;
			}
			
			srand((double) microtime() * 524287);
			shuffle($data);
			
			return $data;
		}
	}
	
	$db		= new Database;

?>