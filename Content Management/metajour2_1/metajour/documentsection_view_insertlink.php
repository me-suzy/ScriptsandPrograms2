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
require_once('basic_field.php');

class documentsection_view_insertlink extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('documentsection_view_insertlink');
	}
	
	function view() {
		$viewer_url = $this->userhandler->getViewerUrl();
		$basicfield = new basic_field($this);
		$this->context->clearall();
		?>
<html>
<head>
	<title>Indsæt link</title>
	<style type="text/css">
		body { background-color: buttonface; font-family: Tahoma; font-size: 8pt; }
		select { font-family: Tahoma; font-size: 8pt; }
		input { font-family: Tahoma; font-size: 8pt; }
		td { font-family: Tahoma; font-size: 8pt; }
	</style>
	
	<script type="text/javascript">
	<!--
	function init() {
		var url = (typeof(window.dialogArguments.args.url) == "undefined") ? '' : window.dialogArguments.args.url;
		var anchors = (typeof(window.dialogArguments.args.anchors) == "undefined") ? '' : window.dialogArguments.args.anchors;
		var where = 'external';
		if (url.match(/(showpage\.php\?pageid=[0-9]+)/)) {
			where = 'internal';
			if (url.match(/<?php echo str_replace('/', '\/', $viewer_url) ?>/)) {
				url = url.substring(<?php echo strlen($viewer_url) ?>, url.length);
			}
		//} else if (url.match(/<?php echo str_replace('/', '\/', $viewer_url) . 'getfile.php'?>/)) {
		//	where = 'file';
		} else if (url.charAt(0) == '#') {
			where = 'anchor';
			url = url.substring(1, url.length);
		}
		
		document.forms[0].linkTarget.value = (typeof(window.dialogArguments.args.target) == "undefined") ? '' : window.dialogArguments.args.target;
		
		for (i = 0; i < anchors.length; i++) {
			document.forms[0].anchor.options[i] = new Option(anchors(i).name, anchors(i).name);
		}
		
		if (where == 'external') {
			document.forms[0].type.selectedIndex = 1;
			document.forms[0].url.value=url;
			document.forms[0].url.style.display = 'block';
			document.forms[0].page.style.display = 'none';
			document.getElementById('divfile').style.display = 'none';
			document.getElementById('typeText').innerText = 'URL:';
		} else if (where == 'internal') {
			document.forms[0].type.selectedIndex = 0;
			document.forms[0].page.selectedIndex = 0;
			for(i=0; i<document.forms[0].page.options.length; i++) {
				if (document.forms[0].page.options[i].value == url) {
					document.forms[0].page.selectedIndex = i;
				}
			}
		} else if (where == 'file') {
			document.forms[0].type.selectedIndex = 2;
			document.forms[0].url.style.display = 'none';
			document.forms[0].page.style.display = 'none';
			document.getElementById('divfile').style.display = 'block';
			for(i=0; i<document.forms[0].file.options.length; i++) {
				if (document.forms[0].file.options[i].value == url) {
					document.forms[0].file.selectedIndex = i;
				}
			}
		} else if (where == 'anchor') {
			document.forms[0].type.selectedIndex = 3;
			document.forms[0].url.style.display = 'none';
			document.forms[0].page.style.display = 'none';
			document.getElementById('divfile').style.display = 'none';
			document.forms[0].anchor.style.display = 'block';
			for (i = 0; i < document.forms[0].anchor.options.length; i++) {
				if (document.forms[0].anchor.options[i].value == url) {
					document.forms[0].anchor.selectedIndex = i;
				}
			}
		}
	}
			
	
	function changeview() {
		var typeSelect = document.forms[0].type;
		var typeText = document.getElementById("typeText");
		if (typeSelect.options[typeSelect.selectedIndex].value == 'internal') {
			typeText.innerText="Side:";
			document.forms[0].url.style.display = 'none';
			document.getElementById('divfile').style.display = 'none';
			document.forms[0].anchor.style.display = 'none';
			document.forms[0].page.style.display = 'block';
		} else if (typeSelect.options[typeSelect.selectedIndex].value == 'external') {
			typeText.innerText="URL:";
			document.forms[0].page.style.display = 'none';
			document.getElementById('divfile').style.display = 'none';
			document.forms[0].anchor.style.display = 'none';
			document.forms[0].url.style.display = 'block';
		} else if (typeSelect.options[typeSelect.selectedIndex].value == 'file') {
			typeText.innerText = 'Fil:';
			document.forms[0].page.style.display = 'none';
			document.forms[0].url.style.display = 'none';
			document.forms[0].anchor.style.display = 'none';
			document.getElementById('divfile').style.display = 'block';
		} else if (typeSelect.options[typeSelect.selectedIndex].value == 'anchor') {
			typeText.innerText = 'Bogmærke:';
			document.forms[0].page.style.display = 'none';
			document.forms[0].url.style.display = 'none';
			document.getElementById('divfile').style.display = 'none';
			document.forms[0].anchor.style.display = 'block';
		} else {
			// Can't happen
			alert('Intern fejl');
		}
			
	}
	
	function submitform() {
		var myReturnValue = new Array();
		var url = '';
		if (document.forms[0].type.selectedIndex == 1) { // external
			url = document.forms[0].url.value;
		} else if (document.forms[0].type.selectedIndex == 0) {
			url = document.forms[0].page.options[document.forms[0].page.selectedIndex].value;
		} else if (document.forms[0].type.selectedIndex == 2) { // Fil
			url = 'getfile.php?objectid='+document.forms[0].file.value;
		} else if (document.forms[0].type.selectedIndex == 3) { // Bogmærke
			
			if (document.forms[0].anchor.selectedIndex > -1) {
				url = '#' + document.forms[0].anchor.options[document.forms[0].anchor.selectedIndex].value;
			}
		} else {
			// Can't happen
			alert('Intern fejl');
			url = null;
		}
		myReturnValue["url"] = url;
		myReturnValue["target"] = document.forms[0].linkTarget.value;
		window.returnValue = myReturnValue;
		window.close();
	}
	// -->
	</script>

	
</head>
<body onload="init()">
<table style="width: 430px; margin: 10px">
<form>
<tr>
	<td>
		<fieldset>
			<legend><?php echo $this->gl('title'); ?></legend>
			<table cellpadding=2>
				<tr>
					<td style="width: 60px; text-align: right"><?php echo $this->gl('text_1'); ?></td>
					<td>
						<select name="type" id="TYPE" style="width: 100px" onchange="changeview()">
							<option value="internal" selected><?php echo $this->gl('text_2'); ?></option>
							<option value="external"><?php echo $this->gl('text_3'); ?></option>
							<option value="file"><?php echo $this->gl('text_4'); ?></option>
							<option value="anchor"><?php echo $this->gl('text_5'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td id="typeText" style="text-align: right"><?php echo $this->gl('text_6'); ?></td>
					<td>
						<select name="page" style="width: 207px">
							<option value=""><?php echo $this->gl('text_7'); ?></option>
							<?php
								$pageobj = owNew('document');
								$pageobj->setsort_col('name');
								$pageobj->listobjects();
								for($i = 0; $i < $pageobj->elementscount; $i++) {
									echo '<option value="showpage.php?pageid=' 
									     . $pageobj->elements[$i]['objectid'] . '">'
									     . stripslashes($pageobj->elements[$i]['name'])
									     . '</option>';
								}
							?>
						</select>
						<div id="divfile" style="display: none;">
							<?php
                            $field = array('name' =>'file', 'type' => 0, 'inputtype' => UI_LISTDIALOG, 'relation' =>'binfile', 'validate' => '', 'labelstyle' => 'display: none;', 'style' => 'display: inline; width: 200px;');
                            $fieldrenderer = new basic_field($this);
                            
                            echo $fieldrenderer->parsefield($field, '');
							?>
						</div>
						<input type="text" name="url" maxlength="255" style="display:none; width: 200px">
						<select name="anchor" style="width: 207px; display: none">
						</select>
					</td>
				</tr>
				<tr>
					<td id="targetLink" style="text-align: right">Target:</td>
					<td>
						<input type="text" name="linkTarget" maxlength="255" style="width: 200px">
					</td>
				</tr>
			</table>
		</fieldset>
	</td>
	<td valign="top">
		<table cellpadding=2>
			<tr>
				<td><input type="button" id="OK" value="<?php echo $this->gl('button_ok'); ?>" style="width: 80px; height: 22px" default onclick="submitform(); return false"></td>
			</tr>
			<tr>		
				<td><input type="button" id="CANCEL" value="<?php echo $this->gl('button_cancel'); ?>" style="width: 80px; height: 22px" onclick="window.close()"></td>
			</tr>	
		</table>
	</td>	
</tr>	
</table>

</form>
</body>
</html>
		<?php
	}
}

?>