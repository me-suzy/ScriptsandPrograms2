/*	$Id: clsAdWidget.h,v 1.2 1999/02/21 02:28:30 josh Exp $	*/
//
//	File:		clsAdWidget.h
//
//	Class:		clsAdWidget
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//		Widget to create ad tag on a html page
//
//
//	Modifications:
//				- 11/14/97 Wen	- Created
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CLSADWIDGET_H__85691953_5C77_11D1_9289_0060979D45D6__INCLUDED_)
#define AFX_CLSADWIDGET_H__85691953_5C77_11D1_9289_0060979D45D6__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

#include "clseBayWidget.h"

class clsDailyAd;

class clsAdWidget : public clseBayWidget  
{
public:
	clsAdWidget(clsMarketPlace *pMarketPlace, clsApp *pApp);
	virtual ~clsAdWidget();

	void Initialize();
	void CleanUp();
	bool EmitHTML(ostream* pStream);
	void EmitHTMLOnTop(ostream* pStream);
	void EmitHTMLAtBottom(ostream* pStream);

	// Sets
	void SetCategoryId(int CatId);

protected:
	void EmitAdTag(clsDailyAd* pAd, ostream* pStream);
	void EmitVariableAdTag(clsDailyAd* pAd, ostream* pStream);
	bool IsComputer();

	int		mWidth;
	int		mHeight;
	int		mBorder;
	int		mCatId;

	int		mMaxCategoryId;
	int		mNumberTopAds;

	int*	mpPageViews;
	void**	mpAdVectorArray;

	clsDailyAd**	mpTopAds;
	clsDailyAd*	mpBottomAd;
};

#endif // !defined(AFX_CLSADWIDGET_H__85691953_5C77_11D1_9289_0060979D45D6__INCLUDED_)
