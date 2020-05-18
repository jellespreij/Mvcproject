<?php
  class Post {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getPosts(){
      $this->db->query("SELECT * FROM Posts INNER JOIN Users ON Posts.userId = Users.usersId ORDER BY Posts.postsCreatedDate DESC;");

      $results = $this->db->resultset();

      return $results;
    }

    public function getPostById($id){
      $this->db->query("SELECT * FROM Posts WHERE postsId = :id");

      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }

    public function addPost($data){

      $this->db->query('INSERT INTO Posts (postsTitle, userId, postsBody) VALUES (:title, :userId, :body)');

      $this->db->bind(':title', $data['title']);
      $this->db->bind(':userId', $data['userId']);
      $this->db->bind(':body', $data['body']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deletePost($id){

      $this->db->query('DELETE FROM Posts WHERE postsId = :id');

      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
