/*	$Id: clsCategoryInfo.h,v 1.2 1998/06/23 04:27:52 josh Exp $	*/
//
//	File:		clsCategoryInfo.h
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
#ifndef CLSCATEGORY_INFO_INCLDUE
#define CLSCATEGORY_INFO_INCLDUE

#include "vector.h"
#include "clsCategories.h"

// define a struct to hold category id and its kids
struct CategoryAndKids
{
	int	CatId;
	char* pKids;
};

// vector
typedef vector<CategoryAndKids*> CategoryAndKidsVector;

// clsCategoryInfo
class clsCategoryInfo
{
public:
	clsCategoryInfo();
	~clsCategoryInfo();

	void SetTopCategories(CategoryVector* pTopCategories);
	void SetCategoryAndKids(int CatId, int* pLeafCategoryIds);

	int  GetCategoryId(int Index);
	char* GetLeafCategories(int Index);
	char* GetTopLevelCategories();

protected:
	char*	mpTopLevelCategories;
	CategoryAndKidsVector mvCategoryAndKids;
};


#endif // CLSCATEGORY_INFO_INCLDUE