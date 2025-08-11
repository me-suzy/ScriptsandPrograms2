<?php
//
// Definition of eZContentCacheManager class
//
// Created on: <23-Sep-2004 12:52:38 jb>
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

/*! \file ezcontentcachemanager.php
*/

/*!
  \class eZContentCacheManager ezcontentcachemanager.php
  \brief Figures out relations between objects, nodes and classes for cache management

  This class works together with eZContentCache to manage the cache files
  for content viewing. This class takes care of finding out the relationship
  and then passes a list of nodes to eZContentCache which does the actual
  clearing.

  The manager uses special rules in 'viewcache.ini' to figure relationships.
  \sa eZContentCache
*/

// Clear cache types
define( 'EZ_VCSC_CLEAR_NODE_CACHE'      , 1 );
define( 'EZ_VCSC_CLEAR_PARENT_CACHE'    , 2 );
define( 'EZ_VCSC_CLEAR_RELATING_CACHE'  , 4 );
define( 'EZ_VCSC_CLEAR_KEYWORD_CACHE'   , 8 );
define( 'EZ_VCSC_CLEAR_ALL_CACHE'       , 15 );

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZContentCacheManager
{
    /*!
     \note Not used, all methods are static
    */
    function eZContentCacheManager()
    {
    }

    /*!
     \static
     Appends parent nodes ids of \a $object to \a $nodeIDList array.
     \param $versionNum The version of the object to use or \c true for current version
     \param[out] $nodeIDList Array with node IDs
    */
    function appendParentNodeIDs( &$object, $versionNum, &$nodeIDList )
    {
        $parentNodes =& $object->parentNodes( $versionNum );
        foreach ( array_keys( $parentNodes ) as $parentNodeKey )
        {
            $parentNode =& $parentNodes[$parentNodeKey];
            if ( is_object ( $parentNode ) )
            {
                $nodeIDList[] = $parentNode->attribute( 'node_id' );
            }
        }
    }

    /*!
     \static
     Appends nodes ids from \a $nodeList list to \a $nodeIDList
     \param[out] $nodeIDList Array with node IDs
    */
    function appendNodeIDs( &$nodeList, &$nodeIDList )
    {
        foreach ( array_keys( $nodeList ) as $nodeKey )
        {
            $assignedNode =& $nodeList[$nodeKey];
            $nodeIDList[] = $assignedNode->attribute( 'node_id' );
        }
    }

    /*!
     \static
     Goes through all content nodes in \a $nodeList and extracts the \c 'path_string'.
     \return An array with \c 'path_string' information.
    */
    function &fetchNodePathString( &$nodeList )
    {
        $pathList = array();
        foreach ( array_keys( $nodeList ) as $nodeKey )
        {
            $node =& $nodeList[$nodeKey];
            $pathList[] = $node->attribute( 'path_string' );
        }
        return $pathList;
    }

    /*!
     \static
     Find all content objects that relates \a $object and appends
     their node IDs to \a $nodeIDList.
     \param[out] $nodeIDList Array with node IDs
    */
    function appendRelatingNodeIDs( &$object, &$nodeIDList )
    {
        $normalRelated =& $object->relatedContentObjectArray();
        $reversedRelated =& $object->contentObjectListRelatingThis();

        $relatedObjects = array_merge( $normalRelated, $reversedRelated );
        foreach ( array_keys( $relatedObjects ) as $relatedObjectKey )
        {
            $relatedObject =& $relatedObjects[$relatedObjectKey];
            $assignedNodes =& $relatedObject->assignedNodes( false );
            foreach ( array_keys( $assignedNodes ) as $assignedNodeKey )
            {
                $assignedNode =& $assignedNodes[$assignedNodeKey];
                $nodeIDList[] = $assignedNode['node_id'];
            }
        }
    }

    /*!
     \static
     Appends node ids of objects with the same keyword(s) as \a $object to \a $nodeIDList array.
     \param $versionNum The version of the object to use or \c true for current version
     \param[out] $nodeIDList Array with node IDs
    */
    function appendKeywordNodeIDs( &$object, $versionNum, &$nodeIDList )
    {
        if ( $versionNum === true )
            $versionNum = false;
        $keywordArray = array();
        $attributes =& $object->contentObjectAttributes( true, $versionNum );
        foreach ( array_keys( $attributes ) as $key )  // Looking for ezkeyword attributes
        {
            if ( get_class( $attributes[$key] ) == 'ezcontentobjectattribute' and
                 $attributes[$key]->attribute( 'data_type_string' ) == 'ezkeyword' )  // Found one
            {
                $keywordObject =& $attributes[$key]->content();
                if ( get_class( $keywordObject ) == 'ezkeyword' )
                {
                    foreach ( $keywordObject->attribute( 'keywords' ) as $keyword )
                    {
                        $keywordArray[] = $keyword;
                    }
                }
            }
        }

        // Find all nodes that have the given keywords
        if ( count( $keywordArray ) > 0 )
        {
            $keywordString = implode( "', '", $keywordArray );
            include_once( 'lib/ezdb/classes/ezdb.php' );
            $db = eZDB::instance();
            $keywordString = "'".$db->escapeString( $keyword )."'";
            $rows = $db->arrayQuery( "SELECT DISTINCT ezcontentobject_tree.node_id
                                       FROM
                                         ezcontentobject_tree,
                                         ezcontentobject_attribute,
                                         ezkeyword_attribute_link,
                                         ezkeyword
                                       WHERE
                                         ezcontentobject_tree.contentobject_id = ezcontentobject_attribute.contentobject_id AND
                                         ezcontentobject_attribute.id = ezkeyword_attribute_link.objectattribute_id AND
                                         ezkeyword_attribute_link.keyword_id = ezkeyword.id AND
                                         ezkeyword.keyword IN ( $keywordString )" );

            foreach ( $rows as $row )
            {
                $nodeIDList[] = $row['node_id'];
            }
        }
    }

    /*
     \static
     Reads 'viewcache.ini' file and determines relation between
     \a $classID and another class.

     \return An associative array with information on the class, containsL:
             - dependent_class_identifier - The class identifier of objects that depend on this class
             - max_parents - The maxium number of parent nodes to check, or \c 0 for no limit
             - clear_cache_type - Bitfield of clear types, see nodeListForObject() for more details
             - object_filter - Array with object IDs, if there are entries only these objects should be checked.
    */
    function dependencyInfo( $classID, $ignoreINISettings = false )
    {
        $ini =& eZINI::instance( 'viewcache.ini' );
        $info = false;

        if ( $ignoreINISettings || $ini->variable( 'ViewCacheSettings', 'SmartCacheClear' ) == 'enabled' )
        {
            if ( $ini->hasGroup( $classID ) )
            {
                $info = array();
                $info['dependent_class_identifier'] = $ini->variable( $classID, 'DependentClassIdentifier' );

                if ( $ini->hasVariable( $classID, 'MaxParents' ) )
                    $info['max_parents'] = $ini->variable( $classID, 'MaxParents' );
                else
                    $info['max_parents'] = 0;

                $info['clear_cache_type'] = 0;
                if ( $ini->hasVariable( $classID, 'ClearCacheMethod' ) )
                {
                    $type = $ini->variable( $classID, 'ClearCacheMethod' );

                    if ( $type == 'clear_all_caches' )
                    {
                        $info['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
                    }
                    else
                    {
                        if ( $type == 'clear_object_caches_only' ||
                             $type == 'clear_object_and_parent_nodes_caches' ||
                             $type == 'clear_object_and_relating_objects_caches' )
                        {
                            $info['clear_cache_type'] |= EZ_VCSC_CLEAR_NODE_CACHE;
                        }

                        if ( $type == 'clear_object_and_parent_nodes_caches' ||
                             $type == 'clear_parent_nodes_caches_only' ||
                             $type == 'clear_parent_nodes_and_relating_caches' )
                        {
                            $info['clear_cache_type'] |= EZ_VCSC_CLEAR_PARENT_CACHE;
                        }

                        if ( $type == 'clear_object_and_relating_objects_caches' ||
                             $type == 'clear_parent_nodes_and_relating_caches' ||
                             $type == 'clear_relating_caches_only' )
                        {
                            $info['clear_cache_type'] |= EZ_VCSC_CLEAR_RELATING_CACHE;
                        }

                        if ( $type == 'clear_keyword_caches_only' )
                        {
                            $info['clear_cache_type'] |= EZ_VCSC_CLEAR_KEYWORD_CACHE;
                        }
                    }
                }
                else
                {
                    $info['clear_cache_type'] = EZ_VCSC_CLEAR_ALL_CACHE;
                }

                $info['object_filter'] = array();
                if ( $ini->hasVariable( $classID, 'ObjectFilter' ) )
                {
                    $info['object_filter'] = $ini->variable( $classID, 'ObjectFilter' );
                }
            }
        }
        return $info;
    }

    /*!
     \static
     Use \a $clearCacheType to include different kind of nodes( parent, relating, etc ).
     If \a $versionNum is true, then current version will be used.

     \param $contentObject Current content object that is checked.
     \param $versionNum The version of the object to use or \c true for current version
     \param $clearCacheType Bit field which controls the the extra nodes to include,
                            use bitwise or with one of these defines:
                            - EZ_VCSC_CLEAR_NODE_CACHE - Clear the nodes of the object
                            - EZ_VCSC_CLEAR_PARENT_CACHE - Clear the parent nodes of the object
                            - EZ_VCSC_CLEAR_RELATING_CACHE - Clear nodes of objects that relate this object
                            - EZ_VCSC_CLEAR_KEYWORD_CACHE - Clear nodes of objects that have the same keyword as this object
                            - EZ_VCSC_CLEAR_ALL_CACHE - Enables all of the above
     \param[out] $nodeList An array with node IDs that are affected by the current object change.

     \note This function is recursive.
    */
    function nodeListForObject( &$contentObject, $versionNum, $clearCacheType, &$nodeList )
    {
        $assignedNodes =& $contentObject->assignedNodes();

        if ( $clearCacheType & EZ_VCSC_CLEAR_NODE_CACHE )
        {
            eZContentCacheManager::appendNodeIDs( $assignedNodes, $nodeList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_PARENT_CACHE )
        {
            eZContentCacheManager::appendParentNodeIDs( $contentObject, $versionNum, $nodeList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_RELATING_CACHE )
        {
            eZContentCacheManager::appendRelatingNodeIDs( $contentObject, $nodeList );
        }

        if ( $clearCacheType & EZ_VCSC_CLEAR_KEYWORD_CACHE )
        {
            eZContentCacheManager::appendKeywordNodeIDs( $contentObject, $versionNum, $nodeList );
        }

        // determine if $contentObject has dependent objects for which cache should be cleared too.
        $objectClassIdentifier =  $contentObject->attribute( 'class_identifier' );
        $dependentClassInfo = eZContentCacheManager::dependencyInfo( $objectClassIdentifier );

        if ( isset( $dependentClassInfo['dependent_class_identifier'] ) )
        {
            // getting 'path_string's for all locations.
            $nodePathList =& eZContentCacheManager::fetchNodePathString( $assignedNodes );

            foreach ( $nodePathList as $nodePath )
            {
                // getting class identifier and node ID for each node in the $nodePath.
                $nodeInfoList =& eZContentObjectTreeNode::fetchClassIdentifierListByPathString( $nodePath, false );

                $step = 0;
                $maxParents = $dependentClassInfo['max_parents'];
                $dependentClassIdentifiers = $dependentClassInfo['dependent_class_identifier'];
                $smartClearType = $dependentClassInfo['clear_cache_type'];

                if ( $maxParents > 0 )
                {
                    // need to reverse $nodeInfoList if $maxParents is used.
                    // if offset is zero then we will loop through all elements in $nodeInfoList. So,
                    // array_reverse don't need.

                    $nodeInfoList = array_reverse( $nodeInfoList );
                }

                // for each node in $nodeInfoList determine if this node belongs to $dependentClassIdentifiers. If
                // so then clear cache for this node.
                foreach ( $nodeInfoList as $item )
                {
                    if ( in_array( $item['class_identifier'], $dependentClassIdentifiers ) )
                    {
                        $node = eZContentObjectTreeNode::fetch( $item['node_id'] );
                        $object =& $node->attribute( 'object' );

                        if ( count( $dependentClassInfo['object_filter'] ) > 0 )
                        {
                            foreach ( $dependentClassInfo['object_filter'] as $objectIDFilter )
                            {
                                if ( $objectIDFilter == $object->attribute( 'id' ) )
                                {
                                    eZContentCacheManager::nodeListForObject( $object, true, $smartClearType, $nodeList );
                                    break;
                                }
                            }
                        }
                        else
                        {
                            eZContentCacheManager::nodeListForObject( $object, true, $smartClearType, $nodeList );
                        }
                    }

                    // if we reached $maxParents then break
                    if ( ++$step == $maxParents )
                    {
                        break;
                    }
                }
            }
        }
    }

    /*!
     \static
     Figures out all nodes that are affected by the change of object \a $objectID.
     This involves finding all nodes, parent nodes and nodes of objects
     that relate this object.
     The 'viewcache.ini' file is also checked to see if some special content classes
     has dependencies to the current object, if this is true extra nodes might be
     included.

     \param $versionNum The version of the object to use or \c true for current version
     \param $additionalNodeList An array with node IDs to add to clear list,
                                or \c false for no additional nodes.
     \return An array with node IDs that must have their viewcaches cleared.
    */
    function &nodeList( $objectID, $versionNum )
    {
        $nodeList = array();

        $object =& eZContentObject::fetch( $objectID );
        if ( !$object )
        {
            $nodeList = false;
            return $nodeList;
        }

        eZContentCacheManager::nodeListForObject( $object, $versionNum, EZ_VCSC_CLEAR_ALL_CACHE, $nodeList );

        return $nodeList;
    }

    /*!
     \static
     Depreciated. Use 'clearObjectViewCache' instead
    */
    function clearViewCache( $objectID, $versionNum = true , $additionalNodeList = false )
    {
        eZDebug::writeWarning( "'clearViewCache' function was depreciated. Use 'clearObjectViewCache' instead", 'eZContentCacheManager::clearViewCache' );
        eZContentCacheManager::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
    }

    /*!
     \static
     Clears view caches of nodes, parent nodes and relating nodes
     of content objects with id \a $objectID.
     It will use 'viewcache.ini' to determine additional nodes.

     \param $versionNum The version of the object to use or \c true for current version
     \param $additionalNodeList An array with node IDs to add to clear list,
                                or \c false for no additional nodes.
    */
    function clearObjectViewCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        $nodeList =& eZContentCacheManager::nodeList( $objectID, $versionNum );

        if ( $nodeList === false and !is_array( $additionalNodeList ) )
            return false;

        if ( is_array( $additionalNodeList ) )
        {
            array_splice( $nodeList, count( $nodeList ), 0, $additionalNodeList );
        }

        eZDebugSetting::writeDebug( 'kernel-content-edit', count( $nodeList ), "count in nodeList" );

        $ini =& eZINI::instance();
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            include_once( 'kernel/classes/ezstaticcache.php' );
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $staticCache = new eZStaticCache();
            $staticCache->generateAlwaysUpdatedCache();
            $staticCache->generateNodeListCache( eZContentObjectTreeNode::fetchAliasesFromNodeList( $nodeList ) );
        }

        include_once( 'kernel/classes/ezcontentcache.php' );

        eZDebug::accumulatorStart( 'node_cleanup', '', 'Node cleanup' );

        eZContentObject::expireComplexViewModeCache();
        $cleanupValue = eZContentCache::calculateCleanupValue( count( $nodeList ) );

        if ( eZContentCache::inCleanupThresholdRange( $cleanupValue ) )
            eZContentCache::cleanup( $nodeList );
        else
            eZContentObject::expireAllViewCache();

        eZDebug::accumulatorStop( 'node_cleanup' );
        return true;
    }

    /*!
     \static
     Clears view cache for specified object.
     Checks 'ViewCaching' ini setting to determine whether cache is enabled or not.
    */
    function clearObjectViewCacheIfNeeded( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'ContentSettings', 'ViewCaching' ) === 'enabled' )
            eZContentCacheManager::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
    }

    /*!
     \static
     Clears template-block cache and template-block with subtree_expiry parameter caches for specified object.
     Checks 'TemplateCache' ini setting to determine whether cache is enabled or not.
     If $objectID is \c false all template block caches will be cleared.
    */
    function clearTemplateBlockCacheIfNeeded( $objectID )
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) === 'enabled' )
            eZContentCacheManager::clearTemplateBlockCache( $objectID );
    }

    /*!
     \static
     Clears template-block cache and template-block with subtree_expiry parameter caches for specified object
     without checking 'TemplateCache' ini setting. If $objectID is \c false all template block caches will be cleared.
    */
    function clearTemplateBlockCache( $objectID )
    {
        // ordinary template block cache
        eZContentObject::expireTemplateBlockCache();

        // subtree template block cache
        $nodeList = false;
        $object = false;
        if ( $objectID )
            $object = eZContentObject::fetch( $objectID );
        if ( $object )
            $nodeList =& $object->assignedNodes();

        include_once( 'kernel/classes/ezsubtreecache.php' );
        eZSubtreeCache::cleanup( $nodeList );
    }

    /*!
     \static
     Generates the related viewcaches (PreGeneration) for the content object.
     It will only do this if [ContentSettings]/PreViewCache in site.ini is enabled.

     \param $objectID The ID of the content object to generate caches for.
    */
    function generateObjectViewCache( $objectID )
    {
        // Generate the view cache
        $ini =& eZINI::instance();
        $object = eZContentObject::fetch( $objectID );
        $user =& eZUser::currentUser();

        include_once( 'kernel/classes/eznodeviewfunctions.php' );
        eZDebug::accumulatorStart( 'generate_cache', '', 'Generating view cache' );
        if ( $ini->variable( 'ContentSettings', 'PreViewCache' ) == 'enabled' )
        {
            $preCacheSiteaccessArray = $ini->variable( 'ContentSettings', 'PreCacheSiteaccessArray' );

            $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];

            // This is the default view parameters for content/view
            $viewParameters = array( 'offset' => 0,
                                     'year' => false,
                                     'month' => false,
                                     'day' => false );

            foreach ( $preCacheSiteaccessArray as $changeToSiteAccess )
            {
                $GLOBALS['eZCurrentAccess']['name'] = $changeToSiteAccess;

                if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
                {
                    eZSys::clearAccessPath();
                    eZSys::addAccessPath( $changeToSiteAccess );
                }

                include_once( 'kernel/common/template.php' );
                $tpl =& templateInit();
                $res =& eZTemplateDesignResource::instance();

                // Get the sitedesign and cached view preferences for this siteaccess
                $siteini = eZINI::instance( 'site.ini', 'settings', null, null, false );
                $siteini->prependOverrideDir( "siteaccess/$changeToSiteAccess", false, 'siteaccess' );
                $siteini->loadCache();
                $designSetting = $siteini->variable( "DesignSettings", "SiteDesign" );
                $cachedViewPreferences = $siteini->variable( 'ContentSettings', 'CachedViewPreferences' );
                $res->setDesignSetting( $designSetting, 'site' );

                $res->setOverrideAccess( $changeToSiteAccess );

                $language = false; // Needs to be specified if you want to generate the cache for a specific language
                $viewMode = 'full';

                $assignedNodes =& $object->assignedNodes();
                $assignedNodes_keys = array_keys( $assignedNodes );
                foreach ( $assignedNodes_keys as $key )
                {
                    $node =& $assignedNodes[$key];

                    // We want to generate the cache for the specified user
                    $previewCacheUsers = $ini->variable( 'ContentSettings', 'PreviewCacheUsers' );
                    foreach ( $previewCacheUsers as $previewCacheUserID )
                    {
                        // If the text is 'anon' we need to fetch the Anonymous user ID.
                        if ( $previewCacheUserID === 'anonymous' )
                        {
                            $previewCacheUserID = $siteini->variable( "UserSettings", "AnonymousUserID" );
                            $previewCacheUser = eZUser::fetch( $previewCacheUserID  );
                        }
                        else if ( $previewCacheUserID === 'current' )
                        {
                            $previewCacheUser =& $user;
                        }
                        else
                        {
                            $previewCacheUser = eZUser::fetch( $previewCacheUserID  );
                        }
                        if ( !$previewCacheUser )
                            continue;

                        // Before we generate the view cache we must change the currently logged in user to $previewCacheUser
                        // If not the templates might read in wrong personalized data (preferences etc.)
                        $previewCacheUser->setCurrentlyLoggedInUser( $previewCacheUser, $previewCacheUser->attribute( 'contentobject_id' ) );

                        // Cache the current node
                        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $previewCacheUser, $node->attribute( 'node_id' ), 0, false, $language, $viewMode, $viewParameters, $cachedViewPreferences );
                        $tmpRes =& eZNodeviewfunctions::generateNodeView( $tpl, $node, $node->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true );

                        // Cache the parent node
                        $parentNode =& $node->attribute( 'parent' );
                        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $previewCacheUser, $parentNode->attribute( 'node_id' ), 0, false, $language, $viewMode, $viewParameters, $cachedViewPreferences );
                        $tmpRes =& eZNodeviewfunctions::generateNodeView( $tpl, $parentNode, $parentNode->attribute( 'object' ), $language, $viewMode, 0, $cacheFileArray['cache_dir'], $cacheFileArray['cache_path'], true );
                    }
                }
            }
            // Restore the old user as the current one
            $user->setCurrentlyLoggedInUser( $user, $user->attribute( 'contentobject_id' ) );

            $GLOBALS['eZCurrentAccess']['name'] = $currentSiteAccess;
            $res->setDesignSetting( $currentSiteAccess, 'site' );
            $res->setOverrideAccess( false );
            if ( $GLOBALS['eZCurrentAccess']['type'] == EZ_ACCESS_TYPE_URI )
            {
                eZSys::clearAccessPath();
                eZSys::addAccessPath( $currentSiteAccess );
            }
        }

        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            include_once( 'kernel/classes/ezstaticcache.php' );
            include_once( 'kernel/classes/ezcontentcachemanager.php' );
            $staticCache = new eZStaticCache();

            $viewCacheINI =& eZINI::instance( 'viewcache.ini' );
            if ( $viewCacheINI->variable( 'ViewCacheSettings', 'SmartCacheClear' ) == 'enabled' )
            {
                eZContentCacheManager::nodeListForObject( $object, true, EZ_VCSC_CLEAR_ALL_CACHE, $nodes);
            }
            else
            {
                eZContentCacheManager::nodeListForObject( $object, true, EZ_VCSC_CLEAR_NODE_CACHE | EZ_VCSC_CLEAR_PARENT_CACHE, $nodes);
            }
            foreach ( $nodes as $nodeID )
            {
                $aNode = eZContentObjectTreeNode::fetch( $nodeID );
                $staticCache->cacheURL( "/" . $aNode->urlAlias(), $nodeID );
            }
            $staticCache->generateAlwaysUpdatedCache();
        }

        eZDebug::accumulatorStop( 'generate_cache' );
    }

    /*!
     \static
     Clears content cache for specified object: view cache, template-block cache, template-block with subtree_expiry parameter cache.
     Checks appropriate ini settings to determine whether caches are enabled or not.
    */
    function clearContentCacheIfNeeded( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        eZContentCacheManager::clearObjectViewCacheIfNeeded( $objectID, $versionNum, $additionalNodeList );
        eZContentCacheManager::clearTemplateBlockCacheIfNeeded( $objectID );

        eZDebug::accumulatorStop( 'check_cache' );
        return true;
    }

    /*!
     \static
     Clears content cache for specified object: view cache, template-block cache, template-block with subtree_expiry parameter cache
     without checking of ini settings.
    */
    function clearContentCache( $objectID, $versionNum = true, $additionalNodeList = false )
    {
        eZDebug::accumulatorStart( 'check_cache', '', 'Check cache' );

        eZContentCacheManager::clearObjectViewCache( $objectID, $versionNum, $additionalNodeList );
        eZContentCacheManager::clearTemplateBlockCache( $objectID );

        eZDebug::accumulatorStop( 'check_cache' );
        return true;
    }

    /*!
     \static
     Clears all content cache: view cache, template-block cache, template-block with subtree_expiry parameter cache.
    */
    function clearAllContentCache( $ignoreINISettings = false )
    {
        if ( !$ignoreINISettings )
        {
            $ini = eZINI::instance();
            $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) === 'enabled' );
            $templateCacheEnabled = ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) === 'enabled' );
        }
        else
        {
            $viewCacheEnabled = true;
            $templateCacheEnabled = true;
        }

        if ( $viewCacheEnabled || $templateCacheEnabled )
        {
            // view cache and/or ordinary template block cache
            eZContentObject::expireAllCache();

            // subtree template block caches
            if ( $templateCacheEnabled )
            {
                include_once( 'kernel/classes/ezsubtreecache.php' );
                eZSubtreeCache::cleanupAll();
            }
        }
    }
}

?>
