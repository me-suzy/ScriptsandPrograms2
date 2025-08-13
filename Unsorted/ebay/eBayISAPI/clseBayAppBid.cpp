/*	$Id: clseBayAppBid.cpp,v 1.18.2.9.28.1 1999/08/01 03:01:07 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//				Despite it's name, this class
//				is all things bids -- making bids,
//				accepting bids, retracting, cancelling
//				and listing bids. Maybe one day we 
//				should rename it to clseBayApp :-)
//
//	Modifications:
//				- 05/01/97 michael	- Created
//				- 02/23/99 anoop	- Check to see if the user verification completes properly.
//				- 02/23/99 kaz		- Add SelfBidErrorMessage as a const and change
//										text per Customer Support request
//				- 02/24/99 kaz		- And reversed the order of items in the above fix
//				- 03/12/99 kaz		- Moved GetAndCheckItem() to clseBayAppItem.cpp
//				- 05/06/99 vicki	- removed userid and password from makebid for ebayla fixing
//				- 07/09/99 Beth		- Change wording for bidding as per Gillian Judge/Elaine Fung
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//				- 05/06/99 vicki	- remove userid and password from makebid for ebayla fixing
//				- 07/22/99 petra	- change time formatting to use clseBayTimeWidget
#include "ebihdr.h"
//#include "clsFeedback.h"
//#include "clsBid.h"


#include "clsBidResult.h"
#include "clseBayItemDetailWidget.h"
#include "clseBayTimeWidget.h"		// petra

#include "hash_map.h"

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// kaz: Added 2/23/99 so we only have to edit it in one place
// buddy check: Barry
static const char *SelfBidErrorMessage =
	"<h2>Sellers Cannot Bid on Their Own Items</h2>"
	"<P>As of February 3, 1999, sellers are not allowed to bid on their own items.</P>"
	"<P>eBay originally allowed users to bid on their own auctions if they wanted "
	"to close the auction and not sell to the high bidder. Outbidding the high "
	"bidder with your own bid was meant as a courtesy to the existing high bidder. "
	"Unfortunately, this privelege was abused to the point that we have decided "
	"to eliminate it.</P>"
	"<P>Sellers will still have the ability to end an auction if they do not want "
	"to sell. In this case, the seller must cancel all bids on the auction as a "
	"courtesy to the bidders before closing the auction and add to the item "
	"description to explain the reasons.</P>";


// kaz: 3/12/99: GetAndCheckItem() was moved to clseBayAppItem.cpp
// to be near & combined with it's overloaded sibling; this way we only
// update the error msg in 1 place


// Emit category-specific message(s) to bidder
void clseBayApp::EmitBidderMessages(CategoryId categoryId, ostream *pStream)
{
	vector<char *>				vMessages;
	vector<char *>::iterator	i;

	mpCategories->GetCategoryMessages()->GetMessageText(categoryId,
														MessageTypeCategoryBidderWhenBidding,
														&vMessages,
														true);

	if (vMessages.size() > 0)
	{
		*pStream	<< "<b><font color=\"red\">\n"
					<< "<h3>Attention Bidders:</h3>\n"
					<< "<p>\n";

		for (i = vMessages.begin(); i != vMessages.end(); ++i)
		{
			if (*i != NULL)
			{
				*pStream	<< (*i)
							<< "<hr width=\"5%\" align=\"left\">";
				delete (*i);
			}
		}

		*pStream	<< "</font></b><p>\n";

		vMessages.erase(vMessages.begin(), vMessages.end());
	}
}

	
// accepted and notity are optional params. These default args are set to false.

void clseBayApp::MakeBid(CEBayISAPIExtension *pThis,
							int item,
							char *pMaxBid,
							int quantity,
							UAChoice uaChoice,
							CHttpServerContext *pCtxt
							)
{
	// Bid Object
	clsBid			*pBid;
	clsBidResult	*pResult;
	float			TotalValue;   // for dutch auctions, total value of the user's bid.

	// Time fields
	time_t	startTime;
	time_t	endTime;

	struct tm *timeAsTm;

	char	cStartTime[64];
	char	cEndTime[64];

	char	cItemNo[20];
	char	cSalt[20];
	char*	pCryptedItemNo;
	char	*cleanTitle = NULL;

	double	maxbid;
	int		maxSize;

	time_t		reserveStartTime, theTime;
	struct tm	timeAsResStart  = { 0, 0, 23, 9, 9, 97 };
//	struct tm	timeAsResStart  = { 0, 30, 13, 9, 9, 97 };
	clsUserIdWidget*	pUserIdWidget;
	clseBayTimeWidget timeWidget (mpMarketPlace, 0, 0);	// petra

	// for possible redirect for adult signin
	unsigned long lLength;
	char newURL[255];

	clsCategory *	pCategory = NULL;


	SetUp();
	
	// Get the item and check it
	if (!GetAndCheckItem(item))
	{
		CleanUp();
		return;
	}

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	reserveStartTime = mktime(&timeAsResStart);

	// First, we convert the times
	startTime	= mpItem->GetStartTime();
	timeWidget.SetDateTimeFormat (1, 2);	// petra
	timeWidget.SetTime (startTime);			// petra
	timeAsTm	= localtime(&startTime);
	if (timeAsTm)
	{
// petra		strftime(cStartTime, sizeof(cStartTime),
// petra				 "%m/%d/%y %H:%M:%S %z",
// petra				 timeAsTm);
		timeWidget.EmitString (cStartTime);	// petra
	}
	else
		strcpy(cStartTime, "(*Error*)");

	
	endTime		= mpItem->GetEndTime();
	timeWidget.SetTime (endTime);		// petra
	timeAsTm	= localtime(&endTime);
	if (timeAsTm)
	{
// petra		strftime(cEndTime, sizeof(cEndTime),
// petra				 "%m/%d/%y %H:%M:%S %z",
// petra				  timeAsTm);
		timeWidget.EmitString (cEndTime);	// petra
	}
	else
		strcpy(cEndTime, "(*Error*)");


	// If we've gotten this far, we can use the boilerplate
	// Title and header
	// We'll need a title here
	*mpStream <<	"<HTML>"
					"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Bidding on item "
			  <<	item
			  <<	" (Ends "
			  <<	cEndTime
			  <<	") - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();
	// Spacer
	*mpStream <<	"<br>";

	// New from Mar 15, 1999
	// Check to see if the user verification completes properly.
	// we cannot do it, since we don't know user yet --vicki
/*	if (ValidateOrBlockAction() == FALSE)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	} */
	// check whether the item can bid by minor
	if (mpItem->IsAdult() || mpItem->NoBidAndListForMinor())
	{
		// check whether the user is adult
		if (!HasAdultCookie())
		{
			// calculate URL of adult login page
			strcpy(newURL, mpMarketPlace->GetCGIPath(PageAdultLoginShow));
			strcat(newURL, "eBayISAPI.dll?AdultLoginShow");
			if (mpItem->IsAdult())
				strcat(newURL, "&t=1");	// erotica
			else
				if (mpItem->NoBidAndListForMinor())
					strcat(newURL, "&t=2");	// firearms

			// Just in case the redirect doesn't work, tell user where to go
			*mpStream << "<p>Click <b>refresh</b> or <b>reload</b> button on your browser now.";

			CleanUp();

			// redirect to adult sign-in page
			pThis->EbayRedirect(pCtxt, newURL);
			lLength = strlen(newURL);
			// pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, newURL, &lLength, NULL);
			return;
		}
	}

	maxSize = mpMarketPlace->GetMaxAmountSize(mpItem->GetCurrencyId());

	// Let's see if the bid amout makes sense
	if (strlen(pMaxBid) > maxSize)
		{
		*mpStream <<	"<h2>"
						"Problem with bid amount"
						"</h2>"
						"Sorry, your bid amount of "
				 <<		pMaxBid
				 <<		" is too large. Please go back and "
				 <<		"check it again."
				 <<		"<br>"
				 <<		mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// petra changed to use locale class
	clsIntlLocale* pLocale = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale(); // PH 05/05/99
	maxbid	= atof(pLocale->GetNormalizedCurrencyAmount(pMaxBid));

	maxbid	= atof(pMaxBid);

	if (maxbid > mpMarketPlace->GetMaxAmount(mpItem->GetCurrencyId()))
	{
		*mpStream <<	"<h2>"
						"Problem with bid amount"
						"</h2>"
						"Sorry, your bid amount of ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), maxbid);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	" is too large. Please go back and "
				 <<		"check it again."
				 <<		"<br>"
				 <<		mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	if (maxbid < 0)
	{
		*mpStream <<	"<h2>Problem with bid amount</h2>"
						"Sorry, your bid amount of ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), maxbid);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	" is less than zero. Please go back and "
						" check it again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}


	// Quantity
	if (quantity < 1)
	{
		*mpStream <<	"<h2>Problem with quantity</h2>"
						"Sorry, your quantity of "
				  <<	quantity
				  <<	" is not valid. Please go back and "
						"check it again."
						"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		
		return;
	}

	if (quantity > mpItem->GetQuantity() ||
		quantity > EBAY_MAX_QUANTITY_AMOUNT)
	{
		*mpStream <<	"<h2>Problem with quantity</h2>"
						"Sorry, but your quantity of "
				  <<	quantity
				  <<	" is too large. Please go back and "
						"check it again."
						"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		
		return;
	}

	// Build a bid object
	pBid	= new clsBid((time_t)0,
						 BID_BID,
						 0,
						 maxbid,
						 quantity,
						 NULL); 

	// ProposeBid does alll the work, passing NULL since we don't have user yet
	pResult = mpItem->ProposeBid(pBid,
					   mpStream,
					   NULL);


	// Added by AlexP 06/03/99
	if (!pResult->mBidAccepted)
	{
		*mpStream <<	"<br><br>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
	
		delete	pBid;
		delete	pResult;

		CleanUp();

		return;
	}

	// If the bid was ok, then let's talk to 
	// the user
	//need move to acceptbid -- vicki done!
//	if (pResult->mBidAccepted)
	{
		// Determine whether to show the new user agreement or go on with
		// the bid. Only show the user agreement if the user has
		// not yet accepted the user agreement AND the user has not
		// just accepted the agreement.
		// (Enums are in clseBayApp.h.)*/
	
/*		if (!mpUser->AcceptedUserAgreement())
		{

			switch (uaChoice)
			{
			case UAAcceptedWithNotify:
				unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement | UserFlagChangesToAgreement);
				break;

			case UAAcceptedWithoutNotify:
				unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement);
				break;

			case UADeclined:
				ProduceUserAgreementFAQ();

				*mpStream <<	"<br>"
						  <<	mpMarketPlace->GetFooter();

				// Free Memory
				delete	pBid;
				delete	pResult;

				CleanUp();
				return;

				// break;

			default:
				UserAgreementForBidding(item, pUserId, NULL, pMaxBid, quantity);

				*mpStream <<	"<br>"
						  <<	mpMarketPlace->GetFooter();

				// Free Memory
				delete	pBid;
				delete	pResult;

				CleanUp();
				return;
			} // the choice
		} // The user had not yet accepted the user agreement.
*/

		// At this point, the user had already accepted the user agreement
		// or just did so, so we can now continue.
		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), 0); // set below

		// First we must check the category for screening and, if necessary,
		// emit a message for/to the bidder
		pCategory = mpCategories->GetCategory(mpItem->GetCategory(), true);
//		if (pCategory != NULL && pCategory->GetScreenItems())
		if (pCategory != NULL)
		{
			EmitBidderMessages(pCategory->GetId(), mpStream);
		}

		// Beth - start changes 07/09/99
		//clean up title
		cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
		*mpStream <<	"<h2>"
						"Review bid for: "
				  <<	cleanTitle
				  <<	" (item #"
				  <<	item
				  <<	")"
						"</h2>"
						"This is it &#151; <strong>the moment you've been waiting for"
						"</strong>! Check that the info below is correct, enter "
						"your User ID and password, and then click on \"place bid.\""
						"\n";
		delete [] cleanTitle;

		//if real estate display legal verbage
		if (mpItem->CheckForRealEstateListing())
		{
			//let do real estate legal verbage in a table with border
			*mpStream <<	"<table border=\"1\"><tr><td>"
							"<p>The offer and sale of real estate is a complex area, and may be governed "
							"by a variety of local, state and federal laws and private party contractual "
							"arrangements. Buyers and sellers are advised to consult with qualified " 
							"professionals as to legal sufficiency, legal effect and tax consequences "
							"when involved in any transactions in real estate."
							"</td></tr></table>";
		}

		if (mpItem->GetQuantity() > 1)
		{
			*mpStream <<	"<pre>"
							"\n"
							"Your bid per item was:"
							"                 ";

			currencyWidget.SetNativeAmount(pResult->mMaxBid);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	"\n";
			
			if (pResult->mBidChanged)
			{
				*mpStream <<	"\n"
								"<b>"
								"  Your bid of ";

			currencyWidget.SetNativeAmount(pBid->mAmount);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			*mpStream	  <<	" was rounded down to the next bid increment of ";

			currencyWidget.SetNativeAmount(pResult->mBidIncrement);
			currencyWidget.EmitHTML(mpStream);

			*mpStream	  <<	", yielding "
						  <<	pResult->mMaxBid
						  <<	"</b>"
								"\n";
			}

			*mpStream <<	"The quantity you are bidding for:"      
							"      "
							"<b>"			
					  <<    pResult->mQuantity
					  <<    "</b>"
					  << 	"\n";			

			TotalValue = pResult->mMaxBid * pResult->mQuantity;

			*mpStream <<	"Total value of your bid is:            ";

			currencyWidget.SetNativeAmount(TotalValue);
			currencyWidget.EmitHTML(mpStream); // Still set to bold.

			*mpStream    <<	"<strong>"
						 <<	" (Quantity x Amount) </strong>"
							"\n"
							"</pre>"
							"<p>";

		}
		else
		{
			*mpStream <<	"<pre>"
							"\n"
							"Your bid was in the amount of:"
							"         ";

			currencyWidget.SetNativeAmount(pResult->mBid);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	"\n";
			if (pResult->mBidChanged)
			{
				*mpStream <<	"\n"
								"<b>"
								"  Your bid of ";

				currencyWidget.SetNativeAmount(pBid->mAmount);
				currencyWidget.SetBold(false);
				currencyWidget.EmitHTML(mpStream); 

				*mpStream <<	" was rounded down to the next bid increment of ";

				currencyWidget.SetNativeAmount(pResult->mBidIncrement);
				currencyWidget.EmitHTML(mpStream);
	
				*mpStream <<	", yielding ";

				currencyWidget.SetNativeAmount(pResult->mMaxBid);
				currencyWidget.EmitHTML(mpStream);

				*mpStream <<	"</b>"
								"\n\n";
			}

			*mpStream <<	"Your maximum bid was in the amount of: ";

			currencyWidget.SetNativeAmount(pResult->mMaxBid);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	"</pre>";
		}
							
//move to acceptBid --vicki
/*		if (mpItem->GetOwner() == mpUser->GetId())
		{
			*mpStream <<	"<strong>"
							"You are bidding on an item you have listed. "
							"Please read our Guidelines on "
							"<a href="
							"\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"help/community/png-list.html"
							"\""
							">"
							"price manipulation"
							"</a>"
							" before proceeding."
							"</strong>"
							"<p>"
							"\n";


		}
*/
		if (mpItem->GetReservePrice() > 0 &&
			 ((mpItem->GetStartTime() >= reserveStartTime) ||
			  ((mpItem->GetSeller() == 65618) || 
				(mpItem->GetSeller() == 158320) ||
				(mpItem->GetSeller() == 230851))))
		{
			*mpStream   <<	"<p>\n"
							"<strong>"
							"You are bidding in a "
							"<a href="
							"\""
						<<	mpMarketPlace->GetHTMLPath()
						<<	"help/buyerguide/bidding-type.html#reserve"
							"\""
							">"
							"reserve price auction."
							"</a>"
							"  Your bid of ";

			currencyWidget.SetNativeAmount(pResult->mBid);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	" will be increased to the seller's "
							"reserve price if your maximum bid of ";

			currencyWidget.SetNativeAmount(pResult->mMaxBid);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	" meets or exceeds the reserve. "
							"Your bid will never be automatically increased "
							"above your maximum."
							"</strong>"
							"<p>"
							"\n";
		}
	
		// follow the weird convention of checking INT_MIN for non-existent feedback or a rating of 0
		if (mpItem->GetSellerFeedbackScore() == INT_MIN) 
			mpItem->SetSellerFeedbackScore(0);
	
		pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
		pUserIdWidget->SetUserInfo(mpItem->GetSellerUserId(), 
			mpItem->GetSellerEmail(),
			(UserStateEnum)mpItem->GetSellerUserState(),
			false,
			mpItem->GetSellerFeedbackScore(),
			mpItem->GetSellerUserFlags());
		
		pUserIdWidget->SetShowMask(true);
		pUserIdWidget->SetShowAboutMe();
		pUserIdWidget->SetShowStar(true);
		
		// encrypt the item no
		sprintf(cSalt, "%d%s", mpItem->GetSeller(), "98IPO");
		sprintf(cItemNo, "%d", item);
		pCryptedItemNo = crypt(cItemNo, cSalt);

		*mpStream <<	"<form method=post action="
						"\""
						"eBayISAPI.dll"
						"\""
						">"
				  <<	"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
						"VALUE=\"AcceptBid\">\n"
						"<input type=hidden name=item value="
				  <<	item
				  <<	">"
						"<input type=hidden name=key value=\""
				  <<	pCryptedItemNo
				  <<	"\">"
						"<input type=hidden name=maxbid value="
				  <<	maxbid
				  <<	">"
						"\n"
				  <<	"<input type=hidden name=quant value="
				  <<	quantity
				  <<	">";
		//userid and password field 
		*mpStream <<	"<table><tr><td>"
						"<p><strong>Your "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	": </strong></td>"
						"<td><INPUT TYPE=\"text\" NAME=\"userid\" SIZE=\"25\" "
						"maxlength=" << EBAY_MAX_PASSWORD_SIZE << "><br></td></tr>";
		*mpStream <<	"<tr><td><strong>Your "
				  <<	mpMarketPlace->GetPasswordPrompt()
				  <<	":</strong></td>"
						"<td><INPUT TYPE=\"password\" NAME=\"pass\" SIZE=\"25\" "
						"maxlength=" << EBAY_MAX_PASSWORD_SIZE << "></td></tr></table>\n"
						"<br>";

		*mpStream <<	"<input type=submit value="
						"\"";

		*mpStream << "place bid";

		*mpStream <<	"\""
						">"
						"</form>";

		*mpStream	<<	"<p>"
						"<font size=-1>"
						"<a href="
						"\""
					<<	mpMarketPlace->GetCGIPath(PageViewItem)
					<<	"eBayISAPI.dll?ViewItem&item="
					<<	item
					<<	"\""
						">"
				    	"Click here if you wish to cancel"
						"</a>"
						"</font><br><br>"; 

		*mpStream	<<	"<font size=\"3\"><strong>A few useful tips...</strong></font>"
						// Begin "A Few Useful Tips..."
						"<table>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">Remember, bidding is fun, but it's binding, too.</font></td>"
						"</tr>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">If you make a mistake or want to change "
						"something before you submit/confirm your bid, use the back "
						"button of your browser to go back and make any corrections."
						"</font></td></tr>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">Your seller is ";
		pUserIdWidget->EmitHTML(mpStream);
		delete pUserIdWidget;
		*mpStream	<<	". It's always a good idea to "
						"<a href=\""
					<<	mpMarketPlace->GetCGIPath(PageViewFeedback)
					<<	"eBayISAPI.dll?ViewFeedback&userid="
					<<	mpItem->GetSellerUserId()
					<<	"\">view comments about your seller</A>"
						" because you can quickly find out about "
						"a trader's reputation <strong>before</strong> you bid."
						"</font></td></tr>"
						"<tr>"
						"<td valign=\"TOP\" width=\"10\"><font size=\"2\"> &#149 </font> </td>"
						"<td><font size=\"2\">If you want extra assurance before "
						"placing a bid on an expensive item, consider using safe, "
						"easy-to-use escrow services. Escrow services, such as "
						"<A HREF=\"http://www.iescrow.com/ebay/\">i-Escrow</A>, "
						"provide additional security by keeping custody of your "
						"funds and releasing them to the seller only when specified "
						"conditions are met."
						"</font></td></tr>"
						"</table>";
						"\n";
		// End of Beth's changes for 07/09/99

		free(pCryptedItemNo);
	}		


	// Get the current time
	theTime	= time(0);

	// only show the You're Number One Sweepstakes until the end of the '98 year
	if ((clsUtilities::CompareTimeToGivenDate(theTime, 12, 27, 98, 23, 59, 59) <= 0)) {
	*mpStream << "<p>When you are the first person to bid on any item in December, you are "
				"automatically entered in eBay's <b>You're Number One!</b>  Sweepstakes. "
				"Click <a href=\""
			<< mpMarketPlace->GetHTMLPath()
			<<	"sweeps-no1.html\"> here </a> to "
				"find out how you can win prizes this month bidding on eBay.</font>";
	}

	// Alll done!
	*mpStream <<	"<br><br>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;
	// Free Memory
	delete	pBid;
	delete	pResult;

	CleanUp();

	return;
}

//
// AcceptBid
//
void clseBayApp::AcceptBid(CEBayISAPIExtension *pThis,
							  int item,
							  char *pKey,
							  char *pUserId,
							  char *pPass,
							  char *pMaxBid,
							  int quantity,
							  UAChoice uaChoice,
							  CHttpServerContext* pCtxt
							  )
{
	// Time fields
	time_t	startTime;
	time_t	endTime;

	struct tm *timeAsTm;

	char	cStartTime[64];
	char	cEndTime[64];
	clseBayTimeWidget timeWidget(mpMarketPlace, 0, 0);	// petra

	double	maxBid;
	double  maxSize;

	// Bids
	clsBid			*pBid;
	clsBidResult	*pResult;

	// Item details
	clseBayItemDetailWidget *idw;

	// for encryption
	char	cItemNo[20];
	char	cSalt[40];
	char*	pCryptedItemNo;

	int     unused; // unused return flags from ebay_user -- vicki

	// for possible redirect for adult signin
	unsigned long lLength;
	char newURL[255];

	int bidder_id;

	SetUp();
	
	// Get the item and check it
	// 
	// ***** NOTE *****
	// Should lock the row here
	// ***** NOTE *****
	if (!GetAndCheckItem(item))
	{
		CleanUp();
		return;
	}

	maxSize = mpMarketPlace->GetMaxAmountSize(mpItem->GetCurrencyId());

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);


	// First, we convert the times
	startTime	= mpItem->GetStartTime();
	timeWidget.SetDateTimeFormat (1, 2);	// petra
	timeWidget.SetTime (startTime);			// petra
	timeAsTm	= localtime(&startTime);
	if (timeAsTm)
	{
// petra		strftime(cStartTime, sizeof(cStartTime),
// petra				 "%m/%d/%y %H:%M:%S %z",
// petra				 timeAsTm);
		timeWidget.EmitString (cStartTime);		// petra
	}
	else
		strcpy(cStartTime, "(*Error*)");

	
	endTime		= mpItem->GetEndTime();
	timeWidget.SetTime (endTime);		// petra
	timeAsTm	= localtime(&endTime);
	if (timeAsTm)
	{
// petra		strftime(cEndTime, sizeof(cEndTime),
// petra				 "%m/%d/%y %H:%M:%S %z",
// petra				  timeAsTm);
		timeWidget.EmitString (cEndTime);		// petra
	}
	else
		strcpy(cEndTime, "(*Error*)");



	// If we've gotten this far, we can use the boilerplate
	// Title and header
	// We'll need a title here
	*mpStream <<	"<HTML>"
			  <<	"<HEAD>"
			  <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Confirming bid for item "
			  <<	item
			  <<	" (Ends "
			  <<	cEndTime
			  <<	") - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// check whether the page has been poisoned by the user
	sprintf(cSalt, "%d%s", mpItem->GetSeller(), "98IPO");
	sprintf(cItemNo, "%d", item);
	pCryptedItemNo = crypt(cItemNo, cSalt);
	if (strcmp(pCryptedItemNo, pKey) != 0)
	{
		// Yes, it has been poison
		*mpStream <<	"<H2>Error in input data</H2>\n"
				  <<	"There are errors in the input data. Please try again."
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
	free(pCryptedItemNo);

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream,
												  true, NULL, false, true, false);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// New from Mar 15, 1999
	// Check to see if the user verification completes properly.
	if (ValidateOrBlockAction() == FALSE)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	//move useragreement from Makebid to here, (for ebayla fix)
	//because in makebid, we couldn't get user obj-- vicki
	if (!mpUser->AcceptedUserAgreement())
	{
		switch (uaChoice)
		{
		case UAAcceptedWithNotify:
			unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement | UserFlagChangesToAgreement);
			break;

		case UAAcceptedWithoutNotify:
			unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement);
			break;

		case UADeclined:
			ProduceUserAgreementFAQ();

			*mpStream <<	"<br>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
			// break;

		default:
			UserAgreementForBidding(item, pUserId, pPass, pMaxBid, quantity, pKey);

			*mpStream <<	"<br>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
		} // the choice
	} 
		
	// New for Feb 2, 1999
	// Check to see if bidder is the same as the seller
	if (mpUser->GetId() == mpItem->GetSeller())
	{
		// kaz: added 2/23/99 Replaced html text w/ string const as it's also used above
		//		and add in links w/ relative paths (someone should make this is a static page one day)
		//		buddy check: Barry
		*mpStream <<	SelfBidErrorMessage
				<< 	"<P>You may:</P>"
				<<  "<UL>"
				<<	"<LI><A HREF="
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/add-to-item.html>Add to your item description</A>"
				<< "<LI><A HREF="
				<<	mpMarketPlace->GetHTMLPath()
				<< "services/buyandsell/seller-cancel-bid.html>Cancel bids</A>"				
				<<	"<LI><A HREF="
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/end-auction.html>End your auction</A>"
				<<	"</UL>"
				<<	mpMarketPlace->GetFooter();

		CleanUp();

		return;

	}

	// Start of special check for the Sosa auction.
	bidder_id = mpUser->GetId();

	if ((item == 54054731) || (item == 54055932) || (item == 54056930) ||
		(item == 54057995) || (item == 54058722) || (item == 54059430) ||
	    (item == 54066966) || (item == 54067808) ||
		(item == 51330584) // Test item!
	   )
	{
		// && in any other bidders that have been prequalified
		if (    (bidder_id != 158320)  // skippy
			 && (bidder_id != 852225)  // barry@ebay.com
			 && (bidder_id != 1078044) // patricia@ebay.com
			 && (bidder_id != 230851)  // poon
			 && (bidder_id != 400708)  // tini
			 && (bidder_id != 825082)  // kate@ebay.com
			 && (bidder_id != 2722791) // todd@strome.com
			 && (bidder_id != 2528091) // trentstrains
			 && (bidder_id != 2752237) // realtimebid
			)
		{
			*mpStream 
				<< "Only bidders who have been prequalified by Guernsey's can participate in "
                   "this auction. If you would like to participate, please refer to "
				   "<A HREF=\""
				 << mpMarketPlace->GetHTMLPath()
				 << "homerun-ball.html\">"
                    "this</a> page for instructions. Your request will be placed "
                    "after our deadlines; however, we will do our best to prequalify you in " 
                    "time. Thank you and happy bidding! "
                    "<br><br>"
                <<  mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}
	}
	// End of special Sosa auction check.

	// check whether the item is not for Minor
	if (mpItem->IsAdult() || mpItem->NoBidAndListForMinor())
	{
		// check whether the user is adult
		if (!HasAdultCookie())
		{
			// calculate URL of adult login page
			strcpy(newURL, mpMarketPlace->GetCGIPath(PageAdultLoginShow));
			strcat(newURL, "eBayISAPI.dll?AdultLoginShow");
			if (mpItem->IsAdult())
				strcat(newURL, "&t=1");	// erotica
			else
				if (mpItem->NoBidAndListForMinor())
					strcat(newURL, "&t=2");	// firearms

			// Just in case the redirect doesn't work, tell user where to go
			*mpStream << "<p>Click <b>refresh</b> or <b>reload</b> button on your browser now.";

			CleanUp();

			// redirect to adult sign-in page
			pThis->EbayRedirect(pCtxt, newURL);
			lLength = strlen(newURL);
			// pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, newURL, &lLength, NULL);
			return;
		}
	}

	// Let's see if the bid amout makes sense
	if (strlen(pMaxBid) > maxSize)
	{
		*mpStream <<	"<h2>"
						"Problem with bid amount"
						"</h2>"
						"Sorry, your bid amount of "
				 <<		pMaxBid
				 <<		" is too large. Please go back and "
				 <<		"check it again."
				 <<		"<br>"
				 <<		mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	maxBid	= atof(pMaxBid);

	if (maxBid > mpMarketPlace->GetMaxAmount(mpItem->GetCurrencyId()))
	{
		*mpStream <<	"<h2>"
						"Problem with bid amount"
						"</h2>"
						"Sorry, your bid amount of ";

		clsCurrencyWidget currencyWidget(mpMarketPlace, mpItem->GetCurrencyId(), maxBid);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	" is too large. Please go back and "
				 <<		"check it again."
				 <<		"<br>"
				 <<		mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Quantity
	if (quantity < 1)
	{
		*mpStream <<	"<h2>Problem with quantity</h2>"
						"Sorry, your quantity of "
				  <<	quantity
				  <<	" is not valid. Please go back and "
						"check it again."
						"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		
		return;
	}

	if (quantity > mpItem->GetQuantity() ||
		quantity > EBAY_MAX_QUANTITY_AMOUNT)
	{
		*mpStream <<	"<h2>Problem with quantity</h2>"
						"Sorry, your quantity of "
				  <<	quantity
				  <<	" is too large. Please go back and "
						"check it again."
						"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		
		return;
	}

	if (!mpMarketPlace->UserCanBid(mpUser, mpStream))
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	} 


	// Build a bid object and let AcceptBid do the work
	// Build a bid object
	pBid	= new clsBid((time_t) 0,
						 BID_BID,
						 mpUser->GetId(),
						 maxBid,
						 quantity,
						 NULL); 

	// ProposeBid does alll the work
	pResult	= mpItem->AcceptBid(pBid,
					 		    mpStream,
								mpUser);

	
		
	if (pResult->mBidAccepted)
	{
	// if this bidder became the high bidder, update some things in mpItem that clseBayItemDetailWidget will need.
		//  this is necessary because when the item was retrieved from the database,
		//  the high bidder at that time is not necessarily still the high bidder.
		if (!pResult->mOutBid)
		{
			// mpUser will delete the Feedback object
			mpItem->SetHighBidderFeedbackScore(mpUser->GetFeedback() ? mpUser->GetFeedback()->GetScore() : 0);
			mpItem->SetHighBidderUserState(mpUser->GetUserState());
			mpItem->SetHighBidderUserFlags(mpUser->GetUserFlags());
			mpItem->SetHighBidderIdLastModified(mpUser->GetUserIdLastModified());
			mpItem->SetHighBidderEmail(mpUser->GetEmail());
			mpItem->SetHighBidderUserId(mpUser->GetUserId());
		}

		idw = new clseBayItemDetailWidget(mpMarketPlace);
		idw->SetItem(mpItem);
		idw->SetColor("#99CCCC");
		idw->SetMode(clseBayItemDetailWidget::Generic);
		idw->EmitHTML(mpStream);
		delete idw;
	}

	*mpStream <<	mpMarketPlace->GetFooter();

	// Clean up!
	delete	pBid;
	delete	pResult;

	CleanUp();

	return;
}

//
// RetractBid
//
//	Retracts a bid for a user
//
void clseBayApp::RetractBid(CEBayISAPIExtension *pThis,
							   int item,
							   char *pUserId,
							   char *pPass,
							   char *pReason)
{
	const struct tm	*pTimeAsTm;
	char			cEndDate[16];
	char			cEndTime[32];
	char			*cleanTitle = NULL;

	time_t			curtime;
	time_t			endtime;
	clseBayTimeWidget	timeWidget (mpMarketPlace, 1, 2);	// petra

	clsBid			*pBid;

	SetUp();

	// Get the item and check it
	if (!GetAndCheckItem(item))
	{
		CleanUp();
		return;
	}

	// If we've gotten this far, we can use the boilerplate
	// Title and header
	// We'll need a title here
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Bid Cancellation on item "
			  <<	item
			  <<	" - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Let's see if the user's legitimate. Note that
	// this doesn't tell us if they've bid on the item
	// or anything, just if they're legit.
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// See if the auction is closed. 
	curtime	= time(0);
	if (curtime > mpItem->GetEndTime())
	{
		endtime		= mpItem->GetEndTime();
		timeWidget.SetDateTimeFormat (1, -1);	// petra
		timeWidget.SetTime (endtime);			// petra
		pTimeAsTm	= localtime(&endtime);
		if (pTimeAsTm)
		{
// petra			strftime(cEndDate, sizeof(cEndDate),
// petra					 "%m/%d/%y",
// petra					 pTimeAsTm);
			timeWidget.EmitString (cEndDate);	// petra

// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%H:%M:%S %z",
// petra					pTimeAsTm);
			timeWidget.SetDateTimeFormat (-1, 2);	// petra
			timeWidget.EmitString (cEndTime);		// petra
		}
		else
		{
			strcpy(cEndDate, "*Error*");
			strcpy(cEndTime, "*Error*");
		}

		*mpStream <<	"<h3>Bidding has closed</h3>"
						"Sorry, bidding for this item was closed for bidding "
						"on "
				  <<	cEndDate
				  <<	" at "
				  <<	cEndTime
				  <<	". "
				  <<	"Bids cannot be retracted after the close of an auction."
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// Ok, now let's get the user's high bid for the item.
	// The real purpose here is to see if the user has
	// ever bid on this item.
	pBid	= mpItem->GetHighBidForUser(mpUser->GetId());
	//clean up title 
	cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());

	if (!pBid)
	{
		*mpStream <<	"<h3>No Bids to Retract!</h3>"
						"Our records show that you have no outstanding "
						"bid on item "
				  <<	cleanTitle
				  <<	" (item #"
				  <<	mpItem->GetId()
				  <<	")."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();

		delete [] cleanTitle;
		CleanUp();
		return;
	}


	// Retract all the existig bids for the user. 
	mpItem->RetractBids(mpUser->GetId());

	// Now, add the "retraction" bid.
	delete	pBid;
	pBid	= new clsBid(time(0),
						 BID_RETRACTION,
						 mpUser->GetId(),
						 0,
						 0,
						 pReason);

	mpItem->Bid(pBid);

	// Recompute the item's price - done in RetractBids
//	mpItem->AdjustPrice();

	// Now tell the user that we're all done.
	*mpStream <<	"\n"
					"<h2>Bid retracted</h2>"
					"Every bid you have placed on "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageViewItem)
			  <<	"eBayISAPI.dll?ViewItem"
					"?item="
			  <<	mpItem->GetId()
			  <<	"\""
					">"
					"item "
			  <<	mpItem->GetId()
			  <<	"</a>"
					" has been cancelled, and your explanation has been "
					"recorded."
			  <<	mpMarketPlace->GetFooter();


	delete pBid;

	CleanUp();

	return;
}

//
// CancelBid
//
//	Cancels a bid for a user
//
void clseBayApp::CancelBid(CEBayISAPIExtension *pThis,
							   char *pSellerUserId,
							   char *pSellerPass,
							   int item,
							   char *pUserId,
							   char *pReason)
{
	// A user object for the high bidder
	clsUser			*pUser	= NULL;
	clsBid			*pBid	= NULL;

	time_t			curtime;
	time_t			endtime;
	const struct tm	*pTimeAsTm;
	char			cEndDate[16];
	char			cEndTime[32];
	clseBayTimeWidget timeWidget (mpMarketPlace, 0, 0);	// petra
	char	*cleanTitle = NULL;

	SetUp();

	// Get the item and check it
	if (!GetAndCheckItem(item))
	{
		CleanUp();
		return;
	}

	// If we've gotten this far, we can use the boilerplate
	// Title and header
	// We'll need a title here
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Bid Cancellation on item "
			  <<	item
			  <<	" - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Let's see if the Seller's Legitimate
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pSellerUserId,
												  pSellerPass,
												  mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// GetAndCheckItem gets the item in such a way
	// that the seller userid is populated. Let's 
	// see if this item belongs to this user before
	// going any furthur
	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<H2>"
				  <<	pSellerUserId
				  <<	" is not the seller for item "
				  <<	item
				  <<	"</H2>"
						"<p>"
						"Only the seller is allowed to cancel a bid on an auction. "
						"If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}


	// See if the auction is closed. 
	curtime	= time(0);
	if (curtime > mpItem->GetEndTime())
	{
		endtime		= mpItem->GetEndTime();
		timeWidget.SetDateTimeFormat (1, -1);	// petra
		timeWidget.SetTime (endtime);			// petra
		pTimeAsTm	= localtime(&endtime);
		if (pTimeAsTm)
		{
// petra			strftime(cEndDate, sizeof(cEndDate),
// petra					 "%m/%d/%y",
// petra					 pTimeAsTm);
			timeWidget.EmitString (cEndDate);	// petra

// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%H:%M:%S %z",
// petra					pTimeAsTm);
			timeWidget.SetDateTimeFormat (-1, 2);	// petra
			timeWidget.EmitString (cEndTime);		// petra
		}
		else
		{
			strcpy(cEndDate, "*Error*");
			strcpy(cEndTime, "*Error*");
		}

		*mpStream <<	"<h3>Bidding has closed</h3>"
						"Sorry, bidding for this item was closed for bidding "
						"on "
				  <<	cEndDate
				  <<	" at "
				  <<	cEndTime
				  <<	". "
				  <<	"Bids cannot be cancelled after the close of an auction."
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// Ok, now let's get the user's high bid for the item.
	// The real purpose here is to see if the user has
	// ever bid on this item.
	pUser	= mpUsers->GetUser(pUserId);

	if (!pUser)
	{
		*mpStream <<	"<h3>Invalid User</h3>"
				  <<	"\""
				  <<	pUserId
				  <<	"\"" 
						" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" user"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (pUser)
		pBid	= mpItem->GetHighBidForUser(pUser->GetId());
	//clean up title
	cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());

	if (!pBid)
	{
		*mpStream <<	"<h3>No Bids to Cancel!</h3>"
						"Our records show that user "
				  <<	pUserId
				  <<	" has no outstanding "
						"bids on item \'"
				  <<	cleanTitle
				  <<	"\' (item #"
				  <<	mpItem->GetId()
				  <<	")."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();
		
		delete [] cleanTitle;
		delete pUser;

		CleanUp();
		return;
	}


	// Retract all the existig bids for the user. 
	mpItem->CancelBids(pUser->GetId());

	// Now, add the "retraction" bid.
	delete	pBid;
	pBid	= new clsBid(time(0),
						 BID_CANCELLED,
						 pUser->GetId(),
						 0,
						 0,
						 pReason);

	mpItem->Bid(pBid);

	// Recompute the item's price - called in cancel bids
//	mpItem->AdjustPrice();

	// Now tell the user that we're all done.
	*mpStream <<	"\n"
					"<h2>Bid cancelled</h2>"
					"Every bid placed on "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageViewItem)
			  <<	"eBayISAPI.dll?ViewItem"
					"&item="
			  <<	mpItem->GetId()
			  <<	"\""
					">"
					"item "
			  <<	mpItem->GetId()
			  <<	"</a>"
					" by user "
			  <<	pUserId
			  <<	" has been cancelled, and your explanantion has been "
					"recorded."
			  <<	mpMarketPlace->GetFooter();


	delete pBid;
	delete pUser;
	CleanUp();

	return;
}

//
// ViewBids
//		These routines are all associated with 


