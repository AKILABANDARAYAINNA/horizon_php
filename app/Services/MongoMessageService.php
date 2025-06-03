<?php

namespace App\Services;

use MongoDB\Client;

class MongoMessageService
{
    protected $collection;

    public function __construct()
    {
        $uri = 'mongodb+srv://cb011951:t9UhCEzoCONXKwuc@cluster0.vncyamh.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0'; // ✅ Full correct URI
        $client = new Client($uri);

        $this->collection = $client
            ->selectDatabase('horizon_db')      // <-- Use your real DB name here
            ->selectCollection('contact_messages');
    }

    /**
     * Get all messages sorted by newest first.
     *
     * @return array
     */
    public function getAllMessages(): array
    {
        $cursor = $this->collection->find([], [
            'sort' => ['created_at' => -1]
        ]);

        return iterator_to_array($cursor);
    }
}
