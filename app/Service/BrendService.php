<?php

namespace app\Service;

use app\Config\Database;
use app\Entity\Brend;
use PDO;

class BrendService
{
    public function __construct(
        Database $conn
    )
    {
        $this->conn = $conn->getConnection();
    }

    public function create(string $name): bool{

        $sql = "INSERT INTO brends (name)
                VALUES (:name)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":name", $name);

        if($stmt->execute()){
            return true;
        }

        return false;

    }

    public function getCollection(): array
    {
        $sql = "SELECT * FROM brends";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $numRow = $stmt->rowCount();

        if($numRow<=0){
            echo json_encode(['message'=>'not found']);
        }

        $brends_collection = array();
        $brends_collection["brends"] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


            $brend_item = [
                'id' => $row['id'],
                'name' => $row['name']
            ];

            array_push($brends_collection['brends'], $brend_item);


        }
        return $brends_collection;
    }

    public function getById(int $id): Brend|null
    {
        $sql = "SELECT * FROM brends WHERE id=$id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        if($stmt->rowCount()>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $brend = new Brend();
            $brend->id = $row['id'];
            $brend->name = $row['name'];

            return $brend;
        }

        return null;


    }

    public function setPutById(int $id, mixed $name)
    {
        $sql = "UPDATE brends
                SET name=:name WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':name',$name);

        if ($stmt->execute()) {

            return true;
        }

        return false;
    }

    public function setPatchById(int $id, string $name): bool
    {
        $sql = "UPDATE brends 
                SET name=:name WHERE id =:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id',$id);
        $stmt->bindParam(':name',$name);

        if($stmt->execute()){
            return true;
        }
        return false;

    }

    public function getCollectionProducts(int $id): array
    {
        $sql = "SELECT p.id,p.name, p.extarnalid,p.datacreate,p.dataupdate FROM products AS p 
                INNER JOIN brends AS b ON p.brendid=$id";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $numRow = $stmt->rowCount();

        if($numRow<=0){
            echo json_encode(['message'=>'not found']);
        }

        $products_collection = array();
        $products_collection["products"] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $product_item = [
                'id' => $row['id'],
                'name' => $row['name'],
                'extarnal_id' => $row['extarnalid'],
                'data_create' => $row['datacreate'],
                'data_update' => $row['dataupdate'],
                'brend_id'=>$id
            ];

            array_push($products_collection['products'], $product_item);

        }

        return $products_collection;

    }

}