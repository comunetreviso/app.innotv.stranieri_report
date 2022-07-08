<?php

class straniero {
    private $conn;
    private $cod;
    private $anno;
    
    function __construct($conn, $cod = null, $anno = null) {
        $this->conn = $conn;
        $this->cod = $cod;
        $this->anno = $anno;
    }
    
    function get_nazioni() {
        $results = array();       
        $stmt = $this->conn->query("SELECT cod, nazione FROM stranieri GROUP BY cod, nazione ORDER BY nazione");

        while ($row = $stmt->fetch()) {
            $results[] = array(
                "cod" => $row["cod"],
                "nazione" => $row["nazione"]
            );
        }

        return $results;
    }
    
    function get_anni_riferimento() {
        $results = array();       
        $stmt = $this->conn->query("SELECT DISTINCT anno FROM stranieri ORDER BY anno DESC");

        while ($row = $stmt->fetch()) {
            array_push($results, $row["anno"]);
        }

        return $results;
    }
    
    function report_anno() {
        $results = array();
        
        if (!$stmt = $this->conn->prepare("SELECT nazione, anno, numero FROM stranieri WHERE anno = ? ORDER BY numero DESC")) {
            throw new Exception("Errore preparazione statement.");
        }

        if (!$stmt->execute(array($this->anno))) {
            throw new Exception("Errore esecuzione statement.");
        }

        while ($row = $stmt->fetch()) {
            $results[] = array(
                "nazione" => $row["nazione"],
                "anno" => $row["anno"],
                "numero" => number_format($row["numero"], 0, ",", ".")
            );
        }

        return $results;					
    }
    
    function report_nazione() {
        $results = array();
        
        if (!$stmt = $this->conn->prepare("SELECT nazione, anno, numero FROM stranieri WHERE cod = ? ORDER BY anno")) {
            throw new Exception("Errore preparazione statement.");
        }

        if (!$stmt->execute(array($this->cod))) {
            throw new Exception("Errore esecuzione statement.");
        }

        while ($row = $stmt->fetch()) {
            $results[] = array(
                "nazione" => $row["nazione"],
                "anno" => $row["anno"],
                "numero" => number_format($row["numero"], 0, ",", ".")
            );
        }

        return $results;					
    }
}