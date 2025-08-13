
//
//	File:		clseBayAppValidateUserForSurvey.cc
//
//	Class:		clseBayApp
//
//	Author:		Steve Yan (stevey@ebay.com)
//
//	Function:
//
//				Get the User ID & Password for survey
//
//	Modifications:
//				- 3/3/99 Steve	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.

#include "stdafx.h"
#include <AFXISAPI.H>

void clseBayApp::ValidateUserForSurvey(CEBayISAPIExtension *pServer, int surveyID)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request User ID and Password for Survey"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Survey</h2>\n";


	if ((599 == surveyID) || (699 == surveyID) || (799 == surveyID) || (899 == surveyID) || (999 == surveyID) || (1099 == surveyID) || (1199 == surveyID) || (1299 == surveyID))
	{
		// legal, rule
		*mpStream	<< "<font size=\"3\">" 
					<< "Your survey answers are anonymous. Your User ID will not be "
					<< "associated with your survey. We ask you to submit your User ID "
					<< "so that we can ensure only registered users complete the survey."

					<< "<P>Please note that you can only enter the survey <b>once</b>, "
					<< "regardless of whether you complete the survey or not. Please allow "
					<< "yourself enough time to answer approximately 30 questions."

					<< "<P>If you do not have enough time to complete the survey at this moment, "
					<< "then do not submit your User ID or password until you are able to do so."
					<< "</font>\n<P>";


		// form
		*mpStream	<<	"<form method=\"POST\" action=\""
					<<	mpMarketPlace->GetCGIPath(PageGoToSurvey)
					<<	"eBayISAPI.dll?\">\n"
						"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"GoToSurvey\">\n"
						"<input TYPE=\"HIDDEN\" NAME=\"surveyid\" "
						"VALUE="  <<  surveyID  <<  ">\n"
						"<table>\n";


			// print out the userid and password form
			*mpStream	<<	"<tr><td>Your "
						<<	mpMarketPlace->GetLoginPrompt()
						<<	":</td>\n"
							"<td><input type=\"text\" name=\"userid\" size=40></td></tr>\n"
							"<tr><td>Your "
						<<	mpMarketPlace->GetPasswordPrompt()
						<<	":</td>\n"
							"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
							"</table>\n";


		// add submit button and finish the form
		*mpStream	<<	"<p><input type=\"submit\" value=\"Submit\"></p>\n"
						"</form>\n";
	}
	else
	{
		*mpStream	<< "<p><font size=\"3\">" 
					<< "The surveyID you are using in the Url is not correct, make sure you use the correct one."
					<< "</font></p>\n";
	}



	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}


bool clseBayApp::GoToSurvey(CEBayISAPIExtension *pServer, char *pUserId, char *pPass, int surveyID, char *pRedirectURL)
{
	SetUp();

	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Survey"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader();

	// Before we do anything, check the user 
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream  <<	"<p>"
				   <<	mpMarketPlace->GetFooter()
				   <<	flush;
		CleanUp();
		return false;
	}

	if (surveyID == 0)
	{
		*mpStream	<<	"<h2>Survey</h2>"
						"Invalid input data"
						"<p>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;
		CleanUp();
		return false;
	}

	// do survey database stuff here

	bool bSurveyed = false;
	bSurveyed = mpUser->IsParticipatedSurvey(surveyID);

	if (bSurveyed)
	{
		// Customer message here
		*mpStream  <<	"<p>"
				   <<	"Our records indicate that you have completed this survey. "
				   <<	"Thank you so much for helping us and telling us what you think.\n"
				   <<	"<P>"
				   <<	"If you believe you have received this message in error, please contact "
				   <<	"<A HREF=\"mailto:buffy@ebay.com\">buffy@ebay.com.</A>"
				   <<	"<P>"
				   <<	mpMarketPlace->GetFooter()
				   <<	flush;
		CleanUp();
		return false;
	}

	mpUser->AddUserToSurveyRecord(surveyID);

	// Redirection for production:

//	char * sSurveyURL = "http://www.ebay.com";

// kakiyama 07/16/99

	char * sSurveyURL = (char *) (mpMarketPlace->GetHTMLPath());


	switch(surveyID)
	{
		case 599:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/may99.html";
				break;
			}
			

		case 699:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/jun99.html";
				break;
			}

		case 799:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/jul99.html";
				break;
			}

		case 899:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/aug99.html";
				break;
			}

		case 999:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/sep99.html";
				break;
			}

		case 1099:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/oct99.html";
				break;
			}

		case 1199:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/nov99.html";
				break;
			}

		case 1299:
			{
				sSurveyURL = "http://www.esurvey.com/ebay/dec99.html";
				break;
			}

		default:
			{
				break;
			}
	}


	sprintf(pRedirectURL, sSurveyURL);

	CleanUp();
	return true;
}

