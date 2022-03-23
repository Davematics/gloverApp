<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChangeRequestRequest;
use App\Http\Requests\UpdateChangeRequestRequest;
use App\Models\ChangeRequest;
use App\Services\ChangeRequestService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChangeRequestController extends Controller
{
    protected $changeRequestService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(ChangeRequestService $changeRequestService)
    {
        $this->changeRequestService = $changeRequestService;
    }

    public function index()
    {
        $changeRequest = $this->changeRequestService->getAllRequest();

        return $this->response('success', $changeRequest);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreChangeRequestRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CreateChangeRequestRequest $request)
    {
        $changeRequest = $this->changeRequestService->createNewUserRequest($request);

        return $this->response('success', $changeRequest, true, Response::HTTP_CREATED);
    }

    public function approveRequest($id)
    {
        $changeRequest = $this->changeRequestService->approveRequest($id);

        return $this->response('success', $changeRequest, true, Response::HTTP_CREATED);
    }

    public function declineRequest($id)
    {
        $changeRequest = $this->changeRequestService->declineRequest($id);

        return $this->response('success', $changeRequest, true, Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $changeRequest = $this->changeRequestService->viewSingleRequest($id);

        if (!$changeRequest) {

            return $this->response('data not found', false, Response::HTTP_NOT_FOUND);
        }
        
        return $this->response('success', $changeRequest, true, Response::HTTP_FOUND);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(ChangeRequest $changeRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChangeRequestRequest  $request
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChangeRequestRequest $request, $changeRequest)
    {

    }

    public function requestToUpdateUser(UpdateChangeRequestRequest $request)
    {
        $changeRequest = $this->changeRequestService->updateUserRequest($request);

        return $this->response('request submitted successfully', $changeRequest, true, Response::HTTP_CREATED);
    }

    public function requestToDeleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $changeRequest = $this->changeRequestService->requestToDeleteUser($request);

        return $this->response('request submitted successfully', $changeRequest, true, Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChangeRequest $changeRequest)
    {
        //
    }
}
