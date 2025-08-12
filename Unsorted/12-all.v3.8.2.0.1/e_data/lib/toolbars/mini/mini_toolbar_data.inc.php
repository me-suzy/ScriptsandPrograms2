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
              'name' => 'cleanup',
              'type' => visEdit_TBI_BUTTON
            ),
        ) // data
      ),
  ),

  
);
?>
