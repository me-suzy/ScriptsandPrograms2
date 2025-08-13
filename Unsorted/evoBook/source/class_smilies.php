<?php
// my little smilies class.. muahaha

class Smilies
{
	//start the freekin cons
	function Smilies()
	{
		global $settings,$database;

		$this -> folder = $settings['imgfolder'].'smilies/';
		$this -> db = $database['smilies'];
		$this -> per_row = '3';
	}
	
	function js($form,$inputname)
	{
		return '<script language="Javascript">
function AddInto(what)
{
	document.'.$form.'.'.$inputname.'.value+=what;
}

function smiley(smiley)
{
	 formname = document.'.$form.';
     AddInto(" "+smiley+" ",formname);
}
</script>';
	}

	function init()
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_GET,$_SERVER,$_REQUEST,$_POST;

		switch ( $_GET['do'] )
		{
			/* + ---------------------------- + */
			case 'submit':
				if ($_POST['addsmiley']) $content = $this->process_add();
				if ($_POST['editsmiley']) $content = $this->process_edit();
			break;
			/* + ---------------------------- + */
			case 'add':
				$content = $this->admin_add();
			break;
			/* + ---------------------------- + */
			case 'edit':
				$content = $this->admin_edit($_GET['id']);
			break;
			/* + ---------------------------- + */
			case 'delete':
				$content = $this->admin_delete($_GET['id']);
			break;
			/* + ---------------------------- + */
			case 'showbox':
				$content = $this->admin_testbox();
			break;
			/* + ---------------------------- + */
			default;
				$content = $this->admin_manage();
		}
		
		return $content;
	}
	
	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}

	function process_click($click)
	{
		global $evoLANG;

		return $click == '1' ? $evoLANG['word_yes'] : $evoLANG['word_no'];
	}

	function admin_manage()
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_SERVER,$settings,$root;
		
		$html .= $admin->link_button($evoLANG['addsmiley'],$_SERVER['PHP_SELF'].'?do=add').' ';
		$html .= $admin->link_button('test',$_SERVER['PHP_SELF'].'?do=showbox').'<br /><br />';
		
		$sql = $udb->query("SELECT * FROM ".$this->db." ORDER BY id DESC");

		$rows .= '<tr class="tblhead"><td colspan="4">'.$evoLANG['managesmilies'].'</td></tr>';
		$rows .= '<tr style="font-weight:bold" class="thrdalt" align="center"><td>Before</td><td>After</td><td> Clickable </td> <td> Options </td></tr>';
		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$i++;
			$bg = $admin->get_bg($i);

			$inrows .= '<tr '.$bg.'><td width="25%" align="center"> '.$row['before'].'</td><td width="25%" align="center"><img src="'.str_replace('{smiley_path}',$root.$settings['img_smilies'],$row['after']).'" alt="'.$row['after'].'" /></td><td width="25%" align="center">'.$this->process_click($row['click']).'</td><td width="25%" align="center">'.$admin->makelink($evoLANG['word_edit'],$_SERVER['PHP_SELF'].'?do=edit&amp;id='.$row['id']).' | '.$this->wrap_confirm($evoLANG['word_delete'],$_SERVER['PHP_SELF']."?do=delete&amp;id=".$row['id']).'</td></tr>';
		}
		
		$inrows = $inrows == '' ? '<tr class="thrdalt"><td colspan="4">'.$evoLANG['s_xavail'].'</td></tr>' : $inrows;
		$rows .= $inrows;
		$html .= $admin->add_table($rows,'90%');

		return $html;

	}
	
	function admin_add()
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_SERVER,$settings;
		
		$admin->row_width = '30%';
		$admin->row_align = 'left';

		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"before"        => $evoLANG['word_name'],
										"after"        => $evoLANG['email']
									 );
		/* ------------------------------------------------ */

		$html .= $admin->form_start('form_add',$_SERVER['PHP_SELF'].'?do=submit');
		$rows .= $admin->add_spacer($evoLANG['addsmiley']);
		$rows .= $admin->add_row($evoLANG['s_before'],$admin->form_input('before'),$evoLANG['s_before_d']);
		$rows .= $admin->add_row($evoLANG['s_after'],$admin->form_input('after','{smiley_path}'),$evoLANG['s_after_d']);
		$rows .= $admin->add_row($evoLANG['isclick'],$admin->form_radio_yesno('click',0),$evoLANG['isclick_d']);
		$rows .= $admin->form_submit('addsmiley');
		$html .= $admin->add_table($rows,'90%');
		$html .= '</form>';

		return $html;
	}

	function admin_edit($id='')
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_SERVER,$settings;
		
		if ($id == '') return 'Invalid ID';
		$row = $udb->query_once("SELECT * FROM ".$this->db." WHERE id='".$id."'");
		if (!is_array($row)) return 'Invalid ID';

		$admin->row_width = '30%';
		$admin->row_align = 'left';

		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"before"        => $evoLANG['word_name'],
										"after"        => $evoLANG['email']
									 );
		/* ------------------------------------------------ */

		$html .= $admin->form_start('form_edit',$_SERVER['PHP_SELF'].'?do=submit');
		$html .= $admin->form_hidden('id',$row['id']);

		$rows .= $admin->add_spacer($evoLANG['editsmiley']);
		$rows .= $admin->add_row($evoLANG['s_before'],$admin->form_input('before',$row['before']),$evoLANG['s_before_d']);
		$rows .= $admin->add_row($evoLANG['s_after'],$admin->form_input('after',$row['after']),$evoLANG['s_after_d']);
		$rows .= $admin->add_row($evoLANG['isclick'],$admin->form_radio_yesno('click',$row['click']),$evoLANG['isclick_d']);
		$rows .= $admin->form_submit('editsmiley');
		$html .= $admin->add_table($rows,'90%');
		$html .= '</form>';

		return $html;
	}

	function process_add()
	{
		global $_POST,$udb,$admin,$settings;
		
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $this->db;
		$udb->insert_data = array (
										"before"           => $_POST['before'],
										"after"            => $_POST['after'],
										"click"			   => $_POST['click']
								   );
		$udb->query_insert();
		header("location: $_SERVER[PHP_SELF]");
	}

	function process_edit()
	{
		global $_POST,$udb,$admin;
		
		$_POST = $admin->slash_array($_POST);

		$udb->query("UPDATE ".$this->db."
										SET 
												before='".$_POST['before']."',
												after='".$_POST['after']."',
												click='".$_POST['click']."'
												
												WHERE id='".$_POST['id']."'
					");

		header("location: $_SERVER[PHP_SELF]");
	}

	function admin_delete($id='')
	{
		global $udb;
		
		if ($id=='') return ;

		$udb->query("DELETE FROM ".$this->db." WHERE id='".$id."'");
		header("location: $_SERVER[PHP_SELF]");
	}
	
	function showbox($formname='',$fieldname='',$width='130')
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_SERVER,$settings,$root;

		$sql = $udb->query("SELECT * FROM ".$this->db." WHERE click='1' ORDER BY id DESC");
		$total = $udb->num_rows($sql);
		if ($total > 0)
		{
			while ($row = $udb->fetch_array($sql))
			{
				$i++;
				if ($i > $this->per_row)
				{
					$rows .= '</tr><tr>';
					$i = 1; /* set it back to 1 */
				}

				$smiley = '<a href="javascript:void();" onclick="smiley(\''.htmlspecialchars($row['before']).'\')"><img src="'.str_replace('{smiley_path}',$root.$settings['img_smilies'],$row['after']).'" alt="'.htmlspecialchars($row['before']).'" /></a>';
				$rows .= '<td align="center" width="33%">'.$smiley.'</td>';

				if ($this->per_row - $i != "0")
				{
					$left =  $this->per_row - $i;
				}
			}

			if (isset($left))
			{
				for ($o=0;$o<$left;$o++)
				{
					$rows .= '<td align="center" width="33%">&nbsp;</td>';
				}
			}
		}

		
		$html .= $this->js($formname,$fieldname);
		$html .= '<div class="smilies_box">';
		$html .= '<table border="0" cellpadding="4" cellspacing="0" align="center" width="'.$width.'">';
		$html .= '<tr><td colspan="'.$this->per_row.'" align="center"><b>'.$evoLANG['smilies'].'</b></td></tr><tr>';
		$html .= $rows;
		$html .= '<tr><td colspan="'.$this->per_row.'" align="center"><a href="javascript:void(window.open(\''.$root.'smilies.php?do=showall&amp;for='.$formname.'.'.$fieldname.'\',\'1\',\'width='.($width + 50).',height=250,toolbar=no,statusbar=no,scrollbars=yes\'))">'.$evoLANG['moresmilies'].'</a></td></tr>';
		$html .= '</table></div>';

		return $html;
	}

	function showall($formname='',$width='130')
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_SERVER,$settings,$root;

		$sql = $udb->query("SELECT * FROM ".$this->db." ORDER BY id DESC");
		$total = $udb->num_rows($sql);
		if ($total > 0)
		{
			while ($row = $udb->fetch_array($sql))
			{
				$i++;
				if ($i > $this->per_row)
				{
					$rows .= '</tr><tr>';
					$i = 1; /* set it back to 1 */
				}

				$smiley = '<a href="javascript:void();" onclick="smilies(\''.htmlspecialchars($row['before']).'\')"><img src="'.str_replace('{smiley_path}',$root.$settings['img_smilies'],$row['after']).'" alt="'.htmlspecialchars($row['before']).'" /></a>';
				$rows .= '<td align="center" width="33%">'.$smiley.'</td>';

				if ($this->per_row - $i != "0")
				{
					$left =  $this->per_row - $i;
				}
			}

			if (isset($left))
			{
				for ($o=0;$o<$left;$o++)
				{
					$rows .= '<td align="center" width="33%">&nbsp;</td>';
				}
			}
		}

		
		//$html .= $this->js($formname,$fieldname);
		$html .= '<script language="javascript">
<!--
function smilies(smile)
{
	opener.document.'.$formname.'.value += \' \'+smile+\' \';
}
//-->
</script>';
		$html .= '<div class="smilies_box">';
		$html .= '<table border="0" cellpadding="4" cellspacing="0" align="center" width="'.$width.'">';
		$html .= '<tr><td colspan="'.$this->per_row.'" align="center"><b>'.$evoLANG['smilies'].'</b></td></tr><tr>';
		$html .= $rows;
		$html .= '<tr><td colspan="'.$this->per_row.'" align="center">'.$admin->link_button('Close','javascript:window.close();').'</td></tr>';
		$html .= '</table></div>';
		

		return $html;
	}

	function admin_testbox()
	{
		global $admin;
		
		$return .= $admin->form_start('test',$_SERVER['PHP_SELF'].'?do=showbox');
		$return .= $this->showbox('test','content');
		$return .= '<br /><br /><b>Test Smilies</b><br />';
		$return .= $admin->form_textarea('content');
		return $return;
	}

	function parse($text)
	{
		global $admin,$database,$udb,$tpl,$evoLANG,$_SERVER,$settings,$root;

		$sql = $udb->query("SELECT * FROM ".$this->db." ORDER BY id DESC");
		while ($row = $udb->fetch_array($sql))
		{
			$text = str_replace($row['before'],'<img src="'.str_replace('{smiley_path}',$root.$settings['img_smilies'],$row['after']).'" alt="" />',$text);
		}

		return $text;
	}

}	

?>