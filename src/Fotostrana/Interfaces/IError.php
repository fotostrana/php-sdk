<?php
namespace Fotostrana\Interfaces;

use Fotostrana\Model\ModelError;

interface IError
{
    /** @return ModelError */
    public function error();
}