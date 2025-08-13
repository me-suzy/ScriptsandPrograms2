/*	$Id: clseBayTimeWidget.cpp,v 1.8.2.2.98.2 1999/08/03 01:19:35 phofer Exp $	*/
//
//	File:	clseBayTimeWidget.cpp
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
//				- 05/14/99  Sam  - Y2K fix, represent year in 2 digits normalized
//				- 06/14/99	nsacco - Australian time
//				- 07/20/99	petra	- change to use clsIntlLocale
//

#include "widgets.h"
#include "clseBayTimeWidget.h"

struct clseBayTimeWidgetOptions
{
    int32_t		 formatDate;		// petra
	int32_t		 formatTime;		// petra
};

// Construct via a blob.
clseBayTimeWidget::clseBayTimeWidget(clsWidgetHandler *pHandler,
                                     clsMarketPlace *pMarketPlace,
                                     clsApp *pApp) : clseBayWidget(pHandler, pMarketPlace, pApp)
{
    mTime = 0;
	mDateFormat = EBAY_TIMEWIDGET_MEDIUM_DATE;			// petra
	mTimeFormat = EBAY_TIMEWIDGET_LONG_TIME;			// petra
}

// petra construct a timewidget with all necessary parms
clseBayTimeWidget::clseBayTimeWidget(clsMarketPlace *pMarketPlace,
                                     int dateFormat,
									 int timeFormat,
									 time_t time)
                                 : clseBayWidget(pMarketPlace)
{
	mTime = time;
	mDateFormat = dateFormat;
	mTimeFormat = timeFormat;
}

// petra construct a timewidget with all necessary parms for current time
clseBayTimeWidget::clseBayTimeWidget(clsMarketPlace *pMarketPlace,
                                     int dateFormat,
									 int timeFormat)
                                 : clseBayWidget(pMarketPlace)
{
	mTime = 0;
	mDateFormat = dateFormat;
	mTimeFormat = timeFormat;
}

void clseBayTimeWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;
	*pStream << ">";
}

// For translation to and from text.
void clseBayTimeWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pValue;

    // Let's run through our known attributes and check them out.
    if (pValue = GetParameterValue("FORMATDATE", pvArgs))
        SetDateFormat(atoi(pValue));						

    else if (pValue = GetParameterValue("FORMATTIME", pvArgs))
        SetTimeFormat(atoi(pValue));							
}

void clseBayTimeWidget::SetParams(const void *pData, 
                                  const char *pStringBase, 
                                  bool fixBytes)
{
    clseBayTimeWidgetOptions *pOptions;

    pOptions = (clseBayTimeWidgetOptions *) pData;

	mDateFormat = pOptions->formatDate;
	mTimeFormat = pOptions->formatTime;	

    if (fixBytes)
    {
		pOptions->formatDate = clsUtilities::FixByteOrder32(pOptions->formatDate);
		pOptions->formatTime = clsUtilities::FixByteOrder32(pOptions->formatTime);
    }
}

long clseBayTimeWidget::GetBlob(clsDataPool *pDataPool, 
                                bool fixBytes)
{
    clseBayTimeWidgetOptions theOptions;

	theOptions.formatDate = mDateFormat;	// petra
	theOptions.formatTime = mTimeFormat;	// petra

    if (fixBytes)
    {
		theOptions.formatDate = clsUtilities::FixByteOrder32(theOptions.formatDate);	// petra
		theOptions.formatTime = clsUtilities::FixByteOrder32(theOptions.formatTime);	// petra
    }

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

// petra: get formatted strings from locale class
bool clseBayTimeWidget::EmitHTML(ostream *pStream)
{
	char Buffer[50];
	
	clsIntlLocale * pLocale = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale();
	if (mDateFormat != EBAY_TIMEWIDGET_NO_DATE)
	{
		if (mTime == 0)
			pLocale->GetDateFormatted (&Buffer[0], mDateFormat);
		else
			pLocale->GetDateFormatted (&Buffer[0], mTime, mDateFormat);
		*pStream << Buffer;
		if (mTimeFormat != EBAY_TIMEWIDGET_NO_TIME)
			*pStream << ", ";
	}
	if (mTimeFormat != EBAY_TIMEWIDGET_NO_TIME)
	{
		if (mTime == 0)
			pLocale->GetTimeFormatted (&Buffer[0], mTimeFormat);
		else
			pLocale->GetTimeFormatted (&Buffer[0], mTime, mTimeFormat);
		*pStream << Buffer;
	}

    return true;
}

// petra: thought I could get away w/o this... but it's used in clseBayMyBalanceWidget
void clseBayTimeWidget::EmitString(char *pString)
{
	char Buffer[50];

	*pString = '\0';

	clsIntlLocale * pLocale = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale();
	if (mDateFormat != EBAY_TIMEWIDGET_NO_DATE)
	{
		if (mTime == 0)
			pLocale->GetDateFormatted (&Buffer[0], mDateFormat);
		else
			pLocale->GetDateFormatted (&Buffer[0], mTime, mDateFormat);
		strcpy (pString, Buffer);
		if (mTimeFormat != EBAY_TIMEWIDGET_NO_TIME)
			strcat (pString, ", ");
	}
	if (mTimeFormat != EBAY_TIMEWIDGET_NO_TIME)
	{
		if (mTime == 0)
			pLocale->GetTimeFormatted (&Buffer[0], mTimeFormat);
		else
			pLocale->GetTimeFormatted (&Buffer[0], mTime, mTimeFormat);
		strcat (pString, Buffer);
	}

    return;
}