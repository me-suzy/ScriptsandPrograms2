/*	$Id: FilterOnURLMap.h,v 1.2 1999/02/21 02:22:02 josh Exp $	*/
#if !defined(AFX_FILTERONURLMAP_H__416EED8B_66D4_11D1_A5A4_00A024D30D0B__INCLUDED_)
#define AFX_FILTERONURLMAP_H__416EED8B_66D4_11D1_A5A4_00A024D30D0B__INCLUDED_

// FILTERONURLMAP.H - Header file for your Internet Server
//    FilterOnURLMap Filter

#include "resource.h"


class CFilterOnURLMapFilter : public CHttpFilter
{
public:
	CFilterOnURLMapFilter();
	~CFilterOnURLMapFilter();

// Overrides
	// ClassWizard generated virtual function overrides
		// NOTE - the ClassWizard will add and remove member functions here.
		//    DO NOT EDIT what you see in these blocks of generated code !
	//{{AFX_VIRTUAL(CFilterOnURLMapFilter)
	public:
	virtual BOOL GetFilterVersion(PHTTP_FILTER_VERSION pVer);
	virtual DWORD OnUrlMap(CHttpFilterContext* pCtxt, PHTTP_FILTER_URL_MAP pMapInfo);
	//}}AFX_VIRTUAL

	//{{AFX_MSG(CFilterOnURLMapFilter)
	//}}AFX_MSG
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Developer Studio will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_FILTERONURLMAP_H__416EED8B_66D4_11D1_A5A4_00A024D30D0B__INCLUDED)
