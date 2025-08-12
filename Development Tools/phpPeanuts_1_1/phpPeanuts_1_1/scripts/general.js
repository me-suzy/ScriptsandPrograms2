// Copyright (c) the partners of MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

//this file should not contain any application- or installation specific code
//for example localization-specific code should be factored out and overridden in
//specific.js 

function inArray(myArray, myElem) {
	for (i=0; i<myArray.length; i++) {
		if (myArray[i]==myElem) {
			return true;
		}
	}
	return false;
}

function getElement( elementId)
    {
    var element;

    if (document.all) {
        element = document.all[elementId];
        }
    else if (document.getElementById) {
        element = document.getElementById(elementId);
        }
    else element = -1;

    return element;
}

// table data link, follows link from table item to its editpage
function tdl(obj, itemId) {
	document.location.href = tdlGetHref(obj, itemId);
}

function tdlGetHref(obj, itemId) {
	dirTypeAndContext = obj.parentNode.parentNode.parentNode.id;
	arr = dirTypeAndContext.split('*');
	link = '../'+arr[0]+'index.php?pntType='+arr[1]+'&id='+itemId;
	if (arr[2] != '')
		link = link + '&pntContext='+arr[2]+'*'+arr[3]+'*'+arr[4];
	return link;
}

// clears dialog widget inputs in form of ObjectEditDetailsPage
function clrDialogWidget(idKey, labelKey)
{
	theForm = document.detailsForm;
	theForm[idKey].value = '';
	theForm[labelKey].value = '';
}

function getParentNodeByTagName(elem, tagName)
{
	while (elem.tagName != tagName) {
		elem = elem.parentNode;
	}
	return elem;
}

function invertTableCheckboxes(aButton)
{

	var aTableSection = getParentNodeByTagName(aButton, 'TABLE');
	var elements = aTableSection.getElementsByTagName("input");
	for (i=0; i<elements.length; i++) {
		if (elements[i].type=="checkbox") {
			if (elements[i].checked) {
				elements[i].checked=false;
			} else {
				elements[i].checked=true;
			}
		}
	}

}

function getObjectHtmlTag(myObj) {
	return myObj.outerHTML.replace(myObj.innerHTML,'');
}

//piece of code for replacing keypad . with ,
//this is the international version that does nothing. see specific.js
var metLK;
function metKD(evt) {
    metLK = evt.keyCode;
}
function metKP(evt) {
	//international version does nothing
}
//end of piece

function scaleContent()
{
	//default implementation: ignore
}

function pntTabSelected(allTabs, selected)
{
	 for (var i=0; i<allTabs.length; i++) {
	 	var contentDiv = getElement(allTabs[i] + 'Content');
	 	var tabDiv = getElement(allTabs[i] + 'Tab');

	 	if (allTabs[i] == selected) {
	 		tabDiv.className='pntTab_selected';
	 		contentDiv.style.display='block';
	 	} else {
	 		tabDiv.className='pntTab';
	 		contentDiv.style.display='none';
	 	}
	}
}


function pntPrint_r(what) {
    var output = '';
    for (var i in what)
        output += i+ ' = ' + what[i] + '\n';
    return(output);
}



