<?php
//
// Definition of eZNotificationSchedule class
//
// Created on: <16-May-2003 15:22:43 sp>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eznotificationschedule.php
*/

/*!
  \class eZNotificationSchedule eznotificationschedule.php
  \brief The class eZNotificationSchedule does

*/
include_once( "lib/ezlocale/classes/ezdate.php" );


class eZNotificationSchedule
{
    /*!
     Constructor
    */
    function eZNotificationSchedule()
    {
    }

    function setDateForItem( &$item, $settings )
    {
        if ( !is_array( $settings ) )
            return false;

        $dayNum = $settings['day'];
        $hour = $settings['hour'];
        $currentDate = getdate();
        $hoursDiff = $hour - $currentDate['hours'];

        switch ( $settings['frequency'] )
        {
            case 'day':
            {
                if ( $hoursDiff <= 0 )
                {
                    $hoursDiff += 24;
                }

                $secondsDiff = 3600 * $hoursDiff
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;

            case 'week':
            {
                $daysDiff = $dayNum - $currentDate['wday'];
                if ( $daysDiff < 0 or
                     ( $daysDiff == 0 and $hoursDiff <= 0 ) )
                {
                    $daysDiff += 7;
                }

                $secondsDiff = 3600 * ( $daysDiff * 24 + $hoursDiff )
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;

            case 'month':
            {
                // If the daynum the user has chosen is larger than the number of days in this month,
                // then reduce it to the number of days in this month.
                $daysInMonth = intval( date( 't', mktime( 0, 0, 0, $currentDate['mon'], 1, $currentDate['year'] ) ) );
                if ( $dayNum > $daysInMonth )
                {
                    $dayNum = $daysInMonth;
                }

                $daysDiff = $dayNum - $currentDate['mday'];
                if ( $daysDiff < 0 or
                     ( $daysDiff == 0 and $hoursDiff <= 0 ) )
                {
                    $daysDiff += $daysInMonth;
                }

                $secondsDiff = 3600 * ( $daysDiff * 24 + $hoursDiff )
                     - $currentDate['seconds']
                     - 60 * $currentDate['minutes'];
            } break;
        }

        $sendDate = mktime() + $secondsDiff;
        eZDebugSetting::writeDebug( 'kernel-notification', getdate( $sendDate ), "item date"  );
        $item->setAttribute( 'send_date', $sendDate );
        return $sendDate;
    }
}

?>
