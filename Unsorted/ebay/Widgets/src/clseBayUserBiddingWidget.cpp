/*	$Id: clseBayUserBiddingWidget.cpp,v 1.9.2.13.4.1 1999/08/01 02:51:28 barry Exp $	*/
//
//	File:	clseBayUserBiddingWidget.cpp
//
//	Class:	clseBayUserBiddingWidget
//
//	Author:	Poon
//
//	Function:
//			Shows items that a user is bidding.
//
//			This is based on a clseBayTableWidget.
//
// Modifications:
//				- 11/10/97	Poon - Created
//				- 04/26/99	Bill - Added table footer to display total
//				- 07/01/99	nsacco - use GetPicsPath()
//
#include "widgets.h"
#include "clseBayUserBiddingWidget.h"

// nsacco 07/01/99
#define HotIconURL	"listings/browse-icon-hot.gif"
#define NewIconURL	"new.gif"
#define PicIconURL	"listings/browse-icon-pic.gif"

clseBayUserBiddingWidget::clseBayUserBiddingWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mpUser = NULL;
	mDaysSince = 7;
	mSortCode = SortItemsByEndTimeReverse;
	mURL[0] = '\0';
	mUserPassword[0] = '\0';
	mRestrictedAccess = false;
	mNeedToDeleteUser = false;
	mpBid = NULL;

	mTotalItem = 0;
	mTotalStartPrice = 0.0;
	mTotalPrice = 0.0;
	mTotalMyMaxPrice = 0.0;
	mTotalQuantity = 0;
	mTotalBidCount = 0;

	mWonTotalItem = 0;
	mWonTotalStartPrice = 0.0;
	mWonTotalPrice = 0.0;
	mWonTotalMyMaxPrice = 0.0;
	mWonTotalQuantity = 0;
	mWonTotalBidCount = 0;
}

clseBayUserBiddingWidget::~clseBayUserBiddingWidget()
{
	ItemList::iterator i;

	// delete all the items
	for (i=mvItems.begin(); i!=mvItems.end(); i++)
	{
		delete (*i).mpItem;
	}

	mvItems.erase(mvItems.begin(), mvItems.end());

	if (mNeedToDeleteUser) 
		delete mpUser;
	mpBid = NULL;

}

void clseBayUserBiddingWidget::SetParams(vector<char *> *pvArgs)
{
	int p;
	char *cArg;
	char cArgCopy[256];
	char *cName;
	char *cValue;
	bool handled = false;
	int x;

	// reverse through these so that deletions are safe.
	//  stop at 1, because we don't care about the tagname
	for (p=pvArgs->size()-1; p>=1; p--)
	{
		cArg = (*pvArgs)[p];
		handled = false;

		// separate the name from the value
		strncpy(cArgCopy, cArg, sizeof(cArgCopy)-1);
		cName = cArgCopy;
		cValue = strchr(cArgCopy, '=');
		if (cValue) 
		{
			cValue[0]='\0';		// lock in cName
			cValue++;			// set cValue
		}
		else
			cValue="";

		// remove start & end quotes if they were provided
		x = strlen(cValue);
		if ((x>1) && (cValue[0]=='\"' && cValue[x-1]=='\"'))
		{
			cValue[x-1]='\0';		// remove ending "
			cValue++;				// remove beginning "
		}

		// try to handle this parameter
		if ((!handled) && (strcmp("userid", cName)==0))
		{
			SetUser(mpMarketPlace->GetUsers()->GetUser(cValue, false, false));
			mNeedToDeleteUser = true;
			handled=true;
		}
		if ((!handled) && (strcmp("dayssince", cName)==0))
		{
			SetDaysSince(atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("sortcode", cName)==0))
		{
			SetSortCode((ItemListSortEnum)atoi(cValue));
			handled=true;
		}
		if ((!handled) && (strcmp("currenturl", cName)==0))
		{
			SetCurrentURL(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("password", cName)==0))
		{
			SetUserPassword(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("restrictedaccess", cName)==0))
		{
			SetRestrictedAccess(strcmp(cValue,"true")==0);
			handled=true;
		}

		// if this parameter was handled, remove (and delete the char*) it from the vector
		if (handled)
		{
			pvArgs->erase(pvArgs->begin()+p);	
			delete [] cArg;	// don't need the parameter anymore
		}
	}

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayTableWidget::SetParams(pvArgs);

}

// Get the items from the database
bool clseBayUserBiddingWidget::Initialize()
{
	// safety
	if (!mpMarketPlace) return false;

	// safety
	if (!mpUser) return false;

	// get 'em (false means don't get more stuff, true means include private)
	mpUser->GetBidItems(&mvItems, mDaysSince, false, mSortCode, true);

	// set the number of cells (each item will take 18 cells)
	mNumItems = mvItems.size() * 18;

	// set the number of columns appropriately so that rows stay in sync
	mNumCols = 9;

	return true;
}

// This will be called mNumItems times n=0..mNumItems-1
bool clseBayUserBiddingWidget::EmitCell(ostream *pStream, int n)
{
	ItemList::iterator		z;
	int						zz;

	int						cellKind;
	int						i;
	clsItem					*pItem;
	char					*fontBegin;
	char					*fontEnd;

// petra	time_t					theTime;
// petra	time_t					currentTime;
// petra	struct tm				*timeAsTm;
// petra	struct tm				*timeAsTm2;
// petra	char					ctheTime[96];
// petra	int						dst;
	char					*cleanTitle;
	//samuel au, 4/8/99
// petra	clseBayTimeWidget		theTimeWidget;
// petra	TimeZoneEnum			timeZone;
	clseBayTimeWidget		theTimeWidget (mpMarketPlace, -1, -1);	// petra
	//end

	int						days, hours, minutes;
	char					cDiffTime[96];
	time_t					diffTime;

	int						bidQuant;
	int						iconType;

	// figure out the kind of cell this is
	cellKind = n % 18;

	// get the item that we're dealing with
	i = n/18;

	//
	// ** NOTE ** 
	// YES, you see this. I don't know how else to do this.
	// ** NOTE **
	for (z = mvItems.begin(), zz = 0;
		 z != mvItems.end() && zz != i;
		 z++, zz++)
	{
		;
	}
	
	if (z == mvItems.end())
		return false;

	// get the item
	pItem = (*z).mpItem;

	// get the bidder's bid if item has changed
	//if ((cellKind==0) && (!mpBid))								// commented out 7/19/99 AlexP
		//mpBid = pItem->GetHighBidForUser(mpUser->GetId());		// commented out 7/19/99 AlexP


	if ((pItem->GetQuantity() <= 1))
	{
		// figure out color to use for prices (green means will win, red means won't win)
		//if ((pItem->GetHighBidder() == mpUser->GetId()) && (mpBid) && (RoundToCents(mpBid->mValue) >= 
										//RoundToCents(pItem->GetReservePrice())))	// commented out 7/19/99 AlexP
		if ((pItem->GetHighBidder() == mpUser->GetId())) 
		{
			fontBegin="<FONT size=\"2\" color=\"green\"><strong>";
			fontEnd="</strong></FONT>";
			mItemWon = true;
		}
		else
		{
			fontBegin="<FONT size=\"2\" color=\"red\">";
			fontEnd="</FONT>";
			mItemWon = false;
		}
	}
	else	// if dutch, or can't get mpBid, don't use color coding
	{
		fontBegin = "<FONT size=\"2\">";
		fontEnd = "</FONT>";
		mItemWon = false;
	}

	// delete the bid now that we're about to be done with this item
	if ((cellKind==17) && (mpBid))
	{
		delete mpBid;	// it was actually created above before the switch
		mpBid = NULL;
	}

	switch (cellKind)
	{
		// the title
		case 0:
			*pStream <<		"<TD width=\"100%\" colspan=\"9\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		"&nbsp;";
			*pStream <<		"<FONT size=\"3\">";
			*pStream <<		"<strong>";
			// new icon
			if (difftime(time(0), pItem->GetStartTime()) < ONE_DAY)
			{
				 *pStream	  << "<img height=11 width=28 alt=\"[NEW!]\" src=\""
							  << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
							  << NewIconURL
							  << "\">";
			}

			// hot icon
			if ((pItem->GetBidCount() > mpMarketPlace->GetHotItemCount()) &&
				(pItem->GetReservePrice() == 0))
			{
				*pStream	 << "<img height=14 width=16 alt=\"[HOT!]\" src=\""
							 << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
							 << HotIconURL
							 << "\">";
			}

			// clean the title
			cleanTitle = clsUtilities::StripHTML(pItem->GetTitle());
			if (!cleanTitle) return false;

			*pStream <<		"<a href="
							"\""
					 <<		mpMarketPlace->GetCGIPath(PageViewItem)
					 <<		"eBayISAPI.dll?ViewItem&item="
					 <<		pItem->GetId()
					 <<		"\">"
					 <<		cleanTitle
					 <<		"</a>"
					 <<		"\n";
			delete [] cleanTitle;

			// pic icon
			// this won't work until the SQL query is modified to get the picture_url
			if (pItem->GetPictureURL())
			{
				*pStream	 << "<img height=14 width=16 alt=\"[PIC]\" src=\""
							 << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
							 << PicIconURL
							 << "\">";
			}

			iconType = pItem->GetGiftIconType();
			if (iconType != GiftIconUnknown )
			{
				if (iconType == RosieIcon)
					*pStream <<  "<A HREF=\""
							 <<	mpMarketPlace->GetMembersPath()
							 <<	"aboutme/4allkids/\">";
				else
					*pStream <<  "<A HREF=\""
							<<	mpMarketPlace->GetHTMLPath()
							<<  "help/buyerguide/gift-icon.html\">";


				*pStream	<< "<img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"" //http://pics.ebay.com/aw"
							<<	mpMarketPlace->GetPicsPath()
							<<	mpMarketPlace->GetGiftIconImage(iconType)
							<<	"\"></a>";
			}
			*pStream <<		"</strong>";
			*pStream <<		"</FONT>";

			// indicate reserve status
			if (pItem->GetReservePrice() != 0)
			{
				// show state of reserve auction
				*pStream	<<	" &nbsp;"
							<<	((RoundToCents(pItem->GetPrice())
								>= RoundToCents(pItem->GetReservePrice())) ?
								"<font size=\"2\" color=\"green\">(reserve met)" : 
								"<font size=\"2\" color=\"red\">(reserve not yet met)")
							<<	"</font>";
			}

			*pStream <<		"</TD>\n";

			// hack to keep EmitHTML in sync
			mCurrentCell += 8;

			break;

		// these should never happen because the mCurrentCell+=7 will skip these
		case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8:
			break;

		// item #
		case 9:
			*pStream <<		"<TD width=\"12%\" align=\"center\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");	
			*pStream <<		"<FONT size=\"2\">";
			*pStream <<		"&nbsp;";
			*pStream <<		pItem->GetId();
			*pStream <<		"</FONT>";
			*pStream <<		"</TD>\n";

			mTotalItem++;
			if (mItemWon)
				mWonTotalItem++;

			break;

		// start price
		case 10:
			*pStream <<		"<TD width=\"12%\" align=\"right\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		fontBegin;
			if (pItem->GetStartPrice()>0)
			{
				clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetStartPrice());
				currencyWidget.EmitHTML(pStream);
			}
			else
				*pStream <<		"-";
			*pStream <<		" ";
			*pStream <<		fontEnd;
			*pStream <<		"</TD>\n";

			mTotalStartPrice += pItem->GetStartPrice();
			if (mItemWon)
				mWonTotalStartPrice += pItem->GetStartPrice();

			break;


		// current price
		case 11:
			*pStream <<		"<TD width=\"12%\" align=\"right\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		fontBegin;
			if (pItem->GetPrice()>0)
			{
				clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetPrice());
				currencyWidget.EmitHTML(pStream);
			}
			else
				*pStream <<		"-";
			*pStream <<		" ";
			*pStream <<		fontEnd;
			*pStream <<		"</TD>\n";

			mTotalPrice += pItem->GetPrice();
			if (mItemWon)
				mWonTotalPrice += pItem->GetPrice();

			break;

		//  my max bid
		case 12:
			*pStream <<		"<TD width=\"12%\" align=\"right\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		fontBegin;

			// get the highest bid by this user (use local cache)
			if ((mpBid) && (!mRestrictedAccess))
			{
				clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), RoundToCents(mpBid->mValue));
				currencyWidget.EmitHTML(pStream);
			}
			else
				*pStream << "n/a";

			*pStream <<		" ";
			*pStream <<		fontEnd;
			*pStream <<		"</TD>\n";

			if (mpBid && (!mRestrictedAccess))
			{
				mTotalMyMaxPrice += RoundToCents(mpBid->mValue);
				if (mItemWon)
					mWonTotalMyMaxPrice += RoundToCents(mpBid->mValue);
			}

			break;


		// quantity
		case 13:
			// get the highest bid by this user (use local cache)
			if (mpBid)
			{
				bidQuant = mpBid->mQuantity;
			}
			else
			{
				bidQuant = 0;
			}
			*pStream <<		"<TD width=\"9%\" align=\"center\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		fontBegin;

			if (bidQuant)
			{
				*pStream <<		bidQuant;
				*pStream <<		" of ";
				*pStream <<		pItem->GetQuantity();
			}
			else
			{
				*pStream <<		"n/a";
			}

			*pStream <<		fontEnd;
			*pStream <<		"</TD>\n";

			mTotalQuantity += bidQuant;
			if (mItemWon)
				mWonTotalQuantity += bidQuant;

			break;

		// bid count
		case 14:
			*pStream <<		"<TD width=\"6%\" align=\"center\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		fontBegin;
			if (pItem->GetBidCount()>0)
				*pStream <<		pItem->GetBidCount();
			else
				*pStream <<		"-";
			*pStream <<		fontEnd;
			*pStream <<		"</TD>\n";

			mTotalBidCount += pItem->GetBidCount();
			if (mItemWon)
				mWonTotalBidCount += pItem->GetBidCount();

			break;

		// start date
		case 15:
			// get the right time
// petra			theTime	= pItem->GetStartTime();

			// format the date only
// petra			timeAsTm2 = localtime(&theTime);
			//samuel au, 4/8/99
// petra			timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra			theTimeWidget.SetTime(theTime);
// petra			theTimeWidget.SetTimeZone(timeZone);
			//if (timeAsTm2)
			//	strftime(ctheTime, sizeof(ctheTime), "%m/%d", timeAsTm2);
			//else
			//	strcpy(ctheTime, "-");
// petra			theTimeWidget.BuildDateString(ctheTime);
			//end

			*pStream <<		"<TD width=\"8%\" align=\"center\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		"<FONT size=\"2\">";
			theTimeWidget.SetDateTimeFormat(1, -1);	// petra
			theTimeWidget.SetTime(pItem->GetStartTime());	// petra
			theTimeWidget.EmitHTML (pStream);	// petra
// petra			*pStream <<		ctheTime;
			*pStream <<		"</FONT>";
			*pStream <<		"</TD>\n";
			break;

		// end date
		case 16:
			// for determining if should show PST or PDT
// petra			currentTime = time(0);
// petra			timeAsTm = localtime(&currentTime);
// petra			dst = timeAsTm->tm_isdst;

			// get the right time
// petra			theTime	= pItem->GetEndTime();

			// format the date & time
// petra			timeAsTm2 = localtime(&theTime);
			//samuel au, 4/8/99
// petra			timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra			theTimeWidget.SetTime(theTime);
// petra			theTimeWidget.SetTimeZone(timeZone);
			//end
// petra			if ((timeAsTm) && (timeAsTm2))
// petra			{
// petra				if (dst)	// is current time daylight savings?
// petra				{
// petra					if (timeAsTm2->tm_isdst)
// petra						strftime(ctheTime, sizeof(ctheTime), "%m/%d %H:%M", timeAsTm2);
// petra					else
// petra						strftime(ctheTime, sizeof(ctheTime), "%m/%d %H:%M PST", timeAsTm2);
// petra				}
// petra				else					// current time is standard
// petra				{
// petra					if (timeAsTm2->tm_isdst)
// petra						strftime(ctheTime, sizeof(ctheTime), "%m/%d %H:%M PDT", timeAsTm2);
// petra					else
// petra						strftime(ctheTime, sizeof(ctheTime), "%m/%d %H:%M", timeAsTm2);
// petra				}
// petra			}
// petra			else
// petra			{
// petra				strcpy(ctheTime, "-");
// petra			}
			*pStream <<		"<TD width=\"15%\" align=\"center\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		"<FONT size=\"2\">";
			theTimeWidget.SetDateTimeFormat(1, 2);	// petra
			theTimeWidget.SetTime(pItem->GetEndTime());	// petra
			//samuel au, 4/8/99
			theTimeWidget.EmitHTML(pStream);
			//*pStream <<		ctheTime;
			//end
			*pStream <<		"</FONT>";
			*pStream <<		"</TD>\n";
			break;

		// Time Left
		case 17:
			*pStream <<		"<TD width=\"14%\" align=\"center\""
					 <<		(((i % 2) == 0) ? " bgcolor=\"#EFEFEF\">" : " bgcolor=\"#FFFFFF\">");
			*pStream <<		"<FONT size=\"2\">";

			// the time left
			diffTime = pItem->GetEndTime() - time(0);

			// if ended, say so
			if (diffTime < 0)
			{
				*pStream <<		"<strong>Ended</strong>";
			}
			else
			{
				// format the time
				days	= diffTime / 86400;
				hours	= (diffTime % 86400) / 3600;
				minutes	= (diffTime % 3600) / 60;
				sprintf(cDiffTime, "%dd&nbsp; %dh&nbsp; %dm", days, hours, minutes);
				*pStream <<		cDiffTime;
			}
			*pStream <<		"</FONT>";
			*pStream <<		"</TD>\n";
			break;
		default:
			break;
	}

	return true;
}

// Header of this widget
bool clseBayUserBiddingWidget::EmitPreTable(ostream *pStream)
{
// petra	struct tm *timeAsTm;
// petra	time_t currentTime;
// petra	char	*timeZone;
	char	*baseURL = NULL;
	char	detailLink[255];
	char	cgipath[128];
	//samuel au, 4/17/99
// petra	clseBayTimeWidget timeWidget;
// petra	bool    bst;
	//end
	strcpy(cgipath, mpMarketPlace->GetCGIPath(PageMyEbayBidder));

	// make link to bidding details
	sprintf(detailLink,"%sebayISAPI.dll?MyEbayBidder&userid=%s&pass=%s&sort=%d&dayssince=%d",
						cgipath, mpUser->GetUserId(), 
						mUserPassword, mSortCode, mDaysSince);

	// determine if should show PST or PDT
// petra	currentTime = time(0);

	//samuel au, 4/17/99
	// call clseBayTimeWidget.isBST() before this function calls localtime
// petra	timeWidget.SetTime(currentTime);
// petra	bst = timeWidget.IsBST();
// petra	timeAsTm = localtime(&currentTime);
// petra	if (timeAsTm->tm_isdst)	timeZone = "PDT";
// petra		else timeZone = "PST";
	//samuel au, 4/8/99
	// for now, we just hardcode the time zone to be "GMT" or "BST"
// petra#ifdef UK_ONLY
// petra	if (bst)
// petra		timeZone = "BST";
// petra	else
// petra		timeZone = "GMT";
//petra#endif
	//end

	// create table properties with or without the bgcolor attribute
	if (mColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"border=\"1\" "
				 <<		"cellpadding=\"0\" "
				 <<		"cellspacing=\"0\" "
				 <<		"width=\""
				 <<		mTableWidth
				 <<		"%\""
				 <<		">"
				 <<		"\n";
	}
	else
	{
		// emit begin table tag with the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"border=\"1\" "
				 <<		"cellpadding=\"0\" "
				 <<		"cellspacing=\"0\" "
				 <<		"width=\""
				 <<		mTableWidth
				 <<		"%\" "
				 <<		"bgcolor=\""
				 <<		mColor
				 <<		"\""
				 <<		">"
				 <<		"\n";
	}

	*pStream <<		"<TR>\n"
			 <<		"<td align=\"center\" width=\"82%\"><font size=\"3\" "
			 <<		"face=\"arial, helvetica\">"
			 <<		"<a name=bidding>"
			 <<		"<strong>Items I'm Bidding On</strong>"
			 <<		"</a>"
			 <<		"</font></td>"
			 <<		"<td align=\"center\" width=\"18%\">"
			 <<		"<a href=\""
			 <<		detailLink
			 <<		"\">"
			 <<		"<font size=\"2\"><b>See details...</b></font>"
//			 <<		"<img src=\"details2.gif\" width=\"41\" height=\"15\" "
//			 <<		"alt=\"[details]\" border=\"0\">
			 <<		"</a></td>"
			 <<		"</TR>\n"
			 <<		"</TABLE>\n";

	// create table properties with or without the bgcolor attribute
	if (mColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"border=\"1\" "
				 <<		"cellpadding=\"0\" "
				 <<		"cellspacing=\"0\" "
				 <<		"width=\""
				 <<		mTableWidth
				 <<		"%\""
				 <<		">"
				 <<		"\n";
	}
	else
	{
		// emit begin table tag with the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"border=\"1\" "
				 <<		"cellpadding=\"0\" "
				 <<		"cellspacing=\"0\" "
				 <<		"width=\""
				 <<		mTableWidth
				 <<		"%\" "
				 <<		"bgcolor=\""
				 <<		mColor
				 <<		"\""
				 <<		">"
				 <<		"\n";
	}

	// header titles
	// first get baseURL (current URL without sortcode)
	baseURL = clsUtilities::RemoveISAPIParameter(mURL, "bidderSort");

	// safety
	if (!baseURL) return false;

	// start row
	*pStream <<		"<TR>\n";

	// item header
	*pStream <<		"<td align=\"center\" width=\"12%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByTitle ? SortItemsByTitleReverse : SortItemsByTitle)
			 <<		"#bidding\">"
			 <<		(((mSortCode==SortItemsByTitle) || (mSortCode==SortItemsByTitleReverse)) ? 
						"<strong>Item</strong>": "Item")
			 <<		"</a></font></td>";

	// start header
	*pStream <<		"<td align=\"center\" width=\"12%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByStartPrice ? SortItemsByStartPriceReverse : SortItemsByStartPrice)
			 <<		"#bidding\">"
			 <<		(((mSortCode==SortItemsByStartPrice) || (mSortCode==SortItemsByStartPriceReverse)) ? 
						"<strong>Start</strong>": "Start")
			 <<		"</a></font></td>";

	// current header
	*pStream <<		"<td align=\"center\" width=\"12%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByPrice ? SortItemsByPriceReverse : SortItemsByPrice)
			 <<		"#bidding\">"
			 <<		(((mSortCode==SortItemsByPrice) || (mSortCode==SortItemsByPriceReverse)) ? 
						"<strong>Current</strong>": "Current")
			 <<		"</a></font></td>";

	// my max header
	*pStream <<		"<td align=\"center\" width=\"12%\"><font size=\"2\">"
			 <<		"My Max"
			 <<		"</font></td>";

	// quantity header
	*pStream <<		"<td align=\"center\" width=\"9%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByQuantity ? SortItemsByQuantityReverse : SortItemsByQuantity)
			 <<		"#bidding\">"
			 <<		(((mSortCode==SortItemsByQuantity) || (mSortCode==SortItemsByQuantityReverse)) ? 
						"<strong>Quant": "Quant")
			 <<		"</a></font></td>";

	// bidcount header
	*pStream <<		"<td align=\"center\" width=\"6%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByBidCount ? SortItemsByBidCountReverse : SortItemsByBidCount)
			 <<		"#bidding\">"
			 <<		(((mSortCode==SortItemsByBidCount) || (mSortCode==SortItemsByBidCountReverse)) ? 
						"<strong>Bids</strong>": "Bids")
			 <<		"</a></font></td>";

	// start date header
	*pStream <<		"<td align=\"center\" width=\"8%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByStartTimeReverse ? SortItemsByStartTime : SortItemsByStartTimeReverse)
			 <<		"#bidding\">";
	if ((mSortCode==SortItemsByStartTime) || (mSortCode==SortItemsByStartTimeReverse))
	{
		*pStream <<		"<strong>Start</strong>"
				 <<		"</a></font></td>";
	}
	else
	{
		*pStream <<		"Start"
				 <<		"</a></font></td>";
	}

	// ending date header
	*pStream <<		"<td align=\"center\" width=\"15%\"><font size=\"2\">"
			 <<		"<a href=\""
			 <<		baseURL
			 <<		"&bidderSort="
			 <<		(mSortCode==SortItemsByEndTimeReverse ? SortItemsByEndTime : SortItemsByEndTimeReverse)
			 <<		"#bidding\">";
	if ((mSortCode==SortItemsByEndTime) || (mSortCode==SortItemsByEndTimeReverse))
	{
		*pStream <<		"<strong>End "
// petra				 <<		timeZone
				 <<		mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale()->GetTimeZone();	// petra
		*pStream <<		"</strong></a></font></td>";
	}
	else
	{
		*pStream <<		"End "
// petra				 <<		timeZone
				 <<		mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale()->GetTimeZone();	// petra
		*pStream <<		"</a></font></td>";
	}

	// time left header
	*pStream <<		"<td align=\"center\" width=\"14%\"><font size=\"2\">"
			 <<		"Time Left"
			 <<		"</font></td>";
	
	// end row & table
	*pStream <<		"</TR>\n"
			 <<		"</TABLE>\n";

	delete [] baseURL;

	return true;
}


bool clseBayUserBiddingWidget::EmitTableFooter(ostream *pStream,
										char*	CellColor,
										int		TotalItem,
										double	TotalStartPrice,
										double	TotalPrice,
										double	TotalMyMaxPrice,
										int		TotalQuantity,
										int		TotalBidCount)
{
	clsCurrencyWidget* pCurrencyWidget;

	// myebay totals table
	*pStream << "<TABLE border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#EFEFEF\">\n";

	// start row
	*pStream <<		"<TR>\n";

	// item footer
	*pStream <<		"<td align=\"left\" width=\"12%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">"
			 <<		"Totals: "
			 <<		TotalItem
			 <<		"</font></B></font></td>\n";

	// start price footer
	*pStream <<		"<td align=\"right\" width=\"12%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">";

	pCurrencyWidget = new clsCurrencyWidget(mpMarketPlace, Currency_USD, TotalStartPrice);
	pCurrencyWidget->EmitHTML(pStream);
	delete pCurrencyWidget;

	*pStream	 <<		"</font></B></font></td>\n";

	// current price footer
	*pStream <<		"<td align=\"right\" width=\"12%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">";

	pCurrencyWidget = new clsCurrencyWidget(mpMarketPlace, Currency_USD, TotalPrice);
	pCurrencyWidget->EmitHTML(pStream);
	delete pCurrencyWidget;

	*pStream	 <<		"</font></B></font></td>\n";

	// myMax price footer
	*pStream <<		"<td align=\"right\" width=\"12%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">";

	if (!mRestrictedAccess)
	{
		if (TotalMyMaxPrice > 0)
		{
			pCurrencyWidget = new clsCurrencyWidget(mpMarketPlace, Currency_USD, TotalMyMaxPrice);
			pCurrencyWidget->EmitHTML(pStream);
			delete pCurrencyWidget;
		}
		else
			*pStream << "-";
	}
	else
		*pStream <<	"n/a";

	*pStream <<		"</font></B></font></td>\n";

	// quantity footer
	*pStream <<		"<td align=\"center\" width=\"9%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">";
	if (TotalQuantity > 0)
		*pStream <<	TotalQuantity;
	else
		*pStream <<	"-";

	*pStream	 <<	"</font></B></font></td>\n";

	// bidcount footer
	*pStream <<		"<td align=\"center\" width=\"6%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">"
		     << 	TotalBidCount
			 <<		"</font></B></font></td>\n";

	// start date footer
	*pStream <<		"<td align=\"center\" width=\"8%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">"
			 <<		"-"
			 <<		"</font></B></font></td>\n";

	// ending date footer
	*pStream <<		"<td align=\"center\" width=\"15%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">"
			 <<		"-"
			 <<		"</font></B></font></td>\n";

	// time left footer
	*pStream <<		"<td align=\"center\" width=\"14%\"><font size=\"2\"><B>"
			 <<		"<font color=\""
			 <<		CellColor
			 <<		"\">"
			 <<		"-"
			 <<		"</B></font></td>\n";
	
	// end row & table
	*pStream <<		"</TR>\n"
			 <<		"</TABLE>\n";

	return true;
}

// Post-table HTML
bool clseBayUserBiddingWidget::EmitPostTable(ostream *pStream)
{
	// table footer
	if (mTotalItem > 0) {
		// display header again
		*pStream << "<TABLE border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#CCCCFF\">\n"
				 <<	"<TR>\n"
				 <<	"<td align=\"center\" width=\"12%\"><font size=\"2\">Item</font></td>\n"
				 <<	"<td align=\"center\" width=\"12%\"><font size=\"2\">Start</font></td>\n"
				 <<	"<td align=\"center\" width=\"12%\"><font size=\"2\">Current</font></td>\n"
				 <<	"<td align=\"center\" width=\"12%\"><font size=\"2\">My Max</font></td>\n"
				 <<	"<td align=\"center\" width=\"9%\"><font size=\"2\">Quant</font></td>\n"
				 <<	"<td align=\"center\" width=\"6%\"><font size=\"2\">Bids</font></td>\n"
				 <<	"<td align=\"center\" width=\"8%\"><font size=\"2\">Start</font></td>\n"
				 <<	"<td align=\"center\" width=\"15%\"><font size=\"2\"><strong>End PDT</strong></font></td>\n"
				 << "<td align=\"center\" width=\"14%\"><font size=\"2\">Time Left</font></td></TR>\n"
				 <<	"</TABLE>\n";

		// ebay totals
		EmitTableFooter(pStream, "black",
						mTotalItem, mTotalStartPrice,
						mTotalPrice, mTotalMyMaxPrice,
						mTotalQuantity, mTotalBidCount);
	
		// ebay sold item totals
		EmitTableFooter(pStream, "green",
						mWonTotalItem, mWonTotalStartPrice,
						mWonTotalPrice, mWonTotalMyMaxPrice,
						mWonTotalQuantity, mWonTotalBidCount);
	}


	*pStream <<		"<BR>\n"
			 <<		"<FONT color=\"green\" size=\"2\"><b>Green</b> </FONT>"
			 <<		"<FONT size=\"2\">indicates auctions in which you are currently winning. </FONT>"
			 <<		"<FONT size=\"2\">The totals in <font color=\"green\">green</font> exclude Dutch auctions.</FONT>"
			 <<		"<BR>"
			 <<		"<FONT color=\"red\" size=\"2\">Red </FONT>"
			 <<		"<FONT size=\"2\">indicates auctions in which you are not currently winning.</FONT>"
			 <<		"<BR>"
			 <<		"<FONT size=\"2\">Note: Dutch auctions do not use the color coding.</FONT>"
			 <<		"<BR>"
			 <<		"<FONT size=\"2\">Click on an <u>underlined</u> column heading to sort in "
			 <<		"either ascending or descending order.</FONT>";

	// Useful links
	*pStream	<<	"<p>"
				<<	"<font size=\"2\">"
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"search/items/search.html\">"
				<<	"Search"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewBoard)
				<<	"eBayISAPI.dll?ViewBoard&amp;name=wanted\">"
				<<	"Wanted page"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/buyerguide/bidding-tips.html\">"
				<<	"Tips for buyers"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/basics/f-bidding.html\">"
				<<	"FAQ"
				<<	"</a>"
				<<	" - "
				<<	"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/buyerguide/after-tips.html\">"
				<<	"After the auction"
				<<	"</a>"
				<<	"</font>"

				// temporary message about MaxBid and Quantity. AlexP 07/27/99
				<<	"<br><br>"
					"<font color=red><b>Note:</b></font> "
					"The <b>My Max</b> and <b>Quantity</b> columns have been temporarily disabled, and will "
					"show <b>n/a</b> until they are re-enabled. We apologize for the inconvenience."

				<<	"</p>";

	return true;
}
