/*var msg = '{{Session::get('alert')}}';
var exist = '{{Session::has('alert')}}';
if (exist) {
var r = confirm(msg);
if (r == true) {
alert("You are right");
} else {
alert("You are wrong");
}
}*/
function checkInValidation(){
  var today = new Date();
  var hour = today.getHours();
  if (hour >= 12){ // show alert message and check if user confirm
    var r = confirm("Are you sure to check in now!");
    if (r == true) {
    return true; // confirm -> store user attendance
    } else {
    return false; // reject
    }
  }
  else {
    return true; // right time -> store user attendance
  }
}

function checkOutValidation(){
  var today = new Date();
  var hour = today.getHours();
  if (hour < 12){
    var r = confirm("Are you sure to check out now!");
    if (r == true) {
    return true;
    } else {
    return false;
    }
  }
  else {
    return true;
  }
}
