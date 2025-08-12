  var errorString = "Sorry but this web page needs\nWindows95 and Internet Explorer 5 or above to view."
  var Ok = "false";
  var name =  navigator.appName;
  var version =  parseFloat(navigator.appVersion);
  var platform = navigator.platform;

    if (platform == "Win32" && name == "Microsoft Internet Explorer" && version >= 4){
        Ok = "true";
    } else {
        Ok= "false";
    }

    if (Ok == "false") {
        alert(errorString);
    }

function ColorPalette_OnClick(colorString){
    
    cpick.bgColor=colorString;
    document.all.colourp.value=colorString;
    doFormat('ForeColor',colorString);
}

function initToolBar(ed) {
    
    var eb = document.all.editbar;
    if (ed!=null) {
        eb._editor = window.frames['myEditor'];
    }
}

function doFormat(what) {

    var eb = document.all.editbar;
        
    if(what == "FontName"){
        if(arguments[1] != 1){
            eb._editor.execCommand(what, arguments[1]);
            document.all.font.selectedIndex = 0;
        } 
    } else if(what == "FontSize"){
    if(arguments[1] != 1){
      eb._editor.execCommand(what, arguments[1]);
      document.all.size.selectedIndex = 0;
    } 
    } else {
       eb._editor.execCommand(what, arguments[1]);
    }
}

function swapMode() {

    var eb = document.all.editbar._editor;
  eb.swapModes();
}

function create() {

    var eb = document.all.editbar;
    eb._editor.newDocument();
}

function newFile(){

    create();
}

function makeUrl(){

    sUrl = document.all.what.value + document.all.url.value;
    doFormat('CreateLink',sUrl);
}

function copyValue() {

    var theHtml = "" + document.frames("myEditor").document.frames("textEdit").document.body.innerHTML + "";
    document.all.EditorValue.value = theHtml;
}

function SwapView_OnClick(){

  if(document.all.btnSwapView.value == "View Html"){
        var sMes = "View Wysiwyg";
    var sStatusBarMes = "Current View Html";
    } else {
        var sMes = "View Html"
    var sStatusBarMes = "Current View Wysiwyg";
  }
    
    document.all.btnSwapView.value = sMes;
  window.status  = sStatusBarMes;
    swapMode();
}

function Help_OnClick(){
  window.open("./editeur/editor_images/help_document.htm","wHelp", "toolbar=0, scrollbars=yes, width=640, height=480");
}