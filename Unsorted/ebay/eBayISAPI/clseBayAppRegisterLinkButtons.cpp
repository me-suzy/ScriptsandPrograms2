/*	$Id: clseBayAppRegisterLinkButtons.cpp,v 1.6.236.1.102.2 1999/08/05 18:59:02 nsacco Exp $	*/
//
//	File:		clsRegisterLinkButtons.cc
//
//	Class:		clsRegisterLinkButtons
//
//	Author:		pete helme (pete@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 04/30/98	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#undef USE_MAIL


void clseBayApp::RegisterLinkButtons(CEBayISAPIExtension *pThis,
								   char *pUserid,
								   char *pPassword,
								   int pHomepage,
								   int pMypage,
								   char *pUrls)
{
#ifdef USE_MAIL
	clsMail	*pMail;
	ostream	*pMStream;
	char	subject[256];
	int		mailRc;
#endif

	
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Links Registration"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;


	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserid, pPassword, mpStream);

	// If we didn't get the user, we're done
	if (mpUser)
	{
		
		// check to see if they've checked the picture they want to use
		if((pHomepage == 0) && (pMypage == 0)) {
			*mpStream	<<	"<h2>Link selection is missing</h2>"
						<<	"You forgot to select a picture. Please hit the back button on your browser and select either the home page and/or My Auctions graphic."
						<<	mpMarketPlace->GetFooter();
						
			CleanUp();
			return;
		}
		
		// URL too long?
		if ( strlen(pUrls) > 250)
		{
			*mpStream <<	"<h2>"
				"Your URL is too long"
				"</h2>"
				"URL entries are limited to 250 characters."
				" Please hit the back button on your browser and shorten your URL. Thanks!<p>\n"
				<<	mpMarketPlace->GetFooter();
			
			CleanUp();

			return;
		}

		// check to see if they've entered any URL on the form
//		if(strcmp(pUrls, "") == 0) {
		if(strlen(pUrls) <= 0) {
			*mpStream	<<	"<h2>URL is missing</h2>"
						<<	"Please tell us where you will be using the graphic(s). Hit the back button on your browser and enter your URL into the appropriate box on the page. Thanks.<p>"
						<<	mpMarketPlace->GetFooter();
			
			CleanUp();
			return;
		}

		// check to see if they've checked the home page picture
		if((pHomepage == 1)) {

			// call the DB code to add this user
			gApp->GetDatabase()->AddLinkButton(mpUser, RecipLinkHomePic, pUrls);
			
		}

		// check to see if they've checked the My Auctions page picture
		if((pMypage == 1)) {

			// call the DB code to add this user
			gApp->GetDatabase()->AddLinkButton(mpUser, RecipLinkMyAuctionsPic, pUrls);

		}

		
#ifdef USE_MAIL
		// create te beginnings of a mail object
		pMail		= new clsMail;
		pMStream	= pMail->OpenStream();


		// Compose the mail
		strlwr(pUserid);
		*pMStream <<	"Dear "
				  <<	pUserid
				  <<	",\n"
				  <<	"\n";

		*pMStream << "Here are the links you asked for as part of the Links registration\n";
#endif

		
		
		
		// set up the success page

		// Instructions for getting the text onto their site
		*mpStream	<< "<br><h2>Instructions for installing buttons on your site</h2>"
					<< "Simply follow the instructions below to copy and paste HTML text into "
					<< "the web site editor or text editor which you use to maintain your HTML "
					<< "files. That's all there is to it!"
					<< "<br><br>";

#ifdef USE_MAIL
		// Instructions for getting the text onto their site
		*pMStream	<< "\nInstructions for copying and installing the text to your site:"
					<< "\nSimply copy the bold HTML text below to your computer's clipboard. "
					<< "Then paste that text into your web site editor or text editor with which you use to maintain your HTML files. That's it!"
					<< "\n";
#endif

		// check if they asked for the homepage...
		if((pHomepage == 1)) {
			*mpStream	<<	"<hr><br>";
			*mpStream	<<	"<p><b>Homepage link - points to eBay's home page</b><br></p>";

			*mpStream	<<	"1. Copy and paste this HTML into your website: <br><br>"  
						<<	"<b>"
//						<<	"&lt;a href=&quot;http://pages.ebay.com/linkButtons&quot;&gt;<br>&lt;img src=&quot;http://pics.ebay.com/aw/pics/ebay_gen_button.gif&quot; alt=&quot;eBay Home&quot;&gt;<br>&lt;/a&gt;"
// kakiyama 07/16/99
						<<  "&lt;a href=&quot;"
						<<  mpMarketPlace->GetHTMLPath()
						<<  "linkButtons&quot;&gt;<br>&lt;img src=&quot;"
						<<  mpMarketPlace->GetPicsPath()
						<<  "ebay_gen_button.gif&quot; alt=&quot;eBay Home&quot;&gt;<br>&lt;/a&gt;"
						<<	"</b>"
						<<	"<br>";

			*mpStream	<< "<br><table border=\"0\" style=\"margin: auto\">"
						<< "  <tr>"
						<< "  <td width=\"50%\">2. This graphic will appear on your page, with a link to eBay's home page: </td>"
//						<< "    <td width=\"50%\"><a href=\"http://pages.ebay.com/linkButtons\">"
// kakiyama 07/16/99
						<< "    <td width=\"50%\"><a href=\""
						<< mpMarketPlace->GetHTMLPath()
						<< "linkButtons\">"
						<< "    <img src=\""
						<<	mpMarketPlace->GetImagePath()
						<<	"ebay_gen_button.gif\""						//  width=\"88\" height=\"31\"
						<< "    alt=\"eBay Home\">\n</a></td>"
						<< "  </tr>"
						<<	"  <tr><td><br></td></tr>"
						<<	"  <tr><td align=\"left\" colspan=\"2\">eBay may change the button images in the future, so it is best that you use this HTML image retrieval code instead of copying the actual button image onto your web site.  This way your site can automatically update when eBay does!</td></tr>"
						<< "</table>";
			*mpStream	<<  "<br>";
		
#ifdef USE_MAIL
			// now fill in the mail stream with similar info
			*pMStream   <<	"\n\nHomepage link - points to eBay's home page\n\n"
//						<<	"<a href=\"http://pages.ebay.com/linkButtons\"><img src=\"http://pics.ebay.com/aw/pics/ebay_gen_button.gif\" alt=\"eBay Home\"></a>";
// kakiyama 07/16/99
						<< "<a href=\""
						<< mpMarketPlace->GetHTMLPath()
						<< "linkButtons\"><img src=\""
						<< mpMarketPlace->GetPicsPath()
						<< "ebay_gen_button.gif\" alt=\"eBay Home\"></a>";
#endif
		}

		// check if they asked for the My Auctions page...
		if((pMypage == 1)) {
			*mpStream	<<	"<hr><br>";
			*mpStream	<<	"<p><b>My Items link - points directly to YOUR seller search list</b><br></p>";

				// display the link they'll need to copy
			*mpStream	<<	"1. Copy and paste this HTML into your website: <br><br>"  
						<<	"<b>"
						<<	"&lt;a href=&quot;"
						<<	mpMarketPlace->GetCGIPath(PageViewListedItemsLinkButtons)
						<<	"ebayISAPI.dll?ViewListedItemsLinkButtons&userid="
						<<    mpUser->GetUserId()
//						<<    "&quot;&gt;<br>&lt;img src=&quot;http://pics.ebay.com/aw/pics/ebay_my_button.gif&quot; alt=&quot;My items on eBay&quot;&gt;<br>&lt;/a&gt;"
// kakiyama 07/16/99
						<<  "&quot;&gt;<br>&lt;img src=&quot;"
						<<  mpMarketPlace->GetPicsPath()
						<<  "ebay_my_button.gif&quot; alt=&quot;My items on eBay&quot;&gt;<br>&lt;/a&gt;"
						<<	"</b>"
						<<	"<br>";

			*mpStream	<<	"<br><table border=\"0\" style=\"margin: auto\">"
						<<	"  <tr>"
						<<	"  <td width=\"50%\">2. This graphic will appear on your page, with a link to eBay's list of your current auctions: </td>"
						<<	"    <td width=\"50%\">"
						<<	"<a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewListedItemsLinkButtons)
						<<	"ebayISAPI.dll?ViewListedItemsLinkButtons&userid="
						<<	mpUser->GetUserId()
						<<	"\">"
//						<<	"    <img src=\"http://pics.ebay.com/aw/pics/ebay_my_button.gif\""					//  width=\"88\" height=\"31\"
// kakiyama 07/16/99
						<<  "    <img src=\""
						<<  mpMarketPlace->GetPicsPath()
						<<  "ebay_my_button.gif\""
						<<	"    alt=\"My items on eBay\"></a></td>"
						<<	"  </tr>"
						<<	"  <tr><td><br></td></tr>"
						<<	"  <tr><td align=\"left\" colspan=\"2\">eBay may change the button images in the future, so it is best that you use this HTML image retrieval code instead of copying the actual button image onto your web site.  This way your site can automatically update when eBay does!</td></tr>"
						<<	"</table>"
						<<  "<br>";
				
				

#ifdef USE_MAIL
			// now fill in the mail stream with similar info
			*pMStream   <<	"\n\nMy Auctions link - points directly to YOUR seller search list:\n\n"
						<<	"<a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewListedItemsLinkButtons)
						<<	"ebayISAPI.dll?ViewListedItemsLinkButtons&userid="
						<<	mpUser->GetUserId()
//						<<	"\"><img src=\"http://pics.ebay.com/aw/pics/ebay_my_button.gif\" alt=\"My items on eBay\"></a>"
// kakiyama 07/16/99
						<<  "\"><img src=\""
						<<  mpMarketPlace->GetPicsPath()
						<<  "ebay_my_button.gif\" alt=\"My items on eBay\"></a>"
						<<	"\n\n";
#endif
		}


/*
		*mpStream	<< "<hr><br><b>Helpful Hints</b><br><br>"
					<< "Of course we can't give all instructions for all situations here. "
					<< "If you have no clue how to set up a website or need additional info, any of the sites found "
					<< "at this <a href=\"http://www.yahoo.com/Computers_and_Internet/Internet/World_Wide_Web/Information_and_Documentation/Beginner_s_Guides/Beginner_s_HTML/\">link</a>"
					<< " on Yahoo will give you some help.";
*/

		*mpStream	<< "<hr><br><b>Suggestions?</b>  Send an e-mail with the word \"Links\" in the subject line to <a href=\"mailto:suggest@ebay.com\">suggest@ebay.com</a>.";

#ifdef USE_MAIL
		// now send them some mail, confirming their actions

		// send the mail

		sprintf(subject, "%s Links request",
				mpMarketPlace->GetCurrentPartnerName());

		mailRc =	pMail->Send(mpUser->GetEmail(), 
								(char *)mpMarketPlace->GetConfirmEmail(),
								subject);

		// We don't need this guy anymore
		delete	pMail;

		if (!mailRc)
		{
			*mpStream <<	"<h2>Unable to send Link Buttons info</h2>"
					  <<	"Sorry, we could not send you the link info "
					  <<	"notice via electronic mail. This is probably because your E-Mail "
					  <<	"address was invalid. Please go back and try again. Or take the information directly off this page."
					  <<	"<br><br>"
					  <<	mpMarketPlace->GetFooter();
			
			CleanUp();
			return;
		}
#endif

	} else {
		// safety
		*mpStream	<<	"No such user."
					<<	mpMarketPlace->GetFooter();
		
		CleanUp();
		return;
	}

	// And the footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}
