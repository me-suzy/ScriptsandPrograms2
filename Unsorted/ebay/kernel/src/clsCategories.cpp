/*	$Id: clsCategories.cpp,v 1.13.22.12.40.7 1999/08/04 16:51:28 nsacco Exp $	*/
//
//	File:	clsCategories.cpp
//
//	Class:	clsCategories
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				The repository for all marketplaces
//
//	Modifications:
//				  mm/dd/yy
//				- 05/07/97 michael	- Created
//				- 07/01/97 tini		- modified for hierarchical categories
//				- 07/23/97 wen		- added GetLinkPath
//				- 09/17/97 poon		- added EmitHTMLLeafSelectionMultipleDropdown
//				- 11/14/97 charles	- added a test in GetCategory() 
//				- 05/01/99 bill		- modified EmitHTMLJavascriptCategorySelector()
//				- 05/11/99 jnace	- added region ID parameter to GetCategoryOpenItemCounts
//				- 06/03/99 petra	- split off clsCategoriesSite; cleaned up unused functions.
//				- 08/03/99 nsacco	- Use mpMarketPlace->GetCurrentSiteId().
//

#include "eBayKernel.h"
#include "clsCategories.h"
#include "clsSites.h"
#include "clsListingFileName.h"

//
// returns qualified name of a category
// qualified name is the name of the ancestor(s) as well as the category itself
// 0 refers to the category itself; 1 to its parent; 2 its grandparent, etc.
//
clsQualifiedCategoryName::clsQualifiedCategoryName(char *pName0,
												   char *pName1,
												   char *pName2,
												   char *pName3,
												   char *pName4,
												   CategoryId pId0,
												   CategoryId pId1,
												   CategoryId pId2,
												   CategoryId pId3,
												   CategoryId pId4)
{
	if (pName0)
	{
		names[0] 
			= new char[strlen(pName0) + 1];
		strcpy(names[0], pName0);
	}
	else
	{
		names[0]	= new char[1];
		*(names[0])	= 0x00;
	}

	if (pName1)
	{
		names[1] 
			= new char[strlen(pName1) + 1];
		strcpy(names[1], pName1);
	}
	else
	{
		names[1]	= new char[1];
		*(names[1])	= 0x00;
	}

	if (pName2)
	{
		names[2] 
			= new char[strlen(pName2) + 1];
		strcpy(names[2], pName2);
	}
	else
	{
		names[2]	= new char[1];
		*(names[2])	= 0x00;
	}

	if (pName3)
	{
		names[3] 
			= new char[strlen(pName3) + 1];
		strcpy(names[3], pName3);
	}
	else
	{
		names[3]	= new char[1];
		*(names[3])	= 0x00;
	}

	if (pName4)
	{
		names[4] 
			= new char[strlen(pName4) + 1];
		strcpy(names[4], pName4);
	}
	else
	{
		names[4]	= new char[1];
		*(names[4])	= 0x00;
	}

	ids[0] = pId0;
	ids[1] = pId1;
	ids[2] = pId2;
	ids[3] = pId3;
	ids[4] = pId4;
}


//
// Default Constructor
//
clsCategories::clsCategories(clsMarketPlace *pMarketPlace) : mpvCategoryOpenItemCounts(NULL)
{
	mpMarketPlace	= pMarketPlace;
	// clear region for mpvCategoryOpenItemCounts
	mRegionID = 0;
	
	// set the category cache to be dirty
	mpCategoriesSiteCache = NULL;
	mCategoriesSiteCacheSize = 0;
	mDirtyCache = true;
	
	mpListingFileName = new clsListingFileName(mpMarketPlace);

	// fill the adult category cache
	gApp->GetDatabase()->GetAdultCategoryIds(mpMarketPlace, &mvAdultCategories);

	return;
}

//
// Destructor
//
clsCategories::~clsCategories()
{
	delete mpListingFileName;

	ClearOpenItemCounts();

	mvAdultCategories.erase(mvAdultCategories.begin(), mvAdultCategories.end());

	PurgeCategoriesSiteCache();

	return;
}

bool clsCategories::IsDescendant(CategoryId parent, CategoryId child)
{
    clsCategory *pChild;

	// get the category from the cache
	pChild = GetCategory(child, true);

	// child doesn't even exist
	if (!pChild)
		return false;

	// check each ancestor of the child to see if the proposed parent
	//  is one of them
    return (parent == pChild->GetLevel1() ||
        parent == pChild->GetLevel2() ||
        parent == pChild->GetLevel3() ||
        parent == pChild->GetLevel4());
}

//
// GetCategory
//	Get a category by Category Id
// If using cache, caller should not delete the categories--they are owned by clsCategoriesSite
clsCategory *clsCategories::GetCategory(CategoryId id, bool useCache /*=false*/)
{
	int siteId = mpMarketPlace->GetCurrentSiteId();

	// using the cache is faster, and caller need not delete the returned category
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoriesSiteCache();

		// safety
		if ((siteId < 0) || (siteId >= mCategoriesSiteCacheSize)) 
			return NULL;

		// sanity
		if (!mpCategoriesSiteCache) 
			return NULL;

		// return the cached array element
		return mpCategoriesSiteCache[siteId]->GetCategory(id, useCache);
	}

	// get the category from the database instead. this is slower and caller
	//  must delete the returned category
	return gApp->GetDatabase()->GetCategoryById(mpMarketPlace->GetId(), id, siteId);
}


// Default category is top level miscellaneous
// If using cache, caller should not delete the categories--they are owned by clsCategoriesSite
clsCategory *clsCategories::GetCategoryDefault(bool useCache /*=false*/)
{
	return GetCategory(324, useCache);
}

// Get the first category in the hierarchy; i.e. top level, prevCategory =0
clsCategory *clsCategories::GetCategoryRoot()
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();
	return gApp->GetDatabase()->GetCategoryFirst(
							mpMarketPlace->GetId(),
							0,
							1,
							siteId);

}

// Get the first category in the hierarchy; i.e. top level, prevCategory =0
clsCategory *clsCategories::GetCategoryFirstChild(clsCategory *pCategory)
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();
	return gApp->GetDatabase()->GetCategoryFirst(
							mpMarketPlace->GetId(),
							pCategory->GetId(),
							2,
							siteId);
}

// petra removed void clsCategories::AddCategoryHierarchy(clsCategory *pCategory,
//							 clsCategory *pParent, 
//							 clsCategory *pNext,
//							 clsCategory *pPrev)
//		(see E117 to restore)

// petra removed void clsCategories::AddCategoryChild(clsCategory *pCategory, CategoryId parent)
//		(see E117 to restore)

// petra removed void clsCategories::AddCategoryBefore(clsCategory *pCategory, 
//						  CategoryId nextid)
//		(see E117 to restore)

// petra removed void clsCategories::AddCategoryAfter(clsCategory *pCategory,
//						 CategoryId previd)
//		(see E117 to restore)

// petra removed void clsCategories::UpdateCategory(clsCategory *pCategory)
//		(see E117 to restore)

// petra removed void clsCategories::DeleteCategory(clsCategory *pCategory)
//		(see E117 to restore)

// petra removed void clsCategories::MoveCategoryItems(clsCategory *pCategory, CategoryId newParent)
//		(see E117 to restore)


// Get parent of a category
clsCategory *clsCategories::Parent(clsCategory *pCategory, bool useCache /*=false*/)
{
// actually, GetLevel1 of category.
	return GetCategory(pCategory->GetLevel1(), useCache);
}


// petra removed clsCategory *clsCategories::PrevSibling(clsCategory *pCategory, bool useCache /*=false*/)
// 		(see E117 to restore)

// petra removed clsCategory *clsCategories::NextSibling(clsCategory *pCategory, bool useCache /*=false*/)
//		(see E117 to restore)

//
// getting category vectors use the same set of
// declarations, etc; only the sql statement differ.
// all = 1; topLevel = 2
// children = 3, descendants = 4, siblings = 5, leaves = 6
//

// get all categories from the database, sorted by order no
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::All(CategoryVector *pvCategories, bool useCache /*=false*/)
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoriesSiteCache();
		
		// sanity
		if (!mpCategoriesSiteCache) 
			return;
		
		mpCategoriesSiteCache[siteId]->All(pvCategories, useCache);
		return;
	}

	// get the categories form the database instead. this is slower and caller
	//  must delete the returned categories
	pvCategories->reserve(1100);
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 1, pvCategories, siteId);
	return;
}


// retained for backwards compatibility
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::AllSorted(CategoryVector *pvCategories, bool useCache /*=false*/)
{
	All(pvCategories, useCache);

	return;
}

// finds all top level categories
void clsCategories::TopLevel(CategoryVector *pvCategories, bool useCache /*=false*/)
{	

	int	siteId = mpMarketPlace->GetCurrentSiteId();

	// for efficiency, tell the vector approximately how big it will be
	pvCategories->reserve(12);

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoriesSiteCache();

		// sanity
		if (!mpCategoriesSiteCache)
			return;

		mpCategoriesSiteCache[siteId]->TopLevel(pvCategories, useCache);
		return;
	}

	// get the categories form the database instead. this is slower and caller
	//  must delete the returned categories
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 2, pvCategories, siteId);

	return;
}


// find all leaf categories
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::Leaves(CategoryVector *pvCategories, bool useCache /*=false*/)
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoriesSiteCache();

		// sanity
		if (!mpCategoriesSiteCache)
			return;

		mpCategoriesSiteCache[siteId]->Leaves(pvCategories, useCache);
		return;
	}

	// get the categories form the database instead. this is slower and caller
	//  must delete the returned categories
	pvCategories->reserve(1100);
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 6, pvCategories, siteId);
	return;


}

// list of the not leaf category ids
void clsCategories::GetNotLeafCategoryIds(vector<int>* pvCatIds)
{
	gApp->GetDatabase()->GetNotLeafCategoryIds(
		mpMarketPlace->GetId(),
		pvCatIds);
}

// selects all the children and returns a category vector
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::Children(CategoryVector *pvCategories,  clsCategory *pCategory, bool useCache /*=false*/)
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache) 
			PopulateCategoriesSiteCache();
		
		// sanity
		if (!mpCategoriesSiteCache)
			return;

		mpCategoriesSiteCache[siteId]->Children(pvCategories, pCategory, useCache);
		return;
	}

	// get the categories from the database instead. this is slower and caller
	//  must delete the returned categories
	gApp->GetDatabase()->
		GetCategoryVector(pCategory->GetMarketPlaceId(), 
				pCategory->GetId(), 3, pvCategories, siteId);
	return;
}


// selects all the children and returns a category vector - sorted
void clsCategories::ChildrenSorted(CategoryVector *pvCategories, 
							 clsCategory *pRoot)
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();

	gApp->GetDatabase()->GetCategoryVector(pRoot->GetMarketPlaceId(),
				pRoot->GetId(), 7, pvCategories, siteId);

	return;
}


// find all descendants of a category - ordered & depth-first.
// if pCategory==NULL, return all categories & subcategories.
void clsCategories::DescendantsOrdered(CategoryVector *pvCategories, clsCategory *pParent)
{
	int	siteId = mpMarketPlace->GetCurrentSiteId();

	if (mDirtyCache)
		PopulateCategoriesSiteCache();

	// safety
	if ((siteId < 0) || (siteId >= mCategoriesSiteCacheSize))
		return;
	
	// sanity
	if (!mpCategoriesSiteCache)
		return;

	mpCategoriesSiteCache[siteId]->DescendantsOrdered(pvCategories, pParent);

	return;
}


// petra removed void clsCategories::Siblings(CategoryVector *pvCategories, clsCategory *pCategory)
//		(see E117 to restore)

// petra removed void clsCategories::SiblingSorted(CategoryVector *pvCategories, clsCategory *pRoot)
//		(see E117 to restore)
		
// Gets all ancestors of a given category
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::Ancestors(CategoryVector *pvCategories,
							  clsCategory *pCategory, bool useCache /*=false*/)
{
	clsCategory		*pNewCategory;

	CategoryId pAncestorId;

	pAncestorId = pCategory->GetLevel1();

	// check if it has parent
	if (pAncestorId != 0)
	{
		pNewCategory = GetCategory(pAncestorId, useCache);

		pvCategories->push_back(pNewCategory);

		// check if it has grandparent
		pAncestorId = pCategory->GetLevel2();
		if (pAncestorId != 0)
		{
			pNewCategory = GetCategory(pAncestorId, useCache);

			pvCategories->push_back(pNewCategory);

			// check if it has great grandparent
			pAncestorId = pCategory->GetLevel3();
			if (pAncestorId != 0)
			{
				pNewCategory = GetCategory(pAncestorId, useCache);

				pvCategories->push_back(pNewCategory);

				// check if it has great great grandparent - should be null in most cases
				pAncestorId = pCategory->GetLevel4();
				if (pAncestorId != 0)
				{
					pNewCategory = GetCategory(pAncestorId, useCache);

					pvCategories->push_back(pNewCategory);
				}
			}
		}
	}

	return;

}



// Gets fully qualified category name (up to 4 levels only including self)
// name[0] is name of the category;
// name[1] is name of its parent;
// name[2] is name of its grandparent;
// name[3] is name of its great grandparent;
clsQualifiedCategoryName *clsCategories::GetQualifiedName(clsCategory *pCategory)
{

	return new clsQualifiedCategoryName(
		pCategory->GetName(),
		pCategory->GetName1(),
		pCategory->GetName2(),
		pCategory->GetName3(),
		pCategory->GetName4(),
		pCategory->GetId(),
		pCategory->GetLevel1(),
		pCategory->GetLevel2(),
		pCategory->GetLevel3(),
		pCategory->GetLevel4());
}

void clsCategories::EmitHTMLQualifiedName(ostream *pStream, clsCategory *pCategory)
{
	clsQualifiedCategoryName	*pQualifiedName;

	pQualifiedName	= GetQualifiedName(pCategory);

	if (*(pQualifiedName->names[3]))
	{
		*pStream <<	pQualifiedName->names[3];
		if (*(pQualifiedName->names[2]))
			*pStream <<	":";
	}

	if (*(pQualifiedName->names[2]))
	{
		*pStream <<	pQualifiedName->names[2];
		if (*(pQualifiedName->names[1]))
			*pStream <<	":";
	}

	if (*(pQualifiedName->names[1]))
	{
		*pStream <<	pQualifiedName->names[1];
		if (*(pQualifiedName->names[0]))
			*pStream <<	":";
	}

	if (*(pQualifiedName->names[0]))
		*pStream <<	pQualifiedName->names[0];

	delete	pQualifiedName;
}


//
//
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::EmitHTMLLeafSelectionList(ostream *pStream,
											  char *pListName,
											  CategoryId selectedValue,
											  char *pUnSelectedValue,
											  char *pUnSelectedLabel,
											  CategoryVector *vCategories,
											  bool includeNonLeaves /* = false */,
											  bool useCache /* = false */)
{
	CategoryVector::iterator	i;
	bool						foundit	= false;

	// Let's get them if its empty vector
	if (vCategories->size() < 1)
	{
		if (includeNonLeaves)
		{
			All(vCategories, useCache);
		}
		else
		{
			Leaves(vCategories, useCache);
		}
	}

	// Emit the first part
	*pStream	<<	"<SELECT SIZE=15 NAME=\""
				<<	pListName
				<<	"\"> ";

	//
	// Emit the unselected value first
	//
	if (pUnSelectedValue != NULL &&
		pUnSelectedLabel != NULL)
	{
		*pStream <<	"<OPTION SELECTED "
					"VALUE = "
					"\""
				 <<	pUnSelectedValue
				 <<	"\""
					">"
				 <<	pUnSelectedLabel
				 <<	"</OPTION>\n";
	}


	// Now, emit the items
	for (i = vCategories->begin();
		 i != vCategories->end();
		 i++)
	{

		if (!(*i)->GetIsExpired())
		{
			*pStream <<	"<OPTION ";
		
			if (!foundit &&
				selectedValue == (*i)->GetId())
			{
				*pStream <<	"SELECTED ";
				foundit	= true;
			}
		
		
			*pStream <<	" VALUE=\""
				 <<	(int)(*i)->GetId()
				 <<	"\">";

			// Emit the "label", which is a concatenation
			// of the various level names.

			// Let's get the qualified name
			EmitHTMLQualifiedName(pStream, (*i));

			*pStream << "</OPTION>\n";
		}
	}

	*pStream <<	"</SELECT>";

	return;
}


// top level categories only
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::EmitHTMLTopSelectionList(ostream *pStream,
											  char *pListName,
											  CategoryId selectedValue,
											  char *pUnSelectedValue,
											  char *pUnSelectedLabel,
											  CategoryVector *vCategories,
											  bool useCache /* = false */)
{
	//CategoryVector vCategories;
	CategoryVector::iterator	i;
	bool			foundit	= false;

	if (vCategories->size() < 1)
	{
		TopLevel(vCategories, useCache);
	}

	// Emit the first part
	*pStream	<<	"<SELECT SIZE=5 NAME=\""
				<<	pListName
				<<	"\"> ";

	//
	// Emit the unselected value first
	//
	if (pUnSelectedValue != NULL &&
		pUnSelectedLabel != NULL)
	{
		*pStream <<	"<OPTION ";
		
		if (selectedValue == 0)
			*pStream << "SELECTED ";

		*pStream <<	"VALUE = "
					"\""
				 <<	pUnSelectedValue
				 <<	"\""
					">"
				 <<	pUnSelectedLabel
				 <<	"</OPTION>\n";
	}


	// Now, emit the items
	for (i = vCategories->begin();
		 i != vCategories->end();
		 i++)
	{

		if (!(*i)->GetIsExpired())
		{
			*pStream <<	"<OPTION ";

			if (!foundit &&
				selectedValue == (*i)->GetId())
			{
				*pStream <<	"SELECTED ";
				foundit	= true;
			}
		
			*pStream <<	" VALUE=\""
				 <<	(int)(*i)->GetId()
				 <<	"\">";

			// Emit the "label", which is a concatenation
			// of the various level names.

			// Let's get the qualified name
			EmitHTMLQualifiedName(pStream, (*i));

			*pStream << "</OPTION>\n";
		}
	}

	*pStream <<	"</SELECT>";

	return;
}

//
// Author: poon
// Note: the caller is responsible for the memory pointed by vCategories (unless useCache is set to true)
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::EmitHTMLLeafSelectionMultipleDropdown(ostream *pStream,
											  CategoryId selectedValue,
											  CategoryVector *vCategories,
											  int numColumns,
											  char *menuNamePrefix /* = "category" */,
											  bool includeNonLeaves /* = false */,
											  bool useCache /* = false */)
{
	CategoryVector::iterator	i;
	char currentTopLevelName[64];
	int numMenus = 0;
	char* names[5];

	// Calculate width of each column of the table
	int width = 100 / numColumns;
	char cWidth[5];
	sprintf(cWidth, "%d", width);

	// Initialize currentTopLevelName to the empty string
	currentTopLevelName[0] = '\0';

	// Let's get them if its empty vector
	if (vCategories->size() < 1)
	{
		if (includeNonLeaves)
		{
			All(vCategories, useCache);
		}
		else
		{
			Leaves(vCategories, useCache);
		}
	}

	// Emit the begin table tag first
	*pStream	<<	"<table border=0 cellpadding=0 cellspacing=6 width=100%>\n";

	// Now, go through each of the leaves
	for (i = vCategories->begin(); i != vCategories->end(); i++)
	{
		if (!(*i)->GetIsExpired())
		{

			// the names for this category.
			//  note: the reason that I didn't create a clsQualifiedCategoryName
			//   to do this is that I don't want to have to create/delete 400
			//   or so clsQualifiedCategoryName objects, especially since the
			//   ctor of class clsQualifiedCategoryName does 5 new()'s and
			//   5 strcpy()'s.
			names[0] = (*i)->GetName();
			names[1] = (*i)->GetName1();
			names[2] = (*i)->GetName2();
			names[3] = (*i)->GetName3();
			names[4] = (*i)->GetName4();


			// find the index of the top-level (root) name of this fully
			//  qualified category name (remember 0 is the leaf name,
			//  (1 is the parent's name, 2 is the grandparent's name, etc.)
			int x = 4;
			while ((x>=0) && ((names[x])[0] == '\0'))
				x--;
			// ASSERT(x>=0);
			if (x < 0)
				continue;	// should never happen

			// check to see if the top-level category has changed
			if (strcmp(currentTopLevelName, names[x]) != 0)
			{

				// we changed of top-level category, so we need to generate
				//  a new drop-down menu in a new table cell (column),
				//  using the name of the top-level category for the name
				//  of the drop-down menu

				// end the previous drop-down menu (unless this is the first one)
				if (numMenus > 0)
				{
					*pStream <<		"\n"
									"</SELECT></TD>"
									"\n";
				}

				// next, we may have to generate a new row if the current row's
				//  cells (columns) are already full
				if (((numMenus) % numColumns) == 0)
				{
					// end the previous row (unless this is the first row)
					if (numMenus > 0)
					{
						*pStream <<		"\n</tr>\n";
					}

					// start a new row
					*pStream <<		"<tr>\n";
				}
				

				numMenus++;				// increment number of menus

				// create menu name
				char menuName[16];
				sprintf(menuName, "%s%d", menuNamePrefix, numMenus);
				
				// now we generate the new drop-down menu
				*pStream <<		"<TD width="
						 <<		cWidth
						 <<		"% "
								"valign=middle><FONT size=2>"
						 <<		names[x]
						 <<		":"
								"</FONT>"
								"<br>";
				*pStream <<		"\n"
								"<SELECT name=\""
						 <<		menuName
						 <<		"\" size=1>";

				// add a blank item with an option value of 0 to be the 
				//  first item each drop-down menu
				*pStream <<		"\n"
								"<OPTION value=\"0\""
						 <<		(((*i)->GetId() == 0) ? " selected" : "")
						 <<		">-</OPTION>";

				// add the first "real" item to the list

				// first, emit the category id as the value,
				//  and add the selected tag if it's the default
				*pStream <<		"\n"
								"<OPTION value=\""
						 <<		(int)(*i)->GetId()
						 <<		"\""
						 <<		(((*i)->GetId() == selectedValue) ? " selected" : "")
						 <<		">";

				// next, emit the category name sans the root name
				if (x==0)
				{
					*pStream <<		"All "
							 <<		names[0];
				}
				else
				{
					// start at x-1 because we don't need to show top-level name
					for (int j=x-1; j>=0; j--)
					{
						*pStream <<		((j==(x-1)) ? "" : ": ")
								 <<		names[j];
					}
				}

				// finally, emit end option tag
				*pStream <<		"</OPTION>";

			}
			else
			{
				// still in the same top-level category, so add this
				//  leaf to the current drop-down menu

				// first, emit the category id as the value,
				//  and add the selected tag if it's the default
				*pStream <<		"\n"
								"<OPTION value=\""
						 <<		(int)(*i)->GetId()
						 <<		"\""
						 <<		(((*i)->GetId() == selectedValue) ? " selected" : "")
						 <<		">";

				// next, emit the category name sans the root name
				for (int j=x-1; j>=0; j--)
				{
					*pStream <<		((j==(x-1)) ? "" : ": ")
							 <<		names[j];	//lint !e676 Lint is crazy, thinks j can be -1
				}

				// finally, emit end option tag
				*pStream <<		"</OPTION>";
			}

			// update the currentTopLevelName
			strcpy(currentTopLevelName, names[x]);

		}
	}

	*pStream <<	"</SELECT></TD></TR>";


	
	// Finish off the table
	*pStream	<<	"</table>\n";

	return;
}

static const char cStaticJavascriptCode1[] =
"<script LANGUAGE=\"JavaScript1.1\">\n"
"<!-- the message below will display only on non-js1.1 browsrs -->\n"
"<!-- --> <hr><h1>This page requires JavaScript 1.1</h1>\n"
"<!-- --> Please consider using Netscape 3.0/4.0 or Internet Explorer 4.0\n"
"<!-- This HTML comment hides the script from non-js1.1 browsers\n"
"\n";

static const char cStaticJavascriptCode2[] =
"function initMenu%s(Id)\n"
"{\n"
"  var CatIdArray = new Array;\n"
"  var ChosenCatIdArray = new Array;\n"
"\n"
"  var CategoryId;\n"
"  var chosenCategory;\n"
"  var numChildren;\n"
"  var CatId;\n"
"  var CatName;\n"
"  var level;\n"
"  var index;\n"
"  var i;\n"
"  var j;\n"
"\n"
"  // make sure the CategoryId is valid\n"
"  if (n[Id] == null)\n" 
"  {\n" 
"    changeMenu%s(-1);\n"
"    return;\n"
"  }\n"
"\n"
"  // initilize\n"
"  CategoryId = Id;\n"
"\n"
"  // category leaf-level = 0\n"
"  level = 0;\n"
"\n"
"  // loop through category list until reaching c[0]\n"
"  while (CategoryId != 0 && level < 4)\n"
"  {\n"
"	ChosenCatIdArray[level] = CategoryId;\n"
"\n"
"  outerloop:\n"
"    for (i = 0; i < c.length; i++)\n"
"    {\n"
"	  if (c[i] == null)\n"
"	    continue;\n"
"\n"
"      for (j = 0; j < c[i].length; j++)\n"
"      {\n"
"        if (c[i][j] == CategoryId)\n"
"        {\n"
"          CategoryId = i;\n"
"          CatIdArray[level] = CategoryId;\n"
"    	  break outerloop;\n"
"	    }\n"
"	  }\n"
"\n"
"    }\n"
"\n"
"    level++;\n"
"  }\n"
"\n"
"  // if can't reach the top-level of categories, return\n"
"  if (CategoryId != 0)\n"
"  {\n"
"    changeMenu%s(-1);\n"
"    return;\n"
"  }\n"
"\n"
"  // reverse the array, so category root-level = 0\n"
"  CatIdArray.reverse();\n"
"  ChosenCatIdArray.reverse();\n"
"\n"
"  // display categories\n"
"  for (index = 0 ; index < CatIdArray.length; index++)\n"
"  {\n"
"    CatMenu%s[index].length = 0;\n"
"\n"
"    chosenCategory = CatIdArray[index];\n"
"	 numChildren = c[chosenCategory].length;\n"
"\n"
"    for (i = 0; i < numChildren; i++)\n"
"    {\n"
"      CatId = c[chosenCategory][i];\n"
"      CatName = (c[CatId]==null) ? n[CatId] : n[CatId]+ \" ->\";\n"
"      CatMenu%s[index].options[i] = new Option(CatName, CatId);\n"
"\n"
"      if (CatId == ChosenCatIdArray[index])\n"
"	     CatMenu%s[index].options[i].selected = true;\n"
"    }\n"
"\n"
"    CatMenu%s[index].length = numChildren;\n"
"\n"
"    // add a blank entry to the end of columnns\n"
"    if (index > 0)\n"
"	   CatMenu%s[index].options[numChildren] = new Option(\"-----------------------------------\");\n"
"  }\n"
"\n"
"  // clear out rest of the empty menus, if there are some\n"
"  for (i = index; i < CatMenu%s.length; i++)\n"
"  {\n"
"    CatMenu%s[i].length = 0;\n"
"    CatMenu%s[i].options[0] = new Option(\"-----------------------------------\");\n"
"  }\n"
"\n"
"}\n"
"\n";

static char cStaticJavascriptCode3[] =
"function changeMenu%s(cm)\n"
"{\n"
"  var chosenCategory;\n"
"  var numChildren;\n"
"  var CatId;\n"
"  var CatName;\n"
"  var i;\n"
"\n"
"  // make sure user didn't click on an empty box\n"
"  if ((cm != -1) && (CatMenu%s[cm].length <= 1))\n"
"    return;\n"
"\n"
"  // get the chosen category from this CatMenu\n"
"  if (cm == -1) \n"
"    chosenCategory = 0;\n"
"  else\n"
"  {\n"
"    if (CatMenu%s[cm].selectedIndex == -1)\n"
"      return;\n"
"    chosenCategory = CatMenu%s[cm].options[CatMenu%s[cm].selectedIndex].value;\n"
"  }\n"
"\n"
"  // if no children, then user is done; \n"
"  if (c[chosenCategory]==null)\n"
"  {\n"
"    document.%s.%s.value = chosenCategory;\n"
"    numChildren = 0;\n"
"  }\n"
"  else\n"
"  {\n"
"    if (cm != -1) \n"
"    {\n"
"      if (document.%s.name != \"ChangePreferencesShow\")\n"
"        document.%s.%s.value = \"\";\n"
"      else\n"
"        document.%s.%s.value = chosenCategory;\n"
"    }\n"
"    numChildren = c[chosenCategory].length;\n"
"  }\n"
"\n"
"  // fill up the next based on the chosenCategory\n"
"  if ((cm+1) < CatMenu%s.length)\n"
"  {\n"
"    for (i = 0; i < numChildren; i++)\n"
"    {\n"
"      CatId = c[chosenCategory][i];\n"
"      CatName = (c[CatId]==null) ? n[CatId] : n[CatId]+ \" ->\";\n"
"      CatMenu%s[cm+1].options[i] = new Option(CatName, CatId);\n"
"    }\n"
"    CatMenu%s[cm+1].length = numChildren;\n"
"  }\n"
"\n"
"  // clear out all menus to the right of this menu\n"
"  for (i = cm+2; i < CatMenu%s.length; i++)\n"
"  {\n"
"    CatMenu%s[i].length = 0;\n"
"  }\n"
"\n"
"  // add a blank entry to the end of each columnn that was affected\n"
"  for (i = cm+1; i < CatMenu%s.length; i++)\n"
"  {\n"
"    if (i) \n"
"      CatMenu%s[i].options[CatMenu%s[i].length] = new Option(\"-----------------------------------\");\n"
"  }\n"
"}\n"
"// This JS comment is also at the end of the HTML comment above -->\n"
"</script>\n"
"<br>\n"
"<TABLE BORDER=\"1\" CELLPADDING=\"0\" CELLSPACING=\"0\"><TR><TD>\n"
"<TABLE BORDER=\"0\" CELLPADDING=\"3\" CELLSPACING=\"0\" width=\"100%%\">\n"
"  <TR bgcolor=\"#efefef\">\n"
"    <TD COLSPAN = 4>\n"
"      <FONT SIZE=\"3\"><b>Category</b> <font size=\"2\" color=\"#006600\">required</font></FONT>"
"&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\">You have chosen category # "
"      <INPUT NAME=%s TYPE=Text SIZE=5 value=%s onChange=\"initMenu%s(document.%s.%s.value);\"></INPUT><br>\n"
"<br>Just click in the boxes below from left to right until you have found the appropriate category for your item.<br>"
"The chosen category number will appear in the small box to indicate that you have made a valid selection."
"    </TD>\n"
"  </TR>\n"
"</TABLE>\n"
"<TABLE BORDER = 0 CELLPADDING = 3 CELLSPACING = 0>\n"
"  <TR>\n"
"    <TD ALIGN = LEFT>\n"
"      <SELECT NAME=\"CatMenu%s_0\" size=\"12\" onClick=\"changeMenu%s(0);\" onChange=\"changeMenu%s(0);\">\n"
"        <OPTION>-----------------------------------</OPTION>\n"
"      </SELECT>\n"
"    </TD>\n"
"    <TD>\n"
"      <SELECT NAME=\"CatMenu%s_1\" size=\"12\" onClick=\"changeMenu%s(1);\" onChange=\"changeMenu%s(1);\">\n"
"        <OPTION>-----------------------------------</OPTION>\n"
"      </SELECT>\n"
"    </TD>\n"
"    <TD>\n"
"      <SELECT NAME=\"CatMenu%s_2\" size=\"12\" onClick=\"changeMenu%s(2);\" onChange=\"changeMenu%s(2);\">\n"
"        <OPTION>-----------------------------------</OPTION>\n"
"      </SELECT>\n"
"    </TD>\n"
"    <TD>\n"
"      <SELECT NAME=\"CatMenu%s_3\" size=\"12\" onClick=\"changeMenu%s(3);\" onChange=\"changeMenu%s(3);\">\n"
"        <OPTION>-----------------------------------</OPTION>\n"
"      </SELECT>\n"
"    </TD>\n"
"  </TR>\n"
"</TABLE>\n"
"</TD></TR></TABLE>\n"
"\n"
"<script LANGUAGE=\"JavaScript1.1\">\n"
"<!-- the message below will display only on non-js1.1 browsrs -->\n"
"<!-- --> <hr><h1>This page requires JavaScript 1.1</h1>\n"
"<!-- --> Please consider using Netscape 3.0/4.0 or Internet Explorer 4.0\n"
"<!-- This HTML comment hides the script from non-js1.1 browsers\n"
"// gather all the CatMenus on the page into an array\n"
"CatMenu%s = new Array;\n"
"j = 0;\n"
"for (i = 0; i < document.%s.elements.length; i++)\n"
"{\n"
"  if (document.%s.elements[i].name.indexOf(\"CatMenu%s\") != -1)\n"
"    CatMenu%s[j++] = document.%s.elements[i];\n"
"}\n"
"\n"
"// start it off\n"
"initMenu%s(document.%s.%s.value);\n"
"\n"
"// This JS comment is also at the end of the HTML comment above -->\n"
"</script>"
"\n";

//
// Author: poon
// Note: the caller is responsible for the memory pointed by vCategories (unless useCache is set to true)
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::EmitHTMLJavascriptCategorySelector(ostream *pStream, 
													   char* cFormName, 
													   char* cOption, 
													   char* cCategoryName,
													   CategoryId selectedValue, 
													   bool emitCategoryArray)
{
	CategoryVector vCategories;
	CategoryVector::iterator	i;
	CategoryVector vChildren;
	CategoryVector::iterator	j;

	char cStaticJavascriptCode2_filledin[25000];
	char cStaticJavascriptCode3_filledin[25000];
	char cSelectedValue[10];

	// javascript header part
	*pStream	<<	cStaticJavascriptCode1;

	if (emitCategoryArray)
	{
		// begin dynamic part
		*pStream	<<	"c = new Array;\n"
					<<	"n = new Array;\n";

		// Get all the categories; use the cache
		All(&vCategories, true);

		// Now, go through each of the categories and output the names array
		for (i = vCategories.begin(); i != vCategories.end(); i++)
		{
			if (!(*i)->GetIsExpired())
			{

				// Emit the name of this category
				*pStream	<< "n["
							<< (*i)->GetId()
							<< "]=\""
							<< (*i)->GetName();
/*
				// Pad with spaces because of a Netscape layout problem
				l = 20 - strlen((*i)->GetName());
				for (k = 0; k < l; k++)
					*pStream	<< " ";
*/
				*pStream	<< "\";"
							<< "\n";
			}
		}

		// Now, emit the children of each category
		for (i = vCategories.begin(); i != vCategories.end(); i++)
		{
			if (!(*i)->GetIsExpired())
			{
				// Get the children of this category
				Children(&vChildren, (*i), true);

				// If no children, then skip
				if (vChildren.size() == 0) continue;

				// Special case if there's only one child, because something like
				//  new Array(5) creates an array of five elements rather than an
				//	an array of one element with value 5
				if (vChildren.size() == 1)
				{
					*pStream	<< "c["
								<< (*i)->GetId()
								<< "]=new Array;"
								<< "c["
								<< (*i)->GetId()
								<<	"][0]="
								<<	(*(vChildren.begin()))->GetId()
								<<	";\n";
				}
				else	// the normal thing is to emit something like
						//  new Array(5,3,2);
				{
					// Emit the first part
					*pStream	<< "c["
								<< (*i)->GetId()
								<< "]=new Array(";

					for (j = vChildren.begin(); j != vChildren.end(); j++)
					{
						// hack for not emitting firearms, cat # 2037
						if ((*j)->GetId() != 2037)
						{
							// Emit preceding comma if not the first
							if (j!=vChildren.begin())
								*pStream	<<	",";
						
							// Emit the child id
							*pStream <<	(*j)->GetId();
						}
					}

					// Emit the last part
					*pStream	<<	");\n";
				}

				// clear out the children for the next round
				vChildren.erase(vChildren.begin(), vChildren.end());
			}
		}

		// special case for top level

		// Get the children of top-level category
		Children(&vChildren, NULL, true);

		// Emit the first part
		*pStream	<< "c["
					<< "0"
					<< "]=new Array(";

		for (j = vChildren.begin(); j != vChildren.end(); j++)
		{
			// Emit preceding comma if not the first
			if (j!=vChildren.begin())
				*pStream	<<	",";
		
			// Emit the child id
			*pStream <<	(*j)->GetId();
		}

		// Emit the last part
		*pStream	<<	");\n";
	}

	// fill in the selected value
	if (selectedValue)
		sprintf(cSelectedValue, "%d", selectedValue);
	else
		strcpy(cSelectedValue, "\"\"");

	// fillup the parameters for Javascript Code2
	sprintf(cStaticJavascriptCode2_filledin, cStaticJavascriptCode2, 
			cOption,		// 1
			cOption,		// 2
			cOption,		// 3
			cOption,		// 4
			cOption,		// 5
			cOption,		// 6
			cOption,		// 7
			cOption,		// 8
			cOption,		// 9
			cOption,		// 10
			cOption);		// 11

	// fillup the parameters for Javascript Code3
	sprintf(cStaticJavascriptCode3_filledin, cStaticJavascriptCode3, 
			cOption,		// 1
			cOption,		// 2
			cOption,		// 3
			cOption,		// 4
			cOption,		// 5
			cFormName,		// 6
			cCategoryName,	// 7
			cFormName,		// 8
			cFormName,		// 9
			cCategoryName,	// 10
			cFormName,		// 11
			cCategoryName,	// 12
			cOption,		// 13
			cOption,		// 14
			cOption,		// 15
			cOption,		// 16
			cOption,		// 17
			cOption,		// 18
			cOption,		// 19
			cOption,		// 20
			cCategoryName,	// 21
			cSelectedValue,	// 22
			cOption,		
			cFormName,
			cCategoryName,
			cOption,		// 23
			cOption,		// 24
			cOption,		// 25
			cOption,		// 26
			cOption,		// 27
			cOption,		// 28
			cOption,		// 29
			cOption,		// 30
			cOption,		// 31
			cOption,		// 32
			cOption,		// 33
			cOption,		// 34
			cOption,		// 35
			cFormName,		// 36
			cFormName,		// 37
			cOption,		// 38
			cOption,		// 39
			cFormName,		// 40
			cOption,  		// 41
			cFormName,		// 42
			cCategoryName);	// 43

	// Emit the rest of the JS code
	*pStream	<<	cStaticJavascriptCode2_filledin;
	*pStream    <<  cStaticJavascriptCode3_filledin;

	// clear out the children for the next round
	vChildren.erase(vChildren.begin(), vChildren.end());

	return;
}

/* petra: not used
//
// prints the name of categories in traversal order
// using radio buttons
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::EmitHTMLCategoriesRadio(ostream *pStream,
											  char *pListName,
											  CategoryVector *vCategories,
											  bool useCache)
{
	CategoryVector::iterator	i;

	// Let's get them if its empty vector
	if (vCategories->size() < 1)
		All(vCategories, useCache, mpMarketPlace->GetSites()->GetCurrentSite()->GetId());
	
	// emit bogus root category as a checked option
	*pStream		<< "<BR>\n";
	*pStream		<< "<INPUT TYPE=\"RADIO\" "
					<<  "NAME=\""
					<<	pListName
					<<	"\""
					<<	"VALUE=\"0\" CHECKED>Root";
	*pStream		<< "<BR>\n";

	// Now, emit the categories
	for (i = vCategories->begin();
		 i != vCategories->end();
		 i++)
	{
		// print category option for each category 
		*pStream	<<	"<INPUT TYPE=\"RADIO\" "
					<<  "NAME=\""
					<<	pListName
					<<	"\""
					<<	"VALUE=\""
					<<	(*i)->GetId()
					<<	"\">";

		// Emit the "label", which is a concatenation
		// of the various level names.
		EmitHTMLQualifiedName(pStream, (*i));
		*pStream << "<BR>\n";

	}

	*pStream << "<BR>\n";
	return;

	// need to clean up the category vector??

}
*/

//
// prints the name of categories in traversal order
// as a list
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategories::EmitHTMLCategoriesList(ostream *pStream,
											  char * /* pListName */,
											  CategoryVector *vCategories,
											  bool useCache /* = false */)
{
	CategoryVector::iterator	i;

	// Let's get them if its empty vector
	if (vCategories->size() < 1)
		All(vCategories, useCache);
	
	// emit title of list
	*pStream		<< "<BR>\n";
	*pStream		<< "<menu>\n";
	*pStream		<<  "<LH><EM>List of Categories:</EM>";
	*pStream		<< "<BR>\n";

	// Now, emit the categories
	for (i = vCategories->begin();
		 i != vCategories->end();
		 i++)
	{
		// print category name for each category 
		*pStream	<<	"<LI> ";
		EmitHTMLQualifiedName(pStream, (*i));
		*pStream	<< "<BR>\n";

	}

	*pStream << "</menu>"
			 << "<BR>\n";
	return;

	// need to clean up the category vector??

}

// petra removed bool clsCategories::isSibling(clsCategory *pCat1, clsCategory *pCat2)
// 		(see E117 to restore)

//
// Get link path for a category
//
const char* clsCategories::GetLinkPath(clsCategory* pCategory, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/)
{
	return mpListingFileName->GetLinkName(pCategory, TimeStamp, PageNumber);
}

//
// Get link path for a category
//
const char* clsCategories::GetLinkPath(int categoryId, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/)
{
	return mpListingFileName->GetLinkName(categoryId, TimeStamp, PageNumber);
}

//
// Get link path for a category
//
const char* clsCategories::GetRelativeLinkPath(clsCategory* pCategory, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/)
{
	return mpListingFileName->GetRelativeLinkName(pCategory, TimeStamp, PageNumber);
}

//
// Get link path for a category
//
const char* clsCategories::GetRelativeLinkPath(int categoryId, 
							   TimeCriterion TimeStamp /*=LISTING*/,
							   int PageNumber /*=1*/)
{
	return mpListingFileName->GetRelativeLinkName(categoryId, TimeStamp, PageNumber);
}

//
// return total number of categories
//
int clsCategories::GetCategoryCount()
{
	return gApp->GetDatabase()->GetCategoryCount(mpMarketPlace->GetId());
}

// return Maximum category id
int clsCategories::GetMaxCategoryId()
{
	return gApp->GetDatabase()->GetMaxCategoryId(mpMarketPlace->GetId());
}

//
// Get first two level category count
//
int clsCategories::GetFirstTwoLevelCategoryCount()
{
	return gApp->GetDatabase()->GetFirstTwoLevelCategoryCount(mpMarketPlace->GetId());
}

//
// Get Child Leaf Category Ids
//
void clsCategories::GetChildLeafCategoryIds(int CatId, int** pIds)
{
	gApp->GetDatabase()->GetChildLeafCategoryIds(
					mpMarketPlace->GetId(),
					CatId,
					pIds);
}

//
// Get the open item counts in all categories, optionally for a given region
//
vector<int> *clsCategories::GetCategoryOpenItemCounts(int iRegionID /*=0*/)
{
	// if the item counts already exist, but for a different region, then clear them first
	if (mpvCategoryOpenItemCounts && (iRegionID != mRegionID))
		ClearOpenItemCounts();

	if (!mpvCategoryOpenItemCounts)
	{
		gApp->GetDatabase()->GetCategoryCountsFromOpenItems(&mpvCategoryOpenItemCounts, iRegionID);
		// store the region that these item counts were calculated for
		mRegionID = iRegionID;
	}

	return mpvCategoryOpenItemCounts;
}

void clsCategories::ClearOpenItemCounts()
{
	if (!mpvCategoryOpenItemCounts)
		return;

	mpvCategoryOpenItemCounts->erase(
		mpvCategoryOpenItemCounts->begin(),
		mpvCategoryOpenItemCounts->end());

	delete mpvCategoryOpenItemCounts;
	mpvCategoryOpenItemCounts = NULL;
	// clear the region ID since item counts have been cleared
	mRegionID = 0;

	return;
}

// check if a given category is has the adult flag set.
// this routine uses a cache of the adult categories, so it's very efficient.
// the cache is just a vector of integers that represent adult category ids
bool clsCategories::IsAdultCategory(CategoryId catId)
{
	// just check the cache for the category in question
	return ((find(mvAdultCategories.begin(), mvAdultCategories.end(), catId) != mvAdultCategories.end()));
}

// clear and delete the caches
void clsCategories::PurgeCategoriesSiteCache()
{
	int i;

	// clear the existing cache
	if (mpCategoriesSiteCache)
	{
		for (i = 0; i < mCategoriesSiteCacheSize; i++)
		{
			if (mpCategoriesSiteCache[i])
			{
				delete mpCategoriesSiteCache[i];
				mpCategoriesSiteCache[i] = NULL;
			}
		}
		delete [] mpCategoriesSiteCache;
	}

	mpCategoriesSiteCache = NULL;
	mCategoriesSiteCacheSize = 0;

	// cache is dirty now
	mDirtyCache = true;
}

// fetch sites from the database and populate the caches
//  there are two caches--one indexed/sorted by category id, and one indexed/sorted by order no
void clsCategories::PopulateCategoriesSiteCache()
{
	int i;
	vector<clsSite *> vSites;
	vector<clsSite *>::iterator	siteWalker;

	// purge first just in case
	PurgeCategoriesSiteCache();

	mpMarketPlace->GetSites()->GetAllMinimalSites(&vSites);

	// set the size of the cache
	mCategoriesSiteCacheSize = vSites.size();

	// allocate memory for the cache
	mpCategoriesSiteCache = new clsCategoriesSite*[mCategoriesSiteCacheSize];

	// safety
	if (!mpCategoriesSiteCache) 
		return;

	// initialize the cache
	for (i = 0; i < mCategoriesSiteCacheSize; i++) 
		mpCategoriesSiteCache[i] = NULL;

	// fill up the array
	for (siteWalker = vSites.begin(); siteWalker != vSites.end(); siteWalker++)
	{
		if ((*siteWalker) != NULL)
		{
			mpCategoriesSiteCache[(*siteWalker)->GetId()] = 
				new clsCategoriesSite(mpMarketPlace, (*siteWalker)->GetId() );
		}
	}

	// cache is now fresh
	mDirtyCache = false;

}


//
// Destructor
//
clsQualifiedCategoryName::~clsQualifiedCategoryName()
{
	delete [] names[0];
	delete [] names[1];
	delete [] names[2];
	delete [] names[3];
	delete [] names[4];

	return;
}

// petra removed void clsCategories::SortByPrevAndNext(CategoryVector *pvCategories)
//		(see E117 to restore)

clsCategoryFilters * clsCategories::GetCategoryFilters()
{
	if (mDirtyCache)
		PopulateCategoriesSiteCache();
	if (!mpCategoriesSiteCache)
		return NULL;
	return mpCategoriesSiteCache[0]->GetCategoryFilters();
}

clsCategoryMessages * clsCategories::GetCategoryMessages()
{
	if (mDirtyCache)
		PopulateCategoriesSiteCache();
	if (!mpCategoriesSiteCache)
		return NULL;
	return mpCategoriesSiteCache[0]->GetCategoryMessages();
}

//
// FlagCategory
//
void clsCategories::FlagCategory(CategoryId id, bool on)
{
	bool success;

	success = gApp->GetDatabase()->FlagCategory(mpMarketPlace->GetId(), id, on);
	if (success)
		mDirtyCache = true;
}

//
// IsBuddyFlaggedCategory
//
bool clsCategories::IsBuddyFlaggedCategory(CategoryId id)
{
	if (mDirtyCache)
		PopulateCategoriesSiteCache();
	if (!mpCategoriesSiteCache)
		return false;  // should really throw an exception here!
	return mpCategoriesSiteCache[0]->IsBuddyFlaggedCategory(id);
}
