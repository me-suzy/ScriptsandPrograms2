/* $Id: clseBayMemberSinceWidget.h,v 1.2 1998/10/16 01:01:01 josh Exp $ */
//
//	File:	clseBayMemberSinceWidget.h
//
//	Class:	clseBayMemberSinceWidget
//
#ifndef clseBayMemberSinceWidget_h
#define clseBayMemberSinceWidget_h

#include "clseBayWidget.h"
#include "clseBayTimeWidget.h"

// This is just a wrapper for a time widget -- the
// only thing special about this is that it 'knows'
// what time to use, via context.
class clseBayMemberSinceWidget : public clseBayWidget
{

public:

    // Construct via a blob.
    clseBayMemberSinceWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

	// Empty dtor
	virtual ~clseBayMemberSinceWidget() {};

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool fixBytes);
    long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayMemberSinceWidget(pHandler, pMarketPlace, pApp); }

	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

protected:

private:
    clseBayTimeWidget mTimeWidget;
};

#endif /* clseBayMemberSinceWidget_h */