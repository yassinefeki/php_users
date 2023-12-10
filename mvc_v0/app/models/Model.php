<?php
class Model {
protected $table;
protected $db;
public function __construct($db, $table) {
$this->db = $db;
$this->table = $table;
}
public function save($data) {
try {
if (isset($data['code'])) {
// Update an existing record
$columns = array_keys($data);
$sets = [];
$i=0;
foreach ($columns as $column) {
$sets[$i] = "$column = :$column";
$i++;
}
$setString = implode(', ', $sets);
$sql = "UPDATE $this->table SET $setString WHERE code = :code";
} else {
// Insert a new record
$columns = implode(', ', array_keys($data));
$placeholders = ':' . implode(', :', array_keys($data));
$sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
}
$stmt = $this->db->prepare($sql);
$stmt->execute($data);
} catch (PDOException $e) {
die("Error saving data: " . $e->getMessage());
}
}
public function searchProducts($searchValue) {
    $sql = "SELECT * FROM " . $this->table . " WHERE designation LIKE ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(["%$searchValue%"]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
public function find($code) {
try {
$sql = "SELECT * FROM $this->table WHERE code = :code";
$stmt = $this->db->prepare($sql);
$stmt->execute(['code' => $code]);
return $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
die("Error finding data: " . $e->getMessage());
}
}
public function findByEmail($email) {
    try {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Gérer l'erreur selon vos besoins
        die("Error finding user by email: " . $e->getMessage());
    }
}
public function findAll() {
    try {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ); // Ensure objects are being fetched
    } catch (PDOException $e) {
        die("Error finding data: " . $e->getMessage());
    }
}
    /*public function create($data) {
    try {
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute($data);
    return $this->db->lastInsertId();
    } catch (PDOException $e) {
    die("Error creating data: " . $e->getMessage());
    }
    }*/
    public function create($data) {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            $sql = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            die("Error creating data: " . $e->getMessage());
            // Handle database-related exceptions, e.g., connection errors, SQL errors
            // You can log the error or perform other actions as needed
            // Throw or return an error message or response if necessary
            // Example: throw new Exception("Error creating data: " . $e->getMessage());
        }
    }
    public function update($code, $data) {
    try {
    $data['code'] = $code;
    $columns = array_keys($data);
    $sets = [];
    $i = 0;
    foreach ($columns as $column) {
    $sets[$i] = "$column = :$column";
    $i++;
    }
    $setString = implode(', ', $sets);
    $sql = "UPDATE $this->table SET $setString WHERE code = :code";
    $stmt = $this->db->prepare($sql);
    $stmt->execute($data);
    } catch (PDOException $e) {
    die("Error updating data: " . $e->getMessage());
    }
    }
    public function delete($code) {
    try {
    $sql = "DELETE FROM $this->table WHERE code = :code";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['code' => $code]);
    } catch (PDOException $e) {
    die("Error deleting data: " . $e->getMessage());
    }
}
}