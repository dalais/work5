<?php

if (!function_exists("flash")) {
    /**
     * Флэш сообщения с использованием маханизма сессий
     *
     * @param string $name
     * @param string $msg
     * @return mixed
     */
    function flash($name, $msg='')
    {
        if ($msg === '' && isset($_SESSION['flash'][$name])) {
            return ($_SESSION['flash'][$name]['exp'] > time()) ? $_SESSION['flash'][$name]['message']: null;
        }

        if ($msg !== '') {
            unset($_SESSION['flash'][$name]);
            $_SESSION['flash'][$name]['exp'] = time()+2;
            $_SESSION['flash'][$name]['message'] = $msg;
            return true;
        }
        return null;
    }


}