<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/summary/summary.inc,v $
## $Revision: 1.4 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:13:32 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Summary extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array(
			'category_list',
			'AVJ_meta',
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
		echo("<input type=checkbox name=\"".get_class($this)."_editable\" value=1 ".(($this->get_value("editable"))?"checked":"")."> Editable.<br>");
		echo("<input type=checkbox name=\"".get_class($this)."_hide_default\" value=1 ".(($this->get_value('hide_default'))?"checked":"")."> Hide default if varieties are available.<br>");
		echo combo_box(get_class($this).'_format',array_merge(array('_default_'=>"-- Default Format --"),$this->attribute->get_type_setting('value_formats')),$this->get_value('format'),'class=data');
		echo '<br>';
		echo combo_box(get_class($this).'_show_varieties',array(''=>'Only Show Default','H'=>'List Varieties Horizontally','V'=>'List Varieties Vertically'),$this->get_value('show_varieties'),'class=data');
	}

	function process_backend() {
		$var_prefix = get_class($this).'_';
		$global_vars = array('editable','hide_default','show_varieties','format');
		foreach($global_vars as $global_var) {
			//global ${$var_prefix.$global_var};
			$$global_var = $_REQUEST[$var_prefix.$global_var];
			if (is_string($$global_var)) $$global_var = gpc_stripslashes($$global_var);
		}
		$this->parameters['editable'] = $editable;
		$this->parameters['hide_default'] = $hide_default;
		$this->parameters['show_varieties'] = $show_varieties;
		$this->parameters['format'] = $format;
		$this->save_parameters();
	}
}
?>