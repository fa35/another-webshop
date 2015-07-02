<?php

class Order
{
    var $id;
    var $user;
    var $date;
    var $paymentType;
    var $recipientName;
    var $recipientPrename;
    var $recipientStreetNr;
    var $recipientZipLocation;
    var $payed;

    public function __construct($id, $user, $date, $paymentType, $recipientName, $recipientPrename,
                                $recipientStreetNr, $recipientZipLocation, $payed)
    {
        $this->id = $id;
        $this->user = $user;
        $this->date = $date;
        $this->paymentType = $paymentType;
        $this->recipientName = $recipientName;
        $this->recipientPrename = $recipientPrename;
        $this->recipientStreetNr = $recipientStreetNr;
        $this->recipientZipLocation = $recipientZipLocation;
        $this->payed = $payed;
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param mixed $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return mixed
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * @param mixed $recipientName
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @return mixed
     */
    public function getRecipientPrename()
    {
        return $this->recipientPrename;
    }

    /**
     * @param mixed $recipientPrename
     */
    public function setRecipientPrename($recipientPrename)
    {
        $this->recipientPrename = $recipientPrename;
    }

    /**
     * @return mixed
     */
    public function getRecipientStreetNr()
    {
        return $this->recipientStreetNr;
    }

    /**
     * @param mixed $recipientStreetNr
     */
    public function setRecipientStreetNr($recipientStreetNr)
    {
        $this->recipientStreetNr = $recipientStreetNr;
    }

    /**
     * @return mixed
     */
    public function getRecipientZipLocation()
    {
        return $this->recipientZipLocation;
    }

    /**
     * @param mixed $recipientZipLocation
     */
    public function setRecipientZipLocation($recipientZipLocation)
    {
        $this->recipientZipLocation = $recipientZipLocation;
    }

    /**
     * @return mixed
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * @param mixed $payed
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;
    }
}

?>