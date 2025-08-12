/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazomenuspace_getControlHTML(control_name) {
	switch (control_name) {
		case "metazomenuspace":
				return '<img src="{$pluginurl}/images/metazomenuspace.gif" width="250" height="20" />';
	}
	return "";
}