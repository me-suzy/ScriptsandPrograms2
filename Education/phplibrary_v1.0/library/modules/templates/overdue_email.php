<?
$subject = "$library_name - you have overdue items";

// Type \n to make a new line
$message = "Hi,\n\nYou currently have:\n\n$display_items\n\nOur agreed return date was: $resultOverdueEmail[date_in_day]/$resultOverdueEmail[date_in_month]/$resultOverdueEmail[date_in_year].\nPlease return your item(s) to $library_name as soon as possible.\n\nThank you,\n$library_owner";
?>