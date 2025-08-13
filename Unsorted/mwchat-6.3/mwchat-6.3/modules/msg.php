<?PHP
  $rgArgs[0] = Clean_Variable($rgArgs[0], "lowercase"); if (!empty($rgArgs[0]) and !empty($rgArgs[1]) and $rgArgs[0] != $Username) { $rgArgs[0] = Clean_Variable($rgArgs[0], "all"); Post_Private($rgArgs[0], $szDialogCommand); } else { Post_System("<B>$LOCALE[DIALOG_Syntax_Error]</B> - /$rgMatches[1] { $LOCALE[COMMON_Username] } { $LOCALE[COMMON_Text] }", "syntax", $Room); } ?>

