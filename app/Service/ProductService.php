<?php

namespace app\Service;

use app\Config\Database;
use app\Entity\Product;
use PDO;

class ProductService
{
    public function __construct(
        Database $conn

    ){
        $this->conn = $conn->getConnection();
    }

    public function getCollection(): array{

        $sql = "SELECT * FROM products";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $numRow = $stmt->rowCount();

        if($numRow<=0){
            echo json_encode(['message'=>'not found']);
        }

        $products_collection = array();
        $products_collection["products"] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // var_dump($row);

            $product_item = [
                'id' => $row['id'],
                'name' => $row['name'],
                'extarnal_id' => $row['extarnalid'],
                'data_create' => $row['datacreate'],
                'data_update' => $row['dataupdate']
            ];

            array_push($products_collection['products'], $product_item);


        }
        return $products_collection;

    }

    public function create(string $name): bool{

        $sql = "INSERT INTO products (name,extarnalid,datacreate,dataupdate)
                VALUES (:name, :extarnalid, :datacreate, :dataupdate)";

        $stmt = $this->conn->prepare($sql);

        $product = new Product();
        $product->name = $name;
        $product->extarnal_id = hash('sha256',str_shuffle("urgf74t23542shufrd242433t2"));
        $product->data_create = date('Y-m-d');
        $product->data_update = date('Y-m-d');

        $stmt->bindParam(":name", $product->name);
        $stmt->bindParam(":extarnalid", $product->extarnal_id);
        $stmt->bindParam(":datacreate", $product->data_create);
        $stmt->bindParam(":dataupdate", $product->data_update);

        if ($stmt->execute()) {

            return true;
        }

        return false;

    }

    public function getById($id): Product|null{

        $sql = "SELECT * FROM products WHERE id= ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(1,$id);

        $stmt->execute();

        if($stmt->rowCount()>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $product = new Product();
            $product->id = $row['id'];
            $product->name = $row['name'];
            $product->extarnal_id = $row['extarnalid'];
            $product->data_create = $row['datacreate'];
            $product->data_update = $row['dataupdate'];

            return $product;
        }

        return null;

    }
}

