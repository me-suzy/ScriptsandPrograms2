/*	$Id: clseBayTableWidget.h,v 1.2.350.2 1999/05/25 03:02:54 poon Exp $	*/
//
//	File:	clseBayTableWidget.h
//
//	Class:	clseBayTableWidget
//
//	Author:	Poon
//
//	Function:
//			Abstract base class for eBay widgets that are based on a table layout.
//
//			Use clseBayTableWidget as a base class for your table-based HTML
//			widgets by overriding the following routines:
//
//				(mandatory override)
//				* EmitCell(int n)			= should emit the HTML for data cell n, 
//											  including the <TD> and </TD> tags
//
//				(probable override)
//				* Initialize()				= init before emitting any HTML
//				* EmitPreTable()			= emits pre-table HTML
//
//				(optional override)
//				* EmitsPostTable()			= emits post-table HTML
//				* EmitBeginRowTag(int r)	= emits the begin row tag for row r
//				* EmitBeginTableTag()		= emits the begin table tag
//				* EmitHTML()				= entry point (emits widget)
//				* SetParams()				= set parameters using vector
//
//
//			If your widget needs access to the current marketplace, 
//			database, or app, then pass them into this base class's ctor.
//
//			See clseBayStatsWidget.h/.cpp for an example.
//
// Modifications:
//				- 10/01/97	Poon - Created
//				- 12/22/97	wen	 - Added caption
//
#ifndef CLSEBAYTABLEWIDGET_INCLUDED
#define CLSEBAYTABLEWIDGET_INCLUDED

#include "clseBayWidget.h"

class clseBayTableWidget : public clseBayWidget
{

public:

	clseBayTableWidget(clsMarketPlace *pMarketPlace = NULL,	clsApp *pApp = NULL);
    clseBayTableWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace, clsApp *pApp);

	// Empty dtor.
	virtual ~clseBayTableWidget() { delete [] mpCaption; }
	
	// Emit the HTML for this widget to the specified stream.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	// Set parameters of the table
	void SetNumItems(int NumItems)			{mNumItems = NumItems;}
	void SetNumCols(int NumCols)			{mNumCols = NumCols;}
	void SetTableWidth(int TableWidth)		{mTableWidth = TableWidth;}
	void SetBorder(int Border)				{mBorder = Border;}
	void SetCellPadding(int CellPadding)	{mCellPadding = CellPadding;}
	void SetCellSpacing(int CellSpacing)	{mCellSpacing = CellSpacing;}
	void SetColor(const char *Color)		{strncpy(mColor, Color, sizeof(mColor) - 1);}
	void SetIncremental(bool Incremental)	{mIncremental = Incremental;}
	void SetCaption(const char* pCaption);

	void SetIcon(const char *Icon)			{strncpy(mIcon, Icon, sizeof(mIcon) - 1);}
	void SetIconHeight(int i)				{mIconHeight = i;}
	void SetIconWidth(int i)				{mIconWidth = i;}

	void SetBeginTags(const char *c)		{strncpy(mBeginTags, c, sizeof(mBeginTags) - 1);}
	void SetEndTags(const char *c)			{strncpy(mEndTags, c, sizeof(mEndTags) - 1);}


	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

    virtual void SetParams(const void *pData,
        const char *pStringBase, bool fixBytes);

    virtual long GetBlob(clsDataPool *pDataPool, bool fixBytes);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true) { }

protected:
	virtual void DrawOptions(ostream *pStream);

	// Perform any initialization steps before emitting any HTML.
	//  Should reutrn whether or not intialization was successful.
	virtual bool Initialize();
	
	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	//  Derived classes that do anything useful should override this routine 
	virtual bool EmitCell(ostream *pStream, int n) = 0;

	// Emit HTML before the table
	virtual bool EmitPreTable(ostream *pStream);

	// Emit HTML after the table
	virtual bool EmitPostTable(ostream *pStream);

	// Emit the begin table tag based on the parameters of the table.
	virtual bool EmitBeginTableTag(ostream *pStream);

	// Emit a begin row table tag for row r.
	virtual bool EmitBeginRowTag(ostream *pStream, int r);

	// Emit simple end tags.
	bool EmitEndRowTag(ostream *pStream);
	bool EmitEndTableTag(ostream *pStream);

	// Emit caption
	bool EmitCaption(ostream* pCaption);

	// mCurrentCell and mCurrentRow are exposed to derived widgets as a hack that 
	// allows colspan to work properly
	int					mCurrentCell;
	int					mCurrentRow;

	int					mNumItems;		// default = 0
	int					mNumCols;		// default = 1
	int					mTableWidth;	// in percentage; default = 100
	int					mBorder;		// in pixels; default = 0
	int					mCellPadding;	// in pixels; default = 0
	int					mCellSpacing;	// in pixels; default = 0
	char				mColor[32];		// background color of table; default = ""
	bool				mIncremental;	// enables incremental loading of table; default = false
	char*				mpCaption;
	char				mIcon[64];		// name of pic displayed in front of each cell
	int					mIconHeight;	// in pixels; default = 0
	int					mIconWidth;		// in pixels; default = 0

	char				mBeginTags[1024];		// misc tags that will come right after each <TD>
	char				mEndTags[1024];			// misc tags that will come right before each </TD>


};

#endif // CLSEBAYTABLEWIDGET_INCLUDED
