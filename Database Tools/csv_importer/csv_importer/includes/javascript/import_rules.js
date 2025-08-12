// Misc Global vars:
var mainWorkarea = "importRuleSetWorkarea";
var ruleDivName = "divRule";


// Step arrays
var step1Array = new Array("Insert", "Update", "Delete");

var step2Array = new Array();
	step2Array["insert"] = new Array();
		step2Array["insert"][step2Array["insert"].length] = new Array("all records in .CSV file", "all");
		step2Array["insert"][step2Array["insert"].length] = new Array("into [" + dbTableName + "] where", "where");
		
	step2Array["update"] = new Array();
		step2Array["update"][step2Array["update"].length] = new Array("[" + dbTableName + "] with .CSV file", "all");
		step2Array["update"][step2Array["update"].length] = new Array("[" + dbTableName + "] where", "where");
		
	step2Array["delete"] = new Array();
		step2Array["delete"][step2Array["delete"].length] = new Array("all records in [" + dbTableName + "]", "all");
		step2Array["delete"][step2Array["delete"].length] = new Array("from [" + dbTableName + "] where", "where");
	
var step3Array = new Array();
	step3Array["insert"] = new Array();
		step3Array["insert"]["all"];
		step3Array["insert"]["where"] = new Array("_colOptions");
	step3Array["update"] = new Array();
		step3Array["update"]["all"];
		step3Array["update"]["where"] = new Array("_colOptions");
	step3Array["delete"] = new Array();
		step3Array["delete"]["all"];
		step3Array["delete"]["where"] = new Array("_colOptions");

var step4Array = new Array();
	step4Array["insert"] = new Array();
	step4Array["insert"]["where"] = new Array();
		step4Array["insert"]["where"]["_coloptions"] = GetOperands();
		
	step4Array["update"] = new Array();
	step4Array["update"]["where"] = new Array();
		step4Array["update"]["where"]["_coloptions"] = GetOperands();
		
	step4Array["delete"] = new Array();
	step4Array["delete"]["where"] = new Array();
		step4Array["delete"]["where"]["_coloptions"] = GetOperands();

var step5Array = new Array("[Set value]", "_colOptions");



// Functions
function ClearSteps(ruleID, stepID) {
	var div;
	stepID++;
	
	while (stepID < ruleStages) {
		div = doc.getElementById(ruleDivName + ruleID + "_step" + stepID);
		WhackDiv(div);
		
		stepID++;
	}
}
		

function GetOperands() {	
	var tempArray = new Array();
	
	for (var i = 0; i < operandsArray.length; i++) {
		tempArray[i] = new Array(operandsArray[i][0], operandsArray[i][1]);
	}
	
	return tempArray;
}



function MakeDelete(id) {
	var a = _a.cloneNode(false);
		a.href="JavaScript:RemoveRule('" + ruleDivName + id + "', " + id + ")";
		a.id = ruleDivName + id + "_delete";

	var img = _img.cloneNode(false);
		img.alt = "Delete this rule";
		img.border = 0;
		img.height = 9;
		img.src = "includes/images/minus.gif";
		img.width = 9;

	a.appendChild(img);

	return a;
}



function MakeRuleCombo(stepArray, ruleID, stepID) {
	var i;
	var z;
	
	var combo = _select.cloneNode(false);
		combo.id = "cmbRule" + ruleID + "_step" + stepID;
		combo.name = "cmbRule" + ruleID + "_step" + stepID;
		combo.onchange = function () {NextStep(this, ruleID, stepID)}
		combo.options[0] = new Option("Select", "");
	
		for (i = 0; i < stepArray.length;  i++) {
			if (stepArray[i] == "_colOptions") {
				for (z = 0; z < numOfCols; z++) {
					combo.options[combo.length] = new Option("column " + (z+1), "_colOption" + z);
				}
			} else if (typeof stepArray[i] == "object") {
				combo.options[combo.length] = new Option(stepArray[i][0], stepArray[i][1].toLowerCase())
			} else {
				combo.options[combo.length] = new Option(stepArray[i], stepArray[i].toLowerCase());
			}
		}
	return combo;
}
		
function NewRule() {
	// Basic vars
	var i;
	var workarea = doc.getElementById(mainWorkarea);
	var newDivId = workarea.childNodes.length;
	
	// Form elements
	var combo;
	
	// HTML elements
	var a;
	var br;
	var div;
	var img;
	
	// Table elements
	var table = _table.cloneNode(false);
		table.align = "left";
		// table.className = "ruleSet";
	var tbody = _tbody.cloneNode (false);
		table.appendChild(tbody);
	var tr = _tr.cloneNode(false);
		tbody.appendChild(tr);
	var td = _td.cloneNode(false);
		var tdArray = CreateElements(td, false, ruleStages);
		
	for (i = 0; i < tdArray.length; i++) {
		switch(i) {
			case 0 :
				a = MakeDelete(newDivId);
				tdArray[i].appendChild(a);
			break;
			
			case 1 :
				div = _div.cloneNode(false);
					div.id = ruleDivName + newDivId + "_step" + i;
					div.appendChild(MakeRuleCombo(step1Array, newDivId, 1));
				tdArray[i].appendChild(div);
			break;

			default :
				div = _div.cloneNode(false);
					div.id = ruleDivName + newDivId + "_step" + i;
				tdArray[i].appendChild(div);
		}
		tr.appendChild(tdArray[i]);
	}
	
	div = _div.cloneNode(false);
		div.id = ruleDivName + newDivId;
	
	if (newDivId > 0) {
		div.appendChild(_br.cloneNode(false));
		div.appendChild(_br.cloneNode(false));
	}
	
	div.appendChild(table);
	
	workarea.appendChild(div);
	
	ReIndexRules(workarea);
}



function NextStep(ruleStep, ruleID, stepID) {
	ClearSteps(ruleID, stepID);

	var arrayCount = (stepID+1);
	var arrayName = "";
	var divNextStep = doc.getElementById(ruleDivName + ruleID + "_step" + (stepID+1));
	var previousStep = doc.getElementById("cmbRule" + ruleID + "_step" + stepID);
	var regexp_colOption = /^_coloption/i;
	var regexp_lastInLine = /\.$/g;
	var textbox;

	if (CheckInsertOnly()) {
		alert("You can only insert because of a lack of a primary key.  Select a field to use as a primary key.");
		arrayName = undefined;
		previousStep.selectedIndex = 0;
	} else {	
		if (!regexp_lastInLine.test(previousStep.options[previousStep.selectedIndex].text)) {
			switch((stepID+1)) {
				case 2 : 
					arrayName = eval("step" + (stepID+1) + "Array['" + previousStep.value.toLowerCase() + "']");
				break;
				
				case 5 :
					arrayName = eval("step" + (stepID+1) + "Array");
				break ;
				
				case 6 :
					if (previousStep.value.toLowerCase() == "[set value]") {
						textbox = _input.cloneNode(false);
							textbox.id = "txtRule" + ruleID + "_step" + (stepID+1);
							textbox.name = "txtRule" + ruleID + "_step" + (stepID+1);
							textbox.size = 12;
							textbox.type = "text";
							
						divNextStep.appendChild(textbox);
					}
				break;
				
				
				default :
					while (arrayCount > 1) {
						previousStep = doc.getElementById("cmbRule" + ruleID + "_step" + (arrayCount-1));
						
						arrayName = (regexp_colOption.test(previousStep.value.toLowerCase())) ? arrayName + '["_coloptions"]' : '["' + previousStep.value.toLowerCase() + '"]' + arrayName;
						arrayCount--;				
					}
					arrayName = eval("step" + (stepID+1) + "Array" + arrayName);
			}
		
		
			if ((arrayName != undefined) && (stepID != 5)) {
				divNextStep.appendChild(MakeRuleCombo(arrayName, ruleID, (stepID+1)));  // Set value
			}
		}
	}
	
	function CheckInsertOnly() {
		if (doc.getElementById("cmbPrimaryKeyField")) {
			return ((previousStep.value.toLowerCase() != "insert") && (doc.getElementById("cmbPrimaryKeyField").value.toLowerCase() == "insertonly")) ? true : false;
		}
	}
}


function ReIndexRules() {
	var a;
	var childDiv;
	var div;
	var nodeParent;
	var oldDivId;
	var workarea = doc.getElementById(mainWorkarea);
	
	for (var i = 0; i < workarea.childNodes.length; i++) {
		childDiv = workarea.childNodes[i]
			oldDivId = childDiv.id;
			oldDivId = oldDivId.replace(ruleDivName, "");
			
		childDiv.id = ruleDivName + i;
		
		a = doc.getElementById(ruleDivName + oldDivId + "_delete");
			nodeParent = a.parentNode;
			nodeParent.removeChild(a);
			nodeParent.appendChild(MakeDelete(i));

		for (var z = 0; z < ruleStages; z++) {
			if (div = doc.getElementById(ruleDivName + oldDivId + "_step" + oldDivId)) div.id = ruleDivName + i + "_step" + i;
		}
	}
	
	var field = doc.getElementById("numOfRules");
		field.value = workarea.childNodes.length;
}


function RemoveRule(divName, index) {
	var div = doc.getElementById(divName);
	var workarea = doc.getElementById(mainWorkarea);
	
	WhackDiv(div);
	
	workarea.removeChild(workarea.childNodes[index]);
	
	ReIndexRules();
}


function SaveRuleSet() {
	var workarea = doc.getElementById(mainWorkarea);
	
	var fileName = (workarea.childNodes.length > 0) ? prompt("What would you like to call this file?", "") : alert("You must create some import rules to be able to save them as a rule set.");

	if (typeof fileName == "string") {
		while (fileName == "") {
			alert("The file name cannot be blank and must contain only letters, numbers and underscore.");
			fileName = prompt("What would you like to call this file?", "");
		}
		doc.getElementById("ruleSetName").value = fileName;
		alert("Your ruleset will be saved when you click \"Next\".");
	}
}


function UseImportRuleSet(field) {
	var config = field.value;
	var element;
	
	WhackDiv(doc.getElementById(mainWorkarea));
	
	if (config != "") {
		var regexp_textfield = /\W/;
		for (var i = 0; i < irfConfigArray[config].length; i++) {
			NewRule();
			for (var z = 0; z < irfConfigArray[config][i].length; z++) {
				if ((isNaN(parseInt(irfConfigArray[config][i][z]))) || (regexp_textfield.test(irfConfigArray[config][i][z]))) {
					if (element = doc.getElementById("txtRule" + i + "_step" + (z+1))) {
						element.value = irfConfigArray[config][i][z];
					}
				} else {
					if (element = doc.getElementById("cmbRule" + i + "_step" + (z+1))) {
						element.selectedIndex = irfConfigArray[config][i][z];
						NextStep(element, i, (z+1));
					}
				}
			}
		}
	}
	
	field.selectedIndex = 0;
}


function ValidateForm(form) {
	var e;
	var i;
	var regexp_fieldOptions = /^dbFieldName/i;
	var regexp_ruleSteps = /^cmbRule\d*_step\d*/i;
	var v = true;
	var workarea = doc.getElementById(mainWorkarea);
	var z;
	
	for (i = 0; i < form.length; i++) {
		e = form[i];
		
		if ((regexp_fieldOptions.test(e.name)) && (e.value == "")) {
			alert("All fields must have either a column, set value or no value specified.");
			v = false;
			break;
		} else if (workarea.childNodes.length == 0) {
			alert("No import rule set has been specified.");
			v = false;
			break;
		} else if ((regexp_ruleSteps.test(e.name)) && (e.value == "")) {
			alert("Invalid rule detected within ruleset.");
			v = false;
			break;
		}
	}
	
	if (v == true) {
		var div = document.getElementById("importRuleSetWorkarea");
		var field = document.getElementById("ruleConfig");
		var ruleConfig = "";
		var numOfRules = div.childNodes.length;
		
		for (i = 0; i < numOfRules; i++) {
			for (z = 0; z < ruleStages; z++) {
				if (e = document.getElementById("cmbRule" + i + "_step" + (z+1))) {
					ruleConfig += (z == 0) ? "|_r" + i + "|" + e.selectedIndex : "|" + e.selectedIndex;
				} else if (e = document.getElementById("txtRule" + i + "_step" + (z+1))) {
					ruleConfig += '|' + e.value;
				} else {
					break;
				}
			}
		}
		field.value = ruleConfig.substring(1, ruleConfig.length);
	}
	
	return v;
}