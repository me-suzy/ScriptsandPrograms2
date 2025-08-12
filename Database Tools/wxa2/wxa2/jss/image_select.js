var L_form;
	var newwin = 0;
	var IndexNom = 0;
	var IndexId = 0;
	var action_image="";
	
	function imgSelect(nom_image,imgWidth,imgHeight){
	   // alert('hello');
	   if (action_image=="")
   		{
	   eval("L_form." + lg + ".value =  nom_image");
    	//self.document.ajoutmodif.elements[IndexId].value = image_id;
		}
	if (action_image=="corpsHTML")
		{
		insertImg(nom_image,imgWidth,imgHeight,"align=left");
		}
	newwin.close();
	}

	function launchwin(frm, image_field, libdir)
		{
    	L_form = frm;
	    var nom = '';
		action_image="";
	    /*for (lg=0 ; lg < L_form.elements.length ; lg++)
			{
    	    nom = L_form.elements[lg].name;
	        if (nom == nom_image) IndexNom = lg;
    	   // if (nom == image_id) IndexId = lg;
	    	}*/
		lg=image_field;
		newwin =  window.open(libdir + "modules/thirdparty/explorer/img_select.php","Selection","top=0, left=0,height=370,width=500,scrollbars=yes,status=no");
		newwin.focus();
		}


