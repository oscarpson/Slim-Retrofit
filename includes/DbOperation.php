<?php
class DbOperation
{
    private $conn;
    function __construct()
    {
        require_once dirname(__FILE__).'/DbConnect.php';
       $db=new DbConnect();
       $this->conn=$db->connect();
    }

    function RecordReading($creading, $accNo, $mtrReader, $mtrStatus,$mtrDate,$readerLocation)
    {
        //check if previous meter reading is less than 3 weeks and terminate entry
        $stmt=$this->conn->prepare("INSERT INTO mReading (creading, accNo, reader, status, rdate, location) VALUES (?, ?, ?, ?,?, ?)" );
       $stmt->bind_params('ssssss',$creading, $accNo, $mtrReader, $mtrStatus,$mtrDate, $readerLocation);
       if($stmt->execute())
       {
           return ENTRY_SUCCESSFUL;
       }
       return READING_ENTRY_FAILED;

    }
    function getAllReadings()
    {
        $stmt = $this->conn->prepare("SELECT * FROM readings");
        $stmt->execute();
        //AccNumber, CurrentReading, MtrReader, MtrStatus,Rdate,Location,ActiveStatus
        $stmt->bind_result($EntryId, $AccNumber,$CurrentReading, $MtrReader, $MtrStatus,$Rdate,$Location,$ActiveStatus);   
        $readings = array();
        while($stmt->fetch()){
            {    
                $temp["EntryId"]=$EntryId;
                $temp["AccNumber"]=$AccNumber;
                $temp["CurrentReading"]=$CurrentReading;
                $temp["MtrReader"]=$MtrReader;
                $temp["MtrStatus"]=$MtrStatus;
                $temp["Rdate"]=$Rdate;
                $temp["Location"]=$Location;
                $temp["ActiveStatus"]=$ActiveStatus;
               
            array_push($readings, $temp);
            }
        return $readings;
        }
    }
}