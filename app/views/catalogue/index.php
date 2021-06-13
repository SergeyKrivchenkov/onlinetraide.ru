<ul class="collection">
    <!-- // todo используем метод с версткой одной лишки чтобы вторая формировалась автоматически!  здесь key не нужен т.к. в моссиве $data необходимость текстовых значениях где $cat это категории -->
    <?php foreach ($data as $cat) :
    ?>
        <li class="collection-item avatar">
            <!-- <i class="material-icons ">computer</i> -->
            <!--//! в БД (по проекту catalogue в поле images) при указании пути рекомендуется прописать / как указания пути картинки от корня т.к. может быть, что не покажет картинку браузер т.к. может увидеть путь по примеру http://onlinetraide.rupublic/images/catalogue/comps.png где домен не разграничен с каталогом public-->
            <img src="<?= $cat['image']; ?>" class="circle">
            <!-- здесь в а href указываем GET параметр кот. называется cat_id и ему через php присваемае id. это можно просмотреть в левом нижнем углу при hover на название ?cat_id=<?= $cat['id']; ?>-->
            <!-- в а добовляем дополнительный атрибут для перехода на стр при помощи JS -->
            <a href="" class="black-text show-products" data-id="<?= $cat['id']; ?>" data-path="<?= $cat['path']; ?>"><b class="title"><?= $cat['name']; ?></b></a>
            <p>
                Количество товара: <?= $cat['count']; ?>
            </p>
            <!-- здесь в <a href="catalogue/computers добовляем get параметр что бы по id понимать на какую категорию товаров был осущетсвлен клик -->
            <a href="" class="secondary-content show-products" data-id="<?= $cat['id']; ?>" data-path="<?= $cat['path']; ?>"><i class="material-icons">grade</i></a>
        </li>
    <?php endforeach; ?>
</ul>

<?php
// debug($data);
//< class="title"><?= $cat['name']; это ссылка на название категории
?>
<!-- пишем событие -->
<script>
    $('.collection').on('click', '.show-products', function(e) {
        e.preventDefault();
        const catId = $(this).data('id');
        const path = $(this).data('path');
        // console.log(catId);
        location.replace(`catalogue/${path}?cat_id=${catId}`); // футкция перезагрузки на указанною стр.
    })
</script>