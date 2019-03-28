<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormConnexion extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');

    }

    function index(){

        $email = $this->input->post('email');
        $mdp = $this->input->post('mdp');

        $user = $this->user_model->verifieConnexion($email,$mdp);
        if ( $user == FALSE ){
            redirect('accueil/erreurconnexion');
        }else {
            $this->session->set_userdata('mail', $user->user_mail);
            $this->session->set_userdata('pseudo', $user->user_pseudo);
            redirect('accueil/connecte');
        }
    }

    function inscription(){

        $email = $this->input->post('email');
        $mdp = $this->input->post('mdp');
        $pseudo = $this->input->post('pseudo');

        if (!$this->user_model->userExist($email)){
            if ( $this->user_model->insertUser($email, $mdp, $pseudo)){
                $this->session->set_userdata('mail', $email);
                $this->session->set_userdata('pseudo', $pseudo);
                redirect('accueil/inscrit');
            }
        }else {
            redirect('accueil/erreurinscription');
        }
    }
}