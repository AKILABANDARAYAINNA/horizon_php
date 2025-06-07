<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

class MongoPropertyService
{
    protected $collection;

    public function __construct()
    {
        $client = new Client('mongodb+srv://cb011951:t9UhCEzoCONXKwuc@cluster0.vncyamh.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
        $this->collection = $client->horizon_real_estates->properties;
    }

    /**
     * Get up to 6 featured and approved properties.
     */
    public function getFeaturedApprovedProperties()
    {
        $filter = [
            'is_featured' => 'yes',
            'status' => 'approved'
        ];

        return $this->collection->find($filter)->toArray();
    }

    /**
     * Get all properties marked for sale and approved.
     */
    public function getPropertiesForSale()
    {
        return iterator_to_array(
            $this->collection->find([
                'for_sale_or_rent' => 'sale',
                'status' => 'approved',
            ])
        );
    }

    /**
     * Get all properties marked for rent and approved.
     */
    public function getPropertiesForRent()
    {
        return iterator_to_array(
            $this->collection->find([
                'for_sale_or_rent' => 'rent',
                'status' => 'approved',
            ])
        );
    }

    public function getPropertiesByUserId($userId)
    {
        return iterator_to_array(
            $this->collection->find([
                'user_id' => (int) $userId
            ])
        );
    }

    public function getAdsByStatus(string $status): array
    {
        $cursor = $this->collection->find(['status' => $status]);

        $results = [];
        foreach ($cursor as $document) {
            $results[] = [
                '_id' => (string) $document['_id'] ?? null,  // ✅ include this
                'property_id' => $document['_id'] ?? null,
                'title' => $document['title'] ?? '',
                'price' => $document['price'] ?? 0,
                'for_sale_or_rent' => $document['for_sale_or_rent'] ?? '',
                'property_type' => $document['property_type'] ?? '',
                'is_featured' => $document['is_featured'] ?? 'no', // ✅ Fix here
                'payment_status' => $document['payment_status'] ?? 'paid', // ✅ And here
            ];
        }

        return $results;
    }

    public function createProperty(array $data)
    {
        if (!isset($data['property_id'])) {
            $data['property_id'] = time(); // or Str::uuid()->toString()
        }

        return $this->collection->insertOne($data);
    }

    public function updatePaymentStatus($propertyId, $status)
    {
        return $this->collection->updateOne(
            ['_id' => new ObjectId($propertyId)],
            ['$set' => ['payment_status' => $status]]
        );
    }


    public function updateAdStatus($propertyId, $status)
    {
        return $this->collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($propertyId)],
            ['$set' => ['status' => $status]]
        );
    }

    public function updateProperty($id, array $data)
    {
        return $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $data]
        );
    }


    public function deleteProperty($id)
    {
        return $this->collection->deleteOne([
            '_id' => new \MongoDB\BSON\ObjectId($id)
        ]);
    }

    public function getPropertyFlexible($id)
    {
        // Try ObjectId first (for admin edit/delete)
        try {
            return $this->collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        } catch (\Exception $e) {
            // If fails, try custom ID fallback (for agent/client/view)
            return $this->collection->findOne(['property_id' => (string) $id]);
        }
    }

    public function searchProperties(array $query): array
    {
        $collection = $this->collection; // Use the property from __construct

        $mongoQuery = [];

        if (!empty($query['location'])) {
            $mongoQuery['location'] = ['$regex' => $query['location'], '$options' => 'i'];
        }

        if (!empty($query['budget'])) {
            $mongoQuery['price'] = ['$lte' => (int)$query['price']];
        }

        if (!empty($query['property_type'])) {
            $mongoQuery['property_type'] = $query['property_type'];
        }

        if (!empty($query['for_sale_or_rent'])) {
            $mongoQuery['for_sale_or_rent'] = $query['for_sale_or_rent'];
        }

        $mongoQuery['status'] = 'approved';

        $cursor = $collection->find($mongoQuery);

        return iterator_to_array($cursor);
    }
    public function filterProperties(array $criteria)
    {
        $query = [];

        if (!empty($criteria['property_type'])) {
            $query['property_type'] = $criteria['property_type'];
        }

        if (!empty($criteria['for_sale_or_rent'])) {
            $query['for_sale_or_rent'] = $criteria['for_sale_or_rent'];
        }

        if (!empty($criteria['location'])) {
            $query['location'] = new \MongoDB\BSON\Regex($criteria['location'], 'i');
        }

        if (!empty($criteria['price'])) {
            $query['price'] = $criteria['price'];
        }

        return $this->collection->find($query)->toArray();
    }
    public function getApprovedProperties()
    {
        return $this->collection
            ->find(['status' => 'approved'])
            ->toArray();
    }

}
