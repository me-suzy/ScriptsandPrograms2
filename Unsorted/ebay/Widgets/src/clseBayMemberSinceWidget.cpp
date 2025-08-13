/* $Id: clseBayMemberSinceWidget.cpp,v 1.3.436.1 1999/08/01 02:51:27 barry Exp $ */
// Just prints out a date for when the user registered.
// Relies on clseBayTimeWidget to do the work -- this just
// sets the time to print to the registration time.
#include "widgets.h"
#include "clseBayMemberSinceWidget.h"

// Construct via a blob.
clseBayMemberSinceWidget::clseBayMemberSinceWidget(clsWidgetHandler *pHandler,
    clsMarketPlace *pMarketPlace,
    clsApp *pApp) : clseBayWidget(pHandler, pMarketPlace, pApp),
    mTimeWidget(pHandler, pMarketPlace, pApp)
{
// petra	mTimeWidget.SetFormat("%B %#d, %Y");
	mTimeWidget.SetDateTimeFormat (2, -1);		// petra
}
// For translation to and from text.
void clseBayMemberSinceWidget::SetParams(vector<char *> *pvArgs)
{
    mTimeWidget.SetParams(pvArgs);
}

void clseBayMemberSinceWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	mTimeWidget.DrawTag(pStream, pName);
}

void clseBayMemberSinceWidget::SetParams(const void *pData, 
                                         const char *pStringBase, 
                                         bool fixBytes)
{
    mTimeWidget.SetParams(pData, pStringBase, fixBytes);
}

long clseBayMemberSinceWidget::GetBlob(clsDataPool *pDataPool, 
                                       bool fixBytes)
{
    return mTimeWidget.GetBlob(pDataPool, fixBytes);
}

// Emit the HTML for the header.
//  Should return whether or not it was successful.
bool clseBayMemberSinceWidget::EmitHTML(ostream *pStream)
{
    clsUser *pUser = mpWidgetHandler->GetWidgetContext()->GetUser();
    if (!pUser)
        return NULL;

    mTimeWidget.SetTime(pUser->GetCreated());

    return mTimeWidget.EmitHTML(pStream);
}

