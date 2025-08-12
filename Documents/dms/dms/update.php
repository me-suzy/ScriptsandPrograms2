<?php
#####################################################################
# NAME/ PURPOSE - this file processes file updates
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done
#
# NOTE: Due to the nature of this program being an open-source project,
#       refer to the project website https://sourceforge.net/projects/gssdms/
#		for the most current status on this project and all files within it
#
#####################################################################

require('lib/config.inc');
require('lib/auth.inc');
require('lib/classes.inc');
require('lib/functions.inc');

function upload_failed($message) {
   global $userfile;

    // Trash it.
    @unlink($userfile);

    echo "<h2>Error: $message</h2>\n";
    print_footer();
    exit;
}

$user = new user($login);
$document = new document($doc_id);

print_header("Updating Document #$document->id");

if(!isset($userfile)){
	upload_failed("Document was not found");
}

if($userfile_name != $document->name ){
    upload_failed("Document should be called $document->name");
}

if(!file_exists($userfile)){
    upload_failed("Document was not uploaded");
}

$fp = fopen($userfile, "r");

if(!$fp){
    upload_failed("Cannot open uploaded document");
}

$content = fread($fp, $userfile_size);
fclose($fp);
unlink($userfile);

$res = @mysql_query("REPLACE INTO documents_content(id,content) VALUES($document->id,'". base64_encode($content) ."')");

switch( mysql_errno() ) {

    // Updated OK.
    case 0:
        echo "<h2>Updated ". htmlspecialchars(stripslashes($userfile_name)) ." ($userfile_size bytes) to revision ". ($document->revision + 1) ."</h2>\n";

        @mysql_query("UPDATE documents SET revision = revision+1 WHERE id=$document->id");
        @mysql_query("UPDATE documents SET modified = NOW() WHERE id=$document->id");

        // New info?
        if($info != NULL && $info != "") {
            @mysql_query("REPLACE INTO documents_info(id,info) VALUES($document->id,'". addslashes($info) ."')");
            if(mysql_errno()) {
                echo "<h3>New info <em>not</em> saved<br>". mysql_error() ."</h3>\n";
            } else {
                echo "<h3>New info saved</h3>\n";
            }
        }

        // New keywords?
        if($keywords != NULL && $keywords != "") {
            // Delete the old keywords.
            @mysql_query("DELETE FROM documents_keywords WHERE id=$document->id");
            $keywords = ereg_replace(",", " ", $keywords);
            $keywords = ereg_replace("  ", " ", $keywords);
            $keywords = explode(" ", $keywords);
            $keyword = current($keywords);
            echo "<h3>Using keywords: \n";
            do {
                @mysql_query("INSERT INTO documents_keywords(id,keyword) VALUES($document->id,'". addslashes($keyword) ."')");
                if(mysql_errno())
                    echo "<br />Error, $keyword not saved\n";
                else
                    echo "<br />$keyword\n";
            } while ($keyword = next($keywords));
            echo "</h3>\n";
        }
        break;

    default:
        upload_failed( "Could not save updated content<br />Error: ". mysql_error() );
        break;
}

print_footer();

?>