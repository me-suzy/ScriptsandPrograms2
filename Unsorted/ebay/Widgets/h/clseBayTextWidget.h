/*	$Id: clseBayTextWidget.h,v 1.2 1998/10/16 01:01:13 josh Exp $	*/
//
//	File:	clseBayTextWidget.h
//
//	Class:	clseBayTextWidget
//
//	Author:	Chad
//
//	Function:
//			Widget that emits text.
//
// Modifications:
//				- 10/20/97	tini - Created
//
#ifndef CLSEBAYTEXTWIDGET_INCLUDED
#define CLSEBAYTEXTWIDGET_INCLUDED

#include "clseBayWidget.h"

class clseBayTextWidget : public clseBayWidget
{
public:

	clseBayTextWidget(clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL);

	~clseBayTextWidget();
	
	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	bool EmitHTML(ostream *pStream);

	void SetParams(int length, const void *data);

	// The setter for things retrieved from the database.
	void SetParams(const void *pData, const char *pTextBase, bool mFixBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayTextWidget(pMarketPlace, pApp); }

	void GetBlob(int *pLength, void **ppData);

private:
	char *mpText;
};

#endif // CLSEBAYTEXTWIDGET_INCLUDED