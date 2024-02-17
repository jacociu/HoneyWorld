<?php

class FUtente extends FDatabase{
    

    //tabella con cui opera (tabella nel db)
    private static $table = "utente";

    private static $class = "FUtente";

    //valori attributi della tabella utenti nel db
    //Modificabile in base agli attributi della classe
    private static $values = '(:idUtente, :Nome, :Cognome, :Email, :Password)';

    public static function bind($stmt, EUtente $Utente)
    {
        $stmt->bindValue(':idUtente',$Utente->getIdUtente(), PDO::PARAM_INT);
        $stmt->bindValue(':Nome', $Utente->getNome(), PDO::PARAM_STR);
        $stmt->bindValue(':Cognome', $Utente->getCognome(), PDO::PARAM_STR);
        $stmt->bindValue(':Email', $Utente->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':Password', $Utente->getPassword(), PDO::PARAM_STR);
    }

     /**
     * Questo metodo restituisce il nome della tabella per la costruzione della query
     * @return string $table nome della tabella
     */
    public static function getTable(): string {
        return self::$table;
    }
    /**
     * Questo metodo restituisce il nome della classe per la costruzione della query
     * @return string $class nome della classe
     */
    public static function getClass(): string {
        return self::$class;
    }

    /**
     * Questo metodo restituisce l'insieme dei valori per la costruzione della Query
     */
    public static function getValues() :string {
        return self::$values;
    }
    //store utente nel db
    public static function insert($object){
        $db = parent::getInstance();
        $id = $db->insertDB(self::$class, $object);
        $object->setId($id);
    }

    //metodo per l'aggiornamento di un campo di utente
    //$val sarebbe id ovvero la chiave identificativaù
    //$newValue nuovo valore da inserire nel db
    //$field campo della tabella dove si vuole aggiornare 
    public static function update($field, $newValue, $pk, $val){
        $db = parent::getInstance();
        $result = $db->updateDB(self::getClass(),$field,$newValue,$pk,$val);
        if($result) return true;
        else return false;
    }
    //elimina utente in base all'id
    public static function delete($field,$id){
        $db = parent::getInstance();
        $result = $db->deleteDB(self::getClass(),$field,$id);
        if($result != null) return true;
        else return false;
    }
    //verifica l'esistenza dell'id
    public static function exist($field,$id){
        $db = parent::getInstance();
        $result = $db->existDB(self::getClass(),$field,$id);
        if ($result != null) return true;
        else return false;
    }
    //cerca un oggetto nel db
    public static function search($parametri=array(),$ordinamento='',$limite=''){
        $db = parent::getInstance();
        $result = $db->searchDB(self::$class,$parametri,$ordinamento,$limite);
        return $result;
    }
    //carica un utente, deve essere prima loggato
    public static function loadLogin($user,$pass){
        $utente = null;
        $db = FDatabase::getInstance();
        $result = $db->checkIfLogged($user,$pass);
        if(isset($result)){
            $utente = self::loadByField(array(['Email', '=', $result['Email']]));
        }
        return $utente;
    }

    public static function getRows($parametri = array(), $ordinamento = '', $limite = ''){
        $db = parent::getInstance();
        $result = $db->getRowNum(self::$class, $parametri, $ordinamento, $limite);
        return $result;
    }

    //metodo che permette la load di uno o più utenti dal DB

    public static function loadByField($parametri = array(), $ordinamento = '', $limite = ''){
        $utente = null;
        $db = parent::getInstance();
        $result = $db->searchDB(static::getClass(),$parametri, $ordinamento,$limite);

        if(count($parametri)>0){
            $rows_number = $db->getRowNum(static::getClass(), $parametri,$ordinamento,$limite);
        } else{
            $rows_number = $db->getRowNum(static::getClass());
        }
        if(($result != null) && ($rows_number == 1)){
            $utente = new EUtente($result['Nome'],$result['Cognome'],
             $result['Password'],$result['Email']);
         }
        else{
            if(($result != null) && ($rows_number > 1)){
                $utente = array();
                for($i = 0; $i < count($result) ;$i++){
                    $utente[] = new EUtente($result[$i]['Nome'],$result[$i]['Cognome'],
                    $result[$i]['Password'],$result[$i]['Email']);

                }
            }
        }
        return $utente;
    }
    /** Metodo che recupera dal db tutte le istanze che contengono il parametro passato in input
     */
    public static function loadByParola($parola){
        $utente = null;
        $db = parent::getInstance();
        $result=$db->ricercaUtente($parola, static::getClass(), "Nome");
        $rows_number = $db->utenteRows($parola, static::getClass(), "Nome");
        if(($result!=null) && ($rows_number== 1)) {
            $utente = new EUtente($result['Nome'],$result['Cognome'],  $result['Password'],
            $result['Email']);
         }
        else {
            if(($result!=null) && ($rows_number > 1)){
                $utente = array();
                for($i=0; $i<count($result); $i++){
                    $utente[] = new EUtente($result[$i]['Nome'],$result[$i]['Cognome'],  
                     $result[$i]['Password'],$result[$i]['Email']);

        }    
    }   
}
return $utente;
}
public static function loadAll() {
    $rec = null;
    $db = FDatabase::getInstance();
    list ($result, $rows_number)=$db->getAllUtenti();
    if(($result != null) && ($rows_number == 1)) {
        $rec = new EUtente($result['Nome'], $result['Cognome'], $result['Password'], $result['Email']);
        $rec->setIdUtente($result['idUtente']);
    }
    else {
        if(($result != null) && ($rows_number > 1)){
            $rec = array();
            for($i = 0; $i < count($result); $i++){
                $rec[] = new EUtente($result [$i]['Nome'], $result [$i]['Cognome'], $result [$i]['Password'], $result [$i]['Email']);
                $rec[$i]->setIdUtente($result[$i]['idUtente']);
            }
        }
    }
    return $rec;
}
}

