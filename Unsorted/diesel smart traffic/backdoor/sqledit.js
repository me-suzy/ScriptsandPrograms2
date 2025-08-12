function rowCol(theRow, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    } 

    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
        }
    }
    
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()) {
        if (theAction == 'out') {
            newColor = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor = theMarkColor;
        }
    }
    
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor = (thePointerColor != '')
                     ? thePointerColor
                     : theDefaultColor;
        }
    }

    
    if (newColor) {
        var c = null;
    
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } 
        }
    
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } 

    return true;
} 