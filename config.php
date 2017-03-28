<?php   
	
	class Player
	{
		public $id;
		public $username;
		
		public $plec;
		public $rasa;
		public $klasa;
		public $foto;
		
		public $sila;
		public $zwinnosc;
		public $celnosc;
		public $kondycja;
		public $inteligencja;
		public $wiedza;
		public $charyzma;
		public $szczescie;
		
		public $HP;
		public $MaxHP;
		
		public $Mana;
		public $MaxMana;
		
		public function updateMaxHP()
		{
			$this->MaxHP = $this->kondycja * 10;
			$this->HP = $this->MaxHP;
		}
		public function updateMaxMana()
		{
			$this->MaxMana = $this->wiedza * 10;
			$this->Mana = $this->MaxMana;
		}
	}
	
	
    function connectDB()
    {
        $dbhost = '127.0.0.1';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'mydb';
        
        $conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
        $conn->set_charset("utf8");
        if ($conn->connect_errno)
        {
            return $conn->connect_error;
            exit();
        }
        
        return $conn;
    }
	
    function get_value($my_SQLI_Connection, $SQL_code)
    {
        $result = $my_SQLI_Connection->query($SQL_code);
        $value = $result->fetch_array(MYSQLI_NUM);
		
        return is_array($value) ? $value[0] : "";
    }
	
	function get_stat($statName, $table, $ID)
    {
        $conn = connectDB();

        $escapedStatName = $conn->real_escape_string($statName);
        $escapedID = $conn->real_escape_string($ID);
        $escapedTable = $conn->real_escape_string($table);
        
        return get_value($conn, "SELECT $escapedStatName FROM $escapedTable WHERE id = $escapedID");
        $conn->close();
    }
	
    function login_check()
    {   
        session_start();
        
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true)
        {
            //user jest zalogowany
        }
        else
        {
            header('Location: login.php');
        }
    }
    
?>