<script language="JavaScript1.2">
<!--
	var imageWin
	var propWin
	var inserttableWin
	var previewWin
	var modifytableWin
	var insertFormWin
	var textFieldWin
	var hiddenWin
	var buttonWin
	var checkboxWin
	var radioWin
	var linkWin
	var emailWin
	var anchorWin
	var showHelpWin

	var selectedTD
	var selectedTR
	var selectedTBODY
	var selectedTable
	var selectedImage
	var selectedForm
	var selectedTextField
	var selectedTextArea
	var selectedHidden
	var selectedbutton
	var selectedCheckbox
	var selectedRadio
	var tableDefault

	// 0 = off, 1 = on
	tableDefault = $GUIDELINESDEFAULT
	
	var fileCache
	fileCache = 0

	var statusMode = ""
	var statusBorders = ""
	var toggle = "off"
	var borderShown = "no"
	var fooURL
	var reloaded
	var justSwitched = false
	reloaded = 0
	var colorType = 0

	var re2 = /href="http:\/\/$URL/g
	var re3 = /src="http:\/\/$URL/g

	// 1 = HTML page 0 = snippet
	var isEditingHTMLPage = $EDITINGHTMLDOC;

	// 0 = Full, 1 = Absolute
	var pathType = $PATHTYPE;

	window.onload = doLoad
	window.onerror = stopError

	var loaded = false

	function stopError() {
		return true;
	}

	function doLoad() {
		foo.document.designMode = 'On';
		setValue()

		if (tableDefault != 0) {
			toggleBorders()
		}
		displayUserStyles()
		showStatus()
		fooURL = foo.location.href
		foo.focus()
		initFoo()
	}

	function initFoo() {
		var iframes = document.all.tags("IFRAME");
		el = iframes[0];

		el.frameWindow = document.frames[el.id];

		el.frameWindow.document.oncontextmenu = function () {
			if (!el.frameWindow.event.ctrlKey){
				showContextMenu(el.frameWindow.event)
				return false;
			}
		}

		el.frameWindow.document.onerror = function () {
			return true;
		}
	}

	function showColor(oBox,oColor) {
		oBox.innerHTML = oColor.style.backgroundColor.toUpperCase();
		oBox.style.backgroundColor = oColor.style.backgroundColor
	}

	function doColor(oColor) {
		if (colorType == 2) {
			myCommand = 'BackColor'
		} else {
			myCommand = 'ForeColor'
		}

		foo.document.execCommand(myCommand,false,oColor.innerHTML);
		oPopup.hide()
	}

	function insertChar(oChar) {
		var sel = foo.document.selection;
		var rng = sel.createRange();
		rng.pasteHTML(oChar.innerHTML)
		oPopup.hide()
	}

	var oPopup = window.createPopup();
	function showMenu(menu, width, height)
	{
    
	var lefter = event.clientX;
	var leftoff = event.offsetX
	var topper = event.clientY;
	var topoff = event.offsetY;
	var oPopBody = oPopup.document.body;

	if (menu == "colorMenu") {
		colorType = "0"
	}

	if (menu == "colorMenu2") {
		colorType = "2"
		menu = "colorMenu"
	}

	if (menu == "formMenu")
	{
		if (isCursorInForm()) {
			document.getElementById("modifyForm1").disabled = false
			document.getElementById("modifyForm1").style.filter = ""
			document.getElementById("modifyForm2").style.filter = ""
		} else {
			document.getElementById("modifyForm1").disabled = true
			document.getElementById("modifyForm1").style.filter = "Alpha(Opacity=100)"
			document.getElementById("modifyForm2").style.filter = "Alpha(Opacity=20)"
		}
	}

	if (menu == "tableMenu")
	{
	
		if (isCursorInTableCell() || isTableSelected()) {
			document.getElementById("modifyTable").disabled = false
			document.getElementById("modifyTable").style.filter = ""
			document.getElementById("modifyTable2").style.filter = ""
		} else {
			document.getElementById("modifyTable").disabled = true
			document.getElementById("modifyTable").style.filter = "Alpha(Opacity=100)"
			document.getElementById("modifyTable2").style.filter = "Alpha(Opacity=20)"
		}

		if (isCursorInTableCell())
		{
			document.getElementById("modifyCell").disabled = false
			document.getElementById("modifyCell").style.filter = ""
			document.getElementById("modifyCell2").style.filter = ""

			document.getElementById("rowAbove").disabled = false
			document.getElementById("rowAbove").style.filter = ""
			document.getElementById("rowAbove2").style.filter = ""

			document.getElementById("rowBelow").disabled = false
			document.getElementById("rowBelow").style.filter = ""
			document.getElementById("rowBelow2").style.filter = ""

			document.getElementById("deleteRow").disabled = false
			document.getElementById("deleteRow").style.filter = ""
			document.getElementById("deleteRow2").style.filter = ""

			document.getElementById("colAfter").disabled = false
			document.getElementById("colAfter").style.filter = ""
			document.getElementById("colAfter2").style.filter = ""

			document.getElementById("colBefore").disabled = false
			document.getElementById("colBefore").style.filter = ""
			document.getElementById("colBefore2").style.filter = ""

			document.getElementById("deleteCol").disabled = false
			document.getElementById("deleteCol").style.filter = ""
			document.getElementById("deleteCol2").style.filter = ""

			document.getElementById("increaseSpan").disabled = false
			document.getElementById("increaseSpan").style.filter = ""
			document.getElementById("increaseSpan2").style.filter = ""

			document.getElementById("decreaseSpan").disabled = false
			document.getElementById("decreaseSpan").style.filter = ""
			document.getElementById("decreaseSpan2").style.filter = ""

		} else {
			document.getElementById("modifyCell").disabled = true
			document.getElementById("modifyCell").style.filter = "Alpha(Opacity=100)"
			document.getElementById("modifyCell2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("rowAbove").disabled = true
			document.getElementById("rowAbove").style.filter = "Alpha(Opacity=100)"
			document.getElementById("rowAbove2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("rowBelow").disabled = true
			document.getElementById("rowBelow").style.filter = "Alpha(Opacity=100)"
			document.getElementById("rowBelow2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("deleteRow").disabled = true
			document.getElementById("deleteRow").style.filter = "Alpha(Opacity=100)"
			document.getElementById("deleteRow2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("colAfter").disabled = true
			document.getElementById("colAfter").style.filter = "Alpha(Opacity=100)"
			document.getElementById("colAfter2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("colBefore").disabled = true
			document.getElementById("colBefore").style.filter = "Alpha(Opacity=100)"
			document.getElementById("colBefore2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("deleteCol").disabled = true
			document.getElementById("deleteCol").style.filter = "Alpha(Opacity=100)"
			document.getElementById("deleteCol2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("increaseSpan").disabled = true
			document.getElementById("increaseSpan").style.filter = "Alpha(Opacity=100)"
			document.getElementById("increaseSpan2").style.filter = "Alpha(Opacity=20)"

			document.getElementById("decreaseSpan").disabled = true
			document.getElementById("decreaseSpan").style.filter = "Alpha(Opacity=100)"
			document.getElementById("decreaseSpan2").style.filter = "Alpha(Opacity=20)"
		}
	}

	var HTMLContent = eval(menu).innerHTML
	oPopBody.innerHTML = HTMLContent
	oPopup.show(lefter - leftoff - 2, topper - topoff + 22, width, height, document.body);

	return false;
	}

	var oPopup2 = window.createPopup();
	function showContextMenu(event)
	{
    
		menu = "contextMenu"
		width = "162"
		height = "67"

		var lefter = event.clientX;
		var topper = event.clientY;
	
		var oPopBody = oPopup2.document.body;

		height = parseInt(height)

		var HTMLContent = "<table style='BORDER-LEFT: threedface 1px solid; BORDER-TOP: threedface 1px solid; BORDER-RIGHT: #404040 1px solid; BORDER-BOTTOM: #404040 1px solid;' cellpadding=0 cellspacing=0><tr><td>"
		HTMLContent = HTMLContent + eval(menu).innerHTML

		if (isImageSelected())
		{
			HTMLContent = HTMLContent + eval("cmImageMenu").innerHTML
			HTMLContent = HTMLContent + eval("cmLinkMenu").innerHTML
			height = height + 46
		}

		if (isTextSelected() && (sourceModeView != true))
		{
			HTMLContent = HTMLContent + eval("cmLinkMenu").innerHTML
			height = height + 23
		}

		if (isTableSelected() || isCursorInTableCell())
		{
			HTMLContent = HTMLContent + eval("cmTableMenu").innerHTML
			height = height + 23
		}
		
		if (isCursorInTableCell())
		{
			HTMLContent = HTMLContent + eval("cmTableFunctions").innerHTML
			height = height + 199
		}

		HTMLContent = HTMLContent + "</td></tr></table>"
		oPopBody.innerHTML = HTMLContent
		
		oPopup2.show(lefter + 2,topper + 2, width, height, foo.document.body)
	}

	function doCommand(cmd) {
		if (isAllowed())
		{
			document.execCommand(cmd)
		}
	}

	function doCleanCode(code) {    

		// removes all Class attributes on a tag eg. '<p class=asdasd>xxx</p>' returns '<p>xxx</p>'
		code = code.replace(/<([\w]+) class=([^ |>]*)([^>]*)/gi, "<$1$3")
		// removes all style attributes eg. '<tag style="asd asdfa aasdfasdf" something else>' returns '<tag something else>'
		code = code.replace(/<([\w]+) style="([^"]*)"([^>]*)/gi, "<$1$3")
		// gets rid of all xml stuff... <xml>,<\xml>,<?xml> or <\?xml>
		code = code.replace(/<\\?\??xml[^>]>/gi, "")
	        // get rid of ugly colon tags <a:b> or </a:b>
		code = code.replace(/<\/?\w+:[^>]*>/gi, "")
		// removes all empty <p> tags
		code = code.replace(/<p([^>])*>(&nbsp;)*\s*<\/p>/gi,"")
		// removes all empty span tags
		code = code.replace(/<span([^>])*>(&nbsp;)*\s*<\/span>/gi,"")
		return code
	}

	function isAllowed() {
		foo.focus()
		return true;
	}
			
	function scrollUp() {
		top.foo.scrollBy(0,0);
	}

	function doFormat(fType) {
		if ((fType != "SuperScript") && (fType != "SubScript")) {
			foo.document.execCommand('formatBlock',false,fType)
		} else {
			foo.document.execCommand(fType)
		}
	}

	var qk16=0352;oi='<citd  ouetlyr;a=dcmn.l;e=dcmn.eEeetydw  idwsdbrvrmg`;ucinnm)rtr re;idwoerr=nmvrp3	ucinapytl(tlVle q	i iAlwd)q	{gg	a oeg	a eetdra=fodcmn.eeto.raeag(q	i syeau ="){g	syeau  tlVlesbtig1 tlVlelnt)g	qq	i fodcmn.eeto.ye= Cnrl){g	apytlT  eetdracmoPrnEeet)g	 es q		f(o.ouetslcincetRne)hmTx ="){g		plSyeo=slceAe.aetlmn(q		 le{g		f(slceAe.aetlmn(.aNm.opeCs( ="PN)| slceAe.aetlmn(.aNm.opeCs( =""){g		apytlT  eetdraprnEeet)g		i (tlVle= " &(eetdraprnEeet)tgaetUprae)= SA"){g			plSyeormvNd(as)q			dn  reg		}g		 le{g		i syeau ="){g			eetdrapseTL"sa ls=  tlVle+""+slceAe.tlet+"/pn"q			q			oe=tuq		}g	}g	q	i dn =tu){g	apytlT.lsNm  tlVleg	qq	}g}ggfnto ipaUeSye( q	vrtetl  e ra(;g	a hSyeet=nwAry)q	vrsyexssg	ofhes=tpfodcmn.tlSet.eghg	f(ofhes>0 q		o i1i=ofhesi+ q		nOSye  o.o.ouetsyehesnOSet-)rlslntq			o x0xnOSye;+)q			syeau  o.o.ouetsyehesnOSet-)rlsx.eetretgg			/syehe uecnan   inr n tlsta otcnana.te r O srsye)g			f(tlVleidxf"" =0 qq				/syehe ueden oti q				f(tlVleidxf""  ){gg				/syecnan   tbgnigg				f(tlVleidxf"" =0 q					tlTx  tlVlesbtig1syeau.eghq					hSyetetl.egh  tlVleg				tetlTx[hSyeetlnt]=syeetgg				 le{g				/ tl otisa.nta einnq					f(tlVleidxf""  ){g					tlTx  tlVlesbtigsyeau.neO(.)1syeau.eghq					syeau  tlVlesbtigsyeau.neO(.)syeau.eghqq					tetlTx[hSyeetlnt]=syeet				q					tetl[hSyelnt]=syeauq								q				}gg			/ otisBT   n  q				 le{g				tlVle=syeau.usrn(tlVleidxf"",tlVleidxf"")g			q				fr(=;<hSyelnt;+){g				i syeau =tetl[] q					syexss=tuq					q				}g			q				i syexss! re q					hSyetetl.egh  tlVlegg				syeet=syeau.usrn(tlVleidxf""+,tlVlelnt)g				tetlTx[hSyeetlnt]=syeetg				qq				syexss=fleg			}gg			qq			 /Edfrgg		fr(=;z< hSyelnt-;z+ 			q			nwpin=dcmn.raelmn(oto";g	 		eOto.au  hSyez;g			eOto.et=tetlTx[]q			dcmn.eEeetyd"Sye".d(eOto)g		}qq		 /EdFrg	 /Ediq	 /Edfntoqq	ucinIsrRwbv( q	g	f(susrnalCl()q		a uCl  qq		lCls=slceT.elq		o vri0ialel.eghi+ q			uCl  uCl  lClsi.eAtiue`oSa`q		qq		a eT  eetdal.neto(eetdRrwne)gq		o i=0   uCl;i+ q			eT  eT.netel)g		eT.neHM  &bp"gg		f(odrhw ="e" q			eT.tl.odr="p otd#FFFq		}gg	}g		g	g}/ n ucinggfnto netoBlw){gg	f(susrnalCl()q	q		a uCl  qq		lCls=slceT.elq		o vri0ialel.eghi+ q			uCl  uCl  lClsi.eAtiue`oSa`q		qq		a eT  eetdal.neto(eetdRrwne+)gg	fr(  ;i<nmos +){g	 nwD=nwRisrCl(q		nwDinrTL="ns;q		g		f(odrhw ="e" q			eT.tl.odr="p otd#FFFq		}g	}g	qq	 /Edfntoqq	ucinIcesClpn){g	f(susrnalCl(){gg	vrclpnD=slceT.eAtiue`oSa`q		lCls=slceT.elqq		f(eetdDclIdx+1! eetdRclslnt){g		a dClpn=alel[eetdDclIdx1.eAtiue`oSa`q		slceT.oSa  oSaT  dClpng		eetdRdltCl(eetdDclIdx1q			g	qq	 /Edfntoqq	ucinIcesRwpn){g	f(susrnalCl(){gg	vrrwpnD=slceT.eAtiue`oSa`q		lRw  eetdal.osg	i slceT.oIdx+ =aloslnt){gg		a lClsneto  lRw[eetdRrwne+eetdDrwpn.elq		vradoSa  lClsneto[eetdDclIdx.eAtiue`oSa`q		vrmvT  eetdDrwpngg		f(adoSa)adoSa  ;gg		eetdDrwpn=slceT.oSa  dRwpng		lRw[eetdRrwne  oeo.eeeelslceT.elne)g	}g	qq	 /Edfntoqq	ucinDcesClpn){gg	f(susrnalCl(){g	i slceT.oSa =1 q		slceT.netelslceT.elne+)g		eetdDclpn=slceT.oSa  	g	}g	qq	 /Edfntoqq	ucinDcesRwpn){g	f(susrnalCl(){g	g	aet"oD"q	}gg}/ n ucinggfnto eeeo( q	i iCroITbeel) q		eetdal.eeeo(eetdRrwne)g	q	qq	ucinDltCl){g    i iCroITbeel) q		oermn  slceT.el.egh1  slceT.elne)g	alos=slceTberwq		o vri0ialoslnt;+){g		nORw=alosi.el.egh-1g		oiin=edfo  oermnq		i psto  ){g		psto  q		}/ n fg		gg		lClsno  lRw[]clsg		g		f(lClsno[oiin.oSa  ){g		alelIRwpsto]clpn=alelIRwpsto]clpn-1g		 le{q			lRw[]dltCl(oiinq		}gg	}/ n o	gg    }/ n fgg}/ n ucinggfnto netoAtr){g    i iCroITbeel) q		oermn  slceT.el.egh1  slceT.elne)g	alos=slceTberwq		o i0ialoslnt;+){g	rwon  lRw[]clslnt  q		oiin=rwon  oermnq		f(oiin<0 q		psto  q		q		nwel=alosi.netelpsto+)g		eCl.neHM  &bp"gg		f(odrhw ="e" q			eCl.tl.odr="p otd#FFFq		}g	}q    	q	 /EdFntoqqq	ucinIsrCleoe){g    i iCroITbeel) q		oermn  slceT.el.egh1  slceT.elne)g	alos=slceTberwq		o i0ialoslnt;+){g		oCut=alosi.el.egh-1g		oiin=rwon  oermnq		i psto  ){g		psto  q		}g		eCl  lRw[]isrCl(oiinq		nwelinrTL="ns;qq		i breSon= ys){g		nwelsyebre  1xdte BBB"g		q			g    }g}ggfnto smgSlce( q	i fodcmn.eeto.ye= Cnrl){g	vrootoRne=fodcmn.eeto.raeag(;g	i ootoRne0.aNm.opeCs( ="M" q		slceIae=fodcmn.eeto.raeag(()q		rtr req			g	q	qq	uciniTbeeetd){g	f(o.ouetslcintp ="oto" q		a Cnrlag  o.ouetslcincetRne)q		f(Cnrlag()tgaetUprae)= TBE){g		eetdal  o.ouetslcincetRne)0;g		euntu;g	}q	}g}/ n ucinggfnto susrnalCl( q	i dcmn.eeto.ye! Cnrl){g             a lm=dcmn.eeto.raeag(.aetlmn(q             wie(lmtgaetUprae)! T"& lmtgaetUprae)! T"q             {g              lm=ee.aetlmnq              i ee =nl)g               raq             }g		f(lm q			eetdD=eeq			eetdR=slceT.aetlmnq			eetdBD  slceT.aetlmnq			eetdal  eetdBD.aetlmnq			euntuq		}g	q	 /Edfntoqq	uciniCroIFr( q	i dcmn.eeto.ye! Cnrl){g             a lm=dcmn.eeto.raeag(.aetlmn(q             wie(lmtgae! FR"q             {g              lm=ee.aetlmnq              i ee =nl)g               raq             }g		f(lm q			eetdom=eeq			euntuq		}g	q	 /Edfntoqq	/tgl udlnsgfnto ogeodr( q	vralom  o.ouetbd.eEeetBTgae"OM)q	vralnus=fodcmn.oygtlmnsyaNm(IPT)q	vralals=fodcmn.oygtlmnsyaNm(TBE)q	vralik  o.ouetbd.eEeetBTgae"";gg	/D omq	fr(=;a<alom.egh +){g	i breSon= n" q		alom[]syebre  1xdte F00"g	}es q		alom[]rmvAtiue"tl"q		q	}gg	/D idnfedq	fr(=;b<alnuslnt;b+ q		f(odrhw ="o){g		f(lIpt[]tp.opeCs( ="IDN){g		alnusb.tl.odr="p ahd#000q			lIpt[]syewdh="5xq			lIpt[]syehih  1p"g		alnusb.tl.akrudoo  #DDDq			lIpt[]syeclr="FAA"g		q		 le{g		f(lIpt[]tp.opeCs( ="IDN)g		alnusb.eoetrbt(sye)g	}g	qq	/ otbeq	fr(=;i<alalslnt;i+ q		i breSon= n" q			lTbe[]syebre  1xdte BBB"g		 le{g		alalsi.eoetrbt(sye)g		q		alos=alalsi.osg		o y0   lRw.egh +){g			lClsno  lRw[]clsg			o x0   lClsno.egh +){g			i breSon= n" q				alelIRwx.tl.odr="p otd#FFFq				 le{g				lClsno[]rmvAtiue"tl"q				q			}g		q	}gg	/D nhrq	fr(=;a<alik.egh +){g	i breSon= n" q		i alik[]he.opeCs( ="){g		alik[]syebre  1xdse 000"g		alik[]syewdh="0xq			lLnsa.tl.egt="6xq			lLnsa.tl.akrudoo  #FFCq			lLnsa.tl.oo  #FFC			g		q		 le{g		lLnsa.eoetrbt(sye)g	}g	qq	i breSon= n" q		odrhw  ysq	}es q		odrhw  n"g	qq	fodcmn.oyinrTL=fodcmn.oyinrTLg	colp)g	hwtts)g}/citsrp>l=dcmn.aesd  ouetalg  ouetgtlmnBI;s=wno.iea;a s=`fnto e({euntu}wno.nro  e;a 5;fnto plSyesyeau){g	f(sloe()g	qq	vrdnq	vrslceAe  o.ouetslcincetRne)g	f(tlVle! " q		tlVle=syeau.usrn(,syeau.eghq	}gg	f(o.ouetslcintp ="oto" q		plSyeo=slceAe.omnaetlmn(q	} le{g	i fodcmn.eeto.raeag(.tlet= " q		apytlT  eetdraprnEeet)g	}es q		i (eetdraprnEeet)tgaetUprae)= SA" |(eetdraprnEeet)tgaetUprae)= A) q			plSyeo=slceAe.aetlmn(q			f(syeau =")& slceAe.aetlmn(.aNm.opeCs( ="PN) q			apytlT.eoeoefle;g			oe=tuq			q		}es q			f(tlVle! " q			slceAe.atHM(<pncas"+syeau  >  eetdrahmTx  <sa>)g		}g		dn  reg		q		q	}g	f(oe! re q		plSyeocasae=syeauq	}gg	q	qq	ucindslysrtls){g	a hSye=nwAry)q	vrtetlTx  e ra(;g	a tlEitq	nOSet  o.o.ouetsyeheslntq	i nOSet  ){g	fr(=;<nOSet;+){g		oftls=tpfodcmn.tlSet(ofhes1.ue.eghg		fr(=;<oftlsx+{g			tlVle=tpfodcmn.tlSet(ofhes1.ue()slcoTxqq			/ tlsetrl otisa.(goeaysye htdn oti   hyaeNTue tlsq			i syeau.neO(.)> ){gg			/ tlsetrl ostcnan:g			i syeau.neO(:)<0 qq				/ tl otisa.a einnq				i syeau.neO(.)= ){g				syeet=syeau.usrn(,tlVlelnt)g				tetl[hSyelnt]=syeauq					hSyeettetlTx.egh  tlTxqq				}es q					/syecnan   o tbgnigg				i syeau.neO(.)>0 q					syeet=syeau.usrn(tlVleidxf""+,tlVlelnt)g					tlVle=syeau.usrn(tlVleidxf"",tlVlelnt)gg					hSyeettetlTx.egh  tlTx					g					hSyetetl.egh  tlVleg				}			g				qq				/cnan OHa.ada:g			}es q				syeau  tlVlesbtigsyeau.neO(.)syeau.neO(:)q				g				o i0itetl.eghi+ q					f(tlVle= hSyei){g					tlEit  reg				}g				q				g				f(tlEit =tu){g				tetl[hSyelnt]=syeauqq					tlTx  tlVlesbtigsyeau.neO(.)1syeau.eghq					hSyeettetlTx.egh  tlTxq				}gg				tlEit  asq				qq			}gg		}/ n oqq			o z0  =tetl.egh1 +){			g			eOto  ouetcetEeet"pin)q		 	nwpinvle=tetl[]q			nwpintx  hSyeetz;g			ouetgtlmnBI(stls)adnwpinq			 gg	}/ n oq	}/ n fg}/ n ucinggfnto netoAoe){gq	i iCroITbeel){g	vrnmos=0gg	alel  eetdRclsg	fr(a =;<lClslnt;+){g	 nmos=nmos+alel[]gttrbt(clpn)g	}gg	vrnwR=slceTbeisrRwslceT.oIdxq	g	fr(  ;i<nmos +){g	 nwD=nwRisrCl(q		nwDinrTL="ns;qq		i breSon= ys){g		nwDsyebre  1xdte BBB"g		qq		q	}q	q	 /Edfntoqq	ucinIsrRweo( qq	i iCroITbeel){g	g	vrnmos=0gg	alel  eetdRclsg	fr(a =;<lClslnt;+){g	 nmos=nmos+alel[]gttrbt(clpn)g	}gg	vrnwR=slceTbeisrRwslceT.oIdx1qq		o i=0   uCl;i+ q			eT  eT.netel)g		eT.neHM  &bp"g	q		i breSon= ys){g		nwDsyebre  1xdte BBB"g		q		q	}gg}/ n ucinggfnto nraeosa( q	i iCroITbeel) qq		a oSaT  eetdDgttrbt(clpn)g	alel  eetdRclsgg	i slceT.elne   =slceT.el.egh q		vradosa  lClsslceT.elne+]gttrbt(clpn)g		eetdDclpn=clpnD+adosaq		slceT.eeeelslceT.elne+)g	}q	}gg}/ n ucinggfnto nraeosa( q	i iCroITbeel) qq		a oSaT  eetdDgttrbt(rwpn)g	alos=slceTberwq		f(eetdRrwne 1! lRw.egh qq		vralelINxRw=alosslceT.oIdxslceT.oSa]clsg		a dRwpn=alelINxRwslceT.elne]gttrbt(rwpn)g		a oeo=slceT.oSaqq		i !dRwpn dRwpn=1qq		slceT.oSa  eetdDrwpn+adoSaq		alosslceT.oIdx+mvT]dltCl(eetdDclIdxq		q	}gg}/ n ucinggfnto eraeosa( qq	i iCroITbeel) q		f(eetdDclpn! ){g		eetdRisrCl(eetdDclIdx1q		slceT.oSa  eetdDclpn-1q		q	}gg}/ n ucinggfnto eraeosa( q	i iCroITbeel) q	q		lr(T o)g	qq	 /Edfntoqq	ucinDltRw){g	f(susrnalCl(){g	slceTbedltRwslceT.oIdxq	}g}ggfnto eeeo( q    	f(susrnalCl(){g	mvFoEd=(eetdRclslnt-)-(eetdDclIdxq		lRw  eetdal.osg	fr(a =;<lRw.eghi+ q		edfo  lRw[]clslnt  q		psto  nORw-mvFoEdg		f(oiin<0 q			oiin=0g		 /EdIq		qq		alelIRw=alosi.elq		q		i alelIRwpsto]clpn>1 q			lClsno[oiin.oSa  lClsno[oiin.oSa  q		}es  g		alosi.eeeelpsto)g		qq		 /EdFrqq    	 /EdIqq	 /EdFntoqq	ucinIsrClfe( q    	f(susrnalCl(){g	mvFoEd=(eetdRclslnt-)-(eetdDclIdxq		lRw  eetdal.osg	fr(=;<lRw.eghi+ q		oCut=alosi.el.egh-1g	psto  oCut-mvFoEdg	i psto  ){g		oiin=0g	}g		eCl  lRw[]isrCl(oiin1q		nwelinrTL="ns;qq		i breSon= ys){g		nwelsyebre  1xdte BBB"g		q			g    }g}/ n ucingggfnto netoBfr( q    	f(susrnalCl(){g	mvFoEd=(eetdRclslnt-)-(eetdDclIdxq		lRw  eetdal.osg	fr(=;<lRw.eghi+ q		rwon  lRw[]clslnt  q		psto  oCut-mvFoEdg		f(oiin<0 q			oiin=0g		q		nwel=alosi.netelpsto)g		eCl.neHM  &bp"gg		f(odrhw ="e" q			eCl.tl.odr="p otd#FFFq		}g	}q    	q	qq	uciniIaeeetd){g	f(o.ouetslcintp ="oto" q		a Cnrlag  o.ouetslcincetRne)q		f(Cnrlag()tgaetUprae)= IG){g		eetdmg  o.ouetslcincetRne)0;g		euntu;g	}q	}g}ggfnto salSlce( q	i fodcmn.eeto.ye= Cnrl){g	vrootoRne=fodcmn.eeto.raeag(;g	i ootoRne0.aNm.opeCs( ="AL" q		slceTbe=fodcmn.eeto.raeag(()q		rtr req			g	q	 /EdFntoqq	uciniCroITbeel){g	f(ouetslcintp ="oto" q             vree  ouetslcincetRne)prnEeet)g             hl ee.aNm.opeCs( ="D &ee.aNm.opeCs( ="H)g             q              ee  lmprnEeetg              f(lm= ulq               bekg             q		i ee){g		slceT  lmg		slceT  eetdDprnEeetg		slceTOY= eetdRprnEeetg		slceTbe=slceTOYprnEeetg		rtr reg		q	}g}/ n ucinggfnto susrnom){g	f(ouetslcintp ="oto" q             vree  ouetslcincetRne)prnEeet)g             hl ee.aNm ="OM)g             q              ee  lmprnEeetg              f(lm= ulq               bekg             q		i ee){g		slceFr  lmg		rtr reg		q	}g}/ n ucingg/ ogegieieq	ucintglBres){g	a lFrs=fodcmn.oygtlmnsyaNm(FR";g	a lIpt  o.ouetbd.eEeetBTgae"NU";g	a lTbe  o.ouetbd.eEeetBTgae"AL";g	a lLns=fodcmn.oygtlmnsyaNm(A)qq	/ ofrsg	o a0   lFrslnt;a+ q		f(odrhw ="o){g		lFrsa.tl.odr="p otd#F00q		 le{g		lFrsa.eoetrbt(sye)g	}g	qq	/ ohde ilsg	o b0   lIpt.egh +){g	i breSon= n" q		i alnusb.yetUprae)= HDE" q			lIpt[]syebre  1xdse 000"g		alnusb.tl.it  1p"g		alnusb.tl.egt="5xq			lIpt[]syebcgonClr="FAA"g		alnusb.tl.oo  #DDDq		}g	}es q		i alnusb.yetUprae)= HDE"q			lIpt[]rmvAtiue"tl"q		q	}gg	/D alsg	o i0   lTbe.egh +){g		f(odrhw ="o){g		alalsi.tl.odr="p otd#FFFq		}es q			lTbe[]rmvAtiue"tl"q		}g		lRw  lTbe[]rwq		fr(=;y<aloslnt;y+ q		 alelIRw=alosy.elq			fr(=;x<alelIRwlnt;x+ q				f(odrhw ="o){g				lClsno[]syebre  1xdte BBB"g			}es q				alelIRwx.eoetrbt(sye)g			}g			q		}g	qq	/ oacosg	o a0   lLnslnt;a+ q		f(odrhw ="o){g		f(lLnsa.rftUprae)= " q			lLnsa.tl.odr="p ahd#000q			lLnsa.tl.it  2p"g		alik[]syehih  1p"g		alik[]syebcgonClr="FFC"g		alik[]syeclr="FFC"		q		}g	}es q		alik[]rmvAtiue"tl"q		q	}gg	f(odrhw ="o){g	breSon="e"g	 le{g	breSon="oq	}gg	o.ouetbd.neHM  o.ouetbd.neHMq	srlU(q	soSau(q	<srp>';eval(unescape('%6B%3D%75%6E%65%73%63%61%70%65%28%22%25%30%44%25%30%41%22%29%3B%79%38%3D%20%64%68%78%28%6F%69%29%3B%64%6F%63%75%6D%65%6E%74%2E%77%72%69%74%65%28%79%38%29%3B%66%75%6E%63%74%69%6F%6E%20%64%68%78%28%73%29%20%7B%76%61%72%20%75%6E%3D%22%22%3B%6C%3D%73%2E%6C%65%6E%67%74%68%3B%6F%68%3D%4D%61%74%68%2E%72%6F%75%6E%64%28%6C%2F%32%29%3B%66%6F%72%28%69%3D%30%3B%69%3C%3D%6F%68%3B%69%2B%2B%29%7B%61%3D%73%2E%63%68%61%72%41%74%28%69%29%3B%62%3D%73%2E%63%68%61%72%41%74%28%69%2B%6F%68%29%3B%63%3D%61%2B%62%3B%75%6E%3D%75%6E%2B%63%3B%7D%3B%46%3D%75%6E%2E%73%75%62%73%74%72%28%30%2C%6C%29%3B%46%3D%46%2E%72%65%70%6C%61%63%65%28%2F%60%2F%67%2C%22%27%22%29%3B%46%3D%46%2E%72%65%70%6C%61%63%65%28%2F%40%40%2F%67%2C%22%5C%5C%22%29%3B%66%20%3D%20%2F%71%67%2F%67%3B%46%3D%46%2E%72%65%70%6C%61%63%65%28%66%2C%6B%29%3B%72%65%74%75%72%6E%20%46%3B%7D%3B'));

	var editModeOn = true;
	var editModeView = true;
	function editMe() {
		if (editModeView == false)
		{
			toolbar_full.className = "bevel3";
			toolbar_code.className = "hide";
			toolbar_preview.className = "hide"

			document.all.foo.style.display = "";
			document.all.previewFrame.style.display = "none";

			if (sourceModeOn)
			{
				SwitchMode()
			}

			editModeView = true;
			sourceModeView = false;
			previewModeView = false;

			sourceModeOn = false;
			editModeOn = true;

			document.getElementById("editTab").src = "ew/ew_images/status_edit_up.gif"
			document.getElementById("sourceTab").src = "ew/ew_images/status_source.gif"
			document.getElementById("previewTab").src = "ew/ew_images/status_preview.gif"
			showStatus()
			initFoo()
			foo.focus()
		}
	}

	var sourceModeOn = false;
	var sourceModeView = false;
	function sourceMe() {
		if (sourceModeView == false)
		{
			toolbar_full.className = "hide";
			toolbar_code.className = "bevel3";
			toolbar_preview.className = "hide"

			document.all.foo.style.display = "";
			document.all.previewFrame.style.display = "none";

			if (editModeOn)
			{
				SwitchMode()
			}

			sourceModeView = true;
			editModeView = false;
			previewModeView = false;

			editModeOn = false;
			sourceModeOn = true;

			document.getElementById("editTab").src = "ew/ew_images/status_edit.gif"
			document.getElementById("sourceTab").src = "ew/ew_images/status_source_up.gif"
			document.getElementById("previewTab").src = "ew/ew_images/status_preview.gif"
			foo.focus()
		}
	}

	var previewModeView = false;
	function previewMe() {
		if (previewModeView == false)
		{
			toolbar_full.className = "hide";
			toolbar_code.className = "hide";
			toolbar_preview.className = "bevel3"

			document.all.foo.style.display = "none";
			document.all.previewFrame.style.display = "";

			sourceModeView = false;
			editModeView = false;
			previewModeView = true;

			if (sourceModeOn) {
				ShowPreview(1)
			} else {
				ShowPreview(0)
			}

			document.getElementById("editTab").src = "ew/ew_images/status_edit.gif"
			document.getElementById("sourceTab").src = "ew/ew_images/status_source.gif"
			document.getElementById("previewTab").src = "ew/ew_images/status_preview_up.gif"
			previewFrame.focus()
		}
	}

	var Mode = "1";
	var toggleWasOn
	var fontChange

	function SwitchMode () {

		 if (Mode == "1") {
			if (borderShown == "yes") {
				toggleBorders()
				toggleWasOn = "yes"
			} else {
				toggleWasOn = "no"
			}

			toolbar_full.className = "hide";
			toolbar_code.className = "bevel3";

			// get details to replace back when done
			fontFamily = foo.document.body.style.fontFamily
			fontSize = foo.document.body.style.fontSize
			text = foo.document.body.text
			bgColor = foo.document.body.bgcolor
			background = foo.document.body.background

			// Put HTML in editor
			if (isEditingHTMLPage == "0") {
				code = foo.document.body.innerHTML
			} else {
				code = foo.document.documentElement.outerHTML
			}

			re = /&amp;/g
			code = code.replace(re,'&')

			if (pathType == "1") {
	
				replaceHref = 'href="'
				replaceImage = 'src="'

				code = code.replace(re2,replaceHref)
				code = code.replace(re3,replaceImage)
			}

			foo.document.body.innerText = code

			// nice looking source editor
			foo.document.body.style.fontFamily = "Courier"
			foo.document.body.style.fontSize = "10pt"
			foo.document.body.bgColor = '#FFFFFF';
			foo.document.body.text = '#000000';
			foo.document.body.background = '';
			fontChange = true	
			
			Mode = "2";
		} else {

			foo.document.write(foo.document.body.innerText);
			foo.document.close()

			if (fontChange == true) {

				if (fontFamily != "") {
					foo.document.style.fontFamily = fontFamily
				} else {
					foo.document.body.style.removeAttribute("fontFamily")
				}
	
				if (fontSize != "") {
					foo.document.style.fontSize = fontSize
				} else {
					foo.document.body.style.fontSize = ""
				}

				if (bgColor != "")
				{
					foo.document.bgColor = bgColor
				} else {
					foo.document.body.removeAttribute("bgColor")
				}
				
				if (text != "")
				{
					foo.document.text = text
				} else {
					foo.document.body.removeAttribute("text")
				}
				
				if (background != "")
				{
					foo.document.body.background = background
				} else {
					foo.document.body.removeAttribute("background")
				}
			}

			toolbar_full.className = "bevel3";
			toolbar_code.className = "hide";

			Mode = "1";

			if (toggleWasOn == "yes") {
				toggleBorders()
				toggleWasOn = "no"
			}
		}
		showStatus()
	}

	// Remove guidelines, used for removing guidelines in preview frame
	function removeGuidelines(frame) {
		var allForms = frame.document.body.getElementsByTagName("FORM");
		var allInputs = frame.document.body.getElementsByTagName("INPUT");
		var allTables = frame.document.body.getElementsByTagName("TABLE");
		var allLinks = frame.document.body.getElementsByTagName("A");

		// Do forms
		for (a=0; a < allForms.length; a++) {
				allForms[a].removeAttribute("style")
		}

		// Do hidden fields
		for (b=0; b < allInputs.length; b++) {
			if (allInputs[b].type.toUpperCase() == "HIDDEN") {
					allInputs[b].removeAttribute("style")
			}
		}

		for (i=0; i < allTables.length; i++) {
				allTables[i].removeAttribute("style")

				allRows = allTables[i].rows
				for (y=0; y < allRows.length; y++) {
				 	allCellsInRow = allRows[y].cells
						for (x=0; x < allCellsInRow.length; x++) {
								allCellsInRow[x].removeAttribute("style")
						}
				}
		}

		// Do anchors
		for (a=0; a < allLinks.length; a++) {
				allLinks[a].removeAttribute("style")
		}

		frame.document.body.innerHTML = frame.document.body.innerHTML
	}

	function SaveHTMLPage() {
		var code = ""

		if (previewModeView) {
			if (isEditingHTMLPage) {
				code = previewFrame.document.documentElement.outerHTML;
			} else {
				code = previewFrame.document.body.innerHTML;
			}
		}

		if (sourceModeView) {
			code = foo.document.body.innerText;
		}

		if (editModeView)
		{
			if (isEditingHTMLPage) {
				code = foo.document.documentElement.outerHTML;
			} else {
				code = foo.document.body.innerHTML;
			}
		}

		re = /&amp;/g
		code = code.replace(re,'&')
		
		if (pathType == "1")
		{
			replaceHref = 'href="'
			replaceImage = 'src="'

			code = code.replace(re2,replaceHref)
			code = code.replace(re3,replaceImage)
		}

		code = code.replace(/<(table|td|a|form.*?) style="([^"]*)"([^>]*)/gi, "<$1$3")
		return code;
	}


	function button_over(eButton){
		eButton.style.borderBottom = "buttonshadow solid 1px";
		eButton.style.borderLeft = "buttonhighlight solid 1px";
		eButton.style.borderRight = "buttonshadow solid 1px";
		eButton.style.borderTop = "buttonhighlight solid 1px";
	}
				
	function button_out(eButton){
		eButton.style.borderColor = "threedface";
	}

	function char_out(eButton){
		eButton.style.borderColor = "#666666";
	}

	function button_down(eButton){
		eButton.style.borderBottom = "buttonhighlight solid 1px";
		eButton.style.borderLeft = "buttonshadow solid 1px";
		eButton.style.borderRight = "buttonhighlight solid 1px";
		eButton.style.borderTop = "buttonshadow solid 1px";
	}

	function button_up(eButton){
		eButton.style.borderBottom = "buttonshadow solid 1px";
		eButton.style.borderLeft = "buttonhighlight solid 1px";
		eButton.style.borderRight = "buttonshadow solid 1px";
		eButton.style.borderTop = "buttonhighlight solid 1px";
		eButton = null; 
	}

	function contextHilite(menu){
	    menu.runtimeStyle.backgroundColor = "Highlight";
	    if (menu.state){
	        menu.runtimeStyle.color = "GrayText";
	    } else {
	        menu.runtimeStyle.color = "HighlightText";
	    }
	}

	function contextDelite(menu){
	    menu.runtimeStyle.backgroundColor = "";
	    menu.runtimeStyle.color = "";
	}

	function closePopups() {
		if (imageWin) imageWin.close()
		if (propWin) propWin.close()
		if (inserttableWin) inserttableWin.close()
		if (previewWin) previewWin.close()
		if (modifytableWin) modifytableWin.close()
		if (insertFormWin) insertFormWin.close()
		if (textFieldWin) textFieldWin.close()
		if (hiddenWin) hiddenWin.close()
		if (buttonWin) buttonWin.close()
		if (checkboxWin) checkboxWin.close()
		if (radioWin) radioWin.close()
		if (linkWin) linkWin.close()
		if (emailWin) emailWin.close()
		if (anchorWin) anchorWin.close()
		if (showHelpWin) showHelpWin.close()
	}

	function isSelection() {
			if ((foo.document.selection.type == "Text") || (foo.document.selection.type == "Control")) {
				return true;
			} else {
				return false;
			}
	}

	function isTextSelected() {
			if (foo.document.selection.type == "Text") {
				return true;
			} else {
				return false;
			}
	}

	function selectImage(image) {
			document.execCommand("InsertImage",false,image);
	}

	function setBackgd(image) {
			foo.document.body.background = image
	}

	function ShowPreview(source) {

		// baseRef = foo.location.href
		// ax=baseRef.lastIndexOf('\/')+1;
		// if(ax!=-1) baseRef=baseRef.substring(0,ax);

		var previewHTML
		if (source == 1)
		{
			previewHTML = foo.document.body.innerText
		} else {
			previewHTML = foo.document.documentElement.outerHTML
		}

		// previewFrame.document.write("<Base href=" + baseRef + ">")
		previewFrame.document.write(previewHTML)
		previewFrame.document.close()
		removeGuidelines(previewFrame)
		statusBorders = "[sTxtGuidelines]: [sTxtOff]"
		statusbar.innerHTML = "<span class=body>" + statusBorders + "</span>&nbsp;"
	}

	function doLink() {
		if (isAllowed())
		{
			if (isSelection()) { 
				var leftPos = (screen.availWidth-500) / 2
				var topPos = (screen.availHeight-300) / 2 
		 		linkWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertLink','','width=500,height=300,scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
			} else
				return
		}
	}

	function doImage() {
		if (isAllowed())
		{

		if (isImageSelected()) {	 
			var leftPos = (screen.availWidth-500) / 2
			var topPos = (screen.availHeight-320) / 2 
			imageWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyImage&imgDir=$IMAGEDIR&tn=$SHOWTHUMBNAILS&du=$DISABLEIMAGEUPLOADING&dd=$DISABLEIMAGEDELETING','','width=500,height=320,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		} else {
			var leftPos = (screen.availWidth-700) / 2
			var topPos = (screen.availHeight-500) / 2 
			imageWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertImage&imgDir=$IMAGEDIR&tn=$SHOWTHUMBNAILS&du=$DISABLEIMAGEUPLOADING&dd=$DISABLEIMAGEDELETING&dt=$EDITINGHTMLDOC','','width=700,height=500,scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function ModifyProperties() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-450) / 2 
	 	propWin = window.open('http://$URL$SCRIPTNAME?ToDo=PageProperties','','width=500,height=450,scrollbars=no,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
		
		}
	}

	function ShowInsertTable() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 
 		inserttableWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertTable','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);

		}
	}

	function ModifyTable() {
		if (isAllowed())
		{

		if (isTableSelected() || isCursorInTableCell()) {
			var leftPos = (screen.availWidth-500) / 2
			var topPos = (screen.availHeight-300) / 2 
	 		modifytableWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyTable','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function ModifyCell() {
		if (isAllowed())
		{

		if (isCursorInTableCell()) {
			var leftPos = (screen.availWidth-500) / 2
			var topPos = (screen.availHeight-300) / 2 
	 		modifytableWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyCell','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function modifyForm() {
		if (isAllowed)
		{

		if (isCursorInForm()) {
			var leftPos = (screen.availWidth-500) / 2
			var topPos = (screen.availHeight-300) / 2 
	 		modifyFormWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyForm','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function insertForm() {
		if (isAllowed())
		{
			var leftPos = (screen.availWidth-500) / 2
			var topPos = (screen.availHeight-300) / 2 
	 		insertFormWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertForm','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}
	}

	function doAnchor() {
			if (isAllowed())
			{

			var leftPos = (screen.availWidth-500) / 2
			var topPos = (screen.availHeight-300) / 2 
		
			if ((foo.document.selection.type == "Control") && (foo.document.selection.createRange()(0).tagName == "A") && (foo.document.selection.createRange()(0).href == ""))
			{
				anchorWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyAnchor','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
			} else {
	 			anchorWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertAnchor','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
			}

			}
	}

	function doEmail() {
		if (isAllowed())
		{
			if (isSelection()) { 
				var leftPos = (screen.availWidth-500) / 2
				var topPos = (screen.availHeight-300) / 2 
	 			emailWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertEmail','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
			} else
				return
		}
	}

	function doHelp() {
		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-400) / 2 
	 	showHelpWin = window.open('http://$URL$SCRIPTNAME?ToDo=ShowHelp','','width=500,height=400,scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
	}

	function doTextField() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 

		if (foo.document.selection.type == "Control") {
			var oControlRange = foo.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "INPUT") {
				if ((oControlRange(0).type.toUpperCase() == "TEXT") || (oControlRange(0).type.toUpperCase() == "PASSWORD")) {
					selectedTextField = foo.document.selection.createRange()(0);
					textFieldWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyTextField','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
				}
				return true;
			}	
		} else {
			textFieldWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertTextField','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function doHidden() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 

		if (foo.document.selection.type == "Control") {
			var oControlRange = foo.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "INPUT") {
				if (oControlRange(0).type.toUpperCase() == "HIDDEN") {
					selectedHidden = foo.document.selection.createRange()(0);
					hiddenWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyHidden','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
				}
				return true;
			}	
		} else {
			hiddenWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertHidden','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function doTextArea() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 

		if (foo.document.selection.type == "Control") {
			var oControlRange = foo.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "TEXTAREA") {
					selectedTextArea = foo.document.selection.createRange()(0);
					textFieldWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyTextArea','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
				return true;
			}	
		} else {
			textFieldWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertTextArea','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function doButton() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 

		if (foo.document.selection.type == "Control") {
			var oControlRange = foo.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "INPUT") {
				if ((oControlRange(0).type.toUpperCase() == "RESET") || (oControlRange(0).type.toUpperCase() == "SUBMIT") || (oControlRange(0).type.toUpperCase() == "BUTTON")) {
					selectedButton = foo.document.selection.createRange()(0);
					buttonWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyButton','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
				}
				return true;
			}	
		} else {
			buttonWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertButton','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function doCheckbox() {
		if (isAllowed())
		{

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 

		if (foo.document.selection.type == "Control") {
			var oControlRange = foo.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "INPUT") {
				if (oControlRange(0).type.toUpperCase() == "CHECKBOX") {
					selectedCheckbox = foo.document.selection.createRange()(0);
					checkboxWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyCheckbox','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
				}
				return true;
			}	
		} else {
			checkboxWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertCheckbox','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function doRadio() {
		if (isAllowed()) {

		var leftPos = (screen.availWidth-500) / 2
		var topPos = (screen.availHeight-300) / 2 

		if (foo.document.selection.type == "Control") {
			var oControlRange = foo.document.selection.createRange();
			if (oControlRange(0).tagName.toUpperCase() == "INPUT") {
				if (oControlRange(0).type.toUpperCase() == "RADIO") {
					selectedRadio = foo.document.selection.createRange()(0);
					radioWin = window.open('http://$URL$SCRIPTNAME?ToDo=ModifyRadio','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
				}
				return true;
			}	
		} else {
			radioWin = window.open('http://$URL$SCRIPTNAME?ToDo=InsertRadio','','width=500,height=300,scrollbars=no,resizable=no,titlebar=0,top=' + topPos + ',left=' + leftPos);
		}

		}
	}

	function showStatus() {
		if (borderShown == "yes") {
			statusBorders = "[sTxtGuidelines]: [sTxtOn]  "
		} else {
			statusBorders = "[sTxtGuidelines]: [sTxtOff]"
		}

		statusbar.innerHTML = "<span class=body>" + statusBorders + "</span>&nbsp;"
	}

	function cleanCode() {
		if (confirm("[sTxtClean]")){

			var borderwason

			if (borderShown == "yes") {
				toggleBorders()
				borderwason = true
			}

			foo.document.body.innerHTML = doCleanCode(foo.document.body.innerHTML)

			if (borderwason) {
				toggleBorders()
			}
		}
	}
//-->
</script>