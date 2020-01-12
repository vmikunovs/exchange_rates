<?php

namespace App\Tests\Services\Processors;

use App\Entity\Currency;
use App\Entity\Exchange;
use App\Services\Processors\CurrencyProcessorCBR;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class CurrencyProcessorCBRTest extends TestCase
{
    const XML_LINK = __DIR__.'/cbr_daily.xml';
    const CBR = 'CBR';
    const RUB = 'RUB';
    const AUD = 'AUD';
    const AMD = 'AMD';
    const EUR = 'EUR';

    /**
     * @var CurrencyProcessorCBR
     */
    private $processor;
    /**
     * @var Currency
     */
    private $rub;
    /**
     * @var Currency
     */
    private $aud;
    /**
     * @var Currency
     */
    private $amd;
    /**
     * @var Currency
     */
    private $eur;

    public function setUp(): void
    {
        $this->processor = new CurrencyProcessorCBR();
        $this->processor->setXml(self::XML_LINK);
        $this->rub = new Currency(self::RUB, 1, 1, null);
        $this->aud = new Currency(self::AUD, 43.3835, 1, null);
        $this->amd = new Currency(self::AMD, 12.9239, 100, null);
        $this->eur = new Currency(self::EUR, 69.3777, 1, null);
    }

    public function testGetRate()
    {
        self::assertEquals(0.0231, round($this->processor->getRate($this->rub, $this->aud), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(43.3835, $this->processor->getRate($this->aud, $this->rub));
        self::assertEquals(0.0144, round($this->processor->getRate($this->rub, $this->eur), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(69.3777, round($this->processor->getRate($this->eur, $this->rub), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(0.0019, round($this->processor->getRate($this->amd, $this->eur), 4, PHP_ROUND_HALF_UP));
        self::assertEquals(536.8171, round($this->processor->getRate($this->eur, $this->amd), 4, PHP_ROUND_HALF_UP));
    }

    public function testParse()
    {
        $exchange = $this->processor->parse();

        self::assertInstanceOf(Exchange::class, $exchange);

        self::assertEquals(self::CBR, $exchange->getType());
        self::assertEquals(self::RUB, $exchange->getBase());
        self::assertEquals(new DateTime('10.01.2020'), $exchange->getDate());

        $currencies = $exchange->getCurrencies();
        self::assertEquals(35, $currencies->count());

        $currencyRUB = $currencies[0];
        self::assertEquals(self::RUB, $currencyRUB->getCode());
        self::assertEquals('1', $currencyRUB->getRate());

        $currencyAUD = $currencies[1];
        self::assertEquals('AUD', $currencyAUD->getCode());
        self::assertEquals('42.0494', $currencyAUD->getRate());
        self::assertEquals('1', $currencyAUD->getNominal());

        $currencyAMD = $currencies[4];
        self::assertEquals('AMD', $currencyAMD->getCode());
        self::assertEquals('12.7904', $currencyAMD->getRate());
        self::assertEquals('100', $currencyAMD->getNominal());
    }
}
