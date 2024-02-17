<?php

class USession {
    /**
     * Summary of __construct
     */
    
    public function __construct(){
        session_start();
    }

    /**
     * Summary of setValue
     * @param string $Key
     * @param mixed $Value
     * @return void
     */
    //IMPOSTA IL VALORE DI UN ELEMENTO DEL VETTORE SESSION TRAMITE CHIAVE IDENTIFICATIVA
    public function setValue(string $Key, $Value) {
        $_SESSION[$Key] = $Value;
    }

    /**
     * Summary of destroyValue
     * @param string $Key
     * @return void
     */
    //ELIMINA UN ELEMENTO DEL VETTORE SESSION TRAMITE CHIAVE IDENTIFICATIVA
    public function destroyValue(string $Key) {
        unset($_SESSION[$Key]);
    }

    /**
     * Summary of readValue
     * @param string $Key
     * @return mixed
     */
    //LEGGE UN ELEMENTO DEL VETTORE SESSION TRAMITE CHIAVE IDENTIFICATIVA 
    public function readValue(string $Key) {
        if (isset($_SESSION[$Key])) {
            return $_SESSION[$Key];
        }
        else {
            return false;
        }
    }

    /**
     * Summary of valueExist
     * @param string $Key
     * @return bool
     */
    //METODO CHE CONTROLLA L'ESISTENZA DI UN ELEMENTO NEL VETTORE SESSION TRAMITE CHIAVE IDENTIFICATIVA
    //RESTITUISCE IL VALORE 0 SE L'ELEMENTO NON ESISTE NEL VETTORE
    //RESTITUISCE IL VALORE 1 SE L'ELEMENTO ESISTE NEL VETTORE
    public function valueExist(string $Key) {
        if (isset($_SESSION[$Key])) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Summary of sessionStatus
     * @return int
     */
    //RESTITUISCE IL VALORE 0 SE LE SESSIONI NON SONO ABILITATE
    //RESTITUISCE IL VALORE 1 SE LE SESSIONI SONO ABILITATE E NON CE NE SONO ATTIVE 
    //RESTITUISCE IL VALORE 2 SE LE SESSIONI SONO ABILITATE CON UNA SESSIONE GIA' ESISTENTE
    public static function sessionStatus() {
        return session_status();
    }

    /**
     * Summary of unsetSession
     * @return void
     */
    //LIBERA TUTTE LE VARIABILI DI SESSIONE IN USO, DEALLOCA RAM DEL SERVER
    public function unsetSession() {
        session_unset();
    }

    /**
     * Summary of destroySession
     * @return void
     */
    //DISTRUGGE IL CODICE IDENTIFICATIVO DELLA SESSIONE DAL FILE SYSTEM DEL SERVER
    public function destroySession() {
        session_destroy();
     }
}


?>