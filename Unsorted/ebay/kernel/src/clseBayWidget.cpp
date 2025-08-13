/*	$Id: clseBayWidget.cpp,v 1.6 1999/03/07 08:16:54 josh Exp $	*/
//
//	File:	clseBayWidget.cpp
//
//	Class:	clseBayWidget
//
//	Author:	Poon
//
//	Function:
//			Abstract base class for eBay widgets
//
// Modifications:
//				- 10/01/97	Poon - Created
//
#include "eBayKernel.h"
#include "clseBayWidget.h"

// Helper function to compare two tags -- basically the same as
// strncasecmp, were such a function to exist portably.
static int compare_tags(const char *p1, const char *p2, unsigned long length)
{
    while (length--)
    {
        if (tolower(*p1) != tolower(*p2))
            return (int) tolower(*p1) - tolower(*p2);
        ++p1; ++p2;
    }

    return 0;
}

clseBayWidget::clseBayWidget(clsMarketPlace *pMarketPlace /* = NULL */, 
								  clsApp *pApp /* = NULL */)
{
	mpMarketPlace = pMarketPlace;
	mpApp = pApp;
	mpWidgetHandler = NULL;
	mpLoggingStream = NULL;
}

clseBayWidget::clseBayWidget(clsWidgetHandler *pWidgetHandler,
							 clsMarketPlace *pMarketPlace,
							 clsApp *pApp)
{
	mpMarketPlace = pMarketPlace;
	mpApp = pApp;
	mpWidgetHandler = pWidgetHandler;
	mpLoggingStream = NULL;
}

const char *clseBayWidget::GetParameterValue(const char *pName,
                                             vector<char *> *pParams)
{
    vector<char *>::iterator i;
    char *pValue;
    char *pLookAhead;
    unsigned long length;

    // We don't count param 1, which is just the name.
    if (pParams->size() <= 1)
        return NULL;

    length = strlen(pName);

    for (i = pParams->begin() + 1; i != pParams->end(); ++i)
    {
        // If they don't match, move on.
        if (compare_tags(*i, pName, length))
            continue;

        pValue = strchr(*i, '=');

        // We return an empty string as as sign that the parameter
        // exists, but with not any value to it -- just the name.
        if (!pValue)
        {
            // Make sure we didn't just catch the prefix.
            if (strlen(*i) != length)
                continue;

            return "";
        }

        // Make sure we didn't just catch the prefix.
        if ((pValue - *i) != length)
            continue;

        // Increment past '='
        ++pValue;

        if (*pValue == '\"')
            ++pValue;

        pLookAhead = strrchr(pValue, '\"');
        if (pLookAhead && pLookAhead >= pValue)
			*pLookAhead = '\0';

		// signfify that this parameter was handled by setting
		// first byte of parameter to 0 (josh's idea)
		*i[0] = '\0';

        return pValue;
    }

    return NULL;
}

void clseBayWidget::DrawTag(ostream *pStream, const char *pName)
{
	*pStream << "<"
			 << pName
			 << ">";
}

