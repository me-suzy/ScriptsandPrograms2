<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

/**
 * Helper class focused on creating misc form elements
 * It is used only by the views, but is not descended from basic_view so no
 * information about context, objectid etc is available in the class. Any
 * information must be passed as parameters to the methods. Only userhandler
 * can be expected.
 */

class basic_field {
	var $userhandler;
	var $view;
	function basic_field(&$view) {
		$this->view = &$view;
		$this->userhandler =& GetUserHandler();
	}

	function userSelection($objectid, $fieldname) {
		$result .= '<select name="'.$fieldname.'[]" style="width: 260px;" size=8 multiple>';
		$arr = array();
		if ($objectid) {
			$obj = owRead($objectid);
			$arr = $obj->getmembers();
		}
		$cobj = owNew('user');
		$cobj->setsort_col('name');
		$cobj->setfilter_nameonly(true);
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		$sel = '';
		if (!is_array($arr)) $sel = ' SELECTED';
		$result .= '<option value=""'.$sel.'>'.$this->view->gl('select_none').'</option>';
		while ($z < $cobj->elementscount) {
			$sel = '';
			if (is_array($arr) && in_array($cobj->elements[$z]['objectid'],$arr)) $sel = ' SELECTED';
			$result .= '<option value="'.$cobj->elements[$z]['objectid'].'"'.$sel.'>'.$cobj->elements[$z]['name'].'</option>';
			$z++;
		}
		unset($cobj);
		$result .= '</select>';
		return $result;
	}

	function usergroupSelection($objectid, $fieldname) {
		$result .= '<select name="'.$fieldname.'[]" style="width: 260px;" size=8 multiple>';
		$arr = array();
		if ($objectid) {
			$obj = owRead($objectid);
			$arr = $obj->getgroupmemberships($objectid);
		}
		$cobj = owNew('usergroup');
		$cobj->setsort_col('name');
		$cobj->setfilter_nameonly(true);
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		while ($z < $cobj->elementscount) {
			$sel = '';
			if (is_array($arr) && in_array($cobj->elements[$z]['objectid'],$arr)) $sel = ' SELECTED';
			$result .= '<option value="'.$cobj->elements[$z]['objectid'].'"'.$sel.'>'.$cobj->elements[$z]['name'].'</option>';
			$z++;
		}
		unset($cobj);
		$result .= '</select>';
		return $result;
	}

	function categorySelection($objectid, $fieldname, $datatype = '') {
		$result .= '<select name="'.$fieldname.'[]" style="width: 260px;" size=8 multiple>';
		$result .= '<option value="0">'.$this->view->gl('select_none').'</option>';
		if ($objectid != NULL) $obj = owRead($objectid);
		$cobj = owNew('category');
		$cobj->setsort_col('name');
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		/* usually we want to display the categories available to the current
		   datatype, but in some situations we want to display the categories
		   available to a different datatype, for instance when we import
		   data using sys_view_import */
		if ($datatype == '') $datatype = $this->view->otype;
		while ($z < $cobj->elementscount) {
			if ($cobj->elements[$z]['datatype'] == $datatype || $cobj->elements[$z]['datatype'] == '') {
				$sel = '';
				if ($objectid != NULL && $obj->ismember($cobj->elements[$z]['objectid'])) $sel = ' SELECTED';
				$result .= '<option value="'.$cobj->elements[$z]['objectid'].'"'.$sel.'>'.$cobj->elements[$z]['name'].'</option>';
			}
			$z++;
		}
		unset($cobj);

		$result .= '</select>';
		return $result;
	}

	function webAccessSelection($objectid, $fieldname) {
		$result .= '<select name="'.$fieldname.'[]" style="width: 260px;" size=8 multiple>';
		$arr = array();
		if ($objectid != NULL) {
			$obj = owRead($objectid);
			$tarr = $obj->getaccess();
		}
		if (is_array($tarr)) {
			foreach ($tarr as $carr) {
				if ($carr['user_read']) $arr[] = $carr['user_read'];
				if ($carr['group_read']) $arr[] = $carr['group_read'];
			}
		}

		$cobj = owNew('user');
		$cobj->setsort_col('name');
		$cobj->setfilter_nameonly(true);
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		$sel = '';
		if (!is_array($tarr)) $sel = ' SELECTED';
		$result .= '<option value=""'.$sel.'>'.$this->view->gl('select_all').'</option>';
		while ($z < $cobj->elementscount) {
			$sel = '';
			if (in_array($cobj->elements[$z]['objectid'],$arr)) $sel = ' SELECTED';
			$result .= '<option value="'.$cobj->elements[$z]['objectid'].'"'.$sel.'>'.$cobj->elements[$z]['name'].'</option>';
			$z++;
		}
		unset($cobj);

		$cobj = owNew('usergroup');
		$cobj->setsort_col('name');
		$cobj->setfilter_nameonly(true);
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		while ($z < $cobj->elementscount) {
			$sel = '';
			if (in_array($cobj->elements[$z]['objectid'],$arr)) $sel = ' SELECTED';
			$result .= '<option value="-'.$cobj->elements[$z]['objectid'].'"'.$sel.'>['.$cobj->elements[$z]['name'].']</option>';
			$z++;
		}
		unset($cobj);

		$result .= '</select>';
		return $result;
	}

	function sysAccessSelection($objectid, $fieldname) {
		$result .= '<select name="'.$fieldname.'[]" style="width: 260px;" size=8 multiple>';
		$arr = array();
		if ($objectid != NULL) {
			$obj = owRead($objectid);
			$tarr = $obj->getaccess();
		}
		if (is_array($tarr)) {
			foreach ($tarr as $carr) {
				if ($carr['user_write']) $arr[] = $carr['user_write'];
				if ($carr['group_write']) $arr[] = $carr['group_write'];
			}
		}

		$cobj = owNew('user');
		$cobj->setsort_col('name');
		$cobj->setfilter_nameonly(true);
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		$sel = '';
		if (!is_array($tarr)) $sel = ' SELECTED';
		$result .= '<option value=""'.$sel.'>'.$this->view->gl('select_owner').'</option>';
		while ($z < $cobj->elementscount) {
			$sel = '';
			if (in_array($cobj->elements[$z]['objectid'],$arr)) $sel = ' SELECTED';
			$result .= '<option value="'.$cobj->elements[$z]['objectid'].'"'.$sel.'>'.$cobj->elements[$z]['name'].'</option>';
			$z++;
		}
		unset($cobj);

		$cobj = owNew('usergroup');
		$cobj->setsort_col('name');
		$cobj->setfilter_nameonly(true);
		$cobj->setlistaccess(true);
		$cobj->listobjects();
		$z = 0;
		while ($z < $cobj->elementscount) {
			$sel = '';
			if (in_array($cobj->elements[$z]['objectid'],$arr)) $sel = ' SELECTED';
			$result .= '<option value="-'.$cobj->elements[$z]['objectid'].'"'.$sel.'>['.$cobj->elements[$z]['name'].']</option>';
			$z++;
		}
		unset($cobj);

		$result .= '</select>';
		return $result;
	}

	/**
	 * @todo Move all the following functions to appropriate methods in the view hierarchy
	 */

	function listallobjects($type, $value, $emptynone = false) {
		$obj = owNew($type);

		if (in_array('default',$obj->getviews())) {
			$std = true;
			$res = '<option value="0">'.$this->view->gl('select_standard').'</option>';
		} else {
			$std = false;
			if ($emptynone) {
				$res = '<option value="">'.$this->view->gl('select_none').'</option>';
			} else {
				$res = '<option value="0">'.$this->view->gl('select_none').'</option>';
			}
		}

		$obj->setlistaccess(true);
		
		$obj->setsort_col('name');
		$obj->listobjects();
		$z = 0;
		if (is_array($value)) {
			$selected = array();
			$unselected = array();
			while ($z < $obj->elementscount) {
		  		if ($std && $obj->elements[$z]['standard'] == 1) {
		  			$standard = ' '.$this->view->gl('select_standard');
		  		} else {
		  			$standard = '';
		  		}

				if (in_array($obj->elements[$z]['objectid'],$value)) {
					$selected[$obj->elements[$z]['objectid']] = $obj->elements[$z]['name'] . $standard;
				} else {
					$unselected[$obj->elements[$z]['objectid']] = $obj->elements[$z]['name'] . $standard;
				}
				$z++;
			}
			
			foreach ($selected as $objectid=>$name) {
				$res .='<option value="' . $objectid . '" SELECTED>' . $name."\n";
			}	
			foreach ($unselected as $objectid=>$name) {
				$res .='<option value="' . $objectid . '">' . $name."\n";
			}	
	
		} else {
			while ($z < $obj->elementscount) {
		  		if ($std && $obj->elements[$z]['standard'] == 1) {
		  			$standard = ' '.$this->view->gl('select_standard');
		  		} else {
		  			$standard = '';
		  		}
				$selection = '';
				if (is_array($value)) {
					if (in_array($obj->elements[$z]['objectid'],$value)) $selection = ' SELECTED';
				} else {
	  			if ($obj->elements[$z]['objectid'] == $value) $selection = ' SELECTED';
	  		}
	  		$name = $obj->elements[$z]['name'] . $standard;
		  	if ($type == 'item') $name = $obj->elements[$z]['name'] . ' ' . $obj->elements[$z]['content1'];
				$res .='<option value="' . $obj->elements[$z]['objectid'] . '"'.$selection.'>' .$name. "\n";
				$z++;
			}
		
		}
		return $res;
	}

	function listallusers($value) {
		$obj = owNew('user');
		$obj->setlistaccess(true);
		$obj->setfilter_nameonly(true);
		$obj->setsort_col('name');
		$obj->listobjects();
		$z = 0;
		while ($z < $obj->elementscount) {
			$selection = '';
  			if ($obj->elements[$z]['objectid'] == $value) $selection = ' SELECTED';
			$res .='<option value="' . $obj->elements[$z]['objectid'] . '"'.$selection.'>' . $obj->elements[$z]['name'] ."\n";
			$z++;
		}
		return $res;
	}

	function listallcategories($value) {
		$res = '<option value="0">'.$this->view->gl('select_category').'</option>';
		$obj = owNew('category');
		$obj->setlistaccess(true);
		$obj->setsort_col('name');
		$obj->listobjects();
		$z = 0;
		while ($z < $obj->elementscount) {
			$selection = '';
	  		if ($obj->elements[$z]['objectid'] == $value) $selection = ' SELECTED';

			if ($obj->elements[$z]['datatype'] == $this->view->otype || $obj->elements[$z]['datatype'] == '')
				$res .='<option value="' . $obj->elements[$z]['objectid'] . '"'.$selection.'>' . $obj->elements[$z]['name'] . "\n";
			$z++;
		}
		return $res;
	}


	function listAllLanguages($value=null) {
		$res = '<option value="">'.$this->view->gl('select_language').'</option>';
    	$languages = system::getlanguages();
		for($i = 0, $n = sizeOf($languages); $i < $n; $i++) {
			$selection = "";
	  		if ($languages[$i]['langcode'] == $value) $selection = ' SELECTED';
			$res .='<option value="' . $languages[$i]['langcode'] . '"'.$selection.'>' . $languages[$i]['language'] . " (". $languages[$i]['langcode'] . ")\n";
		}
		return $res;
	}


	function listCountries($value=null) {
		$res = '<option value="">'.$this->view->gl('select_country').'</option>';
    	$languages = system::getCountries();
		for($i = 0, $n = sizeOf($languages); $i < $n; $i++) {
			$selection = "";
	  		if ($languages[$i]['countrycode'] == $value) $selection = ' SELECTED';
			$res .='<option value="' . $languages[$i]['countrycode'] . '"'.$selection.'>' . $languages[$i]['country'] . " (". $languages[$i]['countrycode'] . ")\n";
		}
		return $res;
	}

	function listallclasses($value) {
		$res = '<option value="">'.$this->view->gl('select_table').'</option>';
	    $datatypes = owListCore();
	    $dt = array();
		foreach ($datatypes as $cur) {
			$idx = owDatatypeDesc($cur);
			$dt[$idx]['datatype'] = $cur;
			$dt[$idx]['datatypename'] = $idx;
			$dt[$idx]['selected'] = '';
			if ($cur == $value) $dt[$idx]['selected'] = ' SELECTED';
		}
		ksort($dt);
		foreach ($dt as $cur) {
			$res .='<option value="'.$cur['datatype'].'"'.$cur['selected'].'>'.$cur['datatypename']."</option>\n";
		}
		return $res;
	}

	function listallapps($value) {
	    $apps = owGetApps();
		foreach ($apps as $app) {
			$selected = '';
			if (is_array($value)) {
				if (in_array($app['app'], $value)) $selected = ' SELECTED';
			} else {
				if ($app['app'] == $value) $selected = ' SELECTED';
			}
			$res .='<option value="'.$app['app'].'"'.$selected.'>'.$app['name']."</option>\n";
		}
		return $res;
	}

	function tmpGetExtName($value) {
		if ($this->userhandler->getGuiLanguage() == 'da') {
			switch ($value) {
				case "shop":
					return "Webshop";
				case "search":
					return "Søgning";
				case "index":
					return "Sideoversigt";
				case "indexadv":
					return "Sideoversigt med søgning";
				case "forum":
					return "Debatforum";
				case "register":
					return "Brugerregistrering";
				case "changepassword":
					return "Skift kodeord";
				case "forgottenpassword":
					return "Glemt kodeord";
				case "slide":
					return "Slide præsentation";
				case "bulletinboard":
					return "Opslagstavle";
				case "login":
					return "Login";
				case "sitemap":
					return "Sitemap";
				case "gallery":
					return "Billedgalleri";
				case "cform":
					return "Brugerdefineret formular";
				case "listing":
					return "Liste";
				case "uptodate":
					return "Up-To-Date";
				case "filelist":
					return "Filudgivelse";
				case "article":
					return "Artikel liste";
				default:
					return false;
			}
		} else {
			switch ($value) {
				case "shop":
					return "Webshop";
				case "search":
					return "Site search";
				case "index":
					return "Page index";
				case "indexadv":
					return "Page index with search";
				case "forum":
					return "Bulletin board";
				case "register":
					return "User registration";
				case "changepassword":
					return "Change password";
				case "forgottenpassword":
					return "Forgotten password";
				case "slide":
					return "Slide presentation";
				case "bulletinboard":
					return "Simple bulletin board";
				case "login":
					return "Login";
				case "sitemap":
					return "Sitemap";
				case "gallery":
					return "Picture gallery";
				case "cform":
					return "Custom form";
				case "listing":
					return "List";
				case "uptodate":
					return "Up-To-Date";
				case "filelist":
					return "File publish";
				case "article":
					return "Article list";
				default:
					return false;
			}
		}
	}
	
	function listallcomponents($value) {
		$res = '<option value="">'.$this->view->gl('select_extension').'</option>';
	    $datatypes = owListExtensions();
	    $dt = array();
		foreach ($datatypes as $cur) {
			$idx = $this->tmpGetExtName($cur);
			if ($idx) {
				$dt[$idx]['datatype'] = $cur;
				$dt[$idx]['datatypename'] = $idx;
				$dt[$idx]['selected'] = '';
				if ($cur == $value) $dt[$idx]['selected'] = ' SELECTED';
			}
		}
		ksort($dt);
		foreach ($dt as $cur) {
			$res .='<option value="'.$cur['datatype'].'"'.$cur['selected'].'>'.$cur['datatypename']."\n";
		}
		return $res;
	}

	function parsefield($field, $value, $con = IN_FORM, $variant = false, $variantfields = array()) {
		
		$system_url = $this->userhandler->getSystemUrl();
		$visible = true;
		if ($field['inputtype'] == UI_HIDDEN || 
			($field['inputtype'] == UI_NOTHING && $con == IN_VIEW) ||
			($field['obj'] && !$field['obj']->visible())
				) $visible = false;
		
		/* UI_READONLY field not visible in create forms, skip processing */
		if ($this->view->view == 'create' && $field['inputtype'] == UI_READONLY) return '';

		ob_start();

		/* set label to column name, if label is empty and inputtype is not UI_HIDDEN */
		if ($field['label'] == '' && $visible) $field['label'] = $field['name'];

		if ($con == IN_FORM || $con == IN_VIEW) {
			if (!isset($field['skipstart'])) {
				echo '<div id="fieldset_' . $field['name'].'"class="mformfieldset" style="'.$field['fieldsetstyle'];
				if (!$visible) echo "display: none;";
				echo '">';
				$labelclass = (isset($field['labelclass'])) ? $field['labelclass'] : 'mformlabel';
			} else {
				$labelclass = (isset($field['labelclass'])) ? $field['labelclass'] : 'mformlabelshort';
			}
			if ($visible) {
				echo '<div id="label_' . $field['name'] .'"class="'.$labelclass.'"  style="'.$field['labelstyle'].'">'.$field['label'].'</div>';
				echo '<div class="mformfield" style="'.$field['fieldstyle'].'">';

				if ($variant) {
					$onchange = 'field = document.getElementById(\''.$field['name'].'\'); if (this.checked) { field.disabled = false; field.style.backgroundColor = \'#ffffff\'; } else { field.style.backgroundColor = \'#cccccc\'; field.disabled = true;}';
					$checked = '';
					if (is_array($variantfields) && in_array($field['name'], $variantfields)) $checked = ' CHECKED';
					echo '<input type="checkbox" name="__var__'.$field['name'].'" id="__var__'.$field['name'].'" onclick="'.$onchange.'"'.$checked.'>';
				}
			}
		}
		if ($field['obj']) {
			$field['obj']->setValue($value);
			$field['obj']->setView($this->view);
			if (!empty($field['relation'])) $field['obj']->setRelation($field['relation']);
			if (!empty($field['validate'])) $field['obj']->setValidate($field['validate']);
			if (!empty($field['style'])) 		$field['obj']->addStyle($field['style']);
			if (!empty($field['comboarray'])) $field['obj']->setComboArray($field['comboarray']);
			echo $field['obj']->output($con);
		} else {
		switch ($field['inputtype']) {
			case UI_DECIMAL:
				if ($con == IN_FORM) {
					echo '<input type="text" validate="'.$field['validate'].'" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$value.'" style="text-align: right; width: 100px; '.$field['style'].'">';
				} else {
					echo $value;
				}
				break;

			case UI_NOTHING:
				$value = '';
				// FALLTHRU

			case UI_READONLY:
				if ($con == IN_FORM) {
					echo '<div name="'.$field['name'].'" id="'.$field['name'].'" style="padding-top: 3px;'.$field['style'].'">'.nl2br($value).'</div>';
				} elseif ($con == IN_VIEW) {
					echo '<div style="padding-top: 3px;'.$field['style'].'">'.nl2br($value).'</div>';
				} else {
					echo substr($value, 0, 70);
				}
				break;

			case UI_PHONE:
				if ($con == IN_FORM) {
					echo '<input type="text" validate="'.$field['validate'].'" name="'.$field['name'].'" id="'.$field['name'].'" value="'.htmlspecialchars($value).'" style="width: 350px; '.$field['style'].'">';
					echo '<input type="button" value="R" onclick="if (top.topmenu) { var dde=top.topmenu.document.all.dde; } else { var dde=document.all.dde; } if (dde) { var handle = dde.connect(\'CCABP\',\'CallControl\'); dde.execute(handle,\'MAKECALL \' + this.form.'.$field['name'].'.value, 0); }; if (typeof(opencomment) != \'undefined\') { opencomment(); }">';
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_STRING:
				if ($con == IN_FORM) {
					echo '<input type="text" validate="'.$field['validate'].'" name="'.$field['name'].'" id="'.$field['name'].'" value="'.htmlspecialchars($value).'" style="width: 350px; '.$field['style'].'">';
				} elseif ($con == IN_VIEW) {
					echo '<div name="'.$field['name'].'" id="'.$field['name'].'" style="padding-top: 3px;'.$field['style'].'">'.nl2br($value).'</div>';
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_STRING_LITERAL:
				if ($con == IN_FORM) {
					echo '<input type="text" validate="'.$field['validate'].'" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$value.'" style="width: 350px; '.$field['style'].'">';
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_TEXT:
				if ($con == IN_FORM) {
					echo '<textarea validate="'.$field['validate'].'" style="width: 90%; height: 200px; font-family: courier-new, monospace; '.$field['style'].'" name="'.$field['name'].'" wrap=off>'.htmlspecialchars($value).'</textarea>';
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_TEXT_LITERAL:
				if ($con == IN_FORM) {
					echo '<textarea validate="'.$field['validate'].'" style="width: 90%; height: 200px; font-family: courier-new, monospace; '.$field['style'].'" name="'.$field['name'].'" wrap=off>'.$value.'</textarea>';
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_TEXT_WRAP:
				if ($con == IN_FORM) {
					echo '<textarea validate="'.$field['validate'].'" style="width: 90%; height: 200px; font-family: courier-new, monospace; '.$field['style'].'" name="'.$field['name'].'" wrap=on>'.htmlspecialchars($value).'</textarea>';
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_TEXT_LITERAL_WRAP:
				if ($con == IN_FORM) {
					echo '<textarea validate="'.$field['validate'].'" style="width: 90%; height: 200px; font-family: courier-new, monospace; '.$field['style'].'" name="'.$field['name'].'" wrap=on>'.$value.'</textarea>';
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
				break;

			case UI_PASSWORD:
				if ($con == IN_FORM) {
					echo '<input type="password" validate="'.$field['validate'].'" name="'.$field['name'].'" value="'.$value.'" style="width: 350px; '.$field['style'].'">';
				} else {
					echo '**********';
				}
				break;

			case UI_LISTDIALOG:
			case UI_LISTDIALOG_STRING:
				$text = '';
				if ($value && is_numeric($value)) {
					$text = owReadName($value);
				}
				if ($con == IN_FORM) {
					if ($field['inputtype'] == UI_LISTDIALOG) {
					?>
					<input type="hidden" validate="<?php echo $field['validate'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $value ?>">
					<?php
					} else {
					?>
					<input type="text" validate="<?php echo $field['validate'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $value ?>">
					<?php
					}
					echo '<div name="listdialog_'.$field['name'].'" id="listdialog_'.$field['name'].'" style="padding-top: 3px; display: inline;'.$field['style'].'">'.$text.'</div>';
					?>
					<img src="<? echo $this->userhandler->getSystemUrl(); ?>image/Open.GIF" class="mButtonMouseOverUp" style="padding-left: 10px; vertical-align: middle; float: left"
					onclick = "
					var src = <?php 
						if (isset($field['masterfield'])) {
							echo $this->view->ListDialog($field['relation'],'','','splitdialog','','','_relcol='.$field['foreigncolumn'].'&_relval='."' + document.forms.metaform.".$field['masterfield'].".options[document.forms.metaform.".$field['masterfield'].".selectedIndex].value + '");
						} else {
							echo $this->view->ListDialog($field['relation'],'','','splitdialog'); 
						}
					?>
					if (src) {
						<?php
						if ($field['inputtype'] == UI_LISTDIALOG) {
						?>
							document.forms[0].<?php echo $field['name'] ?>.value = src.id;
							var element = document.getElementById('listdialog_<?php echo $field['name']; ?>');
							if (element)
								element.innerText = src.name;
						<?php
						} else {
						?>
							document.forms[0].<?php echo $field['name'] ?>.value = src.name;
						<?
						}
						?>
					}
					">

					<img src="image/delete.gif" class="mButton" onmouseover="this.className='mButtonOver'" onmouseout="this.className='mButton'" style="vertical-align: top; float: left;"
					onclick = "
						<?php
						if ($field['inputtype'] == UI_LISTDIALOG) {
						?>
							document.forms[0].<?php echo $field['name'] ?>.value = 0;
							var element = document.getElementById('listdialog_<?php echo $field['name']; ?>');
							if (element)
								element.innerText = '';
						<?php
						} else {
						?>
							document.forms[0].<?php echo $field['name'] ?>.value = 0;
						<?
						}
						?>
					">

					<?php
				} else {
					echo $text;
				}
				break;

			case UI_BINFILE:
				if ($con == IN_FORM) {
					?>
					<input type="hidden" validate="<?php echo $field['validate'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $value ?>" style="width: 350px; <?php echo $field['style'] ?>">
					<img src="image/Open.GIF" class="mButton" onmouseover="this.className='mButtonOver'" onmouseout="this.className='mButton'" style="vertical-align: top; float: left;"
					onclick = "
					var src = <?php echo $this->view->ListDialog('binfile','','','initdialog'); ?>
					if (src) {
						<?php echo $field['name'] ?>.value = src.id;
						document.getElementById('_txt<?php echo $field['name'] ?>').innerHTML= src.name;
					}
					">
					<img src="image/delete.gif" class="mButton" onmouseover="this.className='mButtonOver'" onmouseout="this.className='mButton'" style="vertical-align: top; float: left;"
					onclick = "
						<?php echo $field['name'] ?>.value = 0;
						document.getElementById('_txt<?php echo $field['name'] ?>').innerHTML= '';
					">
					<?php
				}
				if ($con == IN_FORM) {
					echo '<div id="_txt'.$field['name'].'" style="float: left; padding-top: 5px;">&nbsp;';
					if (!empty($value)) echo owReadName($value);
					echo '</div>';
				} else {
					if (!empty($value)) {
						echo '<img name="_img'.$field['name'].'" src="getfileicon.php?objectid='.$value.'">';
					} else {
						echo '<img name="_img'.$field['name'].'" src="image/nothing.gif">';
					}
				}
				break;

			case UI_BINFILE_THUMB:
				if (!empty($value)) {
					echo '<img name="_img'.$field['name'].'" src="getfilethumb.php?w=150&objectid='.$value.'">';
				} else {
					echo '<img name="_img'.$field['name'].'" src="image/nothing.gif">';
				}
				if ($con == IN_FORM) {
					?>
					<input type="hidden" validate="<?php echo $field['validate'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $value ?>" style="width: 350px; <?php echo $field['style'] ?>">
					<img src="image/Open.GIF" class="mButton" onmouseover="this.className='mButtonOver'" onmouseout="this.className='mButton'" style="vertical-align: top; float: left;"
					onclick = "
					var src = <?php echo $this->view->ListDialog('binfile','','','initdialog'); ?>
					if (src) {
						<?php echo $field['name'] ?>.value = src.id;
						_img<?php echo $field['name'] ?>.src='getfilethumb.php?w=150&objectid=' + src.id;
					}
					">
					<img src="image/delete.gif" class="mButton" onmouseover="this.className='mButtonOver'" onmouseout="this.className='mButton'" style="vertical-align: top; float: left;"
					onclick = "
						<?php echo $field['name'] ?>.value = 0;
						_img<?php echo $field['name'] ?>.src='image/nothing.gif';
					">
					<?php
				}
				break;

			case UI_HIDDEN:
				if ($con == IN_FORM) {
					echo '<input type="hidden" validate="'.$field['validate'].'" name="'.$field['name'].'" value="'.$value.'">';
				}
				break;

			case UI_CHECKBOX:
				if ($con == IN_FORM) {
					echo '<input type="hidden" name="'.$field['name'].'" value="0">';
					echo '<input type="checkbox" validate="'.$field['validate'].'" name="'.$field['name'].'" value="1"';
					if ($value == 1) echo ' checked';
					echo ' style="'.$field['style'].'">';
				} else {
					echo '<img src="image/view/infocol_default.gif">';
				}
				break;

			case UI_FILEUPLOAD:
				if ($con == IN_FORM) {
					echo '<input type="hidden" name="MAX_FILE_SIZE" value="80000000" />';
					echo '<input type="file" validate="'.$field['validate'].'" name="'.$field['name'].'" style="'.$field['style'].'">';
				}
				break;

			case UI_LANGUAGE:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'" style="width:260px; '.$field['style'].'">';
					echo $this->listalllanguages($value);
					echo '</select>';
				} else {
					echo $value;
				}
				break;

			case UI_COUNTRY:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'" style="width:260px; '.$field['style'].'">';
					echo $this->listCountries($value);
					echo '</select>';
				} else {
					echo $value;
				}
				break;

			case UI_CLASS:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'" style="width:260px; '.$field['style'].'">';
					echo $this->listallclasses($value);
					echo '</select>';
				} else {
					echo owDatatypeDesc($value);
				}
				break;

			case UI_APP:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'" style="width:260px; '.$field['style'].'">';
					echo $this->listallapps($value);
					echo '</select>';
				} else {
					echo $value;
				}
				break;

			case UI_APP_MULTIPLE:
				if ($con == IN_FORM) {
					echo '<select name="'.$field['name'].'[]" style="width:260px; '.$field['style'].'" MULTIPLE SIZE=8>';
					echo $this->listallapps($value);
					echo '</select>';
				} else {
					echo $value;
				}
				break;

			case UI_COMPONENT:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'" style="width:260px; '.$field['style'].'">';
					echo $this->listallcomponents($value);
					echo '</select>';
				} else {
					echo $value;
				}
				break;

			case UI_COMBO:
				if ($con == IN_FORM) {
					?>
					<select validate="<?php echo $field['validate']?>" name="<?php echo $field['name'] ?>" style="width:260px; <?php echo $field['style'] ?>">
					<?php
					if (is_array($field['comboarray'])) {
						foreach ($field['comboarray'] as $key => $val) {
							$selected = '';
							if ($value == $key) $selected = ' SELECTED';
							echo '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
						}
					}
					?>
					</select>
					<?php
				} else {
					echo $field['comboarray'][$value];
				}
				break;

			case UI_COMBO_MULTIPLE:
				if ($con == IN_FORM) {
					$selecteditems = array();
					$unselecteditems = array();
					if (is_array($field['comboarray'])) {
						foreach ($field['comboarray'] as $key=>$val) {
							if (is_array($value) && in_array($key, $value)) 
								$selecteditems[$key] = $val;
							else
								$unselecteditems[$key] = $val;
						}
					}
					?>
					<select validate="<?php echo $field['validate'] ?>" name="<?php echo $field['name'] ?>[]" style="width:260px; <?php echo $field['style'] ?>" MULTIPLE SIZE=8>
					<?php
					/*if (is_array($field['comboarray'])) {
						foreach ($field['comboarray'] as $key => $val) {
							$selected = '';
							if (in_array($key,$value)) $selected = ' SELECTED';
							echo '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
						}
					}*/
					foreach ($selecteditems as $key=>$val) {
						echo '<option value="' . $key . '" SELECTED>' . $val . "</option>\n";
					}
					foreach ($unselecteditems as $key=>$val) {
						echo '<option value="' . $key . '">' . $val . "</option>\n";
					}
					?>
					</select>
					<?php
				} else {
					if (is_array($value)) {
						$str = '';
						foreach ($value as $val) {
							if ($str) $str .= ', ';
							$str .= $field['comboarray'][$val];
						}
					}
					echo $str;
				}
				break;

			case UI_RELATION:
			case UI_RELATION_NODEFAULT:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'" style="width:260px; '.$field['style'].'">';
					echo $this->listallobjects($field['relation'], $value);
					echo '</select>';

					if (isset($field['detailfield'])) {

						$jsstr = '
							if (typeof(document.metaform.'.$field['detailfield'].'.options) != \'undefined\' && typeof(' . $field['name'] . '_data) != \'undefined\') {
								var selectedValue = document.metaform.'.$field['name'].'.options[document.metaform.'.$field['name'].'.selectedIndex].value;
								var origvalue = document.metaform.'.$field['detailfield'].'.options[document.metaform.'.$field['detailfield'].'.selectedIndex].value;
								document.metaform.'.$field['detailfield'].'.options.length = 1;
								var values = '.$field['name'].'_data[selectedValue];

								var selectedIndex = 0;
								if (typeof(values) != \'undefined\' && values.length) {
									for (var i = 0; i < values.length; i++) {
										document.metaform.'.$field['detailfield'].'.options[document.metaform.'.$field['detailfield'].'.options.length] = new Option(values[i][1], values[i][0]);
										if (values[i][0] == origvalue) selectedIndex = i + 1;
									}
								}

								document.metaform.'.$field['detailfield'].'.selectedIndex = selectedIndex;
							}';
						$this->view->context->addOnLoad('document.metaform.'.$field['name'].'.onchange');
						echo '<script type="text/javascript">
						document.metaform.'.$field['name'].'.onchange = function() {
	   					'.$jsstr.'
					   //if (foreignfield.onchange)
	   					//foreignfield.onchange();

						}
						</script>';
					}
				} else {
					echo owReadName($value);
				}
				break;

			case UI_RELATION_MULTIPLE:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'[]" style="width:260px; '.$field['style'].'" MULTIPLE SIZE=8>';
					echo $this->listallobjects($field['relation'], $value, true);
					echo '</select>';
				} else {
					if (is_array($value)) {
						$str = '';
						foreach ($value as $val) {
							if ($str) $str .= ', ';
							$str .= owReadName($val);
						}
					}
					echo $str;
				}
				break;

			case UI_USERSGROUPS:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'[]" style="width:260px; '.$field['style'].'">';
					echo $this->listallobjects('user', $value, true);
					echo $this->listallobjects('usergroup', $value, true);
					echo '</select>';
				}
				break;

			case UI_USERSGROUPS_MULTIPLE:
				if ($con == IN_FORM) {
					echo '<select validate="'.$field['validate'].'" name="'.$field['name'].'[]" style="width:260px; '.$field['style'].'" MULTIPLE SIZE=8>';
					echo $this->listallobjects('user', $value, true);
					echo $this->listallobjects('usergroup', $value, true);
					echo '</select>';
				}
				break;
			
			case UI_DATE:
				static $calendarjsadded = false;
				if ($con == IN_FORM) {
					if (!$calendarjsadded) {
						$calendarjsadded = true;
						$this->view->context->addHeader("<style type=\"text/css\">@import url(".$system_url."js/calendar/calendar-system.css);</style>");
						$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$system_url."js/calendar/calendar.js\"></script>\n");
						$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$system_url."js/calendar/lang/calendar-da.js\"></script>\n");
						$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$system_url."js/calendar/calendar-setup.js\"></script>\n");
					}
					echo '<input type="text" validate="'.$field['validate'].'" name="'.$field['name'].'" id="'.$field['name'].'" value="'.htmlspecialchars($value).'" style="width: 65px; '.$field['style'].'" readonly calendar>&nbsp;';
					echo '<img src="'.$system_url.'image/cal/cal.gif" class="mButton" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'" style="vertical-align: top;" id="button_' . $field['name'] . '">';
					$this->view->context->addFooter("<script type=\"text/javascript\">
                    Calendar.setup(
					{
						inputField : \"" . $field['name'] . "\",
						ifFormat : \"%Y-%m-%d\",
						button : \"button_" . $field['name'] . "\"
					}
					);
					</script>");
				} elseif ($con == IN_VIEW) {
					echo $value;
				} else {
					echo substr($value,0,70);
				}
					
				break;
		}
		}

		if ($con == IN_FORM || $con == IN_VIEW) {
			if ($visible) echo '</div>';
			if (!isset($field['skipend'])) echo '</div>';
		}
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	function parseFieldsForm($columns, $value=array(), $variant = false, $variantfields = array()) {
		foreach ($columns as $cur) {
			$res .= $this->parsefield($cur,$value[$cur['name']],IN_FORM,$variant,$variantfields);
		}
		return $res;
	}

	function parseFieldsView($columns, $value=array(), $variant = false, $variantfields = array()) {
		foreach ($columns as $cur) {
			$res .= $this->parsefield($cur,$value[$cur['name']],IN_VIEW,$variant,$variantfields);
		}
		return $res;
	}

	function GetStatusJs($descriptor) {
		$cnt = sizeof($descriptor);
		$i = 0;
		while ($i < $cnt) {
			if ($descriptor[$i]['inputtype'] != UI_HIDDEN) {
				$result .= 'if (document.getElementById(\'__var__'.$descriptor[$i]['name'].'\')) {
				if (document.getElementById(\'__var__'.$descriptor[$i]['name'].'\').checked) {
				document.getElementById(\''.$descriptor[$i]['name'].'\').disabled = false;
				} else {
				document.getElementById(\''.$descriptor[$i]['name'].'\').disabled = true;
				document.getElementById(\''.$descriptor[$i]['name'].'\').style.backgroundColor = \'#cccccc\';
				}
				}
				';
			}
			$i++;
		}
		return $result;
	}

}

?>