<?
/*
The Afian file manager
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/


require_once("config.php");
require_once("$config[root_dir]/functions/functions.php");
require_once("$config[root_dir]/functions/compatibility.php");

$filename = safeFilename($filename);
$dir = stripslashes(safepath($dir));


//set path
$base_dir = $config[base_dir];
if ($dir) {
	$base_dir = $base_dir . $dir;
}

	if (!is_dir($base_dir)) {
		echo "Directory not available.";
	} else {

if ($submit) {

$mode = $u.$g.$w;

if (is_dir($base_dir."/".$filename)) {
	if (superChmod($base_dir."/".$filename, $mode)) {
		$alert = "Permisions changed for folder ".safestr($filename, false)."";
	} else {
		$alert = "Failed to chmod folder.";
	}
	if ($recursive) {
		$files = getDirList($base_dir."/".$filename, array());
		for ($i = 0 ; $i < sizeof($files) ; $i++) {
			//echo $files[$i] . "<br>";
			superChmod($files[$i], $mode);
		}
	}
} else {
	if (superChmod("$base_dir/$filename", $mode)){
		$alert = "Permisions changed for file ".safestr($filename, false)."";
	} else {
		$alert = "Failed to chmod file.";
	}
}
?>
<script language="JavaScript1.2" type="text/javascript">
alert('<?echo $alert?>');
parent.closePopup();
</script>
<?




} else {


if (!$multiple) {
	$file = $base_dir . "/" . $filename;
	$perms=getperms($file);
	$user = substr($perms, 0, 1);
	$group = substr($perms, 1, 1);
	$world = substr($perms, 2, 3);
} else {
	$user = 0;
	$group = 0;
	$world = 0;
}
?>
<html>
<head>
<title>Afian file manager - Chmod</title>
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/style.css">

<script language="JavaScript1.2" type="text/javascript">

function rec() {
	if (!ux.checked) {
		alert('It is necesary to set \'execute\' at least for \'User\'');
	}
}

function init() {
	ux = document.fmode.ux;
	uw = document.fmode.uw;
	ur = document.fmode.ur;

	gx = document.fmode.gx;
	gw = document.fmode.gw;
	gr = document.fmode.gr;

	wx = document.fmode.wx;
	ww = document.fmode.ww;
	wr = document.fmode.wr;
	
	u = document.fmode.u;
	g = document.fmode.g;
	w = document.fmode.w;
	
	recheck();
}
function calcperm() {
        if (ur.checked && uw.checked && ux.checked) {
                u.value = 7;
        }
		 if (gr.checked && gw.checked && gx.checked) {
                g.value = 7;
        }
		 if (wr.checked && ww.checked && wx.checked) {
                w.value = 7;
        }
		
		 if (ur.checked && !uw.checked && !ux.checked) {
                u.value = 4;
        }
		 if (gr.checked && !gw.checked && !gx.checked) {
                g.value = 4;
        }
		 if (wr.checked && !ww.checked && !wx.checked) {
                w.value = 4;
        }
		
		if (ur.checked && uw.checked && !ux.checked) {
                u.value = 6;
        }
		 if (gr.checked && gw.checked && !gx.checked) {
                g.value = 6;
        }
		 if (wr.checked && ww.checked && !wx.checked) {
                w.value = 6;
        }
		
		if (!ur.checked && !uw.checked && !ux.checked) {
                u.value = 0;
        }
		 if (!gr.checked && !gw.checked && !gx.checked) {
                g.value = 0;
        }
		 if (!wr.checked && !ww.checked && !wx.checked) {
                w.value = 0;
        }

		if (!ur.checked && !uw.checked && ux.checked) {
                u.value = 1;
        }
		 if (!gr.checked && !gw.checked && gx.checked) {
                g.value = 1;
        }
		 if (!wr.checked && !ww.checked && wx.checked) {
                w.value = 1;
        }
		
		if (ur.checked && !uw.checked && ux.checked) {
                u.value = 5;
        }
		 if (gr.checked && !gw.checked && gx.checked) {
                g.value = 5;
        }
		 if (wr.checked && !ww.checked && wx.checked) {
                w.value = 5;
        }
		
		if (!ur.checked && uw.checked && !ux.checked) {
                u.value = 2;
        }
		 if (!gr.checked && gw.checked && !gx.checked) {
                g.value = 2;
        }
		 if (!wr.checked && ww.checked && !wx.checked) {
                w.value = 2;
        }
		
		if (!ur.checked && uw.checked && ux.checked) {
                u.value = 3;
        }
		 if (!gr.checked && gw.checked && gx.checked) {
                g.value = 3;
        }
		 if (!wr.checked && ww.checked && wx.checked) {
                w.value = 3;
        }
}

function recheck() {
   if (u.value == 7) {
        ux.checked = true;
		uw.checked = true;
		ur.checked = true;
   }
   if (u.value == 6) {
        ux.checked = false;
		uw.checked = true;
		ur.checked = true;
   }
   if (u.value == 5) {
        ux.checked = true;
		uw.checked = false;
		ur.checked = true;
   }
   if (u.value == 4) {
        ux.checked = false;
		uw.checked = false;
		ur.checked = true;
   }
   if (u.value == 3) {
        ux.checked = true;
		uw.checked = true;
		ur.checked = false;
   }
   if (u.value == 2) {
        ux.checked = false;
		uw.checked = true;
		ur.checked = false;
   }
   if (u.value == 1) {
        ux.checked = true;
		uw.checked = false;
		ur.checked = false;
   }
    if (u.value == 0) {
        ux.checked = false;
		uw.checked = false;
		ur.checked = false;
   }

   if (g.value == 7) {
        gx.checked = true;
		gw.checked = true;
		gr.checked = true;
   }
   if (g.value == 6) {
         gx.checked = false;
		 gw.checked = true;
		 gr.checked = true;
   }
   if (g.value == 5) {
         gx.checked = true;
		 gw.checked = false;
		 gr.checked = true;
   }
   if (g.value == 4) {
         gx.checked = false;
		 gw.checked = false;
		 gr.checked = true;
   }
   if (g.value == 3) {
         gx.checked = true;
		 gw.checked = true;
		 gr.checked = false;
   }
   if (g.value == 2) {
         gx.checked = false;
		 gw.checked = true;
		 gr.checked = false;
   }
   if (g.value == 1) {
         gx.checked = true;
		 gw.checked = false;
		 gr.checked = false;
   }
    if (g.value == 0) {
         gx.checked = false;
		 gw.checked = false;
		 gr.checked = false;
   }

   
   if (w.value == 7) {
         wx.checked = true;
		 ww.checked = true;
		 wr.checked = true;
   }
   if (w.value == 6) {
         wx.checked = false;
		 ww.checked = true;
		 wr.checked = true;
   }
   if (w.value == 5) {
         wx.checked = true;
		 ww.checked = false;
		 wr.checked = true;
   }
   if (w.value == 4) {
         wx.checked = false;
		 ww.checked = false;
		 wr.checked = true;
   }
   if (w.value == 3) {
         wx.checked = true;
		 ww.checked = true;
		 wr.checked = false;
   }
   if (w.value == 2) {
         wx.checked = false;
		 ww.checked = true;
		 wr.checked = false;
   }
   if (w.value == 1) {
         wx.checked = true;
		 ww.checked = false;
		 wr.checked = false;
   }
    if (w.value == 0) {
         wx.checked = false;
		 ww.checked = false;
		 wr.checked = false;
   }


}
function submitForm() {
parent.filemanform.act.value='chmod';
parent.filemanform.chmode.value=u.value+g.value+w.value;
if (document.fmode.recursive.checked == true) {
	parent.filemanform.recurschmod.value='yes';
}
parent.filemanform.submit();
}

</script>
</head>
<body bgcolor="white" onLoad="javascript:init()">
<form name="fmode" action="chmod.php" <?if($multiple) {?>onSubmit="javascript:submitForm();return false;"<?}?>>
<br>
		<table border="0" cellpadding="4" cellspacing="0" align="center">
			<tr>
				<td><b>Mode</b>
				</td>
				<td>	User
				</td>
				<td>	Group
				</td>
				<td>	World
				</td>
			</tr>
			<tr>
				<td>	Read
				</td>
				<td>					
					<input type="checkbox" name="ur" value="4" onClick="calcperm();">
				</td>
				<td>					
					<input type="checkbox" name="gr" value="4" onClick="calcperm();">
				</td>
				<td>					
					<input  type="checkbox" name="wr" value="4" onClick="calcperm();">
				</td>
			</tr>
			<tr>
				<td>	Write
				</td>
				<td>					
					<input type="checkbox" name="uw" value="2" onClick="calcperm();">
				</td>
				<td>					
					<input  type="checkbox" name="gw" value="2" onClick="calcperm();">
				</td>
				<td>					
					<input  type="checkbox" name="ww" value="2" onClick="calcperm();">
				</td>
			</tr>
			<tr>
				<td>	Execute
				</td>
				<td>					
					<input  type="checkbox" name="ux" value="1" onClick="calcperm();">
				</td>
				<td>					
					<input  type="checkbox" name="gx" value="1" onClick="calcperm();">
				</td>
				<td>					
					<input  type="checkbox" name="wx" value="1" onClick="calcperm();">
				</td>
			</tr>
			<tr>
				<td>	Permission
				</td>
				<td><input type="text" name="u" size="1" onChange="recheck();" value="<?echo $user?>" style="width:23px;"></td>
				<td><input type="text" name="g" size="1" onChange="recheck();" value="<?echo $group?>" style="width:23px;"></td>
				<td><input type="text" name="w" size="1" onChange="recheck();" value="<?echo $world?>" style="width:23px;"></td>
			</tr>
		</table><br>
		<div align="center">
		<input type="checkbox" name="recursive" value="yes" onClick="javascript:rec()"> apply to all subfolders and files
<br>
<span class="comment">(only for folders)</span>
		<br><br>		
			<input type="submit" value="change" name="submit" class="button">			
			<input type="button" onClick="javascript:parent.closePopup()" value="cancel" class="button">
		</div>		
		<input type="hidden" name="filename" value="<?echo $filename?>">		
		<input type="hidden" name="dir" value="<?echo $dir?>">
	</form>
</body>
</html>
<?
	}
}
?>