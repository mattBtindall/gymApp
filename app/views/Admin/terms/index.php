
<?php
    // check to see if the term_update exists if it doesn't create it here
    if (!isset($data['term_update'])) {
        $data['term_update'] = [
            'display_name' => '',
            'term' => '',
            'term_multiplier' => '',
            'combined_term_multiplier' => '',
            'cost' => '',
            'term_id' => '',
            'display_name_err' => '',
            'cost_err' => ''
        ];
    }

    // created for ease of use when showing/hiding terms in the drop down
    $combinedTermMultipliers = [];
    foreach ($data['terms'] as $term) {
        $combinedTermMultipliers[] = $term['term_multiplier'] . ' ' . $term['term'];
    }

    // used to label edit buttons which are used in javascript as event aggregation is used
    $i = 0;
?>
<?php require APP_ROOT . '/views/inc/header.php'; ?>
    <h3 class="mt-3"> Terms </h3>
    <p class="mt-2">
        Add/edit your custom terms. You may edit one term at a time. Please take in mind that you can add 'custom' memberships of varying lengths when assigning users memberships.
    </p>
    <span href="" class="btn btn-success add-term mb-3">Add</span>
    <?= flash('term_deleted'); ?>
    <?= flash('term_updated'); ?>
    <table class="terms-table">
        <tr>
            <th>Display name</th>
            <th>Term</th>
            <th>Cost (£)</th>
            <th>Date created</th>
        </tr>
        <?php foreach($data['terms'] as $term): ?>
            <?php
                // format the expiry date to d/m/y
                $dateCreated= date_create_from_format(SQL_DATE_TIME_FORMAT, $term['created_at']);
                $dateCreated = $dateCreated->format(OUTPUT_DATE_TIME_FORMAT);

                $combinedTermMultiplier = $term['term_multiplier'] . ' ' . $term['term'];
                $displayName = $term['display_name'];
                $cost = $term['cost'];
                $errClassName = [
                    'display_name' => '',
                    'cost' => ''
                ];

                if ($data['term_update']['term_id'] == $term['id']) {
                    $displayName = $data['term_update']['display_name'];
                    $cost = $data['term_update']['cost'];
                    $errClassName['display_name'] = !empty($data['term_update']['display_name_err']) ? 'is-invalid' : '';
                    $errClassName['cost'] = !empty($data['term_update']['cost_err']) ? 'is-invalid' : '';
                    $combinedTermMultiplier = $data['term_update']['combined_term_multiplier'];
                }
            ?>
            <tr>
                <form action="<?= URL_ROOT; ?>/terms/edit" method="POST">
                    <td>
                        <input type="text" value="<?= $displayName; ?>" class="form-control form-control-lg w-50 terms-edit__display-name <?= $errClassName['display_name']; ?>" name="display_name" disabled data-term-number="<?= $i; ?>">
                        <span class="invalid-feedback"><?= $data['term_update']['display_name_err']; ?></span>
                    </td>
                    <td>
                        <select class="form-control form-control-lg terms-edit__drop-down" name="term" data-term-number="<?= $i; ?>">
                            <option value="please_select">Please select</option>
                            <option value ="1 week" <?= $combinedTermMultiplier == '1 week' ? 'selected' : ''; ?>>1 Week</option>
                            <option value ="2 week" <?= $combinedTermMultiplier == '2 week' ? 'selected' : ''; ?>>2 Weeks</option>
                            <option value ="1 month" <?= $combinedTermMultiplier == '1 month' ? 'selected' : ''; ?>>1 month</option>
                            <option value ="2 month" <?= $combinedTermMultiplier == '2 month' ? 'selected' : ''; ?>>2 Months</option>
                            <option value ="3 month" <?= $combinedTermMultiplier == '3 month' ? 'selected' : ''; ?>>3 Months</option>
                            <option value ="6 month" <?= $combinedTermMultiplier == '3 month' ? 'selected' : ''; ?>>6 Months</option>
                            <option value ="9 month" <?= $combinedTermMultiplier == '9 month' ? 'selected' : ''; ?>>9 Months</option>
                            <option value ="12 month" <?= $combinedTermMultiplier == '12 month' ? 'selected' : ''; ?>>12 Months</option>
                            <option value ="24 month" <?= $combinedTermMultiplier == '24 month' ? 'selected' : ''; ?>>24 Months</option>
                            <option value ="36 month" <?= $combinedTermMultiplier == '36 month' ? 'selected' : ''; ?>>36 Months</option>
                        </select>
                        <span class="terms-edit__term active" data-term-number="<?= $i; ?>"><?php echo $combinedTermMultiplier; echo $term['term_multiplier'] > 1 ? 's' : ''; ?></span>
                    </td>
                    <td>
                        <input type="number" step="0.05" value="<?= $cost; ?>" class="form-control form-control-lg w-50 terms-edit__cost <?= $errClassName['cost']; ?>" name="cost" disabled data-term-number="<?= $i; ?>">
                        <span class="invalid-feedback"><?= $data['term_update']['cost_err']; ?></span>
                    </td>
                    <td><?= $dateCreated;?></td>
                    <td><input class="btn btn-success terms-edit__submit" disabled data-term-number="<?= $i; ?>" type="submit" value="submit"></td>
                    <td><a href="" class="btn btn-warning terms-edit__edit" data-term-number="<?= $i; ?>">Edit</a></td>
                    <td><a href="<?= URL_ROOT; ?>/terms/delete/<?= $term['id']; ?>" class="btn btn-danger">Delete</a></td>
                    <input type="hidden" name="term_id" value="<?= $term['id']; ?>">
                </form>
            </tr>
        <?php $i++ // incerment after so i starts from 0 as element arrays in js are zero indexed; ?>
        <?php endforeach; ?>
    </table>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
