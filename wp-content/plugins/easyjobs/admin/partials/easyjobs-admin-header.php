<?php

/**
 * Header for eayjobs admin pages
 *
 *
 * @link       https://easy.jobs
 * @since      1.0.5
 *
 * @package    Easyjobs
 * @subpackage Easyjobs/admin/partials
 */
$company = Easyjobs_Helper::get_company_info();
?>

<header class="content-area__header d-flex justify-content-between">
    <div>
        <div class="ej-logo">
            <img src="<?php echo EASYJOBS_ADMIN_URL?>/assets/img/logo-blue.svg" alt="">
            <?php if($company->is_pro): ?>
                <span class="ej-pro-label">pro</span>
            <?php else: ?>
                <span class="ej-free-label">free</span>
            <?php endif; ?>
        </div>
        <small class="easyjobs-version"><?php _e('Version: ', 'easyjobs');?><?php echo EASYJOBS_VERSION;?> </small>
    </div>
    <div class="d-flex">
        <a href="#" class="button success-button ej-sync-btn" data-nonce="<?php echo wp_create_nonce('easyjobs_sync')?>">
            <i class="dashicons dashicons-image-rotate"></i>
            <?php _e('Sync data', 'easyjobs'); ?>
        </a>
        <a href="<?php echo !empty($company->company_easyjob_url) ? $company->company_easyjob_url : '#'; ?>" target="_blank" class="button info-button">
            <?php _e('View Company Page', 'easyjobs'); ?>
        </a>
    </div>
</header>
<?php
$status = Easyjobs_Helper::get_verification_status();
if( $status !== null && $status == false) : ?>
    <div class="verification-status">
        <h4 class="not-verified-message mt-5">
            <a href="https://easy.jobs/docs/verify-your-company-profile/" target="_blank"class="link-help">How to verify?</a>
            Your company is not verified, please verify your company.
        </h4>
    </div>
<?php endif; ?>
