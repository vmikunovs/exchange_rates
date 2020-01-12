<?php


namespace App\Services\Processors;


use App\Entity\Currency;
use App\Entity\Exchange;
use SimpleXMLElement;

interface CurrencyProcessorInterface {
    public function getExchangerType() : string;
    public function parse() : Exchange;
    public function getRate(Currency $from, Currency $to) : float;
}