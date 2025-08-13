/*	$Id: clsEBayHttpServer.cpp,v 1.6 1999/03/22 00:09:42 josh Exp $	*/
//
//	File:		clsRegisterApp.cpp
//
//	Class:		clsEBayHttpServer
//
//	Author:		Wen Wen (wen@ebay.com)
//
//	Function:
//				Base class for ebay server extensions
//
//	Modifications:
//				- 06/22/97 Wen - Created
//



#include "eBayKernel.h"

#ifdef _MSC_VER
#include "clsEventLog.h"
#endif /* _MSC_VER */

#if defined(IS_MULTITHREADED) && defined(IS_ISAPI)
#ifdef _MSC_VER
#include <afxwin.h>
#include <afxisapi.h>
#include "clsEBayHttpServer.h"


#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#ifdef _MSC_VER
#define BUILDING_BUGSUTILITY_DLL
#include "../bugslayer/bugslayerutil.h"
extern char StackTraceBuffer[];

LONG __stdcall ExcepCallBack (EXCEPTION_POINTERS * pExPtrs)
{
	DWORD dwOpts = GSTSO_SYMBOL | GSTSO_SRCLINE;
	
	StackTraceBuffer[0] = '\0';
	
	strcat(StackTraceBuffer, GetFaultReason(pExPtrs));
	strcat(StackTraceBuffer, "\n\n");
	
    const char * szBuff = GetFirstStackTraceString (dwOpts, pExPtrs) ;
    do
    {
		strcat(StackTraceBuffer, szBuff);
		strcat(StackTraceBuffer, "\n");
		
        szBuff = GetNextStackTraceString (dwOpts, pExPtrs) ;
    }
    while (NULL != szBuff) ;
    return (EXCEPTION_EXECUTE_HANDLER) ;
}
#undef BUILDING_BUGSUTILITY_DLL
#endif // _MSC_VER
//
// Exception translation routine
//
void eBayExceptionTranslator(unsigned int /* u */, 
							 EXCEPTION_POINTERS *pExp)
{
	EXCEPTION_RECORD	*pRecord;

	// Make sure we can touch this
	if (!AfxIsValidAddress(pExp, sizeof(*pExp), 0))
		return;

#ifdef _MSC_VER    
	ExcepCallBack (pExp);
#endif
	pRecord	= pExp->ExceptionRecord;

	if (pRecord->ExceptionCode == EXCEPTION_ACCESS_VIOLATION)
	{
		throw(eBayStructuredException(pRecord->ExceptionCode,
									  pRecord->ExceptionFlags,
									  pRecord->ExceptionAddress,
									  (unsigned int)pRecord->ExceptionInformation[0],
									  (void *)pRecord->ExceptionInformation[1]));
	}
	else
	{
		throw(eBayStructuredException(pRecord->ExceptionCode,
									  pRecord->ExceptionFlags,
									  pRecord->ExceptionAddress));
	}
}

/////////////////////////////////////////////////////////////////////////////
// clsEBayHttpServer

clsEBayHttpServer::clsEBayHttpServer()
{
}

clsEBayHttpServer::~clsEBayHttpServer()
{
}


// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(clsEBayHttpServer, CHttpServer)
	//{{AFX_MSG_MAP(clsEBayHttpServer)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()
#endif	// 0

/////////////////////////////////////////////////////////////////////////////
// clsEBayHttpServer member functions

//
// Get TLS index
//
DWORD clsEBayHttpServer::GetTlsIndex()
{
	// When we're here, we assume a TLSAlloc has been done. 
	return	g_tlsindex;

#if 0
	HANDLE	sharedMemoryHandle;
	DWORD	tlsIndex;
	DWORD	*pTlsIndexMemory;

	if ((sharedMemoryHandle = OpenFileMapping(FILE_MAP_READ, FALSE, "SharedTlsIndexMemory")) == NULL)
	{
		// there is not shared memory allocated
		CMutex mutexTlsIndex(FALSE, "eBayTlsIndex");
		CSingleLock sglLock(&mutexTlsIndex);
		sglLock.Lock();

		// Check again because other threads might allocate the memory 
		// during lock waiting
		if ((sharedMemoryHandle = OpenFileMapping(FILE_MAP_READ, FALSE, "SharedTlsIndexMemory")) == NULL)
		{
			// Create the memory map
			sharedMemoryHandle = CreateFileMapping((HANDLE)0xFFFFFFFF, 
											   NULL, 
											   PAGE_READWRITE,
											   0,	
											   sizeof(DWORD), 
											   "SharedTlsIndexMemory");
			if (sharedMemoryHandle == NULL)
			{
				return 0;
			}

			// map the view to shared in order to set the index value
			pTlsIndexMemory = (DWORD*) MapViewOfFile(sharedMemoryHandle, FILE_MAP_WRITE, 0, 0, sizeof(DWORD));
			tlsIndex = TlsAlloc();
			*pTlsIndexMemory = tlsIndex;
			UnmapViewOfFile(pTlsIndexMemory);

			// Open it again to make sure there is always an extra handle opened
			sharedMemoryHandle = OpenFileMapping(FILE_MAP_READ, FALSE, "SharedTlsIndexMemory");
		}

		sglLock.Unlock();
	}

	// Map the view in order to access the index
	pTlsIndexMemory = (DWORD*)MapViewOfFile(sharedMemoryHandle, FILE_MAP_READ, 0, 0, sizeof(DWORD));
	tlsIndex = *pTlsIndexMemory;
	UnmapViewOfFile(pTlsIndexMemory);

	CloseHandle(sharedMemoryHandle);

	return tlsIndex;
#endif
}

//
// Get App pointer
//
void clsEBayHttpServer::SetApp(ServerTypeEnum /* AppId */, clsApp* pApp)
{
	TlsSetValue(g_tlsindex, pApp);
	return;
#if 0 
	void**			pPointerArray;
	clsDatabase*	pDB;
	HGLOBAL			MemoryHandle;

	//
	// Get pointer to appoint array
	// Array format:
	//			0	--	Database Pointer
	//			1	--	App#1
	//			2	--	App#2
	//			..............
	//			n	--	App#n
	//

	if ((MemoryHandle = (HGLOBAL) TlsGetValue(g_tlsindex)) == NULL)
	{
		// Create a new app pointer array
		MemoryHandle = GlobalAlloc(GHND, SERVER_LAST*sizeof(void*));
		pPointerArray = (void**) GlobalLock(MemoryHandle);

		pPointerArray[AppId] = pApp;

		// Get the database pointer
//		pDB = pApp->GetDatabase();

		pPointerArray[0] = pDB;

		GlobalUnlock(MemoryHandle);

		TlsSetValue(g_tlsindex, MemoryHandle);
	}
	else
	{
		pPointerArray = (void**) GlobalLock(MemoryHandle);

		pPointerArray[AppId] = pApp;

		// Get the database pointer from the array
		if ((pDB = (clsDatabase*) pPointerArray[0]) == NULL)
		{
//			pDB = pApp->GetDatabase();
			pPointerArray[0] = pDB;
		}
		else
		{
//			pApp->SetDatabase(pDB);
		}

		GlobalUnlock(MemoryHandle);
	}
#endif
}

//
// Simple userid sanity check. Checks for LIMITS, not
// validity.
//
BOOL clsEBayHttpServer::ValidateUserId(LPTSTR pUser)
{
	char	*pColon;

	// See if we can touch it
	if (!AfxIsValidAddress(pUser, 1, false))
		return false;

	// See it it's too big
	if (strlen(pUser) > EBAY_MAX_USERID_SIZE)
		return false;

	// See if it's got weird stuff in it
	pColon	= strchr(pUser, ':');
	if (pColon)
		return false;

	// Looks good to me!
	return true;
}

//
// Password check
//
BOOL clsEBayHttpServer::ValidatePassword(LPTSTR pPassword)
{
	// See if we can touch it
	if (!AfxIsValidAddress(pPassword, 1, false))
		return false;

	// See it it's too big
	if (strlen(pPassword) > EBAY_MAX_PASSWORD_SIZE)
		return false;

	return true;
}

#endif //_MSC_VER
#endif // defined(IS_MULTITHREADED) && defined(IS_ISAPI)


