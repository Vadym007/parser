
<div class="row">
    <a class="btn btn-primary btn-lg btn-block" href="/">Вернуться на главную</a>
</div>
<br>
<?php if(count($data)!=0): ?>
<table class="table table-bordered table-striped table-sm">
    <thead class="thead-dark">
        <tr class='text-center'>
            <th scope="col">#</th>
            <th scope="col">Название</th>
            <th scope="col">Артикул</th>
            <th scope="col">Цена</th>
            <th scope="col">Цена опт.</th>
            <th scope="col">Цвет</th>
<!--        <th scope="col">Availability</th>-->
            <th scope="col">Размер</th>
            <th scope="col">Описание</th>
            <th scope="col">Фото</th>
            <th scope="col">Ссылка</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $k => $item): ?>
            <tr>
                <th scope="row"><?= $k + 1 ?></th>
                <td><?= $item['name'] ?></td>
                <td><?= $item['code'] ?></td>                            
                <td><?= $item['price'] ?></td>
                <td><?= $item['wholesale_price'] ?></td>
                <td><?= $item['color'] ?></td>
<!--            <td><?= $item['availability'] ?></td>-->
                <td><?= $item['size'] ?></td>
                <td><?= !empty($item['description'])?$item['description']:'Описание отсутствует' ?></td>
                <td><img src="<?= $item['photo'] ?>" width="150px" alt="альтернативный текст"></td>
                <td><a target="_blank" href="<?= $item['url'] ?>"><?= $item['url'] ?></a></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<div class="row">
    <a class="btn btn-primary btn-lg btn-block" href="/">Вернуться на главную</a>
</div>
<?php else: ?>
<h2 class="text-center">Данные отсутствуют!</h2>
<?php endif; ?>
