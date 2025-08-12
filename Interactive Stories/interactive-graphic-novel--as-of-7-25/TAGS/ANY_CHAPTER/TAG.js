function NE_CHAPTER() {

var txtarea = document.builder.STORY;
var fileonlist = document.builder.ne_chapter;

if (fileonlist.value != ""){
txtarea.value = txtarea.value + "[" + fileonlist.value + "]";
txtarea.focus();
}
}