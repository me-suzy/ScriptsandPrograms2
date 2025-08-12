		function del_birthday() {
			document.formular.geburtstag.value="";
		}

	    function open_homepage (myURL) {
			window.open (myURL, '_blank', '');
		}

		function show_further_emails() {
			var anz = document.formular.further_emails.length;
			NeuerEintrag = new Option("");
			document.formular.further_emails.options[anz] = NeuerEintrag;
			eval ("var link = 'further_emails.php';");
			window.open(link,"Emails","menubar=no, scrollbar=no, locationbar=no, width=320,height=30,screenX=520,screenY=450");
		}
		
        function gather_further_emails() {
			var result = "";
			for (i=0; i < document.formular.further_emails.length; i++) {
				if (document.formular.further_emails[i].text != "")
					result = result + "|" + document.formular.further_emails[i].text;
			}
			result = result + "|";
			if (document.formular.further_emails.selectedIndex >= 0)
				document.formular.further_emails[document.formular.further_emails.selectedIndex].value=result;
		}

		function del_further_email () {
			var nr = document.formular.further_emails.selectedIndex;
			if (nr >= 0)
				document.formular.further_emails.options[nr] = null;
		}
		
		function add_note (object_type, object_id) {
		    var link = "../../modules/notes/index.php?command=add_note_att_view";
		    link    += "&ref_object_type="+object_type;
		    link    += "&ref_object_id="+object_id;
		    link    += "&ref_type=2";
		    F1 = window.open(link,"addnote","resizable=yes,width=610,height=400,left=50,top=50");
		    F1.focus();
		}    
		
		function edit_entry (link, object_type) {
		    F1 = window.open(link,object_type,"resizable=yes,width=610,height=400,left=50,top=50");
		    F1.focus();
		} 
		
        /*function toggleDisplay (element) {
		    elem = eval(document.getElementById(element));
		    var display = elem.style.display;
		    if (display == "none")
			    elem.style.display = "inline";
		    else
			    elem.style.display = "none";
	    }*/

        
        
        /*function changeTab (nr, length) {
	        var i     = 1;
	
	        while (i <= length) {
				eval ("var link  = 'tabnav"+i+"'"); 
	            if (document.all) {
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
	                eval ("element = 'tab"+i+"'");
	                //eval ("link    = 'tabnav"+i+"'");
	                var style     = document.getElementById(element).style;
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
    	} */  
    	
        function run_apply (goto_tab) {
            something_changed=false;    
            //alert (goto_tab);
            document.formular.goto_tab.value=goto_tab;
        }   
