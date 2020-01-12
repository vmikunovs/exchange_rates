<?php


namespace App\Services\Processors;


use App\Entity\Currency;
use App\Entity\Exchange;
use DateTime;
use Exception;

class CurrencyProcessorECB implements CurrencyProcessorInterface {
    const ECB = 'ECB';
    const EUR = 'EUR';
    private $xmlLink = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    public function parse(): Exchange {
        $xml = simplexml_load_file($this->xmlLink, null, true);

        $currenciesData = $xml->children()[0]->children()[0];
        try {
            $date = new DateTime($currenciesData['time']);
        } catch (Exception $e) {
            return null;
        }

        $exchange = new Exchange(self::EUR, self::ECB, $date);

        $currency = new Currency(self::EUR, 1, 1, null);
        $currency->setExchange($exchange);
        $exchange->addCurrency($currency);
        foreach($currenciesData as $currencyData) {
            $currency = new Currency($currencyData['currency'], floatval($currencyData['rate']), 0, null);

            $currency->setExchange($exchange);
            $exchange->addCurrency($currency);
        }

        return $exchange;
    }

    public function getExchangerType(): string
    {
        return self::ECB;
    }

    public function getRate(Currency $from, Currency $to): float
    {
        return $to->getRate() / $from->getRate();
    }

    public function setXml($xmlLink) {
        $this->xmlLink = $xmlLink;
    }
}