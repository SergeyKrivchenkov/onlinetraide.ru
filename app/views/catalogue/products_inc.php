<?php
//todo покажет что попадаем с этим массивом продукстов
//debug($data); // покажет 2 элементна, это массив продуктов которые мы отрисовываем при помощи ПАГИНАТОРА, и коллество элеметнов всего в БД донном разделе (например количество компьютеров)

$products = $data['products']; // как бы распарсили массив
$count_products = $data['count']; // сюда запишется кол-во товаров 10
$count_on_page = $data['count_on_page'];
$cour_page = $data['cur_page'];
$count_pages = ceil($count_products / $count_on_page); // узнаем кол-во товаров т.е. кол-во товаров делим на кол-во товара на стр.

?>

<!-- // количетво товаров на странице -->

<?php
/*
$cur_page = 1; // переменная обозначающая текущею стр. где cur это current
$count_products = 10; // кол-во в БД
$cout_products_per_page = 2;
$count_pages = ceil($count_products / 2) // где ceil округление в большею сторону, floor в меньшею сторону? count_products кол-во всего стр.

// debug($data);
*/
?>


<!-- библиотекa material ui -->
<!-- библиотекa muicss -->
<!-- библиотекa material-framework -->
<!-- выводим <div class="row"> за пределы метода чтоюы записи производились в один ряд и браузер  сам регулировая кол-во карточек в ряду-->

<!-- карточки товаров -->

<div class="row cards">
    <!-- здесь  $data это массив-->
    <?php foreach ($products as $product) : ?>
        <!-- <div class="col s6 m4"> здесь s6 это на маленьких усройствах каждый товар будет занимать половину экрана(т.к. весь экран это 12 ) в materialize в grid расписано-->
        <!-- <div class="col s6 l4 xl2"> -->
        <div class="col" style="width: 300px;">
            <div class="card" style="padding-top:20px">
                <div class="card-image" style=" height: 200px;">
                    <!-- / необходимо подставить в БД а не в <img src="/-->
                    <img class="product-image" src="<?= $product['image'] ?>" style="width:180px; margin:0 auto">
                </div>
                <div class="card-content" style="height: 140px">
                    <span class="card-title product-name"><?= $product['name'] ?></span>
                </div>
                <div class="card-action">
                    <p class="card-title product-price" style="font-weight:bold"><?= $product['price'] . ' ₽' ?></p>
                    <!-- здесь указываем модалку ссылкой и дополнительно указаываем id чтобы при клике на товар модальное окно понимало на каком товаре кликнули дополнительно пропивываем data-id=... -->
                    <a href="#modal-products" class="btn amber darken-3 modal-trigger" data-id="<?= $product['id'] ?>">Добавить в корзину</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<!-- Modal Trigger -->
<!-- <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a> -->

<!-- Modal Structure -->
<!-- делаем модалку универсальной убераем из div id="modal1" 1 -->
<div id="modal-products" class="modal">
    <div class="modal-content">
        <h4 id="modal-products-name">Корзина покупок</h4>

        <!-- //!коментим эту верстку так как она отрабатывается ниже через foreach. Если этого не сделать то в корзине будет постоянно показываться 1 лишний товар по сравнению с кол-вом его в БД -->
        <!-- <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-image">
                        <img class="modal-product-image" src="" style="width:180px;display:inline-block">
                        <p class="" style="vertical-align:top; display:inline-block; width:400px; vertical-align: top;">
                            Товар: <span class="modal-product-name"><?= $product['name'] ?> </span>
                            <br>
                            Количество: <span class=""><input type="number" name="amout" value=""> </span>
                            <br>
                            Цена: <span class="modal-product-price"> <?= $product['price'] . ' ₽' ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div> -->

        <div id="modal-products-content-a" class="modal-client-cart">
        </div>


    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-orange btn orange">ЗАКРЫТЬ</a>
        <a href="#!" class="modal-close waves-effect waves-orange btn orange checkout">Оформить заказ</a>
    </div>
</div>

<!-- pagination П А Г И Н А Т О Р-->

<ul class="pagination ">
    <li class="disabled "><a href="#!"><i class="material-icons">chevron_left</i></a></li>


    <?php for ($i = 1; $i <= $count_pages; $i++) : // здесь не забываем о знаке : после оператора вместо скобки
    ?>
        <?php $class = ($cur_page == $i) ? ' active' : ''; // здесь если $cur_page (т.е. текущая стр.) совпала с $i (счетчиком) это означаем что данной li которая совподаем с тек стр. добовляем класс active с пробелом, а иначе в переменную класс запишется ничего т.е ''. Пишем это в теле оператора для того чтобы автоматизировать присвоение класса active в имеющееся li
        ?>

        <!-- здесь вставляем $i из оператора for для автоматизации нумерации пагинатора-->
        <li class="waves-effect <?= $class ?>"><a href="?page=<?= $i ?>"><?= $i ?></a></li>

    <?php endfor; ?>
    <!-- <li class="active"><a href="?page=1">1</a></li> -->


    <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
</ul>

<!-- подключаем внешний скрипт для модального окна на карточках товаров -->
<!-- <script src="/public/scripts/modal.js"></script> -->

<script>
    // todo код по модальному окну перенесли в папку со скриптами
    // document.addEventListener('DOMContentLoaded', function() {
    //     var elems = document.querySelectorAll('.modal');
    //     var instances = M.Modal.init(elems, 'open');
    // });
    // ---------------------------------------

    //  ------готовим модальное окно к действии вместе с карточками товаров.----------

    //console.log($('li'));// выдает все лишки

    // вешаем событие для работы jQuery с кнопкой купить (теперь понимаем на какую конкретно кнопку купить нажали)
    $('.cards').on('click', '.modal-trigger', function() {
        // console.log($(this).closest('.card').find('.product-image'));

        let productImage = $(this).closest('.card').find('.product-image').attr('src'); // получаем путь до картинки 
        let productName = $(this).closest('.card').find('.product-name').text(); // имя продукта текстом
        let productPrice = $(this).closest('.card').find('.product-price').text();
        // console.log(productPrice.replace(' ₽', ''));
        let productId = $(this).data('id');

        // пишем что и куда вывести без перезагрузки стр

        // $('.modal-product-image').attr('src', productImage);
        // $('.modal-product-name').text(productName);
        // $('.modal-product-price').text(productPrice);

        // console.log(productImage, productName, productPrice, productId);
        //todo работа ajax проверяется при клике в карточке товара на кнопку купить
        if (<?= (isset($_SESSION['auth']['id']) and !empty($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : 'false'; ?>) {
            $.ajax({
                method: 'post',
                url: "/catalogue/add_to_cart", // стр ответственная за обработку инф кот отправляем т.е создаем php стр в кот созд сценарий как должна обрабатываться информ кот будет от сюда посылать.
                data: {
                    product_id: productId, // product_id это название столбца табл из БД, productId это переменная из скрипта выше с присвоиным значением 
                    count: 1, // 1 по дефолту ставим
                    price: productPrice.replace(' ₽', '') // обрезаем знак валюты и лишний пробел перед знаком Р для записи в БД только чисел
                }
                // context: document.body
                // beforeSend: function() {
                //     alert('Start sending'); // действие непосредственно перед отправкой запроса
                // }
            }).done(function(resp) {
                // в done садержаться промисы, resp - это ответ который будет дан от сервера, в теле находится функция кот сработает когда результат успешной выполнении работы то этому элементу добовляется класс done
                // $(this).addClass("done");
                // console.log(resp);
                if (resp == 'false') {
                    alert('Произошла ошибка. Попробуйте позже!');
                } else {
                    // console.log(resp);// выведет массив со всеми передавваемыми данными
                    var products = JSON.parse(resp);
                    //res смотреть из CatalogueController & Catalogue
                    //const products = jQuery.parseJSON(resp); //jQuery.parseJSON(resp) исп для преобразования ассоциативного массива в js объект
                    // console.log(products);// вывод js массива
                    var productCard = '';
                    //console.log(product); // выводит товары находящееся в БД у зарегистрированного пользователя
                    //! для удаления товара ниже используем скрытый тег input с атрибутом hiden до этого для видимости результата проставляли value="${product.id} в окончательном варианте его нет"
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
                        <a href="" class="waves-effect waves-red btn red delete-product" style="position:absolute;bottom:10px;right:10px" >Удалить товар</a>
                    </div>
                </div>
            </div>
        </div> `
                        // $('#modal-products-name').after(productCard);

                    });
                    $('#modal-products-content-a').html(productCard); // html в jQ а в js innerHTML. выносим за цикл так как сначала формируем карточки а только потом их выводим
                }
            });

        } else {

            //это скрипт для модального окна для вывода что пользователь не авторизован. Он скопирован с matirialize

            var elems = document.querySelectorAll('.modal'); // взять все классы .modal кот есть на странице и присвой переменной
            var instances = M.Modal.init(elems, 'open'); // M.Modal функция с matirialize. Инициализируется открывание модальных окон при щелчке

            // instance.open(); // запуск открытия модального окна при перезагрузке стр. эту фугкцию будем запускать в Controller ветке else.

            var instance = M.Modal.getInstance(document.querySelector('#modal-products')); // берем конкретное модальное окно которое показывает товар в корзине
            //$('#modal_product').modal();//запускает модальное окно
            instance.destroy(); //уничтожает модальное окно, а именно '#modal-products'

            // alert('Вы не авторизованны');
            // var instance = M.Modal.getInstance(document.querySelector('.modal'));
            instance = M.Modal.getInstance(document.querySelector('#modal-auth'));
            $('#modal-auth h4').html('<label style="font-size:20px;color:#f44336">Для добовления товара в корзину необходимо авторизироваться.</label>');
            instance.open();
        };
    })
    // --------------добовление в карзину товара с ajax---
</script>