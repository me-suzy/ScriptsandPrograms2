	function add_tags(objid, action)
	{
		objTextBox = document.getElementById(objid);
		objTextBox.focus();
		get_sel();
		switch(action)
		{
			case 'bold':
			{
				txt = prompt("Please type the text you want to appear in bold:", "");
				if(txt==null)
					code=null;
				else
					code = "<b>" + txt + "</b>";

				break;
			}
			case 'italic':
			{
				txt = prompt("Please type the text you want to appear in italic:", "");
				if(txt==null)
					code=null;
				else
					code = "<i>" + txt + "</i>";

				break;
			}
			case 'underline':
			{
				txt = prompt("Please type the text you want to appear in bold:", "");
				if(txt==null)
					code=null;
				else
					code = "<u>" + txt + "</u>";

				break;
			}
			case 'align_left':
			{
				txt = prompt("Please type the text you want to appear aligned to left:", "");
				if(txt==null)
					code=null;
				else
					code = "<div align=\"left\">" + txt + "</div>";

				break;
			}
			case 'align_center':
			{
				txt = prompt("Please type the text you want to appear aligned to center:", "");
				if(txt==null)
					code=null;
				else
					code = "<div align=\"center\">" + txt + "</div>";

				break;
			}
			case 'align_right':
			{
				txt = prompt("Please type the text you want to appear aligned to right:", "");
				if(txt==null)
					code=null;
				else
					code = "<div align=\"right\">" + txt + "</div>";

				break;
			}
			case 'link':
			{
				link_url = prompt("Please type the URL the link will point to:", "http://");
				if(link_url!=null)
				{
					link_txt = prompt("Please type the link TEXT that the users will see\n(optional, leave blank for URL only):", "");
					
					if(link_txt==null) 		// Cancel creating link when user clicks cancel
						break;
					else if(link_txt=='')	// If blank use link url as text
						link_txt = link_url;
						
					code = '<a href=\"' + link_url + '\">' + link_txt + '</a>';
				}
				else
					code = null;

				
				break;
			}
			case 'image':
			{
				txt = prompt("Please type the URL of the image:", "");
				
				if(txt==null)
					code=null;
				else
					code = "<img src=\"" + txt + "\">";
				
				break;
			}
			case 'email':
			{
				txt = prompt("Please type the e-mail address the link will point to:", "");
				
				if(txt==null)
					code=null;
				else
					code = "<a href=\"mailto:" + txt + "\">" + txt + "</a>";
				
				break;
			}
			case 'ordered_list':
			{
				list_text = '';
				while(1)
				{
					item_text = prompt("Please type the name of the item.\nThen click OK to add one more item and Cancel to finish the list.", "");
					
					if(item_text!=null && item_text!='')
						list_text = list_text + "<li>" + item_text + "</li>\n";
					else
						break;
				}
									
				if(list_text!='')
					code = "\n<ol>\n" + list_text + "\n</ol>\n";
				else
					code = null;
				
				break;
			}
			case 'unordered_list':
			{
				list_text = '';
				while(1)
				{
					item_text = prompt("Please type the name of the item.\nThen click OK to add one more item and Cancel to finish the list.", "");
					
					if(item_text!=null && item_text!='')
						list_text = list_text + "<li>" + item_text + "</li>\n";
					else
						break;
				}
									
				if(list_text!='')
					code = "\n<ul>\n" + list_text + "\n</ul>\n";
				else
					code = null;
				
				break;
			}
			case 'font_size':
			{
				var objFontSize = document.getElementById('font_size');

				if(objFontSize.value > 0)
				{
					txt = prompt("Please type the text you want to change the size of:", "");
				
					if(txt == null)
						code = null;
					else
						code = "<font size=\"" + objFontSize.value + "\">" + txt + "</font>";
				}
				
				objFontSize.value = 0;
				break;
			}
			case 'font_color':
			{
				var objFontColor = document.getElementById('font_color');
				
				if(objFontColor.value != 0)
				{
					txt = prompt("Please type the text you want to change the color of:", "");
					
					if(txt == null)
						code = null;
					else
						code = "<font color=\"" + objFontColor.value + "\">" + txt + "</font>";
				}
				else
					code = null;
					
				objFontColor.value = 0;
				break;
			}
		}
		// If not cancelled, set text
		if(code!=null)
			set_sel(objid, code);
	}
	
	function set_sel(objid, sel_text)
	{
		var objTextBox = document.getElementById(objid);
		
		// If IE/Opera
		if(document.selection)
		{
			sel.text = ' ' + sel_text + ' ';
		}
		else	// Mozilla/Firefox
		{
			var sel_left	= objTextBox.value.substring(0, objTextBox.selectionStart);
			var sel_right	= objTextBox.value.substring(objTextBox.selectionEnd, objTextBox.value.length);
	
			objTextBox.value = sel_left + ' ' + sel_text + ' ' + sel_right;
		}
		objTextBox.focus();
	}

	// For IE/Opera
	function get_sel()
	{
		if(document.selection)
			sel = document.selection.createRange();
	}