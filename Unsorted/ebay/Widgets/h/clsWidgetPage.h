/* $Id: clsWidgetPage.h,v 1.2 1998/10/16 01:00:46 josh Exp $ */
// Handles turning a parsed widget set (data dictionary) into live output.
#ifndef clsWidgetPage_h
#define clsWidgetPage_h

#include "eBayTypes.h"

#include "clsWidgetHandler.h"

class clsWidgetContext;
class clsMarketPlace;
class ostream;

struct widgetHeader
{
	int32_t numWidgets;
	int32_t textOffset; // Also blob offset.
	int32_t widgetOffset;
    int32_t originalText;   // The original, unparsed text offset.
	int32_t byteOrder; // If this is 1, our byte order is 'correct'. Otherwise, reverse it.
};

struct widgetEntry
{
	int32_t widgetType;
	int32_t blobOffset;
};

class clsWidgetPage
{
private:
	const widgetHeader *mpHeader;
	const widgetEntry *mpWidgets;
	const char *mpText;
    const char *mpOriginalText;

	bool mBytesWrong; // True if our byte order is backwards.

	clsMarketPlace *mpMarketPlace;
	clsWidgetHandler mWidgetHandler;
	clsWidgetContext *mpWidgetContext;

public:
	clsWidgetPage();
	~clsWidgetPage();

    clsWidgetContext *GetContext() const { return mpWidgetContext; }

	bool SetPage(void *pBlob);

	bool Draw(ostream *pStream);

    const char *GetOriginalText() const { return mpOriginalText; }
};

#endif /* clsWidgetPage_h */