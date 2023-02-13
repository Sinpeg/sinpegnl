<?php

final class Error {
    const ERROR_KEY = '_erro';

    private static $erros = null;


    private function __construct() {
    }

    public static function hasErros() {
        self::initErros();
        return count(self::$erros) > 0;
    }
     public static function addErro($message) {
        if (!strlen(trim($message))) {
            throw new Exception('Cannot insert empty erro message.');
        }
        self::initErros();
        self::$erros[] = $message;
    }

    /**
     * Get Erro messages and clear them.
     * @return array Erro messages
     */
    public static function getErros() {
        self::initErros();
        $copy = self::$erros;
        self::$erros = array();
        return $copy;
    }

    public static function initErros() {
//        session_start();
        if (self::$erros !== null) {
            return;
        }
        if (!array_key_exists(self::ERROR_KEY, $_SESSION)) {
            $_SESSION[self::ERROR_KEY] = array();
        }
        self::$erros = &$_SESSION[self::ERROR_KEY];
    }

}