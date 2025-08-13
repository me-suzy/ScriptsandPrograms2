/* $Id: clsMarkedTextWidget.h,v 1.2 1998/10/16 01:00:38 josh Exp $ */
#ifndef clsMarkedTextWidget_h
#define clsMarkedTextWidget_h

#include "clseBayWidget.h"

class clsMarkedTextWidget : public clseBayWidget
{
public:
    clsMarkedTextWidget(); // Does not exist!

    clsMarkedTextWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

    ~clsMarkedTextWidget();

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return new clsMarkedTextWidget(pHandler, pMarketPlace, pApp); }

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	const char *GetText() { return mpText; }
	long GetMarker() { return mMarker; }

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

protected:
	char *mpText;
	long mMarker;

	void SetText(const char *pText);
};

#endif /* clsMarkedTextWidget_h */