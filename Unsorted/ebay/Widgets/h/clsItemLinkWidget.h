/* $Id: clsItemLinkWidget.h,v 1.2 1998/10/16 01:00:36 josh Exp $ */
#ifndef clsItemLinkWidget_h
#define clsItemLinkWidget_h

#include "clseBayWidget.h"

class clsItemLinkWidget : public clseBayWidget
{
public:
    clsItemLinkWidget(); // Does not exist!

    clsItemLinkWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

    ~clsItemLinkWidget();

	// Set parameters.
	void SetItemNumber(int item)	{ mItemNumber = item; }

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return new clsItemLinkWidget(pHandler, pMarketPlace, pApp); }

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

protected:
	long mItemNumber;
};

#endif /* clsItemLinkWidget_h */