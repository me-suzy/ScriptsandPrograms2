<?PHP
  if ($CONFIG[Chat_MOTD] == "true") { $rgProcess_INSERT = db_query(Validate(27), $CONN); } else { Post_System("<B>$LOCALE[DIALOG_Syntax_Error]</B> - /$rgMatches[1] $LOCALE[COMMON_Function_Invalid]", "syntax", $Room); } ?>
