<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Bulgarian language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Translated: Atanas Tchobanov, atanas@webdressy.com
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-04-10
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'windows-1251';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Îòðåæè'
  ),
  'copy' => array(
    'title' => 'Êîïèðàé'
  ),
  'paste' => array(
    'title' => 'Âìúêíè'
  ),
  'undo' => array(
    'title' => 'Îòìåíè'
  ),
  'redo' => array(
    'title' => 'Ïîâòîðè'
  ),
  'hyperlink' => array(
    'title' => 'Ëèíê'
  ),
  'image_insert' => array(
    'title' => 'Âìúêíè êàðòèíêà',
    'select' => 'Âìúêíè',
    'cancel' => 'Îòìåíè',
    'library' => 'Áèáëèîòåêà',
    'preview' => 'Ïðåãëåä',
    'images' => 'Êàðòèíêè',
    'upload' => 'Èçïðàòè êàðòèíêà',
    'upload_button' => 'Èçïðàòè',
    'error' => 'Ãðåøêà',
    'error_no_image' => 'Èçáåðåòå êàðòèíêà',
    'error_uploading' => 'Ãðåøêà ïðè èçïðàùàíåòî. Ïðîáâàéòå ïàê.',
    'error_wrong_type' => 'Íåïðàâèëåí òèï êàðòèíêà',
    'error_no_dir' => 'Áèáëèîòåêàòà íå ñúùåñòâóâà',
  ),
  'image_prop' => array(
    'title' => 'Ïàðàìåòðè íà êàðòèíêàòà',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
    'source' => 'Èçòî÷íèê',
    'alt' => 'Êðàòêî îïèñàíèå',
    'align' => 'Ïîäðàâíÿâàíå',
    'left' => 'íàëÿâî (left)',
    'right' => 'íàäÿñíî (right)',
    'top' => 'ãîðå (top)',
    'middle' => 'â öåíòúðà (middle)',
    'bottom' => 'äîëó (bottom)',
    'absmiddle' => 'àáñ. öåíòúð (absmiddle)',
    'texttop' => 'îòãîðå (texttop)',
    'baseline' => 'îòäîëó (baseline)',
    'width' => 'Øèðèíà',
    'height' => 'Âèñî÷èíà',
    'border' => 'Ðàìêà',
    'hspace' => 'Ãîð. ðàçñòîÿíèå',
    'vspace' => 'Âåðò. ðàçñòîÿíèå',
    'error' => 'Ãðåøêà',
    'error_width_nan' => 'Øèðèíàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_height_nan' => 'Âèñî÷èíàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_border_nan' => 'Ðàìêàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_hspace_nan' => 'Õîðèçîíòàëíèòå ïîëåòà òðÿáâà äà ñà ÷èñëåíà ñòîéíîñò',
    'error_vspace_nan' => 'Âåðòèêàëíèòå ïîëåòà òðÿáâà äà ñà ÷èñëåíà ñòîéíîñò',
  ),
  'hr' => array(
    'title' => 'Õîðèçîíòàëíà ëèíèÿ'
  ),
  'table_create' => array(
    'title' => 'Ñúçäàé òàáëèöà'
  ),
  'table_prop' => array(
    'title' => 'Ïàðàìåòðè íà òàáëèöàòà',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
    'rows' => 'Ðåäîâå',
    'columns' => 'Êîëîíè',
    'width' => 'Øèðèíà',
    'height' => 'Âèñî÷èíà',
    'border' => 'Ðàìêà',
    'pixels' => 'ïèêñ.',
    'cellpadding' => 'Îòñòúï îò ðàìêàòà',
    'cellspacing' => 'Ðàçñòîÿíèå ìåæäó êëåòêèòå',
    'bg_color' => 'Öâÿò íà ôîíà',
    'error' => 'Ãðåøêà',
    'error_rows_nan' => 'Ðåäîâåòå òðÿáâà äà ñà ÷èñëåíà ñòîéíîñò',
    'error_columns_nan' => 'Êîëîíèòå òðÿáâà äà ñà ÷èñëåíà ñòîéíîñò',
    'error_width_nan' => 'Øèðèíàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_height_nan' => 'Âèñî÷èíàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_border_nan' => 'Ðàìêàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_cellpadding_nan' => 'Îòñòúïúò îò ðàìêàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_cellspacing_nan' => 'Ðàçñòîÿíèåòî ìåæäó êëåòêèòå òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
  ),
  'table_cell_prop' => array(
    'title' => 'Ïàðàìåòðè íà êëåòêàòà',
    'horizontal_align' => 'Õîðèçîíòàëíî ïîäðàâíÿâàíå',
    'vertical_align' => 'Âåðòèêàëíî ïîäðàâíÿâàíå',
    'width' => 'Øèðèíà',
    'height' => 'Âèñî÷èíà',
    'css_class' => 'Ñòèë',
    'no_wrap' => 'Áåç ïðåíîñè',
    'bg_color' => 'Öâÿò íà ôîíà',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
    'left' => 'Íàëÿâî',
    'center' => 'Â öåíòúðà',
    'right' => 'Íàäÿñíî',
    'top' => 'Îòãîðå',
    'middle' => 'Â öåíòúðà',
    'bottom' => 'Îòäîëó',
    'baseline' => 'Íà áàçîâàòà ëèíèÿ íà òåêñòà',
    'error' => 'Ãðåøêà',
    'error_width_nan' => 'Øèðèíàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    'error_height_nan' => 'Âèñî÷èíàòà òðÿáâà äà å ÷èñëåíà ñòîéíîñò',
    
  ),
  'table_row_insert' => array(
    'title' => 'Âìúêíè ðåä'
  ),
  'table_column_insert' => array(
    'title' => 'Âìúêíè êîëîíà'
  ),
  'table_row_delete' => array(
    'title' => 'Ïðåìàõíè ðåä'
  ),
  'table_column_delete' => array(
    'title' => 'Ïðåìàõíè êîëîíà'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Îáåäèíè íàäÿñíî'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Îáåäèíè íàëÿâî'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Ðàçäåëè õîðèçîíòàëíî'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Ðàçäåëè âåðòèêàëíî'
  ),
  'style' => array(
    'title' => 'Ñòèë'
  ),
  'font' => array(
    'title' => 'Øðèôò'
  ),
  'fontsize' => array(
    'title' => 'Ðàçìåð'
  ),
  'paragraph' => array(
    'title' => 'ïàðàãðàô'
  ),
  'bold' => array(
    'title' => 'Ïîëó÷åð'
  ),
  'italic' => array(
    'title' => 'Êóðñèâ'
  ),
  'underline' => array(
    'title' => 'Ïîä÷åðòàí'
  ),
  'ordered_list' => array(
    'title' => 'Ïðîíóìåðîâàí ñïèñúê'
  ),
  'bulleted_list' => array(
    'title' => 'Îáèêíîâåí ñïèñúê'
  ),
  'indent' => array(
    'title' => 'Óâåëè÷è îòñòúïà'
  ),
  'unindent' => array(
    'title' => 'Íàìàëè îòñòúïà'
  ),
  'left' => array(
    'title' => 'Ïîäðàâíÿâàíå íàëÿâî'
  ),
  'center' => array(
    'title' => 'Ïîäðàâíÿâàíå ïî öåíòúðà'
  ),
  'right' => array(
    'title' => 'Ïîäðàâíÿâàíå íàäÿñíî'
  ),
  'fore_color' => array(
    'title' => 'Öâÿò íà òåêñòà'
  ),
  'bg_color' => array(
    'title' => 'Öâÿò íà ôîíà'
  ),
  'design_tab' => array(
    'title' => 'Ïðåâêëþ÷è â ðåæèì íà ìàêåòèðàíå (WYSIWYG)'
  ),
  'html_tab' => array(
    'title' => 'Ïðåâêëþ÷è â ðåæèì íà ðåäàêòèðàíå íà êîäà (HTML)'
  ),
  'colorpicker' => array(
    'title' => 'Èçáîð íà öâÿò',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
  ),
  'cleanup' => array(
    'title' => 'Î÷èñòâàíå íà HTML',
    'confirm' => 'Òàçè îïåðàöèÿ ïðåìàõâà âñè÷êè ñòèëîâå, øðèôòîâå è íåíóæíè òàãîâå îò ñúäúðæàíèåòî â ðåäàêòîðà. Ôîðìàòèðàíåòî ìîæå äà áúäå çàãóáåíî ÷àñòè÷íî èëè èçöÿëî.',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
  ),
  'toggle_borders' => array(
    'title' => 'Âêëþ÷è ðàìêàòà',
  ),
  'hyperlink' => array(
    'title' => 'Ëèíê',
    'url' => 'Àäðåñ',
    'name' => 'Èìå',
    'target' => 'Öåë',
    'title_attr' => 'Íàçâàíèå',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
  ),
  'table_row_prop' => array(
    'title' => 'Ïàðàìåòðè íà ðåäà',
    'horizontal_align' => 'Õîðèçîíòàëíî ïîäðàâíÿâàíå',
    'vertical_align' => 'Âåðòèêàëíî ïîäðàâíÿâàíå',
    'css_class' => 'Ñòèë',
    'no_wrap' => 'Áåç ïðåíîñè',
    'bg_color' => 'Öâÿò íà ôîíà',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
    'left' => 'Îòëÿâî',
    'center' => 'Â öåíòúðà',
    'right' => 'Îòäÿñíî',
    'top' => 'Îòãîðå',
    'middle' => 'Â öåíòúðà',
    'bottom' => 'Îòäîëó',
    'baseline' => 'Ïî áàçîâàòà ëèíèÿ íà òåêñòà',
  ),
  'symbols' => array(
    'title' => 'Ñïåö. ñèìâîëè',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
  ),
  'templates' => array(
    'title' => 'Ãðàôè÷íè ìîäåëè',
  ),
  'page_prop' => array(
    'title' => 'Ïàðàìåòðè íà ñòðàíèöàòà',
    'title_tag' => 'Çàãëàâèå',
    'charset' => 'Êîäîâà òàáëèöà',
    'background' => 'Ôîíîâà êàðòèíêà',
    'bgcolor' => 'Öâÿò íà ôîíà',
    'text' => 'Öâÿò íà òåêñòà',
    'link' => 'Öâÿò íà ëèíêà',
    'vlink' => 'Öâÿò íà ïîñåòåíèòå ëèíêîâå',
    'alink' => 'Öâÿò íà àêòèâíèòå ëèíêîâå',
    'leftmargin' => 'Îòñòúï îòëÿâî',
    'topmargin' => 'Îòñòúï îòãîðå',
    'css_class' => 'Ñòèë',
    'ok' => 'ÃÎÒÎÂÎ',
    'cancel' => 'Îòìåíè',
  ),
  'preview' => array(
    'title' => 'Ïðåäâàðèòåëåí ïðåãëåä',
  ),
  'image_popup' => array(
    'title' => 'Popup êàðòèíêà',
  ),
  'zoom' => array(
    'title' => 'Óâåëè÷åíèå',
  ),
);
?>

