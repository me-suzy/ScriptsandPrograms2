<?php
//
// Definition of eZContentDataInstace class
//
// Created on: <22-Apr-2002 09:31:57 bf>
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
  \class eZContentObjectAttribute ezcontentobjectattribute.php
  \ingroup eZKernel
  \brief Encapsulates the data for an object attribute

  \sa eZContentObject eZContentClass eZContentClassAttribute
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );
include_once( 'kernel/classes/ezdatatype.php' );

class eZContentObjectAttribute extends eZPersistentObject
{
    /*!
    */
    function eZContentObjectAttribute( $row )
    {
        $this->Content = null;
        $this->DisplayInfo = null;
        $this->HTTPValue = null;
        $this->ValidationError = null;
        $this->ValidationLog = null;
        $this->ContentClassAttributeIdentifier = null;
        $this->ContentClassAttributeID = null;
        $this->InputParameters = false;
        $this->HasValidationError = false;
        $this->DataTypeCustom = null;
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "language_code" => array( 'name' => "LanguageCode",
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         "contentclassattribute_id" => array( 'name' => "ContentClassAttributeID",
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                         "attribute_original_id" => array( 'name' => "AttributeOriginalID",
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                         "sort_key_int" => array( 'name' => "SortKeyInt",
                                                                  'datatype' => 'integer',
                                                                  'default' => '0',
                                                                  'required' => true ),
                                         "sort_key_string" => array( 'name' => "SortKeyString",
                                                                     'datatype' => 'string',
                                                                     'max_length' => 255,
                                                                     'default' => '',
                                                                     'required' => true ),
                                         "data_type_string" => array( 'name' => "DataTypeString",
                                                                     'datatype' => 'string',
                                                                     'default' => '',
                                                                     'required' => true ),
                                         "data_text" => array( 'name' => "DataText",
                                                               'datatype' => 'text',
                                                               'default' => '',
                                                               'required' => true ),
                                         "data_int" => array( 'name' => "DataInt",
                                                              'datatype' => 'integer',
                                                              'default' => null,
                                                              'required' => false ),
                                         "data_float" => array( 'name' => "DataFloat",
                                                                'datatype' => 'float',
                                                                'default' => 0,
                                                                'required' => true ) ),
                      "keys" => array( "id", "contentobject_id", "version", "language_code" ),
                      "function_attributes" => array( "contentclass_attribute" => "contentClassAttribute",
                                                      "contentclass_attribute_identifier" => "contentClassAttributeIdentifier",
                                                      "contentclass_attribute_name" => "contentClassAttributeName",
                                                      "can_translate" => "contentClassAttributeCanTranslate",
                                                      "is_information_collector" => "contentClassAttributeIsInformationCollector",
                                                      "is_required" => "contentClassAttributeIsRequired",
                                                      "content" => "content",
                                                      'has_http_value' => 'hasHTTPValue',
                                                      'value' => 'value',
                                                      'has_content' => 'hasContent',
                                                      "class_content" => "classContent",
                                                      "object" => "object",
                                                      'view_template' => 'viewTemplateName',
                                                      'edit_template' => 'editTemplateName',
                                                      'result_template' => 'resultTemplate',
                                                      "has_validation_error" => "hasValidationError",
                                                      "validation_error" => "validationError",
                                                      "validation_log" => "validationLog",
                                                      "language" => "language",
                                                      "is_a" => "isA",
                                                      'display_info' => 'displayInfo',
                                                      'class_display_info' => 'classDisplayInfo' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectAttribute",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject_attribute" );
    }

    function fetch( $id, $version, $asObject = true, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $asObject );
    }

    function &fetchListByClassID( $id, $version = false, $limit = null, $asObject = true, $asCount = false )
    {
        $conditions = array();
        if ( is_array( $id ) )
            $conditions['contentclassattribute_id'] = array( $id );
        else
            $conditions['contentclassattribute_id'] = $id;
        if ( $version !== false )
            $conditions["version"] = $version;
        $fieldFilters = null;
        $customFields = null;
        if ( $asCount )
        {
            $limit = null;
            $asObject = false;
            $fieldFilters = array();
            $customFields = array( array( 'operation' => 'count( id )',
                                          'name' => 'count' ) );
        }
        $objectList = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                            $fieldFilters, $conditions,
                                                            null, $limit, $asObject,
                                                            null, $customFields );
        if ( $asCount )
            return $objectList[0]['count'];
        else
            return $objectList;
    }

    /*!
     Fetches all contentobject attributes which relates to the contentclass attribute \a $contentClassAttributeID.
     \return An array with contentobject attributes.
     \param $contentClassAttributeID The ID of the contentclass attribute
     \param $asObject If \c true objects will be returned, otherwise associative arrays are returned.
     \param $version The version the of contentobject attributes to fetch or all version if \c false.
    */
    function fetchSameClassAttributeIDList( $contentClassAttributeID, $asObject = true, $version = false )
    {
        $conditions = array( "contentclassattribute_id" => $contentClassAttributeID );
        if ( $version !== false )
            $conditions['version'] = $version;
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    $conditions,
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /*!
     \return the attributes with alternative translations for the current attribute version and class attribute id
    */
    function fetchAttributeTranslations( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    array( "contentclassattribute_id" => $this->ContentClassAttributeID,
                                                           "contentobject_id" => $this->ContentObjectID,
                                                           "version" => $this->Version ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    function create( $contentclassAttributeID, $contentobjectID, $version = 1 )
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $row = array(
            "id" => null,
            "contentobject_id" => $contentobjectID,
            "version" => $version,
            "language_code" => eZContentObject::defaultLanguage(),
            "contentclassattribute_id" => $contentclassAttributeID,
            'data_text' => '',
            'data_int' => null,
            'data_float' => 0.0 );
        return new eZContentObjectAttribute( $row );
    }

    /*!
     \reimp
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store()
    {
        // Unset the cache
        global $eZContentObjectContentObjectCache;
        unset( $eZContentObjectContentObjectCache[$this->ContentObjectID] );
        global $eZContentObjectDataMapCache;
        unset( $eZContentObjectDataMapCache[$this->ContentObjectID] );

        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        if ( is_object( $dataType ) )
        {
            $this->setAttribute( 'data_type_string', $classAttribute->attribute( 'data_type_string' ) );
            $this->updateSortKey( false );

            $db =& eZDB::instance();
            $db->begin();

            // store the content data for this attribute
            $dataType->storeObjectAttribute( $this );

            eZPersistentObject::store();
            $dataType->postStore( $this );
            $db->commit();
        }
    }

    /*!
     Similar to store() but does not call eZDataType::storeObjectAttribute().

     If you have some datatype code that needs to store attribute data you should
     call this instead of store(), this function will avoid infinite recursion.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function storeData()
    {
        // Unset the cache
        global $eZContentObjectContentObjectCache;
        unset( $eZContentObjectContentObjectCache[$this->ContentObjectID] );
        global $eZContentObjectDataMapCache;
        unset( $eZContentObjectDataMapCache[$this->ContentObjectID] );

        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $this->setAttribute( 'data_type_string', $classAttribute->attribute( 'data_type_string' ) );
        $this->updateSortKey( false );

        eZPersistentObject::store();
    }

    /*!
     Copies the sort key value from the attribute according to the datatype rules.
     \note The attribute is not stored
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function updateSortKey( $storeData = true )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();

        $return = false;

        if ( is_object( $dataType ) )
        {
            $return = true;

            $sortKey = $dataType->sortKey( $this );
            $this->setAttribute( 'sort_key_string', "" );
            $this->setAttribute( 'sort_key_int', 0 );
            if ( $dataType->sortKeyType() == 'string' )
            {
                $this->setAttribute( 'sort_key_string', $sortKey );
                $this->setAttribute( 'sort_key_int', 0 );

            }
            else if ( $dataType->sortKeyType() == 'int' )
            {
                $this->setAttribute( 'sort_key_int', $sortKey );
            }

            if ( $storeData )
            {
                $dataType->storeObjectAttribute( $this );
                $return = eZPersistentObject::store();
            }
        }

        return $return;
    }

    /*!
     Store one row into content attribute table
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function storeNewRow()
    {
        return eZPersistentObject::store();
    }

    /**
     * Fetch a node by identifier (unique data_text )

     \param identifier

     \return object attribute
    */
    function fetchByIdentifier( $identifier, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                null,
                                                array( 'sort_key_string' => $identifier, 'data_type_string' => 'ezstring' ),
                                                $asObject );
    }

    function &language( $languageCode = false, $asObject = true )
    {
        if ( !$languageCode )
        {
            $languageCode = eZContentObject::defaultLanguage();
        }
        $language = eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                     null,
                                                     array( "contentclassattribute_id" => $this->ContentClassAttributeID,
                                                            "contentobject_id" => $this->ContentObjectID,
                                                            "version" => $this->Version,
                                                            "language_code" => $languageCode ),
                                                     $asObject );
        return $language;
    }

    /*!
    */
    function &object()
    {
        if( isset( $this->ContentObjectID ) and $this->ContentObjectID )
            $object =& eZContentObject::fetch( $this->ContentObjectID );
        else
            $object = null;
        return $object;
    }

    /*!
      Returns the attribute  for the current data attribute instance
    */
    function &contentClassAttribute()
    {
        eZDebug::accumulatorStart( 'instantiate_class_attribute', 'class_abstraction', 'Instantiating content class attribute' );
        $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
        eZDebug::accumulatorStop( 'instantiate_class_attribute' );
        return $classAttribute;
    }

    /*!
     Sets the cached content class attribute identifier
    */
    function setContentClassAttributeIdentifier( $identifier )
    {
        $this->ContentClassAttributeIdentifier = $identifier;
    }

    /*!
     Sets the cached content class attribute can_translate
    */
    function setContentClassAttributeCanTranslate( $canTranslate )
    {
        $this->ContentClassAttributeCanTranslate = $canTranslate;
    }

    /*!
     Sets the cached content class attribute name
    */
    function setContentClassAttributeName( $name )
    {
        $this->ContentClassAttributeName = $name;
    }

    /*!
     \returns the cached value of the IsRequired value
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &contentClassAttributeIsRequired()
    {
        if ( $this->ContentClassAttributeIsRequired === null )
        {
            eZDebug::accumulatorStart( 'class_a_is_req', 'Sytem overhead', 'Fetch class attribute is_required' );

            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            $this->ContentClassAttributeIsRequired = $classAttribute->attribute( 'is_required' );
            eZDebug::accumulatorStop( 'class_a_is_req' );
        }

        return $this->ContentClassAttributeIsRequired;
    }

    /*!
     \returns the cached value of the is_informationcollector value
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &contentClassAttributeIsInformationCollector()
    {
        if ( $this->ContentClassAttributeIsInformationCollector === null )
        {
            eZDebug::accumulatorStart( 'class_a_is_ic', 'Sytem overhead', 'Fetch class attribute name' );

            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            $this->ContentClassAttributeIsInformationCollector = $classAttribute->attribute( 'is_information_collector' );
            eZDebug::accumulatorStop( 'class_a_is_ic' );
        }

        return $this->ContentClassAttributeIsInformationCollector;
    }

    /*!
     \returns the cached value of the class attribute name
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &contentClassAttributeName()
    {
        if ( $this->ContentClassAttributeName === null )
        {
            eZDebug::accumulatorStart( 'class_a_name', 'Sytem overhead', 'Fetch class attribute name' );

            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            $this->ContentClassAttributeName = $classAttribute->attribute( 'name' );
            eZDebug::accumulatorStop( 'class_a_name' );
        }

        return $this->ContentClassAttributeName;
    }

    /*!
     \returns the cached value if the attribute can be translated or not
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &contentClassAttributeCanTranslate()
    {
        if ( $this->ContentClassAttributeCanTranslate === null )
        {
            eZDebug::accumulatorStart( 'class_a_can_translate', 'Sytem overhead', 'Fetch class attribute can translate value' );

            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );

            $dataType =& $classAttribute->dataType();

            if ( is_object( $dataType ) &&
                 $dataType->Attributes["properties"]["translation_allowed"] &&
                 $classAttribute->attribute( 'can_translate' ) )
                $this->ContentClassAttributeCanTranslate = 1;
            else
                $this->ContentClassAttributeCanTranslate = 0;
            eZDebug::accumulatorStop( 'class_a_can_translate' );
        }

        return $this->ContentClassAttributeCanTranslate;
    }

    /*!
     \return the idenfifier for the content class attribute
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &contentClassAttributeIdentifier()
    {
        if ( $this->ContentClassAttributeIdentifier === null )
        {
            eZDebug::accumulatorStart( 'class_a_id', 'Sytem overhead', 'Fetch class attribute identifier' );

            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            $this->ContentClassAttributeIdentifier = $classAttribute->attribute( 'identifier' );
            eZDebug::accumulatorStop( 'class_a_id' );
        }

        return $this->ContentClassAttributeIdentifier;
    }

    /*!
      Validates the data contents, returns true on success false if the data
      does not validate.
     */
    function validateInput( &$http, $base,
                            &$inputParameters, $validationParameters = array() )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
        {
            $this->setInputParameters( $inputParameters );
            $this->setValidationParameters( $validationParameters );
            $this->IsValid = $dataType->validateObjectAttributeHTTPInput( $http, $base, $this );
            $this->unsetValidationParameters();
            $this->unsetInputParameters();
        }
        return $this->IsValid;
    }

    /*!
     Sets the current input parameters to \a $parameters.
     The input parameters are set by validateInput() and made avaiable to
     datatypes trough the function inputParameters().
     \note The input parameters will only be available for the duration of validateInput().
     \sa inputParameters
    */
    function setInputParameters( &$parameters )
    {
        $this->InputParameters =& $parameters;
    }

    /*!
     Unsets the input parameters previously set by setInputParameters().
     \sa inputParameters
    */
    function unsetInputParameters()
    {
        unset( $this->InputParameters );
        $this->InputParameters = false;
    }

    /*!
     \return the current input parameters or \c false if no parameters has been set.
     \sa setInputParameters, unsetInputParameters
    */
    function &inputParameters()
    {
        return $this->InputParameters;
    }

    /*!
     Sets the current validation parameters to \a $parameters.
     The validation parameters are set by validateInput() and made avaiable to
     datatypes trough the function validationParameters().
     \note The validation parameters will only be available for the duration of validateInput().
     \sa validationParameters
    */
    function setValidationParameters( &$parameters )
    {
        $this->ValidationParameters =& $parameters;
    }

    /*!
     Unsets the validation parameters previously set by setValidationParameters().
     \sa validationParameters
    */
    function unsetValidationParameters()
    {
        unset( $this->ValidationParameters );
        $this->ValidationParameters = false;
    }

    /*!
     \return the current validation parameters or \c false if no parameters has been set.
     \sa setValidationParameters, unsetValidationParameters
    */
    function &validationParameters()
    {
        return $this->ValidationParameters;
    }

    /*!
     \return \c true if 'is_required' validation should be done otherwise \c false;
    */
    function validateIsRequired()
    {
        $classAttribute =& $this->contentClassAttribute();
        $validationParameters =& $this->validationParameters();
        if ( !( isset( $validationParameters['skip-isRequired'] ) && $validationParameters['skip-isRequired'] === true ) && $classAttribute->attribute( "is_required" ) )
            return true;

        return false;
    }

    /*!
      Tries to fixup the input text to be acceptable.
     */
    function fixupInput( &$http, $base )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $dataType->fixupObjectAttributeHTTPInput( $http, $base, $this );
    }

    /*!
      Fetches the data from http post vars and sets them correctly.
    */
    function fetchInput( &$http, $base )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            return $dataType->fetchObjectAttributeHTTPInput( $http, $base, $this );

        return false;
    }

    /*!
      Validates the information collection data.
    */
    function validateInformation( &$http, $base,
                                  &$inputParameters, $validationParameters = array() )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
        {
            $this->setInputParameters( $inputParameters );
            $this->setValidationParameters( $validationParameters );
            $this->IsValid = $dataType->validateCollectionAttributeHTTPInput( $http, $base, $this );
            $this->unsetValidationParameters();
            $this->unsetInputParameters();
        }
        return $this->IsValid;
    }

    /*!
     Collects the information entered by the user from http post vars
    */
    function collectInformation( &$collection, &$collectionAttribute, &$http, $base )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
        {
            $collectionAttribute->setAttribute( 'contentclass_attribute_id', $this->attribute( 'contentclassattribute_id' ) );
            $collectionAttribute->setAttribute( 'contentobject_attribute_id', $this->attribute( 'id' ) );
            $collectionAttribute->setAttribute( 'contentobject_id', $this->attribute( 'contentobject_id' ) );
            return $dataType->fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $this );
        }

        return false;
    }

    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( &$http, $action, $parameters = array() )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $dataType->customObjectAttributeHTTPAction( $http, $action, $this, $parameters );
    }

    /*!
     Sends custom actions to datatype for custom handling.
    */
    function handleCustomHTTPActions( &$http, $attributeDataBaseName,
                                      $customActionAttributeArray, $customActionParameters )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
        {
            $customActionParameters['contentobject_attribute'] =& $this;
            $dataType->handleCustomObjectHTTPActions( $http, $attributeDataBaseName,
                                                      $customActionAttributeArray, $customActionParameters );
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function onPublish( &$object, &$nodes  )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $dataType->onPublish( $this, $object, $nodes );
    }

    /*!
     Inserts the HTTP file \a $httpFile to the content object attribute \a $objectAttribute
     by calling eZDataType::insertHTTPFile on the current datatype.

     The parameters are passed directly to the datatype with the exception of
     \a $object, \a $objectVersion and \a $objectLanguage.
     If \c false is passed to any of these parameters they will be fetched from the
     current attribute, if these are available it is adviced to pass them since it will save time.
    */
    function insertHTTPFile( &$object, $objectVersion, $objectLanguage,
                             &$httpFile, $mimeData,
                             &$result )
    {
        if ( !$object )
            $object =& $this->object();
        if ( $objectVersion === false )
            $objectVersion = $object->attribute( 'current_version' );
        if ( $objectLanguage === false )
            $objectLanguage = $object->attribute( 'default_language' );
        $dataType =& $this->dataType();
        return $dataType->insertHTTPFile( $object, $objectVersion, $objectLanguage,
                                          $this, $httpFile, $mimeData,
                                          $result );
    }

    /*!
     Inserts the file named \a $filePath to the content object attribute \a $objectAttribute.
     by calling eZDataType::insertRegularFile on the current datatype.

     The parameters are passed directly to the datatype with the exception of
     \a $object, \a $objectVersion and \a $objectLanguage.
     If \c false is passed to any of these parameters they will be fetched from the
     current attribute, if these are available it is adviced to pass them since it will save time.
    */
    function insertRegularFile( &$object, $objectVersion, $objectLanguage,
                                $filePath,
                                &$result )
    {
        if ( !$object )
            $object =& $this->object();
        if ( $objectVersion === false )
            $objectVersion = $object->attribute( 'current_version' );
        if ( $objectLanguage === false )
            $objectLanguage = $object->attribute( 'default_language' );
        $dataType =& $this->dataType();
        return $dataType->insertRegularFile( $object, $objectVersion, $objectLanguage,
                                             $this, $filePath,
                                             $result );
    }

    /*!
     Inserts the string \a $string to the content object attribute \a $objectAttribute.
     by calling eZDataType::insertSimpleString on the current datatype.

     The parameters are passed directly to the datatype with the exception of
     \a $object, \a $objectVersion and \a $objectLanguage.
     If \c false is passed to any of these parameters they will be fetched from the
     current attribute, if these are available it is adviced to pass them since it will save time.
    */
    function insertSimpleString( &$object, $objectVersion, $objectLanguage,
                                 $string,
                                 &$result )
    {
        if ( !$object )
            $object =& $this->object();
        if ( $objectVersion === false )
            $objectVersion = $object->attribute( 'current_version' );
        if ( $objectLanguage === false )
            $objectLanguage = $object->attribute( 'default_language' );
        $dataType =& $this->dataType();
        return $dataType->insertSimpleString( $object, $objectVersion, $objectLanguage,
                                              $this, $string,
                                              $result );
    }

    /*!
     Calls hasStoredFileInformation() on the current datatype and returns the result.
    */
    function hasStoredFileInformation( &$object, $objectVersion, $objectLanguage )
    {
        if ( !$object )
            $object =& $this->object();
        if ( $objectVersion === false )
            $objectVersion = $object->attribute( 'current_version' );
        if ( $objectLanguage === false )
            $objectLanguage = $object->attribute( 'default_language' );
        $dataType =& $this->dataType();
        return $dataType->hasStoredFileInformation( $object, $objectVersion, $objectLanguage,
                                                    $this );
    }

    /*!
     Calls storedFileInformation() on the current datatype and returns the result.
    */
    function storedFileInformation( &$object, $objectVersion, $objectLanguage )
    {
        if ( !$object )
            $object =& $this->object();
        if ( $objectVersion === false )
            $objectVersion = $object->attribute( 'current_version' );
        if ( $objectLanguage === false )
            $objectLanguage = $object->attribute( 'default_language' );
        $dataType =& $this->dataType();
        return $dataType->storedFileInformation( $object, $objectVersion, $objectLanguage,
                                                 $this );
    }

    /*!
     Calls handleDownload() on the current datatype and returns the result.
    */
    function handleDownload( &$object, $objectVersion, $objectLanguage )
    {
        if ( !$object )
            $object =& $this->object();
        if ( $objectVersion === false )
            $objectVersion = $object->attribute( 'current_version' );
        if ( $objectLanguage === false )
            $objectLanguage = $object->attribute( 'default_language' );
        $dataType =& $this->dataType();
        return $dataType->handleDownload( $object, $objectVersion, $objectLanguage,
                                          $this );
    }

    /*!
     Initialized the attribute by using the datatype.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function initialize( $currentVersion = null, $originalContentObjectAttribute = null )
    {
        if ( $originalContentObjectAttribute === null )
            $originalContentObjectAttribute = $this;
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        if ( is_object( $dataType ) )
            $dataType->initializeObjectAttribute( $this, $currentVersion, $originalContentObjectAttribute );
    }

    /*!
     Initialized the attribute  by using the datatype after the actual attribute is stored.
    */
    function postInitialize( $currentVersion = null, $originalContentObjectAttribute = null )
    {
        if ( $originalContentObjectAttribute === null )
            $originalContentObjectAttribute = $this;
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        if ( is_object( $dataType ) )
            $dataType->postInitializeObjectAttribute( $this, $currentVersion, $originalContentObjectAttribute );
    }

    /*!
     Remove the attribute by using the datatype.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function remove( $id, $currentVersion = null )
    {
        $dataType =& $this->dataType();
        if ( !is_object( $dataType ) )
            return false;
        $dataType->deleteStoredObjectAttribute( $this, $currentVersion );
        if( $currentVersion == null )
        {
            eZPersistentObject::removeObject( eZContentObjectAttribute::definition(),
                                              array( "id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZContentObjectAttribute::definition(),
                                              array( "id" => $id,
                                                     "version" => $currentVersion ) );
        }
    }

    /*!
     Clones the attribute with new version \a $newVersionNumber and old version \a $currentVersionNumber.
     \note The cloned attribute is not stored.
    */
    function clone( $newVersionNumber, $currentVersionNumber, $contentObjectID = false )
    {
        $tmp = $this;
        $tmp->setAttribute( "version", $newVersionNumber );
        if ( $contentObjectID !== false )
        {
            // if copying the whole object
            if ( $contentObjectID != $tmp->attribute( 'contentobject_id' ) )
            {
                $exAttr =  eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                            null,
                                                            array( 'contentobject_id'         => $contentObjectID,
                                                                   'contentclassattribute_id' => $tmp->attribute( 'contentclassattribute_id' ),
                                                                   'language_code'            => $tmp->attribute( 'language_code' ) ),
                                                            true );

                // if the new object already contains the same attribute with another version
                if ( is_object( $exAttr ) )
                    // we take attribute id from it
                    $id = $exAttr->attribute( 'id' );
                else
                    // otherwise new attribute id will be generated
                    $id = null;

                $tmp->setAttribute( 'id', $id );
            }
            $tmp->setAttribute( 'contentobject_id', $contentObjectID );
        }
        $db =& eZDB::instance();
        $db->begin();
        $tmp->sync();
        $tmp->initialize( $currentVersionNumber, $this );
        $tmp->sync();
        $tmp->postInitialize( $currentVersionNumber, $this );
        $db->commit();

        return $tmp;
    }

    /*!
     Clones the attribute to the translation \a $languageCode.
     \note The cloned attribute is not stored.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &translateTo( $languageCode )
    {
        $tmp = $this;
        $tmp->setAttribute( 'id', null );
        $tmp->setAttribute( 'language_code', $languageCode );

        $db =& eZDB::instance();
        $db->begin();
        $tmp->sync();
        $currentVersionNumber = $this->attribute( 'version' );
        $tmp->initialize( $currentVersionNumber, $this );
        $tmp->sync();
        $tmp->postInitialize( $currentVersionNumber, $this );
        $db->commit();
        return $tmp;
    }

    /*!
     Returns the data type class for the current attribute.
    */
    function &dataType()
    {
        $dataType = null;
        if( !is_null( $this->DataTypeString ) )
            $dataType =& eZDataType::create( $this->DataTypeString );

        return $dataType;
    }

    /*!
      Fetches the title of the data instance which is to form the title of the object.
    */
    function title()
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            return $dataType->title( $this );

        return '';
    }

    /*!
     \return the content for the contentclass attribute which defines this contentobject attribute.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &classContent()
    {
        $attribute =& $this->contentClassAttribute();
        return $attribute->content();
    }

    /*!
     Sets the content of variable for the content of the relevant value(s) submitted in HTTP form.
    */
    function setHTTPValue( &$value )
    {
        $this->HTTPValue =& $value;
    }

    /*!
     Returns the content of the relevant value(s) submitted in HTTP form.
    */
    function &value()
    {
        if ( $this->HTTPValue !== null )
            return $this->HTTPValue;
        else
            return $this->content();
    }

    /*!
     \return \c true if the attribute has relavant value(s) submitted in HTTP form.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &hasHTTPValue()
    {
        if ( $this->HTTPValue !== null )
            $hasValue = true;
        else
            $hasValue = false;
        return $hasValue;
    }

    /*!
     Returns the content for this attribute.
    */
    function &content()
    {
        if ( $this->Content === null )
        {
            $dataType =& $this->dataType();
            if ( is_object( $dataType ) )
                $this->Content =& $dataType->objectAttributeContent( $this );
        }
        return $this->Content;
    }

    /*!
     \return \c true if the attribute is considered to have any content at all (ie. non-empty).

     It will call the hasObjectAttributeContent() for the current datatype to figure this out.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &hasContent()
    {
        $hasContent = false;
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
        {
            $hasContent = $dataType->hasObjectAttributeContent( $this );
        }
        return $hasContent;
    }

    /*!
     Returns the metadata. This is the pure content of the attribute used for
     indexing data for search engines.
     */
    function metaData()
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            return $dataType->metaData( $this );
        else
            return false;
    }

    /*!
     \static
     Goes trough all attributes and fetches metadata for the ones that is searchable.
     \return an array with metadata information.
    */
    function metaDataArray( &$attributes )
    {
        $metaDataArray = array();
        if ( !is_array( $attributes ) )
            return false;
        foreach( array_keys( $attributes ) as $key )
        {
            $attribute =& $attributes[$key];
            $classAttribute =& $attribute->contentClassAttribute();
            if ( $classAttribute->attribute( 'is_searchable' ) )
            {
                $attributeMetaData = $attribute->metaData();
                if ( $attributeMetaData !== false )
                {
                    if ( !is_array( $attributeMetaData ) )
                    {
                        $attributeMetaData = array( array( 'id' => '',
                                                           'text' => $attributeMetaData ) );
                    }
                    $metaDataArray = array_merge( $metaDataArray, $attributeMetaData );
                }
            }
        }
        return $metaDataArray;
    }


    /*!
     Sets the content for the current attribute
    */
    function setContent( &$content )
    {
        $this->Content =& $content;
    }

    /*!
     \return Information on how to display the current attribute.
             See eZDataType::objectDisplayInformation() for more information on what is returned.
    */
    function &displayInfo()
    {
        if ( !$this->DisplayInfo )
        {
            $dataType =& $this->dataType();
            if ( is_object( $dataType ) )
                $this->DisplayInfo = $dataType->objectDisplayInformation( $this, false );
        }
        return $this->DisplayInfo;
    }

    /*!
     \return Information on how to display the class attribute.
             See eZDataType::classDisplayInformation() for more information on what is returned.
     \note This is a copy of eZContentClassAttribute::displayInfo()
    */
    function &classDisplayInfo()
    {
        $classAttribute =& $this->contentClassAttribute();
        if ( !$classAttribute->DisplayInfo )
        {
            $dataType =& $this->dataType();
            if ( is_object( $dataType ) )
            {
                $classAttribute->DisplayInfo =& $dataType->classDisplayInformation( $classAttribute, false );
            }
        }
        return $classAttribute->DisplayInfo;
    }

    /*!
     Returns the content action(s) for this attribute
    */
    function &contentActionList()
    {
        $dataType =& $this->dataType();
        $result = false;

        if ( is_object( $dataType ) )
            $result = $dataType->contentActionList( $this->contentClassAttribute() );

        return $result;
    }

    function setValidationError()
    {
        $numargs = func_num_args();
        if ( $numargs < 1 )
        {
            trigger_error( 'Function must take at least one parameter', WARNING );
            return;
        }
        $argList = func_get_args();
        $text = eZContentObjectAttribute::generateValidationErrorText( $numargs, $argList );
        $this->ValidationError = $text;
        $this->HasValidationError = true;
    }

    function setHasValidationError( $hasError = true )
    {
        $this->HasValidationError = $hasError;
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &hasValidationError()
    {
        return $this->HasValidationError;
    }

    function generateValidationErrorText( $numargs, $argList )
    {
        $text = $argList[0];
        for ( $i = 1; $i < $numargs; ++$i )
        {
            $arg = $argList[$i];
            $text = str_replace( "%$i", $arg, $text );
        }
        return $text;
    }

    function setValidationLog( $text )
    {
        if ( is_string( $text ) )
        {
            $logMessage = array();
            $logMessage[] = $text;
            $this->ValidationLog = $logMessage;
        }
        elseif ( is_array( $text ) )
        {
            $this->ValidationLog = $text;
        }
        else
        {
            $this->ValidationLog = null;
        }
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &validationError()
    {
        return $this->ValidationError;
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &validationLog()
    {
        return $this->ValidationLog;
    }

    /*!
    */
    function &serialize( &$package )
    {
        $result = false;
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $result =& $dataType->serializeContentObjectAttribute( $package, $this );

        return $result;
    }

    function unserialize( &$package, $attributeDOMNode )
    {
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $dataType->unserializeContentObjectAttribute( $package, $this, $attributeDOMNode );
    }

    /*!
    */
    function &isA()
    {
        $result = false;
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $result = $dataType->isA();

        return $result;
    }

    /*!
     \return the template name to use for viewing the attribute or
             if the attribute is an information collector the information
             template name is returned.
     \note The returned template name does not include the .tpl extension.
     \sa editTemplate, informationTemplate
    */
    function &viewTemplateName()
    {
        /* This will not work, datatypes should be able to override this .e.g Online Editor
        // Don't need to do lookup since we already know the datatype string, which is the result
        return $this->DataTypeString;
        */

        $classAttribute =& $this->contentClassAttribute();
        if ( $classAttribute->attribute( 'is_information_collector' ) )
            $retValue =& $this->informationTemplate();
        else
            $retValue =& $this->viewTemplate();
        return $retValue;
    }

    /*!
     \return the template name to use for editing the attribute.
    */
    function &editTemplateName()
    {
        $editTemplate =& $this->editTemplate();
        return $editTemplate;
    }

    /*!
     \return the template name to use for viewing the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa editTemplate, informationTemplate
    */
    function &viewTemplate()
    {
        /* This will not work, datatypes should be able to override this .e.g Online Editor
        return $this->DataTypeString;
        // We really don't need to do a lookup via the datatype since we already have the string
        */

        $result = false;
        $dataType =& $this->dataType();

        if ( is_object( $dataType ) )
            $result =& $dataType->viewTemplate( $this );

        return $result;
    }

    /*!
     \return the template name to use for editing the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, informationTemplate
    */
    function &editTemplate()
    {
        $result = false;
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $result =& $dataType->editTemplate( $this );

        return $result;

    }

    /*!
     \return the template name to use for information collection for the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, editTemplate
    */
    function &informationTemplate()
    {
        /* This will not work, datatypes should be able to override this .e.g Online Editor
        return $this->DataTypeString;
        */

        $result = false;
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $result =& $dataType->informationTemplate( $this );

        return $result;
    }

    /*!
     \return the template name to use for information collection result view for the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, editTemplate, informationTemplate
    */
    function &resultTemplate()
    {
        /* This will not work, datatypes should be able to override this .e.g Online Editor
        return $this->DataTypeString;
        */

        $result = false;
        $dataType =& $this->dataType();
        if ( is_object( $dataType ) )
            $result = $dataType->resultTemplate( $this );

        return $result;
    }

    /// Contains the value(s) submitted in HTTP form
    var $HTTPValue;

    /// Contains the content for this attribute
    var $Content;

    /// Contains information on how to display the current attribute in various viewmodes
    var $DisplayInfo;

    /// Stores the is valid
    var $IsValid;

    var $ContentClassAttributeID;

    /// Contains the last validation error
    var $ValidationError;

    /// Contains the last validation error
    var $ValidationLog;

    ///
    var $ContentClassAttributeIdentifier;
    var $ContentClassAttributeCanTranslate;
    var $ContentClassAttributeName;
    var $ContentClassAttributeIsInformationCollector;
    var $ContentClassAttributeIsRequired;
}

?>
