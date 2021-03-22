<?php
//Command from CLI args - php SalaryPaymentDateCalculator.php "filename=SalaryDateInfo"
$data = array(
    'Month,BonusDate,PayDate'
);

$date = date("d-m-Y");
$curerntYear = date("Y");
$month = 1;
$allMonthsProcessed = 0;
while(!$allMonthsProcessed) {
for ($month = 1; $month <= 12; $month++) {
    $lastDayOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $curerntYear);
    // number of days in that month
    // start: bonus date code
    $day1 = 15;
    $bonusDate = $curerntYear . '-' . $month . '-' . $day1;
    //Is 15th a weekday?
    $bonusDate1 = strtotime($bonusDate);
    $bonusDay = date("l", $bonusDate1);
    $normalWeekday = strtolower($bonusDay);
    $normalizedWeekday = strtolower($normalWeekday);
    $first = 1;
    $second = 2;
    if ($normalizedWeekday == "saturday") { //check if wednesday
        $newBonusDay = $day1 + 4;
        $first = $curerntYear . '-' . $month . '-' . $newBonusDay;

    } elseif ($normalizedWeekday == "sunday") {
        $newBonusDay = $day1 + 3;
        $first = $curerntYear . '-' . $month . '-' . $newBonusDay;
    } else {

        $first = $bonusDate;
    }

    //form the date
    $lastDateOfMonth = $curerntYear . '-' . $month . '-' . $lastDayOfMonth;
    $lastDate = strtotime($lastDateOfMonth);
    $day = date("l", $lastDate);
    $normalizedWeekend = strtolower($day);
    if ($normalizedWeekend == "saturday") {
        $newPayDay = $lastDayOfMonth - 1;
        $newPayDate = $curerntYear . '-' . $month . '-' . $newPayDay;
        $second = $newPayDate;
    } elseif ($normalizedWeekend == "sunday") {

        $newPayDay = $lastDayOfMonth - 2;
        $newPayDate = $curerntYear . '-' . $month . '-' . $newPayDay;
        $second = $newPayDate;
    } else {
        $second = $lastDateOfMonth;
    }
    $monthNum = $month;
    $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
    // Convert: Month Int to String
    $temp = $monthName . ',' . $first . ',' . $second;
    array_push($data, $temp);
}
$allMonthsProcessed = 1;
}

parse_str($argv[1], $_GET);
$fileName = $_GET['filename']. '.' ."csv";
$myfile = fopen($fileName, "w") or die("Unable to open file!");

foreach ($data as $line) {
    $val = explode(",", $line);
    fputcsv($myfile, $val);
}
fclose($myfile);
