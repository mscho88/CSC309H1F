<?php
class Ticket_model extends CI_Model{
	
	function insert($ticket, $first, $last, $creditcardnumber, $creditcardexpiration, $showtime, $seat){
		$data = array(
				'ticket' => $ticket,
				'first' => $first,
				'last' => $last,
				'creditcardnumber' => $creditcardnumber,
				'creditcardexpiration' => $creditcardexpiration,
				'showtime_id' => $showtime,
				'seat' => $seat);
		return $this->db->insert('ticket', $data);
	}
	
	function getAllTickets(){
		return $this->db->query("select * from ticket");
	}
	function getTickets($id){
		$query = $this->db->get_where('ticket', array('showtime_id'=>$id));
		//$query = $this->db->query("select t.ticket, t.first, t.last, t.creditcardnumber, t.creditcardexpiration, t.seat from ticket t where t.showtime_id=$id");
		return $query;
	}
	
	function delete(){
		$this->db->query("delete from ticket");
	}
}
?>