<ul class="collection">
    <!-- // todo используем метод с версткой одной лишки чтобы вторая формировалась автоматически!  здесь key не нужен т.к. в моссиве $data необходимость текстовых значениях где $cat это категории -->
    <?php foreach ($data as $cat) :
    ?>
        <li class="collection-item avatar">

            <img src="<?= $cat['image']; ?>" class="circle">

            <a href="" class="black-text show-products" data-id="<?= $cat['id']; ?>" data-path="<?= $cat['path']; ?>"><b class="title"><?= $cat['name']; ?></b></a>
            <p>
                Количество товара: <?= $cat['count']; ?>
            </p>

            <a href="" class="secondary-content show-products" data-id="<?= $cat['id']; ?>" data-path="<?= $cat['path']; ?>"><i class="material-icons">grade</i></a>
        </li>
    <?php endforeach; ?>
</ul>



<script>
    $('.collection').on('click', '.show-products', function(e) {
        e.preventDefault();
        const catId = $(this).data('id');
        const path = $(this).data('path');
        // console.log(catId);
        location.replace(`catalogue/${path}?cat_id=${catId}`);
    })
</script>