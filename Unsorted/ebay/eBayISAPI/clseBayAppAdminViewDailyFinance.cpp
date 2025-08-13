/*	$Id: clseBayAppAdminViewDailyFinance.cpp,v 1.6.280.1 1999/08/05 20:42:07 nsacco Exp $	*/
//
//	File:		clseBayAppAdminViewDailyFinance.cpp
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/18/97 wen	- Created
//

#include "ebihdr.h"
#include "clsStatistics.h"

static char* WeekDay[] = {"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"}; 

void clseBayApp::AdminViewDailyFinance(CEBayISAPIExtension *pServer,
							 int	StartMon,
							 int	StartDay,
							 int	StartYear,
							 int	EndMon,
							 int	EndDay,
							 int	EndYear,
							 char *pEmail,
							 char *pPass)
{
	time_t	StartTime;
	time_t	EndTime;

	SetUp();

	// title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	"Daily Finance"
			  <<	"</title>"
					"</head>\n"
			  <<	"<body><h2>eBay Daily Finance</h2>\n";

	// Check whether the dates are valid
	if (ConvertToTime_t(StartMon, StartDay, StartYear, &StartTime) == false)
	{
		*mpStream << "Invalid starting date"
				  << "<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
	}

	if (ConvertToTime_t(EndMon, EndDay, EndYear, &EndTime) == false)
	{
		*mpStream << "Invalid ending date"
				  << "<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
	}

	if (StartTime > EndTime)
	{
		*mpStream << "Invalid starting and ending date range"
				  << "<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
	}

	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword(0)) == 0 || 
		strcmp(pPass, mpMarketPlace->GetSpecialPassword(1)) == 0)
	{
		mpUser = NULL;
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pEmail, pPass, mpStream);
	}

	// If we didn't get the user, we're done
	if (!mpUser)
	{   
		*mpStream <<    "Not a valid user or password.";
		*mpStream <<	"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the user can administer categories
	if (!mpUser->HasAdmin(Financial))
	{
		*mpStream <<	"<p>"
						"You do not have Financial Administration privileges."
						"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Show statistics
	GetAndShowDailyFinance(StartTime, EndTime);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;

}

// Get and show the daily finance
//
void clseBayApp::GetAndShowDailyFinance(time_t StartTime, time_t EndTime)
{
	DailyFinanceVector				vDailyFinance;
	DailyFinanceVector::iterator	iDailyFinance;

	time_t	TheDate;
	struct tm*	DateTm;
	char	cDate[20];

	double	Insertion = 0.0;
	double	Bold = 0.0;
	double	CatFeature = 0.0;
	double	SuperFeature = 0.0;
	double	GiftIcon = 0.0;
	double	Gallery = 0.0;
	double	FeatureGallery = 0.0;
	double	FinalValue = 0.0;
	double	CourtesyCredit = 0.0;
	double	RelistingCredit = 0.0;
	double	NoSaleCredits = 0.0;
	double	OtherCRDR = 0.0;

	double	SumInsertion = 0.0;
	double	SumBold = 0.0;
	double	SumCatFeature = 0.0;
	double	SumSuperFeature = 0.0;
	double	SumGiftIcon = 0.0;
	double	SumGallery = 0.0;
	double	SumFeatureGallery = 0.0;
	double	SumFinalValue = 0.0;
	double	SumCourtesyCredit = 0.0;
	double	SumRelistingCredit = 0.0;
	double	SumNoSaleCredits = 0.0;
	double	SumOtherCRDR = 0.0;

	bool	ColorSwitch = true;
	char*	pColor;

	char*	pWhiteColor = "#FFFFFF";
	char*	pYellowColor = "#FFFFCC";

	int		PreMonth;

	// Retrieve daily finance data
	mpStatistics->GetDailyFinance(	StartTime, 
									EndTime, 
									&vDailyFinance);


	// Show table header and caption
	*mpStream <<	"<table border=\"1\" width=\"100%\" cellspacing=\"0\">"
			  <<	"<tr bgcolor=\"#DDDDAA\">"
			  <<	"<th width=\"12%\">Date</th>\n"
			  <<	"<th width=\"6%\">Insertion</th>\n"
			  <<	"<th width=\"6%\">Bold</th>\n"
			  <<	"<th width=\"6%\">Category Featured</th>\n"
			  <<	"<th width=\"6%\">Super Featured</th>\n"
			  <<	"<th width=\"6%\">Gift Icon</th>\n"
			  <<	"<th width=\"6%\">Gallery</th>\n"
			  <<	"<th width=\"6%\">Featured Gallery</th>\n"
			  <<	"<th width=\"6%\">Final Value</th>\n"
			  <<	"<th width=\"6%\">SubTototal</th>\n"
			  <<	"<th width=\"6%\">Courtesy Credits</th>\n"
			  <<	"<th width=\"6%\">Relisting Credits</th>\n"
			  <<	"<th width=\"6%\">No Sale Credits</th>\n"
			  <<	"<th width=\"6%\">Other CR/DR</th>\n"
			  <<	"<th width=\"6%\">Total</th>\n"
			  <<	"</tr>\n";

	iDailyFinance = vDailyFinance.begin();

	// Get date
	TheDate = (*iDailyFinance)->GetDate();
	DateTm = localtime(&TheDate);
	sprintf(cDate, "%02d/%02d/%02d %s", 
		DateTm->tm_mon+1, 
		DateTm->tm_mday, 
		DateTm->tm_year,
		WeekDay[DateTm->tm_wday]);

	PreMonth = DateTm->tm_mon;

	while (iDailyFinance != vDailyFinance.end())
	{
		// get other data
		Insertion	 = (*iDailyFinance)->GetAmount(AccountDetailFeeInsertion);
		Bold		 = (*iDailyFinance)->GetAmount(AccountDetailFeeBold);
		CatFeature	 = (*iDailyFinance)->GetAmount(AccountDetailFeeCategoryFeatured);
		SuperFeature = (*iDailyFinance)->GetAmount(AccountDetailFeeFeatured);
		GiftIcon	 = (*iDailyFinance)->GetAmount(AccountDetailFeeGiftIcon);
		Gallery		 = (*iDailyFinance)->GetAmount(AccountDetailFeeGallery);
		FeatureGallery = (*iDailyFinance)->GetAmount(AccountDetailFeeFeaturedGallery);
		FinalValue	 = (*iDailyFinance)->GetAmount(AccountDetailFeeFinalValue);
		CourtesyCredit	= (*iDailyFinance)->GetAmount(AccountDetailCreditCourtesy);
		RelistingCredit	= (*iDailyFinance)->GetAmount(AccountDetailCreditInsertionFee);
		NoSaleCredits	= (*iDailyFinance)->GetNoSaleCredits();
		OtherCRDR		= (*iDailyFinance)->GetOtherCRDR();

		if (ColorSwitch)
		{
			pColor = pWhiteColor;
			ColorSwitch = false;
		}
		else
		{
			pColor = pYellowColor;
			ColorSwitch = true;
		}

		PrintAFinanceRow(
					pColor,
					cDate,
					Insertion,
					Bold,
					CatFeature,
					SuperFeature,
					GiftIcon,
					Gallery,
					FeatureGallery,
					FinalValue,
					CourtesyCredit,
					RelistingCredit,
					NoSaleCredits,
					OtherCRDR);

		SumInsertion		+= Insertion;
		SumBold				+= Bold;
		SumCatFeature		+= CatFeature;
		SumSuperFeature		+= SuperFeature;
		SumGiftIcon			+= GiftIcon;
		SumGallery			+= Gallery;
		SumFeatureGallery	+= FeatureGallery;
		SumFinalValue		+= FinalValue;
		SumCourtesyCredit	+= CourtesyCredit;
		SumRelistingCredit	+= RelistingCredit;
		SumNoSaleCredits	+= NoSaleCredits;
		SumOtherCRDR		+= OtherCRDR;

		// clean up
		delete (*iDailyFinance);

		iDailyFinance++;

		if (iDailyFinance != vDailyFinance.end())
		{
			// Get date
			TheDate = (*iDailyFinance)->GetDate();
			DateTm = localtime(&TheDate);
			sprintf(cDate, "%02d/%02d/%02d %s", 
				DateTm->tm_mon+1, 
				DateTm->tm_mday, 
				DateTm->tm_year,
				WeekDay[DateTm->tm_wday]);

			if (DateTm->tm_mon != PreMonth)
			{
				// determind color
				if (ColorSwitch)
				{
					pColor = pWhiteColor;
					ColorSwitch = false;
				}
				else
				{
					pColor = pYellowColor;
					ColorSwitch = true;
				}

				// Print the total
				PrintAFinanceRow(
							pColor,
							"Total",
							SumInsertion,
							SumBold,
							SumCatFeature,
							SumSuperFeature,
							SumGiftIcon,
							SumGallery,
							SumFeatureGallery,
							SumFinalValue,
							SumCourtesyCredit,
							SumRelistingCredit,
							SumNoSaleCredits,
							SumOtherCRDR);

				// a break
				*mpStream << "<tr><td colspan=11>&nbsp;</td></tr>\n";

				// Reset
				SumInsertion = 0.0;
				SumBold = 0.0;
				SumCatFeature = 0.0;
				SumSuperFeature = 0.0;
				SumGiftIcon = 0.0;
				SumGallery = 0.0;
				SumFeatureGallery = 0.0;
				SumFinalValue = 0.0;
				SumCourtesyCredit = 0.0;
				SumRelistingCredit = 0.0;
				SumNoSaleCredits = 0.0;
				SumOtherCRDR = 0.0;
				PreMonth = DateTm->tm_mon;
			}

		}

	}

	// print the total
	if (ColorSwitch)
	{
		pColor = pWhiteColor;
		ColorSwitch = false;
	}
	else
	{
		pColor = pYellowColor;
		ColorSwitch = true;
	}

	// Print the total
	PrintAFinanceRow(
				pColor,
				"<b>Total</b>",
				SumInsertion,
				SumBold,
				SumCatFeature,
				SumSuperFeature,
				SumGiftIcon,
				SumGallery,
				SumFeatureGallery,
				SumFinalValue,
				SumCourtesyCredit,
				SumRelistingCredit,
				SumNoSaleCredits,
				SumOtherCRDR);

	// End the table
	*mpStream <<	"</table>\n"
			  <<	"<p>&nbsp;</p>";

	return;
}

// print a single row finance data
void clseBayApp::PrintAFinanceRow(
					char*	pColor,
					char*	pDate,
					double	Insertion,
					double	Bold,
					double	CatFeature,
					double	SuperFeature,
					double	GiftIcon,
					double	Gallery,
					double	FeatureGallery,
					double	FinalValue,
					double	CourtesyCredit,
					double	RelistingCredit,
					double	NoSaleCredits,
					double	OtherCRDR)
{
	char	cInsertion[20];
	char	cBold[20];
	char	cCatFeature[20];
	char	cSuperFeature[20];
	char	cGiftIcon[20];
	char	cGallery[20];
	char	cFeatureGallery[20];
	char	cFinalValue[20];
	char	cSubtotal[20];
	char	cCourtesyCredit[20];
	char	cRelistingCredit[20];
	char	cNoSaleCredits[20];
	char	cOtherCRDR[20];
	char	cTotal[20];

	double	Subtotal =  Insertion + 
						Bold + 
						CatFeature + 
						SuperFeature + 
						GiftIcon +
						Gallery +
						FeatureGallery +
						FinalValue;

	double	Total	 =  Subtotal + 
						CourtesyCredit +
						RelistingCredit +
						NoSaleCredits +
						OtherCRDR;

	FormatString(0.0 - Insertion,		cInsertion);
	FormatString(0.0 - Bold,			cBold);
	FormatString(0.0 - CatFeature,		cCatFeature);
	FormatString(0.0 - SuperFeature,	cSuperFeature);
	FormatString(0.0 - GiftIcon,		cGiftIcon);
	FormatString(0.0 - Gallery,			cGallery);
	FormatString(0.0 - FeatureGallery,	cFeatureGallery);
	FormatString(0.0 - FinalValue,		cFinalValue);
	FormatString(0.0 - Subtotal,		cSubtotal);
	FormatString(0.0 - CourtesyCredit,	cCourtesyCredit);
	FormatString(0.0 - RelistingCredit,	cRelistingCredit);
	FormatString(0.0 - NoSaleCredits,	cNoSaleCredits);
	FormatString(0.0 - OtherCRDR,		cOtherCRDR);
	FormatString(0.0 - Total,			cTotal);

	*mpStream <<	"<tr bgcolor=\""
			  <<	pColor
			  <<	"\"><td width=\"12%\">"
			  <<	pDate
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cInsertion
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cBold
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cCatFeature
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cSuperFeature
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cGiftIcon
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cGallery
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cFeatureGallery
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cFinalValue
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cSubtotal
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cCourtesyCredit
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cRelistingCredit
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cNoSaleCredits
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cOtherCRDR
			  <<	"</td>\n"
			  <<	"<td width=\"6%\" align=\"right\">"
			  <<	cTotal
			  <<	"</td></tr>\n";

}