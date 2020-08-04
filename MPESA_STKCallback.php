<?php 
header("Content-Type:application/json");


$servername = "your_servername";
// Enter your MySQL username below(default=root)
$username = "your_username";
// Enter your MySQL password below
$password = "your_password";
$dbname = "your_dbname";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
	die("Connection failed: " . mysqli_connect_error());
}


$callbackJSONData=file_get_contents('php://input');

$callbackData=json_decode($callbackJSONData);
$resultCode=$callbackData->Body->stkCallback->ResultCode;
$resultDesc=$callbackData->Body->stkCallback->ResultDesc;
$merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
$checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;

$amount= $callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;


$mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;



//$balance= $callbackData->Body->stkCallback->CallbackMetadata->Item[2]->Value;


//$b2CUtilityAccountAvailableFunds=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;


$transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;


$phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;


$sql="INSERT INTO mpesa_payments
( 
	TransID,
	TransDate,
	TransAmount,
	phoneNumber,
	Status
	
)  
VALUES  
    ( 
	'$mpesaReceiptNumber', 
	'$transactionDate', 
	'$amount', 
	'$phoneNumber', 
     0)";

 

	if (!mysqli_query($con,$sql)) { 
		echo mysqli_error($con); 
	}  

	else 
	{ 
		echo '{"ResultCode":0,"ResultDesc":"Confirmation received successfully"}';
	}

	mysqli_close($con); 
	?>

