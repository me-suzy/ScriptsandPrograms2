<?php

// $Id: include.php 238 2005-11-21 10:43:34Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

$WB_DIRECTORY = substr(WB_PATH, strlen($_SERVER['DOCUMENT_ROOT'])).'/media/';

?>

<script type="text/javascript">
  _editor_url = "<?php echo WB_URL;?>/modules/htmlarea/htmlarea/";
  _editor_lang = "en";
</script>

<script type="text/javascript" src="<?php echo WB_URL;?>/modules/htmlarea/htmlarea/htmlarea.js"></script>
<script type="text/javascript">
	HTMLArea.loadPlugin("ContextMenu");
	HTMLArea.loadPlugin("TableOperations");
	window.onload = function() {
<?php
	foreach($id_list AS $textarea_id)
	{
		echo 'var editor = new HTMLArea("'.$textarea_id.'"); '
		.'editor.registerPlugin(ContextMenu);'
		.'editor.registerPlugin(TableOperations);'
		.'editor.config.pageStyle = "body { '.stripslashes(WYSIWYG_STYLE).' }";'
		.'editor.generate();';
	}
?>
}
</script>

<?php
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
?>
