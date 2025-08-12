<?php
  /**
    * $Id: serialized_models.class.php,v 1.5 2005/07/05 10:36:05 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package common
    */
    
  /**
    *
    */
    class serialized_models {

        var $img_path                   = null;
        var $currentObjectType          = null;
        
       /**
        * Constructor.
        *
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @package      easy_framework
        * @since        0.4.6
        * @version      0.4.6
        */
        function serialized_models ($type, $img_path) { 
            $this->currentObjectType = $type;
            $this->img_path = $img_path;
        }
        
        function getClipboardHTML () {
            global $db_hdl;
            
            $ret = '';
            
            $query = "SELECT * FROM ".TABLE_PREFIX."serialized_models 
                      WHERE object_type='".$this->currentObjectType."'
                      	AND user_id=".$_SESSION['user_id']."
						AND save_as='clipboard'
                      ORDER BY name";
            $res   = $db_hdl->Execute ($query);
            $cnt   = $res->RecordCount();
            if ($cnt == 0) 
                return $ret;

            $ret .= "<TR class='line'>
	                    <TH class='box' colspan=2>".translate ('clipboard')."</TH>
                		<TD>
						<table>
		            ";

            while (!$res->EOF) {
            	$ret .= "<tr>";
				$ret .= "<td width='20'><img src='".$this->img_path."clipboard.gif' title='".translate('clipboard', null, true)."'></td>";
                //$ret .= "<td><a href='index.php?command=unserialize&model_id=".$res->fields['model_id']."'>";
                $ret .= "<td>".$res->fields['ts']."</td>";
				$ret .= "<td width='350'><a href='javascript:unserialize_model (".$res->fields['model_id'].");'>";
				$ret .= $res->fields['name']."</a></td>";
                //$ret .= "<td><img src='".$this->img_path."delete2.gif' title='".translate('delete', null, true)."'></td>";
                //$ret .= "<td align='right'><input type='checkbox' name='delete_model'></td>";
            	$ret .= "</tr>";
                $res->MoveNext();   
            }      
		            
		            
            $ret .= "   </table>
						</TD>
	                </TR>
	                ";
	                
            $ret .= "<tr>
                    <td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>
            	  </tr>\n";
            	  
            return $ret;	                        
            
        }            

        function getTemplateHTML () {
            global $db_hdl;
            
            $ret = '';
            
            $query = "SELECT * FROM ".TABLE_PREFIX."serialized_models mi
                      WHERE object_type='".$this->currentObjectType."'
                      	AND 
							(user_id=".$_SESSION['user_id']."
								OR
								(".get_all_groups_or_statement ($_SESSION['user_id']).")
							)
						AND save_as='template'
                      ORDER BY name";
            
            $res   = $db_hdl->Execute ($query);
            $cnt   = $res->RecordCount();
            if ($cnt == 0) 
                return $ret;

            $ret .= "<TR class='line'>
	                    <TH class='box' colspan=2>".translate ('templates')."</TH>
                		<TD>
						<table>
		            ";

            while (!$res->EOF) {
            	$ret .= "<tr>";
				$ret .= "<td width='20'><img src='".$this->img_path."template.gif' title='".translate('clipboard', null, true)."'></td>";
                //$ret .= "<td><a href='index.php?command=unserialize&model_id=".$res->fields['model_id']."'>";
                $ret .= "<td>".$res->fields['ts']."</td>";
                $ret .= "<td width='350'><a href='javascript:get_template(".$res->fields['model_id'].")'>";
				$ret .= $res->fields['name']."</a></td>";
                //$ret .= "<td align='right'><img src='".$this->img_path."/delete2.gif'></td>";
            	$ret .= "</tr>";
                $res->MoveNext();   
            }      
		            
		            
            $ret .= "   </table>
						</TD>
	                </TR>
	                ";
	                
            $ret .= "<tr>
                    <td class='narrow' colspan=5><img src='".$this->img_path."shim.gif' height=1></td>
            	  </tr>\n";
            	  
            return $ret;	                        
            
        }            

    }

?>