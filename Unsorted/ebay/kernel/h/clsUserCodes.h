/*	$Id: clsUserCodes.h,v 1.2 1998/06/23 04:28:28 josh Exp $	*/
//
//	File:	clsUserCodes.h
//
//	Class:	clsUserCodes
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#ifndef CLSUSERCODES_INCLUDED
#include "eBayTypes.h"
#include <vector.h>
#include "clsUserCode.h"

class clsUserCodes
{   
  public:	
	clsUserCodes(); 
	~clsUserCodes();		
	UserCodeVector	*GetUserCodeVector();

  private:
    UserCodeVector	*mpUserCodeVector;	
};

#define CLSUSERCODES_INCLUDED 1
#endif