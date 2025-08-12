<li><a href=<?=$app_url["front"]?> onmouseover="showtip('<?=msg("link_to") . $app_url["front"] ?> ')" onmouseout="hidetip()"><img src="i/buttons/home_s.gif" alt="" width=20 height=20 border=0></a></li>
<li><a href=<?=$app_url["webxadmin"]?>  onmouseover="showtip('<?=msg("link_to_app_home")?> ')" onmouseout="hidetip()"><img src="i/buttons/webxadmin.gif" alt="" width=20 height=20 border=0></a></li>
<li><a href=<?=$app_url["help"]?> onmouseover="showtip('<?=msg("link_to_help")?> ')" onmouseout="hidetip()"><img src="i/buttons/help_s.gif" alt="" width=20 height=20 border=0></a></li>

<?if ($arr_g_self[count($arr_g_self)-1]==$app_url["webxadmin"]) { ?>
<li><a href=javascript:goto_href("a=null&i=null&p_i=null") onmouseover="showtip('<?=msg("link_to_list")?> ')" onmouseout="hidetip()"><img src="i/buttons/cancel_s.gif" alt="" width=20 height=20 border=0></a></li>
<li><a href='javascript:goto_href("a=frm&i=null")'  onmouseover="showtip('<?=msg("link_to_new")?> ')" onmouseout="hidetip()"><img src="i/buttons/add_s.gif" alt="" width=20 height=20 border=0></a></li>
<?if ($g_a=='frm' ||  $g_a=="enr") {?>
<?if ($g_i!='' ) {?>
<li><a href='javascript:confirm_url(get_href("a=sup"), "<?=msg("confirm_delete")?>")'   onmouseover="showtip('<?=msg("link_to_delete")?> ')" onmouseout="hidetip()"><img src="i/buttons/sup_s.gif" alt="" width=20 height=20 border=0></a></li><?}?>
<li><a href='javascript:valid_submit("a=enr")'  onmouseover="showtip('<?=msg("link_to_save")?> ')" onmouseout="hidetip()"><img src="i/buttons/save_s.gif" alt="" width=20 height=20 border=0></a></li>
<li><a href='javascript:valid_submit("a=enr_close")'  onmouseover="showtip('<?=msg("link_to_save_close")?> ')" onmouseout="hidetip()"><img src="i/buttons/save_close_s.gif" alt="" width=20 height=20 border=0></a></li>
<?}?>
<?}?>