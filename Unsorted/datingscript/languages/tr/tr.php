<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               tr.php                           #
# File purpose            Turkish language file            #
# File created by         egerci <egerci@altikom.net>      #
############################################################

define('C_HTML_DIR','ltr'); // HTML direction for this language
define('C_CHARSET', 'iso-8859-9'); // HTML charset for this language

### !!!!! Please read it: RULES for translate!!!!! ###
### 1. Be carefull in translate - don`t use ' { } characters
###    You can use them html-equivalent - &#39; &#123; &#125;
### 2. Don`t translate {some_number} templates - you can only replace it - 
###    {0},{1}... - is not number - it templates
###################################

$w=array(
'<font color=red size=3>*</font>', //0 - Symbol for requirement field
'Güvenlik Hatasý- #', //1
'Bu E-Posta adresi halihazýrda veritabanýnda bulunmakta lütfen baþka bir tane deneyiniz!', //2
'Haytalý Ýsim Giriþi. {0} - {1} Karakter arasýnda olmalý', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Hatalý Soyadý Giriþi. {0} - {1} Karakter arasýnda olmalý', //4
'Hatalý Doðumgünü', //5
'Hatalý Þifre. {0} - {1} Karakter arasýnda olmalý', //6
'Lütfen Cinsiyetinizi Seçiniz', //7
'Lütfen Aradýðýnýz Cinsiyeti seçiniz', //8
'Ýliþki türünü giriniz', //9
'Ülkenizi Seçiniz', //10
'Yanlýþ veya Boþ E-Posta', //11
'Yanlýþ Web Adresi', //12
'Yanlýþ ICQ Numarasý', //13
'Yanlýþ AIM ', //14
'Telefon Numaranýzý Giriniz', //15
'Þehrinizi Giriniz', //16
'Medeni Durumunuz', //17
'Cocuk Durumunuz', //18
'Boyunuzu Seçiniz', //19
'Kilonuzu Seçiniz', //20
'Aradýðýnýz Boy', //21
'Aradýðýnýz Kilo', //22
'Saç Renginizi Seçiniz', //23
'Göz Renginizi Seçiniz', //24
'Etnik Grubunuzu Seçiniz', //25
'Lütfen Ýnanç Grubunuzu Seçiniz', //26
'Aradýðýnýz Etnik Grup', //27
'Aradýðýnýz Ýnanç Grubu', //28
'Sigara Kullanýmý', //29
'Ýçki Kullanýmý', //30
'Eðitim Durumu', //31
'Ýþiniz Hakkýnda Bilgi', //32
'Aradýðýnýz Yaþ Grubu', //33
'Bizi Nereden Buldunuz', //34
'Hobileriniz ve Alýþkanlýklarýnýz ', //35
'Hatalý hobi giriþi. {0} Karakterden uzun olamaz', //36
'Hatalý hobi kelime uzunluðu. {0} Karakterden uzun olamaz', //37
'Lütfen kendi hakkýnýzdaki açýklamanýzý/yazýnýzý buraya yazýn', //38
'Hatalý Açýklama giriþi. {0} Karakterden uzun olamaz', //39
'Hatalý Açýklama kelime uzunluðu. {0} Karakterden uzun olamaz', //40
'Fotografýnýz Gerekli!', //41
'Tebrikler! <br>Aktivasyon kodunuz -posta adresinize gönderildi. <br>Onaylama iþleminizi e-posta ile yapabilirsiniz.!', //42 - Message after register if need confirm by email
'Kayýt Onay Ýsteði', //43 - Confirm mail subject
'Sitemize kayýt olduðunuz için teþekkür ederiz...
Lütfen bu baðlantýya girerek kayýt iþleminizi onaylayýnýz

', //44 - Confirm message
'Kayýt olduðunuz için teþekkürler. Giriþ iþleminiz kýsa bir süre önce onaylandý. Lütfen sitemizi ziyaret etmeyi unutmayýnýz..', //45 - Message after registering if admin allowing is needed
'Tebrikler! <br>Kullanýcý hesabýnýz veritabanýmýza eklenmiþtir.!<br><br>Kullanýcý No:', //46
'<br>Þifreniz:', //47
'Lütfen Þifrenizi tekrar yazýnýz', //48
'Þifreniz geçerli deðil', //49
'Kullanýcý Kayýt Formu', //50
'Ýsminiz', //51
'Karakter', //52
'Soyadýnýz', //53
'Þifreniz', //54
'Þifreniz (tekrar)', //55
'Doðumgünü', //56
'Cinsiyet', //57
'Düþündüðünüz Ýliþki', //58
'Ülke', //59
'E-Posta', //60
'Web Sayfasý', //61
'ICQ', //62
'AIM', //63
'Telefon', //64
'Þehir', //65
'Medeni Durum', //66
'Çocuk', //67
'Boyunuz', //68
'Kilonuz', //69
'Saç Rengi', //70
'Göz Rengi', //71
'Etnik Grup', //72
'Ýnanç Durumu', //73
'Sigara', //74
'Ýçki', //75
'Eðitim', //76
'Ýþiniz', //77
'Hobileriniz', //78
'Lütfen kendinizi ve düþündüðünüz veya aradýðýnýz partner tipini açýklayýnýz.', //79
'Aradýðýnýz Cinsiyet', //80
'Aradýðýnýz Etnik Grup', //81
'Aradýðýnýz Ýnanç', //82
'Aradýðýnýz Yaþ', //83
'Aradýðýnýz Boy', //84
'Aradýðýnýz Kilo', //85
'Bizi Nasýl Buldunuz?', //86
'Resim', //87
'Ana Sayfa', //88
'Kayýt', //89
'Üye Alaný', //90
'Arama', //91
'Yorumlarýnýz', //92
'SSS', //93
'Ýstatistik', //94
'Üye Menüsü ID#', //95
'Mesajlar', //96
'Favori Listem', //97
'Bilgilerim', //98
'Bilgilerimi Deðiþtir', //99
'Þifre Deðiþtir', //100
'Bilgilerimi Sil', //101
'Çýkýþ', //102
'Ýþlem Zamaný:', //103
'San.', //104
'Online Kullanýcýlar:', //105
'Online Misafirler:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Sadece kayýtlý kullanýcýlar detaylý arama yapabilirler', //108
'Üzgünüm, "En az yaþ" alaný "En çok yaþ" alanýndan küçük olmalýdýr', //109
'Arama sonucunda ardýðýnýz kriterlere rastlanmadý', //110
'Yok', //111 Picture available?
'Evet', //112 Picture available?
'Sunucuya baðlanýlamadý<br>MYsql kullanýcý adý veya þifreniz yanlýþ.<brConfig dosyasýnda kontrol ediniz', //113
'Sunucuya baðlanýlamadý<br>Veritabaný bulunamadý.<br>Veya config dosyasýnda ismi deðiþtirildi', //114
'Sayfa :', //115
'Arama Sonuçlarý', //116
'Toplam : ', //117 
'Kullanýcý ID', //118
'Amaçlar', //119
'Yaþ', //120
'Ülke', //121
'Þehir', //122
'Son Eriþim', //123
'Kayýt Tarihi', //124
'Detaylý Arama', //125
'Kullanýcý ID#', //126
'Ýsim', //127
'Soyad', //128
'Burcu', //129
'Boy', //130
'Kilo', //131
'Cinsiyet', //132
'Ýliþki Çeþidi', //133
'Medeni Durum', //134
'Çocuk', //135
'Saç Rengi', //136
'Göz Rengi', //137
'Etnik Grup', //138
'Ýnanç', //139
'Sigara', //140
'Ýçki', //141
'Eðitim', //142
'Kullanýcýlarý Ara', //143
'Web Sayfasý', //144
'ICQ', //145
'AIM', //146
'Telefon', //147
'Kayýt Zamaný ', //148
'Sonuç Listeleme', //149
'Sayfadaki Sonuç Sayýsý', //150
'Basit Arama', //151
'Üye olmayanlara kapalý', //152
'Kötü bilgi gönderen kullanýcýalra kapalý', //153
'Kullanýcý halen kötü kullanýcý tablosunda', //154
'Teþekkürler, Kullanýcý kötü kullanýcý tablosuna eklendi ve kýsa bir süre içinde yönetici tarafýndan konotrol edilecek', //155
'Favori listesi özelliði kapalý', //156
'Kullanýcý halen favori listenizde', //157
'Teþekkürler, Kullanýcý favori lisytenzie eklendi', //158
'Bilgileriniz/Profiliniz yönetici kontrolü için alýndý!', //159
'Kullanýcý profiliniz veritabanýna baþarý ile eklendi ', //160
'Profil aktivasyon hatasý. halen aktif olabilir', //161
'SSS veritabaný boþ', //162
'SSS Cevap#', //163
'Bütün alanlar doldurulmalýdýr', //164
'Mesajýnýz baþarý ile gönderildi', //165
'Lütfen Konu Giriniz.', //166
'Lütfen mesajýnýzý giriniz', //167
'Konu', //168
'Mesaj', //169
'Mesaj Gönder', //170
'Üyeler Ýçin', //171
'Kullanýcý ID', //172
'Kayýp Þifre', //173
'Bizi Önerin', //174
'Arkadaþ-{0} e-posta', //175
'Bugünün doðum günleri', //176
'Doðumgünü yok', //177
'Sitemize Hoþgeldiniz', //178 Welcome message header
'AzDGDatingLite - yeni arkadaþlýklar, iliþkiler, eylence, buluþma vve uzun zamanlý iliþki kurmak için tam ardýðýnýz sitedir. Buluþma ve soyal iliþki insanlar için hem eylenceli hemde güvenlidir..<br><br>Size özel e-posta sistemimizi kullanarak yeni arkadaþlýklar kurabilirsiniz.Bu sistem sizin diðer üyeler ile iliþkiye geçmenize ve arkadaþlýklar kurmanýzý saðlar...<br>', //179 Welcome message
'Son {0} kayýtlý kullanýcý', //180
'Hýzlý Arama', //181
'Detaylý Arama', //182
'Günün Fotografý', //183
'Basit Ýstatistik', //184
'Kullanýcý ID niz sayýsal olmalý', //185
'Yanlýþ Kullanýcý ID si veya þifre', //186
'Mesajlarýn e-postaya gönderilmasi kapalý', //187
'Konullanýcý ID ye mesaj gönderme', //188
'Online kullanýcý yok', //189
'Tavsiye etme sayfasý kapalý', //190
'{0} dan tebrikrek', //191 "Recommend Us" subject, {0} - username
' {0} : Merhaba!

Nasýlsýn :)

LÜtfen bu siteyi ziyaret et. çok beyeneceksin:
{1}', //192 "Recommend Us" message, {0} - username, {1} - site url
'Lütfen doðru yazýnýz#{0} email', //193
'Lütfen isim ve e-posta yazýnýz', //194
'{0} Þifreniz', //195 Reming password email subject
'Bu hesap aktif deðil veya veri tabanýnda bulunmuyor.<br> Lütfen yorumlarýnýz kýsmýnda yöneticiye mesaj atarak bu konuyu bildirin.Lütfen mesajýnýzda ID nizde bulunsun.', //196
'Merhaba!

Kullanýcý ID# :{0}
Þifre         :{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Þifeniz e-posta adresinize baþarý ile gönderildi.', //198
'Lütfen Kullanýcý No yu giriniz', //199
'Þifre Gönder', //200
'Mesaj Gönderme iþlemi kapalý', //201
'Kullanýcý ID# ye mesaj gönderme', //202
'Kullanýcý mesajý okuduðu zaman bana bildir', //203
'Database de kullanýcý yok', //204
'Ýstatistik bölümü aktif deðil', //205
'Bu aktif ID bulunmuyor', //206
'Profil ID#', //207
'Kullanýcýnýn Ýsmi', //208
'Kullanýcýnýn Soyadý', //209
'Doðum günü', //210
'E-Posta', //211
'Mesajýnýz var', //212 - Subject for email
'Ýþ', //213
'Hobi', //214
'Hakkýnda', //215
'Popülerlik', //216
'E-Posta Gönder', //217
'Kötü Profil', //218
'Favori Listeme Ekle', //219
'Yüklenecek dosya yok , <br>veya yüklemek istediðiniz dosya {0} Kb limitini aþýyor. Dosya büyüklüðü {1} Kb', //220
'Yüklemek istediðiniz resmin geniþliði {0} piksel den veya yüksekliði{1} pikselden daha büyük.', //221
'Yüklemek istediðiniz dosya biçimi yanlýþtýr.(Sadece jpg, gif veya png olmalý). Sizinki - ', //222
'(Max. {0} Kb)', //223
'Ülke istatistikleri', //224
'Mesajýnýz Yok', //225
'Toplam Mesaj- ', //226
'Sýra', //227 Number
'Kimden', //228
'Tarih', //229
'Sil', //230 Delete
'<sup>Yeni</sup>', //231 New messages
'Seçili Mesajlarý Sil', //232
'Gelen Mesaj - ', //233
'Cevapla', //234
'Merhaba, Mesajýnýz {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Mesajýnýz Okundu', //236
'Mesajýnýz:<br><br><span class=dat>{0}</span><br><br>{1} tarafýndan okundu [ID#{2}] Okunma Zamaný {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} mesaj baþarý ile silindi!', //238
'Lütfen eski þifrenizi giriniz', //239
'Lütfen yeni þifrenizi giriniz', //240
'Lütfen yeni þifrenizi yendiden giriniz', //241
'Þifre Deðiþtirme', //242
'Eski Þifre', //243
'Yeni Þifre', //244
'Yeni þifrenizi yeniden giriniz', //245
'Favori Listenizde herhangibir kullanýcý bulunmuyor', //246
'Ekleme Tarihi', //247
'Seçili kullanýcýlarý sil', //248
'Bilgilerinizi silmek istediðinize emin misiniz?<br>Bütün mesajlar, resimler silinecektir.', //249
'{0} nolu kullanýcý veritabanýndan baþarý ile silindi', //250
'Bilgileriniz yönetici onayýndan sonra silinecektir', //251
'{0} kullanýcý baþarýyla silindi!', //252
'Hatalý þifre. ÞÝfre karakterlerinde hatalý karakter bulunuyor olabilir', //253
'Þifer deðiþtirmek için hakkýnýz yok', //254
'Eski þifreniz yanlýþ. LÜtfen tekrar deneyiniz!', //255
'Þifreniz baþarý ile deðiþtirildi!', //256
'Bütün resimleri silmek mümkün deðil', //257
'Bilgileriniz baþarý ile deðiþtirildi', //258
' - Resmi Sil', //259
'Sitedeki bilgileriniz baþarý ile temizlendi. Tarayýcýnýzý kapatabilirsiniz', //260
'Bayrak Dosyasý Bulunamadý', //261
'Diller', //262
'Tamam', //263
'Giriþ [3-16 Karakter[A-Za-z0-9_]]', //264
'Giriþ', //265
'Kullanýcý NO: 3-6 karakter arasýnda olmalý ve  A-Za-z0-9_ karakterleri kullanýlabilir', //266
'Bu giriþ veritabanýnda bulunuyor. Lütfen tekrar deneyin!', //267
'Toplam Kullanýcý - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=tr&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
'User type', //270
'Purshase date', //271
'Search results position', //272
'Price', //273
'month', //274
'Purshase Last date', //275
'Higher than', //276
'Purshase', //277
'Purshase with', //278
'PayPal', //279
'Thanks for your registration. Payment has been succesfully send and will be checked by admin in short time.', //280
'Incorrect error. Please try again, or contact with admin!', //281
'Send congratulation letter about privilegies activating', //282
'User type has successfully changed.', //283
'Email with congratulations has been send to user.', //284
'ZIP',// 285 Zip code
'Congratulations, 

Your status is changed to {0}. This privilegies will be available in next {1} month.

Now you can check your messages in your box.

__________________________________
{2}', //286 {0} - Ex:Gold member, {1} - month number, {2} - Sitename from config
'Congratulations', //287 Subject
'ZIP code must be numeric', //288
'Keywords', //289
'We are sorry, but the following error occurred:', //290
'', //291
'', //292
'', //293
'', //294
'', //295
'', //296
'', //297
'', //298
'' //299
); 
?>
