<?php
include_once(_PS_MODULE_DIR_.'kj_enoff/BlockEnOff.php');
require_once(dirname(__FILE__).'/../../'.'config/config.inc.php');
class Kj_EnOff extends Module
{
    protected $_html = '';
    public function __construct() {
        $this->name = 'kj_enoff';
        $this->author = 'Jing';
        $this->version = '1.0.0';
        $this->need_instance= 0;
        $this->bootstrap = true;
        $this->tab = 'others';
        parent::__construct();
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => _PS_VERSION_
        );
        $this->displayName = $this->l('kj en off');
        $this->description = $this->l('Displays a block of en off ');
    }

    public function install(){
        if (parent::install()
            && $this->registerHook('displayHome')
            &&$this->createTable()
            &&$this->installFixtures()
        ) {
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        /* Deletes Module */
        if (!parent::uninstall()||!$this->deleteTable()) {
            return false;
        }
        return true;
    }

    protected function createTable()
    {
        return Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'home_en_off` (
                `id_enoff_block` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `link` varchar(255) NULL,
                `img` varchar(255)  NULL,
                `title`  text  NULL,
                PRIMARY KEY (`id_enoff_block`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
    }

    protected function deleteTable()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'home_en_off`;
        ');
    }
    protected function installFixtures(){
        for($i=0; $i<3;$i++) {
            $block = new BlockEnOff();
            $resulat= $block->add();
        }
        return $resulat;
    }
    public function hookDisplayHome($params)
    {
        $this->context->smarty->assign(array(
            'blocks'=>$this->getAllBlocks(),
            'link'=> _MODULE_DIR_.$this->name."/img/"
        ));
        return $this->display(__FILE__, 'blockenoff.tpl');
    }

    public function getAllBlocks(){
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT *
            FROM '._DB_PREFIX_.'home_en_off h'
        );

    }
   public function getContent(){
       $this->postProcess();
       $this->getConfigFieldsValues();
       return $this->display(__FILE__, 'getContent.tpl');
   }

   public function getConfigFieldsValues(){
       $blocks=$this->getAllBlocks();
       $this->context->smarty->assign(array(
           'blocks'=> $blocks,
           'link'=> _MODULE_DIR_.$this->name."/img/"
       ));
   }
   public function postProcess(){
       if(Tools::isSubmit('submit_block')){
            $block_id= Tools::getValue('block_id');
            $block= new BlockEnOff((int)$block_id);
            $block->link = Tools::getValue('link');
            $block ->title = Tools::getValue('title');
            $filename= basename($_FILES['img']['name']);
            $filetmp=$_FILES['img']['tmp_name'];
            $images= array("image/jpeg","image/jpg","image/png");
            $valide=true;
            if(!isset($filename)||empty($filename)||empty($filetmp)||!in_array($_FILES['img']['type'],$images)) {
               $valide=false;
            }
            if($valide){
                if(!empty($block->img)){
                    @unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$block->img.'.webp');
                    @unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$block->img);
                }
                $file_webp = (dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$filename.'.webp');
                $this->generate_webp_image($filetmp, $file_webp,100);
                if (!move_uploaded_file($filetmp, dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$filename)) {
                    return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
                }
                $block->img=$filename;
            }
           $block->update();
       }
   }

    public function generate_webp_image($file,$output_file, $compression_quality)
    {
        // check if file exists
        if (!file_exists($file)) {
            return false;
        }

        $file_type = $this->getimgsuffix($file);
        if (function_exists('imagewebp')) {

            switch ($file_type) {
                case 'jpeg':
                case 'jpg':
                    $image = imagecreatefromjpeg($file);
                    break;

                case 'png':
                    $image = imagecreatefrompng($file);
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;

                case 'gif':
                    $image = imagecreatefromgif($file);
                    break;
                default:
                    return false;
            }

            // Save the image
            $result = imagewebp($image, $output_file, $compression_quality);
            if (false === $result) {
                return false;
            }

            // Free up memory
            imagedestroy($image);

            return $output_file;
        } elseif (class_exists('Imagick')) {
            $image = new Imagick();
            $image->readImage($file);

            if ($file_type === 'png') {
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality($compression_quality);
                $image->setOption('webp:lossless', 'true');
            }

            $image->writeImage($output_file);
            return $output_file;
        }

        return false;
    }

    public function getimgsuffix($name) {
        $info = getimagesize($name);
        $suffix = false;
        if($mime = $info['mime']){
            $suffix = explode('/',$mime)[1];
        }
        return $suffix;
    }

}