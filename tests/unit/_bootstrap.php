<?php
// Here you can initialize variables that will for your tests
use Symfony\Component\Yaml\Parser;

$yaml = new Parser();
$this->fixtures = $yaml->parse(file_get_contents(dirname(__FILE__).'/../fixtures/datas.yml'));
$this->villes = $this->fixtures['datas']['villes'];