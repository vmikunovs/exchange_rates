<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRepository")
 * @ORM\Table(name="exchanges")
 */
class Exchange {
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="string", length=100, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $base;

    /**
     * @ORM\Column(type="datetime", name="last_update_time")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Currency", mappedBy="exchange", cascade={"persist", "remove"})
     */
    private $currencies;

    public function __construct(string $base, string $type, DateTime $date) {
        $this->type = $type;
        $this->date = $date;
        $this->base = $base;
        $this->currencies = new ArrayCollection();
        $this->createdAt();
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return ArrayCollection
     */
    public function getCurrencies() {
        return $this->currencies;
    }

    /**
     * @param ArrayCollection $currencies
     * @return Exchange
     */
    public function setCurrencies(ArrayCollection $currencies): Exchange
    {
        $this->currencies = $currencies;

        return $this;
    }

    public function addCurrency(Currency $currency) {
        $this->currencies->add($currency);
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * */
    public function createdAt() {
        $this->updatedAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate()
     * */
    public function updateAt() {
        $this->updatedAt = new DateTime();
    }

    public function __toString() {
        return 'Exchange: ' . $this->type . ' last update time:' . $this->date->format('Y-m-d');
    }
}
