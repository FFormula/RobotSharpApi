<?php
namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Login;
use FFormula\RobotSharpApi\Model\User;
use FFormula\RobotSharpApi\System\Log;

/**
 * Class Run - стартовый класс получения запроса для дальнейшей обработки
 * @package FFormula\RobotSharpApi\Api
 */
class Run extends Base
{
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
            if (!$get['class'])
                return $this->error('class not specified');

            if (!$get['method'])
                return $this->error('method not specified');

            $className = $this->az($_GET['class']);
            $class = '\\FFormula\\RobotSharpApi\\Api\\Api' . $className;
            if (!class_exists($class))
                return $this->error('Api-class ' . $className . ' does not exists');

            $api = new $class();

            $method = $this->az($get['method']);
            if (!method_exists($class, $method))
                return $this->error('method ' . $className . '->' . $method . ' not exists');

            if (!($get['class'] == 'Login' &&
                  $get['method'] == 'getUserToken'))
            {
                if (!$get['token'])
                    return $this->error('Token not specified');

                $login = (new Login())->selectByToken($get['token']);
                if (!$login->row['userId'])
                    return $this->error('Token not found or expired');

                $api->user = (new User())->selectById($login->row['userId']);

                if (!$api->user->row['id'])
                    return $this->error('User not found');
            }

            return $api->$method($get);

        } catch (\Exception $ex) {
            Log::get()->error('Exception: ' . $ex->getMessage());
            Log::get()->error('Ex. Trace: ' . $ex->getTraceAsString());
            return $this->exception($ex);
        }
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
}