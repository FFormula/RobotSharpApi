<?php
/**
 * Created by PhpStorm.
 * User: 308
 * Date: 2/5/2019
 * Time: 8:54 AM
 */

namespace FFormula\RobotSharp\ApiSystem;

use FFormula\RobotSharp\Model\Login;
use FFormula\RobotSharp\Model\User;

class Run extends Base
{
    /**
     * @param $get array - All need params
     *      token - logged user token
     *      class - api class to call
     *      method - api method to call
     *      etc - other params
     * @param $post array - some functions require post data
     * @return string - result
     */
    public function start(array $get, array $post = []) : string
    {
        try
        {
            if (!$get['token'])
                return $this->error('Token not specified');

            if (!$get['class'])
                return $this->error('class not specified');

            if (!$get['method'])
                return $this->error('method not specified');

            $class = '\\FFormula\\RobotSharp\\ApiClient\\' . $this->az($_GET['class']);
            if (!class_exists($class))
                return $this->error('class not exists');

            $api = new $class();

            $method = $this->az($get['method']);
            if (!method_exists($class, $method))
                return $this->error('method not exists');

            $login = (new Login())->selectByToken($get['token']);
            if (!$login->row['userId'])
                return $this->error('Token not found or expired');

            $api->user = (new User())->selectById($login->row['userId']);

            if (!$api->user->row['id'])
                return $this->error('User not found');

            if (count($post) > 0)
                $api->post = $post; // if any required

            return $api->$method($get);

        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    protected function az(string $text) : string
    {
        return preg_replace('/[^a-zA-Z0-9_]+/', '', $text);
    }

}