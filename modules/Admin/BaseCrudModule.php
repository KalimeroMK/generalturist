<?php


    namespace Modules\Admin;


    abstract class BaseCrudModule
    {

        static string $version = "1.0";

        public $model;

        public function index(): array
        {
            return [];
        }

        public function create(): array
        {
            return [];
        }
    }
