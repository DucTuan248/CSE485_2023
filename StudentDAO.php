<?php
class StudentDAO {
    private $students = array();
  
    public function create(Student $student) {
      array_push($this->students, $student);
    }
  
    public function read($id) {
      foreach ($this->students as $student) {
        if ($student->getId() == $id) {
          return $student;
        }
      }
      return null;
    }
  
    public function update(Student $student) {
      $index = $this->getIndexById($student->getId());
      if ($index !== null) {
        $this->students[$index] = $student;
      }
    }
  
    public function delete($id) {
      $index = $this->getIndexById($id);
      if ($index !== null) {
        array_splice($this->students, $index, 1);
      }
    }
  
    public function getAll() {
      return $this->students;
    }
  
    private function getIndexById($id) {
      foreach ($this->students as $index => $student) {
        if ($student->getId() == $id) {
          return $index;
        }
      }
      return null;
    }

    public function getById($id) {
      foreach ($this->students as $student) {
        if ($student->getId() == $id) {
          return $student;
        }
      }
      return null;
    }
    public function saveToFile($filename, Student $student) {
      $data = serialize($student);
      file_put_contents($filename, $data);
    }
    
  }

 
?>
