	// Startup variables
	var imageTag = false;
	var theSelection = false;

	// Check for Browser & Platform for PC & IE specific bits
	// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
	var clientPC = navigator.userAgent.toLowerCase(); // Get client info
	var clientVer = parseInt(navigator.appVersion); // Get browser version

	var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
	var is_nav  = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
					&& (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
					&& (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));

	var is_win   = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
	var is_mac    = (clientPC.indexOf("mac")!=-1);


	// Helpline messages
	b_help = "Bold text: [b]text[/b] :: The 'text' section is shown in bold.";
	i_help = "Italic text: [i]text[/i] :: The 'text' section is shown in italic.";
	u_help = "Underline text: [u]text[/u]  :: The 'text' section is shown underlined.";
	s_help = "Striked Text : [s]text[/s] :: The 'text' section is shown striked (line thru)	";
	im_help = "Insert image: [img]http://image_url[/img] :: Allows you to insert images in your post.";
	ur_help = "Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url]";
	l_help = "List: [list]*item *item *item[/list] :: Allows you to create a bulleted list.";
	q_help = "Quote text: [quote=\"Somebody\"]text[/quote] :: Allows you to quote another person.";
	c_help = "Code example: [code]text[/code] :: Show's the 'text' as code.";
	fl_help = "Flash animation: Opens the flash animation wizard.";
	su_help = "Subscript Text : [sub]text[/sub] :: The 'text' section is shown as subscript.";
	sp_help = "Superscript Text : [sup]text[/sup] :: The 'text' section is shown as superscript.";
	

	function postForm() {
		document.pForm.btnSubmit.disabled = true;
		document.pForm.submit();
	}
	
		
	function TBUserLookup(popURL) {	
		popURL += '?w=4';	
		window.open(popURL, 'tbPop2', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=400,height=200');
	}
	function TBTagsHelp(popURL) {		
		window.open(popURL, 'tbPop', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=600,height=410');
	}

	// Define the mCode tags
	mCode = new Array();
	mTags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[s]','[/s]','[img]','[/img]','[url]','[/url]','[list]*','[/list]','[quote=\"Somebody\"]','[/quote]','[code]','[/code]','[sub]','[/sub]','[sup]','[/sup]');
	imageTag = false;



	// Replacement for arrayname.length property
	function getarraysize(thearray) {
		for (i = 0; i < thearray.length; i++) {
			if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
				return i;
			}
		return thearray.length;
	}

	// Appends element to the array
	function arraypush(thearray,value) {
		thearray[ getarraysize(thearray) ] = value;
	}

	// Removes and returns the last element of an array
	function arraypop(thearray) {
		thearraysize = getarraysize(thearray);
		retval = thearray[thearraysize - 1];
		delete thearray[thearraysize - 1];
		return retval;
	}

	var nextReset;
	// Shows the help messages in the helpline window
	function TB_helpRoll(help,itemName,itemState) {
		if (itemState==1) {
			pForm.helpbox.value = eval(help + "_help");
			itemName.className='msgSmButtonRoll';
			nextReset  = new Date();
			//nextReset = eTime.getTime;		
		} else {		
			itemName.className='msgSmButton';
			setTimeout('TB_helpReset()',1000);				
		}	
	}

	// Resets the helpbox message
	function TB_helpReset() {
		var sTime = new Date();
		var elapsed = (sTime.getTime() - nextReset.getTime()) / 1000
		if (elapsed >= 1) pForm.helpbox.value = 'Use the buttons above for quick formatting and item additions.';
	}


	function TB_loadW(wizardID,baseU) {
		var popU = baseU+'/pop.aspx?w='+wizardID;
		var pOpt;
		var pNa;
		switch(wizardID) {
			case '1':
				pOpt='toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=450,height=225';
				pNa='flPop';
				break;
			case '3':
				pOpt='toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=250,height=400';
				pNa='emPop';
				break;
			case '4':
				pOpt='toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,width=300,height=200';
				pNa='luPop';
				break;
			default:
				popU='0';
		}
		if (popU!='0')window.open(popU, pNa, pOpt );	
	}

	// opens a new window to preview the flash animation
	function flashPreview(baseU) {
		var fURL=fform.fu.value;
		var fHe=fform.fh.value;
		var fWi=fform.fw.value;
	//	var fQu=fform.fq.value;
		if (fURL!=''){
			var fPop = baseU+'/pop.aspx?w=2&fl='+escape(fURL)+'&fh='+fHe+'&fw='+fWi;
			var fOpt='toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1,width='+fWi+',height='+fHe;
			var fNa='fPrev';
			window.open(fPop,fNa,fOpt);
		} else {
			alert('You must enter the URL for your flash animation!');
		}

	}
	// posts the flash back to the post message body section
	function flashSubmit() {
		var fURL=fform.fu.value;
		var fHe=fform.fh.value;
		var fWi=fform.fw.value;
		//var fQu=fform.fq.value;
		if (fURL!=''){
			var flStr='[flash|'+fHe+'|'+fWi+']'+fURL+'[/flash]';
			//alert(window.opener.pForm.name);
			window.opener.pForm.msgbody.value+='\n'+flStr;
			window.close();
		} else {
			alert('You must enter the URL for your flash animation!');
		}
	}

	function TB_Daction(mform, mvalue, thetype) {
		var txt_selected;
		if (mvalue != 0) {
			if ((clientVer >= 4) && is_ie && is_win) {
				txt_selected = selected_text()
				if ((txt_selected == null) || (txt_selected == "") || (!txt_selected)) {
					txt_selected = " ";
					inserttext = "";
					append_text("[" + mvalue + "] [/" + mvalue + "]");
				} else {
					inserttext = txt_selected;
					append_text("[" + mvalue + "]" + inserttext + "[/" + mvalue + "]");
				}
			} else {
				window.document.pForm.msgbody.value += "[" + mvalue + "] [/" + mvalue + "]";
			}
		}
		document.pForm.cc.selectedIndex = 0;
		document.pForm.ss.selectedIndex = 0;
		document.pForm.msgbody.focus();	
	}
	
	function selected_text() {
		// returns text selected in the message text area
		document.pForm.msgbody.focus();
		if (document.pForm.msgbody.createTextRange() && document.selection.createRange()) {       
	//		alert (document.selection.createRange().text);
			return document.selection.createRange().text;
		} 
		else {
			return "";
		}
	}
	
	function append_text(text) {
		// appends text to the textbox, or inserts it at the cursor position
		document.pForm.msgbody.focus();
		if (document.pForm.msgbody.createTextRange() && document.selection.createRange()) {       
//			alert (document.selection.createRange().text);
			document.selection.createRange().text = text; //document.PostTopic.Message.createTextRange().text += text; 
		} 
		else {
			document.pForm.msgbody.value += text;
		}
	}
	
	
	function TB_Baction(bnumber) {

		donotinsert = false;
		theSelection = false;
		TBLast = 0;

		if (bnumber == -1) { // Close all open tags & default button names
			while (mCode[0]) {
				butnumber = arraypop(mCode) - 1;
				document.pForm.msgbody.value += mTags[butnumber + 1];
				buttext = eval('document.pForm.tbb' + butnumber + '.value');
				eval('document.pForm.tbb' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
			}
			imageTag = false; // All tags are closed including image tags :D
			document.pForm.msgbody.focus();
			return;
		}

		if ((clientVer >= 4) && is_ie && is_win)
			theSelection = document.selection.createRange().text; // Get text selection

		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = mTags[bnumber] + theSelection + mTags[bnumber+1];
			document.pForm.msgbody.focus();
			theSelection = '';
			return;
		}

		// Find last occurance of an open tag the same as the one just clicked
		for (i = 0; i < mCode.length; i++) {
			if (mCode[i] == bnumber+1) {
				TBLast = i;
				donotinsert = true;
			}
		}

		if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
			while (mCode[TBLast]) {
					butnumber = arraypop(mCode) - 1;
					document.pForm.msgbody.value += mTags[butnumber + 1];
					buttext = eval('document.pForm.tbb' + butnumber + '.value');
					eval('document.pForm.tbb' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
					imageTag = false;
				}
				document.pForm.msgbody.focus();
				return;
		} else { // Open tags

			if (imageTag && (bnumber != 14)) {		// Close image tag before adding another
				document.pForm.msgbody.value += mTags[15];
				lastValue = arraypop(mCode) - 1;	// Remove the close image tag from the list
				document.pForm.tbb14.value = "Img";	// Return button back to normal state
				imageTag = false;
			}

			// Open tag
			document.pForm.msgbody.value += mTags[bnumber];
			if ((bnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
			arraypush(mCode,bnumber+1);
			eval('document.pForm.tbb'+bnumber+'.value += "*"');
			document.pForm.msgbody.focus();
			return;
		}
		storeCaret(document.pForm.msgbody);
	}

	// Insert at Claret position. Code from
	// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
	function storeCaret(textEl) {
		if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
	}

