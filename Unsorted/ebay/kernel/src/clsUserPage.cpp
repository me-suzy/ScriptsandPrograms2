/* $Id: clsUserPage.cpp,v 1.2 1998/10/16 01:06:12 josh Exp $ */
#include "eBayKernel.h"
#include "clsUserPage.h"

// Just implement these as straight calls to the database --
// they're all just wrappers so our clients don't see the
// database side of things.

void clsUserPage::SavePage()
{
    gApp->GetDatabase()->UpdateUserPage(this);
}

void clsUserPage::LoadPage(bool withDictionary)
{
    gApp->GetDatabase()->GetUserPage(this, withDictionary);
}

void clsUserPage::AddView()
{
    gApp->GetDatabase()->AddViewToUserPage(GetUserId(), GetPage());
}

void clsUserPage::SaveCategory()
{
    gApp->GetDatabase()->UpdateUserPageCategoryListing(this);
}

void clsUserPage::DeleteFromCategory()
{
    gApp->GetDatabase()->RemoveUserPageCategoryListing(GetUserId(),
        GetCategory());
}

void clsUserPage::RemovePage()
{
    gApp->GetDatabase()->RemoveUserPage(GetUserId(), GetPage());
}
