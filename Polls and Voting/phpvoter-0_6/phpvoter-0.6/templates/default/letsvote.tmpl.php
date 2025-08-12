<?php

$template_content = <<<ENDSTRING

<form action="{$tmplarr['posturl']}" method="post">
<input type="hidden" name="action" value="vote" />
<input type="hidden" name="voteid" value="{$tmplarr['vote']}" />
<b>{$tmplarr['date']} - {$tmplarr['question']}</b><br /><br />
{$tmplarr['comment']}
ENDSTRING;

for ($i = 0; $i < $tmplarr['number']; $i++) {
  $template_content .= <<<ENDSTRING
<input type="radio" name="phpvoter" value="{$tmplarr[$i]['choice_id']}" />
  {$tmplarr[$i]['choice']}<br />
ENDSTRING;
}

$template_content .= <<<ENDSTRING
<br />
<input type="submit" name="submit" value="{$lang['vote_button']}" />
</form>
ENDSTRING;

?>
