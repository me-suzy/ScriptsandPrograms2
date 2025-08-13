/*	$Id: clseBayAppUserAgreement.cpp,v 1.13.2.2.86.2 1999/08/05 18:59:05 nsacco Exp $	*/
//
//	File:		clseBayAppUserAgreement.cpp
//
//	Class:		clseBayApp
//
//	Author:		Barry Boone
//
//	Function:   Handles acceptance of the new user agreement 
//              (a lawyer's paradise).
//
//
//	Modifications:
//				- 07/01/98 - barry    created
//				- 04/07/99 - kaz	  Added PoliceBadgeLogin(), PoliceBadgeAgreementForSelling()
//				- 04/08/99 - kaz	  Added Legal Text for Police Badge
//				- 04/09/99 - kaz	  No need to call Setup() in PoliceBadgeAgreementForSelling()
//				- 04/15/99 - kaz	  Added PoliceBadgeLogin(), PoliceBadgeAgreementForSelling()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99 nsacco	- Added new params to UserAgreementForSelling
//

#include "ebihdr.h"

static const char *PBLegalTextTitle =
"<P ALIGN=\"CENTER\"><B><FONT SIZE=\"4\">Terms of Use</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">Badges Category</FONT></B><br>\n"
"<B><FONT SIZE=\"4\">(\"Terms of Use\")</FONT></B></P>\n";

static const char *PBAcceptText =
"<P>Thank you for accepting this agreement.  Your account will reflect that you have accepted these "
"Terms of Use and you will not see the Agreement again.\n</P>"
"<P>You can return to your activity by using the back button.</P>";

static const char *PBDeclineText =
"<P>We're sorry -- you will be unable to place your item in this category "
"unless you agree to the Terms of Use.</P>"
"<P>You can return to using the site by using the back button.</P>";

static const char *PBLegalText =
"<P><FONT SIZE=\"2\">"
"Many law enforcement agencies do not want their badges sold over the Internet.  "
"Federal, state or local laws may prohibit listing items in this category.  "
"By entering your UserID and Password to list an item in this category, you are making the following statements:</P>\n"
"</FONT>\n"
"<OL>\n"
"\n"
"<LI>I am a member of the eBay community and I will follow the eBay User Agreement "
"governing my use of the eBay web site.</LI>\n"
"<LI>I will abide by the listing policy for the badges category.  The listing policy includes the following:</LI>\n"
"<UL>\n"
"<LI>All listings of law enforcement department or agency issued badges, including commemorative "
"badges, must have a scanned image of a letter of authorization from the issuing agency or "
"municipality, giving the seller permission to sell the badge.  Any listings of department or agency issue "
"badges without a legible, scanned image of a letter of authorization will be removed if reported to eBay.  "
"The letter must include a contact phone number and address for the issuing agency or municipality.  "
"This policy will be enforced after April 15, 1999.</LI>\n"
"<LI>Any badges from a currently defunct jurisdiction will not require a scanned image of "
"a letter of authorization.  However, the title must clearly state that the badge is from a defunct jurisdiction.</LI>\n"
"<LI>Any reproduction or movie prop badges must have \"reproduction\" or \"movie prop\" "
"clearly listed in the title.  The sellers need to have a scanned letter of authorization from "
"the agency, whose badge is reproduced, stating that the reproduction badge does not violate "
"existing trademarks or copyrights.  The letter must include a contact phone number and address "
"for the issuing agency or municipality.  This policy will be enforced after April 15, 1999.</LI>\n"
"<LI>Resale of authorized miniature badges, hat badges, patches, and lapels will not have "
"restrctions, unless a law enforcement agency notifies eBay that it has adopted policies "
"prohibiting the resale of such items.  eBay reserves the right, as always, to place additional "
"restrictions as appropriate.</LI>\n"
"</UL>\n"
"<LI>I understand and agree to abide by the standards and laws of the community in which I live or from "
"which I am accessing the site.</LI>\n"
"<LI>If I use these services in violation of these Terms of Use, I understand I may be in violation "
"of local and/or federal laws and am solely responsible for my actions.</LI>\n"
"<LI>I am an adult, at least 18 years of age, and I have a legal right to possess "
"specific badges in my community.</LI>\n"
"<LI>By entering my User ID and Password at the bottom of these Terms of Use, and by listing, "
"in the badges category, I agree to abide by these terms.</LI>\n"
"</OL>\n";

//
// This used to be reg-confirm.html. The new reg-confirm.html is now
// the new user agreement, and this password page is emitted from code.
//
void produce_reg_confirm(ostream *mpStream, clsMarketPlace *mpMarketPlace, bool notify, int countryId) 
{

	*mpStream <<	"<h2>Step 3 - Confirm Your Registration: Part 2 of 2</h2>\n";

	*mpStream <<
			  	"<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"590\">\n"
			  	"<tr><td>\n"
				"After you complete this form, your registration will "
				"be activated immediately, and you may begin buying and selling "
				"on eBay. \n"
			  	"<p>\n";

	*mpStream <<
			  	"Please note that you must create a <b>new</b> password now, which <b>must</b> be\n"
			  	"different from the confirmation code sent to you in the confirmation instructions. </p>"
				"</td></tr>\n"
				"</table>";

	*mpStream << "<form method=\"post\" action=\""
		      << mpMarketPlace->GetCGIPath(PageRegisterConfirm)
			  << "eBayISAPI.dll\">\n"
			  << "  <input type=\"hidden\" name=\"MfcISAPICommand\" value=\"RegisterConfirm\"><table border=\"1\"\n"
			  	"  width=\"590\" cellspacing=\"0\" cellpadding=\"3\">\n"
			  	"    <tr>\n"
			    "      <td width=\"300\" bgcolor=\"#EFEFEF\">Your E-mail Address:</td>\n"
			  	"      <td width=\"290\"><input type=\"text\" name=\"email\" size=\"30\"></td>\n"
			  	"    </tr>\n"
			  	"    <tr>\n"
			  	"      <td width=\"300\" bgcolor=\"#EFEFEF\">The confirmation code sent to you in the confirmation instructions."
				"<p><font size=\"2\">Click <a href=\"" 
			  << mpMarketPlace->GetHTMLPath()
			  << "services/registration/reqtemppass.html\">"
				"here</a> "
				"if you need eBay to resend your confirmation instructions.</font>" 
				"</td>\n"
			  	"      <td width=\"290\"><input type=\"text\" name=\"pass\" size=\"30\"></td>\n"
			  	"    </tr>\n"
			  	"    <tr>\n"
			  	"      <td width=\"300\" bgcolor=\"#EFEFEF\">Create a <strong>new</strong>, permanent password:</td>\n"
			  	"      <td width=\"290\"><input type=\"password\" name=\"newpass\" size=\"30\"></td>\n"
			  	"    </tr>\n"
			  	"    <tr>\n"
			  	"      <td width=\"300\" bgcolor=\"#EFEFEF\">Type your <strong>new</strong> password again:</td>\n"
			  	"      <td width=\"290\"><input type=\"password\" name=\"newpass2\" size=\"30\"></td>\n"
			  	"    </tr>\n"
			  	"  </table>\n";

	*mpStream << 
		        "<p><strong><font size=4 color=\"#800000\">Optional</strong> "
				"<br><table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"3\">"
				"<tr>"
				"<td width=300 bgcolor=\"#EFEFEF\"><font size=3> \n"
				"	Choose a <a href=\""
			<<	mpMarketPlace->GetHTMLPath()
			<<	"help/myinfo/userid.html\"><strong>User ID</strong></a> (nickname):<br>"
				"	<font size=2>The User ID that you choose will become "
				"	your \"eBay name\" that others see when you participate on eBay. "
				"	You can create a name or simply use your email address. "
				"	<p> Examples \"wunderkid\", \"jsmith98\", \"jeff@aol.com\".</td>\n"
				"<td width=\"290\">&nbsp;<input type=\"text\" name=\"userid\" size=\"30\">"
				"</td>\n"
				"</tr>\n"
			  	"</table>\n";

	// Carry over into this page whether or not the user has indicated he or 
	// she wants to be notified of changes to the user agreement. That's why
	// this became a dynamic page.
	*mpStream << "<input type=\"hidden\" name=\"notify\" value=";

	if (notify)
		*mpStream << "1>\n";
	else 
		*mpStream << "0>\n";

	*mpStream << "<input type=\"hidden\" name=\"countryid\" value="
		      << countryId
			  << ">\n";


	*mpStream <<
			  	"<p><input type=\"submit\" value=\"Complete your registration\">\n"
 			  	"</p>\n"
			  	"</form>\n";

	return;
}

//


// UserAgreementAccept is called if the user agrees the new user agreement from 
// the sitemap, latest buzz, or wherever -- but outside of registration itself 
// or the flow of buying, selling, etc.
//
void clseBayApp::UserAgreementAccept(CEBayISAPIExtension *pThis,
							  char *pUserId,
							  char *pPassword,
							  bool agree,
							  bool notify)						   				  							   							  						  					  
{
	int unused;

	SetUp();
	
	// We need a title and a standard header

	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User Agreement"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	// Validate the the user accepting the agreement

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	if (!agree) {

		if (notify)
			mpUser->SetSomeUserFlags(true, UserFlagChangesToAgreement);

		// If the user has already agreed, we can't let them just get
		// out by declining. (We're like the Hotel California... the last
		// line in the song.)
		if ( mpUser->AcceptedUserAgreement() ) {

			*mpStream << "<h2>Declining the User Agreement</h2>\n"
						 "You have previously accepted the User Agreement. "
						 "If you have questions about this Agreement, or "
						 "if you would like to cancel your membership at "
						 "eBay, please write to "
				  <<	"<A HREF="
				  <<	"\""
				 <<	mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
				 <<	"eBayISAPI.dll?SendQueryEmailShow&subject=policies"
				 <<	"\">"
				 <<	"Customer Support"
				 <<	"</A>.";

			*mpStream <<	"<p>"
					  <<	mpMarketPlace->GetFooter()
					  <<	flush;

			CleanUp();
			return;
		}
		else
		{
			// The user is being disagreeable.
			// Show the User Agreement FAQ.
			ProduceUserAgreementFAQ();

			CleanUp();
			return;	
		}
	}

	// Otherwise, the user has accepted the user agreement, 
	// so mark this in the database (keep track of whether they want
	// to be notified of amendments to the user agreement.
	
	if (notify)
		unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement | UserFlagChangesToAgreement);
	else
		unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement);

	// Show the thank you page (I've got to take the user somewhere!).
	*mpStream <<
				  " <h2 ALIGN=\"left\">Thank You for Helping Make eBay a Better Place!</h2>\n"
				  " </B> \n"
				  " <P>Your account will now reflect that you have accepted the User Agreement. \n"
				  "   How does this make eBay a better place? \n"
				  "   For starters, you will no longer have to read the Agreement every time you bid \n"
				  "   or sell! One less congestion to help make your trades at eBay faster and easier. \n"
				  "   We are committed to improving your experience at eBay.\n"
				  " <P>We hope the User Agreement will not need to change again. But \n"
				  "   if we need to make major adjustments we will post the changes on the site for \n"
				  "   you to see 30 days prior to its effectiveness. You also have the option to receive \n"
				  "   an email notice of changes should you prefer. See our <a href=\""
			   << mpMarketPlace->GetHTMLPath()
			   << "services/myebay/optin-login.html\">user preferences page</a>\n"
				  " where you can opt-in or opt-out of receiving these notifications. \n"
				  " </P>\n"
				  " <P>Your account will reflect that you have accepted the User Agreement \n"
				  "   and you will not see the Agreement again.</P>\n"
//				  " <P ALIGN=\"center\"><font size=\"2\"><a href=\"http://www.ebay.com\">Go to our home \n"
// kakiyama 07/16/99
			   << "<P ALIGN=\"center\"><font size=\"2\"><a href=\""
			   << mpMarketPlace->GetHTMLPath()
			   << "\">Go to our home \n"
				  "   page</a></font></P>";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	CleanUp();
	return;
}

//
// Handle acceptance of the user agreement as the first step of registration.
//
void clseBayApp::RegistrationAcceptAgreement(CEBayISAPIExtension *pThis,
											 bool agree,
											 bool notify,
											 int  countryId)						   				  							   							  						  					  
{
	SetUp();
	
	// We need a title and a standard header
	
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Finalize Your Registration"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	if (!agree) {

		// Redirect the user to the User Agreement FAQ.
		ProduceUserAgreementFAQ();

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Otherwise, the user has accepted the user agreement, 
	// so emit the password confirmation page.
	produce_reg_confirm(mpStream, mpMarketPlace, notify, countryId);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

//
// Handle acceptance of the user agreement as the first step of registration
// with an anonymous email account.
//
void clseBayApp::CCRegistrationAcceptAgreement(CEBayISAPIExtension *pThis,
											 bool agree,
											 bool notify)						   				  							   							  						  					  
{
	SetUp();
	
	// We need a title and a standard header
	
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Finalize Your Registration"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	if (!agree) {

		// Redirect the user to the User Agreement FAQ.
		ProduceUserAgreementFAQ();

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Otherwise, the user has accepted the user agreement, 
	// so emit the password confirmation page.

	/*** !!! JUST FOR NOW -- WON'T HIT THIS CASE -- JUST FOR SECURE SERVER 
	***
	CCConfirmError(0, // No error - just produce the page.
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   NULL,
				   0,
				   0);
	***
	***/

	return;
}

void clseBayApp::UserAgreementForBidding(int   item,
									     char *pUserId,
						                 char *pPass,
						                 char *pMaxBid,
						                 int   quantity,
										 char *pKey)
{
	
	// Accepting the user agreement goes right back to MakeBid.
	// In these other cases, I've got to set up and take down
	// the environment myself.
	SetUp();

	// Create the form part of the user agreement document.
	ProduceUserAgreementIntroForBiddingAndSelling();
    ProduceUserAgreementTopPart();

	*mpStream <<
			"<form method=\"post\" action=\""
		<<  mpMarketPlace->GetCGIPath(PageMakeBid)
		<<  "eBayISAPI.dll\"> \n"
			"  <p> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AcceptBid\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"item\" VALUE=\""
			<< item
			<< "\"> \n";
	if (pUserId != NULL)
	{
			*mpStream <<	"<INPUT TYPE=HIDDEN NAME=\"userid\" VALUE=\""
					  << pUserId
					<< "\"> \n";
	}
	if (pPass != NULL)
	{
		*mpStream <<	"    <INPUT TYPE=HIDDEN NAME=\"pass\" VALUE=\""
				  << pPass
				  << "\"> \n";
	}

	*mpStream <<	"    <INPUT TYPE=HIDDEN NAME=\"maxbid\" VALUE=\""
			<< pMaxBid
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"quant\" VALUE=\""
			<< quantity
			<< "\"> \n"
			<<	"<INPUT TYPE=HIDDEN NAME=\"key\" VALUE=\""
			<< pKey
			<< "\"> \n";

	ProduceUserAgreementFormAfterAction();

	return;
}

// nsacco 07/27/99 new param list
void clseBayApp::UserAgreementForSelling(
								char *pUserId,
							    char *pPass,
							    char *pTitle,
								char *pLocation,
								char *pReserve,
								char *pStartPrice,
								char *pQuantity,
								char *pDuration,
								char *pBold,
								char *pFeatured,
								char *pSuperFeatured,
								char *pPrivate,
								char *pDesc,
								char *pPicUrl,
								char *pCategory1,
								char *pCategory2,
								char *pCategory3,
								char *pCategory4,
								char *pCategory5,
								char *pCategory6,
								char *pCategory7,
								char *pCategory8,
								char *pCategory9,
								char *pCategory10,
								char *pCategory11,
								char *pCategory12,
								char *pOldItemNo,
								char *pOldKey,
								char *pMoneyOrderAccepted,
							    char *pPersonalChecksAccepted,
							    char *pVisaMasterCardAccepted,
							    char *pDiscoverAccepted,
							    char *pAmExAccepted,
							    char *pOtherAccepted,
							    char *pOnlineEscrowAccepted,
							    char *pCODAccepted,
							    char *pPaymentSeeDescription,
							    char *pSellerPaysShipping,
							    char *pBuyerPaysShippingFixed,
							    char *pBuyerPaysShippingActual,
							    char *pShippingSeeDescription,
							    char *pShippingInternationally,
							    char *pShipToNorthAmerica,
							    char *pShipToEurope,
							    char *pShipToOceania,
							    char *pShipToAsia,
							    char *pShipToSouthAmerica,
							    char *pShipToAfrica,
							    int  siteId,
							    int  descLang,
							    char *pGiftIcon,
							    int  gallery,
							    char *pGalleryUrl,
							    int  countryId,
							    int  currencyId,
							    char *pZip)
{
	
	// Accepting the user agreement goes right back to MakeBid.
	// In these other cases, I've got to set up and take down
	// the environment myself.
	SetUp();

	// Create the form part of the user agreement document.
	ProduceUserAgreementIntroForBiddingAndSelling();
    ProduceUserAgreementTopPart();

	char		*pNewTitle;
	char		*pNewDescription;

	pNewDescription = CleanUpDescription(pDesc);
	pNewTitle		= CleanUpTitle(pTitle);

	*mpStream <<
			"<form method=\"post\" action=\""
		<<  mpMarketPlace->GetCGIPath(PageVerifyNewItem)
		<<  "eBayISAPI.dll\"> \n"
			"  <p> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"VerifyNewItem\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"userid\" VALUE=\""
			<< pUserId
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"pass\" VALUE=\""
			<< pPass
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"title\" VALUE=\""
			<< pNewTitle
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"location\" VALUE=\""
			<< pLocation
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"reserve\" VALUE=\""
			<< pReserve
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"startprice\" VALUE=\""
			<< pStartPrice
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"quant\" VALUE=\""
			<< pQuantity
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"duration\" VALUE=\""
			<< pDuration
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"bold\" VALUE=\""
			<< pBold
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"featured\" VALUE=\""
			<< pFeatured
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"superfeatured\" VALUE=\""
			<< pSuperFeatured
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"private\" VALUE=\""
			<< pPrivate
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"desc\" VALUE=\""
			<< pNewDescription
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"picurl\" VALUE=\""
			<< pPicUrl
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category1\" VALUE=\""
			<< pCategory1
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category2\" VALUE=\""
			<< pCategory2
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category3\" VALUE=\""
			<< pCategory3
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category4\" VALUE=\""
			<< pCategory4
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category5\" VALUE=\""
			<< pCategory5
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category6\" VALUE=\""
			<< pCategory6
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category7\" VALUE=\""
			<< pCategory7
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category8\" VALUE=\""
			<< pCategory8
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category9\" VALUE=\""
			<< pCategory9
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category10\" VALUE=\""
			<< pCategory10
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category11\" VALUE=\""
			<< pCategory11
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"category12\" VALUE=\""
			<< pCategory12
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"olditem\" VALUE=\""
			<< pOldItemNo
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"oldkey\" VALUE=\""
			<< pOldKey
			<< "\"> \n"	
			
			// nsacco 07/27/99 pass new params
			"    <INPUT TYPE=HIDDEN NAME=\"moneyOrderAccepted\" VALUE=\""
			<< pMoneyOrderAccepted
			<< "\"> \n"	
			"    <INPUT TYPE=HIDDEN NAME=\"personalChecksAccepted\" VALUE=\""
			<< pPersonalChecksAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"visaMasterCardAccepted\" VALUE=\""
			<< pVisaMasterCardAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"discoverAccepted\" VALUE=\""
			<< pDiscoverAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"amExAccepted\" VALUE=\""
			<< pAmExAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"otherAccepted\" VALUE=\""
			<< pOtherAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"onlineEscrow\" VALUE=\""
			<< pOnlineEscrowAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"paymentCOD\" VALUE=\""
			<< pCODAccepted
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"paymentSeeDescription\" VALUE=\""
			<< pPaymentSeeDescription
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"sellerPaysShipping\" VALUE=\""
			<< pSellerPaysShipping
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"buyerPaysShippingFixed\" VALUE=\""
			<< pBuyerPaysShippingFixed
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"buyerPaysShippingActual\" VALUE=\""
			<< pBuyerPaysShippingActual
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"shippingSeeDescription\" VALUE=\""
			<< pShippingSeeDescription
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"shippingInternationally\" VALUE=\""
			<< pShippingInternationally
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"northamerica\" VALUE=\""
			<< pShipToNorthAmerica
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"europe\" VALUE=\""
			<< pShipToEurope
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"oceania\" VALUE=\""
			<< pShipToOceania
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"asia\" VALUE=\""
			<< pShipToAsia
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"southamerica\" VALUE=\""
			<< pShipToSouthAmerica
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"africa\" VALUE=\""
			<< pShipToAfrica
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"siteid\" VALUE=\""
			<< siteId
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"language\" VALUE=\""
			<< descLang
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"giftIcon\" VALUE=\""
			<< pGiftIcon
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"gallery\" VALUE=\""
			<< gallery
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"galleryurl\" VALUE=\""
			<< pGalleryUrl
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"countryid\" VALUE=\""
			<< countryId
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"currencyid\" VALUE=\""
			<< currencyId
			<< "\"> \n"
			"    <INPUT TYPE=HIDDEN NAME=\"zip\" VALUE=\""
			<< pZip
			<< "\"> \n"
			// end new params
			   "  </p> \n";

	ProduceUserAgreementFormAfterAction();

	delete [] pNewDescription;
	delete [] pNewTitle;

	return;
}

// kaz: 4/7/99
void clseBayApp::PoliceBadgeAgreementForSelling(char *pUserId, char *pPass)
{
	*mpStream	<< PBLegalTextTitle
				<< PBLegalText;

	*mpStream << "<form method=\"post\" action=\""
		<<  mpMarketPlace->GetCGIPath(PagePoliceBadgeLogin)
		<< "eBayISAPI.dll\"> \n"
			"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"PoliceBadgeLoginForSelling\"> \n";
	
	// show name and password fields
	*mpStream	<<	"<p><table><tr><td>Your "
			<<	mpMarketPlace->GetLoginPrompt()
			<<	":</td>\n"
				"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
				"<tr><td>Your "
			<<	mpMarketPlace->GetPasswordPrompt()
			<<	":</td>\n"
				"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
				"</table>\n"
				"</p>";
	
	// Show Accept, Decline buttons
	*mpStream <<
			"  <table border=0 width=75%> \n"
			"    <tr>  \n"
			"      <td width=23%>  \n"
			"        <input type=\"submit\" name=\"accept\" value=\"I Accept\"> \n"
			"      </td> \n"
			"      <td width=77%>  \n"
			"        <input type=\"submit\" name=\"decline\" value=\"I Decline\"> \n"
			"      </td> \n"
			"    </tr> \n"
			"  </table> \n"
			"  </form> \n";
	return;
}

// kaz: 4/7/99: Handles Police Badge T&C page
void clseBayApp::PoliceBadgeLogin(char *pUserId, char *pPassword, bool agree)
{
    SetUp();

	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Police Badge User Agreement"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	*mpStream <<	PBLegalTextTitle;

	// We need the user and their password, we sent the header,
	// we don't have an action, ghost is not okay, we don't need feedback,
	// we do need the account.
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream,
		true, NULL, false, false, false, true);

	if ((mpUser) && (agree))
	{
        mpUser->SetSomeUserFlags(true,UserFlagSignedPBAgreement);
		*mpStream	<<	PBAcceptText;
	}
	else if (! agree)
		*mpStream	<<  PBDeclineText;
	
	*mpStream	<<	mpMarketPlace->GetFooter();
	CleanUp();
}



