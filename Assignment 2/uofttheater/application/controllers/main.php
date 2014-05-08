<?php
class Main extends CI_Controller {
	function __construct() {
    	// Call the Controller constructor
    	parent::__construct();
    	$this->load->library('calendar');
    	session_start();
    }
    
    function index() {
    	global $date, $mv;
    	$data['title'] = "U of T Cinema";
    	$data['main'] = 'main/index';
    	$mv = "movie";
    	$data['mv'] = $mv;
    	$date = date('Y')."-".date('m')."-".(date('d')+1);
    	$data['date'] = $date;
    	$this->load->view('template', $data);
    }
	
    function showMovieOrVenue(){
    	$data['title'] = "U of T Cinema";
		if(isset($_REQUEST['date'])){
			$_SESSION['date'] = $_REQUEST['date'];
		}
    	$data['main'] = 'main/showtimes';
    	$data['date'] = $_REQUEST['date'];
    	$this->load->library('table');

		//Receive which radio is chosen in tamplate page.
    	if($_REQUEST['mv'] == "movie"){
    		$this->load->model('movie_model');
    		$movies = $this->movie_model->get_movies();
    
    		$result[] = array('Movie', 'Detail');
    		foreach($movies->result() as $movie){
    			$result[] = array($movie->title, anchor("main/showShowtimes/".$movie->id, "Show detail.."));
    		}
    		$data['mv'] = "movie";
    		$_SESSION['mv'] = "movie";
    	}else if($_REQUEST['mv'] == "venue"){
    		$this->load->model('theater_model');
    		$theaters = $this->theater_model->get_theaters();
    
    		$result[] = array('Theater', 'Address', 'Detail');
    		foreach($theaters->result() as $theater){
    			$result[] = array($theater->name, $theater->address, anchor("main/showShowtimes/".$theater->id, "Show detail.."));
    		}
    		$data['mv'] = "venue";
    		$_SESSION['mv'] = "venue";
    	}
    	 
    	$data['mvlist'] = $result;
    	$this->load->view('template', $data);
    }
    
    function showShowtimes($mvid) {
    	//First we load the library and the model
    	$data['title'] = "U of T Cinema";
    	$data['date'] = $_SESSION['date'];
    	$this->load->library('table');
    	$this->load->model('showtime_model');
    
    	//Then we call our model's get_showtimes function
    	//$showtimes = $this->showtime_model->get_showtimes();
    
    	//If it returns some results we continue
    
    	//Prepare the array that will contain the data
    	$table = array();
    	//$table[] = array('Movie','Theater','Address','Date','Time','Available');
    	//$data['date'] = $_SESSION['date'];
    	if($_SESSION['mv'] == "movie"){
    		//Get available theaters from the selected movie id.
    		$showtimes = $this->showtime_model->getVenues($mvid);
    		if ($showtimes->num_rows() > 0){
    			$table[] = array('Theater','Address','Time','Available','Ticketing');
    			foreach ($showtimes->result() as $row){
    				if($row->date == $_SESSION['date']){
    					$data['mvid'] = array($mvid, $row->id, $row->time);
    					$table[] = array($row->name,$row->address,$row->time,$row->available, anchor("main/ticketing/".$data, "Ticketing"));
    				}
    			}
    		}
    		$data['mv'] = "movie";
    		$_SESSION['mv'] = "movie";
    	}else if($_SESSION['mv'] == "venue"){
    		//Get available movies from the selected venue id.
    		$showtimes = $this->showtime_model->getMovies($mvid);
    		if ($showtimes->num_rows() > 0){
    		$table[] = array('Movie','Time','Available','Ticketing');
    			foreach ($showtimes->result() as $row){
    				if($row->date == $_SESSION['date']){
    					$data['mvid'] = array($row->id, $mvid, $row->time);
    					$table[] = array($row->title,$row->time,$row->available, anchor("main/ticketing/".$data, "Ticketing"));
    				}
    			}
    		}
    		$data['mv'] = "venue";
    		$_SESSION['mv'] = "venue";
    	}

    	//Next step is to place our created array into a new array variable, one that we are sending to the view.
    	$data['showtimes'] = $table;
    
    	//Now we are prepared to call the view, passing all the necessary variables inside the $data array
    	$data['main'] = 'main/showtimes';
    
    	$this->load->view('template', $data);
    }
    
    function ticketing($ids){
    	$this->load->model('showtime_model');
    	$showtimes = $this->showtime_model->getShowtime($_REQUEST['mvid'][0], $_REQUEST['mvid'][1]);
    	
    	//based on the user chosed movie, theater, date and time, get the id of showtime
    	$id = 1; // $id contains showtime id
    	
    	foreach ($showtimes->result() as $row){
    		if($row->id == $id){
    			$table = array(true, true, true);
    			if($row->available == 3){
    				$data['seats'] = $table;
    			}else if($row->available < 3){
    				$this->load->model('ticket_model');
    				$tickets = $this->ticket_model->getTickets($id);
					
					foreach($tickets->result() as $ticket){
						$table[$ticket->seat -1] = false;
    				}
    				
    				$data['seats'] = $table;
    			}else{
    				echo "error";
    			}
    			//"<input type='button' id='btn".$i."' onclick='window.open(\"ticketing\", \"\", \"left=100,top=100,width=465,height=600\");'>");
    		}
    	}
    	$data['showtimeid'] = $id;
    	$this->load->view('success', $data);
    }
    
	function ccn_check($ccn) {
    	if (preg_match("/\d{16}/", $ccn) == 0) {
    		$this->form_validation->set_message('ccn_check','Invalid Credit Card Number');
    		return false;
    	}
    	return true;
    }
    
    function ed_check($ed) {
    	if (preg_match("/\d{4}/", $ed) == 0) {
    		$this->form_validation->set_message('ed_check','Invalid Credit Card Expiration Date');
    		return false;
    	}
    	return true;
    }
    
    function register($id){
    	$this->load->library('form_validation');
    	$this->form_validation->set_rules('firstname','First Name : ','required|max_length[45]');
    	$this->form_validation->set_rules('lastname','Last Name : ','required|max_length[45]');
    	$this->form_validation->set_rules('ccn','Credit Card Number (16 digits) : ','required|callback_ccn_check');
    	$this->form_validation->set_rules('ed','Expiration Date : ','required|callback_ed_check');
    	
    	$data['showtimeid'] = $id;
    	if ($this->form_validation->run() == FALSE) {
    		$this->load->view('success', $data);
    	} else {
    		$this->ticketSummary($id);
    		//$this->load->view('print', $id);
    	}
    }
    
    function ticketSummary($id) {
    	$this->load->model('ticket_model');
    	//if ($this->ticket_model->getTickets($id)->num_rows() > 0){
    	//}
    	//if ($ticket->ticketing($id)){
    	//$this->load->library('table');
    	$firstname = $_REQUEST["firstname"];
    	$lastname = $_REQUEST["lastname"];
    	$ccn = $_REQUEST["ccn"];
    	$ed = $_REQUEST["ed"];
    	$showtime = $id;
    	$seat = $_REQUEST["seat"];
    	
    	$this->ticket_model->insert($this->ticket_model->getAllTickets()->num_rows() + 1, $firstname, $lastname, $ccn, $ed, $id, $seat);
    	$this->load->model('showtime_model');
    	$showtimes = $this->showtime_model->get_showtimes();
    	foreach ($showtimes->result() as $row){
    		if($row->id == $id){
    			$data['summary'] = array($row->title, $row->name, $row->date, $row->time, $seat, $firstname, $lastname, $ccn, $ed);
    			//"<input type='button' id='btn".$i."' onclick='window.open(\"ticketing\", \"\", \"left=100,top=100,width=465,height=600\");'>"); }
    			//}
    			//$this->load->view('print');
    			$this->showtime_model->update($id);
    			$this->load->view('summary',$data);
    		}
    	}
    }
    

    
    function populate() {
	    $this->load->model('movie_model');
	    $this->load->model('theater_model');
	    $this->load->model('showtime_model');
	     
	    $this->movie_model->populate();
	    $this->theater_model->populate();
	    $this->showtime_model->populate();
	     
	    //Then we redirect to the index page again
	    redirect('', 'refresh');
	     
    }
    
    function delete() {
	    $this->load->model('movie_model');
	    $this->load->model('theater_model');
	    $this->load->model('showtime_model');
	    $this->load->model('ticket_model');
    	
	    $this->movie_model->delete();
	    $this->theater_model->delete();
	    $this->showtime_model->delete();
	    $this->ticket_model->delete();
	     
    	//Then we redirect to the index page again
    	redirect('', 'refresh');
    
    }
    
    
    /* incomplete */ 
    function delete_tickets() { 
    	$this->load->model('ticket_model'); 
    	$this->load->model('showtime_model'); 
    	$this->ticket_model->delete(); 
    	$this->showtime_model->delete(); 
    	$this->showtime_model->populate(); 
    	
    	redirect('', 'refresh'); 
    } 
    
    /* complete */ 
    function show_tickets() { 
    	$con=mysqli_connect("bungle07.cs.toronto.edu","c3chomin","229878","c3chomin"); 
    	$result = mysqli_query($con,"SELECT * FROM ticket"); 
    	echo "<center><h1>Ticket Information</h1>"; 
    	echo "<table border='1'> 
    			<tr> 
    			<th>ticket</th> 
    			<th>First name</th> 
    			<th>Last name</th> 
    			<th>CreditCard Number</th> 
    			<th>CreditCard Expiration</th> 
    			<th>showtime_id</th> 
    			<th>seat</th> 
    			</tr>"; 
    	while($row = mysqli_fetch_array($result)) { 
    		echo "<tr>"; 
    		echo "<td>".$row['ticket']."</td>"; 
    		echo "<td>".$row['first']."</td>"; 
    		echo "<td>".$row['last']."</td>"; 
    		echo "<td>".$row['creditcardnumber']."</td>"; 
    		echo "<td>".$row['creditcardexpiration']."</td>"; 
    		echo "<td>".$row['showtime_id']."</td>"; 
    		echo "<td>".$row['seat']."</td>"; 
    		echo "</td>";
    	} 
    		
    	echo "</center></table>"; 
    	mysqli_close($con); 
    }
    
}
?>
