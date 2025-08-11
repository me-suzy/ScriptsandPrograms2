<?php
   function print_object($anObject, $indent = 1) {
      $fields = array_keys($anObject);
      sort($fields);
      for ($i = 0; $i < count($fields); $i++) {
         $fieldName = $fields[$i];
         $field = $anObject[$fieldName];
          
         for ($ii = 0; $ii < $indent; $ii++)
         echo('-----');
          
         if (is_array($field)) {
            echo('> ' . $fieldName . '[]<br />');
            print_object($field, $indent+1);
         }
         else
            echo('> ' . $fieldName . '->' . $field . '<br />');
      }
       
   }
?>
