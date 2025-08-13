/*	$Id: clseBayAppAdminReinstateAuction.cpp,v 1.2.2.1.34.2 1999/08/05 20:42:02 nsacco Exp $	*/
//
//	File:		clseBayAppAdminReinstateAuction.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include <time.h>
#include "clseBayTimeWidget.h"

static const int kSecondsPerSevenDays = 7 * 24 * 60 * 60;

static const char ReinstateAuction[] =
"Blocked Item Reinstated:";

void clseBayApp::AdminReinstateAuction(int action,
									   char *pUserId,
									   char *pPass,
								       char *pItemNo,
								       int type,
								       char *pText,
								       char *pEmailSubject,
								       char *pEmailText,
								       eBayISAPIAuthEnum authLevel)
{
	clsUser		*pUser	= NULL;
	clsItem		*pItem	= NULL;

	// For mailing the seller
	clsMail		*pMail;
	ostrstream	*pMailStream;


	char		*pUserInfoText;
	char		*pTextWithUserInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;
	clsAccount	*pAccount;

//	TimeZoneEnum		timeZone;
//	time_t				endTime = 0;
	clsAnnouncement *	pAnnouncement = NULL;
	char *				pTemp;

// petra	clseBayTimeWidget	endTimeWidget;
// petra	TimeZoneEnum		timeZone;

// petra	char		cEndDate[32];
// petra	char		cEndTime[32];

	char		cEmailSubject[512];
	int			mailRc;

	double		dPrice = 0.0;
	double		dStartPrice = 0.0;
	double		dReserve = 0.0;
	int			nQuantity = 1;

	// check for free gallery listing promotion period
	bool freeGallery = false;

	//Make sure we have an action that is valid
	if ((action < 0) || (action > 1 ))
	{
		//Set a default
		action = 0;
	}

	// Check if we just want to add the eNote that the appeal was denied
	if (action == 1)
	{
		AdminAppealeNote(pUserId, pPass, pItemNo, type,	pText, authLevel);

		return;
	}
	
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Reinstatement of Item "
			  <<	pItemNo
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	// Check the input
	if (!ValidateReinstateAuctionInput(pUserId, pPass, pItemNo, type, pText))
	{
		*mpStream <<	"<p>";
		ReinstateAuctionShow(0, pUserId, pPass, pItemNo, type, pText);	//LL: Fix
		CleanUp();

		return;
	}

	// All is well! Let's get the item!
	// Be sure to get the description as well so we can move it to
	// the proper table!!!  (mila 4/27/99)
	pItem = mpItems->GetItem(atoi(pItemNo), true, NULL, 0, true);
	if (pItem == NULL)
	{
		*mpStream <<	"<p>";
		ReinstateAuctionShow(action, pUserId, pPass, pItemNo, type, pText);
		CleanUp();

		return;
	}

	pUser = mpUsers->GetUser(pItem->GetSeller());
	if (pUser == NULL)
	{
		*mpStream <<	"<p>";
		ReinstateAuctionShow(action, pUserId, pPass, pItemNo, type, pText);
		CleanUp();

		return;
	}


	if (!pUser->IsConfirmed())
	{
		*mpStream <<	"<p>";
						"Seller is not confirmed.";
		CleanUp();

		return;
	}

	nowTime	= time(0);

	// Before we add the item, we have to set the new times.  We default
	// to a 7-day auction.
	pItem->SetStartTime(nowTime);
	pItem->SetEndTime(nowTime + kSecondsPerSevenDays);

	//
	//	** NOTE **
	//	FREE GALLERY PERIOD (a gift to our users)
	//	** NOTE **
	if ((clsUtilities::CompareTimeToGivenDate(nowTime, 4, 20, 99, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(nowTime, 5, 2, 99, 23, 59, 59) <= 0))
		freeGallery = true;

	// Add the item to the active items table, then delete it from
	// the blocked items table
	mpItems->AddItem(pItem);
	mpItems->DeleteItem(pItem->GetId(), true);

	// Get the seller's account info
	pAccount = pUser->GetAccount();
	if (pAccount == NULL)
	{
		*mpStream <<	"<p>";
						"Seller has no account.";
		CleanUp();

		return;
	}

	// Add an entry so we get it picked up in the Gallery.
	if (pItem->GetGalleryType() == Gallery || pItem->GetGalleryType() == FeaturedGallery)
	{
		clsGalleryChangedItem item;
		
		item.mID = pItem->GetId();
		item.mSequenceID = gApp->GetDatabase()->GetNextGallerySequence();
		if (pItem->GetPictureURL() != NULL)
			strcpy(item.mURL, pItem->GetPictureURL());
		else
			strcpy(item.mURL, "nothing");
		item.mState = kGalleryNotProcessed;
		item.mStartTime = pItem->GetStartTime();
		item.mEndTime = pItem->GetEndTime();
		item.mAttempts = -1;
		item.mLastAttempt = time(NULL);

		bool appendResult = gApp->GetDatabase()->AppendGalleryChangedItem(item);
	}

	// Charge the appropriate fees to the seller's account
	pAccount->ChargeInsertionFee(pItem);

	// mila 4/26/99  copied from clseBayApp::AddNewItem
	// When UK site first launches, waive insertion fee
	// So, need the following to credit user's account
	// NOTE: this is just for promotional purpose

	if (pUser->GetCountryId() == Country_UK) // check if user is from UK
	{
		// check to see if this offer expires, which
		// will be on July 1st, 1999 0:00
		time_t	now;

		now = time(0);
		// compare if now is before the expiration date
		if (clsUtilities::CompareTimeToGivenDate(now, 
								7, 1, 99, 0, 0, 0) == -1)
		{
			pAccount->ApplyInsertionFeeCredit(pItem, 
							"Promotional Offer for UK sellers: No Insertion Fee!");
		}
	}

	if (pItem->GetBoldTitle())
		pAccount->ChargeBoldFee(pItem);

	if (pItem->GetFeatured())
		pAccount->ChargeCategoryFeaturedFee(pItem);

	if (pItem->GetSuperFeatured())
		pAccount->ChargeFeaturedFee(pItem);

	if (pItem->GetGiftIconType() >0 && pItem->GetGiftIconType() != 2 /*Rosie*/)
		pAccount->ChargeGiftIconFee(pItem);

	if (pItem->GetGalleryType() == FeaturedGallery)
	{
		pAccount->ChargeFeaturedGalleryFee(pItem);
	}

	if (pItem->GetGalleryType() == Gallery)
	{
		pAccount->ChargeGalleryFee(pItem);

		//promotion period for gallery 00:00:00 Feb-21-99
		if (freeGallery)
		{
			pAccount->CreditGalleryFee(pItem);
			//mark it, then we won't give user credit twice
			pItem->SetHasGalleryCredit(true);
		}
	}

	// Add an eNote
	pUserInfoText		= clsNote::GetUserInfo(0, pUser);
	pTextWithUserInfo	= new char[strlen(pUserInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithUserInfo, pUserInfoText);
	strcat(pTextWithUserInfo, "<br><br>");
	strcat(pTextWithUserInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),	// support
						  mpUser->GetId(),						// support person doing this
						  0,
						  pItem->GetId(),						// item
						  pUser->GetId(),						// seller
						  eClsNoteFromTypeAutoAdminPost,
						  type,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  (char *)ReinstateAuction,
						  pTextWithUserInfo);

	pNotes->AddNote(pNote);

	delete [] pTextWithUserInfo;  //aaw added []'s
	delete pUserInfoText;
 	delete pNote;


// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	endTimeWidget.SetTimeZone(timeZone);
// petra	endTimeWidget.BuildDateString(cEndDate);
// petra	endTimeWidget.BuildTimeString(cEndTime);
	
	pMail	= new clsMail;

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	// Now, the goods!
	*pMailStream <<	mpMarketPlace->GetCGIPath(PageViewItem);
	*pMailStream << "eBayISAPI.dll?ViewItem&item="
				<< pItem->GetId()
				 <<	"\n\n"
					"Dear "
				 <<	pUser->GetUserId()
				 <<	",\n"
					"\n";

	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit list notice announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ListNotice,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	dStartPrice = pItem->GetStartPrice();
	dReserve	= pItem->GetReservePrice();
	nQuantity = pItem->GetQuantity();

	// calculate the price of the item
	if (dStartPrice < dReserve)
	{
		// in case dutch with reserve is allowed as well
		dPrice = dReserve;
	}
	else
	{
		dPrice = dStartPrice * nQuantity;
	}

	*pMailStream <<	"This message is to confirm your "
				 <<	mpMarketPlace->GetCurrentPartnerName()
				 <<	" auction reinstatement for:"
					"\n"
					"\n"
				 <<	pItem->GetTitle()
				 <<	"\n"
					"\n"
					"Title of item:                   "
				 <<	pItem->GetTitle()
				 <<	"\n"
					"Minimum bid:                     ";

	clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetStartPrice());
// petra	currencyWidget.SetForMail(true);
	currencyWidget.EmitHTML(pMailStream);

	*pMailStream <<	"\n"
					"Reserve price (if any):          ";

	currencyWidget.SetNativeAmount(pItem->GetReservePrice());
	currencyWidget.EmitHTML(pMailStream);
					 
	*pMailStream <<	"\n"
				 <<	"Quantity:                        "
				 <<	pItem->GetQuantity()
				 <<	"\n"
				 <<	"Auction Ends on:                 ";
	clseBayTimeWidget endTimeWidget (mpMarketPlace, 
									 EBAY_TIMEWIDGET_MEDIUM_DATE, 
									 EBAY_TIMEWIDGET_NO_TIME, 
									 pItem->GetEndTime());	// petra
	endTimeWidget.EmitHTML (pMailStream);	// petra
// petra				 <<	cEndDate
	*pMailStream <<	" at ";					// petra
	endTimeWidget.SetDateTimeFormat (EBAY_TIMEWIDGET_NO_DATE, 
									 EBAY_TIMEWIDGET_LONG_TIME);	// petra
	endTimeWidget.EmitHTML (pMailStream);	// petra
// petra				 <<	cEndTime
	*pMailStream <<	"\n"
					"\n"
					"Your item number is:             "
				 <<	pItem->GetId()
				 <<	"\n"
					"\n"
					"Fees:"
					"\n"
					"Insertion Fee:                   ";

	currencyWidget.SetNativeAmount(pItem->GetInsertionFee(dPrice));
	currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
	currencyWidget.EmitHTML(pMailStream);

#if 0
	if (FreeRelisting)
	{
		if (SuperFreeRelisting)
			*pMailStream <<	" (Insertion Fee waived for temporary free relisting due to recent system outage!)";// ALEX for free relisting
		else
			*pMailStream <<	" (It will be refunded if the item is sold!)";
	}
	else
	{
		if (freeListing)
		{
			*pMailStream << " (Fee Refunded! eBay\'s 1998 Free Listing Day!)";
		}
	}
	*pMailStream <<	"\n";
#endif

	if (pItem->GetBoldTitle())
	{
		*pMailStream <<	"Boldfacing Fee:                  ";

		currencyWidget.SetNativeAmount(pItem->GetBoldFee(dPrice));
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (pItem->GetFeatured())
	{
		*pMailStream <<	"Featured Category Auction Fee:   ";

		currencyWidget.SetNativeAmount(pItem->GetCategoryFeaturedFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (pItem->GetSuperFeatured())
	{
		*pMailStream <<	"Featured Auction Fee:            ";

		currencyWidget.SetNativeAmount(pItem->GetFeaturedFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); 
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (pItem->GetGiftIconType() != GiftIconUnknown)
	{
		*pMailStream <<	"Great Gift Fee:            ";

		currencyWidget.SetNativeAmount(pItem->GetGiftIconFee(pItem->GetGiftIconType()));
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

//			*pMailStream <<	mpMarketPlace->GetGiftIconFee(pItem->GetGiftIconType());

		*pMailStream <<	"\n";
	}

	if (pItem->GetGalleryType() == Gallery)
	{
		*pMailStream <<	"Gallery Fee:                     ";

		currencyWidget.SetNativeAmount(pItem->GetGalleryFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (pItem->GetGalleryType()	== FeaturedGallery)
	{
		*pMailStream <<	"Featured Gallery Fee:         ";

		currencyWidget.SetNativeAmount(pItem->GetFeaturedGalleryFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	*pMailStream <<	"\n\n"
					"IMPORTANT: Keep these numbers. You will need your item "
					"number in any correspondence with "
				 <<	mpMarketPlace->GetCurrentPartnerName()
				 <<	"."
					"\n"
					"\n"
					"You can edit your item as long as no one has left a bid. \n"
					"Choose the \"Update Item\" option on the View Item page for this item.\n"  
					"If you need to add information or remove this listing,\n"
					"you can do so from the Seller Services menu.\n"
					"\n";

	// emit general footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit list notice footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ListNotice,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMailStream <<	mpMarketPlace->GetThankYouText()
				 <<	"\n"
					"\n"
				 <<	mpMarketPlace->GetHomeURL()
				 <<	"\n";

	if (pUser->SendList()) { 
		sprintf(cEmailSubject,
				"%s Listing reinstatement - Item %d: %s",
				mpMarketPlace->GetCurrentPartnerName(),
				pItem->GetId(),
				pItem->GetTitle());

		pMail->Send(pUser->GetEmail(), //AndyTon4
					"support@ebay.com",
					cEmailSubject,
					NULL,
					(char **)AutomatedSupportEmailBccList);

		delete pMail;

		if (!mailRc)
		{
			*mpStream <<	"<h2>Warning: Unable to send listing confirmation notice</h2>"
							"Sorry, we could not send you your listing confirmation "
							"notice via electronic mail. This is probably a temporary "
							"problem, but if it persists, or you are having problems sending "
							"or receiving mail, you may wish to contact your service provider. "
							"Since "
					  <<	mpMarketPlace->GetCurrentPartnerName()
					  <<	" cannot send this e-mail, this is the <b>only</b> confirmation "
							"you will receive for your listing."
							"<br>";
		}
	}

	// Indicate there's a note about this seller
	pUser->SetHasANote(true);

	*mpStream <<	"<font color=green>"
					"Item "
			  <<	pItemNo
			  <<	" Reinstated!"
					"</font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

// This files a quick eNote on a Blocked item that had it's appeal denied
void clseBayApp::AdminAppealeNote(char *pUserId, char *pPass,
									char *pItemNo, int type,
									char *pText, eBayISAPIAuthEnum authLevel)
{
	clsUser		*pSeller	= NULL;

	char		*pSellerInfoText;
	char		*pTextWithSellerInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;


    char		*pEmailSubject=NULL;	//LL: remove this.
	char		*pEmailText=NULL;

	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Reinstatement of Item "
			  <<	pItemNo
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	//
	// Let's make sure this user can do this!
	//
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	if (mpUser && strstr(mpUser->GetEmail(), "@ebay.com") == 0)
	{
		*mpStream <<	"<font color=red>Not Authorized! </font>"
						"You are not authorized to use this "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" function. ";
		
		CleanUp();

		return;
	}

	// Get the blocked item
	if (!GetAndCheckBlockedItem(pItemNo, NULL, 0))
	{
		*mpStream <<	"<p>";
		CleanUp();

		return;
	}

	// Get the seller of the blocked item
	pSeller = mpUsers->GetUser(mpItem->GetSeller());
	if (pSeller == NULL)
	{
		*mpStream <<	"<p>";
		CleanUp();

		return;
	}

	// Get time
	nowTime	= time(0);

	// Add an eNote
	pSellerInfoText		= clsNote::GetUserInfo(0, pSeller);
	pTextWithSellerInfo	= new char[strlen(pSellerInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithSellerInfo, pSellerInfoText);
	strcat(pTextWithSellerInfo, "<br><br>");
	strcat(pTextWithSellerInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),	// support
						  mpUser->GetId(),						// support person doing this
						  0,
						  mpItem->GetId(),						// item
						  pSeller->GetId(),						// seller
						  eClsNoteFromTypeAutoAdminPost,
						  type,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  "Blocked Item Appeal Denied",
						  pTextWithSellerInfo);

	pNotes->AddNote(pNote);

	// Indicate there's a note about this seller
	pSeller->SetHasANote(true);

	// 
	// Tell them it worked
	//
	*mpStream <<	"<font color=green size=+1>"
					"eNote added!"
					"</font>"
					"<br>"
					"<br>";

	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;
	
	// Do cleanup
	delete pTextWithSellerInfo;
	delete pSellerInfoText;
 	delete pNote;
	delete pSeller;

	CleanUp();

	return;
}

	