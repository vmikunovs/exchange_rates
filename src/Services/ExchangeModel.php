<?php


namespace App\Services;


use Doctrine\ORM\PersistentCollection;

class ExchangeModel {
    /**
     * @var string
     * */
    private $fromCurrency;
    /**
     * @var string
     * */
    private $toCurrency;
    /**
     * @var float
     * */
    private $fromAmount;
    /**
     * @var float
     * */
    private $toAmount;
    /**
     * @var PersistentCollection
     */
    private $currencies;

    /**
     * ExchangeModel constructor.
     * @param $fromCurrency
     * @param $toCurrency
     * @param $fromAmount
     * @param $toAmount
     * @param PersistentCollection   $currencies
     */
    public function __construct(
        $fromCurrency,
        $toCurrency,
        $fromAmount,
        $toAmount,
        $currencies
    )
    {
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency = $toCurrency;
        $this->fromAmount = $fromAmount;
        $this->toAmount = $toAmount;
        $this->currencies = $currencies;
    }

    /**
     * @return string
     */
    public function getFromCurrency(): string
    {
        return $this->fromCurrency;
    }

    /**
     * @param string $fromCurrency
     */
    public function setFromCurrency(string $fromCurrency): void
    {
        $this->fromCurrency = $fromCurrency;
    }

    /**
     * @return string
     */
    public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    /**
     * @param string $toCurrency
     */
    public function setToCurrency(string $toCurrency): void
    {
        $this->toCurrency = $toCurrency;
    }

    /**
     * @return float
     */
    public function getFromAmount(): float
    {
        return $this->fromAmount;
    }

    /**
     * @param float $fromAmount
     */
    public function setFromAmount(float $fromAmount): void
    {
        $this->fromAmount = $fromAmount;
    }

    /**
     * @return float
     */
    public function getToAmount(): float
    {
        return $this->toAmount;
    }

    /**
     * @param float $toAmount
     */
    public function setToAmount(float $toAmount): void
    {
        $this->toAmount = $toAmount;
    }

    /**
     * @return PersistentCollection
     */
    public function getCurrencies(): PersistentCollection
    {
        return $this->currencies;
    }

    /**
     * @param PersistentCollection $currencies
     */
    public function setCurrencies(PersistentCollection $currencies): void
    {
        $this->currencies = $currencies;
    }

    public function toString()
    {
     return $this->fromCurrency . ' => ' . $this->toCurrency . ' value ' . $this->fromAmount . ' => ' . $this->toAmount;
    }


}