<?php

namespace FFormula\RobotSharpApi\System;

use FFormula\RobotSharpApi\Model\Test;

class Robot
{
    private $path;

    public function __construct(array $config)
    {
        $this->path = $config['path'];
    }

    public function createRunFiles() : void
    {
        $folder = $this->path . 'init/' . $this->row['runkey'] . '/';
        mkdir($folder);
        file_put_contents($folder . 'Program.' . $this->row['langId'], $this->row['source']);
        $tests = (new Test())->getAllTests($this->row['taskId']);
        foreach ($tests as $test)
            file_put_contents($folder . 'test.' . $test['testNr'] . '.in', $test['fileIn']);
        rename($folder, $this->path . 'wait/' . $this->row['runkey']);
    }

    public function readRunFiles() : array
    {
        $answer = [];
        $folder = $this->path . 'done/' . $this->row['runkey'] . '/';
        if (!file_exists($folder))
            return $answer;
        $answer['compiler'] = file_get_contents($folder . 'compiler.out');
        for ($j = 0; $j < 20; $j ++)
        {
            $fileIn  = $folder . 'test.' . $j . '.in';
            $fileOut = $folder . 'test.' . $j . '.out';
            if (file_exists($fileOut))
                $answer[$j] = [
                    'fileIn'  => file_get_contents($fileIn),
                    'fileOut' => file_get_contents($fileOut)
                ];
            else
                break;
        }
        return $answer;
    }

}