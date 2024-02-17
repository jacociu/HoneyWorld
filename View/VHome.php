<?php

class VRicerca
{

    private $smarty;

    public function __construct()
    {
        $this->smarty = StartSmarty::configuration();
    }

    static function getNome(){
        $value = "";
        if (!empty($_POST['nome']))
            $value = $_POST['nome'];
        return $value;
    }
    public function showHome($prodotti_home, $prodotti_foto,$utente)
    {
        if(CUtente::isLogged())  $this->smarty->assign('userLogged', 'loggato');
        else $this->smarty->assign('userLogged', 'nouser');

        $this->smarty->assign('prodotti_home', $prodotti_home);
        $this->smarty->assign('prodotti_foto', $prodotti_foto);
        $this->smarty->assign('utente', $utente);

        $this->smarty->display('./smarty/libs/templates/index.tpl');
    }
        /**
         * Metodo dedicato a mostrare i prodotti in esploraProdotti
         */
        public function mostraProdotti($prodotti, $prodotti_foto){

            if (CUtente::isLogged())  $this->smarty->assign('userlogged', 'loggato');
            else $this->smarty->assign('userlogged', 'nouser');

            $this->smarty->assign('prodotti', $prodotti);
            $this->smarty->assign('prodotti_foto', $prodotti_foto);

            $this->smarty->display('./smarty/libs/templates/prodotti.tpl');
            
        }
}