<?
$pagetitle="Help!";
require("../../includes/includes.inc.php");

$tabs[]="2.1";
if($_REQUEST["popup_help"]==true){
	//Show left menu and top tabs.
	$nomenu=true;
}
session_start();
header('P3P: CP="'.$_SETTINGS['ADMIN_P3P'].'"'); 
if (isset($_SESSION["uid"])){
	if ($nomenu<>true) {
		$toptab=array("1", "11");	    
	}
	$seltoptab="2";
	$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Jetbox help"),		//2.1 general overview
	);
	jetstream_header($pagetitle);
	show_help();
}
else{
	$toptab=array("1");
	$seltoptab="2";
	$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "About Jetbox"),		//2.1 general overview
	);
	jetstream_header($pagetitle);
	show_about();
}

function show_about(){
	global $jetstream_nav, $jetstream_url, $site_title, $jetstream_version;
	$tabs[]="2.1";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");

?>

<style>
H1 {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	FONT-SIZE: 230%;
	PADDING-BOTTOM: 0px;
	MARGIN: 0px 0px 8px;
	PADDING-TOP: 8px;
	FONT-FAMILY: "Trebuchet MS", Verdana, helvetica;
	color: #A31C00;

}

H2 {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	FONT-WEIGHT: bold;
	FONT-SIZE: 180%;
	PADDING-BOTTOM: 10px;
	MARGIN: 0px;
	COLOR: #8d8f7a;
	PADDING-TOP: 12px;
	FONT-FAMILY: 'Trebuchet MS', verdana, helvetica;
}

H3 {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	FONT-WEIGHT: bold;
	FONT-SIZE: 135%;
	PADDING-BOTTOM: 10px;
	MARGIN: 0px;
	COLOR: #8d8f7a;
	PADDING-TOP: 12px;
	FONT-FAMILY: 'Trebuchet MS', verdana, helvetica;
}

H4 {
	FONT-WEIGHT: bold;
	FONT-SIZE: 119%;
	MARGIN: 0px;
	COLOR: #8d8f7a;
	FONT-FAMILY: 'Trebuchet MS', verdana, helvetica;
	margin-top: 10px;
}

p {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	PADDING-BOTTOM: 8px;
	MARGIN: 0px;
	COLOR: #000000;
	FONT-FAMILY: Verdana, Arial, sans-serif;
	TEXT-ALIGN: justify
}

	</style>
<br>
		<table width='600' cellpadding='' cellspacing='0' border='0'><tr>
		<td width='80' valign='top'><IMG SRC="images/jetbox_info.gif" BORDER=0>&nbsp;&nbsp;</td>
		<td valign='top'><h1>About <? echo $site_title?></h1><p>Jetbox content management system is seriously tested on usability & has a professional intuitive interface. Its role based, with workflow and module orientated. All content is fully separated form layout. It uses php & mysql.
		</p>
		<p>
		<b><?echo $jetstream_version;?> of Jetbox is installed.</b>	
		</p>
		More help information can be found on <a href="http://jetbox.streamedge.com/" target="_blank">http://jetbox.streamedge.com</a>
		<p>
		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
      <TBODY>
        <TR>
          <TD class="lgrey nodec" style="PADDING-RIGHT: 11px; PADDING-LEFT: 0px; PADDING-BOTTOM: 8px; PADDING-TOP: 8px" vAlign=top>
            <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
                <TR>
                  <TD colspan="2"><h2>Jetbox CMS Features</h2>
                    <p>Jetbox CMS has many handy features for fast implementation and reduction of website maintenance costs.</p></TD>
                  </TR>
                <TR>
                  <TD width="50%" valign="top" style="padding: 0px 15px 0px 0px;"><h3>Powerful contents acquisition, management and presentation</h3>
                    <h4>Workflow, publication and tasks</h4>
                    <p>You can  lead contents via standard paths thru the organization (create, edit, publish). Assign task for contents creation (with a deadline), receive status information via e-mail.</p>
                    <h4>Archive items</h4>
                    <p>Older content can be archived. This feature is very usefull for reusing older content, instead of deleting it. Archived items are not displayed on the website and are separately displayed in de administration section. </p>
                    <h4>General and personal resources</h4>
                    <p>General design templates and  images can be used throughout the entire website and are stored centrally. Other texts and images are stored for personal use.</p>
                    <h4>Planning</h4>
                    <p>Publish and archive are easily to plan. You're website will always be up-to-date even when you are on holidays.</p>
                    <h4>Dynamic sitemap</h4>
                    <p>If new content is published on the website, the sitemap and navigation are instantly updated.</p>
                    <h4>Integrated user management</h4>
                    <p>User rights are easily controlled per part of the website. You can define the contents workflow and prevent unchecked content to be published on the website.</p>
                    <h4>Visitor statistics</h4>
                    <p>Per minute website  statistics. You instantly know which pages are most popular.</p>
                    <h4>Search engine  statistics</h4>
                    <p>This feature is even more powerfull than the visitor statistics. Get all the information form every search a visitor has made with the integrated search engine. You exactly know what information your visitors is searching for on your website.</p>
                    </TD>
                  <TD width="50%" valign="top" style="padding: 0px 0px 0px 15px;">                    <h3>Fast implementation and maintenance</h3>
                    <h4>Open source based (GPL or Professional License) </h4>
                    <P>To reduce license costs, Jetbox is based on Linux, Apache, Mysql &amp; Php. Jetbox is offered in GPL or Professional license with full professional support. </P>
                    <h4>Design templates</h4>
                    <P>Website layout and design is defined by layout templates. Templates can be changed per page or part.</P>
                    <h4>Change layout</h4>
                    <P>Change the layout of the website by simply changing the template.</P>
                    <h4>Dynamic pages</h4>
                    <P>Contents and design are combined just in time, the webpage is generated on-the-fly when a web user clicks to it.</P>
                    <h4>Separated contents from design</h4>
                    <P>Contents is stored separately in the database, it's easy to use the contents for other publications such as leaflets or power point presentations.</P>
                    <h4>Publish contents in several pages</h4>
                    <P>Publish the same contents in several pages, each with it's own design. Only once create a new news item. Your homepage is instantly updated  and all news items are  displayed on a separate page. </P>
                    <h4>Integrated search engine</h4>
                    <P>Your visitors can search your pages with an integrated fast and banner free search engine. </P>
                    </TD>
                </TR>
              </TBODY>
          </TABLE></TD>
        </TR>
      </TBODY>
    </TABLE>	

		
		
		</td></tr></table>
	<br>
<?
}


function show_help(){
	global $jetstream_nav, $jetstream_url, $site_title, $faq_url, $more_info_url;
	$tabs[]="2.1";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");

?>
<style>

H1 {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	FONT-SIZE: 230%;
	PADDING-BOTTOM: 0px;
	MARGIN: 0px 0px 8px;
	PADDING-TOP: 8px;
	FONT-FAMILY: "Trebuchet MS", Verdana, helvetica;
	color: #A31C00;

}

H2 {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	FONT-WEIGHT: bold;
	FONT-SIZE: 180%;
	PADDING-BOTTOM: 10px;
	MARGIN: 0px;
	COLOR: #8d8f7a;
	PADDING-TOP: 12px;
	FONT-FAMILY: 'Trebuchet MS', verdana, helvetica;
}

H3 {
	PADDING-RIGHT: 0px;
	PADDING-LEFT: 0px;
	FONT-WEIGHT: bold;
	FONT-SIZE: 135%;
	PADDING-BOTTOM: 10px;
	MARGIN: 0px;
	COLOR: #8d8f7a;
	PADDING-TOP: 12px;
	FONT-FAMILY: 'Trebuchet MS', verdana, helvetica;
}

H4 {
	FONT-WEIGHT: bold;
	FONT-SIZE: 119%;
	MARGIN: 0px;
	COLOR: #8d8f7a;
	FONT-FAMILY: 'Trebuchet MS', verdana, helvetica;
	margin-top: 10px;
}
</style>

<br><br>
<table width='600' cellpadding='' cellspacing='0' border='0'>
	<tr>
		<td width='80' valign='top'><IMG SRC="images/jetbox_info.gif" BORDER=0>&nbsp;&nbsp;</td>
		<td valign='top'><h1><? echo $site_title?> help</h1></td></tr>
	<form id="odSearchForm" method="GET" action="<?echo $faq_url;?>">
	<input type="hidden" name="p" value="search">
	<input type="hidden" name="cat_id" value="">
	<input name="srcWhat" type="hidden" class="odCheckbox" value="">
	<input name="dosearch" type="hidden" value="1">
	<tr><td></td><td><h3>Search in the online knowledge database.</h3><b>Keyword/phrase: </b><input name="srcText" type="textbox" size="30"> <input name="submit" type="submit" value="Go"></td></tr>
	</form>
	<tr><td></td><td><br><p>More help information can be found on <a href="<?echo $more_info_url;?>" target="_blank"><?echo $more_info_url;?></a>
		<p>
</td></tr>
	</table>

<?
}

jetstream_footer();
?>
