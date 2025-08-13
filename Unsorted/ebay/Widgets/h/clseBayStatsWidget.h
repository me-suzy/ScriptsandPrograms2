/*	$Id: clseBayStatsWidget.h,v 1.2.350.1.102.1 1999/08/04 00:48:57 phofer Exp $	*/
//
//	File:	clseBayStatsWidget.h
//
//	Class:	clseBayStatsWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows eBay statistics.
//			This widget was derived from clseBayTableWidget by overriding
//			 the following routines:
//				* EmitCell(int n)			= emits the HTML for each stats item, 
//											  including the <TD> and </TD> tags
//				* Initialize()				= query the database for the stats
//
//			Example code of how to invoke the clseBayStatsWidget:
//
//				clseBayStatsWidget *sw = new clseBayStatsWidget(mpMarketPlace, gApp);
//				sw->SetColor("#E8E8E8");
//				sw->EmitHTML(mpStream);
//				delete sw;
//
// Modifications:
//				- 10/01/97	Poon - Created
//				- 08/02/99	petra - leave the number formatting to clsIntlLocale
//
#ifndef CLSEBAYSTATSWIDGET_INCLUDED
#define CLSEBAYSTATSWIDGET_INCLUDED

#include "clseBayTableWidget.h"

class clseBayStatsWidget : public clseBayTableWidget
{

public:

	// Stats widget requires having access to the marketplace and the app
	//  (for querying the database of items).
	clseBayStatsWidget(clsMarketPlace *pMarketPlace, clsApp *pApp);

	// Empty dtor.
	virtual ~clseBayStatsWidget() {};

	void SetFont(const char *c)					{strncpy(mFont, c, sizeof(mFont) - 1);}
	void SetFontSize(int FontSize)				{mFontSize = FontSize;}
	void SetFontColor(const char *c)			{strncpy(mFontColor, c, sizeof(mFontColor) - 1);}

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayStatsWidget(pMarketPlace, pApp); }
	

protected:
	// Get the stats by querying the database
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);

private:
/* petra	// For formatting stats into xxx,xxx,xxx format
	//  (taken from clsAWHomeTemplate)
	void FormatValue(int Value, char* pFormatedValue); */

	char	mCurrentItemCount[32];
	char	mCategoryCount[32];
	char	mItemCountSinceInception[32];
	char	mBidCountSinceInception[32];
	char	mHitsPerWeek[32];
	char	mPageViewsPerDay[32];

	char				mFont[256];				// font, e.g. arial,helvetica
	int					mFontSize;				// font size, e.g. 3
	char				mFontColor[256];		// font, e.g. "#6699CC"

};

#endif // CLSEBAYSTATSWIDGET_INCLUDED
