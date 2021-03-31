<?php
include 'Utils.php';

parse_str($argv[1], $_GET);
$fileName = $_GET['filename'];
if ($fileName == null || $fileName == '') {
    $fileName = "SalaryDates";
}
$fullFileName = $fileName . '.' . "csv";

$utils = new Utils();
$utils->calculateSalaryAndBonusDates($fullFileName);