<?php

require(APPPATH.'/libraries/MY_REST_Controller.php');

class Files extends MY_REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("date");
		$this->load->model("Editor_model");
		$this->load->model("Editor_datafile_model");
		$this->load->model("Editor_resource_model");
		$this->load->model("Editor_files_model");
		
		$this->load->library("Editor_acl");
		$this->is_authenticated_or_die();
	}

	//override authentication to support both session authentication + api keys
	function _auth_override_check()
	{
		if ($this->session->userdata('user_id')){
			return true;
		}
		parent::_auth_override_check();
	}

	
	/**
	 * 
	 * Project files
	 * 
	 * Return all files for a project
	 * 
	 **/ 
	function index_get($sid=null)
	{		
		try{
			$sid=$this->get_sid($sid);
			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			$this->editor_acl->user_has_project_access($sid,$permission='view');
			$output=$this->Editor_files_model->get_files($sid);
			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * 
	 * upload file
	 * @file_type data | documentation | thumbnail
	 * 
	 **/ 
	function index_post($sid=null,$file_type='documentation')
	{		
		try{
			$sid=$this->get_sid($sid);
			$exists=$this->Editor_model->check_id_exists($sid);
			$user_id=$this->get_api_user_id();
			$user=$this->api_user();

			if(!$exists){
				throw new Exception("Project not found");
			}

			$this->editor_acl->user_has_project_access($sid,$permission='edit',$user);

			if ($file_type=='thumbnail'){
				$output=$this->Editor_resource_model->upload_thumbnail($sid,$file_field_name='file');
				$this->Editor_model->set_project_options($sid,$options=array('thumbnail'=>$output['thumbnail_filename']));
			}else{
				$result=$this->Editor_resource_model->upload_file($sid,$file_type,$file_field_name='file', $remove_spaces=false);
				$uploaded_file_name=$result['file_name'];
				$uploaded_path=$result['full_path'];
				
				$output=array(
					'status'=>'success',
					'uploaded_file_name'=>$uploaded_file_name,
					'uploaded_path'=>$uploaded_path,
					'base64'=>base64_encode($uploaded_file_name)				
				);
			}
						
			//attach to resource if provided
			/*if(is_numeric($resource_id)){
				$options=array(
					'filename'=>$uploaded_file_name
				);
				$this->Survey_resource_model->update($resource_id,$options);
			}*/

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * 
	 * 
	 * Return files and folders for a project with file size information
	 * 
	 */
	function size_get($sid=null,$details=0)
	{
		try{
			$sid=$this->get_sid($sid);
			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			if ($details==1){
				$details=true;
			}else{
				$details=false;
			}

			$this->editor_acl->user_has_project_access($sid,$permission='view');
			$result=$this->Editor_resource_model->files_with_sizes($sid,$details);

			$output=array(
				'result'=>$result
			);

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * 
	 * Return all files and folders for a project
	 * 
	 */
	function tree_get($sid=null,$details=0)
	{
		try{
			$sid=$this->get_sid($sid);
			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			if ($details==1){
				$details=true;
			}else{
				$details=false;
			}

			$this->editor_acl->user_has_project_access($sid,$permission='view');
			
			$this->load->helper("file");
			$project_folder=$this->Editor_model->get_project_folder($sid);
			$result=get_dir_tree($project_folder,$make_relative_to=$project_folder);
			//return $result['files'];       
			

			$output=array(
				'files'=>$result
			);

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	

	/**
	 * 
	 * 
	 * Check if documentation file exists
	 * 
	 * @sid
	 * @filename
	 * @doc_type data | documentation | thumbnail
	 * 
	 */
	function exists_post($sid=null)
	{
		try{
			$sid=$this->get_sid($sid);
			$filename=basename($this->post('file_name'));
			$doc_type=$this->post('doc_type');
			$user=$this->api_user();

			if (!$sid){
				throw new Exception("Missing parameter: sid");
			}

			if (!$filename){
				throw new Exception("Missing parameter: file_name");
			}

			if (!$doc_type){
				throw new Exception("Missing parameter: doc_type");
			}
			
			$this->editor_acl->user_has_project_access($sid,$permission='view',$user);

			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			$result=$this->Editor_resource_model->check_file_exists($sid,$doc_type,$filename);

			$output=array(
				'exists'=>$result
			);

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * 
	 * 
	 * Delete resource file
	 * 
	 */
	function delete_resource_file_post($sid=null,$resource_id=null)
	{

		try{
			$sid=$this->get_sid($sid);
			$user=$this->api_user();

			if (!$sid){
				throw new Exception("Missing parameter: sid");
			}

			if (!$resource_id){
				throw new Exception("Missing parameter: resource_id");
			}
			
			$this->editor_acl->user_has_project_access($sid,$permission='edit',$user);
			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			$result=$this->Editor_resource_model->delete_file_by_resource($sid,$resource_id);

			$output=array(
				'status'=>'success'
			);

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * 
	 * 
	 * Unzip resource file to a tmp folder
	 * 
	 */
	function unzip_resource_post($sid=null,$resource_id=null)
	{

		try{
			$sid=$this->get_sid($sid);
			$user=$this->api_user();

			if (!$sid){
				throw new Exception("Missing parameter: sid");
			}

			if (!$resource_id){
				throw new Exception("Missing parameter: resource_id");
			}
			
			$this->editor_acl->user_has_project_access($sid,$permission='edit',$user);
			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			$result=$this->Editor_resource_model->unzip_resource_file($sid,$resource_id);

			$output=array(
				'status'=>'success'
			);

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * 
	 * 
	 * Unzip file to a tmp folder
	 * 
	 * 
	 */
	function unzip_post($sid=null)
	{

		try{
			$sid=$this->get_sid($sid);
			$user=$this->api_user();
			$file_name=$this->post('file_name');
			$file_name=urldecode($file_name);

			if (!$sid){
				throw new Exception("Missing parameter: sid");
			}

			if (!$file_name){
				throw new Exception("Missing parameter: file_name");
			}

			$this->editor_acl->user_has_project_access($sid,$permission='edit',$user);
			$exists=$this->Editor_model->check_id_exists($sid);

			if(!$exists){
				throw new Exception("Project not found");
			}

			$result=$this->Editor_resource_model->unzip_file($sid,$file_name);

			$output=array(
				'status'=>'success'
			);

			$this->set_response($output, REST_Controller::HTTP_OK);			
		}
		catch(Exception $e){
			$this->set_response($e->getMessage(), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * 
	 * Delete project thumbnail
	 * 
	 */
	function thumbnail_delete($sid=null)
	{
		try{
			$sid=$this->get_sid($sid);
			$user=$this->api_user();

			$this->editor_acl->user_has_project_access($sid,$permission='edit',$user);
			$result=$this->Editor_resource_model->delete_thumbnail($sid);
			
			$response=array(
				'result'=>$result
			);

			$this->set_response($response, REST_Controller::HTTP_OK);
		}
		catch(Exception $e){
			$error_output=array(
				'status'=>'failed',
				'message'=>$e->getMessage()
			);
			$this->set_response($error_output, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//alieas for thumbnail_delete
	function delete_thumbnail_post($sid=null)
	{
		return $this->thumbnail_delete($sid);
	}


	/**
	 * 
	 * Delete file
	 * 
	 */
	function index_delete($sid=null)
	{
		try{
			$sid=$this->get_sid($sid);
			$user=$this->api_user();
			$file_path=$this->input->post('file_path');

			if (!$file_path){
				throw new Exception("Missing parameter: file_path");
			}

			$this->editor_acl->user_has_project_access($sid,$permission='edit',$user);
			$result=$this->Editor_files_model->delete_by_path($sid, $file_path);
			
			
			$response=array(
				'result'=>$result
			);

			$this->set_response($response, REST_Controller::HTTP_OK);
		}
		catch(Exception $e){
			$error_output=array(
				'status'=>'failed',
				'message'=>$e->getMessage()
			);
			$this->set_response($error_output, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	function delete_post($sid=null)
	{
		return $this->index_delete($sid);
	}


	



}
