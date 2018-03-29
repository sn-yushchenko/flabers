<?php
class NovaPoshta{
    private $apiKey;
    public function __construct()
    {
        $config = include('config/np.php');
        $this->apiKey = $config["apiKey"];
    }
    public function сurlQuery($model,$method,$properties)
    {
        
       $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.novaposhta.ua/v2.0/json/",
        CURLOPT_RETURNTRANSFER => True,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\r\n\"".$this->apiKey."\": \"\",\r\n\"modelName\":\"".$model."\",\r\n\"calledMethod\": \"".$method."\",\r\n\"methodProperties\": {".$properties."}\r\n}",
        CURLOPT_HTTPHEADER => array("content-type: application/json",),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        } 
    }
    public function getPropertiesString($properties){
        $stringProperties="";
        foreach($properties as $key=>$value){
            if(!next($properties)){
                $stringProperties.="\"".$key."\"".":"."\"".$value."\"";
            }
            else{
                $stringProperties.="\"".$key."\"".":"."\"".$value."\"".",";
            }
            
        }
        return $stringProperties;
    }
    
    public function getCities($properties = array())
    {
        $address = "Address";
        $method = "getCities";
        $propertiesString = $this->getPropertiesString($properties);
        $obj = $this->сurlQuery($address,$method,$propertiesString);
        $d = new Db();
        foreach($obj->data as $city){
            $d->addCities($city->Description,$city->Ref);
        }
        
    }
    public function getBranch($properties = array())
    {
        $address = "Address";
        $method = "getWarehouses";
        $propertiesString = $this->getPropertiesString($properties);
        $obj = $this->сurlQuery($address,$method,$propertiesString);
        $d = new Db();
        foreach($obj->data as $branch){
            $d->addBranch($branch->Ref,$branch->Description,$branch->CityRef);
        }
        
    }
    public function getCost($properties = array())
    {
        $address = "InternetDocument";
        $method = "getDocumentPrice";
        $propertiesString = $this->getPropertiesString($properties);
        $obj = $this->сurlQuery($address,$method,$propertiesString);
        echo $obj->data[0]->Cost;
    }
    public function getDate($properties = array())
    {
        $address = "InternetDocument";
        $method = "getDocumentDeliveryDate";
        $propertiesString = $this->getPropertiesString($properties);
        $obj = $this->сurlQuery($address,$method,$propertiesString);
        echo $obj->data[0]->DeliveryDate->date;
    }
    public function getCity($name){
        $d = new Db();
        $arr=$d->searchCity($name);
        echo json_encode($arr);
    }
    public function getDep($refCity){
        $d = new Db();
        $arr=$d->searchDep($refCity);
        echo json_encode($arr);
    }
}
?>