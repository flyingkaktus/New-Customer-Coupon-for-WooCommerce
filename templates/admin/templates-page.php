<?php
/**
 * Admin Template Management Page
 *
 * @package NewCustomerDiscount
 */

if (!defined('ABSPATH')) {
    exit;
}

// Hole verfügbare Templates und aktives Template
$email_sender = new NCD_Email_Sender();
$available_templates = $email_sender->get_template_list();
$current_template_id = get_option('ncd_active_template', 'modern');
$current_template = $email_sender->load_template($current_template_id);
?>

<div class="wrap ncd-wrap">
    <h1><?php _e('E-Mail Template Design', 'newcustomer-discount'); ?></h1>

    <?php settings_errors('ncd_template'); ?>

    <div class="ncd-template-manager">
        <!-- Template Selector Dropdown -->
        <div class="ncd-template-selector">
            <select id="template-selector" class="ncd-select">
                <?php foreach ($available_templates as $id => $template): 
                    $template_data = $email_sender->load_template($id);
                ?>
                    <option value="<?php echo esc_attr($id); ?>" 
                            <?php selected($current_template_id, $id); ?>
                            data-preview="<?php echo esc_url($template['preview']); ?>">
                        <?php echo esc_html($template_data['name']); ?> - 
                        <?php echo esc_html($template_data['description']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="ncd-design-container">
            <!-- Settings Panel -->
            <div class="ncd-settings-panel">
                <form method="post" id="template-settings-form">
                    <?php wp_nonce_field('ncd_save_template', 'ncd_template_nonce'); ?>
                    <input type="hidden" name="template_id" value="<?php echo esc_attr($current_template_id); ?>">
                    
                    <div class="ncd-settings-group">
                        <h3><?php _e('Design Anpassen', 'newcustomer-discount'); ?></h3>
                        
                        <!-- Colors -->
                        <div class="ncd-color-controls">
                            <div class="ncd-color-row">
                                <label for="primary_color">
                                    <?php _e('Primärfarbe', 'newcustomer-discount'); ?>
                                </label>
                                <input type="color" 
                                       name="settings[primary_color]" 
                                       id="primary_color" 
                                       value="<?php echo esc_attr($current_template['settings']['primary_color']); ?>">
                            </div>

                            <div class="ncd-color-row">
                                <label for="secondary_color">
                                    <?php _e('Akzentfarbe', 'newcustomer-discount'); ?>
                                </label>
                                <input type="color" 
                                       name="settings[secondary_color]" 
                                       id="secondary_color" 
                                       value="<?php echo esc_attr($current_template['settings']['secondary_color']); ?>">
                            </div>

                            <div class="ncd-color-row">
                                <label for="text_color">
                                    <?php _e('Text', 'newcustomer-discount'); ?>
                                </label>
                                <input type="color" 
                                       name="settings[text_color]" 
                                       id="text_color" 
                                       value="<?php echo esc_attr($current_template['settings']['text_color']); ?>">
                            </div>

                            <div class="ncd-color-row">
                                <label for="background_color">
                                    <?php _e('Hintergrund', 'newcustomer-discount'); ?>
                                </label>
                                <input type="color" 
                                       name="settings[background_color]" 
                                       id="background_color" 
                                       value="<?php echo esc_attr($current_template['settings']['background_color']); ?>">
                            </div>
                        </div>

                        <!-- Typography -->
                        <div class="ncd-typography-control">
                            <label for="font_family">
                                <?php _e('Schriftart', 'newcustomer-discount'); ?>
                            </label>
                            <select name="settings[font_family]" id="font_family">
                                <option value="system-ui, -apple-system, sans-serif" <?php selected($current_template['settings']['font_family'], 'system-ui, -apple-system, sans-serif'); ?>>
                                    System Default
                                </option>
                                <option value="'Inter', sans-serif" <?php selected($current_template['settings']['font_family'], "'Inter', sans-serif"); ?>>
                                    Inter
                                </option>
                                <option value="'Roboto', sans-serif" <?php selected($current_template['settings']['font_family'], "'Roboto', sans-serif"); ?>>
                                    Roboto
                                </option>
                            </select>
                        </div>

                        <!-- Layout -->
                        <div class="ncd-layout-controls">
                            <div class="ncd-control-group">
                                <label for="button_style">
                                    <?php _e('Button Design', 'newcustomer-discount'); ?>
                                </label>
                                <select name="settings[button_style]" id="button_style">
                                    <option value="minimal" <?php selected($current_template['settings']['button_style'], 'minimal'); ?>>
                                        <?php _e('Minimalistisch', 'newcustomer-discount'); ?>
                                    </option>
                                    <option value="rounded" <?php selected($current_template['settings']['button_style'], 'rounded'); ?>>
                                        <?php _e('Abgerundet', 'newcustomer-discount'); ?>
                                    </option>
                                    <option value="pill" <?php selected($current_template['settings']['button_style'], 'pill'); ?>>
                                        <?php _e('Pill', 'newcustomer-discount'); ?>
                                    </option>
                                </select>
                            </div>

                            <div class="ncd-control-group">
                                <label for="layout_type">
                                    <?php _e('Layout', 'newcustomer-discount'); ?>
                                </label>
                                <select name="settings[layout_type]" id="layout_type">
                                    <option value="centered" <?php selected($current_template['settings']['layout_type'], 'centered'); ?>>
                                        <?php _e('Zentriert', 'newcustomer-discount'); ?>
                                    </option>
                                    <option value="full-width" <?php selected($current_template['settings']['layout_type'], 'full-width'); ?>>
                                        <?php _e('Volle Breite', 'newcustomer-discount'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="ncd-action-bar">
                        <button type="submit" name="save_template" class="button button-primary">
                            <?php _e('Speichern', 'newcustomer-discount'); ?>
                        </button>
                        <button type="button" class="button preview-test-email">
                            <?php _e('Test-E-Mail', 'newcustomer-discount'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Panel -->
            <div class="ncd-preview-panel">
                <div class="ncd-preview-header">
                    <h3><?php _e('Vorschau', 'newcustomer-discount'); ?></h3>
                    <div class="ncd-preview-controls">
                        <button type="button" class="preview-mode active" data-mode="desktop">
                            <span class="dashicons dashicons-desktop"></span>
                        </button>
                        <button type="button" class="preview-mode" data-mode="mobile">
                            <span class="dashicons dashicons-smartphone"></span>
                        </button>
                    </div>
                </div>
                
                <div class="ncd-preview-container">
                    <div class="ncd-preview-frame">
                        <?php echo $email_sender->render_preview($current_template_id); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div id="test-email-modal" class="ncd-modal">
    <div class="ncd-modal-content">
        <div class="ncd-modal-header">
            <h3><?php _e('Test-E-Mail senden', 'newcustomer-discount'); ?></h3>
            <button class="ncd-modal-close">&times;</button>
        </div>
        <form id="test-email-form">
            <div class="ncd-modal-body">
                <div class="ncd-form-group">
                    <label for="test-email"><?php _e('E-Mail-Adresse', 'newcustomer-discount'); ?></label>
                    <input type="email" id="test-email" required>
                </div>
            </div>
            <div class="ncd-modal-footer">
                <button type="submit" class="button button-primary">
                    <?php _e('Senden', 'newcustomer-discount'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    new NCDTemplateManager();
});
</script>