<?php
// class dbObj
// {
//     public $serverName="localhost";
//     public $userName="root";
//     public $password="root";
//     public $dbName="aa_rest";
//     public $conn=null;
//     public function getConnstring()
//     {
//         $con=mysqli_connect($this->serverName, $this->userName, $this->password, $this->dbName);
//         // if (mysqli_connect_errno()) {
//         //     printf('Connection Failed dd: %s\n', mysqli_connect_error());
//         //     exit();
//         // } else {
//         //     $this->conn=$con;
//         // }
//         if (mysqli_connect_error()) {
//             printf("Connection Failed : ".mysqli_connect_error());
//             exit();
//         } else {
//             $this->conn=$con;
//         }
//         return $this->conn;
//     }
// }

class DB
{
    public $serverName="localhost";
    public $userName="root";
    public $password="root";
    public $dbName="aaa_rest";
    public $conn=null;
    public function getConnectionString()
    {
        $con=mysqli_connect($this->serverName, $this->userName, $this->password, $this->dbName);
        if (mysqli_connect_errno()) {
            echo "Connection Failed : ".mysqli_connect_error();
            exit();
        } else {
            $this->conn=$con;
        }
        return $this->conn;
    }
}