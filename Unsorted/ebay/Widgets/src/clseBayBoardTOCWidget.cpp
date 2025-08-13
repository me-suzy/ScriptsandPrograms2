/*	$Id: clseBayBoardTOCWidget.cpp,v 1.3 1998/12/06 05:22:50 josh Exp $	*/
//
//	File:	clseBayBoardTOCWidget.cpp
//
//	Class:	clseBayBoardTOCWidget
//
//	Author:	Alex Poon
//
//	Function:
//			Widget that shows a table of contents of boards.
//
// Modifications:
//				- 04/03/98	Poon - Created
//

#include "widgets.h"
#include "clseBayBoardTOCWidget.h"

clseBayBoardTOCWidget::clseBayBoardTOCWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mIncludeCategorySpecific = true;
	mIncludeCustomerSupport = true;
	mIncludeGeneral = true;
	mIncludeNews = true;
	mIncludeRestricted = true;
	mIncludeInvisible = false;
}

clseBayBoardTOCWidget::~clseBayBoardTOCWidget()
{
	// don't delete the boards because they are borrowed
}

void clseBayBoardTOCWidget::SetParams(vector<char *> *pvArgs)
{
	int p;
	char *cArg;
	char cArgCopy[256];
	char *cName;
	char *cValue;
	bool handled = false;
	int x;

	// reverse through these so that deletions are safe.
	//  stop at 1, because we don't care about the tagname
	for (p=pvArgs->size()-1; p>=1; p--)
	{
		cArg = (*pvArgs)[p];
		handled = false;

		// separate the name from the value
		strncpy(cArgCopy, cArg, sizeof(cArgCopy)-1);
		cName = cArgCopy;
		cValue = strchr(cArgCopy, '=');
		if (cValue) 
		{
			cValue[0]='\0';		// lock in cName
			cValue++;			// set cValue
		}
		else
			cValue="";

		// remove start & end quotes if they were provided
		x = strlen(cValue);
		if ((x>1) && (cValue[0]=='\"' && cValue[x-1]=='\"'))
		{
			cValue[x-1]='\0';		// remove ending "
			cValue++;				// remove beginning "
		}

		// try to handle this parameter
		if ((!handled) && (strcmp("includecategoryspecific", cName)==0))
		{
			SetIncludeCategorySpecific(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("includecustomersupport", cName)==0))
		{
			SetIncludeCustomerSupport(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("includegeneral", cName)==0))
		{
			SetIncludeGeneral(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("includenews", cName)==0))
		{
			SetIncludeNews(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("includerestricted", cName)==0))
		{
			SetIncludeRestricted(strcmp(cValue,"true")==0);
			handled=true;
		}
		if ((!handled) && (strcmp("includeinvisible", cName)==0))
		{
			SetIncludeInvisible(strcmp(cValue,"true")==0);
			handled=true;
		}

		// if this parameter was handled, remove (and delete the char*) it from the vector
		if (handled)
		{
			pvArgs->erase(pvArgs->begin()+p);	
			delete [] cArg;	// don't need the parameter anymore
		}
	}

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayTableWidget::SetParams(pvArgs);

}


// Get the boards
bool clseBayBoardTOCWidget::Initialize()
{
	
	BulletinBoardVector				*pvBoards;
	BulletinBoardVector::iterator	i;

	// Get the list of All boards
	pvBoards = mpMarketPlace->GetBulletinBoards()->GetBoardVector();

	// Extract the ones we care about and put them into mvBoards.
	for (i = pvBoards->begin(); i != pvBoards->end(); i++)
	{
		// for each board that matches one of the specified criterion,
		//  add it to our list of filtered boards
		if (((*i)->IsCategorySpecific() && mIncludeCategorySpecific) ||
			((*i)->IsCustomerSupport() && mIncludeCustomerSupport) ||
			((*i)->IsGeneral() && mIncludeGeneral) ||
			((*i)->IsNews() && mIncludeNews))
		{
			// add only if it's not restricted or if user has asked for restricted AND
			//          if it's not invisible or if user has asked for invisible
			if (((!(*i)->IsRestricted()) || ((*i)->IsRestricted() && mIncludeRestricted)) &&
				((!(*i)->IsInvisible()) || ((*i)->IsInvisible() && mIncludeInvisible)))
				mvBoards.push_back(*i);
		}

	}

	// Need to set mNumItems so that the table widget will call EmitCell
	//  the correct number of times
	mNumItems = mvBoards.size();

	return true;
}

// This will be called mNumItems times n=0..mNumItems-1
bool clseBayBoardTOCWidget::EmitCell(ostream *pStream, int n)
{
	clsBulletinBoard *pBoard;

	// safety
	if( (n < 0) || (n >= mvBoards.size())) return false;

	// get the board
	pBoard = mvBoards[n];
	
	// begin table cell
	*pStream	<<	"<TD>";

	// name & link
	*pStream	<<	"<strong><a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewBoard)
				<<	"eBayISAPI.dll?ViewBoard&name=\""
				<<	pBoard->GetShortName()
				<<	"\">"
				<<	pBoard->GetName()
				<<	"</a></strong><br>";
	
	// description
	*pStream	<<	"<font size=\"2\">"
				<<	pBoard->GetShortDescription()
				<<	"</font>";

	// end table cell
	*pStream	<<	"</TD>";

	return true;
}


