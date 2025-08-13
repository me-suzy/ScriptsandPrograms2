<?PHP
  function Load_Language($Language, $szHardLang='FALSE') { global $CONFIG; global $Lang; if ($szHardLang != "FALSE") { return("$CONFIG[MWCHAT_Locales]/$szHardLang/$Language"); } if ($Lang) { return("$CONFIG[MWCHAT_Locales]/$Lang/$Language"); } else { return("$CONFIG[MWCHAT_Locales]/$CONFIG[Locales_Default]/$Language"); } } ?>
