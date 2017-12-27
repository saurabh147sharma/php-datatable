<?php
include_once("connection.php");
$requestData= $_REQUEST;


$columns = array(
    0 =>'name',
    1 => 'email',
    2=> 'phone'
);

$sql = "SELECT id,name, email, phone FROM users";
$query=mysqli_query($conn, $sql);
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;

$searchKeyWord = htmlspecialchars($requestData['search']['value']);
if( !empty($searchKeyWord) ) {
    $sql.=" WHERE name LIKE '".$searchKeyWord."%' ";
    $sql.=" OR email LIKE '".$searchKeyWord."%' ";
    $sql.=" OR phone LIKE '".$searchKeyWord."%' ";
    $query=mysqli_query($conn, $sql);
    $totalFiltered = mysqli_num_rows($query);

}
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($conn, $sql);


$data = array();
while( $row=mysqli_fetch_array($query) ) {
    $data[] = ['id'=>$row['id'],'name'=>$row['name'],'email'=>$row['email'],'phone'=>$row['phone']];
}



$json_data = array(
    "draw"            => intval( $requestData['draw'] ),
    "recordsTotal"    => intval( $totalData ),
    "recordsFiltered" => intval( $totalFiltered ),
    "data"            => $data
);

echo json_encode($json_data);