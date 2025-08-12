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
## Desc: JS functions needed for the Editing of the cell type "Nest Content"
## $Source: /home/cvsroot/squizlib/bodycopy/send_files/js/bodycopy_edit_table_cell_type_nest_content.js,v $
## $Revision: 2.6 $
## $Author: achadszinow $
## $Date: 2003/12/01 01:37:09 $
#######################################################################
*/

function bodycopy_edit_table_cell_type_nest_content(bodycopy_name, tableid, rowid, cellid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	bodycopy_current_edit["data"]["rowid"]   = rowid;
	bodycopy_current_edit["data"]["cellid"]  = cellid;
	var data = get_bodycopy_current_data(bodycopy_name, tableid, rowid, cellid);
	bodycopy_current_edit["data"]["siteid"] = var_unserialise(data["siteid"]);
	if (bodycopy_current_edit["data"]["siteid"] == null) bodycopy_current_edit["data"]["siteid"] = "";
	bodycopy_current_edit["data"]["pageid"] = var_unserialise(data["pageid"]);
	if (bodycopy_current_edit["data"]["pageid"] == null) bodycopy_current_edit["data"]["pageid"] = "";
	bodycopy_current_edit["data"]["variables"] = var_unserialise(data["variables"]);
	if (bodycopy_current_edit["data"]["variables"] == null) bodycopy_current_edit["data"]["variables"] = "";
	bodycopy_current_edit["data"]["submit_type"] = var_unserialise(data["submit_type"]);
	if (bodycopy_current_edit["data"]["submit_type"] == null) bodycopy_current_edit["data"]["submit_type"] = "";
	bodycopy_current_edit["data"]["restrict_links"] = var_unserialise(data["restrict_links"]);
	if (bodycopy_current_edit["data"]["restrict_links"] == null) bodycopy_current_edit["data"]["restrict_links"] = "";

	bodycopy_show_popup("edit_table_cell_type_nest_content.php", 600, 320);

}// end bodycopy_edit_table_cell_type_nest_content()

function bodycopy_save_table_cell_type_nest_content(siteid, pageid, variables, submit_type, restrict_links) {
	var data = get_bodycopy_current_data(bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

	for (var key in data) { if (typeof(data[key]) == "string") { data[key] = var_unserialise(data[key]); }}

	data['siteid'] = siteid;
	data['pageid'] = pageid;
	data['variables'] = variables;
	data['submit_type'] = submit_type;
	data['restrict_links'] = restrict_links;
	bodycopy_hide_popup();
	var id = bodycopy_current_edit["bodycopy_name"] + '_cell_' + bodycopy_current_edit["data"]["tableid"] + '_' + bodycopy_current_edit["data"]["rowid"] + '_' + bodycopy_current_edit["data"]["cellid"]; 
	bodycopy_chgColor(id);
	serialise_table(bodycopy_current_edit["bodycopy_name"], data, bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

}// end bodycopy_save_table_cell_type_nest_content()
