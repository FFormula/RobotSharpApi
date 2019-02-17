<?php

namespace FFormula\RobotSharpApi\System;

use FFormula\RobotSharpApi\Model\Program;
use FFormula\RobotSharpApi\Model\Test;

class Robot
{
    private static $path = null;

    /**
     * Статическая инициализация пути для последующего создания экземпляра
     * @param array $config
     */
    public static function init(array $config)
    {
        self::$path = $config['path'];
    }

    /**
     * Создание файлов для запуска проверки роботом
     * @param Program $program - данные о программе
     * @throws \Exception - в случчае любой ошибки
     */
    public function createRunFiles(Program $program) : void
    {
        if (self::$path == null)
            throw new \Exception('Robot path not specified');
        $folder = self::$path . 'init/' . $program->row['runkey'] . '/';
        if (!mkdir($folder))
            throw new \Exception('Error creating folder ' . $folder);
        $this->writeFile(
            $folder . 'Program.' . $program->row['langId'],
            $program->row['source']);
        $tests = (new Test())->getAllTests($program->row['taskId']);
        foreach ($tests as $test)
            $this->writeFile(
                $folder . 'test.' . $test['testNr'] . '.in',
                $test['fileIn']);
        if (!rename($folder, self::$path . 'wait/' . $program->row['runkey']))
            throw new \Exception('Error moving folder ' . $folder);
    }

    /**
     * @param Program $program
     * @return array
     * @throws \Exception
     */
    public function readTestFiles(Program $program) : array
    {
        $answer = [
            'compiler' => '',
            'tests' => []
        ];
        $folder = self::$path . 'done/' . $program->row['runkey'] . '/';
        if (!file_exists($folder))
            return $answer;
        $answer['compiler'] = file_get_contents($folder . 'compiler.out');
        for ($j = 0; $j < 20; $j ++)
        {
            $fileIn  = $folder . 'test.' . $j . '.in';
            $fileOut = $folder . 'test.' . $j . '.out';
            if (file_exists($fileIn) &&
                file_exists($fileOut))
                $answer['tests'][$j] = [
                    'fileIn'  => $this->readFile($fileIn),
                    'fileOut' => $this->readFile($fileOut)
                ];
            else
                break;
        }
        return $answer;
    }

    /**
     * Запись в файл с проверкой
     * @param string $filename - имя файла
     * @param string $text - что записать
     * @throws \Exception - в случае ошибки
     */
    private function writeFile(string $filename, string $text) : void
    {
        if (FALSE === file_put_contents($filename, $text))
            throw new \Exception('Error writing file ' . $filename);
    }

    /**
     * Считывание файла с проверкой
     * @param string $filename - имя файла
     * @param string $text - что записать
     * @throws \Exception - в случае ошибки
     */
    private function readFile(string $filename) : string
    {
        $text = file_get_contents($filename);
        if (FALSE === $text)
            throw new \Exception('Error reading file ' . $filename);
        return $text;
    }

}