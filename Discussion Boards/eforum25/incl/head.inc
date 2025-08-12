<?php $encd=explode(':',$lang[1]);print "<?xml version=\"1.0\" encoding=\"$encd[0]\"?>\n";
print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
print "\n<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"$encd[1]\">\n";
print "<head><meta http-equiv=\"content-type\" content=\"text/html; charset=$encd[0]\" />";?>
<script type="text/javascript" src="incl/all.js"></script>
<?php if(isset($color_changing)&&$color_changing==1){$gcss=(int)$gcss;
print "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/$gcss.css\" />";
}else{print "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/$default_css.css\" />";}?>