<?php

class BOCalendar extends BizDataObj
{
   public function Render()
   {
      $do = $_GET['do'];
      if ($do == 'NEW') {
         $this->SetDisplayMode(MODE_N);
         $this->UpdateActiveRecord($this->GetDataObj()->NewRecord());
         return $this->RenderHTML();
      }

      return parent::Render();
   }
   
   public function Invite()
   {
      // get the attendee list from the objref
      $attdDataObj = $this->GetDataObj()->GetRefObject("BOCalAttendee");
      $attdList = array();
      $attdDataObj->FetchRecords("",$attdList);
      foreach ($attdList as $attdRec)
         $attd_ids = $attdRec["Id"];
      
      // query events with the day against the attendee list
      $recArr = $this->GetDataObj()->GetRecord(0);
      $st = $recArr['Start'];
      $et = $recArr['End'];
      $searchRule = "([Repeated]<>'Y' AND [Start]>='$st' AND [Start]<'$et')";
      $searchRule .= " OR ([Repeated]='Y' AND [RepeatEnd]>'$st')";
      $qry_events = array();
   	$this->GetDataObj()->FetchRecords($searchRule, $qry_events);
   }
}
?>