<?php
require_once __DIR__ . '/config/Database.php';
class ProductModel
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM product";
                
        $query = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            
            $row["is_available"] = (bool) $row["is_available"];
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO product (name, size, is_available)
                VALUES (:name, :size, :is_available)";
                
        $query = $this->conn->prepare($sql);
        
        $query->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $query->bindValue(":size", $data["size"] ?? 0, PDO::PARAM_INT);
        $query->bindValue(":is_available", (bool) ($data["is_available"] ?? true), PDO::PARAM_BOOL);
        
        $query->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM product
                WHERE id = :id";
                
        $query = $this->conn->prepare($sql);
        
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        
        $query->execute();
        
        $data = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($data !== false) {
            $data["is_available"] = (bool) $data["is_available"];
        }
        
        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE product
                SET name = :name, size = :size, is_available = :is_available
                WHERE id = :id";
                
        $query = $this->conn->prepare($sql);
        
        $query->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
        $query->bindValue(":size", $new["size"] ?? $current["size"], PDO::PARAM_INT);
        $query->bindValue(":is_available", $new["is_available"] ?? $current["is_available"], PDO::PARAM_BOOL);
        
        $query->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $query->execute();
        
        return $query->rowCount();
    }
    
    public function delete(string $id): int
    {
        $sql = "UPDATE product SET is_available = False WHERE id = :id";

        $query = $this->conn->prepare($sql);
        
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        
        $query->execute();
        
        return $query->rowCount();
    }
}