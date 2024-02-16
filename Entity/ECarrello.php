<?php

//classe che modella gli elementi nel carrello composto da prodotto e quantità
class ECarrello implements JsonSerializable{

    //Attributi
    private $idCarrello;
    private $idProdotto;
    private $quantita;

    //Costruttore idCarrello è dato da DB (autoincrement)
    public function __construct(EProdotto $p, $quantita){
        $this->idProdotto=new EProdotto($p->getNome(),$p->getDescrizione(),$p->getPrezzo(),$p->getDisponibilita());
        $this->quantita=$quantita;
    }

    //METODI GET
    public function getIdCarrello(){
       return $this->idCarrello;
    }
    public function getIdProdotto(){
        return $this->idProdotto;
    }
    public function getQuantita(){
        return $this->quantita;
    }

    //METODI SET
    public function setIdCarrello($idCarrello){ 
        $this->idCarrello=$idCarrello;
    }
    public function setIdProdotto( $idProdotto){
        $this->idProdotto=$idProdotto;
    }
    public function setQuantità($Quantità){
        $this->Quantità=$Quantità;
    }

    //Metodo che serializza l'oggetto in formato JSON
    public function jsonSerialize()
    {
        return
        [
            'idCarrello' => $this->getIdCarrello(),
            'idProdotto' => $this->getIdProdotto(),
            'quantita' => $this->getQuantita(),
        ];
    }
    public function __toString(){
        return $this->getIdCarrello()." ".$this->getIdProdotto()." ".$this->getQuantita();
    }

}