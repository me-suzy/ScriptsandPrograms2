/*	$Id: clsCategoryOverview.h,v 1.2 1999/02/21 02:23:47 josh Exp $	*/
//
//	File:	clsCategoryOverview.h
//
//	Class:	clsCategoryOverview
//
//	Author:	Wen Wen
//
//	Function:
//			Create a category overview HTML page
//
// Modifications:
//				- 08/01/97	Wen - Created
//

#ifndef CLSCATEGORYOVERVIEW_INCLUDE
#define CLSCATEGORYOVERVIEW_INCLUDE

class clsRebuildListApp;
class clsCategories;
class clsItems;
class clsFileName;

class clsCategoryOverview
{
public:
	clsCategoryOverview();
	~clsCategoryOverview();

	bool CreatePage();
	void PrintCategory(ostream* pOutStream, CategoryVector* pCategoires, int PrevLevel);
	void CleanUp(CategoryVector*);
	char* FormatName(clsCategory* pCategory);

protected:
	clsRebuildListApp*	mpApp;
	clsCategories*		mpCategories;
	clsItems*			mpItems;
	clsFileName*		mpFileName;
	char				mFormatedName[200];
	int					mCategoryCount;
	int					mLineNumber;
	int					mCurrentColumn;

	time_t	mTime;
};

#endif // CLSCATEGORYOVERVIEW_INCLUDE
