<?php require APP_ROOT . '/views/inc/header.php'; ?>
<script>
    openModal = <?= $data['modal']['open']; ?>;
    currentUserId = <?= $data['modal']['user_id']; ?>;
</script>
<div class="membership-table">
    <div class="container">
        <div class="flash-container mt-3">
            <?= flash('membership_assignment'); ?>
        </div>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Membership</th>
                <th>Expiry Date</th>
            </tr>
            <?php foreach($data['members'] as $row): ?>
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
