/* $Id: clsLinkWidget.h,v 1.2 1998/10/16 01:00:37 josh Exp $ */
#ifndef clsLinkWidget_h
#define clsLinkWidget_h

#include "clseBayWidget.h"

class clsLinkWidget : public clseBayWidget
{
public:
    clsLinkWidget(); // Does not exist!

    clsLinkWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

    ~clsLinkWidget();

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return new clsLinkWidget(pHandler, pMarketPlace, pApp); }

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

	void SetText(const char *pText);
	void SetLocation(const char *pLocation);

protected:
	char *mpText;
	char *mpLocation;
};

#endif /* clsLinkWidget_h */