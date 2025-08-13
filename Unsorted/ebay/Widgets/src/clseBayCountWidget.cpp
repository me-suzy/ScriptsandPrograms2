/* $Id: clseBayCountWidget.cpp,v 1.2 1998/10/16 01:01:39 josh Exp $ */
// Just prints a 'view count', retrieved from context.

#include "widgets.h"
#include "clseBayCountWidget.h"

bool clseBayCountWidget::EmitHTML(ostream *pStream)
{
    clsWidgetContext *pContext = mpWidgetHandler->GetWidgetContext();
    long *pNumViews = pContext->GetNumViews();

    if (!pNumViews)
        return false;

    *pStream << (int) (*pNumViews);
    return true;
}

