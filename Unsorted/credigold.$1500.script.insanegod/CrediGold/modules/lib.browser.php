<?

/////////////////////////////////////////////////////////////////////////////////

// Browser Name/Version/Platform Checker                                       //

// SourceForge: Breaking Down the Barriers to Open Source Development          //

// Copyright 1999-2000 (c) The SourceForge Crew                                //

// http://sourceforge.net                                                      //

//                                                                             //

// $Id: browser.php,v 1.1 2000/08/05 17:42:19 cvs Exp $                        //

// Modified by Svetlin Staev (Added detection of Netscape 6 Gecko & Opera 5.x) //

/////////////////////////////////////////////////////////////////////////////////



unset ($BROWSER_AGENT);

unset ($BROWSER_VERSION);

unset ($BROWSER_PLATFORM);



/*------------------------------------------------------------------------------*/

/*                              Detection Functions                             */

/*------------------------------------------------------------------------------*/

function getBrowser()

   {

      global $BROWSER_AGENT;

      return $BROWSER_AGENT;

   }



function getVersion()

   {

      global $BROWSER_VERSION;

      return $BROWSER_VERSION;

   }



function getPlatform()

   {

      global $BROWSER_PLATFORM;

      return $BROWSER_PLATFORM;

   }

/*------------------------------------------------------------------------------*/

/*                              Platform Detection                              */

/*------------------------------------------------------------------------------*/

function isMac()

   {

      if (getPlatform()=='Mac') return true;

      else return false;

   }



function isWin()

   {

      if (getPlatform()=='Win') return true;

      else return false;

   }



function isLinux()

   {

      if (getPlatform()=='Linux') return true;

      else return false;

   }



/*------------------------------------------------------------------------------*/

/*                              Browser Detection                               */

/*------------------------------------------------------------------------------*/

function isIE()

   {

      if (getBrowser()=='IE') return true;

      else return false;

   }

function isIE55Plus()

   {

      if (getBrowser()=='IE' && getVersion() >= 5.5) return true;

      else return false;

   }



function isNS()

   {

      if (getBrowser()=='NETSCAPE') return true;

      else return false;

   }

function isGecko()

   {

      if (getBrowser()=='NETSCAPE6') return true;

      else return false;

   }

function isOpera3()

   {

      if (getBrowser()=='OPERA' && getVersion()<5) return true;

      else return false;

   }

function isOpera5()

   {

      if (getBrowser()=='OPERA' && getVersion()>=5) return true;

      else return false;

   }



/*------------------------------------------------------------------------------*/

/*                           Browser Version Detection                          */

/*------------------------------------------------------------------------------*/

if (ereg( 'Opera ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version) || ereg( 'Opera/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))

   {

      $BROWSER_VERSION=$log_version[1];

      $BROWSER_AGENT='OPERA';

   }

elseif (ereg( 'MSIE ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))

   {

      $BROWSER_VERSION=$log_version[1];

      $BROWSER_AGENT='IE';

   }

elseif (ereg( 'Netscape6/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))

   {

      $BROWSER_VERSION=$log_version[1];

      $BROWSER_AGENT='NETSCAPE6';

   }

elseif (ereg( 'Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))

   {

      $BROWSER_VERSION=$log_version[1];

      $BROWSER_AGENT='NETSCAPE';

   }

else

   {

      $BROWSER_VERSION=0;

      $BROWSER_AGENT='OTHER';

   }



if      (strstr($HTTP_USER_AGENT,'Win'))   $BROWSER_PLATFORM='Win';

else if (strstr($HTTP_USER_AGENT,'Mac'))   $BROWSER_PLATFORM='Mac';

else if (strstr($HTTP_USER_AGENT,'Linux')) $BROWSER_PLATFORM='Linux';

else if (strstr($HTTP_USER_AGENT,'Unix'))  $BROWSER_PLATFORM='Unix';

else							                   $BROWSER_PLATFORM='Other';

?>