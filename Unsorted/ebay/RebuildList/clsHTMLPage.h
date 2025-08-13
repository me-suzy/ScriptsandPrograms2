/*	$Id: clsHTMLPage.h,v 1.2 1999/02/21 02:23:55 josh Exp $	*/
//
//	File:	clsHTMLPage.h
//
//	Class:	clsHTMLPage
//
//	Author:	Wen Wen
//
//	Function:
//			Create a HTML page for a category
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSHTMLPAGE_INCLUDED
#define CLSHTMLPAGE_INCLUDED

class clsHTMLPortion;
class clsItemPortion;
class clsCategoryPortion;
class clsCategoryNavigator;
class clsCategory;
class clsTemplate;
class clsFileName;
class clsTimePortion;
class clsPageLink;
class clsFocalLink;
class clsSponsorPortion;

class clsHTMLPage
{
public:
	// Should not used this constructor
	clsHTMLPage() {;}

	clsHTMLPage(clsCategory*	pCategory,
				CategoryVector* pCategories,
			    ListingItemVector*		pItems,
				ListingItemVector*		pFeaturedItems,
				ListingItemVector*		pHotItems,
				TimeCriterion	TimeStamp,
				clsFileName*	pFileName);

	~clsHTMLPage();

	// create and initialize necessary objects based on the template.
	bool Initialize();

	// create and parse the template
	bool CreateAndParseTemplate();

	// create necessry objects for each portion
	bool CreatePortions();

	// generate a HTML page
	bool CreatePage();

	// check whether featured items or hot items are going to print
	bool HasHotOrFeaturedPortion();

protected:
	// Header portion object
	clsHTMLPortion*	mpHeader;

	// Trailer portion object
	clsHTMLPortion*	mpTrailer;

	// Focal link object
	clsFocalLink*	mpFocalLink;

	// Hot item portion
	clsItemPortion*	mpHotItemPortion;

	// featured item portion
	clsItemPortion*	mpFeaturedItemPortion;

	// item portion (listing items or completed items)
	clsItemPortion*	mpItemPortion;

	// category portion
	clsCategoryPortion*	mpCategoryPortion;

	// Category navigator portion
	clsCategoryNavigator** mpCategoryNavigator;

	// Page link object
	clsPageLink*	mpPageLink;

	// HTML page update time portion
	clsTimePortion*	mpTimePortion;

	// sponsor
	clsSponsorPortion*	mpSponsorPortion;

	// Category object corresponding to the page
	clsCategory*  		mpCategory;

	// The template file name
	clsTemplate* 		mpTemplate;

	// file name object
	clsFileName*		mpFileName;

	// Time span for searching items
	TimeCriterion 		mTimeStamp;

	// the app
	clsRebuildListApp*	mpApp;

	// category vector
	CategoryVector*		mpCategories;

	// item vector
	ListingItemVector*			mpItems;

	// item vector
	ListingItemVector*			mpFeaturedItems;

	// item vector
	ListingItemVector*			mpHotItems;

	// page header and footer
	const char*	mpDefHeader;
	const char*	mpDefFooter;
	const char*	mpDefHeading;
	const char* mpMarketPlaceName;
};

#endif // CLSHTMLPAGE_INCLUDED
