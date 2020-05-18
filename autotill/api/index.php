<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../autotill.php';
$make_change = new AutoTill();

if ( isset($_GET['mode'])) {
  $make_change->set_mode($_GET['mode']); 
} else {
  $make_change->set_mode('standard');
}

if ( isset($_GET['val']) ) {
  // Sanitize request
  $cleanVal = $make_change->make_float($_GET['val']);
  if (!$cleanVal || $cleanVal === 0){
    http_response_code(400);
    echo "invalid value.";
  } else {
    $json = json_encode($make_change->count_change($cleanVal));
    http_response_code(200);
    echo $json;
  }
} else {
  http_response_code(400);
  echo 'no value provided.';
}