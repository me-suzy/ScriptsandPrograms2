/*	$Id: clseBayUserDemoInfoWidget.cpp,v 1.3 1998/12/06 05:23:23 josh Exp $	*/
//
//	File:	clseBayUserDemoInfoWidget.cpp
//
//	Class:	clseBayUserDemoInfoWidget
//
//	Author:	Craig Huang (chuang@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 11/20/97 Craig Huang - Created
//
#include "widgets.h"
#include "clseBayUserDemoInfoWidget.h"
const int STARTVERYOPTIONAL = 10000;

clseBayUserDemoInfoWidget::clseBayUserDemoInfoWidget(clsMarketPlace *pMarketPlace, 
									  clsApp *pApp, clsCategories	*pclsCategory, CategoryVector *vCategories) :
	clseBayWidget(pMarketPlace, pApp)
{
	mpUserCodes = NULL;
	mvCategories = vCategories;
	mpCategories = pclsCategory;
	mpUsers = NULL;

}


clseBayUserDemoInfoWidget::~clseBayUserDemoInfoWidget()
{	
	mpUsers = NULL;
	mpUserCodes = NULL;
	mpCategories = NULL;
	mvCategories = NULL;
}

bool clseBayUserDemoInfoWidget::Initialize()
{
	
	if (mpMarketPlace)
	{
		mpUsers = mpMarketPlace->GetUsers();		
	}
	else
		return false;
	if(mpUsers)
	{
		mpUserCodes = mpUsers->GetUserCodes();
		return true;
	}
	else
		return false;
}


bool clseBayUserDemoInfoWidget::EmitHTML(ostream *pStream)
{
	UserCodeVector::iterator		vI;
	bool bFirst= true;	
	bool bFirstInput= true;
	bool selectOn= false;
	char qID[64];
//	int currentQuestionID = 0;
	// Need to get UserCode Object from marketplace, users object
	// For setting and save to DB, use the user object

	if(Initialize())
	{
		if(mpUserCodes)
		{
			UserCodeVector * pUserInfoVector = mpUserCodes->GetUserCodeVector();
			if(pUserInfoVector)
			{
				for (vI	= (*pUserInfoVector).begin();
				 vI != (*pUserInfoVector).end();
				 vI++)
				 {

					if((*vI)->GetUserCodeType() == USERCODEQSELECTTYPE)
					{					
						if(!bFirst)
						{
							if(selectOn)
							{
								*pStream << "</select>"
										 <<	"</td></tr>\n";
								selectOn = false;
							}
						}
						if(bFirst)			
						{	
							*pStream << "\n" 
									 << "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><strong><font size=\"4\" color=\"#800000\">"
									 << "Optional Info</strong></font></td>"						
									 << "<td width=\"75%\">&nbsp;</td>"
									 << "</tr>";
						}
						else if( (*vI)->GetSortNumber() == STARTVERYOPTIONAL )
						{
							*pStream << "\n"
									 << "<b>Very Optional </b>Information:"						
									 << "\n"
									 << "\n";
						}
						*pStream <<	"<tr>"
								 << "<td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=\"3\">";
								 
//						currentQuestionID= (*vI)->GetQuestionID();
						
						*pStream << (*vI)->GetQuestion()
								 << "</font></td>"
								 << "<td width=\"75%\">"
								 <<	"                        "
								 <<	"<SELECT NAME="
								 << "\""
								 << "Q"
								 << (*vI)->GetQuestionID()
								 << "\">";
						selectOn = true;
						bFirst = false;
					}					
					else if((*vI)->GetUserCodeType() == USERCODECATEGORYTYPE)
					{	
						
						sprintf(qID, "Q%d", (*vI)->GetQuestionID());
						if(!bFirst)
						{
							if (selectOn)
							{
								*pStream << "</SELECT>\n"
									 <<	"</td></tr>";
								selectOn = false;
							}
						}
						*pStream << "<tr>"
								 <<	"<td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=\"3\">"
								 << (*vI)->GetQuestion()
								 << "</font></td>"
								 << "<td width=\"75%\">"
								 <<	"";
						mpCategories->EmitHTMLTopSelectionList(pStream,
											  qID,
											   0,	
											  "0",
											  "Not Selected",
											  mvCategories);
						//already have </select>
						selectOn = false;
						*pStream << "</td></tr>";
					}
					else if((*vI)->GetUserCodeType() == USERCODEQINPUTTYPE)
					{					
						if(bFirstInput)	
						{
							if (selectOn)
							{
								*pStream << "</SELECT></td></tr>\n";
								selectOn = false;
							}
						}
								
						*pStream << "\n"
								 << (*vI)->GetQuestion()
								 << "\n"
								 << "\n"
								 <<	"                        "	
								 <<	"<INPUT TYPE=TEXT NAME="							 
								 << "Q"
								 << (*vI)->GetQuestionID()							 
								 << " SIZE=64 maxlength=255"							 
								 << ">";
						bFirstInput = false;						
					}
					else if((*vI)->GetUserCodeType() == USERCODEATYPE)
					{						
						*pStream << "<OPTION VALUE ="
								 << "\""
								 << (*vI)->GetQuestionCode()
								 << "\">"
								 << (*vI)->GetQuestion()
								 << "</OPTION>\n";		
						
					}					
					else if((*vI)->GetUserCodeType() == USERCODEADEFAULTTYPE)
					{						
						*pStream << "<OPTION SELECTED VALUE ="
								 << "\""
								 << (*vI)->GetQuestionCode()
								 << "\">"
								 << (*vI)->GetQuestion()
								 << "</OPTION>\n";
					}
				}

				if(selectOn)
				{
					*pStream << "</select>"
							 <<	"</td></tr>\n";
					selectOn = false;
				}
			}
			return true;
		}
		else
			return false;
	}
	else 
		return false;
}



bool clseBayUserDemoInfoWidget::EmitHTML(ostream *pStream, clsUser *pUser)
{
	UserCodeVector::iterator		vI;
	bool bFirst= true;	
	bool bFirstInput= true;	
	int currentQuestionID = 0;
	bool bGotBoolResponse = false;
	bool bGotNumberResponse = false;
	bool bGotTextResponse = false;
	bool bBoolResponse;
	bool selectOn = false;
	float  numberResponse = 0.0;
	char textResponse[256];
	char *pTextResponse = NULL;
	int aID;
	char qID[64];
	bool	bGetAttribute= false;
	// Need to get UserCode Object from marketplace, users object
	// For setting and save to DB, use the user object


	if(Initialize())
	{
		if(mpUserCodes)
		{
			UserCodeVector * pUserInfoVector= mpUserCodes->GetUserCodeVector();
			if(pUserInfoVector)
			{
				for (vI	= (*pUserInfoVector).begin();
				vI != (*pUserInfoVector).end();
				vI++)
				{
					
					if((*vI)->GetUserCodeType() == USERCODEQSELECTTYPE)
					{					
						if(!bFirst)
						{
							if(selectOn)
							{
								*pStream << "</select>"
									<<	"</td></tr>\n";
								selectOn = false;
							}
						}
						
						if(bFirst)			
						{	
							*pStream << "\n" 
								<< "<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><strong><font size=\"4\" color=\"#800000\">"
								<< "Optional Info </strong></font></td>"						
								<< "<td width=\"75%\">&nbsp;</td>"
								<< "</tr>";
						}
						else if( (*vI)->GetSortNumber() == STARTVERYOPTIONAL )
						{
							*pStream << "\n"
								<< "<b>Very Optional </b>Information:"						
								<< "\n"
								<< "\n";
						}
						*pStream <<	"<tr>"
							<< "<td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=\"3\">";
						
						currentQuestionID= (*vI)->GetQuestionID();
						*pStream << (*vI)->GetQuestion()
							<< "</font></td>"
							<< "<td width=\"75%\">"
							<<	"                        "
							<<	"<SELECT NAME="
							<< "\""
							<< "Q"
							<< (*vI)->GetQuestionID()
							<< "\">";
						selectOn = true;
						bFirst = false;
						bGetAttribute= true;
					}					
					else if((*vI)->GetUserCodeType() == USERCODECATEGORYTYPE)
					{						
						sprintf(qID, "Q%d", (*vI)->GetQuestionID());
						if(!bFirst)												
						{
							if (selectOn)
							{
								*pStream << "</SELECT>\n"
									<<	"</td></tr>";
								selectOn = false;
							}
						}						
						pUser->GetAttributeValue((*vI)->GetQuestionID(),
							&bGotBoolResponse,
							&bBoolResponse,
							&bGotNumberResponse,
							&numberResponse,
							&bGotTextResponse,
							(char **)textResponse);
						
						/* For radio Button */
						if(bGotBoolResponse)
						{
							;
						}
						/* For dropdown */
						else if(bGotNumberResponse)
						{
							*pStream << "<tr>"
								<<	"<td width=\"25%\" bgcolor=\"#EFEFEF\"><font size=\"3\">"
								<< (*vI)->GetQuestion()
								<< "</font></td>"
								<< "<td width=\"75%\">"
								<<	"";
							mpCategories->EmitHTMLTopSelectionList(pStream,
								qID,
								(unsigned int)numberResponse,					  	
								"0",
								"Not Selected",
								mvCategories);
							
							*pStream << "</td></tr>\n";
						}	
						else
						{
							*pStream << "\n"								 
								<< (*vI)->GetQuestion()
								<< "\n"
								<< "\n"
								<<	"                        ";
							mpCategories->EmitHTMLTopSelectionList(pStream,
								qID,
								0,	
								"0",
								"Not Selected",
								mvCategories);						
						}
						
					}
					else if((*vI)->GetUserCodeType() == USERCODEQINPUTTYPE)
					{					
						if(bFirstInput)
						{
							if (selectOn)
							{
								*pStream << "</SELECT></td></tr>\n";
								selectOn = false;
							}
						}																		
						aID= (*vI)->GetQuestionID();			
						
						if(pUser)
							pUser->GetAttributeValue(aID,
							&bGotBoolResponse,
							&bBoolResponse,
							&bGotNumberResponse,
							&numberResponse,
							&bGotTextResponse,
							&pTextResponse);
						
						if(!bGotTextResponse)
						{
							*pStream << "\n"
								<< (*vI)->GetQuestion()
								<< "\n"
								<< "\n"
								<<	"                        "	
								<<	"<INPUT TYPE=TEXT NAME="							 
								<< "Q"
								<< (*vI)->GetQuestionID()							 
								<< " VALUE=\"" 
								<< "\""
								<< " SIZE=64 "							 
								<< "maxlength=255"
								<< ">";
						}
						else
						{
							*pStream << "\n"
								<< (*vI)->GetQuestion()
								<< "\n"
								<< "\n"
								<<	"                        "	
								<<	"<INPUT TYPE=TEXT NAME="							 
								<< "Q"
								<< (*vI)->GetQuestionID()							 
								<< " VALUE=\"" 
								<< pTextResponse
								<< "\""
								<< " SIZE=64 "							 
								<< "maxlength=255"							 
								<< ">";
							delete pTextResponse;
						}
						
						bFirstInput= false;						
					}
					else if((*vI)->GetUserCodeType() == USERCODEATYPE
						|| (*vI)->GetUserCodeType() == USERCODEADEFAULTTYPE)
					{					
						if(pUser && bGetAttribute)
							pUser->GetAttributeValue(currentQuestionID,
							&bGotBoolResponse,
							&bBoolResponse,
							&bGotNumberResponse,
							&numberResponse,
							&bGotTextResponse,
							(char **)textResponse);
						
						/* For radio Button */
						if(bGotBoolResponse)
						{
							;
						}
						/* For dropdown */
						else if(bGotNumberResponse)
						{
							if((*vI)->GetQuestionCode() == numberResponse)
								*pStream << "<OPTION SELECTED VALUE ="
								<< "\""
								<< (*vI)->GetQuestionCode()
								<< "\">"
								<< (*vI)->GetQuestion()
								<< "</OPTION>\n";								
							else
								*pStream << "<OPTION VALUE ="
								<< "\""
								<< (*vI)->GetQuestionCode()
								<< "\">"
								<< (*vI)->GetQuestion()
								<< "</OPTION>\n";			
						}
						else
						{
							if((*vI)->GetUserCodeType() == USERCODEATYPE)
							{
								
								*pStream << "<OPTION VALUE ="
									<< "\""
									<< (*vI)->GetQuestionCode()
									<< "\">"
									<< (*vI)->GetQuestion()
									<< "</OPTION>\n";		
								
							}					
							else if((*vI)->GetUserCodeType() == USERCODEADEFAULTTYPE)
							{						
								*pStream << "<OPTION SELECTED VALUE ="
									<< "\""
									<< (*vI)->GetQuestionCode()
									<< "\">"
									<< (*vI)->GetQuestion()
									<< "</OPTION>\n";
							}
						}
						
						bGetAttribute = false;
					}										
				 }
				 if(selectOn)
				 {
					 *pStream << "</select>"
						 <<	"</td></tr>\n";
					 selectOn = false;
				 }
			}
			return true;
		}
		else
			return false;
	}
	else 
		return false;
}