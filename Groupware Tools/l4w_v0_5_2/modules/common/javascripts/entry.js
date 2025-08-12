function changeTab (nr, length) {
    var i     = 1;

    while (i <= length) {
		eval ("var link  = 'tabnav"+i+"'"); 		

        if (document.all) {
            eval ("var elem  = document.all.tab"+i);
            eval ("var style = document.all.tab"+i+".style"); 
            if (i == nr) {
                style.setAttribute ('visibility', 'visible', false);
                document.getElementById(link).className='selected';
            }
            else {
                style.setAttribute ('visibility', 'hidden', false);                
                document.getElementById(link).className='unselected';
            }
        }
        else {
            eval ("elem = 'tab"+i+"'");
            //if (null == document.getElementById(elem)) alert ("hier");
            var style     = document.getElementById(elem).style;
            if (i == nr) {
                style.visibility='visible';
                document.getElementById(link).className='selected';
            }
            else {
                style.visibility='hidden';
                document.getElementById(link).className='unselected';
            }
        }
        i++;    
    }   
}    

function add_reference (id) {
    var selected  = document.formular.reference.selectedIndex;
    var thisvalue = document.formular.reference[selected].value;
    switch(thisvalue) {
        case "note":
            var link = "../../modules/notes/index.php?command=add_ref_view&from_object_id="+id;
            window.open (link, '_blank', 'width=900,height=600,scrollbars');
            break;
        case "ticket":
            var link = "../../modules/tickets/index.php?command=add_ref_view&from_object_id="+id;
            window.open (link, '_blank', 'width=900,height=600,scrollbars');
            break;
        case "todo":
            var link = "../../modules/todos/index.php?command=add_ref_view&from_object_id="+id;
            window.open (link, '_blank', 'width=900,height=600,scrollbars');
            break;
        default:
            alert ('reference not defined');
            break;
    }           
}    

function delete_attachment (type, id) {
    var del_att  = document.formular.delete_attachments.value;
    var elem     = 'att_'+type+'_'+id;
    if (document.all) {            
        eval ("var style = document.all."+elem+".style"); 
    }
    else {
        var style     = document.getElementById(elem).style;
    }   
    var s = type+'_'+id+'|';
    var i = del_att.indexOf (s); 
    
    //alert (i);
    //alert (document.formular.delete_attachments.value);
    
    if (i != -1) { // line-through already set
        dummy = del_att.substring(0,i) + del_att.substring(i+s.length, del_att.length);
        //alert ("dummy: "+dummy);
        document.formular.delete_attachments.value = dummy;
        style.textDecoration = "none"; 
    }
    else { // delete entry
        del_att   += type+'_'+id+'|';
        document.formular.delete_attachments.value = del_att;
        style.textDecoration = "line-through"; 
    }   
}  

	function add_external_link (type, id) {
	    var link = "../../modules/common/addexternallink.php?type="+type+"&id="+id;
	    window.open (link, '_blank', 'width=700,height=300,scrollbars');
	}
    function set_filter (selection, refresh_command) {
        eval ("var element = document.forms[0]."+selection);
        link = "index.php?command="+refresh_command+"&"+selection+"="+element.value;
        //alert (link);
        document.location.href=link;
    }
    
    function clear_filters (refresh_command) {
        link = "index.php?command="+refresh_command+"&my_group=all&my_owner=all&my_state=";
        document.location.href=link;
    }    

	function toggleDisplay (element) {
	    elem = eval(document.getElementById(element));
	    var display = elem.style.display;
	    if (display == "none")
		    elem.style.display = "inline";
	    else
		    elem.style.display = "none";
	}

	function toggleSize (small, big) {
		editor = document.getElementById("mce_editor_0");
	    if (editor) {
	    	if (editor.style.height == small)
				editor.style.height = big;
			else
				editor.style.height = small;
	    } 
	}
	
    function languagemanager (text, mode) {
        window.open ("../../modules/translations/index.php?command=edit_text&mykey="+text+"&mode="+mode, "lm", "width=500,height=300");
    }

    function OpenElement (command, identifier, obj) {
        var link = "index.php?command="+command+"&"+identifier+"=" + obj.id;
        //alert (link);
        window.location.href = link;       
    }    

	/*function confirm_deleting(question, name, id) {
	    //text         = "<?=translate ('confirm_delete_contact', null, true)?>";
		confirmation = confirm (question.replace (/%s/g,name));
        if(confirmation != false) {
            var link = "index.php?command=delete_entry&entry_id=" + id;
            //link = link + "&return_to=" + return_to;
            window.location.href = link;
        }
	}*/
	
	function delete_reference (from_type, from_id, to_type, to_id) {
        var link = "index.php?command=del_ref&from_object_type="+from_type+"&";
        link    += "from_object_id="+from_id+"&";
        link    += "to_object_type="+to_type+"&";
        link    += "to_object_id="+to_id;
        //alert (link);
        
        var elem = parent.l4w_main.document.getElementsByName('ref_' + to_type + '_' + to_id)[0];
        //var st   = elem.getAttributeNode ("style");
        
        var hiddenstyle = document.createAttribute("style");
        hiddenstyle.nodeValue = "visibility:hidden";
        elem.setAttributeNode (hiddenstyle);
        
        parent.executeframe.document.location.href=link;        
    }    
    
    function delete_from_collection (from_type, from_id, to_type, to_id) {
        delete_reference (from_type, from_id, to_type, to_id);
    }  

	function show_calendar (element, timestamp) {
		var link = "../../inc/calendar.php?timestamp="+timestamp+"&element="+element;
		//alert (link);
		window.open (link, "_blank", "width=200,height=200,resizable=yes");
    }
    
    function unserialize_model (id) {
		ok = confirm ("changes might be lost... still go on?");
		if (ok == true) {
		    something_changed = false;
    		var link = "index.php?command=unserialize&model_id="+id;
	        document.location.href = link;
		    return false;
		} 	     	
    }    

    function get_template (id) {
		ok = confirm ("changes might be lost... still go on?");
		if (ok == true) {
		    something_changed = false;
    		var link = "index.php?command=unserialize&model_id="+id;
	        document.location.href = link;
		    return false;
		} 	     	
    }    
