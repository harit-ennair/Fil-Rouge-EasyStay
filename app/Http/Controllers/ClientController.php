<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ClientRepositoryInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientRepository;

    /**
     * Create a new controller instance.
     *
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Display list of all clients
     */
    public function index()
    {
        $clients = $this->clientRepository->getAllClients();
        return view('all-clients', compact('clients'));
    }

    /**
     * Display client profile with statistics
     */
    public function show($id)
    {
        $data = $this->clientRepository->getClientWithStats($id);
        
        if (isset($data['error'])) {
            return redirect()->back()->with('error', $data['error']);
        }
        
        return view('client-profile', $data);
    }
}