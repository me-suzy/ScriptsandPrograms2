// This JavaScript does the magic on the select_db stage.
// It builds the existing tables combo boxes and the new table options.

// Abbreviate document reference
var doc = document;

// Rename sub-elements
var div =	"workarea";
var form = doc.form1;


// Functions start here:  -->

function CopyColumnHeaders() {
	var e;
	var fieldValue;
	var i;
	var msg = "";
	
	// Reg-exps: valid field names
	var regexp_deleteChars = /\W|^\s|\s$/g;
	var regexp_numStart = /^\d/;
	var regexp_underscoreChars = /-| /g;
	
	// Reg-exps: common field names
	var regexp_id = /^id$/i;
	var regexp_price = /price/i;
	
	for (i = 0; i < numOfCols; i++) {
		e = doc.getElementById("fieldName0" + i);

		fieldValue = dataPreviewArray[0][i];
			fieldValue = fieldValue.replace(regexp_deleteChars, "");
			if (regexp_numStart.test(fieldValue)) fieldValue = "_" + fieldValue;
			fieldValue = fieldValue.replace(regexp_underscoreChars, "_");
		
		if (fieldValue != dataPreviewArray[0][i]) msg += "\n   " + dataPreviewArray[0][i] + " -> " + fieldValue;

		e.value = fieldValue;
		
		if (regexp_price.test(fieldValue)) {
			e = doc.getElementById("fieldType0" + i);
			e.value = "float(5,2)";
			e = doc.getElementById("fieldExtras0" + i);
			e.value = "not null";
		} else if (regexp_id.test(fieldValue)) {
			e = doc.getElementById("fieldExtras0" + i);
			e.value = "not null primary key";
		} else {	
			e = doc.getElementById("fieldExtras0" + i);
			e.value = "not null";
		}
	}
	
	if (msg != "") alert("The following columns have been altered to ensure they're compatible in MySQL:" + msg);
}


function CreateForm_existing() {
	doc.form1.stage.value = "import_setup";

	// Empty workarea
	EmptyWorkarea(div);
	
	var workarea = doc.getElementById(div);
	
	// Declare vars
	var freeVar;
	var i;
	var a_href;
	
	var table = _table.cloneNode(true);  // Create table
	var tbody = _tbody.cloneNode(false);  // Create tbody
	
	var row1 = _tr.cloneNode(false);	// Row for database
	var cell11 = _td.cloneNode(false);	// Cell for words
	var cell12 = _td.cloneNode(false);	// Cell for combo
	
	var text1 = doc.createTextNode("Select database: ");
	var combo1 = _select.cloneNode(false);	// SELECT control for database
		combo1.setAttribute("name", "dbName");
		combo1.attachEvent("onchange", GetTables);
		combo1.options[combo1.length] = new Option("Select database", "");  // Add "Select database option"
	
	for (i = 0; i < mysqlDatabaseArray.length; i++) {
		combo1.options[combo1.length] = new Option(mysqlDatabaseArray[i], mysqlDatabaseArray[i]);  // Add databases to options of SELECT control
	}
	
	row2 = _tr.cloneNode(false);	// Row for tables
	cell21 = _td.cloneNode(false);	// Cell for word
	cell22 = _td.cloneNode(false);	// Cell for combo
	
	var text2 = doc.createTextNode("Select table: ");
	var combo2 = _select.cloneNode(false);	// SELECT control for tables
		combo2.setAttribute("name", "dbTableName");
	
	
	workarea.appendChild(table);
	table.appendChild(tbody);
	
	tbody.appendChild(row1);
		row1.appendChild(cell11);
			cell11.appendChild(text1);
		row1.appendChild(cell12);
			cell12.appendChild(combo1);
	
	tbody.appendChild(row2);
		row2.appendChild(cell21);
			cell21.appendChild(text2);
		row2.appendChild(cell22);
			cell22.appendChild(combo2);
	
	combo1.focus();
}

function CreateForm_new() {
	doc.form1.stage.value = "create_table";
	
	// Empty workarea
	EmptyWorkarea(div);
	
	// Declare vars
	var br;
	var freeVar;
	var i;
	var workarea = doc.getElementById(div);
	var z;
	

	// CREATE TABLE TABLE
	var nameTable = _table.cloneNode(false);
			nameTable.setAttribute("width", "100%");
		var nameTbody = _tbody.cloneNode(false);	
		var nameRow1 = _tr.cloneNode(false);
				nameRow1.className = "highlight";
			var nameCell1 = _td.cloneNode(false);
					nameCell1.colSpan = 2;
				var nameText1 = doc.createTextNode("About your new table:");
		var nameRow2 =  _tr.cloneNode(false);
			var nameCell21 = _td.cloneNode(false);
					nameCell21.setAttribute("width", "50%");
				var nameText2 = doc.createTextNode("To which database should your new table be added?");
			var nameCell22 = _td.cloneNode(false);
					nameCell22.setAttribute("width", "50%");
				var combo1 = _select.cloneNode(false);	// SELECT control for database
					combo1.setAttribute("name", "dbName");
					combo1.options[combo1.length] = new Option("Select database", "");  // Add "Select database option"
						for (i = 0; i < mysqlDatabaseArray.length; i++) {
							combo1.options[combo1.length] = new Option(mysqlDatabaseArray[i], mysqlDatabaseArray[i]);  // Add databases to options of SELECT control
						}
		var nameRow3 =  _tr.cloneNode(false);
			var nameCell31 = _td.cloneNode(false);
				var nameText3 = doc.createTextNode("What would you like to call your new table?");
			var nameCell32 = _td.cloneNode(false);
				var nameInput = _input.cloneNode(false);
						nameInput.name = "dbTableName";
						nameInput.type = "text";
	
	nameTable.appendChild(nameTbody);
		nameTbody.appendChild(nameRow1);
			nameRow1.appendChild(nameCell1);
				nameCell1.appendChild(nameText1);
		nameTbody.appendChild(nameRow2);
			nameRow2.appendChild(nameCell21);
				nameCell21.appendChild(nameText2);
			nameRow2.appendChild(nameCell22);
				nameCell22.appendChild(combo1);
		nameTbody.appendChild(nameRow3);
			nameRow3.appendChild(nameCell31);
				nameCell31.appendChild(nameText3);
			nameRow3.appendChild(nameCell32);
				nameCell32.appendChild(nameInput);

	// CREATE PREVIEW
	var previewTable = _table.cloneNode(true);  // Create table
			previewTable.className = "grid";
	
		var previewTbody = _tbody.cloneNode(false);  // Create tbody
			previewTable.appendChild(previewTbody);
		
		var row = _tr.cloneNode(false);
			var rowArray = new Array();
				rowArray = CreateElements(row, true, dataPreviewArray.length);
		
		var cell = _td.cloneNode(false);
			var cellArray = new Array();
				cellArray = CreateElements(cell, true, (dataPreviewArray[0].length*rowArray.length));
	
		// var input = _input.cloneNode(false);	
		// var combo = _select.cloneNode(false);
		
		for (i = 0; i < dataPreviewArray.length; i++) {
			// Add col to options of SELECT control
			//combo.options[combo.length] = new Option("Column " + (i+1), "col" + i);
			
			if (i == 0) rowArray[i].className = "header";
			// Add row
			previewTbody.appendChild(rowArray[i]);
			
			for (z = 0; z < dataPreviewArray[i].length; z++) {
				// Add cell
				rowArray[i].appendChild(cellArray[(i*dataPreviewArray[0].length)+z]);
			
				freeVar = doc.createTextNode(dataPreviewArray[i][z]);
				cellArray[(i*dataPreviewArray[0].length)+z].appendChild(freeVar);
			}
		}

	
	// CREATE MYSQL TABLE CREATION FORM
	var mysqlCell_name = new Array();
		var mysqlCell_type = new Array();
		var mysqlCell_extras = new Array();
		
		var mysqlTable = _table.cloneNode(true);
			mysqlTable.className = "grid";
			
		var mysqlTbody = _tbody.cloneNode(false);
			mysqlTable.appendChild(mysqlTbody);

		// Create header rows and cell
		row = _tr.cloneNode(false);
			row.className = "header";
			mysqlTbody.appendChild(row);

		freeVar = _td.cloneNode(false);
		cellArray = CreateElements(freeVar, true, 3);
			cellArray[0] = _td.cloneNode(false);
				freeVar = doc.createTextNode("Field name");
				cellArray[0].appendChild(freeVar);
				row.appendChild(cellArray[0]);
				
			cellArray[1] = cellArray[0].cloneNode(false);
				freeVar = doc.createTextNode("Field type");
				cellArray[1].appendChild(freeVar);
				row.appendChild(cellArray[1]);
				
			cellArray[2] = cellArray[0].cloneNode(false);
				freeVar = doc.createTextNode("Field extras");
				cellArray[2].appendChild(freeVar);
				row.appendChild(cellArray[2]);
		
				
		
		// Create field rows and cells
		rowArray = CreateElements(_tr, true, numOfCols);

		for (i = 0; i < numOfCols; i++) {
			mysqlTbody.appendChild(rowArray[i]);

			mysqlCell_name[i] = _td.cloneNode(true);
			
			freeVar = _input.cloneNode(false);	
				freeVar.id = "fieldName0" + i;	
				freeVar.name = "fieldName0" + i;	
				freeVar.size = 25;	
				mysqlCell_name[i].appendChild(freeVar);
				
			mysqlCell_type[i] = _td.cloneNode(true);
				freeVar = _input.cloneNode(false);
				freeVar.id = "fieldType0" + i;	
				freeVar.name = "fieldType0" + i;	
				freeVar.size = 12;
				mysqlCell_type[i].appendChild(freeVar);
				
			mysqlCell_extras[i] = _td.cloneNode(true);
				freeVar = _input.cloneNode(false);
				freeVar.id = "fieldExtras0" + i;	
				freeVar.name = "fieldExtras0" + i;	
				freeVar.size = 30;
			mysqlCell_extras[i].appendChild(freeVar);
			
			rowArray[i].appendChild(mysqlCell_name[i]);
			rowArray[i].appendChild(mysqlCell_type[i]);
			rowArray[i].appendChild(mysqlCell_extras[i]);
		}
	
	
	// COMPILE
	workarea.appendChild(nameTable);

	// Break
	workarea.appendChild(_br.cloneNode(false));
	workarea.appendChild(_br.cloneNode(false));
	
	// Directions
	cell = _td.cloneNode(false);
		freeVar = doc.createTextNode("Use the small preview of your file to help you create your new MySQL table.")
		cell.appendChild(freeVar);
		
		freeVar = InsertMessageTable(cell, "100%", "", "highlight");
		workarea.appendChild(freeVar);

	// Break
	workarea.appendChild(_br.cloneNode(false));
	
	// Preview table
	workarea.appendChild(previewTable);

	// Break
	workarea.appendChild(_br.cloneNode(false));
	workarea.appendChild(_br.cloneNode(false));

	// Click here ... Link to "CopyColumnHeaders()"
	cell = _td.cloneNode(false);
		a_href = _a.cloneNode(false);
				a_href.href = "JavaScript:CopyColumnHeaders()";
			text = doc.createTextNode("Click here");
	a_href.appendChild(text);
	cell.appendChild(a_href);
	
	text = doc.createTextNode(" if you would like to use the column headers as the field names.");
		cell.appendChild(text);
	
	workarea.appendChild(InsertMessageTable(cell, "100%", "", "highlight"));
	
	// Break
	workarea.appendChild(_br.cloneNode(false));
	
	// Create MySQL table table
	workarea.appendChild(mysqlTable);
}

function EmptyWorkarea() {
	var loDiv = doc.getElementById("workarea");

	while (loDiv.childNodes.length > 0) {
		for (var i = 0; i < loDiv.childNodes.length; i++) {
			loDiv.removeChild(loDiv.childNodes[0]);
		}
	}
}

function FieldSearch(form, fieldName) {
	var element;
	for (var i = 0; i < form.length; i++) {
		element = form[i];
		if (element.name == fieldName) return element;
	}
}

function GetTables() {
	var cmbTable = FieldSearch(doc.form1, "dbTableName");
	var cmbDatabase = FieldSearch(doc.form1, "dbName");
		var db = cmbDatabase.value;
	
	for (i = cmbTable.length; i >= 0; i--) {
		cmbTable.options[i] = null;
	}
	
	cmbTable.options[0] = new Option("Select table", "");
	
	for (i = 0; i < mysqlTableArray[db].length; i++) {
		cmbTable.options[(i+1)] = new Option(mysqlTableArray[db][i], mysqlTableArray[db][i]);
	}
	
	cmbTable.focus();
}

function InsertMessageTable(messageCell, tableWidth, tableClass, rowClass) {
	// messageCell needs to be a createElement("td") or _td.cloneNode!
	var table = _table.cloneNode(false);
		table.className = tableClass;
		table.width = tableWidth;
	var tbody = _tbody.cloneNode(false);
		table.appendChild(tbody);
		
	var tr = _tr.cloneNode(false);
		tr.className = rowClass;
		
		tbody.appendChild(tr);
		tr.appendChild(messageCell);
	
	return table;
}

function ValidateForm(form) {
	var dbTableName = FieldSearch(form, "dbTableName");
	var e;
	var freeVar;
	var i;
	var l = form.entryMethod.length;
	var m = "";
	var regexp_invalid = /^\d|\W|\s/g;
	var regexp_testFields = /^fieldName0|dbTableName/;
	var regexp_reqFields = /^fieldName0|^fieldType0|dbTableName/;
	var v = true;
	
	for (i = 0; i < l; i++) {
		e = form.entryMethod[i];
		if (e.checked == true) {
			m = e.value;
			break;
		}
	}
	
	switch(m) {
		case "existing" :
			freeVar = FieldSearch(form, "dbTableName");
			if (freeVar.value == "") {
				alert("No database table selected");
				v = false;
			}
		break;
		
		case "new" :
			for (i = 0; i < form.length; i++) {
				e = form[i];
				if ((regexp_reqFields.test(e.name)) && (e.value == "")) {
					alert("Please ensure you have entered the table name, all field names and their types.");
					v = false;
					break;
				} else if ((regexp_testFields.test(e.name)) && (regexp_invalid.test(e.value))) {
					alert("Field names can only be made up of letters, numbers and an underscore _.\t\n\nDo not start a table name or field name with a number.\nDo not use spaces.  (Use an underscore instead.)");
					v = false;
					break;
				}
			}
			
			freeVar = FieldSearch(form, "dbName");
			
			if ((v == true) && (freeVar.value == "")) {
				alert("You haven't selected a target database.");
				v = false;
			}
			
			if (v == true) {
				for (i = 0; i < mysqlTableArray[freeVar.value].length; i++) {
					if (dbTableName.value == mysqlTableArray[freeVar.value][i]) {
						alert("A table with that name already exists.  Please try another.");
						v = false;
						break;
					}
				}
			}
		break;
	}
	
	return v;
}