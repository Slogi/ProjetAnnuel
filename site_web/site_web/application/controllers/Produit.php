<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');

    }

    public function afficherProduit(){

        $id = $this->uri->segment(2);
        $produit = $this->product_model->getProduit($id)->result_array();
        $prix = $this->product_model->getProduitInfoSuivi($produit[0]['product_ean']);
        $ean = array();
        foreach ( $prix as $prixParSite ){
            $urlNomSite = $this->product_model->getNomSiteLien($produit[0]['product_ean'], $prixParSite->price_id_site);
            $ean[$prixParSite->price_id_site]['price_exponent'] = $prixParSite->price_price_exponent;
            $ean[$prixParSite->price_id_site]['price_fraction'] = $prixParSite->price_price_fraction;
            $ean[$prixParSite->price_id_site]['url'] = $urlNomSite[0]->link_url;
            $ean[$prixParSite->price_id_site]['siteName'] = $urlNomSite[0]->site_name;
        }
        $data['suivi'] = $this->product_model->getProductFromList($this->session->mail, $produit[0]['product_ean']);
        $data['title'] = $produit[0]['product_name'];
        $data['info'] = $ean;
        $data['h1'] = '';
        $data['pseudo'] = $this->session->pseudo;
        $data['produit'] = $produit;
        $this->load->view('header.php', $data);
        $this->load->view('page_produit');
        $this->load->view('footer.php');
    }

    public function ajouterListe(){

        $suivi = $this->input->post('mail');
        $seuil = $this->input->post('seuil');
        $ean = $this->input->post('ean');
        $mail = $this->session->mail;

        if ( $seuil != null ){
            if ( $this->product_model->insertProduct($mail, $ean, 1, intval($seuil)) ) {
                redirect('accueil/produitAjoute');
            }
            else {
                redirect('accueil/produitNonAjoute');
            }
        }
        else {
            if ( $this->product_model->insertProduct($mail, $ean) ) {
                redirect('accueil/produitAjoute');
            }
            else {
                redirect('accueil/produitNonAjoute');
            }
        }
    }
    public function file_get_contents_curl($url) {

        $ch = curl_init();
        $headers = [
            'Accept: 	text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept_Encoding: gzip, deflate',
            'Accept-Language: fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7',
            'Cache-Control: no-cache',
            'Host :'.base_url(),
            'Connection: keep-alive',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $data = curl_exec($ch);

        if($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        curl_close($ch);
        return $data;
    }

    public function majPrix($array){
        $content = $this->file_get_contents_curl("http://google.com");
        if ( $content == false ){
            var_dump("MARCHE PAS");
        }
        /*foreach ($array as $row){
            switch ($row['site_id_site']){
                case '01' :
                    $content = $this->file_get_contents_curl($row['link_url']);
                    if ( $content == false ){
                        var_dump("MARCHE PAS");
                    }
                    $page = new DOMDocument();
                    $page->loadHTML($content);
                    $xpath = new DOMXPath($page);
                    var_dump($xpath->query("//p[@class=\"fix-price\"]/span[@class=\"exponent\"]"));
                    var_dump($xpath->query("//p[@class=\"fix-price\"]/sup/span[@class=\"fraction\"]"));
                    break;
                case '02' :
                    $content = $this->file_get_contents_curl($row['link_url']);
                    if ( $content == false ){
                        var_dump("MARCHE PAS");
                    }
                    $page = new DOMDocument();
                    $page->loadHTML($content);
                    $xpath = new DOMXPath($page);
                    var_dump($xpath->query("//div[@class=\"product_price font-2-b\"]/span/"));
                    var_dump($xpath->query("//div[@class=\"product_price font-2-b\"]/span/span/"));
                    break;
            }
        }*/
    }

}