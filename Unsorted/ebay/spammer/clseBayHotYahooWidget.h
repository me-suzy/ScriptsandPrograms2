/*	$Id: clseBayHotYahooWidget.h,v 1.2 1998/06/23 04:31:48 josh Exp $	*/
// clseBayHotYahooWidget.h: interface for the clseBayHotYahooWidget class.
//
//////////////////////////////////////////////////////////////////////

#ifndef CLSEBAYHOTYAHOOWIDGET_INCLUDED
#define CLSEBAYHOTYAHOOWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clseBayHotWidget.h"
#include "clsItem.h"

#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */

const int YAHOO_HOT_ITEMS = 5;

// redirect for partners
const char * const REDIRECTOR = "http://komodo.ebay.com/aw-cgi/eBayISAPI.dll?RedirectEnter&partner=yahooAuction&loc=";
// item URL
const char * const ITEM_URL = "http://iguana.ebay.com/aw-cgi/eBayISAPI.dll%%3FViewItem%%26item=";

class clseBayHotYahooWidget : public clseBayHotWidget  
{
public:
	bool Emit1Cell(ostrstream *pStream, int n);
	clseBayHotYahooWidget(clsMarketPlace *pMarketPlace);
	virtual ~clseBayHotYahooWidget();

	bool Initialize();

/*
	vector<clsItem*>	mvItems;		// the items to show
	bool				mMoreLink;		// show more... link?
	bool				mShowPrice;		// show price?
	bool				mShowBidCount;	// show bid count?
	char				mName[255];		// generic item name to use
*/
};

#endif // CLSEBAYHOTYAHOOWIDGET_INCLUDED
