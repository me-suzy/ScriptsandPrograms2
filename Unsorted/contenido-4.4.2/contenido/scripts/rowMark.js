/**
 * Table rowMark
 *
 * myRow = new rowMark(1,2,3,4)
 *
 *   1:	Farbe des Over Effekts z.B. "#ff0000" - string
 *   2:	Farbe des Mark Effeks - string
 *   3:	Farbe des Over Effeks bei der Marked Row - string
 *   4: Funktion die bei onClick aufgerufen wird - string
 *
 *   <tr class="grau" onMouseOver="myRow.over(this)" onMouseOut="myRow.out(this)" onClick="myRow.click(this)">
 *       <td>eine Zeile</td>
 *       <td><img src="einbild.gif"></td>
 *   </tr>
 *
 * @param String sOverColor     Over-Color
 * @param String sMarkedColor   Marked-Color
 * @param String sOverMarked    Over-Marked-Color
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @version 1.2
 * @copyright Jan Lengowski 2002
 */
function rowMark(overColor, markedColor, overMarked, onClick) {
	
    /**
     * Set class properties
     * @access private
     */		
    this.overColor = overColor;
    this.markedColor = markedColor;
    this.overMarked = overMarked;
    this.onClick = onClick;

    /**
     * dynamic properties
     * @access private
     */
    this.oldColor = '';
    this.oldColorMarked = '';
    this.markedRow = '';

    /**
     * Define class methods
     * @access private
     */
    this.over = rowMark_over;
    this.out = rowMark_out;
    this.click = rowMark_click;

    /**
     * Browsercheck
     * @access private
     */
    this.browser = '';

}

/**
 * rowMark::over()
 * @param object oRow table row object
 */
function rowMark_over(oRow) {

    if ( oRow.style.backgroundColor != this.markedColor ) {
        this.oldColor = oRow.style.backgroundColor;
    }
    
    if ( oRow.style.backgroundColor == this.markedColor ) {
        oRow.style.backgroundColor = this.overMarked;
    } else {
        oRow.style.backgroundColor = this.overColor;
    }
    
}

/**
 * rowMark::out()
 * @param object oRow table row object
 */
function rowMark_out(oRow) {

    if (oRow == this.markedRow) {
        oRow.style.backgroundColor = this.markedColor;
    } else {
        oRow.style.backgroundColor = this.oldColor;
    }
    
}

/**
 * rowMark::over()
 * @param object oRow table row object
 */
function rowMark_click(oRow) {

    if ( "" == this.markedRow ) {
        oRow.style.backgroundColor = this.markedColor;
        this.markedRow = oRow;
        this.oldColorMarked = this.oldColor;
        
        if ( "" != this.onClick ) {
            eval( this.onClick );
        }
        
    } else {
        this.markedRow.style.backgroundColor = this.oldColorMarked;
        oRow.style.backgroundColor = this.markedColor;
        this.markedRow = oRow;
        this.oldColorMarked = this.oldColor;
        
        if ( "" != this.onClick ) {
            eval( this.onClick );
        }
    }
}


/**
 * Table rowMark with image rollover
 *
 * REQUIRES rowMark CLASS!
 *
 * myRow = new imgMark(1, 2, 3, 4, 5, 6);
 *
 *  1:  Farbe des Over Effekts z.B. "#ff0000" - string
 *  2:	Farbe des Mark Effeks - string
 *  3:	Farbe des Over Effeks bei der Marked Row - string
 *  4:  Pfad des Bildes das bei .over() gewechselt wird - string
 *  5:  Pfad des Bildes das bei .out() gewechselt wird - string
 *  6:  Function die bei onClick aufgerufen wird - string
 *
 *   <tr class="grau" onMouseOver="myRow.over(this, 0)" onMouseOut="myRow.out(this, 0)" onClick="myRow.click(this)">
 *       <td>eine Zeile</td>
 *       <td><img src="einbild.gif"></td>
 *   </tr>
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @version 1.2
 * @copyright Jan Lengowski 2002
 */
function imgMark(overColor, markedColor, overMarked, imgOutSrc, imgOverSrc, onClick) {

    /**
     * Call parent class constructor
     * @access private
     */
    this.base = rowMark;
    this.base(overColor, markedColor, overMarked, onClick);

    /**
     * Set image path properties
     * @access private
     */
    this.imgOutSrc = imgOutSrc;
    this.imgOverSrc = imgOverSrc;

    /**
     * Modify inherited .over() method
     * @access private
     */
    var str = this.over + '';
    var astr = str.split('\n');
    var fstr = 'var img = oRow.getElementsByTagName("IMG"); img[imgId].src = this.imgOverSrc;';
    for (i=2; i<astr.length-2; i++) {
        fstr += astr[i];
    }
    this.over = new Function ('oRow', 'imgId', fstr);
    
    /**
     * Modify inherited .out() method
     * @access private
     */
    var str = this.out + '';
    var astr = str.split('\n');
    var fstr = 'var img = oRow.getElementsByTagName("IMG");img[imgId].src = this.imgOutSrc;';

    for (i=2; i<astr.length-2; i++) {
        fstr += astr[i];
    }
    this.out = new Function ('oRow', 'imgId', fstr);
    
}
imgMark.prototype = new rowMark;

/* Sets the path value
   in the area 'upl' */
function setPath( obj ) {
    parent.frames['left_top'].document.forms[0].path.value = obj.id;
    parent.frames['left_top'].document.getElementById("caption2").innerHTML = obj.id;
}

/**
 * Interface function for transfering
 * data from left-bottom frame to the
 * configuration object in the left-top
 * frame.
 *
 * @param object HTML Table Row Object
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function conInjectData(obj) {

    /* Configuration Object Reference */
    cfgObj = parent.frames['left_top'].cfg;
    
    /* Split the data string.
       0 -> category id
       1 -> category template id
       2 -> category online
       3 -> category public
       4 -> has right for: template
       5 -> has right for: online
       6 -> has right for: public
       7 -> idstring not splitted */
    tmp_data = obj.id;
    data = tmp_data.split("-");
    
    if ( data.length == 7 ) {
        /* Transfer data to the cfg object
           through the .load() method */
        cfgObj.load(data[0], data[1], data[2], data[3], data[4], data[5], data[6], data[7]);

    } else {
        cfgObj.reset();

    }
    
    /* String for debugging */
    str  = "";
    str += "Category ID is: "     + data[0] + "\n";
    str += "Template ID is: "     + data[1] + "\n";
    str += "Online status is: "   + data[2] + "\n";
    str += "Public status is: "   + data[3] + "\n";
    str += "Right for Template: " + data[4] + "\n";
    str += "Right for Online: "   + data[5] + "\n";
    str += "Right for Public: "   + data[6] + "\n";

    if (is.NS)
    {
        if (!parent.frames['left_top'].cfg.scrollX) parent.frames['left_top'].cfg.scrollX = 0;
        if (!parent.frames['left_top'].cfg.scrollY) parent.frames['left_top'].cfg.scrollY = 0;

        parent.frames['left_top'].cfg.scrollX = scrollX;
        parent.frames['left_top'].cfg.scrollY = scrollY;
    }
}

/**
rowMark instances 
**/

/* rowMark instance for the
   general use */
row = new rowMark('#D7F0AF', '#D7F0AF', '#a9aec2');

/* rowMark instance for the
   Subnavigation */
//sub = new rowMark('#c6c6d5', '#a9aec2', '#c6c6d5');
sub = new rowMark('#334F77', '#334F77', '#334F77');

/* rowMark instance for the
   Content area */
con = new rowMark('#D7F0AF', '#D7F0AF', '#a9aec2', 'conInjectData(oRow)');

/* rowMark instance for the
   Upload area */
upl = new rowMark('#D7F0AF', '#D7F0AF', '#a9aec2', 'setPath(oRow)');

/* Create a new rowMark
   Instance for the Content-
   Article overview area */
artRow = new rowMark('#D7F0AF', '#D7F0AF', '#D7F0AF', 'conArtOverviewExtractData(oRow)');


/* rowMark instance for
   area 'lay' */
lay = new rowMark('#D7F0AF', '#D7F0AF', '#a9aec2', 'saveObj(oRow)');

function saveObj(oRow)
{
    parent.frames["left_top"].obj = oRow.id;
}

/**
 * Generic function to reMark a row
 */
function reMark(sObjId)
{
    var elm = document.getElementById(sObjId);
    
    if (typeof elm == 'object')
    {
        lay.over(elm);
        lay.click(elm);
    }
}
