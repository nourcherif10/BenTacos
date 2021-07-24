<?php
/**
 * Class Easyjobs_Admin_Candidates
 * Handles all functionality for candidates in admin area
 * @since 1.0.0
 */
class Easyjobs_Admin_Candidates
{
    /**
     * Easyjobs_Admin_Candidates constructor.
     */
    public function __construct()
    {
        add_action('wp_ajax_easyjobs_search_filter_candidates', array($this, 'search_filter_candidates'));
        add_action('wp_ajax_easyjobs_search_filter_all_candidates', array($this, 'search_filter_all_candidates'));
        add_action('wp_ajax_easyjobs_export_job_candidates', array($this, 'export_job_candidates'));
        add_action('wp_ajax_easyjobs_get_invited_candidates', array($this, 'get_invited_candidates'));
        add_action('wp_ajax_easyjobs_save_candidate_note', array($this, 'save_candidate_note'));
        add_action('wp_ajax_easyjobs_delete_candidate_note', array($this, 'delete_candidate_note'));
        add_action('wp_ajax_easyjobs_delete_candidate', array($this, 'delete_candidate'));
    }

    /**
     * Show all candidates
     * @since 1.0.0
     * @param int $job_id
     * @return void
     */
    public function show_job_candidates($job_id)
    {
        $data = $this->get_job_candidates($job_id);
        $candidates = $data->candidates;
        $pipelines = $data->job->pipeline;
        $job = Easyjobs_Helper::get_job($job_id);
        $ai_enabled = Easyjobs_Helper::is_ai_enabled();
        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-candidates-display.php';
    }
    /**
     * Get job candidates
     * @since 1.0.0
     * @param int $job_id
     * @return object | bool
     */
    public function get_job_candidates($job_id)
    {
        $candidates = Easyjobs_Api::get_by_id('job', $job_id,'candidates');
        if($candidates && $candidates->status == 'success'){
            return $candidates->data;
        }
        return false;
    }

    /**
     * Ajax callback for 'easyjobs_search_filter_candidates'
     * Handles search and filter candidates
     * @since 1.0.0
     * @return void
     */
    public function search_filter_candidates()
    {
        if(!isset($_POST['job_id']) && !isset($_POST['parameters'])){
            return;
        };
        echo json_encode($this->get_results(
            $_POST['job_id'],
            $this->build_search_keyword($_POST['parameters'])
        ));
        wp_die();
    }

    /**
     * @param $id
     */
    public function show_details($id)
    {
        $data = $this->get_details($id);
        $ai_enabled = Easyjobs_Helper::is_ai_enabled();
        $notes = $this->get_notes($id);
        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-candidate-details.php';
    }

    /**
     * Show all candidates
     * @param array $parameters
     * @return void
     */
    public function show_all_candidates($parameters)
    {
        $candidates = [];
        $total_page = 1;
        $current_page = 1;
        
        $jobs = $this->get_company_jobs();
        $ai_enabled = Easyjobs_Helper::is_ai_enabled();
        $candidates_response = $this->get_company_candidates($parameters);
        
        if(!empty($candidates_response->data)){
            $candidates = $candidates_response->data;
            $total_page = (int)ceil($candidates_response->total / $candidates_response->per_page);
            $current_page = (int)$candidates_response->current_page;
        }
        
        include EASYJOBS_ADMIN_DIR_PATH . 'partials/easyjobs-all-candidates.php';
    }

    /**
     *
     */
    public function search_filter_all_candidates()
    {
        $parameters = [];
        if(isset($_POST['parameters'])){
            foreach ($_POST['parameters'] as $key => $value){
                $parameters[sanitize_text_field($key)] = sanitize_text_field($value);
            }
        };
        echo json_encode(Easyjobs_Api::get('company_candidates', $parameters));
        wp_die();
    }

    /**
     * ajax callback for export candidates
     * @since 1.3.1
     */
    public function export_job_candidates()
    {
        if(!isset($_POST['job_id']) || empty($_POST['job_id'])){
            echo Easyjobs_Helper::get_error_response('Job id not provided');
        }

        echo Easyjobs_Helper::get_generic_response(Easyjobs_Api::search_within_job(
            abs($_POST['job_id']),
            '',
            $this->build_search_keyword($_POST['keywords']),
            EASYJOBS_APP_URL . '/api/v1/job/' . abs($_POST['job_id']) . '/candidates/export'
        ));

        wp_die();
    }

	/**
	 *
	 */
	public function get_invited_candidates()
    {
        if(!isset($_POST['job_id']) || empty($_POST['job_id'])){
            echo Easyjobs_Helper::get_error_response('Job id not provided');
        }
        echo Easyjobs_Helper::get_generic_response(Easyjobs_Api::get_by_id(
            'job',
            abs($_POST['job_id']),
            'invitations'
        ));
        wp_die();
    }

	/**
	 * Ajax callback for save candidate note
	 * @return void
	 * @since 1.3.7
	 */
	public function save_candidate_note()
	{
		if(!isset($_POST['candidate_id']) || empty($_POST['candidate_id'])){
			echo Easyjobs_Helper::get_error_response('Candidate id not provided');
			wp_die();
		}
		$data = [];
		foreach ($_POST['form_data'] as $d){
			if($d['name'] == 'note'){
				if(empty($d['value'])){
					echo Easyjobs_Helper::get_error_response('Please write some note');
					wp_die();
				}
				$data['note'] = sanitize_text_field($d['value']);
			}
			if($d['name'] == 'tag_select'){
				$data['tags'][] = json_decode(stripslashes($d['value']));
			}
		}
		echo Easyjobs_Helper::get_generic_response(
			Easyjobs_Api::post(
				'save_candidate_note',
				abs($_POST['candidate_id']),
				$data
			)
		);
		wp_die();

    }
	/**
	 * Ajax callback for delete candidate note
	 * @return void
	 * @since 1.3.7
	 */
	public function delete_candidate_note()
	{
		if(!isset($_POST['candidate_id']) || empty($_POST['candidate_id'])){
			echo Easyjobs_Helper::get_error_response('Candidate id not provided');
			wp_die();
		}
		if(!isset($_POST['note_id']) || empty($_POST['note_id'])){
			echo Easyjobs_Helper::get_error_response('Note id not provided');
			wp_die();
		}
		echo Easyjobs_Helper::get_generic_response(
			Easyjobs_Api::post_custom(EASYJOBS_API_URL.'job/applicants/'. abs($_POST['candidate_id']) . '/note/'. abs($_POST['note_id']) . '/delete')
		);
		wp_die();
    }


	public function delete_candidate()
	{
		if(!isset($_POST['candidates']) || empty($_POST['candidates'])){
			echo Easyjobs_Helper::get_error_response('Candidates not provided');
			wp_die();
		}
		if(!isset($_POST['job']) || empty($_POST['job'])){
			echo Easyjobs_Helper::get_error_response('Job not provided');
			wp_die();
		}

		$response = Easyjobs_Api::post('delete_candidate',abs($_POST['job']), [
			'candidates' => $_POST['candidates']
		]);
		if(Easyjobs_Helper::is_success_response($response->status)){
			echo json_encode([
				'status'=> 'success',
				'message' => __('Candidate deleted successfully', 'easyjobs')
			]);
		}else{
			echo json_encode([
				'status'=> 'error',
				'message' => !empty($response->data->message) ? $response->data->message : __('Unable to delete candidate', 'easyjobs')
			]);
		}
		wp_die();
    }


    /**
     * @param $id
     * @return mixed
     */
    private function get_details($id)
    {
        $candidate_details =  Easyjobs_Api::get_by_id('candidate', $id);
        if($candidate_details == null){
            return false;
        }
        if($candidate_details->status == 'success'){
            return $candidate_details->data;
        }
        return false;
    }

    /**
     * Get search and filtered candidates from api
     * @since 1.0.0
     * @param int $job_id
     * @param string $keywords
     * @return bool|object
     */
    private function get_results($job_id, $keywords)
    {
        $results = Easyjobs_Api::search_within_job($job_id,'job_candidates', $keywords);
        if ($results && $results->status == 'success') {
            return (object) array(
                'status' => 'success',
                'candidates' => $results->data->candidates
            );
        }
        return false;
    }
    
    /**
     * Get company jobs
     * @since 2.0.0
     * @return object|bool
     */
    private function get_company_jobs()
    {
        $results = Easyjobs_Api::get('company_jobs');
        if ($results && $results->status == 'success') {
            return $results->data;
        }
        return false;
    }
    
    /**
     * Get company candidates
     * @param array $parameters
     * @return object|bool
     * @since 2.0.0
     */
    
    private function get_company_candidates(array $parameters)
    {
        $results = Easyjobs_Api::get('company_candidates',$parameters);
        if ($results && $results->status == 'success') {
            return $results->data;
        }
        return false;
    }

    private function build_search_keyword($parameters)
    {
        $keywords_arr = array();
        foreach ($parameters as $k => $val){
            if($k== 'filter'){
                foreach ($val as $v){
                    $keywords_arr[] = 'basic[]='.$v;
                }
            }else{
                $value = sanitize_text_field($val);
                $key = sanitize_text_field($k);
                if(!empty($value) || $value == 0){
                    if($key == 'search'){
                        $keywords_arr[] = $key . '=' . urlencode($value);
                    }else{
                        $keywords_arr[] = $key . '=' . $value;
                    }
                }
            }
        }
        return implode('&', $keywords_arr);
    }

	private function get_notes($candidate_id)
	{
		$notes = Easyjobs_Api::get_by_id('candidate_note', $candidate_id, 'note');
		if($notes == null){
			return null;
		}
		if(Easyjobs_Helper::is_success_response($notes->status)){
			return $notes->data;
		}
		return null;
    }

}