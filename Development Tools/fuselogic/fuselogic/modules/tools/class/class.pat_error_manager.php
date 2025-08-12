<?php
						
define( 'PATERRORMANAGER_ERROR_ILLEGAL_OPTIONS', 1 );						
define( 'PATERRORMANAGER_ERROR_CALLBACK_NOT_CALLABLE', 2 ); 
$GLOBALS['_pat_errorHandling']	=	array( 
											E_NOTICE	=> array( 'mode' => 'echo' ),
											E_WARNING	=> array( 'mode' => 'echo' ),
											E_ERROR		=> array( 'mode' => 'die' )
										);
$GLOBALS['_pat_errorLevels']	=	array(
											E_NOTICE	=> 'Notice',
											E_WARNING	=> 'Warning',
											E_ERROR		=> 'Error'
										);
$GLOBALS['_pat_errorClass']	=	'patError';
$GLOBALS['_pat_errorIgnores']	=	array();
$GLOBALS['_pat_errorExpects']	=	array();

class patErrorManager
{   
    function isError( &$object )
    {
		if( !is_object( $object ) )
		{
			return false;
		}
		
		if( !is_a( $object, $GLOBALS['_pat_errorClass'] ) )
		{
			return false;
		}
		
        return  true;
    }	
  
	function &raiseError( $code, $msg, $info = null )
	{
		return patErrorManager::raise( E_ERROR, $code, $msg, $info );
	}
	  
	function &raiseWarning( $code, $msg, $info = null )
	{
		return patErrorManager::raise( E_WARNING, $code, $msg, $info );
	}	
  
	function &raiseNotice( $code, $msg, $info = null )
	{
		return patErrorManager::raise( E_NOTICE, $code, $msg, $info );
	}
	
    function &raise( $level, $code, $msg, $info = null )
    {		
		if( in_array( $code, $GLOBALS['_pat_errorIgnores'] ) )
		{
			return false;
		}
	
		if( !empty( $GLOBALS['_pat_errorExpects'] ) )
		{
			$expected =	array_pop( $GLOBALS['_pat_errorExpects'] );
			if( in_array( $code, $expected ) )
			{
				return false;
			}
		}
	
		$class	=	$GLOBALS['_pat_errorClass'];
		if( !class_exists( $class ) )
		{
			include_once dirname( __FILE__ ) . '/'. $class .'.php';
		}
		
		$error			=&	new	$class( $level, $code, $msg, $info );
		
		$level_human	=	patErrorManager::translateErrorLevel( $level );

		$handling	=	patErrorManager::getErrorHandling( $level );
		switch( $handling['mode'] )
		{
			case 'ignore':
				break;
		
			case 'trigger':
				switch( $error->getLevel() )
				{
					case	E_NOTICE:
						$level	=	E_USER_NOTICE;
						break;
					case	E_WARNING:
						$level	=	E_USER_WARNING;
						break;
					case	E_NOTICE:
						$level =	E_NOTICE;
						break;
					default:
						$level	=	E_USER_ERROR;
						break;
				}
			
				trigger_error( $error->getMessage(), $level );
				break;
		
			case 'verbose':
				if( isset( $_SERVER['HTTP_HOST'] ) )
				{
					echo "<br /><b>pat-$level_human</b>: " . $error->getMessage() . "<br />\n";
					if( $info != null )
					{
						echo "&nbsp;&nbsp;&nbsp;" . $error->getInfo() . "<br />\n";
					}
				}
				else
				{
					echo "pat-$level_human: " . $error->getMessage() . "\n";
					if( $info != null )
					{
						echo "    " . $error->getInfo() . "\n";
					}
				}
				break;
				
			case 'echo':
				if( isset( $_SERVER['HTTP_HOST'] ) )
				{
					echo "<br /><b>pat-$level_human</b>: " . $error->getMessage() . "<br />\n";
				}
				else
				{
					if( defined( 'STDERR' ) )
					{
						fwrite( STDERR, "pat-$level_human: " . $error->getMessage() . "\n" );
					}
					else
					{
						echo "pat-$level_human: " . $error->getMessage() . "\n";
					}
				}
				
				break;
				
			case 'callback';
				$opt	=	$handling['options'];
				$error	=	&call_user_func( $opt, $error );
				break;
				
			case 'die':
				if( isset( $_SERVER['HTTP_HOST'] ) )
				{
					die( "<br /><b>pat-$level_human</b> " . $error->getMessage() . "<br />\n" );
				}
				else
				{
					if( defined( 'STDERR' ) )
					{
						fwrite( STDERR, "pat-$level_human " . $error->getMessage() . "\n" );
					}
					else
					{
						die( "pat-$level_human " . $error->getMessage() . "\n" );
					}
				}
				break;
		}		
        return  $error;
    }

	function registerErrorLevel( $level, $name )
	{
		if( isset( $GLOBALS['_pat_errorLevels'][$level] ) )
		{
			return false;
		}
		$GLOBALS['_pat_errorLevels'][$level]	=	$name;
		patErrorManager::setErrorHandling( $level, 'ignore' );
		return	true;
	}
	  
    function setErrorHandling( $level, $mode, $options = null )
    {
		$levels	=	$GLOBALS['_pat_errorLevels'];

		foreach( $levels as $eLevel => $eTitle )
		{
			if( ( $level & $eLevel ) != $eLevel )
			{
				continue;
			}

			if( $mode == 'callback' )
			{
				if( !is_array( $options ) )
				{
					return patErrorManager::raiseError( E_ERROR, 
														PATERRORMANAGER_ERROR_ILLEGAL_OPTIONS, 
														'Options for callback not valid' 
														);
				}
				
				if( !is_callable( $options ) )
				{
					$tmp	=	array( 'GLOBAL' );
					if( is_array( $options ) )
					{
						$tmp[0]	=	$options[0];
						$tmp[1]	=	$options[1];
					}
					else
					{
						$tmp[1]	=	$options;
					}
					
					return patErrorManager::raiseError(	E_ERROR, 
														PATERRORMANAGER_ERROR_CALLBACK_NOT_CALLABLE, 
														'Function is not callable', 
														'Function:' . $tmp[1]  . ' scope ' . $tmp[0] . '.' 
														);
				}
			}				
		
			$GLOBALS['_pat_errorHandling'][$eLevel]	=	array( 'mode' => $mode );
			if( $options	!= null )
			{
				$GLOBALS['_pat_errorHandling'][$eLevel]['options']	=	$options;
			}
		}
			
        return  true;
    }

    function getErrorHandling( $level )
    {
		return $GLOBALS['_pat_errorHandling'][$level];
    }

  function translateErrorLevel( $level )
	{
		if( isset( $GLOBALS['_pat_errorLevels'][$level] ) )
		{
			return	$GLOBALS['_pat_errorLevels'][$level];
		}
		return	'Unknown error level';
	}
	
    function setErrorClass( $name )
    {
		$GLOBALS['_pat_errorClass']	=	$name;
		return true;
    }

    function addIgnore( $codes )
    {
		if( !is_array( $codes ) )
		{
			$codes	=	array( $codes );
		}
	
		$codes							=	array_merge( $GLOBALS['_pat_errorIgnores'], $codes );
		$GLOBALS['_pat_errorIgnores']	=	array_unique( $codes );
	
		return true;
    }
	
   function removeIngore( $codes )
    {
		if( !is_array( $codes ) )
		{
			$codes	=	array( $codes );
		}
		
		foreach( $codes as $code )
		{
			$index	=	array_search( $code, $GLOBALS['_pat_errorIgnores'] );
			if( $index === false )
			{
				continue;
			}
			
			unset( $GLOBALS['_pat_errorIgnores'][$index] );
		}

		$GLOBALS['_pat_errorIgnores']	=	array_values( $GLOBALS['_pat_errorIgnores'] );
		
		return true;
    }
    function getIgnore()
    {
		return $GLOBALS['_pat_errorIgnores'];
    }
    function clearIgnore()
    {
		$GLOBALS['_pat_errorIgnores']	=	array();
		return true;
    }
	
    function pushExpect( $codes )
    {
		if( !is_array( $codes ) )
		{
			$codes	=	array( $codes );
		}
		
		array_push( $GLOBALS['_pat_errorExpects'], $codes );
		
		return true;
    }

    function popExpect()
    {
		if( empty( $GLOBALS['_pat_errorExpects'] ) )
		{
			return false;
		}
		
		array_pop( $GLOBALS['_pat_errorExpects'] );
		return true;
    }

    function getExpect()
    {
		return $GLOBALS['_pat_errorExpects'];
    }

    function clearExpect()
    {
		$GLOBALS['_pat_errorExpects']	=	array();
		return true;
    }
	
}
?>