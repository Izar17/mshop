<?php 

class Education {
    private $id;
    private $name;
    private $address;
    private $type;
    private $lat;
    private $lng;
    private $fully;
    private $population;
    private $conn;
    private $marker_color;
    private $tableName = "vendor_list";

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }
    function setName($name) { $this->name = $name; }
    function getName() { return $this->name; }
    function setFully($fully) { $this->fully = $fully; }
    function getFully() { return $this->fully; }
    function setPopulation($population) { $this->population = $population; }
    function getPopulation() { return $this->population; }
    function setMarker_color($marker_color) { $this->marker_color = $marker_color; }
    function getMarker_color() { return $this->marker_color; }
    function setAddress($address) { $this->address = $address; }
    function getAddress() { return $this->address; }
    function setType($type) { $this->type = $type; }
    function getType() { return $this->type; }
    function setLat($lat) { $this->lat = $lat; }
    function getLat() { return $this->lat; }
    function setLng($lng) { $this->lng = $lng; }
    function getLng() { return $this->lng; }

    public function __construct() {
        require_once('dbConnect.php');
        $conn = new DbConnect;
        $this->conn = $conn->connect();
    }

    public function getCollegesBlankLatLng() {
        $sql = "SELECT * FROM $this->tableName WHERE lat IS NULL AND lng IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllColleges() {
        $sql = "SELECT * FROM $this->tableName";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCollegesWithLatLng() {
        $sql = "UPDATE $this->tableName SET lat = :lat, lng = :lng WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':lat', $this->lat);
        $stmt->bindParam(':lng', $this->lng);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
