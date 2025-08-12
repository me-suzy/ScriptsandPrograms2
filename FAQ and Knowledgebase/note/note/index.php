<?php
$LOGIN = "user";
$PASSWORD = "pass";
if ( (!isset($PHP_AUTH_USER)) || ! (($PHP_AUTH_USER == $LOGIN) && ( $PHP_AUTH_PW == "$PASSWORD" )) ) {
	header('WWW-Authenticate: Basic realm="NoteIt! Admin"');
	header("HTTP/1.0 401 Unauthorized");
	error("Sorry, authorized access only.");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en-AU">
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
    <meta name="author" content="haran" />
    <meta name="generator" content="author" />

    <!-- Navigational metadata for large websites (an accessibility feature): -->
    <link rel="top"      href="./index.html" title="Homepage" />
    <link rel="up"       href="./index.html" title="Up" />
    <link rel="first"    href="./index.html" title="First page" />
    <link rel="previous" href="./index.html" title="Previous page" />
    <link rel="next"     href="./index.html" title="Next page" />
    <link rel="last"     href="./index.html" title="Last page" />
    <link rel="toc"      href="./index.html" title="Table of contents" />
    <link rel="index"    href="./index.html" title="Site map" />

    <link rel="stylesheet" type="text/css" href="sinorca-screen.css" media="screen" title="Sinorca (screen)" />
    <link rel="stylesheet alternative" type="text/css" href="sinorca-screen-alt.css" media="screen" title="Sinorca (alternative)" />
    <link rel="stylesheet" type="text/css" href="sinorca-print.css" media="print" />

    <title>NoteIt! v1.0</title>
  </head>

  <body>
    <!-- For non-visual user agents: -->
      <div id="top"><a href="#main-copy" class="doNotDisplay doNotPrint">Skip to main content.</a></div>

    <!-- ##### Header ##### -->

    <div id="header">


      <div class="midHeader">
        <h1 class="headerTitle">NoteIt! v1.0</h1>
      </div>


    </div>

    <!-- ##### Side Bar ##### -->

    <div id="side-bar">
      <div>
        <p class="sideBarTitle">Navigation</p>
        <ul>
        <li><a href="index.php">&rsaquo; Home</a></li>
          <li><a href="?id=new">&rsaquo; New Note</a></li>
          <li><a href="?id=view">&rsaquo; View Notes</a></li>
        </ul>
      </div>



      <div class="lighterBackground">
        <p class="sideBarTitle">NoteIt!</p>
        <span class="sideBarText">
	  NoteIt! is developed by <a href="http://anm.centaurinetx.com">CodeZilla</a>.
        </span>
        <span class="sideBarText">
          You may use this free of charge, but you may not redistribute it if modified and/or claim it as your own.
        </span>
      </div>

      <div>
        <p class="sideBarTitle">Support</p>
        <span class="sideBarText">
          You can get limited support by  contacting <a href="mailto:aaron@artlangs.com">codezilla</a>. Since this is a free project, and I dont have lots of time to devote to it, it may take a couple of days for me to get back to you.
        </span>
      </div>
    </div>

    <!-- ##### Main Copy ##### -->

    <div id="main-copy">
    <?php
    if(!isset($_GET['id'])) {
    ?>
  
                <h1>NoteIt!</h1>
                <p>Welcome to NoteIt!, the simple note taking system! This script will give you the benefits of online (or on your own computer server) note taking, backed by a powerful MySQL backend.</p>
  <?php
  }
  else {
        $id = $_GET['id'];
        include "note.inc.php";
        $note = new Note;
        if($id == "new") {
        echo("<h1>Adding Note</h1>");
        ?>
        <p><form method="post" action="?id=new">
        <input type="text" name="title" value="Note Title" /><br />
        <textarea name="text" cols="70" rows="10">Note Text</textarea><br />
        <input type="submit" value="Add Note" />
        </form></p>
        <?php
        if($_POST) {
        $note->newNote();
        echo("Note added! View <a href=\"?id=view\">here</a>");
        }
        }
        if( $id == "view" ) {
		echo("<h1>Viewing Notes</h1>");
                if(isset($_GET['note'])) {
                $note->viewNote();
                }
                else {
                $note->viewNotes();
                }
	}
	if( $id == "delete" ) {
	echo("<h1>Deleting Note</h1>");
	$stat = $_GET['status'];
	        if($stat == "preview") {
		$noteID = $_GET['note'];
	        echo("<p>This CANNOT be reversed. Are you sure? <a href=\"?id=delete&note={$noteID}&status=delete\">Yes</a>. <a href=\"?id=view\">No</a>.</p>");
	        }
	        if($stat == "delete") {
	        $note->deleteNote();
		}
		}
	if($id == "edit") {
	echo("<h1>Editing Note</h1>");
	$stat = $_GET['status'];
	        if($stat == "load") {
		$note->getEditNote();

		?>
		<p><form method="post" action="?id=edit&note=<?php echo $note->noteID; ?>&status=edit"></p>
		<input type="text" name="title" value="<?php echo $note->noteTitle; ?>" /><br />
		<textarea name="text" cols="70" rows="10"><?php echo $note->noteText; ?></textarea><br />
		<input type="submit" value="Edit" /></form></p>
		<?php
		}
		if($stat == "edit") {
		$note->editNote();
		}
		}
}
                
  ?>
    </div>

    <!-- ##### Footer ##### -->


  </body>
</html>
