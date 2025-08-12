/*  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- BodyCopy Include Files - Javascript ----##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## Desc: JS functions needed for the Editing of the cell type "Raw HTML"
## $Source: /home/cvsroot/squizlib/bodycopy/send_files/js/bodycopy_edit_table_cell_type_rawhtml.js,v $
## $Revision: 2.3 $
## $Author: csmith $
## $Date: 2003/08/20 03:17:06 $
#######################################################################
*/

function bodycopy_edit_table_cell_type_rawhtml(bodycopy_name, tableid, rowid, cellid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	bodycopy_current_edit["data"]["rowid"]   = rowid;
	bodycopy_current_edit["data"]["cellid"]  = cellid;
	var data = get_bodycopy_current_data(bodycopy_name, tableid, rowid, cellid);
	bodycopy_current_edit["data"]["html"] = var_unserialise(data["html"]);
	if (bodycopy_current_edit["data"]["html"] == null) bodycopy_current_edit["data"]["html"] = "";

	bodycopy_show_popup("edit_table_cell_type_rawhtml.php", 750,400);

}// end bodycopy_edit_table_cell_type_rawhtml()

function bodycopy_save_table_cell_type_rawhtml(html) {

	check_external_links(html);

	var data = get_bodycopy_current_data(bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

	for (var key in data) {	if (typeof(data[key]) == "string") { data[key] = var_unserialise(data[key]); }}

	data['html'] = html;
	bodycopy_hide_popup();
	var id = bodycopy_current_edit["bodycopy_name"] + '_cell_' + bodycopy_current_edit["data"]["tableid"] + '_' + bodycopy_current_edit["data"]["rowid"] + '_' + bodycopy_current_edit["data"]["cellid"]; 
	bodycopy_chgColor(id);
	serialise_table(bodycopy_current_edit["bodycopy_name"], data, bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

}// end bodycopy_save_table_cell_type_rawhtml()
