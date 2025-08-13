
<?php require_once(FS_PATH."/etc/locale/".LANG_PACK."/inc-lib_inc-footer.php"); ?>

   <!-- Begin Primary Footer -->
      
      <?php
      
        netjuke_session('update');
        
        if (strlen(CUSTOM_FOOTER) > 0) {
        
          include (CUSTOM_FOOTER);

          $dbconn->Close();
         
        }
         
      ?>
      
      <a name="PageBot">&nbsp;</a>
      </BODY>
      </HTML>
   <!--  End Primary Footer  -->
