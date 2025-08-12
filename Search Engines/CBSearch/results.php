<?php if (count($results)) { ?>

<!-- SEARCH RESULTS -->
Your search for <b><?=$keywords;?></b> returned <?=$totalresults;?> results<br><br>

<?php foreach ($results as $result) {  ?>

<!-- SEARCH RESULT ROW -->
&#149; <a href="<?=$result["url"];?>" target="_blank" ><?=$result["title"];?></a><br>
<?=$result["description"];?><br><br>
<!-- /SEARCH RESULT ROW -->

<?php }}elseif ($keywords){ ?>

<!-- NO RESULTS -->
Your search for <b><?=$keywords;?></b> returned no results<br><br>

<?php } ?>