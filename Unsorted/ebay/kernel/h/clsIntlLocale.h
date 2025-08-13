/*	$Id: clsIntlLocale.h,v 1.1.6.4 1999/08/04 05:26:48 phofer Exp $	*/
//
//	File:	clsIntlLocale.h
//
//	Class:	clsIntlLocale
//
//	Author:	Petra Hofer (petra@ebay.com)
//
//	Function:
//
//		Representation of a locale
//
// Modifications:
//				- 07/02/99 petra	- created
//
//

#ifndef CLSINTLLOCALE_INCLUDED
#include "eBayTypes.h"

// class forward	
class clsIntlLocale;

#define EBAY_LOCALE_LONG_FORMAT			20
#define EBAY_LOCALE_MEDIUM_FORMAT		15
#define EBAY_LOCALE_SHORT_FORMAT		10
#define EBAY_LOCALE_TIMEZONE			10

#define EBAY_LOCALE_SUMMERTIME_OFFSET	3600

class clsIntlLocale
{

	public:

	// vanilla ctor
    clsIntlLocale(int localeId, int timezoneId);
						 
	// DTOR
	~clsIntlLocale();

	// getters and setters
	int		GetLocaleId()   { return mLocaleId; }
	int		GetCountryId()  { return mCountryId; }
	int		GetTimeZoneId() { return mTimeZoneId; }

	char *	GetTimeFormatted(char * timeBuffer, int formatLength = 0);	// intl_char
	char *	GetTimeFormatted(char * timeBuffer, time_t dateTime, int formatLength = 0);	// intl_char
	char *	GetTimeZone	();	// intl_char
	char *	GetDateFormatted (char * dateBuffer, int formatLength = 0);	// intl_char
	char *	GetDateFormatted (char * dateBuffer, time_t dateTime, int formatLength = 0);	// intl_char
	char *  GetDateTimeFormatted (char * dateTimeBuffer, int formatLength = 0);	// intl_char
	char *	GetDateTimeFormatted (char * dateTimeBuffer, time_t dateTime, int formatLength = 0);	// intl_char
// sigh	template <class DataType> char * GetNumberFormatted (char * numberBuffer, DataType number, int digitsAfterDecimal = 0);
	char * GetNumberFormatted (char * numberBuffer, int number, int digitsAfterDecimal = 0);
	char * GetNumberFormatted (char * numberBuffer, double number, int digitsAfterDecimal = 0);
	char *	GetCurrencyAmountFormatted (char * amountBuffer, double amount);	// intl_char
	char *	GetCurrencyAmountFormatted (char * amountBuffer, double amount, int currencyId);	// intl_char
	char *  GetNormalizedCurrencyAmount (char * amountBuffer);

	void	SetFromDB (	int	countryId,
						char * pNameStandard,	// intl_char
						char * pNameSummer,		// intl_char
						double timeZoneOffset,
						bool observesSummerTime,
						bool summerTimeBeginsFirst,
						int	summerTimeBeginsMonth,
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
						int defaultCurrencyId);

  private:

	time_t MakeSummerTimeStamp (bool summerTimeFirstSunday,
								int summerTimeMonth);
	char * FormatTime	(char * timeBuffer, 
						struct tm * timeStruct, 
						char * formatString);
	char * FormatDate	(char * dateBuffer,
						struct tm * dateStruct,
						char * formatString);
	char * FormatNumeric	(char * out, 
							double number, 
							int digitsafter, 
							char * format,
							int currencyId);
	struct tm * AdjustTime (time_t timeToAdjust,
							struct tm * timeAdjusted);
	int		mLocaleId;
	int		mCountryId;
	int		mTimeZoneId;
	char	mNameStandard[EBAY_LOCALE_TIMEZONE];	// intl_char
	char	mNameSummer[EBAY_LOCALE_TIMEZONE];	// intl_char
	double	mTimeZoneOffset;
	bool	mObservesSummerTime;
	time_t	mSummerTimeStarts;
	time_t	mSummerTimeEnds;
	char	mDateFormatShort[EBAY_LOCALE_SHORT_FORMAT];
	char	mDateFormatMedium[EBAY_LOCALE_MEDIUM_FORMAT];
	char	mDateFormatLong[EBAY_LOCALE_LONG_FORMAT];
	char	mTimeFormatShort[EBAY_LOCALE_SHORT_FORMAT];
	char	mTimeFormatMedium[EBAY_LOCALE_MEDIUM_FORMAT];
	char	mTimeFormatLong[EBAY_LOCALE_LONG_FORMAT];
	char	mPositiveAmountFormat[EBAY_LOCALE_MEDIUM_FORMAT];
	char	mNegativeAmountFormat[EBAY_LOCALE_MEDIUM_FORMAT];
	char	mDecimalSymbol;	// intl_char
	char	mGroupingSymbol;	// intl_char
	int		mDigitsAfterDecimal;
	int		mDigitsInGroup;
	char	mNegativeNumberFormat[EBAY_LOCALE_MEDIUM_FORMAT];
	int		mDefaultCurrencyId;

	// to avoid recalculating if date & time are requested together, we store the values
	bool	mIsSummerTime;
	time_t	mTimeLastFormatted;
	struct tm mTimeLastFormattedStruct;

};

#define CLSINTLLOCALE_INCLUDED 1
#endif CLSINTLLOCALE_INCLUDED