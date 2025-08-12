<?php

$stats_table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="regular" bgcolor="black">
<table cellpadding="5" cellspacing="1" border="0">
<tr class="alt2">
<td class="bold">&nbsp;</td>
<td class="bold">Points</td>
<td class="bold">Ratings</td>
<td class="bold">Comments</td>
</tr>
<tr class="alt1">
<td class="alt2"><span class="bold">Today</span></td>
<td class="regular"><a href="$base_url/stats/stats_user_points_day.php?i=$i">Points Past Day</a></td>
<td class="regular"><a href="$base_url/stats/stats_user_ratings_day.php?i=$i">Ratings Past Day</a></td>
<td class="regular"><a href="$base_url/stats/stats_user_comments_day.php?i=$i">Comments Past Day</a></td>
</tr>
<tr class="alt1">
<td class="alt2"><span class="bold">Week</span></td>
<td class="regular"><a href="$base_url/stats/stats_user_points_week.php?i=$i">Point Past Week</a></td>
<td class="regular"><a href="$base_url/stats/stats_user_ratings_week.php?i=$i">Ratings Past Week</a></td>
<td class="regular"><a href="$base_url/stats/stats_user_comments_week.php?i=$i">Comments Past Week</a></td>
</tr>
<tr class="alt1">
<td class="alt2"><span class="bold">Month</span></td>
<td class="regular"><a href="$base_url/stats/stats_user_points_month.php?i=$i">Points Past Month</a></td>
<td class="regular"><a href="$base_url/stats/stats_user_ratings_month.php?i=$i">Ratings Past Month</a></td>
<td class="regular"><a href="$base_url/stats/stats_user_comments_month.php?i=$i">Comments Past Month</a></td>
</tr>
</table>
</td>
</tr>
</table>
EOF;

?>