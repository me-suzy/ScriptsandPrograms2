
    function confirm_deleting(name, id) {
	    //text         = "<?=translate ('confirm_delete_entry')?>";
		confirmation = confirm (translate_confirm_delete_entry.replace (/%s/g,name));
        if(confirmation != false) {
            var link = "index.php?command=delete_entry&entry_id=" + id;
            window.location.href = link;
        }
	}

	function OpenElement (obj) {
	    var link = "index.php?command=show_mail&mail_id=" + obj.id;
	    parent.mail.window.location.href = link;       
	}    
	        
    function set_action (reference, confirm_msg, parent) {
        var selected = document.formular.select_action.selectedIndex;
        switch(document.formular.select_action[selected].value) {
            case "add account":
                link = "index.php?command=add_account_view";
                document.location.href=link;
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
            case "move2trash":
		        confirmation = confirm (confirm_msg);
                if(confirmation != false) {
                    document.formular.command.value='move2trash';
                    document.formular.submit();
                }
                break;
            case "delete_from_trash":
		        confirmation = confirm (confirm_msg);
                if(confirmation != false) {
                    document.formular.command.value='delete_from_trash';
                    document.formular.submit();
                }
                break;
            default:
                alert (document.formular.select_action[selected].value);
                break;
        }   
    }    

    function set_mail_action (email, name) {
        var selected = document.formular.mail_action.selectedIndex;
        switch(document.formular.mail_action[selected].value) {
            case "add_as_contact":
                link  = "../../modules/contacts/index.php?command=add_contact_view&email="+email;
				link += "&lastname="+name; 
				//alert (name);
                parent.document.location.href=link;
                break;
			
            default:
                alert (document.formular.mail_action[selected].value);
                break;
        }   
    }    

	function addEntry (obj) {
		alert (obj);
	}
	
    function clone_me (show_text) {
        document.formular.command.value="add_entry";
        alert(show_text);
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