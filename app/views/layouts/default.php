<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" crossorigin="anonymous"></script>

    <!-- integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" -->
    <!-- Compiled and minified JavaScript -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <title>Главная</title>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            /* justify-content: space-between; */
        }

        main {
            flex: 1 0 auto;
        }

        /* modal style стили перенесли из product_inc т.к. defoult - это главный файл и к нему подключаем product_inc*/

        .pagination li.active {
            background-color: blue;
        }

        input[type="number"] {
            display: inline-block !important;
            width: 50px !important;
            font-weight: bold !important;
            border: none !important;
        }

        input[type="number"]:focus,
        input[type="number"]:active,
        input[type="number"]:after,
        input[type="number"]::before {
            border: none !important;
            outline: none !important;
        }

        /* -------------------- */

        .active-auth-btn {
            cursor: default;
            padding: 32px;
            line-height: 5px;
        }

        button.btn.active-auth-btn {
            background-color: #00bfa5 !important;

        }

        .default-fixed-action-btn {
            position: static;
        }

        .default-fixed-action-btn ul {
            position: relative;
            background-color: #eee;
            top: 0 !important;
            z-index: 10 !important;

        }

        .default-fixed-action-btn ul li {
            border-bottom: 1px solid lightblue;
            border-left: 1px solid lightblue;
            border-right: 1px solid lightblue;
            margin: 0 !important;


        }

        .default-fixed-action-btn ul li:hover {
            background-color: #ddd;
        }

        .default-fixed-action-btn .btn-floating {
            width: auto;
            box-shadow: none;
        }
    </style>


</head>

<body>

    <!-- saidbar -->

    <nav class="grey lighten-3 blue-text">
        <div class="container">
            <div class="nav-wrapper">
                <!-- ниже в <a href="/" в значение ставим / для того чтобы при клике на логотип пользователь переходил на главную страницу.-->
                <a href="/" class="brand-logo"><img src="/public/images/layout/logo.svg" width="150" alt="" style="vertical-align: middle"></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down ">
                    <li><a href="/" class="blue-text">Главная</a></li>

                    <!-- //todo здесь в href перед сущностью ставим / (означающий от корня) для того чтобы на сайте при повторном клике на ссылку меню хедера в пути не прописывалась сущность (как например catalogue/catalogue), а открывался например снова catalogue и не возникало ошибки т.е. выходим на выбор категории-->

                    <li><a href="/catalogue" class="blue-text">Каталог</a></li>
                    <li><a href="/contacts" class="blue-text">Контакты</a></li>
                    <!-- Управление изменением названия кнопки при авторизации -->
                    <?php if (isset($_SESSION['auth']) and !empty($_SESSION['auth'])) :  ?>
                        <!-- href="?do=exit" указываем GET параметр для активации кнопки выйти, после чего _GET'ом нужно поймать этот  exit и указать seesion_anset-->
                        <!-- <li><a href="?do=exit" class="blue-text modal-trigger">Выйти</a></li> -->
                        <li>

                            <div class="fixed-action-btn default-fixed-action-btn">
                                <a class=" btn-large transparent" style="box-shadow: none; margin-left:25px;">
                                    <i class="large material-icons blue-text ">person</i>
                                </a>
                                <ul>
                                    <li><a href="?do=exit" class="btn-floating transparent blue-text">Выход</a></li>
                                    <li><a href="#modal-cart" class="btn-floating transparent blue-text modal-trigger" id="user-cart">Корзина</a></li>
                                    <li><a class="btn-floating transparent blue-text">Личные данные</a></li>
                                    <li><label class="btn-floating transparent blue-text" style="cursor: default;"><?= $_SESSION['auth']['email'] ?></li>
                                </ul>
                            </div>
                        </li>
                    <?php else : ?>
                        <li><a href="#modal-auth" class="blue-text modal-trigger">Авторизация</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <!--  

    <div class="row">
        <form class="col s12">
            <div class="row">
                <div class="input-field col s6">
                    <input placeholder="Placeholder" id="first_name" type="text" class="validate">
                    <label for="first_name">First Name</label>
                </div>
                <div class="input-field col s6">
                    <input id="last_name" type="text" class="validate">
                    <label for="last_name">Last Name</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input disabled value="I am not editable" id="disabled" type="text" class="validate">
                    <label for="disabled">Disabled</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password" type="password" class="validate">
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="email" class="validate">
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    This is an inline input field:
                    <div class="input-field inline">
                        <input id="email_inline" type="email" class="validate">
                        <label for="email_inline">Email</label>
                        <span class="helper-text" data-error="wrong" data-success="right">Helper text</span>
                    </div>
                </div>
            </div>
        </form>
    </div>

-->


    <!-- Modal Trigger -->
    <!-- <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a> -->


    <!-- Modal Structure auth start -->
    <!-- делаем модалку универсальной убераем из div id="modal1" 1 -->
    <!-- здесь значение введанные в input как пароль так и имя передаются от сюда в базовый controller.php -->

    <!-- ---------------------------------- -->

    <div id="modal-wrapper">
        <div id="modal-auth" class="modal auth" style="width:400px">
            <div class="modal-content">
                <h4>Войти на сайт</h4>
                <div class="row">
                    <button class="btn teal darken-4 auth active-auth-btn">Авторизация</button>
                    <button class="btn teal teal darken-4  reg">Регистрация</button>
                </div>
                <form class="col s12" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
                            <!-- //! готовую валидацию для более профессионального решения можно взять из jquery validate -->
                            <!-- //todo ВАРИАНТ №1 ----  используем валидацию полей от matirialize со своей корректировкой -->
                            <!-- в  pattern пишем валидацию, если указать pattern с пустыми ковычками он будет работать не верно т.к. станет считать что там есть регулярное выражение кот означает что не одного знака не должно быть введено в поле. ru, com это доменная зона. validate это обязательный класс matirialize, type="text" а не email т.к. берем процесс валидации на себя, <span class="helper-text" взят из matirialize для вывода сообщений под полем ввода пароля (&#10004; это спец символы html). -->
                            <input id="email" type="text" class="validate" name="email" pattern="^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$">
                            <label for="email">E-mail</label>
                            <span class="helper-text" data-error="Введен не корректный e-mail" data-success="&#10004;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <!-- в pattern изначально было /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g убрали (/ и флаг /g т.к. не нужет глобальный поиск, нужет поиск применительно только этой строке), {4,9} количество символов в пароле от 4 до 9, в их числе могут быть цыфры, буквы верх и нижн регистра [0-9a-zA-Z]  -->

                            <input id="password" type="text" class="validate" name="password" pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{4,9}">
                            <label for="password">Пароль</label>
                            <span class="helper-text" data-error="Пароль должен содержать цыфры, латинские буквы в вверхнем и нижнем регистре." data-success="&#10004;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="submit" class="btn orange" value="Вход">

                        </div>
                    </div>


                </form>


            </div>

        </div>
    </div>
    <!-- ---------------------------------- -->
    <!-- Modal Structure auth end -->

    <!-- ------------------------------------------------------- -->
    <!-- Modal Structure cart start //! здесь ошибка по видео lesson 67 2:13:17 ошибка в id тегов div & h4, в моем проекте работает с имеющемися указанными id-->
    <div id="modal-cart" class="modal">
        <div class="modal-content">
            <h4 id="modal-cart-content">Корзина</h4>

            <div id="modal-products-content" class="modal-client-cart">
            </div>

        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-orange btn orange">ЗАКРЫТЬ</a>
            <a href="#!" class="modal-close waves-effect waves-orange btn orange checkout">Оформить заказ</a>
        </div>
    </div>

    <!-- Modal Structure cart end-->
    <!-- --------------------------------------------------- -->
    <!-- ----------------------------------- -->
    <!-- Modal Structure reg start -->

    <!-- <div id="modal-auth" class="modal reg" style="width:500px">
        <div class="modal-content">
            <h4>Зарегистрироваться</h4>
            <div class="row">
                <button class="btn teal darken-4 auth ">Авторизация</button>
                <button class="btn teal teal darken-4 active-auth-btn reg">Регистрация</button>
            </div>
            <form class="col s12" method="POST">
                <div class="row">
                    <div class="input-field col s12">

                        <input id="email" type="text" class="validate" name="email" pattern="^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$">
                        <label for="email">E-mail</label>
                        <span class="helper-text" data-error="Введен не корректный e-mail" data-success="&#10004;"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="text" class="validate" name="password" pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{4,9}">
                        <label for="password">Пароль</label>
                        <span class="helper-text" data-error="Пароль должен содержать цыфры, латинские буквы в вверхнем и нижнем регистре." data-success="&#10004;"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="checkbox" name="agree" value="true">
                        <label for="password">Пароль</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label>
                            <input type="checkbox">
                            <span>Согласен на обработку персональных данных</span>
                        </label>
                        <div class="row">

                        </div>
                    </div>
                    <div class="input-field col s12">
                        <input type="submit" class="btn orange" value="Зарегистрироваться">
                    </div>
                </div>
            </form>
        </div>
    </div> -->

    <!-- Modal Structure reg end -->
    <!-- ----------------------------------- -->


    <!-- content -->
    <main>
        <?php
        echo $content;
        ?>
    </main>


    <!-- footer -->

    <footer class="page-footer  grey lighten-3 black-text">
        <div class="container ">
            <div class="row ">
                <div class="col l6 s12">
                    <h5 class="black-text">Footer Content</h5>
                    <p class="black-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
                </div>
                <div class="col l4 offset-l2 s12">
                    <h5 class="black-text">Links</h5>
                    <ul id="nav-mobile" class="right hide-on-med-and-down ">
                        <li><a href="/" class="black-text">Главная</a></li>
                        <li><a href="catalogue" class="black-text">Каталог</a></li>
                        <li><a href="contacts" class="black-text">Контакты</a></li>
                        <li><a href="collapsible.html" class="black-text">Личный кабинет</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container black-text">
                © 2014 Copyright Text
                <a class="black-text right" href="#!">More Links</a>
            </div>
        </div>
    </footer>

    <script src="/public/scripts/modal.js"></script>
    <script src="/public/scripts/floating_button.js"></script>



    <!-- //!todo ВАРИАНТ №2 ------   пишем скрипт для валидации полей авторизации самостоятельно -->
    <!--  
    <script>
        // pattern="^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2.6}$">

        // blur вместо focus т.е событие срабатывает после расфокусировки
        $('#email').on('blur', function() {
            const res = ($('#email').val().match(/^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/)); // здесь в // (ограничителях рег.выр. вставляем шаблон по которому будем проверять что ввел пользователь), val() исп. т.к. берем данные из input, если совподение будет найдено то оно запишется в массив.
            if (res == null) {
                $(this).after(' <span style="color:red"> error </span>');
            }
        //})
    </script>
-->

    <script>
        $('.checkout').on('click', function(e) {
            e.preventDefault();
            const cardsArr = ($('.modal-client-cart .card')); //необходимо взять все имеющееся карточки только в модальном окне с заказами. через console.log получим html коллекцию т.к. исп jQuery. исп преведение к нормальному массиву или использовать специальный цикл для переборки библиотеки
            let dataArr = []; // создаем массив объектов в который после цикла each попадут данные по id & value в связке ключ значение
            cardsArr.each(function() {
                let productId = ($(this).find('input[data-id]').data('id')); // передавая в log $this в devtools получим div.card каждого товара находящегося в модальном окне.
                let count = ($(this).find('input[name="amout"]').val()); // получим общее кол-во товара нашей карзины

                // по 1 готовому элементу добовляет в вышеуказанный пустой массив let dataArr = [];
                dataArr.push({
                    productId: productId,
                    count: count
                })
            })

            //console.log(dataArr); // увидим сформированный массив объектов с id товара и его количеством


            const productId = $(this).closest('.card-image').find('input[data-id]').data('id');

            $.ajax({
                method: 'post',
                url: "/catalogue/checkout",
                data: {
                    products: dataArr // здесь отправляем массив сформированных объектов dataArr и передаем в формате ключ : занчение
                }
            }).done(function(resp) {

                if (resp == 'false') {
                    alert('Произошла ошибка. Попробуйте позже!');
                } else {

                    const products = JSON.parse(resp);
                    console.log(products);
                    //             var productCard = '';

                    //             products.forEach(product => {
                    //                 productCard += `
                    // <div class="row">
                    //     <div class="col s12">
                    //         <div class="card">
                    //             <div class="card-image" style="postition:relative">
                    //                 <img class="modal-product-image" src="${product.image}" style="width:180px;display:inline-block">
                    //                  <p class="" style="vertical-align:top; display:inline-block; width:400px; vertical-align: top;">
                    //                  <input type="text" data-id="${product.id}" hiden>
                    //                  Товар: <span class="modal-product-name">${product.name}</span>
                    //                  <br>
                    //                 Количество: <span class=""><input type="number" name="amout" value="${product.count}" min="1"> </span>
                    //                 <br>
                    //                 Цена: <span class="modal-product-price"> ${product.price} ₽ </span>
                    //                 </p>
                    //                  <a href="" class="waves-effect waves-red btn  red delete-product" style="position:absolute;bottom:10px;right:10px">Удалить товар</a>
                    //             </div>
                    //         </div>
                    //     </div>
                    // </div> `
                    //             });

                    //             $('.modal-client-cart').html(productCard);
                }
            })
            // ----------------------------
        });


        // здесь исп добавленный класс .modal-client-cart в 2 модальный окна в файлах product_inc & default
        $('.modal-client-cart').on('click', '.delete-product', function(e) {
            e.preventDefault();
            const productId = ($(this).closest('.card-image').find('input[data-id]').data('id')); //при нажатии на кнопку подхватываем скрытый input c data-id (см. devtools) т.е. (id который принадлежит товару по записи в БД) в поле которого инициировали нажатие кнопки удалить товар.

            $.ajax({
                method: 'post',
                url: "/catalogue/delete_from_cart", // нужно завести метод в каталоге-контроллере
                data: {
                    product_id: productId // это const productId, теперь в maincontroller можем посмотреть product_id
                }
            }).done(function(resp) {
                //console.log(resp); // выводит текст указанный в методе в CatalogueController в echo (используем для поверки работы с методом)
                if (resp == 'false') {
                    alert('Произошла ошибка. Попробуйте позже!');
                } else {

                    const products = JSON.parse(resp);
                    var productCard = '';
                    // здесь так же должен быть указан <input type="text" data-id="${product.id}" hiden> так после удаления нужно перерисовать карзину с изменениями т.е. на 1 товар меньше.
                    products.forEach(product => {
                        productCard += `
                    <div class="row">
                         <div class="col s12">
                             <div class="card">
                                 <div class="card-image" style="postition:relative">
                                    <img class="modal-product-image" src="${product.image}" style="width:180px;display:inline-block">
                                     <p class="" style="vertical-align:top; display:inline-block; width:400px; vertical-align: top;">
                                     <input type="text" data-id="${product.id}" hiden>
                                     Товар: <span class="modal-product-name">${product.name}</span>
                                     <br>
                                    Количество: <span class=""><input type="number" name="amout" value="${product.count}" min="1"> </span>
                                    <br>
                                    Цена: <span class="modal-product-price"> ${product.price} ₽ </span>
                                    </p>
                                     <a href="" class="waves-effect waves-red btn  red delete-product" style="position:absolute;bottom:10px;right:10px">Удалить товар</a>
                                </div>
                                </div>
                                </div>
                                </div> `
                    });

                    $('.modal-client-cart').html(productCard); //поставили модальным окнам 1 единый класс что бы не связываться с разными id


                    //$('#modal-cart-content').html(productCard);
                    //$('#modal-products-content-a').html(productCard); // добавили чтобы удалять из основного окна при нажатии на добавить в корзину
                }

            })

        });

        $('#user-cart').on('click', function() {
            const clientId = <?= $_SESSION['auth']['id']; ?>;
            // console.log(clientId);
            if (clientId) {
                $.ajax({
                    method: 'post',
                    url: "/catalogue/get_client_cart", //get_client_cart необходимо добавить а маршруты rutes и метод описать в модели catalogue
                    data: {
                        client_id: clientId,
                    }
                }).done(function(resp) //если done придет ответ и через console.log(resp) увидим что написали в CatalogueController
                    {
                        //console.log(resp);
                        const products = JSON.parse(resp);
                        var productCard = '';
                        //console.log(product); // выводит товары находящееся в БД у зарегистрированного пользователя
                        products.forEach(product => {
                            productCard += `
                            <div class="row">
                            <div class="col s12">
                             <div class="card">
                             <div class="card-image" style="postition:relative">
                             <img class="modal-product-image" src="${product.image}" style="width:180px;display:inline-block">
                             <p class="" style="vertical-align:top; display:inline-block; width:400px; vertical-align: top;">
                             <input type="text" data-id="${product.id}" hiden>
                             Товар: <span class="modal-product-name">${product.name}</span>
                             <br>
                                    Количество: <span class=""><input type="number" name="amout" value="${product.count}" min="1"> </span>
                                    <br>
                                     Цена: <span class="modal-product-price"> ${product.price} ₽ </span>
                                     </p>
                                     <a href="" class="waves-effect waves-red btn  red delete-product" style="position:absolute;bottom:10px;right:10px">Удалить товар</a>
                                </div>
                            </div>
                         </div>
                    </div> `
                        });
                        $('#modal-cart-content').html(productCard);

                    })
            } else {
                alert('Client not found');
            }
        });

        $('#modal-auth').on('click', '.auth,.reg', function() {
            // console.log($(this).text());// выведет в консоле название кнопки
            // console.log($(this).hasClass('active-auth-btn'));
            if ($(this).hasClass('active-auth-btn') == false) {
                if ($(this).hasClass('reg')) {
                    $('.auth').removeClass('active-auth-btn');
                    $(this).addClass('active-auth-btn');
                    const markup = modalStracture('reg');
                    // console.log($('#modal-wrapper'));
                    $('#modal-auth.modal-content').empty();
                    $('#modal-auth.modal-content').append(markup);
                } else {
                    $('.reg').removeClass('active-auth-btn');
                    $(this).addClass('active-auth-btn');
                    const markup = modalStracture('auth');
                    $('#modal-auth.modal-content').empty();
                    $('#modal-auth.modal-content').append(markup);
                    // console.log($('#modal-wrapper'));


                }
            } // если этот элемент с класс

        })

        function modalStracture(clazz) {
            if (clazz == 'reg') {
                return `
        
                <h4>Зарегистрироваться</h4>
                <div class="row">
                    <button class="btn teal darken-4 auth ">Авторизация</button>
                    <button class="btn teal teal darken-4 active-auth-btn reg">Регистрация</button>
                </div>

                <form class="col s12" method="POST">
                    <div class="row">
                        <div class="input-field col s12">
    
                            <input id="email" type="text" class="validate" name="email" pattern="^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$">
                            <label for="email">E-mail</label>
                            <span class="helper-text" data-error="Введен не корректный e-mail" data-success="&#10004;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="text" class="validate" name="password" pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{4,9}">
                            <label for="password">Пароль</label>
                            <span class="helper-text" data-error="Пароль должен содержать цыфры, латинские буквы в вверхнем и нижнем регистре." data-success="&#10004;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="checkbox" name="agree" value="true">
                            <label for="password">Пароль</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label>
                                <input type="checkbox">
                                <span>Согласен на обработку персональных данных</span>
                            </label>
                            <div class="row">
    
                            </div>
                        </div>
                        <div class="input-field col s12">
                            <input type="submit" class="btn orange" value="Зарегистрироваться">
                        </div>
                    </div>
                </form> `
            } else {
                return `
            <h4>Войти на сайт</h4>
            <div class="row">
                <button class="btn teal darken-4 auth active-auth-btn">Авторизация</button>
                <button class="btn teal teal darken-4  reg">Регистрация</button>
            </div>

            <form class="col s12" method="POST">
                <div class="row">
                    <div class="input-field col s12">     
                        <input id="email" type="text" class="validate" name="email" pattern="^([A-Za-z0-9_-]+\.)*[A-Za-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$">
                         <label for="email">E-mail</label>
                        <span class="helper-text" data-error="Введен не корректный e-mail" data-success="&#10004;"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" type="text" class="validate" name="password" pattern="(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{4,9}">
                        <label for="password">Пароль</label>
                        <span class="helper-text" data-error="Пароль должен содержать цыфры, латинские буквы в вверхнем и нижнем регистре." data-success="&#10004;"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="submit" class="btn orange" value="Вход">
                    </div>
                </div>
            </form> `
            }
        }
    </script>

</body>

</html>


<!-- Modal Structure auth start -->
<!-- делаем модалку универсальной убераем из div id="modal1" 1 -->
<!-- здесь значение введанные в input как пароль так и имя передаются от сюда в базовый controller.php -->


<!-- <div id="modal-auth" class="modal auth" style="width:400px">
        <div class="modal-content"> -->

<!-- //! готовую валидацию для более профессионального решения можно взять из jquery validate -->

<!-- //todo ВАРИАНТ №1 ----  используем валидацию полей от matirialize со своей корректировкой -->
<!-- в  pattern пишем валидацию, если указать pattern с пустыми ковычками он будет работать не верно т.к. станет считать что там есть регулярное выражение кот означает что не одного знака не должно быть введено в поле. ru, com это доменная зона. validate это обязательный класс matirialize, type="text" а не email т.к. берем процесс валидации на себя, <span class="helper-text" взят из matirialize для вывода сообщений под полем ввода пароля (&#10004; это спец символы html). -->

<!-- в pattern изначально было /(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/g убрали (/ и флаг /g т.к. не нужет глобальный поиск, нужет поиск применительно только этой строке), {4,9} количество символов в пароле от 4 до 9, в их числе могут быть цыфры, буквы верх и нижн регистра [0-9a-zA-Z]  -->