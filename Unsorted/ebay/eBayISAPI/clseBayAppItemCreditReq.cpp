//
//	File:	clseBayAppItemCreditReq.cpp
//
//	Class:	clseBayApp
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:
//
//		Initial entry for chinese and dutch auction credit
//
// Modifications:
//				- 11/30/98 Sam	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include <clseBayTimeWidget.h>	// petra



static const char *ErrorMsgInvalidTimePeriod =
"<h2>Auction in progress or auction ended less than 7 days back.</h2>"
"<p>You cannot apply for final value fee credits because the auction is either "
"not ended, or you have not waited for at least 7 days from the end of the "
"auction. eBay requires that you wait at least 7 days from the auction end "
"date to allow the bidder sufficient time to contact you.";

static const char *ErrorMsgMissingFields =
"<h2>One or more fields incomplete!</h2>"
"<p>One or more fields that are required for processing were not filled. Please "
"go back and correct this error.";

static const char *ErrorMsgMissingAmountReceived =
"<h2>Amount received is missing!</h2>"
"<p>You did not indicate the amount you have received from bidders of this auction. "
"Please go back and fill in the amount you received.";

static const char *ErrorMsgDuplicateEmail =
"<h2>Duplicate E-Mail!</h2>"
"You specified same E-Mail information for 2 or more bidders."
"<p>Please go back and correct this error.";

static const char *ErrorMsgUnknownError =
"<h2>Internal Error!</h2>"
"<p>An internal error occured while attempting to process your request. "
"Please try again. ";

static const char *ErrorMsgExpiredTimePeriod =
"<h2>Too late to apply for credit!</h2>"
"<p>Sorry, credit cannot be issued because the auction ended more than 60 days back.<br>";

static const char *ErrorInvalidSellerForItem =
"<h2>Cannot issue credit for one of the following reasons.</h2>"
"1. Item does not exist or was not found.<br>"
"2. You are not the seller of this item.<br>";

static const char *ErrorMsgInvalidBidCntOrReservePriceNotMet =
"<h2>Cannot issue credit for item #%d</h2>"
"Your request for credit cannot be processed for one of the following reasons:<br>"
"1. There were no bids placed for this item.<br>"
"2. Reserved price was not met for this item.";

static const char *ErrorMsgCreditAlreadyApplied =
"<h2>Cannot issue credit for item #%d</h2>"
"Credit for this item has already been issued!";


typedef vector<char *> EmailVector;


void clseBayApp::ItemCreditReq(	CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								char * pItemNo,
								int	   moreCredits)
{
	int				item=0;
	bool			error=false;
	time_t			curtime=0;
	time_t			endtime=0;
	time_t			waitperiod=0;
// petra	const struct tm	*pTimeAsTm;
	char			cEndDate[16];
	char			buf[512];
	bool			isArc=false; // item in archive table or not


	// Setup & initialize
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Item Credit Request"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Just print confirmation, done completely with this item credit
	// moreCredits = 0, chineseAuctions since there is only one high bidder
	// moreCredits = 1, dutchAuctions and that more credits need to be processed 
	// moreCredits = 2, dutchAuctions for which all credits have been processed
	if (moreCredits == 2)
	{
		// Done with credits for this item
		*mpStream	<<	"<H2>"
						"Credit request process completed!"
						"</H2>"
						"<p>"
						"Your final value fee credit request has been submitted for item #"
						"<b>";
		*mpStream	<<	pItemNo
					<<	"</b>.<br>"
					<<	"<p>Your credit request will be reviewed by eBay's Customer Accounts "
						"Department."
						"<p><b>Please allow 24 hrs for the credit amount to be posted to "
						"your account.</b>";
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter()
					<<  flush;
		CleanUp();
		return;
	}

	// Field Checks
	if (FIELD_OMITTED(pUserId)	|| 
		FIELD_OMITTED(pPass)	|| 
		FIELD_OMITTED(pItemNo))
	{
		*mpStream	<<	ErrorMsgMissingFields;
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// First pass for item credit
	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												true);			// Admin Query
	}
	else
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												false);			// Admin Query
	}

	if (!mpUser)
	{   
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	// Item Sanity Checks
	// 1. Does Item exist?
	//    Is the seller the owner of item
	item	= atoi(pItemNo);
	mpItem	= mpItems->GetItem(item, true);
	if (mpItem==NULL) // Look into archive
	{
		mpItem	= mpItems->GetItemArcDet(item);
		if (mpItem)
			isArc = true;
	}

	// 1a. Did this person sell this item?
	if ((mpItem == NULL) || (mpItem->GetSeller() != mpUser->GetId()))
	{
		*mpStream	<<	ErrorInvalidSellerForItem
					<<	"Please go back and try again.";
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// 2. Is item type other than dutch or chinese
	if ((mpItem->GetAuctionType() != AuctionChinese) &&
		(mpItem->GetAuctionType() != AuctionDutch))
	{
		*mpStream	<<	"<H2>Invalid auction type for credit.</H2>"
					<<	"Final value fee credits can only auctions that are either "
					<<	"chinese (single item) or dutch"
					;
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}		

	// 3. Is this item's auction in progress or within 3 day period from end of auction
	curtime	= time(0);
	endtime = mpItem->GetEndTime();
	waitperiod = endtime + 7*ONE_DAY; // 7 days after auction ended
// petra	pTimeAsTm	= localtime(&endtime);
// petra	if (pTimeAsTm)
// petra		strftime(cEndDate, sizeof(cEndDate), "%m/%d/%y", pTimeAsTm);
	clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endtime);	// petra
	timeWidget.EmitString (cEndDate);								// petra

	if (waitperiod >= curtime)
	{
		error = true;
		*mpStream << ErrorMsgInvalidTimePeriod;
	}

	// 4. Don't allow credits 60 days after close of auction
	waitperiod = endtime + 60*ONE_DAY; // 60 days after auction ended
	if (!error && (curtime > waitperiod))
	{
		error = true;
		*mpStream << ErrorMsgExpiredTimePeriod;
	}

	// 5. No credit to be issued if no bids were placed or reserved price not met
	if (!error && (mpItem->GetPrice() == 0		||
				   mpItem->GetBidCount() == 0	||
				   mpItem->GetPrice() < mpItem->GetReservePrice()))
	{
		error			= true;
		sprintf(buf, ErrorMsgInvalidBidCntOrReservePriceNotMet, item);
		*mpStream << buf;
	}

	// 6. Deny if credit was applied before

	if (!error && (mpItem->HasNoSaleCredit() || mpItem->HasFVFCredit()))
	{
		error			= true;
		sprintf(buf, ErrorMsgCreditAlreadyApplied, item);
		*mpStream << buf;
	}

	if (error)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// All clear at this point, lets get item info and display
	// Item Title and number link
	*mpStream	<<  "<H2>Final Value Fee Credit Request</H2>";

	*mpStream	<<  "<center><table border=1 cellspacing=0 width=\"100%\" "
				<<	"bgcolor=\"#99CCCC\">"
				<<	"<tr>"
				<<	"<td align=center width=\"100%\"><font size=4 color=\"#000000\">"
				<<	"<b>"
				<<	"\""
				<<	mpItem->GetTitle()
				<<	"\"</b></font></td>"
				<<	"</tr> <tr>"
				<<	"<td align=center width=\"100%\"><font size=3 color=\"#000000\"><b>"
	 			<<  "<A HREF=\""
				<<   mpMarketPlace->GetCGIPath(PageViewItem)
				<<	"eBayISAPI.dll?ViewItem&item="
				<<	item
				<<	"\">"
				<<	"Item #"
				<<	item
				<<  "</A>"
				<<	"</b></font></td></tr></table></center><br>";

	// General information displayed next
	*mpStream	<<  "<b><font size=3>"
				<<  "Date Auction Ended: </font></b>"
				<<	"<i>"
				<<	cEndDate
				<<	"</i><br>"
				<<	"<b><font size=3>"
				<<  "Final Bid Price: </font></b>"
				<<	"<i>";
	
	clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), mpItem->GetPrice());
	currencyWidget.EmitHTML(mpStream);

	*mpStream	<<	"</i>";


	// Generate Credit Request Page
	GenerateCreditRequestPage(moreCredits, isArc);

	*mpStream	<<	"<br>"
				<<	mpMarketPlace->GetFooter();
	CleanUp();
}


void clseBayApp::ChineseAuctionCreditReq(CEBayISAPIExtension* pCtxt,
										char *pItemNo,
										int	  arc,
										int	  wasPaid,
										char *pAmt,
										int	  reason)
{
	float				 Amt=0.0;
	CreditsVector		*pvbidderVector=NULL;
	vector<sItemCredits *>::iterator ibidderVector;
	clsUser				*pBidder=NULL;

	// Setup & initialize
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	"Item Credit Request"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	if (FIELD_OMITTED(pItemNo))
	{
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (wasPaid && FIELD_OMITTED(pAmt))
	{
		*mpStream	<<	ErrorMsgMissingAmountReceived;
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpItems == NULL)
	{	
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (arc == 0)
		mpItem	= mpItems->GetItem(atoi(pItemNo), true);
	else
		mpItem	= mpItems->GetItemArcDet(atoi(pItemNo));

	if (mpItem == NULL)
	{
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	pvbidderVector = new CreditsVector;

	// This is done as GetItemArc does not return high bidder email but only the id
	pBidder = mpUsers->GetUser(mpItem->GetHighBidder());
	
	if ( !CheckItemCreditData(pItemNo, wasPaid, pBidder->GetEmail(), 
							  pAmt, reason, pvbidderVector, NULL, arc, 0))
	{
		delete pvbidderVector;
		delete pBidder;
		return;
	}
	// Write the row
	if (!mpItem->SetItemCredit(pvbidderVector))
	{
		*mpStream	<<	"<H2>"
						"Credit request process error!"
						"</H2>"
						"<p>"
						"Our records indicate that credit request "
						"for item# "
						"<b>";
		*mpStream	<<	pItemNo
					<<	"</b> "
					<<  "has already been submitted.";

	}
	else
	{
		*mpStream	<<	"<H2>"
						"Credit request process completed!"
						"</H2>"
						"<p>"
						"Your final value fee credit request has been submitted for item #"
						"<b>";
		*mpStream	<<	pItemNo
					<<	"</b>.<br>"
						"<p>Your credit request will be reviewed by eBay's Customer Accounts "
						"Department. ";

	}

	*mpStream	<<	"<p><b>Please allow 24 hrs for the credit amount to be posted to "
					"your account.</b><br>";

	*mpStream	<<	"<br>"
				<<	mpMarketPlace->GetFooter();
	// Cleanup
	for (ibidderVector = pvbidderVector->begin(); ibidderVector != pvbidderVector->end(); ibidderVector++)
		delete	(*ibidderVector);
	pvbidderVector->erase(pvbidderVector->begin(), pvbidderVector->end());
	delete pBidder;

	CleanUp();
}


void clseBayApp::DutchAuctionCreditReq(CEBayISAPIExtension* pCtxt,
										char *pItemNo,
										int	  arc,
										int	  wasPaid1,
										char *pAmt1,
										int	  reason1,
										char *pEmail1,
										int	  wasPaid2,
										char *pAmt2,
										int	  reason2,
										char *pEmail2,
										int	  wasPaid3,
										char *pAmt3,
										int	  reason3,
										char *pEmail3,
										int	  wasPaid4,
										char *pAmt4,
										int	  reason4,
										char *pEmail4,
										int	  wasPaid5,
										char *pAmt5,
										int	  reason5,
										char *pEmail5,
										int	  moreCredits)

{
	float			Amt=0.0;
	char			reason_code[3];
	clsUser			*pUser=NULL;
	CreditsVector	*pvbidderVector=NULL;
	vector<sItemCredits *>::iterator ibidderVector;
	BidVector		*pvBids;
	vector<clsBid *>::iterator iBids;
	bool			isArc=false;


	memset(reason_code, 0x00, sizeof(reason_code));

	// Setup & initialize
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	"Item Credit Request"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	// Before anything we need to make sure that some data was provided
	if (FIELD_OMITTED(pItemNo))
	{
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if ((reason1==0 && reason2==0 && reason3==0 && reason4==0 && reason5==0) &&
		((pEmail1 && strcmpi(pEmail1, "--")==0) && 
		 (pEmail2 && strcmpi(pEmail2, "--")==0) && 
		 (pEmail3 && strcmpi(pEmail3, "--")==0) && 
		 (pEmail4 && strcmpi(pEmail4, "--")==0) && 
		 (pEmail5 && strcmpi(pEmail5, "--")==0)))
	{
		*mpStream	<<	"<H2>"
						"Input Error!"
						"</H2>"
						"<p>"
						"No credit request data was provided. Please go back to complete your request. <br> "
					<<	mpMarketPlace->GetFooter();
		return;
	}

	// Check to make sure all Amount fields have default or proper passed in values
	if ((pAmt1==NULL) || (pAmt2==NULL) || 
		(pAmt3==NULL) || (pAmt4==NULL) || 
		(pAmt5==NULL) || (mpItems == NULL))
	{
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if ((wasPaid1 && FIELD_OMITTED(pAmt1)) ||
		(wasPaid2 && FIELD_OMITTED(pAmt2)) ||
		(wasPaid3 && FIELD_OMITTED(pAmt3)) ||
		(wasPaid4 && FIELD_OMITTED(pAmt4)) ||
		(wasPaid5 && FIELD_OMITTED(pAmt5)))
	{
		*mpStream	<<	ErrorMsgMissingAmountReceived;
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (arc == 0)
		mpItem	= mpItems->GetItem(atoi(pItemNo), true);
	else
	{
		mpItem	= mpItems->GetItemArcDet(atoi(pItemNo));
		isArc   = true;
	}

	if (mpItem == NULL)
	{
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// prepare vector
	pvBids = new BidVector;
	// get the high bidders
	mpItem->GetDutchHighBidders(pvBids, isArc);

	pvbidderVector = new CreditsVector;
	// User must absolutely provide a reason
	if ( !CheckItemCreditData(pItemNo, wasPaid1, pEmail1, pAmt1, 
							  reason1, pvbidderVector, pvBids, arc, 1))
	{
		delete pvbidderVector;
		return;
	}
	if ( !CheckItemCreditData(pItemNo, wasPaid2, pEmail2, pAmt2, 
							  reason2, pvbidderVector, pvBids, arc, 2))
	{
		delete pvbidderVector;
		return;
	}
	if ( !CheckItemCreditData(pItemNo, wasPaid3, pEmail3, pAmt3, 
							  reason3, pvbidderVector, pvBids, arc, 3))
	{
		delete pvbidderVector;
		return;
	}
	if ( !CheckItemCreditData(pItemNo, wasPaid4, pEmail4, pAmt4, reason4, 
							  pvbidderVector, pvBids, arc, 4))
	{
		delete pvbidderVector;
		return;
	}
	if ( !CheckItemCreditData(pItemNo, wasPaid5, pEmail5, pAmt5, 
							  reason5, pvbidderVector, pvBids, arc, 5))
	{
		delete pvbidderVector;
		return;
	}

	// Commit data
	if (!mpItem->SetItemCredit(pvbidderVector))
	{
		*mpStream	<<	"<H2>"
						"Credit request error!"
						"</H2>"
						"<p>"
						"Our records indicate that credit request "
						"for item# "
						"<b>";
		*mpStream	<<	pItemNo
					<<	"</b> "
					<<  "has already been submitted.";
	}
	else
	{
		*mpStream	<<	"<H2>"
						"Credit request completed!"
						"</H2>"
						"<p>"
						"Your final value fee credit request has been submitted for item #"
						"<b>"
					<<	pItemNo
					<<	"</b>.<br>";

		pUser = mpUsers->GetUser(mpItem->GetSeller(), false, false);

		// Check the vector size and display msg accordingly
		// It is possible that there are more high bidders left to be processed
		if (moreCredits == 1)
		{
			// More Credits
			*mpStream	<<	"<form method=\"post\" action=\""
						<<	mpMarketPlace->GetCGIPath(PageItemCreditReq)
						<<	"eBayISAPI.dll\">"
						<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" "
						<<	"value=\"ItemCreditReq\">"
						<<	"<input type=\"hidden\" name=\"userid\" value=\""
						<<	pUser->GetUserId()
						<<	"\">"
						<<	"<input type=\"hidden\" name=\"pass\" value=\""
						<<	pUser->GetPassword()
						<<	"\">"
						<<	"<input type=\"hidden\" name=\"itemno\" value=\""
						<<	pItemNo
						<<	"\">"
						<<	"<input type=\"hidden\" name=\"morecredits\" value=\"1\">"
						<< "<p>Click <b>"
						<< "<INPUT TYPE=Submit VALUE=\"More Credits..\"></b> "
						<< "if you wish to request additional credits for this auction."
						<<	"</form>";
		}
		// Done with credits
		*mpStream	<<	"<form method=\"post\" action=\""
					<<	mpMarketPlace->GetCGIPath(PageItemCreditReq)
					<<	"eBayISAPI.dll\">"
					<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" "
					<<	"value=\"ItemCreditReq\">"
					<<	"<input type=\"hidden\" name=\"userid\" value=\""
					<<	pUser->GetUserId()
					<<	"\">"
					<<	"<input type=\"hidden\" name=\"pass\" value=\""
					<<	pUser->GetPassword()
					<<	"\">"
					<<	"<input type=\"hidden\" name=\"itemno\" value=\""
					<<	pItemNo
					<<	"\">"
					<<	"<input type=\"hidden\" name=\"morecredits\" value=\"2\">"
					<<	"Click <b>"
					<<	"<INPUT TYPE=Submit VALUE=\"Done\"></b> "
					<<	"if you are finished entering credits for this item.<br>"
					<<	"</form>";
		
		delete pUser;
	}

	*mpStream	<<	"<p><b>Please allow 24 hrs for the credit amount to be posted to "
					"your account.</b><br>";


	*mpStream	<<	"<br>"
				<<	mpMarketPlace->GetFooter();

	for (ibidderVector = pvbidderVector->begin(); ibidderVector != pvbidderVector->end(); ibidderVector++)
		delete	(*ibidderVector);
	pvbidderVector->erase(pvbidderVector->begin(), pvbidderVector->end());
	// The bid list goes
	for (iBids =  pvBids->begin();
		 iBids != pvBids->end();
		 iBids++)
	{
		// Delete the bid
		delete	(*iBids);
	}
	pvBids->erase(pvBids->begin(), pvBids->end());

	CleanUp();
}



void clseBayApp::GenerateCreditRequestPage(int moreCredits, bool isArc)
{
	char 			wasPaid[10], amt[10], reason[10], email[10];
	BidVector		*pvBids;
	clsUser			*pBidder=NULL;
	vector<clsBid *>::iterator iBids;
	EmailVector		*pvEmails;
	vector<char *>::iterator iEmail;
	char			*pEmail=NULL;
	CreditsVector	 vCredits;
	vector<sItemCredits *>::iterator iCredits;
	bool			 skip=false;
	int				 i=0;


	if ((mpItems == NULL) || (mpItem == NULL))
	{
		*mpStream	<<	ErrorMsgUnknownError;
		return;
	}

	if (mpItem->GetAuctionType() == AuctionChinese)
	{
		pBidder = mpUsers->GetUser(mpItem->GetHighBidder());
		if (pBidder == NULL)
		{
			*mpStream	<<	"<H2>Error Getting High Bidder Information!</H2>"
						<<	"<p>There was a problem obtaining high bidder information "
						<<	"for your item."
						<<	"<p>Please try this operation again";
			return;
		}
		// Seller must provide the following information
		*mpStream	<<	"<br><br><HR>"
					<<	"Please complete all fields provided below to apply for credit.<br><br>"
					<<	"<form method=\"post\" action=\""
					<<	mpMarketPlace->GetCGIPath(PageChineseAuctionCreditReq)
					<<	"eBayISAPI.dll\">"
					<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" "
					<<	"value=\"ChineseAuctionCreditReq\">"
					<<	"<input type=\"hidden\" name=\"itemno\" value=\""
					<<	mpItem->GetId()
					<<	"\">"
					<<	"<input type=\"hidden\" name=\"arc\" value=\""
					<<	isArc
					<<	"\">"
					<<	"<table border=\"1\" width=\"100%\" cellspacing=\"0\" "
					<<	"cellpadding=\"4\" height=\"150\">"
					<<	"<tr>"
					<<	"<td width=\30%\" bgcolor=\"#99CCCC\" height=\"24\"><strong>"
					<<	"Did you receive any money from the bidder? </strong></td>"
					<<	"<td width=\"70%\" height=\"24\">"
					<<	"<input type=\"radio\" name=\"waspaid\" value=\"0\" checked>No&nbsp;"
					<<	"<input type=\"radio\" name=\"waspaid\" value=\"1\" >Yes"
					<<	"&nbsp;&nbsp;&nbsp;If Yes, how much?</strong> "
					<<	"<font size=\"2\"><input type=\"text\" "
					<<	"name=\"amt\" size=\"8\" maxlength=\"12\" style=\"text-align: left\">"
					<<	"&nbsp;(numerals and decimal point '.' only.)</font>"
					<<	"<tr>"
					<<	"</td>"
					<<	"<td width=\"30%\" bgcolor=\"#99CCCC\" height=\"24\">"
					<<	"<strong>Reason for refund</strong> </td>"
					<<	"<td width=\"70%\" height=\"24\"><select name=\"reason\" size=\"1\">"
					<<	"<option selected value=\"0\">--</option>"
					<<	"<option value=\"1\">No response from bidder</option>"
					<<	"<option value=\"2\">Bidder no longer wanted item</option>"
					<<	"<option value=\"3\">Bidder did not send payment</option>"
					<<	"<option value=\"4\">Bidder bounced check or stopped payment</option>"
					<<	"<option value=\"5\">Bidder returned item</option>"
					<<	"<option value=\"6\">"
					<<	"Bidder could not complete auction due to family or financial emergency</option>"
					<<	"<option value=\"7\">Bidder claimed terms were unacceptable</option>"
					<<	"<option value=\"8\">Partial Payment - High bidder backed out, lower bidder took item</option>"
					<<	"<option value=\"9\">Partial Payment - Item damaged, bidder paid lower price</option>"
					<<	"<option value=\"10\">Partial Payment - Confusion of actual bid price</option>"
					<<	"<option value=\"11\">Partial Payment - high bidder had an emergency, lower bidder took item</option>"
					<<	"</select><br>"
					<<	"<font size=\"2\">Select reason from choices provided.</font>"
					<<	"</td></tr><tr>"
					<<	"<td width=\"30%\" bgcolor=\"#99CCCC\" height=\"32\">"
					<<	"<strong>Bidders e-mail address</strong></td>"
					<<	"<td width=\"70%\" nowrap height=\"32\">"
					<<	pBidder->GetEmail()
					<<	"</td>"
					<<	"</tr></table>"
					<<	"<p>Please make sure that you have provided accurate information. eBay "
					<<	"may use information provided here to identify bidders who are not "
					<<	"complying with eBay rules."
					<<	"<p>Making false credit claims is a form of fee avoidance. "
					<<	"Sellers found to be guilty of this offense will be suspended."
					<<	"<p>Click <strong><input type=\"submit\" value=\"Submit\"> "
					<<	"</strong>to enter your final value fee credit request."
					<<	"</form>";

		delete pBidder;
		pBidder = NULL;
	} // Chinese Auction
	else if (mpItem->GetAuctionType() == AuctionDutch)
	{
		// prepare vector
		pvBids = new BidVector;
		// get the high bidders
		mpItem->GetDutchHighBidders(pvBids, isArc);
		if (pvBids == NULL) 
		{
			*mpStream	<<	ErrorMsgUnknownError;
			return;
		}

		// Seller must provide the following information
		*mpStream	<<	"<br><br><HR>";
		*mpStream	<<	"<form method=\"post\" action=\""
					<<	mpMarketPlace->GetCGIPath(PageDutchAuctionCreditReq)
					<<	"eBayISAPI.dll\">"
					<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" "
					<<	"value=\"DutchAuctionCreditReq\">"
					<<	"<input type=\"hidden\" name=\"itemno\" value=\""
					<<	mpItem->GetId()
					<<	"\">"
					<<	"<input type=\"hidden\" name=\"arc\" value=\""
					<<	isArc
					<<	"\">";

		// Build high bidder email list
		pvEmails = new EmailVector;
		if (moreCredits)
			mpItem->GetAllItemCredits(mpItem->GetId(), &vCredits);

		for (iBids = pvBids->begin(); iBids != pvBids->end(); iBids++)
		{
			pBidder		= mpUsers->GetUser((*iBids)->mUser);
			if (pBidder == NULL)
				continue;
			if (moreCredits)
			{
				// Check if credit has already been applied for
				for (iCredits = vCredits.begin(); iCredits != vCredits.end(); iCredits++)
				{

					if ((*iCredits)->bidder_id == pBidder->GetId())
					{
						delete pBidder;
						pBidder = NULL;
						skip=true;
						break;
					}
				}
				if (skip)
				{
					skip = false;
					continue;
				}
			}
			pEmail		= new char [strlen(pBidder->GetEmail())+1];
			strcpy(pEmail, pBidder->GetEmail());
			pvEmails->push_back(pEmail);
			delete	pBidder;
			pBidder = NULL;
		}

		// Check any more dutch high bidders left
		if (pvEmails->size() == 0)
		{
			*mpStream	<<	"<p>"
							"All high bidders for credit request have been processed."
							"<p><b>Please allow 24 hours for credit to be posted to your account.</b>";
		}
		else
		{
			if (moreCredits)
			{
				*mpStream	<<	"The information that you provided in the previous page is being processed. "
							<<	"You may continue with the credit process by completing fields provided "
							<<	"below. Click \"Continue\" to submit your credit request.<br><br>";
			}
			else
			{
				if (pvEmails->size() > 5)
					*mpStream	<<	"This form allows you to request final value fee credits for up to five (5) "
								<<	"bidders at a time. If you have more than five final value fee credit requests "
								<<	"for this item, enter the first five, click the continue button below, and "
								<<	"follow the prompts to return to make additional requests.<br><br>";
				else
					*mpStream	<<	"This form allows you to request final value fee for this item. "
								<<	"Click \"Continue\" button below to submit your request.<br><br>";
			}

			// Display upto 5 tables currently
			memset(wasPaid, 0x00, sizeof(wasPaid));
			memset(amt, 0x00, sizeof(amt));
			memset(reason, 0x00, sizeof(reason));
			memset(email, 0x00, sizeof(email));
			// Get bidder email list
			// First display the tables upto the available high bidders
//			for (i=1; i<=pvEmails->size(); i++)
			for (i=1; i<=pvEmails->size()&&i<=5; i++)
			{
				sprintf(wasPaid,	"waspaid%d",	i);
				sprintf(amt,		"amt%d",		i);
				sprintf(reason,		"reason%d",		i);
				sprintf(email,		"email%d",		i);

				*mpStream	<<  "<b>    Bidder #"
							<<  i
							<<	"<FONT COLOR=\"red\">"
							<<	"&nbsp;**Only provide information if this bidder backed out**</FONT></b>"
							<<	"<br>"
							<<	"<table border=\"1\" width=\"100%\" cellspacing=\"0\" "
							<<	"cellpadding=\"4\" height=\"150\">"
							<<	"<tr>"
							<<	"<td width=\30%\" bgcolor=\"#99CCCC\" height=\"24\"><strong>"
							<<	"Did you receive any money from this bidder? </strong></td>"
							<<	"<td width=\"70%\" height=\"24\">"
							<<	"<input type=\"radio\" name=\""
							<<	wasPaid
							<<	"\" value=\"0\" checked>No&nbsp;"
							<<	"<input type=\"radio\" name=\"" 
							<<	wasPaid
							<<	"\" value=\"1\">Yes"
							<<	"&nbsp;&nbsp;&nbsp;If Yes, how much?</strong> "
							<<	"<font size=\"2\"><input type=\"text\" "
							<<	"name=\""
							<<	amt
							<<	"\" size=\"8\" maxlength=\"12\" style=\"text-align: left\">"
							<<	"&nbsp;(numerals and decimal point '.' only.)</font>"
							<<	"<tr>"
							<<	"</td>"
							<<	"<td width=\"30%\" bgcolor=\"#99CCCC\" height=\"24\">"
							<<	"<strong>Reason for refund</strong> </td>"
							<<	"<td width=\"70%\" height=\"24\"><select name=\""
							<<	reason
							<<	"\" size=\"1\">"
							<<	"<option selected value=\"0\">--</option>"
							<<	"<option value=\"1\">No response from bidder</option>"
							<<	"<option value=\"2\">Bidder no longer wanted item</option>"
							<<	"<option value=\"3\">Bidder did not send payment</option>"
							<<	"<option value=\"4\">Bidder bounced check or stopped payment</option>"
							<<	"<option value=\"5\">Bidder returned item</option>"
							<<	"<option value=\"6\">"
							<<	"Bidder could not complete auction due to family or financial emergency</option>"
							<<	"<option value=\"7\">Bidder claimed terms were unacceptable</option>"
							<<	"<option value=\"8\">Partial Payment - High bidder backed out, lower bidder took item</option>"
							<<	"<option value=\"9\">Partial Payment - Item damaged, bidder paid lower price</option>"
							<<	"<option value=\"10\">Partial Payment - Confusion of actual bid price</option>"
							<<	"<option value=\"11\">Partial Payment - high bidder had an emergency, lower bidder took item</option>"
							<<	"</select>"
							<<	"<br><font size=\"2\">Select reason from choices provided.</font>"
							<<	"</td></tr><tr>"
							<<	"<td width=\"30%\" bgcolor=\"#99CCCC\" height=\"32\">"
							<<	"<strong>Bidders e-mail address</strong></td>"
							<<	"<td width=\"60%\" nowrap height=\"32\">"
							<<	"<select name=\""
							<<	email
							<<	"\" size=size=\"1\"> <br>"
							<<	"<option selected value=\"--\">--</option>";

				for (iEmail = pvEmails->begin(); iEmail != pvEmails->end(); iEmail++)
				{

					*mpStream	<<	"<option value=\""
								<<	*iEmail
								<<	"\">"
								<<	*iEmail
								<<	"</option>";
				}

				*mpStream	<<	"</select> "
							<<	"<br><font size=\"2\">Select a bidder from the list provided.</font>"
							<<	"</td>"
							<<	"</tr></table>"
							<<	"<br><br><p>";

				// There are more than 5 successful bidders
				if (pvEmails->size() > 0)
					moreCredits = 1;
				else
					moreCredits = 0;

			}
			// Just pass blank parameters for the next set of bidders that do not exist
			for (i; i<=5; i++)
			{
				sprintf(wasPaid,	"waspaid%d",	i);
				sprintf(amt,		"amt%d",		i);
				sprintf(reason,		"reason%d",		i);
				sprintf(email,		"email%d",		i);

				*mpStream	<<	"<input type=\"hidden\" name=\""
							<<	wasPaid
							<<	"\" value=\"0\">"
							<<	"<input type=\"hidden\" name=\""
							<<	amt
							<<	"\" value=\"\">"
							<<	"<input type=\"hidden\" name=\""
							<<	reason
							<<	"\" value=\"0\">"
							<<	"<input type=\"hidden\" name=\""
							<<	email
							<<	"\" value=\"--\">";
			}

			*mpStream	<<	"<input type=\"hidden\" name=\"morecredits\" value="
						<<	moreCredits
						<<	">";

			*mpStream		<<	"<p>Please make sure that you have provided accurate information. eBay "
							<<	"may use information provided here to identify bidders who are not "
							<<	"complying with eBay rules.<br>"
							<<	"<p>Making false credit claims is a form of fee avoidance. "
							<<	"Sellers found to be guilty of this offense will be suspended."
							<<	"<p>Click <strong><input type=\"submit\" value=\"Continue\"> </strong>"
							<<	"to enter your final value fee credit request."
							<<	"</form>";
		}
		// The email list goes
		for (iEmail =  pvEmails->begin();
			 iEmail != pvEmails->end();
			 iEmail++)
		{
			// Delete the email buffer
			delete	[] (*iEmail);
		}
		pvEmails->erase(pvEmails->begin(), pvEmails->end());
		// The bid list goes
		for (iBids =  pvBids->begin();
			 iBids != pvBids->end();
			 iBids++)
		{
			// Delete the bid
			delete	(*iBids);
		}
		pvBids->erase(pvBids->begin(), pvBids->end());
		// The credits list goes
		for (iCredits =  vCredits.begin();
			 iCredits != vCredits.end();
			 iCredits++)
		{
			// Delete the credit struct
			delete (*iCredits);
		}
		vCredits.erase(vCredits.begin(), vCredits.end());
	}
}


bool clseBayApp::CheckItemCreditData(char *pItemNo, int wasPaid, 
									 char *pEmail, char *pAmt, 
									 int reason, CreditsVector *bidderVector,
									 BidVector *pvBids, int arc, short ord_num)
{
	float				 Amt = 0.0;
	clsUser				*pBidder=NULL;
	sItemCredits		*sCredits;
	vector<clsBid *>::iterator iBids;
	int					bidderQuant = 1;
	vector<sItemCredits *>::iterator iCredits;
	
	
	// Reason was provided but no email
	if ((FIELD_OMITTED(pEmail) || strcmpi(pEmail, "--")==0) && reason!=0)
	{
		*mpStream <<	"<H2>"
						"Bidders E-mail address not provided";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}
		*mpStream	<<	"!</H2>"
						"<p>"
						"For your credit request to be processed you must select from "
						"bidders E-mail list."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return false;
	}		

	// email was provided but no reason
	if (strcmpi(pEmail, "--")!=0 && reason==0)
	{
		*mpStream	<<	"<H2>"
						"Reason for refund not provided";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}
		*mpStream	<<	"!</H2>"
						"<p>"
						"For your credit request to be processed you must provide a "
						"valid reason."
						"<p>If none of the options provided best fit your reason for "
						"credit request, then please click "
					<<  "<A HREF=\""
					<<   mpMarketPlace->GetHTMLPath();

		if (pvBids==NULL)
			*mpStream	<<	"services/buyandsell/finalfeesingle.html";
		else
			*mpStream	<<	"services/buyandsell/finalfeedutch.html";

		*mpStream	<<	"\">"
					<<	"here "
					<<  "</A>"
					<<	"to bring up our final value fee request form. You may complete this form "
					<<	"by providing appropriate reason and send it to us via fax or mail.";

		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return false;
	}

	// If no more data, return
	if ((strcmpi(pEmail, "--")==0) && reason ==0)
		return true; // go to the next data set

	if (mpItem == NULL)
	{
		if (arc == 0)
			mpItem	= mpItems->GetItem(atoi(pItemNo), true);
		else
			mpItem = mpItems->GetItemArcDet(atoi(pItemNo));
	}

	if (mpItem == NULL)
	{
		*mpStream	<<	ErrorMsgUnknownError;
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return false;
	}

	pBidder	= mpUsers->GetUser(pEmail, false, false);
	if (pBidder == NULL)
	{
		*mpStream <<	"<H2>"
						"Error obtaining bidder information";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}
		*mpStream	<<	"!</H2>"
						"<p>"
						"Please go back and try again.";
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return false;
	}


	// For Dutch Auctions only
	// Check to make sure that no duplicate email has been provided
	if (pvBids)
	{
		for (iCredits = bidderVector->begin(); iCredits != bidderVector->end(); iCredits++)
		{

			if ((*iCredits)->bidder_id == pBidder->GetId())
			{
				delete pBidder;
				pBidder = NULL;
				*mpStream	<<	ErrorMsgDuplicateEmail
							<<	"<p>"
							<<	mpMarketPlace->GetFooter();
				CleanUp();
				return false;
			}
		}	
	}

	if (pvBids) // safe to assume that auction is dutch
	{
		for (iBids = pvBids->begin(); iBids != pvBids->end(); iBids++)
		{
			// Get matching id and set quantity
			if ((*iBids)->mUser == pBidder->GetId())
			{
				bidderQuant = (*iBids)->mQuantity;
				break;
			}
		}		
	}

	// Do the checks
	if (wasPaid)
	{
		// We need to store the amt that was received which will then be
		// used by credit-batch2 to calculate the actual credit amt
		if (strcmpi(pAmt, "default")==0)
			Amt = -1.0; // force error
		else if ((strcmpi(pAmt, "default")!=0))
		{
			Amt = atof(pAmt);
			if ( (Amt > 0) && (Amt >= mpItem->GetPrice()*bidderQuant))
				Amt = -1.0;
			else if (Amt == 0.0)
				Amt = -1.0;
		}
	}

	if (Amt < 0.0) // Asking for more credit or -ve amt value entered
	{
		*mpStream <<	"<H2>"
						"Invalid credit request amount";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}						
		*mpStream <<	"!</H2>"
						"1. Amount must be positive in value.<br>"
						"2. Amount you entered must not exceed applicable credit "
						"   amount for this item."
						"<p>"
						"Please go back and enter proper amount value.";
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();

		delete pBidder;
		CleanUp();
		return false;
	}

	// User entered an amount but chose 'no' option
	if (!wasPaid && ((atof(pAmt) > 0.0) || (atof(pAmt) < 0.0)))
	{
		*mpStream <<	"<H2>"
						"Input Error";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}						
		*mpStream <<	"!</H2>"
						"You entered "
				  <<	atof(pAmt)
				  <<	" in the amount field but chose 'no' option."
						"<p>"
						"Please go back and correct the error.";
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		delete pBidder;
		CleanUp();
		return false;
	}

	// Check to see if partial reason was entered and user selected 'no' for amount
	if (((reason == 8) || (reason == 9) || (reason == 10) || (reason == 11)) && !wasPaid)
	{
		*mpStream <<	"<H2>"
						"Incorrect reason code selected";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}						
		*mpStream	<<	"!</H2>"
						"An amount is required with the partial payment reason "
						"you selected."
						"<p>"
						"Please go back, select \"Yes\" and enter a value in the amount field.";
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		delete pBidder;
		CleanUp();
		return false;
	}

	// Check to see if reason code was not partial payment and an amount was entered
	if (((reason != 8) && (reason != 9) && (reason != 10) && (reason != 11)) && wasPaid)
	{
		*mpStream <<	"<H2>"
						"Incorrect reason code selected";
		if (ord_num !=0)
		{
			*mpStream <<	" for Bidder #"
					  <<	ord_num;
		}						
		*mpStream <<	"!</H2>"
						"You selected reason to receive full credit but "
						"entered an amount in the amount field."
						"<p>"
						"Please go back and correct this error.";
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		delete pBidder;
		CleanUp();
		return false;
	}


	// OK, all set to write to make vector entry
	sCredits				= new sItemCredits;
	sCredits->item_id		= atoi(pItemNo);
	sCredits->bidder_id		= pBidder->GetId();
	sCredits->last_modified = 0;
	sCredits->reason_code	= (CreditTypeEnum)reason;
	memset(sCredits->credit_type, 0x00, sizeof(sCredits->credit_type));
	if (wasPaid)
		strcpy(sCredits->credit_type, "p"); // partial credit
	else
		strcpy(sCredits->credit_type, "n"); // no sale credit
	sCredits->batch_id		= 0;
	// quantity
	sCredits->quantity		= bidderQuant; // always defaults to 1

	// This is the amount that the seller received from bidder, 0 for no sale
	sCredits->amt = Amt;
	bidderVector->push_back(sCredits); // caller to cleanup

	// Cleanup
	delete pBidder;
	pBidder = NULL;

	return true;
}
