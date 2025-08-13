/*	$Id: clsFooters.h,v 1.1.26.1 1999/08/01 03:02:08 barry Exp $	*/
//
//	File:	clsFooters.h
//
//  Class:	clsFooters
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class handle clsFooter 
//
// Modifications:
//				- 05/20/99 wen	- Created
//

#ifndef CLSFOOTERS_INCLUDED

#include "clsFooter.h"

class clsFooters
{
public:
	clsFooters() { mpvFooters = NULL; }
	clsFooters(FooterVector* pvFooters)  {mpvFooters = pvFooters;}
	~clsFooters() {;}

	clsFooter*	GetFooter(int SiteId, int PartnerId, int P1, int P2);

protected:
	FooterVector*	mpvFooters;
};

#define CLSFOOTERS_INCLUDED
#endif // CLSFOOTERS_INCLUDED
