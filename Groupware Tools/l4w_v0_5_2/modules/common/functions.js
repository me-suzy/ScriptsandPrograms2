
var marked_row = new Array;



function ColorOver(theRow, theRowNum, newColor) {

    var theCells = null;

    // 1. Get Current Row
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // Set new color
    for (i = 0; i < theCells.length; i++) {
        theCells[i].style.backgroundColor = newColor;
    }
    //mouseOverId = theRowNum;
    return true;
}

function ColorOut(theRow, theRowNum, theColor) {
    ColorOver (theRow, theRowNum, theColor);
    return true;
}

