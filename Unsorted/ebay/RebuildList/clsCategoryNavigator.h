/*	$Id: clsCategoryNavigator.h,v 1.3 1999/02/21 02:23:45 josh Exp $	*/
//
//	File:	clsCategoryNavigator.h
//
//	Class:	clsCategoryNavigator
//
//	Author:	Wen Wen
//
//	Function:
//			Create hot links to parents and sibling of the category
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSCATEGORYNAVIGATOR_INCLUDED
#define CLSCATEGORYNAVIGATOR_INCLUDED

class clsCategory;
class clsRebuildListApp;
class clsQualifiedCategoryName;

class clsCategoryNavigator
{
public:
	// Constructor
	clsCategoryNavigator(clsCategory*   pCurrCategory,
						 TimeCriterion  TimeStamp,
						 bool			HasTimeLink = true);

	~clsCategoryNavigator();

	// retrieve information about its ascendants and siblings
	void Initialize();

	// Print the category navigator information to a file
	void Print(ostream* pOutputFile, bool PrintJump);

	void PrintTitle(ostream* pOutputFile);

protected:
	void GetAncestorLinks(char* pAncestor);
	void GetSiblingLinks(char* pPrevious, char* pNext);
	void PrintTimeLinks(ostream* pOutputFile);
	void PrintLinks(ostream* pOutputFile);


	clsCategory*			mpCategory;
	clsCategories*			mpCategories;

	clsQualifiedCategoryName* mpQualifiedName;

	clsFileName*			mpFileName;

	clsRebuildListApp*		mpApp;

	TimeCriterion			mTimeStamp;

	bool					mHasTimeLink;

	char				mCompletedHeading[256];
};

#endif // CLSCATEGORYNAVIGATOR_INCLUDED
