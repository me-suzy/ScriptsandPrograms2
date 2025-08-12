<?if ($nb_pages>1) {?>
<li><?=$row_list_begin?>..<?=$row_list_end?> / <?=$row_list_count?>
<?if ($g_p!=1) {?><a href=javascript:goto_href("p=<?=$g_p-1?>")><img src="i/buttons/arrow_prev.gif"  hspace=2 align=absmiddle alt="" width=16 height=16 border=0></a><?}?>
<?if ($g_p!=$nb_pages) {?><a href=javascript:goto_href("p=<?=$g_p+1?>")><img src="i/buttons/arrow_next.gif"  hspace=2 align=absmiddle alt="" width=16 height=16 border=0></a><?}?>
 p.<?=$g_p?> / <?=$nb_pages?></li>
<?} else {?>
<li> <?=$row_list_count!="0"?$row_list_count:""?></li>
<?}?>