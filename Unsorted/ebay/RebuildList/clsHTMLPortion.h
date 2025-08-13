/*	$Id: clsHTMLPortion.h,v 1.2 1999/02/21 02:23:57 josh Exp $	*/
//
//	File:	clsHTMLPortion.h
//
//	Class:	clsHTMLPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Copy the content of the input file to the output file
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSHTMLPORTION_INCLUDED
#define CLSHTMLPORTION_INCLUDED

class clsHTMLPortion
{
public:
	clsHTMLPortion(char* pInputFileName);

	~clsHTMLPortion() {;}


	// set the input file name
	void SetInputFileName(char* pInputFileName);

	// It opens the input file and copy the content to the 
	// output file
	void Print(ostream* pOutputFile);

protected:
	char*	mpInputFileName;
};

#endif // CLSHTMLPORTION_INCLUDED
