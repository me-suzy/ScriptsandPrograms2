
    function confirm_deleting(name, id) {
	    //text         = "<?=translate ('confirm_delete_entry')?>";
		confirmation = confirm (translate_confirm_delete_entry.replace (/%s/g,name));
        if(confirmation != false) {
            var link = "index.php?command=delete_entry&entry_id=" + id;
            window.location.href = link;
        }
	}

	function OpenElement (obj) {
	    var link = "index.php?command=edit_entry&entry_id=" + obj.id;
	    window.location.href = link;       
	}    
	
    function set_filter (selection) {
        eval ("var element = document.forms[0]."+selection);
        link = "index.php?command=show_entries&"+selection+"="+element.value;
        //alert (link);
        document.location.href=link;
    }
    
    function clear_filters () {
        link = "index.php?command=show_entries&my_group=all&my_owner=all";
        document.location.href=link;
    }    

    function set_action (reference, confirm_msg) {
        var selected = document.formular.select_action.selectedIndex;
        switch(document.formular.select_action[selected].value) {
            case "delete selected":
		        confirmation = confirm (confirm_msg);
                if(confirmation != false) {
                    document.formular.command.value='delete_selected';
                    document.formular.submit();
                }
                break;
            case "invert selection":
                var form   = document.formular;
                var inputs = document.getElementsByTagName("input");
                for (i = 0; i < inputs.length; i++) {
                    elem = inputs[i];
                    att  = elem.getAttributeNode("name");
                    if (att.nodeValue.slice (0,5) == reference) {
                        elem.checked = !(elem.checked);
                    }        
                }    
                break;
            case "select all":
                var form   = document.formular;
                var inputs = document.getElementsByTagName("input");
                for (i = 0; i < inputs.length; i++) {
                    elem = inputs[i];
                    att  = elem.getAttributeNode("name");
                    if (att.nodeValue.slice (0,5) == reference) {
                        elem.checked = true;
                    }        
                }    
                break;
            case "new note":
                link = "index.php?command=add_entry_view";
                document.location.href=link;
                break;
            case "add references":
                var form   = document.formular;
                var inputs = document.getElementsByTagName("input");
                var orig   = opener.document.formular;
                for (i = 0; i < inputs.length; i++) {
                    elem = inputs[i];
                    att  = elem.getAttributeNode("name");
                    if (att.nodeValue.slice (0,reference.length) == reference && elem.checked) {
                        to_ref_id = att.nodeValue.slice(reference.length);
                        position  = orig.added_references.length;
                        orig.added_references.options[position]     = null;
		        	    newEntry  = new Option ('Note (ref. #'+to_ref_id+')','note_'+to_ref_id,true, true);
        		        orig.added_references.options[position]     = newEntry;
                        orig.new_references.value += 'note_'+to_ref_id+'|';
                    }   
                }    
                window.close();
                break;
            default:
                alert (document.formular.select_action[selected].value);
                break;
        }   
    }    

    function clone_me (show_text) {
        document.formular.command.value="add_entry";
        alert(show_text);
    }
    
    function add_reference (id) {
        var selected  = document.formular.reference.selectedIndex;
        var thisvalue = document.formular.reference[selected].value;
        switch(thisvalue) {
            case "note":
                var link = "index.php?command=add_ref_view&from_object_id="+id;
                window.open (link, '_blank');
                break;
            default:
                break;
        }           
    }    
    
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
    
    function run_apply (goto_tab) {
        something_changed=false;    
        document.formular.goto_tab.value=goto_tab;
    }    