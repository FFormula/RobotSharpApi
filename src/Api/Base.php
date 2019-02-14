<?php

namespace FFormula\RobotSharpApi\Api;

use FFormula\RobotSharpApi\Model\User;

class Base
{
    /** @var string */
    var $answer;
    /** @var array */
    var $error;
    /** @var User */
    var $user;

    protected function answer(array $answer): string
    {
        $this->error = 'ok';
        $this->answer = $answer;
        return $this->getResponse();
    }

    protected function error(string $error): string
    {
        $this->error = $error;
        $this->answer = null;
        return $this->getResponse();
    }

    protected function exception(\Exception $ex): string
    {
        $this->error = 'Exception: ' . $ex->getMessage() .
                       ' in ' . $ex->getTraceAsString();
        $this->answer = null;
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

