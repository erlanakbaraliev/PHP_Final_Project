<?php

class Storage {
    private $file;
    private $data;

    public function __construct($file) {
        $this->file = $file;
        if (file_exists($file)) {
            $this->data = json_decode(file_get_contents($file), true);
        } else {
            $this->data = [];
        }
    }

    public function getAllBooks() {
        return $this->data;
    }

    public function getBook($id) {
        return $this->data[$id] ?? null;
    }

    public function saveBook($id, $bookData) {
        $this->data[$id] = $bookData;
        $this->saveData();
    }

    public function deleteBook($id) {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);
            $this->saveData();
        }
    }

    public function saveReview($bookId, $review) {
        if (!isset($this->data[$bookId]['reviews'])) {
            $this->data[$bookId]['reviews'] = [];
        }
        $this->data[$bookId]['reviews'][] = $review;
        $this->saveData();
    }

    public function getReviews($bookId) {
        return $this->data[$bookId]['reviews'] ?? [];
    }

    private function saveData() {
        file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
    }
}
?>
