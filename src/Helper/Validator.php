<?php


namespace App\Helper;


use App\Element;
use App\Product;

class Validator
{
    private $product;
    private $element;
    private $condition;
    private $errors = [];

    public function __construct(Product $product,Element $element,array $condition)
    {
            $this->product = $product;
            $this->element = $element;
            $this->condition =$condition;

    }

    public function validate()
    {
        $this->validateProductTitle();
        $this->validateProductPrice();
        $this->validateQuantity();
        return $this->errors;
    }

    private function validateProductTitle()
    {
        $val = trim($this->product->getTitle());
        if(empty($val)){
            $this->addError('title','タイトルを指定してください');
        }

        if(strlen($val)>$this->condition['title'][1]){
            $this->addError('title','商品の最大文字列数'.$this->$this->condition['title'][1].'以下に指定してください');
        }
    }

    private function validateProductPrice()
    {
        $val = $this->product->getPrice();
        $max_price = $this->condition['price'][1];
        $mix_price = $this->condition['price'][0];
        if($val<$mix_price ||$val>$max_price){
            $this->addError('price','価格は'.$mix_price.'から'.$max_price.'までに指定してください');
        }
    }

    private function validateQuantity()
    {
        $val = $this->element->getQuantity();
        $max_quantity = $this->condition['quantity'][1];
        $mix_quantity = $this->condition['quantity'][0];
        $title =trim($this->product->getTitle());
        $title = $title==''?"指定商品":$title;
        if($val<$mix_quantity ||$val>$max_quantity){
            $this->addError('quantity',
                $title.'の数量は'.$mix_quantity.'から'.$max_quantity.'までに指定してください');
        }

    }

    private function addError($key,$val)
    {
        $this->errors[$key] = $val;
    }

}