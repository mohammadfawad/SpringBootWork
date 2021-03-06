<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controller;

/**
 * Description of Mysql
 *
 * @author mmoha
 */
include './DbProperties.php';

class Mysql extends DbProperties {

    //put your code here
    public $connectionString;
    public $dataSet;
    private $sqlQuery;
    
    protected $databaseName;
    protected $hostName;
    protected $userName;
    protected $passCode;

    function Mysql() {
        $this -> connectionString = NULL;
        $this -> sqlQuery = NULL;
        $this -> dataSet = NULL;

        $dbPara = new DbProperties();
        $this -> databaseName = $dbPara -> dbName;
        $this -> hostName = $dbPara -> serverName;
        $this -> userName = $dbPara -> userName;
        $this -> passCode = $dbPara ->passCode;
        $dbPara = NULL;
    }
  
    function dbConnect()    {
        $this -> connectionString = mysql_connect($this -> serverName,$this -> userName,$this -> passCode);
        mysql_select_db($this -> databaseName,$this -> connectionString);
        return $this -> connectionString;
    }

    function dbDisconnect() {
        $this -> connectionString = NULL;
        $this -> sqlQuery = NULL;
        $this -> dataSet = NULL;
        $this -> databaseName = NULL;
        $this -> hostName = NULL;
        $this -> userName = NULL;
        $this -> passCode = NULL;
    }

    function selectAll($tableName)  {
        $this -> sqlQuery = 'SELECT * FROM '.$this -> databaseName.'.'.$tableName;
        $this -> dataSet = mysql_query($this -> sqlQuery,$this -> connectionString);
        return $this -> dataSet;
    }

    function selectWhere($tableName,$colName,$operator,$value,$valueType)   {
        $this -> sqlQuery = 'SELECT * FROM '.$tableName.' WHERE '.$colName.' '.$operator.' ';
        if($valueType == 'int') {
            $this -> sqlQuery .= $value;
        }
        else if($valueType == 'char')   {
            $this -> sqlQuery .= "'".$value."'";
        }
        $this -> dataSet = mysql_query($this -> sqlQuery,$this -> connectionString);
        $this -> sqlQuery = NULL;
        return $this -> dataSet;
        #return $this -> sqlQuery;
    }


    function insertInto($tableName,$values) {
        $i = NULL;

        $this -> sqlQuery = 'INSERT INTO '.$tableName.' VALUES (';
        $i = 0;
        while($values[$i]["val"] != NULL && $values[$i]["type"] != NULL) {
            if($values[$i]["type"] == "char") {
                $this -> sqlQuery .= "'";
                $this -> sqlQuery .= $values[$i]["val"];
                $this -> sqlQuery .= "'";
            }
            else if($values[$i]["type"] == 'int') {
                $this -> sqlQuery .= $values[$i]["val"];
            }
            $i++;
            if($values[$i]["val"] != NULL)  {
                $this -> sqlQuery .= ',';
            }
        }
        $this -> sqlQuery .= ')';
        #echo $this -> sqlQuery;
        mysql_query($this -> sqlQuery,$this ->connectionString);
        return $this -> sqlQuery;
        #$this -> sqlQuery = NULL;
    }

    function selectFreeRun($query) {
        $this -> dataSet = mysql_query($query,$this -> connectionString);
        return $this -> dataSet;
    }

    function freeRun($query) {
        return mysql_query($query,$this -> connectionString);
    }
}
