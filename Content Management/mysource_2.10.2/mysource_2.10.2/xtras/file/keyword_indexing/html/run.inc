<?  ##############################################
   ### MySource ------------------------------###
  ##- Keyword Indexing Xtra ---- PHP4 --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: xtras/file/keyword_indexing/html/run.inc
## Desc: Functions for extracting words from HTML documents
## $Source: /home/cvsroot/xtras/file/keyword_indexing/html/run.inc,v $
## $Revision: 2.3 $
## $Author: ramato $
## $Date: 2003/12/05 05:15:21 $
#######################################################################

 ##########################################
# exctracts words from a HTM document
function extract_words($file) {
	$file = ereg_replace("\"","\\\"",$file);
	$output = file_to_string($file);
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