<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @ORM\Table(name="currencies")
 */
class Currency {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=3)
     */
    private $code;

    /**
     * @ORM\Column(type="float", precision=10, scale=4)
     */
    private $rate;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $nominal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Exchange", inversedBy="currencies", cascade={"persist", "remove"})
     */
    private $exchange;
    
    /**
     * Currency constructor.
     * @param string $code
     * @param float $rate
     * @param int $nominal
     * @param $name
     */
    public function __construct(string $code, float $rate, int $nominal, $name) {
        $this->code = $code;
        $this->rate = $rate;
        $this->nominal = $nominal;
        $this->name = $name;
    }


    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getExchange(): ?Exchange {
        return $this->exchange;
    }

    /**
     * @param mixed $exchange
     */
    public function setExchange($exchange): void {
        $this->exchange = $exchange;
    }

    /**
     * @return mixed
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getRate() : float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate($rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getNominal() {
        return $this->nominal;
    }

    /**
     * @param mixed $nominal
     */
    public function setNominal($nominal): void {
        $this->nominal = $nominal;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void {
        $this->name = $name;
    }

    public function __toString()
    {
      return $this->code;
    }
}
