<?php
/**
 * Created by PhpStorm.
 * Author: shanyuliang
 * Date: 2019/8/31
 * Time: 10:56
 * Email: lqy.shanyu@gmial.com
 */

namespace Shanyuliang\Array2xml;

class Array2xml
{
    /**
     * @var string
     * VERSION
     */
    private $version;

    /**
     * @var string
     * CHARSET
     */
    private $charset;

    /**
     * @var array
     * attribute array
     */
    protected $attribute = [];

    /**
     * @var array
     * cdata array
     */
    protected $cdata = [];

    public function __construct($charset = 'UTF-8', $version = '1.0')
    {
        $this->version = $version;
        $this->charset = $charset;
    }

    /**
     * @param $data
     * @param array $attribute
     * @param array $cdata
     * @return mixed
     * start generate
     */
    public function generate($data, $attribute = [], $cdata = [])
    {
        if(empty($data) || !is_array($data)){
            return [
                'code'  => -1,
                'error' => '请传入要转换的数组数据',
            ];
        }
        $xmlData = [];
        $this->attribute = $attribute;
        $this->cdata = $cdata;
        try{
            $dom = new \DOMDocument($this->version, $this->charset);
            $res =  $this->createData($data, $dom, null);
            if(empty($xmlData = $data = $res->saveXML())){
                throw new \Exception('生成数据为空');
            }
        }catch (\Exception $e){
            $error = '生成失败：'. $e->getMessage();
        }

        if(!empty($error)){
            return [
                'code'  => -1,
                'error' => $error,
            ];
        }
        return [
            'code'  => 0,
            'error' => '',
            'data'  => $xmlData
        ];
    }

    /**
     * @param $data
     * @param $dom
     * @param $parent
     * @return mixed
     * create data
     */
    private function createData($data, $dom, $parent)
    {
        foreach ($data as $key=> $val){
            $key = trim($key);
            if(is_array($val) && $parent == null){
                $element = $dom->createElement($key);
                $dom->appendchild($element);

                $this->createAttribute($key, $dom, $element);

                $dom = $this->createData($val, $dom, $element);
            }elseif(is_array($val) && $parent != null) {
                if($num = strrpos($key,"|")){
                    $key = substr($key, 0, $num);
                }
                $element = $dom->createElement($key);
                $parent->appendchild($element);

                $this->createAttribute($key, $dom, $element);

                $dom = $this->createData($val, $dom, $element);
            }elseif($parent != null){
                $element = $dom->createElement($key);
                $parent->appendchild($element);

                $this->createAttribute($key, $dom, $element);

                if(!empty($val) && in_array($key, $this->cdata)){
                    $this->createCData($dom, $element, $val);
                }else{
                    $text = $dom->createTextNode($val);
                    $element->appendchild($text);
                }
            }else{
                $element = $dom->createElement($key);
                $dom->appendchild($element);

                $this->createAttribute($key, $dom, $element);

                if(!empty($val) && in_array($key, $this->cdata)){
                    $this->createCData($dom, $element, $val);
                }else{
                    $text = $dom->createTextNode($val);
                    $element->appendchild($text);
                }
            }
        }

        return $dom;
    }

    /**
     * @param $key
     * @param $dom
     * @param $parent
     * create attribute
     */
    private function createAttribute($key, $dom, $parent)
    {
        if(empty($this->attribute) || !is_array($this->attribute)){
            return;
        }
        if(isset($this->attribute[$key]) && !empty($items = $this->attribute[$key])){
            foreach ($items as $k => $item){
                $attr = $dom->createAttribute($k);
                $parent->appendchild($attr);

                $text = $dom->createTextNode($item);
                $attr->appendChild($text);
            }
        }
    }

    /**
     * @param $dom
     * @param $parent
     * @param $val
     * create cdata
     */
    private function createCData($dom, $parent, $val)
    {
        $cdata = $dom->createCDATASection($val);
        $parent->appendChild($cdata);
    }
}