<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dataset_manager{

    private $ci;
    private $types=array(
        'survey'=>'microdata',
        'geospatial'=>'geospatial',
        'timeseries'=>'timeseries',
        'document'=>'document',
        'image'=>'image',
        'video'=>'video',
        'table'=>'table',
        'script'=>'script',
        'visualization'=>'visualization'
    );

    function __construct($params=array())
    {
        $this->ci =& get_instance();
        $this->ci->load->model("Dataset_model");
        $this->ci->load->model("Facet_model");
        $this->ci->load->model("Dataset_microdata_model");
        $this->ci->load->model("Dataset_timeseries_model");
        $this->ci->load->model("Dataset_geospatial_model");
        $this->ci->load->model("Dataset_document_model");
        $this->ci->load->model("Dataset_image_model");
        $this->ci->load->model("Dataset_script_model");
        $this->ci->load->model("Dataset_table_model");
        $this->ci->load->model("Dataset_visualization_model");
        $this->ci->load->model("Dataset_video_model");
        $this->ci->load->helper("Array");
    }

    function create_dataset($type,$options)
    {
        $this->validate_type($type);
        $new_id=$this->ci->{'Dataset_'.$this->types[$type].'_model'}->create_dataset($type,$options);

        if ($new_id>0){            
            $this->ci->Facet_model->index_facets($new_id);
        }
        return $new_id;
    }


    function update_dataset($sid,$type,$options,$merge_data=false)
    {
        $this->validate_type($type);
        $result=$this->ci->{'Dataset_'.$this->types[$type].'_model'}->update_dataset($sid,$type,$options, $merge_data);
        $this->ci->Facet_model->index_facets($sid);
        return $result;
    }


    private function validate_type($type)
    {
        if(!$this->is_valid_type($type)){
            throw new Exception("INVALID-TYPE::supported types are: ". implode(", ", array_keys($this->types)));
        }
    }
    
    function is_valid_type($type)
    {
        if (!array_key_exists($type,$this->types)){
            return false;
        }

        return true;
    }

    function get_idno($sid)
    {
        return $this->ci->Dataset_model->get_idno($sid);    
    }


    function find_by_idno($idno)
    {
        return $this->ci->Dataset_model->find_by_idno($idno);    
    }


    /**
     * 
     * Return all rows
     * 
     */
    function get_all($limit=100,$offset=0,$fields=array())
    {
        return $this->ci->Dataset_model->get_all($limit,$offset,$fields);
    }



    /**
     * 
     * Return total number of studies in the catalog
     * 
     */
    function get_total_count()
    {
        return $this->ci->Dataset_model->get_total_count();
    }


    function get_list_by_type($dataset_type, $limit, $start)
    {
        return $this->ci->Dataset_model->get_list_by_type($dataset_type, $limit, $start);
    }


    /**
     * 
     * Return a single row with minimum fields
     * 
     */
    function get_row($sid)
    {
        return $this->ci->Dataset_model->get_row($sid);
    }


    /**
     * 
     * Get metadata
     * 
     */
    function get_metadata($sid,$type=null)
    {
        if (empty($type)){
            return $this->ci->Dataset_model->get_metadata($sid);
        }else{
            return $this->ci->{'Dataset_'.$this->types[$type].'_model'}->get_metadata($sid);
        }
    }


    /**
     * 
     * 
     * Reload facets/filters data
     * 
     */
    function refresh_filters($sid)
    {
        $dataset=$this->get_row($sid);
        $metadata=$this->get_metadata($sid);
        $type=$dataset['type'];

        return $this->ci->{'Dataset_'.$this->types[$type].'_model'}->update_filters($sid,$metadata);
    }


    /**
     * 
     * 
     * Repopulate dataset index
     * 
     */
    function repopulate_index($sid)
    {
        //return $this->ci->Dataset_model->repopulate_index($sid);

        $metadata=$this->ci->Dataset_model->get_metadata($sid);
		$type=$this->ci->Dataset_model->get_type($sid);

		$data=array(
			'keywords'=>$this->ci->Dataset_model->extract_keywords($metadata,$type)
		);

		if($type=='survey'){
			$this->ci->Dataset_microdata_model->index_variable_data($sid);
		}
		
		$this->ci->db->where('id',$sid);
		return $this->ci->db->update('surveys',$data);
    }




    function validate_options($options)
    {
        return $this->ci->Dataset_model->validate_options($options);
    }
    

    public function get_data_access_type_id($name)
    {
        return $this->ci->Dataset_model->get_data_access_type_id($name);
    }

    /**
     * 
     * Setup project folder
     * 
     */
    function setup_folder($repositoryid, $folder_name)
    {
        return $this->ci->Dataset_model->setup_folder($repositoryid, $folder_name);
    }


    /**
     * 
     * Update dataset options
     * 
     */
    function update_options($dataset_id,$update_options)
    {
        return $this->ci->Dataset_model->update_options($dataset_id,$update_options);
    }


    function update_varcount($sid)
    {
        return $this->ci->Dataset_model->update_varcount($sid);
    }

    function delete($sid)
    {
        return $this->ci->Dataset_model->delete($sid);
    }

    function set_data_access_type($sid,$da_type,$da_link)
    {
        return $this->ci->Dataset_model->set_data_access_type($sid,$da_type,$da_link);
    }

    function update_sid($old_sid,$new_id)
    {
        return $this->ci->Dataset_model->update_sid($old_sid,$new_id);
    }

    function set_publish_status($sid,$publish_status)
    {
        return $this->ci->Dataset_model->set_publish_status($sid,$publish_status);
    }

    function remove_datafile_variables($sid,$file_id)
    {
        return $this->ci->Dataset_microdata_model->remove_datafile_variables($sid,$file_id);
    }

    function index_variable_data($sid)
    {
        return $this->ci->Dataset_microdata_model->index_variable_data($sid);
    }

    function get_dataset_with_tags($idno=null)
    {
        return $this->ci->Dataset_model->get_dataset_with_tags($idno);
    }


    function get_dataset_aliases($idno=null)
    {
        return $this->ci->Dataset_model->get_dataset_aliases($idno);
    }

}

/* End of file Dataset_manager.php */
/* Location: ./application/libraries/Dataset_manager.php */
