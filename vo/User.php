<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-28
 * Time: 上午12:24
 * To change this template use File | Settings | File Templates.
 */

class User {
    private $portrait;
    private $id;
    private $name;

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

    public function setPortrait($portrait)
    {
        $this->portrait = $portrait;
    }

    public function getPortrait()
    {
        return $this->portrait;
    }


}