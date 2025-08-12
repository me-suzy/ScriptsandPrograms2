<?php
//
// Definition of eZURLObjectLink class
//
// Created on: <04-Jul-2003 13:14:41 wy>
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

/*! \file ezurlobjectlink.php
*/

/*!
  \class eZURLObjectLink ezurlobjectlink.php
  \ingroup eZDatatype
  \brief The class eZURLObjectLink does

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZURLObjectLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZURLObjectLink( $row )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( 'fields' => array( 'url_id' => array( 'name' => 'URLID',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'contentobject_attribute_id' => array( 'name' => 'ContentObjectAttributeID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'contentobject_attribute_version' => array( 'name' => 'ContentObjectAttributeVersion',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'short_name' => 'contentobject_attr_version',
                                                                      'required' => true ) ),
                      'keys' => array( 'url_id', 'contentobject_attribute_id', 'contentobject_attribute_version' ),
                      'sort' => array( 'url_id' => 'asc' ),
                      'class_name' => 'eZURLObjectLink',
                      'name' => 'ezurl_object_link' );
    }

    /*!
     \static
    */
    function create( $urlID, $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        $row = array(
            'url_id' => $urlID,
            'contentobject_attribute_id' => $contentObjectAttributeID,
            'contentobject_attribute_version' => $contentObjectAttributeVersion );
        return new eZURLObjectLink( $row );
    }

    /*!
     \static
     \return the url object for id \a $id.
    */
    function fetch( $urlID, $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLObjectLink::definition(),
                                                null,
                                                array( 'url_id' => $urlID,
                                                       'contentobject_attribute_id' => $contentObjectAttributeID,
                                                       'contentobject_attribute_version' => $contentObjectAttributeVersion ),
                                                $asObject );
    }

    /*!
     \static
     \return \c true if the URL \a $urlID has any object links
    */
    function hasObjectLinkList( $urlID )
    {
        $rows = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                      array(),
                                                      array( 'url_id' => $urlID ),
                                                      array(),
                                                      null,
                                                      false,
                                                      false,
                                                      array( array( 'name' => 'count',
                                                                    'operation' => 'count( url_id )' ) ) );
        return ( $rows[0]['count'] > 0 );
    }

    /*!
     \static
     \return all object versions which has the link.
    */
    function &fetchObjectVersionList( $urlID, $parameters = false )
    {
        $objectVersionList = array();
        $urlObjectLinkList = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                                   null,
                                                                   array( 'url_id' => $urlID ),
                                                                   null,
                                                                   $parameters,
                                                                   true );
        $storedVersionList = array();
        foreach ( array_keys( $urlObjectLinkList ) as $key )
        {
            $urlObjectLink =& $urlObjectLinkList[$key];
            $objectAttributeID = $urlObjectLink->attribute( 'contentobject_attribute_id' );
            $objectAttributeVersion = $urlObjectLink->attribute( 'contentobject_attribute_version' );
            $objectAttribute = eZContentObjectAttribute::fetch( $objectAttributeID, $objectAttributeVersion );
            if ( $objectAttribute ) // Object and version has been deleted
            {
                $objectID = $objectAttribute->attribute( 'contentobject_id' );
                $objectVersion = $objectAttribute->attribute( 'version' );
                $object =& eZContentObject::fetch( $objectID );
                if ( $object )
                {
                    $versionObject =& $object->version( $objectVersion );
                    $versionID = $versionObject->attribute( 'id' );
                    if ( !in_array( $versionID, $storedVersionList ) )
                    {
                        $objectVersionList[] =& $versionObject;
                        $storedVersionList[] = $versionID;
                    }
                }
            }
        }
        return $objectVersionList;
    }

    /*!
     Get url object count
     \param urld id
    */
     function fetchObjectVersionCount( $urlID )
     {
         return count( eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                            null,
                                                            array( 'url_id' => $urlID ),
                                                            null,
                                                            null,
                                                            true ) );
     }

    /*!
     \static
     Removes all links for the object attribute \a $contentObjectAttributeID and version \a $contentObjectVersion.
     If \a $contentObjectVersion is \c false then all versions are removed as well.
    */
    function removeURLlinkList( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        $conditions = array( 'contentobject_attribute_id' => $contentObjectAttributeID );
        if ( $contentObjectAttributeVersion !== false )
            $conditions['contentobject_attribute_version'] = $contentObjectAttributeVersion;
        eZPersistentObject::removeObject( eZURLObjectLink::definition(),
                                          $conditions );
    }


    /*!
     \static
     \return all links for the contenobject attribute ID \a $contenObjectAttributeID and version \a $contenObjectVersion.
     If \a $contentObjectVersion is \c false then all links for all versions are returned.
    */
    function fetchLinkList( $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        $linkList = array();
        $conditions = array( 'contentobject_attribute_id' => $contentObjectAttributeID );
        if ( $contentObjectAttributeVersion !== false )
            $conditions['contentobject_attribute_version'] = $contentObjectAttributeVersion;
        $urlObjectLinkList = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                                   null,
                                                                   $conditions,
                                                                   null,
                                                                   null,
                                                                   $asObject );
        foreach ( array_keys( $urlObjectLinkList ) as $key )
        {
            $urlObjectLink =& $urlObjectLinkList[$key];
            if ( $asObject )
            {
                $linkID = $urlObjectLink->attribute( 'url_id' );
                $link = eZURL::fetch( $linkID );
                $linkList[] =& $link;
            }
            else
            {
                $linkID = $urlObjectLink['url_id'];
                $linkList[] = $linkID;
            }
        }
        return $linkList;
    }

    /*!
     \static
     Clear view cache for every object which contains URL with given link ID \a $urlID.
    */
    function clearCacheForObjectLink( $urlID )
    {
        include_once( "kernel/classes/ezcontentcachemanager.php" );
        $urlObjectLinkList = eZPersistentObject::fetchObjectList( eZURLObjectLink::definition(),
                                                                    null,
                                                                    array( 'url_id' => $urlID ),
                                                                    null,
                                                                    null,
                                                                    true );
        foreach ( $urlObjectLinkList as $urlObjectLink )
        {
            $objectAttributeID = $urlObjectLink->attribute( 'contentobject_attribute_id' );
            $objectAttributeVersion = $urlObjectLink->attribute( 'contentobject_attribute_version' );
            $objectAttribute = eZContentObjectAttribute::fetch( $objectAttributeID, $objectAttributeVersion );
            if ( $objectAttribute )
            {
                $objectID = $objectAttribute->attribute( 'contentobject_id' );
                $objectVersion = $objectAttribute->attribute( 'version' );
                eZContentCacheManager::clearContentCacheIfNeeded( $objectID, $objectVersion );
            }
        }
    }


    /// \privatesection
    var $URLID;
    var $ContentObjectAttributeID;
    var $ContentObjectAttributeVersion;
}
?>
