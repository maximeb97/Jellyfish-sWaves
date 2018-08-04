<?php

namespace App\Module\Templates;

class Module
{
    public function __construct()
    {
    }

    /**
     * Get the type of the module
     *
     * @return void
     */
    public function getType()
    {
        return "module";
    }

    /**
     * Get a description of the module
     *
     * @return void
     */
    public function getDescription()
    {
        return "A simple module";
    }

    /**
     * Get a list of parameters for the module
     *
     * @return void
     */
    public function getParameters()
    {
        /**
         * Example:
         * [
         *  'parameterName1' => 'integer',
         *  'parameterName2' => 'string'
         * ]
         * 
         */
        return [
        ];
    }
}
