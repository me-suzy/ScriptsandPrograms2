//	File:		clsItemValidator.cpp
//
// Class:		clsItemValidator
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Screens items to be listed
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "vector.h"
#include "clsFilter.h"
#include "clsFilters.h"
#include "clsItemValidator.h"
#include "clsParser.h"


static const char *Qualifiers[] =
{
	"not a ",
	"not an ",
	"not ",
	"no ",
	"has no ",
	"have no ",
	"doesnt have a ",
	"doesnt have an ",
	"does not have a ",
	"does not have an ",
	NULL
};

// Constructor
clsItemValidator::clsItemValidator(clsItem *pItem) :
		mpItem(pItem)
{
	CategoryId	categoryId;
	clsMarketPlace *mpMarketPlace;
	clsCategory *pCategory;

	mNumFilters = 0;

	if (pItem == NULL)
		return;

	mpMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	if (mpMarketPlace != NULL)
	{
		categoryId = mpItem->GetCategory();
		pCategory = mpMarketPlace->GetCategories()->GetCategory(categoryId, true);
		if (pCategory != NULL && pCategory->GetScreenItems())
		{
			mpMarketPlace->GetFilters()->GetThisAndParentCategoryFilters(categoryId, &mvFilters, true);
			mNumFilters = mvFilters.size();
		}
	}
}


//
// Destructor
//
clsItemValidator::~clsItemValidator()
{
	Reset();
}


//
// Reset
//
void clsItemValidator::Reset()
{
	mvFilters.erase(mvFilters.begin(), mvFilters.end());
	mNumFilters = 0;

	mvPatterns.erase(mvPatterns.begin(), mvPatterns.end());
}


//
//	BuildPattern
//
//	Use this to string together all the keywords from the
//	vector of filters into a pattern that can be used by
//	the parser 
//
char * clsItemValidator::BuildPattern(const char *pExpression)
{
	unsigned int	exprLen = 0;
	unsigned int	length = 0;
	unsigned int	numQual = 0;
	const char **	pQual = Qualifiers;
	char *			pPattern = NULL;

	exprLen = strlen(pExpression);
	length = exprLen + 3;
	while (*pQual != NULL)
	{
		numQual++;
		length += (strlen(*pQual) + exprLen + 3);	// make room for " | "
		pQual++;
	}

	// allocate memory for and initialize pattern
	pPattern = new char[length];
	memset(pPattern, 0, length);

	// construct the pattern
	strcat(pPattern, "(");
	strcat(pPattern, pExpression);

	for (pQual = Qualifiers; *pQual != NULL; pQual++)
	{
		// add the interceding string " ! " that strings it all together
		strcat(pPattern, " ! ");

		// add the qualifying phrase that nullifies a match
		strcat(pPattern, *pQual);

		// add the expression itself
		strcat(pPattern, pExpression);
	}

	strcat(pPattern, ")");

	exprLen = strlen(pPattern);

	return pPattern;
}

//
// Validate
//
ActionType clsItemValidator::Validate(FilterVector *pvFilters)
{
	char *			pPattern;
//	char *			pExpression;
	char *			pTarget;
	char *			pText;
	char *			pTitle;
	char *			pDesc;
	unsigned int	length;
	bool			match;

	FilterVector::iterator	iFilters;
	unsigned int			filterCount = 0;

	ActionType				action = ActionTypeDoNothing;
	ActionType				mostSevereAction = ActionTypeDoNothing;

	// just return if there are no filters to apply
	if (mNumFilters == 0)
		return ActionTypeDoNothing;

	pTitle = mpItem->GetTitle();
	pDesc = mpItem->GetDescription();

	// concatenate the item title and description so we can screen
	// both in one pass
	length = strlen(pTitle) + strlen(pDesc) + 2;
	pText = new char[length];
	memset(pText, 0, length);
	strcpy(pText, pTitle);
	strcat(pText, " ");
	strcat(pText, pDesc);

	// delete the HTML tags from the string
	pTarget = clsUtilities::RemoveHTMLTag(pText);
	delete [] pText;	// we have a copy of pText now

	// allocate memory ahead of time
	pvFilters->reserve(mNumFilters);

	for (iFilters = mvFilters.begin(); iFilters != mvFilters.end(); iFilters++)
	{
		// Just get the expression from the filter to use - Support will enter qualifiers
//		pPattern = BuildPattern((*iFilters)->GetExpression());
		pPattern = (*iFilters)->GetExpression();

		// create a parser object
		clsSimpleParser	mParser(pPattern);

		// run the item title and description through the parser
		match = mParser.Match(pTarget);

		if (match)
		{
			action = (*iFilters)->GetActionType();
			if ((*iFilters)->BlockListing() || (*iFilters)->FlagListing())
			{
				// store filters containing offending keywords found by parser
				pvFilters->push_back(*iFilters);

				// if we need to block and no previous filter has required
				// blocking, or we're supposed to flag and no previous
				// filter has required blocking or flagging, then save off
				// this action, cuz this might be the one we need to return
				if (((action & ActionTypeBlockListing) &&
					  !(mostSevereAction & ActionTypeBlockListing)) ||
					((action & ActionTypeFlagListing) &&
					  !(mostSevereAction & ActionTypeBlockListing) &&
					  !(mostSevereAction & ActionTypeFlagListing)))
					mostSevereAction = action;
			}
		}
		
//		delete [] pPattern;
	}

	delete [] pTarget;

	return mostSevereAction;
}

