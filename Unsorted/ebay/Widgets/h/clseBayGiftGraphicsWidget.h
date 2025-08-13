/*	$Id: clseBayGiftGraphicsWidget.h,v 1.2 1998/12/06 05:22:34 josh Exp $	*/
//
//	File:	clseBayGiftGraphicsWidget.h
//
//	Class:	clseBayGiftGraphicsWidget
//
//	Author:	Mila Bird
//
//	Function:
//			Shows graphics specific to particular occasion, such as birthday,
//			Christmas, etc.
//			This widget was derived from clseBayWidget by overriding
//			the following routines:
//				* EmitHTML()
//
//			Example code of how to invoke the clseBayGiftGraphicsWidget:
//
//				clseBayGiftGraphicsWidget *idw = new clseBayGiftGraphicsWidget(mpMarketPlace);
//				idw->SetColor("#FFECEA");
//				idw->EmitHTML(mpStream);
//				delete idw;
//
// Modifications:
//				- 10/24/98	mila	 - Created
//
#ifndef CLSEBAYGIFTGRAPHICSWIDGET_INCLUDED
#define CLSEBAYGIFTGRAPHICSWIDGET_INCLUDED

#include <time.h>

#include "clsWidgetHandler.h"
#include "clseBayWidget.h"

class clseBayGiftGraphicsWidget : public clseBayWidget
{

public:

	// Needs marketplace
	clseBayGiftGraphicsWidget(clsMarketPlace *pMarketPlace);
	clseBayGiftGraphicsWidget(clsWidgetHandler *pWidgetHandler, clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayGiftGraphicsWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayGiftGraphicsWidget(pMarketPlace); }
	
	// Emit the HTML for this widget to the specified stream.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void SetImageFilename(char *filename)	{ mpFilename = filename; }

	// set parameters using a vector of strings, with the first string being
	// the widget tagname.
	// the convention is that this routine should handle any parameters it
	// understands, erase (and delete) them from the vector, then call the parent
	// class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

protected:
	virtual bool EmitHTML(ostream *pStream, clsWidgetHandler *)
	{ return EmitHTML(pStream); }

private:
	char *				mpFilename;			// name of file containing image
};

#endif // CLSEBAYGIFTGRAPHICSWIDGET_INCLUDED
