/*	$Id: clsEnvironmentCGI.cpp,v 1.2 1998/06/23 04:30:14 josh Exp $	*/
//
//	File:		clsEnvironmentCGI.cc
//
// Class:	clsEnvironmentCGI
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Encapsulates all the little nasty thing
//				in the environment, like parameters, etc.
//
//				*** NOTE ***
//				Right now, this thing sucks. When we get
//				STL to work, we can parse out parameters,
//				etc, and stash them into a <list> or
//				something. For now, this is what you get
//				*** NOTE ***
//
// Modifications:
//				- 02/10/97 michael	- Created
//

#include "eBayTypes.h"
#include "clsEnvironment.h"
#include "clsEnvironmentCGI.h"
#include "clsApp.h"


//
// Constructor
//
//		Stashes away some important information
//
clsEnvironmentCGI::clsEnvironmentCGI() :
	clsEnvironment()
{
	return;
};

//
// Destructor
//
clsEnvironmentCGI::~clsEnvironmentCGI()
{
	return;
}

//
// GetFormValue
//
//		Returns the value of the passed form field 
//		variable.
//
const char *clsEnvironmentCGI::GetFormValue(char *pName)
{
	return NULL;
}
	
//
//	GetParameterValue
//
const char *clsEnvironmentCGI::GetParameterValue(char *pName)
{
	return NULL;
}



		
		

	


