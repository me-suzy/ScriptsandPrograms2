/* $Id: clsCategoryFilter.h,v 1.2 1999/02/21 02:23:04 josh Exp $ */
// File: clsCategoryFilter
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: This is used to filter out items that are not of a
// particular subcategory. Created for rolling out gallery to
// a limited number of categories,
//

#ifndef CLSCATEGORYFILTER_INCLUDE
#define CLSCATEGORYFILTER_INCLUDE

#include <vector>
using namespace std;

class clsCategoryFilter
{
public:
	clsCategoryFilter(vector<int>& allowedCategories);

	bool AllowedCategory(int i) const;

	struct Category
	{
		short mCategory1;
		short mCategory2;
		short mCategory3;
		short mCategory4;
	};

	enum {
		kCategoryAntiques = 353,
		kCategoryToysAndBeanies = 220,
		kCategoryJewelry = 281
	};


private:
	const vector<int> mAllowedCategory;

	static const Category* mCategories;
	static int mMaxCategories;


};


#endif