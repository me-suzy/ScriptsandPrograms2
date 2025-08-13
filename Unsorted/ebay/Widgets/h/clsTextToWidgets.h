/* $Id: clsTextToWidgets.h,v 1.2 1998/10/16 01:00:41 josh Exp $ */
// Handles taking raw text and turning it into a parsed widget format
// (data dictionary).
#ifndef clsTextToWidgets_h
#define clsTextToWidgets_h

#include "eBayTypes.h"
#include "clsWidgetHandler.h"
#include "list.h"

class clsWidgetContext;
class clsMarketPlace;

struct widgetDesignator
{
	eBayKnownWidgets widgetType;
	const char *widgetString;
	int stringLength;
};

class clsTextToWidgets
{
private:
	const char *mpOriginalText;				// Stores the original, untouched text
	char *mpDataDictionary;			// The whole point of this class.

	int mDataDictionaryLength;		// How big is the data dictionary?

	clsMarketPlace *mpMarketPlace;			// The current marketplace.
	clsWidgetHandler mWidgetHandler;		// Used to create widgets, to create the widget blobs.
	clsWidgetContext *mpWidgetContext;		// Context object for shared information

	bool mReverseBytes;						// If set to true, swap the byte order before saving.

	const char *mpStartToken;				// How all of the tokens begin.
	const widgetDesignator *mpAcceptable;	// The list of 'acceptable' widgets.
	unsigned long mNumAcceptable;			// How many of these we have.

    bool FindTokenType(const char *pStr, eBayKnownWidgets *pWidgetType) const;
	bool FindTagEnd(const char **ppStart, bool *pfixQuotes /* OUT */) const;
	bool FindAttribute(char **ppStr, vector<char *> *pArgList) const;

public:
	clsTextToWidgets(widgetDesignator *pAcceptableWidgets, unsigned long numAcceptable,
		const char *pStartToken);
	~clsTextToWidgets();
	
	bool SetText(const char *pText);	// Does no actual work. False if it can't set.
	const char *GetDataDictionary(int *pStoreLength);	// Work is done here, if necessary.
														// The length of the DD is placed in pStoreLength.

	void SetReverseBytes(bool on = false)	// Only call this if host machine and target machine byte orders
	{ mReverseBytes = on; }				// differ.
};

#endif /* clsTextToWidgets_h */