<?php
   require('./config.php');
   global $_PSL, $_BE;

   $itemLookup = pslNew('BEDB');
   $searchUrl = $oldArticleID = clean($_GET['id']);
   $query = "SELECT newArticleID,newSectionID FROM be_oldItemLookup WHERE oldArticleID='$oldArticleID'";
   if ($itemLookup->query($query) AND $itemLookup->next_record()) {
      $newArticleID = $itemLookup->Record['newArticleID'];
      $newSectionID = $itemLookup->Record['newSectionID'];
      Header("Location: ".$_PSL['rooturl']."/".$_BE['article_file']."/".$newSectionID."/".$newArticleID);
   } else {
      Header("Location: ".$_PSL['rooturl']."/search.php");
   }
?>