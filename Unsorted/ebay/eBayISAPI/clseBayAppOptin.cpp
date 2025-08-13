/*	$Id: clseBayAppOptin.cpp,v 1.6.2.3.56.1 1999/08/01 03:01:20 barry Exp $	*/
//
//	File:		clseBayAppOptin.cpp
//
//	Class:		clseBayApp
//
//	Author:		Barry Boone
//
//	Function:
//
//
//	Modifications:
//				- 06/23/98 - barry		created
//				- 04/18/99 - kaz		Simplified the UI and rewrote major portions
//				- 05/06/99 - kaz		Commented out Batch e-mails until support is added
//				- 05/14/99 - kaz		Revised text per PM request
//				- 05/14/99 - kaz		sigh, revised text again
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

// Common code for starting off a table
void start_table(ostream *mpStream, char *title)
{
	*mpStream << "<table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"4\">\n"
				 "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">\n"
				 "<strong><font size=\"4\" color=\"#800000\">"
				<< title
				<< "</font></strong></td>\n"
				 "<td width=\"75%\">&nbsp;</td></tr>\n";
}

// And ending a table
void end_table(ostream *mpStream)
{
	*mpStream << "</table><br>\n";
}

// And starting a row
void start_row(ostream *mpStream)
{
	*mpStream << "<tr><td width=\"35%\" bgcolor=\"#EFEFEF\"><font size=\"3\" color=\"#000000\">\n";
}

// And ending a row
void end_row(ostream *mpStream, char *desc)
{
	*mpStream	<< "<BR></td><td width=\"65%\">\n"
				<< desc
				<< "</td></tr>\n";
}

// Helper utility to create the checkboxes for choosing which emails
// to opt in or out of.
// kaz: 04/18/99 modified to handle the form object name and the title
void make_checkbox(ostream *mpStream, char *objName, char *title, bool checked)
{
	*mpStream << "<input type=\"checkbox\"";

	if (checked)
		*mpStream << " checked";

	*mpStream << " name=\""
		      << objName
			  << "\" value=\"1\">\n"
			  << "<strong>"
			  << title
			  << "</strong>";
}


//
// The ISAPI function for optin-login.html.
//
void clseBayApp::OptinLogin(CEBayISAPIExtension *pThis,
							  char *userid,
							  char *password)						   				  							   							  						  					  
{
	SetUp();

    // We need a title and a standard header
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Review Your Opt-in/Opt-out Choices"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	// And a heading for it all
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";


	// Validate the user
	mpUser	= mpUsers->GetAndCheckUserAndPassword(userid, password, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		CleanUp();
		return;
	}

	// Produce the body of the review page for opt-in/opt-out
	
	*mpStream << "<form method=\"post\" action=\""
			  << mpMarketPlace->GetCGIPath(PageOptinConfirm)
              << "eBayISAPI.dll\">\n"
 				 "<input type=hidden name=MfcISAPICommand value=\"OptinConfirm\">\n";
				 
	*mpStream << "<input type=hidden name=\"userid\" value=\""
		      << userid
			  << "\">\n";

	*mpStream << "<h2><b>Change Your Email Preferences</b></h2>\n"
				 "<p>"
				 "Mark each box below to select the email messages that you "
				 "would like to receive.\n"
			     "</p>\n";

	// kaz: 05/14/99: commented out until all 5 auction e-mails are changeable
	/*
	*mpStream << "<p><b>Note: </b>"
				 "If you choose to decline <b>all</b> of the Auction e-mails, \n"
				 "you will automatically be placed on a Special Update mailing list, \n"
				 "which will keep you informed of important events and changes at eBay. \n"
				 "</p>";
	*/

	//============Auction Emails===============================================================

	start_table(mpStream, "Auction Emails");

	/*
	// 05/06/99 kaz -- commented out until we add support in the batch job
    //-----Daily Status
	start_row(mpStream);
	make_checkbox(mpStream, "DailyStatusOption", "Daily Status", mpUser->SendDailyStatus());	
	end_row(mpStream, "Receive a daily summary of all of your current auction activity.");
	*/

     //-----Listing Confirmation
	start_row(mpStream);
	make_checkbox(mpStream, "ListOption", "Listing Confirmations", mpUser->SendList());
	end_row(mpStream, "Receive a confirmation for each item you put up for auction.");
	
    //-----Bid Notice
	start_row(mpStream);
	make_checkbox(mpStream, "BidOption", "Bid Notices", mpUser->SendBid());
	end_row(mpStream, "Receive a notice when eBay has received a bid you have placed on an item.");

    //-----Outbid notice
	start_row(mpStream);
	make_checkbox(mpStream, "OutBidOption", "Outbid Notices", mpUser->SendOutBid());
	end_row(mpStream, "Receive a notice when someone has outbid you.");

	/*
	// 05/06/99 kaz -- commented out until we add support in the batch job
    //-----End of Auction Notice
	start_row(mpStream);
	make_checkbox(mpStream, "EndOfAuctionOption", "End of Auction Notice", mpUser->SendEndofAuction());
	end_row(mpStream, "Receive information about the end of the auction, including the identity of the \n"
				 "high bidder and seller.<BR><BR>\n"
				 "If you choose to not receive this notice, please keep in mind that you must \n"
				 "still complete the transaction if you are the seller or successful high bidder.");
	*/

	end_table(mpStream);

	//==============Legal Notices=============================================================

	start_table(mpStream, "Legal Notices");

    //-----User Agreement Changes
	start_row(mpStream);
	make_checkbox(mpStream, "ChangesToAgreementOption", "User Agreement Changes", mpUser->SendChangesToAgreement());
	end_row(mpStream, "Receive a notice from eBay if the current User Agreement changes.");
	
    //-----Privacy Policy Changes
	start_row(mpStream);
	make_checkbox(mpStream, "ChangesToPrivacyOption", "Privacy Policy Changes", mpUser->SendChangesToPrivacy());
	end_row(mpStream, "Receive a notice from eBay if the current Privacy Policy changes.");

	end_table(mpStream);

	//============== Promos =============================================================

	start_table(mpStream, "eBay Surveys");

    //-----Special Offers
	start_row(mpStream);
	make_checkbox(mpStream, "SpecialOfferOption", "Special Offers", mpUser->SendSpecialOffer());
	end_row(mpStream, "Receive notices about special offers for eBay members.");

    //-----Events and Promotions
	start_row(mpStream);
	make_checkbox(mpStream, "EventPromotionOption", "Events & Promotions", mpUser->SendEventPromotion());
	end_row(mpStream, "Receive notices about events and promotions for eBay members.");

    //-----Surveys 
	start_row(mpStream);
	make_checkbox(mpStream, "TakePartInSurveysOption", "eBay Surveys", mpUser->SendTakePartInSurveys());
	end_row(mpStream, "Take part in occasional surveys to help evaluate new features and proposed changes.");

    //-----Newsletter 
	start_row(mpStream);
	make_checkbox(mpStream, "NewsletterOption", "eBay Newsletter", mpUser->SendNewsletter());
	end_row(mpStream, "Receive planned eBay newsletter.");

	end_table(mpStream);

	//===========================================================================

	*mpStream << "<BR>\n";
    *mpStream << "<input type=\"submit\" value=\"remember my choices\">\n"
		         "</p>\n"
				 "</form>\n";

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";

	CleanUp();
	return;
}	// OptinLogin

//
// ISAPI function called when the user decides to save their new opt-in
// choices.
//
void clseBayApp::OptinConfirm(CEBayISAPIExtension *pThis,
							char *userid,
							int fChangesToAgreementOption,
							int fChangesToPrivacyOption,
							int fTakePartInSurveysOption,
							int fSpecialOfferOption, 
							int fEventPromotionOption,
							int fNewsletterOption,
							int fEndofAuctionOption,
							int fBidOption,
							int fOutBidOption,
							int fListOption,
							int fDailyStatusOption
							)
{
	clsUser		*pUser;
	long		userFlags = 0;

	SetUp();

	// Start with our friend the header.
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Review Your Opt-in/Opt-out Choices"
					"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";


	pUser = mpUsers->GetUser(userid); // free it later!

	if (pUser == NULL) 
	{
		// This is pretty much impossible -- somehow the user id
		// was validated when they logged into the opt-in/opt-out
		// choices, but now the user id cannot be found.
		*mpStream << "<h2>Sorry -- Please enter your user ID again!</h2>\n"
			         "Something went wrong trying to update your user preferences. "
					 "Please return to <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/myebay/optin-login.html\"> "
				     "our user preferences login page</a> and enter your "
					 "user ID (or nickname) and password, then try "
					 "changing your preferences again.\n";

		goto done;
	}

	// start with what he had
	userFlags = pUser->GetUserFlags();

	// Now go through each
	if (fChangesToAgreementOption == 1)
		userFlags |= UserFlagChangesToAgreement;
	else
		userFlags &= ~UserFlagChangesToAgreement;

	if (fChangesToPrivacyOption == 1)
		userFlags |= UserFlagChangesToPrivacy;
	else
		userFlags &= ~UserFlagChangesToPrivacy;

	if (fTakePartInSurveysOption == 1)
		userFlags |= UserFlagTakePartInSurveys;
	else
		userFlags &= ~UserFlagTakePartInSurveys;

	if (fSpecialOfferOption == 1)
		userFlags |= UserFlagSpecialOffer;
	else
		userFlags &= ~UserFlagSpecialOffer;

	if (fEventPromotionOption == 1)
		userFlags |= UserFlagEventPromotion;
	else
		userFlags &= ~UserFlagEventPromotion;

	if (fNewsletterOption == 1)
		userFlags |= UserFlagNewsletter;
	else
		userFlags &= ~UserFlagNewsletter;

	// the weirdo reverse ones
//	if (fEndofAuctionOption != 1)
//		userFlags |= UserFlagEndofAuction;
//	else
//		userFlags &= ~UserFlagEndofAuction;
	
	if (fBidOption != 1)
		userFlags |= UserFlagBid;
	else
		userFlags &= ~UserFlagBid;

	if (fOutBidOption != 1)
		userFlags |= UserFlagOutBid;
	else
		userFlags &= ~UserFlagOutBid;

	if (fListOption != 1)
		userFlags |= UserFlagList;
	else
		userFlags &= ~UserFlagList;

//	if (fDailyStatusOption != 1)
//		userFlags |= UserFlagDailyStatus;
//	else
//		userFlags &= ~UserFlagDailyStatus;

	// Do it
	pUser->SetUserFlags(userFlags);

	// provide confirmation text
	*mpStream	<<	"Your Email Preferences have been saved.";

	delete pUser;

done:
	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	"<br>\n";
	CleanUp();
	return;
}	// OptinConfirm

