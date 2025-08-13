/*	$Id: clsTimePortion.h,v 1.2 1999/02/21 02:24:13 josh Exp $	*/
//
//	File:	clsTimePortion.h
//
//	Class:	clsTimePortion
//
//	Author:	Wen Wen
//
//	Function:
//			Print the creating time
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSTIMEPORTION_INCLUDED
#define CLSTIMEPORTION_INCLUDED

class clsTimePortion
{
public:
	clsTimePortion() {;}

	void Print(ostream* pOutputFile);
};

#endif // CLSTIMEPORTION_INCLUDED
