/*	$Id: clsEnvironmentCGI.h,v 1.2 1998/06/23 04:28:05 josh Exp $	*/
//
//	File:		clsEnvironmentCGI.h
//
// Class:	Environment
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Encapsulates all the little nasty thing
//				in the environment, like parameters, etc
//
// Modifications:
//				- 02/10/97 michael	- Created
//

#ifndef CLSENVIRONMENTCGI_INCLUDED

#include "eBayTypes.h"
class clsEnvironmentCGI : public clsEnvironment
{

	public:
		//
		// Constructor, Destructor
		//
		clsEnvironmentCGI();
		~clsEnvironmentCGI();

		//
		// GetParameterValue
		//		Returns the value of a given parameter, or
		//		null if it's not present.
		//
		const char *GetParameterValue(char *pName);

		//
		// GetFormValue
		//		Returns the value of a given form field name,
		//		or null if it's not present. Of course, this
		//		really only makes sense for Web-Apps
		//
		// *** Note ***
		//	If we always guarantee that fields in forms AND
		// paramters (path variables), then we could unify
		// GetParameterValue and GetFormValue. Probably
		// makes sense
		// *** Note ***
		const char *GetFormValue(char *pName);

	private:

};

	
#define CLSENVIRONMENTCGI_INCLUDED 1
#endif /* CLSENVIRONMENTCGI_INCLUDED */
