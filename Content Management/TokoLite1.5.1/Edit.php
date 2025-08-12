<?php

$charSet = "iso-8859-1";
$dir = "ltr";

if ( isset( $_POST[ "charSet" ] ) )
{
	$charSet = $_POST[ "charSet" ];
	
	if ( $charSet == "windows-1255" )
	{
		$dir = "rtl";
	}
}

header( "Content-Type: text/plain; charset=" . $charSet ); 
include( "CheckLogin.php" );
include( "LoadConfig.php" );

function LoadEditAreas( $fullFileName,
						$searchForStrExp,
						&$fileName,
						&$fileContents,
						&$found )
{
	if ( strlen( $fileContents ) == 0 )
	{
		if ( ( $currFile = @fopen( $fullFileName, "r" ) ) != false )
		{
			$fileContents = fread( $currFile, filesize( $fullFileName ) );
			fclose( $currFile );
		}
		else
		{
			Error( "This file has no read autorization, please contact your administrator" );
		}
	}

	preg_match_all( $searchForStrExp,
					$fileContents,
					$found,
					PREG_OFFSET_CAPTURE );
}

function SaveEditAreas( $fullFileName,
						$searchForStrExp,
						$searchStrEndExp,
						&$fileContents )
{
	if ( !isset( $_POST[ "editArea0" ] ) )
	{
		return;
	}

	$fileContents = "";

	if ( ( $currFile = @fopen( $fullFileName, "r" ) ) != false )
	{
		$fContents = fread( $currFile, filesize( $fullFileName ) );
		fclose( $currFile );
		preg_match_all( $searchForStrExp,
						$fContents,
						$found,
						PREG_OFFSET_CAPTURE );

		$counter   = 0;
		$lastMatch = count( $found[ 0 ] );
		$lastPos   = 0;

		for ( $counter = 0; $counter < $lastMatch; $counter++ )
		{
			$startPos = strlen( $found[ 0 ][ $counter ][ 0 ] ) +
						$found[ 0 ][ $counter ][ 1 ];

			$fileContents .= substr( $fContents,
									 $lastPos,
									 $startPos - $lastPos );

 			if ( preg_match( $searchStrEndExp,
							 $fContents,
							 $found2,
							 PREG_OFFSET_CAPTURE,
							 $startPos ) == 1 )
			{
				if ( get_magic_quotes_gpc() == 1 )
				{
					$fileContents .= stripslashes( $_POST[ "editArea" . $counter ] );
				}
				else
				{
					$fileContents .= $_POST[ "editArea" . $counter ];
				}

				$lastPos = $found2[ 0 ][ 1 ];
			}
			else
			{
				break;
			}
		}

		if ( $counter == $lastMatch && strlen( $fileContents ) > 0 )
		{
			if ( ( $currFile = @fopen( $fullFileName, "w" ) ) != false )
			{
				$fileContents .= substr( $fContents, $lastPos );
				fwrite( $currFile, $fileContents );
				fflush( $currFile );
				fclose( $currFile );
				clearstatcache();
			}
			else
			{
				Error( "This file has no write autorization, please contact your administrator" );
			}
		}
	}
	else
	{
		Error( "This file has no read autorization, please contact your administrator" );
	}
}

$path = $rootPath;
GetPath( $path, $rootPath );
GetFilesAndDirectories( $path,
						$rootPath,
						$hideDirs,
						$extensions,
						$searchStrStartExp,
						$directories,
						$files );

$contents = "";
$fileName = "";
$fileLoaded   = GetFile( $files, $fileName );
$fullFileName = $path . $fileName;

if ( isset( $_POST[ "save" ] ) &&
	 $_POST[ "save" ] == "1" &&
	 $fileLoaded )
{
	SaveEditAreas( $fullFileName,
				   $searchStrStartExp,
				   $searchStrEndExp,
				   $content );
}

$editAreas = array();

if ( $fileLoaded )
{
	LoadEditAreas( $fullFileName,
				   $searchStrStartExp,
				   $fileName,
				   $contents,
				   $editAreas );
}

$lastMatch = 0;

if ( count( $editAreas ) > 0 )
{
	$lastMatch = count( $editAreas[ 0 ] );
}

?> 
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= $charSet ?>"/>
		<link rel="stylesheet" href="toko.css" type="text/css"/>
		<script langugage="JavaScript">

			var g_numEditArea = <?= $lastMatch ?>;
			var g_dirtyFlag = false;

			function Save()
			{
				if ( g_numEditArea < 1 )
				{
					return;
				}

				document.editForm.save.value = "1";
				document.editForm.submit();
			}

			function Logout()
			{
				if ( g_dirtyFlag &&
					 !confirm( "The text has been changed, are you sure you would like to logout?" ) )
				{
					return;
				}

				document.editForm.exit.value = "1";
				document.editForm.submit();
			}

			function Reload()
			{
				if ( g_numEditArea < 1 )
				{
					return;
				}

				if ( g_dirtyFlag &&
					 !confirm( "The text has been changed, are you sure you would like to reload the file?" ) )
				{
					return;
				}

				document.editForm.submit();
			}

			function SetDirtyFlag()
			{
				g_dirtyFlag = true;
			}

			function SetFocus()
			{
				if ( g_numEditArea > 0 )
				{
					editForm.editArea0.focus();
				}
			}

		</script>
	</head>
	<body onload="SetFocus();">
		<form id="editForm" name="editForm" action="Edit.php" method="post">
			<input type="hidden" name="currPath" value="<?= $path ?>"/>
			<input type="hidden" name="save" value=""/>
			<input type="hidden" name="exit" value=""/>
			<input type="hidden" name="path" value="<?= $_POST[ "path" ] ?>"/>
			<input type="hidden" name="fileName" value="<?= $_POST[ "fileName" ] ?>"/>
			<input type="hidden" name="charSet" value="<?= $_POST[ "charSet" ] ?>"/>
			<table class="Content" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="padding-left: 5px;">
						<b>Path:</b> <?= str_replace( $rootPath, "/", $fullFileName ) ?>
					</td>
				</tr>
				<?php

					for ( $counter = 0; $counter < $lastMatch; $counter++ )
					{
						$startPos = strlen( $editAreas[ 0 ][ $counter ][ 0 ] ) +
									$editAreas[ 0 ][ $counter ][ 1 ];

 						if ( preg_match( $searchStrEndExp,
										 $contents,
										 $matches,
										 PREG_OFFSET_CAPTURE,
										 $startPos ) == 1 )
						{
							$encodedText = str_replace( "<",
														"&lt;",
														substr( $contents,
																$startPos,
																$matches[ 0 ][ 1 ] - $startPos ) );
				?>
				<tr>
					<td>
						<table width="100%">
							<tr>
								<td class="title">
									Edit area #<?= $counter + 1 ?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#dcd9c8">
									<textarea dir="<?= $dir ?>" name="editArea<?= $counter ?>" style="width: 100%;" rows="10" onchange="SetDirtyFlag();"><?= $encodedText ?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php

						}
					}

				?>
			</table>
		</form>
	</body>
</html>
