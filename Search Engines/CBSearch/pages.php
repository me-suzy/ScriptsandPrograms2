<?php if (count($pages)){ ?>
Pages -  
<?php foreach($pages as $page){if($page['current']){ ?>

<!-- CURRENT PAGE -->
[<b><?=$page["number"];?></b>]
<!-- /CURRENT PAGE -->

<?php }else{ ?>

<!-- NON-CURRENT PAGE -->
[<a href="<?=$page["url"];?>"><b><?=$page["number"];?></b></a>]
<!-- /NON-CURRENT PAGE -->

<?php }}} ?><p>