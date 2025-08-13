/*	$Id: clsAliasHistoryWidget.h,v 1.2 1998/10/16 01:00:33 josh Exp $	*/
// clsAliasHistoryWidget.h: interface for the clsAliasHistoryWidget class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_clsAliasHistoryWidget_H__8392ABE4_77D9_11D1_BAFC_0060979D45D6__INCLUDED_)
#define AFX_clsAliasHistoryWidget_H__8392ABE4_77D9_11D1_BAFC_0060979D45D6__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

#include "clseBayTableWidget.h"
#include "clsUser.h"

class clsAliasHistoryWidget : public clseBayTableWidget  
{
public:
	clsAliasHistoryWidget(clsMarketPlace *pMarketPlace, clsApp *pApp, UserAliasTypeEnum AliasType);
	virtual ~clsAliasHistoryWidget();

	bool Initialize();
	bool EmitHTML(ostream *pStream);

	void SetUser(clsUser* pUser) {mpUser = pUser;}

protected:
	bool EmitRow(ostream *pStream, int CurrentRow);
	bool EmitCell(ostream *pStream, int CurrentCell) {return true;}
	bool EmitHeaderCelles(ostream *pStream);

	clsUser* mpUser;
	int		 mNumRows;

	UserAliasHistoryVector	mVHistory;
	UserAliasHistoryVector::iterator	mHistoryBegin;
	UserAliasTypeEnum mAliasType;

};

#endif // !defined(AFX_clsAliasHistoryWidget_H__8392ABE4_77D9_11D1_BAFC_0060979D45D6__INCLUDED_)
