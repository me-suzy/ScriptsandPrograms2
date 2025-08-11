<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentclassclassgroup.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );


$Module =& $Params['Module'];
$ClassID = null;
if ( isset( $Params['ClassID'] ) )
    $ClassID = $Params['ClassID'];
$GroupID = null;
if ( isset( $Params['GroupID'] ) )
    $GroupID = $Params['GroupID'];
$GroupName = null;
if ( isset( $Params['GroupName'] ) )
    $GroupName = $Params['GroupName'];
$ClassVersion = null;

switch ( $Params['FunctionName'] )
{
    case 'edit':
    {
    } break;
    default:
    {
        eZDebug::writeError( 'Undefined function: ' . $params['Function'] );
        $Module->setExitStatus( EZ_MODULE_STATUS_FAILED );
        return;
    };
}

$http =& eZHttpTool::instance();
if ( $http->hasPostVariable( 'CancelConflictButton' ) )
{
    $Module->redirectToView( 'grouplist' );
}

if ( is_numeric( $ClassID ) )
{
    $class = eZContentClass::fetch( $ClassID, true, EZ_CLASS_VERSION_STATUS_TEMPORARY );

    // If temporary version does not exist fetch the current and add temperory class to corresponding group
    if ( !is_object( $class ) or $class->attribute( 'id' ) == null )
    {
        $class = eZContentClass::fetch( $ClassID, true, EZ_CLASS_VERSION_STATUS_DEFINED );
        if( is_null( $class ) ) // Class does not exist
        {
            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        $classGroups= eZContentClassClassGroup::fetchGroupList( $ClassID, EZ_CLASS_VERSION_STATUS_DEFINED );
        foreach ( $classGroups as $classGroup )
        {
            $groupID = $classGroup->attribute( 'group_id' );
            $groupName = $classGroup->attribute( 'group_name' );
            $ingroup = eZContentClassClassGroup::create( $ClassID, EZ_CLASS_VERSION_STATUS_TEMPORARY, $groupID, $groupName );
            $ingroup->store();
        }
    }
    else
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $user =& eZUser::currentUser();
        $contentIni =& eZIni::instance( 'content.ini' );
        $timeOut = $contentIni->variable( 'ClassSettings', 'DraftTimeout' );

        if ( $class->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $class->attribute( 'modified' ) + $timeOut > time() )
        {
            include_once( 'kernel/common/template.php' );
            $tpl =& templateInit();

            $res =& eZTemplateDesignResource::instance();
            $res->setKeys( array( array( 'class', $class->attribute( 'id' ) ) ) ); // Class ID
            $tpl->setVariable( 'class', $class );
            $tpl->setVariable( 'lock_timeout', $timeOut );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:class/edit_denied.tpl' );
            $Result['path'] = array( array( 'url' => '/class/grouplist/',
                                            'text' => ezi18n( 'kernel/class', 'Class list' ) ) );
            return $Result;
        }
    }
}
else
{
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( 'contentobject_id' );
    $class = eZContentClass::create( $user_id );
    $class->setAttribute( 'name', ezi18n( 'kernel/class/edit', 'New Class' ) );
    $class->store();
    $ClassID = $class->attribute( 'id' );
    $ClassVersion = $class->attribute( 'version' );
    $ingroup = eZContentClassClassGroup::create( $ClassID, $ClassVersion, $GroupID, $GroupName );
    $ingroup->store();
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID );
    return;
}


$contentClassHasInput = true;
if ( $http->hasPostVariable( 'ContentClassHasInput' ) )
    $contentClassHasInput = $http->postVariable( 'ContentClassHasInput' );

// Find out the group where class is created or edited from.
if ( $http->hasSessionVariable( 'FromGroupID' ) )
{
    $fromGroupID = $http->sessionVariable( 'FromGroupID' );
}
else
{
    $fromGroupID = false;
}
$ClassID = $class->attribute( 'id' );
$ClassVersion = $class->attribute( 'version' );

$validation = array( 'processed' => false,
                     'groups' => array(),
                     'attributes' => array(),
                     'class_errors' => array() );
$unvalidatedAttributes = array();

if ( $http->hasPostVariable( 'DiscardButton' ) )
{
    eZSessionDestroy( $http->sessionVariable( 'CanStoreTicket' ) );
    $http->removeSessionVariable( 'CanStoreTicket' );
    $class->setVersion( EZ_CLASS_VERSION_STATUS_TEMPORARY );
    $class->remove( true, EZ_CLASS_VERSION_STATUS_TEMPORARY );
    eZContentClassClassGroup::removeClassMembers( $ClassID, EZ_CLASS_VERSION_STATUS_TEMPORARY );
    if ( $fromGroupID === false )
    {
        $Module->redirectToView( 'grouplist' );
    }
    else
    {
        $Module->redirectTo( $Module->functionURI( 'classlist' ) . '/' . $fromGroupID . '/' );
    }
    return;
}
if ( $http->hasPostVariable( 'AddGroupButton' ) && $http->hasPostVariable( 'ContentClass_group' ) )
{
    include_once( "kernel/class/ezclassfunctions.php" );
    eZClassFunctions::addGroup( $ClassID, $ClassVersion, $http->postVariable( 'ContentClass_group' ) );
}
if ( $http->hasPostVariable( 'RemoveGroupButton' ) && $http->hasPostVariable( 'group_id_checked' ) )
{
    include_once( "kernel/class/ezclassfunctions.php" );
    if ( !eZClassFunctions::removeGroup( $ClassID, $ClassVersion, $http->postVariable( 'group_id_checked' ) ) )
    {
        $validation['groups'][] = array( 'text' => ezi18n( 'kernel/class', 'You have to have at least one group that the class belongs to!' ) );
        $validation['processed'] = true;
    }
}

// Fetch attributes and definitions
$attributes =& $class->fetchAttributes();

include_once( 'kernel/classes/ezdatatype.php' );
eZDataType::loadAndRegisterAllTypes();
$datatypes =& eZDataType::registeredDataTypes();

$customAction = false;
$customActionAttributeID = null;
// Check for custom actions
if ( $http->hasPostVariable( 'CustomActionButton' ) )
{
    $customActionArray = $http->postVariable( 'CustomActionButton' );
    $customActionString = key( $customActionArray );

    $customActionAttributeID = preg_match( "#^([0-9]+)_(.*)$#", $customActionString, $matchArray );

    $customActionAttributeID = $matchArray[1];
    $customAction = $matchArray[2];
}


// Validate input
$storeActions = array( 'MoveUp',
                       'MoveDown',
                       'StoreButton',
                       'ApplyButton',
                       'NewButton',
                       'CustomActionButton');
$validationRequired = false;
foreach( $storeActions as $storeAction )
{
    if ( $http->hasPostVariable( $storeAction ) )
    {
        $validationRequired = true;
        break;
    }
}

include_once( 'lib/ezutils/classes/ezinputvalidator.php' );
$canStore = true;
$requireFixup = false;
if ( $contentClassHasInput )
{
    if ( $validationRequired )
    {
        foreach ( array_keys( $attributes ) as $key )
        {
            $attribute =& $attributes[$key];
            $dataType =& $attribute->dataType();
            $status = $dataType->validateClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
            if ( $status == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
                $requireFixup = true;
            else if ( $status == EZ_INPUT_VALIDATOR_STATE_INVALID )
            {
                $canStore = false;
                $attributeName = $dataType->attribute( 'information' );
                $attributeName = $attributeName['name'];
                $unvalidatedAttributes[] = array( 'id' => $attribute->attribute( 'id' ),
                                                  'identifier' => $attribute->attribute( 'identifier' ) ? $attribute->attribute( 'identifier' ) : $attribute->attribute( 'name' ),
                                                  'name' => $attributeName );
            }
        }
        $validation['processed'] = true;
        $validation['attributes'] = $unvalidatedAttributes;
        $requireVariable = 'ContentAttribute_is_required_checked';
        $searchableVariable = 'ContentAttribute_is_searchable_checked';
        $informationCollectorVariable = 'ContentAttribute_is_information_collector_checked';
        $canTranslateVariable = 'ContentAttribute_can_translate_checked';
        $requireCheckedArray = array();
        $searchableCheckedArray = array();
        $informationCollectorCheckedArray = array();
        $canTranslateCheckedArray = array();
        if ( $http->hasPostVariable( $requireVariable ) )
            $requireCheckedArray = $http->postVariable( $requireVariable );
        if ( $http->hasPostVariable( $searchableVariable ) )
            $searchableCheckedArray = $http->postVariable( $searchableVariable );
        if ( $http->hasPostVariable( $informationCollectorVariable ) )
            $informationCollectorCheckedArray = $http->postVariable( $informationCollectorVariable );
        if ( $http->hasPostVariable( $canTranslateVariable ) )
            $canTranslateCheckedArray = $http->postVariable( $canTranslateVariable );

        foreach ( array_keys( $attributes ) as $key )
        {
            $attribute =& $attributes[$key];
            $attributeID = $attribute->attribute( 'id' );
            $attribute->setAttribute( 'is_required', in_array( $attributeID, $requireCheckedArray ) );
            $attribute->setAttribute( 'is_searchable', in_array( $attributeID, $searchableCheckedArray ) );
            $attribute->setAttribute( 'is_information_collector', in_array( $attributeID, $informationCollectorCheckedArray ) );
            // Set can_translate to 0 if user has clicked Disable translation in GUI
            $attribute->setAttribute( 'can_translate', !in_array( $attributeID, $canTranslateCheckedArray ) );
        }
    }
}

// Fixup input
if ( $requireFixup )
{
    foreach( array_keys( $attributes ) as $key )
    {
        $attribute =& $attributes[$key];
        $dataType =& $attribute->dataType();
        $status = $dataType->fixupClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
    }
}

$cur_datatype = 'ezstring';
// Apply HTTP POST variables
if ( $contentClassHasInput )
{
    eZHTTPPersistence::fetch( 'ContentAttribute', eZContentClassAttribute::definition(),
                              $attributes, $http, true );
    eZHttpPersistence::fetch( 'ContentClass', eZContentClass::definition(),
                              $class, $http, false );
    if ( $http->hasVariable( 'ContentClass_is_container_exists' ) )
    {
        if ( $http->hasVariable( 'ContentClass_is_container_checked' ) )
        {
            $class->setAttribute( "is_container", 1 );
        }
        else
        {
            $class->setAttribute( "is_container", 0 );
        }
    }
    if ( $http->hasPostVariable( 'DataTypeString' ) )
        $cur_datatype = $http->postVariable( 'DataTypeString' );
}

$class->setAttribute( 'version', EZ_CLASS_VERSION_STATUS_TEMPORARY );

include_once( 'lib/ezi18n/classes/ezchartransform.php' );
$trans =& eZCharTransform::instance();

// Fixed identifiers to only contain a-z0-9_
foreach( array_keys( $attributes ) as $key )
{
    $attribute =& $attributes[$key];
    $attribute->setAttribute( 'version', EZ_CLASS_VERSION_STATUS_TEMPORARY );
    $identifier = $attribute->attribute( 'identifier' );
    if ( $identifier == '' )
        $identifier = $attribute->attribute( 'name' );

    $identifier = $trans->transformByGroup( $identifier, 'identifier' );

    $attribute->setAttribute( 'identifier', $identifier );
    $dataType =& $attribute->dataType();
    $dataType->initializeClassAttribute( $attribute );
}

// Fixed class identifier to only contain a-z0-9_
$identifier = $class->attribute( 'identifier' );
if ( $identifier == '' )
    $identifier = $class->attribute( 'name' );
$identifier = $trans->transformByGroup( $identifier, 'identifier' );
$class->setAttribute( 'identifier', $identifier );

// Run custom actions if any
if ( $customAction )
{
    foreach( array_keys( $attributes ) as $key )
    {
        $attribute =& $attributes[$key];
        if ( $customActionAttributeID == $attribute->attribute( 'id' ) )
        {
            $attribute->customHTTPAction( $Module, $http, $customAction );
        }
    }
}
// Set new modification date
$date_time = time();
$class->setAttribute( 'modified', $date_time );
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
$user =& eZUser::currentUser();
$user_id = $user->attribute( 'contentobject_id' );
$class->setAttribute( 'modifier_id', $user_id );

// Remove attributes which are to be deleted
if ( $http->hasPostVariable( 'RemoveButton' ) )
{
    $validation['processed'] = true;
    if ( eZHttpPersistence::splitSelected( 'ContentAttribute', $attributes,
                                           $http, 'id',
                                           $keepers, $rejects ) )
    {
        $attributes = $keepers;
        foreach ( $rejects as $reject )
        {
            $dataType =& $reject->dataType();
            if ( $dataType->isClassAttributeRemovable( $reject ) )
            {
                $reject->remove();
            }
            else
            {
                $removeInfo = $dataType->classAttributeRemovableInformation( $reject );
                if ( $removeInfo !== false )
                {
                    $validation['attributes'] = array( array( 'id' => $reject->attribute( 'id' ),
                                                              'identifier' => $reject->attribute( 'identifier' ),
                                                              'reason' => $removeInfo ) );
                }
            }
        }
    }
}

// Fetch HTTP input
if ( $contentClassHasInput )
{
    foreach( array_keys( $attributes ) as $key )
    {
        $attribute =& $attributes[$key];
        $dataType =& $attribute->dataType();
        $dataType->fetchClassAttributeHTTPInput( $http, 'ContentClass', $attribute );
    }
}

if ( $validationRequired )
{
    // check for duplicate attribute identifiers in the input
    if ( count( $attributes ) > 1 )
    {
        for( $attrIndex = 0; $attrIndex < count( $attributes ) - 1; $attrIndex++ )
        {
            $classAttribute = $attributes[$attrIndex];
            $identifier = $classAttribute->attribute( 'identifier' );
            for ( $attrIndex2 = $attrIndex + 1; $attrIndex2 < count( $attributes ); $attrIndex2++ )
            {
                $classAttribute2 = $attributes[$attrIndex2];
                $identifier2 = $classAttribute2->attribute( 'identifier' );
                if ( $identifier == $identifier2 )
                {
                    $validation['attributes'][] = array( 'identifier' => $identifier,
                                                         'name' => $classAttribute->attribute( 'name' ),
                                                         'id' => $classAttribute->attribute( 'id' ),
                                                         'reason' => array ( 'text' => 'duplicate attribute identifier' ) );
                    $canStore = false;
                    break;
                }
            }
        }
    }
}

// Store version 0 and discard version 1
if ( $http->hasPostVariable( 'StoreButton' ) && $canStore )
{

    $id = $class->attribute( 'id' );
    $oldClassAttributes = $class->fetchAttributes( $id, true, EZ_CLASS_VERSION_STATUS_DEFINED );
    $newClassAttributes = $class->fetchAttributes( );

    // validate class name and identifier; check presence of class attributes
    // FIXME: object pattern name is never validated

    $basicClassPropertiesValid = true;
    {
        $className       =& $class->attribute( 'name' );
        $classIdentifier =& $class->attribute( 'identifier' );
        $classID         =& $class->attribute( 'id' );

        // validate class name
        if( trim( $className ) == '' )
        {
            $validation['class_errors'][] = array( 'text' => ezi18n( 'kernel/class', 'The class should have nonempty \'Name\' attribute.' ) );
            $basicClassPropertiesValid = false;
        }

        // check presence of attributes
        $newClassAttributes = $class->fetchAttributes( );
        if ( count( $newClassAttributes ) == 0 )
        {
            $validation['class_errors'][] = array( 'text' => ezi18n( 'kernel/class', 'The class should have at least one attribute.' ) );
            $basicClassPropertiesValid = false;
        }

        // validate class identifier

        $db =& eZDB::instance();
        $classCount = $db->arrayQuery( "SELECT COUNT(*) AS count FROM ezcontentclass WHERE  identifier='$classIdentifier' AND version=" . EZ_CLASS_VERSION_STATUS_DEFINED . " AND id <> $classID" );
        if ( $classCount[0]['count'] > 0 )
        {
            $validation['class_errors'][] = array( 'text' => ezi18n( 'kernel/class', 'There is a class already having the same identifier.' ) );
            $basicClassPropertiesValid = false;
        }
        unset( $classList );
        unset( $db );
    }

    if ( !$basicClassPropertiesValid )
    {
        $canStore = false;
        $validation['processed'] = false;
    }
    else
    {
        $firstStoreAttempt =& eZSessionRead( $http->sessionVariable( 'CanStoreTicket' ) );
        if ( !$firstStoreAttempt )
        {
            return $Module->redirectToView( 'view', array( $ClassID ) );
        }
        eZSessionDestroy( $http->sessionVariable( 'CanStoreTicket' ) );

        // Class cleanup, update existing class objects according to new changes
        include_once( 'kernel/classes/ezcontentobject.php' );

        $objects = null;
        $objectCount = eZContentObject::fetchSameClassListCount( $ClassID );
        if ( $objectCount > 0 )
        {
            // Delete object attributes which have been removed.
            foreach ( $oldClassAttributes as $oldClassAttribute )
            {
                $attributeExist = false;
                $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
                foreach ( $newClassAttributes as $newClassAttribute )
                {
                    $newClassAttributeID = $newClassAttribute->attribute( 'id' );
                    if ( $oldClassAttributeID == $newClassAttributeID )
                        $attributeExist = true;
                }
                if ( !$attributeExist )
                {
                    $objectAttributes = eZContentObjectAttribute::fetchSameClassAttributeIDList( $oldClassAttributeID );
                    foreach ( $objectAttributes as $objectAttribute )
                    {
                        $objectAttributeID = $objectAttribute->attribute( 'id' );
                        $objectAttribute->remove( $objectAttributeID );
                    }
                }
            }
            $class->storeDefined( $attributes );

            // Add object attributes which have been added.
            foreach ( $newClassAttributes as $newClassAttribute )
            {
                $attributeExist = false;
                $newClassAttributeID = $newClassAttribute->attribute( 'id' );
                foreach ( $oldClassAttributes as $oldClassAttribute )
                {
                    $oldClassAttributeID = $oldClassAttribute->attribute( 'id' );
                    if ( $oldClassAttributeID == $newClassAttributeID )
                        $attributeExist = true;
                }
                if ( !$attributeExist )
                {
                    if ( $objects == null )
                    {
                        $objects = eZContentObject::fetchSameClassList( $ClassID );
                    }
                    foreach ( $objects as $object )
                    {
                        $contentobjectID = $object->attribute( 'id' );
                        $objectVersions =& $object->versions();
                        foreach ( $objectVersions as $objectVersion )
                        {
                            $translations = $objectVersion->translations( false );
                            $version = $objectVersion->attribute( 'version' );
                            foreach ( $translations as $translation )
                            {
                                $objectAttribute = eZContentObjectAttribute::create( $newClassAttributeID, $contentobjectID, $version );
                                $objectAttribute->setAttribute( 'language_code', $translation );
                                $objectAttribute->initialize();
                                $objectAttribute->store();
                                $objectAttribute->postInitialize();
                            }
                        }
                    }
                }
            }
        }
        else
        {
            $class->storeDefined( $attributes );
        }

        $http->removeSessionVariable( 'CanStoreTicket' );
        return $Module->redirectToView( 'view', array( $ClassID ) );
    }
}

// Store changes
if ( $canStore )
    $class->store( $attributes );

if ( $http->hasPostVariable( 'NewButton' ) )
{
    $new_attribute = eZContentClassAttribute::create( $ClassID, $cur_datatype );
    $attrcnt = count( $attributes ) + 1;
    $new_attribute->setAttribute( 'name', ezi18n( 'kernel/class/edit', 'new attribute' ) . $attrcnt );
    $dataType = $new_attribute->dataType();
    $dataType->initializeClassAttribute( $new_attribute );
    $new_attribute->store();
    $attributes[] =& $new_attribute;
}
else if ( $http->hasPostVariable( 'MoveUp' ) )
{
    $attribute =& eZContentClassAttribute::fetch( $http->postVariable( 'MoveUp' ), true, EZ_CLASS_VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );
    $attribute->move( false );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID );
    return;
}
else if ( $http->hasPostVariable( 'MoveDown' ) )
{
    $attribute =& eZContentClassAttribute::fetch( $http->postVariable( 'MoveDown' ), true, EZ_CLASS_VERSION_STATUS_TEMPORARY,
                                                  array( 'contentclass_id', 'version', 'placement' ) );
    $attribute->move( true );
    $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $ClassID );
    return;
}

$Module->setTitle( 'Edit class ' . $class->attribute( 'name' ) );
if ( !$http->hasSessionVariable( 'CanStoreTicket' ) )
{
    $http->setSessionVariable( 'CanStoreTicket', md5( (string)rand() ) );
    eZSessionWrite( $http->sessionVariable( 'CanStoreTicket' ), 1 );
}

// Fetch updated attributes
$attributes = $class->fetchAttributes();

// Template handling
include_once( 'kernel/common/template.php' );
$tpl =& templateInit();
$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'class', $class->attribute( 'id' ) ) ) ); // Class ID
$tpl->setVariable( 'http', $http );
$tpl->setVariable( 'validation', $validation );
$tpl->setVariable( 'can_store', $canStore );
$tpl->setVariable( 'require_fixup', $requireFixup );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'class', $class );
$tpl->setVariable( 'attributes', $attributes );
$tpl->setVariable( 'datatypes', $datatypes );
$tpl->setVariable( 'datatype', $cur_datatype );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:class/edit.tpl' );
$Result['path'] = array( array( 'url' => '/class/edit/',
                                'text' => ezi18n( 'kernel/class', 'Class edit' ) ) );

?>
