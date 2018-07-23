<?php

/*STATUS CODES FOR ACTIVE REQUESTS:
    0: Inactive
    1: Active

  STATUS CODES FOR SUBMITTEDQUOTES:
    0: Open
    1: Won - Supplier was chosen and has confirmed the booking. 
    2: Lost - Supplier was not chosen at all.
    3: Request Lost - AC manually chooses to close or make the request lost. In this case options may have been presented to the client but AC has    closed the entire bid for some reason manually.
    4: Confirmation Declined - Supplier was chosen but they declined to book the business.
    5: Request Resubmitted - Resubmitted.
*/

$live = '/var/data/bridgestreet/QuoteHistory/';
$test = '/Users/marcoschabolla/Documents/Workspace/html/';



//******************** FUNCTIONS ************************* //

function log1($message){
  echo basename(__FILE__, '.php') . " - " . date('m/d/y H:i:s'). ":" . "\n" . $message . "\n"; 
}



//******************** ITERATE OVER FILES ************************* //


$dir = new DirectoryIterator($live);   ///var/data/bridgestreet/QuoteHistory/
foreach ($dir as $fileinfo) {
    if ($fileinfo->isDot()) { continue; }

$file = file_get_contents($live . $fileinfo->getFilename());
$file = preg_replace("/\n/", "|", $file);
$file = preg_replace("/[^\x20-\x7f]/", "", $file);
$file = preg_replace("/\|/", "\n", $file);
$file = str_replace("&nbsp;", " ", $file);
$doc = new DOMDocument();
@$doc->loadHTML($file);

$arrayforms = array();
$table = $doc->getElementById('ctl00_cphCenter_dgRequests');
//echo "FIRST ONE BITCH: " . $tables[0]->nodeValue . "\n";
$trs = $table->getElementsByTagName("tr");
$count = 0;



foreach($trs as $tr){
  if(!$tr->getAttribute("class"))
    break;
  $tds = $tr->getElementsByTagName("td");
  // echo "quoteID: " . substr($tds->item(5)->getElementsByTagName("a")[0]->getAttribute("href"),29 , -3) . " ";
  // echo "status: " . $tds->item(8)->nodeValue . " ";
  // echo "reason: " . $tds->item(9)->nodeValue . "\n";

  $arrayforms[$count]["quoteID"] = substr($tds->item(5)->getElementsByTagName("a")[0]->getAttribute("href"), 29, -3);
  $arrayforms[$count]["status"] = $tds->item(8)->nodeValue;
  $arrayforms[$count]["reason"] = $tds->item(9)->nodeValue;
  $count++;
}

$forms = array_slice($arrayforms, 1);
var_dump($forms);



//******************** DATABASE INSERT ************************* //

//Server
$servername = "******";                      
$username = "*******";
$password = "********";
$dbname = "*************";

//Create connection & check
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

foreach($forms as $form){
  $reason = $form["reason"];
  $status = $form["status"];
  $id = $form["quoteID"];

  switch($status){
    case "Won":
            $status = 1;
            break;
    case "Lost":
            $status = 2;
            break;
    case "Request Lost":
            $status =  3;
            break;
    case "Confirmation Declined":
            $status =  4;
            break;
    case "Request Resubmitted":
            $status = 5;
            break; 
  }

$sql = "UPDATE SubmittedQuotes SET reason = '$reason', status = '$status' WHERE quoteID = '$id' ";
// echo $sql;
  
   echo "\n";

  if ($conn->query($sql) === TRUE) {
    echo "Successful: ". $conn->affected_rows ." rows updated!";
    // echo "UPDATE SubmittedQuotes
    //     SET reason = $reason, status = $status
    //     WHERE quoteID = $id" ."\n";
  } else {
    echo "Error updating record: " . $conn->error;
  }
} //foreach

echo "\n";
$conn->close();


} //Iterator
?>