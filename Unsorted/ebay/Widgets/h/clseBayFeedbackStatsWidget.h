/*	$Id: clseBayFeedbackStatsWidget.h,v 1.2 1999/02/21 02:28:34 josh Exp $	*/
//
//	File:		clseBayFeedbackStatsWidget.cpp
//
//	Class:		clseBayFeedbackStatsWidget
//
//	Author:		Barry
//
//	Function:
//				Gets the stats for feedback on our site.
//              Shows a summary of how many users have each kind of star.
//
//
//	Modifications:
//				
//
// Usage:
//////////////////////////////////////////////////////////////////////

#ifndef CLSEBAYFEEDBACKSTATSWIDGET_INCLUDED
#define CLSEBAYFEEDBACKSTATSWIDGET_INCLUDED

class clsUser;

class clseBayFeedbackStatsWidget : public clseBayWidget  
{
public:
	clseBayFeedbackStatsWidget(clsMarketPlace *pMarketPlace);
	~clseBayFeedbackStatsWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayFeedbackStatsWidget(pMarketPlace); }

	bool EmitHTML(ostream *pStream);
protected:
};

#endif // CLSEBAYFEEDBACKSTATSWIDGET_INCLUDED


