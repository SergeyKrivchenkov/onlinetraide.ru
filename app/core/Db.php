<?php
//  mysql не горантирует защищенность данных, там нужно жестко прописывать тип когда палучаем от пользователя, но есть у нас PDO (процедурно ориетированный стиль , а не процедурный) т.е. подготовленные sql запросы. Работа в проекте будет с PDO. (инфо здесь https://www.php.net/manual/ru/pdo.connections.php)
// stmt - statement
// в этом файле рекомендуется прописывать методы характерный для всей БД т.к. SELECT INSERT DELETE APDATE, все частные медоды желательно переносить в другое место.
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

            $this->db = new \PDO("mysql:host={$db_config['host']};dbname={$db_config['db_name']}", $db_config['user'], $db_config['password']); // подключаем БД при помощи PDO //! обязательным является наличие \ перед PDO при создаини экземпляра класса
        } catch (\PDOException $e) {
            die('<b>' . "DB connect error!!!" . '</b>');
            // debug($e);
        }
        // echo __CLASS__;
        // echo get_class(); // тоже самое что и __CLASS__
        // $this->queryAll("SELECT * FROM users");
    }


    // запрос на выбор из БД всех сведений, по умолчанию параметр null не передается запрос пишем универсальным чтобы подставлять только название табличы
    public function queryAll($table_name, $param = null)
    {
        //$param=null;
        //$param=['catalogue_id'=>1]
        //$param['catalogue_id']// получим 1
        // echo $param;
        if ($param != null) {
            // echo 'WHERE';
            $keys = array_keys($param); //взять из массива $param ключи которые у него есть
            $param_name = $keys[0];
            //debug($keys[0]); //получим ключ по 0 индексу
            $param_value = $param[$param_name]; // получим значение 1
            $stmt = $this->db->prepare("SELECT * FROM {$table_name} WHERE {$param_name} = ?"); // это запрос с уточнением (все зависит есть ли параметр  $param) расшифровка подготавливаемого запроса: SELECT * FROM products WHERE catalogue_id = 1, если значение асоциативного массива представляется в виде строки, то в prepare необходимо указать после указания ключа, необходимое нам значение в апострофах '' а именно -> {'$param['param_value']'. ? это значение поля (в данном случае ключа присвоенного $param_name)
            //debug($stmt);
            // "SELECT * FROM {$table_name} WHERE {$param_name} = ? LIMIT 2, 2 " // по этой записи LIMIT 2 выдает по 2 товара (а LIMIT 2,2 где первый аргумент с какой записи, второй аргумент кол-во записей, так же отсчет начинается с 0 записи)
            // debug($stmt->fetchAll());
        } else {
            $stmt = $this->db->prepare("SELECT * FROM {$table_name}"); // это запрос без уточнения подготавливаем запрос на получение всех товаров из таблицы
        }
        $stmt->execute([$param_value]); // выполняем запрос, здесь в execute передаем значение зашифрованное в $this->db->prepare как знак ?. сюда придет 1 и подставиться вместо знака ? после чего запрос можно перевести как SELECT * FROM products WHERE catalogue_id = 1. Где получим ассоциативный массив искомых товаров по catalogue_id = 1
        return $stmt->fetchAll(\PDO::FETCH_ASSOC); //todo выдаем запрос возвращая результат в Catalogue.php в метод getProducts (например выдаст товары у которых catalogue_id = 1)
    }

    // запрос на выбот из БД чего то конкретного (здесь в $param передаются сведения от пользователя поэтому $param может соответствовать id, name, full_name, login тоесть заглавию таблиц в mysqli базе данных проекта поэтому записываем таким образом {$param} = ?)
    /*
    public function queryOne($table_name, $field, $param, $value)
    {
        $stmt = $this->db->prepare(" SELECT {$field} FROM {$table_name} WHERE {$param} = ?");
        $stmt->execute([$value]);
        return $stmt->fetch(\PDO::FETCH_ASSOC); // \PDO::FETCH_ASSOC указываем для того чтобы исключить ключи по номерам а аспользовать только имена столбцов без их номеров (т.е убираем индексированный ключ, а оставляем ассоциативный)
    }
 */

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
        return $stmt->fetch(\PDO::FETCH_ASSOC)['count']; //return значения по ключу count (возвращает именно количество товаров только числом без каких либо ключей и т.п.)
    }

    // метод на получение необходимого на странице количества товара 49 мин lesson #57
    public function getLimitProducts($table, $param, $from, $count_on_page)
    {
        // эти 3 свойства описаны в queryAll это ключи
        $keys = array_keys($param);
        // debug($keys);
        $param_name = $keys[0]; // здесь в $param_name будет записано cat_id
        // echo $param_name;
        $param_value = $param[$param_name];
        // echo $param_value;

        //$stmt = $this->db->prepare("SELECT * FROM $table WHERE {$param_name} = ? LIMIT $position, $count "); // где ? подставиться 1, $position это с какого элемента брать (если id =1 то браться будет по логике програмирования т.е. 1 это будет 0 элемент) а $count это сколько элементов брать
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE {$param_name} = {$param_value} LIMIT {$from}, {$count_on_page}");

        $stmt->execute(); // здесь в execute() ничего нет так как нет знаков ?
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }





    // auth - метод для сравнения полей заполняемых пользователем для входа с данными имеющемися в БД по тоаблице пользователей.
    public function auth($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE `email` = ?"); // где ? значит что это будет вводить пользователь и возможно это потанциально опасно и требует проверки. a в `` пишем название полей.
        $stmt->execute([$email]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        // debug($data); // метод fetch возвращаем одну строку из БД, поэтому его следует исп 1 раз а не каждый раз писать где у нас указано свойство $data метод fetch(\PDO::FETCH_ASSOC).
        // todo ПОЛННАЯ проверка на совпадение email и password
        // если пароль совпал?
        if ($data) {
            // debug($stmt->fetch(\PDO::FETCH_ASSOC));

            // echo 'yes';
            $password_hash = $data['password']; // получение информации в ассоциативном формате и сдесь же по ключу password берем наш хешированный пароль и присваиваем это свойству $password_hash
            if (password_verify($password,  $password_hash)) {
                // password_verify($password,  $password_hash); // это функция для обратного преобразования хешированного паролья в норм вид
                // echo 'ok';
                return $data; // получение информации в ассоциативном формате
                // результат этой проверки ловится в Model.php
            } else {
                // echo 'no';
                return false;
            }
        } else {
            return false;
        }
        // после этого возвращаемся в Model.php где это все ловиться
    }

    public function updateProductCount($product_id) // сюда приходит id который есть в твблице count как порядковый номер записи и который передали из Catalogue
    {
        // debug($product_id);
        $product_count_arr = $this->queryOne('cart', 'count', 'id', $product_id);
        $product_count = $product_count_arr['count'];
        $product_count += 1;

        $stmt = $this->db->prepare("UPDATE cart SET `count` = ? WHERE `id`= ?");  //! здесь cart обязательно указывать без '' иначе на работает!!!!!!!! 
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
        return $res; // return возвращается туда от куда вызываем.
        // $price
        /*
        В sql запросах всегда строковое в опострофах.
        ниже указан вариант записи в БД информации в тексовом виде. отличительной особенностью явл. что передоваемый параметр `{$brand}` указывается в косых опострофах не смотря на то что в $brand = "lenova" указана в опатсрафах. Это если данные передаем не через ?. PDO преобразует этот момент что если в $stmt->execute будет указан $brand как и остальные параметры то все будет работать нормально.
        $brand = "lenova";
        $stmt = $this->db->prepare("INSERT INTO cart SET `client_id` = ?, `product_id` = ?, `count`=?, `brand`=`{$brand}`");
        $stmt = $this->db->prepare("INSERT INTO cart SET `client_id` = ?, `product_id` = ?, `count`=?, `brand`=?");
        $stmt->execute([$client_id, $product_id, $count]);
        */
    }
}
