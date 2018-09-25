<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasesDescription
 *
 * @ORM\Table(name="aliases_description")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AliasesDescriptionRepository")
 */
class AliasesDescription
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
     * @ORM\Column(name="ipPort", type="string", length=255)
     */
    private $ipPort;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionIpPort", type="string", length=255)
     */
    private $descriptionIpPort;

    /**
     * @ORM\ManyToOne(targetEntity="AliasesName", inversedBy="exp")
     * @ORM\JoinColumn(name="aliasName_id", referencedColumnName="id")
     */
    private $user;


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
     * Set ipPort
     *
     * @param string $ipPort
     *
     * @return AliasesDescription
     */
    public function setIpPort($ipPort)
    {
        $this->ipPort = $ipPort;

        return $this;
    }

    /**
     * Get ipPort
     *
     * @return string
     */
    public function getIpPort()
    {
        return $this->ipPort;
    }

    /**
     * Set descriptionIpPort
     *
     * @param string $descriptionIpPort
     *
     * @return AliasesDescription
     */
    public function setDescriptionIpPort($descriptionIpPort)
    {
        $this->descriptionIpPort = $descriptionIpPort;

        return $this;
    }

    /**
     * Get descriptionIpPort
     *
     * @return string
     */
    public function getDescriptionIpPort()
    {
        return $this->descriptionIpPort;
    }

    /**
     * Set user
     *
     * @param \PrincipalBundle\Entity\AliasesName $user
     *
     * @return AliasesDescription
     */
    public function setUser(\PrincipalBundle\Entity\AliasesName $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PrincipalBundle\Entity\AliasesName
     */
    public function getUser()
    {
        return $this->user;
    }
}
