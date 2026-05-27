<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all products with optional filters (category, size, color)
    public function get_all_products($filters = []) {
        $this->db->select('p.*, k.nama_kategori, k.slug as kategori_slug');
        $this->db->from('produk p');
        $this->db->join('kategori k', 'p.id_kategori = k.id_kategori');

        // Apply category filter
        if (!empty($filters['category'])) {
            $this->db->where('k.slug', $filters['category']);
        }

        // Apply size and color filters (which require joining the variations table)
        if (!empty($filters['size']) || !empty($filters['color'])) {
            $this->db->join('produk_detail pd', 'p.id_produk = pd.id_produk');
            
            if (!empty($filters['size'])) {
                $this->db->where('pd.ukuran', $filters['size']);
            }
            if (!empty($filters['color'])) {
                $this->db->where('pd.warna', $filters['color']);
            }
            // Group by product id to avoid duplicates due to multiple matching variations
            $this->db->group_by('p.id_produk');
        }

        // Apply price range
        if (!empty($filters['price_max'])) {
            $this->db->where('p.harga <=', $filters['price_max']);
        }

        // Apply search keyword
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nama_produk', $filters['search']);
            $this->db->or_like('p.deskripsi', $filters['search']);
            $this->db->group_end();
        }

        $this->db->order_by('p.id_produk', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get single product details
    public function get_product_by_id($id) {
        $this->db->select('p.*, k.nama_kategori, k.slug as kategori_slug');
        $this->db->from('produk p');
        $this->db->join('kategori k', 'p.id_kategori = k.id_kategori');
        $this->db->where('p.id_produk', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Get all size & color variations for a product
    public function get_product_variations($id_produk) {
        $this->db->where('id_produk', $id_produk);
        $query = $this->db->get('produk_detail');
        return $query->result_array();
    }

    // Get a specific variation by ID
    public function get_variation_by_id($id_detail) {
        $this->db->where('id_detail', $id_detail);
        $query = $this->db->get('produk_detail');
        return $query->row_array();
    }

    // Get specific variation row using product ID, size, and color
    public function get_variation_by_details($id_produk, $ukuran, $warna) {
        $this->db->where('id_produk', $id_produk);
        $this->db->where('ukuran', $ukuran);
        $this->db->where('warna', $warna);
        $query = $this->db->get('produk_detail');
        return $query->row_array();
    }

    // Get all categories
    public function get_categories() {
        $query = $this->db->get('kategori');
        return $query->result_array();
    }

    // Get all unique shoe sizes and colors across all products for filter sidebar
    public function get_unique_attributes() {
        $this->db->select('ukuran');
        $this->db->distinct();
        $this->db->order_by('ukuran', 'ASC');
        $sizes_query = $this->db->get('produk_detail');
        $sizes = array_column($sizes_query->result_array(), 'ukuran');

        $this->db->select('warna');
        $this->db->distinct();
        $this->db->order_by('warna', 'ASC');
        $colors_query = $this->db->get('produk_detail');
        $colors = array_column($colors_query->result_array(), 'warna');

        return [
            'sizes' => $sizes,
            'colors' => $colors
        ];
    }

    // Reduce stock of a specific variation
    public function reduce_stock($id_detail, $qty) {
        $this->db->set('stok', 'stok - ' . (int)$qty, FALSE);
        $this->db->where('id_detail', $id_detail);
        return $this->db->update('produk_detail');
    }

    // Increase stock of a specific variation
    public function increase_stock($id_detail, $qty) {
        $this->db->set('stok', 'stok + ' . (int)$qty, FALSE);
        $this->db->where('id_detail', $id_detail);
        return $this->db->update('produk_detail');
    }

    // Insert new product
    public function insert_product($data) {
        $this->db->insert('produk', $data);
        return $this->db->insert_id();
    }

    // Update product
    public function update_product($id, $data) {
        $this->db->where('id_produk', $id);
        return $this->db->update('produk', $data);
    }

    // Delete product (and cascade details / variations)
    public function delete_product($id) {
        $this->db->where('id_produk', $id);
        return $this->db->delete('produk');
    }

    // Add product variation
    public function insert_variation($data) {
        return $this->db->insert('produk_detail', $data);
    }

    // Delete product variations
    public function delete_variations($id_produk) {
        $this->db->where('id_produk', $id_produk);
        return $this->db->delete('produk_detail');
    }
}
