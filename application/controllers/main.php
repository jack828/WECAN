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
    $crud->field_type('eliminated', 'dropdown', array("0"  => "NO", "1" => "YES"));
    $crud->callback_edit_field('eliminated', function ($value) {
      return ($value == '0') ? "NO" : "YES";
    });

    //form validation (could match database columns set to "not null")
    $crud->required_fields('teamName', 'nfa', 'acronym', 'nickname');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('teamName', 'Team Name');
    $crud->display_as('nfa', 'Association');
    $crud->display_as('acronym', 'Acronym');
    $crud->display_as('nickname', 'Nickname');
    $crud->display_as('eliminated', 'Eliminated');

    $crud->unset_delete();
    $crud->add_action('Eliminate', '', '', 'ui-icon-circle-minus', array($this, 'eliminate_team_url'));

    $output = $crud->render();

    if ($crud->getState() == 'read') {
      // Get competitors
      $teamID = $crud->getStateInfo()->primary_key;
      $competitorCrud = new grocery_CRUD();
      $competitorCrud->set_theme('datatables');

      $competitorCrud->set_table('competitor');
      $competitorCrud->where('teamID', $teamID);

      $competitorCrud->set_subject('Competitors');
      $competitorCrud->columns('titleID', 'fullName', 'role', 'authorised');
      $competitorCrud->field_type('authorised', 'dropdown', array("0"  => "NO", "1" => "YES"));

      $competitorCrud->set_relation('titleID', 'competitorTitle', 'title');

      $competitorCrud->display_as('titleID', 'Title');
      $competitorCrud->display_as('fullName', 'Name');
      $competitorCrud->display_as('role', 'Role');
      $competitorCrud->display_as('authorised', 'Authorised');

      $competitorCrud->unset_operations();

      $state_code = 1; // List state
      $competitors = $competitorCrud->render($state_code);

      // Add the output
      $output->competitors = $competitors;
      // Add access log styling
      $output->js_files = array_merge($competitors->js_files, $output->js_files);
      $output->js_lib_files = array_merge($competitors->js_lib_files, $output->js_lib_files);
      $output->js_config_files = array_merge($competitors->js_config_files, $output->js_config_files);
      $output->css_files = array_merge($competitors->css_files, $output->css_files);

      // Get matches
      $matchesCrud = new grocery_CRUD();
      $matchesCrud->set_theme('datatables');

      $matchesCrud->set_table('matchAccess');
      $matchesCrud->or_where('team1ID', $teamID);
      $matchesCrud->or_where('team2ID', $teamID);

      //give focus on name used for operations e.g. Add Order, Delete Order
      $matchesCrud->set_subject('Match');
      $matchesCrud->fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

      //set the foreign keys to appear as drop-down menus
      // ('this fk column','referencing table', 'column in referencing table')
      $matchesCrud->set_relation('venueID', 'venue', 'venueName');
      $matchesCrud->set_relation('team1ID', 'team', 'teamName');
      $matchesCrud->set_relation('team2ID', 'team', 'teamName');

      //form validation (could match database columns set to "not null")
      $matchesCrud->required_fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

      //change column heading name for readability ('columm name', 'name to display in frontend column header')
      $matchesCrud->display_as('ID', 'Match Number');
      $matchesCrud->display_as('matchDate', 'Match Date');
      $matchesCrud->display_as('venueID', 'Venue');
      $matchesCrud->display_as('team1ID', 'Team 1');
      $matchesCrud->display_as('team2ID', 'Team 2');

      $matchesCrud->unset_operations();

      $state_code = 1; // List state
      $matches = $matchesCrud->render($state_code);

      // Add the output
      $output->matches = $matches;
      // Add access log styling
      $output->js_files = array_merge($matches->js_files, $output->js_files);
      $output->js_lib_files = array_merge($matches->js_lib_files, $output->js_lib_files);
      $output->js_config_files = array_merge($matches->js_config_files, $output->js_config_files);
      $output->css_files = array_merge($matches->css_files, $output->css_files);
    }

    $this->teams_output($output);
  }

  public function eliminate_team_url($primary_key) {
    return site_url('main/eliminate_team/' . $primary_key);
  }

  public function eliminate_team($primary_key) {
    $sql = "UPDATE card, competitor, team SET team.eliminated = TRUE, card.cardStateID = 2, card.endDate = CURDATE(), competitor.authorised = FALSE WHERE card.competitorID = competitor.ID AND competitor.teamID = team.ID AND team.ID = $primary_key;";

    $result = $this->db->query($sql);

    redirect("main/teams", 'refresh');
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
    $crud->field_type('authorised', 'dropdown', array("0"  => "NO", "1" => "YES"));

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

    $crud->callback_after_insert(array($this, 'insert_competitor_callback'));
    $crud->unset_delete();
    $crud->add_action('Eliminate', '', '', 'ui-icon-circle-minus', array($this, 'eliminate_competitor_url'));

    $output = $crud->render();
    $this->competitors_output($output);
  }

  public function insert_competitor_callback($array, $primary_key) {
    $new_card = array(
      "competitorID" => $primary_key,
      "startDate" => date('Y-m-d'),
      "endDate" => "2017-08-06"
    );

    $this->db->insert('card', $new_card);

    return true;
  }

  public function eliminate_competitor_url($primary_key) {
    return site_url('main/eliminate_competitor/' . $primary_key);
  }

  public function eliminate_competitor($primary_key) {
    $now = date('Y-m-d');
    $cardWhere = array(
        'competitorID' => $primary_key
      , 'cardStateID' => 1
    );
    $cardUpdate = array(
        'endDate' => $now
      , 'cardStateID' => 2
    );
    $competitorUpdate = array(
        'authorised' => '0'
    );
    $this->db->update('competitor', $competitorUpdate, "ID = $primary_key");
    $this->db->update('card', $cardUpdate, $cardWhere);

    redirect("main/competitors", 'refresh');
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

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('venueName', 'Venue Name');
    $crud->display_as('stadium', 'Stadium');

    $crud->unset_delete();

    $output = $crud->render();

    if ($crud->getState() == 'read') {
      // Get access logs
      $venueID = $crud->getStateInfo()->primary_key;
      $accessCrud = new grocery_CRUD();
      $accessCrud->set_theme('datatables');
      $accessCrud->set_model('custom_query_model');

      $accessCrud->set_table('venueUsage');

      $accessCrud->set_subject('Access Logs');
      $accessCrud->columns('cardID', 'fullName', 'dateAccessed', 'accessGranted');

      $accessCrud->basic_model->set_query_str("SELECT venueUsage.ID, venueUsage.cardID, competitor.fullName, venueUsage.dateAccessed, venueUsage.accessGranted FROM competitor, card, venueUsage WHERE competitor.ID = card.competitorID AND card.ID = venueUsage.cardID AND venueUsage.venueID = $venueID");

      $accessCrud->field_type('accessGranted', 'dropdown', array("0"  => "NO", "1" => "YES"));

      $accessCrud->display_as('cardID', 'Card ID');
      $accessCrud->unset_columns(array('venueID'));
      $accessCrud->unset_operations();

      $state_code = 1; // List state
      $accessLogs = $accessCrud->render($state_code);

      // Add the output
      $output->accessLogs = $accessLogs;
      // Add access log styling
      $output->js_files = array_merge($accessLogs->js_files, $output->js_files);
      $output->js_lib_files = array_merge($accessLogs->js_lib_files, $output->js_lib_files);
      $output->js_config_files = array_merge($accessLogs->js_config_files, $output->js_config_files);
      $output->css_files = array_merge($accessLogs->css_files, $output->css_files);
    }

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
    $crud->add_fields('competitorID', 'startDate');

    $crud->set_relation('competitorID', 'competitor', 'fullName');
    $crud->set_relation('cardStateID', 'cardState', 'state');

    $crud->required_fields('competitorID');

    //change column heading name for readability ('columm name', 'name to display in frontend column header')
    $crud->display_as('competitorID', 'Competitor Name');
    $crud->display_as('startDate', 'Start Date');
    $crud->display_as('endDate', 'End Date');
    $crud->display_as('cardStateID', 'Card State');

    $crud->unset_delete();
    $crud->unset_edit();
    $crud->unset_add();
    $crud->add_action('Unauthorise/Lost/Stolen', '', '', 'ui-icon-circle-minus', array($this, 'unauthorise_card_url'));

    $output = $crud->render();
    $this->cards_output($output);
  }

  public function unauthorise_card_url($primary_key, $row) {
    return site_url("main/unauthorise_card/$primary_key/" . $row->competitorID);
  }

  public function unauthorise_card($primary_key, $competitorID) {
    // Unauthorise the card first
    $cardUpdate = array(
        'cardStateID' => '3'
      , 'endDate' => date('Y-m-d')
    );
    $cardWhere = array('ID' => $primary_key);

    $result = $this->db->update('card', $cardUpdate, $cardWhere);

    // Reissue new one
    $newCard = array(
        'competitorID' => $competitorID
      , 'startDate' => date('Y-m-d')
      , 'endDate' => '2017-08-06'
      , 'cardStateID' => '1'
    );
    $result = $this->db->insert('card', $newCard);

    redirect('main/cards', 'refresh');
  }

  public function cards_output($output = null) {
    $this->load->view('cards_view.php', $output);
  }
}
