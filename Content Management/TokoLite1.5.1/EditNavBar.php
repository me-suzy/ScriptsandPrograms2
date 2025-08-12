<?php

include( "CheckLogin.php" );
include( "LoadConfig.php" );

$path = $rootPath;
GetPath( $path, $rootPath );
GetFilesAndDirectories( $path,
						$rootPath,
						$hideDirs,
						$extensions,
						$searchStrStartExp,
						$directories,
						$files );

GetFile( $files, $fileName );

function IsSelected( $currVal, $selectedVal )
{
	if ( $currVal == $selectedVal )
	{
		return " selected";
	}
	else
	{
		return "";
	}
}

$charSet = "";

if ( isset( $_POST[ "charSet" ] ) )
{
	$charSet = $_POST[ "charSet" ];
}

$charSets = array
			(
				array( "iso-8859-1","English(en)"),
				array( "iso-8859-1","Afrikaans(af)"),
				array( "iso-8859-1","Albanian(sq)"),
				array( "windows-1256","Arabic(war)"),
				array( "iso-8859-6","Arabic(ar)"),
				array( "iso-8859-1","Basque(eu)"),
				array( "iso-8859-5","Bulgarian(bg)"),
				array( "iso-8859-5","Byelorussian(be)"),
				array( "iso-8859-1","Catalan(ca)"),
				array( "gb2312","Simplified Chinese(ch1)"),
				array( "big5-hkscs","Traditional Chinese Hong-Kong(ch2)"),
				array( "big5","Traditional Chinese(ch3)"),
				array( "windows-1250","Croatian(whr)"),
				array( "iso-8859-2","Croatian(hr)"),
				array( "iso-8859-2","Czech(cs)"),
				array( "iso-8859-1","Danish(da)"),
				array( "iso-8859-1","Dutch(nl)"),
				array( "iso-8859-1","English(en)"),
				array( "iso-8859-3","Esperanto(eo)"),
				array( "iso-8859-15","Estonian(et)"),
				array( "iso-8859-1","Faroese(fo)"),
				array( "iso-8859-1","Finnish(fi)"),
				array( "iso-8859-1","French(fr)"),
				array( "iso-8859-1","Galician(gl)"),
				array( "iso-8859-1","German(de)"),
				array( "iso-8859-7","Greek(el)"),
				array( "iso-8859-8","Hebrew(iw)"),
				array( "windows-1255","Hebrew(wiw)"),
				array( "iso-8859-2","Hungarian(hu)"),
				array( "iso-8859-1","Icelandic(is)"),
				array( "iso-8859-10","Inuit(Eskimo)"),
				array( "iso-8859-1","Irish(ga)"),
				array( "iso-8859-1","Italian(it)"),
				array( "shift_jis","Japanese(sja)"),
				array( "iso-2022-jp","Japanese(ija)"),
				array( "euc-jp","Japanese(eja)"),
				array( "euc-kr","Korean(ko)"),
				array( "iso-8859-10","Lapp(lpl)"),
				array( "windows-1257","Latvian(wlv)"),
				array( "iso-8859-13","Latvian(lv)"),
				array( "iso-8859-13","Lithuanian(lt)"),
				array( "windows-1257","Lithuanian(wlt)"),
				array( "windows-1251","Macedonian(wmk)"),
				array( "iso-8859-5","Macedonian(mk)"),
				array( "iso-8859-3","Maltese(mt)"),
				array( "iso-8859-1","Norwegian(no)"),
				array( "iso-8859-2","Polish(pl)"),
				array( "iso-8859-1","Portuguese(pt)"),
				array( "iso-8859-2","Romanian(ro)"),
				array( "iso-8859-5","Russian(iru)"),
				array( "koi8-r","Russian(ru)"),
				array( "iso-8859-1","Scottish(gd)"),
				array( "windows-1251","Serbian(sr)"),
				array( "iso-8859-5","Serbian(sr)"),
				array( "cyrillic","Serbian(sr)"),
				array( "latin","Serbian(latsr)"),
				array( "iso-8859-2","Serbian(latisr)"),
				array( "windows-1250","Serbian(latwsr)"),
				array( "iso-8859-2","Slovak(sk)"),
				array( "windows-1250","Slovenian(wsl)"),
				array( "iso-8859-2","Slovenian(sl)"),
				array( "iso-8859-1","Spanish(es)"),
				array( "iso-8859-1","Swedish(sv)"),
				array( "windows-1254","Turkish(wtr)"),
				array( "iso-8859-9","Turkish(tr)"),
				array( "iso-8859-5","Ukrainian(uk)"),
				array( "utf-8","utf(utf)")
			);

?>
<html>
	<head>
		<link rel="stylesheet" href="toko.css" type="text/css"/>
		<script langugage="JavaScript">

			function Submit( target, action )
			{
				navForm.target = target;
				navForm.action = action;
				navForm.submit();
			}

			function ChangeDir()
			{
				if ( navForm.path.value.length > 0 )
				{
					navForm.fileName.selectedIndex = 0;
					Submit( "edit", "Edit.php" );
					Submit( "", "EditNavBar.php" );
				}
			}

			function ChangeFile()
			{
				if ( navForm.fileName.value.length > 0 )
				{
					Submit( "edit", "Edit.php" );
				}
			}

			function ChangeCharSet()
			{
				var editFrame = top.frames[ "edit" ];

				if ( editFrame.g_numEditArea == 0 )
				{
					return;
				}

				editFrame.editForm.charSet.value = navForm.charSet.value;
				editFrame.Reload();
			}

		</script>
	</head>
	<body style="background-color: #ccc9b8">
		<form id="navForm" name="navForm" action="EditNavBar.php" method="post">
			<input type="hidden" name="currPath" value="<?= $path ?>"/>
			<input type="hidden" name="save" value=""/>
			<input type="hidden" name="exit" value=""/>
			<table cellspacing="0" cellpadding="0" width="198" height="100%">
				<tr>
					<td valign="top">
						<table class="Content" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="miniTitle" height="25">
									Character Set
								</td>
							</tr>
							<tr>
								<td height="20">
									<select name="charSet" style="width: 100%;" onchange="ChangeCharSet();">
									<?php

										for ( $counter = 0; $counter < count( $charSets ); $counter++ )
										{
											$currCharSet = $charSets[ $counter ][ 0 ];
									?>
										<option value="<?= $currCharSet ?>"<?= IsSelected( $charSet, $currCharSet ) ?>><?= $charSets[ $counter ][ 1 ] ?></option>
									<?php
										}

									?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="miniTitle" height="25">
									Directory
								</td>
							</tr>
							<tr>
								<td align="left" height="20">
									<select name="path" onchange="ChangeDir();" style="width: 100%;">
										<option value="">Choose</option>
										<?php

											if ( $path != $rootPath )
											{

										?>
										<option value="..">.. (Parent directory)</option>
										<?php

											}

											foreach ( $directories as $directory )
											{

										?>
										<option value="<?= $directory ?>"><?= $directory ?></option>
										<?php

											}

										?>
									</select>
								</td>
							</tr>
							</tr>
								<td class="miniTitle" height="25">
									File
								</td>
							</tr>
							<tr>
								<td align="left" height="20">
									<select name="fileName" onchange="ChangeFile();" style="width: 100%;">
										<option value="">Choose</option>
										<?php

											foreach ( $files as $file )
											{
												if ( $fileName == $file )
												{
													$selected = " selected";
												}
												else
												{
													$selected = "";
												}

												$encodedFile = htmlspecialchars( $file );

										?>
										<option value="<?= $encodedFile ?>"<?= $selected ?>><?= $encodedFile ?></option>
										<?php

											}

										?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="miniTitle" height="25">
									Upload File
								</td>
							</tr>
							<tr>
								<td>
									<input type="file" style="width: 100%;"/>
								</td>
							</tr>
							<tr>
								<td valign="center">
									<input type="button" value="Upload" onclick="alert( 'Upload is not supported in the free version' );"/>&nbsp;<input type="checkbox" name="overwrite" value="1" style="border: 1px;" checked/><font color="#000000">Overwrite if file exists</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="miniTitle" height="40" align="center"><iframe height="40" width="100%" src="Str.php" frameborder="0"></iframe></td>
				</tr>
			</table>
		</form>
	</body>
</html>
