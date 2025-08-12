function character() {

var txtarea = document.builder.STORY;
var fileonlist = document.builder.chater;

if (fileonlist.value != ""){
txtarea.value = txtarea.value + "[" + fileonlist.value + "]";
txtarea.focus();
}
}