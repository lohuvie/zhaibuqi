<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-19
 * Time: 上午12:17
 * To change this template use File | Settings | File Templates.
 */

class AttentionFunList {
    public $person = Array();
    public $end;

    function __construct()
    {
        $this->end = "end";
    }

    public function setEnd($end)
    {
        $this->end = $end;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setPerson($person,$index)
    {
        $this->person[$index] = $person;
    }

    public function getPerson()
    {
        return $this->person;
    }


}