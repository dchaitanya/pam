<?php

class db {

    private $host = "127.0.0.1";
    private $username = "root";
    // private $password =  "ciR24i6gzj";
	private $password =  "root_password";
    private $link = null;

    // production database
    private $database = "pam";

    //development database
    // private $database = "pam_dev";

    public function connect() {
        $this->link = mysqli_connect($this->host, $this->username, $this->password);
        mysqli_select_db($this->link, $this->database);
    }

    public function close() {
        mysqli_close($this->link);
    }

    public function query($query) {
        // connect to database
        $this->connect();

        // fire sql query and fetch the result set
        $rs = mysqli_query($this->link, $query);

        // close database connection
        $this->close();

        return $rs;
    }

	public function insert_query($query) {
		// connect to database
		$this->connect();

        // fire sql query and fetch the result set
        $rs = mysqli_query($this->link, $query);

        // get the last insert id
		$insert_id = $this->link->insert_id;

		// close database connection
        $this->close();

        return $insert_id;
	}

	public function get_link() {
		// returns the active resource link identifier
		return $this->link;
	}

	public function get_insert_id() {
		// returns the last insert id executed by insert query
		return $this->link->insert_id;
	}
 }
