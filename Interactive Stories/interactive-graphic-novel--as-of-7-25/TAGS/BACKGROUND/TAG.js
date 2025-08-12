function backgrounding() {

var txtarea = document.builder.STORY;
var fileonlist = document.builder.background;

if (fileonlist.value != ""){
txtarea.value = txtarea.value + "[" + fileonlist.value + "]";
txtarea.focus();
}
}