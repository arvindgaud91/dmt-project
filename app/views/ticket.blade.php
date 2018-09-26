<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<style type="text/css">a:hover{color: #34495e;text-decoration: underline;}</style>
<div ng-controller="listTicketCtrl"  class="head-weight">
  <div class="row">
    <center><h1 style="color: #333;font-size: 3.25em;text-align: center;line-height: 110%;font-weight: bold; ">Please Wait For Redirect ..</h1>
    <a style="font-size: 24px;color: #337ab7;" href="http://support.digitalindiapayments.com/ticket/view/filter" id="myCheck" target="_blank">Please Click Here If Not Redirected..!</a></center>
  </div> 
</div>
@stop
@section('javascript')
<script>
angular.module('DIPApp')

  .controller('listTicketCtrl', ['$scope', '$http','Upload','fileUpload', function ($scope,$http,Upload,fileUpload) {
  window.s = $scope
  


   $scope.submit=submit;
   $scope.onButtonClick=onButtonClick;
   $scope.myFunction=myFunction;
     submit();
      function submit () 
      {
        
         $http.get('ticketlogin')
          .then(function (data) {
            console.log(data.data.at_token); 
            var at_token = data.data.at_token;
            var at_appCode = data.data.at_appCode;


 var storage = new CrossStorageClient('http://support.digitalindiapayments.com/cors/hub.html');
 storage.onConnect().then(function(){
  console.log("connected to the support.digitalindiapayments localStorage")
  return storage.set('at_token',at_token)
 },function (err){
  console.log("error in conecting support.digitalindiapayments localStorage")
  console.log(err)
 }).then(function(){
  return storage.set('at_appCode',at_appCode)
 }).then(function(){
  return storage.set('at_role','agent')
 }) 


 var storage1 = new CrossStorageClient('http://dmt-test.digitalindiapayments.com:8000/hub');
 storage1.onConnect().then(function(){
  console.log("connected to the aeps.digitalindiapayments localStorage")
  return storage1.set('at_token',at_token)

 },function (err){
  console.log("error in conecting aeps.digitalindiapayments localStorage")
  console.log(err)
 }).then(function(){
  return storage1.set('at_appCode',at_appCode);
 
 }).then(function(){
  return storage1.set('at_role','agent')
 }) 
//window.location = "http://support.digitalindiapayments.com/ticket/view/filter";
 // window.open('http://support.digitalindiapayments.com/ticket/view/filter', '_blank'); 
 myFunction();     
          }, fail)
  


      }

    function onButtonClick(){
      
window.open('http://support.digitalindiapayments.com/ticket/view/filter');
}



function myFunction() {
    console.log('c');
    document.getElementById("myCheck").click();
}


    //console.log($scope.transaction);
    function fail (err) {
    //console.log(err)
    sweetAlert('Error', 'Something went wrong', 'error')
  }
        
}])
</script>
@stop