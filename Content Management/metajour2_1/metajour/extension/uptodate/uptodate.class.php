<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path."extension/basicextension.class.php");

class ext_uptodate extends basicextension {

	function ext_uptodate() {
		$this->basicextension();
		$this->extname = 'uptodate';
		$this->addextparam('templatename');
	}

	function _do() {
		$begin = date('Y-m-d',time()-691200);
		$end = date('Y-m-d',time()+691200);
		
		switch ($this->extcmd) {
			default:
				$obj = owNew('employee');
				$obj->setFilter_RawWhere("AND (
				YEAR('$begin') = YEAR('$end')
				 AND MONTH(employee.birthday) * 100 + DAY(employee.birthday) 
				 			BETWEEN 
				 				MONTH('$begin') * 100 + DAY('$begin') 
				 			AND 
				 				MONTH('$end') * 100 + DAY('$end')
				 )
				");
				## ORDER
				$obj->listObjects();
				$this->extresult['birthday'] = $obj->elements;

				$obj = owNew('employee');
				$obj->setFilter_RawWhere("
					AND (employee.specialdate1 BETWEEN '$begin' AND '$end')
				");
				$obj->listObjects();
				$this->extresult['specialdate'] = $obj->elements;

				$obj = owNew('calevent');

				$begin = date('Y-m-d',time()-691200);
				$end = date('Y-m-d',time()+691200);

				$obj->setFilter_RawWhere("
				AND (
				(begindate <= '$begin' AND enddate >= '$end')
				OR
				(begindate >= '$begin' AND enddate <= '$end')
				OR
				(begindate >= '$begin' AND begindate <= '$end' AND enddate >= '$end')
				OR
				(begindate <= '$begin' AND enddate >= '$begin' AND enddate <= '$end')
				)
				");
				$obj->readAllObjectsByParentId();
				$tmp = $obj->elements;
				$res = array();
				
				if (is_array($tmp)) {
					foreach ($tmp as $key => $cur) {
						foreach($cur as $skey => $scur) {
							$scur['employee'] = $key;
							$res[$scur['caleventtypeid']][] = $scur;
						}
					}
				}
				$this->extresult['other'] = $res;
				#$this->useTemplate('templatename','templateid','standard_uptodate_list');
		}
	}
}

?>
