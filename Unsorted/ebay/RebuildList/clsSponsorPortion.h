/*	$Id: clsSponsorPortion.h,v 1.2 1999/02/21 02:24:06 josh Exp $	*/
//
//	File:	clsSponsorPortion.h
//
//	Class:	clsSponsorPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Replace catid for sponsor
//
// Modifications:
//				- 01/22/98	Wen - Created

#ifndef clsSponsorPortion_INCLUDED
#define clsSponsorPortion_INCLUDED

class clsCategory;

class clsSponsorPortion
{
public:
	clsSponsorPortion(clsCategory* pCategory, char* pInputFileName);

	~clsSponsorPortion() {;}


	// set the input file name
	void SetInputFileName(char* pInputFileName);

	// set category
	void SetCategory(clsCategory* pCategory) {mpCategory = pCategory;}

	// It opens the input file and copy the content to the 
	// output file
	void Print(ostream* pOutputFile);

protected:
	char*			mpInputFileName;
	clsCategory*	mpCategory;
};

#endif // clsSponsorPortion_INCLUDED
