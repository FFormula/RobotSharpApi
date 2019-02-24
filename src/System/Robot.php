<?php

namespace FFormula\RobotSharpApi\System;

use FFormula\RobotSharpApi\Model\Program;
use FFormula\RobotSharpApi\Model\Test;

/**
 * Class Robot - Подготовка каталога для запуска программ
 * и считывание результатов проверки из файлов
 * @package FFormula\RobotSharpApi\System
 */
class Robot
{
    private static $path = null;
    private $runkey;

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
        $this->setRunkey($program->row['runkey']);

        $folder = self::$path . 'init/' . $this->runkey . '/';

        $this->createFolder($folder);

        $this->writeFile(
            $folder . 'Program.' . $program->row['langId'],
            $program->row['source']);

        $tests = (new Test())->getAllTests($program->row['taskId']);

        foreach ($tests as $test)
            $this->writeFile(
                $folder . 'test.' . $test['testNr'] . '.in',
                $test['fileIn']);

        $this->moveFolder($folder, self::$path . 'wait/' . $this->runkey);
    }

    /**
     * Считать результат тестирования из созданных роботом файлов
     * @param Program $program
     * @return array
     * @throws \Exception
     */
    public function readTestFiles(string $runkey) : array
    {
        Log::get()->info('Try to read run results for ' . $runkey);
        $this->setRunkey($runkey);

        $folder = self::$path . 'done/' . $this->runkey . '/';

        if (!file_exists($folder))
        {
            Log::get()->info('Done-Api folder not found: ' . $folder);
            return [];
            // throw new \Exception('Results for this runkey not present');
        }

        $answer['compiler'] = $this->readCompiler($folder);
        $answer['tests'] = $this->readTests($folder);

        Log::get()->info('Moving done folder to drop');
        $this->moveFolder($folder, self::$path . 'drop/' . $this->runkey);
        return $answer;
    }

    /**
     * @param string $runkey
     * @throws \Exception
     */
    private function setRunkey(string $runkey) : void
    {
        if (self::$path == null)
            throw new \Exception('Robot path not specified');
        if ($runkey == '')
            throw new \Exception('Runkey not specified');
        $this->runkey = $this->az($runkey);
        if (strlen($this->runkey) < 10 || strlen($this->runkey) > 30)
            throw new \Exception('Invalid runkey');
    }

    /**
     * @param string $folder
     * @return string
     * @throws \Exception
     */
    private function readCompiler(string $folder) : string
    {
        return $this->readFile($folder . 'compiler.out');
    }

    /**
     * @param $folder
     * @return array
     * @throws \Exception
     */
    private function readTests(string $folder) : array
    {
        $tests = [];
        for ($j = 0; $j < 20; $j ++)
        {
            $fileIn  = $folder . 'test.' . $j . '.in';
            $fileOut = $folder . 'test.' . $j . '.out';
            if (file_exists($fileIn) &&
                file_exists($fileOut))
                $tests[$j] = [
                    'fileIn'  => $this->readFile($fileIn),
                    'fileOut' => $this->readFile($fileOut)
                ];
            else
                break;
        }
        return $tests;
    }

    /**
     * @param string $folder
     * @throws \Exception
     */
    private function createFolder(string $folder) : void
    {
        if (!mkdir($folder))
            throw new \Exception('Error creating folder ' . $folder);
    }

    /**
     * @param string $from
     * @param string $to
     * @throws \Exception
     */
    private function moveFolder(string $from, string $to) : void
    {
        if (!rename($from, $to))
            throw new \Exception('Error moving folder ' . $from . ' to ' . $to);
    }

    /**
     * Запись в файл с проверкой
     * @param string $filename - имя файла
     * @param string $text - что записать
     * @throws \Exception - в случае ошибки
     */
    private function writeFile(string $filename, string $text) : void
    {
        if (FALSE === @file_put_contents($filename, $text))
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
        $text = @file_get_contents($filename);
        if (FALSE === $text)
            throw new \Exception('Error reading file ' . $filename);
        return $text;
    }

    protected function az(string $text) : string
    {
        return preg_replace('/[^.a-zA-Z0-9_]+/', '', $text);
    }

}