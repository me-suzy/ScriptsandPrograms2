<?php
//
// Definition of eZContentClass class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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
  \class eZContentClass ezcontentclass.php
  \ingroup eZKernel
  \brief Handles eZ publish content classes

  \sa eZContentObject
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );
include_once( "kernel/common/i18n.php" );

define( "EZ_CLASS_VERSION_STATUS_DEFINED", 0 );
define( "EZ_CLASS_VERSION_STATUS_TEMPORARY", 1 );
define( "EZ_CLASS_VERSION_STATUS_MODIFED", 2 );

class eZContentClass extends eZPersistentObject
{
    function eZContentClass( $row )
    {
        if ( is_array( $row ) )
        {
            $this->eZPersistentObject( $row );
            $this->VersionCount = false;
            $this->InGroups = null;
            $this->AllGroups = null;
            if ( isset( $row["version_count"] ) )
                $this->VersionCount = $row["version_count"];
        }
        $this->DataMap = false;
    }

    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "version" => array( 'name' => 'Version',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "identifier" => array( 'name' => "Identifier",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "contentobject_name" => array( 'name' => "ContentObjectName",
                                                                        'datatype' => 'string',
                                                                        'default' => '',
                                                                        'required' => true ),
                                         "creator_id" => array( 'name' => "CreatorID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "remote_id" => array( 'name' => "RemoteID",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "is_container" => array( 'name' => "IsContainer",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true )),
                      "keys" => array( "id", "version" ),
                      "function_attributes" => array( "data_map" => "dataMap",
                                                      'object_count' => 'objectCount',
                                                      'version_count' => 'versionCount',
                                                      'version_status' => 'versionStatus',
                                                      'remote_id' => 'remoteID', // Note: This overrides remote_id field
                                                      'ingroup_list' => 'fetchGroupList',
                                                      'ingroup_id_list' => 'fetchGroupIDList',
                                                      'match_ingroup_id_list' => 'fetchMatchGroupIDList',
                                                      'group_list' => 'fetchAllGroups',
                                                      'creator' => 'creator',
                                                      'modifier' => 'modifier' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentClass",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentclass" );
    }

    function clone()
    {
        $row = array(
            "id" => null,
            "version" => $this->attribute( 'version' ),
            "name" => $this->attribute( 'name' ),
            "identifier" => $this->attribute( 'identifier' ),
            "contentobject_name" => $this->attribute( 'contentobject_name' ),
            "creator_id" => $this->attribute( 'creator_id' ),
            "modifier_id" => $this->attribute( 'modifier_id' ),
            "created" => $this->attribute( 'created' ),
            "modified" => $this->attribute( 'modified' ),
            "is_container" => $this->attribute( 'is_container' ) );
        $tmpClass = new eZContentClass( $row );
        return $tmpClass;
    }

    function create( $userID = false, $optionalValues = array() )
    {
        $dateTime = time();
        if ( !$userID )
            $userID = eZUser::currentUserID();
        $row = array(
            "id" => null,
            "version" => 1,
            "name" => "",
            "identifier" => "",
            "contentobject_name" => "",
            "creator_id" => $userID,
            "modifier_id" => $userID,
            "created" => $dateTime,
            'remote_id' => md5( (string)mt_rand() . (string)mktime() ),
            "modified" => $dateTime,
            "is_container" => 0 );
        $row = array_merge( $row, $optionalValues );
        $contentClass = new eZContentClass( $row );
        return $contentClass;
    }

    /*!
     Creates a new content object instance and stores it.

     \param user ID (optional), current user if not set
     \param section ID (optional), 0 if not set
     \param version number, create initial version if not set
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &instantiate( $userID = false, $sectionID = 0, $versionNumber = false )
    {
        $attributes = $this->fetchAttributes();

        if ( $userID === false )
        {
            $user =& eZUser::currentUser();
            $userID =& $user->attribute( 'contentobject_id' );
        }

        $object = eZContentObject::create( ezi18n( "kernel/contentclass", "New %1", null, array( $this->attribute( "name" ) ) ),
                                           $this->attribute( "id" ),
                                           $userID,
                                           $sectionID );

        $db =& eZDB::instance();
        $db->begin();

        $object->store();
        //  $object->setName( "New " . $this->attribute( "name" ) );
        $object->setName( ezi18n( "kernel/contentclass", "New %1", null, array( $this->attribute( "name" ) ) ) );

        if ( !$versionNumber )
        {
            $version = $object->createInitialVersion( $userID );
        }
        else
        {
            $version = eZContentObjectVersion::create( $object->attribute( "id" ), $userID, $versionNumber );
        }

        $version->store();

        foreach ( array_keys( $attributes ) as $attributeKey )
        {
            $attribute =& $attributes[$attributeKey];
            $attribute->instantiate( $object->attribute( 'id' ) );
        }

        $db->commit();
        return $object;
    }

    function canInstantiateClasses()
    {
        $ini =& eZINI::instance();
        $enableCaching = $ini->variable( 'RoleSettings', 'EnableCaching' );

        if ( $enableCaching == 'true' )
        {
            $http =& eZHTTPTool::instance();

            include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
            $handler =& eZExpiryHandler::instance();
            $expiredTimeStamp = 0;
            if ( $handler->hasTimestamp( 'user-class-cache' ) )
                $expiredTimeStamp = $handler->timestamp( 'user-class-cache' );

            $classesCachedForUser = $http->sessionVariable( 'CanInstantiateClassesCachedForUser' );
            $classesCachedTimestamp = $http->sessionVariable( 'ClassesCachedTimestamp' );
            $user =& eZUser::currentUser();
            $userID = $user->id();

            if ( ( $classesCachedTimestamp >= $expiredTimeStamp ) && $classesCachedForUser == $userID )
            {
                if ( $http->hasSessionVariable( 'CanInstantiateClasses' ) )
                {
                    return $http->sessionVariable( 'CanInstantiateClasses' );
                }
            }
            else
            {
                // store cache
                $http->setSessionVariable( 'CanInstantiateClassesCachedForUser', $userID );
//                $http->setSessionVariable( 'classesCachedTimestamp', mktime() );
            }
        }
        $user =& eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];
        $canInstantiateClasses = 1;
        if ( $accessWord == 'no' )
        {
            $canInstantiateClasses = 0;
        }

        if ( $enableCaching == 'true' )
        {
            $http->setSessionVariable( 'CanInstantiateClasses', $canInstantiateClasses );
        }
        return $canInstantiateClasses;
    }

    // code-template::create-block: can-instantiate-class-list, group-filter, role-caching, class-policy-list, name-instantiate, object-creation
    // code-template::auto-generated:START can-instantiate-class-list
    // This code is automatically generated from templates/classcreatelist.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    /*!
     \static
     Finds all classes that the current user can create objects from and returns.
     It is also possible to filter the list event more with \a $includeFilter and \a $groupList.

     \param $asObject If \c true then it return eZContentClass objects, if not it will
                      be an associative array with \c name and \c id keys.
     \param $includeFilter If \c true then it will include only from class groups defined in
                           \a $groupList, if not it will exclude those groups.
     \param $groupList An array with class group IDs that should be used in filtering, use
                       \c false if you do not wish to filter at all.
     \param $id A unique name for the current fetch, this must be supplied when filtering is
                used if you want caching to work.
    */
    function &canInstantiateClassList( $asObject = false, $includeFilter = true, $groupList = false, $fetchID = false )
    {
        $ini =& eZINI::instance();
        $groupArray = array();

        $enableCaching = ( $ini->variable( 'RoleSettings', 'EnableCaching' ) == 'true' );
        if ( is_array( $groupList ) )
        {
            if ( $fetchID == false )
                $enableCaching = false;
        }

        if ( $enableCaching )
        {
            $http =& eZHTTPTool::instance();
            //$permissionExpired = $http->sessionVariable( 'roleExpired' );
            include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
            $handler =& eZExpiryHandler::instance();
            $expiredTimeStamp = 0;
            if ( $handler->hasTimestamp( 'user-class-cache' ) )
                $expiredTimeStamp = $handler->timestamp( 'user-class-cache' );
//             print( "expired time stamp = '$expiredTimeStamp'<br/>" );

            $classesCachedForUser = $http->sessionVariable( 'ClassesCachedForUser' );
            $classesCachedTimestamp = $http->sessionVariable( 'ClassesCachedTimestamp' );

//             print( "\$classesCachedForUser='$classesCachedForUser'<br/>" );
//             print( "\$classesCachedTimestamp='$classesCachedTimestamp'<br/>" );

            $cacheVar = 'CanInstantiateClassList';
            if ( is_array( $groupList ) and $fetchID !== false )
            {
                $cacheVar = 'CanInstantiateClassListGroup';
            }
//             print( "\$cacheVar='$cacheVar'<br/>" );

            $user =& eZUser::currentUser();
            $userID = $user->id();
//             print( "\$userID='$userID'<br/>" );
            if ( ( $classesCachedTimestamp >= $expiredTimeStamp ) && $classesCachedForUser == $userID )
            {
                if ( $http->hasSessionVariable( $cacheVar ) )
                {
                    if ( $fetchID !== false )
                    {
                        // Check if the group contains our ID, if not we need to fetch from DB
                        $groupArray = $http->sessionVariable( $cacheVar );
                        if ( isset( $groupArray[$fetchID] ) )
                        {
//                             print( "returning cached class list in group<br/>" );
                            return $groupArray[$fetchID];
                        }
                    }
                    else
                    {
//                         print( "returning cached class list<br/>" );
                        return $http->sessionVariable( $cacheVar );
                    }
                }
            }
            else
            {
                $http->setSessionVariable( 'ClassesCachedForUser' , $userID );
                $http->setSessionVariable( 'ClassesCachedTimestamp', mktime() );
            }
        }

        $user =& eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];

        $classIDArray = array();
        $classList = array();
        $fetchAll = false;
        if ( $accessWord == 'yes' )
        {
            $fetchAll = true;
        }
        else if ( $accessWord == 'no' )
        {
            // Cannnot create any objects, return empty list.
            return $classList;
        }
        else
        {
            $policies  =& $accessResult['policies'];
            foreach ( $policies as $policyKey => $policy )
            {
                $classIDArrayPart = '*';
                if ( isset( $policy['Class'] ) )
                {
                    $classIDArrayPart =& $policy['Class'];
                }

                if ( $classIDArrayPart == '*' )
                {
                    $fetchAll = true;
                    break;
                }
                else
                {
                    $classIDArray = array_merge( $classIDArray, array_diff( $classIDArrayPart, $classIDArray ) );
                    unset( $classIDArrayPart );
                }
            }
        }

        $filterTableSQL = '';
        $filterSQL = '';
        // Create extra SQL statements for the class group filters.
        if ( is_array( $groupList ) )
        {
            $filterTableSQL = ', ezcontentclass_classgroup ccg';
            $filterSQL = ( " AND\n" .
                           "      cc.id = ccg.contentclass_id AND\n" .
                           "      ccg.group_id " );
            $groupText = implode( ', ', $groupList );
            if ( $includeFilter )
                $filterSQL .= "IN ( $groupText )";
            else
                $filterSQL .= "NOT IN ( $groupText )";
        }

        if ( $fetchAll )
        {
            $classList = array();
            $db =& eZDb::instance();
            $classString = implode( ',', $classIDArray );
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*" : "cc.id, cc.name";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL\n" .
                                     "WHERE cc.version = " . EZ_CLASS_VERSION_STATUS_DEFINED . "$filterSQL\n" .
                                     "ORDER BY cc.name ASC" );
            $classList =& eZPersistentObject::handleRows( $rows, 'ezcontentclass', $asObject );
        }
        else
        {
            // If the constrained class list is empty we are not allowed to create any class
            if ( count( $classIDArray ) == 0 )
            {
                $classList = array();
                return $classList;
            }

            $classList = array();
            $db =& eZDb::instance();
            $classString = implode( ',', $classIDArray );
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*" : "cc.id, cc.name";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL\n" .
                                     "WHERE cc.id IN ( $classString  ) AND\n" .
                                     "      cc.version = " . EZ_CLASS_VERSION_STATUS_DEFINED . "$filterSQL\n",
                                     "ORDER BY cc.name ASC" );
            $classList =& eZPersistentObject::handleRows( $rows, 'ezcontentclass', $asObject );
        }

        eZDebugSetting::writeDebug( 'kernel-content-class', $classList, "class list fetched from db" );
        if ( $enableCaching )
        {
            if ( $fetchID !== false )
            {
                $groupArray[$fetchID] = $classList;
                $http->setSessionVariable( $cacheVar, $groupArray );
            }
            else
            {
                $http->setSessionVariable( $cacheVar, $classList );
            }
        }

        return $classList;
    }

    // This code is automatically generated from templates/classcreatelist.ctpl
    // code-template::auto-generated:END can-instantiate-class-list

    /*!
     \return The creator of the class as an eZUser object by using the $CreatorID as user ID.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::fetch( $this->CreatorID );
        }
        else
            $user = null;
        return $user;
    }

    /*!
     \return The modifier of the class as an eZUser object by using the $ModifierID as user ID.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $user = eZUser::fetch( $this->ModifierID );
        }
        else
            $user = null;
        return $user;
    }

    /*!
     Find all groups the current class is placed in and returns a list of group objects.
     \return An array with eZContentClassGroup objects.
     \sa fetchGroupIDList()
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &fetchGroupList()
    {
        $this->InGroups = eZContentClassClassGroup::fetchGroupList( $this->attribute( "id" ),
                                                                     $this->attribute( "version" ),
                                                                     true );
        return $this->InGroups;
    }

    /*!
     Find all groups the current class is placed in and returns a list of group IDs.
     \return An array with integers (ids).
     \sa fetchGroupList()
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &fetchGroupIDList()
    {
        $list = eZContentClassClassGroup::fetchGroupList( $this->attribute( "id" ),
                                                          $this->attribute( "version" ),
                                                          false );
        $this->InGroupIDs = array();
        foreach ( $list as $item )
        {
            $this->InGroupIDs[] = $item['group_id'];
        }
        return $this->InGroupIDs;
    }

    /*!
     Returns the result from fetchGroupIDList() if class group overrides is
     enabled in content.ini.
     \return An array with eZContentClassGroup objects or \c false if disabled.
     \note \c EnableClassGroupOverride in group \c ContentOverrideSettings from INI file content.ini
           controls this behaviour.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &fetchMatchGroupIDList()
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $contentINI =& eZINI::instance( 'content.ini' );
        if( $contentINI->variable( 'ContentOverrideSettings', 'EnableClassGroupOverride' ) == 'true' )
        {
            $retValue =& $this->attribute( 'ingroup_id_list' );
        }
        else
        {
            $retValue = false;
        }
        return $retValue;
    }

    /*!
     Finds all Class groups in the system and returns them.
     \return An array with eZContentClassGroup objects.
     \sa fetchGroupList(), fetchGroupIDList()
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &fetchAllGroups()
    {
        $this->AllGroups = eZContentClassGroup::fetchList();
        return $this->AllGroups;
    }

    /*!
     \return true if the class is part of the group \a $groupID
    */
    function inGroup( $groupID )
    {
        return eZContentClassClassGroup::classInGroup( $this->attribute( 'id' ),
                                                       $this->attribute( 'version' ),
                                                       $groupID );
    }

    /*!
     \static
     Will remove all temporary classes from the database.
    */
    function removeTemporary()
    {
        $version = EZ_CLASS_VERSION_STATUS_TEMPORARY;
        $temporaryClasses = eZContentClass::fetchList( $version, true );
        $db =& eZDb::instance();
        $db->begin();
        foreach ( $temporaryClasses as $class )
        {
            $class->remove( true, $version );
        }
        eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                          array( 'version' => $version ) );

        $db->commit();
    }

    /*!
     Get remote id of content node
    */
    function &remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        if ( !$remoteID &&
             $this->Version == EZ_CLASS_VERSION_STATUS_DEFINED )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)mktime() ) );
            $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        }

        return $remoteID;
    }

    function remove( $remove_childs = false, $version = EZ_CLASS_VERSION_STATUS_DEFINED )
    {
        // If we are not allowed to remove just return false
        if ( $this->Version != EZ_CLASS_VERSION_STATUS_TEMPORARY && !$this->isRemovable() )
            return false;

        if ( is_array( $remove_childs ) or $remove_childs )
        {
            if ( is_array( $remove_childs ) )
            {
                $db =& eZDb::instance();
                $db->begin();

                $attributes =& $remove_childs;
                for ( $i = 0; $i < count( $attributes ); ++$i )
                {
                    $attribute =& $attributes[$i];
                    $attribute->remove();
                }
                $db->commit();
            }
            else
            {
                if ( $version == EZ_CLASS_VERSION_STATUS_DEFINED )
                {
                    $contentObjects = eZContentObject::fetchSameClassList( $this->ID );
                    foreach ( $contentObjects as $contentObject )
                    {
                        $contentObject->remove();
                    }
                    $contentClassID = $this->ID;
                    $version = $this->Version;
                    $classAttributes =& $this->fetchAttributes( );

                    foreach ( $classAttributes as $classAttribute )
                    {
                        $dataType =& $classAttribute->dataType();
                        $dataType->deleteStoredClassAttribute( $classAttribute, $version );
                    }
                    eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                                      array( "contentclass_id" => $contentClassID,
                                                             "version" => $version ) );
                }
                else
                {
                    $contentClassID = $this->ID;
                    $version = $this->Version;
                    $classAttributes =& $this->fetchAttributes( );

                    foreach ( $classAttributes as $classAttribute )
                    {
                        $dataType =& $classAttribute->dataType();
                        $dataType->deleteStoredClassAttribute( $classAttribute, $version );
                    }
                    eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                                      array( "contentclass_id" => $contentClassID,
                                                             "version" => $version ) );
                }
            }
        }
        eZPersistentObject::remove();
    }

    /*!
     Checks if the class can be removed and returns \c true if it can, \c false otherwise.
     \sa removableInformation()
    */
    function isRemovable()
    {
        $info = $this->removableInformation( false );
        return count( $info['list'] ) == 0;
    }

    /*!
     Returns information on why the class cannot be removed,
     it does the same checks as in isRemovable() but generates
     some text in the return array.
     \return An array which contains:
             - text - Plain text description why this cannot be removed
             - list - An array with reasons why this failed, each entry contains:
                      - text - Plain text description of the reason.
                      - list - A sublist of reason (e.g from an attribute), is optional.
     \param $includeAll Controls whether the returned information will contain all
                        sources for not being to remove or just the first that it finds.
    */
    function removableInformation( $includeAll = true )
    {
        $result  = array( 'text' => ezi18n( 'kernel/contentclass', "Cannot remove class '%class_name':",
                                         null, array( '%class_name' => $this->Name ) ),
                       'list' => array() );
        $reasons =& $result['list'];
        $db      =& eZDB::instance();

        // Check top-level nodes
        $rows = $db->arrayQuery( "SELECT ezcot.node_id
FROM ezcontentobject_tree ezcot, ezcontentobject ezco
WHERE ezcot.depth = 1 AND
      ezco.contentclass_id = $this->ID AND
      ezco.id=ezcot.contentobject_id" );
        if ( count( $rows ) > 0 )
        {
            $reasons[] = array( 'text' => ezi18n( 'kernel/contentclass', 'The class is used by a top-level node and cannot be removed.
You will need to change the class of the node by using the swap functionality.' ) );
            if ( !$includeAll )
                return $result;
        }

        // Check class attributes
        $attributes =& $this->fetchAttributes();
        foreach ( $attributes as $key => $attribute )
        {
            $dataType = $attribute->dataType();
            if ( !$dataType->isClassAttributeRemovable( $attribute ) )
            {
                $info = $dataType->classAttributeRemovableInformation( $attribute, $includeAll );
                $reasons[] = $info;
                if ( !$includeAll )
                    return $result;
            }
        }

        return $result;
    }

    function removeAttributes( $attributes = false, $id = false, $version = false )
    {
        if ( is_array( $attributes ) )
        {
            $db =& eZDB::instance();
            $db->begin();
            for ( $i = 0; $i < count( $attributes ); ++$i )
            {
                $attribute =& $attributes[$i];
                $attribute->remove();
                $contentObject->purge();
            }
            $db->commit();
        }
        else
        {
            if ( $version === false )
                $version = $this->Version;
            if ( $id === false )
                $id = $this->ID;
            eZPersistentObject::removeObject( eZContentClassAttribute::definition(),
                                              array( "contentclass_id" => $id,
                                                     "version" => $version ) );
        }
    }

    function adjustAttributePlacements( &$attributes )
    {
        if ( !is_array( $attributes ) )
            return;
        for ( $i = 0; $i < count( $attributes ); ++$i )
        {
            $attribute =& $attributes[$i];
            $attribute->setAttribute( "placement", $i + 1 );
        }
    }

    /*!
     \reimp
    */
    function store( $store_childs = false, $fieldFilters = null )
    {
        $db =& eZDB::instance();
        $db->begin();

        if ( is_array( $store_childs ) or $store_childs )
        {
            if ( is_array( $store_childs ) )
                $attributes =& $store_childs;
            else
                $attributes =& $this->fetchAttributes();

           for ( $i = 0; $i < count( $attributes ); ++$i )
            {
                $attribute =& $attributes[$i];
                if ( is_object ( $attribute ) )
                    $attribute->store();
            }
        }

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-class-cache', mktime() );
        $handler->store();

        eZPersistentObject::store( $fieldFilters );
        $db->commit();
    }

    /*!
     \reimp
    */
    function sync( $fieldFilters = null )
    {
        if ( $this->hasDirtyData() )
            $this->store( false, $fieldFilters );
    }

    /*!
     Initializes this class as a copy of \a $originalClass by
     creating new a new name and identifier.
     It will check if there are other classes already with this name
     in which case it will append a unique number to the name and identifier.
    */
    function initializeCopy( &$originalClass )
    {
        $name = ezi18n( 'kernel/class', 'Copy of %class_name', null,
                        array( '%class_name' => $originalClass->attribute( 'name' ) ) );
        $identifier = 'copy_of_' . $originalClass->attribute( 'identifier' );
        $db =& eZDB::instance();
        $sql = "SELECT count( name ) AS count FROM ezcontentclass WHERE name like '" . $db->escapeString( $name ) . "%'";
        $rows = $db->arrayQuery( $sql );
        $count = $rows[0]['count'];
        if ( $count > 0 )
        {
            ++$count;
            $name .= $count;
            $identifier .= $count;
        }
        $this->setAttribute( 'name', $name );
        $this->setAttribute( 'identifier', $identifier );
        $this->setAttribute( 'created', time() );
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user =& eZUser::currentUser();
        $userID = $user->attribute( "contentobject_id" );
        $this->setAttribute( 'creator_id', $userID );
    }

    /*!
     Stores the current class as a defined version, updates the contentobject_name
     attribute and recreates the class group entries.
     \note It will remove any existing temporary or defined classes before storing.
    */
    function storeDefined( &$attributes )
    {
        $db =& eZDB::instance();
        $db->begin();

        eZContentClass::removeAttributes( false, $this->attribute( "id" ), EZ_CLASS_VERSION_STATUS_DEFINED );
        eZContentClass::removeAttributes( false, $this->attribute( "id" ), EZ_CLASS_VERSION_STATUS_TEMPORARY );
        $this->remove( false );
        $this->setVersion( EZ_CLASS_VERSION_STATUS_DEFINED, $attributes );
        include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
        $user =& eZUser::currentUser();
        $user_id = $user->attribute( "contentobject_id" );
        $this->setAttribute( "modifier_id", $user_id );
        $this->setAttribute( "modified", time() );
        $this->adjustAttributePlacements( $attributes );

        for ( $i = 0; $i < count( $attributes ); ++$i )
        {
            $attribute =& $attributes[$i];
            $attribute->storeDefined();
        }

        // Set contentobject_name to something sensible if it is missing
        if ( count( $attributes ) > 0 )
        {
            $identifier = $attributes[0]->attribute( 'identifier' );
            $identifier = '<' . $identifier . '>';
            if ( trim( $this->attribute( 'contentobject_name' ) ) == '' )
            {
                $this->setAttribute( 'contentobject_name', $identifier );
            }
        }

        // Recreate class member entries
        eZContentClassClassGroup::removeClassMembers( $this->ID, EZ_CLASS_VERSION_STATUS_DEFINED );
        $classgroups = eZContentClassClassGroup::fetchGroupList( $this->ID, EZ_CLASS_VERSION_STATUS_TEMPORARY );
        for ( $i = 0; $i < count( $classgroups ); $i++ )
        {
            $classgroup =& $classgroups[$i];
            $classgroup->setAttribute( 'contentclass_version', EZ_CLASS_VERSION_STATUS_DEFINED );
            $classgroup->store();
        }
        eZContentClassClassGroup::removeClassMembers( $this->ID, EZ_CLASS_VERSION_STATUS_TEMPORARY );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-class-cache', mktime() );
        $handler->store();

        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();

        eZPersistentObject::store();
        $db->commit();
    }

    function setVersion( $version, $set_childs = false )
    {
        if ( is_array( $set_childs ) or $set_childs )
        {
            if ( is_array( $set_childs ) )
                $attributes =& $set_childs;
            else
                $attributes =& $this->fetchAttributes();
            for ( $i = 0; $i < count( $attributes ); ++$i )
            {
                $attribute =& $attributes[$i];
                $attribute->setAttribute( "version", $version );
            }
        }
        eZPersistentObject::setAttribute( "version", $version );
    }

    function exists( $id, $version = EZ_CLASS_VERSION_STATUS_DEFINED, $userID = false, $useIdentifier = false )
    {
        $conds = array( "version" => $version );
        if ( $useIdentifier )
            $conds["identifier"] = $id;
        else
            $conds["id"] = $id;
        if ( $userID !== false and is_numeric( $userID ) )
            $conds["creator_id"] = $userID;
        $version_sort = "desc";
        if ( $version == EZ_CLASS_VERSION_STATUS_DEFINED )
            $conds['version'] = $version;
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      null,
                                                      array( "offset" => 0,
                                                             "length" => 1 ),
                                                      false );
        if ( count( $rows ) > 0 )
            return $rows[0]['id'];
        return false;
    }

    function fetch( $id, $asObject = true, $version = EZ_CLASS_VERSION_STATUS_DEFINED, $user_id = false ,$parent_id = null )
    {

        $conds = array( "id" => $id,
                        "version" => $version );

        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;

        $version_sort = "desc";
        if ( $version == EZ_CLASS_VERSION_STATUS_DEFINED )
            $version_sort = "asc";
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );

        if ( count( $rows ) == 0 )
        {
            $contentClass = null;
            return $contentClass;
        }

        $row =& $rows[0];
        $row["version_count"] = count( $rows );

        if ( $asObject )
            $contentClass = new eZContentClass( $row );
        else
            $contentClass = $row;

        return $contentClass;
    }

    function fetchByRemoteID( $remoteID, $asObject = true, $version = EZ_CLASS_VERSION_STATUS_DEFINED, $user_id = false ,$parent_id = null )
    {
        $conds = array( "remote_id" => $remoteID,
                        "version" => $version );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        $version_sort = "desc";
        if ( $version == EZ_CLASS_VERSION_STATUS_DEFINED )
            $version_sort = "asc";
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );
        if ( count( $rows ) == 0 )
        {
            $contentClass = null;
            return $contentClass;
        }

        $row =& $rows[0];
        $row["version_count"] = count( $rows );

        if ( $asObject )
            $contentClass = new eZContentClass( $row );
        else
            $contentClass = $row;

        return $contentClass;
        
    }

    function fetchByIdentifier( $identifier, $asObject = true, $version = EZ_CLASS_VERSION_STATUS_DEFINED, $user_id = false ,$parent_id = null )
    {
        $conds = array( "identifier" => $identifier,
                        "version" => $version );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        $version_sort = "desc";
        if ( $version == EZ_CLASS_VERSION_STATUS_DEFINED )
            $version_sort = "asc";
        $rows = eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                      null,
                                                      $conds,
                                                      array( "version" => $version_sort ),
                                                      array( "offset" => 0,
                                                             "length" => 2 ),
                                                      false );
        if ( count( $rows ) == 0 )
        {
            $contentClass = null;
            return $contentClass;
        }

        $row =& $rows[0];
        $row["version_count"] = count( $rows );

        if ( $asObject )
            $contentClass = new eZContentClass( $row );
        else
            $contentClass = $row;

        return $contentClass;
    }

    /*!
     \static
    */
    function fetchList( $version = EZ_CLASS_VERSION_STATUS_DEFINED, $asObject = true, $user_id = false,
                         $sorts = null, $fields = null, $classFilter = false, $limit = null )
    {
        $conds = array();
        if ( is_numeric( $version ) )
            $conds["version"] = $version;
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        if ( $classFilter )
        {
            $classIDCount = 0;
            $classIdentifierCount = 0;

            $classIDFilter = array();
            $classIdentifierFilter = array();
            foreach ( $classFilter as $classType )
            {
                if ( is_numeric( $classType ) )
                {
                    $classIDFilter[] = $classType;
                    $classIDCount++;
                }
                else
                {
                    $classIdentifierFilter[] = $classType;
                    $classIdentifierCount++;
                }
            }

            if ( $classIDCount > 1 )
                $conds['id'] = array( $classIDFilter );
            else if ( $classIDCount == 1 )
                $conds['id'] = $classIDFilter[0];
            if ( $classIdentifierCount > 1 )
                $conds['identifier'] = array( $classIdentifierFilter );
            else if ( $classIdentifierCount == 1 )
                $conds['identifier'] = $classIdentifierFilter[0];
        }

        return eZPersistentObject::fetchObjectList( eZContentClass::definition(),
                                                            $fields,
                                                            $conds,
                                                            $sorts,
                                                            $limit,
                                                            $asObject );
    }

    /*!
     Returns all attributes as an associative array with the key taken from the attribute identifier.
    */
    function &dataMap()
    {
        $map =& $this->DataMap[$this->Version];
        if ( !isset( $map ) )
        {
            $map = array();
            $attributes = $this->fetchAttributes( false, true, $this->Version );
            foreach ( array_keys( $attributes ) as $attributeKey )
            {
                $attribute =& $attributes[$attributeKey];
                $map[$attribute->attribute( 'identifier' )] =& $attribute;
            }
        }
        return $map;
    }

    function &fetchAttributes( $id = false, $asObject = true, $version = EZ_CLASS_VERSION_STATUS_DEFINED )
    {
        if ( $id === false )
        {
            if ( isset( $this ) and
                 get_class( $this ) == "ezcontentclass" )
            {
                $id = $this->ID;
                $version = $this->Version;
            }
            else
            {
                $attributes = null;
                return $attributes;
            }
        }

        $filteredList =& eZContentClassAttribute::fetchFilteredList( array( "contentclass_id" => $id,
                                                                            "version" => $version ),
                                                                     $asObject );
        return $filteredList;
    }

    /*!
     Fetch class attribute by identifier, return null if none exist.

     \param attribute identifier.

     \return Class Attribute, null if none matched
    */
    function &fetchAttributeByIdentifier( $identifier, $asObject = true )
    {
        $attributeArray =& eZContentClassAttribute::fetchFilteredList( array( 'contentclass_id' => $this->ID,
                                                                              'version' => $this->Version,
                                                                              'identifier' => $identifier ), $asObject );
        if ( count( $attributeArray ) > 0 )
            return $attributeArray[0];
        $retValue = null;
        return $retValue;
    }

    function fetchSearchableAttributes( $id = false, $asObject = true, $version = EZ_CLASS_VERSION_STATUS_DEFINED )
    {
        if ( $id === false )
        {
            if ( isset( $this ) and
                 get_class( $this ) == "ezcontentclass" )
            {
                $id = $this->ID;
                $version = $this->Version;
            }
            else
                return null;
        }

        return eZContentClassAttribute::fetchFilteredList( array( "contentclass_id" => $id,
                                                                  "is_searchable" => 1,
                                                                  "version" => $version ), $asObject );
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &versionStatus()
    {

        if ( $this->VersionCount == 1 )
        {
            if ( $this->Version == EZ_CLASS_VERSION_STATUS_TEMPORARY )
                $status = EZ_CLASS_VERSION_STATUS_TEMPORARY;
            else
                $status = EZ_CLASS_VERSION_STATUS_DEFINED;
        }
        else
            $status = EZ_CLASS_VERSION_STATUS_MODIFED;
        return $status;
    }

    /*!
     \deprecated
     \return The version count for the class if has been determined.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &versionCount()
    {
        return $this->VersionCount;
    }

    /*!
     Will generate a name for the content object based on the class
     settings for content object.
    */
    function contentObjectName( &$contentObject, $version = false, $translation = false )
    {

        $contentObjectName = $this->ContentObjectName;
        $dataMap =& $contentObject->fetchDataMap( $version, $translation );

        eZDebugSetting::writeDebug( 'kernel-content-class', $dataMap, "data map" );
        preg_match_all( "/[<|\|](\(.+\))[\||>]/U",
                        $contentObjectName,
                        $subTagMatchArray );

        $i = 0;
        $tmpTagResultArray = array();
        foreach ( $subTagMatchArray[1]  as $subTag )
        {
            $tmpTag = 'tmptag' . $i;

            $contentObjectName = str_replace( $subTag, $tmpTag, $contentObjectName );

            $subTag = substr( $subTag, 1,strlen($subTag) - 2 );
            $tmpTagResultArray[$tmpTag] = eZContentClass::buildContentObjectName( $subTag, $dataMap );
            $i++;
        }
        $contentObjectName = eZContentClass::buildContentObjectName( $contentObjectName, $dataMap, $tmpTagResultArray );
        return $contentObjectName;
    }

    /*!
     Generates a name for the content object based on the content object name pattern
     and data map of an object.
    */
    function buildContentObjectName( &$contentObjectName, $dataMap, $tmpTags = false )
    {

        preg_match_all( "|<[^>]+>|U",
                        $contentObjectName,
                        $tagMatchArray );

        foreach ( $tagMatchArray[0] as $tag )
        {
            $tagName = str_replace( "<", "", $tag );
            $tagName = str_replace( ">", "", $tagName );

            $tagParts = explode( '|', $tagName );

            $namePart = "";
            foreach ( $tagParts as $name )
            {
                // get the value of the attribute to use in name
                if ( isset( $dataMap[$name] ) )
                {
                    $namePart = $dataMap[$name]->title();
                    if ( $namePart != "" )
                        break;
                }
                elseif ( $tmpTags && isset( $tmpTags[$name] ) && $tmpTags[$name] != '' )
                {
                    $namePart = $tmpTags[$name];
                    break;
                }

            }

            // replace tag with object name part
            $contentObjectName = str_replace( $tag, $namePart, $contentObjectName );
        }
        return $contentObjectName;
    }

    /*!
     \return will return the number of objects published by this class.
    */
    function &objectCount()
    {
        $db =& eZDB::instance();

        $countRow = $db->arrayQuery( 'SELECT count(*) AS count FROM ezcontentobject '.
                                     'WHERE contentclass_id='.$this->ID ." and status = " . EZ_CONTENT_OBJECT_STATUS_PUBLISHED );

        return $countRow[0]['count'];
    }

    /// \privatesection
    var $ID;
    var $Name;
    var $Identifier;
    var $ContentObjectName;
    var $Version;
    var $VersionCount;
    var $CreatorID;
    var $ModifierID;
    var $Created;
    var $Modified;
    var $InGroups;
    var $AllGroups;
    var $IsContainer;
}

?>
