/*	$Id: clseBayAppChangeEmail.cpp,v 1.5.166.1.2.1 1999/08/01 03:01:08 barry Exp $	*/
//
//	File:	clseBayAppChangeEmail.cpp
//
//	Class:	clseBayApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Handle a change email request
//
// Modifications:
//				- 02/06/97 tini	- Created
//				- 07/02/99 nsacco - modified text to not use %s and GetName() from MarketPlace
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

static const char *ChangeEmailTextPart_1 =
"eBay keeps track of you based on your e-mail address. You receive "
"confirmations and reports via e-mail. Thus, we will need to verify "
"that your new e-mail address works before changing your e-mail address "
"in the eBay system."
"<p>"
"To report a change of your e-mail address:"
"<ol>"
"  <li><b>Fill out</b> the form below</li>"
"  <li>Wait for a <b>confirmation e-mail</b> message to arrive in your mailbox.</li>"
"  <li>Enter the <b>special confirmation code</b> (provided in the e-mail message) "
"   into the <a href=\"%seBayISAPI.dll?ChangeEmailConfirm\">Confirmation form</a>. </li>"
"</ol>"
"<p> Please do not re-register under your new e-mail address, or else you will "
"  end up with two separate eBay registrations.</p>"
"<p> <strong>Your new e-mail address is not valid at eBay until you confirm your "
"  change of e-mail with the special confirmation code. The special confirmation "
"  code is NOT your new password. It is only used for verification purposes and "
"  is discarded after you confirm your new e-mail address with eBay. Your password "
"  and account details will be unchanged. </strong>"
"<p>"
" "
"<strong>Note:</strong> If you don't receive the confirmation "
"e-mail, it is likely that you entered an invalid e-mail "
"address. In that case, you'll need to try to change your e-mail "
"address again. ";


void clseBayApp::ChangeEmail(CEBayISAPIExtension *pServer)
{
	char						*pBlock;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<    mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change of E-mail Address"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	// And a heading for it all
	*mpStream <<	"<h2>"
			  <<	"Change of E-mail Address"
			  <<	"</h2>";


	// We're going to need storage for the block o'text at the top

	pBlock	 = new char[strlen(ChangeEmailTextPart_1) + 1];

	sprintf(pBlock, ChangeEmailTextPart_1);

	*mpStream <<	pBlock;

	delete [] pBlock;
	
	// Now, the rest of the goop
	*mpStream <<	"<h3>Please complete the following:</h3>"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageChangeEmailShow)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"ChangeEmailShow\">"
					"\n"
					"<p>";

	*mpStream <<	"<pre>Your "
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	":            "
					"<input type=text name=userid "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << " "
					">"
					"\n"
					"\n"
					"Your "
			  <<	mpMarketPlace->GetPasswordPrompt()
			  <<	":           "
					"<input type=password name=pass "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_PASSWORD_SIZE << " "
					">"
					"\n"
					"\n"
					"Your new e-mail address: "
					"<input type=text name=newmail "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_USERID_SIZE << " "
					">"
					"\n"
					"\n";
					
	// And now, for the closing
	*mpStream <<	"</pre><br>\n"
					"<strong>Press this button to submit your change of address request:</strong>"
					"<p>"
					"<blockquote><input type=submit value=\"submit\"></blockquote>"
					"<p>"
					"\n"
					"Press this button to clear the form if you made a mistake:"
					"<p>"
					"<blockquote><input type=reset value=\"clear form\"></blockquote>"
					"\n"
					"</form>";


	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();
	return;
}


