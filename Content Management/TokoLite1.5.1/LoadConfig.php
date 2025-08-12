<?php

function LoadConfiguration( &$usersPasswords,
							&$rootPath,
							&$extensions,
							&$hideDirs )
{
	$sectionNum = -1;
	$pos		= 0;
	$confFile   = @fopen( dirname(__FILE__) . "/toko.conf", "r" );
	$replace	= array( "\r", "\n" );

	if ( $confFile )
	{
		while ( !feof( $confFile ) )
		{
			$currLine = str_replace( $replace, "", fgets( $confFile ) );

			if ( strlen( $currLine ) == 0 )
			{
				continue;
			}

			switch ( $currLine )
			{
				case "[users]":

					$sectionNum = 1;
					break;

				case "[settings]":

					$sectionNum = 2;
					break;

				case "[hidedirs]":

					$sectionNum = 3;
					break;

				default:

					switch ( $sectionNum )
					{
						case 1:

							$currUser = explode( ",", $currLine );

							if ( count( $currUser ) == 2 )
							{
								$usersPasswords[ $pos ] = array();
								$usersPasswords[ $pos ][ 0 ] = $currUser[ 0 ];
								$usersPasswords[ $pos ][ 1 ] = $currUser[ 1 ];
								$pos++;
							}

							break;

						case 2:

							$currSetting = explode( "=", $currLine );

							if ( count( $currSetting ) == 2 )
							{
								switch ( $currSetting[ 0 ] )
								{
									case "rootpath":

										$rootPath = $currSetting[ 1 ];
										break;

									case "extensions":

										$extensions = explode( ",", $currSetting[ 1 ] );
										break;
								}
							}

							break;

						case 3:

							if ( $currLine[ 0 ] == "/" )
							{
								$offset = 1;
							}
							else
							{
								$offset = 0;
							}

							if ( $currLine[ strlen( $currLine ) - 1 ] == "/" )
							{
								$length = strlen( $currLine ) - 1 - $offset;
							}
							else
							{
								$length = strlen( $currLine ) - $offset;
							}

							if ( ( $length - $offset ) > 0 )
							{
								$hideDirs[] = substr( $currLine, $offset, $length );
							}

							break;
					}

					break;
			}
		}

		if ( strlen( $rootPath ) == 0 )
		{
			$rootPath = "/";
		}
		else
		if ( $rootPath[ strlen( $rootPath ) - 1 ] != "/" )
		{
			$rootPath .= "/";
		}

		fclose( $confFile );
	}
}

function GetFilesAndDirectories( $path,
								 $rootPath,
								 $hideDirs,
								 $fileExtensions,
								 $searchForStrExp,
								 &$directories,
								 &$files )
{
	$directories = array();
	$files = array();

	if ( ( $dir = @opendir( $path ) ) == false )
	{
		Error( "Cannot access " . $path );
		exit;
	}

	while ( ( $fileName = readdir( $dir ) ) != false )
	{
		$currFile = $path . $fileName;
		if ( is_dir( $currFile ) )
		{
			if ( $fileName[ 0 ] != "." )
			{
				$hidden = false;
				foreach ( $hideDirs as $currDir )
				{
					if ( $currFile == $rootPath . $currDir )
					{
						$hidden = true;
						break;
					}
				}

				if ( !$hidden )
				{
					$directories[] = $fileName;
				}
			}
		}
		else
		{
			foreach ( $fileExtensions as $extension )
			{
				$pos = strrpos( $fileName, "." );
				if ( $pos === false || $pos == ( strlen( $fileName ) -1 ) )
				{
					continue;
				}

				if ( substr( $fileName, strrpos( $fileName, "." ) + 1 ) == $extension )
				{
					if ( ( $currFile = @fopen( $currFile, "r" ) ) != false )
					{
						while ( !feof( $currFile ) )
						{
							$line = @fgets( $currFile, 4096 );

							if ( preg_match( $searchForStrExp, $line ) > 0 )
							{
								$files[] = $fileName;
								break;
							}
						}

						fclose( $currFile );
					}

					break;
				}
			}
		}
	}

	closedir( $dir );
	sort( $directories );
	sort( $files );
}

$str = "PGEgaHJlZj0iaHR0cDovL3Rva28tY29udGVudGVkaXRvci5wYWdlaWwubmV0IiB0YXJnZXQ9Il9ibGFuayI+VG9rbyCpMjAwNCxBbGwgUmlnaHRzIFJlc2VydmVkPC9hPg==";
function GetPath( &$path, $rootPath )
{
	if ( isset( $_POST[ "currPath" ] ) )
	{
		if ( $_POST[ "path" ] == ".." )
		{
			if ( $_POST[ "currPath" ] != $rootPath )
			{
				$temp = $_POST[ "currPath" ];
				$temp = substr( $temp,
								0,
								strlen( $temp ) - 1 );

				if ( strlen( $temp ) == 0 || strrpos( $temp, "/" ) == 0 )
				{
					$path = "/";
				}
				else
				{
					$path = substr( $temp,
									0,
									strrpos( substr( $temp,
													 0,
													 strlen( $temp ) - 1 ),
											 "/" ) ) . "/";
				}
			}
		}
		else
		{
			$path = $_POST[ "currPath" ] . $_POST[ "path" ];

			if ( $path[ strlen( $path ) - 1 ] != '/' )
			{
				$path .= '/';
			}
		}
	}
}

function GetStr( $str )
{
	return base64_decode( $str );
}

function GetFile( $files,
				  &$fileName )
{
	if ( !isset( $_POST[ "fileName" ] ) )
	{
		return false;
	}

	$fileName = $_POST[ "fileName" ];

	foreach ( $files as $currFileName )
	{
		if ( strcasecmp( $fileName, $currFileName ) == 0 )
		{
			return true;
		}
	}

	return false;
}

function Error( $errorMsg )
{
	print( "<html><head><link rel=\"stylesheet\" href=\"toko.css\" type=\"text/css\"/>".
		   "</head><body><font color=\"red\" size=\"2\"><b>" .
		   $errorMsg .
		   "</b></font></body></html>" );
}


$path              = "";
$rootPath		   = "";
$extensions        = array();
$usersPasswords    = array();
$directories       = array();
$files             = array();
$hideDirs          = array();
$searchStrStartExp = "/<!\\s*--\\s*<tokoeditarea>\\s*-->/i";
$searchStrEndExp   = "/<!\\s*--\\s*<\/tokoeditarea>\\s*-->/i";

LoadConfiguration( $usersPasswords,
				   $rootPath,
				   $extensions,
				   $hideDirs );

?>
