<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-19
 * Time: 上午1:16
 * To change this template use File | Settings | File Templates.
 */

class Attention {
    public $potrait;
    public $id;
    public $af_id;
    public $href;
    public $name;
    public $academy;
    public $status;
    public $group;
    public $ag_id;

    function __construct(){
        $this->group = '未分组';
    }


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

    public function setAgId($ag_id)
    {
        $this->ag_id = $ag_id;
    }

    public function getAgId()
    {
        return $this->ag_id;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getGroup()
    {
        return $this->group;
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