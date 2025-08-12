<?php 
include("../../../cms-config.php"); 
include("../../../cms-libs/servervars.php");
include("../../../cms-libs/database.php");
include("../../libs/pages.php"); 
$ServerVars = new ServerVars();
$Db = new DB($CFG->dbhost, $CFG->dbname, $CFG->dbuser, $CFG->dbpass);
$Db->connect();
$pages = new Pages();
?>
<html style="width: 398; height: 178">

<head>
  <title>Insert Link</title>

<script type="text/javascript" src="popup.js"></script>

<script type="text/javascript">
var preview_window = null;

function Init() {
  __dlg_init();
  agt = navigator.userAgent.toLowerCase();
  is_ie = (agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1);
  if (is_ie) {
  } else {
  }
  document.getElementById("type_url").focus();
};

function onOK() {
  var f_type = document.getElementById("type_url");
  var type = f_type.value;
  if (type==1) {
    var f_url = document.getElementById("f_url1");
    var url = f_url.value;
  } else {
    var f_url = document.getElementById("f_url2");
    var url = f_url.value;
  }

  if (!f_url) {
    alert("You must enter URL");
    return false;
  }
  var param = new Object();
    param["f_url"] = url;
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
//  alert("FIXME: preview needs rewritten:\n  show the image inside this window instead of opening a new one.");
  var f_url = document.getElementById("f_url");
  var url = f_url.value;

  if (!url) {
    alert("You have to enter an URL first");
    f_url.focus();
    return false;
  }
  var img = new Image();
  img.src = url;
  var win = null;
  
  if (!document.all) {
    win = window.open("../../images_view.php?name="+url, "ha_imgpreview", "toolbar=no,menubar=no,personalbar=no,innerWidth=100,innerHeight=100,scrollbars=no,resizable=yes");
  } else {
    win = window.open("../../images_view.php?name="+url, "ha_imgpreview", "channelmode=no,directories=no,height=100,width=100,location=no,menubar=no,resizable=yes,scrollbars=no,toolbar=no");
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

function onChange() {
  var type_url_id = document.getElementById("type_url");
  var type_url = type_url_id.value;
  if (type_url==1) {
    var div1 = document.getElementById("link1");
    div1.style.visibility = 'visible';
    var div2 = document.getElementById("link2");
    div2.style.visibility = 'hidden';
  } else {
    var div1 = document.getElementById("link1");
    div1.style.visibility = 'hidden';
    var div2 = document.getElementById("link2");
    div2.style.visibility = 'visible';
  }
}
</script>

<style type="text/css">
#link1 {POSITION: absolute; TOP:63; LEFT:89; 
 Z-INDEX: 100; VISIBILITY: visible; width:290}

#link2 {POSITION: absolute; TOP:63; LEFT:89; 
 Z-INDEX: 100; VISIBILITY: hidden; width:290}

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
.fl { width: 9em; float: left; padding: 2px 5px; text-align: right; }
.fr { width: 6em; float: left; padding: 2px 5px; text-align: right; }
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

<div class="title">Insert Link</div>

<form action="" method="get" name=imageform>
<table border="0" width="100%" style="padding: 0px; margin: 0px">
  <tbody>

  <tr>
    <td style="width: 7em; text-align: right">Type:</td>
    <td>
    <select name="type_url" id="type_url" style="width:100%" onChange="onChange();">
    <option value=1>Link to page on website</option>
    <option value=2>Link to other website</option>
    </select>
    </td>
  </tr>
  <tr>
    <td style="width: 7em; text-align: right">Link:</td>
    <td>
    <div name="link1" id="link1">
    <select name="url1" id="f_url1" style="width:100%">
    <?php
    for($n=0; $n<count($page_tree); $n++) {
    $indent = "";
    $page = $page_tree[$n]["page"];
    for($i=1; $i<$page["level"]; $i++) { $indent .= "&nbsp;&nbsp;"; }
	echo "<option value=".$page["id"]." >".$indent.$page["name_menu"]."</option>";
    }

    ?>
    </select>
    </div>
    <div name="link2" id="link2">
    <input type="text" value='' name="url2" id="f_url2" style="width:100%" />
    </div>

    </td>
  </tr>

  </tbody>
</table>

<p />
<div style="margin-top: 0px; text-align: right;">
<hr />
<button type="button" name="ok" onclick="return onOK();">OK</button>
<button type="button" name="cancel" onclick="return onCancel();">Cancel</button>
</div>

</form>

</body>
</html>
<?php
$DB->disconnect();
?>