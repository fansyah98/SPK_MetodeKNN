<?php 
class Datatraining2 extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('m_admin2');
		$this->load->helper(array('form', 'url'));

		if($this->session->userdata('status') != "login"){
			redirect(base_url("login"));
		}
	}
	function index(){
		$data['datatraining2'] = $this->m_admin2->tampildatatraining()->result();
		$this->load->view('v_admindt/table-basicdengankotak',$data, array('error' => ' ' ));
	}
	function tambah_aksilayak(){
		$cek1 = false;
		$dir = "gambar/hasil/data_trainingdengankotak/layakangkut/";
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					$cek=explode(".", $file);
					if ($cek[1]=="jpg") {
						$this->load->library('image_lib');
						$config['image_library']='gd2';
						$config['source_image']='./gambar/hasil/data_trainingdengankotak/layakangkut/'.$file;
						$config['create_thumb']= FALSE;
						$config['maintain_ratio']= FALSE;
						$config['quality']= '100%';
						$config['width']= 640;
						$config['height']= 480;
						$config['new_image']= './gambar/hasil/data_trainingdengankotak/layakangkut/'.$file;
						$this->image_lib->clear();
						$this->image_lib->initialize($config);
						$this->image_lib->resize();

						$imlayak = imagecreatefromjpeg("gambar/hasil/data_trainingdengankotak/layakangkut/".$file);	
						$rata=0;	
						$r=$g=$b=0;
						for ($i=160; $i < 318; $i+=10) { 
							for ($j=213; $j < 425; $j+=10) { 
								$rgb = imagecolorat($imlayak, $j, $i);
								$r += ($rgb >> 16) & 0xFF;
								$g += ($rgb >> 8) & 0xFF;
								$b += $rgb & 0xFF;
								$rata++;	
							}
						}
						// $imlayak = imagecreatefromjpeg("gambar/hasil/data_trainingdengankotak/layakangkut/".$file);
						// $rgb = imagecolorat($imlayak, 320, 240);
						// $r = ($rgb >> 16) & 0xFF;
						// $g = ($rgb >> 8) & 0xFF;
						// $b = $rgb & 0xFF;
						$cek = $this->db->query("SELECT * FROM datatraining2 where namafile like '%".$file."%'")->num_rows();
						if ($cek>0){

						}else{
							$cek1 = true;
							$data = array(
								'namafile' => "layakangkut/".$file,
								'r' => $r/$rata,
								'g' => $g/$rata,
								'b' => $b/$rata,
								// 'r' => $r,
								// 'g' => $g,
								// 'b' => $b,
								"label" => 'Layak'
							);
							$this->m_admin2->inputdatatraining($data,'datatraining2');
						}
					}
				}
				$this->tambah_aksitidaklayak($cek1);
				closedir($dh);
			}
		}
	}
	function tambah_aksitidaklayak($cek2){
		$cek1 = $cek2;
		$dir = "gambar/hasil/data_trainingdengankotak/tidaklayakangkut/";
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					$cek=explode(".", $file);
					if ($cek[1]=="jpg") {
						$this->load->library('image_lib');
						$config['image_library']='gd2';
						$config['source_image']='./gambar/hasil/data_trainingdengankotak/tidaklayakangkut/'.$file;
						$config['create_thumb']= FALSE;
						$config['maintain_ratio']= FALSE;
						$config['quality']= '100%';
						$config['width']= 640;
						$config['height']= 480;
						$config['new_image']= './gambar/hasil/data_trainingdengankotak/tidaklayakangkut/'.$file;
						$this->image_lib->clear();
						$this->image_lib->initialize($config);
						$this->image_lib->resize();

						$imtidaklayak = imagecreatefromjpeg("gambar/hasil/data_trainingdengankotak/tidaklayakangkut/".$file);
						$rata=0;	
						$r=$g=$b=0;
						for ($i=160; $i < 318; $i+=10) { 
							for ($j=213; $j < 425; $j+=10) { 
								$rgb = imagecolorat($imtidaklayak, $j, $i);
								$r += ($rgb >> 16) & 0xFF;
								$g += ($rgb >> 8) & 0xFF;
								$b += $rgb & 0xFF;
								$rata++;	
							}
						}
						// $imtidaklayak = imagecreatefromjpeg("gambar/hasil/data_trainingdengankotak/tidaklayakangkut/".$file);
						// $rgb = imagecolorat($imtidaklayak, 320, 240);
						// $r = ($rgb >> 16) & 0xFF;
						// $g = ($rgb >> 8) & 0xFF;
						// $b = $rgb & 0xFF;
						$cek = $this->db->query("SELECT * FROM datatraining2 where namafile like '%".$file."%'")->num_rows();
						if ($cek>0){	
							
						}
						else{
							$cek1 = true;
							$data = array(
								'namafile' => "tidaklayakangkut/".$file,
								'r' => $r/$rata,
								'g' => $g/$rata,
								'b' => $b/$rata,
								// 'r' => $r,
								// 'g' => $g,
								// 'b' => $b,
								"label" => 'Tidak Layak'
							);
							$this->m_admin2->inputdatatraining($data,'datatraining2');
						}
					}
				}
				if($cek1){
					$error = array('error' => $this->session->set_flashdata('Berhasil', '<p> Data Berhasil Diperbaharui</p>'));
					$this->load->view('v_admindt/table-basicdengankotak', $error);
				}
				else{
					$error = array('error' => $this->session->set_flashdata('Gagal', '<p> Data Telah Ada!</p>'));
					$this->load->view('v_admindt/table-basicdengankotak', $error);
				}
				closedir($dh);
			}
		}
	}
	function tambahdt(){
		$this->tambah_aksilayak();		
		redirect('datatraining2');
	}
	function hapus($a,$b){
		$group_id = $this->input->post('ID');
		$group_picture = $this->input->post('namafile');
		unlink(FCPATH."/gambar/hasil/data_trainingdengankotak/".$a."/".$b);
		$this->m_admin2->hapus_data($group_id, $a."/".$b);
		redirect('datatraining2');
	}
	function hapusemua(){
		$this->db->empty_table('datatraining2');
		$path='./gambar/hasil/data_trainingdengankotak/';
		$this->load->helper("file"); // load the helper
		delete_files($path, true); // delete all files/folders
		rmdir($path);
		redirect('datatraining2');
	}
}