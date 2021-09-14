<?php


class BlockEnOff extends ObjectModel
{
    public $link;
    public $title;
    public $img;

    public static $definition = array(
        'table' => 'home_en_off',
        'primary' => 'id_enoff_block',
        'fields' => array(
            'link' =>			array('type' => self::TYPE_STRING,'size' => 255),
            'title' =>	array('type' => self::TYPE_HTML,'size' => 255),
            'img' =>			array('type' => self::TYPE_STRING,'size' => 255),
        )
    );

    public	function __construct($id_enoff_block = null)
    {
        parent::__construct($id_enoff_block);
    }

}