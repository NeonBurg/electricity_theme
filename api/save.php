<?php
date_default_timezone_set('Asia/Yekaterinburg');

class Counter
{
	public $c;
	public $KC;
	public $type;
	public $base;
	public $dec;
	public $dt;
	public $level;
}

class Database
{
	private $mysqli;
	private $db_host = 'localhost';             	//  хост
	private $db_user = 'askueene_neon_burg';        //  имя пользователя
	private $db_pass = 'F!e3gpSDtSRj';              //  пароль
	private $db_name = 'askueene_electricity_wp';   //  база данных
	private $mass = array();

	function __construct() 
	{
		//echo 'Constructor ';
       		$this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
	}

	function __destruct() 
	{
		//echo 'Destructor ';
		$this->mysqli->close();
	}

	public function answer($data)
	{
        $status=', дата и время: '.$data->dt.', сигнал: '.$data->level;
	$fz = array('1-ф', '3-ф');
	$DA = array("не выполнена", "выполнена",  "выполняется", "???");
	$E1 = array("нет сигнала КЦ!", "сигнал КЦ 25%", "сигнал КЦ 50%", "сигнал КЦ 75%", "сигнал КЦ 100%");
	$errors = array
	    (" ошибка оборудования при выполнении самодиагностики",
	     " напряжение батарейки опустилось ниже нормы",
	     " крышка клеммной колодки была вскрыта",
	     " корпус счётчика был вскрыт",
	     " корректировалось тарифное расписание или список праздничных дней",
	     " по внешней команде корректировалось время или дата",
	     " напряжение фазы 'A' вышло за пределы нормы",
	     " напряжение фазы 'B' вышло за пределы нормы",
	     " напряжение фазы 'C' вышло за пределы нормы",
	     " потребляемая мощность превысила установленный лимит"
	    );

	    switch ($data->type)
	    {
		case 0x00:
		case 0x01:
		case 0x02:
		case 0x03:
		case 0x0F:
		case 0x10:
		case 0x11:
		case 0x12:
		case 0x13:
		case 0x1F:
		    $rez = 'счётчик: '.$data->c.' ('.$fz[$data->type>>4].'),   показания: '.$data->base.' кВтч'.$status;
		    break;
		case 0x0E:
		case 0x18:
		case 0x19:
		case 0x1A:
		case 0x1E:
		    $rez = 'счётчик: '.$data->c.' ('.$fz[$data->type>>4].'),   точный срез: '.$data->base.','.$data->dec.' кВтч'.$status;
		    break;
		case 0x40:
		case 0x41:
		case 0x42:
		case 0x43:
		    $rez = 'счётчик: '.$data->c.' (тариф '.(($data->type&3)+1).'),   суточный срез: '.$data->base.','.$data->dec.' кВтч'.$status;
		    break;
		case 0x44:
		    $rez = 'счётчик: '.$data->c.', получасовой срез: '.$data->base.','.$data->dec.' кВтч'.$status;
		    break;
		case 0x48:
		    $rez = 'счётчик: '.$data->c.', серийный номер: '.sprintf("%08d",$data->base).$status;
		    break;
		case 0x49:
		    $bad = $data->base&0x03FF;
		    $rez = 'счётчик: '.$data->c.$status;
		    if (!$bad) $rez+= ' нарушений в работе нет!';
		    else
		        for ($i=0; $i<10; $i++)
		            if (($data->base>>$i)&1) $rez+= $errors[$i].' ';
		    break;
		case 0x4F:
		    $rez = 'счётчик: '.$data->c.' (все тарифы),   суточный срез: '.$data->base.','.$data->dec.' кВтч'.$status;
		    break;
		case 0xDA:
		    $rez = 'счётчик: '.$data->c.' </b>команда '.$DA[$data->base].' CRCL: '.$data->dec.$status;
		    break;
		case 0xE1:
		    $rez = 'счётчик: '.$data->base.' сообщает: '.$E1[$data->dec&7].$status;
		    break;
	    }
	return "\n".$rez;
	}

	public function isBase($data)
	{
	    switch ($data->type)
	    {
		case 0x00:
		case 0x01:
		case 0x02:
		case 0x03:
		case 0x0F:
		case 0x10:
		case 0x11:
		case 0x12:
		case 0x13:
		case 0x1F:
		case 0x0E:
		case 0x18:
		case 0x19:
		case 0x1A:
		case 0x1E:
		case 0x40:
		case 0x41:
		case 0x42:
		case 0x43:
		case 0x44: 
		case 0x4F: return true; 
			   break;
		default:   return false;
	    }
	}

	public function parse()
	{
		//echo 'PARSE ';
		$CD = new Counter();
		$b = file_get_contents("php://input");
		$m = explode('end', rawurldecode($b));
		$size=sizeof($m)-1;
		$mass = array();
		//echo ' [SIZE = '.$size.'] ';
		
		$create_request_table_sql = "CREATE TABLE IF NOT EXISTS TestMetersRequests (id int(10) NOT NULL AUTO_INCREMENT, request TEXT, income_date DATETIME, PRIMARY KEY(id))";
		$current_date = new DateTime();
		$insert_request_sql = "INSERT INTO TestMetersRequest(request, income_date) VALUES('".$b."', '".$current_date->format('Y-m-d H:i:s')."')";
		$this->mysqli->query($create_request_table_sql);
		$this->mysqli->query($insert_request_sql);
		
		
		
		for ($x=0; $x<$size; $x++)  //перебор массива из строк, ранее разбитого по строкам с помощью end'ов
		{
			//echo "\n [X = $x] ";
		    $z = $m[$x];        //берем очередную строку
		    $len = strlen($z);  //вычисляем ее длину
		    $CD->dt=substr($z,0,16); //время и дата
		    $sss='';
		    for ($i=16; $i<$len; $i++)  //перебираем символы очередной строки
		    {
		       $mass[$i-16]=ord($z{$i});        //заносим коды символов в одномерный массив целых чисел
		       $sss=$sss.sprintf("%02X",$mass[$i-16]).' ';
		    } 
		    $CD->KC = ($mass[0]<<8)+$mass[1];
		    $CD->c = ($mass[2]<<8)+$mass[3];
		    //echo 'data: '.$sss;
		    //echo 'КЦ: '.sprintf("%04X", $CD->KC).' '.'сч: '.$CD->c;

		    $CD->type=$mass[4];
		    $CD->base=($mass[5]<<24)+($mass[6]<<16)+($mass[7]<<8)+$mass[8];
		    $CD->dec=$mass[9];
		    $CD->level=$mass[10];
		    echo $this->answer($CD);
		    if ($this->isBase($CD)) //echo "\nSAVE: ".sprintf("[%02X]", $CD->type).' сч: '.$CD->c;
			$this->save($CD);
		    //else echo "\nPACKAGE: ".sprintf("[%02X]", $CD->type).' сч: '.$CD->c;
		    else $this->saveService($CD);
		}
		return true;
	}

	public function saveService($data)
	{
		echo "\nPACKAGE: ".sprintf("[%02X]", $data->type).' сч: '.$data->c;
		if ($this->mysqli->connect_errno) 
		{
		    echo "Не удалось создать соединение с базой MySQL: \n";    
		    echo "Ошибка: " . $this->mysqli->connect_error . "\n";
		    exit;
		}
		$query = "SELECT * FROM Meters WHERE num = '{$data->c}';";
		$result = $this->mysqli->query($query);
		$row  = $result->fetch_assoc();
		switch ($data->type)
		{
		case 0x48:
			if (!$row) //вносим серийный номер СЧ в counters + сделать проверку изменения номера!
			{
		    	    //$query = "INSERT INTO Meters (name, `KC`, `level`, `serialnum`) VALUES ('{$data->c}', '{$data->KC}', '{$data->level}', '{$data->base}');";
                	$query = "INSERT INTO Meters (num, KC, level, serialnum) VALUES ('{$data->c}', '{$data->KC}', '{$data->level}', '{$data->base}');";
			    $result = $this->mysqli->query($query);
		    	    if (!$result) die('Invalid INSERT into COUNTERS query: ' . $this->mysqli->error);
			}
			else
			{
		    	    $query = "UPDATE Meters SET serialnum='{$data->base}' WHERE num='{$data->c}';";
			    $result = $this->mysqli->query($query);
		    	    if (!$result) die('Invalid UPDATE COUNTERS query: ' . $this->mysqli->error);
			}
			break;
		case 0x49:
		case 0xDA:
		case 0xE1:

			$query = "SELECT id FROM MeterTypes WHERE name = '{$data->type}'"; // Проверяем наличие "типа счетчика" в таблице
            $result = $this->mysqli->query($query);

            $meter_type_id = -1;

            if($result) {
                $meter_type_row = $result->fetch_assoc();
                $meter_type_id = $meter_type_row["id"];
			}
			else {
                $query = "INSERT INTO MeterTypes(name, type) VALUES('{$data->type}', '{$data->type}')"; // Добавляем новый тип счетчика
                $this->mysqli->query($query);

                $query = "SELECT id FROM MeterTypes WHERE name = '{$data->type}'"; // Выбираем id только что добавленного типа счетчик
                $result = $this->mysqli->query($query);
                if($result) {
                    $meter_type_row = $result->fetch_assoc();
                    $meter_type_id = $meter_type_row["id"];
                }
			}

            if($meter_type_id !== -1) {
                $meter_type_row  = $result->fetch_assoc();
                $meter_type_id = $meter_type_row["id"];

                if (!$row) //вносим серийный номер СЧ в counters
                {
                    $query = "INSERT INTO Meters (num, KC, level, meterType_id) VALUES ('{$data->c}', '{$data->KC}', '{$data->level}', '{$meter_type_id}');";
                    $result = $this->mysqli->query($query);
                    if (!$result) die('Invalid INSERT into COUNTERS query: ' . $this->mysqli->error);
                }
                else
                {
                    $query = "UPDATE Meters SET meterType_id='{$meter_type_id}' WHERE num='{$data->c}';";
                    $result = $this->mysqli->query($query);
                    if (!$result) die('Invalid UPDATE XXX query: ' . $this->mysqli->error);
                }
            }
            else {
                die('Invalid INSERT meterType query for this counter: ' . $this->mysqli->error);
            }
			break;
		}
	}

	public function save($data)
	{		
		//echo '\n SAVE '.$data->c.'/'.$data->base.'/'.$data->dt.' ';
		if ($this->mysqli->connect_errno) 
		{
		    echo "Не удалось создать соединение с базой MySQL: \n";    
		    echo "Ошибка: " . $this->mysqli->connect_error . "\n";
		    exit;
		}
		//$query = "SELECT * FROM `counters` WHERE counters.counter = '{$data->c}';";
        $query = "SELECT * FROM Meters WHERE num = '{$data->c}';";
		$result = $this->mysqli->query($query);
		$row  = $result->fetch_assoc();
		if (!$row) //если данным по счетчику не существовало в БД, создаём таблицу показаний СЧ и вносим СЧ в counters
		{
          	//$query = "CREATE TABLE IF NOT EXISTS `{$data->c}` (`id_data` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `KC` INT NOT NULL, `type` TINYINT UNSIGNED NOT NULL, `base` INT NOT NULL, `dec` TINYINT UNSIGNED NOT NULL, `dt` DATETIME NOT NULL, UNIQUE (`dt`)) ENGINE = InnoDB CHARACTER SET binary;";
            $query = "CREATE TABLE IF NOT EXISTS meter_{$data->c} (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, KC INT NOT NULL, type TINYINT UNSIGNED NOT NULL, base INT NOT NULL, decim varchar(4) NOT NULL, date DATETIME NOT NULL, UNIQUE (date)) ENGINE = InnoDB CHARACTER SET binary;";
		$result = $this->mysqli->query($query);
		if (!$result) die('Invalid CREATE query for this counter: ' . $this->mysqli->error);
		        $query = "INSERT INTO Meters (num, KC, level) VALUES ('{$data->c}', '{$data->KC}', '{$data->level}');";
			$result = $this->mysqli->query($query);
		        if (!$result) die('Invalid INSERT into COUNTERS query: ' . $this->mysqli->error);
		}
		//к данному моменту создана таблица конкретного счетчика и сделана запись в таблицу "counters"...
		$query = "SELECT date FROM meter_{$data->c} WHERE date = '{$data->dt}'";
		$result = $this->mysqli->query($query);
		if($result) {
            $row = $result->fetch_assoc();
            if (!$row) //проверка на отсутствие совпадений датывремени пакета данных с уже имеющимся в таблице
            {
                $query = "INSERT INTO meter_{$data->c} (KC, type, base, dec, date) VALUES ('{$data->KC}', '{$data->type}', '{$data->base}', '{$data->dec}', '{$data->dt}');";
                $result = $this->mysqli->query($query);
                if (!$result) die('Invalid INSERT INTO ' . $data->c . ' query: ' . $this->mysqli->error); //unique
            }
        }
        else {
            die('Invalid SELECT dt' . $this->mysqli->error);
		}
	}
}

echo "Test";
$obj = new Database();
$obj->parse();
?>
