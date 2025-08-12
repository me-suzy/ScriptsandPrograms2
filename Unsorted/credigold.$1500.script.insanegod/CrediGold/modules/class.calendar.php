<?

class Calendar 

	{	

		var $noteArr  = array();

		var $daysArr  = array();

		function init() // Initiate Dating Span

			{

			   global $month, $year, $day, $prevmonth, $prevyear, $nextmonth, $nextyear;

			   global $backward, $forward, $first, $lastday, $current, $getTimes;

				

			   $getTimes = array();



			   if (!isset($month) || $month == "" || $month > 12 || $month < 1)		$month = date("m");

			   if (!isset($year) || $year == "" || $year < 1972 || $year > 2036)	$year  = date("Y");

			   $timestamp = mktime(0, 0, 0, $month, 1, $year); 

			   $current   = date("F Y", $timestamp);

			   if ($month < 2)

					{

					   $prevmonth = 12;

					   $prevyear  = $year - 1;

					}

				else

					{

					   $prevmonth = $month - 1;

					   $prevyear  = $year;

					}

				if ($month > 11)

					{

					   $nextmonth = 1;

					   $nextyear = $year + 1;

					}

				else

					{

					   $nextmonth = $month + 1;

					   $nextyear  = $year;

					}



					$backward = date("F Y", mktime(0, 0, 0, $prevmonth, 1, $prevyear));

					$forward  = date("F Y", mktime(0, 0, 0, $nextmonth, 1, $nextyear));

					$first    = date("w", mktime(0, 0, 0, $month, 1, $year));

					$lastday  = 28;

					for ($i=1;$i<$lastday;$i++)

						{

							array_push($getTimes, mktime(0,0,0,$month,$i,$year));

						}

					for ($i=$lastday;$i<32;$i++)

						{

						   if (checkdate($month, $i, $year))

							   {

									$lastday = $i;

									array_push($getTimes, mktime(0,0,0,$month,$i,$year));

							   }

						}

			}

		function AddNote($onDay, $content)

			{

				global $noteArr, $daysArr;

				$noteArr[] = $content;

				$daysArr[] = $onDay;

			}

		function getDays()

			{

				global $getTimes;

				return $getTimes;

			}

		function AddDay($fday, $fmonth, $fyear, $fvar)

			{

				global $noteArr, $daysArr;

				$code = "";

				if (!isset($fday) || $fday == "")	$code .= "<td class='".$this->tableDayClass."' align=left valign=top height=".$this->dayHeight.">&nbsp;";

				else

					{

						$schurl = eregi_replace("%url%",'schedule.php?day='.$fday.'&month='.$fmonth.'&year='.$fyear.'&time='.mktime(0,0,0,$fmonth,$fday,$fyear), $this->dayFunction);



						if (is_array($daysArr))

							{

								if (in_array($fday, $daysArr))

									{

										for ($x=0;$x<count($daysArr);$x++)

											{

												if ($daysArr[$x] == $fday)

													{

														$coolNote .= (strlen($coolNote) == 0)?$noteArr[$x]:(" ".$noteArr[$x]);

													}

											}

									}

							}



						if (date("m") == $fmonth && date("Y") == $fyear && date("j") == $fday)	$code .= "<td class='".$this->tableCurrentDay."' style='cursor: hand' align=left valign=top height=".$this->dayHeight." ".(($coolNote)?"":"onClick=\"".$schurl."\"").">";

						else																	$code .= "<td class='".$this->tableDayClass."' style='cursor: hand' align=left valign=top height=".$this->dayHeight." ".(($coolNote)?"":"onClick=\"".$schurl."\"").">";

				

						$code    .= "<b class='".$this->dayNumberClass."'>".$fday."</b><br>";

						$code    .= $coolNote;

						$coolNote = "";

						if (isset($fvar) && $fvar != "")	$code .= "<a class='".$this->dayLinkClass."' style='cursor: hand' onClick=\"".$schurl."\">".$fvar."</a>";

					}



				$code .= "</td>\n";

				return $code;

			}

	function getNextBack($monthVis)

		{

			global $month, $year, $day, $prevmonth, $prevyear, $nextmonth, $nextyear, $current;

			global $backward, $forward, $first, $lastday, $calender_title, $calender_title_image;

			$placement = ($monthVis == "yes")?$current:"";

			$source  = "\n";

			$source .= "<table ".$this->tableAttribs." width=".$this->tableWidth." class=".$this->tableNavClass.">\n";

			$source .= "<tr>\n";

			$source .= "<td width=".round($this->tableWidth/7)." class='".$this->tableTopClass."' nowrap align=center valign=middle>&nbsp;<a href='".$this->calendarURL."&month=".$prevmonth."&year=".$prevyear."'>".$this->backText."</a>&nbsp;</td>\n";

			$source .= "<td width=".(round($this->tableWidth/7)*5)." class='".$this->tableTopClass."' nowrap align=center valign=middle>".$placement."</td>\n";

			$source .= "<td width=".round($this->tableWidth/7)." class='".$this->tableTopClass."' nowrap align=center valign=middle><a href='".$this->calendarURL."&month=".$nextmonth."&year=".$nextyear."'>".$this->nextText."</a>&nbsp;</td>\n";

			$source .= "</tr>\n";

			$source .= "<tr>\n";

			$source .= "</table>\n";

			print $source;

		}

	function renderCal()

		{

			global $month, $year, $day, $prevmonth, $prevyear, $nextmonth, $nextyear, $current, $getTimes;

			global $backward, $forward, $first, $lastday, $calender_title, $calender_title_image;



			$getTimes = array();

			$source  = "";						

			$source .= "<table ".$this->tableAttribs." width=".$this->tableWidth.">\n";

			

			if (isset($start_day) && $start_day <= 6 && $start_day >= 0)	$n = $start_day;

			else	$n = 0;



			for ($i=0;$i<7;$i++)

				{

					  if ($n > 6 )	$n = 0;

					  if ($n == 0)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[0]."</td>\n";

					  if ($n == 1)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[1]."</td>\n";

					  if ($n == 2)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[2]."</td>\n";

					  if ($n == 3)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[3]."</td>\n";

					  if ($n == 4)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[4]."</td>\n";

					  if ($n == 5)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[5]."</td>\n";

					  if ($n == 6)	$source .=  "<td class='".$this->tableHeaderClass."' nowrap align=center valign=middle width=".round($this->tableWidth/7)." height=".$this->headerHeight.">".$this->daysAbbr[6]."</td>\n";

					  $n++;

			   }

			$source .= "</tr>\n";



			$calday = 1;

			while ($calday <= $lastday)

				{

					$source .= "<tr>\n";

					for ($j=0;$j<7;$j++)

						{

							if ($j == 0) $n = $start_day;

							else

								{

									if ($n < 6) $n = $n + 1;

									else		$n = 0;

								}

							if ($calday == 1)

								{

									if ($first == $n)

										{

											$info = "";

											$source .= $this->AddDay($calday, $month, $year, $info);

											$calday++;

										}

									else	$source .= $this->AddDay('', '', '', '');

								}

							else

								{

									if ($calday > $lastday) $source .= $this->AddDay('', '', '', '');

									else

										{

											$info = "";

											$source .= $this->AddDay($calday, $month, $year, $info);

											$calday++;

										}

								}

						} 

					$source .= "</tr>\n";

				}

			$source .= "</table>\n";

			print $source;

		}

	}

?>