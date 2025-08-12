function character_SPEAK() {

var txtarea = document.builder.STORY;
var fileonlist = document.builder.CHTK;

if (fileonlist.value != ""){
txtarea.value = txtarea.value + "[" + fileonlist.value + "]";
txtarea.focus();
}
}