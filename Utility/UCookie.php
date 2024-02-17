<?php

class UCookie{

    
    
    /**
     * Summary of set_cookie
     * @param string $Name
     * @param string $Value
     * @param int $Expires
     * @param string $Path
     * @param string $Domain
     * @param bool $Secure
     * @param bool $Httponly
     * @return void
     */
    static function set_cookie(string $Name, string $Value, int $Expires, string $Path, string $Domain, bool $Secure, bool $Httponly){
        setcookie($Name, $Value, $Expires, $Path, $Domain, $Secure, $Httponly);
    }

    /**
     * Summary of get_cookie_value
     * @param string $Name
     * @return mixed
     */
    //METODO CHE DATO IL NOME RESTITUISCE IL VALORE DEL COOKIE
    static function get_cookie_value(string $Name) {
        return $_COOKIE[$Name];
    }

    /**
     * Summary of unset_cookie
     * @param mixed $Name
     * @return void
     */
    //METODO CHE PERMETTE L'ELIMINAZIONE DI UN COOKIE TRAMITE IL SUO NOME
    static function unset_cookie($Name) {
        unset($_COOKIE[$Name]);
    }

    /**
     * Summary of isset_cookie
     * @param mixed $Name
     * @return bool
     */
    //METODO CHE PERMETTE DI VERIFICARE SE UN COOKIE E' SETTATO, 0=NON SETTATO, 1=SETTATO
    static function isset_cookie($Name): bool {
        return isset($_COOKIE[$Name]);
    }
}


?>