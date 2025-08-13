/*	$Id: clsWidgetHandler.h,v 1.3.94.3 1999/06/04 19:13:38 jpearson Exp $	*/
//
// Class Name:		clsWidgetHandler
//
// Description:		A handler class for all widgets
//
// Author:			Chad Musick
// 
#ifndef CLSWIDGETHANDLER_INCLUDED
#define CLSWIDGETHANDLER_INCLUDED

#include "vector.h"
#include "clsWidgetContext.h"

class clseBayWidget;
class clsMarketPlace;
class clsApp;
class clsWidgetContext;
class clsWidgetHandler;
class ostream;

// The enum names for the widget should be of the form:
// wtNameWidget
// Put them here, and do not use = value as it will have a detrimental
// effect on memory usage.
typedef enum
{
	wtTextWidget,
	wtCategoryWidget,			// start of Alex's widgets
	wtFooterWidget,
	wtHeaderWidget,
	wtHotWidget,
	wtItemDetailWidget,
	wtRandomItemWidget,
	wtStaffPicksWidget,
	wtStatsWidget,
	wtSuperFeaturedWidget,
	wtUserBiddingDetailWidget,
	wtUserBiddingWidget,
	wtUserSellingDetailWidget,
	wtUserSellingWidget,
	wtUserWidget,
	wtTimeWidget,
	wtURLPathWidget,
	wtBoardTOCWidget,
	wtCategorySelectorWidget,	// end of Alex's widgets
    wtCountWidget,
    wtUserIdWidget,
    wtMyFeedbackWidget,
    wtFeedbackWidget,
    wtItemListWidget,
    wtMemberSinceWidget,
	wtParagraphWidget,
	wtMarkedTextWidget,
	wtImageWidget,
	wtLinkWidget,
	wtItemLinkWidget,
	wtHighTicketItemWidget,		// wen
	wtFeedbackLeadersWidget,
	wtFeedbackStatsWidget,
	wtCurrencyWidget,
	wtBidBoxWidget,
	wtLACategoryWidget,
	wtGalleryWidget				// bill
} eBayKnownWidgets;

// A couple of typedefs to make readable code.
typedef vector<clseBayWidget *> WidgetVector;

typedef clseBayWidget * (*WidgetMakeFunc)(clsWidgetHandler *,
										  clsMarketPlace * = NULL, 
										  clsApp * = NULL);

// These hold information necessary to parse for and construct a widget.
struct clseBayWidgetInfo
{
	eBayKnownWidgets widgetType;
	const char *widgetString;
	WidgetMakeFunc widgetFunc;
	int stringLength;
};

class clsWidgetHandler
{
public:
	// Constructor. pMarketPlace and pApp are passed right
	// along to the widgets.
	clsWidgetHandler(clsMarketPlace *pMarketPlace = NULL,
					 clsApp *pApp = NULL);

	// Destructor.
	~clsWidgetHandler();

	// Gets the context object.
	clsWidgetContext *GetWidgetContext()
	{ return &mWidgetContext; }

	// Makes and returns a widget of the specified type.
	clseBayWidget *GetWidget(eBayKnownWidgets widgetType);

	// Makes and returns a widget with the given tagname
	clseBayWidget *GetWidget(char *pTagName);

    // Used to release a widget obtained via GetWidget.
    void ReleaseWidget(clseBayWidget *pWidget);

private:

	// A table of information necessary to parse for and make widgets.
	static clseBayWidgetInfo *mpWidgetInfoTable;

	// How many widgets we know about, in mpWidgetInfoTable.
	static int				  mNumKnownWidgets;

	clsMarketPlace *mpMarketPlace;
	clsApp *mpApp;
	clsWidgetContext mWidgetContext;
};

#endif /* CLSWIDGETHANDLER_INCLUDED */
