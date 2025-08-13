/*	$Id: clseBayTableWidget.cpp,v 1.4.206.5 1999/05/25 03:02:55 poon Exp $	*/
//
//	File:	clseBayTableWidget.cpp
//
//	Class:	clseBayTableWidget
//
//	Author:	Poon
//
//	Function:
//			Base class for eBay widgets that are based on a table layout.
//
// Modifications:
//				- 10/01/97	Poon - Created
//				- 12/22/97	wen	 - Added caption
//
#include "widgets.h"

struct clseBayTableWidgetOptions
{
    int32_t mNumItems;
    int32_t mNumCols;
    int32_t mTableWidth;
    int32_t mBorder;
    int32_t mCellPadding;
    int32_t mCellSpacing;
    int32_t mColorOffset;
    int32_t mIncremental;
    int32_t mCaptionOffset;
    int32_t mExpansionOffset;
};

clseBayTableWidget::clseBayTableWidget(clsMarketPlace *pMarketPlace /* = NULL */, 
									  clsApp *pApp /* = NULL */) :
	clseBayWidget(pMarketPlace, pApp)
{
	mNumItems = 0;
	mNumCols = 1;	
	mTableWidth = 100;
	mBorder = 0;
	mCellPadding = 0;
	mCellSpacing = 0;
	mColor[0] = '\0';
	mIncremental = false;
	mpCaption = NULL;

	mIcon[0] = '\0';
	mIconHeight = 0;
	mIconWidth = 0;
	
	mBeginTags[0] = '\0';
	mEndTags[0] = '\0';

	mCurrentCell = 0;
	mCurrentRow = 0;
}

clseBayTableWidget::clseBayTableWidget(clsWidgetHandler *pHandler,
                   clsMarketPlace *pMarketPlace, clsApp *pApp) :
	clseBayWidget(pHandler, pMarketPlace, pApp)
{
    mNumItems = 0;
    mNumCols = 1;
    mTableWidth = 100;
    mBorder = 0;
    mCellPadding = 0;
    mCellSpacing = 0;
    mColor[0] = '\0';
    mIncremental = false;
    mpCaption = NULL;

	mIcon[0] = '\0';
	mIconHeight = 0;
	mIconWidth = 0;
	
	mBeginTags[0] = '\0';
	mEndTags[0] = '\0';

	mCurrentCell = 0;
	mCurrentRow = 0;
}

void clseBayTableWidget::DrawOptions(ostream *pStream)
{
	//char *pStrippedHTML;

	if (mTableWidth != 100)
		*pStream << " tablewidth=" << mTableWidth;

	if (mBorder)
		*pStream << " border=" << mBorder;

	if (mCellPadding)
		*pStream << " cellpadding=" << mCellPadding;

	if (mCellSpacing)
		*pStream << " cellspacing=" << mCellSpacing;

	if (*mColor)
		*pStream << " color=\"" << mColor << "\"";

	if (mpCaption)
	{
		*pStream << " caption=\"";
		clsUtilities::DrawWithEscapedQuotes(pStream, mpCaption);
		*pStream << "\"";
	}

	if (mIncremental)
		*pStream << " incremental";
}

typedef void (clseBayTableWidget::*tTableWidgetIntSetter)(int);
#define apair(x) {#x, clseBayTableWidget::Set##x}
struct {
	const char *str;
	tTableWidgetIntSetter setter;
} tableWidgetIntSetters[] = {
	
	apair(NumItems),
	apair(NumCols),
	apair(TableWidth),
	apair(Border),
	apair(CellPadding),
	apair(CellSpacing),
	{NULL, NULL}
};

typedef void (clseBayTableWidget::*tTableWidgetCharpSetter)(const char *);
struct {
	const char *str;
	tTableWidgetCharpSetter setter;
} tableWidgetCharpSetters[] = {
	apair(Color),
	apair(Caption),
	{NULL, NULL}
};

void clseBayTableWidget::SetParams(vector<char *> *pvArgs)
{

	const char *pV;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " === BEGIN WIDGET " << (*pvArgs)[0] << " ===\n";
	}

	if ((pV = GetParameterValue("NUMITEMS", pvArgs)))
		SetNumItems(atoi(pV));

	if ((pV = GetParameterValue("NUMCOLS", pvArgs)))
		SetNumCols(atoi(pV));

	if ((pV = GetParameterValue("TABLEWIDTH", pvArgs)))
		SetTableWidth(atoi(pV));

	if ((pV = GetParameterValue("BORDER", pvArgs)))
		SetBorder(atoi(pV));

	if ((pV = GetParameterValue("CELLPADDING", pvArgs)))
		SetCellPadding(atoi(pV));

	if ((pV = GetParameterValue("CELLSPACING", pvArgs)))
		SetCellSpacing(atoi(pV));

	if ((pV = GetParameterValue("COLOR", pvArgs)))
		SetColor(pV);

	if ((pV = GetParameterValue("INCREMENTAL", pvArgs)))
		SetIncremental(strcmp(pV, "true") == 0);

	if ((pV = GetParameterValue("ICON", pvArgs)))
		SetIcon(pV);

	if ((pV = GetParameterValue("ICONHEIGHT", pvArgs)))
		SetIconHeight(atoi(pV));

	if ((pV = GetParameterValue("ICONWIDTH", pvArgs)))
		SetIconWidth(atoi(pV));

	if ((pV = GetParameterValue("BEGINTAGS", pvArgs)))
		SetBeginTags(pV);

	if ((pV = GetParameterValue("ENDTAGS", pvArgs)))
		SetEndTags(pV);

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayWidget::SetParams(pvArgs);
}

void clseBayTableWidget::SetParams(const void *pData,
                                   const char *pStringBase, 
                                   bool fixBytes)
{
    clseBayTableWidgetOptions *pOptions;

    pOptions = (clseBayTableWidgetOptions *) pData;

    if (fixBytes)
    {
        pOptions->mNumItems = clsUtilities::FixByteOrder32(pOptions->mNumItems);
        pOptions->mNumCols = clsUtilities::FixByteOrder32(pOptions->mNumCols);
        pOptions->mTableWidth = clsUtilities::FixByteOrder32(pOptions->mTableWidth);
        pOptions->mBorder = clsUtilities::FixByteOrder32(pOptions->mBorder);
        pOptions->mCellPadding = clsUtilities::FixByteOrder32(pOptions->mCellPadding);
        pOptions->mColorOffset = clsUtilities::FixByteOrder32(pOptions->mColorOffset);
        pOptions->mIncremental = clsUtilities::FixByteOrder32(pOptions->mIncremental);
        pOptions->mCaptionOffset = clsUtilities::FixByteOrder32(pOptions->mCaptionOffset);
        // Expansion is unused yet.
//        pOptions->mExpansionOffset = clsUtilities::FixByteOrder32(pOptions->mExpansionOffset);
    }

    mNumItems = pOptions->mNumItems;
    mNumCols = pOptions->mNumCols;
    mTableWidth = pOptions->mTableWidth;
    mBorder = pOptions->mBorder;
    mCellPadding = pOptions->mCellPadding;

    if (pOptions->mColorOffset != -1)
        SetColor(pStringBase + pOptions->mColorOffset);

    mIncremental = pOptions->mIncremental != 0;
    
    if (pOptions->mCaptionOffset != -1)
        SetCaption(pStringBase + pOptions->mCaptionOffset);
}

long clseBayTableWidget::GetBlob(clsDataPool *pDataPool,
                                 bool fixBytes)
{
    clseBayTableWidgetOptions theOptions;

    theOptions.mNumItems = mNumItems;
    theOptions.mNumCols = mNumCols;
    theOptions.mTableWidth = mTableWidth;
    theOptions.mBorder = mBorder;
    theOptions.mCellPadding = mCellPadding;
    theOptions.mIncremental = mIncremental;
    
    if (*mColor)
        theOptions.mColorOffset = pDataPool->AddString(mColor);
    else
        theOptions.mColorOffset = -1;

    if (mpCaption)
        theOptions.mCaptionOffset = pDataPool->AddString(mpCaption);
    else
        theOptions.mCaptionOffset = -1;

    theOptions.mExpansionOffset = -1;

    if (fixBytes)
    {
        theOptions.mNumItems = clsUtilities::FixByteOrder32(theOptions.mNumItems);
        theOptions.mNumCols = clsUtilities::FixByteOrder32(theOptions.mNumCols);
        theOptions.mTableWidth = clsUtilities::FixByteOrder32(theOptions.mTableWidth);
        theOptions.mBorder = clsUtilities::FixByteOrder32(theOptions.mBorder);
        theOptions.mCellPadding = clsUtilities::FixByteOrder32(theOptions.mCellPadding);
        theOptions.mIncremental = clsUtilities::FixByteOrder32(theOptions.mIncremental);
        theOptions.mColorOffset = clsUtilities::FixByteOrder32(theOptions.mColorOffset);
        theOptions.mCaptionOffset = clsUtilities::FixByteOrder32(theOptions.mCaptionOffset);
        theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
    }

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

bool clseBayTableWidget::EmitHTML(ostream *pStream)
{
	bool ok;	// return status
	
	// start off with everything cool...
	ok = true;

	// initialize
	ok = ok && Initialize();

	// emit pre-table HTML.
	ok = ok && EmitPreTable(pStream);

	// emit <TABLE properties> tag. if client asked for incremental load,
	//  then don't emit table tag because it will be emitted for each row.
	if (!mIncremental) ok = ok && EmitBeginTableTag(pStream);

	// emit caption
	if (mpCaption) ok = ok && EmitCaption(pStream);

	// emit the rows and data cells
	mCurrentRow = 0;
	for (mCurrentCell = 0; mCurrentCell < mNumItems; mCurrentCell++)
	{
		// emit a new row if the current row's
		//  cells (columns) are already full
		if (!mNumCols) 
			mNumCols=1;
		if ((mCurrentCell % mNumCols) == 0)
		{
			// end previous row
			if (mCurrentRow > 0)
				ok = ok && EmitEndRowTag(pStream);

			// begin this row
			ok = ok && EmitBeginRowTag(pStream, mCurrentRow);
			mCurrentRow++;
		}

		// emit column for the icon if mIcon is defined
		if (mIcon[0])
		{
			*pStream	<<	"<TD width=\""	<<	mIconWidth+1	<<	"\" valign=\"top\">";
			*pStream	<<	"<img src=\""
						<<	mpMarketPlace->GetImagePath()
						<<	mIcon
						<<	"\" width=\""	<<	mIconWidth	<<	"\" height=\""	<< mIconHeight	<<	"\" border=\"0\">";
			*pStream	<<	"&nbsp;"
						<<	"</TD>";
		}

		// now emit the cell contents, including <TD> and </TD>.
		//  note: derived widgets can increment mCurrentCell and mCurrentRow in EmitCell
		//        in order to make colspan andn rowspan work correctly
		ok = ok && EmitCell(pStream, mCurrentCell);
	}

	// end last row
	if (mCurrentRow > 0)
		ok = ok && EmitEndRowTag(pStream);

	// emit </TABLE> tag
	if (!mIncremental)
		ok = ok && EmitEndTableTag(pStream);

	// emit post-table HTML
	ok = ok && EmitPostTable(pStream);

	return ok;
}

bool clseBayTableWidget::EmitBeginRowTag(ostream *pStream, int /* r */)
{
	// if client wants table to load incrementally, then make
	// each row it's own table
	if (mIncremental)
		EmitBeginTableTag(pStream);

	// emit begin row tag
	*pStream <<		"<TR>\n";

	return true;
}

bool clseBayTableWidget::EmitEndRowTag(ostream *pStream)
{
	// emit end row tag
	*pStream <<		"</TR>\n";

	// if client wants table to load incrementally, then make
	// each row it's own table
	if (mIncremental)
		EmitEndTableTag(pStream);

	return true;
}

bool clseBayTableWidget::EmitBeginTableTag(ostream *pStream)
{
	// create table properties with or without the bgcolor attribute
	*pStream <<		"<TABLE "
			 <<		"border=\""
			 <<		mBorder
			 <<		"\"";

    if (mCellPadding >= 0)
        *pStream
			 <<		" cellpadding=\""
			 <<		mCellPadding
			 <<		"\"";

    if (mCellSpacing >= 0)
        *pStream
			 <<		" cellspacing=\""
			 <<		mCellSpacing
			 <<		"\"";

    if (mTableWidth)
        *pStream
			 <<		" width=\""
			 <<		mTableWidth
			 <<		"%\"";

    if (mColor[0])
        *pStream 
             << " bgcolor=\"" 
             << mColor 
             << "\"";

    *pStream <<		">"
			 <<		"\n";

	return true;
}

bool clseBayTableWidget::EmitEndTableTag(ostream *pStream)
{
	// emit end table tag
	*pStream <<		"</TABLE>\n";

	return true;
}

bool clseBayTableWidget::EmitPreTable(ostream * /* pStream */)
{
	return true;
}

bool clseBayTableWidget::EmitPostTable(ostream * /* pStream */)
{
	return true;
}

bool clseBayTableWidget::Initialize()
{
	return true;
}

void clseBayTableWidget::SetCaption(const char* pCaption)
{
	mpCaption = new char[strlen(pCaption)+1];

	strcpy(mpCaption, pCaption);
}

bool clseBayTableWidget::EmitCaption(ostream *pStream)
{
	if (mpCaption == NULL) return false;

	*pStream	<<	"<caption>"
				<<	mpCaption
				<<	"</caption>";

	return true;
}
