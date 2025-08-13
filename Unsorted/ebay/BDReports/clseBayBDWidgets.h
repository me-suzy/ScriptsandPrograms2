/*	$Id: clseBayBDWidgets.h,v 1.2 1999/02/21 02:21:04 josh Exp $	*/
#ifndef CLSEBAYBDWIDGETS_INCLUDED
#define CLSEBAYBDWIDGETS_INCLUDED

#include "clseBayWidget.h"

class clsWidgetHandler;

#define STANDARD_WIDGET_MAKE(x)										\
	class x	: public clseBayWidget									\
	{																\
	public:															\
	x(clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL) :	\
		clseBayWidget(pMarketPlace, pApp)							\
	{ }																\
																	\
	static clseBayWidget *MakeWidget(clsWidgetHandler *,			\
		clsMarketPlace *pMarketPlace = NULL,						\
		clsApp *pApp = NULL)										\
	{ cout << #x << endl << flush; return (clseBayWidget *) new x(pMarketPlace, pApp); }			\
																	\
	bool EmitHTML(ostream *pStream, clsWidgetHandler *pHandler);	\
	bool EmitHTML(ostream *pStream) { return false; }				\
	}

STANDARD_WIDGET_MAKE(clseBayBDCategoryViewsWidget);
STANDARD_WIDGET_MAKE(clseBayBDNewUsersWidget);
STANDARD_WIDGET_MAKE(clseBayBDTotalUsersWidget);
STANDARD_WIDGET_MAKE(clseBayBDNewItemsCountWidget);
STANDARD_WIDGET_MAKE(clseBayBDBidsByWidget);
STANDARD_WIDGET_MAKE(clseBayBDBidsOnWidget);
STANDARD_WIDGET_MAKE(clseBayBDClosedBidsByWidget);
STANDARD_WIDGET_MAKE(clseBayBDClosedBidsOnWidget);
STANDARD_WIDGET_MAKE(clseBayBDSuccessfulAuctionsWidget);
STANDARD_WIDGET_MAKE(clseBayBDUnsuccessfulAuctionsWidget);
STANDARD_WIDGET_MAKE(clseBayBDTotalAuctionsWidget);
STANDARD_WIDGET_MAKE(clseBayBDBidsPerAuctionWidget);
STANDARD_WIDGET_MAKE(clseBayBDBidsPerAllAuctionsWidget);
STANDARD_WIDGET_MAKE(clseBayBDPercentSuccessfulAuctionsWidget);
STANDARD_WIDGET_MAKE(clseBayBDPercentUnsuccessfulAuctionsWidget);
STANDARD_WIDGET_MAKE(clseBayBDSuccessfulAuctionLengthWidget);
STANDARD_WIDGET_MAKE(clseBayBDUnsuccessfulAuctionLengthWidget);
STANDARD_WIDGET_MAKE(clseBayBDTotalAuctionLengthWidget);
STANDARD_WIDGET_MAKE(clseBayBDAllMinimumPriceWidget);
STANDARD_WIDGET_MAKE(clseBayBDSuccessfulMinimumPriceWidget);
STANDARD_WIDGET_MAKE(clseBayBDUnsuccessfulMinimumPriceWidget);
STANDARD_WIDGET_MAKE(clseBayBDAverageClosePriceWidget);
STANDARD_WIDGET_MAKE(clseBayBDHighestCloseWidget);
STANDARD_WIDGET_MAKE(clseBayBDTotalValueWidget);
STANDARD_WIDGET_MAKE(clseBayBDNewBoldWidget);
STANDARD_WIDGET_MAKE(clseBayBDNewFeaturedWidget);
STANDARD_WIDGET_MAKE(clseBayBDNewSuperFeaturedWidget);
STANDARD_WIDGET_MAKE(clseBayBDTotalRevenueWidget);
STANDARD_WIDGET_MAKE(clseBayBDPartnerPageViewWidget);
STANDARD_WIDGET_MAKE(clseBayBDPercentageSoldValueWidget);
STANDARD_WIDGET_MAKE(clseBayBDCategoryTitleWidget);
STANDARD_WIDGET_MAKE(clseBayBDSuperCategoryTitleWidget);
STANDARD_WIDGET_MAKE(clseBayBDClosedBidTotalWidget);

STANDARD_WIDGET_MAKE(clseBayBDPartnerNameWidget);
STANDARD_WIDGET_MAKE(clseBayBDSubCategoriesTitlesWidget);
STANDARD_WIDGET_MAKE(clseBayBDDatesCoveredWidget);

#undef STANDARD_WIDGET_MAKE

#endif /* CLSEBAYBDWIDGETS_INCLUDED */
