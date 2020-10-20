<?php

namespace App;

use App\Helper\ProductValidator;
use App\Helper\Validator;

class Cart
{
    protected $elements = [];

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    public function add(Element $element)
    {
        $this->elements[] = $element;
    }


    public function show()
    {
        if(empty($this->elements)){
            $result = 'お客様のショッピングカートに商品はありません。';
            return $result;
        }

        $amount = 0;
        $totalQuantity = 0;
        $result = '';

        foreach($this->elements as $element){
            //条件のチェック
            $check_res = $this->check($element->getProduct(),$element);
            if('200' !==$check_res){
                return $check_res;
            }

            $result .= $element->getProduct()->getTitle() . "\t" . $element->getProduct()->getPrice() . "\t" . $element->getQuantity() . "\n";
            $amount += $element->getProduct()->getPrice() * $element->getQuantity();
            $totalQuantity += $element->getQuantity();
        }

        $result .= '小計 ('.$totalQuantity.' 点): \\'.$amount;
        return $result;
    }

    public function check(Product $product,Element $element)
    {
        try {
            //バリデーション、エラー出し
           $validator = new Validator($product,$element,[
               'title'=>[1,255],
               'price'=>[0,99999],
               'quantity'=>[1,9]
           ]);
           $errors = $validator->validate();

            if(count($errors)){
                $error_msg = "";
                foreach ($errors as $error){
                    $error_msg .= $error. "\n";
                }
                return $error_msg;
            }

           return "200";

        }catch (\Exception $e){
            echo "エラーが発生しました".$e->getMessage();
        }
    }
}
