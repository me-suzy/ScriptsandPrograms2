/*	$Id: clsCategoryPortion.h,v 1.2 1999/02/21 02:23:49 josh Exp $	*/
//
//	File:	clsCategoryPortion.h
//
//	Class:	clsCategoryPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Listing the child categories for the current category
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSCATEGORYPORTION_INCLUDED
#define CLSCATEGORYPORTION_INCLUDED

class clsCategory;
class clsCategories;
class clsItems;
class clsRebuildListApp;
class clsFileName;

class clsCategoryPortion
{
public:
	// Constructor
	clsCategoryPortion(clsCategory*    pCurrentCategory, 
					   CategoryVector* pCategories,
					   TimeCriterion   TimeStamp);

	~clsCategoryPortion();

	// retrieve information of the children categories
	void Initialize();

	// Print the children categories
	void Print(ostream* pOutputFile);

protected:
	void	PrintCategory(ostream* pOutputFile, CategoryVector* pCategories, bool Deep);
	void	PrintTopGoingPage(ostream* pOutStream);
	char*	GetCategoryLink(clsCategory* pCategory);
	char*	GetBigBookLink(clsCategory* pCategory);
	char*	GetCategoryAnchor(clsCategory* pCategory);
	void	CreateBigBook(clsCategory* pCategory);
	int		GetNumberOfItemsInCategory(clsCategory* pCategory);

	clsCategory*		mpCategory;
	clsCategories*		mpCategories;
	clsItems*			mpItems;
	CategoryVector*		mpChildren;
	TimeCriterion		mTimeStamp;

	clsRebuildListApp*	mpApp;
	clsFileName*		mpFileName;

	char	mCategoryLink[_MAX_PATH+100];
	char	mCategoryAnchor[10];
	int		mCategoryCount;
	int		mCurrentColumn;
	int		mLineNumber;
	int		mNextCategoryLevel;

};

#endif // CLSCATEGORYPORTION_INCLUDED
