<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################

$GLOBALS['strHome'] = "Ana Sayfa";
$GLOBALS['strBack'] = "Geri";
$GLOBALS['date_format'] = "d-m-Y";
$GLOBALS['time_format'] = "H:i:s";
$GLOBALS['strAdd'] = "Ekle";
$GLOBALS['strUpdate'] = "Güncelle";
$GLOBALS['strDelete'] = "Sil";
$GLOBALS['strName'] = "Ad";
$GLOBALS['strSurname'] = "Soyad";
$GLOBALS['strEmail'] = "Eposta";
$GLOBALS['strUsername'] = "Kullanýcý Adý";
$GLOBALS['strPassword'] = "Þifre";
$GLOBALS['strConfirm'] = "tekrar";
$GLOBALS['strSignup'] = "Kayýt";
$GLOBALS['strLogin'] = "Giriþ";
$GLOBALS['strSubmit'] = "Gönder";
$GLOBALS['strRegFree'] = "Kayýtlý kullanýcý deðilseniz, <a href=\"register.php\"><b>buraya</b></a> týklayarak <b>ücretsiz</b> kayýt olabilirsiniz.";
$GLOBALS['strJSUsername'] = "Kullanýcý adýnýzý kontrol ediniz.\\nKullanýcý adýnýz en az 4 en çok 10\\nkarakter olamalý ve içerisinde\\n0123456789abcdefghijklmnopqrstuvwxyz.-_\\nharici karakter içermemelidir!";
$GLOBALS['strJSPassword'] = "Þifrenizi kontrol ediniz.\\nÞifreniz adýnýz en az 4\\nen çok 10 karakter olabilir.";
$GLOBALS['strErrorWronguser'] = "Hatalý kullanýcý adý/þifre girdiniz";
$GLOBALS['strErrorTimeout'] = "Zaman aþýmý. Tekrar giriþ yapýnýz";
$GLOBALS['strErrorUnknown'] = "Bir hata oluþtu.!";


# agenda_add.php
$GLOBALS['strHaveNotes'] = "<b><u>Not:</u></b> <font color=\"#FF0000\">*</font> iþaretli tarihlerde hatýrlatmalarýnýz bulunmaktadýr.";
$GLOBALS['strAddReminder'] = "Hatýrlatma Ekle";
$GLOBALS['strEditReminder'] = "Hatýrlatma Düzenle";
$GLOBALS['strWriteNote'] = "Hatýrlatma notunuzu buraya yazýn";
$GLOBALS['strMaxNoteChars'] = "En fazla 125 karakter";
$GLOBALS['strRemindBefore'] = "önce uyar";
$GLOBALS['strFromMyDate'] = "eklediðim tarihden";
$GLOBALS['strMyThisReminder'] = "Eklediðim hatýrlatmayý";
$GLOBALS['strError'] = "Hata";
$GLOBALS['strErrorWrongDate'] = "Seçtiðiniz tarih hatalý bir tarih!";
$GLOBALS['strErrorOldDate'] = "Seçtiðiniz tarih geçmiþ bir tarih!";
$GLOBALS['strErrorLackDate'] = "Eksik tarih girdiniz!";
$GLOBALS['strJSNoNote'] = "Not kýsmýný boþ býrakamazsýnýz";
$GLOBALS['strJSToomuchChars'] = "125 karakterden fazla not yazamazsýnýz";
$GLOBALS['strSaveRemindOk'] = "Hatýrlatmanýz kayýt edildi!";
$GLOBALS['strErrorSqlInsert'] = "Bilgileriniz kayýt edilirken bir sorun oluþtu. Lütfen bu sorunu <a href=\"mailto:$contact_mail\">$contact_mail</a> adresine iletiniz.";

# register.php
$GLOBALS['strJSEnterName'] = "Adýnýzý giriniz";
$GLOBALS['strJSEnterSurname'] = "Soyadýnýzý giriniz!";
$GLOBALS['strJSEnterEmail'] = "Eposta adresini uygun formatta giriniz!";
$GLOBALS['strJSPasswordsNoMatch'] = "Girdiðiniz þifreler uyuþmuyor!";
$GLOBALS['strRepeate'] = "Tekrar";

$GLOBALS['strRegisterOk'] = "<b>Tebrikler!</b><p>Kaydýnýz baþarý ile gerçekleþtirildi. Giriþ yapmak için <a href=\"login.php\">buraya</a> týklayýnýz.";
$GLOBALS['strGoLocation'] = "Geldiðiniz yere geri dönmek için lütfen <a href=\"login.php?location=$location\">týklayýnýz</a>.";
$GLOBALS['strExistMail'] = "Girdiðiniz eposta adresi ile daha önce kayýt olunmuþ. Lütfen geri gidip farklý bir eposta adresi giriniz.";
$GLOBALS['strExistUser'] = "Girdiðiniz kullanýcý adý sistemimizde kayýtlý. Lütfen farklý bir kullanýcý adý giriniz.";
$GLOBALS['strWrongMail'] = "Girdiðiniz mail adresi (".$HTTP_POST_VARS[email].") hatalý. Lütfen geri gidip tekrar giriniz.";
$GLOBALS['strReminderUpdated'] = "Hatýrlatmanýz güncellendi!";
$GLOBALS['strReminderDeleted'] = "Hatýrlatmanýz silindi!";



$GLOBALS['strMonthnames'] = array("Ocak", "Þubat", "Mart", "Nisan", "Mayýs", "Haziran", "Temmuz", "Aðustos", "Eylül", "Ekim", "Kasým", "Aralýk");
$GLOBALS['strWeekdays'] = array("Paz", "Pzt", "Sal", "Çrþ", "Prþ", "Cum", "Cmt", "Paz");

$GLOBALS['strGo'] = "Git";
$GLOBALS['strLogout'] = "Çýkýþ";
$GLOBALS['strPrevious'] = "Önceki";
$GLOBALS['strNext'] = "Sonraki";
$GLOBALS['strMailSubject'] = "Hatirlatma";
$GLOBALS['strMailHeader'] = "Sayin {contact},\n";
$GLOBALS['strMailFooter'] = "Hatirlatiriz,\n{programname}\n" . $myAgenda_url;
$GLOBALS['strConfirm'] = "Bu Kaydý Silmek Ýstediðinizden Eminmisiniz ?";
$GLOBALS['strSave'] = "Kaydet";
$GLOBALS['strYes']						= "Evet";
$GLOBALS['strNo']						= "Hayýr";

$GLOBALS['strReminderDate'] = "Hatirlatma Tarihi";
$GLOBALS['strReminderNote'] = "Hatirlatma Notunuz";
$GLOBALS['strMailNextRemindDate'] = "Bir Sonraki Hatirlatma Tarihiniz";
$GLOBALS['strMailReminderSent'] = "Hatirlatmalar Gonderildi";

$GLOBALS['strRemindTypes'] = array(1 => "Hatýrlatma", "Buluþma", "Doðum Günü", "Yýldönümü", "Yemek", "Aktivite", "Ödeme", "Diðer");
$GLOBALS['strRemindRepeates'] = array(1 => "Sadece Bir Kere", "Her Gün", "Her Hafta", "Her Ay", "Her Yýl");
$GLOBALS['strRemindDays'] = array(1 => "1 Gün", "2 Gün", "3 Gün", "7 Gün");

$GLOBALS['strForgotPass'] = "Þifrenizi mi Unuttunuz?";
$GLOBALS['strSendMyPassword'] = "Þifremi Gönder";
$GLOBALS['strJSEmail'] = "E-Posta Adresini Kontrol Ediniz";
$GLOBALS['strForgotPassEmailSubj'] = "Þifreniz";
$GLOBALS['strForgotPassEmailBody'] = "Merhaba {name}\n\nÞifreniz:\n\n";
$GLOBALS['strForgotPassEmailOk'] = "Þifreniz e-posta adresinize gönderildi.";
$GLOBALS['strForgotPassEmailError'] = "Girdiðiniz e-posta adresi veritabanýmýzda bulunamamýþtýr.";
?>