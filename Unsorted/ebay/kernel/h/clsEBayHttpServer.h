/*	$Id: clsEBayHttpServer.h,v 1.4 1999/03/22 00:09:40 josh Exp $	*/
//
//	File:		clsRegisterApp.h
//
//	Class:		clsEBayHttpServer
//
//	Author:		Wen Wen (wen@ebay.com)
//
//	Function:
//				Base class for ebay server extensions
//
//	Modifications:
//				- 06/12/97 Wen - Created
//


#include "ebaytypes.h"

#ifdef IS_ISAPI
#ifdef _MSC_VER
#include "eh.h"
#if !defined(AFX_CLSEBAYHTTPSERVER_H__2CAF3F7F_EC0E_11D0_9234_0060979D45D6__INCLUDED_)
#define AFX_CLSEBAYHTTPSERVER_H__2CAF3F7F_EC0E_11D0_9234_0060979D45D6__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000
// clsEBayHttpServer.h : header file
//

extern char StackTraceBuffer[];
class clsApp;

// Useful Macros

#define LogQueryString(who)											\
	{																\
		HANDLE		EventSource;									\
		char		pidAndTid[16];									\
		char		qString[4096];									\
		DWORD		qStringSize	= sizeof(qString);					\
		PTSTR		pStrings[2] =  { NULL, NULL };					\
		WORD		stringCount	= 0;								\
																	\
		sprintf(pidAndTid, "%x/%x", GetCurrentProcessId(),			\
				GetCurrentThreadId());								\
		pStrings[0]	= pidAndTid;									\
		stringCount++;												\
		(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,	\
											"QUERY_STRING",			\
											&qString,				\
											&qStringSize);			\
		pStrings[1]	= qString;										\
		stringCount++;												\
																	\
		EventSource = RegisterEventSource(							\
								NULL,								\
								who									\
										);							\
		ReportEvent(EventSource,									\
					(WORD)660,										\
					(WORD)660,										\
					(DWORD)660,										\
					(PSID)0,										\
					(WORD)stringCount,								\
					(WORD)0,										\
					(LPCTSTR *)pStrings,							\
					(LPVOID)NULL);									\
	}

		

#define LogException(who)					\
	{										\
		char	pidAndTid[16];				\
		HANDLE	EventSource;				\
		PTSTR	pStrings[1] =  { NULL };	\
		WORD	stringCount = 0;			\
											\
		sprintf(pidAndTid, "%x/%x",			\
				GetCurrentProcessId(),		\
				GetCurrentThreadId());		\
		pStrings[0]	= pidAndTid;			\
		stringCount++;						\
		EventSource = RegisterEventSource(	\
								NULL,		\
								who			\
										);	\
		ReportEvent(EventSource,			\
					(WORD)666,				\
					(WORD)666,				\
					(DWORD)666,				\
					(PSID)0,				\
					(WORD)stringCount,		\
					(WORD)0,				\
					(LPCTSTR *)pStrings,	\
					(LPVOID)NULL);			\
	}

#define LogOracleException(who)				\
	{										\
		char	pidAndTid[16];				\
		HANDLE	EventSource;				\
		char	whoAndWhere[512];			\
		PTSTR	pStrings[4] =  { NULL,		\
								 NULL,		\
								 NULL,		\
								 NULL };	\
		WORD	stringCount = 0;			\
		sprintf(pidAndTid, "%x/%x",			\
				GetCurrentProcessId(),		\
				GetCurrentThreadId());		\
		pStrings[0]	= pidAndTid;			\
		stringCount++;						\
											\
		if (e.mpErrorMsg != NULL)			\
		{									\
			pStrings[1]	= e.mpErrorMsg;		\
			stringCount++;					\
		}									\
		if (e.mpFile != NULL)				\
		{									\
			sprintf(whoAndWhere,			\
					"%s:%d",				\
					e.mpFile,				\
					e.mLine);				\
			pStrings[2]	= whoAndWhere;		\
			stringCount++;					\
		}									\
		if (e.mpSQL != NULL &&				\
			AfxIsValidAddress(e.mpSQL,		\
							  1,			\
							  false))		\
		{									\
			pStrings[3] = e.mpSQL;			\
			stringCount++;					\
		}									\
		EventSource = RegisterEventSource(	\
								NULL,		\
								who			\
										);	\
		ReportEvent(EventSource,			\
					(WORD)e.mRc,			\
					(WORD)e.mRc,			\
					(DWORD)e.mRc,			\
					(PSID)0,				\
					(WORD)stringCount,		\
					(WORD)0,				\
					(LPCTSTR *)pStrings,	\
					(LPVOID)NULL);			\
	}


#define LogStructuredException(who)			\
	{										\
		char	pidAndTid[16];				\
		HANDLE	EventSource;				\
		char	whoAndWhere[512];			\
		PTSTR	pStrings[4] =  { NULL,		\
								 NULL,		\
								 NULL,		\
								 NULL };	\
		WORD	stringCount = 0;			\
		sprintf(pidAndTid, "%x/%x",			\
				GetCurrentProcessId(),		\
				GetCurrentThreadId());		\
		pStrings[stringCount++]	= pidAndTid;\
											\
		sprintf(whoAndWhere,				\
			"Code 0x%x, Flag 0x%x Where 0x%x\n",	\
			e.mExceptionCode,						\
			e.mExceptionFlags,						\
			e.mpExceptionAddress);					\
		pStrings[stringCount++]	= whoAndWhere;		\
		if (StackTraceBuffer[0])			\
			pStrings[stringCount++] = StackTraceBuffer;	\
											\
		EventSource = RegisterEventSource(	\
								NULL,		\
								who			\
										);	\
		ReportEvent(EventSource,			\
					(WORD)659,				\
					(WORD)659,				\
					(DWORD)659,				\
					(PSID)0,				\
					(WORD)stringCount,		\
					(WORD)0,				\
					(LPCTSTR *)pStrings,	\
					(LPVOID)NULL);			\
		}
#define LogCException(who)				\
	HANDLE		EventSource;			\
	EventSource = RegisterEventSource(	\
							NULL,		\
							who			\
									);	\
	ReportEvent(EventSource,			\
				(WORD)e,				\
				(WORD)e,				\
				(DWORD)e,				\
				(PSID)0,				\
				(WORD)0,				\
				(WORD)0,				\
				(LPCTSTR *)NULL,		\
				(LPVOID)NULL); 

#define LogMFCException(who)			\
	char	kind[512];					\
	PTSTR	pStrings[1] =  { NULL };	\
	WORD	stringCount = 0;			\
										\
	e.GetErrorMessage(kind,				\
					  sizeof(kind),		\
					  NULL);			\
	pStrings[0]	= kind;					\
	stringCount++;						\
										\
	HANDLE	EventSource;				\
	EventSource = RegisterEventSource(	\
							NULL,		\
							who			\
									);	\
	ReportEvent(EventSource,			\
				(WORD)665,				\
				(WORD)665,				\
				(DWORD)665,				\
				(PSID)0,				\
				(WORD)stringCount,		\
				(WORD)0,				\
				(LPCTSTR *)pStrings,	\
				(LPVOID)NULL); 


#define LogStrException(who)				\
	{										\
		HANDLE	EventSource;				\
		PTSTR	pStrings[1] =  { NULL };	\
		WORD	stringCount = 0;			\
											\
		pStrings[0]	= e;					\
		stringCount++;						\
											\
		EventSource = RegisterEventSource(	\
								NULL,		\
								who			\
										);	\
		ReportEvent(EventSource,			\
					(WORD)664,				\
					(WORD)664,				\
					(DWORD)664,				\
					(PSID)0,				\
					(WORD)stringCount,		\
					(WORD)0,				\
					(LPCTSTR *)pStrings,	\
					(LPVOID)NULL);			\
	}


#define LogLockException(who)			\
	char	kind[512];					\
	PTSTR	pStrings[1] =  { NULL };	\
	WORD	stringCount = 0;			\
										\
	sprintf(kind,						\
			"GlobalLock LastError %d\n",\
			e.mLastError);				\
	pStrings[0]	= kind;					\
	stringCount++;						\
										\
	HANDLE	EventSource;				\
	EventSource = RegisterEventSource(	\
							NULL,		\
							who			\
									);	\
	ReportEvent(EventSource,			\
				(WORD)663,				\
				(WORD)663,				\
				(DWORD)663,				\
				(PSID)0,				\
				(WORD)stringCount,		\
				(WORD)0,				\
				(LPCTSTR *)pStrings,	\
				(LPVOID)NULL); 


#define LogNoAppException(who)			\
	char	kind[512];					\
	PTSTR	pStrings[1] =  { NULL };	\
	WORD	stringCount = 0;			\
										\
	sprintf(kind,						\
			"No App Exception\n");		\
	pStrings[0]	= kind;					\
	stringCount++;						\
										\
	HANDLE	EventSource;				\
	EventSource = RegisterEventSource(	\
							NULL,		\
							who			\
									);	\
	ReportEvent(EventSource,			\
				(WORD)662,				\
				(WORD)662,				\
				(DWORD)662,				\
				(PSID)0,				\
				(WORD)stringCount,		\
				(WORD)0,				\
				(LPCTSTR *)pStrings,	\
				(LPVOID)NULL);

#define CLEARPOINTER										\
	{														\
		TlsSetValue(g_tlsindex, 0);							\
	}

//		HGLOBAL		MemoryHandle;							
//		void		**pPointerArray;						
//		MemoryHandle = (HGLOBAL) TlsGetValue(g_tlsindex);	
//		pPointerArray = (void**) GlobalLock(MemoryHandle);	
//		if (!pPointerArray)									
//		{													
//			throw eBayGlobalLockException(GetLastError());	
//		}													
//															
//		pPointerArray[gServerId]	= NULL;					
//		GlobalUnlock(MemoryHandle);							

 
#define MYTRY												\
	_se_translator_function pOldFunc;						\
	try														\
	{														\
		pOldFunc	=										\
			_set_se_translator(eBayExceptionTranslator);	

#define MYCATCH(who)							\
	}											\
	catch(eBayOracleException &e)				\
	{											\
		LogQueryString(who);					\
		LogOracleException(who);				\
		pApp->CancelDBTransactions(); \
		*pCtxt <<	InternalErrorMessage;		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	catch(eBayGlobalLockException &e)			\
	{											\
		LogQueryString(who);					\
		LogLockException(who);					\
		pApp->CancelDBTransactions(); \
		*pCtxt <<	InternalErrorMessage;		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	catch(eBayNoAppException &e)				\
	{											\
		e.mNothing	= 0;						\
		LogQueryString(who);					\
		LogNoAppException(who);					\
		pApp->CancelDBTransactions(); \
		*pCtxt <<	InternalErrorMessage;		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	catch(CException &e)						\
	{											\
		LogQueryString(who);					\
		LogMFCException(who);					\
		pApp->CancelDBTransactions(); \
		*pCtxt <<	InternalErrorMessage;		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	catch(char *e)								\
	{											\
		LogQueryString(who);					\
		LogStrException(who);					\
		pApp->CancelDBTransactions(); \
		*pCtxt <<	InternalErrorMessage;		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	catch(eBayStructuredException &e)			\
	{											\
		LogQueryString(who);					\
		LogStructuredException(who);			\
		pApp->CancelDBTransactions();			\
		*pCtxt <<	InternalErrorMessage;		\
		MaybeDumpStackAndRegisters(pCtxt);		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	catch(...)									\
	{											\
		LogQueryString(who);					\
		LogException(who);						\
		pApp->CancelDBTransactions(); \
		*pCtxt <<	InternalErrorMessage;		\
		try										\
		{										\
			delete	pApp;						\
		}										\
		catch(...)								\
		{										\
			;									\
		}										\
		pApp	= NULL;							\
		CLEARPOINTER							\
	}											\
	_set_se_translator(pOldFunc);	

// 
// Exception translation routine
//
#ifdef _MSC_VER
void eBayExceptionTranslator( unsigned int, EXCEPTION_POINTERS* );							
#endif /* _MSC_VER */

/////////////////////////////////////////////////////////////////////////////
// clsEBayHttpServer command target

class clsEBayHttpServer : public CHttpServer
{
// Attributes
public:

// Operations
public:
	clsEBayHttpServer();
	virtual ~clsEBayHttpServer();

// Overrides
public:

	//
	// Retrieve or allocate TLS Index
	//
	DWORD GetTlsIndex();

	//
	//	Set the app pointer in shared memory block
	//
	void SetApp(ServerTypeEnum AppId, clsApp* pApp);

	// 
	// Basic, very basic, data validation routines
	//
	BOOL ValidateUserId(LPTSTR pUser);
	BOOL ValidatePassword(LPTSTR pPass);


	// ClassWizard generated virtual function overrides
	//{{AFX_VIRTUAL(clsEBayHttpServer)
	//}}AFX_VIRTUAL

	// Generated message map functions
	//{{AFX_MSG(clsEBayHttpServer)
		// NOTE - the ClassWizard will add and remove member functions here.
	//}}AFX_MSG

// Implementation
protected:
;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Developer Studio will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_CLSEBAYHTTPSERVER_H__2CAF3F7F_EC0E_11D0_9234_0060979D45D6__INCLUDED_)


#endif // _MSC_VER
#endif // IS_ISAPI

