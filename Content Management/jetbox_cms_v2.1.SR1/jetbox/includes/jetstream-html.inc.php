<?
function jetstream_header($page_name, $popuplogin=true) {
	global $site_title,  $jetstream_url, $front_end_url, $currensection, $toptab, $seltoptab,  $display_name, $u_mail, $extjetstreammenu, $extjetstreammenuhead, $container_id, $display_name, $nomenu, $autologoutpopup, $thisfile, $front_end_url, $_SETTINGS;
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title>
	<? echo $site_title . " - " . $page_name;?>
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Language" content="en-us" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate, max_age=0" />
	<meta http-equiv="Expires" content="0" />
	<link rel="stylesheet" type="text/css" href="<? echo $jetstream_url;?>/images/interface.css"></link>
	<script language="javascript" type="text/javascript" src="<? echo $jetstream_url;?>/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
 	<!--
<?
		if(!isset($_SETTINGS["rte_off"])){
		?>
		tinyMCE.init({
				mode : "specific_textareas",
				theme : "advanced",
			plugins : "table,advhr,advimage,advlink,iespell,insertdatetime,preview,searchreplace,contextmenu",
			theme_advanced_buttons3_add : "separator,insertdate,inserttime,preview",
			theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
			theme_advanced_buttons3_add_before : "tablecontrols,separator",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			plugin_insertdate_dateFormat : "%d-%m-%Y",
			plugin_insertdate_timeFormat : "%H:%M:%S",
			extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
				document_base_url : "<?echo $front_end_url?>"
		 });
	<?
	}
?>
	function toogleEditorMode(sEditorID) {
		try {
			if(tinyMCE.getEditorId(sEditorID)!=null) {
				tinyMCE.removeMCEControl(tinyMCE.getEditorId(sEditorID));
				document.getElementById(sEditorID+'_toggle_text').innerHTML="Turn the rich text editor on.";
			}
			else {
				tinyMCE.addMCEControl(document.getElementById(sEditorID), sEditorID);
				document.getElementById(sEditorID+'_toggle_text').innerHTML="Turn the rich text editor off.";
			}
		} catch(e) {
				//error handling
		}
		return false;
	}

	document.onhelp=DefHelp;
	function DefHelp(){
		event.returnValue=false;
		showHelp('help.php?popup_help=1');
	}
	
	function overview_list_init(){
		table_body= document.getElementById("table_overview_list").firstChild;
		first_1=table_body.childNodes[3];
		last_1=table_body.childNodes[table_body.childNodes.length-2];
		// items in table

		if (first_1==null) {
			return false;
		}
		
		first_2=first_1.nextSibling.nextSibling;

		if (first_2 !== null) {
			last_2=table_body.childNodes[table_body.childNodes.length-4];
			first_1.lastChild.firstChild.firstChild.className="";
			first_2.lastChild.firstChild.firstChild.className="";
			first_2.lastChild.previousSibling.firstChild.firstChild.className="";
			last_1.lastChild.previousSibling.firstChild.firstChild.className="";
			last_2.lastChild.firstChild.firstChild.className="";
			last_2.lastChild.previousSibling.firstChild.firstChild.className="";
		}
		last_1.lastChild.firstChild.firstChild.className="off";
		first_1.lastChild.previousSibling.firstChild.firstChild.className="off";
	}

	function swap(a, way) {
		a2=a.parentNode.parentNode;
		way2=way;
		var item = a2.getAttribute('item');
		var oRsElm = document.createElement("script");
		oRsElm.src = "<?echo $jetstream_url. $thisfile?>?param=" + item + "&way=" + way + "&do_sort=1"; 
		oRsElm.type = "text/javascript"; 
		oRsElm.id = "sort_script"; 
		//alert(oRsElm.src);
		if (document.getElementById("sort_script")) {
			document.body.replaceChild(oRsElm, document.getElementById("sort_script"));
		}
		else{
			document.body.appendChild(oRsElm);
		}
	}
	
	function swap2(a, way) {
		if(way=="1"){
			//alert(a.previousSibling.previousSibling);
			if(a.previousSibling !== null && a.previousSibling.previousSibling !== null){
      //alert(a.previousSibling.previousSibling.id);
				if (a.previousSibling.previousSibling.id=="sorting_row") {
					return false;
        }
				a.parentNode.insertBefore(a.previousSibling, a.nextSibling);
				a.parentNode.insertBefore(a.previousSibling, a.nextSibling.nextSibling);
			}
		}
		else{
			if(a.nextSibling !== null && a.nextSibling.nextSibling !== null){
				a.parentNode.insertBefore(a.nextSibling, a);
				a.parentNode.insertBefore(a.nextSibling, a.previousSibling);
			}
			else{
				return false;
			}
		}
		overview_list_init();
	}

	function MM_findObj(n, d) { //v4.01
		var p,i,x;
		if(!d) d=document;
		if((p=n.indexOf("?"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document;
			n=n.substring(0,p);
		}
		if(!(x=d[n])&&d.all)
			x=d.all[n];
		for (i=0;!x&&i<d.forms.length;i++)
			x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++)
			x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById)
			x=d.getElementById(n);
		return x;
	}

	function MM_showHideLayers() { //v6.0
		var i,p,v,obj,args=MM_showHideLayers.arguments;
		for (i=0; i<(args.length-2); i+=3)
			if ((obj=MM_findObj(args[i]))!=null) {
				v=args[i+2];
				if (obj.style) {
					obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v;
				}
				obj.visibility=v;
			}
	}

	<?
	if ($popuplogin==true && $nomenu<>true) {
	?>
	function doCountdown() {
		for (i = 0; i < myCountdown.length; i++) {
			if (!myCountdown[i].expired) {
				var eventDate = myCountdown[i].eventDate;
				myCountdown[i].eventDate= eventDate-1;
				var eventDate = myCountdown[i].eventDate;
				if (eventDate <= -1) {
					myCountdown[i].expired = true;
					sh();
				}
				else {
					repeat = true;
				}
			}
		}
		if (repeat) {
			repeat = false;
			window.setTimeout("doCountdown()", 1000);
		}
		else {
			return;
		}
	}

	function addCountdown(countdown) {
		myCountdown[myCountdown.length] = countdown;
		return;
	}

	function Countdown() {
		this.tagID = "";
		this.eventDate = 20;
		this.event = "";
		this.expired = false;
	}

	var myCountdown = new Array();
	var repeat = false;
	var mycountdown = new Countdown();
	with (mycountdown) {
		tagID = "loginpopup";
		eventDate = <?echo $autologoutpopup;?>;
		event = "Login popup";
	}
	addCountdown(mycountdown);
	<?
	}
	?>

	function login_focus(){
		if (document.login.login.value == ''){
			document.login.login.focus();
		}
		else{
			document.login.login_password.focus();
		}
	}

	function hideLayer(whichLayer) {
		if (document.getElementById) {
			// this is the way the standards work
			document.getElementById(whichLayer).style.display = "none";
		}
		else if (document.all) {
			// this is the way old msie versions work
			document.all[whichlayer].style.display = "none";
		}
		else if (document.layers) {
			// this is the way nn4 works
			document.layers[whichLayer].display = "none";
		}
	}

	function showLayer(whichLayer) {
			//	alert(whichLayer);
		if (document.getElementById) {
			// this is the way the standards work
			document.getElementById(whichLayer).style.display = "block";
		}
		else if (document.all) {
			// this is the way old msie versions work
			document.all[whichlayer].style.display = "block";
		}
		else if (document.layers) {
			// this is the way nn4 works
			document.layers[whichLayer].display = "block";
		}
	}
	function sz(t) {
		a = t.value.split('\n');
		l=0;
		ll=0;
		for (x=0;x < a.length; x++) {
			if (a[x].length > t.cols){
				l+=a[x].length;
				ll++;
			}
		}
		t.rows = Math.ceil((l/(t.cols-13))+(a.length-ll))+2;
	}

	function getViewportHeight() {
		if (window.innerHeight!=window.undefined) return window.innerHeight;
		if (document.compatMode=='CSS1Compat') return document.documentElement.clientHeight;
		if (document.body) return document.body.clientHeight; 
		return window.undefined; 
	}
	function getViewportWidth() {
		if (window.innerWidth!=window.undefined) return window.innerWidth; 
		if (document.compatMode=='CSS1Compat') return document.documentElement.clientWidth; 
		if (document.body) return document.body.clientWidth; 
		return window.undefined; 
	}

	function sh(){
		o=MM_findObj('overlay_frame');
		oo=MM_findObj('overlay_frame_popup');
		if(o.style.display!='none'){
			o.style.display='none';
			oo.style.display='none';
			oo.src="about:blank";
		}
		else{
			o.style.display='';
			oo.style.display='';
			o.style.width=getViewportWidth()+"px";
			o.style.height=getViewportHeight()+"px";
			oo.src="<? echo $jetstream_url ?>/index.php?task=logout&timeout=true&nomenu=1";
		}
		//setTimeout("sh()",4000);
	}
	//-->
	</script>
	</head>
	<body <?if ($popuplogin==true && $nomenu<>true) {?>onload="doCountdown()"<?}?>>
	<?
		if(!isset($_REQUEST["timeout"]) && isset($_SESSION["uid"])){
		?>
		<iframe style="width:100%;height:100%;position:absolute;background-color:#ffffff;filter:alpha(opacity='40');opacity:.6;margin:0px;display:none" frameborder="0" id="overlay_frame"  src="about:blank"></iframe>

	<iframe style="width:560px;height:375px;margin-top:20px; margin-left:220px;position:absolute; display:none;padding:0" frameborder="0" id="overlay_frame_popup" src=""></iframe>
	<script>
	//setTimeout("sh()",4000);	
	overlay_frame_obj=MM_findObj('overlay_frame');
    var html = ""
            + '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
            + '<html>'
            + '<head>'
            + '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
            + '</head>'
            + '<body>'
            + '</body>'
            + '</html>';
			if(overlay_frame_obj.contentDocument){
				o=overlay_frame_obj.contentDocument;
			}
			else{
				o = overlay_frame_obj.contentWindow.document;
			}
			o.open();
			o.write(html);
			o.close();
		</script>
	<?
	}
	?>
	<table width='100%' border='0' cellspacing='0' cellpadding='0' height="100%">
		<tr>
			<?
				if($nomenu<>true) {

				?>
				<td class="left_menu"><IMG SRC="<? echo $jetstream_url;?>/images/jetbox_logo.gif" BORDER=0 alt="">
				<table width='181' border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td height='33' style="padding: 3px 0px 0px 0px;">
					<?
					//show name at top
					if (isset($_SESSION["uid"]) && $_SESSION["uid"]<>'') {
						# display the users name
						$sql = "SELECT * FROM user WHERE uid='" . $_SESSION["uid"] ."'";
						$res = mysql_prefix_query($sql) or die(mysql_error());
						if ($num = mysql_num_rows($res)) {
							$display_name=mysql_result($res,0,'display_name');
							$u_mail=mysql_result($res,0,'email');
							echo '&nbsp;&nbsp;<img src="'.$jetstream_url.'/images/minimaelhead.gif" width="16" height="16" border=0 alt="" />'. $display_name;
							if ($thisfile=='/user_prefs.php') {
								jetstream_nav_item("Preferences", "/user_prefs.php", true);
							}
							else{
								jetstream_nav_item("Preferences", "/user_prefs.php", false);
							}
						}
					}
					?>
					</td>
				</tr>
			</table>
				<div id="mmenu1Content">
								<?
								if (isset($_SESSION["uid"]) && $_SESSION["uid"]<>'') {
									if ($extjetstreammenu<>''){
										jetstream_nav_head($extjetstreammenuhead, '', '');
										while(list($key, $val)=each($extjetstreammenu)){
											if ($navarray["cont_id"]==$container_id) {
												jetstream_nav_item($key, $val, true);
											}
											else{
												jetstream_nav_item($key, $val, false);
											}
										}
									}
									jetstream_nav_head('Contents', '', true);
									if ($_SESSION["user_type"] == "administrator") {
										$res = mysql_prefix_query("SELECT *, container.id AS cont_id FROM container WHERE level<'100' ORDER BY corder ASC") or die(mysql_error());
									}
									else{
										$res = mysql_prefix_query("SELECT *, container.id AS cont_id FROM container, userrights WHERE container.id=userrights.container_id AND userrights.uid=".$_SESSION["uid"]." ORDER BY container.corder ASC") or die(mysql_error());
									}
									while($navarray=mysql_fetch_array($res)){
										if ($navarray["cont_id"]==$container_id) {
											jetstream_nav_item($navarray["cname"], $navarray["cfile"], true);
										}
										else{
											jetstream_nav_item($navarray["cname"], $navarray["cfile"], false);
										}
									}
									if ($_SESSION["user_type"] == "administrator") {
										$systemsql="SELECT *, container.id AS cont_id FROM container WHERE level='100' OR level='1000' ORDER BY corder ASC";
										$res = mysql_prefix_query($systemsql);
										jetstream_nav_head('System', '', '');
										while($navarray=mysql_fetch_array($res)){
											if ($navarray["cont_id"]==$container_id) {
												jetstream_nav_item($navarray["cname"], $navarray["cfile"], true);
											}
											else{
												jetstream_nav_item($navarray["cname"], $navarray["cfile"], false);
											}
										}
									}
									else{
										$systemsql="SELECT *, container.id AS cont_id FROM container, userrights WHERE container.id=userrights.container_id AND userrights.uid=".$_SESSION["uid"]." AND userrights.type='administrator' ORDER BY container.corder ASC";
										$res = mysql_prefix_query($systemsql);
										if (mysql_num_rows($res)>0){
											$systemsql="SELECT *, container.id AS cont_id FROM container WHERE level='100' ORDER BY corder ASC";
											$res = mysql_prefix_query($systemsql);
											jetstream_nav_head('System', '', '');
											while($navarray=mysql_fetch_array($res)){
												if ($navarray["cont_id"]==$container_id) {
													jetstream_nav_item($navarray["cname"], $navarray["cfile"], true);
												}
												else{
													jetstream_nav_item($navarray["cname"], $navarray["cfile"], false);
												}
											}
										}
									}
								}	# END if($_SESSION["uid"])

								?>
				</div>
			</td>
				<?
				}
				?>
			<td valign="top" bgcolor="#B8D2E7">
				<table width='100%' border='0' cellspacing='0' cellpadding='0'>
					<tr>
						<td class="top_heading">
						<?
							echo jetstream_tabs($toptab, $seltoptab, $nomenu);
						?>
						</td>
					</tr>
				</table>
				<table width='100%' border='0' cellspacing='0' cellpadding='0'>
					<tr bgcolor="#BFDAEF">
						<td height='33'>&nbsp;</td>
					</tr>
				</table>
			<?
}

function jetstream_nav_head($caption, $url, $selected=false) {
	global $jetstream_url, $jetstream_nav_top_selected, $jetstream_nav_bot_selected;
	if ($selected) {
		$jetstream_nav_top_selected=true;
		$jetstream_nav_bot_selected=true;
	}
	if ($jetstream_nav_bot_selected<>true) {
		$jetstream_nav_top_selected=false;
		$jetstream_nav_bot_selected=false;
	}
	else{
	}
	echo "<div class=\"menuhead\">".stripslashes($caption)."</div>";
}

function jetstream_nav_item($caption, $url, $selected=false) {
	global $jetstream_url, $jetstream_nav_top_selected, $jetstream_nav_bot_selected;
	if ($selected) {
		echo "<div class=\"menuselected\"><a href='" . $jetstream_url . $url . "'>".stripslashes($caption)."</a></div>";
		$jetstream_nav_top_selected=true;
		$jetstream_nav_bot_selected=true;
	}
	else{
		echo "<div class=\"menu\"><a href='" . $jetstream_url . $url . "'>".stripslashes($caption)."</a></div>";
		$jetstream_nav_top_selected=false;
		$jetstream_nav_bot_selected=false;
	}
}

function jetstream_footer() {
	GLOBAL $jetstream_version;
	?>
							<br />
							<div class="small">Powered by <a href="http://www.streamedge.com">Jetstream <sup>&copy;</sup></a> <? echo $jetstream_version; ?></div>
						</td>
						<td width='3'></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
	</body>
	</html>
	<?
}