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


//SubmittedQuotesParser receives html by file name of Quote.aspx?ID=bidID~quoteID
//EX:
//Quote.aspx?ID=A8FB0E8A-3CAE-427A-8080-AD4A2CF578F9~6A53C567-6BE3-444C-A459-A80ED04A7FED


$live = '/var/data/bridgestreet/SubmittedQuotes/';
$test = '/Users/marcoschabolla/Documents/Workspace/html/';



//******************** FUNCTIONS ************************* //

function log1($message){
  echo basename(__FILE__, '.php') . " - " . date('m/d/y H:i:s'). ":" . "\n" . $message . "\n"; 
}



//******************** ITERATE OVER FILES ************************* //

$dir = new DirectoryIterator($live); ///var/data/bridgestreet/SubmittedQuotes/
foreach ($dir as $fileinfo) {                                             ///Users/marcoschabolla/Documents/Workspace/html/
    if ($fileinfo->isDot()) { continue; }

$file = file_get_contents($live . $fileinfo->getFilename());
$file = preg_replace("/\n/", "|", $file);
$file = preg_replace("/[^\x20-\x7f]/", "", $file);
$file = preg_replace("/\|/", "\n", $file);
$file = str_replace("&nbsp;", " ", $file);

$doc = new DOMDocument();
@$doc->loadHTML($file);

$inputs = $doc->getElementsByTagName('input');
$selects = $doc->getElementsByTagName('select');
$textarea = $doc->getElementsByTagName('textarea');
$count = 0;
$arrayforms = [];
$arrayforms['reason'] = "";
$arrayforms['status'] = 0;
$arrayforms['quoteID'] = substr($fileinfo->getFilename(), 14, 36);
$arrayforms['bidID'] = substr($fileinfo->getFilename(), 51);
$arrayforms['petPayTypes'] = "";




//Log info
// $date = date_create();
// echo date_format($date, 'U = Y-m-d H:i:s') . "\n";
log1( "bidID: " . $arrayforms['bidID'] . "\n");
echo $fileinfo->getFilename() . "\n";
echo "quoteID: " . $arrayforms['quoteID'] . "\n";



//******************** PARSING FORM ************************* //

foreach ($inputs as $input) {
    // echo $count . ": " . $input->getAttribute("id") ." ". $input->getAttribute("value") ." id: ", PHP_EOL;
    $varName = substr($input->getAttribute("id"), 19);
    // echo $varName;
        switch ($varName) {
        case "RepFirstName":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "RepLastName":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "RepPhone":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "RepEmail":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "CoreInventory_0":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyName":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyUrl":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyAddress":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyAddress2":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyCity":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyState":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyZip":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "PropertyCountry":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Amenities_0":
            if($input->getAttribute("checked"))
              $arrayforms['24hourReception'] = 1;
            else
              $arrayforms['24hourReception'] = 0;
            break;
        case "Amenities_1":
            if($input->getAttribute("checked"))
              $arrayforms['airConditioning'] = 1;
            else
              $arrayforms['airConditioning'] = 0;            
            break;
        case "Amenities_2":
            if($input->getAttribute("checked"))
              $arrayforms['airportTransportation'] = 1;
            else
              $arrayforms['airportTransportation'] = 0;            
            break;
        case "Amenities_3":
            if($input->getAttribute("checked"))
              $arrayforms['carParking'] = 1;
            else
              $arrayforms['carParking'] = 0;            
            break;
        case "Amenities_4":
            if($input->getAttribute("checked"))
              $arrayforms['highSpeedInternet'] = 1;
            else
              $arrayforms['highSpeedInternet'] = 0;            
            break;
        case "Amenities_5":
            if($input->getAttribute("checked"))
              $arrayforms['housekeeping'] = 1;
            else
              $arrayforms['housekeeping'] = 0;            
            break;
        case "Amenities_6":
            if($input->getAttribute("checked"))
              $arrayforms['keysMailed'] = 1;
            else
              $arrayforms['keysMailed'] = 0;            
            break;
        case "Amenities_7":
            if($input->getAttribute("checked"))
              $arrayforms['liftAccess'] = 1;
            else
              $arrayforms['liftAccess'] = 0;            
            break;
        case "Amenities_8":
            if($input->getAttribute("checked"))
              $arrayforms['localPhone'] = 1;
            else
              $arrayforms['localPhone'] = 0;            
            break;
        case "Amenities_9":
            if($input->getAttribute("checked"))
              $arrayforms['meetAndGreet'] = 1;
            else
              $arrayforms['meetAndGreet'] = 0;            
            break;
        case "Amenities_10":
            if($input->getAttribute("checked"))
              $arrayforms['satelliteCableTV'] = 1;
            else
              $arrayforms['satelliteCableTV'] = 0;            
            break;
        case "Amenities_11":
            if($input->getAttribute("checked"))
              $arrayforms['petFriendly'] = 1;
            else
              $arrayforms['petFriendly'] = 0;            
            break;
        case "Amenities_12":
            if($input->getAttribute("checked"))
              $arrayforms['backgroundCheck'] = 1;
            else
              $arrayforms['backgroundCheck'] = 0;            
            break;
        case "PetFee":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "DateAvailable":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Distance":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Tax":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Rate0":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Discount0":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Rate1":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Discount1":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Rate2":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Discount2":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Rate3":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Discount3":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "CommissionPercent":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "SquareFootage":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Reduction30Days_0":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "Reduction30DaysComments":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
        case "CanBeSplit_0":
            $arrayforms[$varName] = $input->getAttribute("value");
            break;
    }
}

foreach ($selects as $select) {
      $varName = substr($select->getAttribute("id"), 19);

      $options = $select->getElementsByTagName('option');
      foreach($options as $option){
        if($option->getAttribute('selected')){
           switch ($varName) {
            case "SupplierProperty":
                $arrayforms[$varName] = $option->getAttribute("value");
                break;
            case "BWComProperties":
                $arrayforms[$varName] = $option->getAttribute("value");
                break;
            case "Refundables":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "petPayTypes":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Parkings":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "WasherDryer":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Internet":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Bathrooms":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "QuoteValidType":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "BudgetTypeID":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Parking":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Currency":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "NumStudios":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Num1Bedrooms":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Num2Bedrooms":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "Num3Bedrooms":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "LeaseTerm":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "NoticeTerm":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "AdvancedDays":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "AdvancedHours":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "CommissionTypeID":
                $arrayforms[$varName] = $option->textContent;
                break;
            case "VATID":
                $arrayforms[$varName] = $option->textContent;
                break;

          // echo $varName .": ". $option->textContent . "\n";
                  }
           }
      }
}

foreach ($textarea as $text) {
      $comment = $text->textContent;
      $arrayforms['comment'] = $comment;
}

// var_dump($arrayforms);



// ******************** DATABASE SETUP/INSERT ************************* //

//Server
$servername = "*****";                
$username = "*****";
$password = "*****";
$dbname = "*****";

//Create connection & check
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$rfn = $arrayforms['RepFirstName'];
$rln = $arrayforms['RepLastName'];
$rp = $arrayforms['RepPhone'];
$re = $arrayforms['RepEmail'];
$sp = $arrayforms['SupplierProperty'];
$BWCP = $arrayforms['BWComProperties'];
$ci = $arrayforms['CoreInventory_0'];
$pn = $arrayforms['PropertyName'];
$purl = $arrayforms['PropertyUrl'];
$pa = $arrayforms['PropertyAddress'];
$pat = $arrayforms['PropertyAddress2'];
$pc = $arrayforms['PropertyCity'];
$ps = $arrayforms['PropertyState'];
$pz = $arrayforms['PropertyZip'];
$pco = $arrayforms['PropertyCountry'];
$hsi = $arrayforms['highSpeedInternet'];
$ac = $arrayforms['airConditioning'];
$hk = $arrayforms['housekeeping'];
$lp = $arrayforms['localPhone'];
$cp = $arrayforms['carParking'];
$tfhr = $arrayforms['24hourReception'];
$at = $arrayforms['airportTransportation'];
$keysmailed = $arrayforms['keysMailed'];
$la = $arrayforms['liftAccess'];
$mag = $arrayforms['meetAndGreet'];
$cable = $arrayforms['satelliteCableTV'];
$petf = $arrayforms['petFriendly'];
$backcheck = $arrayforms['backgroundCheck'];
$pf = $arrayforms['PetFee'];
$refund = $arrayforms['Refundables'];
$wd = $arrayforms['WasherDryer'];
$internet = $arrayforms['Internet'];
$ppt = $arrayforms['petPayTypes'];
$park = $arrayforms['Parkings'];
$bath = $arrayforms['Bathrooms'];
$da = $arrayforms['DateAvailable'];
$qvt = $arrayforms['QuoteValidType'];
$btID = $arrayforms['BudgetTypeID'];
$dist = $arrayforms['Distance'];
$parking = $arrayforms['Parking'];
$curr = $arrayforms['Currency'];
$tax = $arrayforms['Tax'];
$ns = $arrayforms['NumStudios'];
$rz = $arrayforms['Rate0'];
$dz = $arrayforms['Discount0'];
$nob = $arrayforms['Num1Bedrooms'];
$ro = $arrayforms['Rate1'];
$do = $arrayforms['Discount1'];
$ntb = $arrayforms['Num2Bedrooms'];
$rt = $arrayforms['Rate2'];
$dt = $arrayforms['Discount2'];
$nthb = $arrayforms['Num3Bedrooms'];
$rth = $arrayforms['Rate3'];
$dth = $arrayforms['Discount3'];
$lt = $arrayforms['LeaseTerm'];
$nt = $arrayforms['NoticeTerm'];
$ad = $arrayforms['AdvancedDays'];
$ah = $arrayforms['AdvancedHours'];
$ctID = $arrayforms['CommissionTypeID'];
$comp = $arrayforms['CommissionPercent'];
$VATID = $arrayforms['VATID'];
$sf = $arrayforms['SquareFootage'];
$rthd = $arrayforms['Reduction30Days_0'];
$rthdc = $arrayforms['Reduction30DaysComments'];
$cbs = $arrayforms['CanBeSplit_0'];
$comment = addslashes($arrayforms['comment']);
$bidID = $arrayforms['bidID'];
$quoteID = $arrayforms['quoteID'];
$reason = $arrayforms['reason'];
$status = $arrayforms['status'];


$sql = "INSERT IGNORE INTO SubmittedQuotes (quoteID, bidID, repFirstName, repLastName, repPhone, repEmail, supplierProperty, bWComProperties, coreInventory, propertyName, propertyUrl, propertyAddress, propertyAddress2, propertyCity, propertyState, propertyZip, propertyCountry, highSpeedInternet, airConditioning, housekeeping, localPhone, carParking, 24hourReception, airportTransportation, keysMailed, liftAccess, meetAndGreet, satelliteCableTV, petFriendly, backgroundCheck, petFee, refundables, petPayTypes, parkings, washerDryer, internet, bathrooms, dateAvailable, quoteValidType, budgetTypeID, distance, parking, currency, tax, numStudios, rate0, discount0, num1Bedrooms, rate1, discount1, num2Bedrooms, rate2, discount2, num3Bedrooms, rate3, discount3, leaseTerm, noticeTerm, advancedDays, advancedHours, commissionTypeID, commissionPercent, VATID, squareFootage, reduction30Days, reduction30DaysComment, canBeSplit, comment, reason, status) VALUES ('$quoteID', '$bidID', '$rfn', '$rln', '$rp', '$re', '$sp', '$BWCP', '$ci', '$pn', '$purl', '$pa', '$pat', '$pc', '$ps', '$pz', '$pco', '$hsi', '$ac', '$hk', '$lp', '$cp', '$tfhr', '$at', '$keysmailed', '$la', '$mag', '$cable', '$petf', '$backcheck', '$pf', '$refund', '$ppt', '$park', '$wd', '$internet', '$bath', '$da', '$qvt', '$btID', '$dist', '$parking', '$curr', '$tax', '$ns', '$rz', '$dz', '$nob', '$ro', '$do', '$ntb', '$rt', '$dt', '$nthb', '$rth', '$dth', '$lt', '$nt', '$ad', '$ah', '$ctID', '$comp', '$VATID', '$sf', '$rthd', '$rthdc', '$cbs', '$comment', '$reason', '$status')";
  
   //echo $sql;

  if ($conn->query($sql)) {
    echo "Insert Successful: ". $conn->affected_rows ." rows updated!";
  } else {
    echo "Error: ". $sql ."\n". $conn->error;
  }


echo "\n";
$conn->close();



// ******************** OUTPUT PARSED FORM HTML INTO HTML ARCHIVE ************************* //

rename('/var/data/bridgestreet/SubmittedQuotes/'.$fileinfo->getFilename(), '/var/data/bridgestreet/SubmittedQuoteHTML/'. $fileinfo->getFilename());



}
?>