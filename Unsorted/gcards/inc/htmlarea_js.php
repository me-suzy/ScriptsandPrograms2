<?
function getHTMLareaJStop($form, $field, $relpath='')
{
	$htmlarea_js="
	<script language=\"Javascript1.2\">
		<!-- // load htmlarea
		_editor_url = \"".$relpath."inc/htmlarea/\";                     
		var win_ie_ver = parseFloat(navigator.appVersion.split(\"MSIE\")[1]);
		if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
		if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
		if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
		if (win_ie_ver >= 5.5) {
		 document.write('<scr' + 'ipt src=\"' +_editor_url+ 'editor.js\"');
		 document.write(' language=\"Javascript1.2\"></scr' + 'ipt>');  
		} else { document.write('<script>function editor_generate() { }</script>'); }
		
		function insert(id) {
			document.".$form.'.'.$field.".value += ' ' + id + ' ';
		}
			
		// -->
	</script> 	
	";
	return $htmlarea_js;
}

function getHTMLareaJSbot($field)
{
	$htmlarea_js="
	<script language=\"Javascript1.2\">
		<!-- // 
		var config = new Object();
	
		config.width = \"500\";
		config.height = \"200px\";
		config.bodyStyle = 'background-color: white; font-family: \"Verdana\"; font-size: x-small;';
		config.debug = 0;
		
		config.toolbar = [
		    ['fontname'],
		    ['fontsize'],
		    ['bold','italic','underline','separator'],
		    ['forecolor','separator'],
		    ['htmlmode','separator'],
		    ['about'],
		];
		
		editor_generate('".$field."',config);
		
		// -->
	</script> 	
	";
	return $htmlarea_js;
}
?>