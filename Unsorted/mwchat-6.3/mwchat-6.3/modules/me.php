<?PHP
  if (!empty($rgMatches[3])) { $rgMatches[3] = Clean_Variable($rgMatches[3], "html"); Post_System("$Username $rgMatches[3]", "local", $Room); } else { Post_System("<B>$LOCALE[DIALOG_Syntax_Error]</B> - /$rgMatches[1] { $LOCALE[COMMON_Action] }", "syntax", $Room); } ?>
