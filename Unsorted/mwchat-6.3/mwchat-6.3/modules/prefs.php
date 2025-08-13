<?PHP
  if ($STATUS[Registered] == "yes") { $rgProcess_INSERT = db_query(Validate(38), $CONN); } else { Post_System("<B>$LOCALE[DIALOG_Syntax_Error]</B> - /$rgMatches[1] $LOCALE[COMMON_Registered_Only]", "syntax", $Room); } ?>
