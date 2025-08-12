<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/name/name.inc,v $
## $Revision: 1.4 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:13:32 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Name extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array(
			'AVJ_meta',
			'category_list',
			'datetime',
			'equation',
			'file_reference',
			'foreign_key',
			'hyperlink',
			'ISC_user_reference',
			'meta',
			'number',
			'option',
			'random_file',
			'selection',
			'text',
			'user_reference',
			'wysiwyg'
		)
	);

	 ####################################################
	# Requires only a bit of screen space for its thingy
	function parameter_interface_level() {
		return 1;
	}

	 ###################
	# Prints da backend
	function print_backend() {
		echo text_box(get_class($this).'_pre',$this->get_value('pre'),10,50,'class=data');
		echo(" Append to existing name with this prefix.<br>");
		echo text_box(get_class($this).'_suf',$this->get_value('suf'),10,50,'class=data');
		echo(" Append to existing name with this suffix.");
		echo("<br>");
		echo combo_box(get_class($this).'_format',array_merge(array('_default_'=>"-- Default Format --"),$this->attribute->get_type_setting('value_formats')),$this->get_value('format'),'class=data');
	}

	function process_backend() {
		$var_prefix = get_class($this)."_";
		$global_vars = array('pre','suf','format');
		foreach($global_vars as $global_var) {
			$$global_var = $_REQUEST[$var_prefix.$global_var];
			if (is_string($$global_var)) $$global_var = gpc_stripslashes($$global_var);
		}
		$this->parameters['pre'] = $pre;
		$this->parameters['suf'] = $suf;
		$this->parameters['format'] = $format;
		$this->save_parameters();
	}
}
?>