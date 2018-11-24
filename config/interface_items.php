<?php

	interface Items
	{
		public function readAll($from_record_num, $records_per_page);
		public function countAll();
		public function readOne($id);
		public function update($id);
		public function delete($id);
		public function create();
	}

?>