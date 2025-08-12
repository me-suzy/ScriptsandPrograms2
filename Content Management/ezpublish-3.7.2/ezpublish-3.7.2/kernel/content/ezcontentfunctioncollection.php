<?php
//
// Definition of eZContentFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZContentFunctionCollection ezcontentfunctioncollection.php
  \brief The class eZContentFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZContentFunctionCollection
{
    /*!
     Constructor
    */
    function eZContentFunctionCollection()
    {
    }

    function fetchContentObject( $objectID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $contentObject =& eZContentObject::fetch( $objectID );
        if ( $contentObject === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => $contentObject );
        }

        return $result;
    }

    function fetchContentVersion( $objectID, $versionID )
    {
        include_once( 'kernel/classes/ezcontentobjectversion.php' );
        $contentVersion = eZContentObjectVersion::fetchVersion( $versionID, $objectID );
        if ( !$contentVersion )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => $contentVersion );
        }

        return $result;
    }

    function fetchContentNode( $nodeID, $nodePath )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $contentNode = null;
        if ( $nodeID )
        {
            $contentNode = eZContentObjectTreeNode::fetch( $nodeID );
        }
        else if ( $nodePath )
        {
            $contentNode = eZContentObjectTreeNode::fetchByURLPath( $nodePath );
        }
        if ( $contentNode === null )
        {
            $retVal = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $retVal = array( 'result' => $contentNode );
        }

        return $retVal;
    }

    function fetchNonTranslationList( $objectID, $version )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        include_once( 'kernel/classes/ezcontentobjectversion.php' );
        $version = eZContentObjectVersion::fetchVersion( $version, $objectID );
        if ( !$version )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );

        $nonTranslationList =& $version->nonTranslationList();
        if ( $nonTranslationList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$nonTranslationList );
    }

    function fetchTranslationList()
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $translationList =& eZContentObject::translationList();
        if ( $translationList === null )
        {
            $result =  array( 'error' => array( 'error_type' => 'kernel',
                                                'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$translationList );
        }

        return $result;
    }

    function fetchLocaleList( $withVariations )
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $localeList = eZLocale::localeList( true, $withVariations );
        if ( $localeList === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$localeList );
        }

        return $result;
    }

    function fetchObject( $objectID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $object =& eZContentObject::fetch( $objectID );
        if ( $object === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$object );
        }

        return $result;
    }

    function fetchClass( $classID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        if ( !is_numeric( $classID ) )
            $object = eZContentClass::fetchByIdentifier( $classID );
        else
            $object = eZContentClass::fetch( $classID );
        if ( $object === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$object );
        }

        return $result;
    }

    function fetchClassAttributeList( $classID, $versionID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $objectList =& eZContentClass::fetchAttributes( $classID, true, $versionID );
        if ( $objectList === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$objectList );
        }

        return $result;
    }

    function fetchClassAttribute( $attributeID, $versionID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $attribute =& eZContentClassAttribute::fetch( $attributeID, true, $versionID );
        if ( $attribute === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$attribute );
        }

        return $result;
    }

    function calendar( $parentNodeID, $offset, $limit, $depth, $depthOperator,
                               $classID, $attribute_filter, $extended_attribute_filter, $class_filter_type, $class_filter_array,
                               $groupBy, $mainNodeOnly, $ignoreVisibility, $limitation )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $treeParameters = array( 'Offset' => $offset,
                                 'Limit' => $limit,
                                 'Limitation' => $limitation,
                                 'class_id' => $classID,
                                 'AttributeFilter' => $attribute_filter,
                                 'ExtendedAttributeFilter' => $extended_attribute_filter,
                                 'ClassFilterType' => $class_filter_type,
                                 'ClassFilterArray' => $class_filter_array,
                                 'IgnoreVisibility' => $ignoreVisibility,
                                 'MainNodeOnly' => $mainNodeOnly );
        if ( is_array( $groupBy ) )
        {
            $groupByHash = array( 'field' => $groupBy[0],
                                  'type' => false );
            if ( isset( $groupBy[1] ) )
                $groupByHash['type'] = $groupBy[1];
            $treeParameters['GroupBy'] = $groupByHash;
        }

        if ( $depth !== false )
        {
            $treeParameters['Depth'] = $depth;
            $treeParameters['DepthOperator'] = $depthOperator;
        }

        $children = null;
        if ( is_numeric( $parentNodeID ) )
        {
            $children = eZContentObjectTreeNode::calendar( $treeParameters,
                                                            $parentNodeID );
        }

        if ( $children === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$children );
        }
        return $result;
    }

    function fetchObjectTree( $parentNodeID, $sortBy, $onlyTranslated, $language, $offset, $limit, $depth, $depthOperator,
                               $classID, $attribute_filter, $extended_attribute_filter, $class_filter_type, $class_filter_array,
                               $groupBy, $mainNodeOnly, $ignoreVisibility, $limitation, $asObject )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $treeParameters = array( 'Offset' => $offset,
                                 'OnlyTranslated' => $onlyTranslated,
                                 'Language' => $language,
                                 'Limit' => $limit,
                                 'Limitation' => $limitation,
                                 'SortBy' => $sortBy,
                                 'class_id' => $classID,
                                 'AttributeFilter' => $attribute_filter,
                                 'ExtendedAttributeFilter' => $extended_attribute_filter,
                                 'ClassFilterType' => $class_filter_type,
                                 'ClassFilterArray' => $class_filter_array,
                                 'IgnoreVisibility' => $ignoreVisibility,
                                 'MainNodeOnly' => $mainNodeOnly );
        if ( is_array( $groupBy ) )
        {
            $groupByHash = array( 'field' => $groupBy[0],
                                  'type' => false );
            if ( isset( $groupBy[1] ) )
                $groupByHash['type'] = $groupBy[1];
            $treeParameters['GroupBy'] = $groupByHash;
        }
        if ( $asObject !== null )
            $treeParameters['AsObject'] = $asObject;
        if ( $depth !== false )
        {
            $treeParameters['Depth'] = $depth;
            $treeParameters['DepthOperator'] = $depthOperator;
        }

        $children = null;
        if ( is_numeric( $parentNodeID ) or is_array( $parentNodeID ) )
        {
            $children =& eZContentObjectTreeNode::subTree( $treeParameters,
                                                           $parentNodeID );
        }

        if ( $children === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            if ( $asObject === null or $asObject )
                eZContentObject::fillNodeListAttributes( $children );
            $result = array( 'result' => &$children );
        }
        return $result;
    }

    function fetchObjectTreeCount( $parentNodeID, $onlyTranslated, $language, $class_filter_type, $class_filter_array,
                                    $attributeFilter, $depth, $depthOperator,
                                    $ignoreVisibility, $limitation, $mainNodeOnly )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $childrenCount = null;

        if ( is_numeric( $parentNodeID ) or is_array( $parentNodeID ) )
        {
            $childrenCount =& eZContentObjectTreeNode::subTreeCount( array( 'Limitation' => $limitation,
                                                                            'ClassFilterType' => $class_filter_type,
                                                                            'ClassFilterArray' => $class_filter_array,
                                                                            'AttributeFilter' => $attributeFilter,
                                                                            'DepthOperator' => $depthOperator,
                                                                            'Depth' => $depth,
                                                                            'IgnoreVisibility' => $ignoreVisibility,
                                                                            'OnlyTranslated' => $onlyTranslated,
                                                                            'Language' => $language,
                                                                            'MainNodeOnly' => $mainNodeOnly ),
                                                                     $parentNodeID );
        }

        if ( $childrenCount === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => &$childrenCount );
        }
        return $result;
    }

    function fetchContentSearch( $searchText, $subTreeArray, $offset, $limit, $searchTimestamp, $publishDate, $sectionID, $classID, $classAttributeID, $sortArray )
    {
        include_once( "kernel/classes/ezsearch.php" );
        $searchArray =& eZSearch::buildSearchArray();
        $parameters = array();
        if ( $classID !== false )
            $parameters['SearchContentClassID'] = $classID;
        if ( $classAttributeID !== false )
            $parameters['SearchContentClassAttributeID'] = $classAttributeID;
        if ( $sectionID !== false )
            $parameters['SearchSectionID'] = $sectionID;
        if ( $publishDate !== false )
            $parameters['SearchDate'] = $publishDate;
        if ( $sortArray !== false )
            $parameters['SortArray'] = $sortArray;
        $parameters['SearchLimit'] = $limit;
        $parameters['SearchOffset'] = $offset;
        if ( $subTreeArray !== false )
            $parameters['SearchSubTreeArray'] = $subTreeArray;
        if ( $searchTimestamp )
            $parameters['SearchTimestamp'] = $searchTimestamp;
        $searchResult = eZSearch::search( $searchText,
                                          $parameters,
                                          $searchArray );
        return array( 'result' => &$searchResult );
    }

    function fetchTrashObjectCount()
    {
        $trashObjectList = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                                  array(), array( 'status' => EZ_CONTENT_OBJECT_STATUS_ARCHIVED ),
                                                                  array(), null,
                                                                  false,false,
                                                                  array( array( 'operation' => 'count( * )',
                                                                                'name' => 'count' ) ) );
        return array( 'result' => $trashObjectList[0]['count'] );
    }

    function fetchTrashObjectList( $offset, $limit )
    {
        $trashObjectList =  eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                                  null, array( 'status' => EZ_CONTENT_OBJECT_STATUS_ARCHIVED ),
                                                                  null, array( 'length' => $limit, 'offset' => $offset ),
                                                                  true );
        return array( 'result' => &$trashObjectList );
    }

    function fetchDraftVersionList( $offset, $limit )
    {
        $userID = eZUser::currentUserID();
        $draftVersionList =  eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   null, array(  'creator_id' => $userID,
                                                                                 'status' => EZ_VERSION_STATUS_DRAFT ),
                                                                   null, array( 'length' => $limit, 'offset' => $offset ),
                                                                   true );
        return array( 'result' => &$draftVersionList );

    }

    function fetchDraftVersionCount()
    {
        $userID = eZUser::currentUserID();
        $draftVersionList =  eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   array(), array( 'creator_id' => $userID,
                                                                                   'status' => EZ_VERSION_STATUS_DRAFT ),
                                                                   array(), null,
                                                                   false,false,
                                                                   array( array( 'operation' => 'count( * )',
                                                                                 'name' => 'count' ) ) );
        return array( 'result' => $draftVersionList[0]['count'] );
    }

    function fetchPendingList( $offset, $limit )
    {
        $userID = eZUser::currentUserID();
        $pendingList =  eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   null, array(  'creator_id' => $userID,
                                                                                 'status' => EZ_VERSION_STATUS_PENDING ),
                                                                   null, array( 'length' => $limit, 'offset' => $offset ),
                                                                   true );
        return array( 'result' => &$pendingList );

    }

    function fetchPendingCount()
    {
        $userID = eZUser::currentUserID();
        $pendingList =  eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   array(), array( 'creator_id' => $userID,
                                                                                   'status' => EZ_VERSION_STATUS_PENDING ),
                                                                   array(), null,
                                                                   false,false,
                                                                   array( array( 'operation' => 'count( * )',
                                                                                 'name' => 'count' ) ) );
        return array( 'result' => $pendingList[0]['count'] );
    }


    function fetchVersionList( $contentObject, $offset, $limit )
    {
        if ( !is_object( $contentObject ) )
            return array( 'result' => null );
        $versionList =  eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                              null, array(  'contentobject_id' => $contentObject->attribute("id") ),
                                                                   null, array( 'length' => $limit, 'offset' => $offset ),
                                                                   true );
        return array( 'result' => &$versionList );

    }

    function fetchVersionCount( $contentObject )
    {
        if ( !is_object( $contentObject ) )
            return array( 'result' => 0 );
        $versionList =  eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   array(), array( 'contentobject_id' => $contentObject->attribute("id") ),
                                                                   array(), null,
                                                                   false,false,
                                                                   array( array( 'operation' => 'count( * )',
                                                                                 'name' => 'count' ) ) );
        return array( 'result' => $versionList[0]['count'] );
    }

    function canInstantiateClassList( $groupID, $parentNode, $filterType = 'include', $fetchID, $asObject )
    {
        $ClassGroupIDs = false;

        if ( is_numeric( $groupID ) && ( $groupID > 0 ) )
        {
            $ClassGroupIDs = array( $groupID );
        }
        else if( is_array( $groupID ) )
        {
            $ClassGroupIDs = $groupID;
        }

        if ( is_object( $parentNode ) )
        {
            //eZDebug::writeDebug( "can_create_class_list from node " . $parentNode->attribute( 'node_id' ) );
            $classList =& $parentNode->canCreateClassList( $asObject, $filterType == 'include', $ClassGroupIDs, $fetchID );
        }
        else
        {
            //eZDebug::writeDebug( "can_create_class_list for all " );
            include_once( 'kernel/classes/ezcontentclass.php' );
            $classList =& eZContentClass::canInstantiateClassList( $asObject, $filterType == 'include', $ClassGroupIDs, $fetchID );
        }

        return array( 'result' => $classList );
    }

    function canInstantiateClasses( $parentNode )
    {
        if ( is_object( $parentNode ) )
        {
            $contentObject = $parentNode->attribute( 'object' );
            return array( 'result' => $contentObject->attribute( 'can_create' ) );
        }
        include_once( 'kernel/classes/ezcontentclass.php' );
        return array( 'result' => eZContentClass::canInstantiateClasses() );
    }

    function contentobjectAttributes( &$version, $languageCode )
    {
        if ( $languageCode == '' )
        {
            return array( 'result' => $version->contentObjectAttributes( ) );
        }
        else
        {
            return array( 'result' => $version->contentObjectAttributes( $languageCode ) );
        }
    }

    function fetchBookmarks( $offset, $limit )
    {
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        $user =& eZUser::currentUser();
        include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
        return array( 'result' => eZContentBrowseBookmark::fetchListForUser( $user->id(), $offset, $limit ) );
    }

    function fetchRecent()
    {
        $user =& eZUser::currentUser();
        include_once( 'kernel/classes/ezcontentbrowserecent.php' );
        return array( 'result' => eZContentBrowseRecent::fetchListForUser( $user->id() ) );
    }

    function fetchSectionList()
    {
        include_once( 'kernel/classes/ezsection.php' );
        return array( 'result' => eZSection::fetchList() );
    }

    function fetchTipafriendTopList( $offset, $limit )
    {
        include_once( 'kernel/classes/eztipafriendcounter.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $topList =  eZPersistentObject::fetchObjectList( eZTipafriendCounter::definition(),
                                                       null,
                                                       null,
                                                       null,
                                                       array( 'length' => $limit, 'offset' => $offset ),
                                                       true );

        $contentNodeList = array();
        foreach ( array_keys ( $topList ) as $key )
        {
            $nodeID = $topList[$key]->attribute( 'node_id' );
            $contentNode = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $contentNode === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentNodeList[] = $contentNode;
        }
        return array( 'result' => $contentNodeList );
    }

    function fetchMostViewedTopList( $classID, $sectionID, $offset, $limit )
    {
        include_once( 'kernel/classes/ezviewcounter.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $topList = eZViewCounter::fetchTopList( $classID, $sectionID, $offset, $limit );
        $contentNodeList = array();
        foreach ( array_keys ( $topList ) as $key )
        {
            $nodeID = $topList[$key]['node_id'];
            $contentNode = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $contentNode === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentNodeList[] = $contentNode;
        }
        return array( 'result' => $contentNodeList );
    }

    function fetchCollectedInfoCount( $objectAttributeID, $objectID, $value )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        if ( $objectAttributeID )
            $count = eZInformationCollection::fetchCountForAttribute( $objectAttributeID, $value );
        else if ( $objectID )
            $count = eZInformationCollection::fetchCountForObject( $objectID, $value );
        else
            $count = 0;
        return array( 'result' => $count );
    }

    function fetchCollectedInfoCountList( $objectAttributeID )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        $count = eZInformationCollection::fetchCountList( $objectAttributeID );
        return array( 'result' => $count );
    }

    function fetchCollectedInfoCollection( $collectionID, $contentObjectID )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        $collection = false;
        if ( $collectionID )
            $collection = eZInformationCollection::fetch( $collectionID );
        else if ( $contentObjectID )
        {
            $userIdentifier = eZInformationCollection::currentUserIdentifier();
            $collection = eZInformationCollection::fetchByUserIdentifier( $userIdentifier, $contentObjectID );
        }
        return array( 'result' => &$collection );
    }

    function fetchObjectByAttribute( $identifier )
    {
        include_once( 'kernel/classes/ezcontentobjectattribute.php' );
        $contentObjectAttribute = eZContentObjectAttribute::fetchByIdentifier( $identifier );
        if ( $contentObjectAttribute === null )
        {
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        else
        {
            $result = array( 'result' => $contentObjectAttribute->attribute( 'object' ) );
        }
        return $result;
    }

    function fetchObjectCountByUserID( $classID, $userID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $objectCount = eZContentObject::fetchObjectCountByUserID( $classID, $userID );
        return array( 'result' => $objectCount );
    }

    function fetchKeywordCount( $alphabet, $classid )
    {
        $classIDArray = array();
        if ( is_numeric( $classid ) )
        {
            $classIDArray = array( $classid );
        }
        else if ( is_array( $classid ) )
        {
            $classIDArray = $classid;
        }

        $limitationList = array();
        $sqlPermissionCheckingString = eZContentObjectTreeNode::createPermissionCheckingSQLString( eZContentObjectTreeNode::getLimitationList( false ) );

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        if ( $classid != null )
        {
            $classIDString = '(' . implode( ',', $classIDArray ) . ')';
            $query = "SELECT count(*) AS count
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezkeyword.class_id IN $classIDString
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id";
        }
        else
        {
            $query = "SELECT count(*) AS count
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id";
        }

        $keyWords = $db->arrayQuery( $query );

        return array( 'result' => $keyWords[0]['count'] );
    }

    function fetchKeyword( $alphabet, $classid, $offset, $limit )
    {
        $classIDArray = array();
        if ( is_numeric( $classid ) )
        {
            $classIDArray = array( $classid );
        }
        else if ( is_array( $classid ) )
        {
            $classIDArray = $classid;
        }

        $limitationList = array();
        $sqlPermissionCheckingString = "";
        $currentUser =& eZUser::currentUser();
        $accessResult = $currentUser->hasAccessTo( 'content', 'read' );

        if ( $accessResult['accessWord'] == 'limited' && $accessResult['policies'] )
        {
            // make an array of references to policies
            foreach ( array_keys( $accessResult['policies'] ) as $key )
                $limitationList[] =& $accessResult['policies'][$key];

            $sqlParts = array();

            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $hasNodeLimitation = false;

                foreach ( array_keys( $limitationArray ) as $key )
                {
                    switch ( $key )
                    {
                    case 'Class':
                        $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . implode( ',', $limitationArray['Class'] ) . ')';
                        break;
                    case 'Section':
                        $sqlPartPart[] = 'ezcontentobject.section_id in (' . implode( ',', $limitationArray['Section'] ) . ')';
                        break;
                    case 'Owner':
                        eZDebug::writeWarning( $limitationArray, 'System is not configured to check Assigned in objects' );
                        break;
                    case 'Node':
                        $sqlPartPart[] = 'ezcontentobject_tree.node_id in (' . implode( ',', $limitationArray['Node'] ) . ')';
                        $hasNodeLimitation = true;
                        break;
                    case 'Subtree':
                        $pathArray =& $limitationArray['Subtree'];
                        $sqlPartPartPart = array();
                        foreach ( $pathArray as $limitationPathString )
                        {
                            $sqlPartPartPart[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                        }
                        $sqlPartPart[] = implode( ' OR ', $sqlPartPartPart );
                        break;
                    }
                }

                if ( $hasNodeLimitation )
                    $sqlParts[] = implode( ' OR ', $sqlPartPart );
                else
                    $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }

            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
        }

        $db_params = array();
        $db_params["offset"] = $offset;
        $db_params["limit"] = $limit;

        $keywordNodeArray = array();
        $lastKeyword = "";

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        if ( $classIDArray != null )
        {
            $classIDString = '(' . implode( ',', $classIDArray ) . ')';
            $query = "SELECT ezkeyword.keyword,ezcontentobject_tree.node_id
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezkeyword.class_id IN $classIDString
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id ORDER BY ezkeyword.keyword ASC";
        }
        else
        {
            $query = "SELECT ezkeyword.keyword,ezcontentobject_tree.node_id
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id ORDER BY ezkeyword.keyword ASC";
        }

        $keyWords = $db->arrayQuery( $query, $db_params );

        include_once( 'lib/ezi18n/classes/ezchartransform.php' );
        $trans =& eZCharTransform::instance();

        foreach ( array_keys( $keyWords ) as $key )
        {
            $keywordArray =& $keyWords[$key];
            $keyword = $keywordArray['keyword'];
            $nodeID = $keywordArray['node_id'];

            $nodeObject = eZContentObjectTreeNode::fetch( $nodeID );

            if ( $nodeObject != null )
            {
                $keywordLC = $trans->transformByGroup( $keyword, 'lowercase' );
                if ( $lastKeyword == $keywordLC )
                    $keywordNodeArray[] = array( 'keyword' => "", 'link_object' => $nodeObject );
                else
                    $keywordNodeArray[] = array( 'keyword' => $keyword, 'link_object' => $nodeObject );

                $lastKeyword = $keywordLC;
            }
            else
            {
                $lastKeyword = $trans->transformByGroup( $keyword, 'lowercase' );
            }
        }
        return array( 'result' => $keywordNodeArray );
    }

    function fetchSameClassAttributeNodeList( $contentclassattributeID, $value, $datatype )
    {
        if ( $datatype == "int" )
             $type = "data_int";
        else if ( $datatype == "float" )
             $type = "data_float";
        else if ( $datatype == "text" )
             $type = "data_text";
        else
        {
            eZDebug::writeError( "DatatypeString not supported in fetch same_classattribute_node, use int, float or text" );
            return false;
        }
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();
        $resultNodeArray = array();
        $nodeList = $db->arrayQuery( "SELECT ezcontentobject_tree.node_id, ezcontentobject.name, ezcontentobject_tree.parent_node_id
                                            FROM ezcontentobject_tree, ezcontentobject, ezcontentobject_attribute
                                           WHERE ezcontentobject_attribute.$type='$value'
                                             AND ezcontentobject_attribute.contentclassattribute_id='$contentclassattributeID'
                                             AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                                             AND ezcontentobject_attribute.version=ezcontentobject.current_version
                                             AND ezcontentobject_tree.contentobject_version=ezcontentobject.current_version
                                             AND ezcontentobject_tree.contentobject_id=ezcontentobject.id
                                        ORDER BY ezcontentobject.name");

        foreach ( array_keys( $nodeList ) as $key )
        {
            $nodeObject =& $nodeList[$key];
            $nodeID = $nodeObject['node_id'];
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            $resultNodeArray[] = $node;
        }
        return array( 'result' => $resultNodeArray );
    }

    function checkAccess( $access, &$contentObject, $contentClassID, $parentContentClassID )
    {
        if ( get_class( $contentObject ) == 'ezcontentobjecttreenode' )
            $contentObject =& $contentObject->attribute( 'object' );
        if (  $contentClassID !== false and !is_numeric( $contentClassID ) )
        {
            include_once( 'kernel/classes/ezcontentclass.php' );
            $class = eZContentClass::fetchByIdentifier( $contentClassID );
            if ( !$class )
                return array( 'error' => array( 'error_type' => 'kernel',
                                                'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentClassID = $class->attribute( 'id' );
        }
        if ( $access and get_class( $contentObject ) == 'ezcontentobject' )
        {
            $result = $contentObject->checkAccess( $access, $contentClassID, $parentContentClassID );
            return array( 'result' => $result );
        }
    }

    // Fetches all navigation parts as an array
    function fetchNavigationParts()
    {
        include_once( 'kernel/classes/eznavigationpart.php' );
        return array( 'result' => eZNavigationPart::fetchList() );
    }

    // Fetches one navigation parts by identifier
    function fetchNavigationPart( $identifier )
    {
        include_once( 'kernel/classes/eznavigationpart.php' );
        return array( 'result' => eZNavigationPart::fetchPartByIdentifier( $identifier ) );
    }

    // Fetches reverse related objects
    function fetchRelatedObjects( $objectID, $attributeID, $allRelations, $groupByAttribute, $sortBy )
    {
        $params = array();
        if ( $sortBy )
        {
            $params['SortBy'] = $sortBy;
        }

        if ( !$attributeID )
            $attributeID = 0;

        if ( $allRelations )
            $attributeID = false;

        if ( $attributeID && !is_numeric( $attributeID ) )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $attributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $attributeID );
            if ( !$attributeID )
            {
                eZDebug::writeError( "Can't get class attribute ID by identifier" );
                return false;
            }
        }

        $object = eZContentObject::fetch( $objectID );
        include_once( 'kernel/classes/ezcontentobject.php' );
        return array( 'result' => $object->relatedContentObjectList( false, $objectID, $attributeID, $groupByAttribute, $params ) );
    }

    // Fetches count of reverse related objects
    function fetchRelatedObjectsCount( $objectID, $attributeID, $allRelations )
    {
        if ( !$attributeID )
            $attributeID = 0;

        if ( $allRelations )
            $attributeID = false;

        if ( $attributeID && !is_numeric( $attributeID ) )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $attributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $attributeID );
            if ( !$attributeID )
            {
                eZDebug::writeError( "Can't get class attribute ID by identifier" );
                return false;
            }
        }

        $object = eZContentObject::fetch( $objectID );
        include_once( 'kernel/classes/ezcontentobject.php' );
        return array( 'result' => $object->relatedContentObjectCount( false, $objectID, $attributeID ) );
    }

    function fetchReverseRelatedObjects( $objectID, $attributeID, $allRelations, $groupByAttribute, $sortBy )
    {
        $params = array();
        if ( $sortBy )
        {
            $params['SortBy'] = $sortBy;
        }

        if ( !$attributeID )
            $attributeID = 0;

        if ( $allRelations )
            $attributeID = false;

        if ( $attributeID && !is_numeric( $attributeID ) )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $attributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $attributeID );
            if ( !$attributeID )
            {
                eZDebug::writeError( "Can't get class attribute ID by identifier" );
                return false;
            }
        }
        include_once( 'kernel/classes/ezcontentobject.php' );
        return array( 'result' => eZContentObject::reverseRelatedObjectList( false, $objectID, $attributeID, $groupByAttribute, $params ) );
    }

    // Fetches count of reverse related objects
    function fetchReverseRelatedObjectsCount( $objectID, $attributeID, $allRelations )
    {
        if ( !$attributeID )
            $attributeID = 0;

        if ( $allRelations )
            $attributeID = false;

        if ( $attributeID && !is_numeric( $attributeID ) )
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $attributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $attributeID );
            if ( !$attributeID )
            {
                eZDebug::writeError( "Can't get class attribute ID by identifier" );
                return false;
            }
        }
        include_once( 'kernel/classes/ezcontentobject.php' );
        return array( 'result' => eZContentObject::reverseRelatedObjectCount( false, $objectID, $attributeID ) );
    }

}

?>
