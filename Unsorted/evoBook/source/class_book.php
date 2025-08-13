<?php
//1/19/2004

// guestbook Class

class Book
{
	function Book()
	{
		$this->floodcheck = "60"; // in seconds
		$this->use_cookie = 1;
		$this->cookie_prefix = "cookie_";
		$this->shout_width = "150";
	}
	
	function select_country($name,$selected)
	{
		global $admin;

		$country = array('Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo Brazzaville','Congo','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic','Côte d\'Ivoire','Denmark','Djibouti','Dominica','Dominican Republic','East Timor','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Fiji','Finland','France','Gabon','Gambia, The','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Korea, North','Korea, South','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Micronesia','Moldova','Monaco','Mongolia','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Samoa','San Marino','Sao Tome and Principe','Saudi Arabia','Senegal','Serbia and Montenegro','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','Spain','Sri Lanka','Sudan','Suriname','Swaziland','Sweden','Switzerland','Syria','Taiwan','Tajikistan','Tanzania','Thailand','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Western Sahara','Yemen','Zambia','Zimbabwe');
		
		$lists .= '| Select Country,';
		foreach ($country as $ctry)
		{
			$lists .= $ctry.'|'.$ctry.',';
		}
		
		return $admin->form_select($name,$lists,$selected,'');
	}

	function select_gender($name,$selected)
	{
		global $admin,$evoLANG;
		
		return $admin->form_select($name,'m|'.$evoLANG['male'].',f|'.$evoLANG['female'],$evoLANG['gender'],'');
	}

	function process_gender($gender)
	{
		global $root,$settings,$evoLANG;

		if ($gender == 'm')
		{
			return '<img src="'.$settings['imgfolder'].'icon_male.gif" alt="'.$evoLANG['male'].'" />';
		}
		else
		{
			return '<img src="'.$settings['imgfolder'].'icon_female.gif" alt="'.$evoLANG['female'].'" />';
		}
	}

	function main()
	{
		global $evoLANG,$admin,$udb,$database,$tpl,$settings,$_SERVER,$_GET;
		
		require ("./source/class_smilies.php");
		$smi = new Smilies;


		$pg = trim($_GET['pg']) == '' ? '':$_GET['pg'];
		$pg = (isset($pg)) && $pg != '' ? $pg:"1";
		
		$this->perpage = $settings['perpage'];

		$sql2 = $udb->query("SELECT id FROM $database[entry]");
		$total = $udb->num_rows($sql2);
			
		if ($total > $this->perpage)
		{
			$totalpage = ceil($total/$this->perpage);
		}
				
		$totalpage = ($totalpage=='') ? $pg:$totalpage;
		$num = $pg + 1;
		$offset = ($pg-1) * $this->perpage;
		$prev = $pg-1;
		$curpage = $pg;

		if ($pg < $totalpage) $nextpage = "<a href=\"$_SERVER[PHP_SELF]?pg=$num\"> &raquo;</a> ";
		if ($pg > 1) $prevpage = " <a href=\"$_SERVER[PHP_SELF]?pg=".$prev."\">&laquo; </a>";
		
		for ($i = 1; $i <= $totalpage; $i++)
		{
			if ($i == $curpage)
			{
				$pgloop .= "<b>[".$i."]</b> ";
			}
			else
			{
				$pgloop .= " <b><a href=\"$_SERVER[PHP_SELF]?pg=".$i."\">".$i."</a></b> ";
			}
		}

		$sql = $udb->query("SELECT * FROM $database[entry] ORDER BY id DESC LIMIT $offset,".$this->perpage);
		while ( $row = $udb->fetch_array($sql) )
		{
			$i++;
			$bg = $admin->get_bg($i);

			$row = $admin->strip_array($row);
			$row['entry'] = $settings['allow_html'] == 1 ? $row['entry'] : strip_tags($row['entry']);
			$row['entry'] = $this->filter($row['entry']);
			$row['entry'] = $smi->parse($row['entry']);
			$row['date'] = date($settings['dateformat'],$row['date']);
			$row['gender'] = $this->process_gender($row['gender']);
			$row['email'] = trim($row['email']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'email.gif" alt="'.$row['email'].'" />','mailto:'.$row['email']) : '';

			$row['ym'] = trim($row['ym']) == '' ? '-':$row['ym'];
			$row['icq'] = trim($row['icq']) == '' ? '-':$row['icq'];
			$row['country'] = trim($row['country']) == '' ? '-':$row['country'];
			$row['entry'] = $settings['autobr'] == 1 ? nl2br($row['entry']) : $row['entry'];

			$row['www'] = trim($row['www']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'www.gif" alt="'.$row['www'].'" />',$row['www']) : '';

			eval("\$entries .= \"".$tpl->gettemplate("entries_loop")."\";");
		}


		eval("\$content = \"".$tpl->gettemplate("home")."\";");

		return $content;
	}

	function addentry()
	{
		global $evoLANG,$admin,$udb,$database,$tpl,$settings,$_SERVER,$page;
		
		$page->site_title  = $evoLANG['addentry'];

		$admin->row_width = '30%';
		$admin->row_align = 'left';

		require ("./source/class_smilies.php");
		$smi = new Smilies;

		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => $evoLANG['word_name'],
										"email"        => $evoLANG['email'],
										"entry"        => $evoLANG['msg']
									 );
		/* ------------------------------------------------ */

		$html .= $admin->form_start('form_add',$_SERVER['PHP_SELF'].'?do=submit');
		$html .= $admin->form_hidden('ip',$admin->get_ip());
		$html .= $admin->form_hidden('agent',$_SERVER['HTTP_USER_AGENT']);
		
		$rules = $settings['autobr'] == 1 ? '<b>'.$evoLANG['autobr'].'</b> : '.$evoLANG['word_yes']:'<b>'.$evoLANG['autobr'].'</b> : '.$evoLANG['word_no'];
		$rules .= '<br />';
		$rules .= $settings['allowhtml'] == 1 ? '<b>'.$evoLANG['htmlallowed'].'</b> : '.$evoLANG['word_yes']:'<b>'.$evoLANG['htmlallowed'].'</b> : '.$evoLANG['word_no'];

		$rows .= $admin->add_spacer($evoLANG['addentry']);
		$rows .= $admin->add_row($evoLANG['word_name'],$admin->form_input('name'));
		$rows .= $admin->add_row($evoLANG['email'],$admin->form_input('email'));
		$rows .= $admin->add_row($evoLANG['msg'],$admin->form_textarea('entry','','65|15'),$smi->showbox('form_add','entry').'<br /><br />'.$rules );
		
		$rows .= $admin->add_spacer($evoLANG['optional']);
		$rows .= $admin->add_row($evoLANG['website'],$admin->form_input('www'));
		$rows .= $admin->add_row($evoLANG['ym'],$admin->form_input('ym'));
		$rows .= $admin->add_row($evoLANG['icq'],$admin->form_input('icq'));
		$rows .= $admin->add_row($evoLANG['country'],$this->select_country('country','') );
		$rows .= $admin->add_row($evoLANG['gender'],$this->select_gender('gender','') );
		


		$rows .= $admin->form_submit('addentry');
		$html .= $admin->add_table($rows, '90%' );
		$html .= '</form>';

		return $html;

	}

	function admin_editentry($id)
	{
		global $evoLANG,$admin,$udb,$database,$tpl,$settings,$_SERVER,$page;
		
		$page->site_title  = $evoLANG['editentry'];

		$admin->row_width = '30%';
		$admin->row_align = 'left';

		require ("../source/class_smilies.php");
		$smi = new Smilies;
		
		$row = $udb->query_once("SELECT * FROM ".$database['entry']." WHERE id='".$id."'");
		$row = $admin->strip_array($row);

		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => $evoLANG['word_name'],
										"email"        => $evoLANG['email'],
										"entry"        => $evoLANG['msg']
									 );
		/* ------------------------------------------------ */

		$html .= $admin->form_start('form_edit',$_SERVER['PHP_SELF'].'?do=submit');
		$html .= $admin->form_hidden('id',$id);
		
		$rows .= $admin->add_spacer($evoLANG['editentry']);
		$rows .= $admin->add_row($evoLANG['word_name'],$admin->form_input('name',$row['name']));
		$rows .= $admin->add_row($evoLANG['email'],$admin->form_input('email',$row['email']));
		$rows .= $admin->add_row($evoLANG['msg'],$admin->form_textarea('entry',$row['entry'],'65|15'),$smi->showbox('form_edit','entry') );
		
		$rows .= $admin->add_spacer($evoLANG['optional']);
		$rows .= $admin->add_row($evoLANG['website'],$admin->form_input('www',$row['www']));
		$rows .= $admin->add_row($evoLANG['ym'],$admin->form_input('ym',$row['ym']));
		$rows .= $admin->add_row($evoLANG['icq'],$admin->form_input('icq',$row['icq']));
		$rows .= $admin->add_row($evoLANG['country'],$this->select_country('country',$row['country']) );
		$rows .= $admin->add_row($evoLANG['gender'],$this->select_gender('gender',$row['gender']) );
		$rows .= $admin->add_row($evoLANG['ip'],$admin->form_input('ip',$row['ip']) );
		


		$rows .= $admin->form_submit('editentry');
		$html .= $admin->add_table($rows, '90%' );
		$html .= '</form>';

		return $html;

	}
	
	function process_edit()
	{
		global $_POST,$udb,$admin,$settings,$database,$_COOKIE;
		
		$_POST = $admin->slash_array($_POST);
		$udb->query("UPDATE ".$database['entry']." SET 
											ip = '".$_POST['ip']."',
											name = '".$_POST['name']."',
											email = '".$_POST['email']."',
											entry = '".$_POST['entry']."',
											www = '".$_POST['www']."',
											ym = '".$_POST['ym']."',
											icq = '".$_POST['icq']."',
											country = '".$_POST['country']."',
											gender = '".$_POST['gender']."'

												WHERE
													id = '".$_POST['id']."'");


		header("location: $_SERVER[PHP_SELF]?do=manage");
	}

	function admin_deleteentry($id)
	{
		global $_POST,$udb,$admin,$settings,$database,$_COOKIE;
		
		$udb->query("DELETE FROM ".$database['entry']." WHERE id='".$id."'");

		header("location: $_SERVER[PHP_SELF]?do=manage");
	}

	function process_add()
	{
		global $_POST,$udb,$admin,$settings,$database,$_COOKIE;
		
		$_POST = $admin->slash_array($_POST);

		if ($_COOKIE['flood'] == 1)
		{
			return $evoLANG['floodmsg'];
		}
		else
		{
			if ($this->use_cookie == 1)
			{
				$admin->makecookie($this->cookie_prefix."name",$_POST['name']);
				$admin->makecookie($this->cookie_prefix."email",$_POST['email']);
				$admin->makecookie($this->cookie_prefix."site",$_POST['www']);
			}

			$udb->insert_into = $database['entry'];
			$udb->insert_data = array (
											"ip"               => $_POST['ip'],
											"agent"            => $_POST['agent'],
											"date"			   => time(),
											"name"             => $_POST['name'],
											"email"            => $_POST['email'],
											"entry"            => $_POST['entry'],
											"www"              => $_POST['www'],
											"ym"               => $_POST['ym'],
											"icq"              => $_POST['icq'],
											"country"          => $_POST['country'],
											"gender"           => $_POST['gender']
									   );
			$udb->query_insert();
			$admin->makecookie("flood","1",$settings['floodcheck']);

			header("location: $_SERVER[PHP_SELF]");
		}
	}

	function filter($text)
	{
		global $settings;

		if ($settings['filter'] != '')
		{
			$words = explode("\n",$settings['filter']);
				
			if (count($words) > 1)
			{
				foreach($words as $word)
				{
					$text = $this->filter_words($word,$text);
				}
			}
		}

		return $text;
	}

	function filter_words($word,$text)
	{
		$word = trim($word);
		//return str_replace($word,str_repeat("*",strlen($word)),$text);
		return preg_replace("/".$word."/i",str_repeat("*",strlen($word)),$text);
	}

	function shout_main()
	{
		global $evoLANG,$admin,$udb,$database,$tpl,$settings,$_SERVER,$_GET;
		
		require ("./source/class_smilies.php");
		$smi = new Smilies;

		$sql = $udb->query("SELECT * FROM $database[entry] ORDER BY id DESC LIMIT ".$settings['perpage']);
		while ( $row = $udb->fetch_array($sql) )
		{
			$i++;
			$bg = $admin->get_bg($i);

			$row = $admin->strip_array($row);
			$row['entry'] = $settings['allow_html'] == 1 ? $row['entry'] : strip_tags($row['entry']);
			$row['entry'] = $this->filter($row['entry']);
			$row['entry'] = $smi->parse($row['entry']);
			$row['date'] = date($settings['dateformat'],$row['date']);
			$row['gender'] = $this->process_gender($row['gender']);
			$row['email'] = trim($row['email']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'email.gif" alt="'.$row['email'].'" />','mailto:'.$row['email']) : '';

			$row['ym'] = trim($row['ym']) == '' ? '-':$row['ym'];
			$row['icq'] = trim($row['icq']) == '' ? '-':$row['icq'];
			$row['country'] = trim($row['country']) == '' ? '-':$row['country'];
			$row['entry'] = $settings['autobr'] == 1 ? nl2br($row['entry']) : $row['entry'];


			$row['www'] = trim($row['www']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'www.gif" alt="'.$row['www'].'" />',$row['www']) : '';

			$loop .= "<tr><td $bg valign=\"top\"><acronym title=\"$row[date]\"><b>$row[name]</b> $row[email] <br /> $row[entry]</acronym></td></tr>";
		}
		
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
											"name"      => $evoLANG['word_name'],
											"text"        => $evoLANG['shout']
										 );
		// element condition
		$admin->element_condition["name"] = array("Your Name");
		$admin->element_condition["text"] = array("Your Shout");
		$admin->element_condition["email"] = array("email");
		/* ------------------------------------------------ */
		$allshout = $admin->makelink("all shout","javascript:void(window.open('".$_SERVER['PHP_SELF']."?do=viewall','1','width=".($this->shout_width + 50).",height=500,toolbar=no,statusbar=no,scrollbars=yes'))");

		$field['name'] = $this->use_cookie == 1 && $_COOKIE[$this->cookie_prefix."name"] ? $_COOKIE[$this->cookie_prefix."name"]:"Your Name";
		$field['email'] = $this->use_cookie == 1 && $_COOKIE[$this->cookie_prefix."email"] ? $_COOKIE[$this->cookie_prefix."email"]:"email";
		$field['site'] = $this->use_cookie == 1 && $_COOKIE[$this->cookie_prefix."site"] ? $_COOKIE[$this->cookie_prefix."site"]:"http://";


		$admin->inputsize="20";
		$loop = ($loop == "") ? "<tr><td class=\"thrdalt\">$evoLANG[noshouts]</td></tr>":$loop;
		$html .= $admin->form_start("shouter",$_SERVER['PHP_SELF']."?do=submit");
		$html .= $admin->add_spacer($evoLANG['shoutbox'],0);
		$html .= $loop;
		$html .= $admin->add_spacer($allshout,0);
		$html .= "<tr><td class=\"thrdalt\"> ".$admin->form_input($evoLANG['word_name'],$field['name'])."<br />";
		$html .= $admin->form_input($evoLANG['email'],$field['email'],"text")."<br />";
		$html .= $admin->form_input($evoLANG['www'],$field['site'])."<br />";
		$html .= $admin->form_input("entry",$evoLANG['shout'])."<br />";
		$html .= $admin->form_hidden("ip",$admin->get_ip());
		$html .= $admin->form_hidden("agent",$_SERVER['HTTP_USER_AGENT']);
		$html .= "</td></tr>";
		$html .= $admin->form_submit("addentry");
		
		if ($settings['output'] == 1)
		{
			echo $admin->add_table($html,$this->shout_width);
			exit;
		}
		else
		{
			return $admin->add_table($html,$this->shout_width);
		}
		
	}

	function shout_viewall()
	{
		global $evoLANG,$admin,$udb,$database,$tpl,$settings,$_SERVER,$_GET;
		
		require ("./source/class_smilies.php");
		$smi = new Smilies;

		$sql = $udb->query("SELECT * FROM $database[entry] ORDER BY id DESC");
		while ($row = $udb->fetch_array($sql) )
		{
			$i++;
			$bg = $admin->get_bg($i);
			
			$row = $admin->strip_array($row);
			$row['entry'] = $settings['allow_html'] == 1 ? $row['entry'] : strip_tags($row['entry']);
			$row['entry'] = $this->filter($row['entry']);
			$row['entry'] = $smi->parse($row['entry']);
			$row['date'] = date($settings['dateformat'],$row['date']);
			$row['gender'] = $this->process_gender($row['gender']);
			$row['email'] = trim($row['email']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'email.gif" alt="'.$row['email'].'" />','mailto:'.$row['email']) : '';

			$row['ym'] = trim($row['ym']) == '' ? '-':$row['ym'];
			$row['icq'] = trim($row['icq']) == '' ? '-':$row['icq'];
			$row['country'] = trim($row['country']) == '' ? '-':$row['country'];
			$row['entry'] = $settings['autobr'] == 1 ? nl2br($row['entry']) : $row['entry'];


			$row['www'] = trim($row['www']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'www.gif" alt="'.$row['www'].'" />',$row['www']) : '';
			
			$loop .= "<tr><td $bg valign=\"top\"><acronym title=\"$row[date]\"><b>$row[name]</b> $row[email] <br /> $row[entry]</acronym></td></tr>";
		}

			$html .= $admin->add_spacer($evoLANG['shoutbox'],0);
			$html .= $loop;

			$content = $admin->add_table($html,$this->shout_width);	
			eval("\$content = \"".$tpl->gettemplate("popup")."\";");
			echo $content;
			exit;
	}

	function admin_manage()
	{
		global $evoLANG,$admin,$udb,$database,$tpl,$settings,$_SERVER,$_GET;
		
		require ("../source/class_smilies.php");
		$smi = new Smilies;

		$sql = $udb->query("SELECT * FROM $database[entry] ORDER BY id DESC");
		while ($row = $udb->fetch_array($sql) )
		{
			$i++;
			$bg = $admin->get_bg($i);

			$row = $admin->strip_array($row);
			$row['entry'] = $settings['allow_html'] == 1 ? $row['entry'] : strip_tags($row['entry']);
			//$row['entry'] = $this->filter($row['entry']);
			$row['entry'] = $smi->parse($row['entry']);
			$row['date'] = date($settings['dateformat'],$row['date']);
			$row['gender'] = $this->process_gender($row['gender']);
			$row['email'] = trim($row['email']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'email.gif" alt="'.$row['email'].'" />','mailto:'.$row['email']) : '';

			$row['ym'] = trim($row['ym']) == '' ? '-':$row['ym'];
			$row['icq'] = trim($row['icq']) == '' ? '-':$row['icq'];
			$row['country'] = trim($row['country']) == '' ? '-':$row['country'];
			$row['entry'] = $settings['autobr'] == 1 ? nl2br($row['entry']) : $row['entry'];


			$row['www'] = trim($row['www']) != '' ? $admin->makelink('<img src="'.$settings['imgfolder'].'www.gif" alt="'.$row['www'].'" />',$row['www']) : '';

			$rows .= $admin->add_row('<div style="font-weight:normal"><b>'.$row['name'].'</b><br /><span style="font-size:9px">'.$row['date'].'</span><br /><br />'.$row['entry'].'</div>',$admin->makelink($evoLANG['word_edit'],$_SERVER['PHP_SELF'].'?do=edit&amp;id='.$row['id']) .' | '.$this->wrap_confirm($evoLANG['word_delete'],$_SERVER['PHP_SELF'].'?do=delete&amp;id='.$row['id']));
		}

		$rows = $admin->add_spacer($evoLANG['manageentries']).$rows;
		return $admin->add_table($rows,'100%');
	}

	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}

}

?>