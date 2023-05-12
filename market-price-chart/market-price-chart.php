<?php
/**
 * Plugin Name: Market Price Chart
 * Description: A plugin to display market price chart using data from blockchain.info API
 * Version: 1.0
 * Author: alexW3c_maker
 */

 if (!defined('ABSPATH')) {
    exit; // Защита от прямого доступа к файлу
}

// Register shortcode
function register_market_price_chart_shortcode()
{
    add_shortcode('market_price_chart', 'market_price_chart_shortcode_handler');
}
add_action('init', 'register_market_price_chart_shortcode');

// Shortcode handler
function market_price_chart_shortcode_handler()
{
    // Enqueue scripts and styles
    wp_enqueue_script('moment', plugins_url('node_modules/moment/min/moment.min.js', __FILE__), array(), '2.29.4', true);
    wp_enqueue_script('chartjs-adapter-moment', plugins_url('node_modules/chartjs-adapter-moment/dist/chartjs-adapter-moment.min.js', __FILE__), array('chart-js', 'moment'), '0.1.2', true);
    wp_enqueue_script('chart-js', plugins_url('node_modules/chart.js/dist/Chart.min.js', __FILE__), array(), '2.9.4', true);
    wp_enqueue_script('chartjs-plugin-zoom', plugins_url('node_modules/chartjs-plugin-zoom/dist/chartjs-plugin-zoom.min.js', __FILE__), array('chart-js'), '0.7.7', true);
    wp_enqueue_script('market-price-chart-script', plugins_url('js/market-price-chart.js', __FILE__), array('jquery', 'chart-js', 'moment', 'chartjs-adapter-moment'), '1.0', true);
    wp_localize_script('market-price-chart-script', 'marketPriceChart', array(
        'ajaxUrl' => plugins_url('ajax-handler.php', __FILE__)
    ));
    wp_enqueue_style('market-price-chart-style', plugins_url('css/market-price-chart.css', __FILE__), array(), '1.0');

    // Output container for the chart and buttons
    return '<div id="market-price-chart">
                <canvas id="market-price-chart-canvas"></canvas>
                <div class="buttons">
                    <button data-days="30">30 days</button>
                    <button data-days="60">60 days</button>
                    <button data-days="180">180 days</button>
                    <button data-days="365">1 year</button>
                    <button data-days="730">2 years</button>
                    <button data-days="0">All time</button>
                </div>
            </div>';
}

function create_market_data_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'market_data';

    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            date date NOT NULL,
            value float NOT NULL
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'create_market_data_table');

function save_market_data_to_db()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'market_data';

    $csvUrl = 'https://api.blockchain.info/charts/market-price?format=csv&timespan=all';
    $csvText = file_get_contents($csvUrl);
    $lines = explode("\n", $csvText);

    foreach ($lines as $line) {
        $data = str_getcsv($line);
        if (count($data) == 2 && is_numeric($data[0]) && is_numeric($data[1])) {
            $date = date('Y-m-d', intval($data[0] / 1000)); // Деление на 1000 для преобразования миллисекунд в секунды
            $value = floatval($data[1]);

            $wpdb->insert($table_name, ['date' => $date, 'value' => $value]);
        }
    }
}
register_activation_hook(__FILE__, 'save_market_data_to_db');