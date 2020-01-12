<?php


namespace App\Services\Processors;


use App\Entity\Currency;
use App\Entity\Exchange;
use DateTime;
use Exception;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;

class CurrencyProcessorCBR implements CurrencyProcessorInterface
{
    const CBR = 'CBR';
    const RUB = 'RUB';
    private $xmlLink = 'https://www.cbr.ru/scripts/XML_daily.asp';


    public function parse(): Exchange {
        $xml = simplexml_load_file($this->xmlLink, null, true);

        if (false === $xml) {
            //$errors = libxml_get_errors();
            echo 'Errors are ';
            throw new InvalidArgumentException('invalid XML');
        }

        try {
            $date = new DateTime($xml['Date']);
        } catch (Exception $e) {
            throw new InvalidArgumentException('invalid XML');
        }

        $exchange = new Exchange(self::RUB, self::CBR, $date);

        $currency = new Currency(self::RUB, 1, 1, null);
        $currency->setExchange($exchange);
        $exchange->addCurrency($currency);

        foreach ($xml->Valute as $item) {
            $value = $item->Value;
            $value = str_replace(",", ".", $value);

            $currency = new Currency($item->CharCode, $value, intval($item->Nominal), $item->Name);
            $currency->setExchange($exchange);
            $exchange->addCurrency($currency);
        }

        return $exchange;
    }

    public function getExchangerType(): string
    {
        return self::CBR;
    }

    public function getRate(Currency $from, Currency $to): float
    {
        $rateFrom = $from->getRate() / $from->getNominal();
        $rateTo = $to->getNominal() / $to->getRate();

        return $rateFrom * $rateTo;
    }

    public function setXml($xmlLink) {
        $this->xmlLink = $xmlLink;
    }
}