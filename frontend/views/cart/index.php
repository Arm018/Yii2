<?php

use yii\helpers\Html;

/** @var \common\models\Cart[] $cartItems */

$this->title = 'My Cart';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <form action="<?= \yii\helpers\Url::to(['cart/buy']) ?>" method="post">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <table class="table">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" id="select-all">
                </th>
                <th>Book Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selectedItems[]" value="<?= $item->book_id ?>" checked>
                    </td>
                    <td><?= Html::encode($item->book->title) ?></td>
                    <td class="book-price" data-price="<?= Html::encode($item->book->price) ?>">
                        $<?= Html::encode($item->book->price) ?>
                    </td>
                    <td>
                        <input type="number" name="quantity[<?= $item->book_id ?>]" class="form-control quantity-input" value="<?= Html::encode($item->quantity) ?>" min="1" style="width: 60px;">
                    </td>
                    <td class="total-price">
                        $<span><?= Html::encode($item->book->price * $item->quantity) ?></span>
                    </td>
                    <td>
                        <?= Html::a('Remove', ['cart/remove', 'id' => $item->id], [
                            'class' => 'btn btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => 'Are you sure you want to remove this item?'
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-group">
            <?= Html::submitButton('Buy Now', ['class' => 'btn btn-primary']) ?>
        </div>
    </form>
<?php endif; ?>

<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr');
            const price = parseFloat(row.querySelector('.book-price').dataset.price);
            const quantity = parseInt(this.value) || 0;
            const totalPrice = price * quantity;
            row.querySelector('.total-price span').textContent = totalPrice.toFixed(2);
        });
    });

    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="selectedItems[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
