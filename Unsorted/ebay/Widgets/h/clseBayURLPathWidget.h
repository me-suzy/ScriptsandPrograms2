/*	$Id: clseBayURLPathWidget.h,v 1.2 1998/10/16 01:01:15 josh Exp $	*/
//
//	File:	clseBayURLPathWidget.h
//
//	Class:	clseBayURLPathWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits a URL using a path decided upon at runtime.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()	
//				* SetParams()		
//
// Modifications:
//				- 02/05/98	Poon - Created
//
#ifndef CLSEBAYURLPATHWIDGET_INCLUDED
#define CLSEBAYURLPATHWIDGET_INCLUDED

#include "eBayPageTypes.h"
#include "clseBayWidget.h"

class clseBayURLPathWidget : public clseBayWidget
{

public:

	// URL widget needs marketplace.
	clseBayURLPathWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayURLPathWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayURLPathWidget(pMarketPlace); }


	void SetPage(PageEnum page)			{ mPage = page; }
	void SetKind(char *c)				{ strncpy(mKind, c, sizeof(mKind) - 1);}
	void SetURLSuffix(char *c)			{ strncpy(mURLSuffix, c, sizeof(mURLSuffix) - 1);}

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);
	
	// Emit the HTML. Incluces the beginning and ending quotes.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

protected:

private:

	PageEnum			mPage;				// the page you want the path for
	char				mKind[32];			// HTML or CGI
	char				mURLSuffix[256];	// text appended to the end of the generated path

};

#endif // CLSEBAYURLPATHWIDGET_INCLUDED
