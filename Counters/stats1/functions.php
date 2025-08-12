<?
function month($number) {
$month_array = array(
	"10" => "October",
	"11" => "November",
	"12" => "December",
	"1" => "January",
	"2" => "February",
	"3" => "March",
	"4" => "April",
	"5" => "May",
	"6" => "June",
	"7" => "July",
	"8" => "August",
	"9" => "September"
);

$number = str_replace(array_keys($month_array), array_values($month_array), $number); 
return $number;

}
?>