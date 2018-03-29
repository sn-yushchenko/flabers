<?php
include_once("autoload.php");
$nova = new NovaPoshta();
//$date = date("d.m.Y");
$method = $_POST['method'];
switch($method){
        case "getCity":$nova->$method($_POST["value"]);break;
        case "getDep":$nova->$method($_POST["refCity"]);break;
        case "getCost":$arr=array("CitySender"=>$_POST['CitySender'],"CityRecipient"=>$_POST['CityRecipient'],"Cost"=>100,"Weight"=>2,"ServiceType"=>"DoorsDoors","CargoType"=>"Cargo","SeatsAmount"=> "1");$nova->getCost($arr);break;
        case "getDate":$arr=array("CitySender"=>$_POST['CitySender'],"CityRecipient"=>$_POST['CityRecipient'],"DateTime"=>"28.03.2018","ServiceType"=>"DoorsDoors");$nova->getDate($arr);break;
}
?>