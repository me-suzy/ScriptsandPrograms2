<?PHP
  if ($STATUS[SilentHTML] != "true") { echo "  </FONT COLOR>\n"; echo "  </CENTER>\n"; echo " </BODY>\n"; } if (isset($FooterJava)) { echo "  $FooterJava"; } echo "</HTML>\n"; if (isset($CONN)) { db_close($CONN); } exit; ?>
