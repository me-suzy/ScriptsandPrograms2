/*	$Id: clseBayURLPathWidget.cpp,v 1.3 1998/12/06 05:23:19 josh Exp $	*/
//
//	File:	clseBayURLPathWidget.cpp
//
//	Class:	clseBayURLPathWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits a URL path.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 02/05/98	Poon - Created
//
#include "widgets.h"
#include "clseBayURLPathWidget.h"


clseBayURLPathWidget::clseBayURLPathWidget(clsMarketPlace *pMarketPlace) :
	clseBayWidget(pMarketPlace)
{

	mPage = PageUnknown;
	memset(mKind, 0, sizeof(mKind));
	memset(mURLSuffix, 0, sizeof(mURLSuffix));
}

void clseBayURLPathWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("page", cName)==0))
		{
			SetPage(PageEnum(atoi(cValue)));
			handled=true;
		}
		if ((!handled) && (strcmp("kind", cName)==0))
		{
			SetKind(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("URLsuffix", cName)==0))
		{
			SetURLSuffix(cValue);
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
	clseBayWidget::SetParams(pvArgs);

}

bool clseBayURLPathWidget::EmitHTML(ostream *pStream)
{
	// emit begining quote
	*pStream << "\"";

	// emit path
	if (strcmp(mKind, "HTML")==0)						// emit HTML path
		*pStream <<	mpMarketPlace->GetHTMLPath(mPage);
	else if (strcmp(mKind, "CGI")==0)					// emit CGI path
		*pStream <<	mpMarketPlace->GetCGIPath(mPage);
	else if (strcmp(mKind, "LISTING")==0)				// emit LISTING path
		*pStream <<	mpMarketPlace->GetListingPath();
	else
		*pStream <<	mpMarketPlace->GetCGIPath(mPage);	// default, emit CGI path

	// emit URL suffix
	*pStream << mURLSuffix;

	// emit ending quote
	*pStream << "\"";

	return true;

}

