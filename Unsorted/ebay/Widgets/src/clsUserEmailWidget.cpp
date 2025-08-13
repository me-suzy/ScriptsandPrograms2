/*	$Id: clsUserEmailWidget.cpp,v 1.3 1998/12/06 05:22:46 josh Exp $	*/
// clsUserEmailWidget.cpp: implementation of the clsUserEmailWidget class.
//
//////////////////////////////////////////////////////////////////////
#include "widgets.h"
#include "clsUserEmailWidget.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsUserEmailWidget::clsUserEmailWidget()
{
	mpUser = NULL;
}

clsUserEmailWidget::~clsUserEmailWidget()
{
	mpUser = NULL;
}

bool clsUserEmailWidget::EmitHTML(ostream *pStream)
{
	char* pNewEmail = NULL;

	if (mpUser == NULL)
		return false;

	pNewEmail = clsUtilities::DrawSafeEmail(mpUser->GetEmail());

	*pStream << "<a href=\"mailto:"
			 << pNewEmail
			 << "\">"
			 << mpUser->GetEmail()
			 << "</a>\n";

	delete pNewEmail;

	return true;
}
