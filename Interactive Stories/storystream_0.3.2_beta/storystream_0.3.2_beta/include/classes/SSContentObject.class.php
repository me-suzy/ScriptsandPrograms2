<?php

class SSContentObject extends SSTableObject
{
    /**
     * Adds all associated properties
     * This need only be called once per instantiation of this class
     * and is handled automatically by the base class as long
     * as its constructor is called.
     */
    function _addProperties ()
    {
		$this->_addProperty (PROP_LICENSE_URL, '');
		$this->_addProperty (PROP_LICENSE_NAME, '');
		$this->_addProperty (PROP_LICENSE_CODE, '');
        $this->_addProperty (PROP_DATA_TYPE, 0);
        $this->_addProperty (PROP_DATA_BINARY, '');
        $this->_addProperty (PROP_DATA_PROPERTIES, '');
		
		parent::_addProperties ();
    } 

	/** Puts smarty variables containing media related properties into the given array
	 * 	@param array $array The array that will contain the smarty variables on output.
 	 */
	function prepareSmartyMediaVariables (&$array)
	{
        $mediaType = $this->getBinaryDataType();                
        $dataProps = $this->get (PROP_DATA_PROPERTIES);
        $dataTypeString = $this->get (PROP_DATA_TYPE) ? $this->get (PROP_DATA_TYPE) : 'No Media';
        
        $props = array ();
        parse_str ($dataProps, $props);
                
        $array = array ('binary_preview'=>$this->getBinaryLinkOrPreview(),'type'=>$mediaType, 'link'=>$this->getBinaryURL(), 'type_string'=>$dataTypeString);

        // Now add media properties.        
        foreach ($props as $key=>$value) {
	        $array [$key] = $value;
        }
	}
	
    /** Retrieves the binary data from the database for this scene.
     *
     * The binary data is returned as a string in the 'content' field of the returned array.
     *
     * @return mixed If successful, an associative array is returned where the 'content'
     *    				field contains the content of the binary and the 'type' field
     *    				contains the mime-type.
     */
    function getBinary () {
	    
     	$dbQuery = "SELECT data_type, data_binary ";
		$dbQuery .= "FROM ss_scene ";
		$dbQuery .= "WHERE scene_id = ".$this->get (PROP_ID);
		$result = mysql_query($dbQuery) or $this->addError (STR_36, ERROR_TYPE_SERIOUS);
		
		if(mysql_num_rows($result) == 1)
		{
			$fileType = @mysql_result($result, 0, "data_type");
			$fileContent = @mysql_result($result, 0, "data_binary");
			
			return array ('content'=>$fileContent, 'type'=>$fileType);	
	    }    
	    
	    return false;
   }
    	
	/** Converts the mime type stored in the dbase to a simplified constant
		@return integer One of the 'SCENE_DATA' constants.
	*/
	function getBinaryDataType () {
	
		$type = $this->get (PROP_DATA_TYPE);
		
		// See if this is an image
		if (is_integer (strpos ($type, 'image'))) {
		
			return SCENE_DATA_IMAGE;
		}		
		// See if this is a Flash animation
		else if (is_integer (strpos ($type, 'x-shockwave-flash'))) {
		
			return SCENE_DATA_FLASH;
		}
		// See if this is a sound file
		else if (is_integer (strpos ($type, 'x-mpegurl')) ||
			is_integer (strpos ($type, 'mpeg3')) || 
			is_integer (strpos ($type, 'x-mpeg-3')) ||
			is_integer (strpos ($type, 'x-mpeg')) ||
			is_integer (strpos ($type, 'mpeg')) ||
			is_integer (strpos ($type, 'x-mpeg'))) {
			
			return SCENE_DATA_SOUND;
		}
		else {
			
			return SCENE_DATA_NONE;
		}
	}

	/** Gets the path to the file that will render the binary data
	 *  @return string The URL to the file that will render the media.
	 */
	function getBinaryURL () {
		return AUTHORING_ROOT.'/download.php?t='.$this->getType().'&i='.$this->get (PROP_ID);
	}	
		
	/** Converts the stored binary data into a link to that data.  If a preview is possible, then a preview is displayed
		If a preview cannot be displayed then a suitable link is provided
		@return string The link HTML or the preview HTML
	*/
	function getBinaryLinkOrPreview () {
	
		switch ($this->getBinaryDataType ()) {
		case SCENE_DATA_IMAGE:
			return '<img width="100" src="'.$this->getBinaryURL().'">';
		case SCENE_DATA_FLASH:
			return '<a target="_blank" href="'.$this->getBinaryURL().'">Open Flash animation in new window</a>';
		case SCENE_DATA_SOUND:
			return '<a target="_blank" href="'.$this->getBinaryURL().'">Download music/sound</a>';
		case SCENE_DATA_NONE:
			return 'There are no media associated with this scene';
		}
	}
	
    /**
     * Checks to see if the URL contains updated license info and if it
	 *	does then assumes that the page is being opened as an exit URL
	 *	from the Creative Commons license selection site.  It changes
	 *	the license selected then refreshes the originating page.
     */
	function updateLicense () {
	
		// Check to see if the page is being updated after a license change.
		if (isset ($_GET['license_url'])) {
			
			$license_url = $this->queryGetValue ('license_url');
			$license_name = $this->queryGetValue ('license_name');
			$license_code = $this->queryGetValue ('license_code');
			
			$user = $GLOBALS['APP']->getLoggedInUserObject();
			if ($user && ($user->get (PROP_USERNAME) == $this->get (PROP_USERNAME))) {
			
				// Store the old values in case the database
				//	cannot be updated and we need to revert.
				$old_license_url = $this->get (PROP_LICENSE_URL);
				$old_license_name = $this->get (PROP_LICENSE_NAME);
				$old_license_code = $this->get (PROP_LICENSE_CODE);
			
				// Go ahead and change the license.
				$this->set (PROP_LICENSE_URL, $license_url);
				$this->set (PROP_LICENSE_NAME, $license_name);
				$this->set (PROP_LICENSE_CODE, $license_code);
										
				if (!$this->update ()) {
				
					$this->addError (STR_37, ERROR_TYPE_SERIOUS);
					
					$this->set (PROP_LICENSE_URL, $old_license_url);
					$this->set (PROP_LICENSE_NAME, $old_license_name);
					$this->set (PROP_LICENSE_CODE, $old_license_code);
				}
				else {
					$this->addNotification (STR_38);
				}
			}	
			else {
				$this->addError (STR_39, ERROR_TYPE_SERIOUS);
			}			
		}
	}
	
	/** Handles submission of binary media file to the database
	*/
	function handleMediaSubmit () {
	
		// The user has uploaded a file.
		if (isset ($_FILES ['binary_data_file']['name']) && 
			($_FILES ['binary_data_file']['name'] != '')) {
		
			$doUpdate = true;
		
			$uploader = new SSFileUpload;
			if ($uploader->upload ('binary_data_file', 'audio/mpeg3 | audio/x-mpeg-3 | audio/mpeg | audio/x-mpegurl | audio/mpeg3 | application/x-shockwave-flash  | image/png | image/jpeg | image/pjpeg')) {
								
				$this->set (PROP_DATA_BINARY, $uploader->getFileBuffer ());
				$this->set (PROP_DATA_TYPE, $uploader->getFileType());

				switch ($this->getBinaryDataType ()) {
				case SCENE_DATA_IMAGE:											
					// Store image properties in the database for future reference
					list ($width, $height, $type, $attr) = getimagesize ($uploader->getFilePath());
					$this->set (PROP_DATA_PROPERTIES, "width=$width&height=$height");
					break;
				case SCENE_DATA_FLASH:											
					$this->set (PROP_DATA_PROPERTIES, "");					
					break;						
				default:					
					$doUpdate = false;
					$this->addError (STR_40, ERROR_TYPE_SERIOUS);
				}
									
				if ($doUpdate) {
					if (!$this->update ()) {											
						$this->addError (STR_41, ERROR_TYPE_SERIOUS);
						$doUpdate = false;
					}
				}
			}
			else {				
				$doUpdate = false;					
				$this->addError ($uploader->error, ERROR_TYPE_SERIOUS);
			}

			if (!$doUpdate) {				
				$this->addNotification (STR_42, ERROR_TYPE_SERIOUS);		
			}
		}
	}
	/** 
	 * Retrieves information about a property value
	 *	Information that is retrieved includes the following:
	 *	
	 *	'name' - The friendly name for the property
	 *	'diff' - Whether or not the property can be diffed against other versions
	 *
	 * @param string $key The name of the property
	 * @return array See the description for a list of the array contents.
	 */
	function getPropertyInfo ($key) {
		
		$name = '';
		$diff = false;
		$mapping = array ();
		
		switch ($key) {
			case PROP_LICENSE_URL:
				$name = 'license url';
				$diff = false;
				break;
			case PROP_LICENSE_NAME:
				$name = 'license name';
				$diff = true;
				break;
			case PROP_LICENSE_CODE:
				$name = 'license code';
				$diff = true;
				break;
			case PROP_DATA_TYPE:
				$name = 'media type';
				$diff = true;
				break;
			case PROP_DATA_BINARY:
				$name = 'media file';
				$diff = false;
				break;
			case PROP_DATA_PROPERTIES:
				$name = 'media properties';
				$mapping = array (STORY_STATUS_ACTIVE=>'active', STORY_STATUS_DRAFT=>'draft', STORY_STATUS_DELETED=>'deleted');
				$diff = false;
				break;
			default:
				$parent = parent::getPropertyInfo ($key);
				break;
		}
		
		return array ('name'=>$name, 'mapping'=>$mapping, 'diff'=>$diff);
	}	
}
?>