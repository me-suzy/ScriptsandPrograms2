/*	$Id: clseBayCCCheckWidget.cpp,v 1.3.94.1 1999/06/08 03:04:12 poon Exp $	*/
/*	$Id	*/
//
//	File:	clseBayCCCheckWidget.cpp
//
//	Class:	clseBayCCCheckWidget
//
//	Author:	Sam
//
//	Function:
//			Widget that outputs a users Credit Card Info. 
//			Date is displayed in red if expiry date is 30 days or less.
//			This widget was derived from clseBayWidget by overriding
//			the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 02/17/98	Sam - Created
//				- 02/24/98  Sam - Modified to conform to MRD, Project#130

#include "widgets.h"
#include "clsAccount.h"
#include "clseBayCCCheckWidget.h"

clseBayCCCheckWidget::clseBayCCCheckWidget(clsMarketPlace *pMarketPlace)
										   :
	clseBayWidget(pMarketPlace)
{
		mpAccount = (clsAccount *)0;
}


bool clseBayCCCheckWidget::EmitHTML(ostream *pStream)
{
	time_t			ExpTime, DbTime, lastNoticeSent;
	int				MM, noticeMM, DD, noticeDD, YYYY, noticeYYYY;
	char			sNotice[30];
	struct tm		*pTime, *pDbTime, *pNoticeSent;

	// Get current date
	ExpTime		= time(0);
	pTime		= localtime(&ExpTime);

	// Change hour to 11:59:59 for precise comparison with DB date-time
	pTime->tm_hour  = 23;
	pTime->tm_min   = 59;
	pTime->tm_sec   = 59;
	// Now add mCCExpiry Days to pTime to get time to expiry.
	pTime->tm_mday += mCCExpiry;
	pTime->tm_isdst = -1;
	// Time to expiry in time_t format
	ExpTime			= mktime((struct tm *)pTime);

	// Check the expiry time in DB, an entry may not have been populated
	if(mpAccount && ((DbTime = mpAccount->GetCCExpiryDate()) > 0))
	{	
		// Valid Account
		pDbTime = localtime(&DbTime);
		MM   = pDbTime->tm_mon+1;
		DD	 = pDbTime->tm_mday;
		YYYY = pDbTime->tm_year+1900;

		lastNoticeSent	= mpAccount->GetLastCCNoticeSent();
		if (lastNoticeSent <= 0)
			// db entry is not set or was not read correctly
			strcpy(sNotice, "NA");
		else
		{
			pNoticeSent	= localtime(&lastNoticeSent);
			noticeMM	= pNoticeSent->tm_mon+1;
			noticeDD	= pNoticeSent->tm_mday;
			noticeYYYY	= pNoticeSent->tm_year+1900;
			sprintf(sNotice, "%d/%d/%d", noticeMM, noticeDD, noticeYYYY);
		}
		// Inform user to update CC info.
		*pStream << "<hr>"
				 << "<H3>"
				 << "** Account Information **"
				 << "</H3>"
				 << "<B>"
				 << "Credit Card Account:  "
				 << "</B>"
				 << "<I>"
				 << "<font size=\"2\">"
				 << mpAccount->GetCCIdForUser()
				 << "-XXXX-XXXX-XXXX"
				 << "</font>"
				 << "</I>"
				 << "<br>"
				 << "<B>"
				 << "Date Of Expiry:  "
				 << "</B>";

		if (DbTime <= ExpTime)
		{
			*pStream << "<I>"
					 << "<B>"
					 << "<font color=\"red\">"
					 << MM
					 << "/"
					 << DD
					 << "/"
					 << YYYY
					 << "</font>"
					 << "</B>"
					 << "</I>"
					 << "<BR>"
					 << "<B>"
					 << "Last Notice Sent On:  "
  					 << "</B>"
					 << "<I>"
					 << sNotice
					 << "</I>"
					 << "<BR><BR>"
					 << "<strong>"
					 << "Your Credit Card on file is about to expire. You may update your account "
					 << "information by using the following Link:<br>"
					 << "</strong>"
					 << "<A HREF=\""
					 << mpMarketPlace->GetHTMLPath()
					 << "help/basics/n-account.html"
					 << "\">"
					 << "Secure Credit Card Update Form"
					 << "</A>";
		}
		else
		{
			*pStream << "<I>"
					 << MM
					 << "/"
					 << DD
					 << "/"
					 << YYYY
					 << "</I>"
					 << "<br>"
					 << "<B>"
					 << "Last Notice Sent On:  "
  					 << "</B>"
					 << "<I>"
					 << sNotice
					 << "</I>"
					 << "<BR>";
		}

		// close shop
		*pStream << "<br><br>"
				 << "<hr>"
				 << endl;
	}

	return true;
}


