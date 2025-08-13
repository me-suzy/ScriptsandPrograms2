/* $Id: clsImageWidget.h,v 1.2 1998/10/16 01:00:35 josh Exp $ */
#ifndef clsImageWidget_h
#define clsImageWidget_h

#include "clseBayWidget.h"

class clsImageWidget : public clseBayWidget
{
public:
    clsImageWidget(); // Does not exist!

    clsImageWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

    ~clsImageWidget();

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return new clsImageWidget(pHandler, pMarketPlace, pApp); }

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

	void SetLocation(const char *pLoc);
	void SetAlt(const char *pAlt);
	void SetCaption(const char *pCaption);

protected:
	char *mpLocation;
	long mHeight;
	long mWidth;

	char *mpAlt;
	char *mpCaption;

};

#endif /* clsImageWidget_h */