<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Czech language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Czech translation: BrM (BrM@bridlicna.cz)
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-20
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-2';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Vyjmout'
  ),
  'copy' => array(
    'title' => 'Kopírovat'
  ),
  'paste' => array(
    'title' => 'Vloit'
  ),
  'undo' => array(
    'title' => 'Zpìt'
  ),
  'redo' => array(
    'title' => 'Vpøed'
  ),
  'hyperlink' => array(
    'title' => 'Hyperlink'
  ),
  'image_insert' => array(
    'title' => 'Vloit obrázek',
    'select' => 'Výbìr',
    'cancel' => 'Zruit',
    'library' => 'Knihovna',
    'preview' => 'Náhled',
    'images' => 'Obrázek',
    'upload' => 'Upload obrázek',
    'upload_button' => 'Upload',
    'error' => 'Chyba',
    'error_no_image' => 'Vyberte obrázek prosím',
    'error_uploading' => 'V prùbìhu uploadu dolo k chybì. Opakujte akci znovu',
    'error_wrong_type' => 'chybný formát obrázku',
    'error_no_dir' => 'Knihovna fyzicky neexistuje',
  ),
  'image_prop' => array(
    'title' => 'Vlastnosti obrázku',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
    'source' => 'Zdroj',
    'alt' => 'Alternativní text',
    'align' => 'Zarovnání',
    'left' => 'vlevo',
    'right' => 'vpravo',
    'top' => 'Horní',
    'middle' => 'Støední',
    'bottom' => 'Spodní',
    'absmiddle' => 'absolutní støed',
    'texttop' => 'text-nahoru',
    'baseline' => 'baseline',
    'width' => 'íøka',
    'height' => 'Výka',
    'border' => 'Okraje',
    'hspace' => 'Hor. space',
    'vspace' => 'Vert. space',
    'error' => 'Chyba',
    'error_width_nan' => 'íøka není èíslo',
    'error_height_nan' => 'Výka není èíslo',
    'error_border_nan' => 'Okraj není èíslo',
    'error_hspace_nan' => 'Horizontální rozteè není èíslo',
    'error_vspace_nan' => 'Vertikální rozteè není èíslo',
  ),
  'hr' => array(
    'title' => 'Horizontal rule'
  ),
  'table_create' => array(
    'title' => 'Vytvoø tabulku'
  ),
  'table_prop' => array(
    'title' => 'Vlastnosti tabulky',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
    'rows' => 'Øádkù',
    'columns' => 'Sloupcù',
    'width' => 'íøka',
    'height' => 'Výka',
    'border' => 'Okraje',
    'pixels' => 'pixelù',
    'cellpadding' => 'Odsazení v buòce',
    'cellspacing' => 'Vzdálenost bunìk',
    'bg_color' => 'Barva pozadí',
    'error' => 'Chyba',
    'error_rows_nan' => 'Øádky nejsou èíslo',
    'error_columns_nan' => 'Sloupce nejsou èíslo',
    'error_width_nan' => 'íøka není èíslo',
    'error_height_nan' => 'Výka není èíslo',
    'error_border_nan' => 'Okraje nejsou èíslo',
    'error_cellpadding_nan' => 'Odsazení v buòce není èíslo',
    'error_cellspacing_nan' => 'Vzdálenost buòek není èíslo',
  ),
  'table_cell_prop' => array(
    'title' => 'Vlastnosti buòky',
    'horizontal_align' => 'Horizontální zarovnání',
    'vertical_align' => 'Vertikální zarovnání',
    'width' => 'íøka',
    'height' => 'Výka',
    'css_class' => 'CSS tøída',
    'no_wrap' => 'nezalamovat',
    'bg_color' => 'Barva pozadí',
    'ok' => '   OK   ',
    'cancel' => 'Zruit',
    'left' => 'Vlevo',
    'center' => 'Na støed',
    'right' => 'Vpravo',
    'top' => 'Nahoru',
    'middle' => 'Doprostøed',
    'bottom' => 'Dolù',
    'baseline' => 'Základní linka',
    'error' => 'Chyba',
    'error_width_nan' => 'íøka není èíslo',
    'error_height_nan' => 'Výka není èíslo',
    
  ),
  'table_row_insert' => array(
    'title' => 'Vloit øádek'
  ),
  'table_column_insert' => array(
    'title' => 'Vloit sloupec'
  ),
  'table_row_delete' => array(
    'title' => 'Vyma øádek'
  ),
  'table_column_delete' => array(
    'title' => 'Vyma sloupec'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Slouèit vpravo'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Slouèit dolù'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Rozdìlit buòku horizontálnì'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Rozdìlit buòku vertikálnì'
  ),
  'style' => array(
    'title' => 'Styl'
  ),
  'font' => array(
    'title' => 'Font'
  ),
  'fontsize' => array(
    'title' => 'Velikost'
  ),
  'paragraph' => array(
    'title' => 'Odstavec'
  ),
  'bold' => array(
    'title' => 'Tuèné'
  ),
  'italic' => array(
    'title' => 'Kurziva'
  ),
  'underline' => array(
    'title' => 'Podtrení'
  ),
  'ordered_list' => array(
    'title' => 'Èíslování'
  ),
  'bulleted_list' => array(
    'title' => 'Odøáky'
  ),
  'indent' => array(
    'title' => 'Odsazení'
  ),
  'unindent' => array(
    'title' => 'Zruit odsazení'
  ),
  'left' => array(
    'title' => 'Vlevo'
  ),
  'center' => array(
    'title' => 'Na støed'
  ),
  'right' => array(
    'title' => 'Vpravo'
  ),
  'fore_color' => array(
    'title' => 'Barva popøedí'
  ),
  'bg_color' => array(
    'title' => 'Barva pozadí'
  ),
  'design_tab' => array(
    'title' => 'Pøepnout do WYSIWYG módu'
  ),
  'html_tab' => array(
    'title' => 'Pøepnout do HTML módu'
  ),
  'colorpicker' => array(
    'title' => 'Paleta barev',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
  ),
  // <<<<<<<<< NEW >>>>>>>>>
  'cleanup' => array(
    'title' => 'HTML kontrola (odstraní styly)',
    'confirm' => 'Provedením akce odstraníte vechny styly, fonty a zbyteèné tagy z aktuálního obsahu. Nìkteré, nebo vechno formátování bude odstranìno.',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
  ),
  'toggle_borders' => array(
    'title' => 'Upravit okraje',
  ),
  'hyperlink' => array(
    'title' => 'Hyperlink',
    'url' => 'URL',
    'name' => 'Jméno',
    'target' => 'Cíl',
    'title_attr' => 'Název',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
  ),
  'table_row_prop' => array(
    'title' => 'Vlastnosti øádku',
    'horizontal_align' => 'Horizontální zarovnání',
    'vertical_align' => 'Vertikální zarovnání',
    'css_class' => 'CSS class',
    'no_wrap' => 'Nezalamovat',
    'bg_color' => 'Barva pozadí',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
    'left' => 'Vlevo',
    'center' => 'Na støed',
    'right' => 'Vpravo',
    'top' => 'Horní',
    'middle' => 'Støední',
    'bottom' => 'Spodní',
    'baseline' => 'Základní linka',
  ),
  'symbols' => array(
    'title' => 'Speciální znaky',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
  ),
  'symbols' => array(
    'title' => 'Speciální znaky',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
  ),
  'templates' => array(
    'title' => 'ablony',
  ),
  'page_prop' => array(
    'title' => 'Vlastnosti stránky',
    'title_tag' => 'Název',
    'charset' => 'Kódování',
    'background' => 'Obrázek pozadí',
    'bgcolor' => 'Barva pozadí',
    'text' => 'Barva textu',
    'link' => 'Barva odkazu',
    'vlink' => 'Barva navtíveného odkazu',
    'alink' => 'Barva aktivního odkazu',
    'leftmargin' => 'Levý okraj',
    'topmargin' => 'Horní okraj',
    'css_class' => 'CSS class',
    'ok' => '   OK   ',
    'cancel' => 'Storno',
  ),
  'preview' => array(
    'title' => 'Náhled',
  ),
  'image_popup' => array(
    'title' => 'Pøekrývání obrázkù',
  ),
  'zoom' => array(
    'title' => 'Pøiblíení',
  ),
);
?>

