/*	$Id: RedirectCompleted.h,v 1.3 1999/02/21 02:24:20 josh Exp $	*/
#if !defined(AFX_REDIRECTCOMPLETED_H__40461FDD_4031_11D2_B3FC_004033D08A84__INCLUDED_)
#define AFX_REDIRECTCOMPLETED_H__40461FDD_4031_11D2_B3FC_004033D08A84__INCLUDED_

// REDIRECTCOMPLETED.H - Header file for your Internet Server
//    RedirectCompleted Filter

#include "resource.h"

class clseBayCookie;

class CRedirectCompletedFilter : public CHttpFilter
{
public:
	CRedirectCompletedFilter();
	~CRedirectCompletedFilter();

// Overrides
	// ClassWizard generated virtual function overrides
		// NOTE - the ClassWizard will add and remove member functions here.
		//    DO NOT EDIT what you see in these blocks of generated code !
	//{{AFX_VIRTUAL(CRedirectCompletedFilter)
	public:
	virtual BOOL GetFilterVersion(PHTTP_FILTER_VERSION pVer);
	DWORD OnPreprocHeaders(CHttpFilterContext* pCtxt, PHTTP_FILTER_PREPROC_HEADERS pHeaderInfo);
	//}}AFX_VIRTUAL

	//{{AFX_MSG(CRedirectCompletedFilter)
	//}}AFX_MSG

protected:
	// member fuctions
	bool	LoadCompletedAdultURLs();
	bool	HasAdultCookie(CHttpFilterContext* pCtxt, PHTTP_FILTER_PREPROC_HEADERS pHeaderInfo);

	// member valuables
	int		mNumberOfURLs;
	char**	mpURLs;
	char*	mpAdultLoginURL;

	clseBayCookie*	mpCookie;

#ifdef _ADULT_DISABLE_

	int		mNumberOfIPs;
	char**	mpIPs;
	char	mpRemoteHost[256];

#endif // _ADULT_DISABLE_

};

//{{AFX_INSERT_LOCATION}}
// Microsoft Developer Studio will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_REDIRECTCOMPLETED_H__40461FDD_4031_11D2_B3FC_004033D08A84__INCLUDED)
