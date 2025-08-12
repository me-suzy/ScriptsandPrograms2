function paste_string(e, s){
e.value+=s;
e.focus();
}
function paste_string2(e, s){
e.value+=s;
}
function paste_url(e,p1,p2){
paste_string(e, '[url='+prompt(p1,'')+']'+prompt(p2,'')+'[/url]');
}
function paste_email(e,p1,p2){
paste_string(e, '[email='+prompt(p1,'')+']'+prompt(p2,'')+'[/email]');
}
function paste_image(e,p1){
paste_string(e, '[img]'+prompt(p1,'')+'[/img]');
}
function spopup(doc, xwidth, yheight, scrollbar)
{
var _left = eval(screen.width/2 - xwidth/2);
var _top = eval(screen.height/2 - yheight/2);
popupWin = window.open('smilies_popup.php?doc=window.opener.'+doc,"imageWin","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars="+scrollbar+",resizable=0,width="+ xwidth +",height="+ yheight +",left="+ _left +",top="+ _top +"");
}
function get_sel()
{
  if (window.getSelection) return window.getSelection(); else if
     (document.getSelection) return document.getSelection(); else if
     (document.selection) return document.selection.createRange().text; else return;
}
function quote_sel(e)
{
  sel = get_sel();
  paste_string(e, '[quote]'+sel+'[/quote]');
}
