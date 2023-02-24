<?php require APP_ROOT . '/views/inc/header.php'; ?>
<div class="membership-table">
    <div class="container">
        <div class="flash-container mt-3">
            <?= flash('membership_assignment'); ?>
            <?= flash('membership_revoked'); ?>
        </div>
        <table>
            <tr>
                <th>Name</th>
                <th class="d-none d-lg-table-cell">Email</th>
                <th class="d-none d-lg-table-cell">Phone Number</th>
                <th>Membership</th>
                <th>Expiry Date</th>
                <th>Active</th>
            </tr>
            <?php foreach($data['members'] as $row): ?>
                <?php
                    $membershipStatus = getMembershipStatus($row['start_date'], $row['expiry_date']);
                    $statusHtmlClass;
                    switch ($membershipStatus) {
                        case 'active' :
                            $statusHtmlClass = 'text-success';
                            break;
                        case 'future' :
                            $statusHtmlClass = 'text-info';
                            break;
                        case 'expired' :
                            $statusHtmlClass = 'text-danger';
                            break;
                        default :
                            $statusHtmlClass = 'text-warning';
                    }
                    $membershipOutput = $membershipStatus === 'active' ?  $row['term_display_name'] : ucfirst($membershipStatus);
                ?>
                <tr class="border-bottom border-top account-link">
                    <td>
                        <div class="img-container"><img src="<?= $row['img_url']; ?>"></div>
                        <?= $row['name']; ?>
                    </td>
                    <td class="d-none d-lg-table-cell"><?= $row['email']; ?></td>
                    <td class="d-none d-lg-table-cell"><?= $row['phone_number']; ?></td>
                    <td class="<?= $statusHtmlClass; ?>"><?= $membershipOutput?></td>
                    <td class="<?= $statusHtmlClass; ?>"><?= formatForOutput($row['expiry_date']); ?></td>
                    <td class="id" style="display: none;"><?= $row['user_id']; ?></td>
                    <td class="fs-4 <?= $membershipStatus === 'active' ? 'text-success': 'text-danger'; ?>">
                        <?= $membershipStatus === 'active' ? GRANTED_ACCESS_ICON : NO_ENTRY_ICON; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
