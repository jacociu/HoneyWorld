<?php
if(file_exists('Config.inc.php')) require_once 'Config.inc.php';


class FDatabase
{
    /**
     * Variabile che stabilisce ls connessione con il db
     * @var $db PDO
     */
    private $db;
    /**
     * Classe foundation
     * @var string
     */
    private static $class = "FDataBase";

    /**
     * Costruttore, accessibile con il metodo getInstance()
     */
    public function __construct()
    {
        if (!$this->existConn()) {
            try {
                $this->db = new PDO("mysql:host=127.0.0.1;dbname=" . $GLOBALS['database'], $GLOBALS['username'], $GLOBALS['password']);
            } catch (PDOException $e) {
                print 'Errore: '. $e->getMessage();
            }
        }
    }

    /**
     * Metodo che restituisce l'unica istanza dell'oggetto.
     * @return FDataBase l'istanza dell'oggetto.
     */
    public static function getInstance(){
        if (USingleton::getInstance(self::$class) == null) {
            USingleton::getInstance(self::$class);
        }
        return USingleton::getInstance(self::$class);
    }

    /**
     * Verifica che la connessione sia già stata stabilita o meno
     * @return bool
     */
    public function existConn(): bool {
        if($this->db != null){
            return true;
        } else
            return false;
    }

    /**
     * Chiude la connessione con il database
     * @return void
     */
    public function closeConn(){
        USingleton::stopInstance(self::$class);
    }

    /**
     * Questa funzione carica in $result il risultato di una query. Può produrre sia risultati singoli
     * che array di risultati (se le righe prodotte sono maggiori di una)
     * @param $class
     * @param $field
     * @param $id
     * @return array|mixed|null
     */
    public function loadDb($class, $field='', $criterio='', $id='')
    {
        try {
            if ($field == '' || $id == '' || $criterio == ''){
                $query = "SELECT * FROM " . $class::getTable() . ";";
            } else {
                $query = "SELECT * FROM " . $class::getTable() . " WHERE " . $field . $criterio . "'" . $id . "';";
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num == 0) {
                $result = null;                             //la query non ha prodotto risultati. return null
            } elseif ($num == 1) {                          //la query ha prodotto una sola riga
                $result = $stmt->fetch(PDO::FETCH_ASSOC);   //ritorna la sola riga
            } else {
                $result = array();                          //la query ha prodotto piu righe come risultati
                $stmt->setFetchMode(PDO::FETCH_ASSOC);      //result sarà un array
                while ($row = $stmt->fetch())
                    $result[] = $row;
            }
            return $result;
        } catch (PDOException $e) {
            echo "Errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * Verifica l'accesso di un utente, controllando che id e password siano presenti nel DB
     * @param $id
     * @param $pass
     * @return mixed|null
     */
    public function checkIfLogged($email, $Password){
        try {
            $class = 'FUtente';
            $query = 'SELECT * FROM ' . $class::getTable() . " WHERE Email ='" . $email . "' AND Password ='" . $Password . "';";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num == 0) {
                $result = null;
            } else {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return $result;
        } catch (PDOException $e){
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * Metodo utilizzato per salvare una nuova tupla sul DB
     * @param $object
     * @param $class
     * @return bool|mixed
     */
    public function insertDB($class, $object){

        try {
            $this->db->beginTransaction();
            $query = "INSERT INTO ".$class::getTable()." VALUES ".$class::getValues();
            $stmt = $this->db->prepare($query);
            $class::bind($stmt, $object);
            $stmt->execute();
            $id = $this->db->lastInsertId();
            $this->db->commit();
            $this->closeConn();
            return $id;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }


    /**
     * Metodo utilizzato per salvare foto dei prodotti sul db
     * @param $class
     * @param $obj
     * @param $nome_file
     * @return false|string|null
     */
    public function storeMedia($class , $obj, $nome_file) {
        try {
            $this->db->beginTransaction();
            $query = "INSERT INTO ".$class::getTable()." VALUES ".$class::getValues();
            $stmt = $this->db->prepare($query);
            $class::bind($stmt, $obj, $nome_file);
            $stmt->execute();
            $id = $this->db->lastInsertId();
            $this->db->commit();
            $this->closeConn();
            return $id;
        } catch (PDOException $e) {
            echo $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }



    /**
     * Metodo utilizzato per aggiornare dei valori sul DB
     * @param $class
     * @param $field
     * @param $newvalue
     * @param $pk
     * @param $id
     * @return bool
     */
    public function updateDB ($class, $field, $newvalue, $pk, $id)
    {
        try {
            $this->db->beginTransaction();
            $query = "UPDATE " . $class::getTable() . ' SET ' . $field . '="' . addslashes($newvalue) . '" WHERE ' . $pk . '="' . $id . '";';
            //var_dump($query);
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $this->db->commit();
            $this->closeConn();
            return true;
        } catch (PDOException $e) {
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Questa funzione serve a rimuovere i dati di una determinata istanza di un oggetto dal DB
     * @param $object
     * @return bool
     */
    public function deleteDB ($class, $field, $id)
    {
        try {
            $result = null;
            $this->db->beginTransaction();
            $exist = $this->existDB($class, $field, $id);
            if ($exist) {
                $query = "DELETE FROM " . $class::getTable() . " WHERE " . $field . "='" . $id . "';";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $this->db->commit();
                $this->closeConn();
                $result = true;
            }

        } catch (PDOException $e) {
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
        }
        return $result;
    }

    /**
     * Metodo utilizzato per verificare l'esistenza di una tupla sul DB
     * @param $class
     * @param $field
     * @param $id
     * @return array|false|mixed|void|null
     */
    public function existDB ($class, $field, $id)
    {
        try {
            $query = "SELECT * FROM " . $class::getTable() . " WHERE " . $field . "='" . $id . "'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) == 1) return $result[0];  //rimane solo l'array interno
            else if (count($result) > 1) return $result;  //resituisce array di array
            $this->closeConn();

        } catch (PDOException $e) {
            echo "Attenzione errore: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Cerca nel DB le righe che soddisfano le condizioni date da $parametri
     * ed eventualmente le ordina o ne limita il numero di risultati
     * @param array $parametri
     * @param string $ordinamento
     * @param string $limite
     * @return array|false
     */
    public function searchDb($class, $parametri = array(), $ordinamento = '', $limite = ''){
        $filtro = '';
        try {
            for ($i = 0; $i < sizeof($parametri); $i++) {
                if ($i > 0) $filtro .= ' AND';
                $filtro .= ' `' . $parametri[$i][0] . '` ' . $parametri[$i][1] . ' \'' . $parametri[$i][2] . '\'';
            }
            $query = 'SELECT * ' .
                'FROM `' . $class::getTable() . '` ';

            if ($filtro != '')
                $query .= 'WHERE' . $filtro . ' ';
            if ($ordinamento != '')
                $query .= 'ORDER BY ' . $ordinamento . ' ' . 'DESC ';
            if ($limite != '')
                $query .= 'LIMIT ' . $limite . ' ';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $numRow = $stmt->rowCount();
            if ($numRow == 0){
                $result = null;
            } elseif ($numRow == 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

            } else {
                $result = array();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) $result[] = $row;
            }
            return $result;
        } catch (PDOException $e){
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * Questo metodo restituisce il numero di righe prodotte dalla query
     * Necessario per vedere se bisogna costruire uno o più oggetti al momento della load
     * @param $class
     * @param $field
     * @param $id
     * @return int|null
     */
    public function getRowNum($class, $parametri = array(), $ordinamento = '', $limite = ''){
        $filtro = '';
        try {
            for ($i = 0; $i < sizeof($parametri); $i++) {
                if ($i > 0) $filtro .= ' AND';
                $filtro .= ' `' . $parametri[$i][0] . '` ' . $parametri[$i][1] . ' \'' . $parametri[$i][2] . '\'';
            }
            $query = 'SELECT * ' .
                'FROM `' . $class::getTable() . '` ';
            if ($filtro != '')
                $query .= 'WHERE ' . $filtro . ' ';
            if ($ordinamento != '')
                $query .= 'ORDER BY ' . $ordinamento . ' ' ;
            if ($limite != '')
                $query .= 'LIMIT ' . $limite . ' ';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            $this->closeConn();
            return $num;

        } catch (PDOException $e) {
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    } 
    /**
    * Funzione utilizzata per ritornare tutti gli utenti presenti sul DB,
    * utilizzata nella pagina admin
    * @param $query query da eseguire
    * @return array|null
    */
    public function getAllUtenti ()
	{
		try {
			$query = "SELECT * FROM utenti;";
			$stmt = $this->db->prepare($query);
			$stmt->execute();
			$num = $stmt->rowCount();
			if ($num == 0) {
				$result = null;
			} elseif ($num == 1) {
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
			} else {
				$result = array();
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				while ($row = $stmt->fetch())
					$result[] = $row;
			}
			return array($result, $num);
		} catch (PDOException $e) {
			echo "Attenzione errore: " . $e->getMessage();
			$this->db->rollBack();
			return null;
		}
	}
    /**
     * Funzione utilizzata per ritornare tutti gli ordini presenti sul DB,
     * utilizzata nella pagina admin
     * @param $query query da eseguire
     * @return array|null
     */
    public function getAllOrd ()
    {
        try {
            $query = "SELECT * FROM ordine;";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num == 0) {
                $result = null;
            } elseif ($num == 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $result = array();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch())
                    $result[] = $row;
            }
            return array($result, $num);
        } catch (PDOException $e) {
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * Metodo che carica tutti i valori di un determinato attributo di una tabella dal DB
     * @param $class
     * @param $columns
     * @param $order
     * @param $limite
     * @return array|mixed|null
     */
    public function loadDefColDB($class, $columns, $order = '', $limite = '') {
        $cols  = '';
        try {
            for ($i = 0; $i < count($columns); $i++) {
                if ($i > 0) $cols .= ', ';
                $cols .= $columns[$i];
            }
            $query = 'SELECT ' . $cols . ' FROM ' . $class::getTable();
            if ($order != '') {
                $query .= ' ORDER BY ' . $order . 'DESC';
            }

            if ($limite != '') {
                $query .= ' LIMIT ' . $limite;
            }
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $numRow = $stmt->rowCount();
            print($query);
            if ($numRow == 0) {
                $result = null;
            } elseif ($numRow == 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $result = array();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) $result[] = $row;
            }
            return $result;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
	/**
	 * Funzione utilizzata per ritornare i soli prodotti che contengono una parola inserita .
	 * @param  $input stringa inserita nel campo ricerca 
	 * @param input ,parola data in input
	 * @param class, classe interessata
	 * @param campo, campo dove cercare la parola
	 */
	public function ricercaProdotto ($input, $class, $campo)
	{
		try {
			// $this->db->beginTransaction();
            //$class::getTable()
			$query = "SELECT *  FROM `". $class::getTable() ."` WHERE `". $campo ."` LIKE '%" . $input . "%' AND ControlloAcquisto = 0;";
			$stmt = $this->db->prepare($query);
            $stmt->execute();
            $numRow = $stmt->rowCount();
            if ($numRow == 0){
                $result = null;
            } elseif ($numRow == 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

            } else {
                $result = array();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) $result[] = $row;
            }
            return $result;
        } catch (PDOException $e){
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }
    /**   Metodo che restituisce il numero di righe ineteressate dalla query
  * @param class classe interessata
  *@param field campo usato per la ricerca
  *@param id ,id usato per la ricerca
  */
	public function prodRows ($input, $class, $campo)
	{
		try {
			//$this->db->beginTransaction();
			$query = "SELECT *  FROM `". $class::getTable() ."` WHERE `". $campo ."` LIKE '%" . $input . "%' AND ControlloAcquisto = 0;";
			$stmt = $this->db->prepare($query);
			$stmt->execute();
			$num = $stmt->rowCount();
			//$this->closeDbConnection();
			return $num;
		} catch (PDOException $e) {
			echo "Attenzione errore: " . $e->getMessage();
			$this->db->rollBack();
			return null;
		}
	}
	/**
	 * Funzione utilizzata per ritornare i soli  utenti che contengono una parola inserita .
	 * @param  $input stringa inserita nel campo ricerca 
	 * @param input ,parola data in input
	 * @param class, classe interessata
	 * @param campo, campo dove cercare la parola
	 */
	public function ricercaUtente ($input, $class, $campo)
	{
		try {
			// $this->db->beginTransaction();
            //$class::getTable()
			$query = "SELECT *  FROM `". $class::getTable() ."` WHERE `". $campo ."` LIKE '%" . $input . "%' OR Cognome LIKE '%" . $input . "%' AND Admin = 0;";
			$stmt = $this->db->prepare($query);
            $stmt->execute();
            $numRow = $stmt->rowCount();
            if ($numRow == 0){
                $result = null;
            } elseif ($numRow == 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

            } else {
                $result = array();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) $result[] = $row;
            }
            return $result;
        } catch (PDOException $e){
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }
    public function ricercaOrdine($input, $class, $campo){
        try{
            $query="SELECT * FROM `". $class::getTable() . "` WHERE `". $campo . "` LIKE '%". $input . "%' AND Pagato = 0;";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $numRow = $stmt->rowCount();
            if ($numRow == 0){
                $result = null;
            } elseif ($numRow == 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            } else {
                $result = array();
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                while ($row = $stmt->fetch()) $result[] = $row;
            }
            return $result;
        } catch (PDOException $e){
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }
    }



    
    /**   Metodo che restituisce il numero di righe ineteressate dalla query
  * @param class classe interessata
  *@param field campo usato per la ricerca
  *@param id ,id usato per la ricerca
  */
	public function utenteRows ($input, $class, $campo)
	{
		try {
			//$this->db->beginTransaction();
			$query = "SELECT *  FROM `". $class::getTable() ."` WHERE `". $campo ."` LIKE '%" . $input . "%' OR Cognome LIKE '%" . $input . "%' AND Admin = 0;";
			$stmt = $this->db->prepare($query);
			$stmt->execute();
			$num = $stmt->rowCount();
			//$this->closeDbConnection();
			return $num;
		} catch (PDOException $e) {
			echo "Attenzione errore: " . $e->getMessage();
			$this->db->rollBack();
			return null;
		}
	}
    public function ordineRows($input, $class, $campo){
        try{
            $query="SELECT * FROM `". $class::getTable() . "` WHERE `". $campo . "` LIKE '%". $input . "%' AND Pagato = 0;";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            return $num;
        } catch (PDOException $e){
            echo "Attenzione errore: " . $e->getMessage();
            $this->db->rollBack();
            return null;
        }

        }
    }              