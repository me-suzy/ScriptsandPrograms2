<?PHP 
  header('Content-Type: application/x-javascript');
?>
// toolbar button effects
function visEdit_default_bt_over(ctrl)
{
  var imgfile = visEdit_base_image_name(ctrl)+"_over.gif";
  ctrl.src = imgfile;
}
function visEdit_default_bt_out(ctrl)
{
  var imgfile;
  if (ctrl.getAttribute("visEdit_state") == true)
  {
    imgfile = visEdit_base_image_name(ctrl)+"_down.gif";
  }
  else
  {
    imgfile = visEdit_base_image_name(ctrl)+".gif";
  }
  ctrl.src = imgfile;
  ctrl.disabled = false;
}
function visEdit_default_bt_down(ctrl)
{
  var imgfile = visEdit_base_image_name(ctrl)+"_down.gif";
  ctrl.src = imgfile;
}
function visEdit_default_bt_up(ctrl)
{
  var imgfile = visEdit_base_image_name(ctrl)+".gif";
  ctrl.src = imgfile;
}
function visEdit_default_bt_off(ctrl)
{
  var imgfile = visEdit_base_image_name(ctrl)+"_off.gif";
  ctrl.src = imgfile;
  ctrl.disabled = true;
}

