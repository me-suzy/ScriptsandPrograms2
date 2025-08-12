<?php
//
// Definition of eZOrderStatus class
//
// Created on: <07-Mar-2005 17:20:18 jhe>
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

/*!
  \class eZOrderStatus ezorderstatus.php
  \brief Handles statuses which can be used on orders.

  This encapsulates the information about a status using
  the database table ezorder_status.

  This status can be selected on an order and is also stored
  in a history per order (eZOrderStatusHistory).

  The status consists of a name, a global ID and whether it is
  considered active or not.

  The following attributes are defined:
  - id - The auto increment ID for the status, this is only
         used to fetch a given status element from the database.
  - status_id - The global ID of the status, values below 1000 is considerd
                internal and cannot be removed by the user.
  - name - The name of the status.
  - is_active - Whether the status can be used by the end-user or not.

  Some special attributes:
  - is_internal - Returns true if the status is considerd an internal one (ID less than 1000).

  If the user creates a new status the function storeCustom() must be used, it will
  find the next available ID in the database and will use locking to avoid race conditions.

  To fetch a given status use fetch() when you have the DB ID or fetchByStatus() if you have
  a status ID.
  To fetch lists use fetchList() or fetchOrderedList() for a list sorted by name.
  If you intend to lookup many statuses using the ID the map from fetchMap() might be useful.
  To find the number of statuses in the system use orderStatusCount().

*/

include_once( "kernel/classes/ezpersistentobject.php" );

// Define for statuses that doesn't really exist (DB error)
define( 'EZ_ORDER_STATUS_UNDEFINED', 0 );

// Some predefined statuses, they will also appear in the database.
define( 'EZ_ORDER_STATUS_PENDING', 1 );
define( 'EZ_ORDER_STATUS_PROCESSING', 2 );
define( 'EZ_ORDER_STATUS_DELIVERED', 3 );

// All custom order statuses have this value or higher
define( 'EZ_ORDER_STATUS_CUSTOM', 1000 );

class eZOrderStatus extends eZPersistentObject
{
    /*!
    */
    function eZOrderStatus( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZOrderStatus class.
    */
    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "status_id" => array( 'name' => 'StatusID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "is_active" => array( 'name' => "IsActive",
                                                               'datatype' => 'bool',
                                                               'default' => true,
                                                               'required' => true ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array( 'is_internal' => 'isInternal' ),
                      "increment_key" => "id",
                      "class_name" => "eZOrderStatus",
                      "name" => "ezorder_status" );
    }

    /*!
     \return \c true if the status is considered an internal status.
    */
    function &isInternal()
    {
        $isInternal = $this->StatusID < EZ_ORDER_STATUS_CUSTOM;
        return $isInternal;
    }

    /*!
     \static
     Flushes all global caches for the statuses.
    */
    function flush()
    {
        unset( $GLOBALS['eZOrderStatusList'],
               $GLOBALS['eZOrderStatusOList'],
               $GLOBALS['eZOrderStatusMap'],
               $GLOBALS['eZOrderStatusUndefined'] );
    }

    /*!
     \return the status object with the given DB ID.
    */
    function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZOrderStatus::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /*!
     \return the status object with the given status ID.
     \note It is safe to call this with ID 0, instead of fetching the DB
           data it calls createUndefined() and returns that data.
    */
    function fetchByStatus( $statusID, $asObject = true )
    {
        if ( $statusID == 0 )
            return eZOrderStatus::createUndefined();
        return eZPersistentObject::fetchObject( eZOrderStatus::definition(),
                                                null,
                                                array( "status_id" => $statusID ),
                                                $asObject );
    }

    /*!
     \static
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders which maps from the status ID to the object.
    */
    function fetchMap( $asObject = true, $showInactive = false )
    {
        $map =& $GLOBALS['eZOrderStatusMap'][$asObject][$showInactive];
        if ( !isset( $map ) )
        {
            $conds = array();
            if ( !$showInactive )
                $conds['is_active'] = 1;
            $list = eZPersistentObject::fetchObjectList( eZOrderStatus::definition(),
                                                         null,
                                                         $conds,
                                                         null,
                                                         null,
                                                         $asObject );
            $map = array();
            if ( $asObject )
            {
                // Here we access member variables directly since it is of the same class
                foreach ( $list as $statusItem )
                {
                    $map[$statusItem->StatusID] = $statusItem;
                }
            }
            else
            {
                foreach ( $list as $statusItem )
                {
                    $map[$statusItem['status_id']] = $statusItem;
                }
            }
        }
        return $map;
    }

    /*!
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders sorted by status ID.
    */
    function fetchList( $asObject = true, $showInactive = false )
    {
        $list =& $GLOBALS['eZOrderStatusList'][$asObject][$showInactive];
        if ( !isset( $list ) )
        {
            $conds = array();
            if ( !$showInactive )
                $conds['is_active'] = 1;
            $list = eZPersistentObject::fetchObjectList( eZOrderStatus::definition(),
                                                         null,
                                                         $conds,
                                                         array( 'status_id' => false ),
                                                         null,
                                                         $asObject );
        }
        return $list;
    }

    /*!
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders sorted by status ID.
    */
    function fetchPolicyList( $showInactive = false )
    {
        $db =& eZDB::instance();

        $conditionText = '';
        if ( !$showInactive )
            $conditionText = ' WHERE is_active = 1';

        $rows = $db->arrayQuery( "SELECT status_id AS id, name FROM ezorder_status$conditionText" );
        return $rows;
    }

    /*!
     \param $asObject If \c true return them as objects.
     \param $showInactive If \c true it will include status items that are not active, default is \c false.
     \return A list of defined orders sorted by name.
    */
    function fetchOrderedList( $asObject = true, $showInactive = false )
    {
        $list =& $GLOBALS['eZOrderStatusOList'][$asObject][$showInactive];
        if ( !isset( $list ) )
        {
            $conds = array();
            if ( !$showInactive )
                $conds['is_active'] = 1;
            $list = eZPersistentObject::fetchObjectList( eZOrderStatus::definition(),
                                                         null,
                                                         $conds,
                                                         array( 'name' => false ),
                                                         null,
                                                         $asObject );
        }
        return $list;
    }

    /*!
     \return the number of active order statuses
    */
    function orderStatusCount( $showInactive = false )
    {
        $db =& eZDB::instance();

        $condText = '';
        if ( !$showInactive )
            $condText = " WHERE is_active = 1";
        $countArray = $db->arrayQuery(  "SELECT count( * ) AS count FROM ezorder_status$condText" );
        return $countArray[0]['count'];
    }


    /*!
     Will remove the current status from the database identifed by its DB ID.
     \note transaction safe
    */
    function remove()
    {
        $db =& eZDB::instance();
        $db->begin();

        // Set all elements using this status to 0 (undefined).
        $statusID = (int)$this->StatusID;
        $db->query( "UPDATE ezorder SET status_id = 0 WHERE status_id = $statusID" );
        $db->query( "UPDATE ezorder_status_history SET status_id = 0 WHERE status_id = $statusID" );

        $id = $this->ID;
        eZPersistentObject::removeObject( eZOrderStatus::definition(), array( "id" => $id ) );

        $db->commit();

        eZOrderStatus::flush();
    }

    /*!
     \static
     Creates a new order status and returns it.
    */
    function create()
    {
        $row = array(
            'id' => null,
            'is_active' => true,
            'name' => ezi18n( 'kernel/shop', 'Order status' ) );
        return new eZOrderStatus( $row );
    }

    /*!
     \static
     Creates an order status which contains 'Undefined' as name and 0 as status ID.
     This can be used whenever code expects a status object to work with.
     \return The newly created status object.
    */
    function createUndefined()
    {
        $obj =& $GLOBALS['eZOrderStatusUndefined'];
        if ( !isset( $obj ) )
        {
            $row = array(
                'id' => null,
                'status_id' => EZ_ORDER_STATUS_UNDEFINED,
                'is_active' => true,
                'name' => ezi18n( 'kernel/shop', 'Undefined' ) );
            $obj = new eZOrderStatus( $row );
        }
        return $obj;
    }

    /*!
     Stores a new custom order status.
     If there is no status ID yet it will acquire a new unique and store it
     with that.
     If it already has an ID it calls store() as normally.
    */
    function storeCustom()
    {
        if ( $this->StatusID )
        {
            eZOrderStatus::flush();
            $this->store();
        }
        else
        {
            // Lock the table while we find the highest number
            $db =& eZDB::instance();
            $db->lock( 'ezorder_status' );

            $rows = $db->arrayQuery( "SELECT max( status_id ) as status_id FROM ezorder_status" );
            $statusID = $rows[0]['status_id'];

            // If the max ID is below the custom one we set as the first
            // custom ID, if not we increase it by one.
            if ( $statusID < EZ_ORDER_STATUS_CUSTOM )
            {
                $statusID = EZ_ORDER_STATUS_CUSTOM;
            }
            else
            {
                ++$statusID;
            }

            $this->StatusID = $statusID;
            $this->store();

            $db->unlock();

            eZOrderStatus::flush();
        }
    }
}

?>
