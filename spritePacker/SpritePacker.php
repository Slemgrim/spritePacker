<?php

require_once "interface/iSprite.php";
require_once "interface/iRenderer.php";
require_once "Sprite.php";
require_once "render/RenderPNG.php";
require_once "render/RenderCSS.php";
require_once "Atlas.php";
require_once "Block.php";
require_once "Order.php";

class SpritePacker {

    protected $sprites = array();
    protected $options = array(
        'name' => 'atlas',
        'atlas-width'   => 500,
        'atlas-height'  => 500,
        'render' => array(
            'render-png' => 'atlas/atlas.png',
            'render-css' => 'atlas/atlas.css',
        ),
        'save' => true,
    );

    protected $atlasWidth = 0;
    protected $atlasHeight = 0;
    protected $atlas = null;
    protected $atlasResource = null;
    protected $order = null;

    protected $renderer = null;

    public function __construct($options = array()){

        $this->options = array_merge($this->options, $options);
        $this->atlas = new Atlas($this->options['atlas-width'], $this->options['atlas-height']);
        $this->order = new Order($this->atlas);

        foreach($this->options['render'] AS $rendererName => $rendererPath){
            switch($rendererName){
                case 'render-png':
                    $this->renderer[$rendererName] = new RenderPNG($this->options['name'], $rendererPath);
                    break;
                case 'render-css':
                    $this->renderer[$rendererName] = new RenderCSS($this->options['name'], $rendererPath);
                    break;
            }
        }
    }

    public function addSprite($spritePath){
        if(file_exists($spritePath) && $this->isImage($spritePath)){
            $sprite = new Sprite($spritePath);
            array_push($this->sprites, $sprite);
            return true;
        }
        return false;
    }

    public function run(){
        if(empty($this->sprites)){
            return false;
        }
        $this->orderSprites();
        $this->render();
        return true;
    }

    public function show($name){
        $this->renderer[$name]->show();
    }

    protected function isImage($spritePath){
        if(getimagesize($spritePath) !== false){
            return true;
        }
        return false;
    }

    protected function orderSprites(){
        $this->order->addSprites($this->sprites);
        $this->sprites = $this->order->order();
    }

    protected function render(){
        foreach($this->renderer as $renderer){
            $renderer->render($this->atlas, $this->sprites);
            if($this->options['save']){
                $renderer->save();
            }
        }
    }
}