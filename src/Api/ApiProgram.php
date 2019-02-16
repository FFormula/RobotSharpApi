<?php

namespace FFormula\RobotSharpApi\Api;

use \FFormula\RobotSharpApi\Model\Program;
use FFormula\RobotSharpApi\Model\Test;

/**
 * Получение текста программы пользователя и запуск программы на проверку.
 * Class ApiProgram
 * @package FFormula\RobotSharpApi\Api
 */
class ApiProgram extends Base
{
    /**
     * Получение текста программы пользователя или её заготовки
     * @param array $get массив начальных данных
     *          taskId - номер задачи
     *          langId - язык программирования
     * @return string - исходный текст программы
     */
    public function getProgram(array $get) : string
    {
        if (!$this->user->row['id'])
            return $this->error('No user id');

        if (!$get['taskId'])
            return $this->error('taskId not specified');

        if (!$get['langId'])
            return $this->error('langId not specified');

        $program = (new Program())->selectByKeys(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId']);

        if (!$program->row['source'])
            $program->setDefaults(
                $this->user->row['id'],
                $get['taskId'],
                $get['langId']);

        return $this->answer($program->row);
    }

    /**
     * Сохранение и запуск программы на выполнение
     * @param array $get массив начальных данных
     *          taskId - номер задачи
     *          langId - язык программирования
     *          source - исходный текст для запуска на проверку
     * @return string - возвращает созданный $runkey для запускаемой программы
     *                  который используется для получения результатов тестирования
     */
    public function runProgram(array $get) : string
    {
        if (!$this->user->row['id'])
            return $this->error('No user id');

        if (!$get['taskId'])
            return $this->error('taskId not specified');

        if (!$get['langId'])
            return $this->error('langId not specified');

        if (!$get['source'])
            return $this->error('Source not specified');

        $program = (new Program())->selectByKeys(
            $this->user->row['id'],
            $get['taskId'],
            $get['langId']);

        if ($program->row['source'])
            $program->update(
                $this->user->row['id'],
                $get['taskId'],
                $get['langId'],
                $get['source']);
        else
            $program->insert(
                $this->user->row['id'],
                $get['taskId'],
                $get['langId'],
                $get['source']);

        $program->createRunFiles();

        return $this->answer([
            'runkey' => $program->row['runkey']
        ]);
    }

    public function getRunAnswer(array $get)
    {
        if (!$get['runkey'])
            return $this->error('runapi not specified');

        $program = (new Program())->selectByRunkey($get['runkey']);

        return $this->answer([
            'answer' => json_decode($program->row['answer'])
        ]);
    }


}