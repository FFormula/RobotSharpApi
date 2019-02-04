<?php

namespace FFormula\RobotSharp\API;

class Api
{
    var $answer;
    var $error;

    protected function error(string $error): string
    {
        $this->error = $error;
        $this->answer = null;
        return $this->getResponse();
    }

    protected function answer(array $answer): string
    {
        $this->error = 'ok';
        $this->answer = $answer;
        return $this->getResponse();
    }

    protected function getResponse(): string
    {
        $response = [
            'error' => $this->error,
            'answer' => $this->answer
        ];
        return json_encode($response);
    }
}

