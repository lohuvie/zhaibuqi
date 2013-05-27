<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-19
 * Time: 上午12:12
 * To change this template use File | Settings | File Templates.
 */

class Fan {
    private $potrait;
    private $id;
    private $af_id;
    private $href;
    private $name;
    private $academy;
    private $status;

    public function setAcademy($academy)
    {
        $this->academy = $academy;
    }

    public function getAcademy()
    {
        return $this->academy;
    }

    public function setAfId($af_id)
    {
        $this->af_id = $af_id;
    }

    public function getAfId()
    {
        return $this->af_id;
    }

    public function setHref($href)
    {
        $this->href = $href;
    }

    public function getHref()
    {
        return $this->href;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPotrait($potrait)
    {
        $this->potrait = $potrait;
    }

    public function getPotrait()
    {
        return $this->potrait;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}