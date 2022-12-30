<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="membership-table">
    <div class="container">
        <div class="flash-container mt-3">
            <?= flash('membership_assignment'); ?>
        </div>
        <table>
            <tr>
                <th>Name</th>
                <th class="d-none d-lg-table-cell">Email</th>
                <th class="d-none d-lg-table-cell">Phone Number</th>
                <th>Membership</th>
                <th>Expiry Date</th>
            </tr>
            <?php var_dump($data['members']); ?>
            <?php foreach($data['members'] as $row): ?>
                <tr class="border-bottom border-top account-link">
                    <td>
                        <div class="img-container"><img src="<?= $row['img_url']; ?>"></div>
                        <?= $row['name']; ?>
                    </td>
                    <td class="d-none d-lg-table-cell"><?= $row['email']; ?></td>
                    <td class="d-none d-lg-table-cell"><?= $row['phone_number']; ?></td>
                    <td><?= $row['term']; ?> <?= $row['term'] != 'custom' ? 'months' : ''; ?> </td>
                    <td><?= $row['expiry_date']; ?></td>
                    <td class="id" style="display: none;"><?= $row['id']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
