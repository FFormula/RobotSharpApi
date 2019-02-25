<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\Program;
use FFormula\RobotSharpApi\System\Log;
use FFormula\RobotSharpApi\System\Robot;

/**
 * Получение текста программы пользователя и запуск программы на проверку.
 * Class ApiProgram
 * @package FFormula\RobotSharpApi\Api
 */
class ApiProgram extends Api
{
    /**
     * Получение текста программы пользователя или её заготовки
     * @param array $get массив начальных данных
     *          taskId - номер задачи
     *          langId - язык программирования
     * @return array - исходный текст программы
     * @throws \Exception - в случае любой ошибки
     */
    public function getProgram(array $get) : array
    {
        if (!$this->user->row['id'])
            throw new \Exception('No user id');

        if (!$get['taskId'])
            throw new \Exception('taskId not specified');

        if (!$get['langId'])
            throw new \Exception('langId not specified');

        $program = (new Program())->getUserSource(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId']);

        if ($program->row['status'] == 'run')
        {
            $run = (new Robot())->readTestFiles($program->row['runkey']);
            if (count($run) > 0)
                $program->updatePoints($run['compiler'], $run['tests']);
        }

        return $program->row;
    }

    /**
     * Сохранение и запуск программы на выполнение
     * @param array $get массив начальных данных
     *          taskId - номер задачи
     *          langId - язык программирования
     *          source - исходный текст для запуска на проверку
     * @return array - возвращает созданный $runkey для запускаемой программы
     *                  который используется для получения результатов тестирования
     * @throws \Exception - в случае любой ошибки
     */
    public function runProgram(array $get) : array
    {
        if (!$this->user->row['id'])
            throw new \Exception('No user id');

        if (!$get['taskId'])
            throw new \Exception('taskId not specified');

        if (!$get['langId'])
            throw new \Exception('langId not specified');

        if (!$get['source'])
            throw new \Exception('Source not specified');

        if (!$get['mode'])
            throw new \Exception('Mode not specified');

        if ($get['mode'] != 'save' && $get['mode'] != 'run')
            throw new \Exception('Mode must be save or run');

        $program = (new Program())->saveSource(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId'],
            $get['source'],
            $get['mode']);

        if ($get['mode'] == 'run')
            (new Robot())->createRunFiles($program);

        return $program->row;
    }

}