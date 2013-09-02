<?php

require_once '../spritePacker/SpritePacker.php';
require_once 'mock/RendererMock.php';

class RendererTest extends PHPUnit_Framework_TestCase {

    public function setUp(){
        parent::setUp();
        $this->renderer = new RendererMock();
    }

    public function testRenderer_instanceOfiRenderer(){
        $result = $this->renderer instanceof iRenderer;
        $this->assertTrue($result);
    }

}