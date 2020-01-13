<?php

namespace App\Tests\Services;

use App\Repository\CurrencyRepository;
use App\Repository\ExchangeRepository;
use App\Services\ExchangeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ExchangeServiceTest extends TestCase
{

    /**
     * @var ExchangeService
     */
    private $exchangeService;

    public function setUp(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $exchangeRepository = $this->createMock(ExchangeRepository::class);
        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $this->exchangeService = new ExchangeService($entityManager, $exchangeRepository, $currencyRepository);
    }


    public function testConvert()
    {
        //ToDo realize
        $result = $this->exchangeService->convert('','','', 10);
        self::assertNotNull($result);
    }

    public function testListExchangersTypes()
    {
        //ToDo realize
        self::assertTrue(true);
    }

    public function testUpdateRates()
    {
        //ToDo realize
        self::assertTrue(true);
    }

    public function testCreateExchangeModel()
    {
        //ToDo realize
        self::assertTrue(true);
    }
}
