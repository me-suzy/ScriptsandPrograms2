
    function set_action (reference, confirm_msg, parent) {
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
                    if (att.nodeValue.slice (0,7) == reference) {
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
                    if (att.nodeValue.slice (0,7) == reference) {
                        elem.checked = true;
                    }        
                }    
                break;
            case "new ticket":
                link = "index.php?command=add_ticket_view&parent="+parent;
                document.location.href=link;
                break;
            case "new folder":
                link = "index.php?command=add_folder_view&parent="+parent;
                document.location.href=link;
                break;
            case "add references":
                var form   = document.formular;
                var inputs = document.getElementsByTagName("input");
                var orig   = opener.document.formular;
                if (document.all) {
                	alert ("Sorry, this currently does not work with IE");
                	break;
                }
                //alert (reference);
                for (i = 0; i < inputs.length; i++) {
                    elem = inputs[i];
                    att  = elem.getAttributeNode("name");
                    if (att.nodeValue.slice (0,reference.length) == reference && elem.checked) {
                        to_ref_id = att.nodeValue.slice(reference.length);
                        position  = orig.added_references.length;
                        orig.added_references.options[position]     = null;
		        	    newEntry  = new Option ('ticket (ref. #'+to_ref_id+')','ticket_'+to_ref_id,true, true);
        		        orig.added_references.options[position]     = newEntry;
                        orig.new_references.value += 'ticket_'+to_ref_id+'|';
                    }   
                }    
                window.close();
                break;
            case "clear filter":
                link = "index.php?command=clear_filter&parent="+parent;
                document.location.href=link;
                break;
            case "move":
                link = "index.php?command=move_view&parent="+parent;
                //document.location.href=link;
                window.open (link, "folders", "location=no,menubar=no,width=330,height=330,resizable=yes");
                break;    
            case "edit datagrid":
            	link = "../../modules/datagrids/index.php?command=edit_datagrid";
    			link = link + "&datagrid=tickets";
    			//alert (link);
                document.location.href=link;
            	break;
            default:
                alert (document.formular.select_action[selected].value);
                break;
        }   
    }    