<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lohuvie
 * Date: 13-5-28
 * Time: 下午3:18
 * To change this template use File | Settings | File Templates.
 */

class Activity {
    private $id;
    private $name;
    private $site;
    private $cover;
    private $date;
    private $time_begin;
    private $time_end;

    public function setSite($site)
    {
        $this->site = $site;
    }

    public function getSite()
    {
        return $this->site;
    }


    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setTimeBegin($time_begin)
    {
        $this->time_begin = $time_begin;
    }

    public function getTimeBegin()
    {
        return $this->time_begin;
    }

    public function setTimeEnd($time_end)
    {
        $this->time_end = $time_end;
    }

    public function getTimeEnd()
    {
        return $this->time_end;
    }

    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    public function getCover()
    {
        return $this->cover;
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

}