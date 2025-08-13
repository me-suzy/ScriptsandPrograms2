<?PHP
  if (isset($STATUS[Theme]) and file_exists("$CONFIG[MWCHAT_Themes]/$STATUS[Theme]")) { $fcontents = file ("$CONFIG[MWCHAT_Themes]/$STATUS[Theme]"); while (list ($line_num, $line) = each ($fcontents)) { $line = trim($line); $rgLines = explode(":", $line); if (empty($rgLines[1])) { continue; } $szCV = "Color_" . $rgLines[0]; $CONFIG[$szCV] = $rgLines[1]; } } ?>
