<?php

namespace FFormula\RobotSharpApi\Api;

use \FFormula\RobotSharpApi\Model\Task;

/**
 * Class ApiTask - получение информации о задачах
 * @package FFormula\RobotSharpApi\Api
 */
class ApiTask extends Api
{
    /**
     * получить условие задачи
     * @param array $get
     *          taskId - номер задачи
     *          dictId - язык текста, пока не использвется
     * @return string -
     *          id,
     *          authorId,
     *          name,
     *          sectorId,
     *          sector,
     *          step,
     *          caption - переведённое на нужный язык название
     *          description
     * @throws \Exception - в случае любой ошибки
     */
    public function getTask(array $get) : array
    {
        if (!$get['taskId'])
            throw new \Exception('taskId not specified');

        $dictId = 'ru';

        $task = (new Task())->selectById($get['taskId'], $dictId);

        if (!$task->row['id'])
            throw new \Exception('task not found');

        return $task->row;
    }

    /**
     * Получение списка всех задач
     * @param array $get
     *          dictId - язык текста, пока не использвется
     * @return string
     *          id,
     *          authorId,
     *          caption,
     *          sector
     * @throws \Exception - в случае любой ошибки
     */
    public function getTaskList(array $get) : array
    {
        $dictId = 'ru';
        $task = new Task();
        return $task->getList($dictId, $this->user->row['id']);
    }

}