<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################

$LANGUAGE['strHome'] = "Ana Sayfa";
$LANGUAGE['strBack'] = "Geri";
$LANGUAGE['date_format'] = "d-m-Y";
$LANGUAGE['time_format'] = "H:i";
$LANGUAGE['strAdd'] = "Ekle";
$LANGUAGE['strUpdate'] = "Güncelle";
$LANGUAGE['strDelete'] = "Sil";
$LANGUAGE['strName'] = "Ad";
$LANGUAGE['strSurname'] = "Soyad";
$LANGUAGE['strEmail'] = "Eposta";
$LANGUAGE['strUsername'] = "Kullanýcý Adý";
$LANGUAGE['strPassword'] = "Þifre";
$LANGUAGE['strSignup'] = "Kayýt";
$LANGUAGE['strLogin'] = "Giriþ";
$LANGUAGE['strSubmit'] = "Gönder";
$LANGUAGE['strRegFree'] = "Kayýtlý kullanýcý deðilseniz, <a href=\"register.php\"><b>buraya</b></a> týklayarak <b>ücretsiz</b> kayýt olabilirsiniz.";
$LANGUAGE['strJSUsername'] = "Kullanýcý adýnýzý kontrol ediniz.\\nKullanýcý adýnýz en az 4 en çok 10\\nkarakter olamalý ve içerisinde\\n0123456789abcdefghijklmnopqrstuvwxyz.-_\\nharici karakter içermemelidir!";
$LANGUAGE['strJSPassword'] = "Þifrenizi kontrol ediniz.\\nÞifreniz adýnýz en az 4\\nen çok 10 karakter olabilir.";
$LANGUAGE['strErrorWronguser'] = "Hatalý kullanýcý adý/þifre girdiniz";
$LANGUAGE['strErrorTimeout'] = "Zaman aþýmý. Tekrar giriþ yapýnýz";
$LANGUAGE['strErrorUnknown'] = "Bir hata oluþtu.!";


# agenda_add.php
$LANGUAGE['strHaveNotes'] = "<b><u>Not:</u></b> <font color=\"#FF0000\">*</font> iþaretli tarihlerde hatýrlatmalarýnýz bulunmaktadýr.";
$LANGUAGE['strAddReminder'] = "Hatýrlatma Ekle";
$LANGUAGE['strEditReminder'] = "Hatýrlatma Düzenle";
$LANGUAGE['str_At'] = "Saat";
$LANGUAGE['str_Oclock'] = "'de(da)";
$LANGUAGE['strWriteNote'] = "Hatýrlatma notunuzu buraya yazýn";
$LANGUAGE['strMaxNoteChars'] = "En fazla 125 karakter";
$LANGUAGE['strThisReminder'] = " Uyar";
$LANGUAGE['strFromMyDate'] = "eklediðim tarihden";
$LANGUAGE['strMyThisReminder'] = "Eklediðim hatýrlatmayý";
$LANGUAGE['strError'] = "Hata";
$LANGUAGE['strErrorWrongDate'] = "Seçtiðiniz tarih hatalý bir tarih!";
$LANGUAGE['strErrorOldDate'] = "Seçtiðiniz tarih geçmiþ bir tarih!";
$LANGUAGE['strErrorLackDate'] = "Eksik tarih girdiniz!";
$LANGUAGE['strJSNoNote'] = "Not kýsmýný boþ býrakamazsýnýz";
$LANGUAGE['strJSToomuchChars'] = "125 karakterden fazla not yazamazsýnýz";
$LANGUAGE['strSaveRemindOk'] = "Hatýrlatmanýz kayýt edildi!";
$LANGUAGE['strErrorSqlInsert'] = "Bilgileriniz kayýt edilirken bir sorun oluþtu. Lütfen bu sorunu <a href=\"mailto:".$CFG->PROG_EMAIL."\">".$CFG->PROG_EMAIL."</a> adresine iletiniz.";

# register.php
$LANGUAGE['strJSEnterName'] = "Adýnýzý giriniz";
$LANGUAGE['strJSEnterSurname'] = "Soyadýnýzý giriniz!";
$LANGUAGE['strJSEnterEmail'] = "Eposta adresini uygun formatta giriniz!";
$LANGUAGE['strJSPasswordsNoMatch'] = "Girdiðiniz þifreler uyuþmuyor!";
$LANGUAGE['strRepeate'] = "Tekrar";

$LANGUAGE['strRegisterOk'] = "<b>Tebrikler!</b><p>Kaydýnýz baþarý ile gerçekleþtirildi. Giriþ yapmak için <a href=\"login.php\">buraya</a> týklayýnýz.";
$LANGUAGE['strGoLocation'] = "Geldiðiniz yere geri dönmek için lütfen <a href=\"login.php?location=$location\">týklayýnýz</a>.";
$LANGUAGE['strExistMail'] = "Girdiðiniz eposta adresi (//email//) sistemimizde baþkasý adýna kayýtlý. Lütfen farklý bir eposta adresi seçiniz.";
$LANGUAGE['strExistUser'] = "Girdiðiniz kullanýcý adý sistemimizde baþkasý adýna kayýtlý. Lütfen farklý bir kullanýcý adý seçiniz.";
$LANGUAGE['strWrongMail'] = "Girdiðiniz mail adresi (//email//) hatalý.";
$LANGUAGE['strReminderUpdated'] = "Hatýrlatmanýz güncellendi!";
$LANGUAGE['strReminderDeleted'] = "Hatýrlatmanýz silindi!";

$LANGUAGE['strMonthnames'] = array("Ocak", "Þubat", "Mart", "Nisan", "Mayýs", "Haziran", "Temmuz", "Aðustos", "Eylül", "Ekim", "Kasým", "Aralýk");
$LANGUAGE['strWeekdays'] = array("Paz", "Pzt", "Sal", "Çrþ", "Prþ", "Cum", "Cmt", "Paz");

$LANGUAGE['strGo'] = "Git";
$LANGUAGE['strLogout'] = "Çýkýþ";
$LANGUAGE['strPrevious'] = "Önceki";
$LANGUAGE['strNext'] = "Sonraki";
$LANGUAGE['strJSConfirm'] = "Bu Kayd(lar)ý Silmek Ýstediðinizden Eminmisiniz ?";
$LANGUAGE['strSave'] = "Kaydet";
$LANGUAGE['strYes'] = "Evet";
$LANGUAGE['strNo'] = "Hayýr";

$LANGUAGE['strReminderDate'] = "Hatýrlatma Tarihi";
$LANGUAGE['strReminderNote'] = "Hatýrlatma Notunuz";
$LANGUAGE['strMailNextRemindDate'] = "Bir Sonraki Hatýrlatma Tarihiniz";
$LANGUAGE['strMailReminderSent'] = "Hatýrlatmalar Gonderildi";

$LANGUAGE['strRemindTypes'] = array(1 => "Hatýrlatma", "Buluþma", "Doðum Günü", "Yýldönümü", "Yemek", "Aktivite", "Ödeme", "Diðer");
$LANGUAGE['strRemindRepeates'] = array(1 => "Sadece Bir Kere", "Her Gün", "Her Hafta", "Her Ay", "Her Yýl");
$LANGUAGE['strRemindDays'] = array("Ayný Gün", "1 Gün Önce", "2 Gün Önce", "3 Gün Önce", "7 Gün Önce");

$LANGUAGE['strForgotLoginInfo'] = "Giriþ Bilgilerinizi mi Unuttunuz?";
$LANGUAGE['strSendMyPassword'] = "Þifremi Gönder";
$LANGUAGE['strJSEmail'] = "E-Posta Adresini Kontrol Ediniz";
$LANGUAGE['strForgotPassEmailSubj'] = "Þifreniz";
$LANGUAGE['strForgotPassEmailBody'] = "Merhaba {name}\n\nSifreniz: {password}\n\nGiris yapmak icin asagidaki linki tiklayiniz:\n\n{link}\n\n" . $CFG->PROG_NAME . "\n" . $CFG->PROG_URL;
$LANGUAGE['strForgotPassEmailOk'] = "Þifreniz e-posta adresinize gönderildi.";
$LANGUAGE['strForgotPassEmailError'] = "Girdiðiniz e-posta adresi veritabanýmýzda bulunamamýþtýr.";

# Administrative LANGUAGE

$LANGUAGE['str_AdministrativeArea'] = $CFG->PROG_NAME . " Yönetim Merkezi";
$LANGUAGE['str_ListUsers'] = "Kullanýcý Listesi";
$LANGUAGE['str_ListReminders'] = "Hatýrlatma Listesi";

$LANGUAGE['str_myAgendaUsers'] = $CFG->PROG_NAME . " Users";
$LANGUAGE['str_myAgendaUsersReminders'] = $CFG->PROG_NAME . " Users' Reminders";

$LANGUAGE['str_RegDate'] = "Registered";
$LANGUAGE['str_RegUsers'] = "Kayýtlý toplam <b>{TOTAL}</b> abone mevcut.";
$LANGUAGE['str_RegReminders'] = "Kayýtlý toplam <b>{TOTAL}</b> hatýrlatma mevcut.";

$LANGUAGE['strEdit'] = "Düzenle";
$LANGUAGE['strDelete'] = "Sil";
$LANGUAGE['strAction'] = "Ýþlem";
$LANGUAGE['strOtherPages'] = "Diðer Sayfalar";
$LANGUAGE['strPrevPage'] = "Önceki Sayfa";
$LANGUAGE['strNextPage'] = "Sonraki Sayfa";
$LANGUAGE['strRecordUpdated'] = "Kayýt Güncellendi";
$LANGUAGE['strRecordDeleted'] = "Kayýt Silindi";
$LANGUAGE['strReminders'] = "Hatýrlatmalar";
$LANGUAGE['strType'] = "Tip";
$LANGUAGE['strDate'] = "Tarih";
$LANGUAGE['strRepeat'] = "Tekrar";
$LANGUAGE['strDuration'] = "Süre";
$LANGUAGE['strAdvance'] = "Öncelik";
$LANGUAGE['str_ReminderNote'] = "Notu";
$LANGUAGE['str_ReminderAdded'] = "Eklenme Tarihi";
$LANGUAGE['str_UsersStats'] = "Kullanýcý Ýstatistikleri";
$LANGUAGE['str_RemindersStats'] = "Hatýrlatma Ýstatistikleri";

$LANGUAGE['strLastAccess'] = "Son Geliþ";

$LANGUAGE['strDelSelected'] = "Seçili Öðeleri Sil";
$LANGUAGE['strSelectOne'] = "Lütfen en az bir öðe seçiniz";
$LANGUAGE['strItemsDeleted'] = "{TOTAL} adet öðe silindi";
$LANGUAGE['strsendPassword'] = "Þifresini Gönder";

$LANGUAGE['str_NoReminders'] = "Kayýtlý hatýrlatma yok";
$LANGUAGE['str_NoUsers'] = "Kayýtlý kullanýcý bulunmamaktadýr";

$LANGUAGE['str_ChangeUser'] = "Kulanýcý Adý/Þifre Deðiþtir";
$LANGUAGE['str_OldUsername'] = "Eski Kullanýcý Adý";
$LANGUAGE['str_OldPass'] = "Eski Þifre";
$LANGUAGE['str_NewUsername'] = "Yeni Kullanýcý Adý";
$LANGUAGE['str_NewPass'] = "Yeni Þifre";
$LANGUAGE['str_UserChanged'] = "Kullanýcý Adý/Þifre Deðiþti";

$LANGUAGE['str_JSRequiredFields'] = "Gerekli tüm alanlarý doldurunuz";
$LANGUAGE['str_Config'] = "Konfigurasyon";
$LANGUAGE['str_ConfigUpdated'] = "Konfigurasyon Güncellendi";
$LANGUAGE['str_ConfigNotUpdated'] = "Yapýlacak biriþlem bulunamadý!";
$LANGUAGE['str_UserPassInfo'] = "Kullanýcý ad/þifrenizi deðiþtirmek istemiyorsanýz, ilgili alanlarý boþ býrakýnýz.";

$LANGUAGE['str_confirmRegistration'] = "Teþekkürler!<p>Kaydýnýzýn yapýlmasýna bir adým kaldý. Girdiðiniz //email// adresine bir adet onay mektubu gönderilmiþtir. Lütfen bu mektupta yazýlanlarý uygulayýnýz.";
$LANGUAGE['str_confirmEmailSubject'] = "myAgenda Kayýt Onayý";
$LANGUAGE['str_NoEmail'] = "Girdiðiniz ePosta adresine kayýtlarýmýzda rastlanýlmadý.";
$LANGUAGE['str_PasswordSent'] = "Þifreniz eposta adresinize gönderilmiþtir.";
$LANGUAGE['str_LimitedPasswordRequest'] = "Bu ePosta adresine, ayný gün içinde 3 kezden fazla þifre istemi yapýlmýþtýr. Lütfen sistem yöneticisi ile irtibat kurunuz.";
$LANGUAGE['str_ForgotPwEmailSubject'] = "myAgenda Þifreniz";
$LANGUAGE['str_YourRemindersOnToday'] = "Bugünün Hatýrlatmalarý";
$LANGUAGE['str_OK'] = "TAMAM";

$LANGUAGE['strModifyInfo'] = "Bilgi Güncelleme";
$LANGUAGE['strOldPassword'] = "Eski Þifre";
$LANGUAGE['strJSOldPassword'] = "Eski Þifrenizi kontrol ediniz.";
$LANGUAGE['strOldPasswordWrong'] = "Eski Þifreniz Hatalý.";
$LANGUAGE['strForSecurityPass'] = "Bilgilerinizin güncellenebilmesi ve güvenliðiniz için lütfen eski þifrenizi giriniz.";
$LANGUAGE['strUserInfoModified'] = "Bilgileriniz baþarý ile güncellendi";
$LANGUAGE['strNothingUpdated'] = "Herhangi bir güncelleme yapýlmadý";
?>