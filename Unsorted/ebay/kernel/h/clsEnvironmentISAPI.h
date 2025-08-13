/*	$Id: clsEnvironmentISAPI.h,v 1.2.536.3 1999/06/08 16:09:55 poon Exp $	*/
//
//	File:	clsEnvironmentISAPI.h
//
//	Class:	Environment
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Encapsulates all the little nasty thing
//				in the environment, like parameters, etc
//
// Modifications:
//				- 12/24/97 michael	- Created
//				- 05/13/99 jennifer - added function IsMSIE30
//

#ifndef CLSENVIRONMENTISAPI_INCLUDED

#include "eBayTypes.h"
class clsEnvironmentISAPI : public clsEnvironment
{

	public:
		//
		// Constructor, Destructor
		//
		clsEnvironmentISAPI();
		~clsEnvironmentISAPI();

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

		//
		// Reset
		//
		void Reset(unsigned char *pThing);

		// Some browser sniffing stuff
		int GetMozillaLevel();
		bool IsWebTV();
		bool IsAOL();
		bool IsMSIE30();
		bool IsWin16();
		bool IsOpera();

	private:

};

	
#define CLSENVIRONMENTISAPI_INCLUDED 1
#endif /* CLSENVIRONMENTISAPI_INCLUDED */
