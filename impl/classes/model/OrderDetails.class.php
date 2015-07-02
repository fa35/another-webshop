<?php

class OrderDetails
{
    var $id;
    var $order;
    var $article;
    var $nettoPrice;
    var $vat;
    var $amount;

    public function __construct($id, $order, $article, $nettoPrice, $vat, $amount)
    {
        $this->id = $id;
        $this->order = $order;
        $this->article = $article;
        $this->nettoPrice = (integer)$nettoPrice;
        $this->vat = $vat;
        $this->amount = (integer)$amount;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return mixed
     */
    public function getNettoPrice()
    {
        return $this->nettoPrice;
    }

    /**
     * @param mixed $nettoPrice
     */
    public function setNettoPrice($nettoPrice)
    {
        $this->nettoPrice = $nettoPrice;
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param mixed $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}

?>