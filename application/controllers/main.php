<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('table');
	}

	public function index() {
		$this->load->view('header');
		$this->load->view('home');
	}

  public function matches() {
    $this->load->view('header');
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
    $this->load->view('header');
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('team');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Team');
    $crud->fields('ID', 'teamName', 'nfa', 'acronym', 'nickname', 'eliminated');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('ID', 'teamName', 'nfa', 'acronym', 'nickname', 'eliminated');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('ID', 'TeamID');
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
    $this->load->view('header');
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('competitor');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Competitor');
    $crud->fields('ID', 'titleID', 'fullName', 'role', 'teamID', 'authorised');

    //set the foreign keys to appear as drop-down menus
    // ('this fk column','referencing table', 'column in referencing table')
    $crud->set_relation('teamID', 'team', 'teamName');
    $crud->set_relation('titleID', 'competitorTitle', 'title');

    //many-to-many relationship with link table see grocery crud website: www.grocerycrud.com/examples/set_a_relation_n_n
    //('give a new name to related column for list in fields here', 'join table', 'other parent table', 'this fk in join table', 'other fk in join table', 'other parent table's viewable column to see in field')
    // $crud->set_relation_n_n('items', 'order_items', 'items', 'invoice_no', 'item_id', 'itemDesc');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('ID', 'titleID', 'fullName', 'role', 'teamID', 'authorised');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('ID', 'CompetitorID');
    $crud->display_as('titleID', 'Title');
    $crud->display_as('fullName', 'Name');
    $crud->display_as('role', 'Role');
    $crud->display_as('teamID', 'Team Name');
    $crud->display_as('authorised', 'Authorised');

    $output = $crud->render();
    $this->competitors_output($output);
  }

  public function competitors_output($output = null) {
    $this->load->view('competitors_view.php', $output);
  }

  public function venues() {
    $this->load->view('header');
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('venue');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Venue');
    $crud->fields('ID', 'venueName', 'stadium');

    //form validation (could match database columns set to "not null")
    $crud->required_fields('ID', 'venueName', 'stadium');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('ID', 'VenueID');
    $crud->display_as('venueName', 'venue Name');
    $crud->display_as('stadium', 'Stadium');

    $output = $crud->render();
    $this->venues_output($output);
  }

  public function venues_output($output = null) {
    $this->load->view('venues_view.php', $output);
  }

  public function cards() {
    $this->load->view('header');
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    //table name exact from database
    $crud->set_table('card');

    //give focus on name used for operations e.g. Add Order, Delete Order
    $crud->set_subject('Card');
    $crud->fields('ID', 'competitorID', 'startDate', 'endDate', 'cardStateID');

    $crud->set_relation('competitorID', 'competitor', 'fullName');
    $crud->set_relation('cardStateID', 'cardState', 'state');

    $crud->required_fields('ID', 'competitorID', 'startDate', 'endDate', 'cardStateID');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('ID', 'CardID');
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
