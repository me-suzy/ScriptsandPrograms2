<?php
//
// Definition of eZImageAliasHandler class
//
// Created on: <16-Oct-2003 09:34:25 bf>
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
  \class eZImageAliasHandler ezimagealiashandler.php
  \ingroup eZDatatype
  \brief Internal manager for the eZImage datatype

  Takes care of image conversion and serialization from and to
  the internal XML format.

  \note This handler was introduced in eZ publish 3.3 and will detect older
        eZImage structures and convert them on the fly.

*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'lib/ezfile/classes/ezfilehandler.php' );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "kernel/classes/datatypes/ezimage/ezimagefile.php" );

class eZImageAliasHandler
{
    /*!
     Creates the handler and creates a reference to the contentobject attribute that created it.
    */
    function eZImageAliasHandler( &$contentObjectAttribute )
    {
        $this->ContentObjectAttribute =& $contentObjectAttribute;
    }

    /*!
     Lists all available image aliases as attributes as well as:
     - alternative_text - The alternative text input by the user, can be empty
     - original_filename - The name of image which it had on the users disk before it was uploaded
     - is_valid - A boolean which says if there is an image here or not.
    */
    function attributes()
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        $aliasList = $imageManager->aliasList();
        return array_merge( array( 'alternative_text',
                                   'original_filename',
                                   'is_valid' ),
                            array_keys( $aliasList ) );
    }

    /*!
     \return true if the attribute named \a $attributeName exists.
     See eZImageAliasHandler::attributes() for which attributes are available.
    */
    function hasAttribute( $attributeName )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename',
                              'is_valid' ) ) )
            return true;
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        if ( $imageManager->hasAlias( $attributeName ) )
            return true;
        return false;
    }

    /*!
     \return the value of the attribute named \a $attributeName.
     See eZImageAliasHandler::attributes() for which attributes are available.
    */
    function &attribute( $attributeName )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename',
                              'is_valid' ) ) )
        {
            $originalAttribute =& $this->attributeFromOriginal( $attributeName );
            return $originalAttribute;
        }
        $aliasName = $attributeName;
        $imageAlias =& $this->imageAlias( $aliasName );
        return $imageAlias;
    }

    /*!
     \return The value of the attribute named \a $attributeName from the 'original' image alias.

     This is a quick way for extracting information from the 'original' image alias.
    */
    function &attributeFromOriginal( $attributeName )
    {
        $originalAlias =& $this->attribute( 'original' );
        if ( $originalAlias )
            return $originalAlias[$attributeName];
        $retValue = null;
        return $retValue;
    }

    /*!
     Sets the attribute named \a $attributeName to have the value \a $attributeValue.

     The following attributes can be set:
     - alternative text
     - original_filename
    */
    function setAttribute( $attributeName, $attributeValue )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename' ) ) )
        {
            $aliasList =& $this->aliasList();
            foreach ( array_keys( $aliasList ) as $aliasName )
            {
                $alias =& $aliasList[$aliasName];
                $alias[$attributeName] = $attributeValue;
            }
            if ( $attributeName == 'alternative_text' )
            {
                $text = $this->displayText( $attributeValue );
                foreach ( array_keys( $aliasList ) as $aliasName )
                {
                    $alias =& $aliasList[$aliasName];
                    $alias['text'] = $text;
                }
            }
            $this->recreateDOMTree();
            $this->setStorageRequired();
            return true;
        }
        return false;
    }

    /*!
     \return \c true if this is considered to be owner of the image.

     It will be considered an owner if attribute data is not a copy
     of another attribute. For instance each time a new image is uploaded
     this will return \c true.
    */
    function isImageOwner()
    {
        $originalData = $this->originalAttributeData();
        return ( $originalData['attribute_id'] == false );
    }

    /*!
     \return The current serial number, the value will be 1 or higher.

     The serial number is used to create unique filenames for uploaded images,
     it will be increased each time an image is uploaded.


     \note This was required to get around the problem where browsers
           caches image information, if two images were uploaded in one version (e.g. a draft)
           the browser would not load the new image since it thought it had not changed.
    */
    function imageSerialNumber()
    {
        $serialNumber = $this->imageSerialNumberRaw();
        if ( $serialNumber < 1 )
            $serialNumber = 1;
        return $serialNumber;
    }

    /*!
     Increases the serial by one.
    */
    function increaseImageSerialNumber()
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        ++$serialNumber;
    }

    /*!
     Resets the serial number to zero.
    */
    function resetImageSerialNumber()
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        $serialNumber = 0;
    }

    /*!
     Sets the serial number to \a $number.
    */
    function setImageSerialNumber( $number )
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        $serialNumber = $number;
    }

    /*!
     \return A text string which can be used as display for the image.

     The text string will either contain the alternative text from the attribute
     or the parameter \a $alternativeText if it is set.
    */
    function displayText( $alternativeText = null )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( $alternativeText === null )
            $text = $this->attribute( 'alternative_text' );
        else
            $text = $alternativeText;
        // The following code may cause recursion, see:
        //   http://ez.no/community/bug_reports/creating_the_content_of_a_class_with_object_name_pattern_derived_from_image_attribute_crashes
        // and thus is commented.
        //
        // if ( !$text )
        // {
        //     $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
        //                                                              $contentObjectAttribute->attribute( 'contentobject_id' ),
        //                                                              true );
        //     if ( $contentVersion )
        //         $text = $contentVersion->versionName( $contentObjectAttribute->attribute( 'language_code' ) );
        // }
        return $text;
    }

    /*!
     \return The full directory path to the image, this includes the var and storage directory.
    */
    function directoryPath()
    {
        $aliasList =& $this->aliasList();
        if ( isset( $aliasList['original'] ) )
        {
            return $aliasList['original']['dirpath'];
        }
        return false;
    }

    /*!
     \return A normalized name for the image.

     The image name will generated from the name of the current version.
     If this is empty it will use the object name or the alternative text.

     This ensures that the image has a name which corresponds to the object it belongs to.

     The normalization ensures that the name only contains filename and URL friendly characters.
    */
    function imageName( &$contentObjectAttribute, &$contentVersion )
    {
        $objectName = $contentVersion->attribute( 'version_name' );
        if ( !$objectName )
        {
            $objectName = $contentVersion->attribute( 'name' );
            if ( !$objectName )
            {
                $objectName = $this->attribute( 'alternative_text' );
                if ( !$objectName )
                {
                    $objectName = ezi18n( 'kernel/classes/datatypes', 'image', 'Default image name' );
                }
            }
        }
        $objectName = eZImageAliasHandler::normalizeImageName( $objectName );
        $objectName .= $this->imageSerialNumber();
        return $objectName;
    }

    /*!
     \return A normalized name for the image based on a node.

     Similar to \a imageName() but fetches name information from the node \a $mainNode.

     The normalization ensures that the name only contains filename and URL friendly characters.
    */
    function imageNameByNode( &$contentObjectAttribute, &$mainNode )
    {
        $objectName = $mainNode->attribute( 'name' );
        if ( !$objectName )
        {
            $objectName = $this->attribute( 'alternative_text' );
            if ( !$objectName )
            {
                $objectName = ezi18n( 'kernel/classes/datatypes', 'image', 'Default image name' );
            }
        }
        $objectName = eZImageAliasHandler::normalizeImageName( $objectName );
//         $objectName .= $this->imageSerialNumber();
        return $objectName;
    }

    /*!
     \return The storage path for the image.

     The path is calculated by using information from the current object and version.
     If the object is in the node tree it will contain a path that matches the node path,
     if not it will be placed in the versioned storage repository.
    */
    function imagePath( &$contentObjectAttribute, &$contentVersion, $isImageOwner = null )
    {
        $useVersion = false;
        if ( $isImageOwner === null )
            $isImageOwner = $this->isImageOwner();
        if ( $contentVersion->attribute( 'status' ) == EZ_VERSION_STATUS_PUBLISHED or
             !$isImageOwner )
        {
            $contentObject =& $contentVersion->attribute( 'contentobject' );
            $mainNode =& $contentObject->attribute( 'main_node' );
            if ( !$mainNode )
            {
                $ini =& eZINI::instance( 'image.ini' );
                $contentImageSubtree = $ini->variable( 'FileSettings', 'VersionedImages' );
                $pathString = $contentImageSubtree;
                $useVersion = true;
            }
            else
            {
                $ini =& eZINI::instance( 'image.ini' );
                $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
                $pathString = $contentImageSubtree . '/' . $mainNode->attribute( 'path_identification_string' );
            }
        }
        else
        {
            $ini =& eZINI::instance( 'image.ini' );
            $contentImageSubtree = $ini->variable( 'FileSettings', 'VersionedImages' );
            $pathString = $contentImageSubtree;
            $useVersion = true;
        }
        if ( $useVersion )
            $identifierString = $contentObjectAttribute->attribute( 'id' ) . '/' . $contentObjectAttribute->attribute( 'version' ) . '-' . $contentObjectAttribute->attribute( 'language_code' );
        else
            $identifierString = $contentObjectAttribute->attribute( 'id' ) . '-' . $contentObjectAttribute->attribute( 'version' ) . '-' . $contentObjectAttribute->attribute( 'language_code' );
        $imagePath = eZSys::storageDirectory() . '/' . $pathString . '/' . $identifierString;
        return $imagePath;
    }

    /*!
     \return The storage path for the image based on a node.

     Similar to \a imagePath() but fetches name information from the node \a $mainNode.
    */
    function imagePathByNode( &$contentObjectAttribute, &$mainNode )
    {
        $pathString = $mainNode->attribute( 'path_identification_string' );
        $ini =& eZINI::instance( 'image.ini' );
        $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
        $imagePath = eZSys::storageDirectory() . '/' . $contentImageSubtree . '/' . $pathString . '/' . $contentObjectAttribute->attribute( 'id' ) . '-' . $contentObjectAttribute->attribute( 'version' ) . '-' . $contentObjectAttribute->attribute( 'language_code' );
        return $imagePath;
    }

    /*!
     \return The image alias structure for the alias named \a $aliasName.

     This will create the image alias if it does not exist yet, this can involve
     running image operations to for instance scale the image.
    */
    function &imageAlias( $aliasName )
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        if ( !$imageManager->hasAlias( $aliasName ) )
        {
            $retValue = null;
            return $retValue;
        }

        $aliasList =& $this->aliasList();
        if ( array_key_exists( $aliasName, $aliasList ) )
        {
            $alias =& $aliasList[$aliasName];
            return $alias;
        }
        else
        {
            $imageManager =& imageInit();
            if ( $imageManager->hasAlias( $aliasName ) )
            {
                $original =& $aliasList['original'];
                $basename = $original['basename'];
                if ( $imageManager->createImageAlias( $aliasName, $aliasList,
                                                      array( 'basename' => $basename ) ) )
                {
                    $text = $this->displayText( $original['alternative_text'] );
                    $originalFilename = $original['original_filename'];
                    foreach ( array_keys( $aliasList ) as $aliasName )
                    {
                        $alias =& $aliasList[$aliasName];
                        $alias['original_filename'] = $originalFilename;
                        $alias['text'] = $text;
                        if ( $alias['url'] and
                             file_exists( $alias['url'] ) )
                            $alias['filesize'] = filesize( $alias['url'] );
                        if ( $alias['is_new'] )
                            eZImageFile::appendFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $alias['url'] );
                    }
                    $this->addImageAliases( $aliasList );
                    return $aliasList[$aliasName];
                }
            }
        }

        $imageAlias = null;
        return $imageAlias;
    }

    /*!
     \private
     \return A list of aliases structures for the current attribute.

     The first this is called the XML data will be parsed into the internal
     structures. Subsequent calls will simply return the internal structure.
    */
    function &aliasList()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( isset( $contentObjectAttribute->DataTypeCustom['alias_list'] ) )
        {
            $aliasList =& $contentObjectAttribute->DataTypeCustom['alias_list'];
            return $aliasList;
        }
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        eZDebug::AccumulatorStart('imageparse', 'XML', 'Image XML parsing' );
        $xml = new eZXML();
        $xmlString =& $contentObjectAttribute->attribute( 'data_text' );
        $domTree =& $xml->domTree( $xmlString, array(), true );

        if ( $domTree == false )
        {
            $this->generateXMLData();
            $domTree =& $xml->domTree( $xmlString, array(), false );
        }

        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;
        $imageNodeArray = $domTree->get_elements_by_tagname( "ezimage" );
        $imageInfoNodeArray = $domTree->get_elements_by_tagname( "information" );
        $imageVariationNodeArray = $domTree->get_elements_by_tagname( "alias" );
        $imageOriginalArray = $domTree->get_elements_by_tagname( "original" );

        $aliasList = array();

        $aliasEntry = array();
        $alternativeText = $imageNodeArray[0]->get_attribute( 'alternative_text' );
        $originalFilename = $imageNodeArray[0]->get_attribute( 'original_filename' );
        $basename = $imageNodeArray[0]->get_attribute( 'basename' );
        $displayText = $this->displayText( $alternativeText );

        if ( isset( $imageOriginalArray[0] ) )
        {
            $originalData = array( 'attribute_id' => $imageOriginalArray[0]->get_attribute( 'attribute_id' ),
                                   'attribute_version' => $imageOriginalArray[0]->get_attribute( 'attribute_version' ),
                                   'attribute_language' => $imageOriginalArray[0]->get_attribute( 'attribute_language' ) );
//                                    'has_file_copy' => $imageOriginalArray[0]->get_attribute( 'has_file_copy' ) );
            $this->setOriginalAttributeData( $originalData );
        }

        $aliasEntry['name'] = 'original';
        $aliasEntry['width'] = $imageNodeArray[0]->get_attribute( 'width' );
        $aliasEntry['height'] = $imageNodeArray[0]->get_attribute( 'height' );
        $aliasEntry['mime_type'] = $imageNodeArray[0]->get_attribute( 'mime_type' );
        $aliasEntry['filename'] = $imageNodeArray[0]->get_attribute( 'filename' );
        $aliasEntry['suffix'] = $imageNodeArray[0]->get_attribute( 'suffix' );
        $aliasEntry['dirpath'] = $imageNodeArray[0]->get_attribute( 'dirpath' );
        $aliasEntry['basename'] = $basename;
        $aliasEntry['alternative_text'] = $alternativeText;
        $aliasEntry['text'] = $displayText;
        $aliasEntry['original_filename'] = $originalFilename;
        $aliasEntry['url'] = $imageNodeArray[0]->get_attribute( 'url' );
        $aliasEntry['alias_key'] = $imageNodeArray[0]->get_attribute( 'alias_key' );
        $aliasEntry['timestamp'] = $imageNodeArray[0]->get_attribute( 'timestamp' );
        $aliasEntry['full_path'] =& $aliasEntry['url'];
        $aliasEntry['is_valid'] = $imageNodeArray[0]->get_attribute( 'is_valid' );
        $aliasEntry['is_new'] = false;
        $aliasEntry['filesize'] = false;
        if ( $aliasEntry['url'] and
             file_exists( $aliasEntry['url'] ) )
            $aliasEntry['filesize'] = filesize( $aliasEntry['url'] );

        $imageInformation = false;
        if ( count( $imageInfoNodeArray ) > 0 )
        {
            $imageInfoNode =& $imageInfoNodeArray[0];
            $this->parseInformationNode( $imageInfoNode, $imageInformation );
        }
        $aliasEntry['info'] =& $imageInformation;

        $serialNumber = $imageNodeArray[0]->get_attribute( 'serial_number' );
        if ( $serialNumber )
            $this->setImageSerialNumber( $serialNumber );

        $aliasList['original'] = $aliasEntry;

        if ( is_array( $imageVariationNodeArray ) )
        {
            foreach ( $imageVariationNodeArray as $imageVariation )
            {
                $aliasEntry = array();
                $aliasEntry['name'] = $imageVariation->get_attribute( 'name' );
                $aliasEntry['width'] = $imageVariation->get_attribute( 'width' );
                $aliasEntry['height'] = $imageVariation->get_attribute( 'height' );
                $aliasEntry['mime_type'] = $imageVariation->get_attribute( 'mime_type' );
                $aliasEntry['filename'] = $imageVariation->get_attribute( 'filename' );
                $aliasEntry['suffix'] = $imageVariation->get_attribute( 'suffix' );
                $aliasEntry['dirpath'] = $imageVariation->get_attribute( 'dirpath' );
                $aliasEntry['alias_key'] = $imageVariation->get_attribute( 'alias_key' );
                $aliasEntry['timestamp'] = $imageVariation->get_attribute( 'timestamp' );
                $aliasEntry['is_valid'] = $imageVariation->get_attribute( 'is_valid' );
                $aliasEntry['url'] = $imageVariation->get_attribute( 'url' );
                $aliasEntry['basename'] = $basename;
                $aliasEntry['alternative_text'] = $alternativeText;
                $aliasEntry['text'] = $displayText;
                $aliasEntry['original_filename'] = $originalFilename;
                $aliasEntry['full_path'] =& $aliasEntry['url'];
                $aliasEntry['is_new'] = false;
                $aliasEntry['info'] =& $imageInformation;
                if ( $aliasEntry['url'] and
                     file_exists( $aliasEntry['url'] ) )
                    $aliasEntry['filesize'] = filesize( $aliasEntry['url'] );

                include_once( 'kernel/common/image.php' );
                $imageManager =& imageInit();
                if ( $imageManager->isImageAliasValid( $aliasEntry ) )
                {
                    $aliasList[$aliasEntry['name']] = $aliasEntry;
                }
            }
        }
        $contentObjectAttribute->DataTypeCustom['alias_list'] =& $aliasList;
        eZDebug::AccumulatorStop( 'imageparse' );
        return $aliasList;
    }

   /*!
     Removes all image alias files which the attribute refers to.

     If you want to remove the alias information use removeAliases().
    */
    function removeAllAliases( &$contentObjectAttribute )
    {
        $files = eZImageFile::fetchForContentObjectAttribute( $contentObjectAttribute->attribute( 'id' ) );
        $dirs = array();
        foreach ( $files as $filepath )
        {
            if ( unlink( $filepath ) )
            {
                $dirs[] = eZDir::dirpath( $filepath );
            }
            else
            {
                eZDebug::writeError( "Image file $filepath does not exist, could not remove from disk",
                                     'eZImageAliasHandler::removeAllAliases' );
            }
        }
        $dirs = array_unique( $dirs );
        foreach ( $dirs as $dirpath )
        {
            eZDir::cleanupEmptyDirectories( $dirpath );
        }
        eZImageFile::removeForContentObjectAttribute( $contentObjectAttribute->attribute( 'id' ) );
    }

    /*!
     Removes all the image aliases and their information.

     The stored images will also be removed if the attribute is the owner
     of the images.

     After the images are removed the attribute will contained an internal structures with empty data.

     \note Transaction unsafe.
    */
    function removeAliases()
    {
        $aliasList =& $this->aliasList();
        $alternativeText = false;
//         $copyOfFilename = $this->copyOfFilename();
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( $this->isImageOwner() )
        {
            foreach ( array_keys( $aliasList ) as $aliasName )
            {
                $alias =& $aliasList[$aliasName];
                if ( $aliasName == 'original' )
                    $alternativeText = $alias['alternative_text'];
                if ( $alias['is_valid'] )
                {
                    $filepath = $alias['url'];
                    if ( unlink( $filepath ) )
                    {
                        eZImageFile::removeFilepath( $contentObjectAttribute->attribute( 'id' ), $filepath );
                        eZDir::cleanupEmptyDirectories( $filepath );
                    }
                    else
                    {
                        eZDebug::writeError( "Image file $filepath for alias $aliasName does not exist, could not remove from disk",
                                             'eZImageAliasHandler::removeAliases' );
                    }
                }
            }
        }
        unset( $aliasList );
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $alternativeText ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', false ) );

        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $doc;
        unset( $contentObjectAttribute->DataTypeCustom['alias_list'] );
        $this->storeDOMTree( $doc );
    }

    /*!
     Will update the path for images to point to the new path \a $dirpath and filename \a $name.

     This is usually called when the object contain the image attribute is moved in the tree.
    */
    function updateAliasPath( $dirpath, $name )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $can_translate = $contentObjectAttribute->attribute( 'can_translate' );
        // get eZContentObject for current contentObjectAttribute
        $obj =& $contentObjectAttribute->object();
        // get eZContentObjectVersion
        $currVerobj =& $obj->currentVersion();
        // get array of ezcontentobjecttranslations
        $transList = & $currVerobj->translations();
        $translationList = array();
        // create translation List
        // $translationList will contain for example eng-GB, ita-IT etc.
        foreach ( $transList as $transListName )
        {
            $translationList[] = $transListName->LanguageCode;
        }
        // get current language_code
        $langCode = $contentObjectAttribute->attribute( 'language_code' );
        // get count of LanguageCode in translationList
        $countTsl = count( $translationList );
        // order by asc
        sort( $translationList );
        if ( !file_exists( $dirpath ) )
        {
            eZDir::mkdir( $dirpath, eZDir::directoryPermission(), true );
        }
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $aliasList =& $this->aliasList();
//         $hasFileCopy = $this->hasFileCopy();
        $this->resetImageSerialNumber();

        foreach ( array_keys( $aliasList ) as $aliasName )
        {
            $alias =& $aliasList[$aliasName];
            if ( $alias['dirpath'] != $dirpath )
            {
                $oldDirpath = $alias['url'];
                $oldURL = $alias['url'];
                $basename = $name;
                if ( $aliasName != 'original' )
                    $basename .= '_' . $aliasName;
                eZMimeType::changeFileData( $alias, $dirpath, $basename );
                $url = $alias['url'];
                if ( $this->isImageOwner() )
                {
                    if ( $oldURL == '' )
                    {
                        continue;
                    }
                    // if more there are more translations and the attribute is not translatable,
                    // then it is necessary to copy the image. Otherwise the image should be removed.
                    if ( !$can_translate and $countTsl > 1 and $translationList[$countTsl - 1] != $langCode )
                    {
                        eZFileHandler::copy( $oldURL, $alias['url'] );
                    }
                    else
                    {
                        eZFileHandler::move( $oldURL, $alias['url'] );
                        eZDir::cleanupEmptyDirectories( $oldDirpath );
                        eZImageFile::moveFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $oldURL, $alias['url'] );
                    }

                }
                else
                {
//                     $hasFileCopy = true;
                    eZFileHandler::linkCopy( $oldURL, $alias['url'], false );
                    eZImageFile::appendFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $alias['url'] );
                }
            }
        }
//         $this->setHasFileCopy( $hasFileCopy );
        $this->recreateDOMTree();
        $this->setStorageRequired();
    }

    /*!
     \private
     Creates XML attributes containing information on the original image attribute.

     The new attributes will be appended to \a $originalNode.
    */
    function createOriginalAttributeXMLData( &$originalNode, $originalData )
    {
        $originalNode->set_attribute( 'attribute_id', $originalData['attribute_id'] );
        $originalNode->set_attribute( 'attribute_version', $originalData['attribute_version'] );
        $originalNode->set_attribute( 'attribute_language', $originalData['attribute_language'] );
//         $originalNode->set_attribute( 'has_file_copy', $originalData['has_file_copy'] );
    }

    /*!
     \private
     Recreates the DOM tree from the internal array structures and stores the DOM tree
     in the 'data_text' field of the attribute.
    */
    function recreateDOMTree()
    {
        $aliasList =& $this->aliasList();

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $originalNode = $doc->createElementNode( "original" );
        $imageNode->appendChild( $originalNode );

        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $aliasName = 'original';

        $originalData = $this->originalAttributeData();
        $this->createOriginalAttributeXMLData( $originalNode, $originalData );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', $this->imageSerialNumber() ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', $aliasList[$aliasName]['is_valid'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $aliasList[$aliasName]['filename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', $aliasList[$aliasName]['suffix'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', $aliasList[$aliasName]['basename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', $aliasList[$aliasName]['dirpath'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', $aliasList[$aliasName]['url'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $aliasList[$aliasName]['original_filename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $aliasList[$aliasName]['mime_type'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', $aliasList[$aliasName]['width'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', $aliasList[$aliasName]['height'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $aliasList[$aliasName]['alternative_text'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( $aliasName ) ) ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', $aliasList[$aliasName]['timestamp'] ) );

        $filename = $aliasList[$aliasName]['filename'];
        if ( $filename )
        {
            include_once( 'lib/ezutils/classes/ezmimetype.php' );
            $mimeData = eZMimeType::findByFileContents( $filename );

            $imageManager->analyzeImage( $mimeData );

            $this->createImageInformationNode( $imageNode, $mimeData );
        }

        foreach ( array_keys( $aliasList ) as $aliasName )
        {
            if ( $aliasName == 'original' )
                continue;
            $imageAlias =& $aliasList[$aliasName];
            $this->addImageAliasToXML( $doc, $imageAlias );
        }

        $this->setDOMTree( $doc );
    }

    /*!
     \return the DOM tree for the current content object attribute.
     \note It will cache the result in the DataTypeCustom member variable of the
           content object attribute in the 'dom_tree' key.
    */
    function &domTree()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        if ( isset( $contentObjectAttribute->DataTypeCustom['dom_tree'] ) )
            return $contentObjectAttribute->DataTypeCustom['dom_tree'];

        $xml = new eZXML();
        $xmlString =& $contentObjectAttribute->attribute( 'data_text' );
        $domTree =& $xml->domTree( $xmlString );
        if ( $domTree == false )
        {
            $this->generateXMLData();
            $domTree =& $xml->domTree( $xmlString );
        }
//         if ( $domTree == false )
//         {
//             $domTree = new eZDOMNode();
//         }
        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;

        return $domTree;
    }

    /*!
     \private
     Parses the information node and generates the internal information structures.

     The information node contains information from the image itself, for instance
     EXIF data from a JPEG or TIFF image.
    */
    function parseInformationNode( &$imageInfoNode, &$imageInformation )
    {
        $imageInformation = array();

        $attributes = $imageInfoNode->attributes();
        foreach ( $attributes as $attribute )
        {
            $imageInformation[$attribute->name()] = $attribute->value;
        }

        $children = $imageInfoNode->children();
        foreach ( $children as $child )
        {
            if ( isset ( $child->name ) )
            {
                $childName = $child->name;
                if ( $childName == 'array' )
                {
                    $name = $child->get_attribute( 'name' );
                    $items = $child->get_elements_by_tagname( 'item' );
                    $array = array();
                    foreach ( $items as $item )
                    {
                        $array[$item->attributeValue( 'key' )] = $item->textContent();
                    }
                    ksort( $array );
                    $imageInformation[$name] = $array;
                }
                else if ( $childName == 'serialized' )
                {
                    $name = $child->get_attribute( 'name' );
                    $data = $child->get_attribute( 'data' );
                    $imageInformation[$name] = unserialize( $data );
                }
            }
        }
    }

    /*!
     \static
     Normalized the image name \a $imageName by removing all characters that are not considered
     filename or URL friendly.
     The filename will also be in non-capital letters.
    */
    function normalizeImageName( $imageName )
    {
        // Initialize transformation system
        include_once( 'lib/ezi18n/classes/ezchartransform.php' );
        $trans =& eZCharTransform::instance();

        $imageName = $trans->transformByGroup( $imageName, 'identifier' );
        return $imageName;
    }

    /*!
     Sets the uploaded HTTP file object to \a $httpFile.
     This object is used to store information about the image file until the content object attribute is to be stored.
     \sa httpFile
    */
    function setHTTPFile( &$httpFile )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['http_file'] =& $httpFile;
    }

    /*!
     \return the stored HTTP file object or \c false if no object is previously stored.
     \sa setHTTPFile
    */
    function &httpFile( $release = false )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( is_array( $contentObjectAttribute->DataTypeCustom ) and
             isset( $contentObjectAttribute->DataTypeCustom['http_file'] ) )
        {
            $httpFile =& $contentObjectAttribute->DataTypeCustom['http_file'];
            if ( $release )
                unset( $contentObjectAttribute->DataTypeCustom['http_file'] );
            return $httpFile;
        }

        $httpFile = false;
        return $httpFile;
    }

    /*!
     Initializes the content object attribute \a $contentObjectAttribute with the uploaded HTTP file \a $httpFile.
     Optionally you may also specify the alternative text in the parameter \a $imageAltText.
    */
    function initializeFromHTTPFile( &$httpFile, $imageAltText = false )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $this->increaseImageSerialNumber();

        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $httpFile->attribute( 'filename' ) );
        if ( !$mimeData['is_valid'] )
        {
            $mimeData = eZMimeType::findByName( $httpFile->attribute( 'mime_type' ) );
            if ( !$mimeData['is_valid'] )
            {
                $mimeData = eZMimeType::findByURL( $httpFile->attribute( 'original_filename' ) );
            }
        }
        $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                 $contentObjectAttribute->attribute( 'contentobject_id' ) );
        $objectName = $this->imageName( $contentObjectAttribute, $contentVersion );
        $objectPathString = $this->imagePath( $contentObjectAttribute, $contentVersion, true );

        eZMimeType::changeBaseName( $mimeData, $objectName );
        eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );

        $this->removeAliases();

        $httpFile->store( false, false, $mimeData );

        $originalFilename = $httpFile->attribute( 'original_filename' );
        return $this->initialize( $mimeData, $originalFilename, $imageAltText );
    }

    /*!
     Initializes the content object attribute \a $contentObjectAttribute with the filename \a $filename.
     Optionally you may also specify the alternative text in the parameter \a $imageAltText.
     \sa initialize
    */
    function initializeFromFile( $filename, $imageAltText = false, $originalFilename = false )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !file_exists( $filename ) )
        {
            eZDebug::writeError( "The image '$filename' does not exist, cannot initialize image attribute with it",
                                 'eZImageAliasHandler::initializeFromFile' );
            return false;
        }

        $this->increaseImageSerialNumber();

        if ( !$originalFilename )
            $originalFilename = $filename;
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $originalFilename );
        $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                 $contentObjectAttribute->attribute( 'contentobject_id' ) );
        $objectName = $this->imageName( $contentObjectAttribute, $contentVersion );
        $objectPathString = $this->imagePath( $contentObjectAttribute, $contentVersion, true );

        eZMimeType::changeBaseName( $mimeData, $objectName );
        eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );
        $this->removeAliases();
        if ( !file_exists( $mimeData['dirpath'] ) )
        {
            eZDir::mkdir( $mimeData['dirpath'], false, true );
        }
        eZFileHandler::copy( $filename, $mimeData['url'] );

        return $this->initialize( $mimeData, $filename, $imageAltText );
    }

    /*!
     Makes sure the attribute contains the image file mentioned in \a $mimeData.
     This involves removing any previous image (and image aliases), increasing
     the image name counter, figuring out the image size and creating
     the internal XML structure.
     \return \c true on success.
    */
    function initialize( $mimeData, $originalFilename, $imageAltText = false )
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $aliasList = array( 'original' => $mimeData );
        $aliasList['original']['alternative_text'] = $imageAltText;
        $aliasList['original']['original_filename'] = $originalFilename;
        if ( $imageManager->createImageAlias( 'original', $aliasList, array( 'basename' => $mimeData['basename'] ) ) )
        {
            $mimeData = $aliasList['original'];
            $mimeData['name'] = $mimeData['mime_type'];
            $aliasList['original']['original_filename'] = $originalFilename;
        }

        $imageManager->analyzeImage( $mimeData );

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $width = false;
        $height = false;
        $info = getimagesize( $mimeData['url'] );
        if ( $info )
        {
            $width = $info[0];
            $height = $info[1];
        }

        $this->setOriginalAttributeDataValues( false, false, false );

        $originalNode = $doc->createElementNode( "original" );
        $imageNode->appendChild( $originalNode );
        $this->createOriginalAttributeXMLData( $originalNode, $this->originalAttributeData() );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', $this->imageSerialNumber() ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', true ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $mimeData['filename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', $mimeData['suffix'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', $mimeData['basename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', $mimeData['dirpath'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', $mimeData['url'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $originalFilename ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $mimeData['name'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', $width ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', $height ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $imageAltText ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( 'original' ) ) ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', time() ) );

        $this->createImageInformationNode( $imageNode, $mimeData );

        $this->setDOMTree( $doc );

        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $contentObjectAttribute->DataTypeCustom['alias_list'] =& $aliasList;

        eZImageFile::appendFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $mimeData['url'] );
        return true;
    }

    function createImageInformationNode( &$imageNode, &$mimeData )
    {
        if ( isset( $mimeData['info'] ) and
             $mimeData['info'] )
        {
            $imageInfoNode = eZDOMDocument::createElementNode( 'information' );
            $info = $mimeData['info'];
            foreach ( $info as $infoItemName => $infoItem )
            {
                if ( is_array( $infoItem ) )
                {
                    $hasScalarValues = true;
                    foreach ( $infoItem as $infoArrayItem )
                    {
                        if ( is_array( $infoArrayItem ) )
                        {
                            $hasScalarValues = false;
                            break;
                        }
                    }
                    if ( !$hasScalarValues )
                    {
                        unset( $serializedNode );
                        $serializedNode = eZDOMDocument::createElementNode( 'serialized',
                                                                             array( 'name' => $infoItemName,
                                                                                    'data' => serialize( $infoItem ) ) );
                        $imageInfoNode->appendChild( $serializedNode );
                    }
                    else
                    {
                        unset( $arrayNode );
                        $arrayNode = eZDOMDocument::createElementNode( 'array',
                                                                        array( 'name' => $infoItemName ) );
                        $imageInfoNode->appendChild( $arrayNode );
                        foreach ( $infoItem as $infoArrayKey => $infoArrayItem )
                        {
                            unset( $arrayItemNode );
                            $arrayItemNode = eZDOMDocument::createElementNode( 'item',
                                                                                array( 'key' => $infoArrayKey ) );
                            $arrayItemNode->appendChild( eZDOMDocument::createTextNode( $infoArrayItem ) );
                            $arrayNode->appendChild( $arrayItemNode );
                        }
                    }
                }
                else
                {
                    $imageInfoNode->appendAttribute( eZDOMDocument::createAttributeNode( $infoItemName, $infoItem ) );
                }
            }
            $imageNode->appendChild( $imageInfoNode );
        }
    }

    /*!
     Adds all the new image alias structures in \a $imageAliasList to the content object attribute.
    */
    function addImageAliases( &$imageAliasList )
    {
        $domTree =& $this->domTree();
        foreach ( array_keys( $imageAliasList ) as $imageAliasName )
        {
            $imageAlias =& $imageAliasList[$imageAliasName];
            if ( $imageAlias['is_new'] )
            {
                $this->addImageAliasToXML( $domTree, $imageAlias );
                $imageAlias['is_new'] = false;
            }
        }
        $this->storeDOMTree( $domTree );
    }

    /*!
     Adds the image alias structure \a $imageAlias to the content object attribute.
    */
    function addImageAlias( $imageAlias )
    {
        $domTree =& $this->domTree();
        $this->addImageAliasToXML( $domTree, $imageAlias );
        $this->storeDOMTree( $domTree );
    }

    /*!
     Adds the image alias structure \a $imageAlias to the XML DOM document \a $domTree.
    */
    function addImageAliasToXML( &$domTree, $imageAlias )
    {
        $imageVariationNodeArray = $domTree->get_elements_by_tagname( 'alias' );
        $imageNode = false;
        if ( is_array( $imageVariationNodeArray ) )
        {
            foreach ( array_keys( $imageVariationNodeArray ) as $imageVariationKey )
            {
                $imageVariation =& $imageVariationNodeArray[$imageVariationKey];
                $aliasEntryName = $imageVariation->get_attribute( 'name' );
                if ( $aliasEntryName == $imageAlias['name'] )
                {
                    $imageNode =& $imageVariation;
                    break;
                }
            }
        }
        if ( !$imageNode )
        {
            if ( is_a( $domTree, 'domdocument' ) )
            {
                $rootNode = $domTree->root();
            }
            else
            {
                $rootNode =& $domTree->root();
            }

            $imageNode = $domTree->create_element( "alias" );
            $rootNode->append_child( $imageNode );
        }
        else
        {
            $imageNode->remove_attribute( 'name' );
            $imageNode->remove_attribute( 'filename' );
            $imageNode->remove_attribute( 'suffix' );
            $imageNode->remove_attribute( 'dirpath' );
            $imageNode->remove_attribute( 'url' );
            $imageNode->remove_attribute( 'mime_type' );
            $imageNode->remove_attribute( 'width' );
            $imageNode->remove_attribute( 'height' );
            $imageNode->remove_attribute( 'alias_key' );
            $imageNode->remove_attribute( 'timestamp' );
            $imageNode->remove_attribute( 'is_valid' );
        }
        $imageNode->set_attribute( 'name', $imageAlias['name'] );
        $imageNode->set_attribute( 'filename', $imageAlias['filename'] );
        $imageNode->set_attribute( 'suffix', $imageAlias['suffix'] );
        $imageNode->set_attribute( 'dirpath', $imageAlias['dirpath'] );
        $imageNode->set_attribute( 'url', $imageAlias['url'] );
        $imageNode->set_attribute( 'mime_type', $imageAlias['mime_type'] );
        $imageNode->set_attribute( 'width', $imageAlias['width'] );
        $imageNode->set_attribute( 'height', $imageAlias['height'] );
        $imageNode->set_attribute( 'alias_key', $imageAlias['alias_key'] );
        $imageNode->set_attribute( 'timestamp', $imageAlias['timestamp'] );
        $imageNode->set_attribute( 'is_valid', $imageAlias['is_valid'] );

//        var_dump($imageNode);
    }

    /*!
     Sets the XML DOM document \a $domTree as the current DOM document.
    */
    function setDOMTree( &$domTree )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = true;
    }

    /*!
     Stores the XML DOM document \a $domTree to the content object attribute.
    */
    function storeDOMTree( &$domTree, $storeAttribute = true )
    {
        if ( !$domTree )
            return false;
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = false;
        $xmlString = $domTree->dump_mem();
        $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
        if ( $storeAttribute )
            $contentObjectAttribute->storeData();
        return true;
    }

    /*!
     Stores the data in the image alias handler to the content object attribute.
     \sa isStorageRequired, setStorageRequired
    */
    function store()
    {
        $domTree =& $this->domTree();
        if ( $domTree )
            $this->storeDOMTree( $domTree, true );
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = false;
    }

    /*!
     \return \c true if the image alias handler is required to store it's contents.
     \sa setStorageRequired, store
    */
    function isStorageRequired()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( is_array( $contentObjectAttribute->DataTypeCustom ) and
             isset( $contentObjectAttribute->DataTypeCustom['is_storage_required'] ) )
            return $contentObjectAttribute->DataTypeCustom['is_storage_required'];
        return false;
    }

    /*!
     Sets whether storage of the image alias data is required or not.
     \sa isStorageRequired, store
    */
    function setStorageRequired( $require = true )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = $require;
    }

//     function &copyOfFilename()
//     {
//         $contentObjectAttribute =& $this->ContentObjectAttribute;
//         $copyOf = false;
//         if ( is_array( $contentObjectAttribute->DataTypeCustom ) and
//              isset( $contentObjectAttribute->DataTypeCustom['copy_of'] ) )
//             $copyOf = $contentObjectAttribute->DataTypeCustom['copy_of'];
//         return $copyOf;
//     }

    /*!
     \return An array structure with information on which attribute
             originally created the current data.

     This will only contain data if the attribute is a copy of
     another attribute, e.g in the case of a new version without an new image upload.
    */
    function &originalAttributeData()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( isset( $contentObjectAttribute->DataTypeCustom['original_data'] ) )
            return $contentObjectAttribute->DataTypeCustom['original_data'];
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $originalData = array( 'attribute_id' => false,
                               'attribute_version' => false,
                               'attribute_language' => false );
        $contentObjectAttribute->DataTypeCustom['original_data'] =& $originalData;
        return $originalData;
    }

    /*!
     Sets the information on which attribute the data was fetched from.
     See eZImageAliasHandler::originalAttributeData() for more information.
    */
    function setOriginalAttributeData( $originalData )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['original_data'] =& $originalData;

        $domTree =& $this->domTree();
        $imageOriginalArray = $domTree->get_elements_by_tagname( "original" );
        if ( isset( $imageOriginalArray[0] ) )
            $this->createOriginalAttributeXMLData( $imageOriginalArray[0], $originalData );
    }

    /*!
     Sets the information on which attribute the data was fetched from.

     Fetches data from the contentobject attribute \a $contentObjectAttribute and
     sets it using setOriginalAttributeData().
    */
    function setOriginalAttributeDataFromAttribute( &$contentObjectAttribute )
    {
        $originalImageHandler =& $contentObjectAttribute->attribute( 'content' );
        $originalAttributeData =& $originalImageHandler->originalAttributeData();
        $domTree =& $originalImageHandler->domTree();
        $this->setDOMTree( $domTree );
        if ( $originalAttributeData['attribute_id'] )
        {
            $this->setOriginalAttributeData( $originalAttributeData );
        }
        else
        {
            $this->setOriginalAttributeDataValues( $contentObjectAttribute->attribute( 'id' ),
                                                   $contentObjectAttribute->attribute( 'version' ),
                                                   $contentObjectAttribute->attribute( 'language_code' ),
                                                   false );
        }
    }

    /*!
     Sets the information on which attribute the data was fetched from.

     Fetches data from the parameters and sets it using setOriginalAttributeData().
    */
    function setOriginalAttributeDataValues( $attributeID, $attributeVersion, $attributeLanguage )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $originalData = array( 'attribute_id' => $attributeID,
                               'attribute_version' => $attributeVersion,
                               'attribute_language' => $attributeLanguage );
        $this->setOriginalAttributeData( $originalData );
    }

//     function hasFileCopy()
//     {
//         $originalData = $this->originalAttributeData();
//         return $originalData['has_file_copy'];
//     }

//     function setHasFileCopy( $hasFileCopy )
//     {
//         $originalData =& $this->originalAttributeData();
//         $originalData['has_file_copy'] = $hasFileCopy;
//     }

//     function setCopyOfFilename( $copyOf )
//     {
//         $contentObjectAttribute =& $this->ContentObjectAttribute;
//         if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
//             $contentObjectAttribute->DataTypeCustom = array();
//         $aliasList =& $this->aliasList();
//         $contentObjectAttribute->DataTypeCustom['copy_of'] = $copyOf;
//         $this->recreateDOMTree();
//         $this->setStorageRequired();
//     }

    /*!
     \private
     \return The internal serial number.

     It will check if a serial number exists and return that, if not a new one will be created and returned.
    */
    function &imageSerialNumberRaw()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( isset( $contentObjectAttribute->DataTypeCustom['serial_number'] ) and
             $contentObjectAttribute->DataTypeCustom['serial_number'] >= 0 )
            return $contentObjectAttribute->DataTypeCustom['serial_number'];
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['serial_number'] = 0;
        return $contentObjectAttribute->DataTypeCustom['serial_number'];
    }

    /*!
     Fetches image information from the old 3.2 image system and creates new information.
    */
    function generateXMLData()
    {
        include_once( "lib/ezdb/classes/ezdb.php" );

        $db =& eZDB::instance();

        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $attributeID = $contentObjectAttribute->attribute( 'id' );
        $attributeVersion = $contentObjectAttribute->attribute( 'version' );

        if ( is_numeric( $attributeID ) )
        {
            $imageRow = $db->arrayQuery( "SELECT * FROM ezimage
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );
        }

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $isValid = false;
        $fileName = false;
        $suffix = false;
        $baseName = false;
        $dirPath = false;
        $filePath = false;
        $originalFileName = false;
        $mimeType = false;
        $width = false;
        $height = false;
        $altText = false;

        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        if ( count( $imageRow ) == 1 )
        {
            $fileName = $imageRow[0]['filename'];
            $originalFileName = $imageRow[0]['original_filename'];
            $mimeType = $imageRow[0]['mime_type'];
            $altText = $imageRow[0]['alternative_text'];

            $dirPath = eZSys::storageDirectory() . '/original/image';
            $filePath = $dirPath . '/' . $fileName;
            $baseName = $fileName;
            $dotPosition = strrpos( $fileName, '.' );
            if ( $dotPosition !== false )
            {
                $baseName = substr( $fileName, 0, $dotPosition );
                $suffix = substr( $fileName, $dotPosition + 1 );
            }

            $width = false;
            $height = false;
            if ( !file_exists( $filePath ) )
            {
                $referenceDirPath = eZSys::storageDirectory() . '/reference/image';
                $suffixList = array( 'jpg', 'png', 'gif' );
                foreach ( $suffixList as $suffix )
                {
                    $referenceFilePath = $referenceDirPath . '/' . $baseName . '.' . $suffix;
                    if ( file_exists( $referenceFilePath ) )
                    {
                        $filePath = $referenceFilePath;
                        $dirPath = $referenceDirPath;
                        break;
                    }
                }
            }

            if ( file_exists( $filePath ) )
            {
                $isValid = true;
                $info = getimagesize( $filePath );
                if ( $info )
                {
                    $width = $info[0];
                    $height = $info[1];
                }
                $mimeInfo = eZMimeType::findByFileContents( $filePath );
                $mimeType = $mimeInfo['name'];

                $newFilePath = $filePath;
                $newSuffix = $suffix;
                $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                         $contentObjectAttribute->attribute( 'contentobject_id' ) );
                if ( $contentVersion )
                {
                    $objectName = $this->imageName( $contentObjectAttribute, $contentVersion );
                    $objectPathString = $this->imagePath( $contentObjectAttribute, $contentVersion );

                    $newDirPath =  $objectPathString;
                    $newFileName = $objectName . '.' . $mimeInfo['suffix'];
                    $newSuffix = $mimeInfo['suffix'];
                    $newFilePath = $newDirPath . '/' . $newFileName;
                    $newBaseName = $objectName;
                }

                if ( $newFilePath != $filePath )
                {
                    if ( !file_exists( $newDirPath ) )
                    {
                        include_once( 'lib/ezfile/classes/ezdir.php' );
                        eZDir::mkdir( $newDirPath, eZDir::directoryPermission(), true );
                    }
                    eZFileHandler::copy( $filePath, $newFilePath );
                    $filePath = $newFilePath;
                    $fileName = $newFileName;
                    $suffix = $newSuffix;
                    $dirPath = $newDirPath;
                    $baseName = $newBaseName;
                }
            }

        /*
        // Fetch variations
        $imageVariationRowArray = $db->arrayQuery( "SELECT * FROM ezimagevariation
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );

        foreach ( $imageVariationRowArray as $variationRow )
        {
            unset( $imageVariationNode );
            $imageVariationNode = $doc->createElementNode( "variation" );
            $imageNode->appendChild( $imageVariationNode );

            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'name', 'medium' ) );

            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'filename', $variationRow['filename'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'additional_path', $variationRow['additional_path'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'width', $variationRow['width'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'height', $variationRow['height'] ) );

        }
        */
        }
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $mimeData = eZMimeType::findByFileContents( $fileName );

        $imageManager->analyzeImage( $mimeData );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', $isValid ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $fileName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', $suffix ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', $baseName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', $dirPath ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', $filePath ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $originalFileName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $mimeType ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', $width ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', $height ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $altText ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( 'original' ) ) ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', time() ) );

        $this->createImageInformationNode( $imageNode, $mimeData );

        $this->storeDOMTree( $doc );

        eZImageFile::appendFilepath( $contentObjectAttribute->attribute( 'id' ), $filePath );
    }

    /// \privatesection
    /// Contains a reference to the object attribute
    var $ContentObjectAttribute;
}
?>
