/*	$Id: clseBayCategorySelectorWidget.cpp,v 1.3.238.2 1999/05/25 01:01:54 poon Exp $	*/
//
//	File:	clseBayCategorySelectorWidget.cpp
//
//	Class:	clseBayCategorySelectorWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits a category selector composed of drop-down selection boxes
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()	
//				* SetParams()				
//
// Modifications:
//				- 05/20/98	Poon - Created
//

#include "widgets.h"
#include "clseBayCategorySelectorWidget.h"

clseBayCategorySelectorWidget::clseBayCategorySelectorWidget(clsMarketPlace *pMarketPlace) :
	clseBayWidget(pMarketPlace)
{
	mSelectedCategory = 0;
	mNumColumns = 1;
	strcpy(mMenuPrefix, "category");
	mIncludeNonLeaves = false;
}

void clseBayCategorySelectorWidget::SetParams(vector<char *> *pvArgs)
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
		if ((!handled) && (strcmp("selectedcategory", cName)==0))
		{
			SetSelectedCategory(CategoryId(atoi(cValue)));
			handled=true;
		}
		if ((!handled) && (strcmp("numcolumns", cName)==0))
		{
			SetNumColumns(atoi(cValue));
			handled=true;
		}

		if ((!handled) && (strcmp("menuprefix", cName)==0))
		{
			SetMenuPrefix(cValue);
			handled=true;
		}
		if ((!handled) && (strcmp("includenonleaves", cName)==0))
		{
			SetIncludeNonLeaves(strcmp(cValue,"true")==0);
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

bool clseBayCategorySelectorWidget::EmitHTML(ostream *pStream)
{

	clsCategories				*pCategories;
	CategoryVector				vCategories;
//	CategoryVector::iterator	vI;

	pCategories = mpMarketPlace->GetCategories();

	// Emit Category choice list using mNumColumns columns
	pCategories->EmitHTMLLeafSelectionMultipleDropdown(pStream,
											mSelectedCategory,
											&vCategories,
											mNumColumns, mMenuPrefix, mIncludeNonLeaves, true);
	// Clean up Category Vector
//	for (vI	= vCategories.begin(); vI != vCategories.end(); vI++)
//	{
//		delete	(*vI);
//	}

	vCategories.erase(vCategories.begin(), vCategories.end());
	return true;

}

