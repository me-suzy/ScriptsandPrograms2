<?php
   $engine_path = "../../../"; 
   include_once($engine_path . "lib.inc.php");
   $css_path = $_ENGINE['main_url']."/templates/".$config['template_folder']."/style.css";
?>
FCKConfig.EditorAreaCSS = '<?php echo $css_path ?>' ;
FCKConfig.EngineImageBrowser = '<?php echo $_ENGINE['main_url']."/browser.php" ?>';
// FCKConfig.SmileyPath = '<?php echo $_ENGINE['main_url']."/admin/includes/FCKeditor/editor/plugins/images/smiley/msn/" ?>';
FCKConfig.SmileyPath = '<?php echo $_ENGINE['main_url']."/smilie/" ?>';
FCKConfig.SmileyImages	= ['angry.gif','baaa.gif','biggrin.gif','biggrin2.gif','blush.gif','bored.gif','colgate.gif','confused.gif','cool2.gif','cry.gif','dontgetit.gif','dozingoff.gif','happy.gif','inlove.gif','lookaround.gif','mad.gif','music.gif','nervous.gif','plain.gif','sad.gif','sadlike.gif','sarcasm.gif','satisfied.gif','sigh.gif','sleepy.gif','sly.gif','smile.gif','sneaky.gif','sneaky2.gif','thumbs-up.gif','tounge.gif','tounge2.gif','turn.gif','whatsthat.gif','wink.gif','withstupid.gif','wow.gif','xmas.gif','yinyang.gif'] ;