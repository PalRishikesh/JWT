<?php
include_once '../config.php';
require "../vendor/autoload.php";

use \Firebase\JWT\JWT;

$db=new DB;
$connection=$db->getConnectionString();
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'POST':
    registerEmployee();
    break;
    case 'PUT':
    loginEmployee();
    break;
    case 'PATCH':
    verifyToken();
    break;
    default:
    header('Content-Type:application/josn');
    echo json_encode([
        'status'=>0,
        'message'=>'Method are not Allowed'
    ]);

    break;
}
function registerEmployee()
{
    global $connection;
    $data=json_decode(file_get_contents('php://input'), true);
    $name=$data["name"];
    $email=$data["email"];
    $password=$data["password"];
    $passwordHash=password_hash($password, PASSWORD_BCRYPT);
    $query="INSERT INTO details SET `name`='$name', email='$email', `password`='$passwordHash'";
    if (mysqli_query($connection, $query)) {
        header('Content-Type:application/json');
        echo json_encode([
            'status'=>1,
            'message'=>'Data Added successfully'
        ]);
    }
}
function loginEmployee()
{
    global $connection;
    $data=json_decode(file_get_contents('php://input'), true);
    $email=$data["email"];
    $password=$data["password"];
    $query="SELECT * FROM details WHERE email='$email'";
    $result=mysqli_query($connection, $query);
   
    if ($result) {
        while ($row=mysqli_fetch_assoc($result)) {
            // echo $row["password"];
            if (password_verify($password, $row["password"])) {
                $secretKey="HiThisIsSimpleKey";
                $issuerClaim="mywebsite.com";
                $audienceClaim="newAudience";
                $issueDateClaim=time();
                $notBeforeClaim=$issueDateClaim + 10;
                $expireClaim= $issueDateClaim + 60*200;
                $token=[
                    "iss"=>$issuerClaim,
                    "aud"=>$audienceClaim,
                    "iat"=>$issueDateClaim,
                    "nbf"=>$notBeforeClaim,
                    "exp"=>$expireClaim,
                    "data"=>[$row]
                ];
                // $jwt = JWT::encode($token, $secret_key);
                $newJwt = JWT::encode($token, $secretKey);

                header('Content-Type:application/json');
                echo json_encode([
                    'status'=>1,
                    'message'=>'Login successfully',
                    'Token'=>$newJwt
                ]);
            }
        }
    } else {
        echo "no";
    }
}
function verifyToken()
{
    global $connection;
    $data=json_decode(file_get_contents("php://input"), true);
    $token=$data["token"];
    $secretKey="HiThisIsSimpleKey";

    try {
        $decoded = JWT::decode($token, $secretKey, array('HS256'));
        header('Content-Type:application/json');
        echo json_encode(['status'=>1
    ,'data'=>$decoded->data]);
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}