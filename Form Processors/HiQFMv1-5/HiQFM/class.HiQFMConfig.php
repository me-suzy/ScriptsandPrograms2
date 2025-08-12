<?
// assume current directory as resolve path for config files if not defined otherwise.
if ( !defined ( 'FC_RESOLVE_DIR' ) ) {
   define ( 'FC_RESOLVE_DIR',    './' );
}

// assume '.conf' as extension for configuration files if not defined otherwise
if ( !defined ( 'FC_RESOLVE_EXT' ) ) {
   define ( 'FC_RESOLVE_EXT',    '.conf' );
}

class FormConfiguration {

// -- Static

// - - - - - - - - - - - - - - - - - - - - 
   function DIRECTIVES ( $keyName = false ) {
// - - - - - - - - - - - - - - - - - - - - 
      static $directives = false;
      
      if ( ! $directives ) {
         $directives = array (
            'recipient'                 => 'multiple',
            'bcc'                       => 'multiple',
            'valid_attach_types'        => 'multiple',
            'valid_attach_extensions'   => 'multiple',
            'non_valid_extensions'      => 'multiple',
            'required_fields'           => 'multiple',
            'banned'                    => 'multiple',
            'send_blank_fields'         => 'single',
            'redirect'                  => 'single',
            'auto_responder'            => 'single',
            'subject'                   => 'single',
            'footer_file'               => 'single',
            'header_file'               => 'single',
            'er_footer_file'            => 'single',
            'er_header_file'            => 'single',
            'auto_responder'            => 'single',
            'MAX_FILE_SIZE'             => 'single',
            'safemode_temp_dir'         => 'single',
            'REMOTE_USER'               => 'single',
            'HTTP_HOST'                 => 'single',
            'HTTP_REFERER'              => 'single',
            'REMOTE_ADDR'               => 'single',
            'BROWSER'                   => 'single',
            'safe_mode'                 => 'single'
         );
      }
      if ( !$keyName ) {
         return $directives;
      } else {
         return $directives [ $keyName ];
      }
   }
      

// - - - - - - - - - - - - - - - - - - - - 
   function RESOLVE ( $conf ) {
// - - - - - - - - - - - - - - - - - - - - 
      // filter double dots, slashes and backslashes from
      // config to prevent "directory-up" behaviour (possible security hole)
      $filteredName = str_replace ( array ( '..', '\\', '/' ), '', $conf );
      
      $file = 
         FC_RESOLVE_DIR
      .  $filteredName
      .  FC_RESOLVE_EXT;   
      
      if ( file_exists ( $file )) {
         return $file;
      } else {
         trigger_error ( "Invalid configuration handle" );
      }
   }

// -- Non-static
   var   $confName;
   var   $configuration;
   var   $hasErrors;

// - - - - - - - - - - - - - - - - - - - - 
   function FormConfiguration ( $confName ) {
// - - - - - - - - - - - - - - - - - - - - 
      $this->configuration = array ();
      $this->hasErrors = false;
      $this->confName = $confName;
      $this->parseFile ( FormConfiguration::RESOLVE ( $confName ) );
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function parseFile ( $file ) {
// - - - - - - - - - - - - - - - - - - - - 
      foreach ( file ( $file ) as $n => $line ) {
         // trim whitespace
         $line = trim ( $line );         
         // replace comments
         // replace white space(\s*) followed by a # 
         // followed by any characters(.*) to end of string($) with null
         // beginning and ending / ??????
         $line = preg_replace ( '/\s*#.*$/', '', $line );

         // skip white and comment-only lines
         if ( strlen ( $line ) == 0 ) {
            continue;
         }         
         // match directives of the form
         // "[directive name]  value"         
         if ( preg_match ( '~^\s*\[([^\]]+?)\]\s*(.*)\s*$~', $line, $match ) ) {

         // if no value found set it to 'null'
         //if ($match[2] == '')  $match[2] = 'null';
         if (strtolower($match[2]) == 'null')  $match[2] = '';
         if (strtolower($match[2]) == 'none')  $match[2] = '';
         if (strtolower($match[2]) == 'no')  $match[2] = '';
         if ($match[2] == '0')  $match[2] = '';

            // check validity of and store the directive
            if ( $this->isValidDirective ( $match[1] ) ) {
               // special case - subject can contain blanks
               if ( $match[1] != 'subject') {
                  $match[2] = str_replace ( ' ', '', $match[2] );
               }   
               $this->storeDirective ( $match[1], $match[2] );
            } else {
               $this->parseError ( $n, $line, 'Invalid directive' );
            }
            
         } else {
            $this->parseError ( $n, $line, 'Parse error' );
         }
      }
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function parseError ( $lineNr, $line, $comment ) {
// - - - - - - - - - - - - - - - - - - - - 
      $this->hasErrors = true;
      trigger_error ( 
         sprintf ( 
            "Bad configuration. Line %d in configuration %s contains rubbish near \"%s\" (%s)", 
            $lineNr +1, 
            $this->confName,
            $line,
            $comment
         )
      );
      
      exit;
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function isOk () {
// - - - - - - - - - - - - - - - - - - - - 
      return $this->hasErrors == false;
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function isValidDirective ( $directive ) {
// - - - - - - - - - - - - - - - - - - - - 
      return ( in_array ( $directive, array_keys ( FormConfiguration::DIRECTIVES () ) ) );
   }
   

// - - - - - - - - - - - - - - - - - - - - 
   function storeDirective ( $directive, $value ) {
// - - - - - - - - - - - - - - - - - - - - 
      if ( FormConfiguration::DIRECTIVES ( $directive ) == 'single' ) {
         $this->configuration [ $directive ] = $value;
      } else {
         if ( !isset ( $this->configuration [ $directive ] ) ) {
            $this->configuration [ $directive ] = array ();
         }
         $this->configuration [ $directive ][]= $value;
      }
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function getHash () {
// - - - - - - - - - - - - - - - - - - - - 
      return $this->configuration;
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function getDirective ( $directive ) {
// - - - - - - - - - - - - - - - - - - - - 
      if ( isset ( $this->configuration [ $directive ] ) ) {
         return $this->configuration [ $directive ];
      } else {
         return null;
      }
   }
   
// - - - - - - - - - - - - - - - - - - - - 
   function dump () {
// - - - - - - - - - - - - - - - - - - - - 
      echo '<table style="border-collapse:collapse;" border="1">';
      foreach ( $this->configuration as $field => $value ) {
         printf ( '<tr><th>%s</th>', $field );
         echo '<td>';
         if ( is_array ( $value ) ) {
            echo '<ul style="margin:0 0 0 1em;">';
            foreach ( $value as $entry ) {
               printf ( '<li>%s</li>', htmlentities ( $entry ) );
            }
            echo '</ul>';
         } else {
            echo htmlentities ($value);
         }
         echo '</td></tr>';
      }
      echo '</table>';
   }
}

?>