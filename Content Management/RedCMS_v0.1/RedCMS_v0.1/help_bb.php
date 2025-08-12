<?php

  include"top.php";

  include"redcms_bb.php";

?>

<table>
<tr class="tr1"><td colspan="3">BB Code Help</td></tr>
<tr class="tr2"><td>Tag</td><td>Description</td><td>Example</td></tr>

<tr class="tr3"><td>[b]Bold[/b]</td><td>Bold Text</td><td><?php echo bbIt("[b]Bold[/b]"); ?></td></tr>
<tr class="tr3"><td>[u]Underlined[/u]</td><td>Underlined Text</td><td><?php echo bbIt("[u]Underlined[/u]"); ?></td></tr>
<tr class="tr3"><td>[i]Itallic[/i]</td><td>Itallic Text</td><td><?php echo bbIt("[i]Itallic[/i]"); ?></td></tr>
<tr class="tr3"><td>[url="http://somesite.com"]Link[/url]</td><td>Hyperlink</td><td><a href="http://somesite.com">Link</a></td></tr>
<tr class="tr3"><td>[l]Left[/l]</td><td>Left Align</td><td><?php echo bbIt("[l]Left[/l]"); ?></td></tr>
<tr class="tr3"><td>[r]Right[/r]</td><td>Right Align</td><td><?php echo bbIt("[r]Right[/r]"); ?></td></tr>
<tr class="tr3"><td>[c]Center[/c]</td><td>Center Align</td><td><?php echo bbIt("[c]Center[/c]"); ?></td></tr>
<tr class="tr3"><td>[colour="Blue"]Colour[/colour]</td><td>Font Colour</td><td><?php echo bbIt('[colour="Blue"]Colour[/colour]'); ?></td></tr>
<tr class="tr3"><td>[code]Code[/code]</td><td>Display Code</td><td><?php echo bbIt("[code]Code[/code]"); ?></td></tr>
<tr class="tr3"><td>[quote]Quote[/quote]</td><td>Quote</td><td><?php echo bbIt("[quote]Quote[/quote]"); ?></td></tr>
<tr class="tr3"><td>[profile="rederovski"]Profile[/profile]</td><td>Links to a profile</td><td><?php echo bbIt('[profile="rederovski"]Profile[/profile]'); ?></td></tr>


</table>

<?php

  include"bottom.php";

?>