<?php

namespace App\Controller;

use App\Form\ExchangeType;
use App\Services\ExchangeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{

    public function index(Request $request, ExchangeService $exchangeService): Response
    {
        $session = $this->get("session");
        $exchangersTypes = $exchangeService->listExchangersTypes();

        $selectedExchangerType = $this->getSelectedExchanger($exchangersTypes);

        if ($session->has('exchangeModel')) {
            $exchangeModel = $session->get('exchangeModel');
        } else {
            $exchangeModel = $exchangeService->createExchangeModel($selectedExchangerType);
        }

        if (!$exchangeModel) {
            return null;
        }

        $form = $this->createForm(ExchangeType::class, $exchangeModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $this->get("session");
            $amount = floatval($exchangeModel->getFromAmount());
            $toAmount = $exchangeService->convert($selectedExchangerType, $exchangeModel->getFromCurrency(), $exchangeModel->getToCurrency(), $amount);
            $exchangeModel->setToAmount($toAmount);
            $session->set("exchangeModel", $exchangeModel);

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'exchange/index.html.twig',
            array(
                'form' => $form->createView(),
                'exchangersTypes' => $exchangersTypes,
                'selectedExchangerType' => $selectedExchangerType,
            )
        );
    }


    /**
     * @Route("/changeExchanger/{type}", name="change_exchanger")
     * @param string $type
     * @param ExchangeService $exchangeService
     * @return Response
     */
    public function changeExchanger($type, ExchangeService $exchangeService)
    {
        $session = $this->get("session");
        $session->set("selectedExchangerType", $type);

        $exchangeModel = $exchangeService->createExchangeModel($type);
        $session->set("exchangeModel", $exchangeModel);

        return $this->redirectToRoute('index');
    }

    /**
     * @param array|null $exchangersTypes
     * @return mixed
     */
    private function getSelectedExchanger(?array $exchangersTypes): string
    {
        $session = $this->get("session");

        if ($session->has("selectedExchangerType")) {
            $selectedExchangerType = $session->get("selectedExchangerType");
        } else {
            $selectedExchangerType = $exchangersTypes[0]['type'];
            $session->set("selectedExchangerType", $selectedExchangerType);
        }

        return $selectedExchangerType;
    }
}
