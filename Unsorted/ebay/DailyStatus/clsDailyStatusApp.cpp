/*	$Id: clsDailyStatusApp.cpp,v 1.9.240.2 1999/08/06 02:26:53 nsacco Exp $	*/
//
//	File:	clsDailyStatusApp.cpp
//
//	Class:	clsDailyStatusApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//		Include Dutch Auction high bidders
//		07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsDailyStatusApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAnnouncements.h"
#include "clsAnnouncement.h"
#include "clsUtilities.h"
#include "clsMail.h"
#include "clsCurrencyWidget.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif

//#ifdef _WIN32
//FILE *popen(const char *, const char *);
//int pclose(FILE *);
//#endif

// 
// This little class describes the things a 
// given user is buying and selling.
//
class clsUserListAndBid
{
	public:
		clsUserListAndBid() :
			mId(0),
			mListCount(0),
			mpListed(NULL),
			mBidCount(0),
			mpBid(NULL) 
		{ };

		clsUserListAndBid(int userId) :
			mId(userId),
			mListCount(0),
			mpListed(NULL),
			mBidCount(0),
			mpBid(NULL) 
		{ };

		void AddListing(clsItem *pItem)
		{
			clsItem	**pNewList;

			pNewList	= new clsItem*[mListCount + 1];
			memcpy(pNewList, mpListed, 
				   sizeof(clsItem *) * mListCount);
			*(pNewList + mListCount)	= pItem;
			mListCount++;

			delete	mpListed;
			mpListed	= pNewList;
			return;
		}

		void AddBid(clsItem *pItem)
		{
			clsItem	**pNewList;

			pNewList	= new clsItem*[mBidCount + 1];
			memcpy(pNewList, mpBid, 
				   sizeof(clsItem *) * mBidCount);
			*(pNewList + mBidCount)	= pItem;
			mBidCount++;

			delete	mpBid;
			mpBid	= pNewList;
			return;
		}

		int			mId;		// User's id;
		int			mListCount;	// Number of items listed
		clsItem		**mpListed;	// Array of listed items
		int			mBidCount;	// Number of items bid on
		clsItem		**mpBid;		// Array of bid items
};

#if 0
// eqint is used by the hash table of clsUserListAndBid(s)
struct eqint
{
        bool operator()(const int s1, const int s2) const
        {
                return s1 == s2;
        }
};
#endif

clsDailyStatusApp::clsDailyStatusApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	mpAnnouncements = (clsAnnouncements *)0;
	return;
}


clsDailyStatusApp::~clsDailyStatusApp()
{
	return;
};

//
// DailyStatusFileName
//
//	*** NOTE ***
//	Should this be mpMarketPlace->GetDailyStatusFileName()?
//	*** NOTE ***
//
// static const char *DailyStatusFileName	= "newdailystatusinfo.txt";

void clsDailyStatusApp::EmitItemBlurb(ostrstream *pM, clsItem *pItem)
{
	time_t					itemEndTime;
//	struct tm				*pItemEndTimeTM;
	char					cItemEndTime[32];

	itemEndTime		= pItem->GetEndTime();
//	pItemEndTimeTM	= localtime(&itemEndTime);
//	strftime(cItemEndTime, sizeof(cItemEndTime), "%m/%d/%y %H:%M:%Y PST",
//      pItemEndTimeTM);
	clsUtilities::GetDateTime(itemEndTime, &cItemEndTime[0]);

	*pM <<		pItem->GetId()
		  <<	":  "
		  <<	pItem->GetTitle()
		  <<	"\n";
	if (pItem->GetBidCount() > 0)
	{
		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetPrice());


		*pM << "Current bid:       ";
		
		currencyWidget.EmitHTML(pM);

		*pM << "\n";
	}
	else
	{
		clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetStartPrice());


		*pM << "Bidding starts at: ";
		
		currencyWidget.EmitHTML(pM);

		*pM << "\n";
	};
	
	*pM		<< "Auction ends on:    "
		<< cItemEndTime;
	*pM << "\n";

	// url for item?
	*pM	<< mpMarketPlace->GetCGIPath();
	*pM << "eBayISAPI.dll?ViewItem&item="
		<< pItem->GetId();
	*pM	  <<	"\n\n";
	return;
};


void clsDailyStatusApp::EmitUserStatus(clsUserListAndBid *pUserStatus, 
									   	FILE			*pDailyStatusLog,
										time_t			currentTime)
{
		// The stream we'll build the notice in
//	strstream				*pM			= NULL;
	clsUser	*pUser;
	clsItem	*pItem;
	int		i;

// time stuff
	struct tm				*pCurrentTimeTM;
	char					cCurrentTime[32];
// petra - unused	char					cCurrentDateRFC802[128];

//	char					*pTheNotice;
	//
	// Mailer
	//
//	char					mailCommand[64 + EBAY_MAX_USERID_SIZE + 1];
//	FILE					*pPipe;
//	int						mailRc;
	clsAnnouncement			*pAnnouncement;

	clsMail		*pMail;
	ostrstream	*pM;
	char		subject[512];
	char		**recipients;
	char		tinimail[14];

	char		*pSafeText;

	//
	// Reformat dates
	//
//	time(&currentTime);
	pCurrentTimeTM	= localtime(&currentTime);
//	strftime(cCurrentTime, sizeof(cCurrentTime), "%m/%d/%y %H:%M:%Y PST",
//             pCurrentTimeTM);
	clsUtilities::GetDateTime(currentTime, &cCurrentTime[0]);
// petra - unused	strftime(cCurrentDateRFC802, sizeof(cCurrentDateRFC802),
// petra				"%a, %d %b %Y %H:%M:%S %z",
// petra				pCurrentTimeTM);

	pMail	= new clsMail();

	pM	= pMail->OpenStream();
//	pM	= new strstream();

	// prepare the stream
	pM->setf(ios::fixed, ios::floatfield);
	pM->setf(ios::showpoint, 1);
	pM->precision(2);

	// Ok, we'll need the user
	pUser	= mpUsers->GetUser(pUserStatus->mId);
	if (!pUser)
	{
		fprintf(pDailyStatusLog, "** Error ** Could NOT get user %d\n",
				pUserStatus->mId);
		return;
	}

	if (pUser->GetEmail() == '\0')
	{
		fprintf(pDailyStatusLog, "** Error ** Could NOT get email for %d\n",
				pUserStatus->mId);
		return;
	}

	// Nice headers
//	*pM <<	"To: "
//			<<	pUser->GetEmail();

//	*pM <<	"\n"
//			<<	"From: "
//			<<	mpMarketPlace->GetConfirmEmail()
//			<<	"\n"
//			<<	"Subject: "
//			<<	mpMarketPlace->GetCurrentPartnerName();
//	*pM <<  " Daily Status as of "
//			<<  cCurrentTime
//			<<  "\n"
//			<<	"Date: "
//			<<	cCurrentDateRFC802
//			<<	"\n"
//			<<	"Precedence: bulk"
//			<<	"\n"
//			<<	"\n";

	*pM <<	"Dear "
		<<	pUser->GetUserId();
	*pM	<< "\n";


	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header, 
													mpMarketPlace->GetCurrentPartnerId(),
													mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pM << pSafeText;
		*pM << "\n";
		delete pAnnouncement;
		delete pSafeText;
	};

	// emit daily status announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(DailyStatus,Header,
													mpMarketPlace->GetCurrentPartnerId(),
													mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pM << pSafeText;
		*pM << "\n";
		delete pAnnouncement;
		delete pSafeText;
	};

// "------ Begin Status ------\n"
	*pM << "\nAll information is current as of "
		<<  cCurrentTime
		<< "\n"
		<< "Please visit "
		<< mpMarketPlace->GetCurrentPartnerName();
	*pM	<< " for the latest information. \n\n";

	// Emit Seller things...
	if (pUserStatus->mListCount > 0)
	{
		*pM << "You have the following auctions underway:\n\n";

		for (i = 0;
			 i < pUserStatus->mListCount;
			 i++)
		{
		 	pItem	= pUserStatus->mpListed[i];
			EmitItemBlurb(pM, pItem);
		}

		*pM << "Keep in mind that you are committing to selling these items at\n"
			<< mpMarketPlace->GetCurrentPartnerName();
		*pM << ". Refusing to sell these items to the high bidder at the end\n"
			<< "of your auction will cause you to lose your registered status. \n"
			<< "\n";

		 *pM << "-------------\n";
	 };


	// Emit Seller things...
	if (pUserStatus->mBidCount > 0)
	{
		
		*pM << "You are a high bidder on the following auctions:\n\n";
		
		// Emit High Bidder things...
		for (i = 0;
			 i < pUserStatus->mBidCount;
			 i++)
		{
			pItem	= pUserStatus->mpBid[i];
			EmitItemBlurb(pM, pItem);
			 }
	};

	// Allll done
	*pM << "------ End Status ------\n";

	*pM	<<	mpMarketPlace->GetThankYouText()
		<<	" If you have not already done so\n"
			"today, it wouldn't hurt to mention "
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" to a few of your friends!\n"
			"\n";

	// emit general footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
													mpMarketPlace->GetCurrentPartnerId(),
													mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pM << pSafeText;
		*pM << "\n";
		delete pAnnouncement;
		delete pSafeText;
	};

	// emit end of auction footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(DailyStatus,Footer,
													mpMarketPlace->GetCurrentPartnerId(),
													mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pSafeText = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pM << pSafeText;
		*pM << "\n";
		delete pAnnouncement;
		delete pSafeText;
	};

	*pM	<<	endl;

	// Print it!
//	pTheNotice	= pM->str();
//	pTheNotice[pM->pcount()]='\0';

//	sprintf(mailCommand,
//				 "/usr/lib/sendmail -odq -f %s -F \'eBay Billing\' \'%s\'",
//				 mpMarketPlace->GetConfirmEmail(),
//					pUser->GetEmail());
//	pPipe	= popen(mailCommand, "w");
//	fprintf(pPipe, "%s", pTheNotice);
//	mailRc	= pclose(pPipe);

	// Let's clean and free that buffer
//	memset(pTheNotice, 0x00, pM->pcount());
//	delete	pTheNotice;

    if (pUser->SendDailyStatus()) { 

		sprintf(subject,
				"%s Daily Status as of %s",
				mpMarketPlace->GetCurrentPartnerName(),
				cCurrentTime);

		// temporary hack to see what's wrong with this guy's email

		recipients = new char *[2];
		sprintf(tinimail, "tini@ebay.com");
		recipients[0] = tinimail;
		recipients[1] = NULL;

		/*	if ((pUser->GetId() == 78549) || (pUser->GetId() == 400708))
				pMail->Send(pUser->GetEmail(),
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject,
					recipients);
		else
		*/
			pMail->Send(pUser->GetEmail(), 
					(char *)mpMarketPlace->GetConfirmEmail(),
					subject);


		fprintf(pDailyStatusLog, "Mail sent to %s\n",
				pUser->GetEmail());
	}


	delete	pMail;

	// Now, scotch the stream
//	delete	pM;
//	pM	= NULL;

/*	if (mailRc != 0)
	{
		fprintf(pDailyStatusLog,"** Error! Sendmail returned %d mailing to %s.\n",
			   mailRc, pUser->GetEmail());
		fprintf(pDailyStatusLog,"** Command <%s>\n", mailCommand);

	}
	else
		fprintf(pDailyStatusLog, "Mail sent to %s\n",
			pUser->GetEmail());
*/
	return;

}


void clsDailyStatusApp::Run()
{
	// This is the great mother vector of items
	ItemVector				vItems;

	// And it's iterator
	ItemVector::iterator	i;

	BidVector				*pvBids;
	BidVector::iterator		bvi;

	// This is the great father hash of users
	hash_map<const int, clsUserListAndBid *, hash<int>, eqint>
            hListAndBid;

	hash_map<const int, clsUserListAndBid *, hash<int>, eqint>::
		const_iterator ii;

	int					sellerId;
	int					highBidderId;
	clsUserListAndBid	*pListAndBid;

	// Reformatted dates

	time_t					currentTime;

	// This is the 
	// File stuff
	FILE			*pDailyStatusLog;

	// File shenanigans
	pDailyStatusLog	= fopen("dailystatus.log", "a+");

	if (!pDailyStatusLog)
	{
		fprintf(stderr,"%s:%d Unable to open daily status log. \n",
			  __FILE__, __LINE__);
	}

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	if (!mpAnnouncements)
		mpAnnouncements = mpMarketPlace->GetAnnouncements();
	
	// First, let's get the items
	mpItems->GetActiveItems(&vItems);
	// TODO - time widget?
	//
	// Reformat dates
	//
	time(&currentTime);

        time_t          nowtime = time(0);
        struct tm*      LocalTime = localtime(&nowtime);

	fprintf(pDailyStatusLog,
          "%2d/%2d/%2d %2d:%2d:%2d\t Done getting all active items.\n",
                LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
                LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// Now, we loop through them
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	{
		// First, we see if we've seen the seller before
		sellerId	= (*i)->GetSeller();
		ii	= hListAndBid.find(sellerId);

		// If not, let's create an object for them
		if (ii	== hListAndBid.end())
		{
			pListAndBid	= new clsUserListAndBid(sellerId);
			hListAndBid[(const int)sellerId]	= pListAndBid;
		}
		else
			pListAndBid	= (*ii).second;

		// Add this item to the user's collection of listings
		pListAndBid->AddListing(*i);

		// Now, get the high bidder(s) for the item 
		// *** NOTE ***
		// Don't send announcements for Dutch auctions

		if ((*i)->GetAuctionType() == AuctionDutch)
		{
			// get list of dutch high bidders for the item
			pvBids = new BidVector;
			(*i)->GetDutchHighBidders(pvBids);

			// iterate and put in hListAndBid
			for (bvi = pvBids->begin();
				 bvi != pvBids->end();
				 bvi++)
			{
					 // should be abstracted out;
					 // same as chinese
				highBidderId	= (*bvi)->mUser;
			
				// See if we've seen them...
				ii	= hListAndBid.find(highBidderId);
			
				// If not, make them
				if (ii == hListAndBid.end())
				{
					pListAndBid	= new clsUserListAndBid(highBidderId);
					hListAndBid[highBidderId]	= pListAndBid;
				}
				else
					pListAndBid	= (*ii).second;

				// And add this as a high bid item
				pListAndBid->AddBid(*i);
			}
		}

		if ((*i)->GetAuctionType() == AuctionChinese)
		{
			if ((*i)->GetBidCount() > 0 &&
				(*i)->GetPrice() > 0)
			{
				highBidderId	= (*i)->GetHighBidder();
			
				// See if we've seen them...
				ii	= hListAndBid.find(highBidderId);
			
				// If not, make them
				if (ii == hListAndBid.end())
				{
					pListAndBid	= new clsUserListAndBid(highBidderId);
					hListAndBid[highBidderId]	= pListAndBid;
				}
				else
					pListAndBid	= (*ii).second;

				// And add this as a high bid item
				pListAndBid->AddBid(*i);
			}
		}
	}

        nowtime = time(0);
        LocalTime = localtime(&nowtime);

	fprintf(pDailyStatusLog,
          "%2d/%2d/%2d %2d:%2d:%2d\t Done sorting all active items.\n",
                LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
                LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// Welllll, now we're done. Let's go thruough the hash
	// now and emit the users
	for (ii	= hListAndBid.begin();
		 ii != hListAndBid.end();
		 ii++)
	{
		EmitUserStatus((*ii).second, pDailyStatusLog, currentTime);
	}
}

static clsDailyStatusApp *pTestApp = NULL;

int main()
{

	if (!pTestApp)
	{
		pTestApp	= new clsDailyStatusApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
