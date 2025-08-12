<div style="page-break-before: always;">
<h2>school_contact</h2>
Table comments:&nbsp;Contact Information<br /><br />
<!-- TABLE INFORMATIONS -->
<table width="100%" style="border: 1px solid black; border-collapse: collapse; background-color: white;">
<tr>
    <th width="50">Field</th>

    <th width="80">Type</th>
    <!--<th width="50">Attributes</th>-->
    <th width="40">Null</th>
    <th width="70">Default</th>
    <!--<th width="50">Extra</th>-->

    <th>Comments</th>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">

    <u>id</u>&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>

    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">auto_increment&nbsp;</td>-->

    <td class="print">Unique Identifier</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    name&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>

    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    mailingname&nbsp;

    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">

    address&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">text<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>

    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    phone&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>

    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

</table>

    </div>

<div style="page-break-before: always;">
<h2>school_enrolled</h2>
Table comments:&nbsp;InnoDB free: 20480 kB<br /><br />
<!-- TABLE INFORMATIONS -->
<table width="100%" style="border: 1px solid black; border-collapse: collapse; background-color: white;">
<tr>
    <th width="50">Field</th>
    <th width="80">Type</th>
    <!--<th width="50">Attributes</th>-->

    <th width="40">Null</th>
    <th width="70">Default</th>
    <!--<th width="50">Extra</th>-->

    <th>Comments</th>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">


    studentid&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">0&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->


    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    subectcode&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">0&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

</table>

    </div>
<div style="page-break-before: always;">

<h2>school_rollclass</h2>
Table comments:&nbsp;InnoDB free: 20480 kB<br /><br />
<!-- TABLE INFORMATIONS -->
<table width="100%" style="border: 1px solid black; border-collapse: collapse; background-color: white;">
<tr>
    <th width="50">Field</th>
    <th width="80">Type</th>
    <!--<th width="50">Attributes</th>-->
    <th width="40">Null</th>

    <th width="70">Default</th>
    <!--<th width="50">Extra</th>-->

    <th>Comments</th>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">

    <u>rollclasscode</u>&nbsp;

    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">0&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>

</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    rollclasstitle&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>

    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    rollclassyear&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>

    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

</table>

    </div>

<div style="page-break-before: always;">
<h2>school_sportshouse</h2>
Table comments:&nbsp;InnoDB free: 20480 kB<br /><br />
<!-- TABLE INFORMATIONS -->
<table width="100%" style="border: 1px solid black; border-collapse: collapse; background-color: white;">
<tr>
    <th width="50">Field</th>
    <th width="80">Type</th>
    <!--<th width="50">Attributes</th>-->

    <th width="40">Null</th>
    <th width="70">Default</th>
    <!--<th width="50">Extra</th>-->

    <th>Comments</th>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">


    <u>housecode</u>&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">auto_increment&nbsp;</td>-->


    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    housecolour&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    housename&nbsp;

    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>


</table>

    </div>
<div style="page-break-before: always;">
<h2>school_students</h2>
Table comments:&nbsp;InnoDB free: 20480 kB<br /><br />
<!-- TABLE INFORMATIONS -->
<table width="100%" style="border: 1px solid black; border-collapse: collapse; background-color: white;">
<tr>
    <th width="50">Field</th>

    <th width="80">Type</th>
    <!--<th width="50">Attributes</th>-->
    <th width="40">Null</th>
    <th width="70">Default</th>
    <!--<th width="50">Extra</th>-->

    <th>Comments</th>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">

    <u>ID</u>&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>

    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">auto_increment&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    firstname&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>

    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    surname&nbsp;

    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>


<tr>
    <td width=50 class='print' nowrap="nowrap">

    gender&nbsp;
    </td>
    <td width="80" class="print">set('male', 'female')<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>

    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    dob&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>

    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    contactid&nbsp;

    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">0&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>

</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    rollcode&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>

    <td width="70" class="print" nowrap="nowrap">0&nbsp;</td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    housecode&nbsp;
    </td>

    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">0&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>


</table>

    </div>
<div style="page-break-before: always;">
<h2>school_subjects</h2>
Table comments:&nbsp;InnoDB free: 20480 kB<br /><br />
<!-- TABLE INFORMATIONS -->
<table width="100%" style="border: 1px solid black; border-collapse: collapse; background-color: white;">
<tr>
    <th width="50">Field</th>
    <th width="80">Type</th>

    <!--<th width="50">Attributes</th>-->
    <th width="40">Null</th>
    <th width="70">Default</th>
    <!--<th width="50">Extra</th>-->

    <th>Comments</th>
</tr>


<tr>

    <td width=50 class='print' nowrap="nowrap">

    <u>subjectcode</u>&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">int(32)<bdo dir="ltr"></bdo></td>
    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>

    <!--<td width="50" bgcolor="#D5D5D5" nowrap="nowrap">auto_increment&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

<tr>
    <td width=50 class='print' nowrap="nowrap">

    subjectname&nbsp;
    </td>
    <td width="80" class="print" nowrap="nowrap">varchar(32)<bdo dir="ltr"></bdo></td>

    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->
    <td width="40" class="print">No&nbsp;</td>
    <td width="70" class="print" nowrap="nowrap">&nbsp;</td>
    <!--<td width="50" bgcolor="#E5E5E5" nowrap="nowrap">&nbsp;</td>-->

    <td class="print">&nbsp;</td>
</tr>

</table>

    </div>

