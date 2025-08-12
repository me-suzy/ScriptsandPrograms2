
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
	        
    function set_action (reference, confirm_msg, type, state) {
        var selected = document.formular.select_action.selectedIndex;
        switch(document.formular.select_action[selected].value) {
            case "add status":
                link = "index.php?command=add_status";
                if (type != null)
                    link += "&reference="+type;
                //alert (link);
                document.location.href=link;
                break;
            case "add transition":
                link = "index.php?command=add_transition";
                if (type != null)
                    link += "&reference="+type;
                link += "&state="+state;
                //alert (link);
                document.location.href=link;
                break;
            case "":
                break;
            default:
                alert (document.formular.select_action[selected].value);
                break;
        }   
    }    

    function setStartpoint (reference, state) {
        //alert (reference); 
        //alert (parent);
        link = "../../modules/workflow/index.php?command=set_startpoint&reference="+reference+"&state="+state;
        //alert (link);
        parent.executeframe.location.href=link;   
    }
    
    function setEndpoint (reference, state) {
        //alert (reference); 
        ident = state;
        if (ident < 0) {
            ident = -state;
            ident = "_" + ident.toString();   
        }    
        //alert (ident);
        eval ("var element = document.formular.endpoint_" + ident);
        selected = 0;
        if (element.checked) 
            selected = 1;
        link = "../../modules/workflow/index.php?command=set_endpoint&reference="+reference+"&state="+state;
        link = link + "&selected="+selected
        //alert (link);
        parent.executeframe.location.href=link;   
    }    