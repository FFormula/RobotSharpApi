<?php

namespace FFormula\RobotSharpApi\Api;

use \FFormula\RobotSharpApi\Model\Program;

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

        $program = (new Program())->saveSource(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId'],
            $get['source']);

        // $program->createRunFiles();

        return [
            'runkey' => $program->row['runkey']
        ];
    }

    /**
     * Проверка и получение результатов тестирования программы
     * @param array $get - runkey запущенной программы
     * @return array - результат тестирования,
     *          compiler
     *          tests
     * @throws \Exception - в случае любой ошибки
     */
    public function getRunResults(array $get) : array
    {
        if (!$get['runkey'])
            throw new \Exception('runapi not specified');

        $program = (new Program())->selectByRunkey($get['runkey']);

        return $program->row;
    }


}