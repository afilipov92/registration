<?php 

class Template{

    /**
     * @var $html - html-код
     */
    private $html;

    /**
     * Возвращает  шаблон по его имени, если он найден в папке шаблонов. иначе - пустую строку
     * @param $name string - имя шаблона
     * @return string
     */
    public static function getTemplate($name){
        $tpl = "";
        $fileName = 'tpl' . DIRECTORY_SEPARATOR . $name . '.html';
        if(file_exists($fileName)){
            $tpl = file_get_contents($fileName);
        }
        return $tpl;
    }
	
	/**
     * Выполняет подстановки в переданный шаблон
     * @param $tpl string - строка с макросами подстановки вида {{NAME}}
     * @param array $data - массив подстановок вида array('NAME' => 'code')
     * @return string
     */
    public static function processTemplace($tpl, array $data = array()){
        foreach($data as $key => $val){
            $tpl = str_replace('{{'.$key.'}}',$val, $tpl);
        }
        return $tpl;
    }
	
    /**
     * Выводит сообщения об ошибках в переданный шаблон
     * @param array $data
     * @return string
     */
    public function processTemplateErrorOutput(array $data = array()){
        $tpl = $this->html;
        foreach($data as $key => $val){
            $tpl = str_replace(
			"<p data-name=\"$key\"></p>",
			"<p data-name=\"$key\">$val</p>",
			$tpl);
        }
        return $tpl;
    }

    /**
     * устанавливает значение свойству html
     * @param $str
     */
    public function setHtml($str){
        $this->html = $str;
    }

    /**
     * возвращает значение свойства html
     * @return mixed
     */
    public function getHtml(){
        return $this->html;
    }
}