<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Brazilian Portuguese language file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// Brazilian Translation: Fernando José Karl, fernandokarl@superig.com.br
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-04-29
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'iso-8859-1';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Cortar'
  ),
  'copy' => array(
    'title' => 'Copiar'
  ),
  'paste' => array(
    'title' => 'Colar'
  ),
  'undo' => array(
    'title' => 'Desfazer'
  ),
  'redo' => array(
    'title' => 'Refazer'
  ),
  'hyperlink' => array(
    'title' => 'Hyperlink'
  ),
  'image_insert' => array(
    'title' => 'Inserir imagem',
    'select' => 'Selecionar',
    'cancel' => 'Cancelar',
    'library' => 'Biblioteca',
    'preview' => 'Preview',
    'images' => 'Imagens',
    'upload' => 'Enviar imagem',
    'upload_button' => 'Upload',
    'error' => 'Erro',
    'error_no_image' => 'Favor selecionar uma imagem',
    'error_uploading' => 'Ocorreu um erro no envio do arquivo. Favor tentar novamente',
    'error_wrong_type' => 'Tipo de arquivo de imagem inválido',
    'error_no_dir' => 'A bilbioteca não existe fisicamente',
  ),
  'image_prop' => array(
    'title' => 'Propriedades da imagem',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
    'source' => 'Fonte',
    'alt' => 'Texto alternativo',
    'align' => 'Alinhamento',
    'left' => 'esquerda',
    'right' => 'direita',
    'top' => 'superior',
    'middle' => 'meio',
    'bottom' => 'inferior',
    'absmiddle' => 'absmiddle',
    'texttop' => 'texttop',
    'baseline' => 'baseline',
    'width' => 'Comprimento',
    'height' => 'Altura',
    'border' => 'Borda',
    'hspace' => 'Espaço hor.',
    'vspace' => 'Espaço vert.',
    'error' => 'Erro',
    'error_width_nan' => 'Comprimento não é um número',
    'error_height_nan' => 'Altura não é um número',
    'error_border_nan' => 'Borda não é um número',
    'error_hspace_nan' => 'Espaço horizontal não é um número',
    'error_vspace_nan' => 'Espaço vertical não é um número',
  ),
  'hr' => array(
    'title' => 'Linha horizontal'
  ),
  'table_create' => array(
    'title' => 'Criar tabela'
  ),
  'table_prop' => array(
    'title' => 'Propriedades da tabela',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
    'rows' => 'Linhas',
    'columns' => 'Colunas',
    'width' => 'Comprimento',
    'height' => 'Altura',
    'border' => 'Borda',
    'pixels' => 'pixels',
    'cellpadding' => 'Recuo células',
    'cellspacing' => 'Espaço células',
    'bg_color' => 'Cor de Fundo',
    'error' => 'Erro',
    'error_rows_nan' => 'Linhas não é um número',
    'error_columns_nan' => 'Colunas não é um número',
    'error_width_nan' => 'Comprimento não é um número',
    'error_height_nan' => 'Height is not a number',
    'error_border_nan' => 'Border is not a number',
    'error_cellpadding_nan' => 'Cell padding is not a number',
    'error_cellspacing_nan' => 'Cell spacing is not a number',
  ),
  'table_cell_prop' => array(
    'title' => 'Propriedades da célula',
    'horizontal_align' => 'Alinh. horizontal',
    'vertical_align' => 'Alinh. vertical',
    'width' => 'Comprimento',
    'height' => 'Altura',
    'css_class' => 'Classe CSS',
    'no_wrap' => 'Sem quebras',
    'bg_color' => 'Cor de fundo',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
    'left' => 'Esquerda',
    'center' => 'Centralizado',
    'right' => 'Direita',
    'top' => 'Superior',
    'middle' => 'Meio',
    'bottom' => 'Inferior',
    'baseline' => 'Baseline',
    'error' => 'Erro',
    'error_width_nan' => 'Comprimento não é um número',
    'error_height_nan' => 'Altura não é um número',
    
  ),
  'table_row_insert' => array(
    'title' => 'Inserir linha'
  ),
  'table_column_insert' => array(
    'title' => 'Inserir coluna'
  ),
  'table_row_delete' => array(
    'title' => 'Apagar linha'
  ),
  'table_column_delete' => array(
    'title' => 'Apagar coluna'
  ),
  'table_cell_merge_right' => array(
    'title' => 'Mesclar direita'
  ),
  'table_cell_merge_down' => array(
    'title' => 'Mesclar abaixo'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'Dividir células horizontalmente'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'Dividir células verticalmente'
  ),
  'style' => array(
    'title' => 'Estilo'
  ),
  'font' => array(
    'title' => 'Fonte'
  ),
  'fontsize' => array(
    'title' => 'Tamanho'
  ),
  'paragraph' => array(
    'title' => 'Parágrafo'
  ),
  'bold' => array(
    'title' => 'Negrito'
  ),
  'italic' => array(
    'title' => 'Itálico'
  ),
  'underline' => array(
    'title' => 'Sublinhado'
  ),
  'ordered_list' => array(
    'title' => 'Numeração'
  ),
  'bulleted_list' => array(
    'title' => 'Marcadores'
  ),
  'indent' => array(
    'title' => 'Aumentar Recuo'
  ),
  'unindent' => array(
    'title' => 'Diminuir Recuo'
  ),
  'left' => array(
    'title' => 'Esquerda'
  ),
  'center' => array(
    'title' => 'Centralizado'
  ),
  'right' => array(
    'title' => 'Direita'
  ),
  'fore_color' => array(
    'title' => 'Realçar'
  ),
  'bg_color' => array(
    'title' => 'Cor de fundo'
  ),
  'design_tab' => array(
    'title' => 'Mudar para modo WYSIWYG (design)'
  ),
  'html_tab' => array(
    'title' => 'Mudar para modo HTML (código)'
  ),
  'colorpicker' => array(
    'title' => 'Seletor de cores',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
  ),
  // <<<<<<<<<< NEW >>>>>>>>>>>>>>>
  'cleanup' => array(
    'title' => 'Limpeza HTML (remover estilos)',
    'confirm' => 'Realizando esta ação vai remover todos estilos, fontes e tags inúteis do conteúdo. Alguma ou toda formatação pode ser perdida.',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
  ),
  'toggle_borders' => array(
    'title' => 'Toggle borders',
  ),
  'hyperlink' => array(
    'title' => 'Hiperlink',
    'url' => 'URL',
    'name' => 'Nome',
    'target' => 'Alvo',
    'title_attr' => 'Título',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
  ),
  'table_row_prop' => array(
    'title' => 'Propriedades da linha',
    'horizontal_align' => 'Alinhamento horizontal',
    'vertical_align' => 'Alinhamento vertical',
    'css_class' => 'Classe CSS',
    'no_wrap' => 'Sem quebras',
    'bg_color' => 'Cor de Fundo',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
    'left' => 'Esquerda',
    'center' => 'Center',
    'right' => 'Direita',
    'top' => 'Topo',
    'middle' => 'Meio',
    'bottom' => 'Inferior',
    'baseline' => 'Linha base',
  ),
  'symbols' => array(
    'title' => 'Caracteres especiais',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
  ),
  'templates' => array(
    'title' => 'Modelos',
  ),
  'page_prop' => array(
    'title' => 'Propriedades da página',
    'title_tag' => 'Título',
    'charset' => 'Codificação',
    'background' => 'Imagem de Fundo',
    'bgcolor' => 'Cor de Fundo',
    'text' => 'Cor texto',
    'link' => 'Cor link',
    'vlink' => 'Cor link visitados',
    'alink' => 'Cor link ativo',
    'leftmargin' => 'Margem esquerda',
    'topmargin' => 'Margem topo',
    'css_class' => 'Classe CSS',
    'ok' => '   OK   ',
    'cancel' => 'Cancelar',
  ),
  'preview' => array(
    'title' => 'Preview',
  ),
  'image_popup' => array(
    'title' => 'Imagem popup',
  ),
  'zoom' => array(
    'title' => 'Zoom',
  ),
);
?>


