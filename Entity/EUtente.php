<?php

// classe utente (cliente)
class EUtente implements JsonSerializable {
    
    // attributi
    private $idUtente;
    private $nome;
    private $cognome;
    private $email;
    private $password;
    
    // costruttore idUtne è dato da db (autoincrement)
    public function __construct( $nome, $cognome, $email, $password) {
        $this ->nome = $nome;
        $this ->cognome = $cognome;
        $this ->email = $email;
        $this ->password = $password;
    }

    //METODI GET
    public function getIdUtente(){
        return $this->idUtente;
    }
    public function getNome(){
        return $this->nome;
    }
    public function getCognome(){
        return $this->cognome;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPassword(){
        return $this->password;
    }
    //METODI SET
    public function setIdUtente($idUtente){
        return $this->idUtente=$idUtente;
    }
    public function setNome($nome){
        return $this->nome=$nome;
    }
    public function setCognome($cognome){
        return $this->cognome=$cognome;
    }
    public function setEmail($email){
        return $this->email=$email;
    }
    public function setPassword($password){
        return $this->password=$password;
    }
    //METODO CHE SERIALIZZA JSON
    public function jsonSerialize()
    {
        return
            [
                'nome'   => $this->getNome(),
                'cognome' => $this->getCognome(),
                'idUtente'   => $this->getIdUtente(),
                'password'   => $this->getPassword(),
                'email'   => $this->getEmail(),
            ];

    }
    public function __toString(){
        return $this->getNome()." ".$this->getCognome()." ".$this->getIdUtente()." ".$this->getPassword()." ".$this->getEmail();
    }




}