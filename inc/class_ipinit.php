<?php
////////////////////////////////////////////////////
//                 WikiMobile                     //
////////////////////////////////////////////////////
// Автор:              web_demon                  //
// Оф. Сайт:           http://wikimobile.su       //
// Oф. сайт поддержки: http://annimon.com         //
// E-mail:             web_demon@mail.ru          //
////////////////////////////////////////////////////
//Автор модуля: AlkatraZ                          //
//  johncms.com                                   //
//  http://gazenwagen.com                         //
////////////////////////////////////////////////////

defined('MOBILE_WIKI') or die('Demon laughs');

class ipinit {
	  //$data = parse_ini_file('../files/cache/ipinit_set.dat');
    public $ip;    // IP адрес в LONG формате
    public $flood_chk = 1;    // Включение - выключение функции IP антифлуда
    public $flood_interval = '120';    // Интервал времени в секундах
    public $flood_limit = '200';    // Число разрешенных запросов за интервал
    public $flood_file = 'http_antiflood.dat';    // Рабочий файл функции
    private $requests;    // Число запросов с IP адреса за период времени

    function __construct() {
        $this->ip = $this->getip();
        // Проверка адреса IP на HTTP флуд
        if ($this->flood_chk) {
            $this->requests = $this->reqcount();
            if ($this->requests > $this->flood_limit)
                die('Flood!!!');
        }
    }

    // Получаем реальный адрес IP
    private function getip() {
        $ip1 = isset ($_SERVER['HTTP_X_FORWARDED_FOR']) ? ip2long($_SERVER['HTTP_X_FORWARDED_FOR']) : false;
        $ip2 = isset ($_SERVER['HTTP_VIA']) ? ip2long($_SERVER['HTTP_VIA']) : false;
        $ip3 = isset ($_SERVER['REMOTE_ADDR']) ? ip2long($_SERVER['REMOTE_ADDR']) : false;
        if ($ip1 && $ip1 > 184549376) {
            return $ip1;
        }
        elseif ($ip2 && $ip2 > 184549376) {
            return $ip2;
        }
        elseif ($ip3) {
            return $ip3;
        }
        else {
            die('Unknown IP');
        }
    }

    // Счетчик числа обращений с данного IP
    private function reqcount() {
        global $parent;
        $tmp = array();
        $requests = 1;
        if (!file_exists($parent . 'files/cache/' . $this->flood_file))
            $in = fopen($parent . 'files/cache/' . $this->flood_file, "w+");
        else
            $in = fopen($parent . 'files/cache/' . $this->flood_file, "r+");
        flock($in, LOCK_EX) or die("Cannot flock ANTIFLOOD file.");
        $now = time();
        while ($block = fread($in, 8)) {
            $arr = unpack("Lip/Ltime", $block);
            if (($now - $arr['time']) > $this->flood_interval) {
                continue;
            }
            if ($arr['ip'] == $this->ip) {
                $requests++;
            }
            $tmp[] = $arr;
        }
        fseek($in, 0);
        ftruncate($in, 0);
        for ($i = 0; $i < count($tmp); $i++) {
            fwrite($in, pack('LL', $tmp[$i]['ip'], $tmp[$i]['time']));
        }
        fwrite($in, pack('LL', $this->ip, $now));
        fclose($in);
        return $requests;
    }
}

?>