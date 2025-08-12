<?php
//
// Definition of eZGeneralDigestHandler class
//
// Created on: <16-May-2003 10:55:24 sp>
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

/*! \file ezgeneraldigesthandler.php
*/

/*!
  \class eZGeneralDigestHandler ezgeneraldigesthandler.php
  \brief The class eZGeneralDigestHandler does

*/
include_once( 'kernel/classes/notification/eznotificationeventhandler.php' );
include_once( 'kernel/classes/notification/eznotificationcollection.php' );
define( 'EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID', 'ezgeneraldigest' );
include_once( 'kernel/classes/notification/handler/ezgeneraldigest/ezgeneraldigestusersettings.php' );

class eZGeneralDigestHandler extends eZNotificationEventHandler
{
    /*!
     Constructor
    */
    function eZGeneralDigestHandler()
    {
        $this->eZNotificationEventHandler( EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID, "General Digest Handler" );

    }

    function attributes()
    {
        return array_merge( array( 'settings',
                                   'all_week_days',
                                   'all_month_days',
                                   'available_hours' ),
                            eZNotificationEventHandler::attributes() );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function &attribute( $attr )
    {
        if ( $attr == 'settings' )
        {
            $user =& eZUser::currentUser();
            $settings =& $this->settings( $user );
            return $settings;
        }
        else if ( $attr == 'all_week_days' )
        {
            $locale =& eZLocale::instance();
            $nameList =& $locale->attribute( 'weekday_name_list' );
            return $nameList;
        }
        else if ( $attr == 'all_month_days' )
        {
            $range = range( 1, 31 );
            return $range;
        }
        else if ( $attr == 'available_hours' )
        {
            $hours = array( '0:00',
                            '1:00',
                            '2:00',
                            '3:00',
                            '4:00',
                            '5:00',
                            '6:00',
                            '7:00',
                            '8:00',
                            '9:00',
                            '10:00',
                            '11:00',
                            '12:00',
                            '13:00',
                            '14:00',
                            '15:00',
                            '16:00',
                            '17:00',
                            '18:00',
                            '19:00',
                            '20:00',
                            '21:00',
                            '22:00',
                            '23:00' );
            return $hours;
        }
        return eZNotificationEventHandler::attribute( $attr );
    }

    function &settings( $user = false )
    {
        if ( $user === false )
        {
            $user =& eZUser::currentUser();
        }
        $address = $user->attribute( 'email' );
        $settings = eZGeneralDigestUserSettings::fetchForUser( $address );
        if ( $settings == null )
        {
            $settings = eZGeneralDigestUserSettings::create( $address );
            $settings->store();
        }
        return $settings;
    }

    function handle( &$event )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $event, "trying to handle event" );
        if ( $event->attribute( 'event_type_string' ) == 'ezcurrenttime' )
        {
            $date =& $event->content();
            $timestamp = $date->attribute( 'timestamp' );

            $addressArray = $this->fetchUsersForDigest( $timestamp );

            include_once( 'kernel/common/template.php' );
            $tpl =& templateInit();

            foreach ( $addressArray as $address )
            {
                $tpl->setVariable( 'date', $date );
                $tpl->setVariable( 'address', $address['address'] );
                $result = $tpl->fetch( 'design:notification/handler/ezgeneraldigest/view/plain.tpl' );
                $subject = $tpl->variable( 'subject' );
                $transport =& eZNotificationTransport::instance( 'ezmail' );
                $transport->send( $address, $subject, $result);
                eZDebugSetting::writeDebug( 'kernel-notification', $result, "digest result" );
            }

            $collectionItemIDList =& $tpl->variable( 'collection_item_id_list' );
            eZDebugSetting::writeDebug( 'kernel-notification', $collectionItemIDList, "handled items" );

            if ( is_array( $collectionItemIDList ) && count( $collectionItemIDList ) > 0 )
            {
                eZPersistentObject::removeObject( eZNotificationCollectionItem::definition(), array( 'id' => array( $collectionItemIDList, '' ) ) );
            }

        }
        return true;
    }


    function fetchUsersForDigest( $timestamp )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationCollectionItem::definition(),
                                                    array(), array( 'send_date' => array( '', array( 1, $timestamp ) ) ),
                                                    array( 'address' => 'asc' ),null,
                                                    false,false,array( array( 'operation' => 'distinct address' ) ) );

    }

    function fetchHandlersForUser( $time, $address )
    {
        $query = "select distinct handler
                  from eznotificationcollection,
                       eznotificationcollection_item
                  where eznotificationcollection_item.collection_id = eznotificationcollection.id and
                        address='$address' and
                        send_date != 0 and
                        send_date < $time";
        $db =& eZDB::instance();
        $handlerResult = $db->arrayQuery( $query );
        $handlers = array();
        $availableHandlers =& eZNotificationEventFilter::availableHandlers();
        foreach ( $handlerResult as $handlerName )
        {
            $handlers[$handlerName['handler']] =& $availableHandlers[$handlerName['handler']];
        }
        return $handlers;
    }

    function fetchItemsForUser( $time, $address, $handler )
    {
        $query = "select eznotificationcollection_item.*
                  from eznotificationcollection,
                       eznotificationcollection_item
                  where eznotificationcollection_item.collection_id = eznotificationcollection.id and
                        address='$address' and
                        send_date != 0 and
                        send_date < $time and
                        handler = '$handler'
                        order by eznotificationcollection_item.event_id";
        $db =& eZDB::instance();
        $itemResult = $db->arrayQuery( $query );
        $items = array();
        foreach ( $itemResult as $itemRow )
        {
            $items[] = new eZNotificationCollectionItem( $itemRow );
        }
        return $items;
    }

    function storeSettings( &$http, &$module )
    {
        $user =& eZUser::currentUser();
        $address = $user->attribute( 'email' );
        $settings = eZGeneralDigestUserSettings::fetchForUser( $address );

        if ( $http->hasPostVariable( 'ReceiveDigest_' . EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID ) &&
             $http->hasPostVariable( 'ReceiveDigest_' . EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID ) == '1' )
        {
            $settings->setAttribute( 'receive_digest', 1 );
            $digestType = $http->postVariable( 'DigestType_' . EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID );
            $settings->setAttribute( 'digest_type', $digestType );
            if ( $digestType == 1 )
            {
                $settings->setAttribute( 'day', $http->postVariable( 'Weekday_' . EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID ) );
            }
            else if ( $digestType == 2 )
            {
                $settings->setAttribute( 'day', $http->postVariable( 'Monthday_' . EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID ) );
            }
            $settings->setAttribute( 'time', $http->postVariable( 'Time_' . EZ_GENERALDIGEST_NOTIFICATION_HANDLER_ID ) );
            $settings->store();
        }
        else
        {
            $settings->setAttribute( 'receive_digest', 0 );
            $settings->store();
        }
    }

    /*!
     \reimp
    */
    function cleanup()
    {
        eZGeneralDigestUserSettings::cleanup();
    }

}

?>
