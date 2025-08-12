<?php

	class Note {
	
	var $user,
	$pass,
	$host,
	$db,
	$noteID,
	$noteTitle,
	$noteText;
	
	function Note() {
	$this->user = "db username";
	$this->pass = "db pass";
	$this->db = "db name";
	$this->host = "localhost";
	
	mysql_connect($this->host, $this->user, $this->pass);
	mysql_select_db($this->db);
	}
	
	function newNote() {
	$this->noteTitle = $_POST['title'];
	$this->noteText = $_POST['text'];

	mysql_query("INSERT INTO notes ( noteID, noteTitle, noteText )
	        VALUES ( \"\", \"$this->noteTitle\", \"$this->noteText\" )") or die("MySQL Error:".mysql_error());
	}
	

        function viewNotes() {
		$query = "SELECT * FROM notes ORDER BY noteID DESC";
		$queryit = mysql_query($query) or die("MySQL error: ".mysql_error());
		$t = 0;
		echo("<div id=\"menul\">");
		while($row = mysql_fetch_array($queryit)) {
		$this->noteID = $row['noteID'];
		$this->noteTitle = $row['noteTitle'];
		$this->noteText = $row['noteText'];

		?>
		<li style="color:#bbddf0;"><a href="javascript:toggle('<?php echo $t; ?>');"><?php echo $this->noteTitle; ?></a> | <a href="?id=edit&note=<?php echo $this->noteID; ?>&status=load">Edit</a> | <a href="?id=delete&note=<?php echo $this->noteID; ?>&status=preview">Delete </a>|</li>
		<ul id="menul" style="color:#000000;">
		<li id="<?php echo $t;?>" style="color:#000000; background-color: #bbddff; padding:5px"><?php echo $this->noteText; ?></li></ul>
		<?php
		$t++;
		}
		echo("</ul>");
		echo("</div>");
		?>
		<script type="text/javascript">
document.getElementById('menul').style.listStyle="none";
<?php
for($x=0;$x<=$t;$x++ ) {
?>
document.getElementById('<?php echo $x; ?>').style.display="none"; // collapse list
<?php
}
?>
function toggle(list){
var listElementStyle=document.getElementById(list).style;
if (listElementStyle.display=="none"){
listElementStyle.display="block";
 }
else{ listElementStyle.display="none";
 }
}
</script>
		<?php
		}
	function deleteNote() {
	        $this->noteID = $_GET['note'];
	        mysql_query("DELETE FROM notes WHERE noteID = '$this->noteID'") or die("Error:".mysql_error());
	        echo("Note Deleted!");
	        }
	function getEditNote() {
	        $this->noteID = $_GET['note'];
	        $query = "SELECT * FROM notes WHERE noteID = '$this->noteID'";
	        $queryit = mysql_query($query);
	        $row = mysql_fetch_array( $queryit );
	        $this->noteTitle = $row['noteTitle'];
	        $this->noteText = $row['noteText'];
	        }
	function editNote() {
	        $this->noteID = $_GET['note'];
	        $this->noteTitle = $_POST['title'];
	        $this->noteText = $_POST['text'];
	        mysql_query("UPDATE notes SET noteID='$this->noteID', noteTitle='$this->noteTitle', noteText = '$this->noteText' WHERE noteID = '$this->noteID'");
	        }
	        
	        
 }
	        
