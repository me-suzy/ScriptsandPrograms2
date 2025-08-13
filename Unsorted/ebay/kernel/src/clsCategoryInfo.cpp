/*	$Id: clsCategoryInfo.cpp,v 1.4 1998/08/25 03:20:43 josh Exp $	*/
//
//	File:		clsCategoryInfo.cpp
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//				Gethering category information for 
//			daily statistics
//
//	Modifications:
//				- 09/18/97 wen	- Created
//

#include "eBayKernel.h"
#include "clsCategoryInfo.h"

// Constructor
clsCategoryInfo::clsCategoryInfo()
{
	mpTopLevelCategories = NULL;
}

// destructor
clsCategoryInfo::~clsCategoryInfo()
{
	CategoryAndKidsVector::iterator	iCategoryAndKids;

	// clean up
	delete [] mpTopLevelCategories;

	for (iCategoryAndKids = mvCategoryAndKids.begin();
		 iCategoryAndKids != mvCategoryAndKids.end();
		 iCategoryAndKids++)
	{
		delete [] (*iCategoryAndKids)->pKids;
		delete *iCategoryAndKids;
	}

}

// convert the Top categories into string
void clsCategoryInfo::SetTopCategories(CategoryVector* pTopCategories)
{
	CategoryVector::iterator	iCategory;
	char		Temp[6];

	delete [] mpTopLevelCategories;

	if (pTopCategories == NULL || pTopCategories->size() == 0)
	{
		return;
	}

	mpTopLevelCategories = new char[5 * pTopCategories->size() + 3];

	// convert them into (2,4,6)
	iCategory = pTopCategories->begin();
	sprintf(mpTopLevelCategories, "%d", (*iCategory)->GetId());
	iCategory++;

	for (; iCategory != pTopCategories->end(); iCategory++)
	{
		sprintf(Temp, ",%d", (*iCategory)->GetId());
		strcat(mpTopLevelCategories, Temp);
	}

	return;
}

// set cateogries and their kids
void clsCategoryInfo::SetCategoryAndKids(int CatId, int* pLeafCategoryIds)
{
	int		Index=0;
	int		LeafCatId;
	char*	pCategories;
	char	Temp[6];
	CategoryAndKids*	pCategoryAndKids;
	
	// find out how many categories
	while (pLeafCategoryIds[Index++] != -1)
	{ ; }

	pCategories = new char[6*Index];

	// convert the category ids into string (3,2,5)
	Index = 0;
	sprintf(pCategories, "%d", pLeafCategoryIds[Index++]);

	while ((LeafCatId = pLeafCategoryIds[Index++]) != -1)
	{
		sprintf(Temp, ",%d", LeafCatId);
		strcat(pCategories, Temp);
	}

	// Make the pair
	pCategoryAndKids = new CategoryAndKids;
	pCategoryAndKids->CatId = CatId;
	pCategoryAndKids->pKids = pCategories;

	mvCategoryAndKids.push_back(pCategoryAndKids);
}

// get the category id from the vector
int clsCategoryInfo::GetCategoryId(int Index)
{
	CategoryAndKidsVector::iterator	iCategoryAndKids;

	iCategoryAndKids = mvCategoryAndKids.begin() + Index;

	if (iCategoryAndKids >= mvCategoryAndKids.end())
	{
		return -1;
	}

	return (*iCategoryAndKids)->CatId;
}

// Get the leaf category string
char* clsCategoryInfo::GetLeafCategories(int Index)
{
	CategoryAndKidsVector::iterator	iCategoryAndKids;

	iCategoryAndKids = mvCategoryAndKids.begin() + Index;

	if (iCategoryAndKids >= mvCategoryAndKids.end())
	{
		return NULL;
	}

	return (*iCategoryAndKids)->pKids;
}

// get the top level category string
char* clsCategoryInfo::GetTopLevelCategories()
{
	return mpTopLevelCategories;
}
