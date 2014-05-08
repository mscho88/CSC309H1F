<?php

class Board extends CI_Controller {
     
    function __construct() {
    		// Call the Controller constructor
	    	parent::__construct();
	    	session_start();
    } 
          
    public function _remap($method, $params = array()) {
	    	// enforce access control to protected functions	
    		
    		if (!isset($_SESSION['user']))
   			redirect('account/loginForm', 'refresh'); //Then we redirect to the index page again
 	    	
	    	return call_user_func_array(array($this, $method), $params);
    }
    
    
    function index() {
		$user = $_SESSION['user'];
		$this->load->model('user_model');
    	$this->load->model('invite_model');
    	$this->load->model('match_model');
    	
    	$user = $this->user_model->get($user->login);

    	$invite = $this->invite_model->get($user->invite_id);
    	
    	$match = $this->match_model->get($user->match_id);
    	if ($user->user_status_id == User::WAITING) {
    		$invite = $this->invite_model->get($user->invite_id);
    		$otherUser = $this->user_model->getFromId($invite->user2_id);
    	}
    	else if ($user->user_status_id == User::PLAYING) {
    		if ($match->user1_id == $user->id)
    			$otherUser = $this->user_model->getFromId($match->user2_id);
    		else
    			$otherUser = $this->user_model->getFromId($match->user1_id);
    	}
    	
    	$data['user']=$user;
    	$data['otherUser']=$otherUser;
    	$data['isHost']=$match->user2_id;
    	
    	switch($user->user_status_id) {
    		case User::PLAYING:	
    			$data['status'] = 'playing';
    			break;
    		case User::WAITING:
    			$data['status'] = 'waiting';
    			break;
    	}
	    	
		$this->load->view('match/board',$data);
    }
	
    function isWin() {
		//$R represents a row #(1<=$R<=6) & $C represents a column #(0<=$C<=6) and ($R,$C) represents the occupied space most recently
		$R = $this->input->get_post('row');
		$C = $this->input->get_post('column');
		
		$this->load->model('match_model');
		$this->load->model('user_model');
		 
		$user = $_SESSION['user'];
		$user = $this->user_model->getExclusive($user->login);
		$match = $this->match_model->get($user->match_id);
		 
		$board = json_decode($match->board_state);
		
		//Case1: vertical 4  |
		//       total 1 case
		if (4<=$R<=6 && $board[$R][$C] == $board[$R-1][$C] == $board[$R-2][$C] == $board[$R-3][$C]){
			
		}
		
		//Case2: horizontal 4  -
		//       total 4 cases
		if (0<=$C<=3 && $board[$R][$C] == $board[$R][$C+1] == $board[$R][$C+2] == $board[$R][$C+3]){
		//win
		}else if (1<=$C<=4 && $board[$R][$C] == $board[$R][$C-1] == $board[$R][$C+1] == $board[$R][$C+2]){
		//win
		}else if (2<=$C<=5 && $board[$R][$C] == $board[$R][$C-1] == $board[$R][$C-2] == $board[$R][$C+1]){
		//win
		}else if (3<=$C<=6 && $board[$R][$C] == $board[$R][$C-1] == $board[$R][$C-2] == $board[$R][$C-3]){
		//win
		}
		
		//Case3: diagonal 4  /
		//       total 4 cases
		if (4<=$R<=6 && 3<=$C<=6 && $board[$R][$C] == $board[$R-1][$C-1] == $board[$R-2][$C-2] == $board[$R-3][$C-3]){
		//win
		}else if (3<=$R<=5 && 2<=$C<=5 && $board[$R][$C] == $board[$R-1][$C-1] == $board[$R-2][$C-2] == $board[$R+1][$C+1]){
		//win
		}else if (2<=$R<=4 && 1<=$C<=4 && $board[$R][$C] == $board[$R-1][$C-1] == $board[$R+1][$C+1] == $board[$R+2][$C+2]){
		//win
		}else if (1<=$R<=3 && 0<=$C<=3 && $board[$R][$C] == $board[$R+1][$C+1] == $board[$R+2][$C+2] == $board[$R+3][$C+3]){
		//win
		}
		
		//Case4: diagonal 4  \
		//       total 4 cases
		if (4<=$R<=6 && 0<=$C<=3 && $board[$R][$C] == $board[$R-1][$C+1] == $board[$R-2][$C+2] == $board[$R-3][$C+3]){
		//win
		}else if (3<=$R<=5 && 1<=$C<=4 && $board[$R][$C] == $board[$R-1][$C+1] == $board[$R-2][$C+2] == $board[$R+1][$C-1]){
		//win
		}else if (2<=$R<=4 && 2<=$C<=5 && $board[$R][$C] == $board[$R-1][$C+1] == $board[$R+1][$C-1] == $board[$R+2][$C-2]){
		//win
		}else if (1<=$R<=3 && 3<=$C<=6 && $board[$R][$C] == $board[$R+1][$C-1] == $board[$R+2][$C-2] == $board[$R+3][$C-3]){
		//win
		}
		//Add draw case
    }
    
    function postDisc() {
    	$this->load->model('match_model');
    	$this->load->model('user_model');
    	
    	$user = $_SESSION['user'];
    	$user = $this->user_model->getExclusive($user->login);
    	$match = $this->match_model->get($user->match_id);
    	
    	$board = json_decode($match->board_state);
    	
    	if($board->{'board'}->{'6'}[$this->input->get_post('selectedColumn')] != 0){
    		//echo "error";
    		return "error";
    	}
    	
    	for($i = 5; $i >= 1; $i--){
    		if($board->{'board'}->{$i}[$this->input->get_post('selectedColumn')] != 0){
    			$board->{'board'}->{$i+1}[$this->input->get_post('selectedColumn')] = "$user->id";
    			$board->{'lastMove'}[1] = strval($i+1);
    			$board->{'lastMove'}[2] = $this->input->get_post('selectedColumn');
    			$this->match_model->updateBoardState($match->id, json_encode($board));
    			echo $i+1;
    			return;
    		}
    	}
    	$board->{'lastMove'}[1] = strval($i+1);
    	$board->{'lastMove'}[2] = $this->input->get_post('selectedColumn');
    	$board->{'board'}->{$i+1}[$this->input->get_post('selectedColumn')] = "$user->id";
    	$this->match_model->updateBoardState($match->id, json_encode($board));
    	echo $i+1;
    	
    	return;
    }
    
    function getDisc() {
    	$this->load->model('match_model');
    	$this->load->model('user_model');
    	 
    	$user = $_SESSION['user'];
    	$user = $this->user_model->getExclusive($user->login);
    	$match = $this->match_model->get($user->match_id);
    	 
    	$board = json_decode($match->board_state);
    	
    	if($user->id != $board->{'lastMove'}[0]){
	    	if($board->{'lastMove'}[1] != "-1" && $board->{'lastMove'}[2] != "-1"){
	    		$board->{'lastMove'}[2]++;
	    		echo json_encode($board->{'lastMove'});
	    		$board->{'lastMove'}[0] = strval($match->user1_id == $board->{'lastMove'}[0] ? $match->user2_id : $match->user1_id);
	    		$board->{'lastMove'}[1] = strval(-1);
	    		$board->{'lastMove'}[2] = strval(-1);
	    		$this->match_model->updateBoardState($match->id, json_encode($board));
	    	}
    	}
    	return;
    }
    
 	function postMsg() {
 		$this->load->library('form_validation');
 		$this->form_validation->set_rules('msg', 'Message', 'required');
 		
 		if ($this->form_validation->run() == TRUE) {
 			$this->load->model('user_model');
 			$this->load->model('match_model');

 			$user = $_SESSION['user'];
 			 
 			$user = $this->user_model->getExclusive($user->login);
 			if ($user->user_status_id != User::PLAYING) {	
				$errormsg="Not in PLAYING state";
 				goto error;
 			}
 			
 			$match = $this->match_model->get($user->match_id);			
 			
 			$msg = $this->input->post('msg');
 			
 			if ($match->user1_id == $user->id)  {
 				$msg = $match->u1_msg == ''? $msg :  $match->u1_msg . "\n" . $msg;
 				$this->match_model->updateMsgU1($match->id, $msg);
 			}
 			else {
 				$msg = $match->u2_msg == ''? $msg :  $match->u2_msg . "\n" . $msg;
 				$this->match_model->updateMsgU2($match->id, $msg);
 			}
 				
 			echo json_encode(array('status'=>'success'));
 			 
 			return;
 		}
		
 		$errormsg="Missing argument";
 		
		error:
			echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 
	function getMsg() {
 		$this->load->model('user_model');
 		$this->load->model('match_model');
 			
 		$user = $_SESSION['user'];
 		 
 		$user = $this->user_model->get($user->login);
 		if ($user->user_status_id != User::PLAYING) {	
 			$errormsg="Not in PLAYING state";
 			goto error;
 		}
 		// start transactional mode  
 		$this->db->trans_begin();
 			
 		$match = $this->match_model->getExclusive($user->match_id);			
 			
 		if ($match->user1_id == $user->id) {
			$msg = $match->u2_msg;
 			$this->match_model->updateMsgU2($match->id,"");
 		}
 		else {
 			$msg = $match->u1_msg;
 			$this->match_model->updateMsgU1($match->id,"");
 		}

 		if ($this->db->trans_status() === FALSE) {
 			$errormsg = "Transaction error";
 			goto transactionerror;
 		}
 		
 		// if all went well commit changes
 		$this->db->trans_commit();
 		
 		echo json_encode(array('status'=>'success','message'=>$msg));
		return;
		
		transactionerror:
		$this->db->trans_rollback();
		
		error:
		echo json_encode(array('status'=>'failure','message'=>$errormsg));
 	}
 	
 }

