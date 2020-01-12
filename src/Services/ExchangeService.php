<?php


namespace App\Services;


use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeRepository;
use App\Services\Processors\CurrencyProcessorCBR;
use App\Services\Processors\CurrencyProcessorECB;
use App\Services\Processors\CurrencyProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use function Composer\Autoload\includeFile;
use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

class ExchangeService
{
    private $entityManager;
    /**
     * @var ArrayCollection|CurrencyProcessorInterface[]
     */
    private $processors;
    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ExchangeRepository $exchangeRepository,
        CurrencyRepository $currencyRepository
    ) {
        $this->entityManager = $entityManager;
        $this->exchangeRepository = $exchangeRepository;
        $this->currencyRepository = $currencyRepository;

        $this->processors = new ArrayCollection();
        $this->processors->add(new CurrencyProcessorECB());
        $this->processors->add(new CurrencyProcessorCBR());
    }

    public function updateRates()
    {
        foreach ($this->processors as $processor) {
            $exchange = $processor->parse();

            $entityManager = $this->entityManager;

            $entityManager->beginTransaction();
            try {
                $exchangeType = $exchange->getType();
                $oldExchange = $this->exchangeRepository->findOneByType($exchangeType);
                if ($oldExchange) {
                    $this->entityManager->remove($oldExchange);
                    $entityManager->flush();
                }

                foreach ($exchange->getCurrencies() as $currency) {
                    $entityManager->persist($currency);
                }

                $entityManager->persist($exchange);

                $entityManager->flush();
                $entityManager->commit();
            } catch (Exception $e) {
                echo $e->getTrace();
                $entityManager->rollBack();
                throw $e;
            }
        }
    }

    /**
     * @param string $selectedExchangerType
     * @return ExchangeModel|null
     */
    public function createExchangeModel($selectedExchangerType)
    {
        $exchange = $this->exchangeRepository->findOneByType($selectedExchangerType);
        $fromCurrency = $exchange->getBase();

        if ($exchange) {
            $currencies = $exchange->getCurrencies();

            foreach ($currencies as $currency) {
                if ($currency->getCode() != $fromCurrency) {
                    $secondCurrencyCode = $currency->getCode();
                    $secondCurrencyAmount = $currency->getRate();
                    break;
                }
            }

            if (!isset($secondCurrencyCode)) {
                $secondCurrencyCode = $fromCurrency;
            }

            if (!isset($secondCurrencyAmount)) {
                $secondCurrencyAmount = 1;
            }

            return new ExchangeModel($fromCurrency, $secondCurrencyCode, 1, $secondCurrencyAmount, $currencies);
        }

        return null;
    }


    /**
     * @param string $exchangerType
     * @param string $from
     * @param string $to
     * @param float $amount
     * @return float
     */
    public function convert(string $exchangerType, string $from, string $to, float $amount): float
    {

        $currencyFrom = $this->currencyRepository->findOneByCodeAndExchangeId(strtoupper($from), $exchangerType);
        $currencyTo = $this->currencyRepository->findOneByCodeAndExchangeId(strtoupper($to), $exchangerType);


        return $amount  *  $this->getRate($exchangerType, $currencyFrom, $currencyTo);
    }

    /**
     * @param string $exchangeType
     * @param Currency $from
     * @param Currency $to
     * @return float
     */
    private function getRate($exchangeType, $from, $to) {
        //ToDo realize store result in cache example Map<ExchangeType, Map<CurrencyPair, Float> cache = new HashMap<>();

/*        if ($from->getCode() == $from->getExchange()->getBase()) {
            return $to->getValue();
        }

        if ($to->getCode() == $to->getExchange()->getBase()) {
            return 1 / $from->getValue();
        }*/

        foreach ($this->processors as $processor) {
            if ($processor->getExchangerType() == $exchangeType) {

                return $processor->getRate($from, $to);
            }
        }


        //ToDo throw wrong exchange type exception
        return null;
    }

    /**
     * @return string[]|null
     */
    public function listExchangersTypes()
    {
        return $this->exchangeRepository->findTypes();
    }
}