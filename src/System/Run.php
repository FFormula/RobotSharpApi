<?php
namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Login;
use FFormula\RobotSharpApi\Model\User;
use FFormula\RobotSharpApi\System\Log;

/**
 * Class Run - стартовый класс получения запроса для дальнейшей обработки
 * @package FFormula\RobotSharpApi\Api
 */
class Run
{
    var $robot;

    /**
     * Проверка начальных данных,
     * подключение нужного класса,
     * вызов нужного метода и возвращение результата клиенту
     * @param $get array -
     *      token - token подключённого пользователя,
     *              не указывается при вызове Login/getUserToken()
     *      class - имя api-класса для работы
     *      method - имя метода для вызова
     *      ...   - остальные параметры, необходимые для работы метода
     * @return string - ответ готовые для передачи клиенту
     */
    public function start(array $get) : string
    {
        try
        {
            $answer = $this->callApi($get);
            return $this->getResponse(['answer' => $answer]);
        }
        catch (\Exception $ex)
        {
            Log::get()->error('Exception: ' . $ex->getMessage());
            Log::get()->error('Ex. Trace: ' . $ex->getTraceAsString());
            return $this->getResponse(['error' => $ex->getMessage()]);
        }
    }

    /**
     * Создание и подготовка экземпляра API для выполнения задачи
     * @param array $get
     *          class - какой класс создать
     *          method - какой метод вызывать
     *          token - все Api-функции требуют наличие token, по которому устанавливается пользователь
     * @return Api
     * @throws \Exception
     */
    private function callApi(array $get) : array
    {
        if (!$get['class'])
            throw new \Exception('class not specified');

        if (!$get['method'])
            throw new \Exception('method not specified');

        $className = $this->az($_GET['class']);
        $class = '\\FFormula\\RobotSharpApi\\Api\\Api' . $className;
        if (!class_exists($class))
            throw new \Exception('Api-class ' . $className . ' does not exists');

        $api = new $class();

        $method = $this->az($get['method']);
        if (!method_exists($class, $method))
            throw new \Exception('method ' . $className . '->' . $method . ' not exists');

        if (!($get['class'] == 'Login' &&
            $get['method'] == 'getUserToken'))
        {
            if (!$get['token'])
                throw new \Exception('Token not specified');

            $api->user = $this->getUserByToken($get['token']);
        }

        return $api->$method($get);
    }

    /**
     * Получение записи о пользователе по token-у с проверкой его наличия
     * @param string $token
     * @return User
     * @throws \Exception
     */
    private function getUserByToken(string $token) : User
    {
        $login = (new Login())->selectByToken($token);
        if (!$login->row['userId'])
            throw new \Exception('Token not found or expired');

        $user = (new User())->selectById($login->row['userId']);
        if (!$user->row['id'])
            throw new \Exception('User not found');

        return $user;
    }

    /**
     * Оставляем в строке только буквенно-цифровые символы
     * @param string $text - сообщение для обработки
     * @return string - обработанная строка из буквенно-цифровых символов
     */
    protected function az(string $text) : string
    {
        return preg_replace('/[^a-zA-Z0-9_]+/', '', $text);
    }

    /**
     * Формирование ответа для передачи клиенту
     * @return string
     */
    protected function getResponse(array $response) : string
    {
        $json = json_encode($response);
        Log::get()->info('Response: ' . $json);
        return $json;
    }
}