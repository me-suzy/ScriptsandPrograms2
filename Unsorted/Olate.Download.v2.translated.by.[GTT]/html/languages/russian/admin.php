<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 *   
 *   
 *   
 *   Fully translated by GTT                  
 ***************************************************************************/

// Define admin texts - see config.php for author information
$language = array();
$language['button_activate']						= 'Àêòèâèðîâàòü';
$language['button_add']								= 'Äîáàâèòü';
$language['button_update']							= 'Îáíîâèòü';
$language['credits_poweredby']						= 'Èñïîëüçóåòñÿ ñêðèïò Olate Download ';
$language['credits_translatedby']						= 'Ïåðåâîä îò GTT';
$language['description_accessdenied']				= 'Äîñòóï çàêðûò';
$language['description_allfields']					= 'Âû äîëæíû çàïîëíèòü âñå íåîáõîäèìûå ïîëÿ. <a href="JavaScript:history.go(-1);">Âåðíóòüñÿ</a>.';
$language['description_categories_add']				= '×òîáû äîáàâèòü íîâóþ êàòåãîðèþ â áàçó äàííûõ, ââåäèòå å¸ èìÿ è íàæìèòå íà êíîïêó Äîáàâèòü';
$language['description_categories_added']			= 'Êàòåãîðèÿ áûëà äîáàâëåíà â áàçó äàííûõ.';
$language['description_categories_delete']			= '×òîáû óäàëèòü êàòåãîðèþ, âûáåðèòå íóæíóþ èç ñïèñêà:';
$language['description_categories_deleted']			= 'Êàòåãîðèÿ áûëà óñïåøíî óäàëåíà.';
$language['description_categories_edit']			= '×òîáû îòðåäàêòèðîâàòü êàòåãîðèþ, âûáåðèòå íóæíóþ èç ñïèñêà:';
$language['description_categories_edit_view']		= '×òîáû îòðåäàêòèðîâàòü êàòåãîðèþ, èçìåíèòå å¸ íàçâàíèå è íàæìèòå íà êíîïêó Îáíîâèòü:';
$language['description_categories_edited']			= 'Êàòåãîðèÿ áûëà îáíîâëåíà.';
$language['description_categories_name']			= 'Íàçâàíèå:';
$language['description_config_colours']				= 'Öâåòà è øðèôòû âû ñìîæåòå íàñòðîèòü â ôàéëå /css/style.css.';
$language['description_config_general']				= 'Íàñòðîéêè. Èçìåíèòå ïî âàøåìó æåëàíèþ, à ïîòîì íàæìèòå íà êíîïêó Îáíîâèòü.';
$language['description_config_general_noslash']		= '(íå ñòàâüòå îáðàòíûé ñëýø "/")';
$language['description_config_general_upd']			= 'Áàçà äàííûõ áûëà îáíîâëåíà.';
$language['description_config_language']			= 'Íà ýòîé ñòðàíèöå âû ìîæåòå óâèäåòü êàêèå ÿçûêè âû ìîæåòå èñïîëüçîâàòü. Òàê æå âû óâèäåòå äàòó ñîçäàíèÿ ÿçûêà è àâòîðà.';
$language['description_config_language_activated']	= 'Íîâûé ÿçûê áûë àêòèâèðîâàí.';
$language['description_config_language_created']	= 'Ïåðåâåë ñêðèïò ';
$language['description_config_language_current']	= 'ßçûê, êîòîðûé âû èñïîëüçóåòå ñåé÷àñ:';
$language['description_config_language_designed']	= '.<br>Ïåðåâîä äëÿ ñêðèïòà Olate Download âåðñèè ';
$language['description_config_language_new']		= 'Íîâûå ÿçûêè âû ñìîæåòå ñêà÷àòü íà ñàéòå - ðàçðàáîò÷èêå ñêðèïòà <a href="http://www.olate.com" target="_blank">Olate website</a>. Èíñòðóêöèè ïî óñòàíîâêå íîâîãî ÿçûêà âû íàéäåòå â èíñòðóêöèÿõ ê ñêðèïòó. ';
$language['description_config_language_on']			= '<br>Äàòà ñîçäàíèÿ: ';
$language['description_config_language_select']		= 'Âûáåðèòå ÿçûê èç ñïèñêà äîñòóïíûõ ÿçûêîâ è íàæìèòå íà êíîïêó Àêòèâèðîâàòü, ÷òîáû ïðèíÿòü èçìåíåíèÿ.';
$language['description_config_languagesel']			= '---Äîñòóïíûå ÿçûêè---';
$language['description_downloads_add']				= '×òîáû äîáàâèòü íîâûé ôàéë â áàçó äàííûõ, çàïîëíèòå íèæå ôîðìó è íàæìèòå íà êíîïêó Äîáàâèòü. Åñëè âû íå õîòèòå èñïîëüçîâàòü ïîëÿ ïî âûáîðó, îñòàâüòå èõ ïóñòûìè.';
$language['description_downloads_added']			= 'Íîâûé ôàéë áûë äîáàâëåí â áàçó äàííûõ.';
$language['description_downloads_categorysel']		= '---Âûáåðèòå êàòåãîðèþ---';
$language['description_downloads_categorycur']		= 'Òåêóùàÿ: ';
$language['description_downloads_delete']			= '×òîáû óäàëèòü ôàéë, âûáåðèòå íóæíûé âàì ôàéë èç ñïèñêà:';
$language['description_downloads_deleted']			= 'Ôàéë áûë óäàëåí èç ñïèñêà.';
$language['description_downloads_edit']				= '×òîáû îòðåäàêòèðîâàòü ôàéë, âûáåðèòå íóæíûé âàì ôàéë èç ñïèñêà:';
$language['description_downloads_edit_view']		= 'Ôàéë, êîòîðûé âû âûáðàëè ïîêàçàí íèæå. Âíåñèòå íåîáõîäèìûå âàì èçìåíåíèÿ è íàæìèòå íà êíîïêó Îáíîâèòü.';
$language['description_downloads_edited']			= 'Ôàéë áûë îáíîâëåí.';
$language['description_downloads_mb']				= 'ìá:';
$language['description_downloads_noimg']			= 'Îñòàâüòå ïîëå ïóñòûì, åñëè íå èñïîëüçóåòå êàðòèíêó ';
$language['description_loggedinas']					= 'Âû çàøëè êàê ';
$language['description_main']						= 'Íà ýòîé ñòðàíèöå âû ñìîæåòå äîáàâëÿòü ôàéëû è êàòåãîðèè, èçìåíÿòü îñíîâíûå íàñòðîéêè è ìíîãîå äðóãîå.<br><a href="'.$config['urlpath'].'/index.php" target="_blank">Íàæàâ çäåñü, âû ïîïàäåòå íà ãëàâíóþ ñòðàíèöó âàøåãî ôàéëîâîãî àðõèâà.</a>';
$language['description_users_add']					= '<p>×òîáû äîáàâèòü íîâîãî ïîëüçîâàòåëÿ â áàçó äàííûõ, ââåäèòå Ëîãèí è Ïàðîëü äëÿ ïîëüçîâàòåëÿ. Âñå ïàðîëè øèôðóþòñÿ àëãîðèòìîì <b>MD5</b>. Åñëè âû ïîñòàâèòå ãàëî÷êó ðÿäîì ñ íàäïèñüþ <b>Íåóäàëÿåìûé</b>, íèêòî è äàæå Âû íå ñìîæåòå óäàëèòü ýòîãî ïîëüçîâàòåëÿ èç ïàíåëè àäìèíèñòðàòîðà.</p>';
$language['description_users_added']				= 'Ïîëüçîâàòåëü áûë óñïåøíî äîáàâëåí.';
$language['description_users_delete']				= '×òîáû óäàëèòü ïîëüçîâàòåëÿ, âûáåðèòå ëþáîãî èç ñïèñêà. Ïîëüçîâàòåëü ñ ïîìåòêîé <b>Íåóäàëÿåìûé</b> íå áóäåò óäàëåí.';
$language['description_users_deleted']				= 'Ïîëüçîâàòåëü áûë óäàëåí èç áàçû äàííûõ.';
$language['description_other_changelog']			= 'Âû ìîæåòå óâèäåòü ñïèñîê èçìåíåíèé ñ âåðñèè ñêðèïòà 2.0.0 â ñïèñêå èçìåíåíèé ïî <a href="http://www.olate.com/scripts/Olate Download/changelog.php" target="_blank">ýòîìó</a> àäðåñó.';
$language['description_other_license']				= 'Îòñþäà âû ñìîæåòå çàãðóçèòü ïîñëåäíþþ âåðñèÿ ñêðèïòà. Åñëè îáíîâëåíèå áóäåò äîñòóïíî âû óâèäèòå.';
$language['description_other_mailinglist']			= 'Âû ìîæåòå ïîëó÷àòü èíôîðìàöèþ î íîâûõ âåðñèÿõ ñêðèïòà ïî e-mail, ïîäïèñàâøèñü íà <a href="http://www.olate.com/list/index.php" target="_blank">ðàññûëêó</a>.';
$language['description_users_master']				= 'Ïîëüçîâàòåëü, êîòîðîãî âû ïûòàåòåñü óäàëèòü, ïîìå÷åí êàê <b>Íåóäàëÿåìûé</b>.';
$language['description_other_support']				= '<p>Ýòîò ñêðèïò ðàñïðîñòðàíÿåòñÿ áåñïëàòíî. Òåõ. ïîääåðæêà äîñòóïíà òîëüêî íà <a href="http://www.olate.com/forums" target="_blank">ôîðóìàõ </a>. Íèêàêîé e-mail ïîääåðæêè. Ïîääåðæêà îñóùåñòâëÿåòñÿ òîëüêî íà îðèãèíàëüíóþ âåðñèþ ñêðèïòà, òî åñòü íå ìîäèôèöèðîâàííóþ è ñêà÷àííóþ ïðÿìî ñ ñàéòà àâòîðà.</p>';
$language['description_other_updates']				= '<p>Ñåé÷àñ áóäåò ïðîâåäåíà ïðîâåðêà íà îáíîâëåíèÿ ñêðèïòà.</p><p><strong>Îòâåò ñåðâåðà:</strong></p>';
$language['link_addcategory']						= 'Äîáàâèòü íîâóþ êàòåãîðèþ';
$language['link_adddownload']						= 'Äîáàâèòü íîâûé ôàéë';
$language['link_adduser']							= 'Äîáàâèòü íîâîãî ïîëüçîâàòåëÿ';
$language['link_administration']					= 'Àäìèíèñòðèðîâàíèå';
$language['link_adminmain']							= 'Âåðíóòüñÿ ê àäìèíèñòðèðîâàíèþ';
$language['link_clicktologin']						= 'Âõîä â ñèñòåìó';
$language['link_deletecategory']					= 'Óäàëåíèå êàòåãîðèé';
$language['link_deletedownload']					= 'Óäàëåíèå ôàéëîâ';
$language['link_deleteuser']						= 'Óäàëåíèå ïîëüçîâàòåëåé';
$language['link_editcategory']						= 'Ðåäàêòèðîâàíèå êàòåãîðèé';
$language['link_editdownload']						= 'Ðåäàêòèðîâàíèå ôàéëîâ';
$language['link_generalsettings']					= 'Îñíîâíûå íàñòðîéêè';
$language['link_languages']							= 'ßçûêè';
$language['link_languages_viewgenconfig']			= 'Ïîñìîòðåòü îñíîâíûå íàñòðîéêè';
$language['link_languages_viewlangconfig']			= 'Ïîñìîòðåòü äîñòóïíûå ÿçûêè';
$language['link_license']							= 'Ëèöåíçèÿ';
$language['link_logout']							= 'Âûéòè';
$language['link_support']							= 'Òåõ. ïîääåðæêà';
$language['link_updates']							= 'Îáíîâëåíèÿ';
$language['link_viewmain']							= 'Çàéòè íà ãëàâíóþ ñòðàíèöó';
$language['title_admin']							= 'Àäìèíèñòðèðîâàíèå';
$language['title_admin_main']						= 'Àäìèíèñòðèðîâàíèå - Ãëàâíàÿ ñòðàíèöà';
$language['title_categories']						= 'Êàòåãîðèè:';
$language['title_categories_add']					= ' - Êàòåãîðèè - Äîáàâëåíèå íîâîé êàòåãîðèè';
$language['title_categories_delete']				= ' - Êàòåãîðèè - Óäàëåíèå êàòåãîðèè';
$language['title_categories_edit']					= ' - Êàòåãîðèè - Ðåäàêòèðîâàíèå êàòåãîðèè';
$language['title_categories_name']					= 'Íàçâàíèå:';
$language['title_config_general']					= ' - Êîíôèãóðàöèÿ - Îñíîâíûå íàñòðîéêè';
$language['title_config_language']					= ' - Êîíôèãóðàöèÿ - ßçûêè';
$language['title_config_language_available']		= 'Äîñòóïíûå ÿçûêè äëÿ ñêðèïòà';
$language['title_config_general_alldownloads']		= 'Ïîêàçûâàòü "Âñå ôàéëû":';
$language['title_config_general_displaytd']			= 'Ïîêàçûâàòü "Òîï ñêà÷èâàíèé":';
$language['title_config_general_numbertd']			= 'Êîëè÷åñòâî â òîïå:';
$language['title_config_general_numberpage']		= 'Êîëè÷åñòâî ôàéëîâ íà ñòðàíèöå:';
$language['title_config_general_path']				= 'Ïóòü ê ñêðèïòó:';
$language['title_config_general_ratings']			= 'Âêëþ÷èòü ðåéòèíã:';
$language['title_config_general_searchlink']		= 'Ïîêàçûâàòü "Ïîèñê":';
$language['title_config_general_sorting']			= 'Âêëþ÷èòü ñîðòèðîâêó:';
$language['title_config_general_version']			= 'Âåðñèÿ:';
$language['title_configuration']					= 'Êîíôèãóðàöèÿ';
$language['title_downloads']						= 'Ôàéëû:';
$language['title_downloads_add']					= ' - Ôàéëû - Äîáàâëåíèå íîâîãî ôàéëà';
$language['title_downloads_category']				= 'Êàòåãîðèÿ:';
$language['title_downloads_custom1']				= 'Ïîëå ïî âûáîðó 1:';
$language['title_downloads_custom2']				= 'Ïîëå ïî âûáîðó 2:';
$language['title_downloads_custom_label']			= 'Ìåòêà:';
$language['title_downloads_custom_value']			= 'Çíà÷åíèå:';
$language['title_downloads_date']					= 'Äàòà:';
$language['title_downloads_delete']					= ' - Ôàéëû - Óäàëåíèå ôàéëà';
$language['title_downloads_description_b']			= 'Êðàòêîå îïèñàíèå:';
$language['title_downloads_description_f']			= 'Ïîëíîå îïèñàíèå:';
$language['title_downloads_edit']					= ' - Ôàéëû - Ðåäàêòèðîâàíèå ôàéëà';
$language['title_downloads_image']					= 'Àäðåñ êàðòèíêè:';
$language['title_downloads_location']				= 'Àäðåñ ôàéëà:';
$language['title_downloads_name']					= 'Íàçâàíèå:';
$language['title_downloads_size']					= 'Ðàçìåð ôàéëà:';
$language['title_master']							= 'Íåóäàëÿåìûé:';
$language['title_other']							= 'Ðàçíîå:';
$language['title_users_add']						= ' - Ïîëüçîâàòåëè - Äîáàâëåíèå íîâîãî ïîëüçîâàòåëÿ';
$language['title_users_delete']						= ' - Ïîëüçâàòåëè - Óäàëåíèå ïîëüçîâàòåëÿ';
$language['title_other_changelog']					= 'Ñïèñîê èçìåíåíèé:';
$language['title_other_license']					= ' - Ðàçíîå - Ëèöåíçèÿ';
$language['title_other_mailinglist']				= 'Ðàññûëêà:';
$language['title_other_support']					= ' - Ðàçíîå - Òåõ. ïîääåðæêà';
$language['title_other_updates']					= ' - Ðàçíîå - Îáíîâëåíèÿ';
$language['title_password']							= 'Ïàðîëü:';
$language['title_script']							= 'Ñêðèïò íàñòðîéêè';
$language['title_users']							= 'Ïîëüçîâàòåëè:';
$language['title_username']							= 'Ëîãèí:';
?>