<?php

/**
 * La classe CHome si occupa del caricamento dei prodotti in vendita nella homepage
 */
class CHome
{
    /**
     * Metodo utilizzato per il caricamento dei prodotti in vendita nella home che implementa
     * un sistema di refresh dei prodotti ad ogni caricamento della pagina
     * @return void
    */
    public static function blogHome(){
        $vSearch = new CHome();

        $pm = USingleton::getInstance('FPersistentManager');
        $session = USingleton::getInstance('USession');
        $prodotti_home =$pm::load('FProdotto',array(['disponibilita', '>', '0']),'',6);//limitato a 6 per dimensione del template
        

    if(CUtente::isLogged()){
        $utente = unserialize($session->readValue('utente'));
        $prodotti = $pm::load('FProdotto', array(['disponibilita', '>', '0']),'',6);
    }else{
        $prodotti = $pm::load('FProdotto',array(['disponibilita', '>', '0']),'', 6);
    }
    
    if(isset($prodotti_home)){
        if(is_array($prodotti_home)){
            for($i=0; $i< sizeof($prodotti_home); $i++){
                $prodotti_home[$i] = $prodotti[$i];
                $prodotti_foto[$i]= $pm::load('FFotoProdotto', array(['idProdotto','=',$prodotti[$i]->getIdProdotto()]));
            }
            }elseif(!is_array($prodotti_home)){
                $prodotti_foto= $pm::load('FFotoProdotto', array(['idProdotto','=',$prodotti_home->getIdprodotto()]));
            }
        }
        if(!isset($utente)) $utente = null;
        $vSearch->showHome($prodotti_home, $prodotti_foto ,$utente);
        }
        /**
         * Metodo per la visualizzazione della lista dei prodotti presenti nel database.
         */
        static function esploraProdotti(){
            $pm = USingleton::getInstance("FPersistentManager");
            
            $session = USingleton::getInstance("USession");
            if(CUtente::isLogged()){
                $utente = unserialize($session->readValue('utente'));
            }
            $view = new VHome();

            $nome = VHome::getNome(); 

            //l'utente cerca un prodotto per nome
            if($nome != "" ){
                $prodotti = $pm->loadByParola($nome, "FProdotto");

                if(isset($prodotti)){

                    if(is_array($prodotti)){
                        foreach($prodotti as $s){
                            $prodotti_foto[] = $pm::load('FFotoProdotto', array(['idProdotto', '=', $s->getId()])); 
                        }
                    }elseif(!is_array($prodotti)){
                        $prodotti_foto = $pm::load('FFotoProdotto', array(['idProdotto', '=', $prodotti->getId()]));
                    }
    
                }
                if(!isset($prodotti)) $prodotti = null;
                if(!isset($prodotti_foto)) $prodotti_foto = null;
                $view->mostraProdotti($prodotti, $prodotti_foto);
            }
            
            //l'utente non utilizza il filtro
            else{

                $prodotti = $pm::load('FProdotto', array(['disponibilità', '>', '0']));
                $prodotti_foto = null;

                //carico le foto associate ai prodotti trovati
                if(isset($prodotti)){

                    if(is_array($prodotti)){
                        foreach($prodotti as $s){
                            $prodotti_foto[] = $pm::load('FFotoProdotto', array(['idProdotto', '=', $s->getId()])); 
                        }
                    }elseif(!is_array($prodotti)){
                        $prodotti_foto = $pm::load('FFotoProdotto', array(['idProdotto', '=', $prodotti->getId()]));
                    }
    
                }

                //ricarico la view con i nuovi risultati
                $view->mostraProdotti($prodotti, $prodotti_foto);
            }

        }        
    }
            
 
    

    

