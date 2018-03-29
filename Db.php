<?php 
class Db
{
    private $host;
    private $db;
    private $charset;
    private $user;
    private $pass;
    private $pdo;
    public function __construct()
    {
        $config=include("config/db.php");
        $this->host = $config["host"];
        $this->db = $config["dbName"];
        $this->charset = $config["charset"];
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            ];
        $this->pdo = new PDO($dsn, $config["user"], $config["pass"], $opt);
        return $this->pdo;
    }
    
    public function addCities($name,$ref){
        $stmt = $this->pdo->prepare('INSERT INTO cities VALUE(NULL,:name,:ref)');
        $stmt->execute(array('name' => $name,'ref'=>$ref));
    }
    public function addBranch($name,$ref,$cityRef){
        $stmt = $this->pdo->prepare('INSERT INTO branch VALUE(NULL,:ref,:name,:cityRef)');
        $stmt->execute(array('name' => $name,'ref'=>$ref,'cityRef'=>$cityRef));
    }
    public function searchCity($name)
    {
        $arr = array();
        $name = "$name%";
        $stm  = $this->pdo->prepare("SELECT * FROM cities WHERE name LIKE ?");
        $stm->execute(array($name));
        $data = $stm->fetchAll();
        foreach($data as $value){
            $arr[$value["ref"]]=$value["name"];
        }
        return $arr;
    }
    public function searchDep($refCity)
    {
        $arr = array();
        $stm  = $this->pdo->prepare("SELECT * FROM branch WHERE cityRef=:refCity");
        $stm->execute(["refCity"=>$refCity]);
        $data = $stm->fetchAll();
        foreach($data as $value){
            $arr[$value["ref"]]=$value["name"];
        }
        return $arr;
    }

}
?>