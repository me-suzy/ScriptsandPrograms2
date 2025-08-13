/*	$Id: clsIntlLocale.cpp,v 1.1.6.4 1999/08/04 05:26:49 phofer Exp $	*/
//
//	File:	clsIntlLocale.cpp
//
//	Class:	clsIntlLocale
//
//	Author:	Petra Hofer		(petra@ebay.com)
//
//	Function:
//
//				Representation of a locale
//
//	Modifications:
//				- 07/02/99 petra	- created
//

#include "eBayKernel.h"

//
// Default Constructors
//
clsIntlLocale::clsIntlLocale(int localeId, int timezoneId)
{
	mLocaleId			= localeId;
	mTimeZoneId			= timezoneId;

	gApp->GetDatabase()->GetLocaleInfo(mLocaleId, mTimeZoneId, this);

	mIsSummerTime = false;
	mTimeLastFormatted = 0;
}

//
// Destructor
//
clsIntlLocale::~clsIntlLocale()
{

}

//
// used by the DB read method to fill in all fields
// figures out the two time stamps between which summer time is in effect for 
// this locale, if applicable, for later use
//
void clsIntlLocale::SetFromDB (int countryId,
							char * pNameStandard,	// intl_char
							char * pNameSummer,		// intl_char
							double timeZoneOffset,
							bool observesSummerTime, 
							bool summerTimeBeginsFirst,
							int summerTimeBeginsMonth,
							bool summerTimeEndsFirst,
							int summerTimeEndsMonth,
							char * dateFormatShort,
							char * dateFormatMedium,
							char * dateFormatLong,
							char * timeFormatShort,
							char * timeFormatMedium,
							char * timeFormatLong,
							char * positiveAmountFormat,
							char * negativeAmountFormat,
							char * decimalSymbol,
							char * groupingSymbol,
							int digitsAfterDecimal,
							int digitsInGroup,
							char * negativeNumberFormat,
							int defaultCurrencyId)
{
	mCountryId		= countryId;
	strcpy (mNameStandard,	pNameStandard);
	strcpy (mNameSummer,	pNameSummer);
	mTimeZoneOffset = timeZoneOffset;
	mObservesSummerTime	= observesSummerTime;
	if (mObservesSummerTime)
	{
		mSummerTimeStarts = MakeSummerTimeStamp (summerTimeBeginsFirst, summerTimeBeginsMonth);
		mSummerTimeEnds = MakeSummerTimeStamp (summerTimeEndsFirst, summerTimeEndsMonth);
	}
	strcpy (mDateFormatShort,	dateFormatShort);
	strcpy (mDateFormatMedium,	dateFormatMedium);
	strcpy (mDateFormatLong,	dateFormatLong);
	strcpy (mTimeFormatShort,	timeFormatShort);
	strcpy (mTimeFormatMedium,	timeFormatMedium);
	strcpy (mTimeFormatLong,	timeFormatLong);
	strcpy (mPositiveAmountFormat,	positiveAmountFormat);
	strcpy (mNegativeAmountFormat,	negativeAmountFormat);
	strncpy (&mDecimalSymbol, decimalSymbol, 1);
	strncpy (&mGroupingSymbol, groupingSymbol, 1);
	mDigitsAfterDecimal = digitsAfterDecimal;
	mDigitsInGroup		= digitsInGroup;
	strcpy (mNegativeNumberFormat,	negativeNumberFormat);
	mDefaultCurrencyId = defaultCurrencyId;

	return;
}

//
// Our server runs in local time. So we get that and pass it on to the 'other'
// GetTimeFormatted method.
//

char * clsIntlLocale::GetTimeFormatted(char * timeBuffer, int formatLength /* =0 */)
{
	time_t	tStamp;

	// current time
	time(&tStamp);						
	return GetTimeFormatted (timeBuffer, tStamp, formatLength);

}

//
// Unfortunately, all times we get here are NOT in GMT (they are in Pacific
// something time, where something is either standard or daylight saving - or 
// at least they better be in Pacific time, because that's what they get treated
// as, since our server runs in that time zone..)
// That means we have to first revert to GMT, then go to actual time for locale
// If we ever run distributed servers in different time zones, we should 
// seriously consider storing all times in GMT in the database... just an idea...
//

char * clsIntlLocale::GetTimeFormatted(char * timeBuffer, time_t timeToFormat, int formatLength /* =0 */)
{
	struct tm tStruct;
	char * formatString;

	// format it nicely
	switch (formatLength)
	{
	case 1:
		formatString = &mTimeFormatMedium[0];
		break;
	case 2:
		formatString = &mTimeFormatLong[0];
		break;
	case 0:
	default:
		formatString = &mTimeFormatShort[0];
		break;
	}

	// often, we get date and time to format immediately following each other
	// so we store the last formatted time to save computing time - or that's
	// the theory
	if (mTimeLastFormatted == timeToFormat)
		tStruct = mTimeLastFormattedStruct;
	else
	{
		tStruct = *AdjustTime (timeToFormat, &tStruct);
		mTimeLastFormatted = timeToFormat;
		mTimeLastFormattedStruct = tStruct;
	}

	return FormatTime (timeBuffer, &tStruct, formatString);
}

// 
// return the name of the time zone for the current time of the locale
//

char * clsIntlLocale::GetTimeZone()
{
	time_t	tStamp;
	struct tm tStruct;
	char * result;

	time(&tStamp);						// current time
	
	tStruct = *gmtime (&tStamp);		// convert from local (Pacific time) to UTC

	tStruct.tm_sec += mTimeZoneOffset;	// correct for timezone of this locale
	tStamp = mktime (&tStruct);			// normalize

	if (mObservesSummerTime &&			// if country observes summer time
		(mSummerTimeStarts <= tStamp &&	// and it's currently in effect
		 mSummerTimeEnds > tStamp)) 
		result = mNameSummer;			// return summer time name
	else								// otherwise it's standard
		result = mNameStandard;

	return result;		
}

//
// Our server runs in local time. So we get that and pass it on to the 'other'
// GetDateFormatted method.
//

char * clsIntlLocale::GetDateFormatted (char * dateBuffer, int formatLength)
{
	time_t	tStamp;

	// current time
	time(&tStamp);						
	return GetDateFormatted (dateBuffer, tStamp, formatLength);
}

//
// Format a passed in time stamp. See GetTimeFormatted for comments..
//

char * clsIntlLocale::GetDateFormatted (char * dateBuffer, time_t dateToFormat, int formatLength)
{
	struct tm tStruct;
	char * formatString;

	// format it nicely
	switch (formatLength)
	{
	case 1:
		formatString = &mDateFormatMedium[0];
		break;
	case 2:
		formatString = &mDateFormatLong[0];
		break;
	case 0:
	default:
		formatString = &mDateFormatShort[0];
		break;
	}

	// in case we format date and time immediately following each other..
	if (mTimeLastFormatted == dateToFormat)
		tStruct = mTimeLastFormattedStruct;
	else
	{
		tStruct = *AdjustTime (dateToFormat, &tStruct);
		mTimeLastFormatted = dateToFormat;
		mTimeLastFormattedStruct = tStruct;
	}


	return FormatDate (dateBuffer, &tStruct, formatString);
}

//
// format a curreny amount. Currency symbol is taken from member variable (which in
// turn is initialized from the default currency stored with the country that
// the locale is for)
//
char * clsIntlLocale::GetCurrencyAmountFormatted (char * amountBuffer, double amount)
{
	return GetCurrencyAmountFormatted (amountBuffer, amount, mDefaultCurrencyId);
}

// 
// format a currency amount. Currency symbol is taken from parameter
//
//
char * clsIntlLocale::GetCurrencyAmountFormatted (char * amountBuffer, double amount, int currencyId)
{
	char *	pFormat;

	// our formatting template
	if (amount < 0) 
		pFormat = &mNegativeAmountFormat[0];
	else
		pFormat = &mPositiveAmountFormat[0];

	return FormatNumeric (amountBuffer, amount, mDigitsAfterDecimal, pFormat, currencyId);
}

//
// an amount can be entered by a user with decimal and/or grouping
// symbols, but the run-time library function to convert char to num
// (usually atof() is used) can only deal with a string that contains 
// a decimal point, numbers, and possibly a minus sign. this function 
// converts the input string so that it conforms to these rules.
// This is NOT an input validating routine! All it does is convert an
// input string that is correct for a certain locale into an input
// string that can be used by atof!
//
char * clsIntlLocale::GetNormalizedCurrencyAmount (char * amountBuffer)
{
	char * pChar;
	int before, after;

	// change to decimal point
	if (mDecimalSymbol != '.') 
	{
		if ((pChar = strchr (amountBuffer, mDecimalSymbol)) != NULL)
           *pChar = '.';
	}

	// get rid of all grouping symbols
	if (strchr (amountBuffer, mGroupingSymbol) != NULL)
	{
		for (before = 0, after = 0; before < strlen(amountBuffer); before++)
		{
			if (amountBuffer[before] == mGroupingSymbol)
			{
				after = before;
				continue;
			}
			else
			{
				if (after > 0)
					amountBuffer[after++] = amountBuffer[before];
			}
		}
		amountBuffer[after] = '\0';
	}


	return amountBuffer;
}

//
// same as GetCurrencyAmountFormatted, only w/o the currency symbol
//

// oh well.. MS VC++ 5 doesn't build this
// template <class DataType> char * clsIntlLocale::GetNumberFormatted (char * numberBuffer, DataType number, int digitsAfterDecimal)
// so I'll have to resort to more conventional means.. sigh..
char * clsIntlLocale::GetNumberFormatted (char * numberBuffer, int number, int digitsAfterDecimal)
{
	char	positiveNumberFormat[] = "n";		// cheating - all we want is the number
	char *	pFormat;

	// our formatting template
	if (number < 0) 
		pFormat = &mNegativeNumberFormat[0];
	else
		pFormat = &positiveNumberFormat[0];

	return FormatNumeric (numberBuffer, (double)number, digitsAfterDecimal, pFormat, 0);
}
// and this, since we don't have templates
char * clsIntlLocale::GetNumberFormatted (char * numberBuffer, double number, int digitsAfterDecimal)
{
	char	positiveNumberFormat[] = "n";		// cheating - all we want is the number
	char *	pFormat;

	// our formatting template
	if (number < 0) 
		pFormat = &mNegativeNumberFormat[0];
	else
		pFormat = &positiveNumberFormat[0];

	return FormatNumeric (numberBuffer, number, digitsAfterDecimal, pFormat, 0);
}


// to figure out the timestamps between which daylight saving time is
// in effect, we use this.
// we're looking for a timestamp that represents either the first or
// last sunday (param summerTimeFirstSunday) in a given month (param
// summerTimeMonth) for the current year.
//

time_t clsIntlLocale::MakeSummerTimeStamp (bool summerTimeFirstSunday,
											int summerTimeMonth)
{
	time_t tStamp;
	struct tm tStruct;

	time(&tStamp);									// get current time
	tStruct = *gmtime (&tStamp);					// convert it to a structure in UTC
	tStruct.tm_sec += mTimeZoneOffset;				// correct for time zone

	tStamp = mktime (&tStruct);						// bounce back and
	tStruct = *localtime(&tStamp);					// forth to normalize

	tStruct.tm_mday = 1;							// we set the day to the 1st
	if (summerTimeFirstSunday)						// and the month to the month
		tStruct.tm_mon = summerTimeMonth - 1;		// in question if we're looking for
	else											// the first sunday, otherwise to
		tStruct.tm_mon = summerTimeMonth;			// one month later for the last sunday

	tStruct.tm_hour = 1;							// usually the switch happens at 2am
	tStruct.tm_min	= 59;
	tStruct.tm_sec	= 59;
	tStruct.tm_isdst = 0;							// not sure if this actually does anything..
	tStamp = mktime (&tStruct);						// then we convert back to a timestamp
	tStruct = *localtime (&tStamp);					// and back again, so we have a struct
													// so now we should have the day of week
	if (summerTimeFirstSunday)						// for the 1st day of the required month
	{												// in the current year
		if (tStruct.tm_wday != 0)					// if we're looking for the 1st sunday
			tStruct.tm_mday += (7 - tStruct.tm_wday); // in a month, all we do is go forward
	}												// if necessary	
	else											// if we're looking for the last sunday
	{												// we go back to the next closest sunday
		tStruct.tm_mday -= tStruct.tm_wday;			// in the last month
	}
	tStamp = mktime (&tStruct);						// we're all set - make a timestamp

	return tStamp;
}

// 
// do the actual time formatting - some trivial parsing, and we're done
//

char * clsIntlLocale::FormatTime (char * timeBuffer, struct tm * timeStruct, char * formatString)
{
	int input, output;
	output = 0;
	char temp[3];


	for (input = 0; input < strlen (formatString); input++)
	{
		switch (formatString[input])
		{
		// hours (in 24)
		case 'h':
			sprintf (temp, "%02d", timeStruct->tm_hour);
			strncpy (&timeBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// minutes
		case 'm':
			sprintf (temp, "%02d", timeStruct->tm_min);
			strncpy (&timeBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// seconds
		case 's':
			sprintf (temp, "%02d", timeStruct->tm_sec);
			strncpy (&timeBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// time zone abbreviation
		case 'z':
			if (mIsSummerTime)
			{
				strncpy (&timeBuffer[output], mNameSummer, strlen(mNameSummer) );
				output += strlen (mNameSummer);
			}
			else
			{
				strncpy (&timeBuffer[output], mNameStandard, strlen(mNameStandard) );
				output += strlen (mNameStandard);
			}
			break;
		// anything else - simply copy
		default:
			timeBuffer[output++] = formatString[input];
			break;
		}
	}
	timeBuffer[output] = '\0';

	return timeBuffer;
}

// 
// do the actual date formatting - some trivial parsing, and we're done
//

char * clsIntlLocale::FormatDate (char * dateBuffer, struct tm * dateStruct, char * formatString)
{
	int input, output;
	output = 0;
	char temp[30];
	int year;

	for (input = 0; input < strlen (formatString); input++)
	{
		switch (formatString[input])
		{
		// stuff between quotes is copied, stripping the quotes themselves
		case '"':
			input ++;
			while (formatString[input] != '"' && input < strlen (formatString))
				dateBuffer[output++] = formatString[input++];
			input ++;
			break;
		// day of month
		case 'd':
			sprintf (temp, "%02d", dateStruct->tm_mday);
			strncpy (&dateBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// month
		case 'm':
			sprintf (temp, "%02d", dateStruct->tm_mon+1);
			strncpy (&dateBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// year (2 digits)
		case 'y':
			if (dateStruct->tm_year >= 100)
				year = dateStruct->tm_year - 100;
			else
				year = dateStruct->tm_year;
			sprintf (temp, "%02d", year);
			strncpy (&dateBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// year (4 digits)
		case 'Y':
			year = dateStruct->tm_year + 1900;
			sprintf (temp, "%04d", year);
			strncpy (&dateBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// day of week
		case 'D':
			switch (dateStruct->tm_wday)
			{
			case 0:
				// switch this
				strcpy (temp, "Sunday");
				// for this
				// temp = clsResource::GetString (ID_SUN);
				break;
			case 1:
				strcpy (temp, "Monday");
				// temp = clsResource::GetString (ID_MON);
				break;
			case 2:
				strcpy (temp, "Tuesday");
				// temp = clsResource::GetString (ID_TUE);
				break;
			case 3:
				strcpy (temp, "Wednesday");
				// temp = clsResource::GetString (ID_WED);
				break;
			case 4:
				strcpy (temp, "Thursday");
				// temp = clsResource::GetString (ID_THU);
				break;
			case 5:
				strcpy (temp, "Friday");
				// temp = clsResource::GetString (ID_FRI);
				break;
			case 6:
				strcpy (temp, "Saturday");
				// temp = clsResource::GetString (ID_SAT);
				break;
			default:	// impossible?
				temp[0] = '\0';
				break;

			}
			strncpy (&dateBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// month name, abbreviated
		case 'M':
			switch (dateStruct->tm_mon)
			{
			case 0:
				// switch this
				strcpy (temp, "Jan");
				// for this
				// temp = clsResource::GetString (ID_JAN);
				break;
			case 1:
				strcpy (temp, "Feb");
				// temp = clsResource::GetString (ID_FEB);
				break;
			case 2:
				strcpy (temp, "Mar");
				// temp = clsResource::GetString (ID_MAR);
				break;
			case 3:
				strcpy (temp, "Apr");
				// temp = clsResource::GetString (ID_APR);
				break;
			case 4:
				strcpy (temp, "May");
				// temp = clsResource::GetString (ID_MAY);
				break;
			case 5:
				strcpy (temp, "Jun");
				// temp = clsResource::GetString (ID_JUN);
				break;
			case 6:
				strcpy (temp, "Jul");
				// temp = clsResource::GetString (ID_JUL);
				break;
			case 7:
				// switch this
				strcpy (temp, "Aug");
				// for this
				// temp = clsResource::GetString (ID_AUG);
				break;
			case 8:
				strcpy (temp, "Sep");
				// temp = clsResource::GetString (ID_SEP);
				break;
			case 9:
				strcpy (temp, "Oct");
				// temp = clsResource::GetString (ID_OCT);
				break;
			case 10:
				strcpy (temp, "Nov");
				// temp = clsResource::GetString (ID_Nov);
				break;
			case 11:
				strcpy (temp, "Dec");
				// temp = clsResource::GetString (ID_DEC);
				break;
			default:	// impossible?
				temp[0] = '\0';
				break;

			}
			strncpy (&dateBuffer[output], temp, strlen(temp) );
			output += strlen(temp);
			break;
		// everything else - simply copy it
		default:
			dateBuffer[output++] = formatString[input];
			break;
		}
	}
	dateBuffer[output] = '\0';

	return dateBuffer;
}

// 
// do the actual number formatting - some slighly more sophisticated parsing, and
// we're done
// depending on the template that's passed in, this may be used for plain numbers
// or currency amounts
//

char * clsIntlLocale::FormatNumeric (char * out, double number, int digitsafter, char * format, int currencyId)
{
	int		count = 0, j = 0, k = 0;	 // counters
	int		traverseFormat	= 0;		// keeps track of the format
	int		digitcount		= 0;		// keeps track of the output buffer
	int		firstGroup = digitsafter + mDigitsInGroup;
	char	c;								// tmp storage
	const	char *  pSymbol;
    bool	bWasNegative = false;
	int		intNumber;
	double  factor;

	// for rounding, we need the absolute value
    if (number < 0)
	{
		bWasNegative = true;
		number = -number;
	}
	// round correctly to the number of digits displayed after the decimal symbol
	if (digitsafter > 0)
		factor = pow (10.0, (double)digitsafter);
	else
		factor = 1.0;
	// do it...
	intNumber = (int)floor(number * factor + .5);
	// and restore the sign
    number = bWasNegative ? -intNumber : intNumber;

	// OK, let's do the formatting..
	traverseFormat = strlen(format)-1;
	do
	{
		switch (format[traverseFormat])
		{
        // number goes here
		case 'n':
			digitcount = 0;
			// we build the string backwards by extracting the last digit
			do 
			{
				out[count++] = (intNumber % 10) + '0';          // convert digit to char
				digitcount++;

				// the proverbial 2 cents need special handling...
				if ((digitcount == 1) && (intNumber < 10) && digitsafter > 0) {	// 0.0x
					for (k = 1; k < digitsafter; k++) 
						out[count++] = '0';

					out[count++] = mDecimalSymbol;
					out[count++] = '0';					// 0.0x
					break;
				}	// pennies check

				// do we need to insert the decimal symbol here?
				if ((digitsafter > 0) && (digitcount == digitsafter)) {

					out[count++] = mDecimalSymbol;

					// special treatment for a dime as well...
					if (intNumber < 10) {
						out[count++] = '0';				// 0.xx
						break;
					}
				}	// decimal symbol check

				// grouping necessary?
				if ((mDigitsInGroup > 0) && (digitcount >= firstGroup)) {					
					if ((intNumber >= 10) && (((digitcount - digitsafter) % mDigitsInGroup) == 0))

					out[count++] = mGroupingSymbol;

				}	// grouping symbol check

			} while ((intNumber /= 10) > 0); // next digit (backwards)
			break;

		// currency symbol goes here
		case 's':
			pSymbol = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCurrencies()->GetCurrency(currencyId)->GetSymbol();
			for (k = strlen(pSymbol) - 1; k >= 0; k--)
				out[count++] = pSymbol[k];
			break;

		// anything else (blanks, negative signs) - just copy it
		default:
			out[count++] = format[traverseFormat];
			break;
		}
		traverseFormat--;
	} while (traverseFormat >= 0);
			
	out[count] = '\0';						// i now holds the length, so no need to strlen()
	
	// now reverse the string
	for (k = 0, j = count - 1; k < j; k++, j--) {
		c = out[k];
		out[k] = out[j];
		out[j] = c;
	}

	return out;

}

//
// I spent many a confused hour on this..
// It's meant to accept a time, change it to GMT so we have a common point to
// start from, and then adjust it to the time zone the locale is for.
// All cases that I could think of work - but I still don't trust it..
//

struct tm * clsIntlLocale::AdjustTime (time_t dateToAdjust, struct tm * dateAdjusted)
{
	time_t tStamp;

	// make time struct, adjusted to time zone & summer time
	dateAdjusted = gmtime (&dateToAdjust);	// convert from local (Pacific time) to UTC

	dateAdjusted->tm_sec += mTimeZoneOffset;	// correct for timezone of this locale
	tStamp = mktime (dateAdjusted);				// this will adjust for DST of server - sigh

	if (mObservesSummerTime)					// if country observes summer time
	{
		if (mSummerTimeStarts <= tStamp &&		// and it's currently in effect
			mSummerTimeEnds > tStamp)
		{
			mIsSummerTime = true;				// save that info so that FormatTime
												// knows what to display as time zone
//			if (dateAdjusted->tm_isdst == 0)	// at some point, I thought this was necessary..
//			{
			dateAdjusted->tm_sec += EBAY_LOCALE_SUMMERTIME_OFFSET;	// add time zone offset
			tStamp = mktime (dateAdjusted);		// and revert back to normalize
//			}
		}
		else									// it's winter!!
			mIsSummerTime = false;				// save for FormatTime
//		{
//			if (dateAdjusted->tm_isdst == 1)	// more confused code...
//			{
//				dateAdjusted->tm_sec -= EBAY_LOCALE_SUMMERTIME_OFFSET;
//				tStamp = mktime (dateAdjusted);
//			}
//		}
	}
//	else										// and even more...
//	{
//		if (dateAdjusted->tm_isdst == 1)
//		{
//			dateAdjusted->tm_sec -= EBAY_LOCALE_SUMMERTIME_OFFSET;
//			tStamp = mktime (dateAdjusted);
//		}
//	}

	dateAdjusted = localtime (&tStamp);			// convert to structure - done!

	return dateAdjusted;
}