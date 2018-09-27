var casper = require('casper').create(); 
var mouse = require("mouse").create(casper);
var fs = require('fs');
var utils = require('utils');
var p = require('process');

var file = casper.cli.args['0']; // will grab from argument
var index = file.indexOf('.txt')
var id = file.substring(0, index)
console.log('Casper Start.\nSubmitting bid for:', id)
// var dirPath = '/var/data/dashboard/queue/'+id;
// var moveFrom = dirPath;
// var moveTo = '/var/data/dashboard/complete/'+id; // double check
// var jsonData = fs.read(dirPath)
// var data = createJSON(jsonData);

// test data
var id = 'FE942246-036A-4F98-AC22-23E50408CFA3';
var dirPath = '/Users/jgdepew/Documents/valyn/testTextFiles/t9.txt';
var moveFrom = dirPath;
var moveTo = '/Users/jgdepew/Documents/valyn/txt/t9.txt';
var jsonData = fs.read(dirPath)
var data = createJSON(jsonData)
// end test data 

function convertJSONQuoteData(data) {
    var newJSON = {}
    for (var key in data) {
        // input switches
        switch (key) {
            // inputs
            case 'repFirstName':
                newJSON['#ctl00_cphCenter_txtRepFirstName'] = data[key];
                break;
            case 'repLastName':
                newJSON['#ctl00_cphCenter_txtRepLastName'] = data[key];
                break;
            case 'repPhone':
                newJSON['#ctl00_cphCenter_txtRepPhone'] = data[key];
                break;
            case 'repEmail':
                newJSON['#ctl00_cphCenter_txtRepEmail'] = data[key];
                break;
            case '':
                newJSON['#ctl00_cphCenter_fuProfile'] = data[key];
            break;
            case 'coreInventory':
                newJSON['#ctl00_cphCenter_rblCoreInventory'] = data[key];
                break;
            case 'propertyName':
                newJSON['#ctl00_cphCenter_txtPropertyName'] = data[key];
                break;
            case 'propertyURL':
                newJSON['#ctl00_cphCenter_txtPropertyUrl'] = data[key];
                break;
            case 'propertyAddress':
                newJSON['#ctl00_cphCenter_txtPropertyAddress'] = data[key];
                break;
            case 'propertyAddress2':
                newJSON['#ctl00_cphCenter_txtPropertyAddress2'] = data[key];
                break;
            case 'propertyCity':
                newJSON['#ctl00_cphCenter_txtPropertyCity'] = data[key];
                break;
            case 'propertyState':
                newJSON['#ctl00_cphCenter_txtPropertyState'] = data[key];
                break;
            case 'propertyZip':
                newJSON['#ctl00_cphCenter_txtPropertyZip'] = data[key];
                break;
            case 'propertyCountry':
                newJSON['#ctl00_cphCenter_txtPropertyCountry'] = data[key];
                break;
            case '24hourReception':
                newJSON['#ctl00_cphCenter_cblAmenities_0'] = data[key];
                break;
            case 'highSpeedInternet':
                newJSON['#ctl00_cphCenter_cblAmenities_4'] = data[key];
                break;
            case 'localPhone':
                newJSON['#ctl00_cphCenter_cblAmenities_8'] = data[key];
                break;
            case 'washerDryerCheck':
                newJSON['#ctl00_cphCenter_cblAmenities_11'] = data[key];
                break;
            case 'airConditioning':
                newJSON['#ctl00_cphCenter_cblAmenities_1'] = data[key];
                break;
            case 'housekeeping':
                newJSON['#ctl00_cphCenter_cblAmenities_5'] = data[key];
                break;
            case 'meetAndGreet':
                newJSON['#ctl00_cphCenter_cblAmenities_9'] = data[key];
                break;
            case 'petFriendly':
                newJSON['#ctl00_cphCenter_cblAmenities_12'] = data[key];
                break;
            case 'carParking':
                newJSON['#ctl00_cphCenter_cblAmenities_2'] = data[key];
                break;
            case 'keysMailed': 
                newJSON['#ctl00_cphCenter_cblAmenities_6'] = data[key];
                break;
            case 'satelliteCableTV':
                newJSON['#ctl00_cphCenter_cblAmenities_10'] = data[key];
                break;
            case 'backgroundCheck':
                newJSON['#ctl00_cphCenter_cblAmenities_13'] = data[key];
                break;
            case 'carParking':
                newJSON['#ctl00_cphCenter_cblAmenities_3'] = data[key];
                break;
            case 'liftAccess':
                newJSON['#ctl00_cphCenter_cblAmenities_7'] = data[key];
                break;
            case 'petFee':
                newJSON['#ctl00_cphCenter_txtPetFee'] = data[key];
                break;
            case 'dateAvailable':
                newJSON['#ctl00_cphCenter_txtDateAvailable'] = data[key];
                break;
            case 'distance':
                newJSON['#ctl00_cphCenter_txtDistance'] = data[key];
                break;
            case 'tax':
                newJSON['#ctl00_cphCenter_txtTax'] = data[key];
                break;
            case 'rate0':
                newJSON['#ctl00_cphCenter_txtRate0'] = data[key];
                break;
            case 'discount0':
                newJSON['#ctl00_cphCenter_txtDiscount0'] = data[key];
                break;
            case 'rate1':
                newJSON['#ctl00_cphCenter_txtRate1'] = data[key];
                break;
            case 'discount1':
                newJSON['#ctl00_cphCenter_txtDiscount1'] = data[key];
                break;
            case 'rate2':
                newJSON['#ctl00_cphCenter_txtRate2'] = data[key];
                break;
            case 'discount2':
                newJSON['#ctl00_cphCenter_txtDiscount2'] = data[key];
                break;
            case 'rate3':
                newJSON['#ctl00_cphCenter_txtRate3'] = data[key];
                break;
            case 'discount3':
                newJSON['#ctl00_cphCenter_txtDiscount3'] = data[key];
                break;
            case 'commissionPercent':
                newJSON['#ctl00_cphCenter_txtCommissionPercent'] = data[key];
                break;
            case 'squareFootage':
                newJSON['#ctl00_cphCenter_txtSquareFootage'] = data[key];
                break;
            case 'reduction30Days':
                newJSON['#ctl00_cphCenter_rblReduction30Days'] = data[key];
                break;
            case 'reduction30DaysComment':
                newJSON['#ctl00_cphCenter_txtReduction30DaysComments'] = data[key];
                break;
            case 'canBeSplit':
                newJSON['#ctl00_cphCenter_rblCanBeSplit'] = data[key];
                break;
            // selects
            case 'refundable':
                newJSON['#ctl00_cphCenter_ddlRefundables'] = data[key];
                break;
            case 'petTotal':
                newJSON['#ctl00_cphCenter_ddlPetPayTypes'] = data[key];
                break;
            case 'parkings':
                newJSON['#ctl00_cphCenter_ddlParkings'] = data[key];
                break;
            case 'washerDryer':
                newJSON['#ctl00_cphCenter_ddlWasherDryer'] = data[key];
                break;
            case 'internet':
                newJSON['#ctl00_cphCenter_ddlInternet'] = data[key];
                break;
            case 'bathrooms':
                newJSON['#ctl00_cphCenter_ddlBathrooms'] = data[key];
                break;
            case 'quoteValidType':
                newJSON['#ctl00_cphCenter_ddlQuoteValidType'] = data[key];
                break;
            case 'budgetTypeID':
                newJSON['#ctl00_cphCenter_ddlBudgetTypeID'] = data[key];
                break;
            case 'parking':
                newJSON['#ctl00_cphCenter_rblParking'] = data[key];
                break;
            case 'currency':
                newJSON['#ctl00_cphCenter_ddlCurrency'] = data[key];
                break;
            case 'numStudios':
                newJSON['#ctl00_cphCenter_ddlNumStudios'] = data[key];
                break;
            case 'num1Bedrooms':
                newJSON['#ctl00_cphCenter_ddlNum1Bedrooms'] = data[key];
                break;
            case 'num2Bedrooms':
                newJSON['#ctl00_cphCenter_ddlNum2Bedrooms'] = data[key];
                break;
            case 'num3Bedrooms':
                newJSON['#ctl00_cphCenter_ddlNum3Bedrooms'] = data[key];
                break; 
            case 'leaseTerm':
                newJSON['#ctl00_cphCenter_ddlLeaseTerm'] = data[key];
                break;   
            case 'noticeTerm':
                newJSON['#ctl00_cphCenter_ddlNoticeTerm'] = data[key];
                break;  
            case 'advancedDays':
                newJSON['#ctl00_cphCenter_ddlAdvancedDays'] = data[key];
                break;  
            case 'advancedHours':
                newJSON['#ctl00_cphCenter_ddlAdvancedHours'] = data[key];
                break;  
            case 'commissionTypeID':
                newJSON['#ctl00_cphCenter_ddlCommissionTypeID'] = data[key];
                break;  
            case 'VATID':
                newJSON['#ctl00_cphCenter_ddlVATID'] = data[key];
                break;  
            case 'cancellation':
                newJSON['#ctl00_cphCenter_ddlCancelPolicyID'] = data[key];
            case 'comment':
                newJSON['#ctl00_cphCenter_txtComments'] = data[key]
        }
    }
    return newJSON;
}

function createJSON(data) {
    data = JSON.parse(data);
    return convertJSONQuoteData(data);
}

/********************************
            Casper 
*********************************/

casper.on("remote.message", function (msg) {
    console.log(msg);
});

casper.start('http://easysource.bridgestreet.com/Default.aspx', function() {
    this.fillSelectors('form#form1', {
        'input[name="txtUN"]':    'regency',
        'input[name="txtPW"]':    'regency1',
    }, false);
});

casper.then(function(){
    this.click("#btnLogin");
    //console.log("1");
});

casper.then(function(){
    console.log("Logged In");
    //run the JSON stuff
    // return createJSON()
});

casper.then(function(){
    var url = 'http://easysource.bridgestreet.com/Quote.aspx?RequestID='+id;
    console.log('opening url')
    casper.thenOpen(url, function() {
        // var html = this.getHTML();
        // var data = regencyData;
        this.evaluate(function(data) {
            // initialize variables
            var id = '',
                val = '',
                type = '',
                key = '', // key for regencyData
                inputs = document.getElementsByTagName('input'),
                selects = document.getElementsByTagName('select'),
                textareas = document.getElementsByTagName('textarea');

            // inputs
            for (var i = 0; i < inputs.length; i++) {
                id = inputs[i].id;
                type = inputs[i].type;
                key = '#'+id;
                element = document.getElementById(id);
                // conditional to not include inputs outside of quote submission and submit inputs
                if(/ctl00/i.test(id) && (type == 'text' || type == 'date')) {
                    if (key in data) {
                        val = data[key];
                        if (val != " " || val != "") {
                            inputs[i].value = val;
                        }
                    }
                }
                if(/ctl00/i.test(id) && type == 'radio') {
                    if (key in data) {
                        val = data[key];
                        if (val != element.value) {
                            element.value = val;
                        }
                    }
                }
                if(/ctl00/i.test(id) && type == 'checkbox') {
                    if (key in data) {
                        val = data[key];
                        if (val == 'True') {
                            element.checked = true;
                        } else {
                            element.checked = false;
                        }
                    }
                }
            }
            // selects
            for(var i = 0; i < selects.length; i++) {
                id = selects[i].id;
                key = '#'+id;
                // console.log(i, id, key)
                if(key in data) {
                    val = data[key];
                    console.log(i, id, data[key], val)
                    document.getElementById(id).value = val
                }
            }
            // textareas
            for(var i = 0; i < textareas.length; i++) {
                id = textareas[i].id;
                key = '#'+id;
                if (key in data) {
                    val = data[key];
                    document.getElementById(id).value = val;
                }
            }
        }, data);
        // return casper.then(takeScreenshot)
    });
});

// clicks the submit or save button
casper.then(function() {
    // click the submit button
    // this.click(_('#ctl00_cphCenter_btnQuote'));
    // click the save button
    // this.click(_('#ctl00_cphCenter_btnSave'));
    console.log('done')
    fs.move(moveFrom, moveTo);
    console.log('moved files from', moveFrom, 'to', moveTo)
})

casper.run();














