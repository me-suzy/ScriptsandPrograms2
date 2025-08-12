<?PHP 
// ================================================
// Default toolbar data file
// ================================================

// array to hold toolbar definitions
// first dimension - toolbar location (top, left, right, bottom)
// second dimension - toolbar row/column
// third dimension - settings/data
// fourth dimension - setting/toolbar item
// toolbar item: name - item name, type - item type (button, dropdown, separator, etc.)

$visEdit_toolbar_data = array(
  'top_design' => array(
      array(
        'settings' => array(
          'align' => 'left',
          'valign' => 'top'
        ),
        'data' => array (
            array(
              'name' => 'cut',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'copy',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'paste',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'undo',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'redo',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'bold',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'italic',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'underline',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'style',
              'type' => visEdit_TBI_DROPDOWN
            ),
        ) // data
      ),
      array(
        'settings' => array(
          'align' => 'left',
          'valign' => 'top'
        ),
        'data' => array (
            array(
              'name' => 'hyperlink',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'image_insert',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'image_prop',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'hr',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'ordered_list',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'bulleted_list',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'indent',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'unindent',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'left',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'center',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'right',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'fore_color',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'bg_color',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'vertical_separator',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'cleanup',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'toggle_borders',
              'type' => visEdit_TBI_BUTTON
            ),
        ) // data
      ),
  ),

  'bottom_design' => array(
      array(
        'settings' => array(
          'align' => 'right',
          'valign' => 'top'
        ),
        'data' => array (
            array(
              'name' => 'design_tab_on',
              'type' => visEdit_TBI_IMAGE
            ),
            array(
              'name' => 'html_tab',
              'type' => visEdit_TBI_BUTTON
            ),
        ) // data
      )
  ),

  'bottom_html' => array(
      array(
        'settings' => array(
          'align' => 'right',
          'valign' => 'top'
        ),
        'data' => array (
            array(
              'name' => 'design_tab',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'html_tab_on',
              'type' => visEdit_TBI_IMAGE
            ),
        ) // data
      )
  ),
  
  'left_design' => array(
      array(
        'settings' => array(
          'align' => 'center',
          'valign' => 'top'
        ),
        'data' => array (
            array(
              'name' => 'table_create',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_prop',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_cell_prop',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_row_insert',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_column_insert',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_row_delete',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_column_delete',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_cell_merge_right',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_cell_merge_down',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_cell_split_horizontal',
              'type' => visEdit_TBI_BUTTON
            ),
            array(
              'name' => 'table_cell_split_vertical',
              'type' => visEdit_TBI_BUTTON
            )
        ) // data
      )
  )
);
?>
