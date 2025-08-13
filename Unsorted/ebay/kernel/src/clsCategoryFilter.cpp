//	File:		clsCategoryFilter.cpp
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



#include "eBayKernel.h"
#include "clsCategoryFilter.h"

// Some convienent macros

#define IMCAT_METHODS(variable)									\
CategoryId clsCategoryFilter::Get##variable()					\
{																\
	return m##variable;											\
}																\
void clsCategoryFilter::Set##variable(CategoryId newval)		\
{																\
	m##variable	= newval;										\
	return;														\
}																\

#define IMFILT_METHODS(variable)								\
FilterId clsCategoryFilter::Get##variable()						\
{																\
	return m##variable;											\
}																\
void clsCategoryFilter::Set##variable(FilterId newval)			\
{																\
	m##variable	= newval;										\
	return;														\
}																\


IMCAT_METHODS(CategoryId);
IMFILT_METHODS(FilterId);

