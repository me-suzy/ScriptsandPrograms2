<?php

if ($handle = opendir('../templates/')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != "..") {
           echo "$file\n";
       }
   }
   closedir($handle);
}

?>