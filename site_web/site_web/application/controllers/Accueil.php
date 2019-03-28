<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accueil extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');

    }
    public function index($data = null)
    {
        $data['pseudo'] = $this->session->pseudo;
        $data['title'] = 'Accueil';
        $data['h1'] = 'Produits Aléatoires';
        $list = $this->product_model->produitsRandom()->result_array();
        $datalist['list'] = $list;
        $datalist['links'] = '';
        $this->load->view('header.php', $data);
        $this->load->view('liste_produit.php', $datalist);
        $this->load->view('footer.php');
    }

    public function erreurConnexion(){
        $data = array(
            'message' => '<div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    Identifiants Errones
                                </div>'
        );
        $this->index($data);
    }

    public function erreurInscription(){
        $data = array(
            'message' => '<div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    Cette adresse email est déjà utilisée
                                </div>'
        );
        $this->index($data);
    }

    public function connecte(){
        $data = array(
            'message' => '<div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Heureux de vous revoir '. $this->session->pseudo.'
                            </div>'
        );
        $this->index($data);

    }

    public function inscrit(){
        $data = array(
            'message' => '<div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Bienvenue '. $this->session->pseudo.'
                            </div>'
        );
        $this->index($data);

    }

    public function deconnexion(){
        $this->session->sess_destroy();
        redirect('accueil');

    }

    public function produitAjoute(){
        $data = array(
            'message' => '<div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Le produit a bien été ajouté à la liste
                            </div>'
        );
        $this->index($data);
    }

    public function produitNonAjoute(){
        $data = array(
            'message' => '<div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                Ce produit est déjà dans votre liste ou n\'est plus disponible
                            </div>'
        );
        $this->index($data);
    }

    public function autocompletion(){
        $query = '';
        $array = array();
        $i = 0;
        if($this->input->get('term'))
        {
            $term = $this->input->get('term');
            $query = $this->product_model->recherche($term);
            foreach ($query->result() as $row)
            {   if ( $i < 9){
                    array_push($array, $row->product_name);
                }else {
                    break;
                }
                $i++;
            }
            echo json_encode($array);
        }
    }

    public function recherche(){

        $output='';
        $config = array();
        $term = urldecode($this->uri->segment(3));

        $config['per_page'] = 20;
        $config['base_url'] = base_url() . "accueil/recherche/".$term;
        $config['total_rows'] = sizeof($this->product_model->recherche($term)->result_array());
        $config['uri_segment'] = 4;
        $config['num_links']=1;

        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination d-flex justify-content-end">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $list = $this->product_model->recherche($term, $config["per_page"], $page)->result_array();

        $this->pagination->initialize($config);

        $datalist['list'] = $list;
        $datalist['links'] = $this->pagination->create_links();
        $data['pseudo'] = $this->session->pseudo;
        $data['title'] = 'Recherche';
        $data['h1'] = 'Résultat de la recherche "'.$term.'" ('.$config['total_rows'].' Produits)';

        $this->load->view('header.php', $data);
        $this->load->view('liste_produit.php', $datalist);
        $this->load->view('footer.php');

    }
}
