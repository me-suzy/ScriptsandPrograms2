/*	$Id: clseBayAppUser.cpp,v 1.8 1999/05/19 02:34:31 josh Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				This app is all things user. This
//				file contains the methods that are
//				"common" to all uses of this app.
//
//	Modifications:
//				- 05/01/97 michael	- Created
//				- 11/12/97 poon		- moved sorting stuff to kernel\clseBayItems.cpp
//
#include "ebihdr.h"

// Error Messages
static const char *ErrorMsgInvalidPhoneCharacter = 
"Sorry, the phone number you entered contained a character "
"other than a valid digit (0 - 9), \'-\', \'(\', or \')\'. "
"Please go back and try again.";

static const char *ErrorMsgInvalidPhoneLength = 
"Sorry, the phone number you entered is less than 10 digits. "
"Be sure to include area code. Include your country code if you are "
"entering a non-US phone number. "
"<p>Please go back and try again.";

static const char *ErrorMsgInvalidPhoneFormat1 =
"The phone number you entered appears to be of the format "
"\'9999999999\', yet a dash (\'-\'), left parenthesis (\'(\'), "
"or right parenthesis was found. Please go back and try "
"again.";

static const char *ErrorMsgInvalidPhoneFormat2 =
"The phone number you entered appears to be of the format "
"\'(999)9999999\' or \'999-999-9999\', but the characters "
"\'-\', \'(\', and \')\' were not found where they were expected. "
"Please go back and try again.";

static const char *ErrorMsgInvalidPhoneFormat3 = 
"The phone number you entered appears to be of the format "
"\'(999)999-9999\', but the characters "
"\'-\', \'(\', and \')\' were not found where they were expected. "
"Please go back and try again.";

static const char *ErrorMsgInvalidPhoneFormat4 = 
"The phone number you entered is not in a recognizable format. "
"Phone numbers in the United States and Canada should be of the "
"format \'(aaa)nnn-nnnn\', where \'aaa\' is the area code. "
"Please go back and try again.";


// Drop-Down Selection Lists
const DropDownSelection StateSelection[] =
{	
	{	"AL",					"Alabama"				},
	{	"AK",					"Alaska"				},
	{	"AZ",					"Arizona"				},
	{	"AR",					"Arkansas"				},
	{	"CA",					"California"			},
	{	"CO",					"Colorado"				},
	{	"CT",					"Connecticut"			},
	{	"DE",					"Delaware"				},
	{	"DC",					"District of Columbia"	},
	{	"FL",					"Florida"				},
	{	"GA",					"Georgia"				},
	{	"HI",					"Hawaii"				},
	{	"ID",					"Idaho"					},
	{	"IL",					"Illinois"				},
	{	"IN",					"Indiana"				},
	{	"IA",					"Iowa"					},
	{	"KS",					"Kansas"				},
	{	"KY",					"Kentucky"				},
	{	"LA",					"Louisiana"				},
	{	"ME",					"Maine"					},
	{	"MD",					"Maryland"				},
	{	"MA",					"Massachusetts"			},
	{	"MI",					"Michigan"				},
	{	"MN",					"Minnesota"				},
	{	"MS",					"Mississippi"			},
	{	"MO",					"Missouri"				},
	{	"MT",					"Montana"				},
	{	"NE",					"Nebraska"				},
	{	"NV",					"Nevada"				},
	{	"NH",					"New Hampshire"			},
	{	"NJ",					"New Jersey"			},
	{	"NM",					"New Mexico"			},
	{	"NY",					"New York"				},
	{	"NC",					"North Carolina"		},
	{	"ND",					"North Dakota"			},
	{	"OH",					"Ohio"					},
	{	"OK",					"Oklahoma"				},
	{	"OR",					"Oregon"				},
	{	"PA",					"Pennsylvania"			},
	{	"RI"			,		"Rhode Island"			},
	{	"SC",					"South Carolina"		},
	{	"SD",					"South Dakota"			},
	{	"TN",					"Tennessee"				},
	{	"TX",					"Texas"					},
	{	"UT",					"Utah"					},
	{	"VT",					"Vermont"				},
	{	"VA",					"Virginia"				},
	{	"WA",					"Washington"			},
	{	"WV",					"West Virginia"			},
	{	"WI",					"Wisconsin"				},
	{	"WY",					"Wyoming"				},
	{	"Alberta",				"Alberta"				},
	{	"British Columbia",		"British Columbia"		},
	{	"Manitoba",				"Manitoba"				},
	{	"New Brunswick",		"New Brunswick"			},
	{	"Nova Scotia",			"Nova Scotia"			},
	{	"Newfoundland",			"Newfoundland"			},
	{	"N.W.T.",				"N.W.T."				},
	{	"Ontario",				"Ontario"				},
	{	"Quebec",				"Quebec"				},
	{	"Prince Edward Island",	"Prince Edward Island"	},
	{	"Saskatchewan",			"Saskatchewan"			},
	{	"Yukon",				"Yukon"					},
	{	"PR",					"Puerto Rico"			},
	{	"VI",					"Virgin Island"			},
	{	"MP",					"Northern Mariana Islands"	},
	{	"GU",					"Guam"					},
	{	"AS",					"American Samoa"		},
	{	"PW",					"Palau"					},
	{	NULL,					NULL					}
};

const DropDownSelection CountrySelection[] =
{		
	{	"argentina",			"Argentina"			},
	{	"australia",			"Australia"			},
	{	"austria",				"Austria"			},
	{	"belgium",				"Belgium"			},
	{	"brazil",				"Brazil"			},
	{	"canada",				"Canada"			},
	{	"caribbean",			"Caribbean"			},
	{	"chile",				"Chile"				},
	{	"china",				"China"				},
	{	"colombia",				"Colombia"			},
	{	"czech",				"Czech Republic"	},
	{	"denmark",				"Denmark"			},
	{	"europe",				"Europe"			},
	{	"finland",				"Finland"			},
	{	"france",				"France"			},
	{	"germany",				"Germany"			},
	{	"hong kong",			"Hong Kong"			},
	{	"hungary",				"Hungary"			},
	{	"india",				"India"				},
	{	"ireland",				"Ireland"			},
	{	"israel",				"Israel"			},
	{	"italy",				"Italy"				},
	{	"japan",				"Japan"				},
	{	"korea",				"Korea"				},
	{	"latinamerica",			"Latin America"		},
	{	"luxemburg",			"Luxemburg"			},
	{	"malaysia",				"Malaysia",			},
	{	"mexico",				"Mexico"			},
	{	"middleeast",			"Middle East"		},
	{	"netherlands",			"Netherlands"		},
	{	"newzealand",			"New Zealand"		},
	{	"northafrica",			"North Africa"		},
	{	"norway",				"Norway"			},
	{	"peru",					"Peru"				},
	{	"poland",				"Poland"			},
	{	"portugal",				"Portugal"			},
	{	"russia",				"Russia"			},
	{	"singapore",			"Singapore",		},
	{	"slovakia",				"Slovakia"			},
	{	"slovenija",			"Slovenija"			},
	{	"southafrica",			"South Africa"		},
	{	"spain",				"Spain"				},
	{	"sweden",				"Sweden"			},
	{	"switzerland",			"Switzerland"		},
	{	"taiwan",				"Taiwan"			},
	{	"thailand",				"Thailand"			},
	{	"turkey",				"Turkey"			},
	{	"uk",					"United Kingdom"	},
	{	"usa",					"United States"		},
	{	"uruguay",				"Uruguay"			},
	{	"venezuela",			"Venezuela"			},
	{	NULL,					NULL				}
};

const ScrollingSelection FullCountrySelection[] =
{
	{	1,				"United States"						},
	{	2,				"Canada"							},
	{	3,				"United Kingdom"					},
	{	4,				"Afghanistan"						},
	{	5,				"Albania"							},
	{	6,				"Algeria"							},
	{   7,              "American Samoa"                    },
	{	8,				"Andorra"							},
	{	9,				"Angola"							},
	{   10,             "Anguilla"                          },
	{   11,             "Antigua and Barbuda"               },
	{	12,				"Argentina"							},
	{	13,				"Armenia"							},
	{	14,				"Aruba"						  	    },
	{	15,				"Australia"							},
	{	16,				"Austria"							},
	{	17,				"Azerbaijan Republic"				},
	{	18,				"Bahamas"							},
	{	19,				"Bahrain"							},
	{	20,				"Bangladesh"						},
	{	21,				"Barbados"							},
    {	22,				"Belarus"						    },
	{	23,				"Belgium"							},
	{	24,				"Belize"							},
	{	25,				"Benin"								},
	{	26,				"Bermuda"							},
	{	27,				"Bhutan"							},
	{	28,				"Bolivia"							},
	{	29,				"Bosnia and Herzegovina"			},
	{	30,				"Botswana"							},
	{	31,				"Brazil"						    },
	{	32,				"British Virgin Islands"			},
	{	33,				"Brunei Darussalam"					},
	{	34,				"Bulgaria"							},
	{	35,				"Burkina Faso"						},
	{   36,             "Burma"                             },
	{	37,				"Burundi"							},
	{   38,             "Cambodia"                          },
	{	39,				"Cameroon"							},
	{	40,				"Cape Verde Islands"				},
	{	41,				"Cayman Islands"					},
	{	42,				"Central African Republic"			},
	{	43,				"Chad"								},
	{	44,				"Chile"								},
	{	45,				"China"								},
	{	46,				"Colombia"							},
	{   47,             "Comoros"                           },
	{	48,				"Congo, Democratic Republic of the" },
	{   49,             "Congo, Republic of the"            },
	{	50,				"Cook Islands"						},
	{	51,				"Costa Rica"						},
	{   52,             "Cote d'Ivoire"                     },
	{	53,				"Croatia"							},
	{	54,				"Cuba"								},
	{	55,				"Cyprus"							},
	{	56,				"Czech Republic"					},
	{	57,				"Denmark"							},
	{	58,				"Djibouti"							},
	{	59,				"Dominica"							},
	{	60,				"Dominican Republic"				},
	{	61,				"Ecuador"							},
	{	62,				"Egypt"								},
	{	63,				"El Salvador"						},
	{	64,				"Equatorial Guinea"					},
	{   65,             "Eritrea"                           },
	{	66,				"Estonia"							},
	{	67,				"Ethiopia"							},
	{   68,             "Falkland Islands (Islas Makvinas)" },
	{	69,				"Fiji"								},
	{	70,				"Finland"							},
	{	71,				"France"							},
	{	72,				"French Guiana"						},
	{	73,				"French Polynesia"					},
	{	74,				"Gabon Republic"					},
	{	75,				"Gambia"							},
	{	76,				"Georgia"							},
	{	77,				"Germany"							},
	{	78,				"Ghana"								},
	{	79,				"Gibraltar"							},
	{	80,				"Greece"							},
	{   81,             "Greenland"                         },
	{	82,				"Grenada"							},
	{   83,             "Guadeloupe"                        },
	{	84,				"Guam"								},
	{	85,				"Guatemala"							},
	{   86,             "Guernsey"                          },
	{	87,				"Guinea"							},
	{	88,				"Guinea-Bissau"						},
	{	89,				"Guyana"							},
	{	90,				"Haiti"								},
	{	91,				"Honduras"							},
	{	92,				"Hong Kong"							},
	{	93,				"Hungary"							},
	{	94,				"Iceland"							},
	{	95,				"India"								},
	{	96,				"Indonesia"							},
	{	97,				"Iran"								},
	{	98,				"Iraq"								},
	{	99,				"Ireland"							},
	{	100,			"Israel"							},
	{	101,			"Italy"								},
	{	102,			"Jamaica"							},
	{   103,            "Jan Mayen"                         },
	{	104,			"Japan"								},
	{   105,            "Jersey"                            },
	{	106,			"Jordan"							},
	{	107,			"Kazakhstan"						},
	{	108,			"Kenya"								},
	{	109,			"Kiribati"							},
	{	110,			"Korea, North"						},
	{	111,			"Korea, South"						},
	{	112,			"Kuwait"							},
	{	113,			"Kyrgyzstan"						},
	{   114,            "Laos"                              },
	{	115,			"Latvia"							},
	{	116,			"Lebanon"							},
	{	117,			"Lesotho"							},
	{	118,			"Liberia"							},
	{	119,			"Libya"								},
	{	120,			"Liechtenstein"						},
	{	121,			"Lithuania"							},
	{	122,			"Luxembourg"						},
	{	123,			"Macau"								},
	{	124,			"Macedonia"							},
	{	125,			"Madagascar"						},
	{	126,			"Malawi"							},
	{	127,			"Malaysia"							},
	{	128,			"Maldives"							},
	{	129,			"Mali"								},
	{	130,			"Malta"								},
	{   131,            "Marshall Islands"                  },
	{   132,            "Martinique"                        },
	{	133,			"Mauritania"						},
	{	134,			"Mauritius"							},
	{   135,            "Mayotte"                           },
	{	136,			"Mexico"							},
	{	137,			"Moldova"							},
	{	138,			"Monaco"							},
	{	139,			"Mongolia"							},
	{	140,			"Montserrat"						},
	{	141,			"Morocco"							},
	{	142,			"Mozambique"						},
	{	143,			"Namibia"							},
	{	144,			"Nauru"								},
	{	145,			"Nepal"								},
	{	146,			"Netherlands"						},
	{	147,			"Netherlands Antilles"				},
	{	148,			"New Caledonia"						},
	{	149,			"New Zealand"						},
	{	150,			"Nicaragua"							},
	{	151,			"Niger"								},
	{	152,			"Nigeria"							},
	{   153,            "Niue"                              },
	{	154,			"Norway"							},
	{	155,			"Oman"								},
	{	156,			"Pakistan"							},
	{   157,            "Palau"                             },
	{	158,			"Panama"							},
	{	159,			"Papua New Guinea"					},
	{	160,			"Paraguay"							},
	{	161,			"Peru"								},
	{	162,			"Philippines"						},
	{	163,			"Poland"							},
	{	164,			"Portugal"							},
	{	165,			"Puerto Rico"						},
	{	166,			"Qatar"								},
	{	167,			"Romania"							},
	{	168,			"Russian Federation"				},
	{	169,			"Rwanda"							},
	{   170,            "Saint Helena"                      },
	{	171,			"Saint Kitts-Nevis"					},
	{	172,			"Saint Lucia"						},
	{	173,			"Saint Pierre and Miquelon"			},
	{	174,			"Saint Vincent and the Grenadines"	},
	{	175,			"San Marino"						},
	{	176,			"Saudi Arabia"						},
	{	177,			"Senegal"							},
	{	178,			"Seychelles"						},
	{	179,			"Sierra Leone"						},
	{	180,			"Singapore"							},
	{	181,			"Slovakia"							},
	{	182,			"Slovenia"							},
	{	183,			"Solomon Islands"					},
	{	184,			"Somalia"							},
	{	185,			"South Africa"						},
	{	186,			"Spain"								},
	{	187,			"Sri Lanka"							},
	{	188,			"Sudan"								},
	{	189,			"Suriname"							},
	{   190,            "Svalbard"                          },
	{	191,			"Swaziland"							},
	{	192,			"Sweden"							},
	{	193,			"Switzerland"						},
	{	194,			"Syria"								},
	{	195,			"Tahiti"							},
	{	196,			"Taiwan"							},
	{	197,			"Tajikistan"						},
	{	198,			"Tanzania"							},
	{	199,			"Thailand"							},
	{	200,			"Togo"								},
	{	201,			"Tonga"								},
	{	202,			"Trinidad and Tobago"				},
	{	203,			"Tunisia"							},
	{	204,			"Turkey"							},
	{	205,			"Turkmenistan"						},
	{	206,			"Turks and Caicos Islands"			},
	{   207,            "Tuvalu"                            },
	{	208,			"Uganda"							},
	{	209,			"Ukraine"							},
	{	210,			"United Arab Emirates"				},
	{	3,				"United Kingdom"					},
	{	1,				"United States"						},
	{	211,			"Uruguay"							},
	{   212,            "Uzbekistan"                        },
	{	213,			"Vanuatu"							},
	{	214,			"Vatican City State"				},
	{	215,			"Venezuela"							},
	{	216,			"Vietnam"							},
	{	217,			"Virgin Islands (U.S.)"				},
	{   218,            "Wallis and Futuna"                 },
	{   219,            "Western Sahara"                    },
	{	220,			"Western Samoa"						},
	{	221,			"Yemen"								},
	{	222,			"Yugoslavia"						},
	{	223,			"Zambia"							},
	{	224,			"Zimbabwe"							},
	{	-1,				NULL								}
};




const DropDownSelection GenderSelection[] =
{		
	{	"m",	"Male"		},
	{	"f",	"Female"	},
	{	NULL,	NULL		}
};

const DropDownSelection QueryEmailSubject[] =
{
	{	"user information",			"User Information"			},
	{	"selling",					"Selling"					},
	{	"bidding",					"Bidding"					},
	{	"search",					"Search - Finding Items"	},
	{	"my ebay",					"My eBay"					},
	{	"after Auction",			"After the Auction"			},
	{	"feedback",					"Feedback"					},
	{	"trust and Safety",			"Trust and Safety"			},
	{	"policies",					"Policies and Guidelines"	},
	{	"billing",					"Billing"					},
	{	"international",			"International Trading"		},
	{	"system technical issue",	"System Technical Issue"	},
	{	"complaint",				"Complaint"					},
	{	"other",					"Other"						},
	{	NULL,						NULL						}
};

const DropDownSelection GiftIconSelection[] =
{
//	{	"2",	"Rosie Icon"	}, //skip rosie, because we do not want to display it
	{	"3",	"Anniversary"	},
	{	"4",	"Baby"			},
	{	"5",	"Birthday"		},
	{	"6",	"Christmas"		},	
	{	"7",	"Easter"		},
	{	"1",	"Father"		},
	{	"8",	"Graduation"	},						
	{	"9",	"Halloween"		},	
	{	"10",	"Hanukkah"		},			
	{	"11",	"July 4th"		},										
	{	"12",	"Mother"		},												
	{	"13",	"St. Patrick"	},								
	{	"14",	"Thanksgiving"	},									
	{	"15",	"Valentine"		},
	{	"16",	"Wedding"		},	
	{	NULL,	NULL			}

};


const DropDownSelection ItemTypeSelection[] =
{
	{	"computer software",		"Computer Software"			},
	{	"video games",				"Video Games"					},
	{	"erotica",					"Erotica"					},
	{	"badges",					"Badges"	},
	{	"movie",					"Movies"					},
	{	"music",					"Music"			},
	{	"Ddrugs and paraphernalia",					"Drugs and Paraphernalia"					},
	{	"wine and alcohol",			"Wine and Alcohol"			},
	{	"other",					"Other"						},
	{	NULL,						NULL						}
};

//
// A Nice common little routine to Emit a Drop-Down List
//
bool EmitDropDownList(ostream *pStream,
					  char *pListName,
					  DropDownSelection *pSelectionList,
					  char *pSelectedValue,
					  char *pUnSelectedValue,
					  char *pUnSelectedLabel)
{
	DropDownSelection	*pCurrentSelection;
	bool				foundIt	= false;
	
	// Emit the first part
	*pStream	<<	"<SELECT NAME=\""
				<<	pListName
				<<	"\">";

// If we didn't find it, emit "Not Selected" as
	// the default
	*pStream <<	"<OPTION ";

	if  (pSelectedValue == NULL)
		*pStream <<	"SELECTED ";

	*pStream <<	"VALUE=\""
			 <<	pUnSelectedValue
			 <<	"\">"
			 <<	pUnSelectedLabel
			 <<	"</OPTION>\n";

	// Now, emit the items
	pCurrentSelection	= pSelectionList;
	do
	{
		if (pCurrentSelection->pValue == NULL)
			break;

		*pStream <<	"<OPTION ";
		
		if (pSelectedValue != NULL &&
			stricmp(pCurrentSelection->pValue,
				   pSelectedValue) == 0)
		{
			*pStream <<	"SELECTED ";
			foundIt	= true;
		}
				
		*pStream <<	"VALUE=\""
				 <<	pCurrentSelection->pValue
				 <<	"\">"
				 <<	pCurrentSelection->pLabel
				 << "</OPTION>\n";

		pCurrentSelection++;

	} while ( 1 == 1 );

	*pStream <<	"</SELECT>";

	return foundIt;
}

bool EmitScrollingList(ostream *pStream,
					  char *pListName,
					  int   numInView,
					  ScrollingSelection *pSelectionList,
					  int   selectedValue, 
					  int defaultValue,
					  const char* pDefaultEntry)
{
	// Emit the first part
	*pStream	<<	"<SELECT NAME=\""
				<<	pListName
				<<	"\" SIZE="
				<<  numInView
				<<  ">";

	*pStream <<	"<OPTION ";

	// If there is no selected value, then 
	// don't select the first entry.
	if  (defaultValue == -1)
		*pStream <<	"SELECTED ";

	*pStream <<	"VALUE=\""
			 <<	defaultValue
			 <<	"\">"
			 <<	pDefaultEntry
			 <<	"</OPTION>\n";

	return EmitScrollingList(pStream, pListName, numInView, pSelectionList, selectedValue, false);
}

bool EmitScrollingList(ostream *pStream,
					  char *pListName,
					  int   numInView,
					  ScrollingSelection *pSelectionList,
					  int   selectedValue,
					  bool  emitName)
{
	ScrollingSelection	*pCurrentSelection;
	bool			     foundIt = false;

	if (emitName)
	{
		*pStream	<<	"<SELECT NAME=\""
					<<	pListName
					<<	"\" SIZE="
					<<  numInView					
					<<  ">";
	}

	// Now, emit the items
	pCurrentSelection	= pSelectionList;
	do
	{
		if (pCurrentSelection->value == -1)
			break;

		*pStream <<	"<OPTION ";
		
		if (!foundIt && selectedValue == pCurrentSelection->value) 
		{
			*pStream <<	"SELECTED ";
			foundIt = true;
		}
				
		*pStream <<	"VALUE=\""
				 <<	pCurrentSelection->value
				 <<	"\">"
				 <<	pCurrentSelection->pLabel
				 << "</OPTION>\n";

		pCurrentSelection++;

	} while ( 1 == 1 );

	*pStream <<	"</SELECT>";

	return foundIt;
}

//
// Validate a phone number
//
bool clseBayApp::ValidatePhone(char *pPhone, 
							   bool international,
							   char *pWhichPhone,
							   ostream *pStream)
{
	char	phoneWork[EBAY_MAX_PHONE_SIZE + 1];
	char	*i, *ii;
	bool	error	= false;
	bool	dash	= false;
	bool	lParen	= false;
	bool	rParen	= false;
	int		digit_len = 0;

	// Copy the phone to a workarea
	memset(phoneWork, 0x00, sizeof(phoneWork));

	ii	= phoneWork;
	for (i = pPhone;
		 *i != '\0';
		 i++)
	{
		if (*i != ' ')
		{
			*ii	= *i;
			ii++;
		}
	}

	// Now, fake ourselves out!
	pPhone	= phoneWork;


	// First, let's look for invalid characters
	// (other than digits, '-', '(', or ')'
	for (i = pPhone;
		 *i != '\0';
		 i++)
	{
		if (IseBayDigit(*i))
			digit_len++;
	}

	if (digit_len < 10)
	{
		*mpStream <<	"<h2>Error in "
					<<	pWhichPhone
					<<	"</h2>"
					<<	ErrorMsgInvalidPhoneLength
					<<	"<br>";
		error = true;
	}

	return !error; 
}




