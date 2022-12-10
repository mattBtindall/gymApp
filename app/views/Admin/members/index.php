<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="membership-table">   
    <div class="container">
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Membership</th>
                <th>Expiry Date</th>
            </tr>
            <?php foreach($data as $row): ?>
                <tr class="border-bottom border-top account-link">
                    <td>
                        <div class="img-container"><img src="<?= $row['img_url']; ?>"></div>
                        <?= $row['name']; ?>
                    </td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['phone_number']; ?></td>
                    <td><?= $row['term']; ?></td>
                    <td><?= $row['expiry_date']; ?></td>
                    <td class="id" style="display: none;"><?= $row['id']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
