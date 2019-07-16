<?php

include_once('../config.php');
$db=new DB;
$connection=$db->getConnectionString();

$requestMethod=$_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'GET':
    if (!empty($_GET["id"])) {
        $id=$_GET["id"];
        employee($id);
    } else {
        employees();
    }
    break;
    case 'POST':
    insertEmployee();
    break;
    case 'DELETE':
    deleteEmploye();
    break;
    case 'PUT':
    updateEmployee();
    break;
    default:
    header('Content-Type: application/json');
    echo json_encode([
        'status'=>0,
        'message'=>'Method are not allowed'
    ]);
}

function employees()
{
    global $connection;
    $query="SELECT * FROM employees";
    $response=[];
    $result=mysqli_query($connection, $query);
    while ($row=mysqli_fetch_assoc($result)) {
        $response[]=$row;
    }
    header('Content-Type: application/json');
    echo json_encode([
        'status'=>1,
        "data"=>$response
    ]);
}
function employee($id=0)
{
    global $connection;
    $query="SELECT * FROM employees";
    if ($id>0) {
        $query.=" WHERE id=".$id;
    }
    $result=mysqli_query($connection, $query);
    $response=[];
    while ($row=mysqli_fetch_assoc($result)) {
        $response[]=$row;
    }
    header('Content-Type:application/json');
    echo json_encode([
        'status'=>1,
        "data"=>$response
    ]);
}
function insertEmployee()
{
    global $connection;
    $data=json_decode(file_get_contents('php://input'), true);
    $employee_name=$data["employee_name"];
    $employee_salary=$data["employee_salary"];
    $employee_age=$data["employee_age"];
    $query="INSERT INTO employees SET employee_name='$employee_name', employee_salary='$employee_salary', employee_age='$employee_age'";
    if (mysqli_query($connection, $query)) {
        $response=[
            'status'=>1,
            'data'=>'Employee Added Successfully'
        ];
    } else {
        $response=[
            'status'=>0,
            'message'=>'unabled to add employee'
        ];
    }
    header('Content-Type:application/json');
    echo json_encode($response);
}
function deleteEmploye()
{
    global $connection;
    $data=json_decode(file_get_contents("php://input"), true);
    $id=$data["id"];
    $query="DELETE FROM employees WHERE id=".$id;
    if (mysqli_query($connection, $query)) {
        header('Content-Type:application/json');
        echo json_encode([
            'status'=>1,
            'message'=>'Data Delted Successfully'
        ]);
    }
}

function updateEmployee()
{
    global $connection;
    $data=json_decode(file_get_contents("php://input"), true);
    $id=$data["id"];
    $name=$data["employee_name"];
    $salary=$data["employee_salary"];
    $age=$data["employee_age"];
    $query="UPDATE employees SET employee_name='$name', employee_salary='$salary', employee_age='$age' WHERE id=".$id;
    // exit();
    if (mysqli_query($connection, $query)) {
        header('Content-Type:application/json');
        echo json_encode([
            'status'=>1,
            'message'=>'Data updated successfully'
        ]);
    }
}