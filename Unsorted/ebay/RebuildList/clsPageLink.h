/*	$Id: clsPageLink.h,v 1.3 1999/02/21 02:24:01 josh Exp $	*/
//	File:	clsPageLink.cpp
//
//	Class:	clsPageLink
//
//	Author:	Wen Wen
//
//	Function:
//			Print the the link to other item pages
//
// Modifications:
//				- 08/19/97	Wen - Created
//
#ifndef CLSPAGELINK_INCLUDED
#define CLSPAGELINK_INCLUDED

class clsPageLink
{
public:
	clsPageLink(clsCategory*	pCategory,
				int				NumberOfItems,
				TimeCriterion	TimeStamp,
				clsFileName*	pFileName,
				int				ItemsPerPage
				);

	void Print(ostream* pOutputFile, int CurrentPage);

protected:
	// category
	clsCategory*  		mpCategory;

	// file name object
	clsFileName*		mpFileName;

	// Time span for searching items
	TimeCriterion 		mTimeStamp;

	int					mNumberOfItems;
	int					mItemsPerPage;

	char*	mpPreviousDayString;
	char*	mpNextDayString;
	int	mBuildDay;
	int	mPrevDay;
	int	mNextDay;

};

#endif // CLSPAGELINK_INCLUDED
