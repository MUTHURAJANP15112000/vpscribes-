<?php // Process form submission and integrate with Mailchimp API
ini_set("display_errors", 1);
$fname = $_POST['MMERGE3'];
$lname = $_POST['LNAME'];
$emr = $_POST['MMERGE6'];
$ps = $_POST['MMERGE7'];
$email = $_POST['EMAIL'];


$listId = '66ad5df9f3';

$apiKey = 'd4b0685bf0db0e99a3aba77811cd38f8-us21';


//Create mailchimp API url
$dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/';

//Member info
$data = array(
    'email_address' => $email,

    'status' => 'subscribed',
    'merge_fields' => array(
        'MMERGE3' => $fname,
        'LNAME' => $lname,
        'MMERGE6' => $emr,
        'MMERGE7' => $ps


    )
);

$jsonString = json_encode($data);

// send a HTTP POST request with curl
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
//Collecting the status
switch ($httpCode) {
    case 200:
        $msg = 'Success, newsletter subcribed using mailchimp API';
        break;
    case 214:
        $msg = 'Already Subscribed';
        break;
    default:
        $msg = 'Oops, please try again.[msg_code=' . $httpCode . ']';
        break;
}


header("location: ./index.html");
?>