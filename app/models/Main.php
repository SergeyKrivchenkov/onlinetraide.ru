<?php
// этот файл работает с информ на главной стр.
namespace app\models;

use app\core\Model; // так как лежат в разных местах (подключаем для доступа к $this->db)
class Main extends Model
{
    // в этом классе можем работать с данными сформированными из БД, обращение к которой было осущественно в классе Model 
    //эти методы пойдут к БД, отсюда информ должна уйти в maincontroller

    public function getPages()
    {
        // echo 'MODEL getPages method';
        $arr = $this->db->queryAll('users'); // проверяем себя на доступность методов описанном в наследуемом классе это название табл
        $arr = $this->db->queryOne('users', 'login', 'id', '5');
        // debug($arr);
    }
}
