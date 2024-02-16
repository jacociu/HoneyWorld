<?php

//Classe prodotto generica per eventuali upgrade dell'applicativo

class EProdotto implements JsonSerializable{

    //Attributi
    private $nome;
    private $descrizione;
    private $prezzo;
    private $idProdotto;
    private $disponibilita;//inteso come quantità nel magazzino 

    //Costruttore idProdotto è dato da DB (autoincrement)
    public function __construct($nome, $descrizione, $prezzo, $disponibilita){
        $this->nome=$nome;
        $this->descrizione=$descrizione;
        $this->prezzo=$prezzo;
        $this->disponibilita=$disponibilita;
    }

    // METODI GET
    public function getNome(){
        return $this->nome;
    }
    public function getDescrizione(){
        return $this->descrizione;
    }
    public function getPrezzo(){
        return $this->prezzo;
    }
    public function getIdProdotto(){
        return $this->idProdotto;
    }
    public function getDisponibilita(){
        return $this->disponibilita;
    }

    // METODI SET
    public function setNome($nome){
        $this->nome=$nome;
    }
    public function setDescrizione($descrizione){
        $this->descrizione=$descrizione;
    }
    public function setPrezzo($prezzo){
        $this->prezzo=$prezzo;
    }
    public function setIdProdotto($idProdotto){
        $this->idProdotto=$idProdotto;
    }
    public function setDisponibilita($disponibilita){
        $this->disponibilita=$disponibilita;
    }

    /**
     * Metodo che serializza l'oggetto in formato JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return
            [
                'nome'   => $this->getNome(),
                'descrizione'   => $this->getDescrizione(),
                'prezzo'   => $this->getPrezzo(),
				'idProdotto'   => $this->getIdProdotto(),
                'disponibilita'   => $this->getDisponibilita(),
                
            ];

    }
    public function __toString(){
        return $this->getNome()." ".$this->getDescrizione()." ".$this->getPrezzo()." ".$this->getDisponibilita()." ".$this->getIdProdotto();
    }

}