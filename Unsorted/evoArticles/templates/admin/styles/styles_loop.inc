<tr>
<td $bg width="60%">
<b><a href="$PHP_SELF?do=edit&id=$row[id]">$row[name]</a></b>
</td>
<td width="40%" align="center" $bg2>
[<a href="$PHP_SELF?do=edit&id=$row[id]">Edit Style</a>]
$delete
[<a href="$PHP_SELF?do=multiple&dir=$row[tplfolder]" title="Multiple Templates Editor">Edit Templates</a>]
[<a href="$PHP_SELF?do=export&id=$row[id]" title="$evoLANG[export] : $row[name]">$evoLANG[export]</a>]
<!-- [<a href="$PHP_SELF?do=makedefault&id=$row[id]">Default</a>] $def -->
</td>
</tr>
