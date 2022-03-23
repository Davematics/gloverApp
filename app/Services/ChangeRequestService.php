<?php
namespace App\Services;

use App\Models\ChangeRequest;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ChangeRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Notification;

class ChangeRequestService
{

    public function getAllRequest()
    {

        return ChangeRequest::paginate(10);

    }

    public function viewSingleRequest($id)
    {

        return ChangeRequest::find($id);

    }

    public function createNewUserRequest(Request $request)
    {

        $user_details = $request->except('user_id');
        $changeRequest = ChangeRequest::create([
            'requester_id' => auth()->user()->id,
            'request_type' => 'create_new_user',
            'information_to_be_updated' => $user_details,
        ]);

        //creating notification
        $user = User::where('role_id', Role::SUPER_ADMIN)->first();
        $requestData = [
            'body' => 'You have a new change Request',
            'actionText' => 'Click here to view this request',
            'thanks' => 'Thank you',
            'url' => url("/api/v1/change-request/$changeRequest->id"),
        ];
        $this->sendMailNotification($user, $requestData);
        return $changeRequest;
    }

    public function updateUserRequest(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user_details = $request->only('first_name', 'last_name', 'email');
        $changeRequest = ChangeRequest::create([
            'requester_id' => auth()->user()->id,
            'request_type' => 'update_user',
            'information_to_be_updated' => $user_details,
            'user_id'=> $request->user_id
        ]);

        //creating notification
        $requestData = [
            'body' => 'You have a new change Request',
            'actionText' => 'Click here to view this request',
            'thanks' => 'Thank you',
            'url' => url("/api/v1/change-request/$changeRequest->id"),
        ];
        $user = User::where('role_id', Role::SUPER_ADMIN)->first();
        $this->sendMailNotification($user, $requestData);
        return $changeRequest;
    }

    public function requestToDeleteUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user_details = $request->only('first_name', 'last_name', 'email');
        $changeRequest = ChangeRequest::create([
            'requester_id' => auth()->user()->id,
            'request_type' => 'delete_user',
            'information_to_be_updated' => $user_details,
            'user_id'=> $request->user_id
        ]);

             //creating notification
        $requestData = [
            'body' => 'You have a new change Request',
            'actionText' => 'Click here to view this request',
            'thanks' => 'Thank you',
            'url' => url("/api/v1/change-request/$changeRequest->id"),
        ];
        $user = User::where('role_id', Role::SUPER_ADMIN)->first();
        $this->sendMailNotification($user, $requestData);

        return $changeRequest;

    }

    public function approveRequest($id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);

        if ($changeRequest->request_type == 'create_new_user') {
            $this->approveCreateUserRequest($changeRequest);
        }

        if ($changeRequest->request_type == 'update_user') {
            $this->approveUpdateUserRequest($changeRequest);
        }

        if ($changeRequest->request_type == 'delete_user') {
            $this->approveDeleteUserRequest($changeRequest);
        }

    }

    public function approveUpdateUserRequest($changeRequest)
    {

        $user = User::find($changeRequest->user_id);
        $user->first_name = $changeRequest->information_to_be_updated['first_name'];
        $user->last_name = $changeRequest->information_to_be_updated['last_name'];
        $user->email = $changeRequest->information_to_be_updated['email'];
        $user->save();
        $changeRequest->delete();

        return $user;
    }

    public function approveDeleteUserRequest($changeRequest)
    {

        $user = User::find($changeRequest->user_id);
        $user->delete();
        $changeRequest->delete();

        return $user;

    }

    public function approveCreateUserRequest($changeRequest)
    {

        $user = new User();
        $user->first_name = $changeRequest->information_to_be_updated['first_name'];
        $user->last_name = $changeRequest->information_to_be_updated['last_name'];
        $user->email = $changeRequest->information_to_be_updated['email'];
        $user->role_id = $changeRequest->information_to_be_updated['role_id'];
        $user->password = Hash::make($changeRequest->information_to_be_updated['password']);
        $user->save();
        $changeRequest->delete();

        return $user;

    }

    public function declineRequest($id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);
        $changeRequest->delete();

        return $changeRequest;
    }
    public function sendMailNotification(User $user, array $requestData)
    {
        Notification::send($user, new ChangeRequestNotification($requestData));
    }

}
