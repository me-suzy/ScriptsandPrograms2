/*	$Id: CoBrand.h,v 1.2 1999/02/21 02:21:50 josh Exp $	*/
#if !defined(AFX_COBRAND_H__12F4CD97_801D_11D1_A755_00A024BC16C3__INCLUDED_)
#define AFX_COBRAND_H__12F4CD97_801D_11D1_A755_00A024BC16C3__INCLUDED_

// COBRAND.H - Header file for your Internet Server
//    cobrand Filter

#include "resource.h"


class CCobrandFilter : public CHttpFilter
{
public:
	CCobrandFilter();
	~CCobrandFilter();

// Overrides
	// ClassWizard generated virtual function overrides
		// NOTE - the ClassWizard will add and remove member functions here.
		//    DO NOT EDIT what you see in these blocks of generated code !
	//{{AFX_VIRTUAL(CCobrandFilter)
	public:
	virtual BOOL GetFilterVersion(PHTTP_FILTER_VERSION pVer);
	virtual DWORD OnPreprocHeaders(CHttpFilterContext* pCtxt, PHTTP_FILTER_PREPROC_HEADERS pHeaderInfo);
	virtual DWORD OnUrlMap(CHttpFilterContext* pCtxt, PHTTP_FILTER_URL_MAP pMapInfo);
	virtual DWORD OnSendRawData(CHttpFilterContext* pCtxt, PHTTP_FILTER_RAW_DATA pRawData);
	//}}AFX_VIRTUAL

	//{{AFX_MSG(CCobrandFilter)
	//}}AFX_MSG
private:
	CobrandTable	*mpCobrandTable;
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Developer Studio will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_COBRAND_H__12F4CD97_801D_11D1_A755_00A024BC16C3__INCLUDED)
