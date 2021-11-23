<?php

namespace Modules\V1\Lib;

use Modules\V1\Lib\Token;

/**
 * (EN) management class of session status
 * (ES) clase para manejar sesiones
 * @package V1
 * @subpackage Lib
 * @author Juan Bautista <soyjuanbautista0@gmail.com>
 * 
 */
class Session
{
    /**
     * (ES) cookie con el token
     * (EN) token string
     * @var string
     */
    public static $token;
    public static $auth;
    public function __construct()
    {
    }
    /**
     * Start()
     * @access public 
     * (ES) Método que inicia sesíon creando el JWT necesario y seteando su cookie
     * @param array $datos
     * Datos a guardar en el token
     * @param array $exc
     * Excepciones a no guardar en el token
     */
    public static function Start(array $datos, array $exc = []): string
    {
        self::$token = new Token;
        if (count($exc) > 0) {
            foreach ($exc as $key => $value) {
                unset($datos[$value]);
            }
        }
        date_default_timezone_set($datos['time_zone'] ?? $_ENV['APP_TIME_ZONE']);
        $time = ($datos['remember_me'] == true) ? self::TokenTime(30) : self::TokenTime(1);
        return self::$token->SignIn($datos, $time);
    }
    /**
     * TokenTime()
     * @access public
     * @param int $days
     */
    public static function TokenTime(int $days)
    {
        $time =  new \DateTime('now');
        $time = explode(" = ", $time->format('U = Y-m-d H:i:s'));
        $time = intval($time[0]) +  (60 * 60 * 24 * $days);
        return $time;
    }
    /**
     * GetData()
     * @access public
     * (ES) Método que trae los datos de un JWT
     * @param string $token
     * Token pa decodificar
     * @return array
     */
    public static function GetData(string $tokenString): array
    {
        self::$token = new Token;
        if (!empty($tokenString)) {
            return self::$token->GetData($tokenString);
        }
    }

    /**
     * Método exclusivo de Login Controller
     */
    public static function islogged(string $token, array $exceptions = []): bool
    {
        self::$token = new Token;
        return (self::$token->Check($token)) ? true : false;
    }
}
