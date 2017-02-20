<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();

class Main extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('table');
	}

	public function index() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $this->load->view('home');
	}

  function ensure_logged_in() {
    if($this->session->userdata('logged_in')) {
      $session_data = $this->session->userdata('logged_in');
      $data = array();
      $data['username'] = $session_data['username'];

      return $data;
    } else {
      return false;
    }
  }

  public function logout() {
    $this->session->unset_userdata('logged_in');
    session_destroy();
    redirect('login', 'refresh');
  }

  public function matches() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('matchAccess');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Match');
    $crud->fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

    //set the foreign keys to appear as drop-down menus
    // ('this fk column','referencing table', 'column in referencing table')
    $crud->set_relation('venueID', 'venue', 'venueName');
    $crud->set_relation('team1ID', 'team', 'teamName');
    $crud->set_relation('team2ID', 'team', 'teamName');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('ID', 'Match Number');
    $crud->display_as('matchDate', 'Match Date');
    $crud->display_as('venueID', 'Venue');
    $crud->display_as('team1ID', 'Team 1');
    $crud->display_as('team2ID', 'Team 2');

    $output = $crud->render();
    $this->matches_output($output);
  }

  public function matches_output($output = null) {
    $this->load->view('matches_view.php', $output);
  }

  public function teams() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('team');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Team');
    $crud->fields('teamName', 'nfa', 'acronym', 'nickname', 'eliminated');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('teamName', 'nfa', 'acronym', 'nickname', 'eliminated');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('teamName', 'Team Name');
    $crud->display_as('nfa', 'Association');
    $crud->display_as('acronym', 'Acronym');
    $crud->display_as('nickname', 'Nickname');
    $crud->display_as('eliminated', 'Eliminated');

    $output = $crud->render();
    $this->teams_output($output);
  }

  public function teams_output($output = null) {
    $this->load->view('teams_view.php', $output);
  }

  public function competitors() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('competitor');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Competitor');
    $crud->fields('titleID', 'fullName', 'role', 'teamID', 'authorised');

    //set the foreign keys to appear as drop-down menus
    // ('this fk column','referencing table', 'column in referencing table')
    $crud->set_relation('teamID', 'team', 'teamName');
    $crud->set_relation('titleID', 'competitorTitle', 'title');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('titleID', 'fullName', 'role', 'teamID', 'authorised');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('titleID', 'Title');
    $crud->display_as('fullName', 'Name');
    $crud->display_as('role', 'Role');
    $crud->display_as('teamID', 'Team Name');
    $crud->display_as('authorised', 'Authorised');

    $crud->callback_after_insert(array($this, 'insert_card_callback'));

    $output = $crud->render();
    $this->competitors_output($output);
  }

  public function insert_card_callback($array, $primary_key) {
    $new_card = array(
      "competitorID" => $primary_key,
      "startDate" => date('Y-m-d'),
      "endDate" => "2017-08-06"
    );

    $this->db->insert('card', $new_card);

    return true;
  }

  public function competitors_output($output = null) {
    $this->load->view('competitors_view.php', $output);
  }

  public function venues() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('venue');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Venue');
    $crud->fields('venueName', 'stadium');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('venueName', 'stadium');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('venueName', 'Venue Name');
    $crud->display_as('stadium', 'Stadium');

    $output = $crud->render();
    $this->venues_output($output);
  }

  public function venues_output($output = null) {
    $this->load->view('venues_view.php', $output);
  }

  public function cards() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('card');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Card');
    $crud->fields('competitorID', 'startDate', 'endDate', 'cardStateID');

    $crud->set_relation('competitorID', 'competitor', 'fullName');
    $crud->set_relation('cardStateID', 'cardState', 'state');

    $crud->required_fields('competitorID', 'startDate', 'endDate', 'cardStateID');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('competitorID', 'Competitor Name');
    $crud->display_as('startDate', 'Start Date');
    $crud->display_as('endDate', 'End Date');
    $crud->display_as('cardStateID', 'Card State');

    $output = $crud->render();
    $this->cards_output($output);
  }

  public function cards_output($output = null) {
    $this->load->view('cards_view.php', $output);
  }
}
