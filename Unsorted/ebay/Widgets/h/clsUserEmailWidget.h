/*	$Id: clsUserEmailWidget.h,v 1.2 1998/10/16 01:00:42 josh Exp $	*/
// clsUserEmailWidget.h: interface for the clsUserEmailWidget class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CLSUSEREMAILWIDGET_H__8392ABE3_77D9_11D1_BAFC_0060979D45D6__INCLUDED_)
#define AFX_CLSUSEREMAILWIDGET_H__8392ABE3_77D9_11D1_BAFC_0060979D45D6__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

#include "clseBayWidget.h"

class clsUserEmailWidget : public clseBayWidget  
{
public:
	clsUserEmailWidget();
	virtual ~clsUserEmailWidget();

	bool EmitHTML(ostream *pStream);

	void SetUser(clsUser* pUser) {mpUser = pUser;}

protected:
	clsUser* mpUser;
};

#endif // !defined(AFX_CLSUSEREMAILWIDGET_H__8392ABE3_77D9_11D1_BAFC_0060979D45D6__INCLUDED_)
