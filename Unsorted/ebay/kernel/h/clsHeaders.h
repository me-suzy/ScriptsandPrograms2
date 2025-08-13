/*	$Id: clsHeaders.h,v 1.1.26.1 1999/08/01 03:02:09 barry Exp $	*/
//
//	File:	clsHeaders.h
//
//  Class:	clsHeader
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				a class handle clsHeader 
//
// Modifications:
//				- 05/20/99 wen	- Created
//

#ifndef CLSHEADERS_INCLUDED

#include "clsHeader.h"

class clsHeaders
{
public:
	clsHeaders(HeaderVector* pvHeaders)  {mpvHeaders = pvHeaders;}
	~clsHeaders() {;}

	const clsHeader*	GetHeader(int SiteId, int PartnerId, int P1, int P2);

protected:
	HeaderVector*	mpvHeaders;
};

#define CLSHEADERS_INCLUDE
#endif // CLSHEADERS_INCLUDE
