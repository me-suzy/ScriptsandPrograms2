/*	$Id: clsebayappTimeShow.cpp,v 1.4.554.3 1999/08/06 20:31:55 nsacco Exp $	*/
//
//	File:	clseBayAppTimeShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	vicki Shu (vicki@ebay.com)
//
//	Function:
//
// Modifications:
//				- 05/20/98 vicki created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/22/99	petra	use clseBayTimeWidget for eBay time 
//

#include "ebihdr.h"
#include "clseBayTimeWidget.h"		// petra

void clseBayApp::TimeShow(CEBayISAPIExtension *pServer)
{
     
	time_t t;
	char ts[32];
	char ts1[32];
	char ts2[32];
	char ts3[32], ts4[32], ts5[32], ts5_1[32];
	char *pts;
	char *pts1;
	char *pts2; 
	char *pts3;
	char *pts4, *pts5, *pts5_1;
	struct tm*	pTime;


	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Time"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<br>"
			  <<	"\n";
	// header
	*mpStream	<<	"<h2>eBay current date and time </h2>\n";

	t = time(0); //local time
	clseBayTimeWidget timeWidget(mpMarketPlace, 2, 2, t);	// petra

	strcpy(ts, ctime(&t));
	t += 3600;   // Mountain time
	strcpy(ts1, ctime(&t));
	ts[24] = '\0';
	
	pts1 = &ts1[11];
	ts1[20] = '\0'; 

	t += 3600;	//Central time
	strcpy(ts2, ctime(&t));
	pts2 = &ts2[11];
	ts2[20] = '\0';

	t += 3600;	//Eastern Time
	strcpy(ts3, ctime(&t));
	pts3 = &ts3[11];
	ts3[20] = '\0';

	t -= 4*3600; //Alaska Time
	strcpy(ts4, ctime(&t));
	pts4 = &ts4[11];
	ts4[20] = '\0';
	
	t -= 3600;//Hawaii Time
	strcpy(ts5, ctime(&t));
	pts5 = &ts5[11];
	ts5[20] = '\0';

	t -= 3600; //Hawaii Time (no daylight saving)
	strcpy(ts5_1, ctime(&t));
	pts5_1 = &ts5_1[11];
	ts5_1[20] = '\0';

	*mpStream <<	"<p>Look here to see what time eBay thinks it is." 
					"<p>At the tone, the time will be... <b>";
//	*mpStream <<	ts
	timeWidget.EmitHTML(mpStream);		// petra
	*mpStream  <<	"</b><br>";

	pts = &ts[11];
	ts[20] = '\0'; 
			  
	*mpStream <<	"<p><table><TR align=left><TH align=left><font size=2>Pacific Time</font></TH>"
					"<TH align=left><font size=2>Mountain Time</font></TH>"
					"<TH align=left><font size=2>Central Time</font></TH>"
					"<TH align=left><font size=2>Eastern Time</font></TH></TR>"
					"<TR><TD align=left><font size=2>"
			  <<	pts
			  <<	"</font></TD><TD align=left><font size=2>"
			  <<	pts1
			  <<	"</font></TD><TD align=left><font size=2>"
			  <<	pts2
			  <<	"</font></TD><TD align=left><font size=2>"
			  <<	pts3
			  <<	"</font></TD></TR>"
					"<TR><TD colspan = 4>"
			  

	//				"<img src=\"http://pics.ebay.com/aw/";
	// kakiyama 07/16/99
			  <<    "<img src=\""
			  <<    mpMarketPlace->GetPicsPath();

	pTime = localtime(&t);
	//Positive value if daylight saving time is in effect
	if (pTime->tm_isdst > 0)
	//	*mpStream <<	"pics/usmap2.gif\" ";
	// kakiyama 07/16/99
		*mpStream <<    "usmap2.gif\" ";
	else
	//	*mpStream <<	"pics/usmap.gif\" ";
	// kakiyama 07/16/99
		*mpStream <<    "usmap.gif\" ";

	*mpStream <<	"width=\"480\" hspace=\"0\" vspace=\"0\""
					"height=\"280\" alt=\"usa_map\" border=\"0\"></TD></TR>"
			  <<	"<TR><TD align=left><font size=2><strong>Alaska Time:  </strong>"
			  <<	pts4
			  <<	"</font>"
			  <<	"</TD><TD align=right><font size=2><strong>Hawaii Time:  </strong>";

	//Hawaii does not have daylight saving
	if (pTime->tm_isdst > 0)
		*mpStream << pts5_1; 
	else
		*mpStream << pts5;
	
	*mpStream <<	"</font>"
					"</TD></TR>"
					"</table>";

	*mpStream <<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}
