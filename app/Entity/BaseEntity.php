<?php

namespace app\Entity;

class BaseEntity
{
    public int $id;

    public string $name;

    public \DateTime $dataCreate;
    public \DateTime $dataUpdate;

    public function setId(int $id){
        $this->id = $id;
    }
    public function getId(){
        return $this->getId();
    }
}