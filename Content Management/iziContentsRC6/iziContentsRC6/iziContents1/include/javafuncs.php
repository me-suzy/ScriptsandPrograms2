<?php

/***************************************************************************

 javafuncs.php
 --------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

?>
<script language="javascript" type="text/javascript">
	<!-- Begin
	var winArray = new Array();

	function ShowImage(sImageName)
	{
		newWin = window.open(sImageName, "Image", "width=500,height=400,status=no,resizable=yes,scrollbars=yes,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	var gsControlName = "";

	function ImagePicker(sControl)
	{
		gsControlName = sControl;
		newWin = window.open("<?php echo BuildLink('imagepicker.php'); ?>&control=" + sControl, "ImagePicker", "width=490,height=430,status=no,resizable=yes,scrollbars=yes,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	function ColorPicker(sControl) {
		gsControlName = sControl;
		newWin = window.open("<?php echo BuildLink('colorpicker.php'); ?>&control=" + sControl, "ColourPicker", "width=485,height=260,status=no,resizable=yes,scrollbars=no,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	function ModulePicker(sControl)
	{
		gsControlName = sControl;
		newWin = window.open("<?php echo BuildLink('modulepicker.php'); ?>&extin=E&control=" + sControl, "ModulePicker", "width=480,height=350,status=no,resizable=yes,scrollbars=no,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	function DatePicker(sControl,sMM,sYYYY) {
		gsControlName = sControl;
		newWin = window.open("<?php echo BuildLink('datepicker.php'); ?>&control=" + sControl + "&month=" + sMM+ "&year=" + sYYYY, "DatePicker", "width=240,height=225,status=no,resizable=yes,scrollbars=no,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	function TagPicker(sControl,sWYSIWYG)
	{
		gsControlName = sControl;
		newWin = window.open("<?php echo BuildLink('tagpicker.php'); ?>&control=" + sControl + "&WYSIWYG=" + sWYSIWYG, "TagPicker", "width=520,height=400,status=no,resizable=yes,scrollbars=no,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	function TagPicker2(sControl,sWYSIWYG)
	{
		gsControlName = sControl;
		<?php
		if (isset($GLOBALS["PermittedTags"])) {
			?>
			newWin = window.open("<?php echo BuildLink('tagpicker2.php'); ?>&control=" + sControl + "&WYSIWYG=" + sWYSIWYG + "&secure=<?php echo $GLOBALS["RestrictTags"]; ?>&restricted=<?php echo urlencode(implode($GLOBALS["tqSeparator"],$GLOBALS["PermittedTags"])); ?>", "TagPicker", "width=520,height=400,status=no,resizable=yes,scrollbars=no,dependent=yes");
			<?php
		} else {
			?>
			newWin = window.open("<?php echo BuildLink('tagpicker2.php'); ?>&control=" + sControl + "&WYSIWYG=" + sWYSIWYG + "&secure=<?php echo $GLOBALS["RestrictTags"]; ?>&restricted=", "TagPicker", "width=520,height=400,status=no,resizable=yes,scrollbars=no,dependent=yes");
			<?php
		}
		?>
		winArray[winArray.length] = newWin;
	}

	function FieldHelp(sHelpRef,ffocus)
	{
		putFocus('MaintForm',ffocus);
		newWin = window.open(sHelpRef, "Help", "width=400,height=250,status=no,resizable=yes,scrollbars=yes,dependent=yes");
		winArray[winArray.length] = newWin;
	}

	function emailCheck (emailStr) {
		/* The following pattern is used to check if the entered e-mail address
			fits the user@domain format.  It also is used to separate the username
			from the domain. */
		var emailPat=/^(.+)@(.+)$/
		/* The following string represents the pattern for matching all special
			characters.  We don't want to allow special characters in the address. 
			These characters include ( ) < > @ , ; : \ " . [ ]		*/
		var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
		/* The following string represents the range of characters allowed in a 
			username or domainname.  It really states which chars aren't allowed. */
		var validChars="\[^\\s" + specialChars + "\]"
		/* The following pattern applies if the "user" is a quoted string (in
			which case, there are no rules about which characters are allowed
			and which aren't; anything goes).  E.g. "jiminy cricket"@disney.com
			is a legal e-mail address. */
		var quotedUser="(\"[^\"]*\")"
		/* The following pattern applies for domains that are IP addresses,
			rather than symbolic names.  E.g. joe@[123.124.233.4] is a legal
			e-mail address. NOTE: The square brackets are required. */
		var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
		/* The following string represents an atom (basically a series of
			non-special characters.) */
		var atom=validChars + '+'
		/* The following string represents one word in the typical username.
			For example, in john.doe@somewhere.com, john and doe are words.
			Basically, a word is either an atom or quoted string. */
		var word="(" + atom + "|" + quotedUser + ")"
		// The following pattern describes the structure of the user
		var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
		/* The following pattern describes the structure of a normal symbolic
			domain, as opposed to ipDomainPat, shown above. */
		var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")

		/* Finally, let's start trying to figure out if the supplied address is
			valid. */

		/* Begin with the coarse pattern to simply break up user@domain into
			different pieces that are easy to analyze. */
		var matchArray=emailStr.match(emailPat)
		if (matchArray==null) {
				/* Too many/few @'s or something; basically, this address doesn't
					even fit the general mould of a valid e-mail address. */
			alert("Email address seems incorrect (check @ and .'s)")
			return false
		}
		var user=matchArray[1]
		var domain=matchArray[2]

		// See if "user" is valid 
		if (user.match(userPat)==null) {
				// user is not valid
				alert("The username doesn't seem to be valid.")
				return false
		}

		/* if the e-mail address is at an IP address (as opposed to a symbolic
			host name) make sure the IP address is valid. */
		var IPArray=domain.match(ipDomainPat)
		if (IPArray!=null) {
				// this is an IP address
					for (var i=1;i<=4;i++) {
					if (IPArray[i]>255) {
							alert("Destination IP address is invalid!")
				return false
					}
				}
				return true
		}

		// Domain is symbolic name
		var domainArray=domain.match(domainPat)
		if (domainArray==null) {
			alert("The domain name doesn't seem to be valid.")
				return false
		}

		/* domain name seems valid, but now make sure that it ends in a
			three-letter word (like com, edu, gov) or a two-letter word,
			representing country (uk, nl), and that there's a hostname preceding 
			the domain or country. */

		/* Now we need to break up the domain to get a count of how many atoms
			it consists of. */
		var atomPat=new RegExp(atom,"g")
		var domArr=domain.match(atomPat)
		var len=domArr.length
		if (domArr[domArr.length-1].length<2 || 
				domArr[domArr.length-1].length>4) {
			// the address must end in a two letter or three letter word.
			alert("The address must end in a three-letter domain, or two letter country.")
			return false
		}

		// Make sure there's a host name preceding the domain.
		if (len<2) {
			var errStr="This address is missing a hostname!"
			alert(errStr)
			return false
		}

		// If we've gotten this far, everything's valid!
		return true;
	}

	function closeChildWindows() {
		for(i=0;i<winArray.length;i++) {
			if (!winArray[i].closed) {
				winArray[i].close();
			}
		}
		winArray.length = 0;
	}
	//  End -->
</script>
