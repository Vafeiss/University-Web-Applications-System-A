<?php
/* Name: SelectionClass.php 
   Description: This is a class that is used to select data from the database.
   Paraskevas Vafeiadis
   20-Mar-2026 v0.1
   Inputs: Depends on the functions but mostly actions from the dashbaords
   Outputs: Depends on the functions but mostly arrays
   Files in use: routes.php,admin_dashboard.php
   */


require_once __DIR__ . '/databaseconnect.php';

class SelectionClass{
  private $conn;

function __construct() {
    $this->conn = ConnectToDatabase();
}

function getDegrees(){
    $sql = "SELECT DegreeID, DegreeName , Department_Name FROM degree ORDER BY DegreeName ASC";
    $stmt = $this->conn->query($sql);
    $degrees = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

    if(empty($degrees)){
        return [];
    }

    return $degrees;

}

}


