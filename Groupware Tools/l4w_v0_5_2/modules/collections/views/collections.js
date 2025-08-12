
	
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

    function set_action (reference, confirm_msg, parent) {
        var selected = document.formular.select_action.selectedIndex;
        switch(document.formular.select_action[selected].value) {
            case "new collection":
                link = "index.php?command=add_category&parent="+parent;
                document.location.href=link;
                break;
            case "new folder":
                link = "index.php?command=add_folder&parent="+parent;
                document.location.href=link;
                break;
            default:
                alert (document.formular.select_action[selected].value);
                break;
        }   
    }    

    function clone_me (show_text) {
        document.formular.command.value="add_collection";
        alert(show_text);
    }
    
    function add_reference (id) {
        var selected  = document.formular.reference.selectedIndex;
        var thisvalue = document.formular.reference[selected].value;
        switch(thisvalue) {
            case "collection":
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
        
        //parent.executeframe.document.location.href=link;        
    }    