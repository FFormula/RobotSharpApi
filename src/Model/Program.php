<?php

namespace FFormula\RobotSharpApi\Model;

/**
 * Работа с таблицей Program - список всех программ
 * пользователей с результатами последнего запуска на проверку
 * Class Program
 * @package FFormula\RobotSharpApi\Model
 */
class Program extends Record
{
    var $path = 'c:/#Robot/data/';

    /**
     * Получить исходный теекст и результаты проверки решения пользователя
     * @param string $userId - номер пользователя
     * @param string $taskId - номер задачи
     * @param string $langId - язык программирования
     * @return Program - этот экземпляр с данными в row
     */
    public function getUserSource(string $userId, string $taskId, string $langId) : Program
    {
        $this->setDefaults($userId, $taskId, $langId, '');

        if ($this->existsRecord())
            $this->row = $this->db->select1Row('
                SELECT userId, taskId, langId, 
                       runkey, points, runs, 
                       source, compiler, tests
                  FROM program
                 WHERE userId = ?
                   AND taskId = ?
                   AND langId = ?', [ $userId, $taskId, $langId ]);
        else
            $this->row['source'] = (new Lang())->selectByKey($langId)->row['source'];

        return $this;
    }

    /**
     * Получить результаты тестирования по ключу запуска
     * @param string $runkey - уникальный ключ запуска
     * @return Program - этот экземпляр с данными в row
     */
    public function selectByRunkey(string $runkey) : Program
    {
        $this->row = $this->db->select1Row('
            SELECT userId, taskId, langId, 
                   runkey, points, runs, 
                   source, compiler, tests
              FROM program
             WHERE runkey = ?', [ $runkey ]);
        return $this;
    }

    /**
     * Сохранение исходного кода от пользователя для решения задачи
     * @param string $userId - какой пользователь
     * @param string $taskId - какую задачу
     * @param string $langId - на каком языкп программирования
     * @param string $source - решил и прислал этот исходный код на проверку
     * @return Program - этот экземпляр с данными в row
     */
    public function saveSource(string $userId, string $taskId, string $langId, string $source) : Program
    {
        $this->setDefaults($userId, $taskId, $langId, $source);
        $this->generateRunkey();

        if ($this->existsRecord()) // если запись уже есть
            $this->update();      // просто обновляем
        else                     // иначе - добавляем новую запись
            $this->insert();

        return $this;
    }

    /**
     * Установка значений столбцов по умолчанию
     * @param $userId - номер пользователя
     * @param $taskId - номер задачи
     * @param $langId - язык программирования
     * @param string $source - исходный код, если не указан - берётся из lang.source
     * @return Program - этот экземпляр с данными в row
     */
    private function setDefaults(string $userId, string $taskId, string $langId, string $source) : void
    {
        $this->row = [
            'userId' => $userId,
            'taskId' => $taskId,
            'langId' => $langId,
            'runkey' => '',
            'points' => 0,
            'runs'   => 0,
            'source' => $source,
            'compiler' => '',
            'tests' => ''
        ];
    }

    /**
     * Проверка наличия записи по ключевым полям
     * @return bool - True если запись есть, False - если нет
     */
    private function existsRecord() : bool
    {
        return $this->db->selectValue('
            SELECT COUNT(*)
              FROM program
             WHERE userId = ?
               AND taskId = ?
               AND langId = ?',
            [
                $this->row['userId'],
                $this->row['taskId'],
                $this->row['langId']
            ]);
    }

    /**
     * Добавление новой записи
     * @return bool - удачно ли добавлено
     */
    private function insert() : bool
    {
        return $this->db->execute('
            INSERT INTO program
               SET userId = :userId,
                   taskId = :taskId,
                   langId = :langId,
                   runkey = :runkey,
                   points = :points,
                   runs = :runs,
                   source = :source,
                   compiler = :compiler,
                   tests = :tests', $this->row);
    }

    /**
     * Обновление записи
     * @return bool
     */
    private function update() : bool
    {
        return $this->db->execute('
            UPDATE program
               SET runkey = :runkey,
                   points = :points,
                   runs = :runs,
                   source = :source,
                   compiler = :compiler,
                   tests = :tests
             WHERE userId = :userId
               AND taskId = :taskId
               AND langId = :langId', $this->row);
    }

    /**
     * Генерация ключа запуска по текущим данным записи
     */
    private function generateRunkey() : void
    {
        $this->row['runkey'] =
            date('ymd.His', time()) .
            '.' . $this->row['userId'] .
            '.' . $this->row['taskId'] .
            '.' . $this->row['langId'] ;
    }
}