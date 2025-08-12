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
## Desc: JS functions needed for the Editing of Tables, Table Rows and Table Cells in the BodyCopy
## $Source: /home/cvsroot/squizlib/bodycopy/send_files/js/bodycopy_edit_tables.js,v $
## $Revision: 2.12 $
## $Author: dofford $
## $Date: 2004/03/08 23:10:01 $
#######################################################################
*/

function bodycopy_insert_table(bodycopy_name, tableid, before) {

	bodycopy_current_edit["bodycopy_name"]   = bodycopy_name;
	bodycopy_current_edit["data"]            = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	bodycopy_current_edit["data"]["before"]  = before;
	bodycopy_current_edit["data"]["type"]    = get_bodycopy_available_default_cell_type();
	bodycopy_current_edit["data"]["available_types"] = get_bodycopy_available_cell_types();
	bodycopy_show_popup("insert_table.php", 450, 400);
	
}// end bodycopy_insert_table()

function bodycopy_save_insert_table(cols, rows, type, attributes) {

	bodycopy_current_edit["data"]["num_cols"] = cols;
	bodycopy_current_edit["data"]["num_rows"] = rows;
	bodycopy_current_edit["data"]["type"]    = type;
	bodycopy_current_edit["data"]["attributes"] = attributes;
	bodycopy_current_edit["data"]["available_types"] = null;
	bodycopy_hide_popup();
	bodycopy_submit("insert table", bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]);

}// end bodycopy_save_insert_table()

function bodycopy_delete_table(bodycopy_name, tableid) {

	if (confirm('Are you sure you want to delete this table?') && confirm('Really Sure? This is irreversible.')) {

		var data = new Object();
		data["tableid"] = tableid;
		bodycopy_submit("delete table", bodycopy_name, data);

	}// end if
	
}// end bodycopy_delete_table()


function bodycopy_copy_table(bodycopy_name, tableid) {

	var data = new Object();
	data["tableid"] = tableid;
	bodycopy_submit("copy table", bodycopy_name, data);

}// end bodycopy_copy_table()


function bodycopy_paste_table(bodycopy_name, tableid, before) {

	var data = new Object();
	data["tableid"] = tableid;
	data["before"] = before;
	bodycopy_submit("paste table", bodycopy_name, data);
	 
}// end bodycopy_paste_table()

function bodycopy_copy_content(bodycopy_name) {

	bodycopy_submit("copy content", bodycopy_name, bodycopy_current_edit["data"]);

}// end bodycopy_copy_content()


function bodycopy_edit_table_order(bodycopy_name) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	var bodycopy = get_bodycopy_current_data(bodycopy_name);
	var table_order = new Array();
	for(var i = 0; i < bodycopy["num_tables"]; i++) {
		// get the first cell of the current table
		var cell = get_bodycopy_current_data(bodycopy_name, i, 0, 0);
		var str = bodycopy_order_cell_string(cell);
		table_order[i] = 'Table ' + i + ' : ' + str;

	}// end for

	bodycopy_current_edit["data"]["order_type"] = "table";
	bodycopy_current_edit["data"]["table_order"] = table_order;

	bodycopy_show_popup("edit_table_orderer.php", 300, 300);

}// end bodycopy_edit_table_order()

function bodycopy_order_cell_string(cell) {

	// remove the class prefix from the cell type
	var cell_attributes = var_unserialise(cell["attributes"]);
	if (cell_attributes["type"]) {
		var cell_type = cell_attributes["type"].replace(/^bodycopy_table_cell_type_/, "");
	} else {
		var cell_type = '';
	}

	switch (cell_type.toLowerCase()) {

		case "rawhtml" :
		case "wysiwyg" :
			var str = var_unserialise(cell["html"]);
			if (str == '') {
				str = "[ " + cell_type.toUpperCase() + " ]";
			}
		break;

		case "richtext" :
			var str = var_unserialise(cell["text"]);
			if (str == '') {
				str = "[ " + cell_type.toUpperCase() + " ]";
			}
		break;

		default :
			var str = "[ " + cell_type.toUpperCase() + " ]";

	}// end switch

	// If there's just an image, make sure there is something to show.
	var re = /^<img[^>]*>/gi;
	str = str.replace(re, "[Image]");

	var re = /<[^>]*>/gi;
	str = str.replace(re, "");
	var re = /[\n\r]/gi;
	str = str.replace(re, " ");
	if (str.length > 20) {
		str = str.substr(0, 20) + " ... ";
	}
	return str;

}// end bodycopy_order_cell_string()

function bodycopy_save_table_order(table_order) {

	bodycopy_current_edit["data"]["table_order"] = table_order;
	bodycopy_hide_popup();
	bodycopy_submit("edit table order", bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]);

}// end bodycopy_save_table_order()


function bodycopy_edit_table_properties(bodycopy_name, tableid) {

	bodycopy_current_edit["bodycopy_name"]   = bodycopy_name;
	bodycopy_current_edit["data"]            = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	var data = get_bodycopy_current_data(bodycopy_name, tableid);
	bodycopy_current_edit["data"]["attributes"] = var_unserialise(data["attributes"]);
	bodycopy_current_edit["data"]["available_types"] = get_bodycopy_available_cell_types();
	bodycopy_current_edit["data"]["available_conditions"] = get_bodycopy_available_conditions();
	bodycopy_show_popup("edit_table_props.php", 330, 460);

}// end bodycopy_edit_table_properties()

function bodycopy_save_table_properties(attributes) {

	bodycopy_current_edit["data"]["attributes"] = attributes;
	bodycopy_hide_popup();
	var id = bodycopy_current_edit["bodycopy_name"] + '_table_' + bodycopy_current_edit["data"]["tableid"]; 
	bodycopy_chgColor(id);
	serialise_table(bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"], bodycopy_current_edit["data"]["tableid"],null,null);

}// end bodycopy_edit_table_properties()

function bodycopy_insert_table_col(bodycopy_name, tableid, colid, before) {

	var data = new Object();
	data["tableid"] = tableid;
	data["colid"]   = colid;
	data["before"]  = before;

	bodycopy_submit("insert table column", bodycopy_name, data);
	
}// end bodycopy_insert_table()

function bodycopy_delete_table_col(bodycopy_name, tableid, colid) {

	if (confirm('Are you sure you want to delete this column?') && confirm('Really Sure? This is irreversible.')) {

		var data = new Object();
		data["tableid"] = tableid;
		data["colid"]   = colid;

		bodycopy_submit("delete table column", bodycopy_name, data);

	}// end if
	
}// end bodycopy_delete_table_col()


function bodycopy_edit_table_col_order(bodycopy_name, tableid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	var table = get_bodycopy_current_data(bodycopy_name, tableid);
	var col_order = new Array();
	for(var i = 0; i < table["num_cols"]; i++) {
		// get the top cell of the current col
		var cell = get_bodycopy_current_data(bodycopy_name, tableid, 0, i);
		var str = bodycopy_order_cell_string(cell);
		col_order[i] = 'Column ' + (i+1) + ' : ' + str;
	}// end for

	bodycopy_current_edit["data"]["order_type"] = "col";
	bodycopy_current_edit["data"]["col_order"] = col_order;

	bodycopy_show_popup("edit_table_orderer.php", 300, 300);

}// end bodycopy_edit_table_col_order()

function bodycopy_save_table_col_order(col_order) {

	bodycopy_current_edit["data"]["col_order"] = col_order;
	bodycopy_hide_popup();
	bodycopy_submit("edit table col order", bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]);

}// end bodycopy_save_table_col_order()

function bodycopy_edit_table_row_properties(bodycopy_name, tableid, rowid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	bodycopy_current_edit["data"]["rowid"]   = rowid;
	var data = get_bodycopy_current_data(bodycopy_name, tableid, rowid);
	bodycopy_current_edit["data"]["available_conditions"] = get_bodycopy_available_conditions();
	bodycopy_current_edit["data"]["attributes"] = var_unserialise(data["attributes"]);

	bodycopy_show_popup("edit_table_row_props.php", 320, 250);

}// end bodycopy_edit_table_row_properties()

function bodycopy_save_table_row_properties(attributes) {

	bodycopy_current_edit["data"]["attributes"] = attributes;
	bodycopy_hide_popup();
	var id = bodycopy_current_edit["bodycopy_name"] + '_row_' + bodycopy_current_edit["data"]["tableid"] + '_' + bodycopy_current_edit["data"]["rowid"]; 
	bodycopy_chgColor(id);
	serialise_table(bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"], bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"],null);

}// end bodycopy_save_table_row_properties()


function bodycopy_insert_table_row(bodycopy_name, tableid, rowid, before) {

	var data = new Object();
	data["tableid"] = tableid;
	data["rowid"]   = rowid;
	data["before"]  = before;
	bodycopy_submit("insert table row", bodycopy_name, data);
	
}// end bodycopy_insert_table_row()

function bodycopy_delete_table_row(bodycopy_name, tableid, rowid) {

	if (confirm('Are you sure you want to delete this table row?') && confirm('Really Sure? This is irreversible.')) {

		var data = new Object();
		data["tableid"] = tableid;
		data["rowid"]   = rowid;
		bodycopy_submit("delete table row", bodycopy_name, data);

	}// end if
	
}// end bodycopy_delete_table_row()

function bodycopy_edit_table_row_order(bodycopy_name, tableid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	var table = get_bodycopy_current_data(bodycopy_name, tableid);
	var row_order = new Array();
	for(var i = 0; i < table["num_rows"]; i++) {
		// get the first cell of the current row
		var cell = get_bodycopy_current_data(bodycopy_name, tableid, i, 0);
		var str = bodycopy_order_cell_string(cell);
		row_order[i] = 'Row ' + (i+1) + ' : ' + str;

	}// end for

	bodycopy_current_edit["data"]["order_type"] = "row";
	bodycopy_current_edit["data"]["row_order"]  = row_order;

	bodycopy_show_popup("edit_table_orderer.php", 300, 300);

}// end bodycopy_edit_table_row_order()

function bodycopy_save_table_row_order(row_order) {
	
	bodycopy_current_edit["data"]["row_order"] = row_order;
	bodycopy_hide_popup();
	bodycopy_submit("edit table row order", bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]);

}// end bodycopy_save_table_row_order()

function bodycopy_edit_table_cell_properties(bodycopy_name, tableid, rowid, cellid) {

	bodycopy_current_edit["bodycopy_name"] = bodycopy_name;
	bodycopy_current_edit["data"] = new Object();
	bodycopy_current_edit["data"]["tableid"] = tableid;
	bodycopy_current_edit["data"]["rowid"]   = rowid;
	bodycopy_current_edit["data"]["cellid"]  = cellid;
	var data = get_bodycopy_current_data(bodycopy_name, tableid, rowid, cellid);
	bodycopy_current_edit["data"]["available_conditions"] = get_bodycopy_available_conditions();
	bodycopy_current_edit["data"]["attributes"] = var_unserialise(data["attributes"]);
	bodycopy_current_edit["data"]["available_types"] = get_bodycopy_available_cell_types();

	bodycopy_show_popup("edit_table_cell_props.php", 310, 420);

}// end bodycopy_edit_table_cell_properties()

function bodycopy_save_table_cell_properties(attributes) {

	var data = get_bodycopy_current_data(bodycopy_current_edit["bodycopy_name"], bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

	for (var key in data) {	if (typeof(data[key]) == "string") { data[key] = var_unserialise(data[key]); }}

	data['attributes'] = attributes;
	data["available_types"] = null;
	bodycopy_hide_popup();
	var id = bodycopy_current_edit["bodycopy_name"] + '_cell_' + bodycopy_current_edit["data"]["tableid"] + '_' + bodycopy_current_edit["data"]["rowid"] + '_' + bodycopy_current_edit["data"]["cellid"]; 
	bodycopy_chgColor(id);
	serialise_table(bodycopy_current_edit["bodycopy_name"], data, bodycopy_current_edit["data"]["tableid"], bodycopy_current_edit["data"]["rowid"], bodycopy_current_edit["data"]["cellid"]);

}// end bodycopy_save_table_cell_properties()
