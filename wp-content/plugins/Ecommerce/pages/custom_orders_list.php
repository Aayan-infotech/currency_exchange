<?php
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Currency_List_Table extends WP_List_Table
{
    function __construct()
    {
        parent::__construct([
            'singular' => 'currency',
            'plural'   => 'currencies',
            'ajax'     => false,
        ]);
    }

    function get_columns()
    {
        return [
            'currency'      => __('Currency', 'textdomain'),
            'current_price' => __('Current Price', 'textdomain'),
            'change_rate'   => __('Change Rate', 'textdomain'),
            'created_at'    => __('Created At', 'textdomain'),
        ];
    }

    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="currency[]" value="%s" />', $item['id']);
    }

    function column_currency($item)
    {
        $edit_url   = admin_url('admin.php?page=add-new-currency&id=' . $item['id']);
        $delete_url = wp_nonce_url(
            admin_url('admin.php?page=currency-list&action=delete&id=' . $item['id']),
            'delete_currency_' . $item['id']
        );

        $actions = [
            'edit'   => sprintf('<a href="%s">%s</a>', esc_url($edit_url), __('Edit', 'textdomain')),
            'delete' => sprintf('<a href="%s" onclick="return confirm(\'Are you sure you want to delete this currency?\');">%s</a>', esc_url($delete_url), __('Delete', 'textdomain')),
        ];

        return sprintf(
            '<strong>%s</strong> %s',
            esc_html($item['currency']),
            $this->row_actions($actions)
        );
    }

    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mp_currency_list';
        $per_page     = 15;
        $current_page = $this->get_pagenum();
        $offset       = ($current_page - 1) * $per_page;

        if (isset($_GET['action']) && $_GET['action'] === 'delete' && !empty($_GET['id'])) {
            $id = intval($_GET['id']);
            if (wp_verify_nonce($_GET['_wpnonce'], 'delete_currency_' . $id)) {
                $wpdb->delete($table_name, ['id' => $id], ['%d']);
                echo '<div class="updated notice"><p>Currency deleted successfully.</p></div>';
            }
        }

        $total_items = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        $query = $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        );

        $data  = $wpdb->get_results($query, ARRAY_A);

        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$columns, $hidden, $sortable];
        $this->items = $data;

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ]);
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'currency':
            case 'current_price':
            case 'change_rate':
            case 'created_at':
                return esc_html($item[$column_name]);
            default:
                return '';
        }
    }

    function get_sortable_columns()
    {
        return [
            'currency'      => ['currency', false],
            'current_price' => ['current_price', false],
            'change_rate'   => ['change_rate', false],
            'created_at'    => ['created_at', false],
        ];
    }
}

$currencyTable = new Currency_List_Table();
$currencyTable->prepare_items();
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Currency List', 'textdomain'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=add-new-currency'); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'textdomain'); ?>
    </a>
    <hr class="wp-header-end">

    <form method="post">
        <?php
        $currencyTable->search_box(__('Search Currency', 'textdomain'), 'currency');
        $currencyTable->display();
        ?>
    </form>
</div>