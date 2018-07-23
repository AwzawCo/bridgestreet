
//var l = console.log
var casper = require('casper').create(); 
var mouse = require("mouse").create(casper);
var fs = require('fs');
var utils = require('utils');
//var data = fs.read('bridgehtml.txt');
var count = 0;
var activeRequests = [];
var submittedQuotes = [];
var quoteHistory = [];
var bidID = [];

var currentTime = new Date();
var b = 0;
var a = 0;
//utils.dump(data);
var x = require("casper").selectXPath;

casper.on("remote.message", function (msg) {
    console.log(msg);
});

casper.start('http://easysource.bridgestreet.com/Default.aspx', function() {
    this.fillSelectors('form#form1', {
        'input[name="txtUN"]':    '*****',
        'input[name="txtPW"]':    '*****',
    }, false);
});

casper.then(function(){
    this.click("#btnLogin");
    //console.log("1");
});

casper.then(function(){
    var data = this.getHTML();   //for live
    console.log("Logged In");
    var numMove = data.match(/Move/gi);
    utils.dump(numMove);
    console.log(numMove.length);
    if(numMove.length == 4){     //Case that includes ActiveRequests
        this.click(x("(//a[contains(text(), 'Move')])[3]"));
        console.log("3rd Move clicked") 
    }
    else{   //Case with no ActiveRequests 
        this.click(x("//a[contains(text(), 'Move')]"));
        console.log("First Move clicked") 
}
});

//SETUP
casper.then(function(){
 var data = this.getHTML();   //for live
 var idCount = data.match(/Non-Participation/gi);
 for(i in idCount){ count += 1; }
    var myquote = data.match(/Quote\.aspx\?ID=(.*?)"/gi);
    var myid = data.match(/ViewLeadDetails(\('.*?\))/gi);
    for (i in myid) { 
     myid[i] = myid[i].slice(17, -2);
     //console.log(myid[i]); 
    }
    for (i in myquote) { 
        myquote[i] = myquote[i].slice(0, -1);
        //console.log(myquote[i]); 
    }
    submittedQuotes = myquote;
    utils.dump(submittedQuotes);
    bidID = myid.slice(count);
    utils.dump(bidID);
    activeRequests = myid.slice(0, count);
    utils.dump(activeRequests);

});

//RECURSIVELY GRABS ALL ACTIVE REQUESTS
casper.then(function repeatActive(){
    if(!activeRequests[0]){
        console.log("Finished activeRequests queue");
        return;
    }
    var url = activeRequests.pop();
    console.log("Opened " + url + " and capturing.");
    casper.thenOpen('http://easysource.bridgestreet.com/Lead_Details.aspx?id=' + url, function(){
        var myform = this.getHTML();
        var myfile = "/var/data/bridgestreet/ActiveRequests/" + url;
        fs.write(myfile, myform, 'w');
        console.log("Created file" + myfile);
        repeatActive();
    });
});

//RECURSIVELY GRABS ALL SUBMITTEDQUOTES
casper.then(function repeatSubmitted(){
 if(!submittedQuotes[0]){
     console.log("Finished submittedQuotes queue");
        return;
 }

 var url = submittedQuotes.pop();
 var bidreqid = bidID.pop();
 console.log("Opened " + url + " and capturing.");
 casper.thenOpen('http://easysource.bridgestreet.com/' + url, function(){
     var myform = this.getHTML();
     var myfile = "/var/data/bridgestreet/SubmittedQuotes/" + url + "~" + bidreqid;
        fs.write(myfile, myform, 'w');
     console.log("Created file" + myfile);
     repeatSubmitted();
 });
});


//BUG THAT REQUIRES 2 BUTTON CLICKS TO SORT THE HISTORY PROPERLY
casper.thenOpen('http://easysource.bridgestreet.com/ProviderHistoryRequests.aspx', function (){
    this.click(x("//a[contains(text(), 'Move')]"));
});

casper.thenOpen('http://easysource.bridgestreet.com/ProviderHistoryRequests.aspx', function (){
    this.click(x("//a[contains(text(), 'Move')]"));
});


//GRABS QUOTE HISTORY ID'S AND STATUS/REASON
casper.then(function(){
    var myform = this.getHTML();   //for live
    var myfile = "/var/data/bridgestreet/QuoteHistory/history";
    fs.write(myfile, myform, 'w');
    console.log("Created file" + myfile);
});


casper.run();















