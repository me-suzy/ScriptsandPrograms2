/*	$Id: clsUserIdWidget.h,v 1.2.344.2 1999/06/07 16:20:10 poon Exp $	*/
//
//	File:		clsUserIdWidget.cc
//
//	Class:		clsUserIdWidget
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//		Widget to create User Id html segment
//
//
//	Modifications:
//				- 12/20/97 Wen	- Created
//
// Usage:
//			clsUserIdWidget* pUserIdWidget = new clsUserIdWidget(mMarketPlace, mpApp);
//			pUserIdWidget->SetUser(pUser);	// where pUserid the pointer to an object
//											// of clsUser, the object should be valid
//											// in the lifetime of pUserIdWidget.
//			pUserIdWidget->EmitHTML(pStream);
//			delete pUserIdWidget;
//
//		Options:
//			Whether to show the feedback rating, user status, mask icon, and star icon
//			can be controled by the cooresponding member functions (default: show all).
//
//			SetUserIdBold() displays the user id in bold. (default: false)
//			SetIncludeEmail() makes user email available directly (default: false)
//			SetDescription() shows the description instead of user id.
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CLSUSERIDWIDGET_H__02EC0093_757A_11D1_92A0_0060979D45D6__INCLUDED_)
#define AFX_CLSUSERIDWIDGET_H__02EC0093_757A_11D1_92A0_0060979D45D6__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

#include "clseBayWidget.h"

class clsUser;
class clsDataPool;

class clsUserIdWidget : public clseBayWidget  
{
public:
	clsUserIdWidget(clsMarketPlace *pMarketPlace, clsApp *pApp);
    clsUserIdWidget(clsWidgetHandler *pHandler, clsMarketPlace *pMarketPlace,
        clsApp *pApp);
	virtual ~clsUserIdWidget();

	bool EmitHTML(ostream *pStream);

	void SetUser(clsUser* pUser);
	void SetShowFeedback(bool Show=true) {mShowFeedback = Show;}
	void SetShowUserStatus(bool Show=true) {mShowUserStatus = Show;}
	void SetShowMask(bool Show=true) {mShowMask = Show;}
	void SetShowStar(bool Show=true) {mShowStar = Show;}
	void SetUserIdBold(bool BoldId=true) {mBoldId = BoldId;}
	void SetIncludeEmail(bool IncludeEmail=true) { mIncludeEmail = IncludeEmail; }
	void SetUserIdOnly(bool UserIdOnly=true) { mUserIdOnly = UserIdOnly; }
	void SetUserIdLink(bool IsLinked=true) { mIsLinked = IsLinked; }
	void SetShowAboutMe(bool Show=true) {mShowAboutMe = Show;}
	void SetShowUserId(bool Show=true) {mShowUserId = Show;}

	void SetDescription(char* pDescription);

	void SetUserInfo( char* pUserId, 
					  char* pUserEmail,
					  UserStateEnum	UserState,
					  bool	RecentlyChanged,
					  int	Feedback=0,
					  int   UserFlags=0);
	
    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool mFixBytes);
    long GetBlob(clsDataPool *pDataPool, bool mReverseBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clsUserIdWidget(pHandler, pMarketPlace, pApp); }

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

protected:
	bool NeedMask();
	bool HasStar();
	bool HasAboutMe();

	void EmitFeedback(ostream *pStream);
	void EmitStar(ostream *pStream);
	void EmitUserStatus(ostream *pStream);
	void EmitMask(ostream *pStream);
	void EmitAboutMe(ostream *pStream);

	bool	mShowFeedback;
	bool	mShowUserStatus;
	bool	mShowMask;
	bool	mShowStar;
	bool	mBoldId;
	bool	mIncludeEmail;
	bool	mIsLinked;
	bool	mUserValidated;
	bool	mUserIdOnly;
	bool    mUseContext;
	bool    mShowAboutMe;
	bool	mShowUserId;

	char*	mpDescription;

	char*	mpUserId;
	char*	mpEmail;
	bool	mRecentlyChanged;
	int		mUserFeedback;
	int     mUserFlags;

	UserStateEnum	mUserState;
};

#endif // !defined(AFX_CLSUSERIDWIDGET_H__02EC0093_757A_11D1_92A0_0060979D45D6__INCLUDED_)
