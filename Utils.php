<?php

class Utils
{
    public function calculateSalaryAndBonusDates($fileName)
    {
        $this->addHeaderInCsvFile($fileName);
        $currentYear = date("Y");
        $allMonthsProcessed = 0;

        while (!$allMonthsProcessed) {
            for ($month = 1; $month <= 12; $month++) {

                //get month name from month number
                $monthName = $this->getMonthNameFromMonthNumber($month);

                //calculate bonus date for this month
                $finalBonusDateWithMonthAndYear = $this->calculateBonusDate($currentYear, $month);

                //calculate salary date for this month
                $finalSalaryDateWithMonthAndYear = $this->calculateSalaryDate($month, $currentYear);

                //add record for this month to csv file
                $this->addRecordToCsvFile($monthName, $finalBonusDateWithMonthAndYear, $finalSalaryDateWithMonthAndYear, $fileName);

            }
            $allMonthsProcessed = 1;

        }

    }

    /**
     * @param $bonusDate
     * @return mixed
     */
    private function getNormalizedWeekday($bonusDate)
    {
        $bonusDate1 = strtotime($bonusDate);
        $bonusDay = date("l", $bonusDate1);
        $normalWeekday = strtolower($bonusDay);
        $normalizedWeekday = strtolower($normalWeekday);
        return $normalizedWeekday;
    }

    /**
     * @param $currentYear
     * @param $month
     * @return string
     */
    private function calculateBonusDate($currentYear, $month)
    {
        // start: bonus date code
        $bonusDate = 15;

        $defaultBonusDateWithMonthAndYear = $this->getYearMonthDateString($currentYear, $month+1, $bonusDate);
        //Is 15th a weekday?
        $normalizedWeekday = $this->getNormalizedWeekday($defaultBonusDateWithMonthAndYear);
        $finalBonusDateWithMonthAndYear = $defaultBonusDateWithMonthAndYear;
        if ($normalizedWeekday == "saturday") { //check if wednesday
            $bonusDate = $bonusDate + 4;

        } elseif ($normalizedWeekday == "sunday") {
            $bonusDate = $bonusDate + 3;
        }
        if ($month == 12) {
            $finalBonusDateWithMonthAndYear = $this->getYearMonthDateString($currentYear+1, 1, $bonusDate);
        } else {
            $finalBonusDateWithMonthAndYear = $this->getYearMonthDateString($currentYear, $month+1, $bonusDate);
        }
        return $finalBonusDateWithMonthAndYear;
    }

    /**
     * @param $lastDateOfMonth
     * @return mixed
     */
    private function getNormalizedWeekend($lastDateOfMonth)
    {
        $lastDate = strtotime($lastDateOfMonth);
        $day = date("l", $lastDate);
        $normalizedWeekend = strtolower($day);
        return $normalizedWeekend;
    }

    /**
     * @param $month
     * @param $currentYear
     * @return string
     */
    private function calculateSalaryDate($month, $currentYear)
    {
        // number of days in that month
        $numberOfDaysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
        //form the date
        $lastDateOfMonth = $this->getYearMonthDateString($currentYear, $month, $numberOfDaysInThisMonth);
        $normalizedWeekend = $this->getNormalizedWeekend($lastDateOfMonth);
        $finalSalaryDateWithMonthAndYear = $lastDateOfMonth;
        if ($normalizedWeekend == "saturday") {
            $newPayDay = $numberOfDaysInThisMonth - 1;
            $finalSalaryDateWithMonthAndYear = $this->getYearMonthDateString($currentYear, $month, $newPayDay);
        } elseif ($normalizedWeekend == "sunday") {

            $newPayDay = $numberOfDaysInThisMonth - 2;
            $finalSalaryDateWithMonthAndYear = $this->getYearMonthDateString($currentYear, $month, $newPayDay);
        }
        return $finalSalaryDateWithMonthAndYear;
    }

    /**
     * @param $month
     * @return mixed
     */
    private function getMonthNameFromMonthNumber($month)
    {
        // Convert: Month Int to String
        $monthNum = $month;
        $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
        return $monthName;
    }

    private function addHeaderInCsvFile($fileName)
    {
        $headerForCsvFile = "Month,BonusDate,PayDate" . "\n";
        file_put_contents($fileName, print_r($headerForCsvFile, TRUE), FILE_APPEND);
    }

    /**
     * @param $monthName
     * @param $finalBonusDateWithMonthAndYear
     * @param $finalSalaryDateWithMonthAndYear
     * @param $fileName
     */
    private function addRecordToCsvFile($monthName, $finalBonusDateWithMonthAndYear, $finalSalaryDateWithMonthAndYear, $fileName)
    {
        $recordForThisMonth = $monthName . ',' . $finalBonusDateWithMonthAndYear . ',' . $finalSalaryDateWithMonthAndYear . "\n";
        file_put_contents($fileName, print_r($recordForThisMonth, TRUE), FILE_APPEND);
    }

    private function getYearMonthDateString($year, $month, $date) {
        return $year . '-' . $month . '-' . $date;
    }

}