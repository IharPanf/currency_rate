<?php
//    for example:
//    echo  $curValuta->getCurrencyRate("USD");
//    echo  $curValuta->getCurrencyRate("EUR");

    include_once('curs.php');
    $UPCURS     = 1.03; // коэф. повышения
    $curDate    = date("d/m/Y");
    $curValuta  = new CurrencyRate($curDate);
    $massRateDate = $curValuta->allRate();
    include_once('template.php');
?>