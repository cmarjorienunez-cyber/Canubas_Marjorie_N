<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: User
 * 
 * Automatically generated via CLI.
 */
class crud_Controller extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('crud_Model');
        $this->call->library('form_validation');

    }

         public function read() 
    {
        
        $page = 1;
        if(isset($_GET['page']) && ! empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if(isset($_GET['q']) && ! empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 2;

        $all = $this->crud_Model->page($q, $records_per_page, $page);
        $data['all'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => 'First',
            'last_link'      => 'Last',
            'next_link'      => 'Next →',
            'prev_link'      => '← Prev',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('bootstrap'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page, '/?q='.$q);
        $data['page'] = $this->pagination->paginate();
        $this->call->view('index', $data);
    }


  public function createUser()
    {
        $this->form_validation
            ->name('student_id')
                ->required()
                ->max_length(50)
            ->name('first_name')
                ->required()
                ->max_length(200)
            ->name('last_name')
                ->required()
                ->max_length(200)
            ->name('course')
                ->required()
                ->max_length(100);

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->get_errors();
            setErrors($errors);
            redirect('/');
        } else {
            $this->crud_Model->insert([
                'student_id' => $_POST['student_id'],
                'first_name' => $_POST['first_name'],
                'last_name'  => $_POST['last_name'],
                'course'     => $_POST['course']
            ]);

            setMessage('success', 'Student registered successfully!');
            redirect('/');
        }
    }



     public function updateUser($id){
        $this->crud_Model->update($id, [
            'student_id' => $_POST['student_id'], // allow updating student_id too
            'first_name' => $_POST['first_name'],
            'last_name'  => $_POST['last_name'],
            'course'     => $_POST['course'],
        ]);
        setMessage('success', 'Student updated successfully!');
        redirect('/');
    }


     public function deleteUser($id){
        $this->crud_Model->delete($id);
        setMessage('danger', 'Student deleted successfully!');
        redirect('/');
    }
}

