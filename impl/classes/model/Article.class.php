<?php

class Article
{
    var $id;
    var $title;
    var $articleGroup;
    var $nettoPrice;
    var $vat;
    var $description;
    var $picture;

    public function __construct($id, $title, $articleGroup, $nettoPrice, $vat, $description, $picture)
    {
        $this->id = $id;
        $this->title = $title;
        $this->articleGroup = $articleGroup;
        $this->nettoPrice = $nettoPrice;
        $this->vat = $vat;
        $this->description = $description;
        $this->picture = $picture;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getArticleGroup()
    {
        return $this->articleGroup;
    }

    /**
     * @param mixed $articleGroup
     */
    public function setArticleGroup($articleGroup)
    {
        $this->articleGroup = $articleGroup;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
}

?>