<?php

namespace app\models;

use app\core\Model;

class Main extends Model
{


    public function getPages()
    {
        // echo 'MODEL getPages method';
        $arr = $this->db->queryAll('users'); // проверяем себя на доступность методов описанном в наследуемом классе это название табл
        $arr = $this->db->queryOne('users', 'login', 'id', '5');
        // debug($arr);
    }
}
