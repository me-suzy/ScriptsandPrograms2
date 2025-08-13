/* $Id: Options.cpp,v 1.2 1999/02/21 02:22:15 josh Exp $ */
//
// File: Options
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: See Options.h
//

#include "Options.h"
#include <iostream>
#include <string>
#include <fstream>
//using namespace std;

Options::Options() :
	mMaxDownloads(kDefaultMaxDownloads),
	mImageSize(kDefaultImagesize),
	mQuality(kDefaultQuality),
	mMaxConnectWait(kDefaultMaxConnectWait),
	mMaxRequestWaitTime(kDefaultMaxRequestWaitTime),
	mMaxReadWaitTime(kDefaultMaxReadWaitTime),
	mMaxRedirects(kDefaultMaxRedirects),
	mMaxAgeHours(kDefaultMaxAgeHours),
	mMaxItems(kDefaultMaxItems)
{
}

int Options::GetOptions(int argc, char** argv)
{
    int status = 0;	
    int arg;
    int tokencount = 0;
	int configResult = 0;
	std::string configurationFileName;

	if (argc < 2)
		goto HELP;


    // Scan command Line for parameters 
    for (arg=1; arg < argc; arg++)
	{
		if (*argv[arg] == '-') 
		{
			if (argv[arg][1] == '\0') 
			{
				// - alone
				goto HELP;
			} 
			else if (!strcmp(argv[arg],"-?") || !strcmp(argv[arg],"-help")) 
			{
				// -? or -help: show the command line help page
				goto HELP;
			} 
			else
			{
				// Unknown arguement
				std::cout << "Unknown arguement " << argv[arg] << std::endl;
				goto HELP;
			}
		} 
		else 
		{	
			if (configurationFileName.empty())
			{
				configurationFileName = argv[arg];
			}
			else
			{
				// Too many parameters
				std::cout << "Unknown arguement " << argv[arg] << std::endl;
				goto HELP;
			}
		}
    } // for

	if (configurationFileName.empty())
		goto HELP;

	configResult = ReadConfiguration(configurationFileName);
	if (configResult)
		return configResult;

	return 0;

HELP:
	char* toolName = strrchr(argv[0], '\\');
	if (!toolName)
		toolName = argv[0];
	else
		++toolName;

	std::cout << toolName << " configFile " << std::endl
		//	01234567890123456789012345678901234567890123456789012345678901234567890123456789
		<< "  configFile          Mandatory configuration file" << endl
		;

	return 1;
}

static void AddFinalDirectoryIndicator(std::string& dir)
{
	std::string::size_type len = dir.length();
	if (len < 1)
		return;

	char c = dir[len-1];

	if (c == '\\')
		return;

	dir.append("\\");

}

static void AddFinalDirectoryIndicator(vector<std::string>& strings)
{
	vector<std::string>::iterator begin = strings.begin();
	vector<std::string>::iterator end = strings.end();

	for (; begin != end; ++begin)
	{
		AddFinalDirectoryIndicator(*begin);
	}
}

int Options::ReadConfiguration(std::string& configFileName)
{
	try
	{
		std::ifstream in(configFileName.c_str(), std::ios::in);
		if (!in.is_open()) 
			throw std::runtime_error("Couldn\'t open configuration file");

		std::string property;
	
		while (!in.eof())
		{
			in >> property;

			if ("updates" == property)
			{
				in >> mUpdates;
			}
			else if ("imageDB" == property)
			{
				in >> mImageDB;
			}
			else if ("badImages" == property)
			{
				in >> mBadImages;
			}
			else if ("completed" == property)
			{
				in >> mCompleted;
			}
			else if ("failed" == property)
			{
				in >> mFailed;
			}
			else if ("bad" == property)
			{
				in >> mBad;
			}
			else if ("thumbDB" == property)
			{
				in >> mThumbDB;
			}
			else if ("notify" == property)
			{
				std::string who;
				in >> who;
				mNotify.push_back(who);
			}
			else if ("images" == property)
			{
				in >> mImages;
			}
			else if ("idExceptions" == property)
			{
				in >> mIDExceptions;
			}
			else if ("thumbDBDestination" == property)
			{
				std::string destination;
				in >> destination;
				mThumbnailDBDestination.push_back(destination);
			}
			else if ("backupDB" == property)
			{
				in >> mBackupDB;
			}
			else if ("thumbDBNotify" == property)
			{
				std::string destination;
				in >> destination;
				mThumbDBNotify.push_back(destination);
			}
			else if ("maxdownloads" == property)
			{
				in >> mMaxDownloads;
			}
			else if ("imagesize" == property)
			{
				in >> mImageSize;
			}
			else if ("quality" == property)
			{
				in >> mQuality;
			}
			else if ("maxconnectwait" == property)
			{
				in >> mMaxConnectWait;
			}
			else if ("maxrequestwait" == property)
			{
				in >> mMaxRequestWaitTime;
			}
			else if ("maxreadwait" == property)
			{
				in >> mMaxReadWaitTime;
			}
			else if ("maxredirect" == property)
			{
				in >> mMaxRedirects;
			}
			else if ("maxagehours" == property)
			{
				in >> mMaxAgeHours;
			}
			else if ("maxitems" == property)
			{
				in >> mMaxItems;
			}
			else if (property.empty())
			{
			}
			else
			{
				throw std::runtime_error("Bad configuration option");
			}

			std::getline(in, property);

		}

	}
	catch(std::runtime_error err)
	{
		return -1;
	}
	catch(...)
	{
		return -1;
	}

	if (mUpdates.empty())
		return -1;
	if (mImageDB.empty())
		return -1;
	if (mBadImages.empty())
		return -1;
	if (mCompleted.empty())
		return -1;
	if (mFailed.empty())
		return -1;
	if (mBad.empty())
		return -1;
	if (mThumbDB.empty())
		return -1;
	if (mImages.empty())
		return -1;
	if (mIDExceptions.empty())
		return -1;
	if (mThumbnailDBDestination.empty())
		return -1;
	if (mBackupDB.empty())
		return -1;
	if (mThumbDBNotify.empty())
		return -1;

	AddFinalDirectoryIndicator(mUpdates);
	AddFinalDirectoryIndicator(mImageDB);
	AddFinalDirectoryIndicator(mBadImages);
	AddFinalDirectoryIndicator(mCompleted);
	AddFinalDirectoryIndicator(mFailed);
	AddFinalDirectoryIndicator(mBad);
	AddFinalDirectoryIndicator(mThumbDB);
	AddFinalDirectoryIndicator(mImages);
	AddFinalDirectoryIndicator(mThumbnailDBDestination);
	AddFinalDirectoryIndicator(mBackupDB);


	return 0;
}
