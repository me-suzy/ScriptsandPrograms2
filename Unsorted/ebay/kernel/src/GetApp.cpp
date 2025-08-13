/*	$Id: GetApp.cpp,v 1.4 1999/02/21 02:47:11 josh Exp $	*/
#include "eBayKernel.h"

// 
// The following are used to keep track
// of application (clsApp) instances in
// Windows multi-threaded environments
//


#ifdef IS_MULTITHREADED
#ifdef _MSC_VER
DWORD			g_tlsindex = 0xDEADDEAD;
#endif /* _MSC_VER */
#endif // IS_MULTITHREADED

#ifdef _MSC_VER
ServerTypeEnum	gServerId;
#endif /* _MSC_VER */

clsApp	*gTheApp;

clsApp *GetApp()
{
#ifdef IS_MULTITHREADED
#ifdef _MSC_VER
	if (g_tlsindex == 0)
	{
		return gTheApp;
	}
	else
	{
		return	(clsApp *)TlsGetValue(g_tlsindex);
	}

	void**	pPointerArray;
	clsApp*	pApp;
	HGLOBAL	MemoryHandle;

	if (g_tlsindex == 0)
		return gTheApp;
	else
	{
		MemoryHandle = (HGLOBAL) TlsGetValue(g_tlsindex);
		if (MemoryHandle == NULL)
			return NULL;
		pPointerArray = (void**) GlobalLock(MemoryHandle);
		if (!pPointerArray)
		{
			return NULL;
		}

		pApp = (clsApp*) pPointerArray[gServerId];

		if (!pApp)
		{
			return NULL;
		}

		GlobalUnlock(MemoryHandle);

		return pApp; 
	}
#endif /* _MSC_VER */
#else // IS_MULTITHREADED
	return gTheApp;
#endif // IS_MULTITHREADED

}

//
// On Non-Windows systems, g_tlsindex is always
// 0.
//
void SetApp(clsApp *pThis)
{
#ifdef IS_MULTITHREADED
#ifdef _MSC_VER
	if (g_tlsindex == 0)
		gTheApp	= pThis;
	return;
#endif /* _MSC_VER */
#else // IS_MULTITHREADED
	gTheApp		= pThis;
#endif // IS_MULTITHREADED
}

