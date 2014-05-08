<?php
class Showtime_model extends CI_Model {

	function get_showtimes(){
		$query = $this->db->query("select m.title, t.name, t.address, s.date, s.time, s.available
								from movie m, theater t, showtime s
								where m.id = s.movie_id and t.id=s.theater_id");
		return $query;	
	}  

	function getShowtime($movie_id, $venue_id){
		$query = $this->db->query("select distinct m.title, t.name, t.address, s.id, s.date, s.time, s.available
								from movie m, theater t, showtime s
								where m.id = s.movie_id and t.id=s.theater_id and m.id = $movie_id and t.id = $venue_id");
		return $query;
	}
	
	function getMovies($venue_id){
		$query = $this->db->query("select distinct m.title, s.date, s.time, s.available, m.id, s.time
								from movie m, theater t, showtime s
								where t.id=$venue_id and s.theater_id=$venue_id");
		return $query;
	}
	
	function getVenues($movie_id){
		$query = $this->db->query("select distinct t.name, t.address, s.date, s.time, s.available, t.id
								from movie m, theater t, showtime s
								where m.id=$movie_id and s.movie_id=$movie_id");
		return $query;
	}
	
	
	function populate() {
		$movies = $this->movie_model->get_movies();
		$theaters = $this->theater_model->get_theaters();
		
		//If it returns some results we continue
		if ($movies->num_rows() > 0 && $theaters->num_rows > 0){
			foreach ($movies->result() as $movie){
				foreach ($theaters->result() as $theater){
					for ($i=1; $i < 15; $i++) {
						for ($j=20; $j <= 22; $j+=2) {
							$this->db->query("insert into showtime (movie_id,theater_id,date,time,available)
									values ($movie->id,$theater->id,adddate(current_date(), interval $i day),'$j:00',3)");
						}
					}		
				}				
			}
		}		
	}

	function update($id) {
		//$this->db->where('id', $id);
		//return $this->db->update('showtime', array('available' => 'available'));
		return $this->db->query("update showtime set available = available - 1 where id=$id");
	}
	
	function delete() {
		$this->db->query("delete from showtime");
	}
}
?>