<?php

namespace app\core;

class Db
{
    protected $db; // сюда записывается подключение описанное ниже (этот класс подключается к модели)


    public function __construct()
    {
        $db_name = 'app/config/db_config.php';
        if (file_exists($db_name)) {
            $db_config = require_once $db_name;
            // debug($db_config);
        }
        try {

            $this->db = new \PDO("mysql:host={$db_config['host']};dbname={$db_config['db_name']}", $db_config['user'], $db_config['password']); // 
        } catch (\PDOException $e) {
            die('<b>' . "DB connect error!!!" . '</b>');
            // debug($e);
        }
    }


    // запрос на выбор из БД всех сведений, по умолчанию параметр null не передается запрос пишем универсальным чтобы подставлять только название табличы
    public function queryAll($table_name, $param = null)
    {

        if ($param != null) {
            // echo 'WHERE';
            $keys = array_keys($param);
            $param_name = $keys[0];
            //debug($keys[0]); //получим ключ по 0 индексу
            $param_value = $param[$param_name]; // получим значение 1
            $stmt = $this->db->prepare("SELECT * FROM {$table_name} WHERE {$param_name} = ?");
        } else {
            $stmt = $this->db->prepare("SELECT * FROM {$table_name}"); // это запрос без уточнения подготавливаем запрос на получение всех товаров из таблицы
        }
        $stmt->execute([$param_value]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); //todo выдаем запрос возвращая результат в Catalogue.php в метод getProducts (например выдаст товары у которых catalogue_id = 1)
    }



    public function queryOne($table_name, $field, $param1, $value1, $param2 = null, $value2 = null, $param3 = null, $value3 = null) // 2 способом усовершенствуем 1 вариант запроса а именно делаем универсальный запрос при возможности выплнении нескольких условий передачи в БД запроса с предпологаемыми параметрами
    {
        //"SELECT id FROM cart WHERE `client_id` = $client_id AND `product_id` = $product_id"
        if ($param2 and $value2) {
            $stmt = $this->db->prepare(" SELECT {$field} FROM {$table_name} WHERE {$param1} = ? AND {$param2} = ?");
            $stmt->execute([$value1, $value2]);
        } else if ($param3 and $value3) {
            $stmt = $this->db->prepare(" SELECT {$field} FROM {$table_name} WHERE {$param1} = ? AND {$param2} = ? AND {$param3} = ?");
            $stmt->execute([$value1, $value2, $value3]);
        } else {

            $stmt = $this->db->prepare(" SELECT {$field} FROM {$table_name} WHERE {$param1} = ?");
            $stmt->execute([$value1]);
        }
        return $stmt->fetch(\PDO::FETCH_ASSOC); // указываем return здесь т.к. может быть выполнено любое из вышеуказаных условий
    }

    public function queryCountProducts($catalogue_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM products WHERE catalogue_id = ?");
        // debug($stmt);
        $stmt->execute([$catalogue_id]);
        // return $stmt->fetch(\PDO::FETCH_ASSOC);
        // $arr = $stmt->fetch(\PDO::FETCH_ASSOC);
        // return $arr['count'];
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    }

    // метод на получение необходимого на странице количества товара 49 мин lesson #57
    public function getLimitProducts($table, $param, $from, $count_on_page)
    {
        // эти 3 свойства описаны в queryAll это ключи
        $keys = array_keys($param);
        // debug($keys);
        $param_name = $keys[0];
        $param_value = $param[$param_name];
        // echo $param_value;


        $stmt = $this->db->prepare("SELECT * FROM $table WHERE {$param_name} = {$param_value} LIMIT {$from}, {$count_on_page}");

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }






    public function auth($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE `email` = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // todo ПОЛННАЯ проверка на совпадение email и password
        // если пароль совпал?
        if ($data) {

            $password_hash = $data['password'];
            if (password_verify($password,  $password_hash)) {

                return $data;
            } else {
                // echo 'no';
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateProductCount($product_id)
    {
        // debug($product_id);
        $product_count_arr = $this->queryOne('cart', 'count', 'id', $product_id);
        $product_count = $product_count_arr['count'];
        $product_count += 1;

        $stmt = $this->db->prepare("UPDATE cart SET `count` = ? WHERE `id`= ?");
        $res = $stmt->execute([$product_count, $product_id]);
        return $res;
    }

    public function deleteFromCart($param_val, $param_name = 'id')
    {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE `${param_name}`=?");
        $res = $stmt->execute([$param_val]);
        return $res;
    }


    public function addItemIntoCart($client_id, $product_id, $count, $price)
    {
        $stmt = $this->db->prepare("INSERT INTO cart SET `client_id` = ?, `product_id` = ?, `count`=?, `price`=?");
        $res = $stmt->execute([$client_id, $product_id, $count, $price]);
        return $res;
    }
}
