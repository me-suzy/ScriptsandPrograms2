/*	$Id: clsWidgetHandler.cpp,v 1.4.94.3.80.1 1999/08/06 02:26:58 nsacco Exp $	*/
#include "widgets.h"

#include "clseBayCategoryWidget.h"			// start of Alex's widgets
#include "clseBayLACategoryWidget.h"
#include "clseBayFooterWidget.h"
#include "clseBayHeaderWidget.h"
#include "clseBayHotWidget.h"
#include "clseBayItemDetailWidget.h"
#include "clseBayRandomItemWidget.h"
#include "clseBayStaffPicksWidget.h"
#include "clseBayStatsWidget.h"
#include "clseBaySuperFeaturedWidget.h"
#include "clseBayUserBiddingDetailWidget.h"
#include "clseBayUserBiddingWidget.h"
#include "clseBayUserSellingDetailWidget.h"
#include "clseBayUserSellingWidget.h"
#include "clseBayUserWidget.h"
#include "clseBayTimeWidget.h"
#include "clseBayURLPathWidget.h"
#include "clseBayBoardTOCWidget.h"
#include "clseBayCategorySelectorWidget.h"				// end of Alex's widgets
#include "clseBayCountWidget.h"
#include "clsUserIdWidget.h"
#include "clseBayMyFeedbackWidget.h"
#include "clseBayFeedbackWidget.h"
#include "clseBayItemListWidget.h"
#include "clseBayMemberSinceWidget.h"
#include "clsParagraphWidget.h"
#include "clsMarkedTextWidget.h"
#include "clsImageWidget.h"
#include "clsLinkWidget.h"
#include "clsItemLinkWidget.h"
#include "clseBayHighTicketItemWidget.h"
#include "clseBayFeedbackStatsWidget.h"
#include "clseBayGalleryWidget.h"					// bill wang
// Include widget headers here.
#include "clseBayTextWidget.h"
#include "clsCurrencyWidget.h"
#include "clsBidBoxWidget.h"

#include <ctype.h>

// Use this macro to make sure your definition is _right_ --
// 'x' should be the Name of the widget, such that:
// The class name is clseBayNameWidget
// The enum name is wtNameWidget
// The string for parsing is eBayName
#define MAKE_WIDGET_INFO(x)							\
{ wt##x##Widget,	"eBay" #x,	clseBay##x##Widget::MakeWidget, strlen("eBay" #x)	}

#define MAKE_WIDGET_INFO2(x)						\
{ wt##x##Widget,	"eBay" #x,	cls##x##Widget::MakeWidget, strlen("eBay" #x)	}

//#define EBAY_WIDGET_PREFIX "<eBay"
//#define EBAY_IGNORE_PREFIX_LENGTH 1
//#define EBAY_WIDGET_NAME_END " >"
//#define EBAY_WIDGET_TAG_END '>'

static clseBayWidgetInfo spWidgetInfoTable[] =
{
	MAKE_WIDGET_INFO(Text),
	MAKE_WIDGET_INFO(Category),			// start of Alex's widgets
	MAKE_WIDGET_INFO(Footer),
	MAKE_WIDGET_INFO(Header),
	MAKE_WIDGET_INFO(Hot),
	MAKE_WIDGET_INFO(ItemDetail),
	MAKE_WIDGET_INFO(RandomItem),
	MAKE_WIDGET_INFO(StaffPicks),
	MAKE_WIDGET_INFO(Stats),
	MAKE_WIDGET_INFO(SuperFeatured),
	MAKE_WIDGET_INFO(UserBiddingDetail),
	MAKE_WIDGET_INFO(UserBidding),
	MAKE_WIDGET_INFO(UserSellingDetail),
	MAKE_WIDGET_INFO(UserSelling),
	MAKE_WIDGET_INFO(User),
	MAKE_WIDGET_INFO(Time),
	MAKE_WIDGET_INFO(URLPath),
	MAKE_WIDGET_INFO(BoardTOC),
	MAKE_WIDGET_INFO(CategorySelector), // end of Alex's widgets
    MAKE_WIDGET_INFO(Count),
    { wtUserIdWidget, "", clsUserIdWidget::MakeWidget, 0 },
    MAKE_WIDGET_INFO(MyFeedback),
    MAKE_WIDGET_INFO(Feedback),
    MAKE_WIDGET_INFO(ItemList),
    MAKE_WIDGET_INFO(MemberSince),
	MAKE_WIDGET_INFO2(Paragraph),
	MAKE_WIDGET_INFO2(MarkedText),
	MAKE_WIDGET_INFO2(Image),
	MAKE_WIDGET_INFO2(Link),
	MAKE_WIDGET_INFO2(ItemLink),
	MAKE_WIDGET_INFO(HighTicketItem),	// wen
	// nsacco 08/05/99 commented out
	//MAKE_WIDGET_INFO(FeedbackLeaders),	
	MAKE_WIDGET_INFO(FeedbackStats),
	MAKE_WIDGET_INFO2(Currency),
	MAKE_WIDGET_INFO2(BidBox),
	MAKE_WIDGET_INFO(LACategory),
	MAKE_WIDGET_INFO(Gallery)
};

clseBayWidgetInfo *clsWidgetHandler::mpWidgetInfoTable = 
	(clseBayWidgetInfo *) spWidgetInfoTable;

int clsWidgetHandler::mNumKnownWidgets = sizeof (spWidgetInfoTable) / sizeof (clseBayWidgetInfo);

#undef MAKE_WIDGET_INFO

clsWidgetHandler::clsWidgetHandler(clsMarketPlace *pMarketPlace,
								   clsApp *pApp) :
	mpMarketPlace(pMarketPlace), mpApp(pApp)
{
}

clsWidgetHandler::~clsWidgetHandler()
{
	mpMarketPlace = NULL;
	mpApp = NULL;
}

clseBayWidget *clsWidgetHandler::GetWidget(eBayKnownWidgets widgetType) 
{
	clseBayWidget *pWidget;

	pWidget = (mpWidgetInfoTable[widgetType].widgetFunc)(this,
		mpMarketPlace, mpApp);

	if (pWidget)
		pWidget->SetType(widgetType);

	return pWidget;
}

clseBayWidget *clsWidgetHandler::GetWidget(char *pTagName)
{
	clseBayWidget *pWidget;
	int ctr, tagLength;
	
	tagLength = strlen(pTagName);

	for (ctr = 0; ctr < mNumKnownWidgets; ++ctr)
	{
		// check lengths first
		if (mpWidgetInfoTable[ctr].stringLength != tagLength)
			continue;

		// now check for real
		if (strncmp(mpWidgetInfoTable[ctr].widgetString, pTagName, tagLength))
			continue;

		// get it by type and set its type
		pWidget = GetWidget(mpWidgetInfoTable[ctr].widgetType);

		return pWidget;
	}

	return NULL; // widget not found

}

// Used to release a widget obtained via GetWidget.
void clsWidgetHandler::ReleaseWidget(clseBayWidget *pWidget)
{
    delete pWidget;
}

