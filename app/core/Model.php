<?php
//автоматически подключается к базе данных это основная логика этого метода
namespace app\core;
// на прямую этот файл подключаться не будет, он наследуется классом main
//в use app\config\Db больше нет необходимости так как теперь Db лежит в папке core поэтому достаточно будет объявления пространтсва имен. use app\config\Db; // пишем чтобы могли работать с Db и были вынуждены постоянно писать путь до этого файла в коде. НЕ ИСП use В ТОМ СЛУЧАЕ ЕСЛИ ВМЕСТО Db БУДЕМ ПОДКЛЮЧАТЬСЯ ЧТО ТО ЕЩЕ 1,2,3 И ТД
abstract class Model
{
    // формируем свойство в кот запишеся экз класса БД
    protected $db;

    public function __construct()
    {
        $this->db = new Db(); // создай экз класса, дай подключение к БД для осуществления запросов
        // переходим к Db
        // echo 'MODEL base';
        // debug($this->db);
        $this->db->queryAll('fdfdfh'); // проверяем для себя доступность свойств и методов наследуемого класса т.к. теперь в $this->db доступны все свойства и методы описанные в одноименном файле
        // теперь возвращаемся в ребетка в файл Main
    }

    public function auth($email, $password)
    {

        // echo $email;
        // echo  $password;
        // echo $password;
        // SELECT * FROM clients WHERE email=$email AND password=$password
        $res = $this->db->auth($email, $password);
        //debug($res);// возвращает результат того что записано в БД
        //echo password_hash('12Aa', PASSWORD_DEFAULT); //хешируем пароль 1 праметром передаем пароль, 2 параметр алгоритм хеширования

        // password_verify()// это функция для обратного преобразования хешированного паролья в норм вид
        if ($res) {
            return $res; // возвращаем в базовый контроллер
        } else {
            return false;
        }
    }


    // ------------запрос
    // public function queryAll($sql)
    // {
    //     debug($this->db);
    //     /*
    //     $stmt = $this->db->prepare($sql); // подготавливаем запрос
    //     $stmt->execute(); // выполняем запрос
    //     echo 'It is works';
    //     // debug($stmt->fetchAll());
    //     return $stmt->fetchAll(); // выдаем запрос
    //     */
    // }



}
