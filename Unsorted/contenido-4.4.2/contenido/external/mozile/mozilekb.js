/* ***** BEGIN LICENSE BLOCK *****
 * Licensed under Version: MPL 1.1/GPL 2.0/LGPL 2.1
 * Full Terms at http://mozile.mozdev.org/license.html
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is Playsophy code.
 *
 * The Initial Developer of the Original Code is Playsophy
 * Portions created by the Initial Developer are Copyright (C) 2002-2003
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s):
 *
 * ***** END LICENSE BLOCK ***** */

/* 
 * mozilekb V0.46
 * 
 * Keyboard handling for Mozile. You can replace this if you want different keyboard
 * behaviors.
 *
 * POST04:
 * - reimplement ip use: reuse ip whereever possible. Big performance gain.
 * - support keyboard shortcuts for navigation and style settings
 * - consider xbl equivalent
 * - make sure event handlers aren't loaded twice: if user includes script twice, should
 * not register handlers twice (spotted by Chris McCormick)
 * - see if can move to using DOM events and away from Window.getSelection() if possible 
 * (effects how generic it can be!)
 * - selection model: word, line etc. Write custom handlers of clicks and use new Range
 * expansion methods
 */


/*
 * Handle key presses
 *
 * POST04:
 * - IP isn't recreated everytime with its own text pointer; text pointer isn't (in Range
 * create) set up for every key press.
 * - need up and down arrows to be implemented here too (via eDOM!): that way, no problem with
 * not deselecting toolbar at right time
 * - add in support for typical editing shortcuts based on use of ctrl key or tabs; can synthesize events to
 * force selection. http://www.mozilla.org/docs/end-user/moz_shortcuts.html and ctrl-v seems to effect caret mode?
 * - arrow keys: mode concept where if in text mode then only traverse text AND do not traverse objects. If
 * mixed mode, then select objects too.
 * - each editable area gets a CP? If valid (add method that checks TextNode validity?)
 */
document.addEventListener("keypress", keyPressHandler, true);


function keyPressHandler(event)

{	
	var handled = false;

	if(event.ctrlKey)
		handled = ctrlKeyPressHandler(event);
	else
		handled = nonctrlKeyPressHandler(event);


	// handled event so do let things go any further.
	if(handled)
	{

		//cancel event: TODO02: why all three?

		event.stopPropagation();

		event.returnValue = false;

  		event.preventDefault();

  		return false;
	}

}

function ctrlKeyPressHandler(event, cssr)
{
	if(!event.charCode)
		return;

	if(String.fromCharCode(event.charCode).toLowerCase() == "v")
	{
		window.getSelection().paste();
		return true;
	}
	else if(String.fromCharCode(event.charCode).toLowerCase() == "x")
	{
		window.getSelection().cut();
		return true;
	}
	else if(String.fromCharCode(event.charCode).toLowerCase() == "c")
	{
		window.getSelection().copy();
		return true;
	}
	else if(String.fromCharCode(event.charCode).toLowerCase() == "s")
	{
		mozileSave();
		return true;
	}
	return false;
}

/**
 * POST04: 
 * - carefully move selectEditableRange in here
 * - distinguish editable range of deleteOne at start of line and deleteOne
 * on same line [need to stop merge but allow character deletion]. Perhaps
 * need to change eDOM granularity.
 */
function nonctrlKeyPressHandler(event)
{
	var sel = window.getSelection();

	// BACKSPACE AND DELETE (DOM_VK_BACK_SPACE, DOM_VK_DELETE)

	if((event.keyCode == 8) || (event.keyCode == 46))

	{
		var cssr = sel.getEditableRange();
		if(!cssr)
		{
			return;
		}

		// first let's test collapsed
		if(cssr.collapsed)
		{
			var ip = documentCreateInsertionPoint(cssr.top, cssr.startContainer, cssr.startOffset);
			ip.deleteOne();	
			cssr.selectInsertionPoint(ip);
		}
		else
		{
			cssr.deleteTextTree();
		}

		sel.removeAllRanges();

		sel.addRange(cssr);

		return true;

	}

	// PREV (event.DOM_VK_LEFT) Required as Moz left/right doesn't handle white space properly
	if(event.keyCode == 37)
	{
		var cssr = sel.getEditableRange();
		if(!cssr)
		{
			return;
		}

		if(!cssr.collapsed)
			cssr.collapse(true);

		var ip = documentCreateInsertionPoint(cssr.top, cssr.startContainer, cssr.startOffset);
		ip.backOne();
		cssr.selectInsertionPoint(ip);
		sel.removeAllRanges();
		var rng = cssr.cloneRange();
		sel.addRange(rng);
		return true;
	}

	// NEXT (event.DOM_VK_RIGHT) Required as Moz left/right doesn't handle white space properly
	if(event.keyCode == 39)
	{	
		var cssr = sel.getEditableRange();
		if(!cssr)
		{
			return;
		}

		if(!cssr.collapsed)
			cssr.collapse(false);

		var caretTop = cssr.top;

		var ip = documentCreateInsertionPoint(caretTop, cssr.startContainer, cssr.startOffset);
	
		ip.forwardOne();
		
		cssr.setEnd(ip.ipNode, ip.ipOffset);
		cssr.collapse(false);

		sel.removeAllRanges();
		var rng = cssr.cloneRange();
		sel.addRange(rng);
		return true;
	}


	// RETURN OR ENTER (event.DOM_VK_ENTER DOM_VK_RETURN)

	if(event.keyCode == 13)

	{
		var cssr = sel.getEditableRange();
		if(!cssr)
		{
			return;
		}


		if(!cssr.collapsed)

		{ // POST04: delete text when write over it!	

			cssr.collapse(true);

		}
		sel.removeAllRanges();
		ip = documentCreateInsertionPoint(cssr.top, cssr.startContainer, cssr.startOffset);
		// POST04: support concept of not splitting line if mozUserModify indicates writeText ...
		ip.splitLine(); // add logic to split off say a "P" after a Heading element: if at end line
		cssr.selectInsertionPoint(ip);
		sel.removeAllRanges();

		sel.addRange(cssr);
		
		return true;
	}

	// POST04: for non-pre, may change to mean switch to next editable area
	if(event.keyCode == event.DOM_VK_TAB)
	{
		var cssr = sel.getEditableRange();
		if(!cssr)
		{
			return;
		}

		// if there's a selection then delete it

		if(!cssr.collapsed)
		{
			cssr.deleteTextTree();

		}

		// seems to mess up the current position!
		var ip = documentCreateInsertionPoint(cssr.top, cssr.startContainer, cssr.startOffset);
		if(ip.cssWhitespace == "pre")
			ip.insertCharacter(CHARCODE_TAB);
		else
			ip.insertCharacter(CHARCODE_SPACE); // POST05: may change to insert a set of spaces
	

		// put cursor after inserted text: TODO - move on CSSR instead
		sel.collapse(ip.ipNode, ip.ipOffset);
		return true;
	}

	// ALPHANUM
	if(event.charCode)

	{
		var cssr = sel.getEditableRange();
		if(!cssr)
		{
			return;
		}

		// if there's a selection then delete it

		if(!cssr.collapsed)
		{
			cssr.deleteTextTree();

		}

		// seems to mess up the current position!
		var ip = documentCreateInsertionPoint(cssr.top, cssr.startContainer, cssr.startOffset);

		ip.insertCharacter(event.charCode);		



		// put cursor after inserted text: TODO - move on CSSR instead
		sel.collapse(ip.ipNode, ip.ipOffset);
		return true;

	}

	return false;
}