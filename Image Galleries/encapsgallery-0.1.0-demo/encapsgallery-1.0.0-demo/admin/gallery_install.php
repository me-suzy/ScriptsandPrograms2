<?
include("gallery_head.php");
if($config['demo']=='on'){
	echo '<h1>Update disabled with a demo</h1>';
	return;
}

$db = new DB_sql($config["db_host"],$config["db_user"],$config["db_pass"],$config["db_name"],$config["db_type"],1);
$db->usedump('sql/gallery.'.$config["db_type"].'.sql'); 

		$filelist = array();
		$folder_content=opendir("../rwx_gallery/");
		$count=0;
		while($item=readdir($folder_content))
			if($item != "." && $item != "..")
			{
				//if($config['demo'] == 0)
				//unlink("../rwx_gallery/$item");
			}

?>
<h3>
Do not forget to chmod 777 for "rwx_gallery" folder<br>
</h3>

<a href="gallery.php">Run admin back-end&gt;&gt;</a>
<br>
<a href="../gallery.php">Run gallery front-end&gt;&gt;</a>