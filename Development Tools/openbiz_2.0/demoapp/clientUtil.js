 /*
   Openbiz client utility file includes
   @author rockys swen
 */
 
 // ****** change the bin path. ******
 var binPath = "/demoapp/bin/";
 var bizsrvr = binPath+"controller.php";
 // **********************************
 
 var RPC_DEBUG = false;
 
 var objectArray = new Array();
 var onElement = null;
 
 function SetOnElement(elmName)
 {
   onElement = elmName;
 }
 
 function NewObject(objname, classname)
 {
   //alert(objname);
   if (objectArray[objname])
      return;
   else
   {
     if (!classname) return;
     try 
     {
       var newobj  = eval("new "+classname+"('"+objname+"')");
       if (newobj)
         objectArray[objname] = newobj;
     }
     catch(e) {}
   }
 } 
 function GetObject(objname)
 {
   if (objectArray[objname])
      return objectArray[objname];
   else
      return null;
 }
 
 function GoToView(view, rule, loadPageTarget)
 {
   URL = bizsrvr+"?view="+view+"&form="+"&rule="+rule;
   LoadPage(URL, loadPageTarget);
 }
 
 function DrillDownToView(view, rule, loadPageTarget)
 {
   URL = bizsrvr+"?view="+view+"&form="+"&rule="+rule;   // plus a history flag. Is it usefule in 2.0?
   LoadPage(URL, loadPageTarget);
 }
 
 function LoadPage(URL, targetFrame)
 {
    if (!targetFrame)
   {
     window.location = URL;
   }
   else
   {
    tgtFrm = FindFrame(targetFrame);
    if (tgtFrm)
      tgtFrm.location = URL;   // traverse all frames
   }
 }
 
 // obj_method_params as format "obj.method('string',value)"
 function CallFunction(obj_method_params, isLoadPage, loadPageTarget, isPopup)
 {
   //alert (obj_method_params + "," + isLoadPage + "," + loadPageTarget + "," + isPopup);
   
   // find the first "("
   var pos0 = obj_method_params.indexOf("(");
   var obj_method = obj_method_params.substring (0,pos0);
   
   pos0 = obj_method.lastIndexOf(".");
   // parse object name
   var obj = "NULL";
   var attachData= null;
   if (pos0>0)
      obj = obj_method.substring(0,pos0);
     
   // parse method/function name
   var pos1 = obj_method_params.indexOf("(");
   if (pos1>pos0)
   {
      var method = obj_method_params.substring(pos0+1,pos1);
      var pos2 = obj_method_params.indexOf(")");
      if (pos1>pos0)
      {
         // get parameters
         var params = obj_method_params.substring(pos1+1,pos2);
         var params_array = Array();
         if (params) 
            params_array = params.split(",");
         var paramArray = new Array(2+params_array.length);
         paramArray[0]=obj;
         paramArray[1]=method;
         for(i=0;i<params_array.length;i++)
            paramArray[i+2]=params_array[i];

         // try to call client object function
         var client_obj = GetObject(obj);
         if (client_obj)
         {
            client_obj.CallFunction(obj,method,params_array,isLoadPage,loadPageTarget,isPopup);
            return;
         } 
         
         formData = CollectData(obj);
         
         if (isLoadPage)
         {
            if (!loadPageTarget)
               jsrsLoad(bizsrvr, "Invoke", paramArray, formData);
            else
               jsrsLoad(bizsrvr, "Invoke", paramArray, formData, loadPageTarget);
            //document.body.style.cursor = "default";
            return;
         }
         if (isPopup)
         {
            jsrsLoadPopup(bizsrvr, "Invoke", paramArray, formData);
            return;
         }
         
         XmlHttpRPC(bizsrvr, CallbackFunction, "RPCInvoke", paramArray, formData);
      }
    }
 }
 
function dummy_callback(returval) {}
 
function CallbackFunction(returval) 
 {
   document.body.style.cursor = "default";
   // extract an item from type:value string
   //var tmp = ExtractItem(returval, "PARENT", 0);
   //if (!tmp) return;
   //var prtname = tmp[0];
   //alert("callback"+returval);
   var tmp = new Array(2);
 for (i=0;i<10;i++)
 {
   tmp = ExtractItem(returval, "TARGET", tmp[1]);
   if (!tmp) return;
   var tgtname = tmp[0];
   tmp = ExtractItem(returval, "CONTENT", tmp[1]);
   if (!tmp) return;
   var content = tmp[0];
   
   if (tgtname == "ERROR") {
      popupErrorText(content);
      return;
   }
   if (tgtname == "POPUP") {
      popupWindow(content,600,500);
      return;
   }
   if (tgtname == "FUNCTION") {
      eval(content);
      return;
   }
   
   // try to call client object function
   var client_obj = GetObject(tgtname);
   if (client_obj)
   {
      client_obj.CallbackFunction(content);
      CallbackFunction(returval.substring(tmp[1], returval.length));
      return;
   }
   else if (window.opener)     // for popup window
   {
      var client_obj = window.opener.window.GetObject(tgtname);
      if (client_obj)
      {
         client_obj.CallbackFunction(content);
         CallbackFunction(returval.substring(tmp[1], returval.length));
         self.close();
         return;
      }
   }
   else     // see if other frames have the target
   {
      for (i=0; i<top.frames.length; i++)
      {
         var client_obj = top.frames[i].window.GetObject(tgtname);
         if (client_obj)
         {
            client_obj.CallbackFunction(content);
            CallbackFunction(returval.substring(tmp[1], returval.length));
            return;
         }
      }
   }
   
   // if no client object, default handle the return content
   //var containerName = this.m_Name+"_container";
   var containerName = tgtname;
   var dt = document.getElementById(containerName);
   if (dt)
      dt.innerHTML = content;
   else
      alert("Cannot find html object with name as "+containerName);
 }
}
 /*
 var str = "TARGET:7:LCOrder;CONTENT:13:<html></html>;";
 var r = ExtractItem(str, "TARGET", 0);
 alert(r);
 var r = ExtractItem(str, "CONTENT", r[1]);
 alert(r);
 */
 function ExtractItem(str, type, start)
 {
   var pos0 = str.indexOf(type, start);
   if (pos0>=0)
   {
      pos0 += type.length+1;
      pos1 = str.indexOf(":", pos0);
      if (pos1>pos0)
      {  
         len = parseInt(str.substring(pos0, pos1+1),10);
         if (len>0)
         {
            strval = str.substring(pos1+1, pos1+1+len);
            var tmparray = Array(2);
            tmparray[0] = strval;
            tmparray[1] = pos1+1+len+1;
            return tmparray;
         }
      }
   }
   return null;
 }
 
 function CollectData(formName)
 {
   var myform = document.getElementById(formName);
   if (!myform)
   {
      //alert("Cannot find the form with name "+formName);
      return null;
   }
   var rtdata = "";
   // need add multiple selection support


   if (myform.elements.length > 0)
      rtdata += GetCtrlData(myform.elements[0],false);
   for (e=1;e<myform.elements.length;e++)
   {
      ctrlData = GetCtrlData(myform.elements[e],false);
      if (ctrlData)
         rtdata += "^-^-^" + ctrlData;
   }

   if (onElement) {  // get onfocus element
      rtdata += "^-^-^" + "__this=" + onElement;
      onElement = null;
   }
   return rtdata;
 }
function GetCtrlData(Ctrl, valueOnly)
{
   //alert(Ctrl.name+","+Ctrl.value+","+Ctrl.type+","+Ctrl.checked);
   var strTemp = "";
   if (!valueOnly) strTemp += Ctrl.name+"=";
   if (Ctrl.type == "checkbox") {
      if (Ctrl.checked) strTemp += Ctrl.value;
   }
   else if (Ctrl.type == "radio") {
      if (Ctrl.checked) strTemp += Ctrl.value;
      else return null;
   }
   else
      strTemp += Ctrl.value;
   return strTemp;
   /*
   if (Ctrl.length==1)
      return Ctrl.value;
   for(var i = 0;i < Ctrl.length;i++){
      if(Ctrl.options[i].selected == true)
         strTemp += Ctrl.options[i].value+"#";
   }*/
   // return data with format CTRLDATA:DataLength:DataString
   //return "CTRLDATA:"+strTemp.length+":"+strTemp;
}
function jbForm(name) 
{
 this.m_Name = name;

 //set properties
 this.m_SelectedRow = 1;
 this.m_HasSubCtrls = 0;
 this.m_SortColumn = null;
 this.m_ReverseSort = 0;

 //set methods
 this.CallFunction = form_CallFunction;
 this.CallbackFunction = form_CallbackFunction;
 this.Show = form_Show;
}

function form_CallFunction(obj, method, params_array, isLoadPage, loadPageTarget, isPopup)
{
   //alert("form_CallFunction("+obj+"."+method+"("+params_array+")).  SelectedRow="+this.m_SelectedRow)
   
   if (method == "SelectRecord")
   {
      var index = params_array[0];
      var clntOnly = params_array[1];  if(!clntOnly)  clntOnly=0;
      var highlight = params_array[2]; if(!highlight) hightlight=1;
      FocusOn(obj+"_data_"+index);
      this.m_SelectedRow = index;
      if (clntOnly==1) return;
   }
   if (method == "SortRecord")
   {
      var sort_col = params_array[0];
      if (this.m_SortColumn == sort_col)
         this.m_ReverseSort = 1 - this.m_ReverseSort;
      else
         this.m_ReverseSort = 0;
      this.m_SortColumn = sort_col;
      params_array[0] = sort_col+","+this.m_ReverseSort;
   }
   
   if (method == "DeleteRecord")
   {
      if (!confirm("Are you sure you want to delete this record?")) 
         return; 
   }
   
   formData = CollectData(this.m_Name); 
   formData += "^-^-^" + "__SelectedRow=" + this.m_SelectedRow;   // append selectedrow to formdata

   var paramArray = new Array(2+params_array.length);
   paramArray[0] = obj;
   paramArray[1] = method;
   for(i=0;i<params_array.length;i++)  paramArray[2+i] = params_array[i];
/*
   if (method == "ShowSelectForm")
   {
      jsrsLoadPopup(bizsrvr, "Invoke", Array(obj,method,params_array), formData);
      return;
   }
*/
   if (isLoadPage)
   {
      if (!loadPageTarget)
         jsrsLoad(bizsrvr, "Invoke", paramArray, formData);
      else
         jsrsLoad(bizsrvr, "Invoke", paramArray, formData, loadPageTarget);
      document.body.style.cursor = "default";
      return;
   }
   if (isPopup)
   {
      jsrsLoadPopup(bizsrvr, "Invoke", paramArray, formData);
      return;
   }

   XmlHttpRPC(bizsrvr, CallbackFunction, "RPCInvoke", paramArray, formData);
}
function form_CallbackFunction(retContent)
{
   this.Show(retContent);
   document.body.style.cursor = "default";
}
function form_Show(retContent)
{
   if (retContent.indexOf("UPD_FLDS")==0)
   {
      var myform = document.getElementById(this.m_Name);
      if (!myform)
      {
         alert("Cannot find the form with name "+formName);
         return;
      }
      pos0=0; pos1=0;
      while(1)
      {
         fld=""; val="";
         pos0 = retContent.indexOf("[", pos1);
         if (pos0<0) break;
         pos1 = retContent.indexOf("]", pos0);
         if (pos0>0 && pos1>pos0)
            fld = retContent.substring(pos0+1,pos1);
         pos0 = retContent.indexOf("<", pos1);
         if (pos0<0) break;
         pos1 = retContent.indexOf(">", pos0);
         if (pos0>0 && pos1>pos0)
            val = retContent.substring(pos0+1,pos1);
         form_fld = myform.elements[fld];
         if (form_fld)  form_fld.value = val;
      }
   }
   else {
      var containerName = this.m_Name+"_container";
      var dt = document.getElementById(containerName);
      if (dt) {
         dt.innerHTML = retContent;
         var tbody = document.getElementById(this.m_Name+"_tbody");
         if (tbody) {
            var selrow = tbody.getAttribute("SelectedRow");
            if (selrow)
               this.m_SelectedRow = selrow;
         }
      }
      else
         alert("Cannot find html object with name as "+containerName);  
   }
}

var browserType = BrowserSniff();
function BrowserSniff(){
  if (document.layers) return "NS";
  if (document.all) return "IE";
  if (document.getElementById) return "MOZ";
  return "OTHER";
}

// RPC call using XMLHTTP
function XmlHttpRPC(rspage, callback, func, parms, formdata)
{
   var XmlHttp;
   if (browserType == "IE") {
      
      try { XmlHttp = new ActiveXObject("Msxml2.XMLHTTP"); }
      catch (e) { alert(e); }
   }
   //else if (browserType == "MOZ")
   else if (window.XMLHttpRequest)
   {
      try { XmlHttp = new XMLHttpRequest(); }
      catch (e) { alert(e);}
   }
   if (!XmlHttp) {
      alert("Your browser doesn't support XMLHttpRequest. The request is not sent successfully.")
      return;
   }

   try {
      XmlHttp.open("POST", rspage, false);
   }
   catch (e) { 
      alert("Can't open http connection due to the reason:\n"+e); 
      XmlHttp.abort();
      return;
   }

   // for ie compatability
   //if(parms.length>3)
   //   XmlHttp.setRequestHeader('Content-Type', 'text/html');
   //else
      XmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');   // _POST[]
   
   var msg = "RPC=1&";

   if (func != null){
    msg += "F=" + func; //escape(func);    //URL += "&F=" + escape(func);
   
    if (parms != null) {
      if (typeof(parms) == "string"){
        // single parameter
        msg += "&P0=[" + jescape(parms+'') + "]";
      } else {
        // assume parms is array of strings
        for( var i=0; i < parms.length; i++ ){
          msg += "&P" + i + "=[" + jescape(parms[i]+'') + "]";
        }
      } // parm type
    } // parms
   } // func
   
   if (formdata != null) {
      msg += "&__FormData=[" + jescape(formdata+'') + "]";
   }

   XmlHttp.send(msg);
   
   //if (XmlHttp.responseText.indexOf("Fatal error") > 0 || XmlHttp.responseText.indexOf("User error") > 0 )
   //   popupWindow(XmlHttp.responseText, 600, 500);
   
   if (XmlHttp.status != 200) {
      alert("There was a problem with the request.");
      return;
   }
   
   if (RPC_DEBUG)
      debugWindow(XmlHttp.responseText);
   
   callback(XmlHttp.responseText);
}

function jescape(str)
{
   tmp = str.replace(/\+/g, '%2B');
   return tmp;
}

// the following functions are added for page refresh function call
// todo: remove the formdata from the input
// todo: change to submit to post data to server
function jsrsLoad( rsPage, func, parms, formdata, targetFrm )
{
  // build URL to call
  var URL = rsPage;

  // func and parms are optional
  if (func != null) {
    URL += "?F=" + escape(func);
    if (parms != null){
      if (typeof(parms) == "string"){
        // single parameter
        URL += "&P0=[" + escape(parms+'') + "]";
      } else {
        // assume parms is array of strings
        for( var i=0; i < parms.length; i++ ){
          URL += "&P" + i + "=[" + escape(parms[i]+'') + "]";
        }
      } // parm type
    } // parms
  } // func
  
  if (formdata != null) {
      URL += "&_FormData=[" + jescape(formdata+'') + "]";
  }

  LoadPage(URL, targetFrm);
}

// the following functions are added for popup function call
function jsrsLoadPopup(rsPage, func, parms, formdata)
{
  // build URL to call
  var URL = rsPage;

  // func and parms are optional
  if (func != null){
    URL += "?F=" + escape(func);    //URL += "&F=" + escape(func);

    if (parms != null){
      if (typeof(parms) == "string"){
        // single parameter
        URL += "&P0=[" + escape(parms+'') + "]";
      } else {
        // assume parms is array of strings
        for( var i=0; i < parms.length; i++ ){
          URL += "&P" + i + "=[" + escape(parms[i]+'') + "]";
        }
      } // parm type
    } // parms
  } // func
  
  if (formdata != null) {
     URL += "&_FormData=[" + jescape(formdata+'') + "]";
  }

  w = 600;
  h = 500;
  LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
  TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
  //LeftPosition = screen.width + 100;
  //TopPosition = screen.height + 100;
  settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=0,resizable=1';

  window.open (URL, "", settings);

  return "";
}

function FindFrame(targetFrame)
{
   for (i=0; i<top.frames.length; i++)
   {
      if (top.frames[i].name == targetFrame)
         return top.frames[i];
   }
   return null;
}

function RedirectPage(sTargetURL)
{
   window.top.location.replace(sTargetURL);
}

var showmenu = false;

function DrawMenu(menu_div)
{
   HideMenu();
   var menuItems = document.getElementById(menu_div);
   //alert(menuItems.innerHTML);
   var menu_array = menuItems.childNodes;
   if (menu_array) {
      shtml = "<table cellspacing=0 cellpadding=2>";
      for (i=0;i<menu_array.length;i++) {
         shtml += "<tr onmouseover=\"className='menuitem_mouseover'\" onmouseout=\"className='menuitem_mouseout'\"><td><img src='../images/"+menu_array[i].getAttribute("icon")+"' border=0></td>";
         shtml += "<td><a href=\""+menu_array[i].getAttribute("link")+"\">"+menu_array[i].getAttribute("text")+"</a></td></tr>";
      }
      shtml += "</table>";
      divStyle = "top: "+(window.event.clientY)+"px; left: "+(window.event.clientX+10)+"px;";
      var newDiv = document.createElement("<div id='_popmenu' class='menuskin' style='"+divStyle+"'>");
	   newDiv.innerHTML = shtml;
      document.body.appendChild(newDiv);
      if (document.body.clientWidth-window.event.clientX < newDiv.offsetWidth)
         newDiv.style.left = (window.event.clientX - newDiv.offsetWidth) + "px";
      if (document.body.clientHeight-window.event.clientY < newDiv.offsetHeight)
         newDiv.style.top = (window.event.clientY - newDiv.offsetHeight) + "px";
      showmenu = true;
   }
}

function HideMenu()
{
   if(!showmenu)
   {
      var obj = document.getElementById("_popmenu");
   	if(obj) {
   	   document.body.removeChild(obj);
   		obj = null;
   	}
   }
   showmenu = false;
}

function FocusOn(elemId)
{
   var elem = document.getElementById(elemId);
   if (elem) {
      // find its parent element who has attribute as "Highlighted"
      var tmp = elem.parentNode;
      while (tmp) {
         var hlt_id = tmp.getAttribute("Highlighted");
         if (hlt_id!=null) {
            tmp.setAttribute("Highlighted", elemId);
            if (hlt_id) {
               var hlt_elem = document.getElementById(hlt_id);
               if (hlt_elem) {
                  var normalAttr = hlt_elem.getAttribute("normal");
                  if (!normalAttr) 
                     hlt_elem.style.background = "white";
                  else
                     hlt_elem.className = normalAttr;
               }
            }
            break;
         }
         tmp = tmp.parentNode;
      }
      var selAttr = elem.getAttribute("select");
      if (!selAttr)
         elem.style.background = selAttr ? selAttr : "#A4D3EE";
      else
         elem.className = selAttr;
   }
}

function popupErrorText(text)
{
   w = 500;
   h = 200;
   LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
   TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
   settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=0,resizable=1';
   
   debugWindow = window.open("","",settings);
   body = "<body bgcolor=#D9D9D9>";
   body += text;
   body += "<center><p><input type=button value='Close Window' onclick='window.close();'></center></body>";
   debugWindow.document.writeln("<head><title>error</title>"+body+"</head>"); 
}

function moveToCenter(win, w, h)
{
   LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
   TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
   win.resizeTo(w,h);
   win.moveTo(LeftPosition, TopPosition);
   return;
}

function popupWindow(content, w, h)
{
   LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
   TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
   settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=0,resizable=1';
   
   popupWindow = window.open("","",settings);
   popupWindow.document.writeln(content); 
}

function debugWindow(content)
{
   w=600; h=480;
   LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
   TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
   settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars=1,resizable=1';
   
   dbgWindow = window.open("","rpc_debug",settings);
   dbgWindow.document.writeln(content); 
}

function popupIWin(content, w, h)
{
   xi = document.body.clientWidth/2-w/2;
   yi = document.body.clientHeight/2-h/2;
	divStyle = "position: absolute; border:2 outset white; width:"+w+";height:"+h+";top: "+xi+"px; left: "+yi+"px;";
   var newDiv = document.createElement("<div id='tempbox' style='"+divStyle+"'>");

   shtml = "<div id='title' class='handle' handlefor='tempbox'>title</div>";
   shtml += content;
   newDiv.innerHTML = shtml;
   
   document.body.appendChild(newDiv);
      
   //obj = document.getElementById(objId);
   //if (obj)
   //{
   //   obj.style.display = '';
   //   obj.style.left=document.body.clientWidth/2-obj.offsetWidth/2+'px';
   //   obj.style.top=document.body.clientHeight/2-obj.offsetHeight/2+'px';
   //}
}

function resizeFrame(rows, cols)
{
   if (rows)
      top.document.body.rows = rows;
   if (cols)
      top.document.body.cols = cols;
}

function popupConfirm(question, yesFunc, noFunc)
{
   answer = confirm(question);
   if (answer)
   // do yesFunc
   eles
   // do noFunc
}

// keyboard handler code

var SHIFT_KEY  = 16;
var CTRL_KEY   = 17;
var ALT_KEY    = 18;
var SHIFT_DOWN = false;
var CTRL_DOWN  = false;
var ALT_DOWN   = false;

function KeyUp(evt) {
  evt = (evt) ? evt : window.event
  var keyCode = evt.keyCode;

  if ( keyCode == SHIFT_KEY )
    SHIFT_DOWN = false;
  if ( keyCode == CTRL_KEY )
    CTRL_DOWN = false;
  if ( keyCode == ALT_KEY ) 
    ALT_DOWN = false;
}

function KeyDown(evt) {
  evt = (evt) ? evt : window.event;
  var keyCode = evt.keyCode;

  if ( keyCode == SHIFT_KEY )
    SHIFT_DOWN = true;
  if ( keyCode == CTRL_KEY )
    CTRL_DOWN = true;
  if ( keyCode == ALT_KEY ) 
    ALT_DOWN = true;

   //if(CTRL_DOWN && keyCode == 83) { // Ctrl+S, save
     //alert("Ctrl+S"); CTRL_DOWN = false;
   //}
   if (keyCode != SHIFT_KEY && keyCode != CTRL_KEY && keyCode != ALT_KEY) {
      // search the cmd from the key_cmd map, and trigger the cmd
      
      // reset the Shift,Ctrl,Alt to be false
   }
}

