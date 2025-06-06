<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class MongoAgentService
{
    protected $collection;

    public function __construct()
    {
        // Connect to the MongoDB collection
        $client = new Client('mongodb+srv://cb011951:t9UhCEzoCONXKwuc@cluster0.vncyamh.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
        $this->collection = $client->horizon_real_estates->agents;
    }

    /**
     * Insert a new agent document into MongoDB.
     */
    public function insertAgent(array $data)
    {
        // Ensure created_at timestamp is included
        if (!isset($data['created_at'])) {
            $data['created_at'] = new UTCDateTime(now());
        }

        return $this->collection->insertOne($data);
    }

    /**
     * Get all agents (used in admin dashboard).
     */
    public function getAllAgents()
    {
        return $this->collection->find()->toArray();
    }

    /**
     * Get all agents with verification_status = 'pending'.
     */
    public function getPendingAgents()
    {
        return $this->collection->find(['verification_status' => 'pending'])->toArray();
    }

    /**
     * Get filtered agents for the public Agents page.
     */
    public function getFilteredAgents($businessArea = null, $minRating = null)
    {
        $filter = ['verification_status' => 'approved'];

        if ($businessArea) {
            $filter['business_area'] = new Regex($businessArea, 'i');
        }

        $agents = iterator_to_array($this->collection->find($filter));

        if ($minRating !== null) {
            $agents = array_filter($agents, function ($a) use ($minRating) {
                return isset($a['average_rating']) && $a['average_rating'] >= (float) $minRating;
            });
        }

        return $agents;
    }

    /**
     * Get agent document by linked user ID.
     */
    public function getAgentByUserId($userId)
    {
        return $this->collection->findOne(['user_id' => (int) $userId]);
    }

    /**
     * Update an agent’s verification status (used in admin approvals).
     */
    public function updateVerificationStatus($agentId, $status)
    {
        return $this->collection->updateOne(
            ['_id' => new ObjectId($agentId)],
            ['$set' => ['verification_status' => $status]]
        );
    }

    public function updateContactNumber($userId, $contactNumber)
    {
        return $this->collection->updateOne(
            ['user_id' => (int) $userId],
            ['$set' => ['contact_number' => $contactNumber]]
        );
    }

    public function submitRating(string $agentId, int $rating)
    {
        $agent = $this->collection->findOne(['_id' => new ObjectId($agentId)]);

        if (!$agent) return;

        $totalRatings = $agent['total_ratings'] ?? 0;
        $currentAverage = $agent['average_rating'] ?? 0;

        $newTotalRatings = $totalRatings + 1;
        $newAverage = (($currentAverage * $totalRatings) + $rating) / $newTotalRatings;

        $this->collection->updateOne(
            ['_id' => new ObjectId($agentId)],
            [
                '$set' => ['average_rating' => round($newAverage, 1)],
                '$inc' => ['total_ratings' => 1]
            ]
        );
    }

    /**
     * Find agent by email address (for duplication or auto-fill checks)
     */
    public function findAgentByEmail($email)
    {
        return $this->collection->findOne(['email' => $email]);
    }

    public function getApprovedAgents()
    {
        return $this->collection->find(['verification_status' => 'approved'])->toArray();
    }

}
