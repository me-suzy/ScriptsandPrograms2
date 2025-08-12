<?php

require("../../../cms-config.php");

?>
<html style="width: 400; height: 226">

<head>
<title>Insert Image</title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=koi8-r">
<script type="text/javascript" src="popup.js"></script>

<script type="text/javascript">
var preview_window = null;

function Init() {
  __dlg_init();
  document.getElementById("f_url").focus();
};

function onOK() {
  var required = {
    "f_url": "You must select image",
    "f_alt": "Please enter the alternate text"
  };
  for (var i in required) {
    var el = document.getElementById(i);
    if (!el.value) {
      alert(required[i]);
      el.focus();
      return false;
    }
  }
  // pass data back to the calling window
  var fields = ["f_url", "f_alt", "f_align", "f_border",
                "f_horiz", "f_vert"];
  var param = new Object();
  for (var i in fields) {
    var id = fields[i];
    var el = document.getElementById(id);
    param[id] = el.value;
  }
  if (preview_window) {
    preview_window.close();
  }
  __dlg_close(param);
  return false;
};

function onCancel() {
  if (preview_window) {
    preview_window.close();
  }
  __dlg_close(null);
  return false;
};

function onPreview() {
  var f_url = document.getElementById("f_url");
  var url = f_url.value;
  if (!url) {
    alert("You must select image");
    f_url.focus();
    return false;
  }
  var img = new Image();
  img.src = url;
  var win = null;
  if (!document.all) {
    win = window.open("about:blank", "ha_imgpreview", "toolbar=no,menubar=no,personalbar=no,innerWidth=100,innerHeight=100,scrollbars=no,resizable=yes");
  } else {
    win = window.open("about:blank", "ha_imgpreview", "channelmode=no,directories=no,height=100,width=100,location=no,menubar=no,resizable=yes,scrollbars=no,toolbar=no");
  }
  preview_window = win;
  var doc = win.document;
  var body = doc.body;
  if (body) {
    body.innerHTML = "";
    body.style.padding = "0px";
    body.style.margin = "0px";
    var el = doc.createElement("img");
    el.src = url;

    var table = doc.createElement("table");
    body.appendChild(table);
    table.style.width = "100%";
    table.style.height = "100%";
    var tbody = doc.createElement("tbody");
    table.appendChild(tbody);
    var tr = doc.createElement("tr");
    tbody.appendChild(tr);
    var td = doc.createElement("td");
    tr.appendChild(td);
    td.style.textAlign = "center";

    td.appendChild(el);
    win.resizeTo(el.offsetWidth + 30, el.offsetHeight + 30);
  }
  win.focus();
  return false;
};
</script>

<style type="text/css">
html, body {
  background: ButtonFace;
  color: ButtonText;
  font: 11px Tahoma,Verdana,sans-serif;
  margin: 0px;
  padding: 0px;
}
body { padding: 5px; }
table {
  font: 11px Tahoma,Verdana,sans-serif;
}
form p {
  margin-top: 5px;
  margin-bottom: 5px;
}
.f { width: 6em; float: left; padding: 2px 5px; text-align: right; }
.fl { width: 7em; float: left; padding: 2px 5px; text-align: right; }
.fr { width: 9em; float: left; padding: 2px 5px; text-align: right; }
fieldset { padding: 0px 10px 5px 5px; }
select, input, button { font: 11px Tahoma,Verdana,sans-serif; }
button { width: 70px; }
.space { padding: 2px; }

.title { background: #ddf; color: #000; font-weight: bold; font-size: 120%; padding: 3px 10px; margin-bottom: 10px;
border-bottom: 1px solid black; letter-spacing: 2px;
}
form { padding: 0px; margin: 0px; }
</style>

</head>

<body onload="Init()">

<div class="title">Insert Image</div>

<form action="" method="get">

<fieldset>
<div class="space"></div>
<div class="f">Image:</div>
<select name="url" id="f_url" style="width:60%">
<?php
$path = "$CFG->dir_root/cms-images/";
$dir = dir($path);
$n = 0;
while($image = $dir->read()) {
    if(is_file($path.$image)) {
	$images[$n]["name"] = $image;
	$n++;
    }
}
$count_images = $n;
if ($count_images==0) {
?>
<option value="">You don't have any images</option>
<?php
} else {
    $n = 0;
    while($images[$n]) {
	$image_name = $images[$n]["name"];
?>
    <option value="/cms-images/<?php echo $images[$n]["name"] ?>"><?php echo $images[$n]["name"] ?></option>
<?php
	$n++;
    }
}
?>

</select> <button name="preview" onclick="return onPreview();" title="Preview the image in a new window">Preview</button>

<p />
<div class="f">Alternate text:</div>
<input type="text" name="alt" id="f_alt" style="width:100%" title="For browsers that don't support images" />
<div class="space"></div>
</fieldset>

<p />



<fieldset style="float: left;">
<legend>Layout</legend>

<div class="space"></div>

<div class="fl">Alignment:</div>
<select size="1" name="align" id="f_align"
  title="Positioning of this image">
  <option value=""                             >Not set</option>
  <option value="left"                         >Left</option>
  <option value="right"                        >Right</option>
  <option value="bottom"                       >Bottom</option>
  <option value="middle"                       >Middle</option>
  <option value="top"                          >Top</option>

</select>

<p />

<div class="fl">Border thickness:</div>
<input type="text" name="border" id="f_border" size="5"
title="Leave empty for no border" />

<div class="space"></div>

</fieldset>

<fieldset style="float:right;">
<legend>Spacing</legend>

<div class="space"></div>

<div class="fr">Horizontal:</div>
<input type="text" name="horiz" id="f_horiz" size="5"
title="Horizontal padding" />

<p />

<div class="fr">Vertical:</div>
<input type="text" name="vert" id="f_vert" size="5"
title="Vertical padding" />

<div class="space"></div>

</fieldset>

<div style="margin-top: 82px; text-align: right;">
<hr />
<button type="button" name="ok" onclick="return onOK();">Ok</button>
<button type="button" name="cancel" onclick="return onCancel();">Cancel</button>
</div>

</form>

</body>
</html>
