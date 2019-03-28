<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProduitSuivi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('product_model');
    }

    public function index()
    {
        if ($this->session->pseudo !== null) {
            $data['pseudo'] = $this->session->pseudo;
            $data['title'] = 'Articles Suivis';
            $data['h1'] = 'Vos produits favoris';
            $list = $this->product_model->listProduct($this->session->mail);
            $ean = array();
            foreach ($list as $row) {
                $prix = $this->product_model->getProduitInfoSuivi($row->product_ean);
                foreach ($prix as $prixParSite) {
                    $urlNomSite = $this->product_model->getNomSiteLien($row->product_ean, $prixParSite->price_id_site);
                    $ean[$row->product_ean][$prixParSite->price_id_site]['price_exponent'] = $prixParSite->price_price_exponent;
                    $ean[$row->product_ean][$prixParSite->price_id_site]['price_fraction'] = $prixParSite->price_price_fraction;
                    $ean[$row->product_ean][$prixParSite->price_id_site]['url'] = $urlNomSite[0]->link_url;
                    $ean[$row->product_ean][$prixParSite->price_id_site]['siteName'] = $urlNomSite[0]->site_name;
                }
            }
            $datalist['list'] = $list;
            $datalist['infoEan'] = $ean;
            $this->load->view('header.php', $data);
            $this->load->view('produit_suivi.php', $datalist);
            $this->load->view('footer.php');
        } else {
            redirect('accueil');
        }

    }

    public function delete()
    {
        if ($this->session->pseudo !== null) {

            $id = $this->uri->segment(3);
            if ($this->product_model->getProductFromList($this->session->mail, $id)[0] !== null) {
                $this->product_model->deleteFollowedProduct($this->session->mail, $id);
                redirect('produitSuivi');
            } else {
                redirect('produitSuivi');
            }
        } else {
            redirect('accueil');
        }
    }

    public function modifier()
    {
        if ($this->session->pseudo !== null) {
            $ean = $this->input->post('ean');
            $seuil = $this->input->post('seuil' . $ean);
            $suivi = $this->input->post('mail');
            if ($suivi === null or $seuil === null) {
                $suivi = 0;
                $seuil = null;
            } else {
                $suivi = 1;
            }
            $product = $this->product_model->getProductFromList($this->session->mail, $ean)[0];
            if ($product !== null) {
                if ($product->list_products_suivi != $suivi || $seuil != $product->list_products_seuil) {
                    $this->product_model->updateSuivi($this->session->mail, $ean, $suivi, $seuil);
                    redirect('produitSuivi');
                } else {
                    redirect('produitSuivi');
                }
            } else {
                redirect('produitSuivi');
            }

        } else {
            redirect('accueil');
        }
    }

    public function graphique()
    {
        if ($this->session->pseudo !== null) {

            $id = $this->uri->segment(3);
            $info = $this->product_model->getProduitInfoChart($id);
            if ($info != null) {
                $data['pseudo'] = $this->session->pseudo;
                $data['title'] = 'Graphique';
                $data['h1'] = 'Votre suivi en graphique';

                $datas = array();
                $tmp ='';
                $i = 0;
                foreach ($info as $date) {
                    if ($tmp != $date['site_name']){
                        $i = 0;
                    }
                    $datas[$date['site_name']][$i]['date'] = $date['price_date'];
                    $datas[$date['site_name']][$i]['price'] = $date['price_price_exponent'] . '.' . $date['price_price_fraction'];
                    $tmp = $date['site_name'];
                    $i++;
                }

                $dataInfo['info'] = $datas;
                $this->load->view('header.php', $data);
                $this->load->view('chart.php', $dataInfo);
                $this->load->view('footer.php');
            } else {
                redirect('produitSuivi');
            }
        } else {
            redirect('accueil');
        }
    }
}