<?php

namespace App\Tests\Services\Processors;

use App\Entity\Currency;
use App\Entity\Exchange;
use App\Services\Processors\CurrencyProcessorCBR;
use App\Services\Processors\CurrencyProcessorECB;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class CurrencyProcessorECBTest extends TestCase
{
    const XML_LINK = __DIR__.'/ecb_daily.xml';
    const EUR = 'EUR';
    const USD = 'USD';
    const CZK = 'CZK';
    const GBP = 'GBP';
    const ECB = 'ECB';
    /**
     * @var CurrencyProcessorECB
     */
    private $processor;
    /**
     * @var Currency
     */
    private $eur;
    /**
     * @var Currency
     */
    private $usd;
    /**
     * @var Currency
     */
    private $czk;
    /**
     * @var Currency
     */
    private $gbk;


    public function setUp(): void
    {
        $this->processor = new CurrencyProcessorECB();
        $this->processor->setXml(self::XML_LINK);

        $this->eur = new Currency(self::EUR, 1, 0, null);
        $this->usd = new Currency(self::USD, 1.1115, 0, null);
        $this->czk = new Currency(self::CZK, 25.265, 0, null);
        $this->gbk = new Currency(self::GBP, 0.84868, 0, null);
    }

    public function testGetRate()
    {
        $processor = $this->processor;

        self::assertEquals(1,  $processor->getRate($this->eur, $this->eur));
        self::assertEquals(1.1115, $processor->getRate($this->eur, $this->usd));
        self::assertEquals(0.8997,  round($processor->getRate($this->usd, $this->eur), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(22.7305,  round($processor->getRate($this->usd, $this->czk), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(0.044,  round($processor->getRate($this->czk, $this->usd), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(0.84868,  $processor->getRate($this->eur, $this->gbk));
        self::assertEquals(1.1783,  round($processor->getRate($this->gbk, $this->eur), 4, PHP_ROUND_HALF_UP));
    }

    public function testParse()
    {
        $exchange = $this->processor->parse();

        self::assertInstanceOf(Exchange::class, $exchange);

        self::assertEquals(self::ECB, $exchange->getType());
        self::assertEquals(self::EUR, $exchange->getBase());
        self::assertEquals(new DateTime('9.01.2020'), $exchange->getDate());

        $currencies = $exchange->getCurrencies();
        self::assertEquals(33, $currencies->count());

        $currencyEUR = $currencies[0];
        self::assertEquals(self::EUR, $currencyEUR->getCode());
        self::assertEquals('1', $currencyEUR->getRate());

        $currencyUSD = $currencies[1];
        self::assertEquals(self::USD, $currencyUSD->getCode());
        self::assertEquals('1.111', $currencyUSD->getRate());
        self::assertEquals('0', $currencyUSD->getNominal());

        $currencyCZK = $currencies[4];
        self::assertEquals('CZK', $currencyCZK->getCode());
        self::assertEquals('25.253', $currencyCZK->getRate());
        self::assertEquals('0', $currencyCZK->getNominal());
    }
}
