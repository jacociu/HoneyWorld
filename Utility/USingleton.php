<?php
final class USingleton {
    private static $instances=array();


    /**
     * Summary of getInstance
     * @param string $NomeClasse
     * @return object
     */
    //RICHIAMA UN'ISTANZA DI UNA CLASSE, SE NON ESISTE LA CREA
    public static function getInstance(string $NomeClasse) {
        /* Genera un errore sull'interfaccia utente */
        if (!class_exists($NomeClasse)) {
            trigger_error("La classe " . $NomeClasse . "non esiste", E_USER_ERROR);
        }
        /* Converte una stringa in minuscolo */
        $NomeClasse = strtolower($NomeClasse);
        if (!array_key_exists($NomeClasse, self::$instances)) {
            self::$instances[$NomeClasse] = new $NomeClasse;
        }

        return self::$instances[$NomeClasse];
    }

    /**
     * Summary of stopInstance
     * @param string $NomeClasse
     * @return object
     */
    //DISTRUGGE L'ISTANZIAZIONE DI UNA CLASSE
    public static function stopInstance(string $NomeClasse) {

        if (!class_exists($NomeClasse)) {
            trigger_error("La classe " . $NomeClasse . "non esiste", E_USER_ERROR);
        }

        $NomeClasse = strtolower($NomeClasse);
        if (!array_key_exists($NomeClasse, self::$instances)) {
            self::$instances[$NomeClasse] = null;
        }

        return self::$instances[$NomeClasse];
    }
}


?>