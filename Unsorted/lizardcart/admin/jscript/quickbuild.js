/*** Freeware 09.07.02 Open Source writen by ngoCanh 5-2002          */
/*** Original by Vietdev  http://vietdev.sourceforge.net             */
/*** Release 2002.09.15  R5.0                                        */
/*** Release 2002.10.05  R5.1                                        */
/*** GPL - Copyright protected                                       */
/*********************************************************************/


/*** CONFIGURATION - HERE YOU CAN SET DEFAULT-VALUES ********************/
if(typeof(QBPATH)=="undefined") QBPATH='.'
if(typeof(SECURE)=="undefined") SECURE=1; //=0,1
if(typeof(VISUAL)=="undefined") VISUAL=1; //=0,1,2,3 see bottom of this file
if(typeof(POPWIN)=="undefined") POPWIN=1; // Rightclick Popup dialog
if(typeof(DFFACE)=="undefined") DFFACE=''; // 'times new roman'; // Default fontFamily of Editor
if(typeof(DFSIZE)=="undefined") DFSIZE=''; // '14px'; // Default fontSize
if(typeof(DCOLOR)=="undefined") DCOLOR=''; // 'blue'; // Default color
if(typeof(DBGCOL)=="undefined") DBGCOL=''; // 'green'; // Default backgroundColor
if(typeof(DBGIMG)=="undefined") DBGIMG=''; // Default URL-backgroundImage 
if(typeof(DCSS)=="undefined") DCSS=''; // 'test.css'; // Default-Stylesheet-URL
if(typeof(SYMBOLE)=="undefined") SYMBOLE='<QBFBR>' ; // Symbole for end-of-field in clipboard-chipcard
if(typeof(USETABLE)=="undefined") USETABLE=1; // Support table editor
if(typeof(USEFORM)=="undefined") USEFORM=0; // Support form input
/*********************** END CONFIGURAION ****************************/




var fID; //***   IFRAME ID
var TXTOBJ; //***   TEXT Obj
var format=new Array();
var viewm=new Array();
var DEFFFACE= new Array();
var DEFFSIZE= new Array();
var DEFCOLOR= new Array();
var DEFBCOLOR= new Array();
var DEFBIMAGE= new Array();
var DEFCSS= new Array();
var FWORD, FLAGS=0;


document.onmousedown=doMousedown
document.onmouseup=doMouseup
document.onkeydown=doKeydown
window.onbeforeunload= saveBefore

function saveBefore()
{
  if(typeof(ASKED)!="undefined") return;

  actualize()
  var arr= isNeedSave()
  if(!arr) return
  
  var yes= confirm('Save your work into clipboard?')
  if(yes) savetoClipboard(arr)
  ASKED=1
}


function isNeedSave()
{
   if(!document.forms || document.forms.length==0) return;

   var fidx, el;
   var oForm, strx1='', conts='', afield=null, fIDx ;

   for(fidx=0; fidx<document.forms.length; fidx++)
    {
	 oForm= document.forms[fidx]
     for(var i=0; i<oForm.elements.length; i++)
     {
      el= oForm.elements[i]
      if(el.type!='text' && el.type!='textarea' && el.type!='hidden') continue

      fIDx= fidx +'VDevID'+ el.name
      if(!afield && el.type=='hidden' && document.frames[fIDx] ) afield= document.frames[fIDx]
	  if(conts=='' && el.type=='hidden' && document.frames[fIDx] )
		 conts = el.document.frames[fIDx].document.body.innerHTML
      strx1 += el.value + SYMBOLE ;
     }
	}
   strx1 += "END" ;

   if(!afield || conts=='' || conts=='<P></P>' || conts=='<P>&nbsp;</P>') return;

   var arr= new Array(afield,strx1)

   return arr;

}


function savetoClipboard(arr)
{
   var afield= arr[0];
   var strx1= arr[1];

   var strx2= afield.document.body.innerHTML
   afield.document.body.innerText= strx1 ;

   var rng= afield.document.body.createTextRange()
   rng.execCommand('SelectAll')
   rng.execCommand("Copy");

   afield.document.body.innerHTML=strx2;
}


function changetoIframeEditor(el)
{
   var wi= '', hi= '';
   if(el.style.height) hi= el.style.height
   else if(el.rows) hi= (14*el.rows+28)
   if(el.style.width) wi= el.style.width
   else if(el.cols) wi= (6*el.cols +25)
   	   
   var parent= el.parentElement
   while(parent.tagName != 'FORM') parent= parent.parentElement
   var oform= parent
   var fidx=0; while(document.forms[fidx] != oform) fidx++ ; // form index

   var val='', fID;
   if(el.tagName=='TEXTAREA'){ fID= fidx+'VDevID'+el.name; val= el.innerText }
   else fID= fidx+'VDevID'+el.id

   var strx = createEditor(fID,wi,hi);
   el.outerHTML= strx
   
   iEditor(fID)

   if(el.tagName!='TEXTAREA') return

   val= val.replace(/\r/g, "");
   val= val.replace(/\n<TABLE/g, "<TABLE");
   val= val.replace(/\n<TBODY/g, "<TBODY");
   val= val.replace(/\n<TD/g, "<TD");
   val= val.replace(/\n<TR/g, "<TR");
   
   var reg= /<pre>/i ;
   if( reg.test(val) )
	 { val= val.replace(/\n/g, "&#13;"); val= val.replace(/\t/g, "     "); }

   val= val.replace(/\n/g, "<br>");
   val= val.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");

   val= val.replace(/\\/g, "&#92;");
   val= val.replace(/\'/g, "&#39;");

   if(val && val.indexOf('ViEtDeVdIvId')>=0) val= initDefaultOptions1(val,fID)
   else initDefaultOptions0(fID)

   setTimeout("document.frames['"+fID+"'].document.body.innerHTML='"+val+"'",200)

   oform[fID.split('VDevID')[1]].value= val

   TXTOBJ= null
   
}






function iEditor(idF)
{
  var obj=document.frames[idF]
  obj.document.designMode="On"
  obj.document.onmousedown= function(){  TXTOBJ=null; fID=idF; FMousedown();}
  obj.document.onkeydown=FKeydown
  
  format[idF]='HTML'
  viewm[idF]=1;
}



function FKeydown()
{
  var objF=document.frames[fID];
  if(!objF||!objF.event){alert('Please click to select the editor');return}

  var key=objF.event.keyCode
  
  if(objF.event.ctrlKey && key==71) { findText(); return false }  // ctrl+G search
  else if(objF.event.ctrlKey && key==75){ findTextHotKey(0); return false } // ctrl+K  search forward
  else if(objF.event.ctrlKey && key==74){ findTextHotKey(1); return false } // ctrl+J  search backward 
  else if(objF.event.ctrlKey && key==83){ SmartcardData(); return false } // ctrl+S content rewrite
  else if(objF.event.ctrlKey && key==84){ swapMode(); return false } // ctrl+T swapMode

}



function formatDialogF()
{
  var y = screen.height -parseInt('30em')*14 - 30 
  var feature = "font-family:Arial;font-size:10pt;dialogWidth:30em;dialogHeight:27em;dialogTop:"+y
      feature+= ";edge:sunken;help:no;status:no"

  var dialog= QBPATH+'/dialog.html'
  var arr= showModalDialog(dialog, "visual", feature);
  if(arr==null) return ; 

  doFormatF(arr)
  	  
}



function FMousedown()
{
  var objF=document.frames[fID];
  if(objF && objF.event && objF.event.button==2 && POPWIN==1) formatDialogF();

  var el = objF.event.srcElement 
  if(USETABLE) doClick(el)
}





// init all found TEXTAREA in document
function changeAllTextareaToEditors()
{
  var i=0;
  while(document.all.tags('textarea')[i])
   { 
    changetoIframeEditor(document.all.tags('textarea')[i])
	if(++i>0 && !document.all.tags('textarea')[i] ) i=0;
   }

}




// init all found IFRAME in document to Editable
function changeAllIframeToEditors()
{
  var i=0;
  while(document.all.tags('iframe')[i])
  { 
	changetoIframeEditor(document.all.tags('iframe')[i])
	i++
  }

}


// init only IFRAMEs that have "id=argument of initEditors"
// e.g. changeIframeToEditor('id1','id2',...)
function changeIframeToEditor()
{
  for(var j=0;j<arguments.length;j++)
   {
     var i=0;
	 while(document.all.tags('iframe')[i])
	  { 
		if(document.all.tags('iframe')[i].id == arguments[j])
		  { changetoIframeEditor(document.all.tags('iframe')[i]); break; }
	    i++
	  }
   }
}




/////////////////////////////////////////////////////////////////
function controlRows(fid)
{
  var str = "<TR bgColor=#c0c0a0 align=center valign=middle EVENT>\
<TD nowrap style='cursor:hand'>\
<img src='IURL/bold.gif' border=0 alt='Bold' width=23 height=22 onclick='doFormatF(\"Bold\")'>\
<img src='IURL/left.gif' border=0 alt='Left' width=23 height=22 onclick='doFormatF(\"JustifyLeft\")'>\
<img src='IURL/center.gif' border=0 alt='Center' width=23 height=22 onclick='doFormatF(\"JustifyCenter\")'>\
<img src='IURL/right.gif' border=0 alt='Right' width=23 height=22 onclick='doFormatF(\"JustifyRight\")'>\
<img src='IURL/outdent.gif' border=0 alt='Outdent' width=23 height=22 onclick='doFormatF(\"Outdent\")'>\
<img src='IURL/indent.gif' border=0 alt='Indent' width=23 height=22 onclick='doFormatF(\"Indent\")'>\
<img src='IURL/italic.gif' border=0 alt='Italic' width=23 height=22 onclick='doFormatF(\"Italic\")'>\
<img src='IURL/under.gif' border=0 alt='Underline' width=23 height=22 onclick='doFormatF(\"Underline\")'>\
<img src='IURL/strike.gif' border=0 alt='StrikeThrough' width=23 height=22 onclick='doFormatF(\"StrikeThrough\")'>\
<img src='IURL/superscript.gif' border=0 alt='SuperScript' width=23 height=22 onclick='doFormatF(\"SuperScript\")'>\
<img src='IURL/subscript.gif' border=0 alt='SubScript' width=23 height=22 onclick='doFormatF(\"SubScript\")'>\
<img src='IURL/bgcolor.gif' border=0 alt='Background' width=23 height=22 onclick='selectBgColor()'>\
<img src='IURL/fgcolor.gif' border=0 alt='Foreground' width=23 height=22 onclick='selectFgColor()'>\
<img src='IURL/cool.gif' border=0 alt='Emotions' width=23 height=22 onclick='selectEmoticon()'>\
<img src='IURL/wow.gif' border=0 alt='Characters' width=23 height=22 onclick='characters()'>\
<img src='IURL/link.gif' border=0 alt='Create Link' width=23 height=22 onclick='doFormatF(\"CreateLink\")'>\
<img src='IURL/unlink.gif' border=0 alt='Del Link' width=23 height=22 onclick='doFormatF(\"UnLink\")'>\
<img src='IURL/numlist.gif' border=0 alt='OrderedList' width=23 height=22 onclick='doFormatF(\"InsertOrderedList\")'>\
<img src='IURL/bullist.gif' border=0 alt='UnorderedList' width=23 height=22 onclick='doFormatF(\"InsertUnorderedList\")'>\
<img src='IURL/hr.gif' border=0 alt='HR' width=23 height=22 onclick='doFormatF(\"InsertHorizontalRule\")'>\
<img src='IURL/pre.gif' border=0 alt='Pre-Block' width=23 height=22 onclick='doFormatF(\"formatBlock,PRE\")'>\
<img src='IURL/unpre.gif' border=0 alt='Del Pre-Block' width=23 height=22 onclick='doFormatF(\"formatBlock,P\")'>\
<img src='IURL/marquee.gif' border=0 alt='Marquee' width=23 height=22 onclick='doFormatF(\"InsertMarquee\")'>\
<img src='IURL/delformat.gif' border=0 alt='Delete Format' width=23 height=22 onclick='doFormatF(\"RemoveFormat\")'>\
</TD></TR>\
<TR bgColor=#c0c0a0 valign=middle align=center EVENT>\
<TD nowrap style='cursor:hand'>"

if(USETABLE)
{
str += "<img src='IURL/div.gif' border=0 alt='CreateDiv/DivStyle' width=23 height=22 onclick='insertDivLayer()'>\
<img src='IURL/divborder.gif' border=0 alt='DivBorder' width=23 height=22 onclick='editDivBorder()'>\
<img src='IURL/divfilter.gif' border=0 alt='DivFilter' width=23 height=22 onclick='editDivFilter()'>\
\
<img src='IURL/instable.gif' border=0 alt='InsertTable' width=23 height=22 onclick='insertTable()'>\
<img src='IURL/tabprop.gif' border=0 alt='TableProperties' width=23 height=22 onclick='tableProp()'>\
<img src='IURL/cellprop.gif' border=0 alt='CellProperties' width=23 height=22 onclick='cellProp()'>\
<img src='IURL/inscell.gif' border=0 alt='InsertCell' width=23 height=22 onclick='insertCell()'>\
<img src='IURL/delcell.gif' border=0 alt='DeleteCell' width=23 height=22 onclick='deleteCell()'>\
<img src='IURL/insrow.gif' border=0 alt='InsertRow' width=23 height=22 onclick='insertRow()'>\
<img src='IURL/delrow.gif' border=0 alt='DeleteRow' width=23 height=22 onclick='deleteRow()'>\
<img src='IURL/inscol.gif' border=0 alt='InsertCol' width=23 height=22 onclick='insertCol()'>\
<img src='IURL/delcol.gif' border=0 alt='DeleteCol' width=23 height=22 onclick='deleteCol()'>\
<img src='IURL/mrgcell.gif' border=0 alt='IncreaseColSpan' width=23 height=22 onclick='morecolSpan()'>\
<img src='IURL/spltcell.gif' border=0 alt='DecreaseColSpan' width=23 height=22 onclick='lesscolSpan()'>\
<img src='IURL/mrgrow.gif' border=0 alt='IncreaseRowSpan' width=23 height=22 onclick='morerowSpan()'>\
<img src='IURL/spltrow.gif' border=0 alt='DecreaseRowSpan' width=23 height=22 onclick='lessrowSpan()'>"
}

str += "<img src='IURL/all.gif' border=0 alt='SelectAll' width=23 height=22 onclick='selectAll()'>\
<img src='IURL/cut.gif' border=0 alt='Cut' width=23 height=22 onclick='doFormatF(\"Cut\")'>\
<img src='IURL/copy.gif' border=0 alt='Copy' width=23 height=22 onclick='doFormatF(\"Copy\")'>\
<img src='IURL/paste.gif' border=0 alt='Paste' width=23 height=22 onclick='doFormatF(\"Paste\")'>\
<img src='IURL/undo.gif' border=0 alt='Undo' width=23 height=22 onclick='displayHint(\"Please press Ctrl+Z\")'>\
<img src='IURL/redo.gif' border=0 alt='Redo' width=23 height=22 onclick='displayHint(\"Please press Ctrl+Y\")'>\
<img src='IURL/search.gif' border=0 alt='Search/Replace' width=23 height=22 onclick='findText()'>\
<img src='IURL/help.gif' border=0 alt='Help' width=23 height=22 onclick='displayHelp()'>\
<img src='IURL/file.gif' border=0 alt='Open/Save File' width=23 height=22 onclick='FileDialog()'>\
<img src='IURL/chipcard.gif' border=0 alt='Content Recover/Insert-Smartcard-Data' width=23 height=22 onclick='SmartcardData()'>\
</TD></TR>\
\
\
<TR bgColor=#a0a080 valign=middle align=center EVENT>\
<TD nowrap>\
<SELECT name='QBCNTRL1' onchange='doFormatF(\"FontName,\"+this.value)' style='height:22; width:120; background:#a0a080; color:#FFFFFF; cursor:hand'>\
<OPTION value=''>Default Font\
<OPTION value='Arial'>Arial\
<OPTION value='Times New Roman'>Times New Roman\
<OPTION value='Webdings'>Webdings\
</SELECT>\
<SELECT name='QBCNTRL2' onchange='doFormatF(\"formatBlock,\"+this.value)' style='height:22; width:80; background:#a0a080; color:#FFFFFF'>\
<OPTION value=''>Headline\
<OPTION value='H1'>Headline H1\
<OPTION value='H2'>Headline H2\
<OPTION value='H3'>Headline H3\
<OPTION value='H4'>Headline H4\
<OPTION value='H5'>Headline H5\
<OPTION value='H6'>Headline H6\
<OPTION value='P'>No Headline</OPTION>\
</SELECT>\
<SELECT name='QBCNTRL3' onchange='doFormatF(\"FontSize,\"+this.value)' style='height:22; width:55; background:#a0a080; color:#FFFFFF'>\
<OPTION value=3>FSize\
<OPTION value=7>Size=7\
<OPTION value=6>Size=6\
<OPTION value=5>Size=5\
<OPTION value=4>Size=4\
<OPTION value=3>Size=3\
<OPTION value=2>Size=2\
<OPTION value=1>Size=1\
</OPTION>\
</SELECT>"

if(USEFORM)
{
 str += "<SELECT name='QBCNTRL4' onchange=doFormatF(this.value) style='height:22; width:100; background:#a0a080; color:#FFFFFF'>\
<OPTION value=''>Form\
<OPTION value=InsertFieldset>CreateField\
<OPTION value=InsertInputButton>Button\
<OPTION value=InsertInputReset>Resetbutton\
<OPTION value=InsertInputSubmit>Submitbutton\
<OPTION value=InsertInputCheckbox>Checkbox\
<OPTION value=InsertInputRadio>Radiobutton\
<OPTION value=InsertInputText>Text\
<OPTION value=InsertSelectDropdown>Dropdown\
<OPTION value=InsertSelectListbox>Listbox\
<OPTION value=InsertTextArea>TextArea\
<OPTION value=InsertButton>IEButton\
<OPTION value=InsertIFrame>IFrame\
</SELECT>"
}

str += "<INPUT name='QBCNTRL5' value='SwapMode' onclick='swapMode()' type=button style='height:22; width:70; background:#a0a080; border-color:#f0f0c0; color:#FFFFFF'>\
<INPUT name='QBCNTRL6' value='SwapCode' onclick='swapView()' type=button style='height:22; width:70;background:#a0a080; border-color:#f0f0c0; color:#FFFFFF'>\
<INPUT name='QBCNTRL7' value='Upload Insert Image' onclick='doUploadFile()' type=button style='height:22; width:140;background:#a0a080; border-color:#f0f0c0; color:#FFFFFF'>\
<INPUT name='QBCNTRL8' value='Options' onclick='doEditorOptions()' type=button style='height:22; width:65;background:#a0a080; border-color:#f0f0c0; color:#FFFFFF'>\
</TD></TR>"


 var iurl= QBPATH + '/imgedit'
 var event= "onmousedown='fID=\"" + fid +"\"'"
 str = str.replace(/IURL/g, iurl);
 str = str.replace(/EVENT/g, event);
 return str ;
}



function createEditor(id,wi,hi)
{
  if( parseInt(wi) <630) wi=630;
  
  var strx = "<iframe id="+id+ " style='height:" + hi +"; width:"+wi+"'></iframe>"

  var idA= id.split('VDevID')

  strx += "<input name="+idA[1]+" type=hidden></input>"
 
  var str="<TABLE border=1 cellspacing=0 cellpadding=1 width=" + wi + "><tr><td align=center>"
  str += strx + "</td></tr>"
  
  str += controlRows(id);

  str += "</TABLE>" ;

  return str ;

}
/////////////////////////////////////////////////////////////////





function doFormatF(arr)
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()

  var cmd = new Array();
  cmd = arr.split(',')

  if(cmd[0]=='SelectAll') selectAll();
  else if(cmd[0]=='Swap[Text/HTML]') swapMode(); 
  else if(cmd[0]=='Swap[Uni/View]') swapView();
  else if(cmd[0]=='InsertTable') insertTable();
  else if(cmd[0]=='TablePropeties') tableProp();
  else if(cmd[0]=='CellPropeties') cellProp();
  else if(cmd[0]=='InsertLayer') insertDivLayer();
  else if(cmd[0]=='EditLayerBorder') editDivBorder();
  else if(cmd[0]=='EditLayerFilter') editDivFilter();
  else if(cmd[0]=='Emotions') editEmotions(cmd[1],objF);
  else
	{
	  var edit=objF.document; 
	  if(cmd[0]=='formatBlock') edit.execCommand(cmd[0],false,"<"+cmd[1]+">")
	  else if(cmd[0]=='InsertImage' && !cmd[1] )
	   { alert('Please notice:\nThe "Picture Source" in following Dialog must be a URL, not a local address.'); 
	     edit.execCommand(cmd[0],true,"") }
	  else if(cmd[1]) edit.execCommand(cmd[0],false,cmd[1])
	  else edit.execCommand(cmd[0],false)
	}

}



function editEmotions(wrd,obj)
{
  var caret=obj.document.selection.createRange();
  obj.curword=caret.duplicate();
  obj.curword.text= wrd + ' '
}




function swapView()
{
 var objF=document.frames[fID];
 if(!objF){alert('Please click to select the editor');return}
 objF.focus()

 var strx;
 if(format[fID]=="HTML")
 {
  DEFFFACE[fID]= objF.document.body.style.fontFamily
  DEFFSIZE[fID]= objF.document.body.style.fontSize
  DEFCOLOR[fID]= objF.document.body.style.color
  DEFBCOLOR[fID]= objF.document.body.style.backgroundColor
  DEFBIMAGE[fID]= objF.document.body.style.backgroundImage
  DEFBIMAGE[fID]= DEFBIMAGE[fID].substring( DEFBIMAGE[fID].indexOf('(')+1,DEFBIMAGE[fID].indexOf(')') )

  objF.document.body.style.fontFamily="";
  objF.document.body.style.fontSize="12pt"
  objF.document.body.style.fontStyle="normal"
  objF.document.body.style.color="black"
  objF.document.body.style.backgroundColor="#e0e0f0"
  objF.document.body.style.backgroundImage=''
  strx=objF.document.body.innerHTML
  format[fID]="Text"
 }
 else
 {
  strx=objF.document.body.innerText
 }


 if(viewm[fID])
  {
    strx=toUnicode(strx)
    // strx=rewriteUni(strx) ///
  }
 else strx=viewISOCode(strx)

 objF.document.body.innerText=strx
 viewm[fID]=1 - viewm[fID]

}



function swapMode()
{
 var objF=document.frames[fID];
 if(!objF){alert('Please click to select the editor');return}
 objF.focus()

 var MARK= "ViEtDeVtRiCk"
 var selType=objF.document.selection.type

 if(selType!="Control")
 {
   var caret=objF.document.selection.createRange();
   objF.curword=caret.duplicate();
   var selwrd= objF.curword.text
   objF.curword.text = selwrd + MARK;
 }

	 
 if(format[fID]=="HTML")
 {
  DEFFFACE[fID]= objF.document.body.style.fontFamily
  DEFFSIZE[fID]= objF.document.body.style.fontSize
  DEFCOLOR[fID]= objF.document.body.style.color
  DEFBCOLOR[fID]= objF.document.body.style.backgroundColor
  DEFBIMAGE[fID]= objF.document.body.style.backgroundImage
  DEFBIMAGE[fID]= DEFBIMAGE[fID].substring( DEFBIMAGE[fID].indexOf('(')+1,DEFBIMAGE[fID].indexOf(')') )

  objF.document.body.style.fontFamily="";
  objF.document.body.style.fontSize="12pt"
  objF.document.body.style.fontStyle="normal"
  objF.document.body.style.color="black"
  objF.document.body.style.backgroundColor="#e0e0f0"
  objF.document.body.style.backgroundImage=''
  objF.document.body.innerText= objF.document.body.innerHTML
  format[fID]="Text"
 }
 else
 {
  objF.document.body.style.fontFamily= DEFFFACE[fID]
  objF.document.body.style.fontSize= DEFFSIZE[fID]
  objF.document.body.style.color= DEFCOLOR[fID]
  objF.document.body.style.backgroundColor= DEFBCOLOR[fID]
  objF.document.body.style.backgroundImage= "url(" + DEFBIMAGE[fID] + ")"

  objF.document.body.innerHTML= objF.document.body.innerText
  format[fID]="HTML"
  viewm[fID]=1
 }


 if(selType!="Control")
 {
  caret = objF.document.selection.createRange();
  var found= caret.findText(MARK,100000,5) // backward
  if(found==false) 
   found= caret.findText(MARK,100000,4) // foreward

  if(found==false && format[fID]=="HTML") 
   {
     var strx= objF.document.body.innerHTML
	 strx= strx.replace(/ViEtDeVtRiCk/ig,"");
	 objF.document.body.innerHTML= strx
	 alert("You have selected a HTML-Tag.\nCan't find to hi-light it by View-Mode.")
	 return;
   }

  caret.select();
  objF.curword=caret.duplicate();
  objF.curword.text = '' ;  // erase trick selection 

  if(selwrd!="") caret.findText(selwrd,100000,5); // real selection
  caret.select();  caret.scrollIntoView(); 
 }

}



function selectAll()
{ 
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()
  var s=objF.document.body.createTextRange()
  s.execCommand('SelectAll')
}





function doFormatDialog(file,cmd,arg)
{ 
  var urlx= QBPATH + '/' + file

  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}

  var arr=showModalDialog(urlx, arg, "font-family:Verdana;font-size:12;dialogWidth:30em;dialogHeight:34em; edge:sunken;help:no;status:no");
  if(arr !=null) doFormatF(cmd+','+arr)
}


function selectEmoticon()
{ 
  doFormatDialog('emoticon.html','InsertImage',QBPATH)
}

function selectBgColor()
{ 
  doFormatDialog('selcolor.html','BackColor','')
}


function selectFgColor()
{ 
  doFormatDialog('selcolor.html','ForeColor','')
}


function characters()
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus();

  var sel = objF.document.selection;
  if(sel.type=="Control") return 

  var urlx= QBPATH + '/selchar.html'
  var arr=showModalDialog(urlx, '', "font-family:Verdana;font-size:12;dialogWidth:30em;dialogHeight:34em; edge:sunken;help:no;status:no");
  if(arr==null) return

  var arrA = arr.split(';QuIcKbUiLd;')

  var strx= "<FONT FACE='" + arrA[0] + "'>" + arrA[1] + "</FONT>"

  var Range = sel.createRange();
  if(!Range.duplicate) return;
  Range.pasteHTML(strx);

}



function doUploadFile()
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()

  var urlx= QBPATH + '/upload.php'
  var twidth= 0.8*screen.width, theight=500;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"upload","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=yes, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}


function doEditorOptions()
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()

  var urlx= QBPATH + '/options.html'
  var twidth= 0.8*screen.width, theight=190;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"options","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}


function findText()
{
  if(!fID && !TXTOBJ){alert('Please click to select the editor');return}
  if(fID) document.frames[fID].focus()
  else TXTOBJ.focus()

  var urlx= QBPATH + '/dfindtext.html'
  var newWin=window.open(urlx,"find","toolbar=no, width=350px,height=220px,directories=no,status=no,scrollbars=yes,resizable=yes,menubar=no;scroll=no")
  newWin.moveTo(screen.width-500,50);
  newWin.focus()
}



function findTextHotKey(forward)
{
  if(!fID && !TXTOBJ){alert('Please click to select the editor');return}
  if(fID) document.frames[fID].focus()
  else TXTOBJ.focus()

  var rng = objF.document.selection.createRange();
  objF.curword=rng.duplicate();

  if(!FWORD && !objF.curword.text ){ alert('No find string definition'); return }
  else if(objF.curword.text)FWORD= objF.curword.text

  if(objF.curword.text)
   {
     if(forward==1) rng.moveEnd("character", -1 );  
	 else rng.moveStart("character", 1);  
   }

  if(rng.findText(FWORD,100000,FLAGS+forward)==true)
   { rng.select();  rng.scrollIntoView(); return }

  alert("Finish")
  return

}


function displayHint(strx)
{
  document.frames[fID].focus();
  alert(strx);
}



function displayHelp()
{
  var urlx= QBPATH + '/edithelp.html'
  var newWin=window.open(urlx,"help","toolbar=no, width=600px,height=400px,directories=no,status=no,scrollbars=yes,resizable=yes,menubar=no;scroll=no")
  newWin.focus()
}


function FileDialog()
{
  var urlx= QBPATH + '/filedialog.html'
  var twidth= 0.5*screen.width, theight=100;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"fdialog","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()
}



function SmartcardData()
{
  if(!document.forms || document.forms.length==0) return ;

  var fidx, oForm, el , objF=null ;
  var fIDx, linex, lidx=0;
  for(fidx=0; fidx<document.forms.length; fidx++)
	{
	 oForm= document.forms[fidx]
	 for(var i=0; i<oForm.elements.length; i++)
	  {
       el= oForm.elements[i]
       if(el.type!='hidden') continue
        
       fIDx= fidx +'VDevID'+ el.name
	   if(document.frames[fIDx]){ objF=document.frames[fIDx]; break;}
     } // end for i
	} // end for fidx

  if(!objF) return;

  objF.document.body.innerText=''
  var s=objF.document.body.createTextRange()
  s.execCommand('Paste')
  var cbstr= objF.document.body.innerText
  objF.document.body.innerText=''

  var cbArr= cbstr.split(SYMBOLE);
  for(fidx=0; fidx<document.forms.length; fidx++)
	{
	 oForm= document.forms[fidx]
	 for(var i=0; i<oForm.elements.length; i++, linex='')
	  {
       el= oForm.elements[i]
       if(el.type!='text' && el.type!='textarea' && el.type!='hidden') continue
    
	   linex= cbArr[lidx++];
    
       fIDx= fidx +'VDevID'+ el.name
	   if(el.type=='hidden' && document.frames[fIDx] && linex) initDefaultOptions2(linex,fIDx)
	   else if(el.type!='hidden') el.value= linex;
     } // end for i
	} // end for fidx
  
}





function initDefaultOptions0(fID)
{
   setTimeout("document.frames['"+fID+"'].document.body.style.fontFamily='"+DFFACE+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.fontSize='"+DFSIZE+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.color='"+DCOLOR+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.backgroundColor='"+DBGCOL+"'",200)
   setTimeout("document.frames['"+fID+"'].document.body.style.backgroundImage='url("+DBGIMG+")'",200)
   setTimeout("DEFCSS['"+fID+"']=document.frames['"+fID+"'].document.createStyleSheet('"+DCSS+"')",200)
   DEFFFACE[fID]= DFFACE;
   DEFFSIZE[fID]= DFSIZE;
   DEFCOLOR[fID]= DCOLOR;
   DEFBCOLOR[fID]= DBGCOL;
   DEFBIMAGE[fID]= DBGIMG;
}






function DefaultOptions(linex)
{
  var retArr= new Array('','','','','','','');
  var tempx, strx, objx, idx ;

  // DEFAULT DIV
  var idx= linex.indexOf('ViEtDeVdIvId')
  if(idx>=0) 
	{
	  strx= linex.substring(linex.indexOf('ViEtDeVdIvId style="')+20,linex.indexOf('">'))

      var atrA= strx.split("; ")
	  for(var i=0; i<atrA.length; i++)
		{
		  tempx= atrA[i].split(':')
		  switch(tempx[0])
		   {
			case "FONT-FAMILY": retArr[0]= tempx[1]; break;
			case "FONT-SIZE": retArr[1]= tempx[1]; break;
			case "BACKGROUND-COLOR": retArr[2]= tempx[1]; break;
			case "COLOR": retArr[3]= tempx[1]; break;
			case "BACKGROUND-IMAGE": if(tempx[2]) tempx[1] += ':'+ tempx[2];
									 retArr[4]= tempx[1].substring(tempx[1].indexOf('url(')+4,tempx[1].indexOf(')') ); 
									 break;
		   }
	    }
      linex= linex.substring(linex.indexOf('>')+1,linex.lastIndexOf('</DIV>'))
    }


   // EXT STYLE
   idx= linex.indexOf('<style>@import url("')
   if( idx>=0 )
    {
	   var strx= linex.substring(idx+20, linex.indexOf('")'))
       retArr[5]= strx
	   linex= linex.substring(0,idx)
    }

   retArr[6]= linex
	   
   return retArr

}





function initDefaultOptions1(linex,fID)
{
  var retArr= new Array();

  retArr= DefaultOptions(linex);

  setTimeout("document.frames['"+fID+"'].document.body.style.fontFamily='"+retArr[0]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.fontSize='"+retArr[1]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.backgroundColor='"+retArr[2]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.color='"+retArr[3]+"'",200)
  setTimeout("document.frames['"+fID+"'].document.body.style.backgroundImage='url("+retArr[4]+")'",200)
  setTimeout("DEFCSS['"+fID+"']=document.frames['"+fID+"'].document.createStyleSheet('"+retArr[5]+"')",200)
  DEFFFACE[fID]= retArr[0];
  DEFFSIZE[fID]= retArr[1];
  DEFCOLOR[fID]= retArr[3];
  DEFBCOLOR[fID]= retArr[2];
  DEFBIMAGE[fID]= retArr[4];

  return retArr[6]

}




function initDefaultOptions2(linex,fIDx)
{
  var oFrame= document.frames[fIDx]
  var oStyle= oFrame.document.body.style

   // remove old Style
  var oSS= DEFCSS[fIDx]
  if(oSS) for(var i=0; i<oSS.rules.length; i++) oSS.removeRule(i);
  DEFCSS[fIDx]= null 

  var retArr= new Array();

  retArr= DefaultOptions(linex);

  oStyle.fontFamily=retArr[0]
  oStyle.fontSize=retArr[1]
  oStyle.color=retArr[3]
  oStyle.backgroundColor=retArr[2]
  oStyle.backgroundImage= "url("+retArr[4]+")"
  DEFCSS[fIDx]= oFrame.document.createStyleSheet(retArr[5])
  DEFFFACE[fIDx]= retArr[0];
  DEFFSIZE[fIDx]= retArr[1];
  DEFBCOLOR[fIDx]= retArr[2];
  DEFCOLOR[fIDx]= retArr[3];
  DEFBIMAGE[fIDx]= retArr[4];

  oFrame.document.body.innerHTML= retArr[6];

}




function insertLink(linkurl)
{
  var objF=document.frames[fID];
  if(!objF && !TXTOBJ){alert('Please click a text element');return}


  if( objF )
  {
	objF.focus();
    var sel = objF.document.selection;
	var strx= "<A href='"+linkurl+"' target=nwin>" + linkurl + "</A>"

	var Range = sel.createRange();
	if(!Range.duplicate) return;
	Range.pasteHTML(strx);
  }
  else 
  {
	TXTOBJ.focus();
    var caret= TXTOBJ.document.selection.createRange()
	TXTOBJ.curword=caret.duplicate();
	var strx= "<A href='"+linkurl+"' target=nwin>" + linkurl + "</A>,"
	doFormat(strx,caret)
  }


}





function insertDivLayer()
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()
  
  var sel = objF.document.selection;
  if (sel==null) return

  var Range = sel.createRange();
  var wrd='' ;

  if(sel.type!="Control")
  {
  	if(!Range.duplicate) return;
  	objF.curword=Range.duplicate();
  	wrd= objF.curword.text;
	if(wrd=='') wrd="I'm a DIV-Layer. Select me and click the button once more to change properties. Or doubleclick me to change the text."
	var arr= "<DIV style='position:relative; width:150px; height:100px; font-family:Arial; font-size:12px; background-color:#f0fdd0; border:1 solid'>"+ wrd + "</DIV>" ;
	Range.pasteHTML(arr);
	return
  }  

  if(Range(0).tagName!='DIV') return

  var urlx= QBPATH + '/divstyle.html'

  var twidth= 0.8*screen.width, theight=190;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divstyle","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}





function editDivBorder()
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()
  
  var sel = objF.document.selection;
  if (sel==null || sel.type!='Control') {alert('Please click once to select a div-layer');return} 

  var Range = sel.createRange();
  if(Range(0).tagName!='DIV') return

  var urlx= QBPATH + '/divborder.html'

  var twidth= 0.8*screen.width, theight=215;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divborder","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}





function editDivFilter()
{
  var objF=document.frames[fID];
  if(!objF){alert('Please click to select the editor');return}
  objF.focus()

  var sel = objF.document.selection;
  if (sel==null || sel.type!='Control') {alert('Please click once to select a div-layer');return} 

  var Range = sel.createRange();
  if(Range(0).tagName!='DIV') return

  var urlx= QBPATH + '/divfilter.html'

  var twidth= 0.8*screen.width, theight=210;
  var tposx= (screen.width- twidth)/2
  var tposy= screen.height- theight - 55
  	    	  
  var newWin1=window.open(urlx,"divfilter","toolbar=no,width="+ twidth+",height="+ theight+ ",directories=no,status=no,scrollbars=yes,resizable=no, menubar=no")
  newWin1.moveTo(tposx,tposy);
  newWin1.focus()

}




/**** From Html-Code to UNICODE ***********/
function  viewISOCode(str1)
{
 var c0, str2='', strx='', idx;
 
 idx=str1.indexOf('&#')
 if(idx<0) return str1
 var i=0
 while (i<str1.length)
  {
    c0=str1.substring(i,i+2)
    i++
    if(c0 !='&#') continue
    strx  +=str1.substring(0,i-1)
    str1=str1.substring(i-1,str1.length)
    idx=str1.indexOf(';')
    if(idx <0) break;
    str2=str1.substring(2,idx)
    str2++;str2--
    str1=str1.substring(idx+1,str1.length)
    strx +=String.fromCharCode(str2)
    i=0
  }
 return strx+str1;
}





function actualize()
{
  var i=0;
  while(document.all.tags('iframe')[i])
  { 
	setHiddenValue(document.all.tags('iframe')[i].id) 
	i++
  }
}



function setHiddenValue(fid)
{ 
 if(!fid) return

 var strx= editorContents(fid)
 var idA= fid.split('VDevID')
 if(!idA[0]) return;

 var fobj= document.forms[idA[0]]
 if(!fobj) return;

 eval("fobj['" +idA[1] + "'].value='"+ strx +"'")

}	



function editorContents(fid)
{
  var objF=document.frames[fid]
  if(!objF)return

  var strx;
  if(format[fid]=="HTML") strx=objF.document.body.innerHTML
  else strx=objF.document.body.innerText

  strx = strx.replace(/\r/g, ""); 
  strx = strx.replace(/\n/g, "&#13;"); 
  strx = strx.replace(/\'/g,"&#39;")

  // Security
  if(SECURE==1)
	{
	  strx = strx.replace(/<meta/ig, "< meta"); 
	  strx = strx.replace(/&lt;meta/ig, "&lt; meta"); 

	  strx = strx.replace(/<script/ig, "< script"); 
	  strx = strx.replace(/&lt;script/ig, "&lt; script"); 
	  strx = strx.replace(/<\/script/ig, "< /script"); 
	  strx = strx.replace(/&lt;\/script/ig, "&lt; /script"); 

	  strx = strx.replace(/<iframe/ig, "< iframe"); 
	  strx = strx.replace(/&lt;iframe/ig, "&lt; iframe"); 
	  strx = strx.replace(/<\/iframe/ig, "< /iframe"); 
	  strx = strx.replace(/&lt;\/iframe/ig, "&lt; /iframe"); 

	  strx = strx.replace(/<object/ig, "< object"); 
	  strx = strx.replace(/&lt;object/ig, "&lt; object"); 
	  strx = strx.replace(/<\/object/ig, "< /object"); 
	  strx = strx.replace(/&lt;\/object/ig, "&lt; /object"); 

	  strx = strx.replace(/<applet/ig, "< applet"); 
	  strx = strx.replace(/&lt;applet/ig, "&lt; applet"); 
	  strx = strx.replace(/<\/applet/ig, "< /applet"); 
	  strx = strx.replace(/&lt;\/applet/ig, "&lt; /applet"); 

/********
	  strx = strx.replace(/<form/ig, "&lt; form"); 
	  strx = strx.replace(/&lt;form/ig, "&lt; form"); 
	  strx = strx.replace(/<\/form/ig, "&lt; /form"); 
	  strx = strx.replace(/&lt;\/form/ig, "&lt; /form"); 
	  strx = strx.replace(/<input/ig, "&lt; input"); 
	  strx = strx.replace(/&lt;input/ig, "&lt; input"); 
	  strx = strx.replace(/<\/input/ig, "&lt; /input"); 
	  strx = strx.replace(/&lt;\/input/ig, "&lt; /input"); 
	  strx = strx.replace(/<textarea/ig, "&lt; textarea"); 
	  strx = strx.replace(/&lt;textarea/ig, "&lt; textarea"); 
	  strx = strx.replace(/<\/textarea/ig, "&lt; /textarea"); 
	  strx = strx.replace(/&lt;\/textarea/ig, "&lt; /textarea"); 
	  strx = strx.replace(/<button/ig, "&lt; button"); 
	  strx = strx.replace(/&lt;button/ig, "&lt; button"); 
	  strx = strx.replace(/<\/button/ig, "&lt; /button"); 
	  strx = strx.replace(/&lt;\/button/ig, "&lt; /button"); 
********/

	  strx = strx.replace(/ on/ig, " o&shy;n"); 
	  strx = strx.replace(/script:/ig, "script&shy;:"); 
    }


  var idx= strx.indexOf('ViEtDeVdIvId')
  if( idx>=0 ) strx= strx.substring(strx.indexOf('>')+1,strx.lastIndexOf('</DIV>'))

  idx= strx.indexOf('<style>@import url(')
  if( idx>=0 ) strx= strx.substring(0,idx)
  if(DEFCSS[fid] && DEFCSS[fid].href) strx += '<style>@import url("'+DEFCSS[fid].href+'");</style>';


  var defdiv="" ;
  if(DEFFFACE[fid]) defdiv += "; FONT-FAMILY:"+ DEFFFACE[fid] 
  if(DEFFSIZE[fid]) defdiv += "; FONT-SIZE:"+ DEFFSIZE[fid]
  if(DEFCOLOR[fid]) defdiv += "; COLOR:"+ DEFCOLOR[fid]
  if(DEFBCOLOR[fid])defdiv += "; BACKGROUND-COLOR:"+ DEFBCOLOR[fid]
  if(DEFBIMAGE[fid])
	{
     DEFBIMAGE[fid]= DEFBIMAGE[fid].replace(/\\/g,"/"); 
	 defdiv += "; BACKGROUND-IMAGE:url("+ DEFBIMAGE[fid]+")"
    }
  if(defdiv)
	{
	 defdiv = '<DIV id=ViEtDeVdIvId style="POSTION:Relative' + defdiv + '">'
	 strx = defdiv + strx + "</DIV>"
	}

  return strx
}





function doMouseup()
{
 var el=event.srcElement 
 if(!el.type) return
 if(el.type!='text'&&el.type!='textarea'&&el.type!='password'&&el.type!='file')
  {
	if(!el.name || el.name.substring(0,7)!='QBCNTRL')
	 { 
	   actualize(); 
	   if(el.type != 'select-one' && el.type != 'select-multiple') el.focus(); 
	 }
    return
  }

 var visual=''
 if(el.type=='textarea' && VISUAL==0) visual=confirm("Use Visual Mode ?")
 	 
 if(visual) changetoIframeEditor(el);

}




function doMousedown()
{
  var el=event.srcElement 
  if( el.type=='text' || el.type=='textarea')
   {
	 TXTOBJ=el; fID='' 
     if(event.button==2 && POPWIN==1) formatDialog(el)
   }
}



function doKeydown()
{
  var ctrl= event.ctrlKey
  if(!ctrl) return;

  var el=event.srcElement 
  if(el.type!='text' && el.type!='textarea') return
  TXTOBJ=el; fID='';

  var key= event.keyCode
  if(ctrl && key==71) { findText(); return false }  // ctrl+G search
  else if(ctrl && key==75){ findTextHotKey(0); return false } // ctrl+K  search forward
  else if(ctrl && key==74){ findTextHotKey(1); return false } // ctrl+J  search backward 
  else if(ctrl && key==83){ SmartcardData(); return false } // ctrl+S content rewrite
 
}



function doFormat(arr,caret)
{
  var wrd=TXTOBJ.curword.text

  var cmd = new Array();
  cmd = arr.split(',')

  if(!cmd[0] || cmd[0]=='Swap[Text/HTML]' || cmd[0]=='Swap[Uni/View]' ) return 
  if(cmd[0]=='SelectAll') { TXTOBJ.focus(); TXTOBJ.select(); return }
  if(cmd[0]=='Cut') { caret.execCommand("Cut"); return }
  if(cmd[0]=='Copy') { caret.execCommand("Copy"); return }
  if(cmd[0]=='Paste') { caret.execCommand("Paste"); return }

  TXTOBJ.curword=caret.duplicate();
  TXTOBJ.curword.text= cmd[0]+wrd+cmd[1]
}


function formatDialog()
{
  TXTOBJ.focus();
  var caret=TXTOBJ.document.selection.createRange()
  TXTOBJ.curword=caret.duplicate();

  var y = screen.height -parseInt('27em')*14 - 30 
  var feature = "font-family:Arial;font-size:10pt;dialogWidth:30em;dialogHeight:27em;dialogTop:"+y
      feature+= ";edge:sunken;help:no;status:no"

  var dialog= QBPATH+'/dialog.html'
  var arr= showModalDialog(dialog, "", feature);
  if(arr==null) return ;

  doFormat(arr,caret)

}



function  toUnicode(str1)
{
  var code, str2 , j=0;
  var len
  while(j<2)
   {
	len=str1.length
	str2=''
	for(var i=0;i<len;i++) 
	 {
      code=str1.charCodeAt(i);
      if(code<128) continue;
      str2 +=str1.substring(0,i) + '&#' + code + ';'
      str1=str1.substring(i+1,str1.length)
      len=str1.length
      i=0
     }
    str1=str2+str1
    j++;
   }
  return str1;
}



if(USETABLE) document.writeln('<script src="'+QBPATH+'/tabedit.js"></script>');


// VISUAL=0 : Textarea to Editor after confirmation

// VISUAL=1 : all Textarea to Editor
if(VISUAL==1) changeAllTextareaToEditors();

// VISUAL=2 : change only specific textarea
if(VISUAL==2) changetoIframeEditor(document.forms[xxx].yyy); // please replace xxx=formIndex and yyy=textareaName

// VISUAL=3 : all Iframe to Editor
if(VISUAL==3) changeAllIframeToEditors();

// VISUAL=4 : some specific iframes 
if(VISUAL==4) changeIframeToEditor('contents1','contents'); //please replace contents.. = fid

// VISUAL>4 : no Visual-Editor, only use Rightmouse-Control


/******************** NOTICE *********************************
Instead of those function call, you can also set at bottom of your HTML-file like below.
it's flexibler.
<script>
VISUAL=-1
// and one of below Lines
changeAllTextareaToEditors();
changetoIframeEditor(document.forms[xxx].yyy);
changeIframeToEditor('contents1','contents');
changeAllIframeToEditors();
</script>
**************************************************************/