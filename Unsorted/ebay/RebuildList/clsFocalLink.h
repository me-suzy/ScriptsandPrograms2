/*	$Id: clsFocalLink.h,v 1.2 1999/02/21 02:23:53 josh Exp $	*/
//
//	File:	clsFocalLink.h
//
//	Class:	clsFocalLink
//
//	Author:	Wen Wen
//
//	Function:
//			Process link exchanged
//
// Modifications:
//				- 09/02/97	Wen - Created
//

#ifndef CLSFOCALLINK_INCLUDED
#define CLSFOCALLINK_INCLUDED

class clsFocalLink
{
public:
	clsFocalLink(char* pInputFileName);

	~clsFocalLink() {;}


	// set the input file name
	void SetInputFileName(char* pInputFileName);

	// It opens the input file and copy the content to the 
	// output file
	void Print(ostream* pOutputFile);

protected:
	char*	mpInputFileName;
};

#endif // CLSFOCALLINK_INCLUDED
