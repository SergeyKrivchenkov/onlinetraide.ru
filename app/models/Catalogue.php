<?php

namespace app\models;

use app\core\Model;

class Catalogue extends Model // Catalogue подключаем базовый клас модели
{


    public function getCategories()
    {
        // echo 'MODEL getPages method';
        //$arr = $this->db->queryAll('users'); // проверяем себя на доступность методов описанном в наследуемом классе
        // debug($arr);
        // SELECT * FROM table
        $cats_arr = $this->db->queryAll('catalogue');
        return $this->getCountProducts($cats_arr); // здесь обновленный массив $cats_arr с ключом 'count'
    }


    public function getCountProducts($cats_arr)
    {
        foreach ($cats_arr as $key => $value) {
            $count = $this->db->queryCountProducts($value['id']);
            $cats_arr[$key]['count'] = $count;
        }
        return $cats_arr;
    }

    public function getProducts($param_name, $param_value, $cur_page, $count_on_page = 4)
    {



        $from = ($cur_page - 1) * $count_on_page;

        $products = $this->db->getLimitProducts('products', [$param_name => $param_value], $from, $count_on_page);
        $count = $this->db->queryCountProducts($param_value);
        return ['products' => $products, 'count' => $count]; // возвращаем 2 сущности

    }

    public function get_client_cart($client_id)
    {
        $client_card = $this->db->queryAll('cart', ['client_id' => $client_id]);
        //return $client_cart; // инфа должна вернутся в CatalogueController

        foreach ($client_card as $key => $product) {
            $product_id = $product['product_id'];
            $item = $this->db->queryAll('products', ['id' => $product_id]);
            $name = $item[0]['name'];
            $image = $item[0]['image'];
            $client_card[$key]['name'] = $name;
            $client_card[$key]['image'] = $image;
        }
        // debug($client_card);


        return $client_card;
    }




    public function checkout($client_id, $js_arr)
    {
        //! 1. В моссиве изменяем колличество товара на новое
        // return $js_arr;


        $db_arr = $this->db->queryAll('cart', ['client_id' => $client_id]);

        foreach ($db_arr as $i => $db_val) {
            foreach ($js_arr as $j => $js_val) {
                //! 2.   посчитать итоговую цену по каждому товару
                $total_price = $db_val['count'] * $db_val['price'];
                $db_arr[$i]['price'] = $total_price;

                if ($db_val['id'] == $js_val['productId']) {

                    if ($db_val['count'] != $js_val['count']) {
                        // return 'yes';
                        $db_arr[$i]['count'] = $js_val['count'];
                    }
                    break;
                }
            }
        }


        //! 3.  Удалить товары данного пользователя из таблицы cart

        $delete_query = $this->db->deleteFromCart($client_id, 'client_id');
        if ($delete_query) {
            //! 4. Перенести массив $db_arr в табл orders
            //INSERT INTO orders (id,client_id, product_id,count,	price) VALUES (),()
            $value = '';
            foreach ($db_arr as $key => $value) {
                // return 'yes';
                $value .= ',(' . $value['id'] . ',' . $value['client_id'] . ',' . $value['product_id'] . ',' . $value['count'] . ',' . $value['price'] . ')';
            }
            return $value;
        } else {
            // если delete не сработал
            return 'FALSE';
        }
        //return $db_arr;
    }





    public function delete_from_cart($client_id, $product_id)
    {
        $delete_query = $this->db->deleteFromCart($product_id);
        if ($delete_query) {
            // вернуть карзину целиком
            $client_card = $this->db->queryAll('cart', ['client_id' => $client_id]);
            foreach ($client_card as $key => $product) {
                $product_id = $product['product_id'];
                $item = $this->db->queryAll('products', ['id' => $product_id]);
                $name = $item[0]['name'];
                $image = $item[0]['image'];
                $client_card[$key]['name'] = $name;
                $client_card[$key]['image'] = $image;
            }
            return $client_card;
        } else {
            // если delete не сработал
            return 'FALSE';
        }
    }

    public function addItemIntoCart($client_id, $product_id, $count, $price)
    {
        // return $client_id;
        $product = $this->db->queryOne('cart', 'id', 'client_id', $client_id, 'product_id', $product_id);
        if ($product) {
            //Если нажимаем на товар который уже был добавлен -> бублир товар не добавлен в табл а увели уже существующего count
            $prod_id = $product['id'];


            $update_query = $this->db->updateProductCount($prod_id);



            if ($update_query) {
                // вернуть карзину целиком
                $client_card = $this->db->queryAll('cart', ['client_id' => $client_id]);
                foreach ($client_card as $key => $product) {
                    $product_id = $product['product_id'];
                    $item = $this->db->queryAll('products', ['id' => $product_id]);
                    $name = $item[0]['name'];
                    $image = $item[0]['image'];
                    $client_card[$key]['name'] = $name;
                    $client_card[$key]['image'] = $image;
                }
                return $client_card;
            } else {
                // если update не сработал

                return 'FALSE';
            }
        } else {
            // если такого товара нет то он добовляется
            $res = $this->db->addItemIntoCart($client_id, $product_id, $count, $price);
            // return json_encode($res);
            if ($res == false) {

                return 'false';
            } else {

                $client_card = $this->db->queryAll('cart', ['client_id' => $client_id]);
                foreach ($client_card as $key => $product) {
                    $product_id = $product['product_id'];
                    $item = $this->db->queryAll('products', ['id' => $product_id]);
                    $name = $item[0]['name'];
                    $image = $item[0]['image'];
                    $client_card[$key]['name'] = $name;
                    $client_card[$key]['image'] = $image;
                }
                // debug($client_card);
                return $client_card;
            }
        }
    }
}
