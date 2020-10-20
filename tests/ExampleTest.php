<?php

namespace Tests;

use App\Cart;
use App\Element;
use App\Product;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function view()
    {
        //テスト1
        $cart = new Cart();

        $expected = 'お客様のショッピングカートに商品はありません。';
        $this->assertEquals($expected, $cart->show());


        //テスト2 正常系
        $product = new Product('Amazon Web Services 業務システム設計・移行ガイド', 3456);
        $element = new Element($product, 1);

        $cart->add($element);

        $product = new Product('プログラマの数学第2版', 2376);
        $element = new Element($product, 2);

        $cart->add($element);

        $expected = "Amazon Web Services 業務システム設計・移行ガイド\t3456\t1".PHP_EOL
                   ."プログラマの数学第2版\t2376\t2".PHP_EOL
                   ."小計 (3 点): \\8208";
        $this->assertEquals($expected, $cart->show());


        //テスト3不具合
        $cart = new Cart();
        $product = new Product('', 999994);
        $element = new Element($product, 1);

        $cart->add($element);

        $product = new Product('プログラマの数学第2版', 2376);
        $element = new Element($product, 2);

        $cart->add($element);

        $cart->show($product,$element);
        $expected = "タイトルを指定してください".PHP_EOL
            ."価格は0から99999までに指定してください".PHP_EOL;
        $this->assertEquals($expected, $cart->show());


        //テスト4 不具合
        $cart = new Cart();
        $product = new Product('Amazon Web Services 業務システム設計・移行ガイド', 0);
        $element = new Element($product, 1);

        $cart->add($element);

        $product = new Product('プログラマの数学第2版', 237);
        $element = new Element($product, 40);

        $cart->add($element);
        $cart->show($product,$element);
        $expected = "プログラマの数学第2版の数量は1から9までに指定してください".PHP_EOL;
        $this->assertEquals($expected, $cart->show());

        //テスト5 不具合
        $cart = new Cart();
        $product = new Product('Amazon Web Services 業務システム設計・移行ガイド', 100001);
        $element = new Element($product, -4);

        $cart->add($element);

        $product = new Product('プログラマの数学第2版', 2376);
        $element = new Element($product, 40);

        $cart->add($element);

        $cart->show($product,$element);
        $expected = "価格は0から99999までに指定してください".PHP_EOL
            ."Amazon Web Services 業務システム設計・移行ガイドの数量は1から9までに指定してください".PHP_EOL;
        $this->assertEquals($expected, $cart->show());

    }
}
