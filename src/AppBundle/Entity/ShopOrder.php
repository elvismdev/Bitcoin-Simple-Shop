<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShopOrder
 *
 * @ORM\Table(name="shop_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShopOrderRepository")
 */
class ShopOrder
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="address2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=255)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="product", type="object")
     */
    private $product;

    /**
     * @var float
     *
     * @ORM\Column(name="order_total_btc", type="float")
     */
    private $orderTotalBtc;

    /**
     * @var float
     *
     * @ORM\Column(name="order_total_usd", type="float")
     */
    private $orderTotalUsd;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="order_paid", type="boolean")
     */
    private $orderPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=255)
     */
    private $orderStatus;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_paid", type="float", nullable=true)
     */
    private $amountPaid;

    /**
     * @var string
     *
     * @ORM\Column(name="difference", type="string", length=255, nullable=true)
     */
    private $difference;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_hash", type="string", length=255, nullable=true)
     */
    private $transactionHash;

    /**
     * @var string
     *
     * @ORM\Column(name="btc_address_id", type="string", length=255, nullable=true)
     */
    private $btcAddressId;


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
     * Set email
     *
     * @param string $email
     *
     * @return ShopOrder
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ShopOrder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return ShopOrder
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return ShopOrder
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return ShopOrder
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return ShopOrder
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return ShopOrder
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return ShopOrder
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return ShopOrder
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set product
     *
     * @param \stdClass $product
     *
     * @return ShopOrder
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \stdClass
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set orderTotalBtc
     *
     * @param float $orderTotalBtc
     *
     * @return ShopOrder
     */
    public function setOrderTotalBtc($orderTotalBtc)
    {
        $this->orderTotalBtc = $orderTotalBtc;

        return $this;
    }

    /**
     * Get orderTotalBtc
     *
     * @return float
     */
    public function getOrderTotalBtc()
    {
        return $this->orderTotalBtc;
    }

    /**
     * Set orderTotalUsd
     *
     * @param float $orderTotalUsd
     *
     * @return ShopOrder
     */
    public function setOrderTotalUsd($orderTotalUsd)
    {
        $this->orderTotalUsd = $orderTotalUsd;

        return $this;
    }

    /**
     * Get orderTotalUsd
     *
     * @return float
     */
    public function getOrderTotalUsd()
    {
        return $this->orderTotalUsd;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ShopOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ShopOrder
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set orderPaid
     *
     * @param boolean $orderPaid
     *
     * @return ShopOrder
     */
    public function setOrderPaid($orderPaid)
    {
        $this->orderPaid = $orderPaid;

        return $this;
    }

    /**
     * Get orderPaid
     *
     * @return bool
     */
    public function getOrderPaid()
    {
        return $this->orderPaid;
    }

    /**
     * Set orderStatus
     *
     * @param string $orderStatus
     *
     * @return ShopOrder
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set amountPaid
     *
     * @param float $amountPaid
     *
     * @return ShopOrder
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    /**
     * Get amountPaid
     *
     * @return float
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * Set difference
     *
     * @param string $difference
     *
     * @return ShopOrder
     */
    public function setDifference($difference)
    {
        $this->difference = $difference;

        return $this;
    }

    /**
     * Get difference
     *
     * @return string
     */
    public function getDifference()
    {
        return $this->difference;
    }

    /**
     * Set transactionHash
     *
     * @param string $transactionHash
     *
     * @return ShopOrder
     */
    public function setTransactionHash($transactionHash)
    {
        $this->transactionHash = $transactionHash;

        return $this;
    }

    /**
     * Get transactionHash
     *
     * @return string
     */
    public function getTransactionHash()
    {
        return $this->transactionHash;
    }

    /**
     * Set btcAddressId
     *
     * @param string $btcAddressId
     *
     * @return ShopOrder
     */
    public function setBtcAddressId($btcAddressId)
    {
        $this->btcAddressId = $btcAddressId;

        return $this;
    }

    /**
     * Get btcAddressId
     *
     * @return string
     */
    public function getBtcAddressId()
    {
        return $this->btcAddressId;
    }
}

