<?php
require_once('../../../wp-load.php');

if (!defined('ABSPATH')) {
    exit; // Защита от прямого доступа к файлу
}

function get_market_data($days = 0)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'market_data';
    $where = $days ? "WHERE date >= DATE_SUB(CURDATE(), INTERVAL {$days} DAY)" : '';

    $results = $wpdb->get_results("SELECT * FROM {$table_name} {$where} ORDER BY date ASC");

    return $results;
}

if (isset($_GET['days'])) {
    $days = intval($_GET['days']);
    $data = get_market_data($days);

    echo json_encode($data);
    exit;
}