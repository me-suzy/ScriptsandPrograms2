/*	$Id: clseBayWidget.h,v 1.2.390.1 1999/08/01 02:51:22 barry Exp $	*/
//
//	File:	clseBayWidget.h
//
//	Class:	clseBayWidget
//
//	Author:	Poon
//
//	Function:
//			Abstract base class for eBay widgets.
//			
//			Use clseBayWidget a base class for your widgets by simply overriding
//			the EmitHTML() routine to emit whatever HTML you want your
//			widget to emit.
//
//			If your widget needs access to the current marketplace 
//			or app, then pass them into this base class's ctor.
//
//			Note: if your widget outputs table-based HTML, then you can
//			make your life easier by deriving your widget class from
//			clseBayTableWidget, rather than clseBayWidget.
//
// Modifications:
//				- 10/01/97	Poon - Created
//
#ifndef CLSEBAYWIDGET_INCLUDED
#define CLSEBAYWIDGET_INCLUDED

#include <iostream.h>

#include "vector.h"
#include "list.h"

// Class forwards
class clsMarketPlace;
class clsApp;
class clsDataPool;
class clsWidgetHandler;

class clseBayWidget
{

public:
	friend clsWidgetHandler;

	// Widget subclasses should pass non-NULL values to this ctor if it needs them
	//  to work (e.g. a widget that outputs just some graphics might only
	//  need the marketplace).
	clseBayWidget(clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL);
	clseBayWidget(clsWidgetHandler *pWidgetHandler, clsMarketPlace *pMarketPlace = NULL,
		clsApp *pApp = NULL);

	// Empty dtor
	virtual ~clseBayWidget() {};
	
	// Emit the HTML for this widget to the specified stream.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream) = 0;

	// set parameters using the given blob
	virtual void SetParams(int length, const void *data)
	{ return; }

	// Set parameters using the blob pData. Text offsets are from pStringBase,
	// and if mFixBytes is true, parameters need their byte order reversed.
	virtual void SetParams(const void *pData, const char *pStringBase, bool mFixBytes)
    { };

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, zero the first byte to indicate they've been eaten, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	virtual void SetParams(vector<char *> *pvArgs) { }

	// create and return a blob based on the current parameters
	virtual void GetBlob(int *pLength, void **ppData)
	{ *pLength = 0; *ppData = NULL; return; }
    // create a blob and return its offset in the clsDataPool.
    virtual long GetBlob(clsDataPool *pDataPool, bool mReverseBytes)
    { return 0; }

	virtual void DrawTag(ostream *pStream, const char *pName);

	int GetType() { return mType; }

	// turn on performance stats logging to specified output stream
	void SetLoggingStream(ostream *o) { mpLoggingStream = o; }
	

protected:
	virtual bool EmitHTML(ostream *pStream, clsWidgetHandler *)
	{ return EmitHTML(pStream); }


    const char *GetParameterValue(const char *pName, vector<char *> *pParams);

	clsApp*				mpApp;
	clsMarketPlace*		mpMarketPlace;
	clsWidgetHandler*	mpWidgetHandler;
	ostream*			mpLoggingStream;

private:
	void SetType(int wType) { mType = wType; }

	int	mType;	
};

#endif // CLSEBAYWIDGET_INCLUDED
