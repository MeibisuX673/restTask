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
                'data_update' => $row['dataupdate'],
                'brend_id'=>$row['brendid']
            ];

            array_push($products_collection['products'], $product_item);


        }
        return $products_collection;

    }

    public function create(string $name, int $brendId): bool{

        $sql = "INSERT INTO products (name,extarnalid,datacreate,dataupdate,brendid)
                VALUES (:name, :extarnalid, :datacreate, :dataupdate, :brendid)";

        $sqlCheckBrend = "SELECT * FROM brends WHERE id=$brendId";

        $stmtCheckBrend = $this->conn->prepare($sqlCheckBrend);

        $stmtCheckBrend->execute();

        if($stmtCheckBrend->rowCount()==0){
            return false;
        }

        $stmt = $this->conn->prepare($sql);

        $product = new Product();
        $product->name = $name;
        $product->extarnal_id = hash('sha256',str_shuffle("urgf74t23542shufrd242433t2"));
        $product->data_create = date('Y-m-d');
        $product->data_update = date('Y-m-d');
        $product->brend_id = $brendId;

        $stmt->bindParam(":name", $product->name);
        $stmt->bindParam(":extarnalid", $product->extarnal_id);
        $stmt->bindParam(":datacreate", $product->data_create);
        $stmt->bindParam(":dataupdate", $product->data_update);
        $stmt->bindParam(":brendid", $product->brend_id);

        if ($stmt->execute()) {

            return true;
        }

        return false;

    }

    public function getById(int $id): Product|null{

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

    public function setPutById(int $id, string $name): bool{

        $sql = "UPDATE products
                SET name=:name,dataupdate=:data_update WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $date = date('Y-m-d');

        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':data_update', $date);

        if ($stmt->execute()) {

            return true;
        }

        return false;

    }

    public function setPatchById(int $id, string $name): bool{

        $sql = "UPDATE products 
                SET name=:name,dataupdate=:date WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $date = date('Y-m-d');

        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':date',$date);

        if($stmt->execute()){
            return true;
        }

        return false;

    }
}

