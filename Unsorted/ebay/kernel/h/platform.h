/*	$Id: platform.h,v 1.1.4.1 1999/08/05 19:01:10 nsacco Exp $ */
//
//	File:	Platform.h
//
//	Platform dep. things!
//
//	Author:	Robin Kennedy (rkennedy@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 08/02/90 Robin Kennedy - Created
//
//

#ifndef __PLATFORM__
#define __PLATFORM__

#ifdef WIN32
#include <windows.h>
HANDLE    IntlResMutex;
#define	CREATE_IR_LOCK	IntlResMutex=CreateMutex(NULL,FALSE,NULL)
#define	GET_IR_LOCK		WaitForSingleObject(IntlResMutex,INFINITE)
#define FREE_IR_LOCK	ReleaseMutex(IntlResMutex)
#define KILL_IR_LOCK	CloseHandle(IntlResMutex) 
//#elif // Add more platforms
//....
//#elif
//....

#else
#error Platform not suported
#endif

#endif // __PLATFORM__