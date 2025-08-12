<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');

class basic_view_menu extends basic_view {
	var $topitems = 0;

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('basic_view_menu');
	}

	function guiaccess($dt) {
		if ($this->userhandler->GetLevel() >= 40) return true;
		if (is_array($dt)) {
			foreach ($dt as $cur) {
				if ($this->userhandler->GetProfileView($cur,'list')) return true;
			}
		} else {
			if ($this->userhandler->GetProfileView($dt,'list')) return true;
		}
		return false;
	}

	function menuGeneral() {
		if ($this->guiaccess(array('structure','document','template','stylesheet','frame','metadata'))) {
			if ($this->userhandler->getAppName() == 'edocument') {
				echo "{ Kvalitetshåndbog,image/menu_general.png}";
			} else {
				echo "{ ".$this->gl('menu_website').",image/menu_general.png}";
			}
			$this->topitems++;
			if ($this->guiaccess('structure')) echo "{|".owDatatypeDesc('structure').",gui.php?view=init&otype=structureelement,content}\n";
			if ($this->guiaccess('document')) echo "{|".owDatatypeDesc('document').",gui.php?view=init&otype=document,content}\n";
			if ($this->guiaccess('template')) echo "{|".owDatatypeDesc('template').",gui.php?view=init&otype=template,content}\n";
			if ($this->guiaccess('stylesheet')) echo "{|".owDatatypeDesc('stylesheet').",gui.php?view=init&otype=stylesheet,content}\n";
			if ($this->guiaccess('frame')) echo "{|".owDatatypeDesc('frame').",gui.php?view=init&otype=frame,content}\n";
			if ($this->guiaccess('metadata')) echo "{|".owDatatypeDesc('metadata').",gui.php?view=init&otype=metadata,content}\n";
			if ($this->guiaccess('document') || $this->guiaccess('binfile')) echo "{|-}";
			if ($this->guiaccess('document')) echo "{|".$this->gl('menu_statistics').",gui.php?view=statistics&otype=sys,content}\n";
			if ($this->guiaccess('binfile')) echo "{|".$this->gl('menu_filestatistics').",gui.php?view=filestatistics&otype=sys,content}\n";
		}
	}
	
	function menuFile() {
		if ($this->guiaccess('binfile')) {
			echo "{ ".$this->gl('menu_file').",image/menu_file.png}\n";
			$this->topitems++;
			if ($this->guiaccess('binfile')) echo "{|".owDatatypeDesc('binfile').",gui.php?view=init&otype=binfile,content}\n";
			if ($this->guiaccess('staticbinfile')) echo "{|-}";
			#if ($this->guiaccess('staticbinfile')) echo "{|Filstyring (statiske filer),gui.php?view=init&otype=staticbinfile,content}\n";
			if ($this->guiaccess('stimgbinfile')) echo "{|".owDatatypeDesc('stimgbinfile').",gui.php?view=init&otype=stimgbinfile,content}\n";
			#if ($this->guiaccess('stfilebinfile')) echo "{|Filstyring (kompatibilitet),gui.php?view=init&otype=stfilebinfile,content}\n";
		}
	}
	
	function menuAccess() {
		if ($this->guiaccess('user') ||
			$this->guiaccess('usergroup') ||
			$this->guiaccess('profile')) {
		
			echo "{|".$this->gl('menu_access')."}\n";
			$this->topitems++;
			if ($this->guiaccess('user')) echo "{||".owDatatypeDesc('user').",gui.php?view=init&otype=user,content}\n";
			if ($this->guiaccess('usergroup')) echo "{||".owDatatypeDesc('usergroup').",gui.php?view=init&otype=usergroup,content}\n";
			if ($this->guiaccess('profile')) echo "{||".owDatatypeDesc('profile').",gui.php?view=init&otype=profile,content}\n";
		}
	}
	
	function menuEbusiness() {
		if (sizeof($this->userhandler->getAppAvail()) > 1 || $this->guiaccess('item')) {
			$this->topitems++;
				
			echo "{ ".$this->gl('menu_ebusiness').",image/menu_browser3.png}\n";
	
			if ($this->guiaccess('item')) echo "{|".owDatatypeDesc('item').",gui.php?view=init&otype=item,content}\n";
			if ($this->guiaccess('customer')) echo "{|-}\n";
			if ($this->guiaccess('customer')) echo "{|".owDatatypeDesc('customer').",gui.php?view=init&otype=customer,content}\n";
			if ($this->guiaccess('shoporder')) echo "{|".owDatatypeDesc('shoporder').",gui.php?view=init&otype=shoporder,content}\n";
			if ($this->guiaccess('currency')) echo "{|-}\n";
			if ($this->guiaccess('currency')) echo "{|".owDatatypeDesc('currency').",gui.php?view=init&otype=currency,content}\n";
			if ($this->guiaccess('vat')) echo "{|".owDatatypeDesc('vat').",gui.php?view=init&otype=vat,content}\n";
			if ($this->guiaccess('freight')) echo "{|".owDatatypeDesc('freight').",gui.php?view=init&otype=freight,content}\n";
			if ($this->guiaccess('payment')) echo "{|".owDatatypeDesc('payment').",gui.php?view=init&otype=payment,content}\n";
			echo "{|-}\n";
			$apps = owGetApps();
			foreach ($apps as $app) {
				if ($this->userhandler->isAppAvail($app['app'])) echo "{|".$app['name'].",setapp.php?app=".$app['app'].",content}\n";
			}
		}
	}

	function menuIntranet() {
		if ($this->guiaccess(array('company','contact','task','meeting','letter','department','employee','caleventtype','calevent'))) {
			echo "{ ".$this->gl('menu_intranet').",image/menu_intranet.png}\n";
			$this->topitems++;
			if ($this->guiaccess('company')) echo "{|".owDatatypeDesc('company').",gui.php?view=init&otype=company,content}\n";
			if ($this->guiaccess('contact')) echo "{|".owDatatypeDesc('contact').",gui.php?view=init&otype=contact,content}\n";
			if ($this->guiaccess('task')) echo "{|".owDatatypeDesc('task').",gui.php?view=init&otype=task,content}\n";
			if ($this->guiaccess('meeting')) echo "{|".owDatatypeDesc('meeting').",gui.php?view=init&otype=meeting,content}\n";
			if ($this->guiaccess('letter')) echo "{|".owDatatypeDesc('letter').",gui.php?view=init&otype=letter,content}\n";
			if ($this->guiaccess(array('department','employee','caleventtype','calevent'))) echo "{|-}\n";
			if ($this->guiaccess('department')) echo "{|".owDatatypeDesc('department').",gui.php?view=init&otype=department,content}\n";
			if ($this->guiaccess('employee')) echo "{|".owDatatypeDesc('employee').",gui.php?view=init&otype=employee,content}\n";
			if ($this->guiaccess('caleventtype')) echo "{|".owDatatypeDesc('caleventtype').",gui.php?view=init&otype=caleventtype,content}\n";
			if ($this->guiaccess('calevent')) echo "{|".owDatatypeDesc('calevent').",gui.php?view=init&otype=calevent,content}\n";
		}
	}
	
	function menuAdvanced() {
		echo "{".$this->gl('menu_advanced').",image/menu_kcmsystem.png}\n";
		$this->topitems++;
		echo "{|".$this->gl('menu_changeguilanguage')."    }\n";
		echo "{||Dansk (DA),index.php?guilanguage=DA,_top}\n";
		echo "{||English (EN),index.php?guilanguage=EN,_top}\n";
		$this->menuAccess();
		$this->menuDevelopment();
		
	}
	
	function menuCustomForms() {
		if (owTry('customform')) {
			echo "{|-}\n";
			echo "{|".$this->gl('menu_customform')."}\n";
			if ($this->guiaccess('customform')) {
				echo "{||".$this->gl('menu_customform_generator').",gui.php?view=init&otype=customformelement,content}\n";
			}
			$obj = owNew('customform');
			$obj->listobjects();
			if ($obj->elementscount) {
				echo "{||-}\n";
				foreach ($obj->elements as $cur) {
					echo "{||" . $this->gl('menu_customform_form') . " ".$cur['description']."}\n";
					echo "{|||" . $this->gl('menu_customform_list') . ",gui.php?view=init&otype=cform".$cur['name'].",content}\n";
					echo "{|||" . $this->gl('menu_customform_stats') . ",gui.php?view=search&otype=cform".$cur['name'].",content}\n";
				}
			}
		}
	}
	
	function menuDevelopment() {
		
		if ($this->guiaccess(array('customform','category','extradata','listcol','filter','event'))) {
			echo "{|".$this->gl('menu_development')."}\n";
		}
		if ($this->guiaccess('extradata')) echo "{||".owDatatypeDesc('extradata').",gui.php?view=init&otype=extradata,content}\n";
		if ($this->guiaccess('event')) echo "{||".owDatatypeDesc('event').",gui.php?view=init&otype=event,content}\n";
		if ($this->guiaccess('filter')) echo "{||".owDatatypeDesc('filter').",gui.php?view=init&otype=filter,content}\n";
		
		if ($this->userhandler->GetLevel() >= ACCESS_ADMINISTRATOR) {
		
			echo "{||-}\n";
			echo "{||".$this->gl('menu_emptytrashcan').",gui.php?otype=sys&view=cleartrashcan,content}\n";
			echo "{||".$this->gl('menu_import').",gui.php?otype=sys&view=import,content}\n";
			echo "{||".$this->gl('menu_package').",gui.php?otype=sys&view=package,content}\n";
			echo "{||-}\n";
			echo "{||".$this->gl('menu_createsite').",gui.php?otype=sys&view=createsite,content}\n";
			echo "{||".$this->gl('menu_installsite').",gui.php?otype=sys&view=installsite,content}\n";
			echo "{||-}\n";
			echo "{||".$this->gl('menu_extension')."}\n";
				$extensions = owListExtensions();
				foreach($extensions as $name) {
					$result = '';
					
					if (owIsExtendedDatatype($name)) {
						$result .=  "{||||" . owDatatypeDesc($name) . ",gui.php?view=init&otype=".$name.",content}\n";
					}
					
					$datatypes = owListExtensionDatatypes($name);
					foreach ($datatypes as $datatype) {
						if (owIsExtendedDatatype($datatype)) { 
							$result .= "{||||" . owDatatypeDesc($datatype) . ",gui.php?view=init&otype=".$datatype.",content}\n";
						}
					}
					
					if ($result != '') {
						echo "{|||" . owDatatypeDesc($name) . "}";
						echo $result;
					}
				}
		}
		echo "{|-}\n";
		if ($this->guiaccess('category')) echo "{|".owDatatypeDesc('category').",gui.php?view=init&otype=category,content}\n";
		if ($this->guiaccess('listcol')) echo "{|".owDatatypeDesc('listcol').",gui.php?view=init&otype=listcol,content}\n";
		if ($this->guiaccess('savedsearch')) echo "{|".owDatatypeDesc('savedsearch').",gui.php?view=init&otype=savedsearch,content}\n";
		
		$this->menuCustomForms();	
	}
	
	function mainMenu() {
		$this->menuGeneral();
		echo "\n";
		$this->menuFile();
		echo "\n";
		$this->menuEbusiness();
		echo "\n";
		$this->menuIntranet();
		echo "\n";
		$this->menuAdvanced();
		echo "\n";
	}
				
	function view() {
		$this->context->addheader('<style type="text/css">
		<!--
		body {margin: 0px;}
		-->
		</style>');
		$this->context->addheader("<script language=\"javascript\">
		function logout() {
			check = confirm('".$this->gl('dialog_terminate')."');
		      	if (check) {
		      		top.location.href='index.php?cmd=logout';
		      	}
		}
		</script>");

		$result .= '<div class="metamenu">';
		ob_start();
		$this->mainMenu();
		$menustr .= ob_get_contents();
		ob_end_clean();
		$width = $this->topitems * 200;
		if ($width > 900) $width = 900;
		
		ob_start();
		?>
		<applet Code="apPopupMenu" Archive="apPopupMenu.jar" Width="<?php echo $width ?>" Height="25" MAYSCRIPT>
		<param name="Copyright" value="Apycom Software - www.apycom.com">
		<param name="isHorizontal" value="true">
		<param name="buttonType" value="3">
		<param name="systemSubFont" value="true">
		<param name="backPic" value="image/bkgr.gif">
		<param name="backColor" value="E9E9E9">
		<param name="fontColor" value="000000">
		<param name="font" value="Tahoma,11,1">
		<param name="menuItems" value="
		{_,_,_}
		<?php
		echo $menustr;
		echo "{".$this->gl('menu_exit').",javascript:logout(),,image/b03.gif}";
	      ?>
	      {_,_,_}
	      ">
		</applet>
		<?php
		$result .= ob_get_contents();
		ob_end_clean();
		$result .= '</div>';
		return $result;
	}
}

?>