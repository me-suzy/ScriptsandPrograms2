// JavaScript Document

//***************************************************************************************
// + a call from another file could be like this: onClick=javascript:tag_construct('bold','text');
// + possible values for 1. variable: bold, italic, underline, link, image
// + possible values for 2. variable: text, extended
//***************************************************************************************
//
// Version 1.0 - 03. June 2005:
// ============================
// + Introduction of the new bbcode insert system with prompts
//
// Version 1.1 - 05.June 2005:
// ===========================
// + New function insert_text(myField, myValue) for insering
//   the bbcode at the cursor position in textarea
// + New variable field_descriptor in function (tag_name,field_descriptor)
//   so tag_construct2(name) isn't needed anymore
//
// Version 1.2 - 08.June 2005:
// ===========================
// + If a mac based browser is used the selection of text will not recognized
// + Remove the prompt which wants the position of the text insering



//Variables for controlling opening and closing tags (function tag)

var tag = new Array();
//bold tag
tag["bold"] = new Array();
tag["bold"]["open"] = "<b>";
tag["bold"]["close"] = "</b>";
tag["bold"]["text"] = "Text:";
//italic tag
tag["italic"] = new Array();
tag["italic"]["open"] = "<i>";
tag["italic"]["close"] = "</i>";
tag["italic"]["text"] = "Text:";
//underline tag
tag["underline"] = new Array();
tag["underline"]["open"] = "<u>";
tag["underline"]["close"] = "</u>";
tag["underline"]["text"] = "Text:";
//url tag
tag["link"] = new Array();
tag["link"]["open"] = "<a href=";
tag["link"]["close"] = "</a>";
tag["link"]["text"] = "Text:,Link:";
//image tag
tag["image"] = new Array();
tag["image"]["open"] = "<img src=";
tag["image"]["close"] = ">";
tag["image"]["text"] = "Link:";

// Function:
// =========
// + Creating non-font tags in textareas
//
// insert values:
// ==============
// + tag_name: Name of the tag, eg. bold, italic, underline, link, image.
// + field_descriptor: Name of the textarea for the inserting of the text.
//
// return values:
// ==============
// + -

function tag_construct(tag_name, field_descriptor) {
     var selected_text = '';
     var usertext = '';
     var usertext2 = '';
     var text = window.document.getElementsByName(field_descriptor)[0].value;

     //Search for selected text in the textarea for bbcode
     selected_text = get_selected_text(window.document.getElementsByName(field_descriptor)[0]);
     //Search if the tags are already in the selected text
     if ((search_for_tag(tag_name,selected_text) == 1) && (selected_text != '')) {
       window.alert("Tag is already in selected text!");
       return;
     }

     if (tag[tag_name]["text"].indexOf(",") == -1) {
         usertext = window.prompt(tag[tag_name]["text"],selected_text);
         if(usertext != null)
           insert_text(window.document.getElementsByName(field_descriptor)[0],tag[tag_name]["open"] + usertext + tag[tag_name]["close"]);
     } else {
       usertext = window.prompt(tag[tag_name]["text"].substring(0,tag[tag_name]["text"].indexOf(",")),selected_text);
       if(usertext != null) {
         usertext2 = window.prompt(tag[tag_name]["text"].substring(tag[tag_name]["text"].indexOf(",")+1,tag[tag_name]["text"].length),"");
         if(usertext2 != null)
           insert_text(window.document.getElementsByName(field_descriptor)[0],tag[tag_name]["open"] + usertext2 + ">" + usertext + tag[tag_name]["close"]);
       }
     }
}



// Function:
// =========
// + Insert the text at the cursor position. If no cursor position can excluded
//   a prompt to insert the position where the text should be insert will be shown.
// + Code from: http://www.alexking.org/blog/2003/06/02/inserting-at-the-cursor-using-javascript/
//              http://aktuell.de.selfhtml.org/tippstricks/javascript/bbcode/
//
// insert values:
// ==============
// + myField: Name of the textarea for the inserting of the text.
// + myValue: Text which should be insert into the textarea.
//
// return values:
// ==============
// + -

function insert_text(myField, myValue) {
     //IE support
     if (document.selection) {
      myField.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
     }
     //MOZILLA/NETSCAPE support
     else if (myField.selectionStart || myField.selectionStart == '0') {
      var startPos = myField.selectionStart;
      var endPos = myField.selectionEnd;

      myField.value = myField.value.substring(0, startPos)
      + myValue
      + myField.value.substring(endPos, myField.value.length);
     }
     //NON IE/MOZILLA/NETSCAPE browser support
     else {
      myField.value += myValue;
     }
}



// Function:
// =========
// + Get the selected text from the textarea of the site if text was selected.
//
// insert values:
// ==============
// + myField: Name of the textarea where the text should be shown.
//
// return values:
// ==============
// + selected_text: The selected text which was found.
//                  If nothing was selected "" is returned.

function get_selected_text(myField) {
    var selected_text = '';

    if(navigator.platform.toLowerCase() != "macppc") {
     if (window.getSelection) {
      var startPos = myField.selectionStart;
      var endPos = myField.selectionEnd;
      selected_text = myField.value.substring(startPos,endPos);
     } else if (document.getSelection) {
      var startPos = myField.selectionStart;
      var endPos = myField.selectionEnd;
      selected_text = myField.value.substring(startPos,endPos);
     } else if (document.selection) {
      selected_text = document.selection.createRange().text;
     }
    }

    return selected_text;
}



// Function:
// =========
// + Check if the tag the user choose is already in the selected text.
//
// insert values:
// ==============
// + tag_name: Name of the tag which should be searched.
// + text_for_search: Text where should be looked for the tag.
//
// return values:
// ==============
// + found: If the value is "0" the tag could not be found in the text.
//          If the value is "1" the tag was found in the text.

function search_for_tag(tag_name,text_for_search) {

    if (text_for_search.indexOf(tag[tag_name]["open"]) == -1) {
     if (text_for_search.indexOf(tag[tag_name]["close"]) == -1) {
      return 0;
     }
    }

    return 1;
}



// Function:
// =========
// + helpline
//
// insert values:
// ==============
// + tag_name: Name of the tag which should be searched.
//
// return values:
// ==============
// + -

function helpline(tag_name){
  return;
}