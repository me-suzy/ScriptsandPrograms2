/*	$Id: userpage.h,v 1.3 1999/02/21 02:33:18 josh Exp $	*/
#ifndef USERPAGE_INCLUDED
#define USERPAGE_INCLUDED 1

#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsTextToWidgets.h"
#include "clsWidgetContext.h"
#include "clsWidgetPage.h"

static widgetDesignator sUserOkWidgets[] =
{
    { wtCountWidget, "ViewCount", strlen("ViewCount") },
    { wtUserIdWidget, "UserId", strlen("UserId") },
    { wtFeedbackWidget, "Feedback", strlen("Feedback") },
    { wtItemListWidget, "ItemList", strlen("ItemList") },
    { wtTimeWidget, "Time", strlen("Time") },
    { wtMemberSinceWidget, "MemberSince", strlen("MemberSince") },
	{ wtItemLinkWidget, "ItemLink", strlen("ItemLink") },
	{ wtLinkWidget, "Link", strlen("Link") },
	{ wtParagraphWidget, "Paragraph", strlen("Paragraph") },
	{ wtImageWidget, "Image", strlen("Image") }
};

static int sNumOkWidgets = sizeof (sUserOkWidgets) / sizeof (widgetDesignator);

static const char *commentText[] = 
{
	"Show no comments",
	"Show 10 most recent comments",
	"Show 25 most recent comments",
	"Show 50 most recent comments",
	"Show 100 most recent comments",
	"Show all comments"
};

static const char *itemlistText[] = 
{
	"Show no items",
	"Show 10 items",
	"Show 25 items",
	"Show 50 items",
	"Show 100 items",
	"Show all items"
};

static int commentAndFeedbackVals[] = 
{
	0, 10, 25, 50, 100, 999999
};

static const char *itemCaptionText[] = 
{
	"Check it out!",
	"I'm selling this.",
	"I collect these.",
	"I love these!",
	"Fun item for sale.",
	"Should be in every collection.",
	" -- leave blank --"
};

#endif /* USERPAGE_INCLUDED */
