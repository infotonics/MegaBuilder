<?php
/* 
 * Generated by MegaBuilder v1.0 
 * www.MegaBuilder.com
 */
 
class %%tblname%% extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('%%tblname%%_model');
    } 

    /*
     * Listing of %%tblname%%
     */
    function index()
    {
        $params['limit'] = RECORDS_PER_PAGE; 
        $params['offset'] = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        
        $config = $this->config->item('pagination');
        $config['base_url'] = site_url('%%tblname%%/index?');
        $config['total_rows'] = $this->%%tblname%%_model->get_all_%%tblname%%_count();
        $this->pagination->initialize($config);

        $data['%%tblname%%'] = $this->%%tblname%%_model->get_all_%%tblname%%($params);
        
        $data['_view'] = '%%tblname%%/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new %%tblname%%
     */
    function add()
    {   
        $this->load->library('form_validation');

		//
		%%validation%%
		
		if($this->form_validation->run())     
        {   
            $params = array(
				//
				%%params%%
            );
            
            $%%tblname%%_id = $this->%%tblname%%_model->add_%%tblname%%($params);
            redirect('%%tblname%%/index');
        }
        else
        {       
			$this->load->model('Work_type_model');
			$data['all_work_type'] = $this->Work_type_model->get_all_work_type();
				
            $data['_view'] = '%%tblname%%/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a %%tblname%%
     */
    function edit($id)
    {   
        // check if the %%tblname%% exists before trying to edit it
        $data['%%tblname%%'] = $this->%%tblname%%_model->get_%%tblname%%($id);
        
        if(isset($data['%%tblname%%']['id']))
        {
            $this->load->library('form_validation');

			//
			%%validation%%
		
			if($this->form_validation->run())     
            {   
                $params = array(
					//
					%%params%%
                );

                $this->%%tblname%%_model->update_%%tblname%%($id,$params);            
                redirect('%%tblname%%/index');
            }
            else
            {
                $data['_view'] = '%%tblname%%/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The %%tblname%% you are trying to edit does not exist.');
    } 

    /*
     * Deleting %%tblname%%
     */
    function remove($id)
    {
        $%%tblname%% = $this->%%tblname%%_model->get_%%tblname%%($id);

        // check if the %%tblname%% exists before trying to delete it
        if(isset($%%tblname%%['id']))
        {
            $this->%%tblname%%_model->delete_%%tblname%%($id);
            redirect('%%tblname%%/index');
        }
        else
            show_error('The %%tblname%% you are trying to delete does not exist.');
    }
    
}