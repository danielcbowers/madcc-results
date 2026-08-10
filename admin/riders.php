<?php

if (!defined('ABSPATH')) {
    exit;
}

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'delete' &&
    isset($_GET['id'])
) {
    $id = intval($_GET['id']);

    check_admin_referer('delete_rider_' . $id);

    if (mcc_delete_rider($id)) {
        mcc_success_notice('Rider deleted successfully.');
    } else {
        mcc_error_notice('Unable to delete rider.');
    }
}

$riders = mcc_get_all_riders();

?>

<div class="wrap">

    <h1 class="wp-heading-inline">Riders</h1>

    <a href="<?php echo esc_url(admin_url('admin.php?page=mcc-add-rider')); ?>" class="page-title-action">
        Add Rider
    </a>

    <hr class="wp-header-end">

    <?php if (empty($riders)) : ?>

        <p>No riders found.</p>

    <?php else : ?>

        <table class="widefat striped">

            <thead>

                <tr>
                    <th>Name</th>
                    <th>Club</th>
                    <th>Category</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($riders as $rider) : ?>

                    <tr>

                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=mcc-view-rider&id=' . $rider->id)); ?>">
                                <?php echo esc_html($rider->first_name . ' ' . $rider->last_name); ?>
                            </a>
                        </td>

                        <td>
                            <?php echo esc_html($rider->club); ?>
                        </td>

                        <td>
                            <?php echo esc_html($rider->category ?? '-'); ?>
                        </td>

                        <td>
                            <?php echo esc_html($rider->email); ?>
                        </td>

                        <td>
                            <?php echo $rider->active ? 'Active' : 'Inactive'; ?>
                        </td>

                        <td>

                            <a href="<?php echo esc_url(admin_url('admin.php?page=mcc-edit-rider&id=' . $rider->id)); ?>">
                                Edit
                            </a>

                            |

                            <a
                                href="<?php echo esc_url(
                                    wp_nonce_url(
                                        admin_url('admin.php?page=mcc-riders&action=delete&id=' . $rider->id),
                                        'delete_rider_' . $rider->id
                                    )
                                ); ?>"
                                onclick="return confirm('Are you sure you want to delete this rider?');">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>