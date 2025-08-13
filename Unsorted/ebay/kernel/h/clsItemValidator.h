//	File:		clsItemValidator.h
//
// Class:	clsItemValidator
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Validates an item based a one or more filters
//				containing screening criteria.
//
// Modifications:
//				- 04/15/99 mila		- Created
//


#ifndef CLSITEMVALIDATOR_INCLUDED

#include "eBayTypes.h"
#include "clsFilter.h"
#include "clsItem.h"


class clsItemValidator {

public:

	// Default constructor
	clsItemValidator() : 
		mpItem(NULL), 
		mNumFilters(0)
	{
	}

	// Constructor
	clsItemValidator(clsItem *pItem);

	// Destructor
	virtual ~clsItemValidator();

	//
	// Reset
	//
	void		Reset();

	//
	// SetItem
	//
	void		SetItem(clsItem *pItem) { mpItem = pItem; }

	//
	// Validate
	//
	ActionType	Validate(FilterVector *pvFilters);

protected:

	//
	// BuildPattern
	//
	char *		BuildPattern(const char *pExpression);

private:

	clsItem	*		mpItem;			// item to be screened
	FilterVector	mvFilters;		// filters to be applied during screening
	unsigned int	mNumFilters;	// number of filters
	vector<char *>	mvPatterns;		// patterns extracted from filters
};


#define CLSITEMVALIDATOR_INCLUDED
#endif /* CLSITEMVALIDATOR_INCLUDED */

