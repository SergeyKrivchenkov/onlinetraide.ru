<?php

namespace app\controllers;

use app\core\Controller;
// все что написанно в Controller доступно на стр. CatalogueController 

class CatalogueController extends Controller // находимся на стр. каталог
{

    public function indexAction() // это то что будет отображаться на главной стр. каталога
    {
        $categories = $this->model->getCategories();
        // $this->model->getCountProducts();
        $this->view->render($categories); // из каждого конкретного контроллера будет вызываться метод который отрисует вид и шаблон
        // debug($categories);
        //debug($this->view); // проверяем доступен ли нам вид
        // echo '<br>' . " Контроллер: " . __CLASS__ . "|Экшен: " . __METHOD__;
        // getCategories();
    }

    public function computersAction() //запускает действия которые берут информацию
    {
        //echo "Hello"; // будет виден при запуске ajax
        $this->save_ses_cat_id(); // это сессия и редерект
        $arr = $this->getProducts(); // это не тот метод что вызывается из модели, этот метод описан здесь ниже
        $this->view->render($arr); // это отправляется в вид (в базовый View.php)
    }

    public function clothesAction()
    {
        $this->save_ses_cat_id();
        $arr = $this->getProducts();
        $this->view->render($arr);
    }




    private function save_ses_cat_id()
    {


        //? если зашли в 1 раз на страницу и сессии нет а кат-ид есть
        if (isset($_GET['cat_id']) and !isset($_SESSION['cat_id'])) {

            $_SESSION['cat_id'] = $_GET['cat_id'];
            header('location: ' . $_SERVER['REDIRECT_URL']);

            //! ЕСЛИ ЗАШЛИ НЕ В 1 РАЗ И СЕССИЯ ЕСТЬ ТО НУЖНО ПРОВЕРИТЬ ЧТО, ТО ЧТО В СЕССИИ СОВП ИЛИ НЕТ С GET;

        } else if (isset($_GET['cat_id']) and isset($_SESSION['cat_id']) and ($_SESSION['cat_id'] == $_GET['cat_id'])) {

            header('location: ' . $_SERVER['REDIRECT_URL']);


            //todo б) если  GET и SESSION различные то (работа с одеждой т.к. id теперь не 1 а 2 и поэтому нужно перезаписывать сессию )
        } else if (isset($_GET['cat_id']) and isset($_SESSION['cat_id']) and ($_SESSION['cat_id'] != $_GET['cat_id'])) {
            $_SESSION['cat_id'] = $_GET['cat_id'];
            header('location: ' . $_SERVER['REDIRECT_URL']);
        }
    }

    private function getProducts()
    {
        //todo ПРОВЕРКА ДЛЯ ПАГИНАЦИИ
        // если есть страница на которую мы щелкнули


        if (isset($_GET['page'])) {
            $cur_page = $_GET['page'];
        } else {
            $cur_page = 1;
        }

        $param_value = $_SESSION['cat_id'];
        $param_name = 'catalogue_id';
        $count_on_page = 3;


        $arr = $this->model->getProducts($param_name, $param_value, $cur_page, $count_on_page);
        $arr['count_on_page'] = $count_on_page;
        $arr['cur_page'] = $cur_page;
        return $arr;
    }

    //todo оформляем заказ (lesson 68 45:14)

    public function checkoutAction()
    {
        $products_arr = $_POST['products'];
        $res = $this->model->checkout($_SESSION['auth']['id'], $products_arr);
        echo json_encode($res);
    }





    // todo метод удаления из корзины (lesson 67)
    public function delete_from_cartAction()
    {
        if ($this->isAjax()) {
            if (isset($_POST['product_id'])) {
                $res =  $this->model->delete_from_cart($_SESSION['auth']['id'], $_POST['product_id']);
                echo json_encode($res);
            } else {
                echo 'false';
            }
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }

    // todo метод добовления в корзину

    public function add_to_cartAction()
    {
        if ($this->isAjax()) {

            ////echo ($_POST['product_id'] . $_POST['count'] . ($_POST['price']));

            if (isset($_POST['product_id']) and isset($_POST['count']) and isset($_POST['price'])) {
                $res =  $this->model->addItemIntoCart($_SESSION['auth']['id'], $_POST['product_id'], $_POST['count'], $_POST['price']);
                echo json_encode($res);
            } else {
                echo 'false';
            }
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }
    public function get_client_cartAction() // без префикса Action запрос не сработает
    {
        if ($this->isAjax()) {
            // echo 'client cart';
            $client_id = $_POST['client_id']; // приняли id
            $res = $this->model->get_client_cart($client_id);
            echo json_encode($res);
        } else {
            echo "<img src='/public/images/errors/404.png' width='100%'>";
        }
    }
}
