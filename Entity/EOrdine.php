<?php

//classe che modella il carrello dell'utente composto da Item
class EOrdine implements JsonSerializable{

    //Attributi
    
    private $idOrdine;
    private $idUtente;
    private $data;
    

    //Costruttore idOrdine è dato da db (autoincrement)
    public function __construct(EUtente $u, $data){
        $this->idUtente= new EUtente ($u->getNome(),$u->getCognome(),$u->getEmail(),$u->getPassword());
        $this->data=$data;
    }
    //METODI GET
    public function getIdOrdine(){
        return $this->idOrdine;
    }
    public function getIdUtente(){
        return $this->idUtente;
    }
    public function getData(){
        return $this->data;
    }
    //METODI SET
    public function setIdOrdine($idOrdine){
        $this->idOrdine=$idOrdine;
    }
    public function setIdUtente($idUtente){
        $this->idUtente=$idUtente;
    }
    public function setData($data){
        $this->data=$data;
    }
   
    public function jsonSerialize()
    {
        return
            [
                'idOrdine' => $this->getIdOrdine(),
                'idUtente' => $this->getIdUtente(),
                'data' => $this->getData(),
            ];

    }
    public function __toString(){
        return $this->getIdOrdine()." ".$this->getIdUtente()." ".$this->getData();
    }


}