<?php

namespace App\Model;
use App\Core\Database;

class PageModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $title, string $slug, string $content, int $userId): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO pages (title, slug, content, user_id) 
            VALUES (:title, :slug, :content, :user_id)
        ");
    
        return $stmt->execute([
            "title"   => $title,
            "slug"    => $slug,
            "content" => $content,
            "user_id" => $userId
        ]);
    }

    public function update(int $id, string $title, string $slug, string $content): bool
    {
        $stmt = $this->db->prepare("UPDATE pages SET title = :title, slug = :slug, content = :content, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([
            'title'   => $title,
            'slug'    => $slug,
            'content' => $content,
            'id'      => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
