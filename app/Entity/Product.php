<?php

namespace app\Entity;

class Product
{
    private string $table_name = 'products';

    public int $id;

    public string $name;
    public string $extarnal_id;
    public string $data_create;
    public string $data_update;



}