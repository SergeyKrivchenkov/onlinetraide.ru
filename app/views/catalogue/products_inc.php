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
        <?php $class = ($cur_page == $i) ? ' active' : '';
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
    $('.cards').on('click', '.modal-trigger', function() {


        let productImage = $(this).closest('.card').find('.product-image').attr('src'); // получаем путь до картинки 
        let productName = $(this).closest('.card').find('.product-name').text(); // имя продукта текстом
        let productPrice = $(this).closest('.card').find('.product-price').text();
        // console.log(productPrice.replace(' ₽', ''));
        let productId = $(this).data('id');


        //todo работа ajax проверяется при клике в карточке товара на кнопку купить
        if (<?= (isset($_SESSION['auth']['id']) and !empty($_SESSION['auth']['id'])) ? $_SESSION['auth']['id'] : 'false'; ?>) {
            $.ajax({
                method: 'post',
                url: "/catalogue/add_to_cart",
                data: {
                    product_id: productId,
                    count: 1,
                    price: productPrice.replace(' ₽', '')
                }

            }).done(function(resp) {

                if (resp == 'false') {
                    alert('Произошла ошибка. Попробуйте позже!');
                } else {

                    var products = JSON.parse(resp);

                    var productCard = '';
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


            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems, 'open');


            var instance = M.Modal.getInstance(document.querySelector('#modal-products'));
            instance.destroy();

            instance = M.Modal.getInstance(document.querySelector('#modal-auth'));
            $('#modal-auth h4').html('<label style="font-size:20px;color:#f44336">Для добовления товара в корзину необходимо авторизироваться.</label>');
            instance.open();
        };
    })
    // --------------добовление в карзину товара с ajax---
</script>