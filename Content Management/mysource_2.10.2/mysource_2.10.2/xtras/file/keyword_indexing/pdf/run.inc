<?  ##############################################
   ### MySource ------------------------------###
  ##- Keyword Indexing Xtra ---- PHP4 --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/file/keyword_indexing/pdf/run.inc
## Desc: Functions for extracting words from PDF documents
## $Source: /home/cvsroot/xtras/file/keyword_indexing/pdf/run.inc,v $
## $Revision: 2.3 $
## $Author: ramato $
## $Date: 2003/12/05 05:15:21 $
#######################################################################

 ##########################################
# exctracts words from a PDF document
function extract_words($file) {
	global $DATA_PATH, $XTRAS_PATH;
	
	$file_type = get_file_type($file);
	
	$indexer_path = "$XTRAS_PATH/file/keyword_indexing/$file_type/bin";

	$file = ereg_replace("\"","\\\"",$file);
	$tmp_file = $DATA_PATH . "/pdf_".time();

	copy($file,$tmp_file);
	# Run a program that converts a PDF to HTML, set environment variable of bin_path
	putenv("DIR=$DATA_PATH"); # mor reliable than /tmp ..no, really
	putenv("PDFTOHTMLBIN_DIR=$indexer_path");
	$cmd = "$indexer_path/pdftohtml $tmp_file"; 
	$output =`$cmd`;
	unlink($tmp_file);
	
	if(!$output) {
		return false;
	}
	// strip out the tags
	$output = strip_tags_padded($output);
	// strip out the whitespace
	$output = trim(preg_replace("/[\s]+/", " ", $output));
	// strip out words that wouldn't be keywords
	$output = retrieve_keywords($output);
	
	return $output;
}