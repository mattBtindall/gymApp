<?php
    # Terms
    # New db table for terms
    # Db fields:
    # id, admin_id, term, term_multiplier, cost, created_at
    # use term & term_multiplier so that we can seperate the time frame month or week e.g. term = weeek and term_multiplier = 2 (2 week)
    # All current terms are displayed here with a choice to edit the cost of the term
    # Remind user that they can add a custom term in when adding a membership, these are just prest templates
    # list of terms:
    # one week, two week, one month, 6 week, two month, three month, six month, twelve month
?>
<?php require APP_ROOT . '/views/inc/header.php'; ?>
<?php
    // created for ease of use when showing/hiding terms in the drop down
    $combinedTermMultipliers = [];
    foreach ($data['terms'] as $term) {
        $combinedTermMultipliers[] = $term['term_multiplier'] . ' ' . $term['term'];
    }

    // used to label edit buttons which are used in javascript as event aggregation is used
    $i = 0;
?>
    <h3 class="mt-3"> Terms </h3>
    <p class="mt-2">
        Add/edit your custom terms. You may edit one term at a time. Please take in mind that you can add 'custom' memberships of varying lengths when assigning users memberships.
    </p>
    <?= flash('term_deleted'); ?>
    <table class="terms-table">
        <tr>
            <th>Display name</th>
            <th>Term</th>
            <th>Cost</th>
            <th>Date created</th>
        </tr>
        <?php foreach($data['terms'] as $term): ?>
            <?php
                // format the expiry date to d/m/y
                $expiryDate = date_create_from_format(SQL_DATE_TIME_FORMAT, $term['created_at']);
                $expiryDate = $expiryDate->format(OUTPUT_DATE_TIME_FORMAT);
                $combinedTermMultiplier = $term['term_multiplier'] . ' ' . $term['term'];
            ?>
            <tr>
                <td><input type="text" value="<?= $term['display_name']; ?>" class="form-control form-control-lg w-50 terms-edit__display-name" disabled data-term-number="<?= $i; ?>"></td>
                <td>
                    <select class="form-control form-control-lg terms-edit__drop-down" name="term" data-term-number="<?= $i; ?>">
                        <option value="please_select">Please select</option>
                        <!-- user can't select a term that has already been sued but is not the current term as they're editing that -->
                        <option value ="1 week" class="<?= ($combinedTermMultiplier !== '1 week' && in_array('1 week', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '1 week' ? 'selected' : ''; ?>>1 Week</option>
                        <option value ="2 week" class="<?= ($combinedTermMultiplier !== '2 week' && in_array('2 week', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '2 week' ? 'selected' : ''; ?>>2 Weeks</option>
                        <option value ="1 month" class="<?= ($combinedTermMultiplier !== '1 month' && in_array('1 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '1 month' ? 'selected' : ''; ?>>1 month</option>
                        <option value ="2 month" class="<?= ($combinedTermMultiplier !== '2 month' && in_array('2 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '2 month' ? 'selected' : ''; ?>>2 Months</option>
                        <option value ="3 month" class="<?= ($combinedTermMultiplier !== '3 month' && in_array('3 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '3 month' ? 'selected' : ''; ?>>3 Months</option>
                        <option value ="6 month" class="<?= ($combinedTermMultiplier !== '3 month' && in_array('3 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '3 month' ? 'selected' : ''; ?>>6 Months</option>
                        <option value ="9 month" class="<?= ($combinedTermMultiplier !== '9 month' && in_array('9 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '9 month' ? 'selected' : ''; ?>>9 Months</option>
                        <option value ="12 month" class="<?= ($combinedTermMultiplier !== '12 month' && in_array('12 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '12 month' ? 'selected' : ''; ?>>12 Months</option>
                        <option value ="24 month" class="<?= ($combinedTermMultiplier !== '24 month' && in_array('24 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '24 month' ? 'selected' : ''; ?>>24 Months</option>
                        <option value ="36 month" class="<?= ($combinedTermMultiplier !== '36 month' && in_array('36 month', $combinedTermMultipliers)) ? 'd-none' : ''; ?>" <?= $combinedTermMultiplier == '36 month' ? 'selected' : ''; ?>>36 Months</option>
                    </select>
                    <span class="terms-edit__term active" data-term-number="<?= $i; ?>"><?php echo $combinedTermMultiplier; echo $term['term_multiplier'] > 1 ? 's' : ''; ?></span>
                </td>
                <td><input type="text" value="£<?= $term['cost']; ?>" class="form-control form-control-lg w-50 terms-edit__cost" disabled data-term-number="<?= $i; ?>"></td>
                <td><?= $expiryDate ;?></td>
                <td><button href="" class="btn btn-success terms-edit__submit" data-term-number="<?= $i; ?>" disabled data-term-number="<?= $i; ?>">Submit</button></td>
                <td><a href="" class="btn btn-warning terms-edit__edit" data-term-number="<?= $i; ?>">Edit</a></td>
                <td><a href="<?= URL_ROOT; ?>/users/term_delete/<?= $term['id']; ?>" class="btn btn-danger">Delete</a></td>
            </tr>
        <?php $i++ // incerment after so i starts from 0 as element arrays in js are zero indexed; ?> 
        <?php endforeach; ?>
    </table>
<?php require APP_ROOT . '/views/inc/footer.php'; ?>
