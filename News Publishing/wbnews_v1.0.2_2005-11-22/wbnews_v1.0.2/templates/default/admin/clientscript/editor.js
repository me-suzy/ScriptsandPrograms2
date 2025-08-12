/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created:  17th May 2005                          #||
||#     Filename: editor.js                              #||
||#                                                      #||
||########################################################||
/*========================================================*/

var controls = new Array("bold","italic","underline","link","left","center","right","orderedlist","unorderedlist", "fontfamily", "fontfamily_arrow", "fontcolor", "fontsize", "php", "emoticon");
var tagStack = new Array(); //stores the selected buttons e.g. b|i|u
var iconSelectedClass = "wysiwyg_button";
var myEditor = "";

function editor_init()
{
    /* add the mouseover mouseout */
    for (var i in controls)
    {
        e = getObject("cmd_" + controls[i]);
        if (e != null)
        {
            if (!is_ie && !is_op)
            {
                e.addEventListener("mouseover", editor_buttonRollOver, true);
                e.addEventListener("mouseout", editor_buttonRollOver, true);
            }
            else
            {
                e.onmouseover = editor_buttonRollOver;
                e.onmouseout = editor_buttonRollOver;
            }
        }
    }
    
    // lets make sure the WYSIWYG Editor container is displayed
    getObject('wysiwyg-container').style.display = 'block';
    
    /* Basic Editor Available */
    basic_init();
    myEditor = "basic";
    
    /* check what editor we can use */
    /*
        No Rich Text Editor Support in 1.0
        WYSIWYG Editor Removed from 1.0
        
        <input type="hidden" name="message" value="this is a test [b]bold text[/b] another test" />
        <iframe id="wysiwyg_editor" name="wysiwyg_editor"></iframe>
        
    if (is_op)
    {
        // must load basic editor
    }
    else
    {
        wysiwyg_init();
    }
    */
    
    if (myEditor !== "basic")
    {
        /*****************************************************************************/
        //############################## LOAD PALETTES ##############################//
        /*****************************************************************************/
        fontfamily_init();
        fontsize_init();
        fontcolor_init();
        /*****************************************************************************/
    }
    
}

/* controls rollover */
function editor_buttonRollOver(e)
{
    e = (window.event ? window.event : e);
	var id = (!is_ie ? e.target.id : e.srcElement.id);
	
	var elem = getObject(id);
		
	if (e.type == "mouseover" && elem.className == "")
	{
		if (id === "cmd_fontfamily")
		{
			elem.className = iconSelectedClass;
			getObject(id + "_arrow").className = iconSelectedClass;
		}
        else if (id.indexOf("arrow") !== -1)
        {
            elem.className = iconSelectedClass;
            getObject(id.substr(0, id.indexOf("arrow") - 1)).className = iconSelectedClass;
            
        }
		else
			elem.className = "wysiwyg_button";
	}
	else
	{
		elem.className = (inArray(id, tagStack) == false ? "" : iconSelectedClass);
		if (id === "cmd_fontfamily")
			getObject(id+"_arrow").className = "";
        else if (id.indexOf("arrow") !== -1)
        {
            elem.className = "";
            getObject(id.substr(0, id.indexOf("arrow") - 1)).className = "";
            
        }
	}
}

/* function not really required yet */
function editor_submit()
{
    return (basic_submit() === false ? false : true);
}
