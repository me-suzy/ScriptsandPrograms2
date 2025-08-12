<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

/*
$q['title'] = 'Pealkiri';
$q['summary'] = 'Sisukokkuvõte';
$q['data'][0]['type']='radio';
$q['data'][0]['question']='Mis värk on?';
$q['data'][0]['comment']='Jah, pole siin hõisata midagi...';
$q['data'][0]['variant'][0]['text']='Ei ole siin midagi';
$q['data'][0]['variant'][0]['correct']=1;
$q['data'][0]['variant'][1]['text']='On kah';
$q['data'][0]['variant'][2]['text']='Mis see sinu asi peaks olema?';
*/

class Quiz {
	var $filename;	// konkreetse faili nimi
	var $path;		// xls failide kataloog
	var $cachepath = 'cache/';
	var $quiz= array();
	var $res = array();
	var $err = '';

	function Quiz($filename, $path='db/')
	{
		$this->filename	= $filename;
		$this->path			= $path;
	}

	function load($forceCache=0)
	{
		$fn = $this->cachepath . $this->filename;
		if(!is_file($fn) || $forceCache)
		{
			$this->parse();
			$this->saveCache();
		}
		else
		{
			$this->loadCache();
		}
	}

	function saveCache()
	{
		if(!is_writable($this->cachepath)) return false;
		$fn = $this->cachepath . $this->filename;
		$f = fopen($fn, 'w');
		if($f && !empty($this->quiz))
		{
			fwrite($f, serialize($this->quiz));
			fclose($f);
			return true;
		}
		return false;
	}

	function loadCache()
	{
		$fn = $this->cachepath . $this->filename;
		if(is_file($fn))
		{
			$this->quiz = unserialize(file_get_contents($fn));
			return true;
		}
		return false;
	}

	function Parse()
	{
		$filepath = $this->path . $this->filename . '.xls';
		if(!is_file($filepath))
		{
			$this->setError('Test not found =/');
			return 0;
		}

		$excel = new Spreadsheet_Excel_Reader();
		$excel->setOutputEncoding('CP1251');
		$excel->read($filepath);


		$this->quiz['title'] = $excel->sheets[0]['cells'][1][1];
		$this->quiz['intro'] = $excel->sheets[0]['cells'][3][1];

		for($q=1;$q<=count($excel->sheets);$q++)
		{
			if(!isset($excel->sheets[$q]['cells'][1][1])) continue;
			
			$this->quiz['data'][$q]['type'] = $excel->sheets[$q]['cells'][1][1];
			$this->quiz['data'][$q]['question'] = htmlspecialchars($excel->sheets[$q]['cells'][2][1]);
			$this->quiz['data'][$q]['comment'] = htmlspecialchars($excel->sheets[$q]['cells'][4][1]);

			$w = -1;	// current variant
			for($v = 6; $v <= $excel->sheets[$q]['numRows']; $v++)
			{
				$w++;
				if(isset($excel->sheets[$q]['cells'][$v][1]))
					$this->quiz['data'][$q]['variant'][$w]['text'] = htmlspecialchars($excel->sheets[$q]['cells'][$v][1]);

				if(isset($excel->sheets[$q]['cells'][$v][2]))
					$this->quiz['data'][$q]['variant'][$w]['correct'] = $excel->sheets[$q]['cells'][$v][2];
				
			}
		}
	}

	function getHTML()
	{
		$out  = '';
		$out .= '<h1>' . $this->quiz['title'] . "</h1>\n\n";

		$_SESSION['start'] = array_sum(explode(' ', microtime()));

		$out .= '<div class="intro">' .nl2br($this->quiz['intro']) . "</div>\n";
		$out .= '<h2>Questions</h2>';
		$out .= sprintf('<form action="%s" method="post">', $_SERVER['SCRIPT_NAME'].'?id='.$this->filename);

		foreach($this->quiz['data'] as $q=>$arr)
		{
			$out .= '<div class="qbox">'."\n<h4>" . $q . '. ' .tpl::bbcode($arr['question']) . "</h4>";

			switch(strtolower($arr['type']))
			{
				case 'text':
						$out .= 'Your answer: <input type="text" name="t['.$q.']" /><br/>';
				break;
				
				case 'checkbox':
					if(is_array($arr['variant']))
					{
						foreach($arr['variant'] as $v=>$varr)
						{
							$out .= $this->getChar($v, 1) . '. <input type="checkbox" name="t['.$q.$v.']" value="1" id="t'.$q.$v.'" /> <label for="t'.$q.$v.'">' . tpl::bbcode($varr['text']) . "</label> <br />\n";
						}
					}
				break;

				case 'radio':
				default:
					if(is_array($arr['variant']))
					{
						foreach($arr['variant'] as $v=>$varr)
						{
							$out .= $this->getChar($v, 1) . '. <input type="radio" name="t['.$q.']" value="'.$v.'" id="t'.$q.$v.'" /> <label for="t'.$q.$v.'">' . tpl::bbcode($varr['text']) . "</label> <br />\n";
						}
					}
			}

			$out .= "</div>\n\n";
		}

		$name = isset($_COOKIE['name_']) ? htmlspecialchars($_COOKIE['name_']) : t('Enter Your name');

		$htmlInput = <<<HTML
<p>
<input type="text" name="name" class="input-name" value="%s" onclick="if(this.value=='%s'){this.value='';}" /><br />
HTML;

		$out .= sprintf($htmlInput, $name, t('Enter Your name'));
		$out .= '<input type="submit" class="submit-button-chek" name="submit" value="'.t('Chek').'" /></p>';
		$out .= "</form>\n";

		return $out;
	}

	function getResults($res)
	{
		$out  = '';
		$out .= '<h1>' . $this->quiz['title'] . "</h1>\n\n";

		$this->res['time_elapsed']		= array_sum(explode(' ', microtime())) - $_SESSION['start'];
		$this->res['total_points']		= 0;
		$this->res['correct_answers']	= 0;

		$out .= "\n<!--summary-->\n";
		$out .= sprintf("<h2>%s</h2>", t('Answers'));

		foreach($this->quiz['data'] as $q=>$arr)
		{
			$out .= '<div class="qbox">'."\n<h4>" . $q . '. ' .tpl::bbcode($arr['question']) . "</h4>";

			switch(strtolower($arr['type']))
			{
				case 'text':
#					$out .= 'Your answer: <input type="text" name="t['.$q.']" /><br/>';
					
					$this->res['total_points'] += 1;
					$this->res['points'][$q] = 1;

					$out .= t('Your answer') . ': ';
					if(!empty($res['t'][$q]))
					{
						$out .= htmlspecialchars($res['t'][$q]);

						$correct = false;
						foreach($arr['variant'] as $v=>$varr)
						{
							if(trim(strtolower($res['t'][$q])) == strtolower(trim($varr['correct'])))
							{
								$correct = true;
								$this->res['correct_answers'] += 1;
								$out .= sprintf(' <span class="correct">%s</span> <br />'."\n", t('Correct'));
								break;
							}
						}

						if(!$correct)
							$out .= sprintf(' <span class="wrong">%s</span> :/ <br />'."\n", t('Wrong'));
					}
					else 
					{
						 $out .= '<span class="wrong">'.t('Not answered') . "</span><br />\n";
					}
				break;
				
				case 'checkbox':
					if(is_array($arr['variant']))
					{
						$mistakes = false;
						$correct_answers = 0;

						// $v = variant number: 0 - 4 (or, less or more)
						// $varr contains variant text and correct variant as
						// $varr['text'] <- variant text
						// $varr['correct'] = 1; 
						// $t is array with gived answers, say if answered is q1 1st variant:  $t[1]=0;
						foreach($arr['variant'] as $v=>$varr)
						{
							$report = $checked = $css1 = $css2 = '' ;

							if(isset($varr['correct']))
							{
								$this->res['total_points'] += 1;
								@$this->res['points'][$q] += 1;
							}

							// if this isset, we know that some variants is checked from this radiobox
							if (isset($res['t'][$q.$v]))
							{
								//checked variant
								if($res['t'][$q.$v]==1)
								{
									$checked = ' checked="checked"';
									$css1 = '<span class="your_choice">';
									$css2 = '</span>';
									if(isset($varr['correct']))
									{
										$this->res['correct_answers'] += 1;
										$correct_answers += 1;
										$report = sprintf(' <span class="correct">%s</span> ', t('Correct'));
									}
									else
									{
										$report = sprintf(' <span class="wrong">%s</span> ', t('Wrong'));
										$mistakes = true;
									}
								}
								// unchecked variants
								else
								{
									$report = $checked = $css1 = $css2 = '';
								}
							}
							#$out .= $this->getChar($v, 1) . '. <input type="checkbox" disabled="disabled"'.$checked.' />'.$css1.tpl::bbcode($varr['text']) . $css2. $report . "<br />\n";
							$out .= $this->getChar($v, 1) . '. <input type="checkbox" disabled="disabled"'.$checked.' />'.$css1.tpl::bbcode($varr['text']) . $css2. $report . "<br />\n";
						}

						// if there are any mistakes, we subtract this question correct answer
						// points from correct answers sum
						if($mistakes==true)
							$this->res['correct_answers'] -= $correct_answers;
					}
/*
					if(is_array($arr['variant']))
					{
						foreach($arr['variant'] as $v=>$varr)
						{
							$out .= $this->getChar($v, 1) . '. <input type="checkbox" name="t['.$q.$v.']" value="1" id="t'.$q.$v.'" /> <label for="t'.$q.$v.'">' . tpl::bbcode($varr['text']) . "</label> <br />\n";
						}
					}
*/
				break;

				case 'radio':
				default:
					$this->res['total_points'] += 1;
					$this->res['points'][$q] = 1;

					if(is_array($arr['variant']))
					{
						// $v = variant number: 0 - 4 (or, less or more)
						// $varr contains variant text and correct variant as
						// $varr['text'] <- variant text
						// $varr['correct'] = 1; 
						// $t is array with gived answers, say if answered is q1 1st variant:  $t[1]=0;
						foreach($arr['variant'] as $v=>$varr)
						{
							$report = $checked = $css1 = $css2 = '' ;

							// if this isset, we know that some variants is checked from this radiobox
							if (isset($res['t'][$q]))
							{
								//checked variant
								if($res['t'][$q]==$v)
								{
									$checked = ' checked="checked"';
									$css1 = '<span class="your_choice">';
									$css2 = '</span>';
									if(isset($varr['correct']))
									{
										$this->res['correct_answers'] += 1;
										$report = sprintf(' <span class="correct">%s</span> ', t('Correct'));
									}
									else
									{
										$report = sprintf(' <span class="wrong">%s</span> ', t('Wrong'));	
									}
								}
								// unchecked variants
								else
								{
									$report = $checked = $css1 = $css2 = '';
								}
							}
							$out .= $this->getChar($v, 1) . '. <input type="radio" disabled="disabled"'.$checked.' />'.$css1.tpl::bbcode($varr['text']) . $css2. $report . "<br />\n";
						}
					}
			}
			$out .= '<div class="key">'.nl2br(tpl::bbcode($arr['comment'])).'</div>';
			$out .= "</div>\n\n";
		}

		$out .= sprintf('<h2><a href="index.php">%s</a></h2>', t('Back to mainpage'));

#		$out .= '<input type="submit" name="submit" value="Chek" />';
#		$out .= "</form>\n";

		return $out;
	}

	function getSummary()
	{
		if(trim($_POST['name'])&&$_POST['name']!=t('Enter Your name'))
		{
			$this->res['name']=trim($_POST['name']);
			$name = htmlspecialchars($this->res['name']);

			setcookie('name_', $this->res['name'], time()+7*24*3600);
		}
		else
		{
			$this->res['name'] = $name = '';
		}

		$out  = '<div class="summary">'."\n";
		$out .= '<a name="summary"></a><h2 id="summary">'.($name?$name.', h':'H').'ere is Your result'."</h2>\n";

#		$out .= '<a name="summary"></a><h2 id="summary">'.t('Summary')."</h2>\n";

#		if(empty($this->res)) 
#		{
#			return $out . '<p>'.t('Summary not found').'...</p></div>';
#		}

		$out .= '<h3>'.t('Total questions') . ' : ' .count($this->res['points'])."</h3>\n";

		$out .= '<h3>'.t('Max. points') . ' : ' . $this->res['total_points']."</h3>\n";

		//score * 100 / max points
		$percent = round($this->res['correct_answers'] * 100 / $this->res['total_points'],1);

		$out .= '<h3>'.t('Your score') . ' : ' . $this->res['correct_answers']."</h3>\n";
		$out .= '<h3>'.t('Percent').' : '. $percent."%</h3>\n";

		$time = Duration::toString($this->res['time_elapsed']);
		$out .= '<h3>'.t('Time') . ' : ' .$time."</h3>\n";
		$out .= '<h4><a href="index.php">'.t('Back to mainpage').'</a></h4>';
		$out .= "</div>\n\n";
		return $out;
	}

	function addSummaryToDb()
	{
		$name = SQL::esc($this->res['name']);

		$is_already_in = SQL::getAssoc('SELECT * FROM ' . TABLE_RESULTS . ' WHERE result_user_name="'.$name.'" AND DATE_ADD(result_date, INTERVAL '.RESULTS_EXPIRY_TIME.' SECOND) > NOW() AND result_test_id="'.SQL::esc($this->filename).'"');

		if(!$is_already_in && !empty($name))
		{
			$sql_query = sprintf('INSERT into %s SET result_test_id="%s", result_user_name="%s", result_score=%u, result_date=now(), result_time=%u, result_ip="%s"',
				TABLE_RESULTS,
				SQL::esc($this->filename),
				$name,
				$this->res['correct_answers'],
				$this->res['time_elapsed'],
				$_SERVER['REMOTE_ADDR']
			);
	
			SQL::insert($sql_query);
		}
	}

	function getChar($no, $case=0)
	{
		static $chars;

		if(empty($chars))
			$chars = join("", range('a', 'z'));
		return $case == 1 ? strtoupper($chars[$no]) : $chars[$no];
	}

	function setError($err)
	{
		$this->err = $err;
	}

	function getError()
	{
		return $this->err;
	}
}

?>