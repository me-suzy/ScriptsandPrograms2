/*	$Id: clseBayBoardTOCWidget.h,v 1.2 1998/10/16 01:00:47 josh Exp $	*/
//
//	File:	clseBayBoardTOCWidget.h
//
//	Class:	clseBayBoardTOCWidget
//
//	Author:	Alex Poon
//
//
//  Modifications:
//				- 04/03/98	Poon - Created

#ifndef CLSEBAYBOARDTOCWIDGET_INCLUDED
#define CLSEBAYBOARDTOCWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsBulletinBoards.h"
#include <vector.h>

class clseBayBoardTOCWidget : public clseBayTableWidget
{

public:

	// Bulletin board TOC widget requires having access to the marketplace
	clseBayBoardTOCWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayBoardTOCWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayBoardTOCWidget(pMarketPlace); }

	// Controls which boards should be included in the list. All default to true.
	void SetIncludeCategorySpecific(bool b)	{ mIncludeCategorySpecific = b;}
	void SetIncludeCustomerSupport(bool b)	{ mIncludeCustomerSupport = b;}
	void SetIncludeGeneral(bool b)			{ mIncludeGeneral = b;}
	void SetIncludeNews(bool b)				{ mIncludeNews = b;}
	void SetIncludeRestricted(bool b)		{ mIncludeRestricted = b;}
	void SetIncludeInvisible(bool b)		{ mIncludeInvisible = b;}

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);
	

protected:
	// Get the boards that match the specified criteria and put them into mvBoards
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);

private:

	BulletinBoardVector	mvBoards;

	bool				mIncludeCategorySpecific;		// default = true
	bool				mIncludeCustomerSupport;		// default = true
	bool				mIncludeGeneral;				// default = true
	bool				mIncludeNews;					// default = true
	bool				mIncludeRestricted;				// default = true
	bool				mIncludeInvisible;				// default = false
};

#endif // CLSEBAYBOARDTOCWIDGET_INCLUDED
