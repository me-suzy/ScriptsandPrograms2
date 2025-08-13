//	File:		clsCategoryFilter.h
//
// Class:	clsCategoryFilter
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a category-filter relationship
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#ifndef CLSCATEGORYFILTER_INCLUDED

#include "eBayTypes.h"


#define CMCAT_VARIABLE(name)				\
private:									\
	CategoryId		m##name;				\
public:										\
	CategoryId		Get##name();			\
	void	Set##name(CategoryId new_value);

#define CMFILT_VARIABLE(name)				\
private:									\
	FilterId		m##name;				\
public:										\
	FilterId		Get##name();			\
	void	Set##name(FilterId new_value);


//
// clsCategoryFilter
//

class clsCategoryFilter
{
public:

	// Default constructor
	clsCategoryFilter() :
		mCategoryId(0),
		mFilterId(0)
	{
	}

	// Constructor
	clsCategoryFilter(CategoryId categoryId, FilterId filterId) :
		mCategoryId(categoryId),
		mFilterId(filterId)
	{
	}

	// Destructor
	virtual ~clsCategoryFilter()
	{
	}

	CMCAT_VARIABLE(CategoryId);
	CMFILT_VARIABLE(FilterId);

private:

//	FilterType	mType;

};

typedef vector<clsCategoryFilter *> CategoryFilterVector;

#define CLSCATEGORYFILTER_INCLUDED
#endif /* CLSCATEGORYFILTER_INCLUDED */



