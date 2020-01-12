<?php


namespace App\Controller;


use App\Services\ExchangeService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;


class RestController extends AbstractFOSRestController
{

    /**
     * @var ExchangeService
     **/
    private $exchangeService;

    /**
     * RestController constructor.
     * @param ExchangeService $exchangeService
     */
    public function __construct(ExchangeService $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    /**
     *
     * @Rest\View()
     * @Rest\QueryParam(name="from", description="currency code from", nullable=false)
     * @Rest\QueryParam(name="to", description="currency code to", nullable=false)
     * @Rest\QueryParam(name="amount", description="currency amount for convertation", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @return View
     */
    public function getConvertAction(ParamFetcher $paramFetcher)
    {

        $from = $paramFetcher->get("from");
        $to = $paramFetcher->get("to");
        $amount = floatval($paramFetcher->get("amount"));

        if ($from === '') {
                return $this->view(['from' => 'can not be '. $from],  Response::HTTP_BAD_REQUEST);
        }
        if ($to === '') {
                return $this->view(['from' => 'can not be '. $to],  Response::HTTP_BAD_REQUEST);
        }
        if ($amount <= 0) {
                return $this->view(['from' => 'amount for currency convert can not be '. $amount],  Response::HTTP_BAD_REQUEST);
        }

         return $this->view($this->exchangeService->convert('ECB', $from, $to, $amount), Response::HTTP_OK);
    }
}