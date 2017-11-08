<?php
// MD4 - YA - auto distribution ticket
class Ctr_Helpcso_Login extends CI_Controller {
		function __construct(){
			parent::__construct();
            $this->load->model('mdl_helpcso_login');
            $this->load->model('user/mdl_user');
		}
		function index() {
            $data['message']='';
			$this->load->view('helpcso_login',$data);
		}
		function cek_user() {
                $username=$this->input->post('text-username');
                $password=$this->input->post('text-password');
                $cek_login=$this->mdl_helpcso_login->cek_login($username,$password);
                $user_id='';
                $user_name='';
                $level='';
				$status_active='';
				$user_group_id='';
                foreach ($cek_login as $p) {
                    $user_id=$p->user_id;
                    $user_name=$p->user_name;
                    $level=$p->level;
					$status_active=$p->status_active;
					$user_group_id=$p->group_id;
                }
                if ($user_id=='' || $status_active== '2'){
                    redirect('ctr_helpcso_login/error_login','location');
                }
                else {
					$session = array('session_user_id'=>$user_id,'session_user_name'=>$user_name,'session_level'=>$level,'session_user_group_id'=>$user_group_id);
                    $this->session->set_userdata($session);
                    if ($level=='1'){    
                        redirect('admin/ctr_home_admin/index');
                    }
					else {
                        // MD4
                        $status_login = "true";
                        $this->mdl_user->update_status_login($user_id, $status_login);
                        // MD4
						redirect("user/ctr_welcome/index");
					}
                }
        }
        
        function error_login(){
                $data['message']='Invalid Username or Password !';
                $this->load->view('helpcso_login',$data);
        }
		

        function confirm_logout()
        {
            $this->cso_template->view("admin/home_admin/confirm_logout");
        }

		function logout(){
            // MD4
            $userid=$this->session->userdata('session_user_id');
            $status_login = "false";
            // MD4
            $this->mdl_user->update_status_login($userid, $status_login);
            $session = array('user_id'=>'','user_name'=>'','level'=>'','user_group_id'=>'');
            $this->session->set_userdata($session);
            redirect('ctr_helpcso_login/index');
        }
}
?>