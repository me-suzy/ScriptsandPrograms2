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
## Desc: JS functions needed for the Editing of the cell type "File"
## $Source: /home/cvsroot/squizlib/bodycopy/send_files/js/bodycopy_edit_table_cell_type_file.js,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/06/05 02:08:30 $
#######################################################################
*/

function bodycopy_edit_table_cell_type_file(bodycopy_name, tableid, rowid, cellid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	bodycopy_current_edit["data"]["rowid"]   = rowid;
	bodycopy_current_edit["data"]["cellid"]  = cellid;

	var data = get_bodycopy_current_data(bodycopy_name, tableid, rowid, cellid);
	bodycopy_current_edit["data"]["fileid"] = var_unserialise(data["fileid"]);
	if (bodycopy_current_edit["data"]["fileid"] == null) bodycopy_current_edit["data"]["fileid"] = "";
	bodycopy_current_edit["data"]["embed"] = var_unserialise(data["embed"]);
	if (bodycopy_current_edit["data"]["embed"] == null) bodycopy_current_edit["data"]["embed"] = "";
	bodycopy_current_edit["data"]["embed_options"] = var_unserialise(data["embed_options"]);

	bodycopy_show_popup("edit_table_cell_type_file.php", 500, 410);

}// end bodycopy_edit_table_cell_type_file()

function bodycopy_save_table_cell_type_file(fileid, embed, embed_options) {

	var data = get_bodycopy_current_data(bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

	for (var key in data) {	if (typeof(data[key]) == "string") { data[key] = var_unserialise(data[key]); }}

	data['fileid'] = fileid;
	data['embed'] = embed;
	data['embed_options'] = embed_options;
	bodycopy_hide_popup();
	var id = bodycopy_current_edit["bodycopy_name"] + '_cell_' + bodycopy_current_edit["data"]["tableid"] + '_' + bodycopy_current_edit["data"]["rowid"] + '_' + bodycopy_current_edit["data"]["cellid"]; 
	bodycopy_chgColor(id);
	serialise_table(bodycopy_current_edit["bodycopy_name"], data, bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

}// end bodycopy_save_table_cell_type_file()
