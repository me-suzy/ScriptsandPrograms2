<?PHP
  if (!empty($rgMatches[3])) { mail("bugs@appindex.net", "[MWCHAT $CONFIG[System_Version]] Bug Report!", "$rgMatches[3]", "From: bugs@appindex.net"); Post_System("<I>$LOCALE[DIALOG_Bug_Sent]</I>", "syntax", $Room); } else { Post_System("<B>$LOCALE[DIALOG_Syntax_Error]</B> - /$rgMatches[1] { $LOCALE[COMMON_Bug_Report] }", "syntax", $Room); } ?>
