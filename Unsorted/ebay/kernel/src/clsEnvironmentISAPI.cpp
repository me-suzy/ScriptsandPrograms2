/*	$Id: clsEnvironmentISAPI.cpp,v 1.8.348.3.68.1 1999/08/01 03:02:26 barry Exp $	*/
//
//	Class:	clsEnvironmentISAPI
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 12/24/97 michael	- Created
//				- 05/13/99 jennifer - added function IsMSIE30
//				- 05/25/99 nsacco	- added mServerName to Reset()
//
#include "eBayKernel.h"
#include "clsEnvironment.h"
#include "clsEnvironmentISAPI.h"
#include <afxwin.h>
#include <afxext.h>
#include <afxisapi.h>

//
// CTOR
//
// Let the superclass do all the work
//
clsEnvironmentISAPI::clsEnvironmentISAPI() :
	clsEnvironment()
{
	return;
}


//
// DTOR
//
clsEnvironmentISAPI::~clsEnvironmentISAPI()
{
	return;
}


//
//	Reset
//
//		Get the host variables, and stuff them into 
//		our instance variables
//
void clsEnvironmentISAPI::Reset(unsigned char *pThing)
{
	CHttpServerContext	*pCtxt;

	DWORD		size;

	pCtxt	= (CHttpServerContext *)pThing;

	memset(mRemoteAddr, 0x00, sizeof(mRemoteAddr));
	size	= sizeof(mRemoteAddr);
	(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,
										"REMOTE_ADDR",
										&mRemoteAddr,
										&size);

	memset(mRemoteHost, 0x00, sizeof(mRemoteHost));
	size	= sizeof(mRemoteHost);
	(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,
										"REMOTE_HOST",
										&mRemoteHost,
										&size);


	memset(mRemoteUser, 0x00, sizeof(mRemoteUser));
	size	= sizeof(mRemoteUser);
	(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,
										"REMOTE_HOST",
										&mRemoteUser,
										&size);

	memset(mScriptName, '\0', sizeof (mScriptName));
	size = sizeof(mScriptName) - 1;
	pCtxt->GetServerVariable("SCRIPT_NAME", 
		(char *) mScriptName, 
		&size);

	memset(mCookie, '\0', sizeof (mCookie));
	size = sizeof(mCookie) - 1;
	pCtxt->GetServerVariable("HTTP_COOKIE", 
		(char *) mCookie, 
		&size);

	memset(mBrowser, '\0', sizeof (mBrowser));
	size = sizeof(mBrowser) - 1;
	pCtxt->GetServerVariable("HTTP_USER_AGENT", 
		(char *) mBrowser, 
		&size);

	//referrer -- vicki
	memset(mReferrer, '\0', sizeof (mReferrer));
	size = sizeof(mReferrer) - 1;
	pCtxt->GetServerVariable("HTTP_REFERER", 
		(char *) mReferrer, 
		&size);

	// nsacco 05/25/99 - server name
	memset(mServerName, '\0', sizeof (mServerName));
	size = sizeof(mServerName) - 1;
	pCtxt->GetServerVariable("SERVER_NAME",
		(char *) mServerName,
		&size);

	return;
}

//
// GetParameterValue
//
const char *clsEnvironmentISAPI::GetParameterValue(char *pName)
{
	return	NULL;
}

//
// GetFormValue
//
const char *clsEnvironmentISAPI::GetFormValue(char *pName)
{
	return	NULL;
}


// Some browser sniffing stuff
// --------------------------------


// Returns what level of Mozilla browser this is.
// Returns -1 if Mozilla string not found, otherwise returns 1,2,3,4....
// Here are some examples:

// IE 4.0
//	Mozilla/4.0 (compatible; MSIE 4.01; Windows NT)

// IE 3.0
//	Mozilla/2.0 compatible; MSIE 4.01; Windows 95)

// Netscape 4.05
//	Mozilla/4.05 [en] (WinNT; I)


// Netscape 3.01
//	Mozilla/3.01 (WinNT; I)


// Netscape 2.02
//	Mozilla/2.02 (WinNT; I)

// WebTV
//	Mozilla/3.0 WebTV/2.3 (Compatible; MSIE 2.0)

// Windows AOL 3.0/Win95 MSIE 3.02
//	Mozilla/2.0 (Compatible; MSIE 3.02; AOL 3.0; Windows 95)

int clsEnvironmentISAPI::GetMozillaLevel()
{
	int level = -1;
	int foundMozilla = 0;

	// safety
	if (!GetBrowser())
		return -1;

	foundMozilla = sscanf(GetBrowser(), "Mozilla/%d", &level);

	if (foundMozilla)
		return level;
	
	return -1;
}

bool clsEnvironmentISAPI::IsWebTV()
{
	char *p;

	// safety
	if (!GetBrowser())
		return false;

	p = strstr(GetBrowser(), "WebTV");

	return (p != NULL);
}

bool clsEnvironmentISAPI::IsAOL()
{
	char *p;

	// safety
	if (!GetBrowser())
		return false;

	p = strstr(GetBrowser(), "AOL");

	return (p != NULL);
}

bool clsEnvironmentISAPI::IsMSIE30()
{
	char *p;

	// safety
	if (!GetBrowser())
		return false;

	p = strstr(GetBrowser(), "MSIE 3");

	return (p != NULL);
}

bool clsEnvironmentISAPI::IsWin16()
{
	char *p;

	// safety
	if (!GetBrowser())
		return false;

	p = strstr(GetBrowser(), "Win16");

	if (!p)
		p = strstr(GetBrowser(), "Windows 3.1");

	return (p != NULL);
}

bool clsEnvironmentISAPI::IsOpera()
{
	char *p;

	// safety
	if (!GetBrowser())
		return false;

	p = strstr(GetBrowser(), "Opera");

	return (p != NULL);
}