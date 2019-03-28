<?php

class Product_model extends CI_Model
{
    protected $table="product";

    public function recherche($term, $limit = null, $start = null){

        $this->db->select("*");
        $this->db->from($this->table);
        if($term != '') {
            $this->db->like('product_ean', $term);
            $this->db->or_like('product_brand', $term);

            if (preg_match("/[\s,]+/", $term)) {
                $termArray = preg_split("/[\s,]+/", $term);
                $where ='';
                for( $i = 0; $i < sizeof($termArray); $i++){
                    if ( $i == sizeof($termArray)-1 ){
                        $where .= "product_brand LIKE '%".$termArray[$i]."%' or ";
                        $where .= "product_name LIKE '%".$termArray[$i]."%'";


                    }else {
                        $where .= "product_brand LIKE '%".$termArray[$i]."%' or ";
                        $where .= "product_name LIKE '%".$termArray[$i]."%' and ";
                    }
                }
                $this->db->or_where($where);
            } else {
                $this->db->or_like('product_name', $term);
            }
            $this->db->order_by('product_name');

        }
        if ($limit != null && $start >= 0 ){
            $this->db->limit($limit, $start);
        }
        return $this->db->get();

    }

    public function produitsRandom(){
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->order_by('rand()');
        $this->db->limit(10);
        return $this->db->get();
    }

    public function getProduit($id){
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->where('product_ean', $id);
        return $this->db->get();
    }

    public function getUrlProduit($id){
        $this->db->select('link_url, site_id_site')
            ->from('link')
            ->where('link_ean', $id)
            ->join('site','link_id_site = site_id_site');
        return $this->db->get();
    }

    public function insertProduct($email, $ean, $suivi = null, $seuil = null ){

        $data = array(
            'list_products_mail' => $email,
            'list_products_ean' => $ean
        );
        if ( $suivi != null || $seuil != null){
            $data['list_products_suivi'] = $suivi;
            $data['list_products_seuil'] = $seuil;
        }
        if ( sizeof($this->getProduit($ean)->result_array()) == 1){
            return $this->db->insert('list_products', $data);
        }
        return 0;
    }

    public function insertNewPrice($id, $idSite ,$priceFraction, $priceExponent){
        $data = array(
            'price_ean' => $id,
            'price_id_site' => $idSite,
            'price_date' => date('Y-m-d'),
            'price_price_exponent' => $priceExponent,
            'price_price_fraction' => $priceFraction
        );

        if ( sizeof($this->getProduit($id)->result_array()) == 1){
            return $this->db->insert('price', $data);
        }
        return 0;
    }

    public function listProduct($email){
        return $this->db->select('product_ean, product_name, product_brand, product_img, list_products_suivi,
        list_products_seuil')
            ->from('list_products')
            ->where('list_products_mail', $email)
            ->join('product', 'list_products_ean = product_ean')
            ->get()
            ->result();
    }

    public function updateSuivi($email, $ean, $suivi, $seuil){
        if ( $suivi === null ){
            $suivi = 1;
        }
        $this->db->set('list_products_suivi', $suivi);
        $this->db->set('list_products_seuil', $seuil);
        $this->db->where('list_products_mail', $email);
        $this->db->where('list_products_ean', $ean);
        $this->db->update('list_products');
    }

    public function getProductFromList($email, $ean){
        $query = $this->db->select('*')
            ->from('list_products')
            ->where('list_products_mail', $email)
            ->where('list_products_ean', $ean)
            ->get()
            ->result();
        if ($query == array()) return null;
        else return $query;
    }

    public function deleteFollowedProduct($email, $ean){
        $this->db->where('list_products_mail', $email);
        $this->db->where('list_products_ean', $ean);
        $this->db->delete('list_products');
    }

    public function getProduitInfoSuivi($id){
        $sql = "SELECT distinct price_ean, price_id_site, price_date, price_price_exponent, price_price_fraction
                FROM price
                WHERE price_date in 
                (
                  SELECT  max(price_date) FROM price
	              where price_ean = ".$id."
	              group by price_id_site
                )    
                AND
                price_ean = ".$id."
                group by price_ean, price_id_site
                ORDER BY price.`price_id_site` ASC
                ";
        return $queryR = $this->db->query($sql)
            ->result();

    }

    public function getNomSiteLien($ean, $id_site){
        return $this->db->select('link_ean, site_name, link_url')
            ->from('link')
            ->join('site','site_id_site = link_id_site')
            ->where('link_ean', $ean)
            ->where('link_id_site', $id_site)
            ->get()
            ->result();
    }

    public function getProduitInfoChart($id){
        return $this->db->select('*')
            ->from('price')
            ->join('site','site_id_site = price_id_site')
            ->where('price_ean', $id)
            ->order_by('price_id_site')
            ->get()
            ->result_array();
    }

}