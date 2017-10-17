<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * PriceOption
 *
 * @ORM\Table(name="price_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceOptionRepository")
 */
class PriceOption
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
     * @var int
     *
     * @ORM\Column(name="days", type="integer")
     */
    private $days;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * Many Prices have One Product.
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="prices")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;


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
     * Set days
     *
     * @param integer $days
     *
     * @return PriceOption
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }


    // /**
    //  *  Return value in string for this entity.
    //  */
    // public function __toString() {
    //     return "$this->days days - $this->price €";
    // }




    /**
     * Set price
     *
     * @param float $price
     *
     * @return PriceOption
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return PriceOption
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
