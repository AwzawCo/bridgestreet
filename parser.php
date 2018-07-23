<?php

$FLAG = 1;      // Global Flag that signals that the DB returned 0 entries with a status of 1 (Active)

// **********DOUBLE CHECK THAT bidID IS WORKING CORRECTLY*************
// *******************************************************************

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

$live = '/var/data/bridgestreet/ActiveRequests/';
$test = '/Users/marcoschabolla/Documents/Workspace/html/';



//******************** MYSQL SETUP AND SELECT ************************* //

//Server Setup
$servername = "******";     
$username = "*******";
$password = "*******";
$dbname = "*******";

$activeRequestsInDB = [];


//Create connection & check
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

  //Grab all Bids with close_timestamp = NULL;
  $getActiveRequestsInDB = "SELECT bidID FROM ActiveRequests WHERE status = 1";
  $res = $conn->query($getActiveRequestsInDB);
  $i=0;
  if ($res->num_rows > 0) {
    echo "Retrieved Rows: " . $res->num_rows . "\n";
    while($row = $res->fetch_assoc()){
      $activeRequestsInDB[$i++] = $row["bidID"];
    }
    // output data of each row
    // while($row = $activeRequestsInDB->fetch_assoc()) {
    //     echo "bidID: " . $row["bidID"] . "\n";
    // }
  } else {
    $FLAG = 0;
    log1( "0 results for status = 1 in DB \n" );
  }

//TESTING
//Files are named after their bidID exemplified below
//$activeRequestsInDB = array("45A53B30-1C86-4DFE-9DCB-52C44B4A91CA", "01D4275C-92D9-4F18-B44E-9D8D213332D2", "FF62B3C2-28D7-4DBA-81EA-C148CE2DE6A8");
//Active Requests are received as only bidID's


//******************** FUNCTIONS ************************* //

function compareToDB($bidID){
  if(!$GLOBALS['FLAG']){
    return -1;
  }
  $indexerino = -1;
  $funcActiveRequestsInDB = $GLOBALS['activeRequestsInDB'];
  foreach ($funcActiveRequestsInDB as $key => $row_bidID) {
    //If found the bid in the Array of unclosed Active Requests from the DB return true, if not false
    if($row_bidID == $bidID){
      return $key;    //IndexNumber of $row_bidID: 0, 1, 2, ...
    } else{
      continue;
    }
  }
  //If it iterated over everything and didnt match return false;
  return $indexerino;
}

function log1($message){
  echo basename(__FILE__, '.php') . " - " . date('m/d/y H:i:s'). ":" . "\n" . $message . "\n"; 
}


//******************** ITERATE OVER ALL FILES IN DIRECTORY ************************* //

$dir = new DirectoryIterator($live); ///var/data/bridgestreet/ActiveRequests/
foreach ($dir as $fileinfo) {                                                   ///Users/marcoschabolla/Documents/Workspace/html/
  if ($fileinfo->isDot()) { continue; }

$file = file_get_contents($live . $fileinfo->getFilename());
$file = preg_replace("/\n/", "|", $file);
$file = preg_replace("/[^\x20-\x7f]/", "", $file);
$file = preg_replace("/\|/", "\n", $file);
$file = str_replace("&nbsp;", " ", $file);
// $activeRequestsInDB = $GLOBALS['activeRequestsInDB']
        

$doc = new DOMDocument();
$doc->loadHTML($file);
$tables = $doc->getElementsByTagName('tr');
  // echo $doc->saveHTML();

  $count = 0;

  $arrayforms = [];
  //$arrayforms["id"] = $fileinfo->getFilename();
  $arrayforms["bidID"] = $fileinfo->getFilename(); //"45A53B30-1C86-4DFE-9DCB-52C44B4A91CA";  For Testing
  $arrayforms["status"] = 1;
  $arrayforms["Air Conditioning"] = 'No';
  $arrayforms["Car Parking"] = 'No';
  $arrayforms["High-Speed Internet"] = 'No';
  $arrayforms["Housekeeping"] = 'No';
  $arrayforms["Local Phone"] = 'No';
  $arrayforms["Satellite/Cable TV"] = 'No';



  //******************** PARSES THE FILE ************************* //

  foreach ($tables as $row) {
      $tds = $row->getElementsByTagName('td');

      if(isset($tds[0]->nodeValue) && $tds[0]->nodeValue != ""){
        // echo $count . ': ';
        // echo $tds[0]->nodeValue ."\n";

        if($tds[0]->nodeValue == 'Amenities:'){
          $amenities = $tds[1]->getElementsByTagName('li');
          foreach($amenities as $amenity){
            $arrayforms[$amenity->nodeValue] = 'Yes';
          }
        }
        else{
        $arrayforms[$tds[0]->nodeValue] = isset($tds[1]->nodeValue) ? $tds[1]->nodeValue: "";
        }

      } else {  continue;   }
  }
  // var_dump($arrayforms);



  //******************** ARRAY COMPARISON LOGIC AND DATABASE HANDLING ************************* //


  $index = compareToDB($arrayforms['bidID']);
  if($index != -1){
        echo "Unsetting bidID: " . $activeRequestsInDB[$index]; 
        unset($activeRequestsInDB[$index]);

        //var_dump($activeRequestsInDB);

  } else {
    $row = $arrayforms;

        $utype = isset($row["Type of Housing Request:"]) ? $row["Type of Housing Request:"] : "";
        $uname = isset($row["Name:"]) ? $row["Name:"] : "";
        $email = isset($row["Email Address:"]) ? $row["Email Address:"] : "";
        $phone = isset($row["Phone Number:"]) ? $row["Phone Number:"] : "";
        $clientName = isset($row["Client Name:"]) ? $row["Client Name:"] : "";
        $guestName = isset($row["Guest Name:"]) ? $row["Guest Name:"] : "";
        $companyName = isset($row["Company Name:"]) ? $row["Company Name:"] : "";
        $locale = isset($row["Locale:"]) ? $row["Locale:"] : "";
        $address = isset($row["Address:"]) ? $row["Address:"] : "";
        $moveInDate = isset($row["Move-in Date:"]) ? $row["Move-in Date:"] : "";
        $moveOutDate = isset($row["Move-out Date:"]) ? $row["Move-out Date:"] : "";
        $LOS = isset($row["LOS:"]) ? $row["LOS:"] : "";
        $numStudios = isset($row["# of Studios:"]) ? $row["# of Studios:"] : "";
        $numOneBedroom = isset($row["# of 1 Bedrooms:"]) ? $row["# of 1 Bedrooms:"] : "";
        $numTwoBedroom = isset($row["# of 2 Bedrooms:"]) ? $row["# of 2 Bedrooms:"] : "";
        $numThreeBedroom = isset($row["# of 3 Bedrooms:"]) ? $row["# of 3 Bedrooms:"] : "";
        $numBathrooms = isset($row["# of Bathrooms:"]) ? $row["# of Bathrooms:"] : "";
        $numParkingSpaces = isset($row["# of Parking Spaces:"]) ? $row["# of Parking Spaces:"] : "";
        $reqLeaseTerms = isset($row["Requested Lease Terms:"]) ? $row["Requested Lease Terms:"] : "";
        $reqNoticeTerms = isset($row["Requested Notice Terms:"]) ? $row["Requested Notice Terms:"] : "";

        $temp_SR = addslashes($row["Special Requests:"]);

        $specialRequests = isset($temp_SR) ? $temp_SR : "";
        $amenities = isset($row["Amenities:"]) ? $row["Amenities:"] : "";
        $petTypeWeight = isset($row["Pet Type and Weight:"]) ? $row["Pet Type and Weight:"] : "";
        $washerDryer = isset($row["Washer/Dryer:"]) ? $row["Washer/Dryer:"] : "";
        $internet = isset($row["Internet:"]) ? $row["Internet:"] : "";
        $coreInventory = isset($row["Core Inventory:"]) ? $row["Core Inventory:"] : "";
        $currency = isset($row["Currency:"]) ? $row["Currency:"] : "";
        $referralFee = isset($row["Referral Fee:"]) ? $row["Referral Fee:"] : "";
        $easySourceFee = isset($row["EasySource Fee:"]) ? $row["EasySource Fee:"] : "";
        $GAM = isset($row["GAM:"]) ? $row["GAM:"] : "";
        $uid = isset($row["bidID"]) ? $row["bidID"] : "";
        $status = isset($row["status"]) ? $row["status"] : "";

         $sql = "INSERT IGNORE INTO ActiveRequests (type, name, email, phoneNumber, clientName, guestName, companyName, locale, address, moveIn, moveOut, LOS, numStudios, numOneBedroom, numTwoBedroom, numThreeBedroom, numBathroom, numParkingSpaces, requestedLeastTerms, requestedNoticeTerms, specialRequests, amenities, petTypeWeight, washerDryer, internet, coreInventory, currency, referralFee, easySourceFee, GAM, bidID, status) VALUES ('$utype', '$uname', '$email', '$phone', '$clientName', '$guestName', '$companyName', '$locale', '$address', '$moveInDate', '$moveOutDate', '$LOS', '$numStudios', '$numOneBedroom', '$numTwoBedroom', '$numThreeBedroom', '$numBathrooms', '$numParkingSpaces', '$reqLeaseTerms', '$reqNoticeTerms', '$specialRequests', '$amenities', '$petTypeWeight', '$washerDryer', '$internet', '$coreInventory', '$currency', '$referralFee', '$easySourceFee', '$GAM', '$uid', '$status')";
        
         echo "\n" . $sql . "\n";

        if ($conn->query($sql)) {
            echo "Insert Successful: ". $conn->affected_rows ." rows updated!";
        } else {
            log1( "Error: " . "\n" . $conn->error );
        }
  }// else



  //******************** OUTPUT PARSED FORM INTO DONE FOLDER ************************* //


  rename('/var/data/bridgestreet/ActiveRequests/' . $fileinfo->getFilename(), '/var/data/bridgestreet/ActiveRequestHTML/'. $fileinfo->getFilename());

} //Iterator loop



//********************  CLOSES THE REST OF THE UNCLOSED ACTIVE REQUESTS ************************* //

foreach($activeRequestsInDB as $activeRequest){
  $sql = "UPDATE ActiveRequests SET close_timestamp = FROM_UNIXTIME(UNIX_TIMESTAMP()), status = 0 WHERE bidID = '$activeRequest' ";
  $conn->query($sql);
  echo $sql . "\n";
}

$conn->close();
?>
