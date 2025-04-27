<?php

namespace App\Repositories\Interfaces;

interface ClientRepositoryInterface
{
    /**
     * Get all clients
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllClients();

    /**
     * Get client by ID with statistics
     * 
     * @param int $id
     * @return array
     */
    public function getClientWithStats($id);
}