/*	$Id: clsSpammerControl.h,v 1.2 1998/06/23 04:31:44 josh Exp $	*/
// clsSpammerControl.h: interface for the clsSpammerControl class.
//
//////////////////////////////////////////////////////////////////////

#include "clsSpammer.h"

#if !defined(AFX_CLSSPAMMERCONTROL_H__3CC879F5_40EF_11D1_9E72_006097379B29__INCLUDED_)
#define AFX_CLSSPAMMERCONTROL_H__3CC879F5_40EF_11D1_9E72_006097379B29__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

class clsSpammerControl
{
public:
	bool readTemplateFile();
	clsSpammerControl();
	virtual ~clsSpammerControl();

};

#endif // !defined(AFX_CLSSPAMMERCONTROL_H__3CC879F5_40EF_11D1_9E72_006097379B29__INCLUDED_)
