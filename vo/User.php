<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-28
 * Time: 上午12:24
 * To change this template use File | Settings | File Templates.
 */

class User {
    private $potrait;
    private $id;
    private $href;
    private $name;

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


}