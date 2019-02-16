<?php

namespace FFormula\RobotSharpApi\Api;

use \FFormula\RobotSharpApi\Model\Task;

/**
 * Class ApiTask - получение информации о задачах
 * @package FFormula\RobotSharpApi\Api
 */
class ApiTask extends Base
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
     */
    public function getTask(array $get) : string
    {
        if (!$get['taskId'])
            return $this->error('taskId not specified');

        $dictId = 'ru';

        $task = (new Task())->selectById($get['taskId'], $dictId);

        if (!$task->row['id'])
            return $this->error('task not found');

        return $this->answer($task->row);
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
     */
    public function getTaskList(array $get) : string
    {
        if ($get == []) return ''; // не знаю, как избавиться от варнинга, что параметр не используется :(
        $dictId = 'ru';
        $task = new Task();
        $list = $task->getList($dictId);
        return $this->answer($list);
    }

}