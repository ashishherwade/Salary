<?php

Class Utility
{

    public $temp1;

    public function calculateSalaryAndBonusDay()
    {
        $data =  "Month,BonusDate,PayDate"."\n";
        file_put_contents('SalaryDates.csv', print_r($data, TRUE),FILE_APPEND);
        $date = date("d-m-Y");
        $curerntYear = date("Y");
        $month = 1;
        $allMonthsProcessed = 0;


        while(!$allMonthsProcessed) {
            for ($month = 1; $month <= 12; $month++)
            {
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
                // Convert: Month Int to String
                $monthNum = $month;
                $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
                $temp = $monthName.','. $first.','. $second."\n" ;
                file_put_contents('SalaryDates.csv', print_r($temp, TRUE),FILE_APPEND);

            }
            $allMonthsProcessed = 1;

        }

    }

    public function getDate()
    {
        return $this->temp1;
    }
}

$showData= new Utility();
$showData->calculateSalaryAndBonusDay();

/*
parse_str($argv[1], $_GET);
$fileName = $_GET['filename']. '.' ."csv";
$myfile = fopen($fileName, "w") or die("Unable to open file!");
fclose($myfile); */


