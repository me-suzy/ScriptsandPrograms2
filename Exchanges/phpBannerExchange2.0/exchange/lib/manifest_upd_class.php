<?
$file_rev="041305";
// Updates the manifest for file versioning.
// section 1: Get info from the files.

// begin common section
$array=file('../cou.php');
$FILE_common_cou=$array[1];
$FILE_common_cou=str_replace('$file_rev=','',$FILE_common_cou);
$FILE_common_cou=str_replace('"','',$FILE_common_cou);
$FILE_common_cou=str_replace(';','',$FILE_common_cou);
$FILE_common_cou=rtrim($FILE_common_cou);

$array=file('../click.php');
$FILE_common_click=$array[1];
$FILE_common_click=str_replace('$file_rev=','',$FILE_common_click);
$FILE_common_click=str_replace('"','',$FILE_common_click);
$FILE_common_click=str_replace(';','',$FILE_common_click);
$FILE_common_click=rtrim($FILE_common_click);

$array=file('../common_menuing.php');
$FILE_common_menu=$array[1];
$FILE_common_menu=str_replace('$file_rev=','',$FILE_common_menu);
$FILE_common_menu=str_replace('"','',$FILE_common_menu);
$FILE_common_menu=str_replace(';','',$FILE_common_menu);
$FILE_common_menu=rtrim($FILE_common_menu);

$array=file('../cookies.php');
$FILE_common_cookies=$array[1];
$FILE_common_cookies=str_replace('$file_rev=','',$FILE_common_cookies);
$FILE_common_cookies=str_replace('"','',$FILE_common_cookies);
$FILE_common_cookies=str_replace(';','',$FILE_common_cookies);
$FILE_common_cookies=rtrim($FILE_common_cookies);

$array=file('../dblog.php');
$FILE_common_dblog=$array[1];
$FILE_common_dblog=str_replace('$file_rev=','',$FILE_common_dblog);
$FILE_common_dblog=str_replace('"','',$FILE_common_dblog);
$FILE_common_dblog=str_replace(';','',$FILE_common_dblog);
$FILE_common_dblog=rtrim($FILE_common_dblog);

$array=file('../faq.php');
$FILE_common_faq=$array[1];
$FILE_common_faq=str_replace('$file_rev=','',$FILE_common_faq);
$FILE_common_faq=str_replace('"','',$FILE_common_faq);
$FILE_common_faq=str_replace(';','',$FILE_common_faq);
$FILE_common_faq=rtrim($FILE_common_faq);

$array=file('../footer.php');
$FILE_common_footer=$array[1];
$FILE_common_footer=str_replace('$file_rev=','',$FILE_common_footer);
$FILE_common_footer=str_replace('"','',$FILE_common_footer);
$FILE_common_footer=str_replace(';','',$FILE_common_footer);
$FILE_common_footer=rtrim($FILE_common_footer);

$array=file('../index.php');
$FILE_common_index=$array[1];
$FILE_common_index=str_replace('$file_rev=','',$FILE_common_index);
$FILE_common_index=str_replace('"','',$FILE_common_index);
$FILE_common_index=str_replace(';','',$FILE_common_index);
$FILE_common_index=rtrim($FILE_common_index);

$array=file('../overall.php');
$FILE_common_overall=$array[1];
$FILE_common_overall=str_replace('$file_rev=','',$FILE_common_overall);
$FILE_common_overall=str_replace('"','',$FILE_common_overall);
$FILE_common_overall=str_replace(';','',$FILE_common_overall);
$FILE_common_overall=rtrim($FILE_common_overall);

$array=file('../recoverpw.php');
$FILE_common_recoverpw=$array[1];
$FILE_common_recoverpw=str_replace('$file_rev=','',$FILE_common_recoverpw);
$FILE_common_recoverpw=str_replace('"','',$FILE_common_recoverpw);
$FILE_common_recoverpw=str_replace(';','',$FILE_common_recoverpw);
$FILE_common_recoverpw=rtrim($FILE_common_recoverpw);

$array=file('../resetpw.php');
$FILE_common_resetpw=$array[1];
$FILE_common_resetpw=str_replace('$file_rev=','',$FILE_common_resetpw);
$FILE_common_resetpw=str_replace('"','',$FILE_common_resetpw);
$FILE_common_resetpw=str_replace(';','',$FILE_common_resetpw);
$FILE_common_resetpw=rtrim($FILE_common_resetpw);

$array=file('../rules.php');
$FILE_common_rules=$array[1];
$FILE_common_rules=str_replace('$file_rev=','',$FILE_common_rules);
$FILE_common_rules=str_replace('"','',$FILE_common_rules);
$FILE_common_rules=str_replace(';','',$FILE_common_rules);
$FILE_common_rules=rtrim($FILE_common_rules);

$array=file('../signup.php');
$FILE_common_signup=$array[1];
$FILE_common_signup=str_replace('$file_rev=','',$FILE_common_signup);
$FILE_common_signup=str_replace('"','',$FILE_common_signup);
$FILE_common_signup=str_replace(';','',$FILE_common_signup);
$FILE_common_signup=rtrim($FILE_common_signup);

$array=file('../signupconfirm.php');
$FILE_common_signconf=$array[1];
$FILE_common_signconf=str_replace('$file_rev=','',$FILE_common_signconf);
$FILE_common_signconf=str_replace('"','',$FILE_common_signconf);
$FILE_common_signconf=str_replace(';','',$FILE_common_signconf);
$FILE_common_signconf=rtrim($FILE_common_signconf);

$array=file('../top.php');
$FILE_common_top=$array[1];
$FILE_common_top=str_replace('$file_rev=','',$FILE_common_top);
$FILE_common_top=str_replace('"','',$FILE_common_top);
$FILE_common_top=str_replace(';','',$FILE_common_top);
$FILE_common_top=rtrim($FILE_common_top);

$array=file('../view.php');
$FILE_common_view=$array[1];
$FILE_common_view=str_replace('$file_rev=','',$FILE_common_view);
$FILE_common_view=str_replace('"','',$FILE_common_view);
$FILE_common_view=str_replace(';','',$FILE_common_view);
$FILE_common_view=rtrim($FILE_common_view);

// end common section.
// begin user section.

$array=file('../client/addconfirm.php');
$FILE_user_addconfirm=$array[1];
$FILE_user_addconfirm=str_replace('$file_rev=','',$FILE_user_addconfirm);
$FILE_user_addconfirm=str_replace('"','',$FILE_user_addconfirm);
$FILE_user_addconfirm=str_replace(';','',$FILE_user_addconfirm);
$FILE_user_addconfirm=rtrim($FILE_user_addconfirm);

$array=file('../client/banners.php');
$FILE_user_banners=$array[1];
$FILE_user_banners=str_replace('$file_rev=','',$FILE_user_banners);
$FILE_user_banners=str_replace('"','',$FILE_user_banners);
$FILE_user_banners=str_replace(';','',$FILE_user_banners);
$FILE_user_banners=rtrim($FILE_user_banners);

$array=file('../client/category.php');
$FILE_user_category=$array[1];
$FILE_user_category=str_replace('$file_rev=','',$FILE_user_category);
$FILE_user_category=str_replace('"','',$FILE_user_category);
$FILE_user_category=str_replace(';','',$FILE_user_category);
$FILE_user_category=rtrim($FILE_user_category);

$array=file('../client/categoryconfirm.php');
$FILE_user_categoryconf=$array[1];
$FILE_user_categoryconf=str_replace('$file_rev=','',$FILE_user_categoryconf);
$FILE_user_categoryconf=str_replace('"','',$FILE_user_categoryconf);
$FILE_user_categoryconf=str_replace(';','',$FILE_user_categoryconf);
$FILE_user_categoryconf=rtrim($FILE_user_categoryconf);

$array=file('../client/changeurlconf.php');
$FILE_user_changeurlconf=$array[1];
$FILE_user_changeurlconf=str_replace('$file_rev=','',$FILE_user_changeurlconf);
$FILE_user_changeurlconf=str_replace('"','',$FILE_user_changeurlconf);
$FILE_user_changeurlconf=str_replace(';','',$FILE_user_changeurlconf);
$FILE_user_changeurlconf=rtrim($FILE_user_changeurlconf);

$array=file('../client/clicklog.php');
$FILE_user_clicklog=$array[1];
$FILE_user_clicklog=str_replace('$file_rev=','',$FILE_user_clicklog);
$FILE_user_clicklog=str_replace('"','',$FILE_user_clicklog);
$FILE_user_clicklog=str_replace(';','',$FILE_user_clicklog);
$FILE_user_clicklog=rtrim($FILE_user_clicklog);

$array=file('../client/client_menuing.php');
$FILE_user_menu=$array[1];
$FILE_user_menu=str_replace('$file_rev=','',$FILE_user_menu);
$FILE_user_menu=str_replace('"','',$FILE_user_menu);
$FILE_user_menu=str_replace(';','',$FILE_user_menu);
$FILE_user_menu=rtrim($FILE_user_menu);

$array=file('../client/commerce.php');
$FILE_user_commerce=$array[1];
$FILE_user_commerce=str_replace('$file_rev=','',$FILE_user_commerce);
$FILE_user_commerce=str_replace('"','',$FILE_user_commerce);
$FILE_user_commerce=str_replace(';','',$FILE_user_commerce);
$FILE_user_commerce=rtrim($FILE_user_commerce);

$array=file('../client/deletebanner.php');
$FILE_user_delban=$array[1];
$FILE_user_delban=str_replace('$file_rev=','',$FILE_user_delban);
$FILE_user_delban=str_replace('"','',$FILE_user_delban);
$FILE_user_delban=str_replace(';','',$FILE_user_delban);
$FILE_user_delban=rtrim($FILE_user_delban);

$array=file('../client/deleteconfirm.php');
$FILE_user_delbanconf=$array[1];
$FILE_user_delbanconf=str_replace('$file_rev=','',$FILE_user_delbanconf);
$FILE_user_delbanconf=str_replace('"','',$FILE_user_delbanconf);
$FILE_user_delbanconf=str_replace(';','',$FILE_user_delbanconf);
$FILE_user_delbanconf=rtrim($FILE_user_delbanconf);

$array=file('../client/editbanner.php');
$FILE_user_editbanner=$array[1];
$FILE_user_editbanner=str_replace('$file_rev=','',$FILE_user_editbanner);
$FILE_user_editbanner=str_replace('"','',$FILE_user_editbanner);
$FILE_user_editbanner=str_replace(';','',$FILE_user_editbanner);
$FILE_user_editbanner=rtrim($FILE_user_editbanner);

$array=file('../client/editinfo.php');
$FILE_user_editinfo=$array[1];
$FILE_user_editinfo=str_replace('$file_rev=','',$FILE_user_editinfo);
$FILE_user_editinfo=str_replace('"','',$FILE_user_editinfo);
$FILE_user_editinfo=str_replace(';','',$FILE_user_editinfo);
$FILE_user_editinfo=rtrim($FILE_user_editinfo);

$array=file('../client/editpass.php');
$FILE_user_editpass=$array[1];
$FILE_user_editpass=str_replace('$file_rev=','',$FILE_user_editpass);
$FILE_user_editpass=str_replace('"','',$FILE_user_editpass);
$FILE_user_editpass=str_replace(';','',$FILE_user_editpass);
$FILE_user_editpass=rtrim($FILE_user_editpass);

$array=file('../client/emailstats.php');
$FILE_user_emailstats=$array[1];
$FILE_user_emailstats=str_replace('$file_rev=','',$FILE_user_emailstats);
$FILE_user_emailstats=str_replace('"','',$FILE_user_emailstats);
$FILE_user_emailstats=str_replace(';','',$FILE_user_emailstats);
$FILE_user_emailstats=rtrim($FILE_user_emailstats);

$array=file('../client/gethtml.php');
$FILE_user_gethtml=$array[1];
$FILE_user_gethtml=str_replace('$file_rev=','',$FILE_user_gethtml);
$FILE_user_gethtml=str_replace('"','',$FILE_user_gethtml);
$FILE_user_gethtml=str_replace(';','',$FILE_user_gethtml);
$FILE_user_gethtml=rtrim($FILE_user_gethtml);

$array=file('../client/index.php');
$FILE_user_index=$array[1];
$FILE_user_index=str_replace('$file_rev=','',$FILE_user_index);
$FILE_user_index=str_replace('"','',$FILE_user_index);
$FILE_user_index=str_replace(';','',$FILE_user_index);
$FILE_user_index=rtrim($FILE_user_index);

$array=file('../client/infoconfirm.php');
$FILE_user_infoconfirm=$array[1];
$FILE_user_infoconfirm=str_replace('$file_rev=','',$FILE_user_infoconfirm);
$FILE_user_infoconfirm=str_replace('"','',$FILE_user_infoconfirm);
$FILE_user_infoconfirm=str_replace(';','',$FILE_user_infoconfirm);
$FILE_user_infoconfirm=rtrim($FILE_user_infoconfirm);

$array=file('../client/logout.php');
$FILE_user_logout=$array[1];
$FILE_user_logout=str_replace('$file_rev=','',$FILE_user_logout);
$FILE_user_logout=str_replace('"','',$FILE_user_logout);
$FILE_user_logout=str_replace(';','',$FILE_user_logout);
$FILE_user_logout=rtrim($FILE_user_logout);

$array=file('../client/passconfirm.php');
$FILE_user_passconfirm=$array[1];
$FILE_user_passconfirm=str_replace('$file_rev=','',$FILE_user_passconfirm);
$FILE_user_passconfirm=str_replace('"','',$FILE_user_passconfirm);
$FILE_user_passconfirm=str_replace(';','',$FILE_user_passconfirm);
$FILE_user_passconfirm=rtrim($FILE_user_passconfirm);

$array=file('../client/promo.php');
$FILE_user_promo=$array[1];
$FILE_user_promo=str_replace('$file_rev=','',$FILE_user_promo);
$FILE_user_promo=str_replace('"','',$FILE_user_promo);
$FILE_user_promo=str_replace(';','',$FILE_user_promo);
$FILE_user_promo=rtrim($FILE_user_promo);

$array=file('../client/remove.php');
$FILE_user_remove=$array[1];
$FILE_user_remove=str_replace('$file_rev=','',$FILE_user_remove);
$FILE_user_remove=str_replace('"','',$FILE_user_remove);
$FILE_user_remove=str_replace(';','',$FILE_user_remove);
$FILE_user_remove=rtrim($FILE_user_remove);

$array=file('../client/stats.php');
$FILE_user_stats=$array[1];
$FILE_user_stats=str_replace('$file_rev=','',$FILE_user_stats);
$FILE_user_stats=str_replace('"','',$FILE_user_stats);
$FILE_user_stats=str_replace(';','',$FILE_user_stats);
$FILE_user_stats=rtrim($FILE_user_stats);

$array=file('../client/uploadbanner.php');
$FILE_user_uploadbanner=$array[1];
$FILE_user_uploadbanner=str_replace('$file_rev=','',$FILE_user_uploadbanner);
$FILE_user_uploadbanner=str_replace('"','',$FILE_user_uploadbanner);
$FILE_user_uploadbanner=str_replace(';','',$FILE_user_uploadbanner);
$FILE_user_uploadbanner=rtrim($FILE_user_uploadbanner);

// end client section.
// begin admin section.

$array=file('../admin/addacct.php');
$FILE_admin_addacct=$array[1];
$FILE_admin_addacct=str_replace('$file_rev=','',$FILE_admin_addacct);
$FILE_admin_addacct=str_replace('"','',$FILE_admin_addacct);
$FILE_admin_addacct=str_replace(';','',$FILE_admin_addacct);
$FILE_admin_addacct=rtrim($FILE_admin_addacct);

$array=file('../admin/addacctconfirm.php');
$FILE_admin_addacctconf=$array[1];
$FILE_admin_addacctconf=str_replace('$file_rev=','',$FILE_admin_addacctconf);
$FILE_admin_addacctconf=str_replace('"','',$FILE_admin_addacctconf);
$FILE_admin_addacctconf=str_replace(';','',$FILE_admin_addacctconf);
$FILE_admin_addacctconf=rtrim($FILE_admin_addacctconf);

$array=file('../admin/addadmin.php');
$FILE_admin_addadmin=$array[1];
$FILE_admin_addadmin=str_replace('$file_rev=','',$FILE_admin_addadmin);
$FILE_admin_addadmin=str_replace('"','',$FILE_admin_addadmin);
$FILE_admin_addadmin=str_replace(';','',$FILE_admin_addadmin);
$FILE_admin_addadmin=rtrim($FILE_admin_addadmin);

$array=file('../admin/addcat.php');
$FILE_admin_addcat=$array[1];
$FILE_admin_addcat=str_replace('$file_rev=','',$FILE_admin_addcat);
$FILE_admin_addcat=str_replace('"','',$FILE_admin_addcat);
$FILE_admin_addcat=str_replace(';','',$FILE_admin_addcat);
$FILE_admin_addcat=rtrim($FILE_admin_addcat);

$array=file('../admin/admin_menuing.php');
$FILE_admin_menu=$array[1];
$FILE_admin_menu=str_replace('$file_rev=','',$FILE_admin_menu);
$FILE_admin_menu=str_replace('"','',$FILE_admin_menu);
$FILE_admin_menu=str_replace(';','',$FILE_admin_menu);
$FILE_admin_menu=rtrim($FILE_admin_menu);

$array=file('../admin/adminconfirm.php');
$FILE_admin_adminconf=$array[1];
$FILE_admin_adminconf=str_replace('$file_rev=','',$FILE_admin_adminconf);
$FILE_admin_adminconf=str_replace('"','',$FILE_admin_adminconf);
$FILE_admin_adminconf=str_replace(';','',$FILE_admin_adminconf);
$FILE_admin_adminconf=rtrim($FILE_admin_adminconf);

$array=file('../admin/banners.php');
$FILE_admin_banners=$array[1];
$FILE_admin_banners=str_replace('$file_rev=','',$FILE_admin_banners);
$FILE_admin_banners=str_replace('"','',$FILE_admin_banners);
$FILE_admin_banners=str_replace(';','',$FILE_admin_banners);
$FILE_admin_banners=rtrim($FILE_admin_banners);

$array=file('../admin/catmain.php');
$FILE_admin_catmain=$array[1];
$FILE_admin_catmain=str_replace('$file_rev=','',$FILE_admin_catmain);
$FILE_admin_catmain=str_replace('"','',$FILE_admin_catmain);
$FILE_admin_catmain=str_replace(';','',$FILE_admin_catmain);
$FILE_admin_catmain=rtrim($FILE_admin_catmain);

$array=file('../admin/changedefaultbanner.php');
$FILE_admin_changedefban=$array[1];
$FILE_admin_changedefban=str_replace('$file_rev=','',$FILE_admin_changedefban);
$FILE_admin_changedefban=str_replace('"','',$FILE_admin_changedefban);
$FILE_admin_changedefban=str_replace(';','',$FILE_admin_changedefban);
$FILE_admin_changedefban=rtrim($FILE_admin_changedefban);

$array=file('../admin/checkbanners.php');
$FILE_admin_checkbanners=$array[1];
$FILE_admin_checkbanners=str_replace('$file_rev=','',$FILE_admin_checkbanners);
$FILE_admin_checkbanners=str_replace('"','',$FILE_admin_checkbanners);
$FILE_admin_checkbanners=str_replace(';','',$FILE_admin_checkbanners);
$FILE_admin_checkbanners=rtrim($FILE_admin_checkbanners);

$array=file('../admin/checkbannersgo.php');
$FILE_admin_checkbannersgo=$array[1];
$FILE_admin_checkbannersgo=str_replace('$file_rev=','',$FILE_admin_checkbannersgo);
$FILE_admin_checkbannersgo=str_replace('"','',$FILE_admin_checkbannersgo);
$FILE_admin_checkbannersgo=str_replace(';','',$FILE_admin_checkbannersgo);
$FILE_admin_checkbannersgo=rtrim($FILE_admin_checkbannersgo);

$array=file('../admin/commerce.php');
$FILE_admin_commerce=$array[1];
$FILE_admin_commerce=str_replace('$file_rev=','',$FILE_admin_commerce);
$FILE_admin_commerce=str_replace('"','',$FILE_admin_commerce);
$FILE_admin_commerce=str_replace(';','',$FILE_admin_commerce);
$FILE_admin_commerce=rtrim($FILE_admin_commerce);

$array=file('../admin/commerce_display.php');
$FILE_admin_commercedisp=$array[1];
$FILE_admin_commercedisp=str_replace('$file_rev=','',$FILE_admin_commercedisp);
$FILE_admin_commercedisp=str_replace('"','',$FILE_admin_commercedisp);
$FILE_admin_commercedisp=str_replace(';','',$FILE_admin_commercedisp);
$FILE_admin_commercedisp=rtrim($FILE_admin_commercedisp);

$array=file('../admin/commerce_edit.php');
$FILE_admin_commercedit=$array[1];
$FILE_admin_commercedit=str_replace('$file_rev=','',$FILE_admin_commercedit);
$FILE_admin_commercedit=str_replace('"','',$FILE_admin_commercedit);
$FILE_admin_commercedit=str_replace(';','',$FILE_admin_commercedit);
$FILE_admin_commercedit=rtrim($FILE_admin_commercedit);

$array=file('../admin/css_change.php');
$FILE_admin_csschange=$array[1];
$FILE_admin_csschange=str_replace('$file_rev=','',$FILE_admin_csschange);
$FILE_admin_csschange=str_replace('"','',$FILE_admin_csschange);
$FILE_admin_csschange=str_replace(';','',$FILE_admin_csschange);
$FILE_admin_csschange=rtrim($FILE_admin_csschange);

$array=file('../admin/dbdump.php');
$FILE_admin_dbdump=$array[1];
$FILE_admin_dbdump=str_replace('$file_rev=','',$FILE_admin_dbdump);
$FILE_admin_dbdump=str_replace('"','',$FILE_admin_dbdump);
$FILE_admin_dbdump=str_replace(';','',$FILE_admin_dbdump);
$FILE_admin_dbdump=rtrim($FILE_admin_dbdump);

$array=file('../admin/dbrestore.php');
$FILE_admin_dbrestore=$array[1];
$FILE_admin_dbrestore=str_replace('$file_rev=','',$FILE_admin_dbrestore);
$FILE_admin_dbrestore=str_replace('"','',$FILE_admin_dbrestore);
$FILE_admin_dbrestore=str_replace(';','',$FILE_admin_dbrestore);
$FILE_admin_dbrestore=rtrim($FILE_admin_dbrestore);

$array=file('../admin/dbtools.php');
$FILE_admin_dbtools=$array[1];
$FILE_admin_dbtools=str_replace('$file_rev=','',$FILE_admin_dbtools);
$FILE_admin_dbtools=str_replace('"','',$FILE_admin_dbtools);
$FILE_admin_dbtools=str_replace(';','',$FILE_admin_dbtools);
$FILE_admin_dbtools=rtrim($FILE_admin_dbtools);

$array=file('../admin/dbupload.php');
$FILE_admin_dbupload=$array[1];
$FILE_admin_dbupload=str_replace('$file_rev=','',$FILE_admin_dbupload);
$FILE_admin_dbupload=str_replace('"','',$FILE_admin_dbupload);
$FILE_admin_dbupload=str_replace(';','',$FILE_admin_dbupload);
$FILE_admin_dbupload=rtrim($FILE_admin_dbupload);

$array=file('../admin/deladmin.php');
$FILE_admin_deladmin=$array[1];
$FILE_admin_deladmin=str_replace('$file_rev=','',$FILE_admin_deladmin);
$FILE_admin_deladmin=str_replace('"','',$FILE_admin_deladmin);
$FILE_admin_deladmin=str_replace(';','',$FILE_admin_deladmin);
$FILE_admin_deladmin=rtrim($FILE_admin_deladmin);

$array=file('../admin/deladminconfirm.php');
$FILE_admin_deladminconf=$array[1];
$FILE_admin_deladminconf=str_replace('$file_rev=','',$FILE_admin_deladminconf);
$FILE_admin_deladminconf=str_replace('"','',$FILE_admin_deladminconf);
$FILE_admin_deladminconf=str_replace(';','',$FILE_admin_deladminconf);
$FILE_admin_deladminconf=rtrim($FILE_admin_deladminconf);

$array=file('../admin/delcat.php');
$FILE_admin_delcat=$array[1];
$FILE_admin_delcat=str_replace('$file_rev=','',$FILE_admin_delcat);
$FILE_admin_delcat=str_replace('"','',$FILE_admin_delcat);
$FILE_admin_delcat=str_replace(';','',$FILE_admin_delcat);
$FILE_admin_delcat=rtrim($FILE_admin_delcat);

$array=file('../admin/delcatconf.php');
$FILE_admin_delcatconf=$array[1];
$FILE_admin_delcatconf=str_replace('$file_rev=','',$FILE_admin_delcatconf);
$FILE_admin_delcatconf=str_replace('"','',$FILE_admin_delcatconf);
$FILE_admin_delcatconf=str_replace(';','',$FILE_admin_delcatconf);
$FILE_admin_delcatconf=rtrim($FILE_admin_delcatconf);

$array=file('../admin/deleteacct.php');
$FILE_admin_delacct=$array[1];
$FILE_admin_delacct=str_replace('$file_rev=','',$FILE_admin_delacct);
$FILE_admin_delacct=str_replace('"','',$FILE_admin_delacct);
$FILE_admin_delacct=str_replace(';','',$FILE_admin_delacct);
$FILE_admin_delacct=rtrim($FILE_admin_delacct);

$array=file('../admin/deleteacctconfirm.php');
$FILE_admin_delacctconf=$array[1];
$FILE_admin_delacctconf=str_replace('$file_rev=','',$FILE_admin_delacctconf);
$FILE_admin_delacctconf=str_replace('"','',$FILE_admin_delacctconf);
$FILE_admin_delacctconf=str_replace(';','',$FILE_admin_delacctconf);
$FILE_admin_delacctconf=rtrim($FILE_admin_delacctconf);

$array=file('../admin/deletebanner.php');
$FILE_admin_delbanner=$array[1];
$FILE_admin_delbanner=str_replace('$file_rev=','',$FILE_admin_delbanner);
$FILE_admin_delbanner=str_replace('"','',$FILE_admin_delbanner);
$FILE_admin_delbanner=str_replace(';','',$FILE_admin_delbanner);
$FILE_admin_delbanner=rtrim($FILE_admin_delbanner);

$array=file('../admin/do_update.php');
$FILE_admin_doupdate=$array[1];
$FILE_admin_doupdate=str_replace('$file_rev=','',$FILE_admin_doupdate);
$FILE_admin_doupdate=str_replace('"','',$FILE_admin_doupdate);
$FILE_admin_doupdate=str_replace(';','',$FILE_admin_doupdate);
$FILE_admin_doupdate=rtrim($FILE_admin_doupdate);

$array=file('../admin/edit.php');
$FILE_admin_edit=$array[1];
$FILE_admin_edit=str_replace('$file_rev=','',$FILE_admin_edit);
$FILE_admin_edit=str_replace('"','',$FILE_admin_edit);
$FILE_admin_edit=str_replace(';','',$FILE_admin_edit);
$FILE_admin_edit=rtrim($FILE_admin_edit);

$array=file('../admin/editcat.php');
$FILE_admin_editcat=$array[1];
$FILE_admin_editcat=str_replace('$file_rev=','',$FILE_admin_editcat);
$FILE_admin_editcat=str_replace('"','',$FILE_admin_editcat);
$FILE_admin_editcat=str_replace(';','',$FILE_admin_editcat);
$FILE_admin_editcat=rtrim($FILE_admin_editcat);

$array=file('../admin/editcatconfirm.php');
$FILE_admin_editcatconfirm=$array[1];
$FILE_admin_editcatconfirm=str_replace('$file_rev=','',$FILE_admin_editcatconfirm);
$FILE_admin_editcatconfirm=str_replace('"','',$FILE_admin_editcatconfirm);
$FILE_admin_editcatconfirm=str_replace(';','',$FILE_admin_editcatconfirm);
$FILE_admin_editcatconfirm=rtrim($FILE_admin_editcatconfirm);

$array=file('../admin/editconfirm.php');
$FILE_admin_editconf=$array[1];
$FILE_admin_editconf=str_replace('$file_rev=','',$FILE_admin_editconf);
$FILE_admin_editconf=str_replace('"','',$FILE_admin_editconf);
$FILE_admin_editconf=str_replace(';','',$FILE_admin_editconf);
$FILE_admin_editconf=rtrim($FILE_admin_editconf);

$array=file('../admin/editcss.php');
$FILE_admin_editcss=$array[1];
$FILE_admin_editcss=str_replace('$file_rev=','',$FILE_admin_editcss);
$FILE_admin_editcss=str_replace('"','',$FILE_admin_editcss);
$FILE_admin_editcss=str_replace(';','',$FILE_admin_editcss);
$FILE_admin_editcss=rtrim($FILE_admin_editcss);
$array=file('../admin/editpass.php');
$FILE_admin_editpass=$array[1];
$FILE_admin_editpass=str_replace('$file_rev=','',$FILE_admin_editpass);
$FILE_admin_editpass=str_replace('"','',$FILE_admin_editpass);
$FILE_admin_editpass=str_replace(';','',$FILE_admin_editpass);
$FILE_admin_editpass=rtrim($FILE_admin_editpass);

$array=file('../admin/editstuff.php');
$FILE_admin_editstuff=$array[1];
$FILE_admin_editstuff=str_replace('$file_rev=','',$FILE_admin_editstuff);
$FILE_admin_editstuff=str_replace('"','',$FILE_admin_editstuff);
$FILE_admin_editstuff=str_replace(';','',$FILE_admin_editstuff);
$FILE_admin_editstuff=rtrim($FILE_admin_editstuff);

$array=file('../admin/editvars.php');
$FILE_admin_editvars=$array[1];
$FILE_admin_editvars=str_replace('$file_rev=','',$FILE_admin_editvars);
$FILE_admin_editvars=str_replace('"','',$FILE_admin_editvars);
$FILE_admin_editvars=str_replace(';','',$FILE_admin_editvars);
$FILE_admin_editvars=rtrim($FILE_admin_editvars);

$array=file('../admin/email.php');
$FILE_admin_email=$array[1];
$FILE_admin_email=str_replace('$file_rev=','',$FILE_admin_email);
$FILE_admin_email=str_replace('"','',$FILE_admin_email);
$FILE_admin_email=str_replace(';','',$FILE_admin_email);
$FILE_admin_email=rtrim($FILE_admin_email);

$array=file('../admin/emailgo.php');
$FILE_admin_emailgo=$array[1];
$FILE_admin_emailgo=str_replace('$file_rev=','',$FILE_admin_emailgo);
$FILE_admin_emailgo=str_replace('"','',$FILE_admin_emailgo);
$FILE_admin_emailgo=str_replace(';','',$FILE_admin_emailgo);
$FILE_admin_emailgo=rtrim($FILE_admin_emailgo);

$array=file('../admin/emailsend.php');
$FILE_admin_emailsend=$array[1];
$FILE_admin_emailsend=str_replace('$file_rev=','',$FILE_admin_emailsend);
$FILE_admin_emailsend=str_replace('"','',$FILE_admin_emailsend);
$FILE_admin_emailsend=str_replace(';','',$FILE_admin_emailsend);
$FILE_admin_emailsend=rtrim($FILE_admin_emailsend);

$array=file('../admin/emailuser.php');
$FILE_admin_emailuser=$array[1];
$FILE_admin_emailuser=str_replace('$file_rev=','',$FILE_admin_emailuser);
$FILE_admin_emailuser=str_replace('"','',$FILE_admin_emailuser);
$FILE_admin_emailuser=str_replace(';','',$FILE_admin_emailuser);
$FILE_admin_emailuser=rtrim($FILE_admin_emailuser);

$array=file('../admin/emailusergo.php');
$FILE_admin_emailusergo=$array[1];
$FILE_admin_emailusergo=str_replace('$file_rev=','',$FILE_admin_emailusergo);
$FILE_admin_emailusergo=str_replace('"','',$FILE_admin_emailusergo);
$FILE_admin_emailusergo=str_replace(';','',$FILE_admin_emailusergo);
$FILE_admin_emailusergo=rtrim($FILE_admin_emailusergo);

$array=file('../admin/faq.php');
$FILE_admin_faq=$array[1];
$FILE_admin_faq=str_replace('$file_rev=','',$FILE_admin_faq);
$FILE_admin_faq=str_replace('"','',$FILE_admin_faq);
$FILE_admin_faq=str_replace(';','',$FILE_admin_faq);
$FILE_admin_faq=rtrim($FILE_admin_faq);

$array=file('../admin/faqdel.php');
$FILE_admin_faqdel=$array[1];
$FILE_admin_faqdel=str_replace('$file_rev=','',$FILE_admin_faqdel);
$FILE_admin_faqdel=str_replace('"','',$FILE_admin_faqdel);
$FILE_admin_faqdel=str_replace(';','',$FILE_admin_faqdel);
$FILE_admin_faqdel=rtrim($FILE_admin_faqdel);

$array=file('../admin/faqedit.php');
$FILE_admin_faqedit=$array[1];
$FILE_admin_faqedit=str_replace('$file_rev=','',$FILE_admin_faqedit);
$FILE_admin_faqedit=str_replace('"','',$FILE_admin_faqedit);
$FILE_admin_faqedit=str_replace(';','',$FILE_admin_faqedit);
$FILE_admin_faqedit=rtrim($FILE_admin_faqedit);

$array=file('../admin/index.php');
$FILE_admin_index=$array[1];
$FILE_admin_index=str_replace('$file_rev=','',$FILE_admin_index);
$FILE_admin_index=str_replace('"','',$FILE_admin_index);
$FILE_admin_index=str_replace(';','',$FILE_admin_index);
$FILE_admin_index=rtrim($FILE_admin_index);

$array=file('../admin/listall.php');
$FILE_admin_listall=$array[1];
$FILE_admin_listall=str_replace('$file_rev=','',$FILE_admin_listall);
$FILE_admin_listall=str_replace('"','',$FILE_admin_listall);
$FILE_admin_listall=str_replace(';','',$FILE_admin_listall);
$FILE_admin_listall=rtrim($FILE_admin_listall);

$array=file('../admin/logout.php');
$FILE_admin_logout=$array[1];
$FILE_admin_logout=str_replace('$file_rev=','',$FILE_admin_logout);
$FILE_admin_logout=str_replace('"','',$FILE_admin_logout);
$FILE_admin_logout=str_replace(';','',$FILE_admin_logout);
$FILE_admin_logout=rtrim($FILE_admin_logout);

$array=file('../admin/pause.php');
$FILE_admin_pause=$array[1];
$FILE_admin_pause=str_replace('$file_rev=','',$FILE_admin_pause);
$FILE_admin_pause=str_replace('"','',$FILE_admin_pause);
$FILE_admin_pause=str_replace(';','',$FILE_admin_pause);
$FILE_admin_pause=rtrim($FILE_admin_pause);

$array=file('../admin/process_edit_stuff.php');
$FILE_admin_processedit=$array[1];
$FILE_admin_processedit=str_replace('$file_rev=','',$FILE_admin_processedit);
$FILE_admin_processedit=str_replace('"','',$FILE_admin_processedit);
$FILE_admin_processedit=str_replace(';','',$FILE_admin_processedit);
$FILE_admin_processedit=rtrim($FILE_admin_processedit);

$array=file('../admin/process_faq.php');
$FILE_admin_processfaq=$array[1];
$FILE_admin_processfaq=str_replace('$file_rev=','',$FILE_admin_processfaq);
$FILE_admin_processfaq=str_replace('"','',$FILE_admin_processfaq);
$FILE_admin_processfaq=str_replace(';','',$FILE_admin_processfaq);
$FILE_admin_processfaq=rtrim($FILE_admin_processfaq);

$array=file('../admin/processvars.php');
$FILE_admin_processvars=$array[1];
$FILE_admin_processvars=str_replace('$file_rev=','',$FILE_admin_processvars);
$FILE_admin_processvars=str_replace('"','',$FILE_admin_processvars);
$FILE_admin_processvars=str_replace(';','',$FILE_admin_processvars);
$FILE_admin_processvars=rtrim($FILE_admin_processvars);

$array=file('../admin/promodetails.php');
$FILE_admin_promodetails=$array[1];
$FILE_admin_promodetails=str_replace('$file_rev=','',$FILE_admin_promodetails);
$FILE_admin_promodetails=str_replace('"','',$FILE_admin_promodetails);
$FILE_admin_promodetails=str_replace(';','',$FILE_admin_promodetails);
$FILE_admin_promodetails=rtrim($FILE_admin_promodetails);

$array=file('../admin/promodetails.php');
$FILE_admin_promodetails=$array[1];
$FILE_admin_promodetails=str_replace('$file_rev=','',$FILE_admin_promodetails);
$FILE_admin_promodetails=str_replace('"','',$FILE_admin_promodetails);
$FILE_admin_promodetails=str_replace(';','',$FILE_admin_promodetails);
$FILE_admin_promodetails=rtrim($FILE_admin_promodetails);

$array=file('../admin/promos.php');
$FILE_admin_promos=$array[1];
$FILE_admin_promos=str_replace('$file_rev=','',$FILE_admin_promos);
$FILE_admin_promos=str_replace('"','',$FILE_admin_promos);
$FILE_admin_promos=str_replace(';','',$FILE_admin_promos);
$FILE_admin_promos=rtrim($FILE_admin_promos);

$array=file('../admin/pwconfirm.php');
$FILE_admin_pwconfirm=$array[1];
$FILE_admin_pwconfirm=str_replace('$file_rev=','',$FILE_admin_pwconfirm);
$FILE_admin_pwconfirm=str_replace('"','',$FILE_admin_pwconfirm);
$FILE_admin_pwconfirm=str_replace(';','',$FILE_admin_pwconfirm);
$FILE_admin_pwconfirm=rtrim($FILE_admin_pwconfirm);

$array=file('../admin/rmbackup.php');
$FILE_admin_rmbackup=$array[1];
$FILE_admin_rmbackup=str_replace('$file_rev=','',$FILE_admin_rmbackup);
$FILE_admin_rmbackup=str_replace('"','',$FILE_admin_rmbackup);
$FILE_admin_rmbackup=str_replace(';','',$FILE_admin_rmbackup);
$FILE_admin_rmbackup=rtrim($FILE_admin_rmbackup);

$array=file('../admin/stats.php');
$FILE_admin_stats=$array[1];
$FILE_admin_stats=str_replace('$file_rev=','',$FILE_admin_stats);
$FILE_admin_stats=str_replace('"','',$FILE_admin_stats);
$FILE_admin_stats=str_replace(';','',$FILE_admin_stats);
$FILE_admin_stats=rtrim($FILE_admin_stats);

$array=file('../admin/templates.php');
$FILE_admin_templates=$array[1];
$FILE_admin_templates=str_replace('$file_rev=','',$FILE_admin_templates);
$FILE_admin_templates=str_replace('"','',$FILE_admin_templates);
$FILE_admin_templates=str_replace(';','',$FILE_admin_templates);
$FILE_admin_templates=rtrim($FILE_admin_templates);

$array=file('../admin/templates_change.php');
$FILE_admin_templateedit=$array[1];
$FILE_admin_templateedit=str_replace('$file_rev=','',$FILE_admin_templateedit);
$FILE_admin_templateedit=str_replace('"','',$FILE_admin_templateedit);
$FILE_admin_templateedit=str_replace(';','',$FILE_admin_templateedit);
$FILE_admin_templateedit=rtrim($FILE_admin_templateedit);

$array=file('../admin/update.php');
$FILE_admin_update=$array[1];
$FILE_admin_update=str_replace('$file_rev=','',$FILE_admin_update);
$FILE_admin_update=str_replace('"','',$FILE_admin_update);
$FILE_admin_update=str_replace(';','',$FILE_admin_update);
$FILE_admin_update=rtrim($FILE_admin_update);
$array=file('../admin/uploadbanner.php');
$FILE_admin_uploadbanner=$array[1];
$FILE_admin_uploadbanner=str_replace('$file_rev=','',$FILE_admin_uploadbanner);
$FILE_admin_uploadbanner=str_replace('"','',$FILE_admin_uploadbanner);
$FILE_admin_uploadbanner=str_replace(';','',$FILE_admin_uploadbanner);
$FILE_admin_uploadbanner=rtrim($FILE_admin_uploadbanner);

$array=file('../admin/validate.php');
$FILE_admin_validate=$array[1];
$FILE_admin_validate=str_replace('$file_rev=','',$FILE_admin_validate);
$FILE_admin_validate=str_replace('"','',$FILE_admin_validate);
$FILE_admin_validate=str_replace(';','',$FILE_admin_validate);
$FILE_admin_validate=rtrim($FILE_admin_validate);

// end Admin Section.
// Begin Libraries section

// this file!
$array=file('../lib/manifest_upd_class.php');
$FILE_lib_manifest_upd_class=$array[1];
$FILE_lib_manifest_upd_class=str_replace('$file_rev=','',$FILE_lib_manifest_upd_class);
$FILE_lib_manifest_upd_class=str_replace('"','',$FILE_lib_manifest_upd_class);
$FILE_lib_manifest_upd_class=str_replace(';','',$FILE_lib_manifest_upd_class);
$FILE_lib_manifest_upd_class=rtrim($FILE_lib_manifest_upd_class);

$array=file('../lib/ipn.php');
$FILE_lib_ipn_in=$array[1];
$FILE_lib_ipn_in=str_replace('$file_rev=','',$FILE_lib_ipn_in);
$FILE_lib_ipn_in=str_replace('"','',$FILE_lib_ipn_in);
$FILE_lib_ipn_in=str_replace(';','',$FILE_lib_ipn_in);
$FILE_lib_ipn_in=rtrim($FILE_lib_ipn_in);

$array=file('../lib/template_class.php');
$FILE_lib_template=$array[1];
$FILE_lib_template=str_replace('$file_rev=','',$FILE_lib_template);
$FILE_lib_template=str_replace('"','',$FILE_lib_template);
$FILE_lib_template=str_replace(';','',$FILE_lib_template);
$FILE_lib_template=rtrim($FILE_lib_template);

$array=file('../lib/commerce/ipn.php');
$FILE_lib_ipnlib=$array[1];
$FILE_lib_ipnlib=str_replace('$file_rev=','',$FILE_lib_ipnlib);
$FILE_lib_ipnlib=str_replace('"','',$FILE_lib_ipnlib);
$FILE_lib_ipnlib=str_replace(';','',$FILE_lib_ipnlib);
$FILE_lib_ipnlib=rtrim($FILE_lib_ipnlib);

$array=file('../lib/commerce/paypal.config.php');
$FILE_lib_paypalconf=$array[1];
$FILE_lib_paypalconf=str_replace('$file_rev=','',$FILE_lib_paypalconf);
$FILE_lib_paypalconf=str_replace('"','',$FILE_lib_paypalconf);
$FILE_lib_paypalconf=str_replace(';','',$FILE_lib_paypalconf);
$FILE_lib_paypalconf=rtrim($FILE_lib_paypalconf);

$array=file('../lib/class_compare.php');
$FILE_lib_class_compare=$array[1];
$FILE_lib_class_compare=str_replace('$file_rev=','',$FILE_lib_class_compare);
$FILE_lib_class_compare=str_replace('"','',$FILE_lib_class_compare);
$FILE_lib_class_compare=str_replace(';','',$FILE_lib_class_compare);
$FILE_lib_class_compare=rtrim($FILE_lib_class_compare);

// Language Files..
$array=file('../lang/admin.php');
$FILE_lang_admin=$array[1];
$FILE_lang_type=$array[2];
$FILE_lang_admin=str_replace('$file_rev=','',$FILE_lang_admin);
$FILE_lang_admin=str_replace('"','',$FILE_lang_admin);
$FILE_lang_admin=str_replace(';','',$FILE_lang_admin);
$FILE_lang_type=str_replace('$file_lang=','',$FILE_lang_type);
$FILE_lang_type=str_replace('"','',$FILE_lang_type);
$FILE_lang_type=str_replace(';','',$FILE_lang_type);
$FILE_lang_admin=rtrim($FILE_lang_admin);
$FILE_lang_type=rtrim($FILE_lang_type);

$array=file('../lang/client.php');
$FILE_lang_client=$array[1];
$FILE_lang_client=str_replace('$file_rev=','',$FILE_lang_client);
$FILE_lang_client=str_replace('"','',$FILE_lang_client);
$FILE_lang_client=str_replace(';','',$FILE_lang_client);
$FILE_lang_client=rtrim($FILE_lang_client);

$array=file('../lang/common.php');
$FILE_lang_common=$array[1];
$FILE_lang_common=str_replace('$file_rev=','',$FILE_lang_common);
$FILE_lang_common=str_replace('"','',$FILE_lang_common);
$FILE_lang_common=str_replace(';','',$FILE_lang_common);
$FILE_lang_common=rtrim($FILE_lang_common);

$array=file('../lang/errors.php');
$FILE_lang_errors=$array[1];
$FILE_lang_errors=str_replace('$file_rev=','',$FILE_lang_errors);
$FILE_lang_errors=str_replace('"','',$FILE_lang_errors);
$FILE_lang_errors=str_replace(';','',$FILE_lang_errors);
$FILE_lang_errors=rtrim($FILE_lang_errors);

?>