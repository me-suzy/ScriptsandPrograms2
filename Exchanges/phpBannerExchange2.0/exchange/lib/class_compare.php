<?
$file_rev="041305";
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

$release_notes="http://www.eschew.net/scripts/phpbe/2.0/releasenotes.txt";
$upg="0";

// compares the parsed xml file with the manifest.

if($FILE_common_cou != $MASTER_COMMON_COU){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/cou.txt";
}

if($FILE_common_click != $MASTER_COMMON_CLICK){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/click.txt";
}

if($FILE_common_menu != $MASTER_COMMON_MENU){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/common_menuing.txt";
}

if($FILE_common_cookies != $MASTER_COMMON_COOKIES){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/cookies.txt";
}

if($FILE_common_dblog != $MASTER_COMMON_DBLOG){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/dblog.txt";
}

if($FILE_common_faq != $MASTER_COMMON_FAQ){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/faq.txt";
}

if($FILE_common_footer != $MASTER_COMMON_FOOTER){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/footer.txt";
}

if($FILE_common_index != $MASTER_COMMON_INDEX){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/index.txt";
}

if($FILE_common_overall != $MASTER_COMMON_OVERALL){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/overall.txt";
}

if($FILE_common_recoverpw != $MASTER_COMMON_RECOVERPW){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/recoverpw.txt";
}

if($FILE_common_resetpw != $MASTER_COMMON_RESETPW){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/resetpw.txt";
}

if($FILE_common_rules != $MASTER_COMMON_RULES){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/rules.txt";
}

if($FILE_common_signup != $MASTER_COMMON_SIGNUP){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/signup.txt";
}

if($FILE_common_signconf != $MASTER_COMMON_SIGNCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/signupconfirm.txt";
}

if($FILE_common_view != $MASTER_COMMON_VIEW){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/view.txt";
}

//Begin User Section

if($FILE_user_addconfirm != $MASTER_USER_ADDCONFIRM){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/addconfirm.txt";
}

if($FILE_user_banners != $MASTER_USER_BANNERS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/banners.txt";
}

if($FILE_user_category != $MASTER_USER_CATEGORY){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/category.txt";
}

if($FILE_user_categoryconf != $MASTER_USER_CATEGORYCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/categoryconfirm.txt";
}

if($FILE_user_changeurlconf != $MASTER_USER_CHANGEURLCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/changeurlconf.txt";
}

if($FILE_user_clicklog != $MASTER_USER_CLICKLOG){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/clicklog.txt";
}

if($FILE_user_menu != $MASTER_USER_MENU){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/client_menuing.txt";
}

if($FILE_user_commerce != $MASTER_USER_COMMERCE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/commerce.txt";
}

if($FILE_user_delban != $MASTER_USER_DELBAN){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/deletebanner.txt";
}

if($FILE_user_delbanconf != $MASTER_USER_DELBANCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/deleteconfirm.txt";
}

if($FILE_user_editbanner != $MASTER_USER_EDITBANNER){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/editbanner.txt";
}

if($FILE_user_editinfo != $MASTER_USER_EDITINFO){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/editinfo.txt";
}

if($FILE_user_editpass != $MASTER_USER_EDITPASS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/editpass.txt";
}

if($FILE_user_emailstats != $MASTER_USER_EMAILSTATS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/emailstats.txt";
}

if($FILE_user_gethtml != $MASTER_USER_GETHTML){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/gethtml.txt";
}

if($FILE_user_index != $MASTER_USER_INDEX){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/index.txt";
}

if($FILE_user_logout != $MASTER_USER_LOGOUT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/logout.txt";
}

if($FILE_user_infoconfirm != $MASTER_USER_INFOCONFIRM){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/infoconfirm.txt";
}

if($FILE_user_passconfirm != $MASTER_USER_PASSCONFIRM){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/passconfirm.txt";
}

if($FILE_user_promo != $MASTER_USER_PROMO){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/promo.txt";
}

if($FILE_user_remove != $MASTER_USER_REMOVE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/remove.txt";
}

if($FILE_user_uploadbanner != $MASTER_USER_UPLOADBANNER){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/client/uploadbanner.txt";
}

// Begin Admin section..

if($FILE_admin_addacct != $MASTER_ADMIN_ADDACCT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/addacct.txt";
}

if($FILE_admin_addacctconf != $MASTER_ADMIN_ADDACCTCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/addacctconfirm.txt";
}

if($FILE_admin_addadmin != $MASTER_ADMIN_ADDADMIN){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/addadmin.txt";
}

if($FILE_admin_addcat != $MASTER_ADMIN_ADDCAT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/addcat.txt";
}

if($FILE_admin_menu != $MASTER_ADMIN_MENU){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/admin_menuing.txt";
}

if($FILE_admin_adminconf != $MASTER_ADMIN_ADMINCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/adminconfirm.txt";
}

if($FILE_admin_banners != $MASTER_ADMIN_BANNERS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/banners.txt";
}

if($FILE_admin_changedefban != $MASTER_ADMIN_CHANGEDEFBAN){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/changedefaultbanner.txt";
}

if($FILE_admin_checkbanners != $MASTER_ADMIN_CHECKBANNERS){
	echo "FILE: $FILE_admin_checkbanners Master: $MASTER_ADMIN_CHECKBANNERS";
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/checkbanners.txt";
}

if($FILE_admin_checkbannersgo != $MASTER_ADMIN_CHECKBANNERSGO){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/checkbannersgo.txt";
}

if($FILE_admin_commerce != $MASTER_ADMIN_COMMERCE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/commerce.txt";
}

if($FILE_admin_commercedisp != $MASTER_ADMIN_COMMERCEDISP){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/commerce_display.txt";
}

if($FILE_admin_commercedit != $MASTER_ADMIN_COMMERCEDIT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/commerce_edit.txt";
}

if($FILE_admin_dbdump != $MASTER_ADMIN_DBDUMP){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/dbdump.txt";
}

if($FILE_admin_dbrestore != $MASTER_ADMIN_DBRESTORE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/dbrestore.txt";
}

if($FILE_admin_dbtools != $MASTER_ADMIN_DBTOOLS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/dbtools.txt";
}

if($FILE_admin_dbupload != $MASTER_ADMIN_DBUPLOAD){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/dbtools.txt";
}

if($FILE_admin_deladmin != $MASTER_ADMIN_DELADMIN){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/deladmin.txt";
}

if($FILE_admin_deladminconf != $MASTER_ADMIN_DELADMINCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/deladminconfirm.txt";
}

if($FILE_admin_delcat != $MASTER_ADMIN_DELCAT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/delcat.txt";
}

if($FILE_admin_delacct != $MASTER_ADMIN_DELACCT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/deleteacct.txt";
}

if($FILE_admin_delacctconf != $MASTER_ADMIN_DELACCTCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/delacctconfirm.txt";
}

if($FILE_admin_delbanner != $MASTER_ADMIN_DELBANNER){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/deletebanner.txt";
}

if($FILE_admin_edit != $MASTER_ADMIN_EDIT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/edit.txt";
}

if($FILE_admin_editcat != $MASTER_ADMIN_EDITCAT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editcat.txt";
}

if($FILE_admin_editcatconfirm != $MASTER_ADMIN_EDITCATCONFIRM){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editcatconfirm.txt";
}

if($FILE_admin_editconf != $MASTER_ADMIN_EDITCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editconfirm.txt";
}

if($FILE_admin_editcss != $MASTER_ADMIN_EDITCSS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editcss.txt";
}

if($FILE_admin_editpass != $MASTER_ADMIN_EDITPASS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editpass.txt";
}

if($FILE_admin_editstuff != $MASTER_ADMIN_EDITSTUFF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editstuff.txt";
}

if($FILE_admin_editvars != $MASTER_ADMIN_EDITVARS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/editvars.txt";
}

if($FILE_admin_email != $MASTER_ADMIN_EMAIL){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/email.txt";
}

if($FILE_admin_emailgo != $MASTER_ADMIN_EMAILGO){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/emailgo.txt";
}

if($FILE_admin_emailsend != $MASTER_ADMIN_EMAILSEND){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/emailsend.txt";
}

if($FILE_admin_emailuser != $MASTER_ADMIN_EMAILUSER){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/emailuser.txt";
}

if($FILE_admin_emailusergo != $MASTER_ADMIN_EMAILUSERGO){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/emailusergo.txt";
}

if($FILE_admin_faq != $MASTER_ADMIN_FAQ){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/faq.txt";
}

if($FILE_admin_faqdel != $MASTER_ADMIN_FAQDEL){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/faqdel.txt";
}

if($FILE_admin_faqedit != $MASTER_ADMIN_FAQEDIT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/faqedit.txt";
}

if($FILE_admin_index != $MASTER_ADMIN_INDEX){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/index.txt";
}

if($FILE_admin_listall != $MASTER_ADMIN_LISTALL){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/listall.txt";
}

if($FILE_admin_logout != $MASTER_ADMIN_LOGOUT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/logout.txt";
}

if($FILE_admin_pause != $MASTER_ADMIN_PAUSE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/pause.txt";
}

if($FILE_admin_processedit != $MASTER_ADMIN_PROCESSEDIT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/process_edit_stuff.txt";
}

if($FILE_admin_processfaq != $MASTER_ADMIN_PROCESSFAQ){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/process_faq.txt";
}

if($FILE_admin_processvars != $MASTER_ADMIN_PROCESSVARS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/processvars.txt";
}

if($FILE_admin_promodetails != $MASTER_ADMIN_PROMODETAILS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/promodetails.txt";
}

if($FILE_admin_promos != $MASTER_ADMIN_PROMOS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/promos.txt";
}

if($FILE_admin_pwconfirm != $MASTER_ADMIN_PWCONFIRM){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/pwconfirm.txt";
}

if($FILE_admin_rmbackup != $MASTER_ADMIN_RMBACKUP){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/rmbackup.txt";
}

if($FILE_admin_stats != $MASTER_ADMIN_STATS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/stats.txt";
}

if($FILE_admin_templates != $MASTER_ADMIN_TEMPLATES){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/templates.txt";
}

if($FILE_admin_templateedit != $MASTER_ADMIN_TEMPLATEEDIT){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/templates_change.txt";
}

if($FILE_admin_update != $MASTER_ADMIN_UPDATE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/update.txt";
}

if($FILE_admin_uploadbanner != $MASTER_ADMIN_UPLOADBANNER){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/uploadbanner.txt";
}

if($FILE_admin_validate != $MASTER_ADMIN_VALIDATE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/validate.txt";
}

if($FILE_admin_doupdate != $MASTER_ADMIN_DOUPDATE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/admin/do_update.txt";
}

// Libs...
if($FILE_lib_manifest_upd_class != $MASTER_LIB_MANIFEST_UPD_CLASS){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lib/manifest_upd_class.txt";
}

if($FILE_lib_ipn_in != $MASTER_LIB_IPN_IN){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lib/ipn.txt";
}

if($FILE_lib_template != $MASTER_LIB_TEMPLATE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lib/template_class.txt";
}

if($FILE_lib_ipnlib != $MASTER_LIB_IPNLIB){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lib/commerce/ipn.txt";
}

if($FILE_lib_paypalconf != $MASTER_LIB_PAYPALCONF){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lib/commerce/paypal.config.txt";
}

if($FILE_lib_class_compare != $MASTER_LIB_CLASS_COMPARE){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lib/class_compare.txt";
}

$MASTER_admin_lang='MASTER_LANG_ADMIN_'.$FILE_lang_type;
$MASTER_admin_lang=$$MASTER_admin_lang;
$MASTER_client_lang='MASTER_LANG_CLIENT_'.$FILE_lang_type;
$MASTER_client_lang=$$MASTER_client_lang;
$MASTER_common_lang='MASTER_LANG_COMMON_'.$FILE_lang_type;
$MASTER_common_lang=$$MASTER_common_lang;
$MASTER_errors_lang='MASTER_LANG_ERRORS_'.$FILE_lang_type;
$MASTER_errors_lang=$$MASTER_errors_lang;

if($FILE_lang_admin != $MASTER_admin_lang){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lang/$FILE_lang_type/admin.txt";
}

if($FILE_lang_client != $MASTER_client_lang){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lang/$FILE_lang_type/client.txt";
}

if($FILE_lang_common != $MASTER_common_lang){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lang/$FILE_lang_type/common.txt";
}

if($FILE_lang_errors != $MASTER_errors_lang){
	$upg="1";
	$upgurl[]="http://www.eschew.net/scripts/phpbe/2.0/lang/$FILE_lang_type/errors.txt";
}

?>