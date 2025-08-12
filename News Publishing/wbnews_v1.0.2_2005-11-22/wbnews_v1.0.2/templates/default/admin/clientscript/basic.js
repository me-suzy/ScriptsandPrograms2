/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created:  17th May 2005                          #||
||#     Filename: basic.js                               #||
||#                                                      #||
||########################################################||
/*========================================================*/

var basic;

/* Initialise Editor and Properties */
function basic_init()
{    
    // get editor and Display Basic Editor
    basic = getObject("basic_editor");
    basic.style.display = 'block';
    
    /* Setup Controls */
    for (var i in controls)
    {
        var e = getObject('cmd_' + controls[i]);
        if (e != null)
        {
            if (!is_ie && !is_op)
            {
                if (controls[i] !== "emoticon")
                {
                    e.addEventListener("click", basic_format, true);
                }
                else
                {
                    e.addEventListener("click", basic_popupemoticon, true);
                }
            }
            else
            {
                // i hate IE unfortunately the addEventListener doesnt work, attachEvent isnt always going to work
                if (controls[i] !== "emoticon")
                {
                    e.onclick = basic_format;
                }
                else
                {
                     e.onclick = basic_popupemoticon;
                }
            }
        }
    }
	
    /* Onload we need to reorganize the Textarea - unfortunately this is quite a hack */
	basic.style.width = getObject('wysiwyg-container').offsetWidth + (is_ie || is_op ? -10 : 0) + 'px';
}

//########################## BASIC EMOTICON CODE ############################//
function basic_emoticon(code)
{
    if (!is_ie)
    {
        basic.value = basic.value.substr(0, basic.selectionStart) + code + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
    }
    else
    {
        basic.value = basic.value + code;
    }
}

//########################## BASIC EMOTICON POPUP ###########################//
function basic_popupemoticon()
{
    window.open("news.php?action=emoticon", "Emoticon", 'width=200,height=180,top=100,left=150');
}

//########################### BASIC BB FORMATTER ############################//
function basic_format(e)
{
    e = (window.event ? window.event : e);
	var id = (!is_ie ? e.target.id : e.srcElement.id); //we using IE if not we have an event sent
    
    var type = new String(id);
	type = type.substr(type.indexOf("_") + 1); // remove the cmd_
    
    switch (type)
	{
	case 'bold':
	    basic_noAttr(id, 'b');
    break;
	case 'underline':
        basic_noAttr(id, 'u');
	break;
	case 'italic':
        basic_noAttr(id, 'i');
	break;
	case 'link':
    
        if ((basic.selectionStart !== basic.selectionEnd) || (is_ie && document.selection.type === "Text"))
        {
            // Its ok
            var link = prompt("Enter a URL", "http://");
            if ((link) && link  !== "http://")
            {
                basic_Attr(id, 'url', link);
            }
        }
        else
        {
            // do we have an open tag ?
            if (inArray(id, tagStack))
            {
                // close, do a function call only
                basic_Attr(id, 'url');
            }
            else
            {
                var link = prompt("Enter a URL", "http://");
                if ((link) && link  !== "http://")
                {
                    basic_Attr(id, 'url', link);
                }
            }
        }
        
	break;
	case 'center':
	case 'left':
	case 'right':
        basic_Attr(id, 'align', type);
	break;
	case 'orderedlist':
	case 'unorderedlist':
        basic_buildlist(type);
	break;
    case 'php':
        basic_noAttr(id, 'php');
    break;
	default:
		return;
	}
    
    basic.focus();
    
}

//############################# BASIC FORM VAL ##############################//
function basic_submit()
{
    /*
        Check Title Size
        Check Message Size
        Check Message Has all tags closed
    */
    var isValid = true;
    
    var form = document.forms[0];
    if (form.elements["title"].value.length == 0)
    {
        alert("Title mustn't be empty");
        return false;
    }
    
    if (form.elements["message"].value.length == 0)
    {
        alert("Message mustn't be empty");
        return false;
    }
    
    if (tagStack.length !== 0)
    {
        alert("There is/are " + tagStack.length + " tag(s) still open");
        return false;
    }
        
    return true;
    
}

//#############################BAISC NO BB ATTR #############################//
function basic_noAttr(id, code)
{
    var element = document.getElementById(id);
    if ((basic.selectionStart !== basic.selectionEnd) || (is_ie && document.selection.type === "Text"))
    {
        // text has been selected
        // check if client is_ie or not
        if (!is_ie)
        {
            basic.value = basic.value.substr(0, basic.selectionStart) + '[' + code + ']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + '[/' + code + ']' + basic.value.substr(basic.selectionEnd);
        }
        else
        {
            var range = document.selection.createRange();
            range.text = '[' + code + ']' + range.text + '[/' + code + ']';
        }
    }
    else
    {
        if (!is_ie)
        {
            // check if the Tag has already been used
            if (inArray(id, tagStack))
            {
                // close tag
                basic.value = basic.value.substr(0, basic.selectionStart) + '[/' + code + ']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
                tagStack = removeArrayElement(id, tagStack);
                element.className = '';
            }
            else
            {
                // open tag
                basic.value = basic.value.substr(0, basic.selectionStart) + '[' + code + ']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
                tagStack.push(id);
                element.className = iconSelectedClass;
            }
        }
        else // IE Implementation
        {
            /* IE unfortunately doesnt work correctly for the moment and will be old style */
            if (inArray(id, tagStack))
            {
                // close tag
                basic.value = basic.value + '[/' + code + ']';
                tagStack = removeArrayElement(id, tagStack);
                element.className = '';
            }
            else
            {
                // open tag
                basic.value = basic.value + '[' + code + ']';
                tagStack.push(id);
                element.className = iconSelectedClass;
            }
        }
    }
}

//############################## BASIC BB ATTR ##############################//
function basic_Attr(id, code, attr)
{
    var element = document.getElementById(id);
    
    //if ((basic.selectionStart !== basic.selectionEnd) || (is_ie && document.selection.type === "Text"))
    if ((basic.selectionStart !== basic.selectionEnd))
    {
        // text has been selected
        // check if client is_ie or not
        if (!is_ie)
        {
            basic.value = basic.value.substr(0, basic.selectionStart) + '[' + code + '=' + attr +']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + '[/' + code + ']' + basic.value.substr(basic.selectionEnd);
        }
        else
        {
            var range = document.selection.createRange();
            range.text = '[' + code + ']' + range.text + '[/' + code + ']';
        }
    }
    else
    {
        if (!is_ie)
        {
            // check if the Tag has already been used
            if (inArray(id, tagStack))
            {
                // close tag
                basic.value = basic.value.substr(0, basic.selectionStart) + '[/' + code + ']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
                if (element !== null)
                tagStack = removeArrayElement(id, tagStack);
                element.className = '';
            }
            else
            {
                // open tag
                basic.value = basic.value.substr(0, basic.selectionStart) + '[' + code + '=' + attr +']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
                tagStack.push(id);
                element.className = iconSelectedClass;
            }
        }
        else // IE Implementation
        {
            /* IE unfortunately doesnt work correctly for the moment and will be old style */
            if (inArray(id, tagStack))
            {
                // close tag
                basic.value = basic.value + '[/' + code + ']';
                tagStack = removeArrayElement(id, tagStack);
                element.className = '';
            }
            else
            {
                // open tag
                basic.value = basic.value + '[' + code + '=' + attr +']';
                tagStack.push(id);
                element.className = iconSelectedClass;
            }
        }
    }
}

//########################## BASIC BB FONT FAMILY ###########################//
function basic_fontfamily(fontName)
{
    var id = "cmd_fontfamily";
    var arrowID = "cmd_fontfamily_arrow";
    element = getObject(id);
    elementArray = getObject(arrowID);
    
    //if ((basic.selectionStart !== basic.selectionEnd) || (is_ie && document.selection.type === "Text"))
    if ((basic.selectionStart !== basic.selectionEnd))
    {
        // text has been selected
        // check if client is_ie or not
        if (!is_ie)
        {
            basic.value = basic.value.substr(0, basic.selectionStart) + '[type=' + fontName + ']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + '[/type]' + basic.value.substr(basic.selectionEnd);
        }
        else
        {
            var range = document.selection.createRange();
            range.text = '[type=' + fontName + ']' + range.text + '[/type]';
        }
    }
    else
    {
        if (!is_ie)
        {
            // check if the Tag has already been used
            if (inArray(id, tagStack))
            {
                // close tag
                basic.value = basic.value.substr(0, basic.selectionStart) + '[/type]' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
                
                tagStack = removeArrayElement(id, tagStack);
                tagStack = removeArrayElement(arrowID, tagStack);
                
                element.className = '';
                arrowID.className = '';
            }
            else
            {
                // open tag
                basic.value = basic.value.substr(0, basic.selectionStart) + '[type=' + fontName + ']' + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
                
                tagStack.push(id);
                tagStack.push(arrowID);
                
                element.className = iconSelectedClass;
                arrowID.className = iconSelectedClass;
            }
        }
        else // IE Implementation
        {
            /* IE unfortunately doesnt work correctly for the moment and will be old style */
            if (inArray(id, tagStack))
            {
                // close tag
                basic.value = basic.value + '[/type]';
                
                tagStack = removeArrayElement(id, tagStack);
                tagStack = removeArrayElement(arrowID, tagStack);
                
                element.className = '';
                arrowID.className = '';
            }
            else
            {
                // open tag
                basic.value = basic.value + '[type=' + fontName + ']';
                
                tagStack.push(id);
                tagStack.push(arrowID);
                
                element.className = iconSelectedClass;
                arrowID.className = iconSelectedClass;
            }
        }
    }
}

//############################## BASIC LISTS ################################//
function basic_buildlist(type)
{
    var listType = (type == "orderedlist" ? "numbered" : "");
    var listArray = new Array;
    
    var doAgain = true;
    var curStr;
    var i = 0;
    
    while (doAgain === true)
    {
        curStr = prompt("Press Cancel or Leave Blank to exit","");
        if (!curStr || curStr == "" || curStr === null)
        {
            doAgain = false;
        }
        else
        {
            listArray[i] = curStr;
        }
        i = i + 1;
    }
    
    if (listArray.length === 0)
    {
        basic.focus();
        return;
    }
    else
    {
        
        var myCode = "";
        for (var i in listArray)
        {
            if (listArray[i] !== "")
            {
                myCode += "[*]" + listArray[i] + "\r\n";
            }
        }
        
        var code = "[list" + (listType !== "" ? "=" + listType : "") + "]\r\n" + myCode + "[/list]";
        
        // IE will have to do it the old way for the time being
        if (!is_ie)
        {
            basic.value = basic.value.substr(0, basic.selectionStart) + code + basic.value.substring(basic.selectionStart, basic.selectionEnd) + basic.value.substr(basic.selectionEnd);
        }
        else
        {
            basic.value += code;
        }
        
    }
    
}

//######################## BASIC FONT FAMILY BUILD ##########################//
var fontFamily = new Array("Arial", "Arial Black", "Arial Narrow", "Book Antiqua", "Century Gothic", "Comic Sans MS", "Courier New", "Fixedsys", "Franklin Gothic Medium", "Garamond", "Georgia", "Impact", "Lucida Console", "Lucida Sans Unicode", "Microsoft Sans Serif", "Palatino Linotype", "System", "Tahoma", "Times New Roman", "Trebuchet MS", "Verdana");
function basic_build_fontfamily()
{
    for (var i in fontFamily)
    {
        document.writeln('<option value="' + fontFamily[i] + '" style="font-family: ' + fontFamily[i] + ';">' + fontFamily[i] + '</option>');
    }
}

//######################## BASIC SIZE FAMILY BUILD ##########################//
var fontSize = new Array(1, 2, 3, 4, 5, 6, 7);
function basic_build_fontsize()
{
    for (var i in fontSize)
    {
        document.writeln('<option value="' + fontSize[i] + '">' + fontSize[i] + '</option>');
    }
}

//######################### BASIC FONT COLOR BUILD ##########################//
var fontColor = new Array(new Array("AliceBlue", "#F0F8FF"), new Array("AntiqueWhite", "#FAEBD7"), new Array("Aqua", "#00FFFF"), new Array("Aquamarine", "#7FFFD4"), new Array("Azure", "#F0FFFF"), new Array("Beige", "#F5F5DC"), new Array("Bisque", "#FFE4C4"), new Array("Black", "#000000"), new Array("BlanchedAlmond", "#FFEBCD"), new Array("Blue", "#0000FF"), new Array("BlueViolet", "#8A2BE2"), new Array("Brown", "#A52A2A"), new Array("BurlyWood", "#DEB887"), new Array("CadetBlue", "#5F9EA0"), new Array("Chartreuse", "#7FFF00"), new Array("Chocolate", "#D2691E"), new Array("Coral", "#FF7F50"), new Array("CornflowerBlue", "#6495ED"), new Array("Cornsilk", "#FFF8DC"), new Array("Crimson", "#DC143C"), new Array("Cyan", "#00FFFF"), new Array("DarkBlue", "#00008B"), new Array("DarkCyan", "#008B8B"), new Array("DarkGoldenRod", "#B8860B"), new Array("DarkGray", "#A9A9A9"), new Array("DarkGreen", "#006400"), new Array("DarkKhaki", "#BDB76B"), new Array("DarkMagenta", "#8B008B"), new Array("DarkOliveGreen", "#556B2F"), new Array("Darkorange", "#FF8C00"), new Array("DarkOrchid", "#9932CC"), new Array("DarkRed", "#8B0000"), new Array("DarkSalmon", "#E9967A"), new Array("DarkSeaGreen", "#8FBC8F"), new Array("DarkSlateBlue", "#483D8B"), new Array("DarkSlateGray", "#2F4F4F"), new Array("DarkTurquoise", "#00CED1"), new Array("DarkViolet", "#9400D3"), new Array("DeepPink", "#FF1493"), new Array("DeepSkyBlue", "#00BFFF"), new Array("DimGray", "#696969"), new Array("DodgerBlue", "#1E90FF"), new Array("Feldspar", "#D19275"), new Array("FireBrick", "#B22222"), new Array("FloralWhite", "#FFFAF0"), new Array("ForestGreen", "#228B22"), new Array("Fuchsia", "#FF00FF"), new Array("Gainsboro", "#DCDCDC"), new Array("GhostWhite", "#F8F8FF"), new Array("Gold", "#FFD700"), new Array("GoldenRod", "#DAA520"), new Array("Gray", "#808080"), new Array("Green", "#008000"), new Array("GreenYellow", "#ADFF2F"), new Array("HoneyDew", "#F0FFF0"), new Array("HotPink", "#FF69B4"), new Array("IndianRed ", "#CD5C5C"), new Array("Indigo ", "#4B0082"), new Array("Ivory", "#FFFFF0"), new Array("Khaki", "#F0E68C"), new Array("Lavender", "#E6E6FA"), new Array("LavenderBlush", "#FFF0F5"), new Array("LawnGreen", "#7CFC00"), new Array("LemonChiffon", "#FFFACD"), new Array("LightBlue", "#ADD8E6"), new Array("LightCoral", "#F08080"), new Array("LightCyan", "#E0FFFF"), new Array("LightGoldenRodYellow", "#FAFAD2"), new Array("LightGrey", "#D3D3D3"), new Array("LightGreen", "#90EE90"), new Array("LightPink", "#FFB6C1"), new Array("LightSalmon", "#FFA07A"), new Array("LightSeaGreen", "#20B2AA"), new Array("LightSkyBlue", "#87CEFA"), new Array("LightSlateBlue", "#8470FF"), new Array("LightSlateGray", "#778899"), new Array("LightSteelBlue", "#B0C4DE"), new Array("LightYellow", "#FFFFE0"), new Array("Lime", "#00FF00"), new Array("LimeGreen", "#32CD32"), new Array("Linen", "#FAF0E6"), new Array("Magenta", "#FF00FF"), new Array("Maroon", "#800000"), new Array("MediumAquaMarine", "#66CDAA"), new Array("MediumBlue", "#0000CD"), new Array("MediumOrchid", "#BA55D3"), new Array("MediumPurple", "#9370D8"), new Array("MediumSeaGreen", "#3CB371"), new Array("MediumSlateBlue", "#7B68EE"), new Array("MediumSpringGreen", "#00FA9A"), new Array("MediumTurquoise", "#48D1CC"), new Array("MediumVioletRed", "#C71585"), new Array("MidnightBlue", "#191970"), new Array("MintCream", "#F5FFFA"), new Array("MistyRose", "#FFE4E1"), new Array("Moccasin", "#FFE4B5"), new Array("NavajoWhite", "#FFDEAD"), new Array("Navy", "#000080"), new Array("OldLace", "#FDF5E6"), new Array("Olive", "#808000"), new Array("OliveDrab", "#6B8E23"), new Array("Orange", "#FFA500"), new Array("OrangeRed", "#FF4500"), new Array("Orchid", "#DA70D6"), new Array("PaleGoldenRod", "#EEE8AA"), new Array("PaleGreen", "#98FB98"), new Array("PaleTurquoise", "#AFEEEE"), new Array("PaleVioletRed", "#D87093"), new Array("PapayaWhip", "#FFEFD5"), new Array("PeachPuff", "#FFDAB9"), new Array("Peru", "#CD853F"), new Array("Pink", "#FFC0CB"), new Array("Plum", "#DDA0DD"), new Array("PowderBlue", "#B0E0E6"), new Array("Purple", "#800080"), new Array("Red", "#FF0000"), new Array("RosyBrown", "#BC8F8F"), new Array("RoyalBlue", "#4169E1"), new Array("SaddleBrown", "#8B4513"), new Array("Salmon", "#FA8072"), new Array("SandyBrown", "#F4A460"), new Array("SeaGreen", "#2E8B57"), new Array("SeaShell", "#FFF5EE"), new Array("Sienna", "#A0522D"), new Array("Silver", "#C0C0C0"), new Array("SkyBlue", "#87CEEB"), new Array("SlateBlue", "#6A5ACD"), new Array("SlateGray", "#708090"), new Array("Snow", "#FFFAFA"), new Array("SpringGreen", "#00FF7F"), new Array("SteelBlue", "#4682B4"), new Array("Tan", "#D2B48C"), new Array("Teal", "#008080"), new Array("Thistle", "#D8BFD8"), new Array("Tomato", "#FF6347"), new Array("Turquoise", "#40E0D0"), new Array("Violet", "#EE82EE"), new Array("VioletRed", "#D02090"), new Array("Wheat", "#F5DEB3"), new Array("White", "#FFFFFF"), new Array("WhiteSmoke", "#F5F5F5"), new Array("Yellow", "#FFFF00"), new Array("YellowGreen", "#9ACD32"));
function basic_build_fontcolor()
{
    for (var i in fontColor)
    {
        document.writeln('<option value="' + fontColor[i][1] + '" style="background: ' + fontColor[i][1] + ';">' + fontColor[i][0] + '</option>');
    }
}
