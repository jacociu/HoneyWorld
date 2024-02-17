<?php

class CProdotto
{   

    /**
     * Metodo per la gestione della foto caricata associata al prodotto
     * @param id del prodotto di cui gestire la foto
     * @param funz, stringa che indica se il prodotto è nuovo oppure sta subendo una modifica
     * @param nome_file, nome del file
     */
    static function upload($id_prodotto, $funz, $nome_file){
        $pm = new FPersistentManager();
        $ris = null;
        $nome = '';

        $max_size = 1000000; //1MB
        $result = is_uploaded_file($_FILES[$nome_file]['tmp_name']); //booleano che indica se il file è stato uploadato

        if (!$result) { //se l'utente non carica l'immagine, lancio l'errore
            $ris = "conclusione";
        }else{
            $size = $_FILES[$nome_file]['size'];
            $type = $_FILES[$nome_file]['type'];

            if($size>$max_size){ //se il file supera determinate dimensioni lancio un errore
                $ris = "dimensioniMax";
            }
            elseif ($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/jpg') {

                $nome = $_FILES[$nome_file]['name']; //nome del file

                $contenuto = file_get_contents($_FILES[$nome_file]['tmp_name']); //contenuto del file
                $contenuto = addslashes($contenuto); //per evitare l'interpretazione errata di caratteri come ' 
                                                     //e dunque anche problemi di injection
                if($funz == "nuovoProdotto"){

                    $fotoProdotto = new EFotoProdotto($id, $nome, $size, $type, $contenuto, $id_prodotto);                               
                    $pm->insertMedia($fotoProdotto, $nome_file);

                    $ris = "conclusione";

                }elseif($funz == "modificaProdotto"){

                    //cancello la vecchia foto (se presente) e carico la nuova.
                    $fotoPrecendente = $pm::load('FFotoProdotto', array(['idProdotto','=',$id]));
                    if($fotoPrecendente != null){
                        $pm::delete("idProdotto",$id,"FFotoProdotto");
                        }
                    $foto_prodotto = new EFotoProdotto($id, $nome, $size, $type, $contenuto, $id_prodotto);                               
                    $pm::insertMedia($foto_prodotto, $nome_file);

                    $ris = "conclusione";

                }
            }
            else{ //caso in cui il file non è un immagine del formato indicato
                $ris = "tipoErrato";
            }
        }
        return $ris;               
    }
           /**
         * Metodo che permette la visualizzazione delle informazioni specifiche di un prodotto
         * @param id da visualizzare
         */
        static function infoProdotto(int $idProdotto){

            $view = new VProdotto();

            $pm = USingleton::getInstance('FPersistentManager');
            $session = USingleton::getInstance('USession');

            //informazioni sull'utente visitatore, utile nella view per verificare se il visitatore del sito è lo stesso che lo fornisce
            $utente = unserialize($session->readValue('utente'));
            //informazioni sul prodotto
            $prodotto = $pm::load('FProdotto', array(['idProdotto', '=', $idProdotto]));

            if(isset($prodotto)){
                $foto_prodotto = $pm::load('FFotoProdotto', array(['idProdotto', '=', $idProdotto]));       
                if(!isset($foto_prodotto)) $foto_prodotto = null;
                $view->mostraInfo($prodotto, $foto_prodotto, $utente);
            
            }else header('Location: /HoneyWorld');
        }


}