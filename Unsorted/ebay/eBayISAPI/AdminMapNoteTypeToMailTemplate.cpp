/*	$Id: AdminMapNoteTypeToMailTemplate.cpp,v 1.6.2.4.76.1 1999/08/06 20:31:49 nsacco Exp $	*/
//
//	File:		AdminMapNoteTypeToMailTemplate.cpp.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	Contains the template e-mails for various admin functions.
//
//	Modifications:
//				- 11/28/98	michael	- created
//
#include "ebihdr.h"

//
// This is the "bcc" list for all emails sent out via
// various support functions
//
const char *AutomatedSupportEmailBccList[] = 
{
	"notify@ebay.com",
	NULL
};

const char *AutomatedSupportEmailBccListAuctionEnded[] = 
{
	"ebayend@ebay.com",
	NULL
};


clsMapNoteTypeToMailTemplate clseBayApp::mMapNoteTypeToMailTemplate[] =
{	
	//
	// Warnings
	//
	{	eNoteTypeProceduralWarning,
		"NOTICE: eBay Procedural Warning - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"[ATTN SUPPORT REP! insert text here!]\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningFeedbackSolicitation,
		"NOTICE: eBay Procedural Warning - Feedback Solicitation - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Feedback Solicitation - Offering to sell feedback, trade feedback\n"
		"gratuitously, or buy feedback, for the sake of the feedback itself.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeWarningShillBidding,
		"NOTICE: eBay Procedural Warning - Shill Bidding - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Shill Bidding,  - The  use of secondary registrations\n"
		"or associates, to artificially raise the level of bidding and/or price on\n"
		"an item.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},
			

	{	eNoteTypeWarningBidShielding,
		"NOTICE: eBay Procedural Warning - Bid Shielding - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Bid Shielding - The use of secondary registrations or\n"
		"associates, to artificially raise the level of bidding and/or price on an\n"
		"item to high levels, temporarily, in order to protect the low bid\n"
		"level of a third bidder.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeWarningShillFeedbackDefensive,
		"NOTICE: eBay Procedural Warning - Shill Feedback, Defensive - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Shill Feedback, Defensive - The use of secondary registrations\n"
		"or associates, to artificially raise the level of your own feedback.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},
		

	{	eNoteTypeWarningSpam,
		"NOTICE: eBay Procedural Warning - Sending Spam - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Sending Spam - SPAM is the sending of unsolicited, commercial email. This\n"
		"includes unsolicited e-mail to past bidders/buyers. Below is a copy of the email sent:\n\n" 
		"	<copy of spam email> "
		"\n"
		"\n"
		"This activity is prohibited as per the terms of our Disclaimer. We ask that\n"
		"you refrain from any further activity of this nature.  Instead of sending a\n"
		"mass, unsolicited email, you might consider compiling a Mailing List of\n"
		"eBay users who agree to receive your emailings. You may approach individual\n"
		"users via email and ask them privately if they would like to be included in\n"
		"your mailing lists. This would not be considered \"spam\". You will be\n"
		"surprised how many users will agree to be placed on your email mailing list\n"
		"once politely approached in this manner. \n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},



	{	eNoteTypeWarningFeedbackExtortion,
		"NOTICE: eBay Procedural Warning - Feedback Extortion - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Feedback Extortion - Demanding some undeserved action of a fellow user\n"
		"at the threat of leaving negative feedback. (\"Even though it didn\'t reach\n"
		"reserve, sell it to me for my bid or...\"; \"Pay me $100.00 or I will...\";\n"
		"Sell me all of the Dutch items or I will get all of my friends to...\")\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningBidSiphoning,
		"NOTICE: eBay Procedural Warning - Bid Siphoning - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Bid Siphoning -  E-mailing the bidders in a currently open auction,\n"
		"offering similar or the same item at price levels below the current bid.\n"
		"This includes auction interference, emailing bidders to warn them away from\n"
		"a seller or item.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningMisrepresentationIdentityeBay,
		"NOTICE: eBay Procedural Warning - Misrepresentation of Identity - eBay - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Misrepresentation of Identity (eBay) - Representing one's self as an eBay\n"
		"employee or a representative thereof.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningMisrepresentationIdentityUser,
		"NOTICE: eBay Procedural Warning - Misrepresentation of Identity - User - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Misrepresentation of Identity (user) - Representing one\'s self as another\n"
		"eBay user or registering using the identity of another user.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningAuctionInterception,
		"NOTICE: eBay Procedural Warning - Auction Interception - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Auction Interception -  Representing one\'s self as an eBay seller, and\n"
		"intercepting the ended auctions of that seller for the purposes of\n"
		"accepting payment for them	\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningBidManipulationRetraction,
		"NOTICE: eBay Procedural Warning - Bid Manipulation, Retraction - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Bid Manipulation by use of the retraction option. - This is usually used\n"
		"to discover the Max Bid of the current high bidder.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningBidManipulationHot,
		"NOTICE: eBay Procedural Warning - Bid Manipulation, Hot - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Bid Manipulation by Use of an Alias to Register Bids To Gain A HOT!\n"
		"Rating - The use of secondary registrations or associates, to\n"
		"artificially raise the number of bids to the level required to receive the\n"
		"HOT! designation.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},



	{	eNoteTypeWarningBidManipulationChronic,
		"NOTICE: eBay Procedural Warning - Bid Manipulation, Chronic - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Bid Manipulation (Chronic) - Bidding on items at auction without\n"
		"completing the transaction, thus blocking legitimate bidders, to the\n"
		"detriment of sellers\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningListingFormatAbuse,
		"NOTICE: eBay Procedural Warning - Listing format abuse - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Abuse of the Listing Formats to Avoid Fees. - Avoidance of fees through\n"
		"any manipulation of the system\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningAuctionNonperformanceChronic,
		"NOTICE: eBay Procedural Warning - Auction Nonperformance, Chronic - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Auction Nonperformance (Chronic) - Selling items at auction without\n"
		"completing the transaction, after having accepted payment.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningSiteInterference,
		"NOTICE: eBay Procedural Warning - Site Interference - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Using Any Mechanism To Interfere with eBay's Site and/or Operations\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningBadContactInformation,
		"NOTICE: eBay Procedural Warning - Contact information - %s",	
		"Dear %s (%s),\n\n"
		"Unfortunately,  the contact information you have provided on your \n"
		"registration record appears to be incomplete or invalid. eBay requires \n"
		"that a real-world address and phone number be entered as a prerequisite \n"
		"to registration and use of our online service. Note that you can provide \n"
		"a valid work or voice mail phone number and a P.O. Box instead of a \n"
		"street address.\n"
		"You may change, correct, or add to your contact information by following the simple instructions at:\n"
		"\n\n"
		"http://pages.ebay.com/services/myebay/change-registration.html \n"
		"\n\n"
		"In order to retain your registered status without any interruption in service, \n"
		"we ask that you review and correct the following information within the next 24 hours: \n" 
		"\n\n"
		"Full name: \n"
		"Address: \n"
		"City, State, Zip code: \n" 
		"Daytime phone number: \n"
		"Nighttime phone number: \n"
		"Fax (optional): \n"
		"\n"
		"It is not mandatory to provide both a daytime and nighttime phone number. \n"
		"If you encounter any difficulties updating your information, please feel \n"
		"free to contact us and we will be glad to assist. \n"
		"\n"
		"Thank you for your cooperation in this important matter. \n" 
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningPatentlyFalseContactInformation,
		"NOTICE: eBay Procedural Warning - Contact information - %s",	
		"Dear %s (%s),\n\n"
		"Unfortunately, the contact information you furnished \n"
		"when registering at eBay is either inaccurate or incomplete.\n"
		"Please respond to this message and include the following information:\n"
		"\n"
		"Email address: \n"
		"Full name:\n"
		"Company:(optional)\n"
		"Address:\n"
		"City:\n"
		"State:\n"
		"Postal Code:\n"
		"Country:\n"
		"Daytime phone number:\n\n"
		"Please note that if this information is not received within 24 hours,\n"
		"your eBay registration will be subject to suspension. \n\n"
		"Your cooperation and quick response in this matter is appreciated.\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningInvalidEmailAddress,
		"NOTICE: eBay Procedural Warning - Invalid email address - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Dead/invalid email addresses\n"
		"\n"
		"Please note that all eBay registered users must have a valid, working \n"
		"email address. We realize that you may not have been aware \n"
		"that your email address is currently invalid, so we are taking\n"
		"this opportunity to inform you of such and to ask that you make sure \n"
		"your email address is valid in the future. Failure to do so could result \n"
		"in the suspension of your eBay registration. \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeWarningUnderAgeUser,
		"NOTICE: eBay Procedural Warning - Under age user - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Underage User - User under the age of 18, users must be 18 years of age\n"
		"and able to legally enter into contracts\n"
		"\n"
		"Please note that minors are not allowed to register or use.\n"
		"eBay's system.\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningMinusFourFeedback,
		"NOTICE: eBay Procedural Warning - Feedback rating - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Minus 4 Feedback Rating\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningUnwelcomeBids,
		"NOTICE: eBay Procedural Warning - Unwelcome bids - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Persisting to bid on a seller's items, despite a warning from the seller\n"
		"that bids are not welcome\n"
		"\n"
		"Please note that this activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result \n"
		"in the suspension of your eBay registration. \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningPublishingContactInformation,
		"NOTICE: eBay Procedural Warning - Publishing Contact Information - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Publishing the Contact Information of Another User on ANY On-Line Public\n"
		"Area (Make sure to send a copy of the post with the header.)\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeWarningPiratedBootlegIllegalItems,
		"NOTICE: eBay Procedural Warning - Unauthorized or Infringing Items - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Offering Unauthorized Copies of Items for Sale\n"
		"\n"
		"eBay does not allow the listing of \n"
		"unauthorized copies of copyright protected audio, video,or software media. \n"
		"Please be aware that individuals who post unauthorized or infringing copies \n"
		"of protected works may be liable for civil or criminal penalties. Please be mindful \n"
		"of the possible legal issues pertaining to your listings in future. In addition,\n"
		"any reoccurrence of this activity on your part will result in the termination \n"
		"of your eBay registration.\n\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. \n\n"
		"Thank you for your cooperation in this matter.\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},
		

	{	eNoteTypeWarningBadLanguage,
		"NOTICE: eBay Procedural Warning - Language - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Use of inappropriate language for the eBay community\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},



	{	eNoteTypeWarningAuctionInterference,
		"NOTICE: eBay Procedural Warning - Auction Interference - %s",	
		"Dear %s (%s),\n\n"
		"We were recently informed that your eBay registered account \n"
		"was used in the following activity: \n\n"
		"* Auction Interference - emailing bidders to warn them away from a seller\n"
		"or item or emailing sellers to blacklist a bidder.\n"
		"\n"
		"This activity is not permitted at eBay.\n"
		"We realize that you may not have been aware of this eBay rule\n"
		"so we are taking this opportunity to inform you of the policy and \n"
		"ask that you to refrain from such activity in the future. \n"
		"Failure to do so could result in the suspension of your eBay registration.\n"
		"Thank you for your cooperation. ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionPublicBoardAbuse,

		"NOTICE: eBay Registration Suspension - Abuse of Public Boards - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* (NEED TEXT HERE)\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	//
	// Suspensions
	//
	{	eNoteTypeSuspensionFeedbackSolicitation,
		"NOTICE: eBay Registration Suspension - Feedback Solicitation - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Feedback Solicitation - Offering to sell feedback, trade feedback\n"
		"gratuitously, or buy feedback, for the sake of the feedback itself.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSuspensionShillBidding,
		"NOTICE: eBay Registration Suspension - Shill Bidding - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Shill Bidding,  - The use of secondary registrations\n"
		"or associates, to artificially raise the level of bidding and/or price on\n"
		"an item.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSuspensionBidShielding,
		"NOTICE: eBay Registration Suspension - Bid Shielding - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Bid Shielding - The use of secondary registrations or\n"
		"associates, to artificially raise the level of bidding and/or price on an\n"
		"item to extremely high levels, temporarily, in order to protect the low bid\n"
		"level of a third bidder.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},
 

	{	eNoteTypeSuspensionShillFeedbackDefensive,
		"NOTICE: eBay Registration Suspension - Shill Feedback, Defensive - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Shill Feedback, Defensive - The  use of secondary registrations\n"
		"or associates, to artificially raise the level of your own feedback.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionSpam,
		"NOTICE: eBay Registration Suspension - Sending Spam - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Sending Spam - SPAM is the sending of unsolicited, commercial email. This\n"
		"includes unsolicited e-mail to past bidders/buyers.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionFeedbackExtortion,
		"NOTICE: eBay Registration Suspension - Feedback Extortion - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Feedback Extortion - Demanding some undeserved action of a fellow user\n"
		"at the threat of leaving negative feedback. (\"Even though it didn\'t reach\n"
		"reserve, sell it to me for my bid or...\"; \"Pay me $100.00 or I will...\";\n"
		"Sell me all of the Dutch items or I will get all of my friends to...\")\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionBidSiphoning,
		"NOTICE: eBay Registration Suspension - Bid Siphoning - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Bid Siphoning -  E-mailing the bidders in a currently open auction,\n"
		"offering similar or the same item at price levels below the current bid.\n"
		"This includes auction interference, emailing bidders to warn them away from\n"
		"a seller or item.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionMisrepresentationIdentityeBay,
		"NOTICE: eBay Registration Suspension - Misrepresentation of Identity - eBay - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Misrepresentation of Identity (eBay) - Representing one's self as an eBay\n"
		"employee or a representative thereof.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionMisrepresentationIdentityUser,
		"NOTICE: eBay Registration Suspension - Misrepresentation of Identity - User - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Misrepresentation of Identity (user) - Representing one\'s self as another\n"
		"eBay user or registering using the identity of another user.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionAuctionInterception,
		"NOTICE: eBay Registration Suspension - Auction Interception - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Auction Interception -  Representing one\'s self as an eBay seller, and\n"
		"intercepting the ended auctions of that seller for the purposes of\n"
		"accepting payment for them	\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionBidManipulationRetraction,
		"NOTICE: eBay Registration Suspension - Bid Manipulation, Retraction - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Bid Manipulation by use of the retraction option. - This is usually used\n"
		"to discover the Max Bid of the current high bidder.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionBidManipulationHot,
		"NOTICE: eBay Registration Suspension - Bid Manipulation, Hot - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Bid Manipulation by Use of an Alias to Register Bids To Gain A HOT!\n"
		"Rating - The  use of secondary registrations or associates, to\n"
		"artificially raise the number of bids to the level required to receive the\n"
		"HOT! designation.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionBidManipulationChronic,
		"NOTICE: eBay Registration Suspension - Bid Manipulation, Chronic - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Bid Manipulation (Chronic) - Bidding on items at auction without\n"
		"completing the transaction, thus blocking legitimate bidders, to the\n"
		"detriment of sellers\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionListingFormatAbuse,
		"NOTICE: eBay Registration Suspension - Listing format abuse - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Abuse of the Listing Formats to Avoid Fees. - Avoidance of fees through\n"
		"any manipulation of the system\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionAuctionNonperformanceChronic,
		"NOTICE: eBay Registration Suspension - Auction Nonperformance, Chronic - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Auction Nonperformance (Chronic) - Selling items at auction without\n"
		"completing the transaction, after having accepted payment.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionSiteInterference,
		"NOTICE: eBay Registration Suspension - Site Interference - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Using Any Mechanism To Interfere with eBay's Site and/or Operations\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionBadContactInformation,
		"NOTICE: eBay Registration Suspension - Contact information - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* False, Missing or Omissive Contact Information\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionPatentlyFalseContactInformation,
		"NOTICE: eBay Registration Suspension - Contact information - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* False Contact Information\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSuspensionInvalidEmailAddress,
		"NOTICE: eBay Registration Suspension - Invalid email address - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Dead/invalid email addresses\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSuspensionUnderAgeUser,
		"NOTICE: eBay Registration Suspension - Under age user - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Underage User - User under the age of 18, users must be 18 years of age\n"
		"and able to legally enter into contracts\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionMinusFourFeedback,
		"NOTICE: eBay Registration Suspension - Feedback rating - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Minus 4 Feedback Rating\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionUnwelcomeBids,
		"NOTICE: eBay Registration Suspension - Unwelcome bids - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Persisting to bid on a seller's items, despite a warning from the seller\n"
		"that bids are not welcome\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionPublishingContactInformation,
		"NOTICE: eBay Registration Suspension - Publishing Contact Information - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Publishing the Contact Information of Another User on ANY On-Line Public\n"
		"Area (Make sure to send a copy of the post with the header.)\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionPiratedBootlegIllegalItems,
		"NOTICE: eBay Registration Suspension - Pirated, Bootleg, or Illegal items - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Offering Pirated Software/Bootleg Tapes/Illegal items for Sale\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	

	{	eNoteTypeSuspensionBadLanguage,
		"NOTICE: eBay Registration Suspension - Language - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Use of profanity or patently vulgar language of a racist, hateful, sexual\n"
		"or obscene nature in a public area.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionAuctionInterference,
		"NOTICE: eBay Registration Suspension - Auction Interference - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Auction Interference - emailing bidders to warn them away from a seller\n"
		"or item or emailing sellers to blacklist a bidder.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},



	//
	// Auction Endings here
	//

	{	eNoteTypeAuctionEndChoice,

		"NOTICE: eBay Auction(s) ended - Dutch Auction Choice",
		
		"Dear %s (%s),\n\n"
		"Your recently listed eBay Dutch Auction(s):\n\n"
		"%s\n\n"
		"was not properly listed. You are offering multiple items as \"choice\" in\n" 
		"a Dutch Auction whereas the rules (per our User Agreement, Section 5)\n"
		"for Dutch Auctions state that all items offered must be absolutely identical.\n" 
		"\n"
		"We have ended this auction early and credited your account for the\n" 
		"insertion fees.\n" 
		"\n"
		"Of course, you may relist the items in separate Dutch Auctions; one for\n" 
		"each type of item. For example, if you have a quantity of items that\n" 
		"only differ in size or color, you can list a Dutch Auction for each item\n"
		"by size or color. If you are unsure if your Dutch Auction listings do\n" 
		"not meet this criteria, you may send it to Support at support@ebay.com\n" 
		"for help and we can offer suggested changes if necessary.\n" 
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d ended - Dutch Auction Choice",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndCrossPost,

		"NOTICE: eBay Auction(s) Ended - Crossposting Service/Info Items",
		
		"Dear %s (%s),\n\n"
		"Your auction(s):\n\n"
		"%s\n\n"
		"which offer a service or informational item for bid were crossposted to\n"
		"non-service/non informational categories. In order to keep the \n"
		"categories free from inappropriate items, we have ended these auctions\n" 
		"and credited the insertion fees to your account. Items that offer a\n"
		"service or information pertaining to a service, may only be listed in\n"
		"the Miscellaneous : Services : Information Services category. If you are\n"
		"unsure whether or not your auction listings meet this criteria, you may\n"
		"send a copy of it directly to eBay Support ( at support@ebay.com ) and\n"
		"we can offer suggested changes if necessary.\n"
		"\n"
		"We have ended this auction early and credited your account for the\n" 
		"insertion fees.\n" 
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Crossposting Service/Info Items",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndCounterfeit,

		"NOTICE: eBay Auction(s) Ended - Counterfeit items",
		
		"Dear %s (%s),\n\n"
		"Your recent eBay item listing(s):\n\n"
		"%s\n\n"
		"has been ended early by eBay. The item was listed in violation of the\n" 
		"terms of our User Agreement, which states:\n"
		"\n"
		"\"...6.2 Your Information and the sale of your item(s) on eBay: (a) shall\n"
		"not infringe any third party\'s copyright, patent, trademark, trade\n"
		"secret or other proprietary rights or rights of publicity or privacy;...\"\n" 
		"\n"
		"You are hereby requested to refrain from listing any items that fit the\n" 
		"above criteria. Any future occurrence of this activity will result in\n"
		"your eBay registration suspension\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Counterfeit items",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},


	{	eNoteTypeAuctionEndBulkEmail,

		"NOTICE: eBay Auction(s) Ended - Bulk Email",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction:\n\n"
		"%s\n\n"
		"has been ended early by eBay. All fees for this auction will be \n"
		"credited to your account eBay does not allow the listing\n"
		"of auctions for lists of bulk email addresses. \n\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d ended - Bulk Email",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},


	{	eNoteTypeAuctionEndBootlegsEtc,

		"NOTICE: eBay Auction(s) Ended - Bootlegs, Pirated media, etc",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction::\n\n"
		"%s\n\n"
		"has been ended early because it may be infringing the \n"
		"copyright and/or trademark of the original owner. All fees for this \n"
		"listing will be credited to your account. Neither the bidder nor you,\n"
		"the seller, are under obligation to complete this transaction.\n\n"
		"eBay does not allow the listing of \"pirated\", \"bootlegged\", counterfeit\n"
		"or otherwise unauthorized uses of copyright or trademarked audio, video, \n"
		"or other media. eBay works with copyright and intellectual property \n"
		"owners to vigorously and expeditiously protect such rights. If you \n"
		"believe that you have received this message in error because you have \n"
		"a licensed right to sell such material, please contact the Content Owner directly.\n"
		"(for example, if a seller has questions about a counterfeit Rolex item, they should\n"
		"contact Rolex directly.)\n"		
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d ended - Bootlegs, Pirated media, etc",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndBonusesEtc,

		"NOTICE: eBay Auction(s) Ended - Bonuses, Giveaways, Prizes",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction: \n"
		"%s\n\n"
		"has been ended early by eBay. All fees for this auction \n"
		"will be credited to your account.\n"
		"eBay does not allow the listing of auctions that contain bonus \n"
		"items, giveaways, or random drawings or prizes as an enticement \n"
		"for bidders as such promotions are illegal lotteries in many states.\n"
		"Auction listings may only refer to the actual item or items listed for auction.\n\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d ended - Bonuses, Giveaways, Prizes",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndBestiality,

		"NOTICE: eBay Auction(s) Ended - Bestiality",
		
		"Dear %s (%s),\n\n"
		"Your recent eBay item listing(s):\n\n"
		"%s\n\n"
		"was recently brought to our attention. eBay does not allow the listing\n" 
		"of auctions for items which depict bestiality as these items are illegal\n"
		"to sell. We have ended this auction early and all appropriate fees will\n" 
		"be credited to your account.\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d ended - Bestiality",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},


	{	eNoteTypeAuctionEndAdvertisement,

		"NOTICE: eBay Auction(s) Ended - Advertisement",
		
		"Dear %s (%s),\n\n"
		"Were sorry but we had to end your auction(s) because it was not in \n"
		"accordance with our eBay listing policy. The following auction:\n"
		"%s\n\n"
		"was not listed as an actual auction but rather as an advertisement for \n"
		"your item or service and unfortunately, there is a difference. We allow \n"
		"goods or services being offered to advertise a business, however, eBay does \n"
		"not allow auction listings to be used as advertisements for soliciting \n"
		"potential customers for an item or service by offering it for direct purchase \n"
		"(that is,  bypassing the auction format). Therefore, we have ended this auction \n"
		"early and credited your account for the insertion fees. \n\n"
		"You may relist the auction but only as an actual auction with no references to \n"
		"direct purchase instructions or solicitations. If you are unsure if your auction \n"
		"listing  meets this criteria, please contact Customer Support for help and suggested changes.\n" 
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Advertisement",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndDoNotBidSingleItem,

		"NOTICE: eBay Auction(s) Ended - \"Do Not Bid - Single Item\"",
		
		"Dear %s (%s),\n\n"
		"We are sorry but we had to end your auctions early because directing users \n" 
		"to other auction items in your listing is not in accordance with eBays policies. \n"
		"The following eBay auctions:\n\n"
		"%s\n\n"
		"direct users to other auction items, which is not allowed. Although eBay \n"
		"understands your desire to get the best exposure for your auctions, eBay does \n"
		"not allow \"do not bid\" notices to be listed as auction listings. We have \n"
		"therefore ended the auctions listed above early and credited the insertion \n"
		"fees to your account. \n"
		"\n"
		"Please note that you can add a link in your item descriptions that points directly to\n" 
		"your other auctions by typing the following directly into the\n"
		"description when you first list the item:\n"
		"\n"
		"Click <A HREF=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewListedItems&userid=x\">\n"
		"here </A> for a list of our other auctions.\n" 
		"\n"
		"(Substitute your User Id or email address for \"x\")\n" 
		"\n"
		"This will create a \"hotlink\" at the word \"here\" that leads to a list of\n" 
		"all your other current auctions at eBay.\n" 
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - \"Do Not Bid - Single Item\"",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndSignPost,

		"NOTICE: eBay Auction(s) Ended - \"Do Not Bid - Single Item\"",
		
		"Dear %s (%s),\n\n"
		"Your recent eBay item listing(s):\n\n"
		"%s\n\n"
		"was not properly listed in as much as it is not an actual auction but\n" 
		"rather is a \"sign post\" notice pointing to another auction item.\n"
		"Although eBay can certainly understand your desire to get the best\n"
		"exposure to your auctions as possible, eBay does not allow do not bid\n" 
		"notices to be listed as auction listings at eBay.  You can well imagine\n"
		"the category chaos that would ensue were we to allow them. There would\n" 
		"be more sign posts than actual auctions! We have therefore, ended this\n"
		"auction early and credited the insertion fee to your account.\n"
		"\n"
		"You can add a link in your item descriptions that points directly to\n" 
		"your other auctions by typing the following directly into the\n"
		"description when you first list the item:\n"
		"\n"
		"Click <A HREF=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewListedItems&userid=x\">\n"
		"here </A> for a list of our other auctions.\n" 
		"\n"
		"(Substitute your User Id or email address for \"x\")\n" 
		"\n"
		"This will create a \"hotlink\" at the word \"here\" that leads to a list of\n" 
		"all your other current auctions at eBay.\n" 
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - \"Do Not Bid - Single Item\"",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndUsedUndergarments,

		"NOTICE: eBay Auction(s) Ended - Used,Unwashed Undergarments",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction: \n\n"
		"%s\n\n"
		"has been ended by eBay early. All fees associated with this auction \n"
		"have been credited to your account. \n\n"
		"Although you may not be aware of this policy, we do not allow used, \n"
		"unwashed undergarments for bid at eBay. \n\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",


		"eBay Auction %d Ended - Used Undergarments",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndSellerReserve,

		"NOTICE: eBay Auction(s) Ended - Seller indicates \"reserve\"",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction:\n\n"
		"%s\n\n"
		"has been ended by eBay early. All fees associated with this listing will\n"
		"be credited to your account.\n"
		"\n"
		"Although you may not be aware of this policy,\n"
		"eBay does not allow sellers to use a reserve price in a non Reserve\n" 
		"Price format listing. You may relist the item as either a Reserve Price\n" 
		"Auction or as a regular auction with an minimum opening bid equal to the\n"
		"lowest amount for which you are willing to sell the item. Please note\n" 
		"that sellers are obliged to sell their non Reserve Price Auction item at\n"
		"the current high bid to the current high bidder shown at the time of the\n"
		"auction's close regardless of any statement to the contrary that a\n" 
		"seller may include within the item's description.\n" 
		"\n"
		"For more information, please read the various auction guidelines on our\n" 
		"Rules page at:\n"
		"\n"
		"http://pages.ebay.com/help/community/png-comm.html\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Seller indicates \"reserve\"",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndRisqueTitlesFeatured,

		"NOTICE: eBay Auction(s) Ended - Risque Titles for Feature Auctions",
		
		"Dear %s (%s),\n\n"
		"Your recent eBay item listing(s):\n\n"
		"%s\n\n"
		"has been ended early by eBay. All fees for this auction will be credited\n"
		"to your account.\n"
		"\n"
		"This auction contains a title that violates eBay's guidelines for\n" 
		"listing Feature Auctions. eBay does not allow sellers of Featured\n" 
		"Auctions to list titles for their auctions that are risque or sexually\n"
		"provocative in nature. We ask that you refrain from using such titles in\n"
		"the future.\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"		
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Risque Titles for Feature Auctions",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndReplicas,

		"NOTICE: eBay Auction(s) Ended - Replicas",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction:\n\n"
		"%s\n\n"
		"has been ended by eBay due to listing irregularities. All fees associated\n"
		"with this auction will be credited to your account.\n"
		"\n"
		"As per the terms of our User Agreement...\n"
		"\n"
		"\"...6.2 Your Information and the sale of your item(s) on eBay: (a) shall\n"
		"not infringe any third party's copyright, patent, trademark, trade\n" 
		"secret or other proprietary rights or rights of publicity or privacy...\"\n"
		"\n"
		"Although you might not have been aware of this, this restriction covers even\n"
		"the mention of a trademarked name in an auction listing for a replica or\n"
		"fake item. For example, mentioning \"Not a Rolex (Breitling, etc)\" or stating\n"
		"that something is \"Gucci-style\" is not allowed. \n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Replicas",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndRaffles,

		"NOTICE: eBay Auction(s) ended - Raffles, Lotteries",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction:\n\n"
		"%s\n\n"
		"has been ended by eBay early and all fees associated with this auction\n"
		"will be credited to your account. Although you may not be aware of this\n"
		"policy, eBay does not allow the listing of raffles or lotteries as auction\n"
		"items, since they are illegal in many states.\n" 
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Raffles, Lotteries",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndPerBidAdditionalPurchase,

		"NOTICE: eBay Auction(s) Ended - Per bid additional purchase",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction:\n\n"
		"%s\n\n"
		"has been ended early and all fees associated with this auction will be\n" 
		"credited to your account.\n"
		"\n"

		"Although you may not be aware of this policy, eBay does not allow the\n"
		"listing of items which contain an offer to purchase additional items per\n"
		"the bid price, since this effectively circumvents eBay auction fees..\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Per bid additional purchase",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndMultiListing,

		"NOTICE: eBay Auction(s) Ended - Multi-listing",
		
		"Dear %s (%s),\n\n"
		"Your recent eBay item listing(s):\n\n"
		"%s\n\n"
		"have been ended early. All fees for these listings will be credited to\n"
		"your account.\n" 
		"\n"
		"Multiple listings of like items must use a Dutch Action format and be\n" 
		"listed in a single appropriate category\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Multi-listing",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndMLM,

		"NOTICE: eBay Auction(s) Ended - Multi-level marketing",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction:\n\n"
		"%s\n\n"
		"has been ended by eBay early, and all fees for this auction will be\n"
		"credited to your account. Neither the bidder nor the seller are obligated\n"
		"to complete this transaction.\n\n"
		"Although you may not be aware of this policy, eBay does not allow the listing\n"
		"of Multi-Level Marketing (MLM) programs as auction items.\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Multi-level marketing",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndLiveAnimals,

		"NOTICE: eBay Auction(s) Ended - Live Animals",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction:\n\n"
		"%s\n\n"
		"has been ended by eBay early and all fees associated with this auction\n"
		"will be credited to your account. Although we are certain that you\n" 
		"had only the best of intentions in mind when you listed this auction,\n"
		"eBay does not allow the listing of live animals for bid. We have ended\n"
		"this auction early and all appropriate fees will be credited to your\n"
		"account.\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Live Animals",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndLinksOtherAuctionServices,

		"NOTICE: eBay Auction(s) Ended - Links to other auction services",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your auction(s):\n\n"
		"%s\n\n"
		"has been ended by eBay early and all fees associated with this auction\n"
		"will be credited to your account. Although you may not be aware of this\n"
		"policy, eBay does not allow the listing of auctions which include links\n"
		"(static or live) to other online auction services.\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Links to other auction services",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndDirectSale,

		"NOTICE: eBay Auction(s) Ended - Item for Direct Sale",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction:\n\n"
		"%s\n\n"
		"has been ended by eBay early and all fees associated with this auction\n"
		"will be credited to your account.\n\n"
		"Although you may not be aware of this policy, eBay does not allow the\n"
		"listing of items that instruct bidders to not bid and/or offer the item\n"
		"for direct purchase from the seller since this effectively circumvents\n"
		"eBay auction fees.\n"
		"\n"		
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Item for Direct Sale",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndItemEmailedBeforeAuctionEnd,

		"NOTICE: eBay Auction(s) Ended - Item Emailed Before Auction Ends",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction:\n\n"
		"%s\n\n"
		"has been ended early. All fees associated with this listing will be\n" 
		"credited to your account within the next 30 days.\n"
		"\n"
		"Although you may not be aware of this policy, eBay does not allow\n"
		"the listing of items which invite the bidder to\n" 
		"receive the product via email before the auction is completed. You may\n" 
		"relist the item if you choose but only minus any such offer.\n"
		"\n"		
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Item Emailed Before Auction Ends",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndIllegalAdobeItems,

		"NOTICE: eBay Auction(s) Ended - Illegal Adobe Items",
		
		"Dear %s (%s),\n\n"
		"Your recent eBay item listing(s):\n\n"
		"%s\n\n"
		"Adobe Systems Incorporated (\"Adobe\") has asked ebay to close your auction(s)\n"
		"posted at http://www.ebay.com in connection with our investigation of\n"
		"instances of illegal duplication and/or distribution of Adobe\'s proprietary\n"
		"software products:\n"
		"\n"
		"%s\n\n"
		"\n"
		"From the Adobe Anti-Piracy Team:\n\n"
		"We are in receipt of your auction posted at http://www.ebay.com in which\n"
		"you have offered, or have transferred, what appear to be illegal copies of\n"
		"Adobe's proprietary software. Based on the information contained in your\n"
		"auction, we believe you may be in possession of and distributing\n"
		"unauthorized copies of Adobe\'s software.\n"
		"\n"
		"Please be advised that copyright infringement is a violation of the\n"
		"United States Copyright Act.  (17 U.S.C. section 101 et seq.)  Adobe views\n"
		"unauthorized duplication of its proprietary software products very\n"
		"seriously and is prepared to pursue its civil remedies.  The potential\n"
		"remedies available in copyright infringement actions are significant.\n"
		"For example, federal civil penalties provided in 17 U.S.C. Section 504\n"
		"allow the recovery of actual damages based upon the number of copies\n"
		"produced, or statutory damages.  Where the copyright owner proves that\n"
		"the infringement was willful, the court may increase the award of\n"
		"statutory damages up to $100,000 for each copyrighted product that has\n"
		"been infringed.  In addition, 17 U.S.C. Section 505 provides for the\n"
		"recovery of attorney's fees by the prevailing party.\n"
		"\n"
		"Even if  willful infringement is not conclusively established,\n"
		"Section 504 permits the copyright owner to elect an award of non-willful\n"
		"statutory damages of as much as $20,000 for each work infringed without\n"
		"the necessity of demonstrating actual damages.\n"
		"\n"
		"Your potential exposure for your contributory infringement, should this\n"
		"matter be litigated, could exceed $100,000.  However, despite the foregoing\n"
		"remedies available to us, Adobe may be willing to forego the\n"
		"filing a civil action provided you immediately cease all duplication and\n"
		"further distribution of any Adobe software product.\n"
		"\n"
		"In addition, you must not use eBay or any other online classifieds\n"
		"service or auction service to sell illegal copies of software.  If we\n"
		"discover more apparently illicit activity, we may seek maximum legal\n"
		"remedies against you.\n"
		"\n"
		"Accordingly, we must request that you respond to us within 20 days at\n"
		"piracy@adobe.com.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Illegal Adobe Items",

		"Dear %s (%s),\n\n"
		"Adobe has reviewed the software auction posted at http://www.ebay.com that\n"
		"you bid on:"
		"\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"Adobe requested that the posting be withdrawn for one or more of the\n" 
		"following reasons based on the representations in the auction and/or\n" 
		"further investigation by Adobe:\n"
		"\n"
		"From the Adobe Anti-Piracy Team:\n\n"
		"1. Adobe products are offered in quantity and the seller is not an\n"
		"authorized reseller of Adobe products or otherwise licensed to reproduce or\n"
		"distribute Adobe products.\n"
		"\n"
		"2. The software offered was previously distributed in violation of an\n"
		"authorized distributor, reseller, or OEM contract with Adobe and further\n"
		"distribution by the seller constitutes patent, trademark and copyright\n"
		"infringement.\n"
		"\n"
		"3. The software was duplicated in violation of the product\'s end-user\n"
		"license agreement and Adobe's intellectual property rights.\n"
		"\n"
		"4. The software was an academic, \"backup copy\", NFR (\"not-for-resale\"), or\n"
		"OEM version being offered in violation of applicable agreements with Adobe\n"
		"and further distribution by the seller constitutes patent, trademark and\n"
		"copyright infringement.\n"
		"\n"
		"5. The software being offered had previously been used to obtain an upgrade\n"
		"to a newer version and transfer from the prior owner is prohibited.\n"
		"\n"
		"\n"
		"Illegal distribution of software can subject the reseller to arrest and\n"
		"felony charges with fines up to $250,000 and prison terms of up to 5 years.\n"
		" In civil litigation against infringers trafficking in Adobe product that\n"
		"is illegally distributed or reproduced, Adobe can obtain the higher of its\n"
		"lost profits, the infringer's profits, or statutory damages of up to\n"
		"$100,000 per product, per infringement, in addition to recovery of Adobe\'s\n"
		"attorneys' fees in the action.  In 1997, piracy cost the software industry\n"
		"$2.8 billion in the U.S. alone.  For more information on software piracy,\n"
		"visit the websites of the Business Software Alliance http://www.bsa.org or\n"
		"the Software Publishers Association http://www.spa.org.\n"
		"\n"
		"The names of persons responsible for operating auctions of illegal Adobe\n"
		"software are retained and Adobe will seek criminal or civil prosecution of\n"
		"any repeat offenders.  (No waiver of any rights is made or intended by\n"
		"Adobe with respect to first-time offenders--Adobe has and will continue to\n"
		"pursue many first-time offenders.)\n"
		"\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Adobe has reviewed the software auction posted at http://www.ebay.com that\n"
		"you bid on:"
		"\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"Adobe requested that the posting be withdrawn for one or more of the\n" 
		"following reasons based on the representations in the auction and/or\n" 
		"further investigation by Adobe:\n"
		"\n"
		"From the Adobe Anti-Piracy Team:\n\n"
		"1. Adobe products are offered in quantity and the seller is not an\n"
		"authorized reseller of Adobe products or otherwise licensed to reproduce or\n"
		"distribute Adobe products.\n"
		"\n"
		"2. The software offered was previously distributed in violation of an\n"
		"authorized distributor, reseller, or OEM contract with Adobe and further\n"
		"distribution by the seller constitutes patent, trademark and copyright\n"
		"infringement.\n"
		"\n"
		"3. The software was duplicated in violation of the product\'s end-user\n"
		"license agreement and Adobe's intellectual property rights.\n"
		"\n"
		"4. The software was an academic, \"backup copy\", NFR (\"not-for-resale\"), or\n"
		"OEM version being offered in violation of applicable agreements with Adobe\n"
		"and further distribution by the seller constitutes patent, trademark and\n"
		"copyright infringement.\n"
		"\n"
		"5. The software being offered had previously been used to obtain an upgrade\n"
		"to a newer version and transfer from the prior owner is prohibited.\n"
		"\n"
		"\n"
		"Illegal distribution of software can subject the reseller to arrest and\n"
		"felony charges with fines up to $250,000 and prison terms of up to 5 years.\n"
		" In civil litigation against infringers trafficking in Adobe product that\n"
		"is illegally distributed or reproduced, Adobe can obtain the higher of its\n"
		"lost profits, the infringer's profits, or statutory damages of up to\n"
		"$100,000 per product, per infringement, in addition to recovery of Adobe\'s\n"
		"attorneys' fees in the action.  In 1997, piracy cost the software industry\n"
		"$2.8 billion in the U.S. alone.  For more information on software piracy,\n"
		"visit the websites of the Business Software Alliance http://www.bsa.org or\n"
		"the Software Publishers Association http://www.spa.org.\n"
		"\n"
		"The names of persons responsible for operating auctions of illegal Adobe\n"
		"software are retained and Adobe will seek criminal or civil prosecution of\n"
		"any repeat offenders.  (No waiver of any rights is made or intended by\n"
		"Adobe with respect to first-time offenders--Adobe has and will continue to\n"
		"pursue many first-time offenders.)\n"
		"\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndHighShippingCharges,

		"NOTICE: eBay Auction(s) Ended - High shipping charges",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction: n\n"
		"%s\n\n"
		"has been ended early and all fees associated with this auction will be\n" 
		"credited to your account.\n"
		"\n"
		"eBay does not allow the listing of Dutch Auction items that start at\n" 
		"less than $1 but state a shipping and handling charge of more than $5 in\n"
		"an attempt to circumvent eBay auction fees. You may relist the item but\n" 
		"only with either a starting price of $1 or more or with total shipping\n" 
		"and handling charges not more than $5.\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - High shipping charges",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndGenericLegal,

		"NOTICE: eBay Auction(s) Ended - Generic, legal",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction(s):\n\n"
		"%s\n\n"
		"has (have) been ended. All fees associated with this (these) auction(s)\n" 
		"has (have) been credited to your account.\n"
		"\n"
		"The item you have listed does not appear to be consistent with eBay guidelines.\n"
		"For further information about eBay's guidelines, please refer to:\n"
		"http://pages.ebay.com/help/community/png-items.html\n"
		"and \n"
		"http://pages.ebay.com/help/community/prohibited-items.html.\n\n"
		"Please note that any attempt to relist this item may result in the\n" 
		"suspension of your eBay registration.\n"
		"\n"		
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. \n"
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Generic, legal",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction:\n"
		"\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndFireworksExplosives,

		"NOTICE: eBay Auction(s) Ended - Fireworks, Explosives  ",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay auction(s)\n\n"
		"%s\n\n"
		"has been ended early and all appropriate fees will be credited to your\n"
		"account. Although you may not be aware of this policy, eBay does not allow\n"
		"the listing of fireworks, explosives, or other incendiary devices because\n"
		"they are illegal to sell. \n"
		"\n"		
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Fireworks, Explosives  ",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndFeaturedAuctions,

		"NOTICE: eBay Auction(s) Ended - Featured Auctions",
		
		"Dear %s (%s),\n\n"
		"Your eBay Featured Auction(s):\n\n"
		"%s\n\n"
		"does not meet the criteria for inclusion in the Featured Auction section\n"
		"and thus has been ended early by eBay. Neither the bidder(s) nor the\n" 
		"seller in this auction have any obligation to complete the transaction\n"
		"All fees for this auction, including the Featured Auction Fee, will be\n" 
		"credited to your eBay account.\n"
		"\n"
		"Per the Featured Auction policy below to which you agreed when you\n" 
		"placed this listing, we reserve the right to remove Featured auctions\n" 
		"without prior notice.  Your account will be credited all fees incurred.\n" 
		"\n"
		"Please note that the following types of auction are not eligible for\n" 
		"Featured placement:\n"
		"\n"
		"	Listings of an adult nature.\n"
		"	Listings for services or for the sale of information.\n" 
		"	Listings that are of a promotional/advertising nature.\n" 
		"	Listings that may be illicit, illegal, or immoral.\n"
		"	Listings that do not offer a genuine auction per eBay's Auction Rules.\n"
		"\n"
		"Please note that this is a non-exhaustive list, and eBay's decisions on\n" 
		"Featured placement are final.\n" 
		"\n"
		"We reserve the right to refuse Featured Auction placement for any\n" 
		"auction for any reason without explanation. You will be notified if we\n" 
		"remove your listing from this section, and your feature fee will be\n" 
		"refunded. Your insertion fee will not be refunded, as your auction will\n" 
		"be allowed to continue in a different category. Your Featured Auction\n" 
		"request indicates your agreement to this policy.\n" 
		"\n"
		"If you are unsure whether or not your auction qualifies for inclusion in\n"
		"the Featured Auction section, perhaps you could run any future featured\n" 
		"auction listings past our customer support folk (support@ebay.com) for\n" 
		"approval before posting.\n"
		"\n"		
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Featured Auctions",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndBuddy,

		"NOTICE: eBay Auction(s) Ended due to Copyright / Trademark issues",
		
		"Dear %s (%s),\n\n"
		"eBay Inc. has been notified that the following item(s) you have listed for\n"
		"auction is not authorized by the Content Owner (copyright or trademark holder).\n"
		"The item number(s) in\n"
		"question are:\n\n"
		"%s\n\n"
		"We take no position on the authenticity of your goods.  However, we are\n"
		"required to take any and all action as requested by a registered Content\n"
		"Owner with regard to their works, upon notice.  Accordingly, this auction\n"
		"has been ended early and the associated fees will be credited to your\n"
		"account.  Please do not list anything of this nature in the future as, it\n"
		"will result in suspension from eBay. \n" 
		"\n"
		"Be advised:  The auction of illegal items, including all counterfeit goods,\n"
		"is expressly prohibited and may subject you to criminal prosecution under\n"
		"Federal and State law.\n"  
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended due to Copyright / Trademark issues",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. If you proceed with the purchase of the item,\n"
		"you do so at your own legal risk and may be engaging in an illegal\n"
		"transaction; therefore, you as a bidder are not legally required to\n"
		"complete the transaction.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		"eBay Auction %d Ended due to Copyright/Trademark violation",

		"Dear %s,\n\n"
		"We wish to inform you that the following auction(s):\n"
		"\n"
		"#%d - %s\n"
		"\n"
		"has(have) been ended. All sellers and bidders have been warned and each item\n"
		"has been removed completely from the site.\n"
		"Regards ,\n\n"
		"%s (%s)\n\n"
	},

	{	eNoteTypeAuctionEndOther,

		"NOTICE: eBay Auction(s) Ended",
		
		"Dear %s (%s),\n\n"
		"You auction(s):\n\n"
		"%s\n\n"
		"have been ended for the following reason:\n\n\n\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndBuddyAreadyEnded,

		"NOTICE: eBay Procedural Warning (Content Owner Violation)",
		
		"Dear %s (%s),\n\n"
		"This letter is to inform you that eBay Inc. has been notified directly by\n"
		"the Content Owner (trademark or copyright holder) that the following item(s)\n"
		"you had listed for auction was (were)not authorized by them.\n"
		"The item number(s) in question are:\n\n"
		"%s\n\n"
		"We take no position on the authenticity of your goods.  However, we are\n"
		"required to take any and all action requested by a registered Content Owner\n"
		"with regard to their works, upon notice.  Had the above auction(s) still been\n"
		"running, it would have been ended early.  Please do not list anything of\n"
		"this nature in the future as, it will result in suspension from eBay.\n"  
		"\n"
		"Be advised:  The auction of illegal items, including all counterfeit goods,\n"
		"is expressly prohibited and may subject you to criminal prosecution under\n"
		"Federal and State law.\n"  
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Warning",

		"Dear %s (%s),\n\n"
		"We were notified that the following auction:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was not authorized by the Content Owner.  eBay takes no position on the\n"
		"authenticity of an item but we must take any necessary action as requested\n"
		"by such Content Owners.  If you proceed with the purchase of the item, you\n"
		"do so at your own legal risk and may be engaging in an illegal transaction;\n"
		"therefore, you as a bidder are not legally required to complete the\n"
		"transaction.\n"  
		"\n"
		"Be advised:  The purchase of illegal items, including all counterfeit\n"
		"goods, is expressly prohibited and may subject you to criminal prosecution\n"
		"under Federal and State law.  If you have questions about the product,\n"
		"please contact the Content Owner of the product.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"We were notified that the following auction:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was not authorized by the Content Owner.  eBay takes no position on the\n"
		"authenticity of an item but we must take any necessary action as requested\n"
		"by such Content Owners.  If you proceed with the purchase of the item, you\n"
		"do so at your own legal risk and may be engaging in an illegal transaction;\n"
		"therefore, you as a bidder are not legally required to complete the\n"
		"transaction.\n"  
		"\n"
		"Be advised:  The purchase of illegal items, including all counterfeit\n"
		"goods, is expressly prohibited and may subject you to criminal prosecution\n"
		"under Federal and State law.  If you have questions about the product,\n"
		"please contact the Content Owner of the product.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		"eBay Auction %d ended due to Copyright/Trademark violation",

		"Dear %s,\n\n"
		"Regarding Auction:\n"
		"\n"
		"#%d - %s\n"
		"\n"
		"The seller, %s (%s) has notified that it was not Authorized by the Content\n"
		"Owner, as have all the high bidders.\n\n"
		"Please let me know if you have any furthur questions.\n\n"
		"Regards ,\n\n"
		"%s (%s)\n\n"
	},


	{	eNoteTypeAuctionEndAlreadyEnded,

		"NOTICE: eBay Procedural Warning",
		
		"Dear %s (%s),\n\n"
		"The following auction(s) have been brought to our attention:\n"
		"\n"
		"%s"
		"\n\n"
		"(insert text here)\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Warning",

		"Dear %s (%s),\n\n"
		"The following auction:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"has been ended. "
		"(insert text here)\n"
		"\n\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. \n"
		"\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"The following auction:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"has been ended. "
		"(insert text here)\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndAlreadyEndedBootlegPiratedReplica,

		"NOTICE: eBay Procedural Warning - Infringing Item",
		
		"Dear %s (%s),\n\n"
		"We would like to inform you that the owner or agent of the owner of\n"
		"intellectual property rights for the following item: :\n"
		"\n"
		"%s"
		"\n\n"
		"has notified us that the above item potentially infringes those rights.\n"
		"(Please see definition below of What Is Infringing?).  eBay takes no\n"
		"position on the authenticity of an item, but when a content owner notifies\n"
		"eBay that such an item exists on our site, it is our responsibility to notify\n"
		"all parties involved.  If eBay had been notified prior to the end of this\n"
		"auction, this auction would have been ended early.  We understand that you\n"
		"may have not been aware that these items were in violation of the Content\n"
		"Owners right, but now that you have been notified, please do not place any\n"
		"more items of this nature on the site.  Further actions, including suspension,\n"
		"may result to those parties involved in such activities. Please be aware that\n"
		"the auction of infringing items, including all counterfeit goods, is expressly\n"
		"prohibited and may subject a person to criminal prosecution under Federal and State law.\n"
		"\n"
		"What Is Infringing?\n"\
		"\n"
		"An item that may infringe on the copyright, trademark or content ownership of\n"
		"another company or individual.\n"
		"\n"  
		"To help better explain we have included some common examples when content owners\n"
		"right are violated:\n"
		"\n"
		"*\"Pirated\", \"Bootlegged\", or otherwise unauthorized copies of copyright protected\n"
		"audio, video or software media\n"
		"\n"
		"*Items which are authorized to be sold by a licensed retailer.\n"
		"\n"
		"\"Faux\" or \"Replica\" Items.  This restriction covers even the mention of a trade marked\n"
		"name in an auction listing for a replica or faux item.  For example:  \"not a Rolex\" or\n"
		"stating that an item is \"Gucci style\" is not allowed.\n"
		"\n"
		"We hope this information has been helpful to you.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Warning - Infringing Item",

		"Dear %s (%s),\n\n"
		"We would like to inform you that eBay has been notified that:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was potentially infringing the intellectual property rights (such as copyright) of a third party.\n"
		"(Please see definition below for \"What Is Infringing?\").  eBay takes no position on the\n"
		"authenticity of an item, but when an intellectual property owner or an agent of an intellectual\n"
		"propery owner notifies eBay that such an item exists on our site, it is our responsibility to\n"
		"notify all parties involved.  This auction has been ended early.  You as a bidder are not legally\n"
		"required to complete this transaction.  Please be advised that if you proceed with this transaction,\n"
		"you may do at your own legal risk, and may be engaging in an illegal transaction.  Please be aware that\n"
		"the purchase of potentially infringing items, including all counterfeit goods, is expressly prohibited\n"
		"and may subject a person to criminal prosecution under Federal and State law.\n"
		"\n"
		"What Is Infringing?\n"\
		"\n"
		"An item that may infringe on the copyright, trademark or content ownership of\n"
		"another company or individual.\n"
		"\n"  

		"To help better explain we have included some common examples when content owners\n"
		"right are violated:\n"
		"\n"
		"*\"Pirated\", \"Bootlegged\", or otherwise unauthorized copies of copyright protected\n"
		"audio, video or software media\n"
		"\n"
		"*Items which are authorized to be sold by a licensed retailer.\n"
		"\n"
		"\"Faux\" or \"Replica\" Items.  This restriction covers even the mention of a trade marked\n"
		"name in an auction listing for a replica or faux item.  For example:  \"not a Rolex\" or\n"
		"stating that an item is \"Gucci style\" is not allowed.\n"
		"\n"
		"We hope this information has been helpful to you.\n"
		"\n\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"We would like to inform you that eBay has been notified that:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was potentially infringing the intellectual property rights (such as copyright) of a third party.\n"
		"(Please see definition below for \"What Is Infringing?\").  eBay takes no position on the\n"
		"authenticity of an item, but when an intellectual property owner or an agent of an intellectual\n"
		"propery owner notifies eBay that such an item exists on our site, it is our responsibility to\n"
		"notify all parties involved.  This auction has been ended early.  You as a bidder are not legally\n"
		"required to complete this transaction.  Please be advised that if you proceed with this transaction,\n"
		"you may do at your own legal risk, and may be engaging in an illegal transaction.  Please be aware that\n"
		"the purchase of potentially infringing items, including all counterfeit goods, is expressly prohibited\n"
		"and may subject a person to criminal prosecution under Federal and State law.\n"
		"\n"
		"What Is Infringing?\n"\
		"\n"
		"An item that may infringe on the copyright, trademark or content ownership of\n"
		"another company or individual.\n"
		"\n"  

		"To help better explain we have included some common examples when content owners\n"
		"right are violated:\n"
		"\n"
		"*\"Pirated\", \"Bootlegged\", or otherwise unauthorized copies of copyright protected\n"
		"audio, video or software media\n"
		"\n"
		"*Items which are authorized to be sold by a licensed retailer.\n"
		"\n"
		"\"Faux\" or \"Replica\" Items.  This restriction covers even the mention of a trade marked\n"
		"name in an auction listing for a replica or faux item.  For example:  \"not a Rolex\" or\n"
		"stating that an item is \"Gucci style\" is not allowed.\n"
		"\n"
		"We hope this information has been helpful to you.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndAdultItemInappropriateCategory,

		"NOTICE: eBay Procedural Warning - Adult Item in Inappropriate Category",
		
		"Dear %s (%s),\n\n"
		"We regret to inform you that your recently listed item(s):\n"
		"\n"
		"%s"
		"\n\n"
		"has been ended early and all fees credited to your account. Your auction\n" 
		"was ended early because it was not listed in the appropriate category. All\n" 
		"items of adult nature are to be listed in the Adult categories ONLY. Should\n" 
		"you re list this, or similar items outside of the Adult category, your\n" 
		"account will be suspended.\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the\n" 
		"correct categorization of all items listed for auction, we will end \n" 
		"any inappropriately listed auction or move, to an appropriate category,\n"
		"any auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation.  Please do not hesitate to contact us if you have any \n"
            "questions about listing procedures. For more information on infringing or\n"
		"illegal items or for information on other eBay listing violations, we urge\n"
		"you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html\n\n"
		"Thank you in advance for your cooperation.		\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndAlreadyEndedAdultItemInappropriateCategory,

		"NOTICE: eBay Procedural Warning - Adult Item in Inappropriate Category",
		
		"Dear %s (%s),\n\n"
		"Your recently listed auction(s):\n"
		"\n"
		"%s"
		"\n\n"
		"was listed outside the Adult category. All items of adult nature are to be\n" 
		"listed in the Adult categories ONLY. Should you re list this, or similar\n" 
		"items outside of the Adult category, your account will be suspended.\n"
		"\n"
		"In an ongoing effort to conserve system resources and maintain the correct\n" 
		"categorization of all items listed for auction, we will end any\n" 
		"inappropriately listed auction or move, to an appropriate category, any\n" 
		"auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation. Please do not hesitate to contact us if you have any\n" 
		"questions about listing procedures.\n"
		"\n"
		"Thank you in advance for your cooperation.\n\n" 
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	//
	// NEW Warnings
	//
	{	eNoteTypeWarningeBayTrademarkViolation,
		"NOTICE: eBay Procedural Warning - %s",	
		"Dear %s (%s),\n\n"
		"This is to inform you that use of the eBay name in your auction listing:\n" 
		"\n"
		"<Auction title and item number>\n" 
		"\n"
		"is in violation of trademark laws.\n" 
		"\n"
		"If you want to include the eBay logo on your Web page, you must follow\n"
		"the instructions on the \"Link your site to eBay\" page located at\n" 
		"http://pages.ebay.com/services/buyandsell/link-buttons.html. If you do not not follow the\n" 
		"instructions on this page, you may  not use our logo. You may use or name\n" 
		"(not logo) in a descriptive sense if you include the following disclaimer:\n"
		"\n"
		"\"This listing is not endorsed, licensed, or supported by eBay in any manner.\"\n" 
		"\n"
		"Additionally, you must also email the following statement to all current bidders\n"
		"on your item:\n" 
		"\n"
		"\"This program is not endorsed, licensed or supported by eBay in any manner.\n" 
		"Any technical support for this program will be supplied (Seller or Company\n" 
		"Name) and not by eBay.\"\n" 
		"\n"
		"Your cooperation in this matter is appreciated.\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	//
	// NEW Suspensions
	//
	{	eNoteTypeSuspension30Days,
		"NOTICE: eBay 30 Day Suspension - %s",
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay registration has been suspended\n"
		"temporarily for a term of no less than 30 days.\n"
		"\n"
		"Your registration has been suspended for the following reason:\n"
		"\n"
		"<insert text here>"
		"\n"
		"\n"
		"As previously stated, this suspension is temporary and will be lifted\n"
		"after 30 days. Opening a secondary registration to continue to use our system\n"
		"in any way will be considered grounds for permanent suspension with no\n" 
		"possibility of reinstatement. The suspension does not relieve you of\n" 
		"your agreed-upon obligation to pay any fees you may owe to eBay.\n" 
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionSampleForMultipleAccounts,
		"NOTICE: eBay Registration Suspension - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that the following eBay accounts have been suspended:\n"
		"\n"
		"<insert account names here>\n"
		"\n"
		"\n"
		"These accounts have been suspended for the following reason(s):\n"
		"\n"
		"*<reason, see list suspendable offenses>\n"
		"\n"
		"\n"
		"While this suspension is active, you are prohibited from registering\n" 
		"under a new account name or using our system in any way. Any attempt\n" 
		"to reregister will result in permanent account suspension with no\n"
		"possibility of reinstatement. This suspension does not relieve you\n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay.\n" 
		"All responses or appeals regarding this suspension must be made \n"
		"directly to the Customer Support representative indicated in the \n"
		"signature of this notice, or you can write to eBay at the following \n"
		"address:\n"
		"\n"
		"eBay Inc\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspensionSampleForSingleAccount,
		"NOTICE: eBay Registration Suspension - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that the following eBay account has been suspended:\n"
		"\n"
		"<insert account name here>\n"
		"\n"
		"\n"
		"This account have been suspended for the following reason(s):\n"
		"\n"
		"*<reason, see list suspendable offenses>\n"
		"\n"
		"\n"
		"While this suspension is active, you are prohibited from registering\n" 
		"under a new account name or using our system in any way. Any attempt\n" 
		"to reregister will result in permanent account suspension with no\n"
		"possibility of reinstatement. This suspension does not relieve you\n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay.\n" 
		"All responses or appeals regarding this suspension must be made \n"
		"directly to the Customer Support representative indicated in the \n"
		"signature of this notice, or you can write to eBay at the following \n"
		"address:\n"
		"\n"
		"eBay Inc\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},



	{	eNoteTypeItemMovedItemMovedToAppropriateCategory,
		"NOTICE: eBay Auction(s) moved to appropriate category",	
		"Dear %s (%s),\n\n"
		"The following items were found to be listed in an inappropriate:\n"
		"eBay category, and have been moved:\n"
		"\n"
		"%s"
		"\n"
		"\n"
		"Thank you for your cooperation,\n\n"
		"eBay Customer Support\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	//
	// NEW Auction Endings -- Microsoft
	//
	{	eNoteTypeAuctionEndMicrosoft,

		"NOTICE: eBay Procedural Warning (Infringing Item Violation)",
		
		"Dear %s (%s),\n\n"
		"eBay has been notified by Microsoft Corporation that the following item(s) you have\n" 
		"listed for auction on eBay is believed to be counterfeit or other unauthorized Microsoft\n"
		"product.  The item(s) in question are:\n" 
		"%s"
		"\n"
		"\n"
		"We take no position on the authenticity of your goods.  However, we are required to take\n" 
		"any and all action requested by a Content Owner with regard to their works, upon notice.\n"  
		"Accordingly, the auction listed above has been terminated.\n"
		"\n"
		"Be advised: The auction of illegal items, including all counterfeit goods, is expressly\n" 
		"prohibited and may subject you to criminal prosecution under Federal and State law.\n" 
		"\n"
		"Below is information provided by Microsoft:\n"
		"\n"
		"	Dear Vendor:\n"
		"\n"
		"	As you are probably aware, software piracy on the Internet, including on Internet auction\n"
		"	sites, is a serious problem confronting the Internet community.  The distribution of\n"
		"	counterfeit and other unauthorized software on the web hurts consumers, leaving them with\n" 
		"	illegal product and putting at risk the integrity of their computer systems.  To address\n" 
		"	this problem and protect consumers, Microsoft and other software publishers continually\n"
		"	monitor the Internet to identify sites where pirated software products are being made available,\n"
		"	and take action to stop their unlawful distribution.  This process led us to your auction\n"
		"	on eBay.  Microsoft believes that the product offered in this auction is not genuine Microsoft\n" 
		"	product or that the proposed transaction otherwise infringes Microsoft's intellectual property\n"
		"	rights.\n"
		"\n"
		"	The distribution of counterfeit or other unauthorized Microsoft product is a very serious\n" 
		"	matter and could result in the imposition of civil damages or criminal penalties.  Federal\n"
		"	law authorizes damages up to $100,000 per willful copyright infringement and up to $1,000,000\n" 
		"	for willful trademark counterfeiting.  Intentional violators may also be subject to criminal\n" 
		"	penalties, including fines and imprisonment.\n"
		"\n"
		"	Microsoft demands that you cease and desist from distributing counterfeit or any other\n" 
		"	unauthorized Microsoft product immediately and reserves the right to seek all applicable\n" 
		"	legal remedies without further notice.  For information on obtaining genuine Microsoft\n" 
		"	products, you can contact one of our authorized distributors listed on our web site at\n" 
		"	http://www.microsoft.com/directaccess/antipiracy/disti.htm.  If you can provide\n" 
		"	information regarding the source of any unauthorized product offered in this auction,\n" 
		"	please e-mail us at Nettheft@microsoft.com.  For more information regarding Microsoft\'s\n"
		"	efforts to combat software piracy, please visit the Microsoft Piracy homepage at\n" 
		"	http://www.microsoft.com/piracy/.\n"   
		"\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"NOTICE: eBay Auction Warning - Item %d",

		"Dear %s (%s),\n\n"
		"The following auction(s):\n\n"
		"%s"
		"\n\n"
		"were ended early by eBay.  The auction was ended because Microsoft Corporation notified\n"
		"eBay that the item is believed to be counterfeit or other unauthorized Microsoft product.\n"
		"eBay takes no position on the authenticity of an item.  However, we are required to take \n"
		"any and all action requested by a Content Owner with regard to their works, upon notice. \n"
		"Because the auction has been terminated, you are not legally required to complete the\n" 
		"transaction.  In fact, if you were to proceed with the transaction, you would do so at your\n"
		"own legal risk and may be engaging in an illegal transaction.\n" 
		"\n"
		"Be advised: The auction of illegal items, including all counterfeit goods, is expressly\n"
		"prohibited and may subject you to criminal prosecution under Federal and State law.\n"
		"\n"
		"Below is information provided by Microsoft:\n"
		"\n"
		"Dear Auction Participant:\n"
		"\n"					
		"\n"
		"	As you may know, software piracy on the Internet, including on Internet auction sites,\n"
		"	is a serious problem confronting the Internet community.  The distribution of counterfeit\n" 
		"	and other unauthorized software on the web hurts consumers, leaving them with illegal\n" 
		"	product and putting at risk the integrity of their computer systems.  To address\n" 
		"	this problem and to protect consumers, Microsoft and other software publishers continually\n" 
		"	monitor the Internet to identify sites where pirated software products are being made \n"
		"	available, and take action to stop their unlawful distribution.  This process led to \n"
		"	the termination of the auction in which you were a bidder.  Microsoft believes that the \n"
		"	product offered in this auction is not genuine Microsoft product or that the proposed\n" 
		"	transaction otherwise infringes Microsoft's intellectual property rights.\n"  
		"\n"
		"	Microsoft takes the protection of its trademarks and copyrights very seriously and\n"
		"	undertakes substantial legal and educational programs to protect consumers as well\n" 
		"	as honest resellers and system builders from counterfeit and illegal products.  Federal\n" 
		"	law authorizes damages up to $100,000 for each willful copyright infringement and up to\n" 
		"	$1,000,000 for willful trademark counterfeiting.  Intentional violators may also be subject\n"
		"	to criminal penalties, including fines and imprisonment.  To protect yourself and ensure\n" 
		"	you are obtaining genuine, authorized Microsoft products, purchase your software from\n" 
		"	recognized resellers of Microsoft products.  For a list of merchants that distribute\n" 
		"	authorized Microsoft products in your area or on line, visit\n" 
		"	http://www.microsoft.com/isapi/referral/product_search.asp.\n"  
		"\n"
		"	If you would like to provide information about piracy on the Internet, please e-mail\n" 
		"	us at Nettheft@microsoft.com.  For more information regarding Microsoft's efforts to combat\n"
		"	software piracy or information about how to identify legitimate Microsoft products, please \n"
		"	visit the Microsoft Piracy homepage at http://www.microsoft.com/piracy/. \n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"The following auction(s):\n\n"
		"%s"
		"\n\n"
		"were ended early by eBay.  The auction was ended because Microsoft Corporation notified\n"
		"eBay that the item is believed to be counterfeit or other unauthorized Microsoft product.\n"
		"eBay takes no position on the authenticity of an item.  However, we are required to take \n"
		"any and all action requested by a Content Owner with regard to their works, upon notice. \n"
		"Because the auction has been terminated, you are not legally required to complete the\n" 
		"transaction.  In fact, if you were to proceed with the transaction, you would do so at your\n"
		"own legal risk and may be engaging in an illegal transaction.\n" 
		"\n"
		"Be advised: The auction of illegal items, including all counterfeit goods, is expressly\n"
		"prohibited and may subject you to criminal prosecution under Federal and State law.\n"
		"\n"
		"Below is information provided by Microsoft:\n"
		"\n"
		"Dear Auction Participant:\n"
		"\n"					
		"\n"
		"	As you may know, software piracy on the Internet, including on Internet auction sites,\n"
		"	is a serious problem confronting the Internet community.  The distribution of counterfeit\n" 
		"	and other unauthorized software on the web hurts consumers, leaving them with illegal\n" 
		"	product and putting at risk the integrity of their computer systems.  To address\n" 
		"	this problem and to protect consumers, Microsoft and other software publishers continually\n" 
		"	monitor the Internet to identify sites where pirated software products are being made \n"
		"	available, and take action to stop their unlawful distribution.  This process led to \n"
		"	the termination of the auction in which you were a bidder.  Microsoft believes that the \n"
		"	product offered in this auction is not genuine Microsoft product or that the proposed\n" 
		"	transaction otherwise infringes Microsoft's intellectual property rights.\n"  
		"\n"
		"	Microsoft takes the protection of its trademarks and copyrights very seriously and\n"
		"	undertakes substantial legal and educational programs to protect consumers as well\n" 
		"	as honest resellers and system builders from counterfeit and illegal products.  Federal\n" 
		"	law authorizes damages up to $100,000 for each willful copyright infringement and up to\n" 
		"	$1,000,000 for willful trademark counterfeiting.  Intentional violators may also be subject\n"
		"	to criminal penalties, including fines and imprisonment.  To protect yourself and ensure\n" 
		"	you are obtaining genuine, authorized Microsoft products, purchase your software from\n" 
		"	recognized resellers of Microsoft products.  For a list of merchants that distribute\n" 
		"	authorized Microsoft products in your area or on line, visit\n" 
		"	http://www.microsoft.com/isapi/referral/product_search.asp.\n"  
		"\n"
		"	If you would like to provide information about piracy on the Internet, please e-mail\n" 
		"	us at Nettheft@microsoft.com.  For more information regarding Microsoft's efforts to combat\n"
		"	software piracy or information about how to identify legitimate Microsoft products, please \n"
		"	visit the Microsoft Piracy homepage at http://www.microsoft.com/piracy/. \n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	{	eNoteTypeAuctionEndAlreadyEndedMicrosoft,

		"NOTICE: eBay Procedural Warning (Infringing Item Violation)",
		
		"Dear %s (%s),\n\n"
		"eBay has been notified by Microsoft Corporation that the following item(s) you\n" 
		"listed for auction on eBay are believed to be counterfeit or other unauthorized Microsoft\n"
		"product(s).  The item(s) in question are:\n" 
		"%s"
		"\n"
		"\n"
		"We take no position on the authenticity of your goods.  However, we are required to take\n" 
		"any and all action requested by a Content Owner with regard to their works, upon notice.\n"  
		"Accordingly, the auction listed above have been removed from eBay.\n"
		"\n"
		"Be advised: The auction of illegal items, including all counterfeit goods, is expressly\n" 
		"prohibited and may subject you to criminal prosecution under Federal and State law.\n" 
		"\n"
		"Below is information provided by Microsoft:\n"
		"\n"
		"	Dear Vendor:\n"
		"\n"
		"	As you are probably aware, software piracy on the Internet, including on Internet auction\n"
		"	sites, is a serious problem confronting the Internet community.  The distribution of\n"
		"	counterfeit and other unauthorized software on the web hurts consumers, leaving them with\n" 
		"	illegal product and putting at risk the integrity of their computer systems.  To address\n" 
		"	this problem and protect consumers, Microsoft and other software publishers continually\n"
		"	monitor the Internet to identify sites where pirated software products are being made available,\n"
		"	and take action to stop their unlawful distribution.  This process led us to your auction\n"
		"	on eBay.  Microsoft believes that the product offered in this auction is not genuine Microsoft\n" 
		"	product or that the proposed transaction otherwise infringes Microsoft's intellectual property\n"
		"	rights.\n"
		"\n"
		"	The distribution of counterfeit or other unauthorized Microsoft product is a very serious\n" 
		"	matter and could result in the imposition of civil damages or criminal penalties.  Federal\n"
		"	law authorizes damages up to $100,000 per willful copyright infringement and up to $1,000,000\n" 
		"	for willful trademark counterfeiting.  Intentional violators may also be subject to criminal\n" 
		"	penalties, including fines and imprisonment.\n"
		"\n"
		"	Microsoft demands that you cease and desist from distributing counterfeit or any other\n" 
		"	unauthorized Microsoft product immediately and reserves the right to seek all applicable\n" 
		"	legal remedies without further notice.  For information on obtaining genuine Microsoft\n" 
		"	products, you can contact one of our authorized distributors listed on our web site at\n" 
		"	http://www.microsoft.com/directaccess/antipiracy/disti.htm.  If you can provide\n" 
		"	information regarding the source of any unauthorized product offered in this auction,\n" 
		"	please e-mail us at Nettheft@microsoft.com.  For more information regarding Microsoft\'s\n"
		"	efforts to combat software piracy, please visit the Microsoft Piracy homepage at\n" 
		"	http://www.microsoft.com/piracy/.\n"   
		"\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		"eBay Auction %d Ended",

		"Dear %s (%s),\n\n"
		"Microsoft Corporation notified eBay that following auction(s):\n\n"
		"%s"
		"\n\n"
		"are believed to be counterfeit or other unauthorized Microsoft product.\n"
		"eBay takes no position on the authenticity of an item.  However, we are required to take \n"
		"any and all action requested by a Content Owner with regard to their works, upon notice. \n"
		"Because of this, you are not legally required to complete the\n" 
		"transaction.  In fact, if you were to proceed with the transaction, you would do so at your\n"
		"own legal risk and may be engaging in an illegal transaction.\n" 
		"\n"
		"Be advised: The auction of illegal items, including all counterfeit goods, is expressly\n"
		"prohibited and may subject you to criminal prosecution under Federal and State law.\n"
		"\n"
		"Below is information provided by Microsoft:\n"
		"\n"
		"Dear Auction Participant:\n"
		"\n"					
		"\n"
		"	As you may know, software piracy on the Internet, including on Internet auction sites,\n"
		"	is a serious problem confronting the Internet community.  The distribution of counterfeit\n" 
		"	and other unauthorized software on the web hurts consumers, leaving them with illegal\n" 
		"	product and putting at risk the integrity of their computer systems.  To address\n" 
		"	this problem and to protect consumers, Microsoft and other software publishers continually\n" 
		"	monitor the Internet to identify sites where pirated software products are being made \n"
		"	available, and take action to stop their unlawful distribution.  This process led to \n"
		"	the termination of the auction in which you were a bidder.  Microsoft believes that the \n"
		"	product offered in this auction is not genuine Microsoft product or that the proposed\n" 
		"	transaction otherwise infringes Microsoft's intellectual property rights.\n"  
		"\n"
		"	Microsoft takes the protection of its trademarks and copyrights very seriously and\n"
		"	undertakes substantial legal and educational programs to protect consumers as well\n" 
		"	as honest resellers and system builders from counterfeit and illegal products.  Federal\n" 
		"	law authorizes damages up to $100,000 for each willful copyright infringement and up to\n" 
		"	$1,000,000 for willful trademark counterfeiting.  Intentional violators may also be subject\n"
		"	to criminal penalties, including fines and imprisonment.  To protect yourself and ensure\n" 
		"	you are obtaining genuine, authorized Microsoft products, purchase your software from\n" 
		"	recognized resellers of Microsoft products.  For a list of merchants that distribute\n" 
		"	authorized Microsoft products in your area or on line, visit\n" 
		"	http://www.microsoft.com/isapi/referral/product_search.asp.\n"  
		"\n"
		"	If you would like to provide information about piracy on the Internet, please e-mail\n" 
		"	us at Nettheft@microsoft.com.  For more information regarding Microsoft's efforts to combat\n"
		"	software piracy or information about how to identify legitimate Microsoft products, please \n"
		"	visit the Microsoft Piracy homepage at http://www.microsoft.com/piracy/. \n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Microsoft Corporation notified eBay that following auction(s):\n\n"
		"%s"
		"\n\n"
		"are believed to be counterfeit or other unauthorized Microsoft product.\n"
		"eBay takes no position on the authenticity of an item.  However, we are required to take \n"
		"any and all action requested by a Content Owner with regard to their works, upon notice. \n"
		"Because of this, you are not legally required to complete the\n" 
		"transaction.  In fact, if you were to proceed with the transaction, you would do so at your\n"
		"own legal risk and may be engaging in an illegal transaction.\n" 
		"\n"
		"Be advised: The auction of illegal items, including all counterfeit goods, is expressly\n"
		"prohibited and may subject you to criminal prosecution under Federal and State law.\n"
		"\n"
		"Below is information provided by Microsoft:\n"
		"\n"
		"Dear Auction Participant:\n"
		"\n"					
		"\n"
		"	As you may know, software piracy on the Internet, including on Internet auction sites,\n"
		"	is a serious problem confronting the Internet community.  The distribution of counterfeit\n" 
		"	and other unauthorized software on the web hurts consumers, leaving them with illegal\n" 
		"	product and putting at risk the integrity of their computer systems.  To address\n" 
		"	this problem and to protect consumers, Microsoft and other software publishers continually\n" 
		"	monitor the Internet to identify sites where pirated software products are being made \n"
		"	available, and take action to stop their unlawful distribution.  This process led to \n"
		"	the termination of the auction in which you were a bidder.  Microsoft believes that the \n"
		"	product offered in this auction is not genuine Microsoft product or that the proposed\n" 
		"	transaction otherwise infringes Microsoft's intellectual property rights.\n"  
		"\n"
		"	Microsoft takes the protection of its trademarks and copyrights very seriously and\n"
		"	undertakes substantial legal and educational programs to protect consumers as well\n" 
		"	as honest resellers and system builders from counterfeit and illegal products.  Federal\n" 
		"	law authorizes damages up to $100,000 for each willful copyright infringement and up to\n" 
		"	$1,000,000 for willful trademark counterfeiting.  Intentional violators may also be subject\n"
		"	to criminal penalties, including fines and imprisonment.  To protect yourself and ensure\n" 
		"	you are obtaining genuine, authorized Microsoft products, purchase your software from\n" 
		"	recognized resellers of Microsoft products.  For a list of merchants that distribute\n" 
		"	authorized Microsoft products in your area or on line, visit\n" 
		"	http://www.microsoft.com/isapi/referral/product_search.asp.\n"  
		"\n"
		"	If you would like to provide information about piracy on the Internet, please e-mail\n" 
		"	us at Nettheft@microsoft.com.  For more information regarding Microsoft's efforts to combat\n"
		"	software piracy or information about how to identify legitimate Microsoft products, please \n"
		"	visit the Microsoft Piracy homepage at http://www.microsoft.com/piracy/. \n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},


	//
	// New Suspensions
	//
	
	
	{	eNoteTypeSuspensionRepeatOffense,

		"NOTICE: eBay Registration Suspension - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that the following eBay account(s) have been suspended:\n"
		"\n"
		"<insert account names here>\n"
		"\n"
		"\n"
		"These accounts have been suspended for the following reason(s):\n"
		"\n"
		"* engaging in activity expressly prohibited on eBay's site after receiving\n"
		"prior warning to discontinue such activity, specifically:\n\n"
		"	<insert activity here>\n"
		"\n"
		"\n"
		"While this suspension is active, you are prohibited from registering\n" 
		"under a new account name or using our system in any way. Any attempt\n" 
		"to reregister will result in permanent account suspension with no\n"
		"possibility of reinstatement. This suspension does not relieve you\n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay.\n" 
		"All responses or appeals regarding this suspension must be made \n"
		"directly to the Customer Support representative indicated in the \n"
		"signature of this notice, or you can write to eBay at the following \n"
		"address:\n"
		"\n"
		"eBay Inc\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSuspensionPreviouslySuspendedUser,

		"NOTICE: eBay Registration Suspension - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended \n"
		"for the following reason:\n\n"
		"* Registered to a previously suspended user.\n"
		"\n"
		"While this suspension is active, you are prohibited from registering \n"
		"under a new account name or using our system in any way. Any attempt \n"
		"to reregister will result in permanent account suspension with no \n"
		"possibility of reinstatement. This suspension does not relieve you \n"
		"of your agreed-upon obligation to pay any fees you may owe to eBay. \n"
		"All responses or appeals regarding this suspension must be made directly \n"
		"to the Customer Support representative indicated in the signature of this \n"
		"notice, or you can write to eBay at the following address:\n\n"
		"eBay Inc.\n"
		"2005 Hamilton Ave., Ste 350\n"
		"San Jose, California 95125\n"
		"Attn: Escalations\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	{	eNoteTypeSuspension24Hours,

		"NOTICE: eBay Temporary Registration Suspension - %s",	
		"Dear %s (%s),\n\n"
		"We regret to inform you that your eBay account has been suspended temporarily\n"
		"for a term of no less than 24 hours. \n"
		"This registration has been suspended for the following reason:\n"
		"	* Disruption of a public posting site on eBay\n" 
		"Our Public formats must be kept clear for discussions directly related\n"
		"to the board to which you are posting. As previously stated, this suspension\n"
		"is temporary and will be lifted after 24 hours. Opening a secondary registration\n"
		"to continue posting or email bombing will be considered grounds for permanent suspension.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSuspensionUnregisteredPerUserRequest,

		"NOTICE: eBay Registration Unregistered - per user request",	
		"Dear %s (%s),\n\n"
		"Per your request, your eBay account has been deactivated:\n\n"
		"If you decide to have your registration reactivated, please send a\n"
		"request along with a copy of this email notice to safeharbor@ebay.com.\n" 
		"NOTE: Please do not re-register with another email address account\n"
		"without notifying us first.\n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL
	},

	//
	// New Auction Ended
	//

	{	eNoteTypeAuctionEndSellerSuspended,

		"NOTICE: eBay Auction(s) Ended - Seller Suspended",
		
		"Dear %s (%s),\n\n"
		"Your auction(s):\n"
		"\n"
		"%s"
		"\n\n"
		"have been ended early and all fees credited to your account. Your auctions\n"
		"were ended for the following reason:\n\n"
		"	* Account Suspension\n\n"
		"In an ongoing effort to conserve system resources and maintain the correct\n" 
		"categorization of all items listed for auction, we will end any\n" 
		"inappropriately listed auction or move, to an appropriate category, any\n" 
		"auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation. Please do not hesitate to contact us if you have any\n" 
		"questions about listing procedures.\n"
		"\n"
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay. The auction was ended due to the account suspension of the seller.\n"
		"All results for this auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay. The auction was ended due to the account suspension of the seller.\n"
		"All results for this auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL,
	},


	//
	// Miscellaneous
	//	
	{	eNoteTypeReinstatement,

		"NOTICE: eBay Registration Reinstatement - %s",	

		"Dear %s (%s),\n\n" 
		"We are happy to inform you that your registration has been reinstated.\n" 
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL
	},

	{	eNoteTypeReinstatementAfterShillWarning,

		"NOTICE: eBay Registration Reinstatement - %s",	

		"Dear %s (%s),\n\n" 
		"We are happy to inform you that your registration has been reinstated.\n" 
		"For a better understanding of the policies surrounding your suspension,\n"
		"please feel free to review the information at the following URL:\n\n" 
		"http://pages.ebay.com/safeharbor-shill.html \n\n"
		"http://pages.ebay.com/safeharbor-investigates.html \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL
	},

	//
	// NEW Warning
	//

	{	eNoteTypeWarningRegistrationVerification,

		"NOTICE: eBay Registration Verification - %s",	

		"Dear %s (%s),\n\n"
		"Unfortunately, the contact information you supplied when registering at eBay\n"
		"is either inaccurate or incomplete. Please respond to this message and include\n"
		"the following information:\n\n"
		"Email address:\n" 
		"Full name:\n"
		"Company:(optional)\n"
		"Address:\n"
		"City:\n"
		"State:\n"
		"Postal Code:\n"
		"Country:\n"
		"Daytime phone number:\n\n"
		"Please note that if this information is not received within 24 hours, your eBay\n"
		"registration will be subject to suspension.\n\n" 
		"Your cooperation and quick response in this matter is appreciated.\n\n"
		"%s (%s)\n%s\n\n",

		NULL,
		NULL,
		NULL,
		NULL
	},

// requested new category - 03/11/99

	{	eNoteTypeAuctionEndInappropriateItems,

		"NOTICE: eBay Auction(s) Ended - Item Deemed Inappropriate for eBay Listing",
		
		"Dear %s (%s),\n\n"
		"The following auction(s):\n\n"
		"%s\n\n"
		"has been ended early.  The content of your auction has been deemed inappropriate for\n"
		"inclusion in the eBay forum.\n\n"  
		"In an ongoing effort to conserve system resources and maintain the correct\n" 
		"categorization of all items listed for auction, we will end any\n" 
		"inappropriately listed auction or move, to an appropriate category, any\n" 
		"auction listed in an inappropriate category, pending Safe Harbor\n" 
		"investigation. Please do not hesitate to contact us if you have any\n" 
		"questions about listing procedures.\n"
		"For more information on infringing or illegal items or for information on other eBay listing \n"
		"violations, we urge you to visit the following eBay web page:\n\n"
		"http://pages.ebay.com/help/community/png-items.html \n\n"		
		"Thank you for your cooperation in this matter.\n"
		"\n"	
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",

		"eBay Auction %d Ended - Item Deemed Inappropriate for eBay Listing",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"Please be advised that the following auction, in which you were a\n"
		"participating bidder:\n"
		"\n"
		"%d - %s\n"                              
		"\n"
		"was ended early by eBay for listing violations. All results for this\n"
		"auction are null and void. \n"
		"\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",


		NULL,
		NULL

	},

// requested new category - 04/20/99


	// TODO - fix www.ebay.com, pages.ebay.com
	{	eNoteTypeAuctionEndBuddyIDSA,

		"NOTICE: eBay Auction(s) Ended - Infringing Items",
		
		"Dear %s (%s),\n\n"
		"Based on criteria supplied to eBay by the video game industry \n"
		"including Electronic Arts, Sony, Nintendo and Sega, concerning \n"
		"illegal or infringing items, the following auction: \n\n"
		"%s\n\n"
		"has been terminated.  Your auction listing either offered to sell\n"
		"infringing material, game copying devices, \"Mod Chips\" and/or other\n"
		"devices with the primary purpose of permitting the play of pirated \n"
		"product. Pursuant to our privacy policy, we have forwarded information \n"
		"concerning this auction to the appropriate companies, and will provide\n"
		"them your name and address and other information upon their request. \n"
		"\n"
		"Computer and video games are protected by U.S. copyright law. \n"

		"This means that only the copyright owner may reproduce and distribute \n"
		"the work. (The copyright owner is often identified on the game packaging.\n"
		"For example, you may see the designation \"ã [company name] 1999. All Rights\n"
		"Reserved\".) Without the copyright owner's permission, the only games \n"
		"that you may offer for sale on eBay are the original, legal copies that\n"
		"you purchased. Sale of pirated product, including backup copies and cdr's,\n"
		"is illegal and infringes the copyright owner's rights. Similarly, sale\n"
		"of devices which permit pirated product to be played is also illegal and\n"
		"contributes to the infringement of the copyright owner's rights. \n"
		"\n"
		"eBay has zero tolerance for the sale of pirated and illegal goods \n"
		"on its site. Our User Agreement provides that members may not offer \n"
		"items for sale that infringe upon any third party's copyright or other\n"
		"proprietary rights. We reserve the right to terminate any auction for \n"
		"any reason, including if it offers infringing or illegal goods for sale,\n"
		"and may also terminate your account if you continue to offer to sell \n"
		"goods that infringe another party's intellectual property rights.\n"
		"\n"
		"If you have questions about this policy, please see our User Agreement\n"
		"and our privacy policy posted at:\n"
		"\n"
		"www.ebay.com/privacy-policy.html\n\n"
		"If you have further questions about video game piracy, please see our \n"
		"FAQ at:\n"
		"\n"
		"<http://pages.ebay.com/help/help-faq-piracy.html> \n\n"
		"If you believe we've terminated your auction in error, please reply\n"
		"to the email address below for further information.\n\n" 
		"Respectfully,\n\n" 
		"%s (%s)\n%s\n\n",
		

		"NOTICE: eBay Auction(s) Ended - Infringing Items",

		"Dear %s (%s),\n\n"
		"We would like to thank you for your interest in video game products.\n"
		"Unfortunately, based on criteria supplied to eBay by the video game \n"
		"industry including Electronic Arts, Sony, Nintendo, and Sega \n"
		"concerning illegal or infringing items, the auction you recently bid on: \n\n"
		"%d - %s\n"                              
		"has been terminated. \n\n"
		"For your information, eBay's User Agreement provides that members \n"
		"may not offer items for sale that infringe upon any third party's \n"
		"copyright or other proprietary rights. We reserve the right to \n"
		"terminate any auction for any reason, including if it offers \n"
		"illegal and infringing goods for sale, and may also indefinitely suspend\n"
		"the accounts of users that repeatedly offer to sell goods that \n"
		"infringe another party's intellectual property rights. We also \n"
		"provide game publishers with all of the information that we can \n"
		"to help them bring civil and/or criminal charges against people \n"
		"who traffic in pirated products. The seller of the product you \n"
		"attempted to purchase has also been advised of this. \n"
		"\n"
		"If you have any questions about our policy please see our User\n"
		"Agreement and the SafeHarbor section of our web site. \n\n"
		"We understand that you may be unaware that the product you \n"
		"attempted to purchase is infringing. Accordingly, if you have \n"
		"any questions regarding infringing video game products please \n"
		"see the piracy section of our FAQ page at:\n\n"
		"	<http://pages.ebay.com/help/help-faq.html> \n\n"
		"or the piracy section of the Interactive Digital Software \n"
		"Association's (\"IDSA\") web site at www.idsa.com. \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"We would like to thank you for your interest in video game products.\n"
		"Unfortunately, based on criteria supplied to eBay by the video game \n"
		"industry including Electronic Arts, Sony, Nintendo, and Sega \n"
		"concerning illegal or infringing items, the auction you recently bid on: \n\n"
		"%d - %s\n"                              
		"has been terminated. \n\n"
		"For your information, eBay's User Agreement provides that members \n"
		"may not offer items for sale that infringe upon any third party's \n"
		"copyright or other proprietary rights. We reserve the right to \n"
		"terminate any auction for any reason, including if it offers \n"
		"illegal and infringing goods for sale, and may also terminate \n"
		"the accounts of users that repeatedly offer to sell goods that \n"
		"infringe another party's intellectual property rights. We also \n"
		"provide game publishers with all of the information that we can \n"
		"to help them bring civil and/or criminal charges against people \n"
		"who traffic in pirated products. The seller of the product you \n"
		"attempted to purchase has also been advised of this. \n"
		"\n"
		"If you have any questions about our policy please see our User\n"
		"Agreement and the SafeHarbor section of our web site. \n\n"
		"We understand that you may be unaware that the product you \n"
		"attempted to purchase is infringing. Accordingly, if you have \n"
		"any questions regarding infringing video game products please \n"
		"see the piracy section of our FAQ page at:\n\n"
		"	<http://pages.ebay.com/help/help-faq.html> \n\n"
		"or the piracy section of the Interactive Digital Software \n"
		"Association's (\"IDSA\") web site at www.idsa.com. \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Ended Auction Notification - Infringing Items\n",
		
		"Dear %s,\n\n"	
		"We wish to inform you that the following auction(s):\n\n"
		"%d - %s\n\n"	
		"has(have) been ended. All sellers and bidders have been\n"
		"warned and each item has been removed completely from the site.\n\n"
		"Regards,\n"
		"%s (%s)\n"
	},

	// fix www.ebay.com, pages.ebay
	{	eNoteTypeAuctionEndAlreadyEndedBuddyIDSA,

		"NOTICE: eBay Auction(s) Ended - Infringing Items",
		
		"Dear %s (%s),\n\n"
		"Based on criteria supplied to eBay by the video game industry \n"
		"including Electronic Arts, Sony, Nintendo and Sega, concerning \n"
		"illegal or infringing items, the following auction: \n\n"
		"%s\n\n"
		"has been terminated.  Your auction listing either offered to sell\n"
		"infringing material, game copying devices, \"Mod Chips\" and/or other\n"
		"devices with the primary purpose of permitting the play of pirated \n"
		"product. Pursuant to our privacy policy, we have forwarded information \n"
		"concerning this auction to the appropriate companies, and will provide\n"
		"them your name and address and other information upon their request. \n"
		"\n"
		"Computer and video games are protected by U.S. copyright law. \n"

		"This means that only the copyright owner may reproduce and distribute \n"
		"the work. (The copyright owner is often identified on the game packaging.\n"
		"For example, you may see the designation \"ã [company name] 1999. All Rights\n"
		"Reserved\".) Without the copyright owner's permission, the only games \n"
		"that you may offer for sale on eBay are the original, legal copies that\n"
		"you purchased. Sale of pirated product, including backup copies and cdr's,\n"
		"is illegal and infringes the copyright owner's rights. Similarly, sale\n"
		"of devices which permit pirated product to be played is also illegal and\n"
		"contributes to the infringement of the copyright owner's rights. \n"
		"\n"
		"eBay has zero tolerance for the sale of pirated and illegal goods \n"
		"on its site. Our User Agreement provides that members may not offer \n"
		"items for sale that infringe upon any third party's copyright or other\n"
		"proprietary rights. We reserve the right to terminate any auction for \n"
		"any reason, including if it offers infringing or illegal goods for sale,\n"
		"and may also terminate your account if you continue to offer to sell \n"
		"goods that infringe another party's intellectual property rights.\n"
		"\n"
		"If you have questions about this policy, please see our User Agreement\n"
		"and our privacy policy posted at:\n"
		"\n"
		"www.ebay.com/privacy-policy.html\n\n"
		"If you have further questions about video game piracy, please see our \n"
		"FAQ at:\n"
		"\n"
		"<http://pages.ebay.com/help/help-faq-piracy.html> \n\n"
		"If you believe we've terminated your auction in error, please reply\n"
		"to the email address below for further information.\n\n" 
		"Respectfully ,\n\n"
		"%s (%s)\n%s\n\n",
		

		"NOTICE: eBay Auction(s) Ended - Infringing Items",

		"Dear %s (%s),\n\n"
		"We would like to thank you for your interest in video game products.\n"
		"Unfortunately, based on criteria supplied to eBay by the video game \n"
		"industry including Electronic Arts, Sony, Nintendo, and Sega \n"
		"concerning illegal or infringing items, the auction you recently bid on: \n\n"
		"%d - %s\n"                              
		"has been terminated. \n\n"
		"For your information, eBay's User Agreement provides that members \n"
		"may not offer items for sale that infringe upon any third party's \n"
		"copyright or other proprietary rights. We reserve the right to \n"
		"terminate any auction for any reason, including if it offers \n"
		"illegal and infringing goods for sale, and may also terminate \n"
		"the accounts of users that repeatedly offer to sell goods that \n"
		"infringe another party's intellectual property rights. We also \n"
		"provide game publishers with all of the information that we can \n"
		"to help them bring civil and/or criminal charges against people \n"
		"who traffic in pirated products. The seller of the product you \n"
		"attempted to purchase has also been advised of this. \n"
		"\n"
		"If you have any questions about our policy please see our User\n"
		"Agreement and the SafeHarbor section of our web site. \n\n"
		"We understand that you may be unaware that the product you \n"
		"attempted to purchase is infringing. Accordingly, if you have \n"
		"any questions regarding infringing video game products please \n"
		"see the piracy section of our FAQ page at:\n\n"
		"	<http://pages.ebay.com/help/help-faq.html> \n\n"
		"or the piracy section of the Interactive Digital Software \n"
		"Association's (\"IDSA\") web site at www.idsa.com. \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Dear %s (%s),\n\n"
		"We would like to thank you for your interest in video game products.\n"
		"Unfortunately, based on criteria supplied to eBay by the video game \n"
		"industry including Electronic Arts, Sony, Nintendo, and Sega \n"
		"concerning illegal or infringing items, the auction you recently bid on: \n\n"
		"%d - %s\n"                              
		"has been terminated. \n\n"
		"For your information, eBay's User Agreement provides that members \n"
		"may not offer items for sale that infringe upon any third party's \n"
		"copyright or other proprietary rights. We reserve the right to \n"
		"terminate any auction for any reason, including if it offers \n"
		"illegal and infringing goods for sale, and may also terminate \n"
		"the accounts of users that repeatedly offer to sell goods that \n"
		"infringe another party's intellectual property rights. We also \n"
		"provide game publishers with all of the information that we can \n"
		"to help them bring civil and/or criminal charges against people \n"
		"who traffic in pirated products. The seller of the product you \n"
		"attempted to purchase has also been advised of this. \n"
		"\n"
		"If you have any questions about our policy please see our User\n"
		"Agreement and the SafeHarbor section of our web site. \n\n"
		"We understand that you may be unaware that the product you \n"
		"attempted to purchase is infringing. Accordingly, if you have \n"
		"any questions regarding infringing video game products please \n"
		"see the piracy section of our FAQ page at:\n\n"
		"	<http://pages.ebay.com/help/help-faq.html> \n\n"
		"or the piracy section of the Interactive Digital Software \n"
		"Association's (\"IDSA\") web site at www.idsa.com. \n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",

		"Ended Auction Notification - Infringing Items\n",
		
		"Dear %s,\n\n"	
		"We wish to inform you that the following auction(s):\n\n"
		"%d - %s\n"                              
		"has(have) been ended. All sellers and bidders have been\n"
		"warned and each item has been removed completely from the site.\n\n"
		"Regards,\n"
		"%s (%s)\n"
	},


// legal buddies stuff 4/26/99

	{	eNoteTypeItemBlockedUponListing,
		"NOTICE: eBay Auction Screened and Blocked - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemBlockedAfterReview,
		"NOTICE: eBay Auction Blocked after Review - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemFlaggedUponListing,
		"NOTICE: eBay Auction Screened and Flagged - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemFlaggedAddToDescr,
		"NOTICE: eBay Auction New Description Flagged - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemFlaggedUpdateItemInfo,
		"NOTICE: eBay Updated Auction Flagged - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemFlaggedChangeCategory,
		"NOTICE: eBay Auction Category Change Flagged - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemReinstatementAfterReview,
		"NOTICE: eBay Auction Reviewed and Reinstated - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSellerFlaggedForBlockedItems,
		"NOTICE: eBay Auction Seller Has Blocked Item(s)",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeSellerFlagClearedForBlockedItems,
		"NOTICE: eBay Auction Seller Reprieve for Blocked Items",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeFlaggedSellerListNewItem,
		"NOTICE: eBay Auction Seller Listed New Item - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeFlaggedSellerAddToItemDescription,
		"NOTICE: eBay Auction Seller Added to Description - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeFlaggedSellerUpdateItemInfo,
		"NOTICE: eBay Auction Seller Updated Item Info - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeFlaggedSellerChangeCategory,
		"NOTICE: eBay Auction Seller Changed Item Category - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemAddToDescrDenied,
		"NOTICE: eBay Auction New Description Denied - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemUpdateItemInfoDenied,
		"NOTICE: eBay Auction Updated Info Denied - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemChangeCategoryDenied,
		"NOTICE: eBay Auction Category Change Denied - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeItemBlockedAppealDenied,
		"NOTICE: eBay Blocked Auction Appeal Denied - %s",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},


	{	eNoteTypeAuctionEndAttemptForGoodSeller,
		"Power Seller Ended Auction",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	},

	//
	// Last entry -- all others go above this one!!!
	//
	{	0,
		"eBay Support",
		"Dear %s (%s)\n\n"
		"Regards ,\n\n"
		"%s (%s)\n%s\n\n",
		NULL,
		NULL,
		NULL,
		NULL,
	}
};

