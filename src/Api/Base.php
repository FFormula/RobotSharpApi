<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\User;
use FFormula\RobotSharpApi\System\Log;

/**
 * Class Base Базовый класс для всех Api-классов
 * Содержит методы для формирования результата и ошибок
 * @package FFormula\RobotSharpApi\Api
 */
class Base
{
    /** @var string - сформированный ответ в json-формате */
    var $answer;
    /** @var array - полученное сообщение об ошибке */
    var $error;
    /** @var User - данные подключившегося пользователя */
    var $user;

    /**
     * формирование ответа без ошибок
     * @param array $answer - массив с ответом для передачи клиенту
     * @return string - готовый к выводу ответ
     */
    protected function answer(array $answer) : string
    {
        $this->error = 'ok';
        $this->answer = $answer;
        return $this->getResponse();
    }

    /**
     * формирование ответа с текстом ошибки
     * @param string $error - текст ошибки
     * @return string - готовые к печати ответ
     */
    protected function error(string $error) : string
    {
        $this->error = $error;
        $this->answer = null;
        return $this->getResponse();
    }

    /**
     * формирование сообщения об ошибке по исключению
     * @param \Exception $ex - исключение
     * @return string - готовые к печати ответ
     */
    protected function exception(\Exception $ex) : string
    {
        return $this->error(
            'Exception: ' . $ex->getMessage() .
                        ' in ' . $ex->getTraceAsString());
    }

    /**
     * Формирование ответа для передачи клиенту
     * @return string
     */
    protected function getResponse() : string
    {
        $response = [
            'error' => $this->error,
            'answer' => $this->answer
        ];
        $json = json_encode($response);
        Log::get()->info('Response: ' . $json);
        return $json;
    }
}

