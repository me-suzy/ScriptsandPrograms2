<?PHP
$this->tree=array(
"index" => array( "label" => "Hello world!", "variable" => "", "level" => "1", "xslt" => "index.xsl", "childs"  => "", "type" => ""), 
"1.sample1" => array( "label" => "Step 1", "variable" => "sample1", "level" => "1", "xslt" => "sample2.xsl", "childs"  => array( "2.sample_link.sample1", ), "type" => ""), 
"1.sample2" => array( "label" => "Step 2", "variable" => "sample2", "level" => "1", "xslt" => "sample2.xsl", "childs"  => "", "type" => ""), 
"1.sample3" => array( "label" => "Step 3", "variable" => "sample3", "level" => "1", "xslt" => "sample1.xsl", "childs"  => "", "type" => ""), 
"1.feedback" => array( "label" => "Feedback", "variable" => "feedback", "level" => "1", "xslt" => "feedback.xsl", "childs"  => "", "type" => ""), 
"2.sample_link.sample1" => array( "label" => "Sample link", "variable" => "sample_link", "level" => "2", "xslt" => "sample2.xsl", "childs"  => "", "type" => "hidden"), 
"2.sample3.bcwb" => array( "label" => "Title of sample3", "variable" => "sample3", "level" => "2", "xslt" => "index.xsl", "childs"  => "", "type" => "hidden"), 
"1.bcwb" => array( "label" => "Title of bcwb", "variable" => "bcwb", "level" => "1", "xslt" => "index.xsl", "childs"  => array( "2.sample1.bcwb", ), "type" => "hidden"), 
"2.sample1.bcwb" => array( "label" => "Title of sample1", "variable" => "sample1", "level" => "2", "xslt" => "index.xsl", "childs"  => "", "type" => "hidden"), 
"1.guestbook" => array( "label" => "Guest book", "variable" => "guestbook", "level" => "1", "xslt" => "guestbook.xsl", "childs"  => "", "type" => ""), 
"1.gallery" => array( "label" => "Gallery", "variable" => "gallery", "level" => "1", "xslt" => "sample2.xsl", "childs"  => "", "type" => ""), 
);
?>
