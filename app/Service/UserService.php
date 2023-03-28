<?php

namespace app\Service;

use app\Config\Database;
use app\Entity\User;
use PDO;

class UserService
{

    public function __construct
    (
        Database $conn
    )
    {
        $this->conn = $conn->getConnection();
    }

    public function create(string $firstName, string $lastName): bool
    {
        $sql = "INSERT INTO users (firstname,lastname)
                VALUES (:first_name,:last_name)";

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':first_name',$user->firstName);
        $stmt->bindParam('last_name',$user->lastName);

        if($stmt->execute()){
            return true;
        }
        return false;


    }

    public function getCollection(): array
    {
        $sql = "SELECT * FROM users";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $numRow = $stmt->rowCount();

        if($numRow<=0){
            echo json_encode(['message'=>'not found']);
        }

        $users_collection = array();
        $users_collection["users"] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


            $user_item = [
                'id' => $row['id'],
                'first_name' => $row['firstname'],
                'last_name' => $row['lastname'],

            ];

            array_push($users_collection['users'], $user_item);


        }
        return $users_collection;
    }

    public function getById(int $id): User|null
    {
        $sql = "SELECT * FROM users WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id',$id);

        $stmt->execute();

        if($stmt->rowCount()>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $user = new User();
            $user->id = $row['id'];
            $user->firstName = $row['firstname'];
            $user->lastName = $row['lastname'];


            return $user;
        }

        return null;

    }

    public function setPutById(int $id, string $first_name, string $last_name)
    {

        $sql = "UPDATE users 
                SET firstname=:first_name, lastname=:last_name WHERE id=:id";

        $stmt = $this->conn->prepare($sql);


        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':last_name',$last_name);
        $stmt->bindParam(':id',$id);

        if ($stmt->execute()) {

            return true;
        }

        return false;
    }

    public function setPatchById(int $id, array $data): bool
    {
        $user = $this->getById($id);

        $sql = "UPDATE users
                SET firstname=:first_name, lastname=:last_name WHERE id=:id";

        $stmt = $this->conn->prepare($sql);

        $firstName = array_key_exists('first_name',$data)? $data['first_name']: $user->firstName;
        $lastName = array_key_exists('last_name',$data)? $data['last_name']: $user->lastName;

        $stmt->bindParam(':first_name',$firstName);
        $stmt->bindParam(':last_name',$lastName);
        $stmt->bindParam(':id',$id);

        if($stmt->execute()){
            return true;
        }
        return false;

    }
}