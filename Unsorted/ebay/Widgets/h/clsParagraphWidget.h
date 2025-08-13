/* $Id: clsParagraphWidget.h,v 1.2 1998/10/16 01:00:40 josh Exp $ */
#ifndef clsParagraphWidget_h
#define clsParagraphWidget_h

#include "clseBayWidget.h"

class clsParagraphWidget : public clseBayWidget
{
public:
    clsParagraphWidget(); // Does not exist!

    clsParagraphWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

    ~clsParagraphWidget();

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return new clsParagraphWidget(pHandler, pMarketPlace, pApp); }

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

    void SetText(const char *pText);
    void SetHeader(const char *pHeader);
    void SetHeaderColor(const char *pHeaderColor);

protected:
    char *mpText;
    char *mpHeader;
    char *mpHeaderColor;
};

#endif /* clsParagraphWidget_h */