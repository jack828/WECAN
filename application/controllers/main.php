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
    $this->load->view('footer');
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

  public function match() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    $crud->set_table('matchAccess');

    $crud->set_subject('Match');
    $crud->fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

    $crud->set_relation('venueID', 'venue', 'venueName')
          ->set_relation('team1ID', 'team', 'teamName')
          ->set_relation('team2ID', 'team', 'teamName');

    $crud->required_fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

    $crud->display_as('ID', 'Match Number')
          ->display_as('matchDate', 'Match Date')
          ->display_as('venueID', 'Venue')
          ->display_as('team1ID', 'Team 1')
          ->display_as('team2ID', 'Team 2');

    $output = $crud->render();
    if ($crud->getState() == 'read') {
      // Get a list of every competitor authorised for the match
      $matchID = $crud->getStateInfo()->primary_key;
      $matchCrud = new grocery_CRUD();
      $matchCrud->set_theme('datatables');

      $matchCrud->set_model('custom_query_model');

      $matchCrud->set_table('matchAccess');
      $matchCrud->set_subject('Authorised Competitors');
      $matchCrud->columns('ID', 'title', 'fullName', 'role', 'teamName');

      $sql = "SELECT matchAccess.ID, competitorTitle.title, competitor.fullName, competitor.role, team.teamName"
          . " FROM matchAccess"
          . " LEFT JOIN team"
          . "  ON (team.ID = matchAccess.team1ID OR team.ID = matchAccess.team2ID)"
          . " LEFT JOIN competitor"
          . "  ON (competitor.teamID = team.ID)"
          . " LEFT JOIN card"
          . "  ON (card.competitorID = competitor.ID)"
          . " LEFT JOIN competitorTitle"
          . "  ON (competitor.titleID = competitorTitle.ID)"
          . " WHERE competitor.Authorised = TRUE"
          . "  AND card.cardStateID = 1"
          . "  AND matchAccess.ID = $matchID;";
      $matchCrud->basic_model->set_query_str($sql);

      $matchCrud->display_as('title', 'Title')
                ->display_as('fullName', 'Full Name')
                ->display_as('role', 'Role')
                ->display_as('teamName', 'Team');

      $matchCrud->unset_delete();
      $matchCrud->unset_add();
      $matchCrud->unset_print();
      $matchCrud->unset_export();
      $matchCrud->add_action('Eliminate', '', '', 'fa-minus-circle', array($this, 'eliminate_competitor_url'));
      $matchCrud->unset_columns(array('ID'));

      $state_code = 1; // List state
      $competitors = $matchCrud->render($state_code);

      // Add the output
      $output->competitors = $competitors;
      // Add access log styling
      $output->js_files = array_merge($competitors->js_files, $output->js_files);
      $output->js_lib_files = array_merge($competitors->js_lib_files, $output->js_lib_files);
      $output->js_config_files = array_merge($competitors->js_config_files, $output->js_config_files);
      $output->css_files = array_merge($competitors->css_files, $output->css_files);
    }

    $this->matches_output($output);
    $this->load->view('footer');
  }

  public function matches_output($output = null) {
    $this->load->view('matches_view.php', $output);
  }

  public function team() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    $crud->set_table('team');

    $crud->set_subject('Team');
    $crud->fields('teamName', 'nfa', 'acronym', 'nickname', 'eliminated');
    $crud->field_type('eliminated', 'dropdown', array("0"  => "NO", "1" => "YES"));
    $crud->callback_edit_field('eliminated', function ($value) {
      return ($value == '0') ? "NO" : "YES";
    });

    $crud->required_fields('teamName', 'nfa', 'acronym', 'nickname');

    $crud->display_as('teamName', 'Team Name')
          ->display_as('nfa', 'Association')
          ->display_as('acronym', 'Acronym')
          ->display_as('nickname', 'Nickname')
          ->display_as('eliminated', 'Eliminated');

    $crud->unset_delete();
    $crud->add_action('Eliminate', '', '', 'fa-minus-circle', array($this, 'eliminate_team_url'));

    $output = $crud->render();

    if ($crud->getState() == 'read') {
      // Get competitors
      $teamID = $crud->getStateInfo()->primary_key;
      $competitorCrud = new grocery_CRUD();
      $competitorCrud->set_theme('datatables');

      $competitorCrud->set_table('competitor')
                      ->where('teamID', $teamID);

      $competitorCrud->set_subject('Competitor');
      $competitorCrud->columns('titleID', 'fullName', 'role', 'authorised');
      $competitorCrud->field_type('authorised', 'dropdown', array("0"  => "NO", "1" => "YES"));

      $competitorCrud->set_relation('titleID', 'competitorTitle', 'title');

      $competitorCrud->display_as('titleID', 'Title')
                      ->display_as('fullName', 'Name')
                      ->display_as('role', 'Role')
                      ->display_as('authorised', 'Authorised');

      $competitorCrud->unset_delete();
      $competitorCrud->unset_add();
      $competitorCrud->unset_export();
      $competitorCrud->unset_print();
      $competitorCrud->add_action('Eliminate', '', '', 'fa-minus-circle', array($this, 'eliminate_competitor_url'));

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

      $matchesCrud->set_table('matchAccess')
                  ->or_where('team1ID', $teamID)
                  ->or_where('team2ID', $teamID);

      //give focus on name used for operations e.g. Add Order, Delete Order
      $matchesCrud->set_subject('Match');
      $matchesCrud->fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

      //set the foreign keys to appear as drop-down menus
      // ('this fk column','referencing table', 'column in referencing table')
      $matchesCrud->set_relation('venueID', 'venue', 'venueName')
                  ->set_relation('team1ID', 'team', 'teamName')
                  ->set_relation('team2ID', 'team', 'teamName');

      //form validation (could match database columns set to "not null")
      $matchesCrud->required_fields('ID', 'matchDate', 'venueID', 'team1ID', 'team2ID');

      //change column heading name for readability ('columm name', 'name to display in frontend column header')
      $matchesCrud->display_as('ID', 'Match Number')
                  ->display_as('matchDate', 'Match Date')
                  ->display_as('venueID', 'Venue')
                  ->display_as('team1ID', 'Team 1')
                  ->display_as('team2ID', 'Team 2');

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
    $this->load->view('footer');
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

  public function competitor() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    $crud->set_table('competitor');

    $crud->set_subject('Competitor');
    $crud->edit_fields('titleID', 'fullName', 'role', 'teamID', 'authorised');

    $crud->field_type('authorised', 'dropdown', array("0"  => "NO", "1" => "YES"));
    $crud->callback_edit_field('authorised', function ($value) {
      return ($value == '0') ? "NO" : "YES";
    });

    $crud->set_relation('teamID', 'team', 'teamName')
          ->set_relation('titleID', 'competitorTitle', 'title');

    $crud->required_fields('titleID', 'fullName', 'role', 'teamID', 'authorised');

    $crud->display_as('titleID', 'Title')
          ->display_as('fullName', 'Name')
          ->display_as('role', 'Role')
          ->display_as('teamID', 'Team Name')
          ->display_as('authorised', 'Authorised');

    $crud->callback_after_insert(array($this, 'insert_competitor_callback'));
    $crud->unset_delete();
    $crud->add_action('Eliminate', '', '', 'fa-minus-circle', array($this, 'eliminate_competitor_url'));

    $output = null;

    if($crud->getState() == 'add') {
      $crud->add_fields('titleID', 'fullName', 'role', 'teamID', 'authorised', 'startDate');
      $crud->callback_add_field('startDate', array($this, 'start_date_callback'));
      $crud->change_field_type('startDate', 'datetime');

      $crud->display_as('startDate', 'Authorised From');

      // Necessary for custom datepicker
      $crud->set_js('assets/grocery_crud/js/jquery-1.10.2.min.js');
      $crud->set_js('assets/grocery_crud/js/jquery_plugins/jquery.ui.datetime.js');
      $crud->set_js('assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js');
      $crud->set_js('assets/grocery_crud/js/jquery_plugins/config/jquery.datepicker.config.js');

      $crud->set_css('assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css');
      $crud->set_css('assets/grocery_crud/css/jquery_plugins/jquery.ui.datetime.css');
      $output = $crud->render();
    } elseif ($crud->getState() == 'read') {
      $output = $crud->render();
      // Get competitor's cards
      $competitorID = $crud->getStateInfo()->primary_key;
      $cardCrud = new grocery_CRUD();
      $cardCrud->set_theme('datatables');

      $cardCrud->set_table('card')
                ->where('competitorID', $competitorID);

      $cardCrud->set_subject('Card');
      $cardCrud->columns('ID', 'startDate', 'endDate', 'cardStateID');
      $cardCrud->fields('ID', 'startDate', 'endDate', 'cardStateID');

      $cardCrud->set_relation('cardStateID', 'cardState', 'state');

      $cardCrud->display_as('cardID', 'Card ID')
                ->display_as('startDate', 'Start Date')
                ->display_as('endDate', 'End Date')
                ->display_as('cardStateID', 'Card State');

      $cardCrud->unset_delete();
      $cardCrud->unset_add();
      $cardCrud->unset_edit();
      $cardCrud->unset_export();
      $cardCrud->unset_print();
      $cardCrud->add_action('Unauthorise/Lost/Stolen', '', '', 'fa-minus-circle', array($this, 'unauthorise_card_url'));

      $state_code = 1; // List state
      $cards = $cardCrud->render($state_code);

      // Add the output
      $output->cards = $cards;
      // Add access log styling
      $output->js_files = array_merge($cards->js_files, $output->js_files);
      $output->js_lib_files = array_merge($cards->js_lib_files, $output->js_lib_files);
      $output->js_config_files = array_merge($cards->js_config_files, $output->js_config_files);
      $output->css_files = array_merge($cards->css_files, $output->css_files);
    }

    if ($output === null) { $output = $crud->render(); }

    $this->competitors_output($output);
    $this->load->view('footer');
  }

  public function start_date_callback() {
    return "
      <input id='field-startDate' name='startDate' type='text' value='2017-07-16' maxlength='20' class='datepicker-input form-control'>";
  }

  public function check () {
    $fullName = $this->input->get('fullName');
    $teamName = $this->input->get('teamName');

    if (strlen($fullName) > 1) {
      // Only query the database when there are two words (firstname lastname)
      if (strpos($fullName, ' ') === false) {
        return exit(json_encode(array('count' => 0)));
      }

      $this->db->select('competitorTitle.title, competitor.fullName, team.teamName, competitor.role')
                ->from('competitor')
                ->like('fullName', $fullName)
                ->join('team', 'competitor.teamID = team.ID')
                ->join('competitorTitle', 'competitor.titleID = competitorTitle.ID');

      $result = $this->db->get();

      $data = array(
          'count' => $result->num_rows()
        , 'rows' => $result->result_array()
      );

      exit(json_encode($data));
    } else if (strlen($teamName) > 1) {
      $this->db->select('*')
                ->from('team')
                ->like('teamName', $teamName);

      $result = $this->db->get();

      $data = array(
          'count' => $result->num_rows()
        , 'rows' => $result->result_array()
      );

      exit(json_encode($data));
    } else {
      return exit(json_encode(array('count' => 0)));
    }
  }

  public function insert_competitor_callback($array, $primary_key) {
    if ($array['authorised'] === '0') {
      // Don't create a card for an unauthorised competitor
      return false;
    }

    $startDate = date('Y-m-d');
    if (isset($array['startDate']) && $array['startDate'] != '') {
      $startDate = $array['startDate'];
    }
    $new_card = array(
      "competitorID" => $primary_key,
      "startDate" => $startDate,
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

  public function venue() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    $crud->set_table('venue');

    $crud->set_subject('Venue');
    $crud->fields('venueName', 'stadium');

    $crud->required_fields('venueName', 'stadium');

    $crud->display_as('venueName', 'Venue Name')
          ->display_as('stadium', 'Stadium');

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
    $this->load->view('footer');
  }

  public function venues_output($output = null) {
    $this->load->view('venues_view.php', $output);
  }

  public function card() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');

    $crud->set_table('card');

    $crud->set_subject('Card');
    $crud->fields('ID', 'competitorID', 'startDate', 'endDate', 'cardStateID');
    $crud->add_fields('competitorID', 'startDate');

    $crud->set_relation('competitorID', 'competitor', 'fullName')
          ->set_relation('cardStateID', 'cardState', 'state');

    $crud->required_fields('competitorID');

    $crud->display_as('ID', 'Card ID')
          ->display_as('competitorID', 'Competitor Name')
          ->display_as('startDate', 'Start Date')
          ->display_as('endDate', 'End Date')
          ->display_as('cardStateID', 'Card State');

    $crud->unset_delete();
    $crud->unset_edit();
    $crud->unset_add();
    $crud->add_action('Unauthorise/Lost/Stolen', '', '', 'fa-minus-circle', array($this, 'unauthorise_card_url'));

    $output = $crud->render();

    if ($crud->getState() == 'read') {
      // Get access logs
      $cardID = $crud->getStateInfo()->primary_key;
      $accessCrud = new grocery_CRUD();
      $accessCrud->set_theme('datatables');

      $accessCrud->set_table('venueUsage');
      $accessCrud->where('cardID', $cardID);

      $accessCrud->set_subject('Access Logs');
      $accessCrud->columns('ID', 'venueID', 'dateAccessed', 'accessGranted');

      $accessCrud->field_type('accessGranted', 'dropdown', array("0"  => "NO", "1" => "YES"));
      $accessCrud->set_relation('venueID', 'venue', 'venueName');

      $accessCrud->display_as('ID', 'Access Log ID');
      $accessCrud->display_as('cardID', 'Card ID');
      $accessCrud->display_as('venueID', 'Venue');
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

      // Search by card for authorisation to access a venue for a match
      $venueCrud = new grocery_CRUD();
      $venueCrud->set_theme('datatables');

      $venueCrud->set_model('custom_query_model');

      $venueCrud->set_table('venue');
      $venueCrud->set_subject('Authorised Venues');
      $venueCrud->columns('ID', 'matchDate', 'venueName', 'stadium');


      $sql = "SELECT matchAccess.ID, matchAccess.matchDate, venue.venueName, venue.stadium"
           . " FROM matchAccess"
           . " LEFT JOIN venue"
           . "  ON (venue.ID = matchAccess.venueID)"
           . " LEFT JOIN team"
           . "  ON (team.ID = matchAccess.team1ID OR team.ID = matchAccess.team2ID)"
           . " LEFT JOIN competitor"
           . "  ON (competitor.teamID = team.ID)"
           . " LEFT JOIN card"
           . "  ON (card.competitorID = competitor.ID)"
           . " WHERE competitor.authorised = TRUE"
           . "  AND card.cardStateID = 1"
           . "  AND	card.ID = $cardID;";
      $venueCrud->basic_model->set_query_str($sql);

      $venueCrud->display_as('venueName', 'Venue')
                ->display_as('stadium', 'Stadium')
                ->display_as('matchDate', 'Match Date');

      $venueCrud->unset_operations();
      $venueCrud->unset_columns(array('ID'));

      $state_code = 1; // List state
      $venues = $venueCrud->render($state_code);

      // Add the output
      $output->venues = $venues;
      // Add access log styling
      $output->js_files = array_merge($venues->js_files, $output->js_files);
      $output->js_lib_files = array_merge($venues->js_lib_files, $output->js_lib_files);
      $output->js_config_files = array_merge($venues->js_config_files, $output->js_config_files);
      $output->css_files = array_merge($venues->css_files, $output->css_files);
    }

    $this->cards_output($output);
    $this->load->view('footer');
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

  public function end_competition() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);
    $this->load->view('end_competition_view');
    $this->load->view('footer');
  }

  public function terminate() {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    $cardUpdate = array(
        'endDate' => date('Y-m-d')
      , 'cardStateID' => 2
    );
    $competitorUpdate = array(
        'authorised' => '0'
    );
    $this->db->update('competitor', $competitorUpdate, 'authorised = 1');
    $this->db->update('card', $cardUpdate, 'cardStateID = 1');

    $data = array('terminate' => true);
    $this->load->view('end_competition_view', $data);
    $this->load->view('footer');
  }

  public function venue_access($cardID = null, $venueID = null, $date = null) {
    $userData = $this->ensure_logged_in();
    if (!$userData) {
      redirect('login', 'refresh');
    }
    $this->load->view('header', $userData);

    // This is a cheaty way to get all the needed CSS and JS files loaded
    $crud = new grocery_CRUD();
    $crud->set_theme('datatables');
    $crud->set_table('card');

    $state_code = 2;
    $output = $crud->render($state_code);

    // Manually query database for card numbers and venues
    $this->db->select('card.ID, competitor.fullName')
              ->from('card, competitor')
              ->where('competitor.ID = card.competitorID AND card.cardStateID = 1');
    $output->cards = $this->db->get()->result();

    $this->db->select('ID, venueName')
              ->from('venue');
    $output->venues = $this->db->get()->result();

    $result = null;

    if ($date) {
      $pieces = explode('-', $date);
      $isDateValid = checkdate($pieces[1], $pieces[2], $pieces[0]);
      $result = '2';
    }

    if ($cardID && $venueID && $date && $isDateValid) {

      // Test for venue access
      $sql = 'SELECT IF(EXISTS('
            . '  SELECT *'
           . '    FROM matchAccess, team, competitor, card, venue'
           . '  WHERE (matchAccess.team1ID = team.ID OR matchAccess.team2ID = team.ID)'
           . '    AND team.ID = competitor.teamID'
           . '    AND competitor.ID = card.competitorID'
           . '    AND card.ID = ' . $cardID
           . '    AND card.cardStateID = 1'
           . '    AND matchAccess.matchDate >= card.startDate'
           . '    AND matchAccess.venueID = venue.ID'
           . '    AND venue.ID = ' . $venueID
           . "    AND matchAccess.matchDate = '$date'"
           . '), 1, 0) AS result;';

      $result = $this->db->query($sql)
                          ->result()[0]
                          ->result;

      // Log request
      $data = array(
          'venueID' => $venueID
        , 'cardID' => $cardID
        , 'dateAccessed' => $date
        , 'accessGranted' => $result
      );
      $this->db->insert('venueUsage', $data);
    }

    $output->granted = $result;


    $this->load->view('venue_access_view', $output);
    $this->load->view('footer');
  }
}
