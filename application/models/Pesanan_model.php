<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Produk_model');
    }

    // Create a new order
    public function create_order($order_data, $cart_items) {
        $this->db->trans_start();

        // 1. Insert into pesanan
        $this->db->insert('pesanan', $order_data);
        $id_pesanan = $this->db->insert_id();

        // 2. Insert into pesanan_detail
        foreach ($cart_items as $item) {
            // Find specific variation detail ID in database
            $variation = $this->Produk_model->get_variation_by_details($item['id'], $item['options']['Size'], $item['options']['Color']);
            
            $detail_data = [
                'id_pesanan' => $id_pesanan,
                'id_produk' => $item['id'],
                'id_detail' => $variation ? $variation['id_detail'] : 0,
                'qty' => $item['qty'],
                'harga' => $item['price'],
                'ukuran' => $item['options']['Size'],
                'warna' => $item['options']['Color']
            ];
            $this->db->insert('pesanan_detail', $detail_data);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $id_pesanan : FALSE;
    }

    // Get all orders
    public function get_all_orders() {
        $this->db->order_by('id_pesanan', 'DESC');
        $query = $this->db->get('pesanan');
        return $query->result_array();
    }

    // Get single order by ID
    public function get_order_by_id($id) {
        $this->db->where('id_pesanan', $id);
        $query = $this->db->get('pesanan');
        return $query->row_array();
    }

    // Get details of items bought in an order
    public function get_order_details($id_pesanan) {
        $this->db->select('pd.*, p.nama_produk, p.gambar');
        $this->db->from('pesanan_detail pd');
        $this->db->join('produk p', 'pd.id_produk = p.id_produk');
        $this->db->where('pd.id_pesanan', $id_pesanan);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Confirm Payment & Reduce Stock dynamically
    public function confirm_payment($id_pesanan) {
        // Start transaction
        $this->db->trans_start();

        // 1. Get the order details
        $order = $this->get_order_by_id($id_pesanan);
        if (!$order || $order['status'] === 'Paid') {
            return FALSE;
        }

        $items = $this->get_order_details($id_pesanan);

        // 2. Reduce stock for each item's variation
        foreach ($items as $item) {
            if ($item['id_detail'] > 0) {
                // Reduce stock by quantity purchased
                $this->Produk_model->reduce_stock($item['id_detail'], $item['qty']);
            }
        }

        // 3. Update order status to 'Paid' and record payment date
        $this->db->where('id_pesanan', $id_pesanan);
        $this->db->update('pesanan', [
            'status' => 'Paid',
            'tgl_bayar' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Cancel order (restore stock if previously paid)
    public function cancel_order($id_pesanan) {
        $this->db->trans_start();

        $order = $this->get_order_by_id($id_pesanan);
        if (!$order || $order['status'] === 'Cancelled') {
            return FALSE;
        }

        // If the order was already Paid, we should restore/increase the stock back!
        if ($order['status'] === 'Paid') {
            $items = $this->get_order_details($id_pesanan);
            foreach ($items as $item) {
                if ($item['id_detail'] > 0) {
                    $this->Produk_model->increase_stock($item['id_detail'], $item['qty']);
                }
            }
        }

        // Update status to Cancelled
        $this->db->where('id_pesanan', $id_pesanan);
        $this->db->update('pesanan', [
            'status' => 'Cancelled'
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Fetch dashboard sales statistics
    public function get_sales_stats() {
        // Total Sales (Paid Revenue)
        $this->db->select_sum('total_bayar');
        $this->db->where('status', 'Paid');
        $revenue_query = $this->db->get('pesanan');
        $total_revenue = $revenue_query->row()->total_bayar ?? 0;

        // Total Orders Count
        $total_orders = $this->db->count_all('pesanan');

        // Pending Orders Count
        $this->db->where('status', 'Pending');
        $pending_orders = $this->db->count_all_results('pesanan');

        // Paid Orders Count
        $this->db->where('status', 'Paid');
        $paid_orders = $this->db->count_all_results('pesanan');

        // Total Products Count
        $total_products = $this->db->count_all('produk');

        // Recent Orders
        $this->db->order_by('id_pesanan', 'DESC');
        $this->db->limit(5);
        $recent_orders_query = $this->db->get('pesanan');
        $recent_orders = $recent_orders_query->result_array();

        // Top Selling Shoes (by Qty Sold in Paid Orders)
        $this->db->select('p.nama_produk, SUM(pd.qty) as total_sold, p.harga, p.gambar');
        $this->db->from('pesanan_detail pd');
        $this->db->join('pesanan o', 'pd.id_pesanan = o.id_pesanan');
        $this->db->join('produk p', 'pd.id_produk = p.id_produk');
        $this->db->where('o.status', 'Paid');
        $this->db->group_by('pd.id_produk');
        $this->db->order_by('total_sold', 'DESC');
        $this->db->limit(4);
        $top_selling_query = $this->db->get();
        $top_selling = $top_selling_query->result_array();

        // Sales by Category
        $this->db->select('c.nama_kategori, COUNT(pd.id_pesanan_detail) as transaction_count, SUM(pd.qty) as total_qty');
        $this->db->from('pesanan_detail pd');
        $this->db->join('pesanan o', 'pd.id_pesanan = o.id_pesanan');
        $this->db->join('produk p', 'pd.id_produk = p.id_produk');
        $this->db->join('kategori c', 'p.id_kategori = c.id_kategori');
        $this->db->where('o.status', 'Paid');
        $this->db->group_by('c.id_kategori');
        $category_sales_query = $this->db->get();
        $category_sales = $category_sales_query->result_array();

        return [
            'total_revenue' => $total_revenue,
            'total_orders' => $total_orders,
            'pending_orders' => $pending_orders,
            'paid_orders' => $paid_orders,
            'total_products' => $total_products,
            'recent_orders' => $recent_orders,
            'top_selling' => $top_selling,
            'category_sales' => $category_sales
        ];
    }
}
