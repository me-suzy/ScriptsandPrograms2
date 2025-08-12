<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

CheckAuthority();

if ($OnlineVersionCheck != 1)
	die("Sorry, Online Version Checking is disabled");

// Display the heading
DisplayGroupHeading('Online Version Check');

?>
<TABLE align="center" border="0" width="500">
	<TR>
		<TD>
			<BR>
		</TD>
	</TR>
	<TR>
		<TD>
			Your Script is Version: <?= $ScriptVersion?>
		</TD>
	</TR>

	<TR>
		<TD>
			<?php

			// Find the latest version
			$LatestVersion = GetLatestVersionNumber();

			// If an array was returned then we have the info we need. If a number was returned then we encountered a problem
			if (is_array($LatestVersion))
			{
				$LatestLiveVersion = trim($LatestVersion[0]);
				$LatestBetaVersion = trim($LatestVersion[1]);

				// Is there a later LIVE version available?
				if ($LatestLiveVersion > $ScriptVersion)
				{
					?><BR /><B>Version <?=$LatestLiveVersion?> is available. It is recommended that you upgrade.</B><?php
				}

				// Is there a later BETA version available?
				if ($LatestBetaVersion > $ScriptVersion)
				{
					?><BR /><B>Beta Version <?=$LatestBetaVersion?> is available. This is a BETA release, and as such it may or may not be stable. Install at your own risk.<?php
				}

				// Using the latest version?
				if ( ($ScriptVersion >= $LatestLiveVersion) && ($ScriptVersion >= $LatestBetaVersion) )
				{
					?><BR />You would appear to be running the latest version, no action is necessary.<?php
				}
			}
			elseif ($LatestVersion == -1)
			{
				?><B>Unable to connect to www.phpfreenews.co.uk to check for the latest version.</B><?php
			}
			elseif ($LatestVersion == -2)
			{
				?><B>Your server configuration prevents the use of Sockets to check for the latest version.</B><?php
			}
			else
			{
				?><B>Unknown return-code received!</B><?php
			}
			?>
		</TD>
	</TR>

	<TR>
		<TD>
			<BR /><BR /><CENTER>Visit the <A href="http://www.phpfreenews.co.uk/Download.php" target="_blank">PHPFreeNews</A> website.</CENTER>			
		</TD>
	</TR>	
</TABLE>