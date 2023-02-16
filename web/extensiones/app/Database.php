<?php

	class Database {
		private $db;

		public function __construct () {
			$this->db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'],$_ENV['DB_PORT']);
		}

		public function query($sql) {
			return $this->db->query($sql);
		}	

		public function fetchData($result) {
			if (!$result) return [];
			if ($result->num_rows === 0) return [];

			if ($result->num_rows === 1) {
				return [$result->fetch_array()];
			}

			$data = [];

			while ($row = $result->fetch_array()) {
				array_push($data, $row);
			}

			return $data;
		}

		public function getConnectionInstance() {
			return $this->db;
		}

		public function closeConnection() {
			$this->db->close();
		}

	}