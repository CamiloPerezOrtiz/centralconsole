<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Txtip
 *
 * @ORM\Table(name="txtip")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\TxtipRepository")
 */
class Txtip
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=40)
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=40)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="string", length=40)
     */
    private $cliente;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     *
     * @return Txtip
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Txtip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set cliente
     *
     * @param string $cliente
     *
     * @return Txtip
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string
     */
    public function getCliente()
    {
        return $this->cliente;
    }
}

