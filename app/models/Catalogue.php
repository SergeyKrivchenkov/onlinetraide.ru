<?php
//это МОДЕЛЬ
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

    // рекомендуется под каждое новое действие писать новый метод
    public function getCountProducts($cats_arr) // метод для подсчета кол-ва товаров
    {
        foreach ($cats_arr as $key => $value) {
            $count = $this->db->queryCountProducts($value['id']);
            $cats_arr[$key]['count'] = $count; // добовляем в имеющися массив $cats_arr новый ключ 'count' со значение $count
        }
        return $cats_arr;
    }

    public function getProducts($param_name, $param_value, $cur_page, $count_on_page = 4) // универсальный метод (запускается в CatalogController) который понимает для какой категории должен тащить товары (товаров множетсво - метод отдин!!!) сюда передаем название поля и значение параметра кот передаем в виде массива и остальное при этом по дефолту кол-во отображаемых товаров = 4.
    //где посылаем запрос там принимаем ответ
    {
        // echo $catalogue_id;
        // $products = $this->db->queryAll('products', [$param_name => $param_value]); // ['$param_name'=>$param_value] - этот массив записан в свойство $param. products название таблицы  ...асоциативный массив по которому подставиться. если 2 параметр не передать то выводит where и не заходит в Db.php в ветку else где stmt). Вся эта инфа передается в метод queryAll описанный в Db.php


        $from = ($cur_page - 1) * $count_on_page; // эта формула нужно так как что бы из БД брать данные для вывода на 1 меньше т.е записи с 0 элемента

        $products = $this->db->getLimitProducts('products', [$param_name => $param_value], $from, $count_on_page); // указываем все 4 параметра (где  [$param_name => $param_value] - это все 2 параметр) при этом $param_name это catalogue_id a $param_value это 1 (если категория за компьютерами закреплена 1).

        // todo делаем запрос на кол-во товаров из ДБ для пагинатора $count
        // $item = $this->db->queryAll('products', ['id' => 1]);
        // debug($item[0]['name']);
        // $arr = $this->db->queryAll('cart', ['client_id' => 1]);
        // debug($arr);
        $count = $this->db->queryCountProducts($param_value);
        return ['products' => $products, 'count' => $count]; // возвращаем 2 сущности
        // echo $count;
        // debug($products);

        //return $products; // значение вернется в  СatalogueController
    }

    public function get_client_cart($client_id)
    {
        $client_card = $this->db->queryAll('cart', ['client_id' => $client_id]);
        //return $client_cart; // инфа должна вернутся в CatalogueController

        foreach ($client_card as $key => $product) // где $product это каждый отдельный продукт. Используем для добовляения недостающих сведений из БД
        {
            $product_id = $product['product_id']; // берем id товара
            $item = $this->db->queryAll('products', ['id' => $product_id]); // берем поле id из табл products  
            $name = $item[0]['name'];
            $image = $item[0]['image'];
            $client_card[$key]['name'] = $name;
            $client_card[$key]['image'] = $image; // добовляем картинку в существующий массив
        }
        // debug($client_card);


        return $client_card;
    }


    //todo в БД в таблице cart у всех товаров должны обновить count lesson 68 00:57:00
    //! пояснение работы сличения переданных данных ветки if lesson 68 01:25:00

    public function checkout($client_id, $js_arr) // здесь $products это из модального окна (все указанные там товары отобранные пользователем)
    {
        //! 1. В моссиве изменяем колличество товара на новое
        // return $js_arr;

        //return $products; // видим что в массиве 2 поля это product_id & count
        $db_arr = $this->db->queryAll('cart', ['client_id' => $client_id]); //выбираем все товары из таблицы cart непосредственно для пользователя 

        //return $db_arr; // здесь тоже кол-во товара как и в $products (описанном выше), но дополнительно еще указаны: id по порядку записи в таблице БД, client_id, id товара только под другим ключом и price.

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
                    break; // пишем если совподение найдено то переходи к следующей опирации сравнения
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

    // db=[5,3,2]
    // js=[3,1,5]


    // здесь формируем логику
    public function delete_from_cart($client_id, $product_id)
    {
        $delete_query = $this->db->deleteFromCart($product_id); // метод описываем в ДБ для удобства понимания camelCase пишем названия методов в ДБ, а в модели пишем snake_case
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
        $product = $this->db->queryOne('cart', 'id', 'client_id', $client_id, 'product_id', $product_id); // т.е. отправляем запрос на выборку одной конкретной записи у конкретного пользователя если у него эта запись уже имеется (передали параметры в queryOne из Db.php)

        if ($product) {
            //Если нажимаем на товар который уже был добавлен -> бублир товар не добавлен в табл а увели уже существующего count
            $prod_id = $product['id'];
            // return  $prod_id; //116

            $update_query = $this->db->updateProductCount($prod_id);
            // return $update_query;


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
                //echo 'Ошибка добавления'; // вариант когда товара еще не существует и при попытки добовления произошла ошибка
                return 'false';
            } else {
                //echo 'товар добавлен'; //вариант когда товара еще не существует и добовление прошло успешно
                //return 'товар добавлен';
                $client_card = $this->db->queryAll('cart', ['client_id' => $client_id]); // подобная запись так как в Db.php queryAll($table_name, $param = null) 2 параметром передается ассоциативный массив.
                // здесь пока не достает имени и картинки
                foreach ($client_card as $key => $product) // где $product это каждый отдельный продукт. Используем для добовляения недостающих сведений из БД
                {
                    $product_id = $product['product_id']; // берем id товара
                    $item = $this->db->queryAll('products', ['id' => $product_id]); // берем поле id из табл products  
                    $name = $item[0]['name'];
                    $image = $item[0]['image'];
                    $client_card[$key]['name'] = $name;
                    $client_card[$key]['image'] = $image; // добовляем картинку в существующий массив
                }
                // debug($client_card);
                return $client_card;
            }
        }


        // $table_name, $field, $param1, $value1, $param2 = null, $value2 = null, $param3 = null, $value3 = null

        //$arr = [$client_id, $product_id, $count, $price];
        //return json_encode($product); // возвращаем json представление данных (json_decode принимает закадированную в json строку и преобразует ее в переменную php)

    }
}

// добовление элемента в массив
//arr = ['name' => 'john'];
//arr['age'] = 32;       