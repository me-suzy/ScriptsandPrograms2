/*	$Id: clseBayTimeWidget.h,v 1.5.160.1 1999/08/01 02:51:21 barry Exp $	*/
//
//	File:	clseBayTimeWidget.h
//
//	Class:	clseBayTimeWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits the current time.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 01/07/97	Poon - Created
//				- 07/19/99	petra	- changed to use clsIntlLocale
//
#ifndef CLSEBAYTIMEWIDGET_INCLUDED
#define CLSEBAYTIMEWIDGET_INCLUDED

#include "clseBayWidget.h"
#include <time.h>

#define EBAY_TIMEWIDGET_NO_DATE -1
#define EBAY_TIMEWIDGET_SHORT_DATE 0
#define EBAY_TIMEWIDGET_MEDIUM_DATE 1
#define EBAY_TIMEWIDGET_LONG_DATE 2
#define EBAY_TIMEWIDGET_NO_TIME -1
#define EBAY_TIMEWIDGET_SHORT_TIME 0
#define EBAY_TIMEWIDGET_MEDIUM_TIME 1
#define EBAY_TIMEWIDGET_LONG_TIME 2

class clseBayTimeWidget : public clseBayWidget
{

public:

    // Construct via a blob.
    clseBayTimeWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

	// petra construct with parameters
	clseBayTimeWidget(clsMarketPlace *pMarketPlace, int dateFormat, int timeFormat, time_t time);
	// petra construct with parameters for current time/date
	clseBayTimeWidget(clsMarketPlace *pMarketPlace, int dateFormat, int timeFormat);

	// Empty dtor
	virtual ~clseBayTimeWidget() {};

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayTimeWidget(pHandler, pMarketPlace, pApp); }

	void SetTime(time_t theTime) { mTime = theTime; }	
	void SetDateTimeFormat (int dateFormat, int timeFormat) {	mDateFormat = dateFormat;		// petra
																mTimeFormat = timeFormat; }		// petra
	void SetDateFormat (int dateFormat) { mDateFormat = dateFormat; }	// petra
	void SetTimeFormat (int timeFormat) { mTimeFormat = timeFormat; }	// petra

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

	void EmitString (char *pString);

private:
    time_t	    mTime;		// in local eBay time
	int			mDateFormat;	// petra: -1 means don't emit date
							// petra: 0 means short date format
							// petra: 1 means medium date format
							// petra: 2 means long date format
	int			mTimeFormat;	// petra: -1 menas don't emit time
							// petra: 0 means short date format
							// petra: 1 means medium date format
							// petra: 2 means long date format
};

#endif // CLSEBAYTIMEWIDGET_INCLUDED